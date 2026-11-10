<?php
declare(strict_types=1);
require __DIR__ . '/../app/admin.php';

if (is_post()) {
    csrf_check();
    admin_logout();
    flash('success', 'You have been signed out.');
}
redirect('admin/login.php');
