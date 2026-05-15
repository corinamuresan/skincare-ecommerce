<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coș de cumpărături - Skincare Shop</title>
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
    <h3 class="mb-4">Coșul tău de cumpărături</h3>

    <?php if(empty($cartItems)): ?>
        <div class="alert alert-info">
            Coșul tău este gol. <a href="index.php?page=products">Vezi produsele</a>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-md-8">
                <?php foreach($cartItems as $item): ?>
                    <div class="card mb-3 shadow-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-5">
                                    <h6 class="mb-0"><?php echo $item['nume']; ?></h6>
                                    <small class="text-muted"><?php echo $item['brand_nume']; ?></small>
                                </div>
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center">
                                        <a href="index.php?page=cart&action=update&item_id=<?php echo $item['id']; ?>&cantitate=<?php echo $item['cantitate'] - 1; ?>" 
                                           class="btn btn-outline-dark btn-sm">-</a>
                                        <span class="mx-2"><?php echo $item['cantitate']; ?></span>
                                        <a href="index.php?page=cart&action=update&item_id=<?php echo $item['id']; ?>&cantitate=<?php echo $item['cantitate'] + 1; ?>" 
                                           class="btn btn-outline-dark btn-sm">+</a>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <strong><?php echo number_format($item['pret'] * $item['cantitate'], 2); ?> lei</strong>
                                </div>
                                <div class="col-md-2">
                                    <a href="index.php?page=cart&action=remove&item_id=<?php echo $item['id']; ?>" 
                                       class="btn btn-outline-danger btn-sm">Șterge</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Sumar comandă</h5>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Total:</span>
                            <strong><?php echo number_format($cartTotal, 2); ?> lei</strong>
                        </div>
                        <a href="index.php?page=checkout" class="btn btn-dark w-100">Finalizează comanda</a>
                        <a href="index.php?page=products" class="btn btn-outline-dark w-100 mt-2">Continuă cumpărăturile</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../views/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>