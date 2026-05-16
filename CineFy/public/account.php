<?php

require_once __DIR__ . '/../app/helpers/functions.php';

require_login();

$user = current_user();
$stats = get_user_stats((int)$user['id']);
$activity = user_recent_activity((int)$user['id']);

$pageTitle = page_title('Account');

require __DIR__ . '/../app/includes/header.php';
require __DIR__ . '/../app/includes/alerts.php';

?>

<section class="section account-hero-section">
    <div class="container">
        <div class="panel profile-top">
            <div class="profile-box">
                <div class="account-avatar" aria-label="<?php echo htmlspecialchars($user['username']); ?> profile picture">
                    <?php echo htmlspecialchars(strtoupper(substr($user['username'], 0, 1))); ?>
                </div>
                <div>
                    <p class="section-label">My Account</p>
                    <h1><?php echo htmlspecialchars($user['username']); ?></h1>
                    <p><?php echo htmlspecialchars($user['email']); ?></p>
                </div>
            </div>
            <div class="button-row">
                <a class="button" href="#favorites">Favorites</a>
                <a class="button button-light" href="#ratings">Ratings</a>
                <a class="button button-light" href="#comments">Comments</a>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="info-grid">
            <div class="info-card">
                <p>Favorites</p>
                <strong><?php echo $stats['favorites_count']; ?></strong>
            </div>
            <div class="info-card">
                <p>Ratings</p>
                <strong><?php echo $stats['ratings_count']; ?></strong>
            </div>
            <div class="info-card">
                <p>Comments</p>
                <strong><?php echo $stats['comments_count']; ?></strong>
            </div>
        </div>

        <div class="simple-grid">
            <div>
                <div class="panel">
                    <h3>Update profile</h3>
                    <form method="POST" action="../app/handlers/update-profile-handler.php">
                        <label for="username">Username</label>
                        <input id="username" type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>">

                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">

                        <button class="button" type="submit">Save changes</button>
                    </form>
                </div>

                <div class="panel" id="favorites">
                    <p class="section-label">Favorites Preview</p>
                    <h3>Saved movies</h3>
                    <?php if ($activity['favorites']): ?>
                        <div class="poster-grid">
                            <?php foreach ($activity['favorites'] as $favorite): ?>
                                <a href="movie.php?slug=<?php echo urlencode($favorite['slug']); ?>" class="poster-link">
                                    <img src="<?php echo asset($favorite['poster']); ?>" alt="<?php echo htmlspecialchars($favorite['title']); ?>">
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p>No favorites yet.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div>
                <div class="panel" id="ratings">
                    <p class="section-label">Recent Ratings</p>
                    <h3>Your scores</h3>
                    <?php if ($activity['ratings']): ?>
                        <?php foreach ($activity['ratings'] as $rating): ?>
                            <div class="list-row">
                                <strong><?php echo htmlspecialchars($rating['title']); ?></strong>
                                <p class="card-note"><?php echo htmlspecialchars(format_date($rating['created_at'])); ?></p>
                                <p><?php echo (int) $rating['rating_value']; ?>/5</p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No ratings yet.</p>
                    <?php endif; ?>
                </div>

                <div class="panel" id="comments">
                    <p class="section-label">Recent Comments</p>
                    <h3>Your latest comments</h3>
                    <?php if ($activity['comments']): ?>
                        <?php foreach ($activity['comments'] as $comment): ?>
                            <div class="list-row">
                                <strong><?php echo htmlspecialchars($comment['title']); ?></strong>
                                <p class="card-note"><?php echo htmlspecialchars(format_date($comment['created_at'])); ?></p>
                                <p><?php echo htmlspecialchars($comment['comment_text']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No comments yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../app/includes/footer.php'; ?>
