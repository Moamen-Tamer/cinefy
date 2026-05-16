<?php

function app_url (string $path = ''): string {
    return $path;
}

function redirect (string $path): void {
    header('Location: ' . $path);
    exit;
}

function asset(string $path): string {
    return '../assets/' . ltrim($path, '/');
}

function page_title(string $title): string {
    return $title . ' | CineFy';
}

function db_available(): bool {
    return db() instanceof PDO;
}

function format_date(string $date): string {
    return date('M d, Y', strtotime($date));
}

function format_runtime(?int $minutes): string {
    if (!$minutes) return 'runtime unavailable';

    $hrs = floor($minutes / 60);
    $mins = $minutes % 60;

    return $hrs . ' hrs, ' . $mins . ' mins';
}

function stars_from_rating(float $rating): string {
    $output = '';

    for ($i = 1; $i <= 5; $i++) {
        $output .= $i <= (int)round($rating) ? '&#9733;' : '&#9734;';
    }

    return $output;
}
