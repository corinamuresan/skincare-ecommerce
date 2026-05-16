<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Skincare Shop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/skincare-ecommerce/public/assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">
 
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php?page=admin">Skincare Admin</a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link" href="index.php?page=admin">Dashboard</a>
            <a class="nav-link" href="index.php?page=admin_orders">Comenzi</a>
            <a class="nav-link" href="index.php?page=admin_products">Produse</a>
            <a class="nav-link" href="index.php?page=profile">Contul meu</a>
            <a class="nav-link" href="index.php?page=logout">Logout</a>
        </div>
    </div>
</nav>
 
<div class="container mt-4">
    <h3 class="mb-4">Dashboard Admin</h3>
 
    <!-- Statistici generale -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm text-center p-3">
                <h6 class="text-muted">Total Comenzi</h6>
                <h2 class="fw-bold"><?php echo $stats['total_comenzi']; ?></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-center p-3">
                <h6 class="text-muted">Vânzări Totale</h6>
                <h2 class="fw-bold"><?php echo number_format($stats['vanzari_totale'], 2); ?> lei</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-center p-3">
                <h6 class="text-muted">Utilizatori</h6>
                <h2 class="fw-bold"><?php echo $stats['total_utilizatori']; ?></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-center p-3">
                <h6 class="text-muted">Produse</h6>
                <h2 class="fw-bold"><?php echo $stats['total_produse']; ?></h2>
            </div>
        </div>
    </div>
 
    <!-- Butoane CRUD -->
    <div class="row mb-4">
        <div class="col-md-6">
            <a href="index.php?page=admin_products" class="btn btn-dark w-100">Gestionare Produse</a>
        </div>
        <div class="col-md-6">
            <a href="index.php?page=admin_orders" class="btn btn-dark w-100">Gestionare Comenzi</a>
        </div>
    </div>
 
    <!-- Grafice -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm p-4" style="height:320px;">
                <h5 class="mb-3">Vânzări pe Categorii</h5>
                <div style="height:230px;">
                    <canvas id="chartCategorii"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm p-4" style="height:320px;">
                <h5 class="mb-3">Status Comenzi</h5>
                <div style="height:230px;">
                    <canvas id="chartStatus"></canvas>
                </div>
            </div>
        </div>
    </div>
 
    <!-- Comenzi recente -->
    <div class="card shadow-sm p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Comenzi Recente</h5>
            <a href="index.php?page=admin_orders" class="btn btn-dark btn-sm">Vezi toate</a>
        </div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Client</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($recentOrders as $order): ?>
                <tr>
                    <td><?php echo $order['id']; ?></td>
                    <td><?php echo $order['prenume'] . ' ' . $order['nume']; ?></td>
                    <td><?php echo number_format($order['pret_total'], 2); ?> lei</td>
                    <td>
                        <?php
                        $colors = ['in_asteptare' => 'warning', 'platita' => 'success', 'in_procesare' => 'info', 'expediata' => 'primary', 'livrata' => 'success', 'anulata' => 'danger'];
                        $labels = ['in_asteptare' => 'În așteptare', 'platita' => 'Plătită', 'in_procesare' => 'În procesare', 'expediata' => 'Expediată', 'livrata' => 'Livrată', 'anulata' => 'Anulată'];
                        $color = $colors[$order['status']] ?? 'secondary';
                        $label = $labels[$order['status']] ?? $order['status'];
                        ?>
                        <span class="badge bg-<?php echo $color; ?>"><?php echo $label; ?></span>
                    </td>
                    <td><?php echo date('d.m.Y H:i', strtotime($order['data_creare'])); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
 
<script>
var ctxCat = document.getElementById('chartCategorii').getContext('2d');
new Chart(ctxCat, {
    type: 'bar',
    data: {
        labels: [<?php echo implode(',', array_map(function($c) { return '"' . $c['categorie'] . '"'; }, $vanzariCategorii)); ?>],
        datasets: [{
            label: 'Vânzări (lei)',
            data: [<?php echo implode(',', array_map(function($c) { return $c['total']; }, $vanzariCategorii)); ?>],
            backgroundColor: 'rgba(40, 60, 40, 0.7)'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } }
    }
});
 
var ctxStatus = document.getElementById('chartStatus').getContext('2d');
new Chart(ctxStatus, {
    type: 'doughnut',
    data: {
        labels: [<?php echo implode(',', array_map(function($s) { return '"' . $s['status'] . '"'; }, $statusComenzi)); ?>],
        datasets: [{
            data: [<?php echo implode(',', array_map(function($s) { return $s['total']; }, $statusComenzi)); ?>],
            backgroundColor: ['#ffc107','#198754','#0dcaf0','#0d6efd','#20c997','#dc3545']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom' } }
    }
});
</script>
 
<?php require_once __DIR__ . '/../../views/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>