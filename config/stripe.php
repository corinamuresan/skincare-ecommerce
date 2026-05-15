<?php

require_once __DIR__ . '/../vendor/autoload.php';

define('STRIPE_PUBLIC_KEY', 'pk_test_51TVvkm0lcGbshl2Fxm9qK8GYJMPGkI8JQ13fPBnCmNBvwHpT4kaxzSyvNorHNXfBNiXTcA1xs6zJl8I4RHsVznns00zig2M5wz');
define('STRIPE_SECRET_KEY', 'sk_test_51TVvkm0lcGbshl2Fn1e4S3RlbuEyJdgMMKfShJQog0yJE3bFdxE7UcV8qOcnMSBoESMMFuT1hwWUZjTxUGTWpDdL00gjxgghNw');

\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);