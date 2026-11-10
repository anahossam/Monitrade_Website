<?php
declare(strict_types=1);
require __DIR__ . '/../app/admin.php';
require_admin();

$filter = in_array($_GET['filter'] ?? '', ['unread', 'contact', 'request'], true) ? $_GET['filter'] : '';
handle_delete('submissions', 'admin/submissions.php' . ($filter ? "?filter=$filter" : ''));

if (is_post() && ($_POST['action'] ?? '') === 'mark_all_read') {
    csrf_check();
    q('UPDATE submissions SET is_read = 1 WHERE is_read = 0');
    flash('success', 'All submissions marked as read.');
    redirect('admin/submissions.php');
}

$where = match ($filter) {
    'unread'  => 'WHERE is_read = 0',
    'contact' => "WHERE type = 'contact'",
    'request' => "WHERE type = 'request'",
    default   => '',
};
$page = max(1, (int)($_GET['page'] ?? 1));
$per = 30;
$total = (int)q_one("SELECT COUNT(*) c FROM submissions $where")['c'];
$rows = q_all("SELECT * FROM submissions $where ORDER BY created_at DESC LIMIT $per OFFSET " . (($page - 1) * $per));
$pages = max(1, (int)ceil($total / $per));

ob_start(); ?>
<div class="ahead">
  <h1>Submissions</h1>
  <?php if (unread_count()): ?>
  <form method="post" class="inline"><?= csrf_field() ?><input type="hidden" name="action" value="mark_all_read"><button class="abtn abtn--ghost" type="submit">Mark all as read</button></form>
  <?php endif; ?>
</div>
<nav class="afilters">
  <?php foreach (['' => 'All', 'unread' => 'Unread', 'request' => 'Business requests', 'contact' => 'Contact messages'] as $k => $v): ?>
    <a class="<?= $filter === $k ? 'is-active' : '' ?>" href="<?= url('admin/submissions.php' . ($k ? "?filter=$k" : '')) ?>"><?= e($v) ?></a>
  <?php endforeach; ?>
</nav>
<div class="acard">
  <?php if ($rows): ?>
  <div class="atable-wrap"><table class="atable">
    <thead><tr><th>Date</th><th>Type</th><th>From</th><th>Subject / message</th><th>E-mailed</th><th class="right">Actions</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $s): ?>
      <tr class="<?= $s['is_read'] ? '' : 'is-unread' ?>">
        <td class="nowrap"><?= e(date('d M Y H:i', strtotime($s['created_at']))) ?></td>
        <td><span class="tag tag--<?= e($s['type']) ?>"><?= $s['type'] === 'request' ? 'Request' : 'Contact' ?></span></td>
        <td><a href="<?= url('admin/submission.php?id=' . $s['id']) ?>"><strong><?= e(trim($s['first_name'] . ' ' . $s['last_name'])) ?></strong></a><br><small><?= e($s['company'] ?: $s['email']) ?></small></td>
        <td><?= e(excerpt(($s['subject'] ? $s['subject'] . ' — ' : '') . $s['message'], 90)) ?></td>
        <td><?= $s['mail_sent'] ? '✓' : '<span class="muted" title="E-mail could not be sent; the message is stored here.">—</span>' ?></td>
        <td class="right nowrap">
          <a class="abtn abtn--ghost abtn--sm" href="<?= url('admin/submission.php?id=' . $s['id']) ?>">Open</a>
          <?= delete_button((int)$s['id'], 'submission') ?>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table></div>
  <?php if ($pages > 1): ?>
    <nav class="apager">
      <?php for ($i = 1; $i <= $pages; $i++): ?>
        <a class="<?= $i === $page ? 'is-active' : '' ?>" href="<?= url('admin/submissions.php?' . http_build_query(array_filter(['filter' => $filter, 'page' => $i]))) ?>"><?= $i ?></a>
      <?php endfor; ?>
    </nav>
  <?php endif; ?>
  <?php else: ?><p class="muted">No submissions.</p><?php endif; ?>
</div>
<?php admin_render('Submissions');
