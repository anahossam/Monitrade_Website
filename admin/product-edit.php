<?php
declare(strict_types=1);
require __DIR__ . '/../app/admin.php';
require_admin();

$id = (int)($_GET['id'] ?? 0);
$p = $id ? q_one('SELECT * FROM products WHERE id = ?', [$id]) : null;
if ($id && !$p) {
    flash('error', 'That product no longer exists.');
    redirect('admin/products.php');
}
$p = $p ?? [
    'id' => 0, 'section' => isset(SECTIONS[$_GET['section'] ?? '']) ? $_GET['section'] : 'machines', 'category' => 'spinning',
    'name' => '', 'model' => '', 'specs' => '', 'offer' => '', 'image' => null, 'sort_order' => 0, 'is_published' => 1,
];

$errors = [];
if (is_post()) {
    csrf_check();
    $p['section'] = isset(SECTIONS[$_POST['section'] ?? '']) ? $_POST['section'] : 'machines';
    $cat = $_POST['category'] ?? '';
    $p['category'] = ($p['section'] !== 'software' && isset(CATEGORIES[$cat])) ? $cat : null;
    $p['name'] = post_str('name', 190);
    $p['model'] = post_str('model', 190);
    $p['specs'] = post_str('specs', 20000);
    $p['offer'] = post_str('offer', 190);
    $p['sort_order'] = (int)($_POST['sort_order'] ?? 0);
    $p['is_published'] = !empty($_POST['is_published']) ? 1 : 0;

    if ($p['name'] === '') {
        $errors[] = 'Please enter the product name.';
    }
    if ($p['section'] !== 'software' && !$p['category']) {
        $errors[] = 'Please choose a category for machines and accessories.';
    }
    if (!$errors) {
        try {
            $p['image'] = apply_image('image', 'products', $p['image']);
        } catch (RuntimeException $ex) {
            $errors[] = $ex->getMessage();
        }
    }
    if (!$errors) {
        $slug = unique_slug('products', $p['name'] . ($p['model'] && $p['model'] !== $p['name'] ? ' ' . $p['model'] : ''), $id);
        $vals = [$p['section'], $p['category'], $p['name'], $slug, $p['model'] ?: null, $p['specs'], $p['offer'] ?: null, $p['image'], $p['sort_order'], $p['is_published']];
        if ($id) {
            q('UPDATE products SET section=?, category=?, name=?, slug=?, model=?, specs=?, offer=?, image=?, sort_order=?, is_published=? WHERE id=?', [...$vals, $id]);
        } else {
            q('INSERT INTO products (section, category, name, slug, model, specs, offer, image, sort_order, is_published) VALUES (?,?,?,?,?,?,?,?,?,?)', $vals);
            $id = (int)db()->lastInsertId();
        }
        flash('success', 'Product saved.');
        redirect('admin/product-edit.php?id=' . $id);
    }
}

ob_start(); ?>
<div class="ahead">
  <h1><?= $p['id'] ? 'Edit product' : 'Add product' ?></h1>
  <a class="abtn abtn--ghost" href="<?= url('admin/products.php') ?>">← Back to products</a>
</div>
<?php foreach ($errors as $err): ?><div class="aalert aalert--error"><?= e($err) ?></div><?php endforeach; ?>
<form method="post" enctype="multipart/form-data" class="aform acard">
  <?= csrf_field() ?>
  <div class="agrid">
    <div class="agrid__main">
      <?= a_input('name', 'Product name', $p['name'], 'text', ['required' => true]) ?>
      <?= a_input('model', 'Model', $p['model'], 'text') ?>
      <?= a_input('specs', 'Specifications / description', $p['specs'], 'textarea', ['rows' => 12, 'hint' => 'Plain text. Leave an empty line between paragraphs.']) ?>
      <?= a_input('offer', 'Offer', $p['offer'], 'text', ['hint' => 'Optional, e.g. a price or special offer. Leave empty to hide.']) ?>
    </div>
    <div class="agrid__side">
      <?= a_select('section', 'Section', $p['section'], SECTIONS) ?>
      <?= a_select('category', 'Category', $p['category'], CATEGORIES, true, '— none (software) —') ?>
      <?= a_input('sort_order', 'Display order', (string)$p['sort_order'], 'number', ['hint' => 'Lower numbers are shown first.']) ?>
      <?= a_image('image', 'Image', $p['image']) ?>
      <?= a_check('is_published', 'Published (visible on the website)', (bool)$p['is_published']) ?>
    </div>
  </div>
  <div class="aactions">
    <button class="abtn abtn--primary" type="submit">Save product</button>
    <?php if ($p['id'] && $p['is_published']): ?><a class="abtn abtn--ghost" href="<?= product_url($p) ?>" target="_blank" rel="noopener">View on website ↗</a><?php endif; ?>
  </div>
</form>
<?php admin_render($p['id'] ? 'Edit product' : 'Add product');
