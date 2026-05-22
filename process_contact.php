<?php
session_start();

require_once 'config/db.php';
require_once 'includes/functions.php';
require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isPost()) {
    redirect('contact.php');
}

$name = sanitize($_POST['contact_name'] ?? '');
$email = sanitize($_POST['contact_email'] ?? '');
$subject = sanitize($_POST['contact_subject'] ?? '');
$message = sanitize($_POST['contact_message'] ?? '');

// Oude invoer bewaren
$_SESSION['old_contact'] = [
    'contact_name' => $name,
    'contact_email' => $email,
    'contact_subject' => $subject,
    'contact_message' => $message
];

$errors = [];

// Validatie
if (empty($name)) {
    $errors[] = 'Naam is verplicht.';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Voer een geldig e-mailadres in.';
}

if (empty($subject)) {
    $errors[] = 'Onderwerp is verplicht.';
}

if (empty($message)) {
    $errors[] = 'Bericht is verplicht.';
}

if (strlen($message) < 5) {
    $errors[] = 'Bericht is te kort.';
}

if (!empty($errors)) {
    $_SESSION['contact_errors'] = $errors;
    redirect('contact.php');
}

// Bericht opslaan in database
$sql = "INSERT INTO contact_messages (name, email, subject, message)
        VALUES (:name, :email, :subject, :message)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':name' => $name,
    ':email' => $email,
    ':subject' => $subject,
    ':message' => $message
]);

// Mail naar restaurant sturen
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'neym759@gmail.com';
    $mail->Password   = 'ykmwhjuwueydmlvi';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;
    $mail->CharSet    = 'UTF-8';

    // Alleen voor lokaal testen
    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        ]
    ];

    $mail->setFrom('neym759@gmail.com', 'La Bella Tavola Website');
    $mail->addAddress('neym759@gmail.com', 'La Bella Tavola'); // restaurant ontvangt bericht
    $mail->addReplyTo($email, $name);

    $mail->isHTML(true);
    $mail->Subject = 'Nieuw contactbericht: ' . $subject;

    $mail->Body = "
        <h2>Nieuw contactbericht via de website</h2>
        <p><strong>Naam:</strong> " . htmlspecialchars($name) . "</p>
        <p><strong>E-mail:</strong> " . htmlspecialchars($email) . "</p>
        <p><strong>Onderwerp:</strong> " . htmlspecialchars($subject) . "</p>
        <p><strong>Bericht:</strong><br>" . nl2br(htmlspecialchars($message)) . "</p>
    ";

    $mail->AltBody = "Nieuw contactbericht via de website

Naam: $name
E-mail: $email
Onderwerp: $subject

Bericht:
$message";

    $mail->send();

} catch (Exception $e) {
    // Mail mag mislukken zonder dat het contactbericht verloren gaat
}

// Optioneel: bevestigingsmail naar bezoeker
$confirmationMail = new PHPMailer(true);

try {
    $confirmationMail->isSMTP();
    $confirmationMail->Host       = 'smtp.gmail.com';
    $confirmationMail->SMTPAuth   = true;
    $confirmationMail->Username   = 'neym759@gmail.com';
    $confirmationMail->Password   = 'ykmwhjuwueydmlvi';
    $confirmationMail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $confirmationMail->Port       = 465;
    $confirmationMail->CharSet    = 'UTF-8';

    $confirmationMail->SMTPOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        ]
    ];

    $confirmationMail->setFrom('neym759@gmail.com', 'La Bella Tavola');
    $confirmationMail->addAddress($email, $name);

    $confirmationMail->isHTML(true);
    $confirmationMail->Subject = 'Bevestiging van uw contactbericht';

    $confirmationMail->Body = "
        <h2>Beste " . htmlspecialchars($name) . ",</h2>
        <p>Bedankt voor uw bericht aan <strong>La Bella Tavola</strong>.</p>
        <p>Wij hebben uw bericht goed ontvangen en nemen zo snel mogelijk contact met u op.</p>
        <p><strong>Onderwerp:</strong> " . htmlspecialchars($subject) . "</p>
        <p>Met vriendelijke groet,<br>La Bella Tavola</p>
    ";

    $confirmationMail->AltBody = "Beste $name,

Bedankt voor uw bericht aan La Bella Tavola.
Wij hebben uw bericht goed ontvangen en nemen zo snel mogelijk contact met u op.

Onderwerp: $subject

Met vriendelijke groet,
La Bella Tavola";

    $confirmationMail->send();

} catch (Exception $e) {
    // Ook deze fout mag genegeerd worden
}

// Opruimen na succes
unset($_SESSION['old_contact']);
unset($_SESSION['contact_errors']);

redirect('contact.php?success=1');
?>