<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/stripe.php';
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../models/Order.php';

if(!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Neautorizat']);
    exit();
}

$cart = new Cart();
$order = new Order();

$input = json_decode(file_get_contents('php://input'), true);
$amount = isset($input['amount']) ? (int)$input['amount'] : 0;

if($amount <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Suma invalida']);
    exit();
}

$livrare = [
    'prenume' => $input['prenume'] ?? '',
    'nume' => $input['nume'] ?? '',
    'telefon' => $input['telefon'] ?? '',
    'judet' => $input['judet'] ?? '',
    'localitate' => $input['localitate'] ?? '',
    'adresa' => $input['adresa'] ?? '',
    'cod_postal' => $input['cod_postal'] ?? '',
    'metoda_livrare' => $input['metoda_livrare'] ?? ''
];

try {
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => $amount,
        'currency' => 'ron',
        'automatic_payment_methods' => ['enabled' => true],
    ]);

    $cartItems = $cart->getCartItems($_SESSION['user_id']);
    $cartTotal = $cart->getCartTotal($_SESSION['user_id']);

    $items = [];
    foreach($cartItems as $item) {
        $items[] = [
            'produs_id' => $item['produs_id'],
            'cantitate' => $item['cantitate'],
            'pret' => $item['pret']
        ];
    }

    $comanda_id = $order->createOrder($_SESSION['user_id'], $items, $amount / 100, $livrare);
    $order->createPayment($comanda_id, $paymentIntent->id, $amount / 100);

    $_SESSION['pending_order_id'] = $comanda_id;
    $_SESSION['pending_payment_intent'] = $paymentIntent->id;

    echo json_encode(['client_secret' => $paymentIntent->client_secret]);

} catch(\Stripe\Exception\ApiErrorException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}