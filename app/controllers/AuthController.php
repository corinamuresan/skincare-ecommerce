<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/User.php';

$user = new User();

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['prenume'])) {
    $prenume = trim($_POST['prenume']);
    $nume = trim($_POST['nume']);
    $email = trim($_POST['email']);
    $telefon = trim($_POST['telefon']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if($password !== $password_confirm) {
        $error = 'Parolele nu coincid!';
    } else {
        $result = $user->register($prenume, $nume, $email, $telefon, $password);
        if($result['success']) {
            $success = $result['message'];
        } else {
            $error = $result['message'];
        }
    }
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && !isset($_POST['prenume'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $result = $user->login($email, $password);
    if($result['success']) {
        header('Location: index.php');
        exit();
    } else {
        $error = $result['message'];
    }
}

if(isset($_GET['page']) && $_GET['page'] === 'logout') {
    $user->logout();
    header('Location: index.php');
    exit();
}