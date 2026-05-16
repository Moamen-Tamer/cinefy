<?php

require_once __DIR__ . '/../app/helpers/functions.php';

$pageTitle = page_title('Search');

$search = raw_value($_GET['q'] ?? '');
$genre = raw_value($_GET['genre'] ?? '');
$sort = raw_value($_GET['sort'] ?? 'title');

$genres = get_all_genres();
$movies = search_movies($search, $genre, $sort);

require __DIR__ . '/../app/includes/header.php';
require __DIR__ . '/../app/includes/alerts.php';

?>

<section class="section">
    <div class="container">
        <p class="section-label">Movies</p>
        <h1>Search the movie list</h1>
        <p class="lead-text">Use the form below to find a movie by title, genre, or release order.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <form class="panel form-grid" method="GET">
            <div>
                <label for="q">Search by title</label>
                <input id="q" type="text" name="q" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search for a movie">
            </div>
            <div>
                <label for="genre">Genre</label>
                <select id="genre" name="genre">
                    <option value="">All genres</option>
                    <?php foreach ($genres as $genreOption): ?>
                        <option value="<?php echo htmlspecialchars((string)$genreOption['id']); ?>" <?php echo $genre === (string)$genreOption['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($genreOption['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="sort">Sort</label>
                <select id="sort" name="sort">
                    <option value="title" <?php echo $sort === 'title' ? 'selected' : ''; ?>>Title</option>
                    <option value="newest" <?php echo $sort === 'newest' ? 'selected' : ''; ?>>Newest</option>
                    <option value="oldest" <?php echo $sort === 'oldest' ? 'selected' : ''; ?>>Oldest</option>
                </select>
            </div>
            <div class="form-button">
                <button class="button" type="submit">Search</button>
            </div>
        </form>

        <p class="result-text"><strong><?php echo count($movies); ?></strong> movie(s) found</p>

        <div class="movie-grid">
            <?php foreach ($movies as $movie): ?>
                <a class="movie-card" href="movie.php?slug=<?php echo urlencode($movie['slug']); ?>" data-title="<?php echo htmlspecialchars(strtolower($movie['title'])); ?>">
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
