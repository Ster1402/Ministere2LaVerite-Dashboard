<?php

namespace App\Services;

use App\Models\Donation;
use App\Models\Transaction;
use App\Services\Commons\PhoneNumberFormatter;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FreeMoPayService
{
    protected $baseUrl;
    protected $appKey;
    protected $secretKey;
    protected $callbackUrl;
    protected $token;

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
     * Initialize a payment with FreeMoPay
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
            $response = Http::withToken($this->token)
                ->post("{$this->baseUrl}/api/v2/payment", [
                    'payer' => $donation->donor_phone,
                    'amount' => (int)$donation->amount,
                    'externalId' => $donation->reference,
                    'description' => 'Donation to Ministere2LaVerite' . ($donation->is_anonymous ? '' : ' from ' . $donation->donor_name),
                    'callback' => $this->callbackUrl,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                ddd($data);

                // Update the donation with payment reference
                $donation->update([
                    'payment_data' => $data,
                    'transaction_id' => $data['reference'] ?? null
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
                    'merchandRef' => $data['merchandRef'] ?? null,
                    'amount' => $data['amount'] ?? null,
                    'status' => $data['status'] ?? 'UNKNOWN',
                    'reason' => $data['reason'] ?? null,
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
     * Process a successful payment
     *
     * @param string $reference Payment reference from FreeMoPay
     * @param string $externalId External reference (our donation reference)
     * @param float $amount Payment amount
     * @return bool
     */
    public function processSuccessfulPayment($reference, $externalId, $amount)
    {
        try {
            // Find the donation by external reference
            $donation = Donation::where('reference', $externalId)->first();

            if (!$donation) {
                Log::error('Donation not found for payment', [
                    'reference' => $reference,
                    'externalId' => $externalId
                ]);
                return false;
            }

            // Update donation status
            $donation->update([
                'status' => 'completed',
                'transaction_id' => $reference
            ]);

            // Create or update transaction
            if (!$donation->transaction_id) {
                Transaction::create([
                    'amount' => $amount,
                    'currency' => 'XAF',
                    'comment' => 'Online donation',
                    'user_id' => $donation->user_id,
                    'donor_name' => $donation->donor_name,
                    'donor_email' => $donation->donor_email,
                    'donor_phone' => $donation->donor_phone,
                    'payment_method_id' => $donation->payment_method_id,
                    'is_processed' => true,
                    'transaction_reference' => $reference
                ]);
            }

            Log::info('Payment processed successfully', [
                'reference' => $reference,
                'donation_id' => $donation->id
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Error processing payment', [
                'reference' => $reference,
                'externalId' => $externalId,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Process a failed payment
     *
     * @param string $reference Payment reference from FreeMoPay
     * @param string $externalId External reference (our donation reference)
     * @param string $reason Failure reason
     * @return bool
     */
    public function processFailedPayment($reference, $externalId, $reason)
    {
        try {
            // Find the donation by external reference
            $donation = Donation::where('reference', $externalId)->first();

            if (!$donation) {
                Log::error('Donation not found for failed payment', [
                    'reference' => $reference,
                    'externalId' => $externalId
                ]);
                return false;
            }

            // Update donation status
            $donation->update([
                'status' => 'failed',
                'transaction_id' => $reference,
                'payment_data' => array_merge(
                    $donation->payment_data ?? [],
                    ['failure_reason' => $reason]
                )
            ]);

            Log::info('Failed payment processed', [
                'reference' => $reference,
                'donation_id' => $donation->id,
                'reason' => $reason
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Error processing failed payment', [
                'reference' => $reference,
                'externalId' => $externalId,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }
}
