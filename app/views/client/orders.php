<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comenzile mele - Skincare Shop</title>
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
    <h3 class="mb-4">Comenzile mele</h3>

    <?php if(empty($orders)): ?>
        <div class="alert alert-info">
            Nu ai nicio comandă încă. <a href="index.php?page=products">Descoperă produsele</a>
        </div>
    <?php else: ?>
        <?php foreach($orders as $comanda): ?>
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><strong>Comanda #<?php echo $comanda['id']; ?></strong> — <?php echo date('d.m.Y H:i', strtotime($comanda['data_creare'])); ?></span>
                    <div class="d-flex align-items-center gap-2">
                        <?php
                        $status_colors = [
                            'in_asteptare' => 'warning',
                            'platita' => 'success',
                            'in_procesare' => 'info',
                            'expediata' => 'primary',
                            'livrata' => 'success',
                            'anulata' => 'danger'
                        ];
                        $status_labels = [
                            'in_asteptare' => 'În așteptare',
                            'platita' => 'Plătită',
                            'in_procesare' => 'În procesare',
                            'expediata' => 'Expediată',
                            'livrata' => 'Livrată',
                            'anulata' => 'Anulată'
                        ];
                        $color = $status_colors[$comanda['status']] ?? 'secondary';
                        $label = $status_labels[$comanda['status']] ?? $comanda['status'];
                        ?>
                        <span class="badge bg-<?php echo $color; ?>"><?php echo $label; ?></span>
                        <a href="index.php?page=invoice&comanda_id=<?php echo $comanda['id']; ?>" 
                           class="btn btn-outline-dark btn-sm">📄 Descarcă factură</a>
                    </div>
                </div>
                <div class="card-body">
                    <?php
                    $items = $orderModel->getOrderItems($comanda['id']);
                    foreach($items as $item):
                    ?>
                        <div class="d-flex justify-content-between mb-2">
                            <span><?php echo $item['nume']; ?> x<?php echo $item['cantitate']; ?></span>
                            <span><?php echo number_format($item['pret_la_moment'] * $item['cantitate'], 2); ?> lei</span>
                        </div>
                    <?php endforeach; ?>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Total:</strong>
                        <strong><?php echo number_format($comanda['pret_total'], 2); ?> lei</strong>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../views/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>