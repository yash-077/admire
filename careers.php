<?php
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$name = $_POST["name"];
$phone = $_POST["mobile"];
$email = $_POST["email"];
$experience = $_POST["experience"];
$details = $_POST["coverLetter"];
$resume = $_FILES["resume"]["tmp_name"];
$resumeName = $_FILES["resume"]["name"];

// Ensure all fields are filled
if (empty($name) || empty($phone) || empty($email) || empty($experience) || empty($resume)) {
    die('<center><h1>All fields are required. Please try again!</h1></center>');
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die('<center><h1>Invalid email format. Please try again!</h1></center>');
}

// Configure PHPMailer
$mail = new PHPMailer(true);

try {
    // SMTP configuration
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'your-email@gmail.com'; // Replace with your Gmail address
    $mail->Password = 'your-email-password'; // Replace with your Gmail app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Email details
    $mail->setFrom($email, $name); // Sender's email and name from the form
    $mail->addAddress('recipient-email@gmail.com', 'HR Team'); // Replace with the recipient email

    $mail->Subject = 'Job Application: ' . $name;
    $mail->isHTML(true);
    $mail->Body = "
        <h2>Job Application Details</h2>
        <ul>
            <li><strong>Name:</strong> {$name}</li>
            <li><strong>Phone:</strong> {$phone}</li>
            <li><strong>Email:</strong> {$email}</li>
            <li><strong>Experience:</strong> {$experience} years</li>
            <li><strong>Details:</strong> {$details}</li>
        </ul>
        <p>Resume is attached below.</p>
    ";

    // Attach the resume
    $mail->addAttachment($resume, $resumeName);

    // Send email
    if ($mail->send()) {
        echo '<center><h1>Thanks! Your application has been submitted successfully.</h1></center>';
    } else {
        echo '<center><h1>Error sending email. Please try again later.</h1></center>';
    }
} catch (Exception $e) {
    echo '<center><h1>Error: ' . $mail->ErrorInfo . '</h1></center>';
}
?>
