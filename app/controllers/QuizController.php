<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/User.php';

if(!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit();
}

$userModel = new User();
$pdo = getConnection();

$stmt = $pdo->prepare("SELECT * FROM intrebari_quiz ORDER BY ordine");
$stmt->execute();
$intrebari = $stmt->fetchAll();
$total_intrebari = count($intrebari);

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $intrebare_id = $_POST['intrebare_id'];
    $intrebare_nr = $_POST['intrebare_nr'];

    if(isset($_POST['raspuns_id'])) {
        $raspunsuri_selectate = is_array($_POST['raspuns_id']) ? $_POST['raspuns_id'] : [$_POST['raspuns_id']];
        
        $stmt = $pdo->prepare("DELETE FROM raspunsuri_utilizator_quiz WHERE utilizator_id = ? AND intrebare_id = ?");
        $stmt->execute([$_SESSION['user_id'], $intrebare_id]);

        foreach($raspunsuri_selectate as $raspuns_id) {
            $stmt = $pdo->prepare("INSERT INTO raspunsuri_utilizator_quiz (utilizator_id, intrebare_id, raspuns_id) VALUES (?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $intrebare_id, $raspuns_id]);
        }
    }

    if($intrebare_nr >= $total_intrebari) {
        $result = $userModel->generateSkinProfile($_SESSION['user_id']);
        if($result) {
            $success = true;
        }
    } else {
        $intrebare_nr_urmator = $intrebare_nr + 1;
        header("Location: index.php?page=quiz&q=" . $intrebare_nr_urmator);
        exit();
    }
}

if(!isset($success)) {
    $q = isset($_GET['q']) ? (int)$_GET['q'] : 1;
    $intrebare_nr = $q;
    $intrebare = $intrebari[$q - 1];

    $stmt = $pdo->prepare("SELECT * FROM raspunsuri_quiz WHERE intrebare_id = ?");
    $stmt->execute([$intrebare['id']]);
    $raspunsuri = $stmt->fetchAll();
}