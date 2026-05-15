<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/User.php';

if(!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit();
}

$userModel = new User();
$productModel = new Product();

$profil = $userModel->getSkinProfile($_SESSION['user_id']);

if(!$profil) {
    header('Location: index.php?page=quiz');
    exit();
}

$recommendations = $productModel->getRecommendations($profil);