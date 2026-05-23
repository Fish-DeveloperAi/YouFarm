    <?php
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'YouFarm' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@600;700&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <?php if (!empty($styles)): ?>
        <link rel="stylesheet" href="<?= str_replace('index.php?url=', '', BASE_URL) ?>assets/css/<?= $styles ?>">
    <?php endif; ?>
    <script>
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            // The page was loaded from bfcache (back button)
            location.reload();
        }
    });
</script>
</head>
<body>
<header>
    <div class="container nav-wrapper">
        <div class="logo">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="logo-icon">
                <path d="M2 22h20"></path>
                <path d="M12 22v-9"></path>
                <path d="M9 13c-2.67-1-6-2.5-6-6a5.5 5.5 0 0 1 7.6-4.9l.4.2.4-.2A5.5 5.5 0 0 1 19 7c0 3.5-3.33 5-6 6"></path>
            </svg>
            YouFarm
        </div>
        <ul class="nav-links">
            <li><a href="<?= BASE_URL ?>">Home</a></li>
            <li><a href="<?= BASE_URL ?>data">Data</a></li>
            <li><a href="<?= BASE_URL ?>shop">Shop</a></li>
            <li><a href="<?= BASE_URL ?>about">About Us</a></li>
            <li><a href="<?= BASE_URL ?>contact">Contact Us</a></li>
        </ul>
<?php if (isset($_SESSION['user_id'])): ?>
    <?php
    // Safe cart count
    if (!class_exists('Cart')) {
        require_once __DIR__ . '/../../models/Cart.php';
    }
    $cartModel = new Cart();
    $cartCount = $cartModel->countItems($_SESSION['user_id']);
    ?>
            <a href="<?= BASE_URL ?>cart" class="cart-btn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                <span class="cart-count"><?= $cartCount ?></span>
            </a>
            <a href="<?= BASE_URL ?>logout" class="btn-signin">Logout</a>
        <?php else: ?>
            <a href="<?= BASE_URL ?>login" class="btn-signin">Sign In</a>
        <?php endif; ?>
    </div>
</header>