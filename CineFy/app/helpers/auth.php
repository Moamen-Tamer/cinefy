<?php

function current_user(): ?array {
    if (!isset($_SESSION['user_id'])) return null;

    $pdo = db();

    if (!$pdo) {
        return [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'] ?? 'Guest',
            'email' => $_SESSION['email'] ?? '',
            'profile_image' => 'default-profile.jpg'
        ];
    }

    $query = 'SELECT id, username, email, profileImage AS profile_image, createdAt AS created_at
              FROM users
              WHERE id = ?
              LIMIT 1';

    $statement = $pdo->prepare($query);

    $statement->execute([$_SESSION['user_id']]);

    return $statement->fetch() ?: null;
}

function is_logged(): bool {
    return isset($_SESSION['user_id']);
}

function require_login() {
    if (!is_logged()) {
        set_note('warning', 'Please log in to continue.');
        redirect('login.php');
    }
}
