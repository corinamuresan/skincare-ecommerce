<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionare Comenzi - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/skincare-ecommerce/public/assets/css/style.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php?page=admin">Admin Panel</a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link" href="index.php">Vezi site-ul</a>
            <a class="nav-link" href="index.php?page=logout">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Gestionare Comenzi</h3>
        <a href="index.php?page=admin" class="btn btn-outline-dark btn-sm">← Înapoi la dashboard</a>
    </div>

    <?php if(isset($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Client</th>
                        <th>Email</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Data</th>
                        <th>Acțiuni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($orders as $comanda): ?>
                        <tr>
                            <td><?php echo $comanda['id']; ?></td>
                            <td><?php echo $comanda['prenume'] . ' ' . $comanda['nume']; ?></td>
                            <td><?php echo $comanda['email']; ?></td>
                            <td><?php echo number_format($comanda['pret_total'], 2); ?> lei</td>
                            <td>
                                <?php
                                $colors = ['in_asteptare' => 'warning', 'platita' => 'success', 'in_procesare' => 'info', 'expediata' => 'primary', 'livrata' => 'success', 'anulata' => 'danger'];
                                $labels = ['in_asteptare' => 'În așteptare', 'platita' => 'Plătită', 'in_procesare' => 'În procesare', 'expediata' => 'Expediată', 'livrata' => 'Livrată', 'anulata' => 'Anulată'];
                                $color = $colors[$comanda['status']] ?? 'secondary';
                                $label = $labels[$comanda['status']] ?? $comanda['status'];
                                ?>
                                <span class="badge bg-<?php echo $color; ?>"><?php echo $label; ?></span>
                            </td>
                            <td><?php echo date('d.m.Y H:i', strtotime($comanda['data_creare'])); ?></td>
                            <td>
                                <form action="index.php?page=admin_orders" method="POST" class="d-inline">
                                    <input type="hidden" name="comanda_id" value="<?php echo $comanda['id']; ?>">
                                    <select name="status" class="form-select form-select-sm d-inline w-auto">
                                        <option value="in_asteptare" <?php echo $comanda['status'] === 'in_asteptare' ? 'selected' : ''; ?>>În așteptare</option>
                                        <option value="platita" <?php echo $comanda['status'] === 'platita' ? 'selected' : ''; ?>>Plătită</option>
                                        <option value="in_procesare" <?php echo $comanda['status'] === 'in_procesare' ? 'selected' : ''; ?>>În procesare</option>
                                        <option value="expediata" <?php echo $comanda['status'] === 'expediata' ? 'selected' : ''; ?>>Expediată</option>
                                        <option value="livrata" <?php echo $comanda['status'] === 'livrata' ? 'selected' : ''; ?>>Livrată</option>
                                        <option value="anulata" <?php echo $comanda['status'] === 'anulata' ? 'selected' : ''; ?>>Anulată</option>
                                    </select>
                                    <button type="submit" class="btn btn-dark btn-sm">Actualizează</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>