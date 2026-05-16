<?php

require_once __DIR__ . '/../app/helpers/functions.php';

$pageTitle = page_title('Home');
$featured = get_featured_movies(5);
$heroMovies = search_movies();
$heroMovie = $heroMovies ? $heroMovies[array_rand($heroMovies)] : $featured[0];

require __DIR__ . '/../app/includes/header.php';

require __DIR__ . '/../app/includes/alerts.php';
?>

<section class="hero">
    <div class="container">
        <div class="simple-grid">
            <div>
                <p class="section-label">Movie Journal Website</p>
                <h1>Keep your favorite movies in one simple place.</h1>
                <p class="lead-text">Build a personal watchlist, capture your ratings, and revisit the films that stay with you.</p>
                <div class="button-row">
                    <a href="search.php" class="button button-hero">Browse Movies</a>
                    <a href="register.php" class="button button-light button-hero">Create Account</a>
                </div>
                <div class="info-grid">
                    <div class="info-card">
                        <strong>Search</strong>
                        <p>Find movies by title or genre.</p>
                    </div>
                    <div class="info-card">
                        <strong>Rate</strong>
                        <p>Give any movie a score from 1 to 5.</p>
                    </div>
                    <div class="info-card">
                        <strong>Save</strong>
                        <p>Keep favorites and comments in your account.</p>
                    </div>
                </div>
            </div>
            <div class="hero-image-box">
                <img src="<?php echo asset($heroMovie['poster']); ?>" alt="<?php echo htmlspecialchars($heroMovie['title']); ?> poster">
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="title-row">
            <div>
                <p class="section-label">Featured Movies</p>
                <h2>Some movies ready to explore</h2>
            </div>
            <a href="search.php" class="simple-link">Open all movies</a>
        </div>
        <div class="movie-grid">
            <?php foreach ($featured as $movie): ?>
                <a class="movie-card" href="movie.php?slug=<?php echo urlencode($movie['slug']); ?>">
                    <img src="<?php echo asset($movie['poster']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?> poster">
                    <div class="movie-card-body">
                        <p class="card-note"><?php echo htmlspecialchars($movie['genre_name'] ?? 'Genre'); ?> | <?php echo htmlspecialchars((string) $movie['release_year']); ?></p>
                        <h3><?php echo htmlspecialchars($movie['title']); ?></h3>
                        <p><?php echo htmlspecialchars($movie['description']); ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../app/includes/footer.php'; ?>
