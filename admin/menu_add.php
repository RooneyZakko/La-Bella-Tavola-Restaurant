<?php
require_once '../includes/auth.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$categories = getCategories();
$error = '';

if (isPost()) {
    $name = sanitize($_POST['name'] ?? '');
    $category = sanitize($_POST['category'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $imagePath = '';

    // Basis validatie
    if (empty($name) || empty($category) || empty($description) || $price <= 0) {
        $error = 'Vul alle velden correct in.';
    } elseif (!isset($_FILES['image'])) {
        $error = 'Er is geen afbeelding meegestuurd.';
    } else {
        $uploadError = $_FILES['image']['error'];

        if ($uploadError === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            $tmpName = $_FILES['image']['tmp_name'];

            // MIME type controleren
            $fileType = mime_content_type($tmpName);

            if (!in_array($fileType, $allowedTypes)) {
                $error = 'Alleen JPG, PNG en WEBP bestanden zijn toegestaan.';
            } else {
                $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $newFileName = uniqid('menu_', true) . '.' . $extension;

                $uploadDir = '../uploads/menu/';
                $uploadFile = $uploadDir . $newFileName;

                // Maak map aan als die nog niet bestaat
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                if (move_uploaded_file($tmpName, $uploadFile)) {
                    // Pad opslaan voor database
                    $imagePath = 'uploads/menu/' . $newFileName;
                } else {
                    $error = 'Uploaden van de afbeelding is mislukt.';
                }
            }
        } else {
            switch ($uploadError) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $error = 'De afbeelding is te groot.';
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $error = 'De afbeelding is niet volledig geüpload.';
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $error = 'Kies een afbeelding.';
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $error = 'Tijdelijke uploadmap ontbreekt op de server.';
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $error = 'De server kon het bestand niet opslaan.';
                    break;
                default:
                    $error = 'Er is een fout opgetreden bij het uploaden van de afbeelding.';
                    break;
            }
        }
    }

    if (empty($error)) {
        $stmt = $pdo->prepare("
            INSERT INTO menu_items (name, category, description, price, image)
            VALUES (:name, :category, :description, :price, :image)
        ");

        $stmt->execute([
            ':name' => $name,
            ':category' => $category,
            ':description' => $description,
            ':price' => $price,
            ':image' => $imagePath
        ]);

        redirect('menu_items.php?added=1');
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu-item toevoegen</title>
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
            <h1>Nieuw menu-item toevoegen</h1>

            <?php if (!empty($error)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="admin-form-card">
                <div class="form-group">
                    <label for="name">Naam</label>
                    <input type="text" name="name" id="name" required>
                </div>

                <div class="form-group">
                    <label for="category">Categorie</label>
                    <select name="category" id="category" required>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo htmlspecialchars($category); ?>">
                                <?php echo htmlspecialchars($category); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group full-width">
                    <label for="description">Beschrijving</label>
                    <textarea name="description" id="description" rows="4" required></textarea>
                </div>

                <div class="form-group">
                    <label for="price">Prijs</label>
                    <input type="number" name="price" id="price" step="0.01" min="0.01" required>
                </div>

                <div class="form-group">
                    <label for="image">Afbeelding uploaden</label>
                    <input type="file" name="image" id="image" accept=".jpg,.jpeg,.png,.webp" required>
                </div>

                <button type="submit" class="btn-admin">Toevoegen</button>
            </form>
        </main>
    </div>
</body>
</html>