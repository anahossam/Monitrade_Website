<?php
declare(strict_types=1);
require __DIR__ . '/../app/admin.php';
require_admin();

$section = isset(SECTIONS[$_GET['section'] ?? '']) ? $_GET['section'] : '';
handle_delete('products', 'admin/products.php' . ($section ? "?section=$section" : ''), 'image');

$rows = q_all('SELECT * FROM products' . ($section ? ' WHERE section = ?' : '') . ' ORDER BY section, category, sort_order, name', $section ? [$section] : []);

ob_start(); ?>
<div class="ahead">
  <h1>Products</h1>
  <a class="abtn abtn--primary" href="<?= url('admin/product-edit.php' . ($section ? "?section=$section" : '')) ?>">+ Add product</a>
</div>
<nav class="afilters">
  <a class="<?= $section === '' ? 'is-active' : '' ?>" href="<?= url('admin/products.php') ?>">All</a>
  <?php foreach (SECTIONS as $k => $v): ?><a class="<?= $section === $k ? 'is-active' : '' ?>" href="<?= url('admin/products.php?section=' . $k) ?>"><?= e($v) ?></a><?php endforeach; ?>
</nav>
<div class="acard">
  <?php if ($rows): ?>
  <div class="atable-wrap"><table class="atable">
    <thead><tr><th></th><th>Name / model</th><th>Category</th><th>Order</th><th>Status</th><th class="right">Actions</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $r): ?>
      <tr>
        <td class="thumb"><?php if ($r['image']): ?><img src="<?= e(upload_url($r['image'])) ?>" alt=""><?php endif; ?></td>
        <td><a href="<?= url('admin/product-edit.php?id=' . $r['id']) ?>"><strong><?= e($r['name']) ?></strong></a><?php if ($r['model']): ?><br><small><?= e($r['model']) ?></small><?php endif; ?></td>
        <td><?= e(SECTIONS[$r['section']]) ?><?= $r['category'] ? ' · ' . e(CATEGORIES[$r['category']]) : '' ?></td>
        <td><?= (int)$r['sort_order'] ?></td>
        <td><?= $r['is_published'] ? '<span class="tag tag--ok">Published</span>' : '<span class="tag">Hidden</span>' ?></td>
        <td class="right nowrap">
          <?php if ($r['is_published']): ?><a class="abtn abtn--ghost abtn--sm" href="<?= product_url($r) ?>" target="_blank" rel="noopener">View</a><?php endif; ?>
          <a class="abtn abtn--ghost abtn--sm" href="<?= url('admin/product-edit.php?id=' . $r['id']) ?>">Edit</a>
          <?= delete_button((int)$r['id'], 'product') ?>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table></div>
  <?php else: ?><p class="muted">No products yet.</p><?php endif; ?>
</div>
<?php admin_render('Products');
