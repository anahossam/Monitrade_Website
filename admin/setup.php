<?php
/**
 * First-run setup: creates the first administrator.
 * Only works while the admins table is empty — afterwards it redirects to login.
 */
declare(strict_types=1);
require __DIR__ . '/../app/admin.php';

if (q_one('SELECT id FROM admins LIMIT 1')) {
    redirect('admin/login.php');
}

$errors = [];
if (is_post()) {
    csrf_check();
    $username = post_str('username', 60);
    $email = post_str('email', 190);
    $pass = (string)($_POST['password'] ?? '');
    if (!preg_match('~^[A-Za-z0-9._-]{3,60}$~', $username)) {
        $errors[] = 'Username must be 3–60 characters: letters, numbers, dot, dash or underscore.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid e-mail address.';
    }
    if (strlen($pass) < 10) {
        $errors[] = 'Password must be at least 10 characters.';
    }
    if ($pass !== ($_POST['password2'] ?? '')) {
        $errors[] = 'The two passwords do not match.';
    }
    if (!$errors) {
        q('INSERT INTO admins (username, email, password_hash) VALUES (?,?,?)', [$username, $email, password_hash($pass, PASSWORD_DEFAULT)]);
        admin_login($username, $pass);
        flash('success', 'Welcome! Your administrator account has been created.');
        redirect('admin/');
    }
}

ob_start(); ?>
<div class="auth">
  <img class="auth__logo" src="<?= asset('assets/img/logo.svg') ?>" alt="Monitrade" width="220" height="60">
  <h1>Create the first administrator</h1>
  <p class="muted">This page is only available until the first admin account exists.</p>
  <?php foreach ($errors as $err): ?><div class="aalert aalert--error"><?= e($err) ?></div><?php endforeach; ?>
  <form method="post" class="aform">
    <?= csrf_field() ?>
    <?= a_input('username', 'Username', post_str('username', 60), 'text', ['required' => true, 'attrs' => 'autocomplete="username"']) ?>
    <?= a_input('email', 'E-mail', post_str('email', 190), 'email', ['required' => true, 'attrs' => 'autocomplete="email"']) ?>
    <?= a_input('password', 'Password', '', 'password', ['required' => true, 'hint' => 'At least 10 characters.', 'attrs' => 'autocomplete="new-password" minlength="10"']) ?>
    <?= a_input('password2', 'Repeat password', '', 'password', ['required' => true, 'attrs' => 'autocomplete="new-password"']) ?>
    <button class="abtn abtn--primary abtn--block" type="submit">Create account</button>
  </form>
</div>
<?php admin_render('Setup');
