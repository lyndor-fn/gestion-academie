<?php
$db_host = '127.0.0.1';
$db_name = 'gestion_ecole';
$db_user = 'root';
$db_pass = '';

$admin_user = 'admin';
$admin_pass = 'admin123';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Connexion échouée: ' . $e->getMessage());
}

function e($s){ return htmlspecialchars($s, ENT_QUOTES); }
