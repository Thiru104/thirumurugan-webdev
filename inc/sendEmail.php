<?php

// Replace this with your own email address
$siteOwnersEmail = 'thirumurugan1042@gmail.com';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim(stripslashes($_POST['contactName']));
    $email = trim(stripslashes($_POST['contactEmail']));
    $subject = trim(stripslashes($_POST['contactSubject']));
    $contact_message = trim(stripslashes($_POST['contactMessage']));

    $errors = [];

    // Validate Name
    if (strlen($name) < 2) {
        $errors['name'] = "Please enter your name.";
    }

    // Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Please enter a valid email address.";
    }

    // Validate Message
    if (strlen($contact_message) < 15) {
        $errors['message'] = "Please enter a message with at least 15 characters.";
    }

    // Set default subject if empty
    if (empty($subject)) {
        $subject = "Contact Form Submission";
    }

    // If no errors, send email
    if (empty($errors)) {
        $message = "Email from: " . htmlspecialchars($name) . "<br />";
        $message .= "Email address: " . htmlspecialchars($email) . "<br />";
        $message .= "Message: <br />";
        $message .= nl2br(htmlspecialchars($contact_message));
        $message .= "<br /> ----- <br /> This email was sent from your site's contact form.";

        // Email Headers
        $headers = "From: " . htmlspecialchars($name) . " <" . $email . ">\r\n";
        $headers .= "Reply-To: " . $email . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        if (mail($siteOwnersEmail, $subject, $message, $headers)) {
            echo json_encode(['status' => 'OK', 'message' => 'Your message was sent successfully!']);
        } else {
            echo json_encode(['status' => 'ERROR', 'message' => 'Something went wrong. Please try again.']);
        }
    } else {
        // Return errors in JSON format
        echo json_encode(['status' => 'ERROR', 'errors' => $errors]);
    }
}
?>
