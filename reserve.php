<?php
session_start();
include 'includes/header.php';

$old = $_SESSION['old_reservation'] ?? [];
$errors = $_SESSION['reservation_errors'] ?? [];

unset($_SESSION['old_reservation']);
unset($_SESSION['reservation_errors']);
?>

<section class="page-header">
    <div class="container">
        <h1>Reserveer een tafel</h1>
        <p>Vul het formulier in om een reservering te maken.</p>
    </div>
</section>

<section class="section">
    <div class="container form-container">
        <?php if (isset($_GET['success'])): ?>
            <div class="success-message">Je reservering is succesvol opgeslagen.</div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="error-message">
                <ul style="margin: 0; padding-left: 20px;">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="process_reservation.php" method="POST" id="reservationForm" class="form-card">
            <div class="form-group">
                <label for="name">Naam</label>
                <input type="text" name="name" id="name" required placeholder="Rooney Zakko" value="<?php echo htmlspecialchars($old['name'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="phone">Telefoonnummer</label>
                <input type="text" name="phone" id="phone" required placeholder="06 12345678" value="<?php echo htmlspecialchars($old['phone'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="email">E-mailadres</label>
                <input type="email" name="email" id="email" required placeholder="labellatavola@example.com" value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="reservation_date">Datum</label>
                <input type="date" name="reservation_date" id="reservation_date" required value="<?php echo htmlspecialchars($old['reservation_date'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="reservation_time">Tijd</label>
                <input type="time" name="reservation_time" id="reservation_time" required value="<?php echo htmlspecialchars($old['reservation_time'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="guests">Aantal personen</label>
                <input type="number" name="guests" id="guests" min="1" max="20" required placeholder="2" value="<?php echo htmlspecialchars((string)($old['guests'] ?? '')); ?>">
            </div>

            <div class="form-group full-width">
                <label for="notes">Opmerkingen</label>
                <textarea name="notes" id="notes" rows="4" placeholder="Ik heb een allergie, dus ik kan dit niet eten."><?php echo htmlspecialchars($old['notes'] ?? ''); ?></textarea>
            </div>

            <div class="form-group full-width">
                <button type="submit" class="btn">Reservering opslaan</button>
            </div>
        </form>
    </div>
</section>

<?php include 'includes/footer.php'; ?>