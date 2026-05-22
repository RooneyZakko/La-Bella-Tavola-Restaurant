<?php

function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function isPost() {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

function isLoggedIn() {
    return isset($_SESSION['admin_id']);
}

function getCategories() {
    return [
        'Voorgerechten',
        'Hoofdgerechten',
        'Desserts',
        'Drankjes'
    ];
}
?>