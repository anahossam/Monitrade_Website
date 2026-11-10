<?php
/**
 * Monitrade — public site front controller.
 * Every public URL is routed here by .htaccess (or router.php in local dev).
 */
declare(strict_types=1);

require __DIR__ . '/app/bootstrap.php';
require APP . '/controllers.php';

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$base = rtrim($GLOBALS['config']['base_path'] ?? '', '/');
if ($base !== '' && str_starts_with($path, $base)) {
    $path = substr($path, strlen($base));
}
$path = '/' . trim(rawurldecode($path), '/');

// ---- Old monitrade.org addresses → new pages (301 so search engines follow).
$legacy = [
    '/index.php'               => '/',
    '/pages/cProfile.php'      => '/company-profile',
    '/products/index.php'      => '/products',
    '/products/software.php'   => '/products/software',
    '/pages/viewNews.php'      => '/news',
    '/pages/viewArticle.php'   => '/articles',
    '/pages/contact.php'       => '/contact',
    '/pages/request.php'       => '/business-request',
    '/pages/terms.php'         => '/terms',
    '/pages/privacy.php'       => '/privacy',
    '/pages/navRules.php'      => '/navigation',
    '/pages/siteMap.php'       => '/sitemap',
    '/pages/mailToFriend.php'  => '/share',
    '/pages/members.php'       => '/',
];
if (isset($legacy[$path])) {
    redirect($legacy[$path], 301);
}
if ($path === '/products/machines.php' || $path === '/products/accessories.php') {
    $sec = str_contains($path, 'machines') ? 'machines' : 'accessories';
    $cat = strtolower((string)($_GET['mType'] ?? $_GET['aType'] ?? ''));
    redirect(category_url($sec, isset(CATEGORIES[$cat]) ? $cat : null), 301);
}

// ---- Routes
try {
    switch (true) {
        case $path === '/':
            page_home();
            break;
        case $path === '/company-profile':
            page_profile();
            break;
        case $path === '/products':
            page_products();
            break;
        case (bool)preg_match('~^/products/(machines|accessories|software)(?:/(spinning|weaving|dyeing|garments))?$~', $path, $m):
            page_category($m[1], $m[2] ?? null);
            break;
        case (bool)preg_match('~^/products/item/(\d+)(?:-[a-z0-9-]*)?$~', $path, $m):
            page_product((int)$m[1]);
            break;
        case $path === '/news':
            page_posts('news');
            break;
        case $path === '/articles':
            page_posts('article');
            break;
        case (bool)preg_match('~^/(news|articles)/(\d+)(?:-[a-z0-9-]*)?$~', $path, $m):
            page_post($m[1] === 'news' ? 'news' : 'article', (int)$m[2]);
            break;
        case $path === '/contact':
            page_contact();
            break;
        case $path === '/business-request':
            page_request();
            break;
        case $path === '/thank-you':
            view('thanks', ['title' => 'Thank you']);
            break;
        case $path === '/share':
            page_share();
            break;
        case $path === '/search':
            page_search();
            break;
        case $path === '/sitemap':
            page_sitemap();
            break;
        case $path === '/sitemap.xml':
            sitemap_xml();
            break;
        case in_array($path, ['/terms', '/privacy', '/navigation'], true):
            view('legal/' . substr($path, 1), ['title' => [
                '/terms' => 'Terms and Conditions', '/privacy' => 'Privacy Policy', '/navigation' => 'Navigation',
            ][$path]]);
            break;
        default:
            not_found();
    }
} catch (PDOException $e) {
    error_log('DB error: ' . $e->getMessage());
    http_response_code(503);
    view('error', ['title' => 'Temporarily unavailable']);
}
