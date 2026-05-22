<?php
require_once '../includes/auth.php';
require_once '../config/db.php';

$reservationCount = $pdo->query("SELECT COUNT(*) FROM reservations")->fetchColumn();
$menuItemCount = $pdo->query("SELECT COUNT(*) FROM menu_items")->fetchColumn();
$messageCount = $pdo->query("SELECT COUNT(*) FROM contact_messages")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
                <a href="logout.php">Uitloggen</a>
            </nav>
        </aside>

        <main class="admin-main">
            <h1>Dashboard</h1>
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Reserveringen</h3>
                    <p><?php echo $reservationCount; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Menu-items</h3>
                    <p><?php echo $menuItemCount; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Contactberichten</h3>
                    <p><?php echo $messageCount; ?></p>
                </div>
            </div>

            <div class="admin-card">
                <h2>Welkom</h2>
                <p>Je bent ingelogd als admin. Gebruik het menu links om reserveringen en menu-items te beheren.</p>
            </div>
        </main>
    </div>
</body>
</html>