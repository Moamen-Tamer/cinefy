<?php

require_once __DIR__ . '/../helpers/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('../../public/register.php');

$username = raw_value($_POST['username'] ?? '');
$email = raw_value($_POST['email'] ?? '');
$password = raw_value($_POST['password'] ?? '');
$confirmPassword = raw_value($_POST['confirm_password'] ?? '');

store_old_input([
    'username' => $username,
    'email' => $email
]);

if ($username === '' || $email === '' || $password === '' || $confirmPassword === '') {
    set_note('danger', 'Please fill in all registration fields.');
    redirect('../../public/register.php');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    set_note('danger', 'Please enter a valid email address.');
    redirect('../../public/register.php');
}

if (strlen($password) < 6) {
    set_note('danger', 'Password must be at least 6 characters.');
    redirect('../../public/register.php');
}

if ($password !== $confirmPassword) {
    set_note('danger', 'Passwords do not match.');
    redirect('../../public/register.php');
}

if (!db_available()) {
    set_note('warning', 'Database is not connected yet. Import the SQL files before registering users.');
    redirect('../../public/register.php');
}

if (user_exists($username, $email)) {
    set_note('danger', 'That username or email is already in use.');
    redirect('../../public/register.php');
}

if (create_user($username, $email, $password)) {
    clear_old_input();
    set_note('success', 'Account created successfully. Please log in.');
    redirect('../../public/login.php');
}

set_note('danger', 'Unable to create account right now.');
redirect('../../public/register.php');
