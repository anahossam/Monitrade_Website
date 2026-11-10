<?php
declare(strict_types=1);
require __DIR__ . '/../app/admin.php';
require_admin();

$id = (int)($_GET['id'] ?? 0);
$b = $id ? q_one('SELECT * FROM brands WHERE id = ?', [$id]) : null;
if ($id && !$b) {
    flash('error', 'That brand no longer exists.');
    redirect('admin/brands.php');
}
$b = $b ?? ['id' => 0, 'name' => '', 'description' => '', 'website' => '', 'logo' => null, 'sort_order' => 0, 'is_published' => 1];

$errors = [];
if (is_post()) {
    csrf_check();
    $b['name'] = post_str('name', 120);
    $b['description'] = post_str('description', 255);
    $b['website'] = post_str('website', 255);
    $b['sort_order'] = (int)($_POST['sort_order'] ?? 0);
    $b['is_published'] = !empty($_POST['is_published']) ? 1 : 0;

    if ($b['name'] === '') {
        $errors[] = 'Please enter the brand name.';
    }
    if ($b['website'] !== '' && !preg_match('~^https?://~i', $b['website'])) {
        $b['website'] = 'https://' . $b['website'];
    }
    if ($b['website'] !== '' && !filter_var($b['website'], FILTER_VALIDATE_URL)) {
        $errors[] = 'The website address is not valid.';
    }
    if (!$errors) {
        try {
            $b['logo'] = apply_image('logo', 'brands', $b['logo']);
        } catch (RuntimeException $ex) {
            $errors[] = $ex->getMessage();
        }
    }
    if (!$errors) {
        $vals = [$b['name'], $b['description'] ?: null, $b['website'] ?: null, $b['logo'], $b['sort_order'], $b['is_published']];
        if ($id) {
            q('UPDATE brands SET name=?, description=?, website=?, logo=?, sort_order=?, is_published=? WHERE id=?', [...$vals, $id]);
        } else {
            q('INSERT INTO brands (name, description, website, logo, sort_order, is_published) VALUES (?,?,?,?,?,?)', $vals);
        }
        flash('success', 'Brand saved.');
        redirect('admin/brands.php');
    }
}

ob_start(); ?>
<div class="ahead">
  <h1><?= $b['id'] ? 'Edit brand' : 'Add brand' ?></h1>
  <a class="abtn abtn--ghost" href="<?= url('admin/brands.php') ?>">← Back to brands</a>
</div>
<?php foreach ($errors as $err): ?><div class="aalert aalert--error"><?= e($err) ?></div><?php endforeach; ?>
<form method="post" enctype="multipart/form-data" class="aform acard anarrow">
  <?= csrf_field() ?>
  <?= a_input('name', 'Brand name', $b['name'], 'text', ['required' => true]) ?>
  <?= a_input('description', 'Short description', $b['description'], 'text', ['hint' => 'e.g. “Travellers & cots”.']) ?>
  <?= a_input('website', 'Website', $b['website'], 'url', ['hint' => 'Optional. The brand links here when set.']) ?>
  <?= a_input('sort_order', 'Display order', (string)$b['sort_order'], 'number') ?>
  <?= a_image('logo', 'Logo', $b['logo']) ?>
  <?= a_check('is_published', 'Show on the website', (bool)$b['is_published']) ?>
  <div class="aactions"><button class="abtn abtn--primary" type="submit">Save brand</button></div>
</form>
<?php admin_render($b['id'] ? 'Edit brand' : 'Add brand');
