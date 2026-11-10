<?php
/** @var array $posts @var array $counts */
partial('partials/page-head', ['heading' => 'Site Map', 'crumbs' => ['Site Map' => null]]);
$news = array_filter($posts, fn($p) => $p['type'] === 'news');
$arts = array_filter($posts, fn($p) => $p['type'] === 'article');
?>
<section class="section">
  <div class="container sitemap">
    <div>
      <h2 class="h4">Company</h2>
      <ul>
        <li><a href="<?= url('/') ?>">Home</a></li>
        <li><a href="<?= url('company-profile') ?>">Company Profile</a></li>
        <li><a href="<?= url('contact') ?>">Contact Us</a></li>
        <li><a href="<?= url('business-request') ?>">Business Request</a></li>
        <li><a href="<?= url('search') ?>">Search</a></li>
      </ul>
      <h2 class="h4">Legal</h2>
      <ul>
        <li><a href="<?= url('terms') ?>">Terms and Conditions</a></li>
        <li><a href="<?= url('privacy') ?>">Privacy Policy</a></li>
        <li><a href="<?= url('navigation') ?>">Navigation</a></li>
      </ul>
    </div>
    <div>
      <h2 class="h4"><a href="<?= url('products') ?>">Products</a></h2>
      <ul>
        <?php foreach (['machines', 'accessories'] as $s): ?>
          <li><a href="<?= category_url($s) ?>"><?= e(SECTIONS[$s]) ?></a>
            <ul><?php foreach (CATEGORIES as $ck => $cl): ?><li><a href="<?= category_url($s, $ck) ?>"><?= e($cl) ?></a> <span class="count"><?= (int)($counts["$s/$ck"] ?? 0) ?></span></li><?php endforeach; ?></ul>
          </li>
        <?php endforeach; ?>
        <li><a href="<?= category_url('software') ?>">Software</a></li>
      </ul>
    </div>
    <div>
      <h2 class="h4"><a href="<?= url('news') ?>">News</a></h2>
      <ul><?php foreach ($news as $p): ?><li><a href="<?= post_url($p) ?>"><?= e($p['title']) ?></a></li><?php endforeach; ?></ul>
      <h2 class="h4"><a href="<?= url('articles') ?>">Articles</a></h2>
      <ul><?php foreach ($arts as $p): ?><li><a href="<?= post_url($p) ?>"><?= e($p['title']) ?></a></li><?php endforeach; ?></ul>
    </div>
  </div>
</section>
