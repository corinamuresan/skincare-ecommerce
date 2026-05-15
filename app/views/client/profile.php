<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilul meu - Skincare Shop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/skincare-ecommerce/public/assets/css/style.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">Skincare Shop</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="navbar-nav me-auto">
                <a class="nav-link" href="index.php?page=products">Catalog</a>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a class="nav-link" href="index.php?page=recommendations">Recomandări</a>
                <?php endif; ?>
            </div>
            <div class="navbar-nav ms-auto">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a class="nav-link" href="index.php?page=wishlist">♡ Wishlist</a>
                    <a class="nav-link" href="index.php?page=cart">🛒 Coș</a>
                    <a class="nav-link" href="index.php?page=profile">Contul meu</a>
                    <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                        <a class="nav-link text-warning" href="index.php?page=admin">Admin</a>
                    <?php endif; ?>
                    <a class="nav-link" href="index.php?page=logout">Logout</a>
                <?php else: ?>
                    <a class="nav-link" href="index.php?page=login">Login</a>
                    <a class="nav-link" href="index.php?page=register">Înregistrare</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm p-4 mb-4">
                <h5 class="mb-3">Contul meu</h5>
                <p><strong>Nume:</strong> <?php echo $user['prenume'] . ' ' . $user['nume']; ?></p>
                <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
                <p><strong>Telefon:</strong> <?php echo $user['telefon'] ?? 'Nespecificat'; ?></p>
                <hr>
                <a href="index.php?page=orders" class="btn btn-dark w-100 mb-2">Comenzile mele</a>
                <a href="index.php?page=wishlist" class="btn btn-outline-dark w-100 mb-2">Wishlist</a>
                <a href="index.php?page=quiz" class="btn btn-outline-dark w-100">Actualizează profil ten</a>
            </div>
        </div>

        <div class="col-md-8">
            <?php if(isset($profil)): ?>
                <div class="card shadow-sm p-4 mb-4">
                    <h5 class="mb-3">Profilul meu de ten</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Tip ten:</strong> <span class="badge bg-dark"><?php echo ucfirst($profil['tip_ten']); ?></span></p>
                            <p><strong>Predispus acnee:</strong> 
                                <?php echo $profil['predispus_acnee'] ? '<span class="badge bg-warning text-dark">Da</span>' : '<span class="badge bg-success">Nu</span>'; ?>
                            </p>
                            <p><strong>Ten sensibil:</strong> 
                                <?php echo $profil['sensibilitate'] ? '<span class="badge bg-warning text-dark">Da</span>' : '<span class="badge bg-success">Nu</span>'; ?>
                            </p>
                            <p><strong>Nivel hidratare:</strong> <span class="badge bg-info text-dark"><?php echo ucfirst($profil['nivel_hidratare']); ?></span></p>
                        </div>
                        <div class="col-md-6">
                            <?php if(!empty($profil['sensibilitati'])): ?>
                                <p><strong>Sensibilități cunoscute:</strong></p>
                                <p class="small text-muted"><?php echo $profil['sensibilitati']; ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <a href="index.php?page=recommendations" class="btn btn-dark btn-sm mt-2">Vezi recomandările tale</a>
                </div>
            <?php else: ?>
                <div class="card shadow-sm p-4 mb-4">
                    <h5 class="mb-3">Profilul meu de ten</h5>
                    <div class="alert alert-info">
                        <p class="mb-2">Nu ai completat încă quiz-ul de profil de ten.</p>
                        <a href="index.php?page=quiz" class="btn btn-dark btn-sm">Completează quiz-ul</a>
                    </div>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm p-4">
                <h5 class="mb-3">Ultimele comenzi</h5>
                <?php if(empty($orders)): ?>
                    <p class="text-muted">Nu ai nicio comandă încă.</p>
                <?php else: ?>
                    <?php foreach(array_slice($orders, 0, 3) as $comanda): ?>
                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
                            <span>Comanda #<?php echo $comanda['id']; ?></span>
                            <span><?php echo number_format($comanda['pret_total'], 2); ?> lei</span>
                            <?php
                            $colors = ['in_asteptare' => 'warning', 'platita' => 'success', 'expediata' => 'primary', 'livrata' => 'success', 'anulata' => 'danger'];
                            $labels = ['in_asteptare' => 'În așteptare', 'platita' => 'Plătită', 'expediata' => 'Expediată', 'livrata' => 'Livrată', 'anulata' => 'Anulată'];
                            $color = $colors[$comanda['status']] ?? 'secondary';
                            $label = $labels[$comanda['status']] ?? $comanda['status'];
                            ?>
                            <span class="badge bg-<?php echo $color; ?>"><?php echo $label; ?></span>
                        </div>
                    <?php endforeach; ?>
                    <a href="index.php?page=orders" class="btn btn-outline-dark btn-sm mt-2">Vezi toate comenzile</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../views/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>