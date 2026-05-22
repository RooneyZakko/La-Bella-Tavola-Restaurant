<?php
require_once 'config/db.php';
require_once 'includes/functions.php';

if (!isPost()) {
    redirect('contact.php');
}

$name = sanitize($_POST['contact_name'] ?? '');
$email = sanitize($_POST['contact_email'] ?? '');
$subject = sanitize($_POST['contact_subject'] ?? '');
$message = sanitize($_POST['contact_message'] ?? '');

$errors = [];

if (empty($name)) $errors[] = "Naam is verplicht.";
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Ongeldig e-mailadres.";
if (empty($subject)) $errors[] = "Onderwerp is verplicht.";
if (empty($message)) $errors[] = "Bericht is verplicht.";

if (!empty($errors)) {
    redirect('contact.php?error=1');
}

$sql = "INSERT INTO contact_messages (name, email, subject, message)
        VALUES (:name, :email, :subject, :message)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':name' => $name,
    ':email' => $email,
    ':subject' => $subject,
    ':message' => $message
]);

redirect('contact.php?success=1');
?>