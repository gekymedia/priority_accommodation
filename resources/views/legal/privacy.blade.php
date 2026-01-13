<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - Priority Accommodations</title>
    
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

        .highlight-box.info {
            border-left-color: #3182ce;
            background: #ebf8ff;
        }

        .highlight-box.warning {
            border-left-color: var(--secondary);
            background: #fef2f2;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
            font-size: 0.9rem;
        }

        .data-table th,
        .data-table td {
            padding: 0.75rem 1rem;
            text-align: left;
            border-bottom: 1px solid var(--gray-200);
        }

        .data-table th {
            background: var(--gray-100);
            font-weight: 600;
            color: var(--gray-700);
        }

        .data-table td {
            color: var(--gray-600);
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

        .rights-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--primary);
            color: white;
            padding: 0.35rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
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

            .data-table {
                font-size: 0.8rem;
            }

            .data-table th,
            .data-table td {
                padding: 0.5rem;
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
            <h1><i class="fas fa-shield-alt"></i> Privacy Policy</h1>
            <p>How we collect, use, and protect your personal information</p>
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
                    <li><a href="#introduction">1. Introduction</a></li>
                    <li><a href="#information">2. Information We Collect</a></li>
                    <li><a href="#how-we-use">3. How We Use Your Information</a></li>
                    <li><a href="#sharing">4. Information Sharing</a></li>
                    <li><a href="#data-security">5. Data Security</a></li>
                    <li><a href="#data-retention">6. Data Retention</a></li>
                    <li><a href="#your-rights">7. Your Rights</a></li>
                    <li><a href="#cookies">8. Cookies and Tracking</a></li>
                    <li><a href="#third-party">9. Third-Party Services</a></li>
                    <li><a href="#children">10. Children's Privacy</a></li>
                    <li><a href="#changes">11. Changes to This Policy</a></li>
                    <li><a href="#contact">12. Contact Us</a></li>
                </ul>
            </nav>

            <section class="section" id="introduction">
                <h2>1. Introduction</h2>
                <p>Priority Accommodations ("we," "us," or "our") is committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our platform and services.</p>
                <p>This policy applies to all users of the Priority Accommodations platform, including hostel owners, students, and visitors in Ghana.</p>
                <div class="highlight-box info">
                    <strong>Our Commitment:</strong> We believe in transparency and giving you control over your personal data. We only collect what we need and protect it with industry-standard security measures.
                </div>
            </section>

            <section class="section" id="information">
                <h2>2. Information We Collect</h2>
                
                <h3>2.1 Information You Provide</h3>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Type of Data</th>
                            <th>Examples</th>
                            <th>Purpose</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Account Information</strong></td>
                            <td>Name, email, phone number, password</td>
                            <td>Account creation and authentication</td>
                        </tr>
                        <tr>
                            <td><strong>Student Information</strong></td>
                            <td>Student ID, programme, level (from CUG system)</td>
                            <td>Verification and booking eligibility</td>
                        </tr>
                        <tr>
                            <td><strong>Property Information</strong></td>
                            <td>Hostel name, address, photos, room details</td>
                            <td>Listing accommodations</td>
                        </tr>
                        <tr>
                            <td><strong>Payment Information</strong></td>
                            <td>Mobile money number, bank details</td>
                            <td>Processing transactions</td>
                        </tr>
                        <tr>
                            <td><strong>Communication Data</strong></td>
                            <td>Messages, support inquiries</td>
                            <td>Customer service and dispute resolution</td>
                        </tr>
                    </tbody>
                </table>

                <h3>2.2 Information Collected Automatically</h3>
                <ul>
                    <li><strong>Device Information:</strong> Browser type, operating system, device identifiers</li>
                    <li><strong>Usage Data:</strong> Pages visited, features used, time spent on platform</li>
                    <li><strong>Location Data:</strong> General location based on IP address (not precise GPS)</li>
                    <li><strong>Log Data:</strong> Access times, error logs, referring URLs</li>
                </ul>

                <h3>2.3 Information from Third Parties</h3>
                <ul>
                    <li><strong>CUG Admissions System:</strong> Student verification data (with your consent)</li>
                    <li><strong>Payment Providers:</strong> Transaction status and confirmation</li>
                </ul>
            </section>

            <section class="section" id="how-we-use">
                <h2>3. How We Use Your Information</h2>
                <p>We use your information for the following purposes:</p>
                <ul>
                    <li><strong>Provide Services:</strong> Create accounts, process bookings, facilitate payments</li>
                    <li><strong>Verification:</strong> Confirm your identity and student status</li>
                    <li><strong>Communication:</strong> Send booking confirmations, important updates, and respond to inquiries</li>
                    <li><strong>Improve Platform:</strong> Analyze usage patterns to enhance features</li>
                    <li><strong>Safety & Security:</strong> Detect fraud, prevent abuse, and protect users</li>
                    <li><strong>Legal Compliance:</strong> Meet regulatory requirements and respond to legal requests</li>
                    <li><strong>Marketing:</strong> Send promotional content (only with your consent)</li>
                </ul>
                
                <div class="highlight-box">
                    <strong>Legal Basis:</strong> We process your data based on: (a) your consent, (b) contractual necessity, (c) legal obligations, and (d) legitimate business interests.
                </div>
            </section>

            <section class="section" id="sharing">
                <h2>4. Information Sharing</h2>
                <p>We may share your information with:</p>
                
                <h3>4.1 Other Users</h3>
                <ul>
                    <li>Hostel owners can see tenant information for confirmed bookings</li>
                    <li>Students can see hostel owner contact details after booking</li>
                    <li>Public profiles display limited information as you choose</li>
                </ul>

                <h3>4.2 Service Providers</h3>
                <ul>
                    <li>Payment processors (MTN Mobile Money, Vodafone Cash, banks)</li>
                    <li>Cloud hosting providers</li>
                    <li>Email and SMS service providers</li>
                    <li>Analytics services</li>
                </ul>

                <h3>4.3 Legal Requirements</h3>
                <p>We may disclose information when required by law, court order, or to protect our rights and safety.</p>

                <div class="highlight-box warning">
                    <strong>We Never Sell Your Data:</strong> We do not sell, rent, or trade your personal information to third parties for marketing purposes.
                </div>
            </section>

            <section class="section" id="data-security">
                <h2>5. Data Security</h2>
                <p>We implement industry-standard security measures to protect your data:</p>
                <ul>
                    <li><strong>Encryption:</strong> All data transmitted via HTTPS/TLS encryption</li>
                    <li><strong>Password Security:</strong> Passwords are hashed using bcrypt algorithm</li>
                    <li><strong>Access Controls:</strong> Limited employee access to personal data</li>
                    <li><strong>Regular Audits:</strong> Periodic security assessments and updates</li>
                    <li><strong>Secure Infrastructure:</strong> Data hosted on secure, monitored servers</li>
                </ul>
                <p>While we strive to protect your information, no method of transmission over the internet is 100% secure. We encourage you to use strong passwords and protect your account credentials.</p>
            </section>

            <section class="section" id="data-retention">
                <h2>6. Data Retention</h2>
                <p>We retain your data for as long as necessary to provide our services and fulfill the purposes outlined in this policy:</p>
                <ul>
                    <li><strong>Active Accounts:</strong> Data retained while your account is active</li>
                    <li><strong>Closed Accounts:</strong> Basic data retained for 3 years for legal/accounting purposes</li>
                    <li><strong>Transaction Records:</strong> Retained for 7 years per Ghanaian tax law</li>
                    <li><strong>Communication Logs:</strong> Retained for 2 years</li>
                </ul>
                <p>You may request deletion of your data at any time, subject to legal retention requirements.</p>
            </section>

            <section class="section" id="your-rights">
                <h2>7. Your Rights</h2>
                <p>You have the following rights regarding your personal data:</p>
                <ul>
                    <li>
                        <span class="rights-badge"><i class="fas fa-eye"></i> Access</span>
                        Request a copy of your personal data
                    </li>
                    <li>
                        <span class="rights-badge"><i class="fas fa-edit"></i> Rectification</span>
                        Correct inaccurate or incomplete data
                    </li>
                    <li>
                        <span class="rights-badge"><i class="fas fa-trash"></i> Erasure</span>
                        Request deletion of your data
                    </li>
                    <li>
                        <span class="rights-badge"><i class="fas fa-download"></i> Portability</span>
                        Receive your data in a machine-readable format
                    </li>
                    <li>
                        <span class="rights-badge"><i class="fas fa-ban"></i> Restriction</span>
                        Limit how we process your data
                    </li>
                    <li>
                        <span class="rights-badge"><i class="fas fa-times-circle"></i> Objection</span>
                        Object to certain processing activities
                    </li>
                    <li>
                        <span class="rights-badge"><i class="fas fa-undo"></i> Withdraw Consent</span>
                        Withdraw previously given consent
                    </li>
                </ul>
                <p>To exercise these rights, contact us using the information provided below. We will respond within 30 days.</p>
            </section>

            <section class="section" id="cookies">
                <h2>8. Cookies and Tracking</h2>
                <p>We use cookies and similar technologies to:</p>
                <ul>
                    <li>Keep you logged in to your account</li>
                    <li>Remember your preferences</li>
                    <li>Understand how you use our platform</li>
                    <li>Improve our services</li>
                </ul>
                
                <h3>Types of Cookies We Use</h3>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Cookie Type</th>
                            <th>Purpose</th>
                            <th>Duration</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Essential</td>
                            <td>Required for platform functionality</td>
                            <td>Session</td>
                        </tr>
                        <tr>
                            <td>Authentication</td>
                            <td>Keep you logged in</td>
                            <td>30 days</td>
                        </tr>
                        <tr>
                            <td>Preferences</td>
                            <td>Remember your settings</td>
                            <td>1 year</td>
                        </tr>
                        <tr>
                            <td>Analytics</td>
                            <td>Understand usage patterns</td>
                            <td>2 years</td>
                        </tr>
                    </tbody>
                </table>
                <p>You can control cookies through your browser settings, but disabling certain cookies may affect platform functionality.</p>
            </section>

            <section class="section" id="third-party">
                <h2>9. Third-Party Services</h2>
                <p>Our platform may contain links to third-party websites and integrate with external services. We are not responsible for the privacy practices of these third parties. We encourage you to review their privacy policies.</p>
                
                <h3>Key Third-Party Integrations</h3>
                <ul>
                    <li><strong>CUG Admissions System:</strong> Student verification</li>
                    <li><strong>Payment Gateways:</strong> MTN Mobile Money, Vodafone Cash, bank APIs</li>
                    <li><strong>Google Services:</strong> Maps, Analytics</li>
                </ul>
            </section>

            <section class="section" id="children">
                <h2>10. Children's Privacy</h2>
                <p>Priority Accommodations is not intended for children under 18 years of age. We do not knowingly collect personal information from children. If we discover that a child has provided us with personal information, we will delete it immediately.</p>
                <p>If you are a parent or guardian and believe your child has provided us with personal information, please contact us.</p>
            </section>

            <section class="section" id="changes">
                <h2>11. Changes to This Policy</h2>
                <p>We may update this Privacy Policy from time to time. We will notify you of significant changes by:</p>
                <ul>
                    <li>Posting the updated policy on our platform</li>
                    <li>Updating the "Last Updated" date</li>
                    <li>Sending an email notification for material changes</li>
                </ul>
                <p>Your continued use of our services after changes constitutes acceptance of the updated policy.</p>
            </section>

            <section class="section" id="contact">
                <h2>12. Contact Us</h2>
                <p>If you have questions, concerns, or requests regarding this Privacy Policy or your personal data, please contact us:</p>
                <div class="contact-info">
                    <h4><i class="fas fa-user-shield"></i> Data Protection Officer</h4>
                    <p><strong>Priority Accommodations</strong></p>
                    <p><i class="fas fa-map-marker-alt"></i> Catholic University of Ghana, Fiapre, Sunyani</p>
                    <p><i class="fas fa-envelope"></i> privacy@priorityaccommodations.com</p>
                    <p><i class="fas fa-phone"></i> +233 XX XXX XXXX</p>
                    <p><i class="fas fa-clock"></i> Monday - Friday: 8:00 AM - 5:00 PM GMT</p>
                </div>
                
                <div class="highlight-box" style="margin-top: 1.5rem;">
                    <strong>Response Time:</strong> We aim to respond to all privacy-related inquiries within 30 days. For urgent matters, please call us directly.
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

