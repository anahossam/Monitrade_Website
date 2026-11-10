<?php /** @var array $p */ ?>
<article class="pcard">
  <a class="pcard__media" href="<?= product_url($p) ?>" tabindex="-1" aria-hidden="true">
    <?php if ($p['image']): ?>
      <img src="<?= e(upload_url($p['image'])) ?>" alt="" loading="lazy">
    <?php else: ?>
      <span class="pcard__placeholder"><?= icon('box') ?></span>
    <?php endif; ?>
  </a>
  <div class="pcard__body">
    <p class="pcard__meta"><?= e(SECTIONS[$p['section']]) ?><?= $p['category'] ? ' · ' . e(CATEGORIES[$p['category']]) : '' ?></p>
    <h3 class="pcard__title"><a href="<?= product_url($p) ?>"><?= e($p['name']) ?></a></h3>
    <?php if ($p['model'] && $p['model'] !== $p['name']): ?><p class="pcard__model"><?= e($p['model']) ?></p><?php endif; ?>
    <p class="pcard__text"><?= e(excerpt($p['specs'], 120)) ?></p>
  </div>
</article>
