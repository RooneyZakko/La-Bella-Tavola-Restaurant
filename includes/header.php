<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Bella Tavola Restaurant</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header class="site-header">
    <div class="container nav-container">
        <a href="index.php" class="logo">La Bella Tavola</a>

        <button class="menu-toggle" id="menu-toggle">☰</button>

        <nav class="nav" id="nav">
            <a href="index.php">Home</a>
            <a href="menu.php">Menu</a>
            <a href="reserve.php">Reserveren</a>
            <a href="contact.php">Contact</a>
            <a href="admin/login.php" class="admin-link">Admin</a>
        </nav>
    </div>
</header>

<main>