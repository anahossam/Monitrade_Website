<?php
declare(strict_types=1);
require __DIR__ . '/../app/admin.php';
$me = require_admin();

$errors = [];
if (is_post()) {
    csrf_check();
    $action = $_POST['action'] ?? '';

    if ($action === 'password') {
        $row = q_one('SELECT password_hash FROM admins WHERE id = ?', [$me['id']]);
        $new = (string)($_POST['new_password'] ?? '');
        if (!password_verify((string)($_POST['current_password'] ?? ''), $row['password_hash'])) {
            $errors[] = 'Your current password is not correct.';
        } elseif (strlen($new) < 10) {
            $errors[] = 'The new password must be at least 10 characters.';
        } elseif ($new !== ($_POST['new_password2'] ?? '')) {
            $errors[] = 'The two new passwords do not match.';
        } else {
            q('UPDATE admins SET password_hash = ? WHERE id = ?', [password_hash($new, PASSWORD_DEFAULT), $me['id']]);
            session_regenerate_id(true);
            flash('success', 'Your password has been changed.');
            redirect('admin/account.php');
        }
    }

    if ($action === 'add') {
        $u = post_str('username', 60);
        $em = post_str('email', 190);
        $pw = (string)($_POST['password'] ?? '');
        if (!preg_match('~^[A-Za-z0-9._-]{3,60}$~', $u)) {
            $errors[] = 'Username must be 3–60 characters: letters, numbers, dot, dash or underscore.';
        }
        if (!filter_var($em, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid e-mail address.';
        }
        if (strlen($pw) < 10) {
            $errors[] = 'The password must be at least 10 characters.';
        }
        if (!$errors && q_one('SELECT id FROM admins WHERE username = ? OR email = ?', [$u, $em])) {
            $errors[] = 'An administrator with that username or e-mail already exists.';
        }
        if (!$errors) {
            q('INSERT INTO admins (username, email, password_hash) VALUES (?,?,?)', [$u, $em, password_hash($pw, PASSWORD_DEFAULT)]);
            flash('success', "Administrator “{$u}” added.");
            redirect('admin/account.php');
        }
    }

    if ($action === 'remove') {
        $rid = (int)($_POST['id'] ?? 0);
        if ($rid === (int)$me['id']) {
            $errors[] = 'You cannot remove your own account.';
        } else {
            q('DELETE FROM admins WHERE id = ?', [$rid]);
            flash('success', 'Administrator removed.');
            redirect('admin/account.php');
        }
    }
}

$admins = q_all('SELECT id, username, email, created_at, last_login_at FROM admins ORDER BY id');

ob_start(); ?>
<div class="ahead"><h1>Admins &amp; password</h1></div>
<?php foreach ($errors as $err): ?><div class="aalert aalert--error"><?= e($err) ?></div><?php endforeach; ?>
<div class="agrid">
  <div class="agrid__main">
    <section class="acard">
      <h2>Administrators</h2>
      <div class="atable-wrap"><table class="atable">
        <thead><tr><th>Username</th><th>E-mail</th><th>Last sign-in</th><th class="right"></th></tr></thead>
        <tbody>
        <?php foreach ($admins as $a): ?>
          <tr>
            <td><strong><?= e($a['username']) ?></strong><?= (int)$a['id'] === (int)$me['id'] ? ' <span class="tag">you</span>' : '' ?></td>
            <td><?= e($a['email']) ?></td>
            <td><?= $a['last_login_at'] ? e(date('d M Y H:i', strtotime($a['last_login_at']))) : '—' ?></td>
            <td class="right">
              <?php if ((int)$a['id'] !== (int)$me['id']): ?>
                <form method="post" class="inline" onsubmit="return confirm('Remove this administrator?')"><?= csrf_field() ?><input type="hidden" name="action" value="remove"><input type="hidden" name="id" value="<?= (int)$a['id'] ?>"><button class="abtn abtn--danger abtn--sm" type="submit">Remove</button></form>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table></div>
    </section>
    <form method="post" class="aform acard">
      <h2>Add administrator</h2>
      <?= csrf_field() ?><input type="hidden" name="action" value="add">
      <?= a_input('username', 'Username', '', 'text', ['required' => true, 'attrs' => 'autocomplete="off"']) ?>
      <?= a_input('email', 'E-mail', '', 'email', ['required' => true, 'attrs' => 'autocomplete="off"']) ?>
      <?= a_input('password', 'Password', '', 'password', ['required' => true, 'hint' => 'At least 10 characters. Share it with the person securely.', 'attrs' => 'autocomplete="new-password" minlength="10"']) ?>
      <div class="aactions"><button class="abtn abtn--primary" type="submit">Add administrator</button></div>
    </form>
  </div>
  <div class="agrid__side">
    <form method="post" class="aform acard">
      <h2>Change your password</h2>
      <?= csrf_field() ?><input type="hidden" name="action" value="password">
      <?= a_input('current_password', 'Current password', '', 'password', ['required' => true, 'attrs' => 'autocomplete="current-password"']) ?>
      <?= a_input('new_password', 'New password', '', 'password', ['required' => true, 'attrs' => 'autocomplete="new-password" minlength="10"']) ?>
      <?= a_input('new_password2', 'Repeat new password', '', 'password', ['required' => true, 'attrs' => 'autocomplete="new-password"']) ?>
      <div class="aactions"><button class="abtn abtn--primary" type="submit">Change password</button></div>
    </form>
  </div>
</div>
<?php admin_render('Admins & password');
