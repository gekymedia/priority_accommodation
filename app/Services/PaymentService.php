<?php

namespace App\Services;

use App\Models\PaymentSettings;
use App\Models\BookingRequest;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentService
{
    protected PaymentSettings $settings;
    protected string $activeProvider;

    public function __construct()
    {
        $this->settings = PaymentSettings::first() ?? new PaymentSettings();
        $this->activeProvider = $this->determineActiveProvider();
    }

    /**
     * Determine which payment provider to use (Hubtel takes precedence)
     */
    protected function determineActiveProvider(): string
    {
        if ($this->settings->hubtel_enabled && $this->settings->hubtel_client_id) {
            return 'hubtel';
        }
        
        if ($this->settings->paystack_enabled && $this->settings->paystack_secret_key) {
            return 'paystack';
        }

        return 'none';
    }

    /**
     * Get the active payment provider
     */
    public function getActiveProvider(): string
    {
        return $this->activeProvider;
    }

    /**
     * Check if any payment provider is configured
     */
    public function isConfigured(): bool
    {
        return $this->activeProvider !== 'none';
    }

    /**
     * Initialize a payment for a booking request
     */
    public function initializePayment(BookingRequest $request, array $customerData): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'error' => 'No payment provider configured. Please contact admin.',
            ];
        }

        if ($this->activeProvider === 'hubtel') {
            return $this->initializeHubtelPayment($request, $customerData);
        }

        return $this->initializePaystackPayment($request, $customerData);
    }

    /**
     * Initialize Hubtel payment
     */
    protected function initializeHubtelPayment(BookingRequest $request, array $customerData): array
    {
        $reference = 'PA-' . strtoupper(Str::random(8)) . '-' . $request->id;
        
        $payload = [
            'totalAmount' => $request->total_amount,
            'description' => "Room booking at {$request->hostel->name}",
            'callbackUrl' => route('payments.hubtel.callback'),
            'returnUrl' => route('bookings.payment.status', ['token' => $request->request_token]),
            'cancellationUrl' => route('bookings.payment.cancelled', ['token' => $request->request_token]),
            'merchantBusinessLogoUrl' => asset('images/logo.png'),
            'merchantAccountNumber' => $this->settings->hubtel_merchant_account_number,
            'clientReference' => $reference,
            'customerName' => $customerData['name'],
            'customerMsisdn' => $customerData['phone'],
            'customerEmail' => $customerData['email'] ?? null,
        ];

        try {
            $baseUrl = $this->settings->hubtel_mode === 'live' 
                ? 'https://api-txnstatus.hubtel.com/checkout/initiate'
                : 'https://api-txnstatus.hubtel.com/checkout/initiate'; // Same for sandbox in Hubtel

            $response = Http::withBasicAuth(
                $this->settings->hubtel_client_id,
                $this->settings->hubtel_client_secret
            )->post($baseUrl, $payload);

            if ($response->successful()) {
                $data = $response->json();
                
                // Update booking request
                $request->update([
                    'payment_reference' => $reference,
                    'payment_provider' => 'hubtel',
                    'status' => 'payment_processing',
                    'payment_initiated_at' => now(),
                ]);

                return [
                    'success' => true,
                    'provider' => 'hubtel',
                    'reference' => $reference,
                    'checkout_url' => $data['data']['checkoutUrl'] ?? null,
                    'checkout_id' => $data['data']['checkoutId'] ?? null,
                ];
            }

            Log::error('Hubtel payment initialization failed', [
                'request_id' => $request->id,
                'response' => $response->json(),
            ]);

            return [
                'success' => false,
                'error' => 'Payment initialization failed. Please try again.',
            ];
        } catch (\Exception $e) {
            Log::error('Hubtel payment error', [
                'request_id' => $request->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => 'Payment service unavailable. Please try again later.',
            ];
        }
    }

    /**
     * Initialize Paystack payment
     */
    protected function initializePaystackPayment(BookingRequest $request, array $customerData): array
    {
        $reference = 'PA-' . strtoupper(Str::random(8)) . '-' . $request->id;
        
        $payload = [
            'email' => $customerData['email'],
            'amount' => $request->total_amount * 100, // Paystack uses pesewas
            'currency' => 'GHS',
            'reference' => $reference,
            'callback_url' => route('payments.paystack.callback'),
            'metadata' => [
                'booking_request_id' => $request->id,
                'request_token' => $request->request_token,
                'customer_name' => $customerData['name'],
                'customer_phone' => $customerData['phone'],
            ],
        ];

        try {
            $response = Http::withToken($this->settings->paystack_secret_key)
                ->post('https://api.paystack.co/transaction/initialize', $payload);

            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['status'] === true) {
                    // Update booking request
                    $request->update([
                        'payment_reference' => $reference,
                        'payment_provider' => 'paystack',
                        'status' => 'payment_processing',
                        'payment_initiated_at' => now(),
                    ]);

                    return [
                        'success' => true,
                        'provider' => 'paystack',
                        'reference' => $reference,
                        'checkout_url' => $data['data']['authorization_url'],
                        'access_code' => $data['data']['access_code'],
                    ];
                }
            }

            Log::error('Paystack payment initialization failed', [
                'request_id' => $request->id,
                'response' => $response->json(),
            ]);

            return [
                'success' => false,
                'error' => 'Payment initialization failed. Please try again.',
            ];
        } catch (\Exception $e) {
            Log::error('Paystack payment error', [
                'request_id' => $request->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => 'Payment service unavailable. Please try again later.',
            ];
        }
    }

    /**
     * Verify Hubtel payment
     */
    public function verifyHubtelPayment(string $reference): array
    {
        try {
            $baseUrl = 'https://api-txnstatus.hubtel.com/transactions/' . $reference . '/status';

            $response = Http::withBasicAuth(
                $this->settings->hubtel_client_id,
                $this->settings->hubtel_client_secret
            )->get($baseUrl);

            if ($response->successful()) {
                $data = $response->json();
                
                return [
                    'success' => true,
                    'status' => $data['data']['status'] ?? 'unknown',
                    'data' => $data['data'] ?? [],
                ];
            }

            return [
                'success' => false,
                'error' => 'Could not verify payment status',
            ];
        } catch (\Exception $e) {
            Log::error('Hubtel verification error', [
                'reference' => $reference,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => 'Verification failed',
            ];
        }
    }

    /**
     * Verify Paystack payment
     */
    public function verifyPaystackPayment(string $reference): array
    {
        try {
            $response = Http::withToken($this->settings->paystack_secret_key)
                ->get('https://api.paystack.co/transaction/verify/' . $reference);

            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['status'] === true && $data['data']['status'] === 'success') {
                    return [
                        'success' => true,
                        'status' => 'success',
                        'amount' => $data['data']['amount'] / 100, // Convert from pesewas
                        'data' => $data['data'],
                    ];
                }
                
                return [
                    'success' => true,
                    'status' => $data['data']['status'] ?? 'failed',
                    'data' => $data['data'] ?? [],
                ];
            }

            return [
                'success' => false,
                'error' => 'Could not verify payment status',
            ];
        } catch (\Exception $e) {
            Log::error('Paystack verification error', [
                'reference' => $reference,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => 'Verification failed',
            ];
        }
    }

    /**
     * Process successful payment
     */
    public function processSuccessfulPayment(BookingRequest $request, array $paymentData): bool
    {
        try {
            // Update booking request
            $request->update([
                'status' => 'awaiting_confirmation',
                'payment_completed_at' => now(),
                'confirmation_sent_at' => now(),
                'confirmation_deadline' => now()->addMinutes($this->settings->confirmation_timeout_minutes),
                'payment_response' => $paymentData,
            ]);

            // Notify hostel owner
            app(NotificationService::class)->notifyHostelOwnerOfBooking($request);

            // Notify admin
            if ($this->settings->notify_admin_on_payment) {
                app(NotificationService::class)->notifyAdminOfPayment($request);
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Error processing successful payment', [
                'request_id' => $request->id,
                'error' => $e->getMessage(),
            ]);
            
            return false;
        }
    }

    /**
     * Get payment settings
     */
    public function getSettings(): PaymentSettings
    {
        return $this->settings;
    }
}

