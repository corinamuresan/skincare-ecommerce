<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist - Skincare Shop</title>
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
    <h3 class="mb-4">Lista mea de dorințe</h3>

    <?php if(empty($wishlistItems)): ?>
        <div class="alert alert-info">
            Lista ta de dorințe este goală. <a href="index.php?page=products">Vezi produsele</a>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach($wishlistItems as $item): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <span class="badge bg-secondary mb-2"><?php echo $item['categorie_nume']; ?></span>
                            <h6 class="card-title"><?php echo $item['nume']; ?></h6>
                            <p class="text-muted small"><?php echo $item['brand_nume']; ?></p>
                            <p class="card-text small"><?php echo substr($item['descriere'], 0, 80) . '...'; ?></p>
                            <p class="fw-bold"><?php echo number_format($item['pret'], 2); ?> lei</p>
                            <a href="index.php?page=product&action=view&id=<?php echo $item['produs_id']; ?>" 
                               class="btn btn-dark btn-sm w-100 mb-2">Vezi produs</a>
                            <a href="index.php?page=cart&action=add&id=<?php echo $item['produs_id']; ?>" 
                               class="btn btn-outline-dark btn-sm w-100 mb-2">Adaugă în coș</a>
                            <a href="index.php?page=wishlist&action=remove&id=<?php echo $item['produs_id']; ?>" 
                               class="btn btn-outline-danger btn-sm w-100">Șterge din wishlist</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../views/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>