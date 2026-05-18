<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($produs) ? $produs['nume'] : 'Produs'; ?> - Skincare Shop</title>
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
    <?php if(isset($produs)): ?>
        <a href="index.php?page=products" class="btn btn-outline-dark btn-sm mb-3">← Înapoi la catalog</a>
        
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm p-4">
                    <?php
                    $pdo_img = getConnection();
                    $stmt_img = $pdo_img->prepare("SELECT * FROM imagini_produse WHERE produs_id = ?");
                    $stmt_img->execute([$produs['id']]);
                    $imagini = $stmt_img->fetchAll();
                    ?>

                    <?php if(count($imagini) > 1): ?>
                        <div id="carouselProdus" class="carousel slide mb-3" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                <?php foreach($imagini as $index => $img): ?>
                                    <button type="button"
                                            data-bs-target="#carouselProdus"
                                            data-bs-slide-to="<?php echo $index; ?>"
                                            <?php echo $index === 0 ? 'class="active"' : ''; ?>>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                            <div class="carousel-inner">
                                <?php foreach($imagini as $index => $img): ?>
                                    <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                        <img src="../uploads/<?php echo $img['url_imagine']; ?>"
                                             class="d-block w-100 rounded"
                                             style="max-height:400px;object-fit:contain;background:#f8f9fa;"
                                             alt="<?php echo $produs['nume']; ?>">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselProdus" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselProdus" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        </div>

                    <?php elseif(count($imagini) === 1): ?>
                        <img src="../uploads/<?php echo $imagini[0]['url_imagine']; ?>"
                             class="img-fluid rounded mb-3"
                             style="width:100%;max-height:400px;object-fit:contain;background:#f8f9fa;"
                             alt="<?php echo $produs['nume']; ?>">

                    <?php endif; ?>

                    <span class="badge bg-secondary mb-2"><?php echo $produs['categorie_nume']; ?></span>
                    <h2><?php echo $produs['nume']; ?></h2>
                    <p class="text-muted"><?php echo $produs['brand_nume']; ?></p>
                    <p><?php echo $produs['descriere']; ?></p>
                    <h4 class="text-dark"><?php echo number_format($produs['pret'], 2); ?> lei</h4>
                    <p class="text-muted small">Stoc disponibil: <?php echo $produs['stoc']; ?> buc.</p>
                    
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="index.php?page=cart&action=add&id=<?php echo $produs['id']; ?>" 
                           class="btn btn-dark mt-2">Adaugă în coș</a>
                        <a href="index.php?page=wishlist&action=add&id=<?php echo $produs['id']; ?>" 
                           class="btn btn-outline-dark mt-2 ms-2">♡ Wishlist</a>
                    <?php else: ?>
                        <a href="index.php?page=login" class="btn btn-dark mt-2">Loghează-te pentru a cumpăra</a>
                    <?php endif; ?>
                </div>

                <div class="card shadow-sm p-4 mt-3">
                    <h5>Ingrediente</h5>
                    <?php if(!empty($ingrediente)): ?>
                        <div class="row">
                            <?php foreach($ingrediente as $ing): ?>
                                <div class="col-md-6 mb-2">
                                    <div class="p-2 border rounded">
                                        <strong><?php echo $ing['nume']; ?></strong>
                                        <p class="small text-muted mb-0"><?php echo $ing['descriere']; ?></p>
                                        <?php if($ing['rating_comedogenic'] >= 3): ?>
                                            <span class="badge bg-warning text-dark">Comedogenic <?php echo $ing['rating_comedogenic']; ?>/5</span>
                                        <?php endif; ?>
                                        <?php if($ing['iritant'] == 1): ?>
                                            <span class="badge bg-danger">Iritant</span>
                                        <?php endif; ?>
                                        <?php if($ing['toxic'] == 1): ?>
                                            <span class="badge bg-dark">Controversat</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Nu sunt ingrediente disponibile pentru acest produs.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-md-4">
                <?php if(isset($compatibility)): ?>
                    <div class="card shadow-sm p-4 mb-3">
                        <h5>Indicator Compatibilitate</h5>
                        <?php if($compatibility['status'] === 'compatibil'): ?>
                            <div class="alert alert-success">
                                <strong>✅ Compatibil cu profilul tău</strong>
                                <p class="small mb-0">Scor: <?php echo $compatibility['score']; ?>/100</p>
                            </div>
                        <?php elseif($compatibility['status'] === 'atentie'): ?>
                            <div class="alert alert-warning">
                                <strong>⚠️ Atenție</strong>
                                <p class="small mb-0">Scor: <?php echo $compatibility['score']; ?>/100</p>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-danger">
                                <strong>❌ Evită acest produs</strong>
                                <p class="small mb-0">Scor: <?php echo $compatibility['score']; ?>/100</p>
                            </div>
                        <?php endif; ?>

                        <?php if(!empty($compatibility['warnings'])): ?>
                            <h6>Avertismente:</h6>
                            <ul class="small">
                                <?php foreach($compatibility['warnings'] as $warning): ?>
                                    <li><?php echo $warning; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                <?php elseif(isset($_SESSION['user_id'])): ?>
                    <div class="card shadow-sm p-4 mb-3">
                        <h5>Indicator Compatibilitate</h5>
                        <div class="alert alert-info">
                            <p class="small mb-0">Completează quiz-ul de profil de ten pentru a vedea compatibilitatea!</p>
                            <a href="index.php?page=quiz" class="btn btn-dark btn-sm mt-2">Completează quiz</a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="card shadow-sm p-4 mb-3">
                        <h5>Indicator Compatibilitate</h5>
                        <div class="alert alert-info">
                            <p class="small mb-0">Loghează-te și completează quiz-ul pentru a vedea dacă acest produs e potrivit pentru tine!</p>
                            <a href="index.php?page=login" class="btn btn-dark btn-sm mt-2">Login</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-danger">Produsul nu a fost găsit!</div>
    <?php endif; ?>
</div>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm p-4">
                <h5>Recenzii (<?php echo isset($avgRating) ? $avgRating['total'] : 0; ?>)</h5>
                
                <?php if(isset($avgRating) && $avgRating['total'] > 0): ?>
                    <p class="mb-3">
                        Rating mediu: <strong><?php echo number_format($avgRating['avg_rating'], 1); ?>/5</strong>
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <?php echo $i <= round($avgRating['avg_rating']) ? '⭐' : '☆'; ?>
                        <?php endfor; ?>
                    </p>
                <?php endif; ?>

                <?php if(isset($_SESSION['user_id'])): ?>
                    <div class="card p-3 mb-4 bg-light">
                        <h6><?php echo isset($userReview) && $userReview ? 'Editează recenzia ta' : 'Lasă o recenzie'; ?></h6>
                        <form action="index.php?page=product&action=view&id=<?php echo isset($produs) ? $produs['id'] : ''; ?>" method="POST">
                            <input type="hidden" name="produs_id" value="<?php echo isset($produs) ? $produs['id'] : ''; ?>">
                            <div class="mb-3">
                                <label class="form-label">Rating</label>
                                <select name="rating" class="form-select" required>
                                    <option value="">Selectează rating</option>
                                    <?php for($i = 5; $i >= 1; $i--): ?>
                                        <option value="<?php echo $i; ?>" <?php echo (isset($userReview) && $userReview && $userReview['rating'] == $i) ? 'selected' : ''; ?>>
                                            <?php echo $i; ?> stele
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Comentariu</label>
                                <textarea name="comentariu" class="form-control" rows="3" placeholder="Spune-ne părerea ta..."><?php echo (isset($userReview) && $userReview) ? $userReview['comentariu'] : ''; ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-dark btn-sm">Trimite recenzia</button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info small">
                        <a href="index.php?page=login">Loghează-te</a> pentru a lăsa o recenzie.
                    </div>
                <?php endif; ?>

                <?php if(isset($reviews) && !empty($reviews)): ?>
                    <?php foreach($reviews as $review): ?>
                        <div class="border-bottom pb-3 mb-3">
                            <div class="d-flex justify-content-between">
                                <strong><?php echo $review['prenume'] . ' ' . $review['nume']; ?></strong>
                                <small class="text-muted"><?php echo date('d.m.Y', strtotime($review['data_creare'])); ?></small>
                            </div>
                            <div class="mb-1">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <?php echo $i <= $review['rating'] ? '⭐' : '☆'; ?>
                                <?php endfor; ?>
                            </div>
                            <p class="mb-0 small"><?php echo $review['comentariu']; ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted small">Nu există recenzii pentru acest produs încă.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../views/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>