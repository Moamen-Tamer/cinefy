<?php

require_once __DIR__ . '/../app/helpers/functions.php';

if (is_logged()) redirect('account.php');

$pageTitle = page_title('Register');

require __DIR__ . '/../app/includes/header.php';
require __DIR__ . '/../app/includes/alerts.php';

?>

<section class="section">
    <div class="container">
        <div class="panel narrow-box">
            <p class="section-label">Register</p>
            <h1>Create a new account</h1>
            <p>Fill in the form to start using the movie journal.</p>
            <form method="POST" action="../app/handlers/register-handler.php">
                <label for="username">Username</label>
                <input id="username" type="text" name="username" value="<?php echo get_old_input('username'); ?>" required>

                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="<?php echo get_old_input('email'); ?>" required>

                <label for="password">Password</label>
                <input id="password" type="password" name="password" required>

                <label for="confirm_password">Confirm password</label>
                <input id="confirm_password" type="password" name="confirm_password" required>

                <button class="button" type="submit">Create account</button>
            </form>
            <p class="switch-text">Already have an account? <a href="login.php">Log in here</a>.</p>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../app/includes/footer.php'; ?>
