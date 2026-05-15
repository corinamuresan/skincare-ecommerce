<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Order.php';

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

$pdo = getConnection();
$orderModel = new Order();

$stmt = $pdo->prepare("SELECT COUNT(*) as total_comenzi FROM comenzi");
$stmt->execute();
$totalComenzi = $stmt->fetch();

$stmt = $pdo->prepare("SELECT SUM(pret_total) as vanzari_totale FROM comenzi WHERE status = 'platita'");
$stmt->execute();
$vanzariTotale = $stmt->fetch();

$stmt = $pdo->prepare("SELECT COUNT(*) as total_utilizatori FROM utilizatori WHERE rol = 'client'");
$stmt->execute();
$totalUtilizatori = $stmt->fetch();

$stmt = $pdo->prepare("SELECT COUNT(*) as total_produse FROM produse");
$stmt->execute();
$totalProduse = $stmt->fetch();

$stats = [
    'total_comenzi' => $totalComenzi['total_comenzi'],
    'vanzari_totale' => $vanzariTotale['vanzari_totale'] ?? 0,
    'total_utilizatori' => $totalUtilizatori['total_utilizatori'],
    'total_produse' => $totalProduse['total_produse']
];

$stmt = $pdo->prepare("
    SELECT c.nume as categorie, SUM(pc.pret_la_moment * pc.cantitate) as total
    FROM produse_comanda pc
    JOIN produse p ON pc.produs_id = p.id
    JOIN categorii c ON p.categorie_id = c.id
    JOIN comenzi co ON pc.comanda_id = co.id
    WHERE co.status = 'platita'
    GROUP BY c.id, c.nume
    ORDER BY total DESC
");
$stmt->execute();
$vanzariCategorii = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT status, COUNT(*) as total FROM comenzi GROUP BY status");
$stmt->execute();
$statusComenzi = $stmt->fetchAll();

$stmt = $pdo->prepare("
    SELECT c.*, u.prenume, u.nume
    FROM comenzi c
    JOIN utilizatori u ON c.utilizator_id = u.id
    ORDER BY c.data_creare DESC
    LIMIT 5
");
$stmt->execute();
$recentOrders = $stmt->fetchAll();