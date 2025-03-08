<?php

namespace App\Services;

use App\Models\Donation;
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
        $this->baseUrl = config('services.freemopay.url');
        $this->appKey = config('services.freemopay.app_key');
        $this->secretKey = config('services.freemopay.secret_key');
        $this->callbackUrl = route('donations.callback');
    }

    /**
     * Generate a token for API requests
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
                $this->token = $data['token'] ?? null;
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
                    'payer' => $donation->phone_number,
                    'amount' => (float)$donation->amount,
                    'externalId' => $donation->reference,
                    'description' => 'Donation to Ministere de la Verite' . ($donation->is_anonymous ? '' : ' from ' . $donation->donor_name),
                    'callback' => $this->callbackUrl,
                ]);

            if ($response->successful()) {
                $data = $response->json();

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
}
