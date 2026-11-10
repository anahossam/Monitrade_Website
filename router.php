<?php
/**
 * Router for PHP's built-in server — LOCAL DEVELOPMENT ONLY.
 *   php -S localhost:8000 router.php
 * On cPanel/Apache, .htaccess does this job and this file is not used.
 */
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (preg_match('~^/(app|config|database|docs|Documentation)(/|$)|/\.~', $path)) {
    http_response_code(403);
    exit('Forbidden');
}
if (str_starts_with($path, '/uploads/') && preg_match('~\.php~i', $path)) {
    http_response_code(403);
    exit('Forbidden');
}

$file = __DIR__ . $path;
if ($path !== '/' && is_file($file)) {
    return false; // serve static files and admin/*.php as-is
}
if (is_dir($file) && is_file(rtrim($file, '/') . '/index.php') && $path !== '/') {
    if (!str_ends_with($path, '/')) {
        header('Location: ' . $path . '/');
        exit;
    }
    require rtrim($file, '/') . '/index.php';
    return true;
}
require __DIR__ . '/index.php';
