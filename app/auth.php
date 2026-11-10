<?php
declare(strict_types=1);

const LOGIN_MAX_ATTEMPTS = 5;      // per IP
const LOGIN_WINDOW_MIN   = 15;     // minutes

function current_admin(): ?array
{
    static $admin = false;
    if ($admin === false) {
        $admin = null;
        if (!empty($_SESSION['admin_id'])) {
            $admin = q_one('SELECT id, username, email FROM admins WHERE id = ?', [$_SESSION['admin_id']]);
        }
    }
    return $admin;
}

function require_admin(): array
{
    $a = current_admin();
    if (!$a) {
        redirect('admin/login.php');
    }
    // Idle timeout: 2 hours.
    if (isset($_SESSION['admin_seen']) && time() - $_SESSION['admin_seen'] > 7200) {
        admin_logout();
        flash('error', 'You were signed out after 2 hours of inactivity.');
        redirect('admin/login.php');
    }
    $_SESSION['admin_seen'] = time();
    header('Cache-Control: no-store');
    return $a;
}

function login_locked(): bool
{
    $n = (int)q_one(
        'SELECT COUNT(*) c FROM login_attempts WHERE ip = ? AND attempted_at > (NOW() - INTERVAL ' . LOGIN_WINDOW_MIN . ' MINUTE)',
        [client_ip()]
    )['c'];
    return $n >= LOGIN_MAX_ATTEMPTS;
}

function admin_login(string $username, string $password): bool
{
    $row = q_one('SELECT * FROM admins WHERE username = ? OR email = ?', [$username, $username]);
    if (!$row || !password_verify($password, $row['password_hash'])) {
        q('INSERT INTO login_attempts (ip) VALUES (?)', [client_ip()]);
        return false;
    }
    if (password_needs_rehash($row['password_hash'], PASSWORD_DEFAULT)) {
        q('UPDATE admins SET password_hash = ? WHERE id = ?', [password_hash($password, PASSWORD_DEFAULT), $row['id']]);
    }
    q('DELETE FROM login_attempts WHERE ip = ?', [client_ip()]);
    q('UPDATE admins SET last_login_at = NOW() WHERE id = ?', [$row['id']]);
    session_regenerate_id(true);
    $_SESSION['admin_id'] = (int)$row['id'];
    $_SESSION['admin_seen'] = time();
    return true;
}

function admin_logout(): void
{
    unset($_SESSION['admin_id'], $_SESSION['admin_seen']);
    session_regenerate_id(true);
}
