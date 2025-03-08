<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteDonationRequest;
use App\Models\Donation;
use App\Models\Transaction;
use App\Models\PaymentMethod;
use App\Services\Commons\PhoneNumberFormatter;
use App\Services\FreeMoPayService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DonationController extends Controller
{
    protected $freeMoPayService;

    public function __construct(FreeMoPayService $freeMoPayService)
    {
        $this->freeMoPayService = $freeMoPayService;
    }

    /**
     * Display a listing of donations
     */
    public function index()
    {
        $donations = Donation::with(['user', 'paymentMethod'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('donations.index', compact('donations'));
    }

    /**
     * Show donation creation form
     */
    public function create()
    {
        $paymentMethods = PaymentMethod::where('is_active', true)
            ->orderBy('order')
            ->get();

        return view('donations.create', compact('paymentMethods'));
    }

    /**
     * Store a new donation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:100',
            'currency' => 'required|string',
            'comment' => 'nullable|string',
            'name' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'required|string',
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        // Validate the phone number against the selected payment method
        $paymentMethod = PaymentMethod::findOrFail($validated['payment_method_id']);
        $phone = preg_replace('/\s+/', '', PhoneNumberFormatter::reformat($validated['phone']));

        if ($paymentMethod->phone_regex && !preg_match('/' . $paymentMethod->phone_regex . '/', $phone)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['phone' => 'Le numéro de téléphone ne correspond pas au service de paiement sélectionné.']);
        }

        // Generate a reference number
        $referenceNumber = 'DON-' . strtoupper(Str::random(6));

        // Create the donation record
        $donation = Donation::create([
            'amount' => $validated['amount'],
            'currency' => $validated['currency'],
            'donor_name' => $validated['name'] ?? 'Anonyme',
            'donor_email' => $validated['email'] ?? null,
            'donor_phone' => $phone,
            'donation_date' => now(),
            'message' => $validated['comment'] ?? null,
            'is_anonymous' => empty($validated['name']),
            'user_id' => auth()->id(),
            'payment_method_id' => $validated['payment_method_id'],
            'status' => 'pending',
            'reference' => $referenceNumber
        ]);

        // Process payment with FreeMoPay
        $paymentResult = $this->freeMoPayService->initiatePayment($donation);

        if (!$paymentResult['success']) {
            // If payment initiation failed, update donation status and show error
            $donation->update([
                'status' => 'failed',
                'payment_data' => ['error' => $paymentResult['message']]
            ]);

            return redirect()->back()
                ->with('error', 'Échec de l\'initialisation du paiement: ' . $paymentResult['message']);
        }

        // If payment was initiated successfully
        if ($paymentResult['status'] === 'SUCCESS') {
            // Create transaction record
            Transaction::create([
                'amount' => $validated['amount'],
                'currency' => $validated['currency'],
                'comment' => $validated['comment'] ?? 'Don en ligne',
                'user_id' => auth()->id(),
                'donor_name' => $validated['name'] ?? 'Anonyme',
                'donor_email' => $validated['email'] ?? null,
                'donor_phone' => $phone,
                'payment_method_id' => $validated['payment_method_id'],
                'is_processed' => true,
                'transaction_reference' => $paymentResult['reference']
            ]);

            // Update donation status
            $donation->update([
                'status' => 'completed',
                'transaction_id' => $paymentResult['reference']
            ]);

            return redirect()->route('donations.confirm', $donation)
                ->with('success', 'Votre don a été traité avec succès! Merci pour votre générosité.');
        }

        // If payment is pending (requires user action)
        return redirect()->route('donations.confirm', $donation)
            ->with('info', 'Votre demande de don a été initiée. Veuillez suivre les instructions sur votre téléphone pour confirmer le paiement.');
    }

    /**
     * Show donation confirmation page
     */
    public function confirm(Donation $donation)
    {
        // If the donation is still pending, check its status
        if ($donation->status === 'pending' && $donation->transaction_id) {
            $statusResult = $this->freeMoPayService->checkPaymentStatus($donation->transaction_id);

            if ($statusResult['success']) {
                // Update donation status based on payment status
                if ($statusResult['status'] === 'SUCCESS') {
                    $donation->update(['status' => 'completed']);
                } elseif (in_array($statusResult['status'], ['FAILED', 'CANCELED'])) {
                    $donation->update([
                        'status' => 'failed',
                        'payment_data' => array_merge(
                            $donation->payment_data ?? [],
                            ['reason' => $statusResult['reason'] ?? 'Unknown failure reason']
                        )
                    ]);
                }
            }
        }

        return view('donations.confirm', compact('donation'));
    }

    /**
     * Callback URL for payment gateway responses
     */
    public function callback(Request $request)
    {
        // Log the callback data
        \Log::info('Payment callback received', $request->all());

        $reference = $request->input('reference');
        $status = $request->input('status');
        $reason = $request->input('message');

        ddd($request->all());

        if (!$reference) {
            return response()->json(['status' => 'error', 'message' => 'Missing required parameters'], 400);
        }

        if ($status === 'SUCCESS') {
            $success = $this->freeMoPayService->processSuccessfulPayment($reference, $externalId, $amount);

            if ($success) {
                return response()->json(['status' => 'success', 'message' => 'Payment processed successfully']);
            }
        } elseif (in_array($status, ['FAILED', 'CANCELED'])) {
            $success = $this->freeMoPayService->processFailedPayment($reference, $externalId, $reason);

            if ($success) {
                return response()->json(['status' => 'success', 'message' => 'Failed payment processed']);
            }
        }

        return response()->json(['status' => 'error', 'message' => 'Payment processing failed'], 400);
    }

    /**
     * Delete a specific donation.
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
