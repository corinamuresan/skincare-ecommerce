<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

if(!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit();
}

if(!isset($_GET['comanda_id'])) {
    header('Location: index.php?page=orders');
    exit();
}

$comanda_id = $_GET['comanda_id'];
$pdo = getConnection();

$stmt = $pdo->prepare("
    SELECT c.*, u.prenume, u.nume, u.email, u.telefon
    FROM comenzi c
    JOIN utilizatori u ON c.utilizator_id = u.id
    WHERE c.id = ? AND c.utilizator_id = ?
");
$stmt->execute([$comanda_id, $_SESSION['user_id']]);
$comanda = $stmt->fetch();

if(!$comanda) {
    header('Location: index.php?page=orders');
    exit();
}

$stmt = $pdo->prepare("
    SELECT pc.*, p.nume, b.nume as brand_nume
    FROM produse_comanda pc
    JOIN produse p ON pc.produs_id = p.id
    JOIN branduri b ON p.brand_id = b.id
    WHERE pc.comanda_id = ?
");
$stmt->execute([$comanda_id]);
$items = $stmt->fetchAll();

$html = '
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 13px; color: #1A1A1A; }
    .header { background-color: #2D5016; color: #F5F0E8; padding: 20px; margin-bottom: 30px; }
    .header h1 { margin: 0; font-size: 24px; }
    .header p { margin: 5px 0 0; font-size: 13px; opacity: 0.8; }
    .section { margin-bottom: 20px; }
    .section h3 { color: #2D5016; border-bottom: 1px solid #E8DCC8; padding-bottom: 5px; }
    table { width: 100%; border-collapse: collapse; }
    th { background-color: #2D5016; color: #F5F0E8; padding: 8px; text-align: left; }
    td { padding: 8px; border-bottom: 1px solid #E8DCC8; }
    .total { font-size: 16px; font-weight: bold; color: #2D5016; text-align: right; margin-top: 10px; }
    .footer { margin-top: 40px; text-align: center; color: #5F5E5A; font-size: 11px; }
</style>
</head>
<body>
<div class="header">
    <h1>Skincare Shop</h1>
    <p>Factura nr. ' . $comanda_id . ' — ' . date('d.m.Y', strtotime($comanda['data_creare'])) . '</p>
</div>

<div class="section">
    <h3>Date client</h3>
    <p><strong>Nume:</strong> ' . $comanda['prenume'] . ' ' . $comanda['nume'] . '</p>
    <p><strong>Email:</strong> ' . $comanda['email'] . '</p>
    <p><strong>Telefon:</strong> ' . ($comanda['telefon'] ?? 'Nespecificat') . '</p>
</div>

<div class="section">
    <h3>Adresa de livrare</h3>
    <p><strong>Nume:</strong> ' . ($comanda['prenume_livrare'] ?? '') . ' ' . ($comanda['nume_livrare'] ?? '') . '</p>
    <p><strong>Telefon:</strong> ' . ($comanda['telefon_livrare'] ?? 'Nespecificat') . '</p>
    <p><strong>Adresa:</strong> ' . ($comanda['adresa'] ?? 'Nespecificat') . '</p>
    <p><strong>Localitate:</strong> ' . ($comanda['localitate'] ?? 'Nespecificat') . '</p>
    <p><strong>Judet:</strong> ' . ($comanda['judet'] ?? 'Nespecificat') . '</p>
    <p><strong>Cod postal:</strong> ' . ($comanda['cod_postal'] ?? 'Nespecificat') . '</p>
    <p><strong>Metoda livrare:</strong> ' . ($comanda['metoda_livrare'] ?? 'Nespecificat') . '</p>
</div>

<div class="section">
    <h3>Detalii comandă</h3>
    <table>
        <thead>
            <tr>
                <th>Produs</th>
                <th>Brand</th>
                <th>Cantitate</th>
                <th>Preț unitar</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>';

foreach($items as $item) {
    $html .= '
        <tr>
            <td>' . $item['nume'] . '</td>
            <td>' . $item['brand_nume'] . '</td>
            <td>' . $item['cantitate'] . '</td>
            <td>' . number_format($item['pret_la_moment'], 2) . ' lei</td>
            <td>' . number_format($item['pret_la_moment'] * $item['cantitate'], 2) . ' lei</td>
        </tr>';
}

$html .= '
        </tbody>
    </table>
    <p class="total">Total: ' . number_format($comanda['pret_total'], 2) . ' lei</p>
</div>

<div class="footer">
    <p>Skincare Shop — Multumim pentru comanda!</p>
</div>
</body>
</html>';

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('factura_' . $comanda_id . '.pdf', ['Attachment' => true]);
exit();