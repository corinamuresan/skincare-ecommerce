<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skincare Shop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/skincare-ecommerce/public/assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        .hero-section { background-color: #2D5016; padding: 80px 0; }
        .hero-title { font-size: 38px; font-weight: 500; color: #F5F0E8; line-height: 1.3; }
        .hero-subtitle { font-size: 15px; color: #E8DCC8; line-height: 1.7; }
        .hero-badge { font-size: 12px; color: #C0DD97; letter-spacing: 2px; text-transform: uppercase; }
        .btn-hero-primary { background-color: #F5F0E8; color: #2D5016; border: none; padding: 12px 28px; border-radius: 6px; font-size: 14px; font-weight: 500; text-decoration: none; }
        .btn-hero-primary:hover { background-color: #E8DCC8; color: #2D5016; }
        .btn-hero-secondary { background-color: transparent; color: #F5F0E8; border: 1.5px solid #E8DCC8; padding: 12px 28px; border-radius: 6px; font-size: 14px; text-decoration: none; }
        .btn-hero-secondary:hover { background-color: rgba(255,255,255,0.1); color: #F5F0E8; }
        .hero-image-placeholder { background-color: #3B6D11; border-radius: 12px; height: 320px; display: flex; align-items: center; justify-content: center; overflow: hidden; }
        .categories-section { background-color: #E8DCC8; padding: 40px 0; }
        .category-card { background-color: #F5F0E8; border-radius: 8px; padding: 20px; text-align: center; text-decoration: none; display: block; transition: all 0.2s; border: 0.5px solid #E8DCC8; }
        .category-card:hover { background-color: #2D5016; color: #F5F0E8 !important; }
        .category-card:hover p { color: #F5F0E8 !important; }
        .category-card:hover i { color: #F5F0E8 !important; }
        .category-name { font-size: 13px; color: #2D5016; font-weight: 500; margin: 0; }
        .stats-section { background-color: #2D5016; padding: 30px 0; }
        .stat-number { font-size: 28px; font-weight: 500; color: #F5F0E8; margin: 0; }
        .stat-label { font-size: 13px; color: #E8DCC8; margin: 4px 0 0; }
        .navbar { background-color: #1A3009 !important; }
    </style>
</head>
<body>

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

<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-7">
                <p class="hero-badge mb-2">Skincare personalizat</p>
                <h1 class="hero-title mb-3">Produsele potrivite<br>pentru tipul tău de ten</h1>
                <p class="hero-subtitle mb-4">Completează quiz-ul nostru și primești recomandări personalizate bazate pe ingredientele potrivite pentru tine.</p>
                <div class="d-flex gap-3">
                    <?php if(!isset($_SESSION['user_id'])): ?>
                        <a href="index.php?page=register" class="btn-hero-primary">Începe quiz-ul</a>
                        <a href="index.php?page=products" class="btn-hero-secondary">Vezi catalog</a>
                    <?php else: ?>
                        <a href="index.php?page=quiz" class="btn-hero-primary">Începe quiz-ul</a>
                        <a href="index.php?page=products" class="btn-hero-secondary">Vezi catalog</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-5">
                <div class="hero-image-placeholder">
                    <img src="../uploads/hero.jpg" alt="Skincare products" style="width:100%;height:320px;object-fit:cover;border-radius:12px;">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="categories-section">
    <div class="container">
        <p style="font-size: 11px; color: #4A7C2F; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 16px;">Categorii populare</p>
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <a href="index.php?page=products&categorie_id=1" class="category-card">
                    <i class="ti ti-wash" style="font-size:32px; color:#2D5016; display:block; margin-bottom:8px;"></i>
                    <p class="category-name">Cleanser</p>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="index.php?page=products&categorie_id=3" class="category-card">
                    <i class="ti ti-droplet" style="font-size:32px; color:#2D5016; display:block; margin-bottom:8px;"></i>
                    <p class="category-name">Serum</p>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="index.php?page=products&categorie_id=6" class="category-card">
                    <i class="ti ti-sun" style="font-size:32px; color:#2D5016; display:block; margin-bottom:8px;"></i>
                    <p class="category-name">SPF</p>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="index.php?page=products&categorie_id=4" class="category-card">
                    <i class="ti ti-leaf" style="font-size:32px; color:#2D5016; display:block; margin-bottom:8px;"></i>
                    <p class="category-name">Crema hidratanta</p>
                </a>
            </div>
        </div>
    </div>
</section>

<section class="stats-section">
    <div class="container">
        <div class="row text-center">
            <div class="col-4">
                <p class="stat-number">30+</p>
                <p class="stat-label">Produse</p>
            </div>
            <div class="col-4">
                <p class="stat-number">6</p>
                <p class="stat-label">Branduri</p>
            </div>
            <div class="col-4">
                <p class="stat-number">100%</p>
                <p class="stat-label">Personalizat</p>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../../views/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>