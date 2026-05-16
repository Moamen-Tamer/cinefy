<?php $user = current_user(); ?>

<nav class="site-navbar">
    <div class="container nav-wrap">
        <a class="brand-mark" href="index.php" aria-label="CineFy home">
            <img src="<?php echo asset('logo/CineFy-footer.png'); ?>" alt="CineFy logo">
        </a>

        <div class="nav-links">
            <a class="nav-link <?php echo $currentPage === 'index.php' ? 'active' : ''; ?>" href="index.php">Home</a>
            <a class="nav-link <?php echo $currentPage === 'search.php' ? 'active' : ''; ?>" href="search.php">Movies</a>

            <?php if (is_logged()): ?>
                <a class="nav-link <?php echo $currentPage === 'account.php' ? 'active' : ''; ?>" href="account.php">Account</a>
                <a class="nav-link <?php echo $currentPage === 'account.php' ? 'active' : ''; ?>" href="account.php#favorites">Favorites</a>
                <a class="button button-small" href="logout.php">Logout</a>
            <?php else: ?>
                <a class="nav-link <?php echo $currentPage === 'register.php' ? 'active' : ''; ?>" href="register.php">Register</a>
                <a class="button button-small" href="login.php">Login</a>
            <?php endif; ?>
        </div>

        <?php if ($user): ?>
            <div class="nav-user">
                <span>Signed in as</span>
                <strong><?php echo htmlspecialchars($user['username']); ?></strong>
            </div>
        <?php endif; ?>
    </div>
</nav>
