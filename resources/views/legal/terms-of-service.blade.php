@extends('layouts.app')

@section('title', 'Terms of Service - Priority Accommodation')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h1 class="h3 mb-0">Terms of Service</h1>
                    <p class="mb-0 small">Effective Date: {{ date('F j, Y') }}</p>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <strong>Important:</strong> Please read these Terms of Service carefully. By using Priority Accommodation services, you agree to be bound by these terms.
                    </div>

                    <h2 class="h4 text-primary mt-5 mb-3">1. Acceptance of Terms</h2>
                    <p>Welcome to Priority Accommodation. These Terms govern your use of our student accommodation management system for booking, managing, and operating student housing facilities.</p>

                    <h2 class="h4 text-primary mt-5 mb-3">2. Services Description</h2>
                    <p>Priority Accommodation provides:</p>
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item">Student accommodation booking and management</li>
                        <li class="list-group-item">Room assignment and hostel management</li>
                        <li class="list-group-item">Payment processing (rent, deposits, fees)</li>
                        <li class="list-group-item">Check-in and check-out management</li>
                        <li class="list-group-item">Maintenance request handling</li>
                        <li class="list-group-item">Accommodation reporting and analytics</li>
                    </ul>

                    <h2 class="h4 text-primary mt-5 mb-3">3. Booking Terms</h2>
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item">Bookings are subject to availability</li>
                        <li class="list-group-item">Reservation confirmation is required</li>
                        <li class="list-group-item">Cancellation policies apply as specified at booking</li>
                        <li class="list-group-item">Room assignments may be subject to change</li>
                        <li class="list-group-item">Check-in and check-out dates must be adhered to</li>
                    </ul>

                    <h2 class="h4 text-primary mt-5 mb-3">4. Payment Terms</h2>
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item">Rent and fees must be paid according to schedule</li>
                        <li class="list-group-item">Security deposits may be required</li>
                        <li class="list-group-item">Late payments may incur fees</li>
                        <li class="list-group-item">Refunds are subject to cancellation and refund policies</li>
                        <li class="list-group-item">Payment methods: Bank transfer, mobile money, cash (where applicable)</li>
                    </ul>

                    <h2 class="h4 text-primary mt-5 mb-3">5. Student Responsibilities</h2>
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item">Provide accurate information during booking</li>
                        <li class="list-group-item">Maintain accommodation in good condition</li>
                        <li class="list-group-item">Comply with accommodation rules and regulations</li>
                        <li class="list-group-item">Pay rent and fees on time</li>
                        <li class="list-group-item">Report maintenance issues promptly</li>
                        <li class="list-group-item">Respect other residents and property</li>
                    </ul>

                    <h2 class="h4 text-primary mt-5 mb-3">6. Accommodation Rules</h2>
                    <p>Students must comply with:</p>
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item">Hostel rules and regulations</li>
                        <li class="list-group-item">Noise and conduct policies</li>
                        <li class="list-group-item">Visiting hours and guest policies</li>
                        <li class="list-group-item">Prohibited items and activities</li>
                        <li class="list-group-item">Safety and security requirements</li>
                    </ul>

                    <h2 class="h4 text-primary mt-5 mb-3">7. Termination</h2>
                    <p>Accommodation may be terminated:</p>
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item">By student with proper notice (subject to terms)</li>
                        <li class="list-group-item">By management for violations of rules or non-payment</li>
                        <li class="list-group-item">Upon completion of academic program</li>
                    </ul>
                    <p>Early termination may be subject to penalties as specified in booking terms.</p>

                    <h2 class="h4 text-primary mt-5 mb-3">8. Limitation of Liability</h2>
                    <p>To the maximum extent permitted by law, Priority Accommodation's liability is limited to the value of accommodation fees paid. We are not liable for:</p>
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item">Loss or damage to personal property</li>
                        <li class="list-group-item">Injuries or accidents on premises</li>
                        <li class="list-group-item">Indirect, consequential, or special damages</li>
                    </ul>
                    <div class="alert alert-warning">
                        Students are advised to obtain personal property insurance.
                    </div>

                    <h2 class="h4 text-primary mt-5 mb-3">9. Service Modifications</h2>
                    <p>We reserve the right to modify services, policies, or facilities with reasonable notice to residents.</p>

                    <h2 class="h4 text-primary mt-5 mb-3">10. Modifications to Terms</h2>
                    <p>We may update these Terms. Significant changes will be communicated. Continued use after changes constitutes acceptance.</p>

                    <h2 class="h4 text-primary mt-5 mb-3">11. Contact Information</h2>
                    <div class="card bg-light">
                        <div class="card-body">
                            <p class="mb-1"><strong>Priority Accommodation</strong></p>
                            <p class="mb-1">Email: <a href="mailto:info@priorityaccommodation.com">info@priorityaccommodation.com</a></p>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-muted text-center">
                    <small>Document Version: 1.0 | Effective Date: {{ date('F j, Y') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

