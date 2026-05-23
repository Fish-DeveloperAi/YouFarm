<?php require 'layouts/auth_header.php'; ?>
<div class="container register-container">
    <h2>Create Account</h2>
    <?php if (isset($error)): ?>
        <p style="color:#ef4444; margin-bottom:1rem"><?= $error ?></p>
    <?php endif; ?>
    <form class="register-form" method="POST" action="<?= BASE_URL ?>register">
        <div class="input-group">
            <label>First Name</label>
            <input type="text" name="firstname" placeholder="First Name" required>
        </div>
        <div class="input-group">
            <label>Last Name</label>
            <input type="text" name="lastname" placeholder="Last Name" required>
        </div>
        <div class="input-group full-width">
            <label>Email Address</label>
            <input type="email" name="email" placeholder="example@mail.com" required>
        </div>
        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="••••••••" required>
        </div>
        <div class="input-group">
            <label>National ID</label>
            <input type="text" name="cin" placeholder="CIN Number" required>
        </div>
        <div class="input-group">
            <label>Property Title</label>
            <input type="text" name="title" placeholder="Title Number" required>
        </div>
        <div class="input-group">
            <label>Age</label>
            <input type="number" name="age" placeholder="18+" required>
        </div>
        <button type="submit" class="full-width">Register</button>
        <div class="login-link full-width">
            Already have an account? <a href="<?= BASE_URL ?>login">Login</a>
        </div>
    </form>
</div>
<?php require 'layouts/auth_footer.php'; ?>