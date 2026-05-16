<?php

require_once __DIR__ . '/../helpers/functions.php';

$currentPage = basename($_SERVER['PHP_SELF']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
    <link rel="icon" type="image/png" href="<?php echo asset('logo/CF.png'); ?>">
    <title><?php echo htmlspecialchars($pageTitle ?? 'CineFy'); ?></title>
</head>
<body class="<?php echo htmlspecialchars($bodyClass ?? '') ?>">
<?php require_once __DIR__ . '/navbar.php'; ?>
    <main class="page-shell">
