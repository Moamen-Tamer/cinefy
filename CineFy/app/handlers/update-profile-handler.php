<?php

require_once __DIR__ . '/../helpers/functions.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('../../public/account.php');

$username = raw_value($_POST['username'] ?? '');
$email = raw_value($_POST['email'] ?? '');

if ($username === '' || $email === '') {
    set_note('danger', 'Username and email are required.');
    redirect('../../public/account.php');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    set_note('danger', 'Please enter a valid email address.');
    redirect('../../public/account.php');
}

$pdo = db();

if (!$pdo) {
    set_note('warning', 'Database is not connected yet. Profile changes cannot be saved.');
    redirect('../../public/account.php');
}

$query = 'UPDATE users
          SET username = ?, email = ?
          WHERE id = ?';

$statement = $pdo->prepare($query);
$statement->execute([$username, $email, $_SESSION['user_id']]);

$_SESSION['username'] = $username;
$_SESSION['email'] = $email;

set_note('success', 'Profile updated.');
redirect('../../public/account.php');
