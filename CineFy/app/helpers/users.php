<?php

function get_user_by_email_or_username(string $data): ?array {
    $pdo = db();

    if (!$pdo) return null;

    $statement = $pdo->prepare(
        'SELECT * 
         FROM users
         WHERE email = ? OR username = ?
         LIMIT 1'
    );

    $statement->execute([$data, $data]);

    return $statement->fetch() ?: null;
}

function user_exists(string $username, string $email): bool {
    $pdo = db();

    if (!$pdo) return false;

    $statement = $pdo->prepare(
        'SELECT id
         FROM users
         WHERE username = ? OR email = ?
         LIMIT 1'
    );

    $statement->execute([$username, $email]);

    return (bool)$statement->fetch();
}

function create_user(string $username, string $email, string $password): bool {
    $pdo = db();

    if (!$pdo) return false;

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $statement = $pdo->prepare(
        'INSERT INTO users (username, email, password, profileImage, createdAt)
         VALUES (?, ?, ?, ?, NOW())'
    );

    return $statement->execute([$username, $email, $hash, 'default-profile.jpg',]);
}

function get_user_stats(int $userId): array {
    $pdo = db();

    if (!$pdo) return [
        'favorites_count' => 0,
        'ratings_count' => 0,
        'comments_count' => 0
    ];

    $favorites = $pdo->prepare(
        'SELECT COUNT(*)
         AS count
         FROM favorites
         WHERE userId = ?'
    );

    $ratings = $pdo->prepare(
        'SELECT COUNT(*)
         AS count
         FROM ratings
         WHERE userId = ?'
    );

    $comments = $pdo->prepare(
        'SELECT COUNT(*)
         AS count
         FROM comments
         WHERE userId = ?'
    );

    $favorites->execute([$userId]);
    $ratings->execute([$userId]);
    $comments->execute([$userId]);

    return [
        'favorites_count' => (int)$favorites->fetch()['count'],
        'ratings_count' => (int)$ratings->fetch()['count'],
        'comments_count' => (int)$comments->fetch()['count']
    ];
}

function get_user_favorites(int $userId, ?int $limit = null): array {
    $pdo = db();

    if (!$pdo) return [];

    $query = 'SELECT favorites.createdAt AS created_at, movies.title, movies.slug, movies.poster, movies.releaseYear AS release_year
              FROM favorites
              INNER JOIN movies ON movies.id = favorites.movieId
              WHERE favorites.userId = ?
              ORDER BY favorites.createdAt DESC';

    if ($limit) $query .= ' LIMIT ' . (int)$limit;

    $statement = $pdo->prepare($query);
    $statement->execute([$userId]);

    return $statement->fetchAll();
}

function get_user_ratings(int $userId, ?int $limit = null): array {
    $pdo = db();

    if (!$pdo) return [];

    $query = 'SELECT ratings.createdAt AS created_at, ratings.ratingValue AS rating_value, movies.title, movies.poster, movies.releaseYear AS release_year
              FROM ratings
              INNER JOIN movies ON movies.id = ratings.movieId
              WHERE ratings.userId = ?
              ORDER BY ratings.createdAt DESC';

    if ($limit) $query .= ' LIMIT ' . (int)$limit;

    $statement = $pdo->prepare($query);
    $statement->execute([$userId]);

    return $statement->fetchAll();
}

function get_user_comments(int $userId, ?int $limit = null): array {
    $pdo = db();

    if (!$pdo) return [];

    $query = 'SELECT comments.createdAt AS created_at, comments.comment AS comment_text, movies.title, movies.poster, movies.releaseYear AS release_year
              FROM comments
              INNER JOIN movies ON movies.id = comments.movieId
              WHERE comments.userId = ?
              ORDER BY comments.createdAt DESC';

    if ($limit) $query .= ' LIMIT ' . (int)$limit;

    $statement = $pdo->prepare($query);
    $statement->execute([$userId]);

    return $statement->fetchAll();
}

function user_recent_activity(int $userId): array {
    return [
        'ratings' => get_user_ratings($userId, 5),
        'comments' => get_user_comments($userId, 5),
        'favorites' => get_user_favorites($userId, 5)
    ];
}
