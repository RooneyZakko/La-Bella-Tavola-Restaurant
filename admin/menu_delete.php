<?php
require_once '../includes/auth.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM menu_items WHERE id = :id");
    $stmt->execute([':id' => $id]);
}

redirect('menu_items.php?deleted=1');
?>