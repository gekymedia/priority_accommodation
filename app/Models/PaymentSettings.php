<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class PaymentSettings extends Model
{
    protected $fillable = [
        'hubtel_enabled',
        'hubtel_client_id',
        'hubtel_client_secret',
        'hubtel_merchant_account_number',
        'hubtel_api_key',
        'hubtel_mode',
        'paystack_enabled',
        'paystack_public_key',
        'paystack_secret_key',
        'paystack_mode',
        'currency',
        'currency_symbol',
        'minimum_deposit_percentage',
        'allow_partial_payment',
        'confirmation_timeout_minutes',
        'auto_confirm_if_no_response',
        'default_commission_percentage',
        'notify_admin_on_payment',
        'notify_hostel_owner_on_booking',
        'notify_student_on_confirmation',
        'sms_gateway',
        'sms_api_key',
        'sms_sender_id',
    ];

    protected $casts = [
        'hubtel_enabled' => 'boolean',
        'paystack_enabled' => 'boolean',
        'allow_partial_payment' => 'boolean',
        'auto_confirm_if_no_response' => 'boolean',
        'notify_admin_on_payment' => 'boolean',
        'notify_hostel_owner_on_booking' => 'boolean',
        'notify_student_on_confirmation' => 'boolean',
        'minimum_deposit_percentage' => 'decimal:2',
        'default_commission_percentage' => 'decimal:2',
        'confirmation_timeout_minutes' => 'integer',
    ];

    /**
     * Encrypt sensitive fields when setting
     */
    public function setHubtelClientSecretAttribute($value)
    {
        $this->attributes['hubtel_client_secret'] = $value ? Crypt::encryptString($value) : null;
    }

    public function setPaystackSecretKeyAttribute($value)
    {
        $this->attributes['paystack_secret_key'] = $value ? Crypt::encryptString($value) : null;
    }

    public function setSmsApiKeyAttribute($value)
    {
        $this->attributes['sms_api_key'] = $value ? Crypt::encryptString($value) : null;
    }

    /**
     * Decrypt sensitive fields when getting
     */
    public function getHubtelClientSecretAttribute($value)
    {
        try {
            return $value ? Crypt::decryptString($value) : null;
        } catch (\Exception $e) {
            return $value; // Return as-is if not encrypted
        }
    }

    public function getPaystackSecretKeyAttribute($value)
    {
        try {
            return $value ? Crypt::decryptString($value) : null;
        } catch (\Exception $e) {
            return $value;
        }
    }

    public function getSmsApiKeyAttribute($value)
    {
        try {
            return $value ? Crypt::decryptString($value) : null;
        } catch (\Exception $e) {
            return $value;
        }
    }

    /**
     * Check if Hubtel is properly configured
     */
    public function isHubtelConfigured(): bool
    {
        return $this->hubtel_enabled 
            && $this->hubtel_client_id 
            && $this->hubtel_client_secret
            && $this->hubtel_merchant_account_number;
    }

    /**
     * Check if Paystack is properly configured
     */
    public function isPaystackConfigured(): bool
    {
        return $this->paystack_enabled 
            && $this->paystack_public_key 
            && $this->paystack_secret_key;
    }

    /**
     * Get the active payment provider
     */
    public function getActiveProvider(): ?string
    {
        // Hubtel takes precedence if both are configured
        if ($this->isHubtelConfigured()) {
            return 'hubtel';
        }

        if ($this->isPaystackConfigured()) {
            return 'paystack';
        }

        return null;
    }

    /**
     * Check if any payment provider is configured
     */
    public function hasPaymentProvider(): bool
    {
        return $this->getActiveProvider() !== null;
    }
}

