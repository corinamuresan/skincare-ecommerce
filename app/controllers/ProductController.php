<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Review.php';

$product = new Product();
$reviewModel = new Review();

$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$categorie_id = isset($_GET['categorie_id']) ? $_GET['categorie_id'] : null;
$brand_id = isset($_GET['brand_id']) ? $_GET['brand_id'] : null;

$categories = $product->getCategories();
$brands = $product->getBrands();

if($categorie_id && $brand_id) {
    $products = $product->getProductsByCategoryAndBrand($categorie_id, $brand_id);
} elseif($categorie_id) {
    $products = $product->getProductsByCategory($categorie_id);
} elseif($brand_id) {
    $products = $product->getProductsByBrand($brand_id);
} else {
    $products = $product->getAllProducts();
}

if($action === 'view' && isset($_GET['id'])) {
    $produs_id = $_GET['id'];
    $produs = $product->getProductById($produs_id);
    $ingrediente = $product->getProductIngredients($produs_id);
    $reviews = $reviewModel->getProductReviews($produs_id);
    $avgRating = $reviewModel->getAverageRating($produs_id);
    $userReview = null;

    $compatibility = null;
    if(isset($_SESSION['user_id'])) {
        require_once __DIR__ . '/../models/User.php';
        $userModel = new User();
        $profil = $userModel->getSkinProfile($_SESSION['user_id']);
        if($profil) {
            $compatibility = $product->getCompatibilityScore($produs_id, $profil);
        }
        $userReview = $reviewModel->getUserReview($_SESSION['user_id'], $produs_id);
    }
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rating']) && isset($_SESSION['user_id'])) {
    $rating = (int)$_POST['rating'];
    $comentariu = trim($_POST['comentariu']);
    $produs_id_review = $_POST['produs_id'];
    $reviewModel->addReview($_SESSION['user_id'], $produs_id_review, $rating, $comentariu);
    header('Location: index.php?page=product&action=view&id=' . $produs_id_review);
    exit();
}