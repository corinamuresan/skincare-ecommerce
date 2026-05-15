<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Profil Ten - Skincare Shop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/skincare-ecommerce/public/assets/css/style.css">
    <style>
        .quiz-container { max-width: 600px; margin: 80px auto; }
        .answer-option { border: 1px solid #dee2e6; border-radius: 8px; padding: 14px 20px; margin-bottom: 12px; cursor: pointer; transition: all 0.2s; }
        .answer-option:hover { border-color: #2D5016; background-color: #f8f9fa; }
        .answer-option input[type="radio"], .answer-option input[type="checkbox"] { margin-right: 12px; }
        .btn-next { background-color: #2D5016; color: #fff; border: none; padding: 12px 40px; border-radius: 6px; font-size: 15px; }
        .btn-next:hover { background-color: #4A7C2F; color: #fff; }
        .question-counter { color: #999; font-size: 13px; margin-bottom: 10px; }
    </style>
</head>
<body class="bg-white">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">Skincare Shop</a>
        <div class="navbar-nav ms-auto">
            <?php if(isset($_SESSION['user_id'])): ?>
                <a class="nav-link" href="index.php?page=profile">Contul meu</a>
                <a class="nav-link" href="index.php?page=logout">Logout</a>
            <?php else: ?>
                <a class="nav-link" href="index.php?page=login">Login</a>
                <a class="nav-link" href="index.php?page=register">Înregistrare</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="quiz-container">
    <?php if(isset($success)): ?>
        <div class="text-center">
            <h3 class="mb-3">Profilul tău a fost salvat! 🎉</h3>
            <p class="text-muted">Acum îți putem recomanda produsele potrivite pentru tine.</p>
            <a href="index.php?page=recommendations" class="btn btn-next mt-3">Vezi recomandările tale</a>
        </div>
    <?php elseif(isset($intrebare)): ?>
        <form action="index.php?page=quiz" method="POST">
            <input type="hidden" name="intrebare_id" value="<?php echo $intrebare['id']; ?>">
            <input type="hidden" name="intrebare_nr" value="<?php echo $intrebare_nr; ?>">

            <p class="question-counter">Întrebarea <?php echo $intrebare_nr; ?> din <?php echo $total_intrebari; ?></p>
            <h4 class="mb-4"><?php echo $intrebare['text_intrebare']; ?></h4>

            <?php if($intrebare_nr == 8 || $intrebare_nr == 9): ?>
                <?php foreach($raspunsuri as $r): ?>
                    <label class="answer-option d-block">
                        <input type="checkbox" name="raspuns_id[]" value="<?php echo $r['id']; ?>">
                        <?php echo $r['text_raspuns']; ?>
                    </label>
                <?php endforeach; ?>
            <?php else: ?>
                <?php foreach($raspunsuri as $r): ?>
                    <label class="answer-option d-block">
                        <input type="radio" name="raspuns_id" value="<?php echo $r['id']; ?>" required>
                        <?php echo $r['text_raspuns']; ?>
                    </label>
                <?php endforeach; ?>
            <?php endif; ?>

            <div class="mt-4">
                <button type="submit" class="btn btn-next">
                    <?php echo ($intrebare_nr == $total_intrebari) ? 'Finalizează' : 'Următoarea →'; ?>
                </button>
            </div>
        </form>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../views/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>