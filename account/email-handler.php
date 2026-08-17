<?php



function renderEmailTemplate($templatePath, $data = [])
{
    // Load template
    $template = file_get_contents($templatePath);

    // Replace variables
    foreach ($data as $key => $value) {
        $template = str_replace('{{' . $key . '}}', $value, $template);
    }

    // Remove unused placeholders (optional cleanup)
    $template = preg_replace('/{{(.*?)}}/', '', $template);

    return $template;
}



function sendVerificationEmail($email, $name, $verificationLink)
{
    $data = [
        'subject' => 'Verify Your Email Address',
        'body_email' => "Thank you for choosing to join Quantum Scalp. We sincerely appreciate your decision to place your trust in us as your investment partner.",
        'body_email_2' => "As part of our standard onboarding process, we will be conducting a background verification to review the information and documentation you have submitted. This step is essential in maintaining the security, compliance, and integrity of our investment community, helping to protect both our existing clients and new partners like yourself.Once the verification process has been completed and your account has been fully approved, you will be promptly notified.<br/><br/>We appreciate your patience and cooperation throughout this process and look forward to officially welcoming you as an active investor.",
        'title' => $name,
        'action_link' => $verificationLink,
        'action_btn' => 'Verify Email'
    ];

    $html = renderEmailTemplate('email_template.html', $data);

    return sendEmail($email, $data['subject'], $html);
}

function sendAdminNotificationRegistration($admins = [], $name, $userEmail)
{
    $data = [
        'subject' => 'Account Registration',
        'body_email' => "A new user has just registered on Quantum Scalp.",
        'body_email_2' => "
            <strong>Name:</strong> {$name}<br>
            <strong>Email:</strong> {$userEmail}<br><br>
            Please review and verify this account from the admin dashboard.
        ",
        'title' => 'Admin',
        'action_link' => 'https://quantumscalp.io/account/board/',
        'action_btn' => 'Go to Admin Panel'
    ];

    $html = renderEmailTemplate(__DIR__ . '/email_template.html', $data);

    $results = [];

    foreach ($admins as $adminEmail) {
        $results[] = sendEmail($adminEmail, $data['subject'], $html);
    }

    return $results;
}


function sendAdminNotificationPayment($admins = [], $package, $amount, $fullName, $userEmail)
{
    $data = [
        'subject' => 'Payment Alert',
        'body_email' => "A new user has just registered on Quantum Scalp.",
        'body_email_2' => "
            <strong>Package:</strong> {$package}<br>
            <strong>Amount:</strong> {$amount}<br>
            <strong>Email:</strong> {$userEmail}<br>
            <strong>Full name:</strong> {$fullName}<br><br>
            Please review and verify this account from the admin dashboard.
        ",
        'title' => 'Admin',
        'action_link' => 'https://quantumscalp.io/account/board/',
        'action_btn' => 'Go to Admin Panel'
    ];

    $html = renderEmailTemplate(__DIR__ . '/email_template.html', $data);

    $results = [];

    foreach ($admins as $adminEmail) {
        $results[] = sendEmail($adminEmail, $data['subject'], $html);
    }

    return $results;
}


function sendWelcomeEmail($email)
{
    $data = [
        'subject' => 'Welcome to Quantum Scalp',
        'body_email' => "On behalf of Quantum Scalp, it is my pleasure to warmly welcome you to our distinguished investment community. We are truly grateful for the confidence you have placed in us and are excited to begin this journey together.<br/><br/>At Quantum Scalp, we recognize that selecting the right investment partner is a meaningful decision—one that plays a vital role in shaping your financial future. Your trust in our firm is something we deeply value, and we are committed to delivering exceptional service, disciplined investment strategies, and opportunities designed to help you achieve sustainable growth, passive income, and long-term financial independence.<br/><br/>Integrity, transparency, and performance are the cornerstones of our approach. Over the years, we have earned the trust of our investors through consistent results and a steadfast commitment to excellence. Our experienced team brings deep market insight and a rigorous, research-driven mindset to every decision, ensuring your portfolio is managed with care, precision, and diligence.<br/><br/>As a partner with Quantum Scalp, you can expect:<br/><br/><b>Consistency and Strength</b><br/>Our strategies are built to deliver reliable, risk-conscious returns while identifying opportunities for meaningful growth.<br/><br/><b>Tailored Investment Solutions</b> <br/>We take the time to understand your individual goals, preferences, and risk tolerance, crafting a personalized investment plan aligned with your vision.<br/><br/>",
        'body_email_2' => "<b>In-Depth Market Research</b><br/>Our analysts continuously evaluate market trends and opportunities across sectors to support well-informed, forward-looking investment decisions. <br/><br/><b>Dedicated Client Support</b> <br/>We prioritize long-term relationships. Our team remains accessible to provide updates, answer questions, and offer guidance whenever you need it. <br/><br/><b>Transparency and Trust</b><br/>Clear communication is central to our philosophy. You will receive regular updates and reporting so you always have a full understanding of your portfolio’s performance.<br/>At Quantum Scalp, your success is our mission. We are honored to support your financial journey and look forward to building a strong and lasting partnership.<br/>Once again, welcome. Should you have any questions or require assistance, please don’t hesitate to reach out. We are here to ensure your experience with us is both rewarding and seamless.",
        'title' => 'Valued Investor',
        'item_1' => 'Explore your dashboard',
        'action_link' => 'https://quantumscalp.io/account/dashboard',
        'action_btn' => 'Go to Dashboard'
    ];

    $html = renderEmailTemplate('email_template.html', $data);

    return sendEmail($email, $data['subject'], $html);
}


function sendForgotPasswordEmail($email,  $resetLink)
{
    $data = [
        'subject' => 'Reset Your Password',
        'body_email' => "Dear User,<br/><br/>We received a request to reset your password. If you did not make this request, please ignore this email.<br/><br/>To reset your password, click the button below:",
        'body_email_2' => "If you have any questions or need assistance, please don't hesitate to contact our support team.",
        'item_1' => 'Reset your password',
         'title' => 'Valued Investor',
        'action_link' => $resetLink,
        'action_btn' => 'Reset Password'
    ];

    $html = renderEmailTemplate('email_template.html', $data);

    return sendEmail($email, $data['subject'], $html);
}


function sendWithdrawalEmail($email, $wallet, $amount, $method, $date  ){
        $data = [
        'subject' => 'Withdrawal Notification',
        'body_email' => "Your withdrawal request has been sent successfully.",
        'body_email_2' => "
            <strong>Wallet:</strong> {$wallet}<br>
            <strong>Amount:</strong> {$amount}<br>
            <strong>Date:</strong> {$date}<br>
            <strong>Method:</strong> {$method}<br><br>
            ",

        'title' => 'Valued Investor',
        'action_link' => 'https://quantumscalp.io/account',
        'action_btn' => 'Sign In'
    ];

    $html = renderEmailTemplate('email_template.html', $data);

    return sendEmail($email, $data['subject'], $html);
}



function sendInvoiceEmail($email, $name, $wallet, $orderId, $date  ){
        $data = [
        'subject' => 'Order Generated',
        'body_email' => "Order Generated, View details below:",
        'body_email_2' => "
            <strong>Package:</strong> {$name}<br>
            <strong>Wallet:</strong> {$wallet}<br>
            <strong>Date:</strong> {$date}<br>
            <strong>Invoice Id:</strong> {$orderId}<br><br>
            
            ",

        'title' => 'Valued Investor',
        'action_link' => 'https://quantumscalp.io/account',
        'action_btn' => 'Sign In'
    ];

    $html = renderEmailTemplate('email_template.html', $data);

    return sendEmail($email, $data['subject'], $html);
}



function sendPaymentEmail($email, $body){
        $data = [
        'subject' => 'Order Completed',
        'body_email' => $body,
       

        'title' => 'Valued Investor',
        'action_link' => 'https://quantumscalp.io/account',
        'action_btn' => 'Sign In'
    ];

    $html = renderEmailTemplate('email_template.html', $data);

    return sendEmail($email, $data['subject'], $html);
}




function sendInvitationEmail($email,  $subject, $body_email, $body_email_2, $inviteLink)
{
    $data = [
        'subject' => $subject,
        'body_email' => $body_email,
        'body_email_2' => $body_email_2,
        'item_1' => 'Invitation to join Quantum Scalp',
        'action_link' => $inviteLink,
        'action_btn' => 'Accept Invitation'
    ];

    $html = renderEmailTemplate('email_template.html', $data);

    return sendEmail($email, $data['subject'], $html);
}

   


function sendAdminNotificationWithdrawal($admins = [], $name, $userEmail, $amount, $currency, $walletAddress)
{
    $data = [
        'subject' => 'Withdrawal Request Notification',
        'body_email' => "A withdrawal request has been submitted by a user.",
        'body_email_2' => "
            <strong>Name:</strong> {$name}<br>
            <strong>Email:</strong> {$userEmail}<br><br>
            <strong>Amount:</strong> {$amount}<br><br>
            <strong>Currency:</strong> {$currency}<br><br>
            <strong>Wallet Address:</strong> {$walletAddress}<br><br>
            Please review and process this withdrawal request from the admin dashboard.
        ",
        'item_1' => 'Withdrawal request requires review',
        'action_link' => 'https://quantumscalp.io/account/board/',
        'action_btn' => 'Go to Admin Panel'
    ];

    $html = renderEmailTemplate(__DIR__ . '/email_template.html', $data);

    $results = [];

    foreach ($admins as $adminEmail) {
        $results[] = sendEmail($adminEmail, $data['subject'], $html);
    }

    return $results;
}


function sendEmail($to, $subject, $htmlContent)
{
    $curl = curl_init();

    // Mailjet API endpoint
    $url = "https://api.mailjet.com/v3.1/send";

    // Your Mailjet API credentials (Base64 encoded)
    $apiKey = "NjIwMjNlMDUxZDlhNzMzNzU4MGY1NWU5OGZiMjczM2E6MzRmZmNjZjgxZDhmMDFjNDcwNzE1NjMwYzMyODhiZjE=";

    // Prepare the payload with Mailjet's structure
    $payload = json_encode([
        "SandboxMode" => false,
        "Messages" => [
            [
                "From" => [
                    "Email" => "info@quantumscalp.io",
                    "Name" => "Quantum Scalp"
                ],
                "To" => [
                    [
                        "Email" => $to,
                        "Name" => ""
                    ]
                ],
                "Subject" => $subject,
                "HTMLPart" => $htmlContent,
                "TextPart" => strip_tags($htmlContent), // Fallback text version
                "TemplateLanguage" => true,
                "TrackOpens" => "account_default",
                "TrackClicks" => "account_default"
            ]
        ]
    ]);

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Authorization: Basic " . $apiKey
        ]
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    curl_close($curl);

    // Log or debug the response
    if ($err) {
        error_log("Mailjet cURL Error: " . $err);
        return [
            'status' => false,
            'error' => $err
        ];
    }

    $decodedResponse = json_decode($response, true);

    // Check if the email was sent successfully
    if ($httpCode >= 200 && $httpCode < 300) {
        return [
            'status' => true,
            'response' => $decodedResponse
        ];
    } else {
        error_log("Mailjet API Error: " . $response);
        return [
            'status' => false,
            'error' => $decodedResponse['ErrorMessage'] ?? 'Unknown error',
            'httpCode' => $httpCode,
            'response' => $decodedResponse
        ];
    }
}

?>