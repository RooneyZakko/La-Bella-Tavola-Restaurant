<?php
require_once '../includes/auth.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$id = $_GET['id'] ?? null;
$categories = getCategories();
$error = '';

if (!$id) {
    redirect('menu_items.php');
}

$stmt = $pdo->prepare("SELECT * FROM menu_items WHERE id = :id");
$stmt->execute([':id' => $id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$item) {
    redirect('menu_items.php');
}

if (isPost()) {
    $name = sanitize($_POST['name'] ?? '');
    $category = sanitize($_POST['category'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $price = (float)($_POST['price'] ?? 0);

    // Huidige afbeelding behouden als er geen nieuwe upload is
    $imagePath = $item['image'];

    if (empty($name) || empty($category) || empty($description) || $price <= 0) {
        $error = 'Vul alle velden correct in.';
    } else {
        // Alleen verwerken als admin echt een nieuwe afbeelding kiest
        if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadError = $_FILES['image']['error'];

            if ($uploadError === UPLOAD_ERR_OK) {
                $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
                $tmpName = $_FILES['image']['tmp_name'];
                $fileType = mime_content_type($tmpName);

                if (!in_array($fileType, $allowedTypes)) {
                    $error = 'Alleen JPG, PNG en WEBP bestanden zijn toegestaan.';
                } else {
                    $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                    $newFileName = uniqid('menu_', true) . '.' . $extension;

                    $uploadDir = '../uploads/menu/';
                    $uploadFile = $uploadDir . $newFileName;

                    // Uploadmap maken als die nog niet bestaat
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    if (move_uploaded_file($tmpName, $uploadFile)) {
                        // Oude afbeelding verwijderen als die bestaat
                        if (!empty($item['image']) && file_exists('../' . $item['image'])) {
                            unlink('../' . $item['image']);
                        }

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
    }

    if (empty($error)) {
        $update = $pdo->prepare("
            UPDATE menu_items
            SET name = :name,
                category = :category,
                description = :description,
                price = :price,
                image = :image
            WHERE id = :id
        ");

        $update->execute([
            ':name' => $name,
            ':category' => $category,
            ':description' => $description,
            ':price' => $price,
            ':image' => $imagePath,
            ':id' => $id
        ]);

        redirect('menu_items.php?updated=1');
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu-item bewerken</title>
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
            <h1>Menu-item bewerken</h1>

            <?php if (!empty($error)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="admin-form-card">
                <div class="form-group">
                    <label for="name">Naam</label>
                    <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($item['name']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="category">Categorie</label>
                    <select name="category" id="category" required>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo htmlspecialchars($category); ?>" <?php echo $item['category'] === $category ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group full-width">
                    <label for="description">Beschrijving</label>
                    <textarea name="description" id="description" rows="4" required><?php echo htmlspecialchars($item['description']); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="price">Prijs</label>
                    <input type="number" name="price" id="price" step="0.01" min="0.01" value="<?php echo htmlspecialchars($item['price']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="image">Nieuwe afbeelding uploaden</label>
                    <input type="file" name="image" id="image" accept=".jpg,.jpeg,.png,.webp">
                </div>

                <?php if (!empty($item['image'])): ?>
                    <div class="form-group full-width">
                        <p>Huidige afbeelding:</p>
                        <img 
                            src="../<?php echo htmlspecialchars($item['image']); ?>" 
                            alt="Huidige afbeelding"
                            style="max-width: 200px; height: 140px; object-fit: cover; border-radius: 10px; margin-top: 10px;"
                        >
                    </div>
                <?php endif; ?>

                <button type="submit" class="btn-admin">Opslaan</button>
            </form>
        </main>
    </div>
</body>
</html>