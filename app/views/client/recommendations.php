<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recomandări - Skincare Shop</title>
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
    <div class="row mb-4">
        <div class="col-12">
            <h3>Recomandări personalizate pentru tine</h3>
            <p class="text-muted">Bazate pe profilul tău de ten: 
                <strong><?php echo ucfirst($profil['tip_ten']); ?></strong>
                <?php if($profil['predispus_acnee']): ?>
                    · <span class="badge bg-warning text-dark">Predispus acnee</span>
                <?php endif; ?>
                <?php if($profil['sensibilitate']): ?>
                    · <span class="badge bg-info text-dark">Ten sensibil</span>
                <?php endif; ?>
            </p>
            <?php if(!empty($profil['sensibilitati'])): ?>
                <p class="small text-muted">Sensibilități cunoscute: <strong><?php echo $profil['sensibilitati']; ?></strong></p>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <?php foreach($recommendations as $p): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <?php if($p['compatibility']['status'] === 'compatibil'): ?>
                            <span class="badge bg-success mb-2">✅ Compatibil</span>
                        <?php elseif($p['compatibility']['status'] === 'atentie'): ?>
                            <span class="badge bg-warning text-dark mb-2">⚠️ Atenție</span>
                        <?php else: ?>
                            <span class="badge bg-danger mb-2">❌ Evită</span>
                        <?php endif; ?>

                        <span class="badge bg-secondary mb-2 ms-1"><?php echo $p['categorie_nume']; ?></span>
                        <h6 class="card-title"><?php echo $p['nume']; ?></h6>
                        <p class="text-muted small"><?php echo $p['brand_nume']; ?></p>
                        <p class="card-text small"><?php echo substr($p['descriere'], 0, 80) . '...'; ?></p>

                        <div class="mb-2">
                            <small class="text-muted">Scor compatibilitate: </small>
                            <strong><?php echo $p['compatibility']['score']; ?>/100</strong>
                        </div>

                        <?php if(!empty($p['compatibility']['warnings'])): ?>
                            <ul class="small text-danger mb-2">
                                <?php foreach($p['compatibility']['warnings'] as $warning): ?>
                                    <li><?php echo $warning; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>

                        <p class="fw-bold"><?php echo number_format($p['pret'], 2); ?> lei</p>
                        <a href="index.php?page=product&action=view&id=<?php echo $p['id']; ?>" 
                           class="btn btn-dark btn-sm w-100">Vezi produs</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../../views/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>