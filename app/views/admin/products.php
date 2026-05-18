<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionare Produse - Admin</title>
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
        <h3>Gestionare Produse</h3>
        <a href="index.php?page=admin" class="btn btn-outline-dark btn-sm">← Înapoi la dashboard</a>
    </div>

    <?php if(isset($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if(isset($editProduct)): ?>
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Editează produs</h5>
        </div>
        <div class="card-body">
            <form action="index.php?page=admin_products" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="produs_id" value="<?php echo $editProduct['id']; ?>">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nume produs</label>
                        <input type="text" name="nume" class="form-control" value="<?php echo $editProduct['nume']; ?>" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Brand</label>
                        <select name="brand_id" class="form-select" required>
                            <?php foreach($brands as $brand): ?>
                                <option value="<?php echo $brand['id']; ?>" <?php echo $brand['id'] == $editProduct['brand_id'] ? 'selected' : ''; ?>>
                                    <?php echo $brand['nume']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Categorie</label>
                        <select name="categorie_id" class="form-select" required>
                            <?php foreach($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo $cat['id'] == $editProduct['categorie_id'] ? 'selected' : ''; ?>>
                                    <?php echo $cat['nume']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Descriere</label>
                        <textarea name="descriere" class="form-control" rows="3" required><?php echo $editProduct['descriere']; ?></textarea>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Preț (lei)</label>
                        <input type="number" name="pret" class="form-control" step="0.01" value="<?php echo $editProduct['pret']; ?>" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Stoc</label>
                        <input type="number" name="stoc" class="form-control" value="<?php echo $editProduct['stoc']; ?>" required>
                    </div>
                 <div class="col-md-6 mb-3">
                     <label class="form-label">Imagini produs (opțional)</label>
                     <input type="file" name="imagini[]" class="form-control" multiple accept="image/*">
                     <small class="text-muted">Poți selecta mai multe imagini simultan</small>
                 </div>
                </div>
                <button type="submit" class="btn btn-dark">Salvează modificările</button>
                <a href="index.php?page=admin_products" class="btn btn-outline-dark ms-2">Anulează</a>
            </form>
        </div>
    </div>
    <?php else: ?>
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Adaugă produs nou</h5>
        </div>
        <div class="card-body">
            <form action="index.php?page=admin_products" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="add">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nume produs</label>
                        <input type="text" name="nume" class="form-control" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Brand</label>
                        <select name="brand_id" class="form-select" required>
                            <?php foreach($brands as $brand): ?>
                                <option value="<?php echo $brand['id']; ?>"><?php echo $brand['nume']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Categorie</label>
                        <select name="categorie_id" class="form-select" required>
                            <?php foreach($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>"><?php echo $cat['nume']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Descriere</label>
                        <textarea name="descriere" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Preț (lei)</label>
                        <input type="number" name="pret" class="form-control" step="0.01" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Stoc</label>
                        <input type="number" name="stoc" class="form-control" required>
                    </div>
                   <div class="col-md-6 mb-3">
                        <label class="form-label">Imagini produs</label>
                        <input type="file" name="imagini[]" class="form-control" multiple accept="image/*">
                        <small class="text-muted">Poți selecta mai multe imagini simultan</small>
                   </div>
                </div>
                <button type="submit" class="btn btn-dark">Adaugă produs</button>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nume</th>
                        <th>Brand</th>
                        <th>Categorie</th>
                        <th>Preț</th>
                        <th>Stoc</th>
                        <th>Acțiuni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($products as $p): ?>
                        <tr>
                            <td><?php echo $p['id']; ?></td>
                            <td><?php echo $p['nume']; ?></td>
                            <td><?php echo $p['brand_nume']; ?></td>
                            <td><?php echo $p['categorie_nume']; ?></td>
                            <td><?php echo number_format($p['pret'], 2); ?> lei</td>
                            <td><?php echo $p['stoc']; ?></td>
                            <td>
                                <a href="index.php?page=admin_products&action=edit&id=<?php echo $p['id']; ?>"
                                   class="btn btn-warning btn-sm me-1">Editează</a>
                                <a href="index.php?page=admin_products&action=delete&id=<?php echo $p['id']; ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Ești sigură că vrei să ștergi acest produs?')">
                                   Șterge
                                </a>
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