<?php
declare(strict_types=1);
require __DIR__ . '/../app/admin.php';
require_admin();

$id = (int)($_GET['id'] ?? 0);
$p = $id ? q_one('SELECT * FROM posts WHERE id = ?', [$id]) : null;
if ($id && !$p) {
    flash('error', 'That item no longer exists.');
    redirect('admin/posts.php');
}
$p = $p ?? [
    'id' => 0, 'type' => ($_GET['type'] ?? '') === 'article' ? 'article' : 'news', 'title' => '', 'slug' => '', 'summary' => '',
    'body' => '', 'image' => null, 'source' => '', 'published_on' => date('Y-m-d'), 'is_ticker' => 0, 'is_published' => 1,
];

$errors = [];
if (is_post()) {
    csrf_check();
    $p['type'] = ($_POST['type'] ?? '') === 'article' ? 'article' : 'news';
    $p['title'] = post_str('title', 255);
    $p['summary'] = post_str('summary', 500);
    $p['body'] = post_str('body', 100000);
    $p['source'] = post_str('source', 190);
    $date = post_str('published_on', 10);
    $p['published_on'] = preg_match('~^\d{4}-\d{2}-\d{2}$~', $date) ? $date : null;
    $p['is_ticker'] = !empty($_POST['is_ticker']) ? 1 : 0;
    $p['is_published'] = !empty($_POST['is_published']) ? 1 : 0;

    if ($p['title'] === '') {
        $errors[] = 'Please enter a title.';
    }
    if (!$errors) {
        try {
            $p['image'] = apply_image('image', 'posts', $p['image']);
        } catch (RuntimeException $ex) {
            $errors[] = $ex->getMessage();
        }
    }
    if (!$errors) {
        $slug = unique_slug('posts', $p['title'], $id);
        $vals = [$p['type'], $p['title'], $slug, $p['summary'] ?: null, $p['body'], $p['image'], $p['source'] ?: null,
            $p['published_on'], $p['is_ticker'], $p['is_published']];
        if ($id) {
            q('UPDATE posts SET type=?, title=?, slug=?, summary=?, body=?, image=?, source=?, published_on=?, is_ticker=?, is_published=? WHERE id=?', [...$vals, $id]);
        } else {
            q('INSERT INTO posts (type, title, slug, summary, body, image, source, published_on, is_ticker, is_published) VALUES (?,?,?,?,?,?,?,?,?,?)', $vals);
            $id = (int)db()->lastInsertId();
        }
        flash('success', 'Saved.');
        redirect('admin/post-edit.php?id=' . $id);
    }
}

$label = $p['type'] === 'article' ? 'article' : 'news item';
ob_start(); ?>
<div class="ahead">
  <h1><?= $p['id'] ? 'Edit ' . $label : 'Add ' . $label ?></h1>
  <a class="abtn abtn--ghost" href="<?= url('admin/posts.php') ?>">← Back to list</a>
</div>
<?php foreach ($errors as $err): ?><div class="aalert aalert--error"><?= e($err) ?></div><?php endforeach; ?>
<form method="post" enctype="multipart/form-data" class="aform acard">
  <?= csrf_field() ?>
  <div class="agrid">
    <div class="agrid__main">
      <?= a_input('title', 'Title', $p['title'], 'text', ['required' => true]) ?>
      <?= a_input('summary', 'Summary', $p['summary'], 'textarea', ['rows' => 3, 'hint' => 'One or two sentences shown on cards and in search results.']) ?>
      <?= a_input('body', 'Text', $p['body'], 'textarea', ['rows' => 16, 'hint' => 'Plain text. Leave an empty line between paragraphs.']) ?>
    </div>
    <div class="agrid__side">
      <?= a_select('type', 'Type', $p['type'], ['news' => 'News', 'article' => 'Article']) ?>
      <?= a_input('published_on', 'Date', $p['published_on'], 'date') ?>
      <?= a_input('source', 'Source', $p['source'], 'text', ['hint' => 'Optional, e.g. a newspaper name.']) ?>
      <?= a_image('image', 'Image', $p['image']) ?>
      <?= a_check('is_published', 'Published (visible on the website)', (bool)$p['is_published']) ?>
      <?= a_check('is_ticker', 'Show in the homepage news ticker', (bool)$p['is_ticker']) ?>
    </div>
  </div>
  <div class="aactions">
    <button class="abtn abtn--primary" type="submit">Save</button>
    <?php if ($p['id'] && $p['is_published']): ?><a class="abtn abtn--ghost" href="<?= post_url($p) ?>" target="_blank" rel="noopener">View on website ↗</a><?php endif; ?>
  </div>
</form>
<?php admin_render($p['id'] ? 'Edit ' . $label : 'Add ' . $label);
