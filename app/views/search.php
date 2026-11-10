<?php
/** @var string $term @var array $products @var array $posts */
partial('partials/page-head', ['heading' => 'Search', 'crumbs' => ['Search' => null]]);
$total = count($products) + count($posts);
?>
<section class="section">
  <div class="container">
    <form class="searchbar" action="<?= url('search') ?>" method="get" role="search">
      <label class="sr-only" for="q-page">Search the site</label>
      <input id="q-page" type="search" name="q" value="<?= e($term) ?>" placeholder="Search products, news and articles" minlength="2">
      <button class="btn btn--primary" type="submit"><?= icon('search', 'icon icon--sm') ?> Search</button>
    </form>
    <?php if ($term !== '' && mb_strlen($term) < 2): ?>
      <p class="muted">Please enter at least 2 characters.</p>
    <?php elseif ($term !== ''): ?>
      <p class="muted"><?= $total ?> result<?= $total === 1 ? '' : 's' ?> for “<?= e($term) ?>”.</p>
      <?php if ($products): ?>
        <h2 class="group-title">Products</h2>
        <div class="grid grid--3"><?php foreach ($products as $p) { partial('partials/product-card', ['p' => $p]); } ?></div>
      <?php endif; ?>
      <?php if ($posts): ?>
        <h2 class="group-title">News &amp; articles</h2>
        <div class="grid grid--3"><?php foreach ($posts as $p) { partial('partials/post-card', ['p' => $p]); } ?></div>
      <?php endif; ?>
      <?php if (!$total): ?>
        <p>No matches. Try a brand name (e.g. “Bräcker”), a product (e.g. “spindles”) or <a href="<?= url('business-request') ?>">send us a request</a>.</p>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</section>
