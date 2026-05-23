<?php require 'layouts/auth_header.php'; ?>
<div class="container login-container">
    <h2>Welcome Back</h2>
    <?php if (isset($error)): ?>
        <p style="color:#ef4444; text-align:center; margin-bottom:1rem"><?= $error ?></p>
    <?php endif; ?>
    <form class="login-form" method="POST" action="<?= BASE_URL ?>login">
        <div class="input-group">
            <label>Email Address</label>
            <input type="email" name="email" placeholder="Enter Your Email" required>
        </div>
        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Enter Your Password" required>
        </div>
        <div class="options-row">
            <label class="remember-me">
                <input type="checkbox" name="remember"> Remember me
            </label>
            <a href="<?= BASE_URL ?>register" class="forgot-link">Register?</a>
        </div>
        <button type="submit">Login</button>
    </form>
</div>
<?php require 'layouts/auth_footer.php'; ?>