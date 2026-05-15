<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/mail.php';

$pdo = getConnection();

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = trim($_POST['email']);

    $stmt = $pdo->prepare("SELECT * FROM utilizatori WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if($user) {
        $token = bin2hex(random_bytes(32));
        $expirare = date('Y-m-d H:i:s', time() + 86400);

        $stmt = $pdo->prepare("INSERT INTO resetare_parola (utilizator_id, token, data_expirare) VALUES (?, ?, ?)");
        $stmt->execute([$user['id'], $token, $expirare]);

        $reset_link = 'http://localhost/skincare-ecommerce/public/index.php?page=reset_password&token=' . $token;

        $mail_body = '
        <h2>Resetare parolă</h2>
        <p>Bună ziua, ' . $user['prenume'] . '!</p>
        <p>Ai solicitat resetarea parolei pentru contul tău de pe Skincare Shop.</p>
        <p>Click pe linkul de mai jos pentru a seta o parolă nouă:</p>
        <p><a href="' . $reset_link . '" style="background:#2D5016;color:#F5F0E8;padding:12px 24px;text-decoration:none;border-radius:6px;">Resetează parola</a></p>
        <p>Linkul este valabil <strong>24 ore</strong>.</p>
        <p>Dacă nu ai solicitat resetarea parolei, ignoră acest email.</p>
        <p>Echipa Skincare Shop</p>';

        sendOrderConfirmationCustom($user['email'], $user['prenume'] . ' ' . $user['nume'], 'Resetare parolă - Skincare Shop', $mail_body);
    }

    $success = 'Dacă emailul există în sistem, vei primi un link de resetare în câteva minute!';
}