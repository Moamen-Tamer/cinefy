<?php

require_once __DIR__ . '/../helpers/functions.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('../../public/search.php');

$movieId = (int)(raw_value($_POST['movie_id'] ?? 0));
$slug = raw_value($_POST['slug'] ?? '');

if ($movieId <= 0) {
    set_note('danger', 'Invalid movie selected.');
    redirect('../../public/movie.php?slug=' . urlencode($slug));
}

$pdo = db();

if (!$pdo) {
    set_note('warning', 'Database is not connected yet. Favorites cannot be updated.');
    redirect('../../public/movie.php?slug=' . urlencode($slug));
}

$query = 'SELECT id
          FROM favorites
          WHERE userId = ? AND movieId = ?
          LIMIT 1';

$check = $pdo->prepare($query);
$check->execute([$_SESSION['user_id'], $movieId]);

$exists = $check->fetch();

if ($exists) {
    $query = 'DELETE FROM favorites
              WHERE id = ?';

    $delete = $pdo->prepare($query);
    $delete->execute([$exists['id']]);

    set_note('success', 'Movie removed from favorites.');
} else {
    $query = 'INSERT INTO favorites (userId, movieId, createdAt)
              VALUES (?, ?, NOW())';

    $insert = $pdo->prepare($query);
    $insert->execute([$_SESSION['user_id'], $movieId]);
    
    set_note('success', 'Movie added to favorites.');
}

redirect('../../public/movie.php?slug=' . urlencode($slug));
