@extends('layouts.app')

@section('title', 'System Settings - Priority Accommodations')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <h1 class="page-title">System Settings</h1>
    <div class="page-actions">
        <button type="submit" form="settings-form" class="btn btn-primary">
            <i class="fas fa-save"></i>
            Save Changes
        </button>
    </div>
</div>

<div class="settings-layout">
    <!-- Settings Navigation -->
    <div class="settings-sidebar">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Configuration</h3>
            </div>
            <div class="card-body p-0">
                <nav class="settings-nav">
                    <button class="settings-nav-item active" data-tab="general">
                        <div class="settings-nav-icon">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div class="settings-nav-content">
                            <div class="settings-nav-title">General</div>
                            <div class="settings-nav-subtitle">Basic system configuration</div>
                        </div>
                    </button>

                    <button class="settings-nav-item" data-tab="hostel">
                        <div class="settings-nav-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="settings-nav-content">
                            <div class="settings-nav-title">Hostel Operations</div>
                            <div class="settings-nav-subtitle">Check-in/out times & policies</div>
                        </div>
                    </button>

                    <button class="settings-nav-item" data-tab="payment">
                        <div class="settings-nav-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div class="settings-nav-content">
                            <div class="settings-nav-title">Payment Settings</div>
                            <div class="settings-nav-subtitle">Payment methods & receipts</div>
                        </div>
                    </button>

                    <button class="settings-nav-item" data-tab="online-payments">
                        <div class="settings-nav-icon">
                            <i class="fas fa-globe"></i>
                        </div>
                        <div class="settings-nav-content">
                            <div class="settings-nav-title">Online Payments</div>
                            <div class="settings-nav-subtitle">Hubtel & Paystack config</div>
                        </div>
                    </button>

                    <button class="settings-nav-item" data-tab="notifications">
                        <div class="settings-nav-icon">
                            <i class="fas fa-bell"></i>
                        </div>
                        <div class="settings-nav-content">
                            <div class="settings-nav-title">Notifications</div>
                            <div class="settings-nav-subtitle">Email & SMS alerts</div>
                        </div>
                    </button>

                    <button class="settings-nav-item" data-tab="backup">
                        <div class="settings-nav-icon">
                            <i class="fas fa-database"></i>
                        </div>
                        <div class="settings-nav-content">
                            <div class="settings-nav-title">Backup & Restore</div>
                            <div class="settings-nav-subtitle">Data management</div>
                        </div>
                    </button>

                    <button class="settings-nav-item" data-tab="maintenance">
                        <div class="settings-nav-icon">
                            <i class="fas fa-tools"></i>
                        </div>
                        <div class="settings-nav-content">
                            <div class="settings-nav-title">Maintenance</div>
                            <div class="settings-nav-subtitle">System maintenance mode</div>
                        </div>
                    </button>
                </nav>
            </div>
        </div>
    </div>

    <!-- Settings Content -->
    <div class="settings-content">
        <form action="{{ route('admin.settings.update') }}" method="POST" id="settings-form">
            @csrf
            
            <!-- General Settings -->
            <div class="settings-section active" id="general-section">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-cog text-blue-600 mr-2"></i>
                            General Settings
                        </h3>
                        <p class="card-subtitle">Basic system configuration and preferences</p>
                    </div>
                    <div class="card-body">
                        <div class="settings-grid">
                            <div class="form-group">
                                <label class="form-label">System Name *</label>
                                <input type="text" name="site_name" class="form-control" 
                                       value="{{ old('site_name', $settings['site_name'] ?? 'Priority Accommodations') }}" 
                                       placeholder="Enter system name" required>
                                <div class="form-help">This name will appear throughout the system</div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Contact Email *</label>
                                <input type="email" name="contact_email" class="form-control" 
                                       value="{{ old('contact_email', $settings['contact_email'] ?? 'admin@hostel.com') }}" 
                                       placeholder="Enter contact email" required>
                                <div class="form-help">System notifications will be sent from this address</div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Contact Phone *</label>
                                <input type="text" name="contact_phone" class="form-control" 
                                       value="{{ old('contact_phone', $settings['contact_phone'] ?? '+1234567890') }}" 
                                       placeholder="Enter contact phone" required>
                                <div class="form-help">Primary contact number for the hostel</div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Timezone</label>
                                <select name="timezone" class="form-control">
                                    @foreach(timezone_identifiers_list() as $timezone)
                                    <option value="{{ $timezone }}" {{ ($settings['timezone'] ?? 'UTC') == $timezone ? 'selected' : '' }}>
                                        {{ $timezone }}
                                    </option>
                                    @endforeach
                                </select>
                                <div class="form-help">Set the default timezone for the system</div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Currency</label>
                                <select name="currency" class="form-control">
                                    <option value="GHS" {{ ($settings['currency'] ?? 'GHS') == 'GHS' ? 'selected' : '' }}>Ghana Cedi (₵)</option>
                                    <option value="USD" {{ ($settings['currency'] ?? 'GHS') == 'USD' ? 'selected' : '' }}>US Dollar ($)</option>
                                    <option value="EUR" {{ ($settings['currency'] ?? 'GHS') == 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                                    <option value="GBP" {{ ($settings['currency'] ?? 'GHS') == 'GBP' ? 'selected' : '' }}>British Pound (£)</option>
                                </select>
                                <div class="form-help">Default currency for all financial transactions</div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Date Format</label>
                                <select name="date_format" class="form-control">
                                    <option value="Y-m-d" {{ ($settings['date_format'] ?? 'Y-m-d') == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                                    <option value="d/m/Y" {{ ($settings['date_format'] ?? 'Y-m-d') == 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY</option>
                                    <option value="m/d/Y" {{ ($settings['date_format'] ?? 'Y-m-d') == 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY</option>
                                    <option value="d M Y" {{ ($settings['date_format'] ?? 'Y-m-d') == 'd M Y' ? 'selected' : '' }}>DD Mon YYYY</option>
                                </select>
                                <div class="form-help">How dates are displayed throughout the system</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hostel Settings -->
            <div class="settings-section" id="hostel-section">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-building text-green-600 mr-2"></i>
                            Hostel Operations
                        </h3>
                        <p class="card-subtitle">Configure hostel-specific settings and policies</p>
                    </div>
                    <div class="card-body">
                        <div class="settings-grid">
                            <div class="form-group">
                                <label class="form-label">Check-in Time *</label>
                                <input type="time" name="check_in_time" class="form-control" 
                                       value="{{ old('check_in_time', $settings['check_in_time'] ?? '14:00') }}" required>
                                <div class="form-help">Default check-in time for all bookings</div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Check-out Time *</label>
                                <input type="time" name="check_out_time" class="form-control" 
                                       value="{{ old('check_out_time', $settings['check_out_time'] ?? '11:00') }}" required>
                                <div class="form-help">Default check-out time for all bookings</div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Maximum Booking Days *</label>
                                <input type="number" name="max_booking_days" class="form-control" 
                                       value="{{ old('max_booking_days', $settings['max_booking_days'] ?? 180) }}" 
                                       min="1" max="365" required>
                                <div class="form-help">Maximum number of days for a single booking</div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Security Deposit (₵)</label>
                                <input type="number" name="security_deposit" class="form-control" 
                                       value="{{ old('security_deposit', $settings['security_deposit'] ?? 500) }}" 
                                       step="0.01" min="0">
                                <div class="form-help">Default security deposit amount for new bookings</div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Late Check-out Fee (₵)</label>
                                <input type="number" name="late_checkout_fee" class="form-control" 
                                       value="{{ old('late_checkout_fee', $settings['late_checkout_fee'] ?? 100) }}" 
                                       step="0.01" min="0">
                                <div class="form-help">Fee charged for late check-outs</div>
                            </div>

                            <div class="form-group full-width">
                                <label class="form-label">Cancellation Policy *</label>
                                <textarea name="cancellation_policy" class="form-control" rows="4" 
                                          placeholder="Enter cancellation policy terms" required>{{ old('cancellation_policy', $settings['cancellation_policy'] ?? 'Free cancellation up to 24 hours before check-in') }}</textarea>
                                <div class="form-help">This policy will be shown to students during booking</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Settings -->
            <div class="settings-section" id="payment-section">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-credit-card text-purple-600 mr-2"></i>
                            Payment Settings
                        </h3>
                        <p class="card-subtitle">Configure payment methods and receipt settings</p>
                    </div>
                    <div class="card-body">
                        <div class="settings-grid">
                            <div class="form-group full-width">
                                <label class="form-label">Enabled Payment Methods</label>
                                <div class="payment-methods-grid">
                                    <label class="payment-method-card">
                                        <input type="checkbox" name="payment_methods[]" value="cash" 
                                               {{ in_array('cash', $settings['payment_methods'] ?? ['cash', 'bank_transfer']) ? 'checked' : '' }}>
                                        <div class="payment-method-content">
                                            <div class="payment-icon cash">
                                                <i class="fas fa-money-bill-wave"></i>
                                            </div>
                                            <div class="payment-info">
                                                <div class="payment-name">Cash</div>
                                                <div class="payment-description">Physical cash payments</div>
                                            </div>
                                        </div>
                                    </label>

                                    <label class="payment-method-card">
                                        <input type="checkbox" name="payment_methods[]" value="bank_transfer" 
                                               {{ in_array('bank_transfer', $settings['payment_methods'] ?? ['cash', 'bank_transfer']) ? 'checked' : '' }}>
                                        <div class="payment-method-content">
                                            <div class="payment-icon bank">
                                                <i class="fas fa-university"></i>
                                            </div>
                                            <div class="payment-info">
                                                <div class="payment-name">Bank Transfer</div>
                                                <div class="payment-description">Direct bank transfers</div>
                                            </div>
                                        </div>
                                    </label>

                                    <label class="payment-method-card">
                                        <input type="checkbox" name="payment_methods[]" value="mobile_money" 
                                               {{ in_array('mobile_money', $settings['payment_methods'] ?? []) ? 'checked' : '' }}>
                                        <div class="payment-method-content">
                                            <div class="payment-icon mobile">
                                                <i class="fas fa-mobile-alt"></i>
                                            </div>
                                            <div class="payment-info">
                                                <div class="payment-name">Mobile Money</div>
                                                <div class="payment-description">MTN Mobile Money, AirtelTigo Money, etc.</div>
                                            </div>
                                        </div>
                                    </label>

                                    <label class="payment-method-card">
                                        <input type="checkbox" name="payment_methods[]" value="card" 
                                               {{ in_array('card', $settings['payment_methods'] ?? []) ? 'checked' : '' }}>
                                        <div class="payment-method-content">
                                            <div class="payment-icon card">
                                                <i class="fas fa-credit-card"></i>
                                            </div>
                                            <div class="payment-info">
                                                <div class="payment-name">Credit/Debit Card</div>
                                                <div class="payment-description">Visa, Mastercard payments</div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Receipt Prefix</label>
                                <input type="text" name="receipt_prefix" class="form-control" 
                                       value="{{ old('receipt_prefix', $settings['receipt_prefix'] ?? 'PAY') }}" 
                                       placeholder="e.g., PAY">
                                <div class="form-help">Prefix for auto-generated receipt numbers</div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Auto-generate Receipts</label>
                                <select name="auto_receipt" class="form-control">
                                    <option value="1" {{ ($settings['auto_receipt'] ?? 1) ? 'selected' : '' }}>Yes, automatically generate</option>
                                    <option value="0" {{ !($settings['auto_receipt'] ?? 1) ? 'selected' : '' }}>No, manual entry only</option>
                                </select>
                                <div class="form-help">Automatically generate receipt numbers for payments</div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Tax Rate (%)</label>
                                <input type="number" name="tax_rate" class="form-control" 
                                       value="{{ old('tax_rate', $settings['tax_rate'] ?? 0) }}" 
                                       step="0.01" min="0" max="100">
                                <div class="form-help">Default tax rate applied to all payments</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Online Payment Gateway Settings -->
            <div class="settings-section" id="online-payments-section">
                @php
                    $paymentSettings = \App\Models\PaymentSettings::first() ?? new \App\Models\PaymentSettings();
                @endphp
                <div class="card mb-6">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-globe text-blue-600 mr-2"></i>
                            Online Payment Gateways
                        </h3>
                        <p class="card-subtitle">Configure Hubtel and/or Paystack for online payments. If both are enabled, Hubtel takes precedence.</p>
                    </div>
                    <div class="card-body">
                        <!-- Priority Notice -->
                        <div class="alert alert-info mb-6" style="background: rgba(67, 97, 238, 0.1); border: 1px solid rgba(67, 97, 238, 0.2);">
                            <div class="alert-icon" style="color: var(--primary);">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div class="alert-content">
                                <div class="alert-title">Payment Gateway Priority</div>
                                <div class="alert-description">
                                    <strong>Hubtel takes precedence</strong> if both gateways are configured. Paystack is used as fallback.
                                </div>
                            </div>
                        </div>

                        <!-- Hubtel Configuration -->
                        <div class="mb-8 p-6 border-2 border-dashed border-gray-200 rounded-lg">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-mobile-alt text-2xl text-blue-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-lg">Hubtel</h4>
                                        <p class="text-sm text-gray-500">Mobile Money & Card Payments</p>
                                    </div>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox" name="hubtel_enabled" value="1" {{ $paymentSettings->hubtel_enabled ? 'checked' : '' }}>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                            <div class="settings-grid" id="hubtel-fields">
                                <div class="form-group">
                                    <label class="form-label">Client ID</label>
                                    <input type="text" name="hubtel_client_id" class="form-control" 
                                           value="{{ $paymentSettings->hubtel_client_id }}" placeholder="Enter Hubtel Client ID">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Client Secret</label>
                                    <input type="password" name="hubtel_client_secret" class="form-control" 
                                           placeholder="{{ $paymentSettings->hubtel_client_secret ? '••••••••' : 'Enter Client Secret' }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Merchant Account Number</label>
                                    <input type="text" name="hubtel_merchant_account_number" class="form-control" 
                                           value="{{ $paymentSettings->hubtel_merchant_account_number }}" placeholder="e.g., HM2706240001">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Mode</label>
                                    <select name="hubtel_mode" class="form-control">
                                        <option value="sandbox" {{ $paymentSettings->hubtel_mode === 'sandbox' ? 'selected' : '' }}>Sandbox (Testing)</option>
                                        <option value="live" {{ $paymentSettings->hubtel_mode === 'live' ? 'selected' : '' }}>Live (Production)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Paystack Configuration -->
                        <div class="mb-8 p-6 border-2 border-dashed border-gray-200 rounded-lg">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
                                        <i class="fas fa-credit-card text-2xl text-green-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-lg">Paystack</h4>
                                        <p class="text-sm text-gray-500">Cards & Mobile Money</p>
                                    </div>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox" name="paystack_enabled" value="1" {{ $paymentSettings->paystack_enabled ? 'checked' : '' }}>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                            <div class="settings-grid" id="paystack-fields">
                                <div class="form-group">
                                    <label class="form-label">Public Key</label>
                                    <input type="text" name="paystack_public_key" class="form-control" 
                                           value="{{ $paymentSettings->paystack_public_key }}" placeholder="pk_test_xxxxx or pk_live_xxxxx">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Secret Key</label>
                                    <input type="password" name="paystack_secret_key" class="form-control" 
                                           placeholder="{{ $paymentSettings->paystack_secret_key ? '••••••••' : 'Enter Secret Key' }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Mode</label>
                                    <select name="paystack_mode" class="form-control">
                                        <option value="test" {{ $paymentSettings->paystack_mode === 'test' ? 'selected' : '' }}>Test Mode</option>
                                        <option value="live" {{ $paymentSettings->paystack_mode === 'live' ? 'selected' : '' }}>Live Mode</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Confirmation Settings -->
                <div class="card mb-6">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-clock text-orange-600 mr-2"></i>
                            Booking Confirmation Settings
                        </h3>
                        <p class="card-subtitle">Configure how long hostel owners have to confirm bookings</p>
                    </div>
                    <div class="card-body">
                        <div class="settings-grid">
                            <div class="form-group">
                                <label class="form-label">Confirmation Timeout (minutes)</label>
                                <input type="number" name="confirmation_timeout_minutes" class="form-control" 
                                       value="{{ $paymentSettings->confirmation_timeout_minutes ?? 3 }}" min="1" max="30">
                                <div class="form-help">Time hostel owners have to confirm/reject a booking after payment</div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Default Commission (%)</label>
                                <input type="number" name="default_commission_percentage" class="form-control" 
                                       value="{{ $paymentSettings->default_commission_percentage ?? 10 }}" min="0" max="50" step="0.5">
                                <div class="form-help">Platform commission on each booking</div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Minimum Deposit (%)</label>
                                <input type="number" name="minimum_deposit_percentage" class="form-control" 
                                       value="{{ $paymentSettings->minimum_deposit_percentage ?? 50 }}" min="0" max="100" step="5">
                                <div class="form-help">Minimum percentage students must pay upfront</div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">If No Response</label>
                                <select name="auto_confirm_if_no_response" class="form-control">
                                    <option value="0" {{ !$paymentSettings->auto_confirm_if_no_response ? 'selected' : '' }}>Reject & Refund (Safer)</option>
                                    <option value="1" {{ $paymentSettings->auto_confirm_if_no_response ? 'selected' : '' }}>Auto-Confirm Booking</option>
                                </select>
                                <div class="form-help">What happens if hostel owner doesn't respond in time</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SMS Gateway Settings -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-sms text-purple-600 mr-2"></i>
                            SMS Gateway Settings
                        </h3>
                        <p class="card-subtitle">Configure SMS notifications for booking alerts</p>
                    </div>
                    <div class="card-body">
                        <div class="settings-grid">
                            <div class="form-group">
                                <label class="form-label">SMS Gateway</label>
                                <select name="sms_gateway" class="form-control">
                                    <option value="">Disabled</option>
                                    <option value="hubtel_sms" {{ ($paymentSettings->sms_gateway ?? '') === 'hubtel_sms' ? 'selected' : '' }}>Hubtel SMS</option>
                                    <option value="arkesel" {{ ($paymentSettings->sms_gateway ?? '') === 'arkesel' ? 'selected' : '' }}>Arkesel</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">SMS API Key</label>
                                <input type="password" name="sms_api_key" class="form-control" 
                                       placeholder="{{ $paymentSettings->sms_api_key ? '••••••••' : 'Enter API Key' }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Sender ID</label>
                                <input type="text" name="sms_sender_id" class="form-control" 
                                       value="{{ $paymentSettings->sms_sender_id ?? 'PriorityAcc' }}" maxlength="11">
                                <div class="form-help">Max 11 characters, no spaces</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notification Settings -->
            <div class="settings-section" id="notifications-section">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-bell text-orange-600 mr-2"></i>
                            Notification Settings
                        </h3>
                        <p class="card-subtitle">Configure email and SMS notifications</p>
                    </div>
                    <div class="card-body">
                        <div class="settings-grid">
                            <div class="form-group full-width">
                                <label class="form-label">Email Notifications</label>
                                <div class="notification-settings">
                                    <label class="notification-item">
                                        <input type="checkbox" name="email_notifications[]" value="new_booking" 
                                               {{ in_array('new_booking', $settings['email_notifications'] ?? ['new_booking', 'payment_received']) ? 'checked' : '' }}>
                                        <div class="notification-content">
                                            <div class="notification-title">New Booking Requests</div>
                                            <div class="notification-description">Get notified when a new booking is made</div>
                                        </div>
                                    </label>

                                    <label class="notification-item">
                                        <input type="checkbox" name="email_notifications[]" value="payment_received" 
                                               {{ in_array('payment_received', $settings['email_notifications'] ?? ['new_booking', 'payment_received']) ? 'checked' : '' }}>
                                        <div class="notification-content">
                                            <div class="notification-title">Payment Received</div>
                                            <div class="notification-description">Notify when a payment is successfully processed</div>
                                        </div>
                                    </label>

                                    <label class="notification-item">
                                        <input type="checkbox" name="email_notifications[]" value="checkin_reminder" 
                                               {{ in_array('checkin_reminder', $settings['email_notifications'] ?? []) ? 'checked' : '' }}>
                                        <div class="notification-content">
                                            <div class="notification-title">Check-in Reminders</div>
                                            <div class="notification-description">Send reminders before scheduled check-ins</div>
                                        </div>
                                    </label>

                                    <label class="notification-item">
                                        <input type="checkbox" name="email_notifications[]" value="maintenance" 
                                               {{ in_array('maintenance', $settings['email_notifications'] ?? []) ? 'checked' : '' }}>
                                        <div class="notification-content">
                                            <div class="notification-title">Maintenance Alerts</div>
                                            <div class="notification-description">Receive maintenance request notifications</div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group full-width">
                                <label class="form-label">SMS Notifications</label>
                                <div class="notification-settings">
                                    <label class="notification-item">
                                        <input type="checkbox" name="sms_notifications[]" value="booking_confirmation" 
                                               {{ in_array('booking_confirmation', $settings['sms_notifications'] ?? []) ? 'checked' : '' }}>
                                        <div class="notification-content">
                                            <div class="notification-title">Booking Confirmation</div>
                                            <div class="notification-description">Send SMS when booking is confirmed</div>
                                        </div>
                                    </label>

                                    <label class="notification-item">
                                        <input type="checkbox" name="sms_notifications[]" value="payment_reminder" 
                                               {{ in_array('payment_reminder', $settings['sms_notifications'] ?? []) ? 'checked' : '' }}>
                                        <div class="notification-content">
                                            <div class="notification-title">Payment Reminders</div>
                                            <div class="notification-description">Send payment due reminders via SMS</div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Backup & Restore -->
            <div class="settings-section" id="backup-section">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-database text-red-600 mr-2"></i>
                            Backup & Restore
                        </h3>
                        <p class="card-subtitle">Manage system data backups and restoration</p>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning">
                            <div class="alert-icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="alert-content">
                                <div class="alert-title">Important</div>
                                <div class="alert-description">
                                    Regular backups are essential for data security. We recommend backing up your data at least once a week.
                                </div>
                            </div>
                        </div>

                        <div class="settings-grid">
                            <div class="backup-actions">
                                <div class="backup-card">
                                    <div class="backup-icon download">
                                        <i class="fas fa-download"></i>
                                    </div>
                                    <div class="backup-content">
                                        <h4 class="backup-title">Create Backup</h4>
                                        <p class="backup-description">Download a complete backup of your system data including all records and settings.</p>
                                        <button type="button" class="btn btn-primary">
                                            <i class="fas fa-download"></i>
                                            Download Backup
                                        </button>
                                    </div>
                                </div>

                                <div class="backup-card">
                                    <div class="backup-icon upload">
                                        <i class="fas fa-upload"></i>
                                    </div>
                                    <div class="backup-content">
                                        <h4 class="backup-title">Restore Backup</h4>
                                        <p class="backup-description">Upload a previous backup file to restore your system data and settings.</p>
                                        <input type="file" class="hidden" id="backup-file" accept=".json,.sql,.backup">
                                        <button type="button" class="btn btn-success" onclick="document.getElementById('backup-file').click()">
                                            <i class="fas fa-upload"></i>
                                            Upload Backup
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Auto Backup Schedule</label>
                                <select name="backup_schedule" class="form-control">
                                    <option value="daily" {{ ($settings['backup_schedule'] ?? 'weekly') == 'daily' ? 'selected' : '' }}>Daily</option>
                                    <option value="weekly" {{ ($settings['backup_schedule'] ?? 'weekly') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                    <option value="monthly" {{ ($settings['backup_schedule'] ?? 'weekly') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    <option value="never" {{ ($settings['backup_schedule'] ?? 'weekly') == 'never' ? 'selected' : '' }}>Never (Not Recommended)</option>
                                </select>
                                <div class="form-help">How often should the system automatically create backups?</div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Keep Backups For</label>
                                <select name="backup_retention" class="form-control">
                                    <option value="7" {{ ($settings['backup_retention'] ?? '30') == '7' ? 'selected' : '' }}>7 days</option>
                                    <option value="30" {{ ($settings['backup_retention'] ?? '30') == '30' ? 'selected' : '' }}>30 days</option>
                                    <option value="90" {{ ($settings['backup_retention'] ?? '30') == '90' ? 'selected' : '' }}>90 days</option>
                                    <option value="365" {{ ($settings['backup_retention'] ?? '30') == '365' ? 'selected' : '' }}>1 year</option>
                                </select>
                                <div class="form-help">How long to keep backup files before automatic deletion</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Maintenance Settings -->
            <div class="settings-section" id="maintenance-section">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-tools text-gray-600 mr-2"></i>
                            Maintenance Mode
                        </h3>
                        <p class="card-subtitle">Take the system offline for maintenance</p>
                    </div>
                    <div class="card-body">
                        <div class="settings-grid">
                            <div class="form-group full-width">
                                <label class="toggle-label">
                                    <div class="toggle-content">
                                        <div class="toggle-title">Enable Maintenance Mode</div>
                                        <div class="toggle-description">
                                            When enabled, the system will be temporarily unavailable to users. 
                                            Only administrators will be able to access the system.
                                        </div>
                                    </div>
                                    <label class="toggle-switch">
                                        <input type="checkbox" name="maintenance_mode" 
                                               {{ ($settings['maintenance_mode'] ?? false) ? 'checked' : '' }}>
                                        <span class="toggle-slider"></span>
                                    </label>
                                </label>
                            </div>

                            <div class="form-group full-width">
                                <label class="form-label">Maintenance Message</label>
                                <textarea name="maintenance_message" class="form-control" rows="4" 
                                          placeholder="Enter a message to display to users during maintenance">{{ old('maintenance_message', $settings['maintenance_message'] ?? 'System is currently under maintenance. Please check back later.') }}</textarea>
                                <div class="form-help">This message will be shown to users when maintenance mode is active</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
/* Settings Layout */
.settings-layout {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 2rem;
    align-items: start;
}

.settings-sidebar {
    position: sticky;
    top: 2rem;
}

.settings-content {
    min-height: 600px;
}

/* Navigation Styles */
.settings-nav {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.settings-nav .settings-nav-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem 1.25rem;
    border: none;
    background: none;
    color: var(--gray-700);
    text-align: left;
    cursor: pointer;
    transition: all 0.3s ease;
    border-radius: var(--border-radius);
    width: 100%;
}

.settings-nav .settings-nav-item:hover {
    background: var(--gray-50);
    color: var(--primary);
}

.settings-nav .settings-nav-item.active {
    background: var(--primary-light);
    color: var(--primary);
    font-weight: 600;
}

.settings-nav .settings-nav-icon {
    width: 2rem;
    height: 2rem;
    border-radius: var(--border-radius);
    background: var(--gray-100);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gray-600);
    flex-shrink: 0;
    transition: all 0.3s ease;
}

.settings-nav .settings-nav-item.active .settings-nav-icon,
.settings-nav .settings-nav-item:hover .settings-nav-icon {
    background: var(--primary);
    color: white;
}

.settings-nav .settings-nav-content {
    flex: 1;
    min-width: 0;
}

.settings-nav .settings-nav-title {
    font-weight: 600;
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

.settings-nav .settings-nav-subtitle {
    font-size: 0.75rem;
    color: var(--gray-500);
    line-height: 1.4;
}

.settings-nav .settings-nav-item.active .settings-nav-subtitle {
    color: var(--primary);
}

/* Settings Sections */
.settings-section {
    display: none;
}

.settings-section.active {
    display: block;
}

.card-subtitle {
    color: var(--gray-600);
    font-size: 0.875rem;
    margin-top: 0.5rem;
}

/* Settings Grid */
.settings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

/* Form Styles */
.form-label {
    display: block;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.form-control {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    background: white;
    transition: all 0.2s ease;
    font-size: 0.875rem;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
}

.form-help {
    font-size: 0.75rem;
    color: var(--gray-500);
    margin-top: 0.5rem;
    line-height: 1.4;
}

/* Payment Methods */
.payment-methods-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.payment-method-card {
    display: block;
    border: 2px solid var(--border-color);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.payment-method-card:hover {
    border-color: var(--primary);
    transform: translateY(-2px);
}

.payment-method-card input {
    position: absolute;
    opacity: 0;
}

.payment-method-card input:checked + .payment-method-content {
    color: var(--primary);
}

.payment-method-card input:checked + .payment-method-content .payment-icon {
    background: var(--primary);
    color: white;
}

.payment-method-content {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.payment-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    transition: all 0.3s ease;
}

.payment-icon.cash {
    background: rgba(76, 201, 240, 0.1);
    color: var(--success);
}

.payment-icon.bank {
    background: rgba(67, 97, 238, 0.1);
    color: var(--primary);
}

.payment-icon.mobile {
    background: rgba(147, 51, 234, 0.1);
    color: #8b5cf6;
}

.payment-icon.card {
    background: rgba(248, 150, 30, 0.1);
    color: var(--warning);
}

.payment-info {
    flex: 1;
}

.payment-name {
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 0.25rem;
}

.payment-description {
    font-size: 0.75rem;
    color: var(--gray-500);
    line-height: 1.4;
}

/* Notification Settings */
.notification-settings {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.notification-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    cursor: pointer;
    transition: all 0.3s ease;
}

.notification-item:hover {
    border-color: var(--primary);
    background: var(--gray-50);
}

.notification-item input {
    margin-top: 0.25rem;
}

.notification-content {
    flex: 1;
}

.notification-title {
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 0.25rem;
}

.notification-description {
    font-size: 0.875rem;
    color: var(--gray-600);
    line-height: 1.4;
}

/* Backup Cards */
.backup-actions {
    grid-column: 1 / -1;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.backup-card {
    display: flex;
    align-items: flex-start;
    gap: 1.5rem;
    padding: 2rem;
    border: 2px solid var(--border-color);
    border-radius: var(--border-radius);
    transition: all 0.3s ease;
}

.backup-card:hover {
    border-color: var(--primary);
    transform: translateY(-2px);
}

.backup-icon {
    width: 4rem;
    height: 4rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.backup-icon.download {
    background: rgba(67, 97, 238, 0.1);
    color: var(--primary);
}

.backup-icon.upload {
    background: rgba(76, 201, 240, 0.1);
    color: var(--success);
}

.backup-content {
    flex: 1;
}

.backup-title {
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 0.5rem;
    font-size: 1.125rem;
}

.backup-description {
    color: var(--gray-600);
    margin-bottom: 1.5rem;
    line-height: 1.5;
}

/* Toggle Switch */
.toggle-label {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    padding: 1.5rem;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    cursor: pointer;
    transition: all 0.3s ease;
}

.toggle-label:hover {
    border-color: var(--primary);
}

.toggle-content {
    flex: 1;
}

.toggle-title {
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 0.5rem;
}

.toggle-description {
    color: var(--gray-600);
    font-size: 0.875rem;
    line-height: 1.5;
}

.toggle-switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
    margin-left: 1rem;
    flex-shrink: 0;
}

.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: var(--gray-300);
    transition: .4s;
    border-radius: 34px;
}

.toggle-slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

input:checked + .toggle-slider {
    background-color: var(--primary);
}

input:checked + .toggle-slider:before {
    transform: translateX(26px);
}

/* Alert Styles */
.alert {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem 1.5rem;
    border-radius: var(--border-radius);
    margin-bottom: 1.5rem;
}

.alert-warning {
    background: rgba(248, 150, 30, 0.1);
    border: 1px solid rgba(248, 150, 30, 0.2);
}

.alert-icon {
    color: var(--warning);
    font-size: 1.25rem;
    margin-top: 0.125rem;
}

.alert-title {
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 0.25rem;
}

.alert-description {
    color: var(--gray-600);
    font-size: 0.875rem;
    line-height: 1.5;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .settings-layout {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .settings-sidebar {
        position: static;
    }
    
    .settings-nav {
        flex-direction: row;
        overflow-x: auto;
        padding-bottom: 0.5rem;
    }
    
    .settings-nav .settings-nav-item {
        min-width: 200px;
        flex-direction: column;
        text-align: center;
        gap: 0.75rem;
    }
    
    .settings-nav .settings-nav-content {
        text-align: center;
    }
}

@media (max-width: 768px) {
    .settings-grid {
        grid-template-columns: 1fr;
    }
    
    .payment-methods-grid {
        grid-template-columns: 1fr;
    }
    
    .backup-actions {
        grid-template-columns: 1fr;
    }
    
    .backup-card {
        flex-direction: column;
        text-align: center;
    }
    
    .toggle-label {
        flex-direction: column;
        gap: 1rem;
    }
    
    .toggle-switch {
        margin-left: 0;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab navigation
    const navItems = document.querySelectorAll('.settings-nav-item');
    const sections = document.querySelectorAll('.settings-section');

    navItems.forEach(item => {
        item.addEventListener('click', function() {
            const tabName = this.dataset.tab;
            
            // Update active nav item
            navItems.forEach(nav => nav.classList.remove('active'));
            this.classList.add('active');
            
            // Show corresponding section
            sections.forEach(section => {
                section.classList.remove('active');
                if (section.id === `${tabName}-section`) {
                    section.classList.add('active');
                }
            });
        });
    });

    // Enhanced form interactions
    const paymentMethodCards = document.querySelectorAll('.payment-method-card');
    paymentMethodCards.forEach(card => {
        card.addEventListener('click', function() {
            const checkbox = this.querySelector('input[type="checkbox"]');
            checkbox.checked = !checkbox.checked;
            this.classList.toggle('active', checkbox.checked);
        });
    });

    // File input enhancement
    const backupFileInput = document.getElementById('backup-file');
    if (backupFileInput) {
        backupFileInput.addEventListener('change', function(e) {
            if (this.files.length > 0) {
                const fileName = this.files[0].name;
                const uploadBtn = this.previousElementSibling;
                if (uploadBtn) {
                    uploadBtn.innerHTML = `<i class="fas fa-check"></i> ${fileName}`;
                    uploadBtn.classList.add('btn-primary');
                    uploadBtn.classList.remove('btn-success');
                }
            }
        });
    }

    // Form validation
    const form = document.getElementById('settings-form');
    form.addEventListener('submit', function(e) {
        const requiredFields = form.querySelectorAll('[required]');
        let valid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                valid = false;
                field.style.borderColor = 'var(--danger)';
            } else {
                field.style.borderColor = '';
            }
        });

        if (!valid) {
            e.preventDefault();
            // Show error message
            const errorAlert = document.createElement('div');
            errorAlert.className = 'alert alert-danger';
            errorAlert.innerHTML = `
                <div class="alert-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="alert-content">
                    <div class="alert-title">Please fill in all required fields</div>
                    <div class="alert-description">Fields marked with * are required.</div>
                </div>
            `;
            form.prepend(errorAlert);
            
            // Scroll to first error
            const firstError = form.querySelector('[required]:invalid');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
        }
    });

    // Real-time validation
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            if (this.hasAttribute('required') && !this.value.trim()) {
                this.style.borderColor = 'var(--danger)';
            } else {
                this.style.borderColor = '';
            }
        });
    });
});
</script>
@endsection