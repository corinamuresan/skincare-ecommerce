<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Wishlist.php';

if(!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit();
}

$wishlist = new Wishlist();
$action = isset($_GET['action']) ? $_GET['action'] : 'view';

if($action === 'add' && isset($_GET['id'])) {
    $wishlist->addToWishlist($_SESSION['user_id'], $_GET['id']);
    header('Location: index.php?page=wishlist');
    exit();
}

if($action === 'remove' && isset($_GET['id'])) {
    $wishlist->removeFromWishlist($_SESSION['user_id'], $_GET['id']);
    header('Location: index.php?page=wishlist');
    exit();
}

$wishlistItems = $wishlist->getWishlistItems($_SESSION['user_id']);