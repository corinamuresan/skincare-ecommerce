<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Product.php';

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

$product = new Product();
$categories = $product->getCategories();
$brands = $product->getBrands();

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if($_POST['action'] === 'add') {
        $nume = trim($_POST['nume']);
        $brand_id = $_POST['brand_id'];
        $categorie_id = $_POST['categorie_id'];
        $descriere = trim($_POST['descriere']);
        $pret = $_POST['pret'];
        $stoc = $_POST['stoc'];

        $pdo = getConnection();
        $stmt = $pdo->prepare("INSERT INTO produse (nume, brand_id, categorie_id, descriere, pret, stoc) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nume, $brand_id, $categorie_id, $descriere, $pret, $stoc]);
        $produs_id = $pdo->lastInsertId();

        if(isset($_FILES['imagine']) && $_FILES['imagine']['error'] === 0) {
            $upload_dir = __DIR__ . '/../../uploads/';
            $filename = $produs_id . '.jpg';
            move_uploaded_file($_FILES['imagine']['tmp_name'], $upload_dir . $filename);

            $stmt = $pdo->prepare("INSERT INTO imagini_produse (produs_id, url_imagine) VALUES (?, ?)");
            $stmt->execute([$produs_id, $filename]);
        }

        $success = 'Produsul a fost adăugat cu succes!';
    }

    if($_POST['action'] === 'update') {
        $produs_id = $_POST['produs_id'];
        $nume = trim($_POST['nume']);
        $brand_id = $_POST['brand_id'];
        $categorie_id = $_POST['categorie_id'];
        $descriere = trim($_POST['descriere']);
        $pret = $_POST['pret'];
        $stoc = $_POST['stoc'];

        $pdo = getConnection();
        $stmt = $pdo->prepare("UPDATE produse SET nume = ?, brand_id = ?, categorie_id = ?, descriere = ?, pret = ?, stoc = ? WHERE id = ?");
        $stmt->execute([$nume, $brand_id, $categorie_id, $descriere, $pret, $stoc, $produs_id]);

        if(isset($_FILES['imagine']) && $_FILES['imagine']['error'] === 0) {
            $upload_dir = __DIR__ . '/../../uploads/';
            $filename = $produs_id . '.jpg';
            move_uploaded_file($_FILES['imagine']['tmp_name'], $upload_dir . $filename);

            $stmt = $pdo->prepare("SELECT id FROM imagini_produse WHERE produs_id = ?");
            $stmt->execute([$produs_id]);
            if($stmt->fetch()) {
                $stmt = $pdo->prepare("UPDATE imagini_produse SET url_imagine = ? WHERE produs_id = ?");
                $stmt->execute([$filename, $produs_id]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO imagini_produse (produs_id, url_imagine) VALUES (?, ?)");
                $stmt->execute([$produs_id, $filename]);
            }
        }

        $success = 'Produsul a fost actualizat cu succes!';
    }
}

if(isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $pdo = getConnection();
    $stmt = $pdo->prepare("DELETE FROM produse WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $success = 'Produsul a fost șters cu succes!';
}

if(isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $pdo = getConnection();
    $stmt = $pdo->prepare("SELECT * FROM produse WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $editProduct = $stmt->fetch();
}

$products = $product->getAllProducts();