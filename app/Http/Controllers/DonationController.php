<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteDonationRequest;
use App\Models\Donation;
use App\Models\PaymentMethod;
use App\Services\FreeMoPayService;
use App\Services\LoggingService; // Ajout du service de journalisation
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
        // Journaliser la consultation de la liste des dons
        LoggingService::info(
            'Consultation de la liste des dons',
            [
                'action' => 'list_donations',
                'user_agent' => request()->userAgent()
            ],
            'donations'
        );

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
        // Journaliser l'accès au formulaire de don
        LoggingService::info(
            'Accès au formulaire de don',
            [
                'action' => 'view_donation_form',
                'user_agent' => request()->userAgent()
            ],
            'donations'
        );

        // Get available payment methods
        $paymentMethods = PaymentMethod::where('is_active', true)->get();

        // Check if the authenticated user has any pending donations
        $pendingDonations = null;
        if (Auth::check()) {
            $pendingResult = $this->freeMoPayService->getUserPendingDonations(Auth::id());

            // Journaliser si l'utilisateur a des dons en attente
            if ($pendingResult['success'] && !empty($pendingResult['pending_donations'])) {
                LoggingService::info(
                    'Utilisateur avec des dons en attente',
                    [
                        'action' => 'pending_donations_found',
                        'user_id' => Auth::id(),
                        'count' => count($pendingResult['pending_donations'])
                    ],
                    'donations'
                );

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
        // Journaliser la tentative de création d'un don
        LoggingService::info(
            'Tentative de création d\'un don',
            [
                'action' => 'donation_attempt',
                'amount' => $request->amount,
                'payment_method_id' => $request->payment_method_id,
                'is_anonymous' => (bool) $request->is_anonymous,
                'has_message' => !empty($request->message)
            ],
            'donations'
        );

        // Validate the donation request
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:25',
            'donor_name' => 'required_unless:is_anonymous,1|string|max:100',
            'donor_email' => 'required_unless:is_anonymous,1|email|max:100',
            'donor_phone' => ['required', 'string', 'max:20', 'regex:' . '/' . PaymentMethod::find($request->payment_method_id)->phone_regex . '/'],
            'message' => 'nullable|string|max:500',
            'is_anonymous' => 'nullable|boolean',
            'payment_method_id' => 'required|exists:payment_methods,id'
        ]);

        if ($validator->fails()) {
            // Journaliser l'échec de validation
            LoggingService::warning(
                'Échec de validation pour un don',
                [
                    'action' => 'donation_validation_failed',
                    'errors' => $validator->errors()->toArray(),
                    'inputs' => $request->except(['donor_name', 'donor_email', 'donor_phone', 'message']) // Exclure les données sensibles
                ],
                'donations'
            );

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
            // Journaliser l'échec de création du don
            LoggingService::error(
                'Échec de création d\'un don',
                [
                    'action' => 'donation_creation_failed',
                    'amount' => $request->amount,
                    'payment_method_id' => $request->payment_method_id,
                    'user_id' => Auth::id()
                ],
                'donations'
            );

            return redirect()->back()
                ->with('error', 'Unable to process your donation at this time. Please try again later.')
                ->withInput();
        }

        // Journaliser la création réussie du don
        LoggingService::info(
            'Don créé avec succès',
            [
                'action' => 'donation_created',
                'donation_id' => $donation->id,
                'amount' => $donation->amount,
                'payment_method_id' => $donation->payment_method_id,
                'is_anonymous' => $donation->is_anonymous,
                'user_id' => $donation->user_id
            ],
            'donations'
        );

        // Attempt to initiate payment
        $paymentResult = $this->freeMoPayService->initiatePayment($donation);

        if (!$paymentResult['success']) {
            // Journaliser l'échec d'initiation du paiement
            LoggingService::error(
                'Échec d\'initiation du paiement',
                [
                    'action' => 'payment_initiation_failed',
                    'donation_id' => $donation->id,
                    'error_message' => $paymentResult['message'],
                    'payment_method_id' => $donation->payment_method_id
                ],
                'payments'
            );

            return redirect()->back()
                ->with('error', 'Payment initiation failed: ' . $paymentResult['message'])
                ->with('donation_id', $donation->id)
                ->withInput();
        }

        // Journaliser l'initiation réussie du paiement
        LoggingService::info(
            'Paiement initié avec succès',
            [
                'action' => 'payment_initiated',
                'donation_id' => $donation->id,
                'transaction_id' => $paymentResult['reference'],
                'amount' => $donation->amount,
                'payment_method_id' => $donation->payment_method_id
            ],
            'payments'
        );

        // Redirect to payment confirmation page
        return redirect()->route('donations.confirmation', ['id' => $donation->id])
            ->with('payment_reference', $paymentResult['reference']);
    }

    /**
     * Show donation confirmation/status page
     */
    public function showConfirmation($id)
    {
        // Journaliser l'accès à la page de confirmation
        LoggingService::info(
            'Accès à la page de confirmation de don',
            [
                'action' => 'view_donation_confirmation',
                'donation_id' => $id
            ],
            'donations'
        );

        $donationInfo = $this->freeMoPayService->getDonationInfo($id);

        if (!$donationInfo['success']) {
            // Journaliser l'information de don non trouvée
            LoggingService::warning(
                'Information de don non trouvée',
                [
                    'action' => 'donation_info_not_found',
                    'donation_id' => $id
                ],
                'donations'
            );

            return redirect()->route('donations.form')
                ->with('error', 'Donation information not found.');
        }

        $donation = $donationInfo['donation'];

        // Check if this donation belongs to the current user (if authenticated)
        if (Auth::check() && $donation['user_id'] && $donation['user_id'] != Auth::id()) {
            // Journaliser la tentative d'accès non autorisé
            LoggingService::warning(
                'Tentative d\'accès non autorisé à un don',
                [
                    'action' => 'unauthorized_donation_access',
                    'donation_id' => $id,
                    'attempted_user_id' => Auth::id(),
                    'donation_owner_id' => $donation['user_id']
                ],
                'security'
            );

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
        // Journaliser la réception du callback de paiement
        $callbackData = $request->all();
        LoggingService::info(
            'Callback de paiement reçu',
            [
                'action' => 'payment_callback_received',
                'reference' => $callbackData['reference'] ?? 'unknown',
                'status' => $callbackData['status'] ?? 'unknown',
                'external_id' => $callbackData['externalId'] ?? 'unknown'
            ],
            'payments'
        );

        $result = $this->freeMoPayService->processCallback($callbackData);

        if ($result) {
            // Journaliser le traitement réussi du callback
            LoggingService::info(
                'Callback de paiement traité avec succès',
                [
                    'action' => 'payment_callback_processed',
                    'reference' => $callbackData['reference'] ?? 'unknown',
                    'status' => $callbackData['status'] ?? 'unknown'
                ],
                'payments'
            );

            return response()->json(['status' => 'success']);
        } else {
            // Journaliser l'échec du traitement du callback
            LoggingService::error(
                'Échec du traitement du callback de paiement',
                [
                    'action' => 'payment_callback_processing_failed',
                    'reference' => $callbackData['reference'] ?? 'unknown',
                    'status' => $callbackData['status'] ?? 'unknown',
                    'data' => $callbackData
                ],
                'payments'
            );

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
            // Journaliser la validation échouée de l'ID de don
            LoggingService::warning(
                'Validation échouée pour la vérification du statut de don',
                [
                    'action' => 'donation_status_check_validation_failed',
                    'errors' => $validator->errors()->toArray(),
                    'input' => $request->all()
                ],
                'donations'
            );

            return response()->json([
                'success' => false,
                'message' => 'Invalid donation ID'
            ], 400);
        }

        $donation = Donation::find($request->donation_id);

        // Security check - only allow users to check their own donations or donations without a user_id
        if (Auth::check() && $donation->user_id && $donation->user_id != Auth::id()) {
            // Journaliser la tentative d'accès non autorisé
            LoggingService::warning(
                'Tentative non autorisée de vérification du statut d\'un don',
                [
                    'action' => 'unauthorized_donation_status_check',
                    'donation_id' => $donation->id,
                    'attempted_user_id' => Auth::id(),
                    'donation_owner_id' => $donation->user_id
                ],
                'security'
            );

            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to check this donation'
            ], 403);
        }

        // Journaliser la vérification du statut de don
        LoggingService::info(
            'Vérification du statut de don',
            [
                'action' => 'donation_status_check',
                'donation_id' => $donation->id,
                'current_status' => $donation->status
            ],
            'donations'
        );

        // Get fresh donation info (this will trigger status update if needed)
        $donationInfo = $this->freeMoPayService->getDonationInfo($request->donation_id);

        if (!$donationInfo['success']) {
            // Journaliser l'échec de récupération du statut
            LoggingService::error(
                'Échec de récupération du statut de don',
                [
                    'action' => 'donation_status_retrieval_failed',
                    'donation_id' => $donation->id
                ],
                'donations'
            );

            return response()->json([
                'success' => false,
                'message' => 'Error checking donation status'
            ], 500);
        }

        // Journaliser le statut mis à jour si différent
        if ($donation->status !== $donationInfo['donation']['status']) {
            LoggingService::info(
                'Statut de don mis à jour',
                [
                    'action' => 'donation_status_updated',
                    'donation_id' => $donation->id,
                    'old_status' => $donation->status,
                    'new_status' => $donationInfo['donation']['status']
                ],
                'donations'
            );
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
            // Journaliser la tentative d'accès à l'historique sans authentification
            LoggingService::info(
                'Tentative d\'accès à l\'historique des dons sans authentification',
                [
                    'action' => 'unauthenticated_donation_history_access',
                    'redirect' => 'login'
                ],
                'security'
            );

            return redirect()->route('login')
                ->with('message', 'Please login to view your donation history');
        }

        // Journaliser la consultation de l'historique des dons
        LoggingService::info(
            'Consultation de l\'historique des dons personnels',
            [
                'action' => 'view_personal_donation_history',
                'user_id' => Auth::id()
            ],
            'donations'
        );

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
            // Journaliser la validation échouée
            LoggingService::warning(
                'Validation échouée pour l\'annulation de don',
                [
                    'action' => 'donation_cancellation_validation_failed',
                    'errors' => $validator->errors()->toArray(),
                    'input' => $request->all()
                ],
                'donations'
            );

            return response()->json([
                'success' => false,
                'message' => 'Invalid donation ID'
            ], 400);
        }

        $donation = Donation::find($request->donation_id);

        // Only allow cancellation of pending donations by the donation owner
        if ($donation->status !== 'pending') {
            // Journaliser la tentative d'annulation d'un don non en attente
            LoggingService::warning(
                'Tentative d\'annulation d\'un don non en attente',
                [
                    'action' => 'non_pending_donation_cancellation_attempt',
                    'donation_id' => $donation->id,
                    'current_status' => $donation->status
                ],
                'donations'
            );

            return response()->json([
                'success' => false,
                'message' => 'Only pending donations can be canceled'
            ], 400);
        }

        // Security check - only allow users to cancel their own donations
        if (Auth::check() && $donation->user_id && $donation->user_id != Auth::id()) {
            // Journaliser la tentative d'annulation non autorisée
            LoggingService::warning(
                'Tentative d\'annulation non autorisée d\'un don',
                [
                    'action' => 'unauthorized_donation_cancellation',
                    'donation_id' => $donation->id,
                    'attempted_user_id' => Auth::id(),
                    'donation_owner_id' => $donation->user_id
                ],
                'security'
            );

            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to cancel this donation'
            ], 403);
        }

        try {
            // Journaliser la demande d'annulation
            LoggingService::info(
                'Demande d\'annulation de don',
                [
                    'action' => 'donation_cancellation_requested',
                    'donation_id' => $donation->id,
                    'transaction_id' => $donation->transaction_id,
                    'amount' => $donation->amount
                ],
                'donations'
            );

            // If there's a transaction ID, we should notify the payment provider
            if ($donation->transaction_id) {
                // This would ideally cancel the payment with the provider
                // For now, we just log it
                LoggingService::info(
                    'Demande d\'annulation de paiement',
                    [
                        'action' => 'payment_cancellation_requested',
                        'donation_id' => $donation->id,
                        'transaction_id' => $donation->transaction_id
                    ],
                    'payments'
                );
            }

            // Update donation status
            $donation->update(['status' => 'failed']);

            // Journaliser le succès de l'annulation
            LoggingService::info(
                'Don annulé avec succès',
                [
                    'action' => 'donation_cancelled',
                    'donation_id' => $donation->id,
                    'transaction_id' => $donation->transaction_id,
                    'amount' => $donation->amount
                ],
                'donations'
            );

            return response()->json([
                'success' => true,
                'message' => 'Donation canceled successfully'
            ]);
        } catch (\Exception $e) {
            // Journaliser l'erreur d'annulation
            LoggingService::error(
                'Erreur lors de l\'annulation du don',
                [
                    'action' => 'donation_cancellation_error',
                    'donation_id' => $donation->id,
                    'error_message' => $e->getMessage(),
                    'error_trace' => $e->getTraceAsString()
                ],
                'donations'
            );

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
        // Journaliser la tentative de suppression
        LoggingService::info(
            'Tentative de suppression d\'un don',
            [
                'action' => 'donation_deletion_attempt',
                'donation_id' => $donation->id,
                'user_id' => Auth::id(),
                'donation_amount' => $donation->amount,
                'donation_status' => $donation->status
            ],
            'donations'
        );

        // Soft delete the donation
        $donation->delete();

        // Journaliser la suppression réussie
        LoggingService::info(
            'Don supprimé avec succès',
            [
                'action' => 'donation_deleted',
                'donation_id' => $donation->id,
                'deleted_by' => Auth::id()
            ],
            'donations'
        );

        // Flash success message
        session()->flash('success', 'Le don a été supprimé avec succès.');

        // Redirect back to donations index
        return redirect()->route('donations.index');
    }
}
