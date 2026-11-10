<?php
declare(strict_types=1);
require __DIR__ . '/../app/admin.php';
require_admin();

handle_delete('brands', 'admin/brands.php', 'logo');
$rows = q_all('SELECT * FROM brands ORDER BY sort_order, name');

ob_start(); ?>
<div class="ahead">
  <h1>Brands</h1>
  <a class="abtn abtn--primary" href="<?= url('admin/brand-edit.php') ?>">+ Add brand</a>
</div>
<p class="muted">Brands appear in the “Highly trusted names in the industry” section of the home page. Without a logo, the brand name is shown as text.</p>
<div class="acard">
  <?php if ($rows): ?>
  <div class="atable-wrap"><table class="atable">
    <thead><tr><th></th><th>Brand</th><th>Description</th><th>Order</th><th>Status</th><th class="right">Actions</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $r): ?>
      <tr>
        <td class="thumb"><?php if ($r['logo']): ?><img src="<?= e(upload_url($r['logo'])) ?>" alt=""><?php endif; ?></td>
        <td><a href="<?= url('admin/brand-edit.php?id=' . $r['id']) ?>"><strong><?= e($r['name']) ?></strong></a></td>
        <td><?= e($r['description']) ?></td>
        <td><?= (int)$r['sort_order'] ?></td>
        <td><?= $r['is_published'] ? '<span class="tag tag--ok">Shown</span>' : '<span class="tag">Hidden</span>' ?></td>
        <td class="right nowrap">
          <a class="abtn abtn--ghost abtn--sm" href="<?= url('admin/brand-edit.php?id=' . $r['id']) ?>">Edit</a>
          <?= delete_button((int)$r['id'], 'brand') ?>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table></div>
  <?php else: ?><p class="muted">No brands yet.</p><?php endif; ?>
</div>
<?php admin_render('Brands');
