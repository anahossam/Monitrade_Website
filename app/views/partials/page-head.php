<?php /** @var string $heading @var array $crumbs [label => url|null] @var string|null $lead */ ?>
<section class="page-head">
  <div class="container">
    <nav class="crumbs" aria-label="Breadcrumb">
      <ol>
        <li><a href="<?= url('/') ?>">Home</a></li>
        <?php foreach ($crumbs as $label => $href): ?>
          <li><?php if ($href): ?><a href="<?= e($href) ?>"><?= e($label) ?></a><?php else: ?><span aria-current="page"><?= e($label) ?></span><?php endif; ?></li>
        <?php endforeach; ?>
      </ol>
    </nav>
    <h1<?= is_rtl($heading) ? ' dir="rtl"' : '' ?>><?= e($heading) ?></h1>
    <?php if (!empty($lead)): ?><p class="page-head__lead"><?= e($lead) ?></p><?php endif; ?>
  </div>
</section>
