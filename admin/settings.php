<?php
declare(strict_types=1);
require __DIR__ . '/../app/admin.php';
require_admin();

$groups = [
    'Company' => [
        'company_name'  => ['Company name', 'text'],
        'tagline'       => ['Tagline', 'text'],
        'founded_year'  => ['Founded (year)', 'text'],
        'chairman'      => ['Chairman', 'text'],
        'company_email' => ['Company e-mail (shown on the site; receives enquiries unless set in config)', 'email'],
    ],
    'Cairo office' => [
        'cairo_address' => ['Address', 'text'],
        'cairo_phone'   => ['Telephone (separate several with “/”)', 'text'],
        'cairo_fax'     => ['Fax', 'text'],
        'cairo_mobile'  => ['Mobile', 'text'],
    ],
    'Alexandria office' => [
        'alex_address' => ['Address', 'text'],
        'alex_phone'   => ['Telephone (separate several with “/”)', 'text'],
        'alex_fax'     => ['Fax', 'text'],
        'alex_mobile'  => ['Mobile', 'text'],
    ],
    'Page text' => [
        'home_intro'  => ['Home page — about Monitrade (empty line between paragraphs)', 'textarea'],
        'profile_who' => ['Company Profile — “Who is Monitrade?”', 'textarea'],
        'profile_why' => ['Company Profile — “Why Monitrade?” (one reason per line)', 'textarea'],
    ],
];

if (is_post()) {
    csrf_check();
    $st = db()->prepare('INSERT INTO settings (`key`, `value`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)');
    foreach ($groups as $fields) {
        foreach ($fields as $key => [$label, $type]) {
            $st->execute([$key, post_str($key, $type === 'textarea' ? 20000 : 500)]);
        }
    }
    flash('success', 'Settings saved.');
    redirect('admin/settings.php');
}

ob_start(); ?>
<div class="ahead"><h1>Company settings</h1></div>
<form method="post" class="aform">
  <?= csrf_field() ?>
  <?php foreach ($groups as $title => $fields): ?>
    <fieldset class="acard">
      <legend><?= e($title) ?></legend>
      <?php foreach ($fields as $key => [$label, $type]): ?>
        <?= a_input($key, $label, setting($key), $type, $type === 'textarea' ? ['rows' => $key === 'home_intro' ? 12 : 6] : []) ?>
      <?php endforeach; ?>
    </fieldset>
  <?php endforeach; ?>
  <div class="aactions asticky"><button class="abtn abtn--primary" type="submit">Save settings</button></div>
</form>
<?php admin_render('Company settings');
