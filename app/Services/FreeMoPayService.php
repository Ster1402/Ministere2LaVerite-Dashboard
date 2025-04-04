<?php

namespace App\Services;

use App\Models\Donation;
use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FreeMoPayService
{
    protected $baseUrl;
    protected $appKey;
    protected $secretKey;
    protected $callbackUrl;
    protected $token;

    // Payment status check settings
    protected $maxRetries = 5;
    protected $statusCheckInterval = 300; // 5 minutes in seconds

    public function __construct()
    {
        // Load configuration from config/services.php
        $this->baseUrl = config('services.freemopay.url');
        $this->appKey = config('services.freemopay.app_key');
        $this->secretKey = config('services.freemopay.secret_key');
        $this->callbackUrl = route('donations.callback');
    }

    /**
     * Generate a bearer token for API requests
     *
     * @return string|null
     */
    public function generateToken()
    {
        try {
            $response = Http::post("{$this->baseUrl}/api/v2/payment/token", [
                'appKey' => $this->appKey,
                'secretKey' => $this->secretKey,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $this->token = $data['access_token'] ?? null;
                return $this->token;
            }

            Log::error('FreeMoPay token generation failed', [
                'response' => $response->json()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('FreeMoPay token generation error', [
                'message' => $e->getMessage()
            ]);

            return null;
        }
    }

    /**
     * Create a new pending donation in the database
     *
     * @param array $donationData
     * @return Donation|null
     */
    public function createPendingDonation(array $donationData)
    {
        try {
            // Ensure we have the required fields
            $requiredFields = ['amount', 'donor_name', 'donor_email', 'donor_phone', 'payment_method_id'];
            foreach ($requiredFields as $field) {
                if (!isset($donationData[$field]) && !array_key_exists($field, $donationData)) {
                    Log::error('Missing required field for donation', [
                        'field' => $field,
                        'data' => $donationData
                    ]);
                    return null;
                }
            }

            // Create a pending donation
            $donation = Donation::create([
                'amount' => $donationData['amount'],
                'donor_name' => $donationData['donor_name'],
                'donor_email' => $donationData['donor_email'],
                'donor_phone' => $donationData['donor_phone'],
                'donation_date' => Carbon::now(),
                'message' => $donationData['message'] ?? null,
                'is_anonymous' => $donationData['is_anonymous'] ?? false,
                'user_id' => $donationData['user_id'] ?? null,
                'payment_method_id' => $donationData['payment_method_id'],
                'status' => 'pending'
            ]);

            Log::info('Created pending donation', [
                'donation_id' => $donation->id
            ]);

            return $donation;
        } catch (\Exception $e) {
            Log::error('Error creating pending donation', [
                'message' => $e->getMessage(),
                'data' => $donationData
            ]);
            return null;
        }
    }

    /**
     * Initialize a payment with FreeMoPay for an existing donation
     *
     * @param Donation $donation
     * @return array
     */
    public function initiatePayment(Donation $donation)
    {
        if (!$this->token) {
            $this->generateToken();
        }

        if (!$this->token) {
            return [
                'success' => false,
                'message' => 'Authentication with payment gateway failed'
            ];
        }

        try {
            // Using provided data fields from the Donation model
            $response = Http::withToken($this->token)
                ->post("{$this->baseUrl}/api/v2/payment", [
                    'payer' => $donation->donor_phone,
                    'amount' => (float)$donation->amount,
                    'externalId' => '' . $donation->id, // Using ID as reference
                    'description' => 'Donation to Ministere2LaVerite' .
                        ($donation->is_anonymous ? '' : ' from ' . $donation->donor_name),
                    'callback' => $this->callbackUrl,
                ]);

            if ($response->successful()) {
                $data = $response->json();

                // Log the successful payment initiation
                Log::info('Payment initiated successfully', [
                    'donation_id' => $donation->id,
                    'response' => $data
                ]);

                // Update transaction ID only
                $donation->update([
                    'reference' => $data['reference'] ?? null
                ]);

                return [
                    'success' => true,
                    'reference' => $data['reference'] ?? null,
                    'status' => $data['status'] ?? null,
                    'message' => $data['message'] ?? 'Payment initiated successfully'
                ];
            }

            Log::error('FreeMoPay payment initiation failed', [
                'response' => $response->json(),
                'donation' => $donation->toArray()
            ]);

            return [
                'success' => false,
                'message' => 'Payment initiation failed: ' . ($response->json()['message'] ?? 'Unknown error')
            ];
        } catch (\Exception $e) {
            Log::error('FreeMoPay payment initiation error', [
                'message' => $e->getMessage(),
                'donation' => $donation->toArray()
            ]);

            return [
                'success' => false,
                'message' => 'Payment initiation error occurred: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Process callback data from FreeMoPay
     *
     * @param array $callbackData
     * @return bool
     */
    public function processCallback(array $callbackData)
    {
        try {
            $reference = $callbackData['reference'] ?? null;
            $status = $callbackData['status'] ?? null;
            $message = $callbackData['message'] ?? null;
            $externalId = $callbackData['externalId'] ?? null;

            if (!$reference || !$status || !$externalId) {
                Log::error('Incomplete callback data from FreeMoPay', [
                    'callbackData' => $callbackData
                ]);
                return false;
            }

            // Find the donation by ID (externalId)
            $donation = Donation::find($externalId);

            if (!$donation) {
                Log::error('Donation not found for payment', [
                    'reference' => $reference,
                    'externalId' => $externalId
                ]);
                return false;
            }

            // Log callback received
            Log::info('Payment callback received', [
                'donation_id' => $donation->id,
                'status' => $status,
                'callbackData' => $callbackData
            ]);

            if ($status === 'SUCCESS') {
                return $this->processSuccessfulPayment($donation, $reference, $callbackData);
            } elseif ($status === 'FAILED' || $status === 'CANCELED') {
                return $this->processFailedPayment($donation, $reference, $message);
            } else {
                // Handle other statuses - keep as pending
                Log::info('Payment still in progress', [
                    'donation_id' => $donation->id,
                    'status' => $status
                ]);
                return true;
            }
        } catch (\Exception $e) {
            Log::error('Error processing FreeMoPay callback', [
                'error' => $e->getMessage(),
                'callbackData' => $callbackData
            ]);
            return false;
        }
    }

    /**
     * Process a successful payment
     *
     * @param Donation $donation
     * @param string $reference Payment reference from FreeMoPay
     * @param array $callbackData Complete callback data
     * @return bool
     */
    protected function processSuccessfulPayment(Donation $donation, string $reference, array $callbackData)
    {
        try {
            // Begin transaction
            \DB::beginTransaction();

            // Update donation status
            $donation->update([
                'status' => 'completed',
                'reference' => $reference
            ]);

            // Create transaction for the donation
            $tnx = Transaction::updateOrCreate(
                ['transaction_reference' => $reference],
                [
                    'amount' => $donation->amount,
                    'currency' => 'XAF', // Default currency based on your code
                    'comment' => 'Online donation via ' . ($donation->paymentMethod ? $donation->paymentMethod->display_name : 'FreeMoPay'),
                    'user_id' => $donation->user_id,
                    'donor_name' => $donation->donor_name,
                    'donor_email' => $donation->donor_email,
                    'donor_phone' => $donation->donor_phone,
                    'payment_method_id' => $donation->payment_method_id,
                    'is_processed' => true,
                    'transaction_reference' => $reference
                ]
            );

            // Update donation transaction ID
            $donation->update([
                'transaction_id' => $tnx->id
            ]);

            \DB::commit();

            // Log the successful payment
            Log::info('Payment processed successfully', [
                'reference' => $reference,
                'donation_id' => $donation->id
            ]);

            return true;
        } catch (\Exception $e) {
            \DB::rollBack();

            Log::error('Error processing successful payment', [
                'reference' => $reference,
                'donation_id' => $donation->id,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Process a failed payment
     *
     * @param Donation $donation
     * @param string $reference Payment reference from FreeMoPay
     * @param string|null $reason Failure reason
     * @return bool
     */
    protected function processFailedPayment(Donation $donation, string $reference, ?string $reason)
    {
        try {
            // Update donation status
            $donation->update([
                'status' => 'failed',
                'reference' => $reference
            ]);

            // Log the failure reason
            Log::info('Failed payment processed', [
                'reference' => $reference,
                'donation_id' => $donation->id,
                'reason' => $reason ?? 'Unknown failure reason'
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Error processing failed payment', [
                'reference' => $reference,
                'donation_id' => $donation->id,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Check payment status with FreeMoPay
     *
     * @param string $reference
     * @return array
     */
    public function checkPaymentStatus($reference)
    {
        if (!$this->token) {
            $this->generateToken();
        }

        if (!$this->token) {
            return [
                'success' => false,
                'message' => 'Authentication with payment gateway failed'
            ];
        }

        try {
            $response = Http::withToken($this->token)
                ->get("{$this->baseUrl}/api/v2/payment/{$reference}");

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'reference' => $data['reference'] ?? null,
                    'externalId' => $data['externalId'] ?? null,
                    'amount' => $data['amount'] ?? null,
                    'status' => $data['status'] ?? 'UNKNOWN',
                    'reason' => $data['message'] ?? null,
                    'data' => $data
                ];
            }

            Log::error('FreeMoPay payment status check failed', [
                'response' => $response->json(),
                'reference' => $reference
            ]);

            return [
                'success' => false,
                'message' => 'Payment status check failed: ' . ($response->json()['message'] ?? 'Unknown error')
            ];
        } catch (\Exception $e) {
            Log::error('FreeMoPay payment status check error', [
                'message' => $e->getMessage(),
                'reference' => $reference
            ]);

            return [
                'success' => false,
                'message' => 'Payment status check error occurred: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get donation information for displaying to users
     *
     * @param int $donationId
     * @return array
     */
    public function getDonationInfo($donationId)
    {
        try {
            $donation = Donation::find($donationId);

            if (!$donation) {
                return [
                    'success' => false,
                    'message' => 'Donation not found'
                ];
            }

            // If donation is pending and has a transaction ID, check its current status
            if ($donation->status === 'pending' && $donation->reference) {
                $this->updateDonationStatus($donation);

                // Refresh the donation to get the latest status
                $donation->refresh();
            }

            return [
                'success' => true,
                'donation' => [
                    'id' => $donation->id,
                    'amount' => $donation->amount,
                    'donor_name' => $donation->is_anonymous ? 'Anonymous' : $donation->donor_name,
                    'is_anonymous' => $donation->is_anonymous,
                    'status' => $donation->status,
                    'donation_date' => $donation->donation_date->format('d/m/Y H:i'),
                    'payment_method' => $donation->paymentMethod ? $donation->paymentMethod->name : 'Unknown',
                    'reference' => $donation->reference,
                    'is_pending' => $donation->status === 'pending',
                    'is_completed' => $donation->status === 'completed',
                    'is_failed' => $donation->status === 'failed',
                    'user_id' => $donation->user_id
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Error getting donation info', [
                'donation_id' => $donationId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Error retrieving donation information'
            ];
        }
    }

    /**
     * Update status of a pending donation
     *
     * @param Donation $donation
     * @return bool
     */
    public function updateDonationStatus(Donation $donation)
    {
        // Only check pending donations with transaction IDs
        if ($donation->status !== 'pending' || !$donation->reference) {
            return false;
        }

        $statusResult = $this->checkPaymentStatus($donation->reference);

        if (!$statusResult['success']) {
            Log::error('Failed to update donation status', [
                'donation_id' => $donation->id,
                'status_result' => $statusResult
            ]);
            return false;
        }

        Log::info('Payment status checked', [
            'donation_id' => $donation->id,
            'status' => $statusResult['status']
        ]);

        if ($statusResult['status'] === 'SUCCESS') {
            return $this->processSuccessfulPayment($donation, $donation->reference, $statusResult['data']);
        } elseif (in_array($statusResult['status'], ['FAILED', 'CANCELED'])) {
            return $this->processFailedPayment(
                $donation,
                $donation->reference,
                $statusResult['reason'] ?? 'Payment processing failed'
            );
        }

        // For other statuses, keep the donation as pending
        return true;
    }

    /**
     * Check all pending donations and update their status
     * This method can be called from a scheduled task
     *
     * @return int Number of donations updated
     */
    public function checkPendingDonations()
    {
        $updatedCount = 0;

        try {
            // Get all pending donations with transaction IDs
            $pendingDonations = Donation::where('status', 'pending')
                ->whereNotNull('reference')
                ->get();

            foreach ($pendingDonations as $donation) {
                $updated = $this->updateDonationStatus($donation);
                if ($updated) {
                    $updatedCount++;
                }
            }

            Log::info('Checked pending donations', [
                'total_checked' => $pendingDonations->count(),
                'updated' => $updatedCount
            ]);

            return $updatedCount;
        } catch (\Exception $e) {
            Log::error('Error checking pending donations', [
                'error' => $e->getMessage()
            ]);

            return $updatedCount;
        }
    }

    /**
     * Get all pending donations for a specific user
     * Useful for displaying pending donations in the UI
     *
     * @param int $userId
     * @return array
     */
    public function getUserPendingDonations($userId)
    {
        try {
            $pendingDonations = Donation::where('user_id', $userId)
                ->where('status', 'pending')
                ->orderBy('donation_date', 'desc')
                ->get();

            $result = [];

            foreach ($pendingDonations as $donation) {
                // Check if the donation status needs updating
                if ($donation->reference) {
                    $this->updateDonationStatus($donation);
                    $donation->refresh();

                    // Skip if no longer pending after update
                    if ($donation->status !== 'pending') {
                        continue;
                    }
                }

                $result[] = [
                    'id' => $donation->id,
                    'amount' => $donation->amount,
                    'donation_date' => $donation->donation_date->format('d/m/Y H:i'),
                    'payment_method' => $donation->paymentMethod ? $donation->paymentMethod->name : 'Unknown',
                    'reference' => $donation->reference
                ];
            }

            return [
                'success' => true,
                'pending_donations' => $result
            ];
        } catch (\Exception $e) {
            Log::error('Error getting user pending donations', [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Error retrieving pending donations'
            ];
        }
    }
}
