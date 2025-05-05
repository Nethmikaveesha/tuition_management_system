<?php
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';
    
    // Validate form data
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    if (empty($subject)) {
        $errors[] = "Subject is required";
    }
    
    if (empty($message)) {
        $errors[] = "Message is required";
    }
    
    // If there are no errors, send the email
    if (empty($errors)) {
        // Email recipient
        $to = "info@eduplatform.com";
        
        // Email headers
        $headers = "From: $email" . "\r\n";
        $headers .= "Reply-To: $email" . "\r\n";
        $headers .= "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
        
        // Email content
        $email_content = "
            <html>
            <head>
                <title>New Contact Form Submission</title>
            </head>
            <body>
                <h2>New Contact Form Submission</h2>
                <p><strong>Name:</strong> $name</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Subject:</strong> $subject</p>
                <p><strong>Message:</strong></p>
                <p>$message</p>
            </body>
            </html>
        ";
        
        // Send email
        $mail_sent = mail($to, "Contact Form: $subject", $email_content, $headers);
        
        if ($mail_sent) {
            // Redirect back to contact page with success message
            header("Location: contact.html?status=success");
            exit;
        } else {
            // Redirect back to contact page with error message
            header("Location: contact.html?status=error");
            exit;
        }
    } else {
        // Redirect back to contact page with validation errors
        $error_string = implode(",", $errors);
        header("Location: contact.html?status=validation&errors=" . urlencode($error_string));
        exit;
    }
} else {
    // If not a POST request, redirect to contact page
    header("Location: contact.html");
    exit;
}
?> 