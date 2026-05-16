<?php

require_once __DIR__ . '/../helpers/functions.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('../../public/search.php');

$movieId = (int)(raw_value($_POST['movie_id'] ?? 0));
$slug = raw_value($_POST['slug'] ?? '');
$comment = raw_value($_POST['comment_text'] ?? '');

if ($movieId <= 0 || $comment === '') {
    set_note('danger', 'Comment text cannot be empty.');
    redirect('../../public/movie.php?slug=' . urldecode($slug));
}

$pdo = db();

if (!$pdo) {
    set_note('warning', 'Database is not connected yet. Comments cannot be saved.');
    redirect('../../public/movie.php?slug=' . urldecode($slug));
}

$query = 'INSERT INTO comments (userId, movieId, comment, createdAt)
          VALUES (?, ?, ?, NOW())';

$statement = $pdo->prepare($query);
$statement->execute([$_SESSION['user_id'], $movieId, $comment]);

set_note('success', 'Your comment has been posted.');
redirect('../../public/movie.php?slug=' . urldecode($slug));
