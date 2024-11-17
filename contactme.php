<?php
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Validate form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $mobile = $_POST["mobile"];
    $email = $_POST["email"];
    $message = $_POST["message"];

    // Validate all required fields
    if (empty($name) || empty($mobile) || empty($email) || empty($message)) {
        die('<center><h1>All fields are required. Please try again!</h1></center>');
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('<center><h1>Invalid email format. Please provide a valid email address.</h1></center>');
    }

    // PHPMailer configuration
    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'your-email@gmail.com'; // Your Gmail address
        $mail->Password = 'your-app-password';   // Gmail app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email details
        $mail->setFrom($email, $name); // Sender (from user)
        $mail->addAddress('recipient-email@gmail.com', 'Recipient Name'); // Replace with the recipient's email

        $mail->Subject = 'New Message from Contact Form';
        $mail->isHTML(true);

        // Email body
        $mail->Body = "
            <h2>Contact Form Details</h2>
            <ul>
                <li><strong>Name:</strong> {$name}</li>
                <li><strong>Email:</strong> {$email}</li>
                <li><strong>Mobile:</strong> {$mobile}</li>
                <li><strong>Message:</strong> {$message}</li>
            </ul>
        ";

        // Send email
        if ($mail->send()) {
            echo '<center><h1>Thanks! We will contact you soon.</h1></center>';
        } else {
            echo '<center><h1>Error sending email. Please try again later.</h1></center>';
        }
    } catch (Exception $e) {
        echo '<center><h1>Error: ' . $mail->ErrorInfo . '</h1></center>';
    }
} else {
    die('<center><h1>Invalid request method!</h1></center>');
}
?>
