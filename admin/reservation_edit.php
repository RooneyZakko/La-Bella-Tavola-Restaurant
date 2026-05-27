<?php
require_once '../includes/auth.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    redirect('reservations.php');
}

$stmt = $pdo->prepare("SELECT * FROM reservations WHERE id = :id");
$stmt->execute([':id' => $id]);
$reservation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reservation) {
    redirect('reservations.php');
}

if (isPost()) {
    $name = sanitize($_POST['name'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $reservation_date = $_POST['reservation_date'] ?? '';
    $reservation_time = $_POST['reservation_time'] ?? '';
    $guests = (int)($_POST['guests'] ?? 0);
    $notes = sanitize($_POST['notes'] ?? '');

    $sql = "UPDATE reservations 
            SET name = :name, phone = :phone, email = :email, reservation_date = :reservation_date,
                reservation_time = :reservation_time, guests = :guests, notes = :notes
            WHERE id = :id";
    $updateStmt = $pdo->prepare($sql);
    $updateStmt->execute([
        ':name' => $name,
        ':phone' => $phone,
        ':email' => $email,
        ':reservation_date' => $reservation_date,
        ':reservation_time' => $reservation_time,
        ':guests' => $guests,
        ':notes' => $notes,
        ':id' => $id
    ]);

    redirect('reservations.php?updated=1');
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservering bewerken</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <h2>Admin</h2>
            <nav>
                <a href="dashboard.php">Dashboard</a>
                <a href="reservations.php">Reserveringen</a>
                <a href="menu_items.php">Menu-items</a>
                <a href="contact_messages.php">Contactberichten</a>
                <a href="logout.php">Uitloggen</a>
            </nav>
        </aside>

        <main class="admin-main">
            <h1>Reservering bewerken</h1>

            <form method="POST" class="admin-form-card">
                <div class="form-group">
                    <label>Naam</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($reservation['name']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Telefoon</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($reservation['phone']); ?>" required>
                </div>

                <div class="form-group">
                    <label>E-mail</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($reservation['email']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Datum</label>
                    <input type="date" name="reservation_date" value="<?php echo htmlspecialchars($reservation['reservation_date']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Tijd</label>
                    <input type="time" name="reservation_time" value="<?php echo htmlspecialchars($reservation['reservation_time']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Aantal gasten</label>
                    <input type="number" name="guests" value="<?php echo htmlspecialchars($reservation['guests']); ?>" required>
                </div>

                <div class="form-group full-width">
                    <label>Opmerkingen</label>
                    <textarea name="notes" rows="4"><?php echo htmlspecialchars($reservation['notes']); ?></textarea>
                </div>

                <button type="submit" class="btn-admin">Opslaan</button>
            </form>
        </main>
    </div>
</body>
</html>