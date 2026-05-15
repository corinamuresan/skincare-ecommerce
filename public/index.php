<?php
session_start();
require_once __DIR__ . '/../config/database.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

if($page === 'logout') {
    require_once __DIR__ . '/../app/controllers/AuthController.php';
    exit();
}

if($page === 'register') {
    require_once __DIR__ . '/../app/controllers/AuthController.php';
    require_once __DIR__ . '/../app/views/auth/register.php';
    exit();
}

if($page === 'login') {
    require_once __DIR__ . '/../app/controllers/AuthController.php';
    require_once __DIR__ . '/../app/views/auth/login.php';
    exit();
}

if($page === 'products') {
    require_once __DIR__ . '/../app/controllers/ProductController.php';
    require_once __DIR__ . '/../app/views/client/products.php';
    exit();
}

if($page === 'product') {
    require_once __DIR__ . '/../app/controllers/ProductController.php';
    require_once __DIR__ . '/../app/views/client/product.php';
    exit();
}

if($page === 'quiz') {
    if(!isset($_SESSION['user_id'])) {
        header('Location: index.php?page=login');
        exit();
    }
    require_once __DIR__ . '/../app/controllers/QuizController.php';
    require_once __DIR__ . '/../app/views/client/quiz.php';
    exit();
}

if($page === 'recommendations') {
    if(!isset($_SESSION['user_id'])) {
        header('Location: index.php?page=login');
        exit();
    }
    require_once __DIR__ . '/../app/controllers/RecommendationsController.php';
    require_once __DIR__ . '/../app/views/client/recommendations.php';
    exit();
}

if($page === 'cart') {
    if(!isset($_SESSION['user_id'])) {
        header('Location: index.php?page=login');
        exit();
    }
    require_once __DIR__ . '/../app/controllers/CartController.php';
    require_once __DIR__ . '/../app/views/client/cart.php';
    exit();
}

if($page === 'wishlist') {
    if(!isset($_SESSION['user_id'])) {
        header('Location: index.php?page=login');
        exit();
    }
    require_once __DIR__ . '/../app/controllers/WishlistController.php';
    require_once __DIR__ . '/../app/views/client/wishlist.php';
    exit();
}

if($page === 'checkout') {
    if(!isset($_SESSION['user_id'])) {
        header('Location: index.php?page=login');
        exit();
    }
    require_once __DIR__ . '/../app/controllers/CheckoutController.php';
    require_once __DIR__ . '/../app/views/client/checkout.php';
    exit();
}

if($page === 'create_payment_intent') {
    require_once __DIR__ . '/../app/controllers/PaymentController.php';
    exit();
}

if($page === 'order_success') {
    if(!isset($_SESSION['user_id'])) {
        header('Location: index.php?page=login');
        exit();
    }
    require_once __DIR__ . '/../app/controllers/OrderSuccessController.php';
    require_once __DIR__ . '/../app/views/client/order_success.php';
    exit();
}

if($page === 'orders') {
    if(!isset($_SESSION['user_id'])) {
        header('Location: index.php?page=login');
        exit();
    }
    require_once __DIR__ . '/../app/controllers/OrdersController.php';
    require_once __DIR__ . '/../app/views/client/orders.php';
    exit();
}

if($page === 'admin') {
    if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
        header('Location: index.php');
        exit();
    }
    require_once __DIR__ . '/../app/controllers/AdminController.php';
    require_once __DIR__ . '/../app/views/admin/dashboard.php';
    exit();
}

if($page === 'admin_orders') {
    if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
        header('Location: index.php');
        exit();
    }
    require_once __DIR__ . '/../app/controllers/AdminOrdersController.php';
    require_once __DIR__ . '/../app/views/admin/orders.php';
    exit();
}

if($page === 'admin_products') {
    if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
        header('Location: index.php');
        exit();
    }
    require_once __DIR__ . '/../app/controllers/AdminProductsController.php';
    require_once __DIR__ . '/../app/views/admin/products.php';
    exit();
}

if($page === 'profile') {
    if(!isset($_SESSION['user_id'])) {
        header('Location: index.php?page=login');
        exit();
    }
    require_once __DIR__ . '/../app/controllers/ProfileController.php';
    require_once __DIR__ . '/../app/views/client/profile.php';
    exit();
}

if($page === 'invoice') {
    if(!isset($_SESSION['user_id'])) {
        header('Location: index.php?page=login');
        exit();
    }
    require_once __DIR__ . '/../app/controllers/InvoiceController.php';
    exit();
}

if($page === 'forgot_password') {
    require_once __DIR__ . '/../app/controllers/ForgotPasswordController.php';
    require_once __DIR__ . '/../app/views/auth/forgot_password.php';
    exit();
}

if($page === 'reset_password') {
    require_once __DIR__ . '/../app/controllers/ResetPasswordController.php';
    require_once __DIR__ . '/../app/views/auth/reset_password.php';
    exit();
}

switch($page) {
    case 'home':
        require_once __DIR__ . '/../app/views/client/home.php';
        break;
    default:
        require_once __DIR__ . '/../app/views/client/home.php';
        break;
}