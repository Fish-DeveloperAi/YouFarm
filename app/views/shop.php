    <?php require 'layouts/header.php'; ?>
<main>
    <section class="shop-header container">
        <div class="header-content">
            <h1>Farm Essentials</h1>
            <p>Premium tools, seeds, and fertilizers for the modern grower.</p>
        </div>
        <div class="search-bar">
            <input type="text" placeholder="Search products..." id="searchInput">
            <button>Search</button>
        </div>
    </section>

    <section class="shop-section container">
        <div class="product-grid">
            <?php foreach ($products as $p): ?>
            <article class="product-card">
                <div class="image-container">
                    <?php if ($p['on_sale']): ?>
                        <span class="badge-sale">Sale</span>
                    <?php endif; ?>
                    <img src="<?= rtrim(dirname(BASE_URL . 'x'), '/x') . '/assets/images/' ?><?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
                </div>
                <div class="info">
                    <p class="category"><?= htmlspecialchars($p['category']) ?></p>
                    <h3><?= htmlspecialchars($p['name']) ?></h3>
                    <div class="price-row">
                        <p class="price">$<?= number_format($p['price'], 2) ?></p>
                        <button class="add-btn" data-product-id="<?= $p['id'] ?>">Add to Cart</button>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<script>
// AJAX add-to-cart
document.querySelectorAll('.add-btn').forEach(btn => {
    btn.addEventListener('click', async () => {
        const productId = btn.dataset.productId;
        try {
            const res = await fetch('<?= BASE_URL ?>cart/add', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'product_id=' + productId
            });
            const data = await res.json();
            if (data.success) {
                // Update cart badge
                const badge = document.querySelector('.cart-count');
                if (badge) badge.textContent = data.cartCount;
                // optional: tiny notification
            } else {
                alert(data.error || 'Failed to add item');
            }
        } catch (e) {
            console.error(e);
        }
    });
});
</script>
<?php require 'layouts/footer.php'; ?>