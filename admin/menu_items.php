<?php
require_once '../includes/auth.php';
require_once '../config/db.php';

$stmt = $pdo->query("SELECT * FROM menu_items ORDER BY category ASC, id DESC");
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

function getCategoryBadgeClass($category) {
    switch ($category) {
        case 'Voorgerechten':
            return 'badge-voorgerechten';
        case 'Hoofdgerechten':
            return 'badge-hoofdgerechten';
        case 'Desserts':
            return 'badge-desserts';
        case 'Drankjes':
            return 'badge-drankjes';
        default:
            return 'badge-default';
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu-items beheren</title>
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
            <div class="page-actions">
                <h1>Menu-items</h1>
                <a href="menu_add.php" class="btn-admin">Nieuw item toevoegen</a>
            </div>

            <?php if (isset($_GET['added'])): ?>
                <div class="success-message">Menu-item toegevoegd.</div>
            <?php endif; ?>

            <?php if (isset($_GET['updated'])): ?>
                <div class="success-message">Menu-item aangepast.</div>
            <?php endif; ?>

            <?php if (isset($_GET['deleted'])): ?>
                <div class="success-message">Menu-item verwijderd.</div>
            <?php endif; ?>

            <div class="admin-card">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Afbeelding</th>
                            <th>Naam</th>
                            <th>Categorie</th>
                            <th>Beschrijving</th>
                            <th>Prijs</th>
                            <th>Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($item['image'])): ?>
                                        <img 
                                            src="../<?php echo htmlspecialchars($item['image']); ?>" 
                                            alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                            class="admin-menu-thumb"
                                        >
                                    <?php else: ?>
                                        <span class="no-image">Geen afbeelding</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <strong><?php echo htmlspecialchars($item['name']); ?></strong>
                                </td>

                                <td>
                                    <span class="category-badge <?php echo getCategoryBadgeClass($item['category']); ?>">
                                        <?php echo htmlspecialchars($item['category']); ?>
                                    </span>
                                </td>

                                <td class="description-cell">
                                    <?php echo htmlspecialchars($item['description']); ?>
                                </td>

                                <td>
                                    <span class="price-badge">€ <?php echo number_format($item['price'], 2, ',', '.'); ?></span>
                                </td>

                                <td>
                                    <div class="table-actions">
                                        <a class="action-link" href="menu_edit.php?id=<?php echo $item['id']; ?>">Bewerken</a>
                                        <a class="action-link delete-link" href="menu_delete.php?id=<?php echo $item['id']; ?>" onclick="return confirm('Weet je zeker dat je dit item wilt verwijderen?');">Verwijderen</a>
                                    </div>
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