<?php
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);


switch ($path) {
    case '/test':
        require __DIR__ . '/controllers/Test.php';
        break;
    case '/check':
        require __DIR__ . '/controllers/CheckController.php';
        break;
    case '/history':
        require __DIR__ . '/controllers/HistoryController.php';
        break;
    default:
        require __DIR__ . '/public/view/main.php';
        exit;
}