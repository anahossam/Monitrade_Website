<?php
declare(strict_types=1);
require __DIR__ . '/../app/admin.php';
require_admin();

handle_delete('team_members', 'admin/team.php');

// Inline add / edit on one page — the team list is short.
$editId = (int)($_GET['id'] ?? 0);
$edit = $editId ? q_one('SELECT * FROM team_members WHERE id = ?', [$editId]) : null;
$errors = [];
if (is_post() && ($_POST['action'] ?? '') === 'save') {
    csrf_check();
    $id = (int)($_POST['id'] ?? 0);
    $d = [
        'name'  => post_str('name', 120),
        'title' => post_str('title', 160),
        'phone' => post_str('phone', 60),
        'email' => post_str('email', 190),
        'sort'  => (int)($_POST['sort_order'] ?? 0),
    ];
    if ($d['name'] === '') {
        $errors[] = 'Please enter a name.';
    }
    if ($d['email'] !== '' && !filter_var($d['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'The e-mail address is not valid.';
    }
    if (!$errors) {
        $vals = [$d['name'], $d['title'] ?: null, $d['phone'] ?: null, $d['email'] ?: null, $d['sort']];
        if ($id) {
            q('UPDATE team_members SET name=?, title=?, phone=?, email=?, sort_order=? WHERE id=?', [...$vals, $id]);
        } else {
            q('INSERT INTO team_members (name, title, phone, email, sort_order) VALUES (?,?,?,?,?)', $vals);
        }
        flash('success', 'Team member saved.');
        redirect('admin/team.php');
    }
    $edit = ['id' => $id, 'name' => $d['name'], 'title' => $d['title'], 'phone' => $d['phone'], 'email' => $d['email'], 'sort_order' => $d['sort']];
}
$edit = $edit ?? ['id' => 0, 'name' => '', 'title' => '', 'phone' => '', 'email' => '', 'sort_order' => 0];
$rows = q_all('SELECT * FROM team_members ORDER BY sort_order, id');

ob_start(); ?>
<div class="ahead"><h1>Team</h1></div>
<p class="muted">Team members are shown on the Company Profile and Contact pages, including any phone number or e-mail you enter.</p>
<div class="agrid">
  <div class="agrid__main acard">
    <?php if ($rows): ?>
    <div class="atable-wrap"><table class="atable">
      <thead><tr><th>Name</th><th>Title</th><th>Phone</th><th>Order</th><th class="right">Actions</th></tr></thead>
      <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><strong><?= e($r['name']) ?></strong><?php if ($r['email']): ?><br><small><?= e($r['email']) ?></small><?php endif; ?></td>
          <td><?= e($r['title']) ?></td>
          <td class="nowrap"><?= e($r['phone'] ?: '—') ?></td>
          <td><?= (int)$r['sort_order'] ?></td>
          <td class="right nowrap">
            <a class="abtn abtn--ghost abtn--sm" href="<?= url('admin/team.php?id=' . $r['id']) ?>">Edit</a>
            <?= delete_button((int)$r['id'], 'team member') ?>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table></div>
    <?php else: ?><p class="muted">No team members yet.</p><?php endif; ?>
  </div>
  <div class="agrid__side">
    <form method="post" class="aform acard" id="edit">
      <h2><?= $edit['id'] ? 'Edit team member' : 'Add team member' ?></h2>
      <?php foreach ($errors as $err): ?><div class="aalert aalert--error"><?= e($err) ?></div><?php endforeach; ?>
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="save">
      <input type="hidden" name="id" value="<?= (int)$edit['id'] ?>">
      <?= a_input('name', 'Name', $edit['name'], 'text', ['required' => true]) ?>
      <?= a_input('title', 'Job title', $edit['title']) ?>
      <?= a_input('phone', 'Phone / mobile', $edit['phone'], 'tel') ?>
      <?= a_input('email', 'E-mail', $edit['email'], 'email') ?>
      <?= a_input('sort_order', 'Display order', (string)$edit['sort_order'], 'number') ?>
      <div class="aactions">
        <button class="abtn abtn--primary" type="submit">Save</button>
        <?php if ($edit['id']): ?><a class="abtn abtn--ghost" href="<?= url('admin/team.php') ?>">Cancel</a><?php endif; ?>
      </div>
    </form>
  </div>
</div>
<?php admin_render('Team');
