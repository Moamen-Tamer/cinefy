<?php

require_once __DIR__ . '/../helpers/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('../../public/login.php');

$login = raw_value($_POST['login'] ?? '');
$password = raw_value($_POST['password'] ?? '');

store_old_input([
    'login' => $login
]);

if ($login === '' || $password === '') {
    set_note('danger', 'Please enter your login details');
    redirect('../../public/login.php');
}

if (!db_available()) {
    set_note('warning', 'Database is not connected yet. Import the SQL files before using login.');
    redirect('../../public/login.php');
}

$user = get_user_by_email_or_username($login);

if (!$user || !password_verify($password, $user['password'])) {
    set_note('danger', 'Invalid credentials. Please try again.');
    redirect('../../public/login.php');
}

$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['email'] = $user['email'];

clear_old_input();

set_note('success', 'Welcome back, ' . $user['username'] . '.');
redirect('../../public/account.php');
