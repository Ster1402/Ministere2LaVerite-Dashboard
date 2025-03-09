<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteDonationRequest;
use App\Models\Donation;
use App\Models\PaymentMethod;
use App\Services\FreeMoPayService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DonationController extends Controller
{
    protected $freeMoPayService;

    public function __construct(FreeMoPayService $freeMoPayService)
    {
        $this->freeMoPayService = $freeMoPayService;
    }

    public function index()
    {
        $donations = Donation::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('donations.index', compact('donations'));
    }

    /**
     * Show the donation form
     */
    public function showDonationForm()
    {
        // Get available payment methods
        $paymentMethods = PaymentMethod::where('is_active', true)->get();

        // Check if the authenticated user has any pending donations
        $pendingDonations = null;
        if (Auth::check()) {
            $pendingResult = $this->freeMoPayService->getUserPendingDonations(Auth::id());
            if ($pendingResult['success'] && !empty($pendingResult['pending_donations'])) {
                $pendingDonations = $pendingResult['pending_donations'];
            }
        }

        return view('donations.form', [
            'paymentMethods' => $paymentMethods,
            'pendingDonations' => $pendingDonations
        ]);
    }

    /**
     * Process a new donation submission
     */
    public function processDonation(Request $request)
    {
        // Validate the donation request
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:100',
            'donor_name' => 'required_unless:is_anonymous,1|string|max:100',
            'donor_email' => 'required_unless:is_anonymous,1|email|max:100',
            'donor_phone' => 'required|string|max:20',
            'message' => 'nullable|string|max:500',
            'is_anonymous' => 'nullable|boolean',
            'payment_method_id' => 'required|exists:payment_methods,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Prepare donation data
        $donationData = [
            'amount' => $request->amount,
            'donor_name' => $request->donor_name,
            'donor_email' => $request->donor_email,
            'donor_phone' => $request->donor_phone,
            'message' => $request->message,
            'is_anonymous' => (bool) $request->is_anonymous,
            'payment_method_id' => $request->payment_method_id,
            'user_id' => Auth::id(), // Will be null for guests
        ];

        // Create a pending donation
        $donation = $this->freeMoPayService->createPendingDonation($donationData);

        if (!$donation) {
            Log::error('Failed to create donation', ['data' => $donationData]);
            return redirect()->back()
                ->with('error', 'Unable to process your donation at this time. Please try again later.')
                ->withInput();
        }

        // Attempt to initiate payment
        $paymentResult = $this->freeMoPayService->initiatePayment($donation);

        if (!$paymentResult['success']) {
            // Payment initiation failed, but we keep the pending donation
            return redirect()->back()
                ->with('error', 'Payment initiation failed: ' . $paymentResult['message'])
                ->with('donation_id', $donation->id)
                ->withInput();
        }

        // Redirect to payment confirmation page
        return redirect()->route('donations.confirmation', ['id' => $donation->id])
            ->with('payment_reference', $paymentResult['reference']);
    }

    /**
     * Show donation confirmation/status page
     */
    public function showConfirmation($id)
    {
        $donationInfo = $this->freeMoPayService->getDonationInfo($id);

        if (!$donationInfo['success']) {
            return redirect()->route('donations.form')
                ->with('error', 'Donation information not found.');
        }

        $donation = $donationInfo['donation'];

        // Check if this donation belongs to the current user (if authenticated)
        if (Auth::check() && $donation['user_id'] && $donation['user_id'] != Auth::id()) {
            return redirect()->route('donations.form')
                ->with('error', 'You do not have permission to view this donation.');
        }

        return view('donations.confirmation', [
            'donation' => $donation,
            'paymentReference' => session('payment_reference')
        ]);
    }

    /**
     * FreeMoPay callback handler
     */
    public function handleCallback(Request $request)
    {
        // Log the incoming callback data
        Log::info('Payment callback received', [
            'data' => $request->all()
        ]);

        $result = $this->freeMoPayService->processCallback($request->all());

        if ($result) {
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Check the status of a donation (AJAX endpoint)
     */
    public function checkDonationStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'donation_id' => 'required|exists:donations,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid donation ID'
            ], 400);
        }

        $donation = Donation::find($request->donation_id);

        // Security check - only allow users to check their own donations or donations without a user_id
        if (Auth::check() && $donation->user_id && $donation->user_id != Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to check this donation'
            ], 403);
        }

        // Get fresh donation info (this will trigger status update if needed)
        $donationInfo = $this->freeMoPayService->getDonationInfo($request->donation_id);

        if (!$donationInfo['success']) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking donation status'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'donation' => $donationInfo['donation']
        ]);
    }

    /**
     * Show the user's donation history
     */
    public function showMyDonations()
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('message', 'Please login to view your donation history');
        }

        $donations = Donation::where('user_id', Auth::id())
            ->orderBy('donation_date', 'desc')
            ->paginate(10);

        return view('donations.my-donations', [
            'donations' => $donations
        ]);
    }

    /**
     * Cancel a pending donation (AJAX endpoint)
     */
    public function cancelDonation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'donation_id' => 'required|exists:donations,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid donation ID'
            ], 400);
        }

        $donation = Donation::find($request->donation_id);

        // Only allow cancellation of pending donations by the donation owner
        if ($donation->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending donations can be canceled'
            ], 400);
        }

        // Security check - only allow users to cancel their own donations
        if (Auth::check() && $donation->user_id && $donation->user_id != Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to cancel this donation'
            ], 403);
        }

        try {
            // If there's a transaction ID, we should notify the payment provider
            if ($donation->transaction_id) {
                // This would ideally cancel the payment with the provider
                // For now, we just log it
                Log::info('Payment cancellation requested', [
                    'donation_id' => $donation->id,
                    'transaction_id' => $donation->transaction_id
                ]);
            }

            // Update donation status
            $donation->update(['status' => 'failed']);

            return response()->json([
                'success' => true,
                'message' => 'Donation canceled successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error canceling donation', [
                'donation_id' => $donation->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error canceling donation'
            ], 500);
        }
    }

    /**
     * Delete a specific donation.
     *
     * @param DeleteDonationRequest $request
     * @param Donation $donation
     * @return RedirectResponse
     */
    public function destroy(DeleteDonationRequest $request, Donation $donation): RedirectResponse
    {
        // Soft delete the donation
        $donation->delete();

        // Flash success message
        session()->flash('success', 'Le don a été supprimé avec succès.');

        // Redirect back to donations index
        return redirect()->route('donations.index');
    }
}
