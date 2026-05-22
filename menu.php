<?php
require_once 'config/db.php';
include 'includes/header.php';

$stmt = $pdo->query("SELECT * FROM menu_items ORDER BY category, name");
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$groupedItems = [];
foreach ($items as $item) {
    $groupedItems[$item['category']][] = $item;
}

function categoryId($category) {
    $map = [
        'Voorgerechten' => 'voorgerechten',
        'Hoofdgerechten' => 'hoofdgerechten',
        'Desserts' => 'desserts',
        'Drankjes' => 'drankjes'
    ];

    return $map[$category] ?? strtolower(str_replace(' ', '-', $category));
}
?>

<section class="page-header">
    <div class="container">
        <h1>Ons Menu</h1>
        <p>Ontdek onze heerlijke gerechten en dranken.</p>
    </div>
</section>

<section class="section">
    <div class="container">

        <div class="menu-filter">
            <a href="#voorgerechten" class="filter-btn">Voorgerechten</a>
            <a href="#hoofdgerechten" class="filter-btn">Hoofdgerechten</a>
            <a href="#desserts" class="filter-btn">Desserts</a>
            <a href="#drankjes" class="filter-btn">Drankjes</a>
        </div>

        <?php if (!empty($groupedItems)): ?>
            <?php foreach ($groupedItems as $category => $categoryItems): ?>
                <div class="menu-category" id="<?php echo htmlspecialchars(categoryId($category)); ?>">
                    <h2><?php echo htmlspecialchars($category); ?></h2>

                    <div class="menu-grid">
                        <?php foreach ($categoryItems as $item): ?>
                            <div class="menu-item-card">
                                <img 
                                    src="<?php echo !empty($item['image']) ? htmlspecialchars($item['image']) : 'assets/images/placeholder.jpg'; ?>" 
                                    alt="<?php echo htmlspecialchars($item['name']); ?>"
                                >
                                <div class="menu-item-content">
                                    <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                                    <p><?php echo htmlspecialchars($item['description']); ?></p>
                                    <span class="price">€ <?php echo number_format($item['price'], 2, ',', '.'); ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Er zijn nog geen menu-items beschikbaar.</p>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>