<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parolă nouă - Skincare Shop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/skincare-ecommerce/public/assets/css/style.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">Skincare Shop</a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link" href="index.php?page=login">Login</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-body p-4">
                    <h3 class="text-center mb-4">Parolă nouă</h3>

                    <?php if(isset($success)): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                        <div class="text-center mt-3">
                            <a href="index.php?page=login" class="btn btn-dark">Mergi la login</a>
                        </div>
                    <?php elseif(isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                        <div class="text-center mt-3">
                            <a href="index.php?page=forgot_password" class="btn btn-outline-dark">Încearcă din nou</a>
                        </div>
                    <?php else: ?>
                        <form action="index.php?page=reset_password&token=<?php echo $_GET['token']; ?>" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Parolă nouă</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirmă parola nouă</label>
                                <input type="password" name="password_confirm" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-dark w-100">Schimbă parola</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>