<?php
session_start();

require_once 'config/db.php';
require_once 'includes/functions.php';
require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isPost()) {
    redirect('reserve.php');
}

$name = sanitize($_POST['name'] ?? '');
$phone = sanitize($_POST['phone'] ?? '');
$email = sanitize($_POST['email'] ?? '');
$reservation_date = $_POST['reservation_date'] ?? '';
$reservation_time = $_POST['reservation_time'] ?? '';
$guests = (int)($_POST['guests'] ?? 0);
$notes = sanitize($_POST['notes'] ?? '');

// Bewaar oude invoer tijdelijk
$_SESSION['old_reservation'] = [
    'name' => $name,
    'phone' => $phone,
    'email' => $email,
    'reservation_date' => $reservation_date,
    'reservation_time' => $reservation_time,
    'guests' => $guests,
    'notes' => $notes
];

$errors = [];

// Validatie
if (empty($name)) {
    $errors[] = 'Naam is verplicht.';
}

if (empty($phone)) {
    $errors[] = 'Telefoonnummer is verplicht.';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Voer een geldig e-mailadres in.';
}

if (empty($reservation_date)) {
    $errors[] = 'Datum is verplicht.';
}

if (empty($reservation_time)) {
    $errors[] = 'Tijd is verplicht.';
}

if ($guests < 1) {
    $errors[] = 'Aantal personen moet minimaal 1 zijn.';
}

if (!empty($reservation_date) && strtotime($reservation_date) < strtotime(date('Y-m-d'))) {
    $errors[] = 'De reserveringsdatum mag niet in het verleden liggen.';
}

// Bij fouten terugsturen met behoud van gegevens
if (!empty($errors)) {
    $_SESSION['reservation_errors'] = $errors;
    redirect('reserve.php');
}

// Reservering opslaan in database
$sql = "INSERT INTO reservations 
        (name, phone, email, reservation_date, reservation_time, guests, notes)
        VALUES 
        (:name, :phone, :email, :reservation_date, :reservation_time, :guests, :notes)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':name' => $name,
    ':phone' => $phone,
    ':email' => $email,
    ':reservation_date' => $reservation_date,
    ':reservation_time' => $reservation_time,
    ':guests' => $guests,
    ':notes' => $notes
]);

// Bevestigingsmail sturen
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

    $mail->setFrom('neym759@gmail.com', 'La Bella Tavola');
    $mail->addAddress($email, $name);

    $mail->isHTML(true);
    $mail->Subject = 'Bevestiging van uw reservering';

    $mail->Body = "
        <h2>Beste " . htmlspecialchars($name) . ",</h2>
        <p>Bedankt voor uw reservering bij <strong>La Bella Tavola</strong>.</p>
        <p>Hieronder vindt u uw reserveringsgegevens:</p>
        <ul>
            <li><strong>Datum:</strong> " . htmlspecialchars($reservation_date) . "</li>
            <li><strong>Tijd:</strong> " . htmlspecialchars($reservation_time) . "</li>
            <li><strong>Aantal personen:</strong> " . htmlspecialchars((string)$guests) . "</li>
        </ul>
        <p><strong>Opmerkingen:</strong> " . (!empty($notes) ? htmlspecialchars($notes) : 'Geen') . "</p>
        <p>Wij kijken uit naar uw komst.</p>
        <p>Met vriendelijke groet,<br>La Bella Tavola</p>
    ";

    $mail->AltBody = "Beste $name,

Bedankt voor uw reservering bij La Bella Tavola.

Reserveringsgegevens:
- Datum: $reservation_date
- Tijd: $reservation_time
- Aantal personen: $guests
- Opmerkingen: " . (!empty($notes) ? $notes : 'Geen') . "

Wij kijken uit naar uw komst.

Met vriendelijke groet,
La Bella Tavola";

    $mail->send();

} catch (Exception $e) {
    // Mail mag mislukken zonder dat reservering verloren gaat
}

// Opruimen na succes
unset($_SESSION['old_reservation']);
unset($_SESSION['reservation_errors']);

redirect('reserve.php?success=1');
?>