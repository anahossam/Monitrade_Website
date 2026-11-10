<?php
/** @var array $p @var array $related */
$crumbs = ['Products' => url('products'), SECTIONS[$p['section']] => category_url($p['section'])];
if ($p['category']) {
    $crumbs[CATEGORIES[$p['category']]] = category_url($p['section'], $p['category']);
}
$crumbs[$p['name']] = null;
partial('partials/page-head', ['heading' => $p['name'], 'crumbs' => $crumbs]);
?>
<section class="section">
  <div class="container product">
    <div class="product__media">
      <?php if ($p['image']): ?>
        <img src="<?= e(upload_url($p['image'])) ?>" alt="<?= e($p['name']) ?>">
      <?php else: ?>
        <span class="pcard__placeholder"><?= icon('box') ?></span>
      <?php endif; ?>
    </div>
    <div class="product__info">
      <dl class="specs">
        <div><dt>Product name</dt><dd><?= e($p['name']) ?></dd></div>
        <?php if ($p['model']): ?><div><dt>Model</dt><dd><?= e($p['model']) ?></dd></div><?php endif; ?>
        <div><dt>Category</dt><dd><?= e(SECTIONS[$p['section']]) ?><?= $p['category'] ? ' · ' . e(CATEGORIES[$p['category']]) : '' ?></dd></div>
        <?php if ($p['offer']): ?><div><dt>Offer</dt><dd><?= e($p['offer']) ?></dd></div><?php endif; ?>
      </dl>
      <h2 class="h4">Specifications</h2>
      <div class="prose"><?= paragraphs($p['specs']) ?></div>
      <div class="product__actions">
        <a class="btn btn--primary" href="<?= url('business-request?product=' . (int)$p['id']) ?>">Request a quotation</a>
        <a class="btn btn--ghost" href="<?= url('share?url=' . rawurlencode(product_url($p))) ?>"><?= icon('share', 'icon icon--sm') ?> Send to a friend</a>
      </div>
    </div>
  </div>
</section>

<?php if ($related): ?>
<section class="section section--tint">
  <div class="container">
    <h2 class="section-title section-title--sm">Related products</h2>
    <div class="grid grid--3"><?php foreach ($related as $r) { partial('partials/product-card', ['p' => $r]); } ?></div>
  </div>
</section>
<?php endif; ?>
