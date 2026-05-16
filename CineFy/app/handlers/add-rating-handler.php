<?php

require_once __DIR__ . '/../helpers/functions.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('../../public/search.php');

$movieId = (int)(raw_value($_POST['movie_id'] ?? 0));
$slug = raw_value($_POST['slug'] ?? '');
$ratingValue = (int)(raw_value($_POST['rating_value'] ?? 0));

if ($movieId <= 0 || $ratingValue < 1 || $ratingValue > 5) {
    set_note('danger', 'Please choose a rating between 1 and 5.');
    redirect('../../public/movie.php?slug=' . urlencode($slug));
}

$pdo = db();

if (!$pdo) {
    set_note('warning', 'Database is not connected yet. Ratings cannot be saved.');
    redirect('../../public/movie.php?slug=' . urlencode($slug));
}

$query = 'INSERT INTO ratings (userId, movieId, ratingValue, createdAt)
          VALUES (?, ?, ?, NOW())
          ON DUPLICATE KEY UPDATE ratingValue = VALUES(ratingValue), createdAt = NOW()';

$statement = $pdo->prepare($query);
$statement->execute([$_SESSION['user_id'], $movieId, $ratingValue]);

set_note('success', 'Your rating has been saved.');
redirect('../../public/movie.php?slug=' . urlencode($slug));
