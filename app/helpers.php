<?php
declare(strict_types=1);

const SECTIONS = [
    'machines'    => 'Machines',
    'accessories' => 'Accessories',
    'software'    => 'Software',
];
const CATEGORIES = [
    'spinning' => 'Spinning',
    'weaving'  => 'Weaving',
    'dyeing'   => 'Dyeing',
    'garments' => 'Garments',
];

/** HTML-escape. */
function e(?string $s): string
{
    return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Site-relative URL honouring the configured base path. */
function url(string $path = ''): string
{
    $base = rtrim($GLOBALS['config']['base_path'] ?? '', '/');
    return $base . '/' . ltrim($path, '/');
}

/** Absolute URL for sharing / canonical tags. */
function abs_url(string $path = ''): string
{
    return rtrim($GLOBALS['config']['base_url'] ?? '', '/') . url($path);
}

function asset(string $path): string
{
    $file = ROOT . '/' . ltrim($path, '/');
    $v = is_file($file) ? '?v=' . filemtime($file) : '';
    return url($path) . $v;
}

function redirect(string $to, int $code = 302): never
{
    if (!preg_match('~^https?://~', $to)) {
        $to = url($to);
    }
    header('Location: ' . $to, true, $code);
    exit;
}

function slugify(string $s): string
{
    $s = strtolower(trim($s));
    $s = strtr($s, ['ä' => 'a', 'ö' => 'o', 'ü' => 'u', 'ß' => 'ss', 'é' => 'e', 'è' => 'e', '&' => 'and']);
    $s = preg_replace('~[^a-z0-9]+~u', '-', $s) ?? '';
    $s = trim($s, '-');
    return $s !== '' ? substr($s, 0, 120) : 'item';
}

/** Render a view inside the public layout. */
function view(string $name, array $data = [], string $layout = 'layout'): void
{
    extract($data, EXTR_SKIP);
    ob_start();
    require APP . "/views/{$name}.php";
    $content = ob_get_clean();
    require APP . "/views/{$layout}.php";
}

/** Render a partial without layout. */
function partial(string $name, array $data = []): void
{
    extract($data, EXTR_SKIP);
    require APP . "/views/{$name}.php";
}

function not_found(): never
{
    http_response_code(404);
    view('404', ['title' => 'Page not found']);
    exit;
}

/* ---------- CSRF ---------- */
function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

function csrf_check(): void
{
    $t = $_POST['_csrf'] ?? '';
    if (!is_string($t) || !hash_equals(csrf_token(), $t)) {
        http_response_code(419);
        exit('Your session expired. Please go back, reload the page and try again.');
    }
}

/* ---------- Flash messages ---------- */
function flash(string $type, string $msg): void
{
    $_SESSION['flash'][] = [$type, $msg];
}

function flashes(): array
{
    $f = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $f;
}

/* ---------- Form helpers ---------- */
function old(string $key, string $default = ''): string
{
    return e((string)($_POST[$key] ?? $default));
}

function post_str(string $key, int $max = 1000): string
{
    $v = $_POST[$key] ?? '';
    if (!is_string($v)) {
        return '';
    }
    return mb_substr(trim($v), 0, $max);
}

/** Simple arithmetic challenge (replaces the old site's "code verification"). */
function captcha_new(string $form): string
{
    $a = random_int(1, 9);
    $b = random_int(1, 9);
    $_SESSION['captcha'][$form] = $a + $b;
    return "What is {$a} + {$b}?";
}

function captcha_ok(string $form): bool
{
    $expected = $_SESSION['captcha'][$form] ?? null;
    unset($_SESSION['captcha'][$form]);
    return $expected !== null && (string)$expected === trim((string)($_POST['captcha'] ?? ''));
}

function client_ip(): string
{
    return substr((string)($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0'), 0, 45);
}

/** Render plain text (from the admin) as safe paragraphs. */
function paragraphs(?string $text): string
{
    $text = trim((string)$text);
    if ($text === '') {
        return '';
    }
    $out = '';
    foreach (preg_split("~\R{2,}~", $text) as $p) {
        $out .= '<p>' . nl2br(e(trim($p))) . "</p>\n";
    }
    return $out;
}

function excerpt(?string $text, int $len = 160): string
{
    $t = trim(preg_replace('~\s+~u', ' ', (string)$text) ?? '');
    return mb_strlen($t) > $len ? rtrim(mb_substr($t, 0, $len)) . '…' : $t;
}

/** Detect Arabic text so it can be rendered right-to-left. */
function is_rtl(?string $s): bool
{
    return (bool)preg_match('~\p{Arabic}~u', (string)$s);
}

function fmt_date(?string $d): string
{
    if (!$d || str_starts_with($d, '0000')) {
        return '';
    }
    $ts = strtotime($d);
    return $ts ? date('j F Y', $ts) : '';
}

function tel_href(string $phone): string
{
    return 'tel:' . preg_replace('~[^0-9+]~', '', $phone);
}

function upload_url(?string $file): string
{
    return $file ? url('uploads/' . ltrim($file, '/')) : '';
}

function post_url(array $p): string
{
    return url(($p['type'] === 'article' ? 'articles/' : 'news/') . $p['id'] . '-' . $p['slug']);
}

function product_url(array $p): string
{
    return url('products/item/' . $p['id'] . '-' . $p['slug']);
}

function category_url(string $section, ?string $category = null): string
{
    return url('products/' . $section . ($category ? '/' . $category : ''));
}

/** Labelled input with inline error, for the public forms. */
function form_field(string $name, string $label, array $errors, string $type = 'text', bool $required = false, string $autocomplete = '', string $default = ''): string
{
    $id = 'f-' . $name;
    $err = $errors[$name] ?? '';
    $attrs = ($required ? ' required aria-required="true"' : '')
        . ($autocomplete ? ' autocomplete="' . e($autocomplete) . '"' : '')
        . ($err ? ' aria-invalid="true" aria-describedby="' . $id . '-err"' : '');
    $html = '<div class="field' . ($err ? ' field--error' : '') . '"><label for="' . $id . '">' . e($label)
        . ($required ? ' <span class="req" aria-hidden="true">*</span>' : '') . '</label>';
    if ($type === 'textarea') {
        $html .= '<textarea id="' . $id . '" name="' . e($name) . '" rows="6"' . $attrs . '>' . old($name, $default) . '</textarea>';
    } else {
        $html .= '<input id="' . $id . '" type="' . e($type) . '" name="' . e($name) . '" value="' . old($name, $default) . '"' . $attrs . '>';
    }
    if ($err) {
        $html .= '<p class="field__error" id="' . $id . '-err">' . e($err) . '</p>';
    }
    return $html . '</div>';
}

/** Security question + hidden honeypot shared by both public forms. */
function form_guard(string $question, array $errors): string
{
    return '<div class="hp" aria-hidden="true"><label for="f-website">Leave this empty</label><input id="f-website" type="text" name="website" tabindex="-1" autocomplete="off"></div>'
        . str_replace('<input ', '<input inputmode="numeric" ', form_field('captcha', 'Security question: ' . $question, $errors, 'text', true, 'off'));
}

function countries(): array
{
    return require APP . '/countries.php';
}
