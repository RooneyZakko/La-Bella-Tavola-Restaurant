<?php
session_start();
include 'includes/header.php';

$old = $_SESSION['old_contact'] ?? [];
$errors = $_SESSION['contact_errors'] ?? [];

unset($_SESSION['old_contact']);
unset($_SESSION['contact_errors']);
?>

<section class="page-header">
    <div class="container">
        <h1>Contact</h1>
        <p>Neem contact met ons op voor vragen of speciale verzoeken.</p>
    </div>
</section>

<section class="section">
    <div class="container contact-grid">
        <div class="contact-info">
            <h2>Contactgegevens</h2>
            <p><strong>Adres:</strong> Stationsstraat 10, Tilburg</p>
            <p><strong>Telefoon:</strong> 013-1234567</p>
            <p><strong>E-mail:</strong> info@labellatavola.nl</p>

            <h3>Openingstijden</h3>
            <p>Maandag t/m donderdag: 12:00 - 22:00</p>
            <p>Vrijdag en zaterdag: 12:00 - 23:00</p>
            <p>Zondag: 13:00 - 21:00</p>

            <h3>Locatie</h3>
            <p>Gelegen in het centrum van Tilburg, makkelijk bereikbaar met auto en openbaar vervoer.</p>
        </div>

        <div class="contact-form-wrapper">
            <?php if (isset($_GET['success'])): ?>
                <div class="success-message">Je bericht is succesvol verzonden.</div>
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

            <form action="process_contact.php" method="POST" id="contactForm" class="form-card">
                <div class="form-group">
                    <label for="contact_name">Naam</label>
                    <input type="text" name="contact_name" id="contact_name" required value="<?php echo htmlspecialchars($old['contact_name'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="contact_email">E-mail</label>
                    <input type="email" name="contact_email" id="contact_email" required value="<?php echo htmlspecialchars($old['contact_email'] ?? ''); ?>">
                </div>

                <div class="form-group full-width">
                    <label for="contact_subject">Onderwerp</label>
                    <input type="text" name="contact_subject" id="contact_subject" required value="<?php echo htmlspecialchars($old['contact_subject'] ?? ''); ?>">
                </div>

                <div class="form-group full-width">
                    <label for="contact_message">Bericht</label>
                    <textarea name="contact_message" id="contact_message" rows="5" required><?php echo htmlspecialchars($old['contact_message'] ?? ''); ?></textarea>
                </div>

                <div class="form-group full-width">
                    <button type="submit" class="btn">Verstuur bericht</button>
                </div>
            </form>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>