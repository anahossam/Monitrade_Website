<?php
declare(strict_types=1);

define('ROOT', dirname(__DIR__));
define('APP', ROOT . '/app');

$configFile = ROOT . '/config/config.php';
if (!is_file($configFile)) {
    http_response_code(500);
    exit('Configuration missing: copy config/config.example.php to config/config.php and edit it.');
}
$GLOBALS['config'] = require $configFile;

$isDev = ($GLOBALS['config']['env'] ?? 'production') === 'development';
error_reporting(E_ALL);
ini_set('display_errors', $isDev ? '1' : '0');
ini_set('log_errors', '1');
date_default_timezone_set('Africa/Cairo');
mb_internal_encoding('UTF-8');

// Sessions: secure cookie settings.
if (session_status() === PHP_SESSION_NONE) {
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    session_name('MTSESSID');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => ($GLOBALS['config']['base_path'] ?? '') ?: '/',
        'secure'   => $https,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

// Basic security headers for every response.
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');

require APP . '/db.php';
require APP . '/helpers.php';
require APP . '/auth.php';
require APP . '/mail.php';
require APP . '/upload.php';
require APP . '/icons.php';
