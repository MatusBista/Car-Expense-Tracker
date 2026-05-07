<?php

declare(strict_types=1);

ob_start();

spl_autoload_register(function ($class) {
    $file = __DIR__ . '/classes/' . $class . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

Auth::start();

$page = $_GET['page'] ?? 'home';

$publicPages = ['home', 'login', 'register'];

if (!in_array($page, $publicPages, true)) {
    Auth::requireLogin();
}

$viewFile = __DIR__ . '/views/' . $page . '.php';

if (!file_exists($viewFile)) {
    http_response_code(404);
    $viewFile = __DIR__ . '/views/404.php';
}

require __DIR__ . '/views/header.php';
require $viewFile;
require __DIR__ . '/views/footer.php';
