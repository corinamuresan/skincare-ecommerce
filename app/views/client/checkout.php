<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizare comandă - Skincare Shop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/skincare-ecommerce/public/assets/css/style.css">
    <script src="https://js.stripe.com/v3/"></script>
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
            </div>
            <div class="navbar-nav ms-auto">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a class="nav-link" href="index.php?page=cart">🛒 Coș</a>
                    <a class="nav-link" href="index.php?page=profile">Contul meu</a>
                    <a class="nav-link" href="index.php?page=logout">Logout</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h3 class="mb-4">Finalizare comandă</h3>

    <?php if(empty($cartItems)): ?>
        <div class="alert alert-info">
            Coșul tău este gol. <a href="index.php?page=products">Vezi produsele</a>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-md-7">
                <div class="card shadow-sm p-4 mb-4">
                    <h5 class="mb-3">Detalii livrare</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Prenume</label>
                            <input type="text" id="prenume" class="form-control" placeholder="Prenume">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nume</label>
                            <input type="text" id="nume" class="form-control" placeholder="Nume">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Telefon</label>
                            <input type="text" id="telefon" class="form-control" placeholder="07xxxxxxxx">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Județ</label>
                            <input type="text" id="judet" class="form-control" placeholder="Județ">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Localitate</label>
                            <input type="text" id="localitate" class="form-control" placeholder="Localitate">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cod poștal</label>
                            <input type="text" id="cod_postal" class="form-control" placeholder="Cod poștal">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Adresă</label>
                            <input type="text" id="adresa" class="form-control" placeholder="Strada, număr, bloc, apartament">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Metodă de livrare</label>
                            <select id="metoda_livrare" class="form-select" onchange="updateTotal()">
                                <option value="25">Curier rapid — 2-3 zile lucrătoare — 25 lei</option>
                                <option value="15">Curier standard — 4-5 zile lucrătoare — 15 lei</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm p-4">
                    <h5 class="mb-3">Detalii plată</h5>
                    <div id="card-element" class="form-control p-3 mb-3"></div>
                    <div id="card-errors" class="text-danger small mb-3"></div>
                    <button id="btn-pay" class="btn btn-dark w-100">
                        Plătește <span id="total-display"><?php echo number_format($cartTotal + 25, 2); ?></span> lei
                    </button>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card shadow-sm p-4">
                    <h5 class="mb-3">Sumar comandă</h5>
                    <?php foreach($cartItems as $item): ?>
                        <div class="d-flex justify-content-between mb-2">
                            <span><?php echo $item['nume']; ?> x<?php echo $item['cantitate']; ?></span>
                            <strong><?php echo number_format($item['pret'] * $item['cantitate'], 2); ?> lei</strong>
                        </div>
                    <?php endforeach; ?>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span><?php echo number_format($cartTotal, 2); ?> lei</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Livrare:</span>
                        <span id="livrare-display">25.00 lei</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Total:</strong>
                        <strong><span id="total-sumar"><?php echo number_format($cartTotal + 25, 2); ?></span> lei</strong>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    var cartTotal = <?php echo floatval(str_replace(',', '.', $cartTotal)); ?>;

    function updateTotal() {
        var livrare = parseFloat(document.getElementById('metoda_livrare').value);
        var total = cartTotal + livrare;
        document.getElementById('livrare-display').textContent = livrare.toFixed(2) + ' lei';
        document.getElementById('total-display').textContent = total.toFixed(2);
        document.getElementById('total-sumar').textContent = total.toFixed(2);
        currentTotal = total;
    }

    var currentTotal = cartTotal + 25;

    var stripe = Stripe('<?php echo STRIPE_PUBLIC_KEY; ?>');
    var elements = stripe.elements();
    var cardElement = elements.create('card', {
        hidePostalCode: true
    });
    cardElement.mount('#card-element');

    cardElement.on('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    document.getElementById('btn-pay').addEventListener('click', function() {
        var btn = this;
        btn.disabled = true;
        btn.textContent = 'Se procesează...';
fetch('index.php?page=create_payment_intent', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({
        amount: Math.round(currentTotal * 100),
        prenume: document.getElementById('prenume').value,
        nume: document.getElementById('nume').value,
        telefon: document.getElementById('telefon').value,
        judet: document.getElementById('judet').value,
        localitate: document.getElementById('localitate').value,
        adresa: document.getElementById('adresa').value,
        cod_postal: document.getElementById('cod_postal').value,
        metoda_livrare: document.getElementById('metoda_livrare').options[document.getElementById('metoda_livrare').selectedIndex].text
    })
})
        .then(function(response) { return response.json(); })
        .then(function(data) {
            stripe.confirmCardPayment(data.client_secret, {
                payment_method: {card: cardElement}
            }).then(function(result) {
                if (result.error) {
                    document.getElementById('card-errors').textContent = result.error.message;
                    btn.disabled = false;
                    btn.textContent = 'Plătește ' + currentTotal.toFixed(2) + ' lei';
                } else {
                    window.location.href = 'index.php?page=order_success&payment_intent=' + result.paymentIntent.id;
                }
            });
        });
    });
</script>

<?php require_once __DIR__ . '/../../views/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>