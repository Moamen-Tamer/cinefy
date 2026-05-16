<?php

require_once __DIR__ . '/../app/helpers/functions.php';

$slug = raw_value($_GET['slug'] ?? '');
$movie = get_movie_by_slug($slug);

if (!$movie) {
    set_note('danger', 'Movie not found.');
    redirect('search.php');
}

$pageTitle = page_title($movie['title']);

$comments = get_movie_comments((int)$movie['id']);
$averageRating = get_movie_average_rating((int)$movie['id']);
$related = get_related_movies((int)$movie['genre_id'], (int)$movie['id']);
$userRating = is_logged() ? get_user_rating_for_movie((int)$_SESSION['user_id'], (int)$movie['id']) : null;
$isFavorite = is_logged() ? is_favorite_movie((int)$_SESSION['user_id'], (int)$movie['id']) : false;

require __DIR__ . '/../app/includes/header.php';
require __DIR__ . '/../app/includes/alerts.php';

?>

<section class="section movie-hero-section">
    <div class="movie-hero-overlay"></div>
    <div class="container">
        <div class="simple-grid movie-top">
            <div class="poster-box">
                <img class="movie-poster-large" src="<?php echo asset($movie['poster']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?> poster">
            </div>
            <div>
                <p class="section-label"><?php echo htmlspecialchars($movie['genre_name'] ?? 'Movie'); ?></p>
                <h1><?php echo htmlspecialchars($movie['title']); ?></h1>
                        <p class="meta-line"><?php echo htmlspecialchars((string) $movie['release_year']); ?> | <?php echo htmlspecialchars($movie['director']); ?> | <?php echo htmlspecialchars(format_runtime((int) $movie['duration_minutes'])); ?></p>
                <p class="lead-text"><?php echo htmlspecialchars($movie['description']); ?></p>
                <div class="panel score-box">
                    <p>Average rating</p>
                    <strong><?php echo number_format($averageRating, 1); ?>/5</strong>
                    <p><?php echo stars_from_rating($averageRating); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="simple-grid">
            <div>
                <div class="panel">
                    <h3>Favorite this movie</h3>
                    <p>Save this movie to your personal favorites list.</p>
                    <?php if (is_logged()): ?>
                        <form method="POST" action="../app/handlers/toggle-favorite-handler.php">
                            <input type="hidden" name="movie_id" value="<?php echo (int) $movie['id']; ?>">
                            <input type="hidden" name="slug" value="<?php echo htmlspecialchars($movie['slug']); ?>">
                            <button class="button <?php echo $isFavorite ? 'button-light' : ''; ?>" type="submit">
                                <?php echo $isFavorite ? 'Remove from favorites' : 'Add to favorites'; ?>
                            </button>
                        </form>
                    <?php else: ?>
                        <a class="button" href="login.php">Log in to favorite</a>
                    <?php endif; ?>
                </div>

                <div class="panel">
                    <h3>Rate this movie</h3>
                    <p>Choose a number from 1 to 5 and save your rating.</p>
                    <?php if (is_logged()): ?>
                        <form method="POST" action="../app/handlers/add-rating-handler.php">
                            <input type="hidden" name="movie_id" value="<?php echo (int) $movie['id']; ?>">
                            <input type="hidden" name="slug" value="<?php echo htmlspecialchars($movie['slug']); ?>">
                            <div class="rating-list">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <label class="radio-line">
                                        <input type="radio" name="rating_value" value="<?php echo $i; ?>" <?php echo $userRating === $i ? 'checked' : ''; ?>>
                                        <span><?php echo $i; ?> star</span>
                                    </label>
                                <?php endfor; ?>
                            </div>
                            <button class="button" type="submit">Save rating</button>
                        </form>
                    <?php else: ?>
                        <a class="button button-light" href="login.php">Log in to rate</a>
                    <?php endif; ?>
                </div>
            </div>

            <div>
                <div class="panel">
                    <h3>Write a comment</h3>
                    <p>Write a short opinion about this movie.</p>
                    <?php if (is_logged()): ?>
                        <form method="POST" action="../app/handlers/add-comment-handler.php">
                            <input type="hidden" name="movie_id" value="<?php echo (int) $movie['id']; ?>">
                            <input type="hidden" name="slug" value="<?php echo htmlspecialchars($movie['slug']); ?>">
                            <label for="comment_text">Comment</label>
                            <textarea id="comment_text" name="comment_text" rows="5" placeholder="Write your comment here"></textarea>
                            <button class="button" type="submit">Post comment</button>
                        </form>
                    <?php else: ?>
                        <a class="button" href="login.php">Log in to comment</a>
                    <?php endif; ?>
                </div>

                <div class="panel">
                    <p class="section-label">Comments</p>
                    <h3>Recent comments</h3>
                    <?php if ($comments): ?>
                        <?php foreach ($comments as $comment): ?>
                            <div class="list-row">
                                <strong><?php echo htmlspecialchars($comment['username']); ?></strong>
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

        <div class="title-row">
            <div>
                <p class="section-label">More Movies</p>
                <h2>Related films</h2>
            </div>
        </div>
        <div class="movie-grid">
            <?php foreach ($related as $relatedMovie): ?>
                <a class="movie-card" href="movie.php?slug=<?php echo urlencode($relatedMovie['slug']); ?>">
                    <img src="<?php echo asset($relatedMovie['poster']); ?>" alt="<?php echo htmlspecialchars($relatedMovie['title']); ?> poster">
                    <div class="movie-card-body">
                        <h3><?php echo htmlspecialchars($relatedMovie['title']); ?></h3>
                        <p><?php echo htmlspecialchars((string) $relatedMovie['release_year']); ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../app/includes/footer.php'; ?>
