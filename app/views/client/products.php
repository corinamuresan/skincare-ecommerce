<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalog Produse - Skincare Shop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/skincare-ecommerce/public/assets/css/style.css">
    <style>
        .product-img-wrapper {
            height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            border-bottom: 1px solid #f0f0f0;
            padding: 30px;
            position: relative;
        }
        .product-img-wrapper img {
            max-height: 80%;
            max-width: 80%;
            object-fit: contain;
        }
        .wishlist-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 1.4rem;
            line-height: 1;
            color: red;
        }
    </style>
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
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0">Categorii</h6>
                </div>
                <div class="card-body p-2">
                    <a href="index.php?page=products" class="list-group-item list-group-item-action">Toate produsele</a>
                    <?php foreach($categories as $cat): ?>
                        <a href="index.php?page=products&categorie_id=<?php echo $cat['id']; ?><?php echo $brand_id ? '&brand_id=' . $brand_id : ''; ?>" 
                           class="list-group-item list-group-item-action <?php echo ($categorie_id == $cat['id']) ? 'active' : ''; ?>">
                            <?php echo $cat['nume']; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0">Branduri</h6>
                </div>
                <div class="card-body p-2">
                    <a href="index.php?page=products" class="list-group-item list-group-item-action">Toate brandurile</a>
                    <?php foreach($brands as $brand): ?>
                        <a href="index.php?page=products&brand_id=<?php echo $brand['id']; ?><?php echo $categorie_id ? '&categorie_id=' . $categorie_id : ''; ?>" 
                           class="list-group-item list-group-item-action <?php echo ($brand_id == $brand['id']) ? 'active' : ''; ?>">
                            <?php echo $brand['nume']; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <h4 class="mb-4">Catalog Produse</h4>
            <div class="row">
                <?php
               $wishlistIds = [];
                if(isset($_SESSION['user_id'])) {
                $pdo_wl = getConnection();
                $stmt_wl = $pdo_wl->prepare("
                   SELECT pw.produs_id 
                   FROM produse_wishlist pw
                   JOIN wishlisturi w ON pw.wishlist_id = w.id
                   WHERE w.utilizator_id = ?
                   ");
                $stmt_wl->execute([$_SESSION['user_id']]);
                $wl = $stmt_wl->fetchAll();
                foreach($wl as $item) {
                $wishlistIds[] = $item['produs_id'];
                }
            }
                ?>
                <?php foreach($products as $p): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="product-img-wrapper">
                                <?php if(isset($_SESSION['user_id']) && in_array($p['id'], $wishlistIds)): ?>
                                    <span class="wishlist-badge">♥</span>
                                <?php endif; ?>
                                <?php
                                $img = $product->getProductImage($p['id']);
                                if($img): ?>
                                    <img src="../uploads/<?php echo $img; ?>" alt="<?php echo $p['nume']; ?>">
                                <?php else: ?>
                                    <span class="text-muted">Fără imagine</span>
                                <?php endif; ?>
                            </div>
                            <div class="card-body">
                                <span class="badge bg-secondary mb-2"><?php echo $p['categorie_nume']; ?></span>
                                <h6 class="card-title"><?php echo $p['nume']; ?></h6>
                                <p class="text-muted small"><?php echo $p['brand_nume']; ?></p>
                                <p class="card-text small"><?php echo substr($p['descriere'], 0, 80) . '...'; ?></p>
                                <p class="fw-bold"><?php echo number_format($p['pret'], 2); ?> lei</p>
                                <a href="index.php?page=product&action=view&id=<?php echo $p['id']; ?>" 
                                   class="btn btn-dark btn-sm w-100">Vezi produs</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../views/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>