<?php /** @var array $p */ $rtl = is_rtl($p['title']); ?>
<article class="postcard"<?= $rtl ? ' dir="rtl"' : '' ?>>
  <?php if ($p['image']): ?>
    <a class="postcard__media" href="<?= post_url($p) ?>" tabindex="-1" aria-hidden="true"><img src="<?= e(upload_url($p['image'])) ?>" alt="" loading="lazy"></a>
  <?php endif; ?>
  <div class="postcard__body">
    <p class="postcard__meta"><?= $p['type'] === 'article' ? 'Article' : 'News' ?><?= fmt_date($p['published_on']) ? ' · <time datetime="' . e($p['published_on']) . '">' . e(fmt_date($p['published_on'])) . '</time>' : '' ?></p>
    <h3 class="postcard__title"><a href="<?= post_url($p) ?>"><?= e($p['title']) ?></a></h3>
    <p class="postcard__text"><?= e(excerpt($p['summary'] ?: $p['body'], 150)) ?></p>
    <a class="link-arrow" href="<?= post_url($p) ?>">Read more <?= icon('arrow', 'icon icon--sm') ?><span class="sr-only">: <?= e($p['title']) ?></span></a>
  </div>
</article>
