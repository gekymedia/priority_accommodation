<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Service - Priority Accommodations</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #006b3f;
            --primary-dark: #005530;
            --primary-light: #e8f5f0;
            --secondary: #ce1126;
            --accent: #fcd116;
            --dark: #1a202c;
            --gray-100: #f7fafc;
            --gray-200: #edf2f7;
            --gray-500: #718096;
            --gray-600: #4a5568;
            --gray-700: #2d3748;
            --radius: 0.5rem;
            --radius-lg: 1rem;
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--gray-100);
            color: var(--dark);
            line-height: 1.7;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 3rem 1rem;
            text-align: center;
            position: relative;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--secondary) 33.33%, var(--accent) 33.33%, var(--accent) 66.66%, var(--primary) 66.66%);
        }

        .header-content {
            max-width: 800px;
            margin: 0 auto;
        }

        .header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .header p {
            opacity: 0.9;
            font-size: 1.1rem;
        }

        .back-link {
            position: absolute;
            top: 1.5rem;
            left: 1.5rem;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            opacity: 0.9;
            transition: opacity 0.3s;
        }

        .back-link:hover {
            opacity: 1;
        }

        /* Content */
        .content {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem 1rem 4rem;
        }

        .legal-card {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            padding: 2.5rem;
            margin-bottom: 2rem;
        }

        .last-updated {
            background: var(--primary-light);
            color: var(--primary);
            padding: 0.75rem 1rem;
            border-radius: var(--radius);
            font-size: 0.875rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section {
            margin-bottom: 2rem;
        }

        .section:last-child {
            margin-bottom: 0;
        }

        .section h2 {
            color: var(--primary);
            font-size: 1.35rem;
            font-weight: 700;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary-light);
        }

        .section h3 {
            color: var(--gray-700);
            font-size: 1.1rem;
            font-weight: 600;
            margin: 1.25rem 0 0.75rem;
        }

        .section p {
            color: var(--gray-600);
            margin-bottom: 1rem;
        }

        .section ul, .section ol {
            color: var(--gray-600);
            margin-left: 1.5rem;
            margin-bottom: 1rem;
        }

        .section li {
            margin-bottom: 0.5rem;
        }

        .highlight-box {
            background: var(--gray-100);
            border-left: 4px solid var(--primary);
            padding: 1rem 1.25rem;
            margin: 1rem 0;
            border-radius: 0 var(--radius) var(--radius) 0;
        }

        .highlight-box.warning {
            border-left-color: var(--secondary);
            background: #fef2f2;
        }

        .contact-info {
            background: var(--primary-light);
            padding: 1.5rem;
            border-radius: var(--radius);
            margin-top: 1.5rem;
        }

        .contact-info h4 {
            color: var(--primary);
            margin-bottom: 0.75rem;
        }

        .contact-info p {
            margin-bottom: 0.5rem;
            color: var(--gray-700);
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 2rem;
            color: var(--gray-500);
            font-size: 0.875rem;
        }

        .footer a {
            color: var(--primary);
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        /* Table of Contents */
        .toc {
            background: var(--gray-100);
            padding: 1.5rem;
            border-radius: var(--radius);
            margin-bottom: 2rem;
        }

        .toc h3 {
            color: var(--dark);
            font-size: 1rem;
            margin-bottom: 1rem;
        }

        .toc ul {
            list-style: none;
            margin: 0;
        }

        .toc li {
            margin-bottom: 0.5rem;
        }

        .toc a {
            color: var(--primary);
            text-decoration: none;
            font-size: 0.9rem;
        }

        .toc a:hover {
            text-decoration: underline;
        }

        @media (max-width: 640px) {
            .header h1 {
                font-size: 1.75rem;
            }

            .legal-card {
                padding: 1.5rem;
            }

            .back-link {
                position: static;
                margin-bottom: 1rem;
                display: inline-flex;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <a href="{{ url('/') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Home
        </a>
        <div class="header-content">
            <h1><i class="fas fa-file-contract"></i> Terms of Service</h1>
            <p>Please read these terms carefully before using our services</p>
        </div>
    </header>

    <main class="content">
        <div class="legal-card">
            <div class="last-updated">
                <i class="fas fa-calendar-alt"></i>
                <span>Last Updated: {{ date('F d, Y') }}</span>
            </div>

            <nav class="toc">
                <h3>Table of Contents</h3>
                <ul>
                    <li><a href="#acceptance">1. Acceptance of Terms</a></li>
                    <li><a href="#services">2. Description of Services</a></li>
                    <li><a href="#accounts">3. User Accounts</a></li>
                    <li><a href="#hostel-owners">4. Hostel Owner Responsibilities</a></li>
                    <li><a href="#students">5. Student/Tenant Responsibilities</a></li>
                    <li><a href="#bookings">6. Bookings and Payments</a></li>
                    <li><a href="#prohibited">7. Prohibited Activities</a></li>
                    <li><a href="#intellectual">8. Intellectual Property</a></li>
                    <li><a href="#liability">9. Limitation of Liability</a></li>
                    <li><a href="#termination">10. Termination</a></li>
                    <li><a href="#governing">11. Governing Law</a></li>
                    <li><a href="#contact">12. Contact Information</a></li>
                </ul>
            </nav>

            <section class="section" id="acceptance">
                <h2>1. Acceptance of Terms</h2>
                <p>Welcome to Priority Accommodations. By accessing or using our platform, you agree to be bound by these Terms of Service ("Terms"). If you do not agree to these Terms, please do not use our services.</p>
                <p>These Terms constitute a legally binding agreement between you and Priority Accommodations ("we," "us," or "our") governing your use of the Priority Accommodations platform and related services.</p>
                <div class="highlight-box">
                    <strong>Important:</strong> By creating an account or using our services, you confirm that you are at least 18 years old and have the legal capacity to enter into this agreement.
                </div>
            </section>

            <section class="section" id="services">
                <h2>2. Description of Services</h2>
                <p>Priority Accommodations provides a digital platform that connects hostel owners/managers with students and individuals seeking accommodation in Ghana. Our services include:</p>
                <ul>
                    <li>Hostel management tools for property owners</li>
                    <li>Room listing and booking management</li>
                    <li>Student accommodation search and booking</li>
                    <li>Payment processing and tracking</li>
                    <li>Communication tools between landlords and tenants</li>
                    <li>Integration with Catholic University of Ghana (CUG) student systems</li>
                </ul>
                <p>We act as an intermediary platform and are not party to any rental agreements between hostel owners and tenants.</p>
            </section>

            <section class="section" id="accounts">
                <h2>3. User Accounts</h2>
                <h3>3.1 Account Registration</h3>
                <p>To access certain features, you must register for an account. You agree to:</p>
                <ul>
                    <li>Provide accurate, current, and complete information</li>
                    <li>Maintain and update your information as necessary</li>
                    <li>Keep your password secure and confidential</li>
                    <li>Accept responsibility for all activities under your account</li>
                    <li>Notify us immediately of any unauthorized access</li>
                </ul>
                
                <h3>3.2 Account Security</h3>
                <p>You are responsible for maintaining the confidentiality of your account credentials. We recommend using a strong, unique password and enabling any additional security features we provide.</p>
                
                <div class="highlight-box warning">
                    <strong>Warning:</strong> Sharing your account credentials with others is strictly prohibited and may result in account termination.
                </div>
            </section>

            <section class="section" id="hostel-owners">
                <h2>4. Hostel Owner Responsibilities</h2>
                <p>If you register as a hostel owner or manager, you agree to:</p>
                <ul>
                    <li>Provide accurate information about your property and available rooms</li>
                    <li>Maintain your property in safe, habitable condition</li>
                    <li>Comply with all applicable Ghanaian laws and regulations regarding rental properties</li>
                    <li>Honor confirmed bookings at the listed price</li>
                    <li>Respond to inquiries and booking requests in a timely manner</li>
                    <li>Maintain valid business registration and permits where required</li>
                    <li>Not discriminate against potential tenants based on ethnicity, religion, gender, or disability</li>
                    <li>Provide accurate photos and descriptions of accommodations</li>
                </ul>
            </section>

            <section class="section" id="students">
                <h2>5. Student/Tenant Responsibilities</h2>
                <p>If you use our platform to find accommodation, you agree to:</p>
                <ul>
                    <li>Provide accurate personal and student information</li>
                    <li>Make payments on time as agreed</li>
                    <li>Treat the property with care and respect</li>
                    <li>Comply with hostel rules and regulations</li>
                    <li>Report any issues or maintenance needs promptly</li>
                    <li>Not engage in illegal activities on the premises</li>
                    <li>Vacate the property at the end of your tenancy agreement</li>
                </ul>
            </section>

            <section class="section" id="bookings">
                <h2>6. Bookings and Payments</h2>
                <h3>6.1 Booking Process</h3>
                <p>Bookings made through our platform are subject to confirmation by the hostel owner. A booking is only confirmed once you receive a confirmation notification.</p>
                
                <h3>6.2 Payments</h3>
                <ul>
                    <li>All prices are displayed in Ghana Cedis (GHS)</li>
                    <li>Payment methods accepted include mobile money, bank transfer, and card payments</li>
                    <li>Service fees may apply to certain transactions</li>
                    <li>Receipts will be provided for all payments</li>
                </ul>
                
                <h3>6.3 Cancellations and Refunds</h3>
                <p>Cancellation and refund policies are determined by individual hostel owners and will be displayed at the time of booking. Priority Accommodations is not responsible for refund disputes between parties.</p>
            </section>

            <section class="section" id="prohibited">
                <h2>7. Prohibited Activities</h2>
                <p>You agree not to:</p>
                <ul>
                    <li>Use the platform for any illegal purpose</li>
                    <li>Post false, misleading, or fraudulent content</li>
                    <li>Harass, abuse, or harm other users</li>
                    <li>Attempt to gain unauthorized access to our systems</li>
                    <li>Use automated systems to access the platform without permission</li>
                    <li>Circumvent or manipulate our fee structure</li>
                    <li>Post spam or unsolicited advertisements</li>
                    <li>Impersonate another person or entity</li>
                    <li>Violate any applicable laws or regulations</li>
                </ul>
            </section>

            <section class="section" id="intellectual">
                <h2>8. Intellectual Property</h2>
                <p>All content on the Priority Accommodations platform, including but not limited to text, graphics, logos, icons, images, and software, is the property of Priority Accommodations or its licensors and is protected by Ghanaian and international copyright laws.</p>
                <p>You may not reproduce, distribute, modify, or create derivative works from any content without our express written permission.</p>
            </section>

            <section class="section" id="liability">
                <h2>9. Limitation of Liability</h2>
                <p>To the maximum extent permitted by law:</p>
                <ul>
                    <li>Priority Accommodations is not liable for any indirect, incidental, or consequential damages</li>
                    <li>We do not guarantee the accuracy of information provided by users</li>
                    <li>We are not responsible for disputes between hostel owners and tenants</li>
                    <li>We do not guarantee availability of any accommodation</li>
                    <li>Our total liability shall not exceed the fees paid by you in the preceding 12 months</li>
                </ul>
                <div class="highlight-box">
                    <strong>Disclaimer:</strong> Priority Accommodations provides the platform "as is" and makes no warranties regarding the quality, safety, or legality of listed accommodations.
                </div>
            </section>

            <section class="section" id="termination">
                <h2>10. Termination</h2>
                <p>We reserve the right to suspend or terminate your account at any time for:</p>
                <ul>
                    <li>Violation of these Terms</li>
                    <li>Fraudulent or illegal activity</li>
                    <li>Complaints from other users</li>
                    <li>Extended periods of inactivity</li>
                    <li>Any other reason at our discretion</li>
                </ul>
                <p>You may terminate your account at any time by contacting us. Termination does not affect any obligations incurred prior to termination.</p>
            </section>

            <section class="section" id="governing">
                <h2>11. Governing Law</h2>
                <p>These Terms shall be governed by and construed in accordance with the laws of the Republic of Ghana. Any disputes arising from these Terms shall be subject to the exclusive jurisdiction of the courts of Ghana.</p>
            </section>

            <section class="section" id="contact">
                <h2>12. Contact Information</h2>
                <p>If you have any questions about these Terms of Service, please contact us:</p>
                <div class="contact-info">
                    <h4><i class="fas fa-building"></i> Priority Accommodations</h4>
                    <p><i class="fas fa-map-marker-alt"></i> Catholic University of Ghana, Fiapre, Sunyani</p>
                    <p><i class="fas fa-envelope"></i> legal@priorityaccommodations.com</p>
                    <p><i class="fas fa-phone"></i> +233 XX XXX XXXX</p>
                    <p><i class="fas fa-clock"></i> Monday - Friday: 8:00 AM - 5:00 PM GMT</p>
                </div>
            </section>
        </div>
    </main>

    <footer class="footer">
        <p>&copy; {{ date('Y') }} Priority Accommodations. All rights reserved.</p>
        <p style="margin-top: 0.5rem;">
            <a href="{{ route('terms') }}">Terms of Service</a> • 
            <a href="{{ route('privacy') }}">Privacy Policy</a> • 
            <a href="{{ url('/') }}">Home</a>
        </p>
    </footer>
</body>
</html>

