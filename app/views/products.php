<?php
/** @var array $counts */
partial('partials/page-head', ['heading' => 'Products', 'crumbs' => ['Products' => null],
    'lead' => 'Machines, accessories, spare parts and software for spinning, weaving, dyeing and garment production.']);
$intro = [
    'machines'    => 'Complete production lines and machinery from world-leading manufacturers.',
    'accessories' => 'Travellers, cots, rings, spindles, flyers, cans and spare parts — stocked in Cairo and Alexandria.',
    'software'    => 'Software solutions for textile businesses. Contact us for details.',
];
?>
<section class="section">
  <div class="container">
    <div class="catalog">
      <?php foreach (SECTIONS as $sk => $sl): ?>
        <div class="catalog__card">
          <div class="catalog__head">
            <h2><a href="<?= category_url($sk) ?>"><?= e($sl) ?></a></h2>
            <span class="count count--lg"><?= (int)($counts[$sk] ?? 0) ?> items</span>
          </div>
          <p><?= e($intro[$sk]) ?></p>
          <?php if ($sk !== 'software'): ?>
            <ul class="catalog__cats">
              <?php foreach (CATEGORIES as $ck => $cl): ?>
                <li><a href="<?= category_url($sk, $ck) ?>"><span><?= e($cl) ?></span><span class="count"><?= (int)($counts["$sk/$ck"] ?? 0) ?></span></a></li>
              <?php endforeach; ?>
            </ul>
          <?php else: ?>
            <a class="link-arrow" href="<?= category_url('software') ?>">View software <?= icon('arrow', 'icon icon--sm') ?></a>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php partial('partials/cta'); ?>
