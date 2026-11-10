<?php
/** @var string|null $title  @var string $content */
$siteName = setting('company_name', 'Monitrade');
$tagline  = setting('tagline', 'Agent & Service Provider for the Textile Industry');
$fullTitle = $title ? "{$title} | {$siteName}" : "{$siteName} — {$tagline}";
$metaDesc = $description ?? 'Monitrade is a leading textile agency in Egypt since ' . setting('founded_year', '1989')
    . ' — machines, spare parts and accessories for spinning, weaving, dyeing and garments, with offices in Cairo and Alexandria.';
$reqPath = '/' . trim((string)parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');
$base = rtrim($GLOBALS['config']['base_path'] ?? '', '/');
if ($base && str_starts_with($reqPath, $base)) { $reqPath = '/' . trim(substr($reqPath, strlen($base)), '/'); }
$active = static fn(string $p) => ($p === '/' ? $reqPath === '/' : str_starts_with($reqPath, $p)) ? ' is-active' : '';
$ticker = ticker_items();
$email = setting('company_email');
$cairoPhone = trim(explode('/', setting('cairo_phone'))[0]);
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($fullTitle) ?></title>
<meta name="description" content="<?= e($metaDesc) ?>">
<link rel="canonical" href="<?= e(abs_url(ltrim($reqPath, '/'))) ?>">
<meta property="og:type" content="website">
<meta property="og:site_name" content="<?= e($siteName) ?>">
<meta property="og:title" content="<?= e($fullTitle) ?>">
<meta property="og:description" content="<?= e($metaDesc) ?>">
<meta property="og:url" content="<?= e(abs_url(ltrim($reqPath, '/'))) ?>">
<meta name="theme-color" content="#f2d800">
<link rel="icon" href="<?= asset('assets/img/favicon.svg') ?>" type="image/svg+xml">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>">
<script type="application/ld+json"><?= json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Organization',
    'name' => $siteName,
    'url' => abs_url(''),
    'logo' => abs_url('assets/img/logo.svg'),
    'foundingDate' => setting('founded_year', '1989'),
    'email' => $email,
    'address' => ['@type' => 'PostalAddress', 'streetAddress' => setting('cairo_address'), 'addressCountry' => 'EG'],
    'telephone' => $cairoPhone,
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) ?></script>
</head>
<body>
<a class="skip-link" href="#main">Skip to content</a>

<div class="topbar">
  <div class="container topbar__inner">
    <div class="topbar__contact">
      <?php if ($cairoPhone): ?><a href="<?= e(tel_href($cairoPhone)) ?>"><?= icon('phone') ?><span><?= e($cairoPhone) ?></span></a><?php endif; ?>
      <?php if ($email): ?><a href="mailto:<?= e($email) ?>"><?= icon('mail') ?><span><?= e($email) ?></span></a><?php endif; ?>
    </div>
    <div class="topbar__tools">
      <a href="<?= url('share?url=' . rawurlencode($reqPath)) ?>"><?= icon('share') ?><span>Send to a Friend</span></a>
      <a href="<?= url('sitemap') ?>"><span>Site Map</span></a>
      <form class="topbar__search" action="<?= url('search') ?>" method="get" role="search">
        <label class="sr-only" for="q-top">Search</label>
        <input id="q-top" type="search" name="q" placeholder="Search products & news" value="<?= e($_GET['q'] ?? '') ?>">
        <button type="submit" aria-label="Search"><?= icon('search') ?></button>
      </form>
    </div>
  </div>
</div>

<header class="site-header">
  <div class="container site-header__inner">
    <a class="brand" href="<?= url('/') ?>" aria-label="<?= e($siteName) ?> home">
      <img src="<?= asset('assets/img/logo.svg') ?>" alt="<?= e($siteName) ?> — Since <?= e(setting('founded_year', '1989')) ?>" width="220" height="60">
    </a>
    <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="site-nav">
      <?= icon('menu', 'icon icon--open') ?><?= icon('close', 'icon icon--close') ?><span class="sr-only">Menu</span>
    </button>
    <nav id="site-nav" class="site-nav" aria-label="Main">
      <ul>
        <li><a class="<?= $active('/') ?>" href="<?= url('/') ?>">Home</a></li>
        <li><a class="<?= $active('/company-profile') ?>" href="<?= url('company-profile') ?>">Company Profile</a></li>
        <li class="has-sub">
          <a class="<?= $active('/products') ?>" href="<?= url('products') ?>">Products <?= icon('chevron', 'icon icon--sm') ?></a>
          <button class="sub-toggle" type="button" aria-expanded="false" aria-label="Show product menu"><?= icon('chevron') ?></button>
          <div class="mega">
            <?php foreach (['machines', 'accessories'] as $sec): ?>
              <div class="mega__col">
                <a class="mega__head" href="<?= category_url($sec) ?>"><?= e(SECTIONS[$sec]) ?></a>
                <?php foreach (CATEGORIES as $ck => $cl): ?>
                  <a href="<?= category_url($sec, $ck) ?>"><?= e($cl) ?></a>
                <?php endforeach; ?>
              </div>
            <?php endforeach; ?>
            <div class="mega__col">
              <a class="mega__head" href="<?= category_url('software') ?>">Software</a>
              <a class="mega__cta" href="<?= url('business-request') ?>">Request a quotation <?= icon('arrow', 'icon icon--sm') ?></a>
            </div>
          </div>
        </li>
        <li><a class="<?= $active('/news') ?>" href="<?= url('news') ?>">News</a></li>
        <li><a class="<?= $active('/articles') ?>" href="<?= url('articles') ?>">Articles</a></li>
        <li><a class="<?= $active('/contact') ?>" href="<?= url('contact') ?>">Contact Us</a></li>
        <li class="nav-cta"><a class="btn btn--primary btn--sm" href="<?= url('business-request') ?>">Business Request</a></li>
      </ul>
    </nav>
  </div>
</header>

<?php if ($ticker): ?>
<div class="ticker" aria-label="Latest news">
  <div class="container ticker__inner">
    <span class="ticker__label"><?= icon('news', 'icon icon--sm') ?> Latest News</span>
    <div class="ticker__track"><div class="ticker__items">
      <?php for ($dup = 0; $dup < 2; $dup++): foreach ($ticker as $t): ?>
        <a href="<?= post_url($t) ?>"<?= $dup ? ' aria-hidden="true" tabindex="-1"' : '' ?><?= is_rtl($t['title']) ? ' dir="rtl"' : '' ?>><?= e($t['title']) ?></a>
      <?php endforeach; endfor; ?>
    </div></div>
  </div>
</div>
<?php endif; ?>

<main id="main">
<?php foreach (flashes() as [$type, $msg]): ?>
  <div class="container"><div class="alert alert--<?= e($type) ?>"><?= e($msg) ?></div></div>
<?php endforeach; ?>
<?= $content ?>
</main>

<footer class="site-footer">
  <div class="site-footer__band"></div>
  <div class="container site-footer__grid">
    <div class="site-footer__about">
      <img src="<?= asset('assets/img/logo.svg') ?>" alt="<?= e($siteName) ?>" width="190" height="52" class="site-footer__logo">
      <p><?= e($tagline) ?>. Founded by <?= e(setting('founder', 'Eng. Ahmed Mounir')) ?>.</p>
      <a class="btn btn--primary btn--sm" href="<?= url('business-request') ?>">Send a business request</a>
    </div>
    <div>
      <h2 class="site-footer__h">Products</h2>
      <ul class="site-footer__links">
        <li><a href="<?= category_url('machines') ?>">Machines</a></li>
        <li><a href="<?= category_url('accessories') ?>">Accessories &amp; Spare Parts</a></li>
        <li><a href="<?= category_url('software') ?>">Software</a></li>
        <li><a href="<?= url('news') ?>">News</a></li>
        <li><a href="<?= url('articles') ?>">Articles</a></li>
      </ul>
    </div>
    <div>
      <h2 class="site-footer__h">Cairo Office</h2>
      <address>
        <p><?= icon('pin', 'icon icon--sm') ?> <?= e(setting('cairo_address')) ?></p>
        <?php if (setting('cairo_phone')): ?><p><?= icon('phone', 'icon icon--sm') ?> <?= e(setting('cairo_phone')) ?></p><?php endif; ?>
        <?php if (setting('cairo_mobile')): ?><p><?= icon('mobile', 'icon icon--sm') ?> <a href="<?= e(tel_href(setting('cairo_mobile'))) ?>"><?= e(setting('cairo_mobile')) ?></a></p><?php endif; ?>
      </address>
    </div>
    <div>
      <h2 class="site-footer__h">Alexandria Office</h2>
      <address>
        <p><?= icon('pin', 'icon icon--sm') ?> <?= e(setting('alex_address')) ?></p>
        <?php if (setting('alex_phone')): ?><p><?= icon('phone', 'icon icon--sm') ?> <?= e(setting('alex_phone')) ?></p><?php endif; ?>
        <?php if ($email): ?><p><?= icon('mail', 'icon icon--sm') ?> <a href="mailto:<?= e($email) ?>"><?= e($email) ?></a></p><?php endif; ?>
      </address>
    </div>
  </div>
  <div class="site-footer__bottom">
    <div class="container site-footer__bottom-inner">
      <p>&copy; <?= date('Y') ?> <?= e($siteName) ?>. All rights reserved.</p>
      <ul>
        <li><a href="<?= url('terms') ?>">Terms and Conditions</a></li>
        <li><a href="<?= url('privacy') ?>">Privacy Policy</a></li>
        <li><a href="<?= url('navigation') ?>">Navigation</a></li>
        <li><a href="<?= url('sitemap') ?>">Site Map</a></li>
      </ul>
    </div>
  </div>
</footer>

<script src="<?= asset('assets/js/main.js') ?>" defer></script>
</body>
</html>
