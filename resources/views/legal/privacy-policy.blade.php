@extends('layouts.app')

@section('title', 'Privacy Policy - Priority Accommodation')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h1 class="h3 mb-0">Privacy Policy</h1>
                    <p class="mb-0 small">Last Updated: {{ date('F j, Y') }}</p>
                </div>
                <div class="card-body">
                    <p class="lead">At Priority Accommodation, we are committed to protecting the privacy and security of student and accommodation information. This Privacy Policy explains how we collect, use, disclose, and safeguard information when you use our student accommodation management system.</p>

                    <h2 class="h4 text-primary mt-5 mb-3">1. Information We Collect</h2>
                    <div class="alert alert-info">
                        <p class="mb-0">We collect information necessary to manage student accommodations and bookings.</p>
                    </div>
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item"><strong>Student Information:</strong> Personal details, contact information, identification documents, academic information</li>
                        <li class="list-group-item"><strong>Accommodation Data:</strong> Booking details, room assignments, check-in/check-out dates, preferences</li>
                        <li class="list-group-item"><strong>Payment Information:</strong> Payment records, transaction history, billing details</li>
                        <li class="list-group-item"><strong>Hostel/Room Data:</strong> Property information, room availability, maintenance records</li>
                        <li class="list-group-item"><strong>Communication Records:</strong> Emails, messages, service inquiries, notifications</li>
                    </ul>

                    <h2 class="h4 text-primary mt-5 mb-3">2. How We Use Your Information</h2>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="border p-3 rounded">
                                <h5 class="h6 text-primary"><i class="fas fa-bed me-2"></i>Accommodation Management</h5>
                                <p class="small mb-0">Managing bookings, room assignments, and accommodation operations</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border p-3 rounded">
                                <h5 class="h6 text-primary"><i class="fas fa-money-bill me-2"></i>Payment Processing</h5>
                                <p class="small mb-0">Processing rent payments, security deposits, and maintenance fees</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border p-3 rounded">
                                <h5 class="h6 text-primary"><i class="fas fa-envelope me-2"></i>Communication</h5>
                                <p class="small mb-0">Sending booking confirmations, updates, and important notifications</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border p-3 rounded">
                                <h5 class="h6 text-primary"><i class="fas fa-wrench me-2"></i>Maintenance & Services</h5>
                                <p class="small mb-0">Managing maintenance requests and facility services</p>
                            </div>
                        </div>
                    </div>

                    <h2 class="h4 text-primary mt-5 mb-3">3. Data Protection</h2>
                    <div class="d-flex align-items-start mb-3">
                        <div class="me-3 text-primary">
                            <i class="fas fa-lock fa-2x"></i>
                        </div>
                        <div>
                            <p>We implement comprehensive security measures:</p>
                            <ul>
                                <li>256-bit SSL encryption for all data transmissions</li>
                                <li>Secure storage with access controls</li>
                                <li>Regular security audits</li>
                                <li>Role-based access controls</li>
                                <li>Secure payment processing</li>
                            </ul>
                        </div>
                    </div>

                    <h2 class="h4 text-primary mt-5 mb-3">4. Data Sharing</h2>
                    <p>We may share information with:</p>
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item"><strong>Educational Institutions:</strong> For verification and coordination purposes</li>
                        <li class="list-group-item"><strong>Service Providers:</strong> Payment processors, cloud hosting, communication services</li>
                        <li class="list-group-item"><strong>Legal Authorities:</strong> When required by law, court order, or regulatory authority</li>
                    </ul>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>We never sell student information to third-party marketers.
                    </div>

                    <h2 class="h4 text-primary mt-5 mb-3">5. Your Rights</h2>
                    <p>Under data protection laws, you have the right to:</p>
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <span class="badge bg-primary">Access</span>
                        <span class="badge bg-primary">Correction</span>
                        <span class="badge bg-primary">Deletion</span>
                        <span class="badge bg-primary">Data Portability</span>
                        <span class="badge bg-primary">Object to Processing</span>
                    </div>
                    <p>To exercise these rights, contact us at:</p>
                    <div class="card bg-light">
                        <div class="card-body">
                            <p class="mb-1"><strong>Email:</strong> <a href="mailto:privacy@priorityaccommodation.com">privacy@priorityaccommodation.com</a></p>
                        </div>
                    </div>

                    <h2 class="h4 text-primary mt-5 mb-3">6. Data Retention</h2>
                    <p>We retain records according to legal requirements:</p>
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item"><strong>Student Records:</strong> Duration of stay + 2 years</li>
                        <li class="list-group-item"><strong>Financial Records:</strong> 7 years (legal requirement)</li>
                        <li class="list-group-item"><strong>Booking Records:</strong> 3 years after checkout</li>
                    </ul>

                    <h2 class="h4 text-primary mt-5 mb-3">7. Contact Information</h2>
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

