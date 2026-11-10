<?php
/** @var array $p @var array $more */
$isNews = $p['type'] === 'news';
$listUrl = url($isNews ? 'news' : 'articles');
$rtl = is_rtl($p['title'] . $p['body']);
partial('partials/page-head', ['heading' => $p['title'], 'crumbs' => [$isNews ? 'News' : 'Articles' => $listUrl, excerpt($p['title'], 40) => null]]);
?>
<section class="section">
  <div class="container with-aside">
    <article class="article"<?= $rtl ? ' dir="rtl"' : '' ?>>
      <p class="article__meta">
        <?= $isNews ? 'News' : 'Article' ?>
        <?php if (fmt_date($p['published_on'])): ?> · <time datetime="<?= e($p['published_on']) ?>"><?= e(fmt_date($p['published_on'])) ?></time><?php endif; ?>
        <?php if ($p['source']): ?> · Source: <?= e($p['source']) ?><?php endif; ?>
      </p>
      <?php if ($p['summary']): ?><p class="lead"><?= e($p['summary']) ?></p><?php endif; ?>
      <?php if ($p['image']): ?><figure class="article__figure"><img src="<?= e(upload_url($p['image'])) ?>" alt=""></figure><?php endif; ?>
      <div class="prose"><?= paragraphs($p['body']) ?></div>
      <div class="article__foot">
        <a class="link-arrow link-arrow--back" href="<?= $listUrl ?>"><?= icon('arrow', 'icon icon--sm icon--flip') ?> Back to <?= $isNews ? 'news' : 'articles' ?></a>
        <a class="btn btn--ghost btn--sm" href="<?= url('share?url=' . rawurlencode(post_url($p))) ?>"><?= icon('share', 'icon icon--sm') ?> Send to a friend</a>
      </div>
    </article>
    <?php if ($more): ?>
      <aside class="aside-list">
        <h2 class="h4">More <?= $isNews ? 'news' : 'articles' ?></h2>
        <ul>
          <?php foreach ($more as $m): ?>
            <li<?= is_rtl($m['title']) ? ' dir="rtl"' : '' ?>><a href="<?= post_url($m) ?>"><?= e($m['title']) ?></a><?php if (fmt_date($m['published_on'])): ?><span><?= e(fmt_date($m['published_on'])) ?></span><?php endif; ?></li>
          <?php endforeach; ?>
        </ul>
      </aside>
    <?php endif; ?>
  </div>
</section>
