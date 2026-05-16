<?php

function set_note(string $type, string $message): void {
    $_SESSION['note'] = [
        'type' => $type,
        'message' => $message
    ];
}

function get_note(): ?array {
    if (!isset($_SESSION['note'])) return null;

    $note = $_SESSION['note'];
    unset($_SESSION['note']);

    return $note;
}

function store_old_input(array $input): void {
    $_SESSION['old'] = $input;
}

function get_old_input(string $key, string $default = ''): string {
    return htmlspecialchars($_SESSION['old'][$key] ?? $default);
}

function clear_old_input() {
    unset($_SESSION['old']);
}