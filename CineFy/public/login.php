<?php

require_once __DIR__ . '/../app/helpers/functions.php';

if (is_logged()) redirect('account.php');

$pageTitle = page_title('Login');

require __DIR__ . '/../app/includes/header.php';
require __DIR__ . '/../app/includes/alerts.php';

?>

<section class="section">
    <div class="container">
        <div class="panel narrow-box">
            <p class="section-label">Login</p>
            <h1>Log in to your account</h1>
            <p>Enter your username or email and your password.</p>
            <form method="POST" action="../app/handlers/login-handler.php">
                <label for="login">Email or username</label>
                <input id="login" type="text" name="login" value="<?php echo get_old_input('login'); ?>" required>

                <label for="password">Password</label>
                <input id="password" type="password" name="password" required>

                <button class="button" type="submit">Log in</button>
            </form>
            <p class="switch-text">Need an account? <a href="register.php">Register here</a>.</p>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../app/includes/footer.php'; ?>
