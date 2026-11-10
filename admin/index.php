<?php
declare(strict_types=1);
require __DIR__ . '/../app/admin.php';
$admin = require_admin();

$stats = [
    ['Unread submissions', unread_count(), 'submissions.php?filter=unread'],
    ['Products', (int)q_one('SELECT COUNT(*) c FROM products')['c'], 'products.php'],
    ['News items', (int)q_one("SELECT COUNT(*) c FROM posts WHERE type = 'news'")['c'], 'posts.php?type=news'],
    ['Articles', (int)q_one("SELECT COUNT(*) c FROM posts WHERE type = 'article'")['c'], 'posts.php?type=article'],
    ['Brands', (int)q_one('SELECT COUNT(*) c FROM brands')['c'], 'brands.php'],
];
$recent = q_all('SELECT * FROM submissions ORDER BY created_at DESC LIMIT 8');

ob_start(); ?>
<div class="ahead"><h1>Dashboard</h1></div>
<div class="astats">
  <?php foreach ($stats as [$label, $n, $href]): ?>
    <a class="astat" href="<?= url('admin/' . $href) ?>"><span class="astat__n"><?= $n ?></span><span><?= e($label) ?></span></a>
  <?php endforeach; ?>
</div>

<div class="aquick">
  <a class="abtn abtn--primary" href="<?= url('admin/post-edit.php?type=news') ?>">+ Add news</a>
  <a class="abtn abtn--ghost" href="<?= url('admin/post-edit.php?type=article') ?>">+ Add article</a>
  <a class="abtn abtn--ghost" href="<?= url('admin/product-edit.php') ?>">+ Add product</a>
  <a class="abtn abtn--ghost" href="<?= url('admin/brand-edit.php') ?>">+ Add brand</a>
</div>

<section class="acard">
  <div class="acard__head"><h2>Latest submissions</h2><a href="<?= url('admin/submissions.php') ?>">View all →</a></div>
  <?php if ($recent): ?>
  <div class="atable-wrap"><table class="atable">
    <thead><tr><th>Date</th><th>Type</th><th>From</th><th>Subject</th></tr></thead>
    <tbody>
      <?php foreach ($recent as $s): ?>
        <tr class="<?= $s['is_read'] ? '' : 'is-unread' ?>">
          <td><?= e(date('d M Y H:i', strtotime($s['created_at']))) ?></td>
          <td><span class="tag tag--<?= e($s['type']) ?>"><?= $s['type'] === 'request' ? 'Business request' : 'Contact' ?></span></td>
          <td><a href="<?= url('admin/submission.php?id=' . $s['id']) ?>"><?= e(trim($s['first_name'] . ' ' . $s['last_name'])) ?></a><br><small><?= e($s['company'] ?: $s['email']) ?></small></td>
          <td><?= e(excerpt($s['subject'] ?: $s['message'], 70)) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table></div>
  <?php else: ?><p class="muted">No submissions yet. Contact messages and business requests from the website will appear here.</p><?php endif; ?>
</section>
<?php admin_render('Dashboard');
