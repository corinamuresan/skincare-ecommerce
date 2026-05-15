<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/stripe.php';
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../models/Order.php';

if(!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit();
}

$cart = new Cart();
$order = new Order();

$cartItems = $cart->getCartItems($_SESSION['user_id']);
$cartTotal = $cart->getCartTotal($_SESSION['user_id']);