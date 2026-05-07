<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Expenses Tracker</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
    <div class="container nav">
        <a class="logo" href="index.php">Car Expenses</a>
        <nav>
            <?php if (Auth::check()): ?>
                <a href="index.php?page=dashboard">Prehľad</a>
                <a href="index.php?page=vehicles">Vozidlá</a>
                <a href="index.php?page=expenses">Výdavky</a>
                <span class="user">Prihlásený: <?= htmlspecialchars(Auth::username()) ?></span>
                <a href="index.php?page=logout">Odhlásiť</a>
            <?php else: ?>
                <a href="index.php?page=login">Prihlásenie</a>
                <a href="index.php?page=register">Registrácia</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main class="container">
