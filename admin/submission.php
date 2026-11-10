<?php
declare(strict_types=1);
require __DIR__ . '/../app/admin.php';
require_admin();

$id = (int)($_GET['id'] ?? 0);
$s = q_one('SELECT * FROM submissions WHERE id = ?', [$id]);
if (!$s) {
    flash('error', 'That submission no longer exists.');
    redirect('admin/submissions.php');
}

if (is_post()) {
    csrf_check();
    $action = $_POST['action'] ?? '';
    if ($action === 'delete') {
        q('DELETE FROM submissions WHERE id = ?', [$id]);
        flash('success', 'Submission deleted.');
        redirect('admin/submissions.php');
    }
    if ($action === 'unread') {
        q('UPDATE submissions SET is_read = 0 WHERE id = ?', [$id]);
        flash('success', 'Marked as unread.');
        redirect('admin/submissions.php');
    }
}
if (!$s['is_read']) {
    q('UPDATE submissions SET is_read = 1 WHERE id = ?', [$id]);
}

$fields = [
    'first_name' => 'First name', 'last_name' => 'Last name', 'company' => 'Company', 'job_title' => 'Job title',
    'email' => 'E-mail', 'mobile' => 'Mobile', 'phone' => 'Phone', 'country' => 'Country', 'city' => 'City', 'subject' => 'Subject',
];
$replySubject = 'Re: ' . ($s['subject'] ?: ($s['type'] === 'request' ? 'Your business request' : 'Your message') . ' — Monitrade');

ob_start(); ?>
<div class="ahead">
  <h1><?= $s['type'] === 'request' ? 'Business request' : 'Contact message' ?></h1>
  <a class="abtn abtn--ghost" href="<?= url('admin/submissions.php') ?>">← All submissions</a>
</div>
<div class="agrid">
  <div class="agrid__main acard">
    <p class="muted">Received <?= e(date('l j F Y, H:i', strtotime($s['created_at']))) ?> · IP <?= e($s['ip']) ?> ·
      <?= $s['mail_sent'] ? 'Notification e-mail sent' : 'Notification e-mail was <strong>not</strong> sent' ?></p>
    <div class="amessage"><?= nl2br(e($s['message'])) ?></div>
    <div class="aactions">
      <a class="abtn abtn--primary" href="mailto:<?= e($s['email']) ?>?subject=<?= rawurlencode($replySubject) ?>">Reply by e-mail</a>
      <form method="post" class="inline"><?= csrf_field() ?><input type="hidden" name="action" value="unread"><button class="abtn abtn--ghost" type="submit">Mark as unread</button></form>
      <form method="post" class="inline" onsubmit="return confirm('Delete this submission? This cannot be undone.')"><?= csrf_field() ?><input type="hidden" name="action" value="delete"><button class="abtn abtn--danger" type="submit">Delete</button></form>
    </div>
  </div>
  <div class="agrid__side acard">
    <h2>Sender</h2>
    <dl class="adl">
      <?php foreach ($fields as $k => $label): if (!empty($s[$k])): ?>
        <dt><?= e($label) ?></dt>
        <dd><?php if ($k === 'email'): ?><a href="mailto:<?= e($s[$k]) ?>"><?= e($s[$k]) ?></a><?php elseif (in_array($k, ['phone', 'mobile'], true)): ?><a href="<?= e(tel_href($s[$k])) ?>"><?= e($s[$k]) ?></a><?php else: ?><?= e($s[$k]) ?><?php endif; ?></dd>
      <?php endif; endforeach; ?>
    </dl>
  </div>
</div>
<?php admin_render('Submission');
