<?php require 'layouts/header.php'; ?>
<div class="container cart-page">
    <h1>Your Cart</h1>
    <?php if (empty($items)): ?>
        <p>Your cart is empty. <a href="<?= BASE_URL ?>shop">Browse products</a></p>
    <?php else: ?>
        <table class="cart-table">
            <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Total</th><th></th></tr></thead>
            <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td>
                        <img src="<?= str_replace('index.php?url=', '', BASE_URL) ?>assets/images/<?= htmlspecialchars($item['image']) ?>" width="50" alt="">
                        <?= htmlspecialchars($item['name']) ?>
                    </td>
                    <td>$<?= number_format($item['price'], 2) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td>$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                    <td>
                        <form method="POST" action="<?= BASE_URL ?>cart/remove" style="display:inline">
                            <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                            <button type="submit">Remove</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <form method="POST" action="<?= BASE_URL ?>cart/clear">
            <button type="submit">Clear Cart</button>
        </form>
    <?php endif; ?>
</div>
<?php require 'layouts/footer.php'; ?>