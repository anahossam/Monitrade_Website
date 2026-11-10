<?php
/** @var string $type @var array $items @var string $title */
partial('partials/page-head', ['heading' => $title, 'crumbs' => [$title => null],
    'lead' => $type === 'news' ? 'Updates from Monitrade and the Egyptian textile industry.' : 'Background reading on textiles, spinning and the industry.']);
?>
<section class="section">
  <div class="container">
    <?php if ($items): ?>
      <div class="grid grid--3"><?php foreach ($items as $p) { partial('partials/post-card', ['p' => $p]); } ?></div>
    <?php else: ?>
      <p class="muted">Nothing published yet — please check back soon.</p>
    <?php endif; ?>
  </div>
</section>
