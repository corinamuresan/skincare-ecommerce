<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/stripe.php';
require_once __DIR__ . '/../../config/mail.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Cart.php';

if(!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit();
}

$order = new Order();
$cart = new Cart();

if(isset($_GET['payment_intent'])) {
    $payment_intent_id = $_GET['payment_intent'];
    
    try {
        $paymentIntent = \Stripe\PaymentIntent::retrieve($payment_intent_id);
        
        if($paymentIntent->status === 'succeeded') {
            if(isset($_SESSION['pending_order_id'])) {
                $comanda_id = $_SESSION['pending_order_id'];
                
                $order->updateOrderStatus($comanda_id, 'platita', $payment_intent_id);
                $order->updatePaymentStatus($payment_intent_id, 'succeeded');
                
                $pdo = getConnection();
                $stmt = $pdo->prepare("SELECT u.email, u.prenume, u.nume FROM utilizatori u WHERE u.id = ?");
                $stmt->execute([$_SESSION['user_id']]);
                $user = $stmt->fetch();
                
                $items = $order->getOrderItems($comanda_id);
                $stmt = $pdo->prepare("SELECT pret_total FROM comenzi WHERE id = ?");
                $stmt->execute([$comanda_id]);
                $comanda = $stmt->fetch();
                
                sendOrderConfirmation(
                    $user['email'],
                    $user['prenume'] . ' ' . $user['nume'],
                    $comanda_id,
                    $items,
                    $comanda['pret_total']
                );
                
                $cart->clearCart($_SESSION['user_id']);
                
                unset($_SESSION['pending_order_id']);
                unset($_SESSION['pending_payment_intent']);
            }
        }
    } catch(\Stripe\Exception\ApiErrorException $e) {
        header('Location: index.php?page=cart');
        exit();
    }
}