<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Order.php';

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

$orderModel = new Order();

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comanda_id'])) {
    $comanda_id = $_POST['comanda_id'];
    $status = $_POST['status'];
    $orderModel->updateOrderStatus($comanda_id, $status);
    $success = 'Status actualizat cu succes!';
}

$orders = $orderModel->getAllOrders();