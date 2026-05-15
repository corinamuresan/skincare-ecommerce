<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Order.php';

if(!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit();
}

$orderModel = new Order();
$orders = $orderModel->getOrdersByUser($_SESSION['user_id']);