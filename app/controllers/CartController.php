<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Cart.php';

if(!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit();
}

$cart = new Cart();
$action = isset($_GET['action']) ? $_GET['action'] : 'view';

if($action === 'add' && isset($_GET['id'])) {
    $produs_id = $_GET['id'];
    $cantitate = isset($_GET['cantitate']) ? (int)$_GET['cantitate'] : 1;
    $cart->addToCart($_SESSION['user_id'], $produs_id, $cantitate);
    header('Location: index.php?page=cart');
    exit();
}

if($action === 'remove' && isset($_GET['item_id'])) {
    $cart->removeFromCart($_SESSION['user_id'], $_GET['item_id']);
    header('Location: index.php?page=cart');
    exit();
}

if($action === 'update' && isset($_GET['item_id']) && isset($_GET['cantitate'])) {
    $cart->updateQuantity($_SESSION['user_id'], $_GET['item_id'], (int)$_GET['cantitate']);
    header('Location: index.php?page=cart');
    exit();
}

$cartItems = $cart->getCartItems($_SESSION['user_id']);
$cartTotal = $cart->getCartTotal($_SESSION['user_id']);