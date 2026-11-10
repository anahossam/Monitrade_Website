<?php
declare(strict_types=1);
require __DIR__ . '/../app/admin.php';

if (current_admin()) {
    redirect('admin/');
}
if (!q_one('SELECT id FROM admins LIMIT 1')) {
    redirect('admin/setup.php');
}

$error = '';
if (is_post()) {
    csrf_check();
    if (login_locked()) {
        $error = 'Too many failed attempts. Please wait ' . LOGIN_WINDOW_MIN . ' minutes and try again.';
    } elseif (admin_login(post_str('username', 190), (string)($_POST['password'] ?? ''))) {
        redirect('admin/');
    } else {
        $error = 'Incorrect username or password.';
    }
}

ob_start(); ?>
<div class="auth">
  <img class="auth__logo" src="<?= asset('assets/img/logo.svg') ?>" alt="Monitrade" width="220" height="60">
  <h1>Admin sign in</h1>
  <?php if ($error): ?><div class="aalert aalert--error" role="alert"><?= e($error) ?></div><?php endif; ?>
  <form method="post" class="aform">
    <?= csrf_field() ?>
    <?= a_input('username', 'Username or e-mail', post_str('username', 190), 'text', ['required' => true, 'attrs' => 'autocomplete="username" autofocus']) ?>
    <?= a_input('password', 'Password', '', 'password', ['required' => true, 'attrs' => 'autocomplete="current-password"']) ?>
    <button class="abtn abtn--primary abtn--block" type="submit">Sign in</button>
  </form>
  <p class="auth__back"><a href="<?= url('/') ?>">← Back to website</a></p>
</div>
<?php admin_render('Sign in');
