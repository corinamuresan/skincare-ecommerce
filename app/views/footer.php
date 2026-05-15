<footer style="background-color: #1A3009; color: #E8DCC8; padding: 40px 0 20px; margin-top: 60px;">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <h5 style="color: #F5F0E8; font-weight: 500;">Skincare Shop</h5>
                <p style="font-size: 13px; color: #C0DD97; line-height: 1.7;">Platformă e-commerce pentru produse de skincare cu sistem de recomandare personalizată bazat pe profilul tău de ten.</p>
            </div>
            <div class="col-md-4 mb-3">
                <h6 style="color: #F5F0E8; font-weight: 500; margin-bottom: 12px;">Link-uri rapide</h6>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li style="margin-bottom: 8px;"><a href="index.php" style="color: #E8DCC8; text-decoration: none; font-size: 13px;">Acasă</a></li>
                    <li style="margin-bottom: 8px;"><a href="index.php?page=products" style="color: #E8DCC8; text-decoration: none; font-size: 13px;">Catalog produse</a></li>
                    <li style="margin-bottom: 8px;"><a href="index.php?page=quiz" style="color: #E8DCC8; text-decoration: none; font-size: 13px;">Quiz profil ten</a></li>
                    <li style="margin-bottom: 8px;"><a href="index.php?page=recommendations" style="color: #E8DCC8; text-decoration: none; font-size: 13px;">Recomandări</a></li>
                </ul>
            </div>
            <div class="col-md-4 mb-3">
                <h6 style="color: #F5F0E8; font-weight: 500; margin-bottom: 12px;">Cont</h6>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li style="margin-bottom: 8px;"><a href="index.php?page=profile" style="color: #E8DCC8; text-decoration: none; font-size: 13px;">Profilul meu</a></li>
                        <li style="margin-bottom: 8px;"><a href="index.php?page=orders" style="color: #E8DCC8; text-decoration: none; font-size: 13px;">Comenzile mele</a></li>
                        <li style="margin-bottom: 8px;"><a href="index.php?page=wishlist" style="color: #E8DCC8; text-decoration: none; font-size: 13px;">Wishlist</a></li>
                        <li style="margin-bottom: 8px;"><a href="index.php?page=logout" style="color: #E8DCC8; text-decoration: none; font-size: 13px;">Logout</a></li>
                    <?php else: ?>
                        <li style="margin-bottom: 8px;"><a href="index.php?page=login" style="color: #E8DCC8; text-decoration: none; font-size: 13px;">Login</a></li>
                        <li style="margin-bottom: 8px;"><a href="index.php?page=register" style="color: #E8DCC8; text-decoration: none; font-size: 13px;">Înregistrare</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <hr style="border-color: #3B6D11; margin-bottom: 16px;">
        <div class="d-flex justify-content-between align-items-center">
            <p style="font-size: 12px; color: #C0DD97; margin: 0;">© 2026 Skincare Shop. Toate drepturile rezervate.</p>
            <p style="font-size: 12px; color: #C0DD97; margin: 0;">Plăți securizate prin Stripe</p>
        </div>
    </div>
</footer>