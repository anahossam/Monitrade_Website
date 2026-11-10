<?php
declare(strict_types=1);
require __DIR__ . '/../app/admin.php';
require_admin();

$type = in_array($_GET['type'] ?? '', ['news', 'article'], true) ? $_GET['type'] : '';
handle_delete('posts', 'admin/posts.php' . ($type ? "?type=$type" : ''), 'image');

$sql = 'SELECT * FROM posts' . ($type ? ' WHERE type = ?' : '') . " ORDER BY COALESCE(published_on, '1000-01-01') DESC, id DESC";
$rows = q_all($sql, $type ? [$type] : []);

ob_start(); ?>
<div class="ahead">
  <h1>News &amp; Articles</h1>
  <div>
    <a class="abtn abtn--primary" href="<?= url('admin/post-edit.php?type=news') ?>">+ Add news</a>
    <a class="abtn abtn--ghost" href="<?= url('admin/post-edit.php?type=article') ?>">+ Add article</a>
  </div>
</div>
<nav class="afilters">
  <a class="<?= $type === '' ? 'is-active' : '' ?>" href="<?= url('admin/posts.php') ?>">All</a>
  <a class="<?= $type === 'news' ? 'is-active' : '' ?>" href="<?= url('admin/posts.php?type=news') ?>">News</a>
  <a class="<?= $type === 'article' ? 'is-active' : '' ?>" href="<?= url('admin/posts.php?type=article') ?>">Articles</a>
</nav>
<div class="acard">
  <?php if ($rows): ?>
  <div class="atable-wrap"><table class="atable">
    <thead><tr><th>Title</th><th>Type</th><th>Date</th><th>Status</th><th class="right">Actions</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $r): ?>
      <tr>
        <td><a href="<?= url('admin/post-edit.php?id=' . $r['id']) ?>"><strong><?= e($r['title']) ?></strong></a></td>
        <td><span class="tag tag--<?= e($r['type']) ?>"><?= $r['type'] === 'news' ? 'News' : 'Article' ?></span></td>
        <td><?= e(fmt_date($r['published_on']) ?: '—') ?></td>
        <td>
          <?= $r['is_published'] ? '<span class="tag tag--ok">Published</span>' : '<span class="tag">Draft</span>' ?>
          <?= $r['is_ticker'] ? '<span class="tag tag--ticker">Ticker</span>' : '' ?>
        </td>
        <td class="right nowrap">
          <?php if ($r['is_published']): ?><a class="abtn abtn--ghost abtn--sm" href="<?= post_url($r) ?>" target="_blank" rel="noopener">View</a><?php endif; ?>
          <a class="abtn abtn--ghost abtn--sm" href="<?= url('admin/post-edit.php?id=' . $r['id']) ?>">Edit</a>
          <?= delete_button((int)$r['id'], $r['type'] === 'news' ? 'news item' : 'article') ?>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table></div>
  <?php else: ?><p class="muted">Nothing here yet.</p><?php endif; ?>
</div>
<?php admin_render('News & Articles');
