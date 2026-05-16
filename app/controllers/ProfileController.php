<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Order.php';

if(!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit();
}

$userModel = new User();
$orderModel = new Order();

$pdo = getConnection();
$stmt = $pdo->prepare("SELECT * FROM utilizatori WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

$profil = $userModel->getSkinProfile($_SESSION['user_id']) ?: null;
$orders = $orderModel->getOrdersByUser($_SESSION['user_id']);