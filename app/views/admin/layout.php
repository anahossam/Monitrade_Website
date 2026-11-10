<?php
/** @var string $content @var ?array $admin @var string $title */
$self = basename($_SERVER['SCRIPT_NAME'] ?? '');
$nav = [
    'index.php'       => ['Dashboard', ['index.php']],
    'submissions.php' => ['Submissions', ['submissions.php', 'submission.php']],
    'posts.php'       => ['News & Articles', ['posts.php', 'post-edit.php']],
    'products.php'    => ['Products', ['products.php', 'product-edit.php']],
    'brands.php'      => ['Brands', ['brands.php', 'brand-edit.php']],
    'team.php'        => ['Team', ['team.php', 'team-edit.php']],
    'settings.php'    => ['Company Settings', ['settings.php']],
    'account.php'     => ['Admins & Password', ['account.php']],
];
$unread = $admin ? unread_count() : 0;
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title><?= e($title ?? 'Admin') ?> · Monitrade Admin</title>
<link rel="icon" href="<?= asset('assets/img/favicon.svg') ?>" type="image/svg+xml">
<link rel="stylesheet" href="<?= asset('assets/css/admin.css') ?>">
</head>
<body class="<?= $admin ? 'has-nav' : 'is-auth' ?>">
<?php if ($admin): ?>
<header class="abar">
  <a class="abar__brand" href="<?= url('admin/') ?>"><img src="<?= asset('assets/img/logo.svg') ?>" alt="Monitrade" width="150" height="41"><span>Admin</span></a>
  <button class="abar__toggle" type="button" aria-expanded="false" aria-controls="anav">Menu</button>
  <div class="abar__right">
    <a href="<?= url('/') ?>" target="_blank" rel="noopener">View website ↗</a>
    <span class="abar__user"><?= e($admin['username']) ?></span>
    <form method="post" action="<?= url('admin/logout.php') ?>" class="inline"><?= csrf_field() ?><button type="submit" class="abtn abtn--ghost abtn--sm">Sign out</button></form>
  </div>
</header>
<nav id="anav" class="anav" aria-label="Admin">
  <ul>
    <?php foreach ($nav as $file => [$label, $files]): ?>
      <li><a href="<?= url('admin/' . ($file === 'index.php' ? '' : $file)) ?>" class="<?= in_array($self, $files, true) ? 'is-active' : '' ?>"><?= e($label) ?><?php if ($file === 'submissions.php' && $unread): ?> <span class="badge"><?= $unread ?></span><?php endif; ?></a></li>
    <?php endforeach; ?>
    <li class="anav__mobile"><a href="<?= url('/') ?>" target="_blank" rel="noopener">View website ↗</a></li>
    <li class="anav__mobile"><form method="post" action="<?= url('admin/logout.php') ?>"><?= csrf_field() ?><button type="submit">Sign out (<?= e($admin['username']) ?>)</button></form></li>
  </ul>
</nav>
<?php endif; ?>
<main class="amain">
  <?php foreach (flashes() as [$type, $msg]): ?>
    <div class="aalert aalert--<?= e($type) ?>" role="status"><?= e($msg) ?></div>
  <?php endforeach; ?>
  <?= $content ?>
</main>
<script>
(function () {
  var t = document.querySelector('.abar__toggle'), n = document.getElementById('anav');
  if (t && n) t.addEventListener('click', function () {
    var o = n.classList.toggle('is-open'); t.setAttribute('aria-expanded', String(o));
  });
})();
</script>
</body>
</html>
