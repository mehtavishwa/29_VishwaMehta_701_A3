<?php
// config.php
session_start();

$DB_HOST = '127.0.0.1';
$DB_NAME = 'shoppingcart';
$DB_USER = 'root';
$DB_PASS = 'root'; // set your DB password
$BASE_URL = '/shoppingcart'; // change if needed, no trailing slash

try {
    $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4", $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (Exception $e) {
    die('DB connection error: ' . $e->getMessage());
}

function url($path = '') {
    global $BASE_URL;
    $path = ltrim($path, '/');
    return $BASE_URL . '/' . $path;
}

function isAdmin() {
    return !empty($_SESSION['admin']);
}
function requireAdmin() {
    if (!isAdmin()) {
        header('Location: login.php');
        exit;
    }
}
