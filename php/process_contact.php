<?php
/**
 * Contact Form Handler
 * Processes contact form submissions from contact.php
 * Includes validation, sanitization, and email sending functionality
 * 
 * @author Football Agent SL Team
 * @version 1.0
 */

// Start session for CSRF protection
session_start();

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../contact.php?error=invalid_method');
    exit();
}

// CSRF Token Validation
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header('Location: ../contact.php?error=csrf_token');
    exit();
}

// Initialize variables
$errors = [];
$success_message = '';

// Validate and sanitize input data
$name = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name']), ENT_QUOTES, 'UTF-8') : '';
$email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
$phone = isset($_POST['phone']) ? htmlspecialchars(trim($_POST['phone']), ENT_QUOTES, 'UTF-8') : '';
$subject = isset($_POST['subject']) ? htmlspecialchars(trim($_POST['subject']), ENT_QUOTES, 'UTF-8') : '';
$message = isset($_POST['message']) ? htmlspecialchars(trim($_POST['message']), ENT_QUOTES, 'UTF-8') : '';

// Validation rules
if (empty($name)) {
    $errors[] = 'Name is required';
} elseif (strlen($name) < 2) {
    $errors[] = 'Name must be at least 2 characters';
}

if (empty($email)) {
    $errors[] = 'Email is required';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address';
}

if (empty($message)) {
    $errors[] = 'Message is required';
} elseif (strlen($message) < 10) {
    $errors[] = 'Message must be at least 10 characters';
}

// Phone validation (optional but if provided, must be valid)
if (!empty($phone) && !preg_match('/^[\d\s\-\+\(\)]+$/', $phone)) {
    $errors[] = 'Please enter a valid phone number';
}

// If there are errors, redirect back with error messages
if (!empty($errors)) {
    $error_string = urlencode(implode(', ', $errors));
    header('Location: ../contact.php?error=' . $error_string);
    exit();
}

// Prepare email data
$to = 'nelson@footballagent.sl';
$from = $email;
$subject_prefix = 'Football Agent Contact: ';
$full_subject = $subject_prefix . ucfirst($subject);

// Email body template
$email_body = "
You have received a new contact form submission from Football Agent SL website.

CONTACT DETAILS:
================
Name: $name
Email: $email
Phone: " . (!empty($phone) ? $phone : 'Not provided') . "
Subject: $subject

MESSAGE:
================
$message

================
Sent on: " . date('Y-m-d H:i:s') . "
IP Address: " . $_SERVER['REMOTE_ADDR'] . "
";

// Email headers
$headers = [
    'From: ' . $name . ' <' . $from . '>',
    'Reply-To: ' . $from,
    'Return-Path: ' . $from,
    'X-Mailer: PHP/' . phpversion(),
    'Content-Type: text/plain; charset=UTF-8'
];

// Attempt to send email
try {
    $mail_sent = mail($to, $full_subject, $email_body, implode("\r\n", $headers));
    
    if ($mail_sent) {
        // Log successful submission
        error_log("Contact form submission successful from: $email");
        
        // Redirect with success message
        header('Location: ../contact.php?success=1');
        exit();
    } else {
        throw new Exception('Email sending failed');
    }
    
} catch (Exception $e) {
    // Log error
    error_log("Contact form error: " . $e->getMessage());
    
    // Redirect with error message
    header('Location: ../contact.php?error=email_failed');
    exit();
}

// If we reach here, something went wrong
header('Location: ../contact.php?error=unknown');
exit();
?>