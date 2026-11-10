<?php
/** @var string $section @var ?string $category @var array $items @var array $counts @var string $title */
$crumbs = ['Products' => url('products')];
if ($category) {
    $crumbs[SECTIONS[$section]] = category_url($section);
    $crumbs[CATEGORIES[$category]] = null;
} else {
    $crumbs[SECTIONS[$section]] = null;
}
partial('partials/page-head', ['heading' => $title, 'crumbs' => $crumbs]);
?>
<section class="section">
  <div class="container with-sidebar">
    <aside class="sidebar" aria-label="Product categories">
      <?php foreach (SECTIONS as $sk => $sl): ?>
        <div class="sidebar__group">
          <a class="sidebar__head<?= $sk === $section && !$category ? ' is-active' : '' ?>" href="<?= category_url($sk) ?>"><?= e($sl) ?> <span class="count"><?= (int)($counts[$sk] ?? 0) ?></span></a>
          <?php if ($sk !== 'software'): ?>
            <ul>
              <?php foreach (CATEGORIES as $ck => $cl): ?>
                <li><a class="<?= $sk === $section && $ck === $category ? 'is-active' : '' ?>" href="<?= category_url($sk, $ck) ?>"><?= e($cl) ?> <span class="count"><?= (int)($counts["$sk/$ck"] ?? 0) ?></span></a></li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </aside>

    <div>
      <?php if ($items): ?>
        <?php if (!$category && $section !== 'software'):
            $grouped = [];
            foreach ($items as $it) { $grouped[$it['category'] ?? ''][] = $it; }
            foreach ($grouped as $ck => $list): ?>
              <h2 class="group-title"><a href="<?= category_url($section, $ck ?: null) ?>"><?= e($ck ? CATEGORIES[$ck] : 'Other') ?></a></h2>
              <div class="grid grid--3"><?php foreach ($list as $p) { partial('partials/product-card', ['p' => $p]); } ?></div>
            <?php endforeach; ?>
        <?php else: ?>
          <div class="grid grid--3"><?php foreach ($items as $p) { partial('partials/product-card', ['p' => $p]); } ?></div>
        <?php endif; ?>
      <?php else: ?>
        <div class="empty">
          <span class="empty__icon"><?= icon('box') ?></span>
          <h2>Products in this category are available on request</h2>
          <p>We source <?= e(strtolower($title)) ?> from leading manufacturers. Tell us what you need and our engineers will propose the right solution.</p>
          <a class="btn btn--primary" href="<?= url('business-request') ?>">Send a business request</a>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>
