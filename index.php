<?php
require_once 'config/db.php';
include 'includes/header.php';

// Laatste 3 gerechten ophalen
$stmt = $pdo->query("SELECT * FROM menu_items ORDER BY id DESC LIMIT 3");
$latestDishes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="hero">
    <div class="container hero-content">
        <div class="hero-text">
            <h1>Welkom bij La Bella Tavola</h1>
            <p>Geniet van heerlijke gerechten, een gezellige sfeer en een onvergetelijke culinaire ervaring.</p>
            <div class="hero-buttons">
                <a href="menu.php" class="btn">Bekijk Menu</a>
                <a href="reserve.php" class="btn btn-secondary">Reserveer Nu</a>
            </div>
        </div>
    </div>
</section>

<section class="about section">
    <div class="container">
        <h2>Over ons restaurant</h2>
        <p>
            La Bella Tavola is een modern restaurant in Tilburg waar kwaliteit, gastvrijheid en smaak centraal staan.
            Wij serveren verse gerechten bereid met zorg en passie. Of je nu komt lunchen, dineren of een speciale avond plant,
            wij zorgen voor een mooie ervaring.
        </p>
    </div>
</section>

<section class="featured-dishes section">
    <div class="container">
        <h2>Uitgelichte gerechten</h2>
        <div class="cards">
            <?php if (!empty($latestDishes)): ?>
                <?php foreach ($latestDishes as $dish): ?>
                    <div class="card">
                        <img 
                            src="<?php echo !empty($dish['image']) ? htmlspecialchars($dish['image']) : 'assets/images/placeholder.jpg'; ?>" 
                            alt="<?php echo htmlspecialchars($dish['name']); ?>"
                        >
                        <div class="card-body">
                            <h3><?php echo htmlspecialchars($dish['name']); ?></h3>
                            <p><?php echo htmlspecialchars($dish['description']); ?></p>
                            <span class="price">€ <?php echo number_format($dish['price'], 2, ',', '.'); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Er zijn nog geen gerechten beschikbaar.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="cta section">
    <div class="container cta-box">
        <h2>Wil je een tafel reserveren?</h2>
        <p>Reserveer eenvoudig online en verzeker jezelf van een plek in ons restaurant.</p>
        <a href="reserve.php" class="btn">Reserveer een tafel</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>