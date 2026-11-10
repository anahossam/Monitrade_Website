<?php
declare(strict_types=1);

function db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $c = $GLOBALS['config']['db'];
        $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s',
            $c['host'], (int)($c['port'] ?? 3306), $c['name'], $c['charset'] ?? 'utf8mb4');
        $pdo = new PDO($dsn, $c['user'], $c['password'], [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    }
    return $pdo;
}

/** Run a prepared statement and return it. */
function q(string $sql, array $params = []): PDOStatement
{
    $st = db()->prepare($sql);
    $st->execute($params);
    return $st;
}

function q_all(string $sql, array $params = []): array
{
    return q($sql, $params)->fetchAll();
}

function q_one(string $sql, array $params = []): ?array
{
    $row = q($sql, $params)->fetch();
    return $row === false ? null : $row;
}

/** Cached key/value site settings. */
function setting(string $key, string $default = ''): string
{
    static $cache = null;
    if ($cache === null) {
        $cache = [];
        try {
            foreach (q_all('SELECT `key`, `value` FROM settings') as $r) {
                $cache[$r['key']] = (string)$r['value'];
            }
        } catch (Throwable $e) {
            $cache = [];
        }
    }
    return $cache[$key] ?? $default;
}
