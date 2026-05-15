<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comandă plasată - Skincare Shop</title>
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
            </div>
            <div class="navbar-nav ms-auto">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a class="nav-link" href="index.php?page=profile">Contul meu</a>
                    <a class="nav-link" href="index.php?page=logout">Logout</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card shadow p-5">
                <h1 class="mb-3">🎉</h1>
                <h3 class="mb-3">Comandă plasată cu succes!</h3>
                <p class="text-muted mb-4">Îți mulțumim pentru comandă! Vei primi un email de confirmare în curând.</p>
                <a href="index.php?page=orders" class="btn btn-dark mb-2">Vezi comenzile mele</a>
                <a href="index.php?page=products" class="btn btn-outline-dark">Continuă cumpărăturile</a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../views/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>