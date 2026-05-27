<?php
require_once '../includes/auth.php';
require_once '../config/db.php';

// Nieuwste reserveringen eerst tonen
$stmt = $pdo->query("SELECT * FROM reservations ORDER BY  id DESC");
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserveringen beheren</title>
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
            <h1>Reserveringen</h1>

            <?php if (isset($_GET['updated'])): ?>
                <div class="success-message">Reservering aangepast.</div>
            <?php endif; ?>

            <?php if (isset($_GET['deleted'])): ?>
                <div class="success-message">Reservering verwijderd.</div>
            <?php endif; ?>

            <div class="admin-card">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Naam</th>
                            <th>Telefoon</th>
                            <th>E-mail</th>
                            <th>Datum</th>
                            <th>Tijd</th>
                            <th>Gasten</th>
                            <th>Opmerkingen</th>
                            <th>Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservations as $reservation): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($reservation['name']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['phone']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['email']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['reservation_date']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['reservation_time']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['guests']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['notes']); ?></td>
                                <td>
                                    <a class="action-link" href="reservation_edit.php?id=<?php echo $reservation['id']; ?>">Bewerken</a>
                                    <a class="action-link delete-link" href="reservation_delete.php?id=<?php echo $reservation['id']; ?>" onclick="return confirm('Weet je zeker dat je deze reservering wilt verwijderen?');">Verwijderen</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>