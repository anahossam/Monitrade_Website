<?php
/** @var array $products @var array $news @var array $articles @var array $counts */
$year = setting('founded_year', '1989');
$years = (int)date('Y') - (int)$year;
$brands = q_all('SELECT * FROM brands WHERE is_published = 1 ORDER BY sort_order, name');
$intro = preg_split("~\R{2,}~", trim(setting('home_intro')));
$sectorText = [
    'spinning' => 'Blow-room to ring spinning — lines, travellers, cots, rings, spindles, flyers and spares.',
    'weaving'  => 'Weaving machinery and accessories sourced from leading European and Asian makers.',
    'dyeing'   => 'Dyeing and finishing equipment and consumables for modern mills.',
    'garments' => 'Home-textile lines, non-wovens and professional embroidery systems.',
];
?>
<section class="hero">
  <div class="hero__shape hero__shape--a" aria-hidden="true"></div>
  <div class="hero__shape hero__shape--b" aria-hidden="true"></div>
  <div class="container hero__inner">
    <div class="hero__copy">
      <p class="eyebrow">Textile agency · Egypt · Since <?= e($year) ?></p>
      <h1>Machines, spare parts &amp; know-how for Egypt’s textile mills</h1>
      <p class="hero__lead">Monitrade represents the biggest names in textile machinery — from spinning to garments — and backs every sale with expert service from our Cairo and Alexandria offices.</p>
      <div class="hero__actions">
        <a class="btn btn--dark" href="<?= url('products') ?>">Explore products <?= icon('arrow', 'icon icon--sm') ?></a>
        <a class="btn btn--ghost" href="<?= url('business-request') ?>">Request a quotation</a>
      </div>
    </div>
    <div class="hero__visual">
      <figure class="hero__card">
        <img src="<?= asset('assets/img/yarn-cones.jpg') ?>" alt="Yarn cones in a spinning mill" width="180" height="276">
        <figcaption><strong><?= $years ?>+ years</strong> serving the Egyptian textile industry</figcaption>
      </figure>
    </div>
  </div>
</section>

<section class="stats" aria-label="Monitrade in numbers">
  <div class="container stats__grid">
    <div class="stat"><span class="stat__num"><?= e($year) ?></span><span class="stat__label">Founded in Egypt</span></div>
    <div class="stat"><span class="stat__num">100s</span><span class="stat__label">Truetzschler, Zinser &amp; Schlumberger machines sold</span></div>
    <div class="stat"><span class="stat__num">2</span><span class="stat__label">Offices &amp; warehouses — Cairo and Alexandria</span></div>
    <div class="stat"><span class="stat__num">35+</span><span class="stat__label">Years of textile expertise per senior engineer</span></div>
  </div>
</section>

<section class="section">
  <div class="container about">
    <div class="about__text">
      <p class="eyebrow">About Monitrade</p>
      <h2 class="section-title">A trusted partner of the Egyptian textile industry</h2>
      <?php foreach ($intro as $i => $para): ?>
        <p<?= $i === 0 ? ' class="lead"' : '' ?>><?= e(trim($para)) ?></p>
      <?php endforeach; ?>
      <a class="link-arrow" href="<?= url('company-profile') ?>">Read our company profile <?= icon('arrow', 'icon icon--sm') ?></a>
    </div>
    <aside class="about__features" aria-label="Why customers choose Monitrade">
      <div class="feature"><span class="feature__icon"><?= icon('truck') ?></span><div><h3>Nationwide distribution</h3><p>A reliable network reaching almost every Egyptian industrial city for speedy delivery.</p></div></div>
      <div class="feature"><span class="feature__icon"><?= icon('box') ?></span><div><h3>Stock in Cairo &amp; Alexandria</h3><p>Warehoused consumables for the moments your mill can’t wait.</p></div></div>
      <div class="feature"><span class="feature__icon"><?= icon('coins') ?></span><div><h3>Pay in Egyptian pounds</h3><p>We buy in foreign currency and resell to you at the equivalent EGP price.</p></div></div>
      <div class="feature"><span class="feature__icon"><?= icon('globe') ?></span><div><h3>At ITMA &amp; ITCE</h3><p>Present at major international exhibitions and with our own booth locally.</p></div></div>
    </aside>
  </div>
</section>

<section class="section section--tint">
  <div class="container">
    <div class="section-head">
      <div><p class="eyebrow">What we supply</p><h2 class="section-title">Solutions for every stage of textile production</h2></div>
      <a class="link-arrow" href="<?= url('products') ?>">All products <?= icon('arrow', 'icon icon--sm') ?></a>
    </div>
    <div class="sectors">
      <?php foreach (CATEGORIES as $ck => $cl): ?>
        <div class="sector">
          <h3><?= e($cl) ?></h3>
          <p><?= e($sectorText[$ck]) ?></p>
          <div class="sector__links">
            <a href="<?= category_url('machines', $ck) ?>">Machines <span class="count"><?= (int)($counts["machines/$ck"] ?? 0) ?></span></a>
            <a href="<?= category_url('accessories', $ck) ?>">Accessories <span class="count"><?= (int)($counts["accessories/$ck"] ?? 0) ?></span></a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php if ($products): ?>
<section class="section">
  <div class="container">
    <div class="section-head">
      <div><p class="eyebrow">Featured</p><h2 class="section-title">Machines &amp; accessories we represent</h2></div>
      <a class="link-arrow" href="<?= url('products') ?>">Browse catalog <?= icon('arrow', 'icon icon--sm') ?></a>
    </div>
    <div class="grid grid--4">
      <?php foreach ($products as $p) { partial('partials/product-card', ['p' => $p]); } ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ($brands): ?>
<section class="section section--dark">
  <div class="container">
    <p class="eyebrow eyebrow--light">Brands</p>
    <h2 class="section-title">Highly trusted names in the industry</h2>
    <ul class="brands">
      <?php foreach ($brands as $b): ?>
        <li class="brand-chip" title="<?= e($b['description']) ?>">
          <?php $inner = $b['logo'] ? '<img src="' . e(upload_url($b['logo'])) . '" alt="' . e($b['name']) . '" loading="lazy">' : '<span class="brand-chip__name">' . e($b['name']) . '</span>'; ?>
          <?= $b['website'] ? '<a href="' . e($b['website']) . '" target="_blank" rel="noopener">' . $inner . '</a>' : $inner ?>
          <?php if ($b['description']): ?><span class="brand-chip__desc"><?= e($b['description']) ?></span><?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>
<?php endif; ?>

<section class="section">
  <div class="container news-cols">
    <div>
      <div class="section-head"><h2 class="section-title section-title--sm">Latest news</h2><a class="link-arrow" href="<?= url('news') ?>">All news <?= icon('arrow', 'icon icon--sm') ?></a></div>
      <div class="stack">
        <?php foreach ($news as $p) { partial('partials/post-card', ['p' => $p]); } ?>
        <?php if (!$news): ?><p class="muted">No news yet.</p><?php endif; ?>
      </div>
    </div>
    <div>
      <div class="section-head"><h2 class="section-title section-title--sm">Articles</h2><a class="link-arrow" href="<?= url('articles') ?>">All articles <?= icon('arrow', 'icon icon--sm') ?></a></div>
      <div class="stack">
        <?php foreach ($articles as $p) { partial('partials/post-card', ['p' => $p]); } ?>
        <?php if (!$articles): ?><p class="muted">No articles yet.</p><?php endif; ?>
      </div>
    </div>
  </div>
</section>

<?php partial('partials/cta'); ?>
