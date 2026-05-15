<?php

require_once __DIR__ . '/../../config/database.php';

$pdo = getConnection();

if(!isset($_GET['token'])) {
    header('Location: index.php?page=forgot_password');
    exit();
}

$token = $_GET['token'];

$stmt = $pdo->prepare("SELECT * FROM resetare_parola WHERE token = ? AND folosit = 0 AND data_expirare > NOW()");
$stmt->execute([$token]);
$reset = $stmt->fetch();

if(!$reset) {
    $error = 'Linkul de resetare este invalid sau a expirat!';
} else {
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $password = $_POST['password'];
        $password_confirm = $_POST['password_confirm'];

        if($password !== $password_confirm) {
            $error = 'Parolele nu coincid!';
        } else {
            $parola_hash = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $pdo->prepare("UPDATE utilizatori SET parola_hash = ? WHERE id = ?");
            $stmt->execute([$parola_hash, $reset['utilizator_id']]);

            $stmt = $pdo->prepare("UPDATE resetare_parola SET folosit = 1 WHERE token = ?");
            $stmt->execute([$token]);

            $success = 'Parola a fost schimbată cu succes!';
        }
    }
}