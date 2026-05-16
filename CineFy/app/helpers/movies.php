<?php

function fallback_movies(): array {
    return [
        [
            'id' => 1,
            'genre_id' => 1,
            'genre_name' => 'Sci-Fi',
            'title' => 'Interstellar',
            'slug' => 'interstellar',
            'description' => 'A team travels beyond the known solar system to search for a future for humanity.',
            'release_year' => 2014,
            'duration_minutes' => 169,
            'poster' => 'posters/interstellar.webp',
            'banner_image' => 'hero/interstellar-banner.svg',
            'director' => 'Christopher Nolan',
        ],
        [
            'id' => 2,
            'genre_id' => 2,
            'genre_name' => 'Thriller',
            'title' => 'Nightcrawler',
            'slug' => 'nightcrawler',
            'description' => 'An ambitious freelance cameraman chases crime scenes across Los Angeles.',
            'release_year' => 2014,
            'duration_minutes' => 117,
            'poster' => 'posters/nightcrawler.webp',
            'banner_image' => 'hero/nightcrawler-banner.svg',
            'director' => 'Dan Gilroy',
        ],
        [
            'id' => 3,
            'genre_id' => 3,
            'genre_name' => 'Drama',
            'title' => 'Whiplash',
            'slug' => 'whiplash',
            'description' => 'A young drummer faces relentless pressure inside a brutal music conservatory.',
            'release_year' => 2014,
            'duration_minutes' => 107,
            'poster' => 'posters/whiplash.webp',
            'banner_image' => 'hero/whiplash-banner.svg',
            'director' => 'Damien Chazelle',
        ],
        [
            'id' => 4,
            'genre_id' => 4,
            'genre_name' => 'Action',
            'title' => 'Mad Max: Fury Road',
            'slug' => 'mad-max-fury-road',
            'description' => 'A road war erupts in a scorched wasteland where survival demands speed and nerve.',
            'release_year' => 2015,
            'duration_minutes' => 120,
            'poster' => 'posters/mad-max-fury-road.webp',
            'banner_image' => 'hero/madmax-banner.svg',
            'director' => 'George Miller',
        ],
        [
            'id' => 5,
            'genre_id' => 5,
            'genre_name' => 'Mystery',
            'title' => 'Prisoners',
            'slug' => 'prisoners',
            'description' => 'Two families spiral into dread when their daughters vanish without a trace.',
            'release_year' => 2013,
            'duration_minutes' => 153,
            'poster' => 'posters/prisoners.webp',
            'banner_image' => 'hero/prisoners-banner.svg',
            'director' => 'Denis Villeneuve',
        ],
        [
            'id' => 6,
            'genre_id' => 6,
            'genre_name' => 'Animation',
            'title' => 'Spider-Man: Into the Spider-Verse',
            'slug' => 'spider-man-into-the-spider-verse',
            'description' => 'Miles Morales discovers a multiverse of heroes while learning what makes him one.',
            'release_year' => 2018,
            'duration_minutes' => 117,
            'poster' => 'posters/spider-man-into-the-spider-verse.webp',
            'banner_image' => 'hero/spiderverse-banner.svg',
            'director' => 'Bob Persichetti',
        ],
    ];
}

function get_all_genres(): array {
    $pdo = db();

    if (!$pdo) return [
        ['id' => 1, 'name' => 'Sci-Fi'],
        ['id' => 2, 'name' => 'Thriller'],
        ['id' => 3, 'name' => 'Drama'],
        ['id' => 4, 'name' => 'Action'],
        ['id' => 5, 'name' => 'Mystery'],
        ['id' => 6, 'name' => 'Animation'],
    ];

    $query = 'SELECT id, name
              FROM genres
              ORDER BY name';

    $statement = $pdo->prepare($query);
    $statement->execute();
    
    return $statement->fetchAll();
}

function get_featured_movies(int $limit = 5): array {
    $pdo = db();

    if (!$pdo) return array_slice(fallback_movies(), 0, $limit);

    $query = 'SELECT movies.id, movies.genreId AS genre_id, genres.name AS genre_name,
                     movies.title, movies.slug, movies.description, movies.releaseYear AS release_year,
                     movies.duration AS duration_minutes, movies.poster, movies.director, movies.createdAt AS created_at
              FROM movies
              LEFT JOIN genres ON genres.id = movies.genreId
              ORDER BY movies.releaseYear DESC, movies.title ASC
              LIMIT ?';

    $statement = $pdo->prepare($query);
    $statement->bindValue(1, $limit, PDO::PARAM_INT);
    $statement->execute();

    return $statement->fetchAll();
}

function search_movies(string $search = '', string $genre = '', string $sort = 'title'): array {
    $pdo = db();

    if (!$pdo) return array_values(array_filter(fallback_movies(), function ($movie) use ($search, $genre) {
        $matchSearch = $search === '' || stripos($movie['title'], $search) !== false;
        $matchGenre = $genre === '' || (string)$movie['genre_id'] === $genre;
        
        return $matchSearch && $matchGenre;
    }));

    $params = [];
    $query = 'SELECT movies.id, movies.genreId AS genre_id, genres.name AS genre_name,
                     movies.title, movies.slug, movies.description, movies.releaseYear AS release_year,
                     movies.duration AS duration_minutes, movies.poster, movies.director, movies.createdAt AS created_at
              FROM movies
              LEFT JOIN genres ON genres.id = movies.genreId
              WHERE 1 = 1';

    if ($search !== '') {
        $query .= ' AND movies.title LIKE ?';
        $params[] = '%' . $search . '%';
    }

    if ($genre !== '') {
        $query .= ' AND movies.genreId = ?';
        $params[] = $genre;
    }

    $orderBy = [
        'title' => 'movies.title ASC',
        'newest' => 'movies.releaseYear DESC',
        'oldest' => 'movies.releaseYear ASC'
    ];

    $query .= ' ORDER BY ' . ($orderBy[$sort] ?? $orderBy['title']);

    $statement = $pdo->prepare($query);
    $statement->execute($params);

    return $statement->fetchAll();
}

function get_movie_by_slug(string $slug): ?array {
    $pdo = db();

    if (!$pdo) {
        foreach (fallback_movies() as $movie) {
            if ($movie['slug'] === $slug) return $movie;
        }

        return null;
    }

    $query = 'SELECT movies.id, movies.genreId AS genre_id, genres.name AS genre_name,
                     movies.title, movies.slug, movies.description, movies.releaseYear AS release_year,
                     movies.duration AS duration_minutes, movies.poster, movies.director, movies.createdAt AS created_at
              FROM movies
              LEFT JOIN genres ON genres.id = movies.genreId
              WHERE movies.slug = ?
              LIMIT 1';

    $statement = $pdo->prepare($query);
    $statement->execute([$slug]);

    return $statement->fetch() ?: null;
}

function get_movie_average_rating(int $movieId): float {
    $pdo = db();

    if (!$pdo) return 0;

    $query = 'SELECT AVG(ratingValue)
              AS averageRating
              FROM ratings
              WHERE movieId = ?';

    $statement = $pdo->prepare($query);
    $statement->execute([$movieId]);

    return (float)($statement->fetch()['averageRating'] ?? 0);
}

function get_movie_comments(int $movieId): array {
    $pdo = db();

    if (!$pdo) return [
        [
            'username' => 'cinephile',
            'comment_text' => 'Visually stunning and emotionally massive.',
            'created_at' => '2026-04-01 18:10:00',
        ],
        [
            'username' => 'midnightviewer',
            'comment_text' => 'Exactly the kind of film that stays with you for days.',
            'created_at' => '2026-04-05 20:40:00',
        ],
    ];

    $query = 'SELECT comments.id, comments.userId AS user_id, comments.movieId AS movie_id,
                     comments.comment AS comment_text, comments.createdAt AS created_at, users.username
              FROM comments
              INNER JOIN users ON users.id = comments.userId
              WHERE comments.movieId = ?
              ORDER BY comments.createdAt DESC';

    $statement = $pdo->prepare($query);
    $statement->execute([$movieId]);

    return $statement->fetchAll();
}

function get_related_movies(int $genreId, int $movieId, int $limit = 5): array {
    $pdo = db();

    if (!$pdo) return array_slice(array_values(array_filter(fallback_movies(), function ($movie) use ($movieId) {
        return $movie['id'] !== $movieId;
    })), 0, $limit);

    $query = 'SELECT movies.id, movies.genreId AS genre_id, genres.name AS genre_name,
                     movies.title, movies.slug, movies.description, movies.releaseYear AS release_year,
                     movies.duration AS duration_minutes, movies.poster, movies.director, movies.createdAt AS created_at
              FROM movies
              LEFT JOIN genres ON genres.id = movies.genreId
              WHERE movies.genreId = ? AND movies.id != ?
              ORDER BY movies.releaseYear DESC
              LIMIT ?';

    $statement = $pdo->prepare($query);
    $statement->bindValue(1, $genreId, PDO::PARAM_INT);
    $statement->bindValue(2, $movieId, PDO::PARAM_INT);
    $statement->bindValue(3, $limit, PDO::PARAM_INT);
    $statement->execute();

    return $statement->fetchAll();
}

function get_user_rating_for_movie(int $userId, int $movieId): float {
    $pdo = db();

    if (!$pdo) return 0;

    $query = 'SELECT ratingValue
              FROM ratings
              WHERE userId = ? AND movieId = ?
              LIMIT 1';

    $statement = $pdo->prepare($query);
    $statement->execute([$userId, $movieId]);
    
    return (float)($statement->fetch()['ratingValue'] ?? 0);
}

function is_favorite_movie(int $userId, int $movieId): bool {
    $pdo = db();

    if (!$pdo) return false;

    $query = 'SELECT id
              FROM favorites
              WHERE userId = ? AND movieId = ?
              LIMIT 1';

    $statement = $pdo->prepare($query);
    $statement->execute([$userId, $movieId]);

    return (bool)($statement->fetch());
}
