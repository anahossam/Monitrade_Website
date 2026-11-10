<?php
declare(strict_types=1);

/* ============================================================ pages */

function page_home(): void
{
    view('home', [
        'title'    => null,
        'products' => q_all("SELECT * FROM products WHERE is_published = 1 AND image IS NOT NULL ORDER BY section, sort_order LIMIT 8"),
        'news'     => q_all("SELECT * FROM posts WHERE is_published = 1 AND type = 'news' ORDER BY COALESCE(published_on, '1000-01-01') DESC, id DESC LIMIT 3"),
        'articles' => q_all("SELECT * FROM posts WHERE is_published = 1 AND type = 'article' ORDER BY COALESCE(published_on, '1000-01-01') DESC, id DESC LIMIT 3"),
        'counts'   => category_counts(),
    ]);
}

function page_profile(): void
{
    view('profile', [
        'title' => 'Company Profile',
        'team'  => q_all('SELECT * FROM team_members ORDER BY sort_order, id'),
    ]);
}

function page_products(): void
{
    view('products', ['title' => 'Products', 'counts' => category_counts()]);
}

function page_category(string $section, ?string $category): void
{
    if ($section === 'software' && $category !== null) {
        not_found();
    }
    $sql = 'SELECT * FROM products WHERE is_published = 1 AND section = ?';
    $params = [$section];
    if ($category) {
        $sql .= ' AND category = ?';
        $params[] = $category;
    }
    $sql .= ' ORDER BY category, sort_order, id';
    $title = $category ? CATEGORIES[$category] . ' ' . SECTIONS[$section] : SECTIONS[$section];

    view('category', [
        'title'    => $title,
        'section'  => $section,
        'category' => $category,
        'items'    => q_all($sql, $params),
        'counts'   => category_counts(),
    ]);
}

function page_product(int $id): void
{
    $p = q_one('SELECT * FROM products WHERE id = ? AND is_published = 1', [$id]);
    if (!$p) {
        not_found();
    }
    $related = q_all(
        'SELECT * FROM products WHERE is_published = 1 AND section = ? AND category <=> ? AND id <> ? ORDER BY sort_order LIMIT 3',
        [$p['section'], $p['category'], $id]
    );
    view('product', [
        'title'       => $p['name'] . ($p['model'] && $p['model'] !== $p['name'] ? ' — ' . $p['model'] : ''),
        'description' => excerpt($p['specs'], 155),
        'p'           => $p,
        'related'     => $related,
    ]);
}

function page_posts(string $type): void
{
    view('posts', [
        'title' => $type === 'news' ? 'Latest News' : 'Articles',
        'type'  => $type,
        'items' => q_all(
            "SELECT * FROM posts WHERE is_published = 1 AND type = ? ORDER BY COALESCE(published_on, '1000-01-01') DESC, id DESC",
            [$type]
        ),
    ]);
}

function page_post(string $type, int $id): void
{
    $p = q_one('SELECT * FROM posts WHERE id = ? AND type = ? AND is_published = 1', [$id, $type]);
    if (!$p) {
        not_found();
    }
    view('post', [
        'title'       => $p['title'],
        'description' => $p['summary'] ?: excerpt($p['body'], 155),
        'p'           => $p,
        'more'        => q_all('SELECT * FROM posts WHERE is_published = 1 AND type = ? AND id <> ? ORDER BY id DESC LIMIT 4', [$type, $id]),
    ]);
}

function page_contact(): void
{
    $errors = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $errors = handle_submission('contact');
    }
    view('contact', [
        'title'   => 'Contact Us',
        'errors'  => $errors,
        'captcha' => captcha_new('contact'),
        'team'    => q_all('SELECT * FROM team_members ORDER BY sort_order, id'),
    ]);
}

function page_request(): void
{
    $errors = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $errors = handle_submission('request');
    }
    $products = q_all('SELECT id, name, model, section FROM products WHERE is_published = 1 ORDER BY section, name');
    view('request', [
        'title'     => 'Business Request',
        'errors'    => $errors,
        'captcha'   => captcha_new('request'),
        'products'  => $products,
        'preselect' => (int)($_GET['product'] ?? 0),
    ]);
}

function page_share(): void
{
    // Replaces the old "Send to a Friend" e-mail form (which could be abused
    // to send spam through the server) with share links the visitor sends themself.
    $target = (string)($_GET['url'] ?? '/');
    if (!str_starts_with($target, '/') || str_starts_with($target, '//')) {
        $target = '/';
    }
    view('share', ['title' => 'Send to a Friend', 'link' => rtrim($GLOBALS['config']['base_url'], '/') . $target]);
}

function page_search(): void
{
    $term = trim(mb_substr((string)($_GET['q'] ?? ''), 0, 100));
    $products = $posts = [];
    if (mb_strlen($term) >= 2) {
        $like = '%' . addcslashes($term, '%_\\') . '%';
        $products = q_all(
            'SELECT * FROM products WHERE is_published = 1 AND (name LIKE ? OR model LIKE ? OR specs LIKE ?) ORDER BY section, sort_order',
            [$like, $like, $like]
        );
        $posts = q_all(
            'SELECT * FROM posts WHERE is_published = 1 AND (title LIKE ? OR summary LIKE ? OR body LIKE ?) ORDER BY id DESC',
            [$like, $like, $like]
        );
    }
    view('search', ['title' => 'Search', 'term' => $term, 'products' => $products, 'posts' => $posts]);
}

function page_sitemap(): void
{
    view('sitemap', [
        'title' => 'Site Map',
        'posts' => q_all("SELECT id, type, title, slug FROM posts WHERE is_published = 1 ORDER BY type, id DESC"),
        'counts' => category_counts(),
    ]);
}

function sitemap_xml(): void
{
    header('Content-Type: application/xml; charset=UTF-8');
    $urls = ['', 'company-profile', 'products', 'products/machines', 'products/accessories', 'products/software',
        'news', 'articles', 'contact', 'business-request', 'sitemap', 'terms', 'privacy', 'navigation'];
    foreach (['machines', 'accessories'] as $s) {
        foreach (array_keys(CATEGORIES) as $c) {
            $urls[] = "products/$s/$c";
        }
    }
    $base = rtrim($GLOBALS['config']['base_url'], '/');
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    foreach ($urls as $u) {
        echo '  <url><loc>' . e(abs_url($u)) . "</loc></url>\n";
    }
    foreach (q_all('SELECT id, slug FROM products WHERE is_published = 1') as $p) {
        echo '  <url><loc>' . e($base . product_url($p)) . "</loc></url>\n";
    }
    foreach (q_all('SELECT id, type, slug FROM posts WHERE is_published = 1') as $p) {
        echo '  <url><loc>' . e($base . post_url($p)) . "</loc></url>\n";
    }
    echo "</urlset>\n";
}

/* ============================================================ shared */

/** Published product counts keyed "section" and "section/category". */
function category_counts(): array
{
    $out = [];
    foreach (q_all('SELECT section, category, COUNT(*) n FROM products WHERE is_published = 1 GROUP BY section, category') as $r) {
        $out[$r['section']] = ($out[$r['section']] ?? 0) + (int)$r['n'];
        if ($r['category']) {
            $out[$r['section'] . '/' . $r['category']] = (int)$r['n'];
        }
    }
    return $out;
}

function ticker_items(): array
{
    try {
        return q_all("SELECT id, type, title, slug FROM posts WHERE is_published = 1 AND is_ticker = 1 ORDER BY id DESC LIMIT 6");
    } catch (Throwable $e) {
        return [];
    }
}

/**
 * Validate, store and e-mail a contact message or business request.
 * Redirects to /thank-you on success; returns field errors otherwise.
 */
function handle_submission(string $type): array
{
    csrf_check();
    $errors = [];

    // Honeypot: real visitors never fill this hidden field.
    if (!empty($_POST['website'])) {
        redirect('thank-you');
    }

    $d = [
        'first_name' => post_str('first_name', 100),
        'last_name'  => post_str('last_name', 100),
        'company'    => post_str('company', 160),
        'job_title'  => post_str('job_title', 120),
        'email'      => post_str('email', 190),
        'phone'      => post_str('phone', 60),
        'mobile'     => post_str('mobile', 60),
        'country'    => post_str('country', 100),
        'city'       => post_str('city', 100),
        'subject'    => post_str('subject', 200),
        'message'    => post_str('message', 5000),
    ];

    if ($d['first_name'] === '') {
        $errors['first_name'] = 'Please enter your name.';
    }
    if (!filter_var($d['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid e-mail address.';
    }
    if (mb_strlen($d['message']) < 5) {
        $errors['message'] = $type === 'request' ? 'Please describe your request.' : 'Please enter your message.';
    }
    if ($d['country'] !== '' && !in_array($d['country'], countries(), true)) {
        $d['country'] = '';
    }
    if (!captcha_ok($type)) {
        $errors['captcha'] = 'The answer to the security question was not correct.';
    }

    // Rate limit: max 5 submissions per IP per hour.
    $recent = (int)q_one('SELECT COUNT(*) c FROM submissions WHERE ip = ? AND created_at > (NOW() - INTERVAL 1 HOUR)', [client_ip()])['c'];
    if ($recent >= 5) {
        $errors['form'] = 'Too many messages were sent from your connection. Please try again later or call us.';
    }

    if ($errors) {
        return $errors;
    }

    q('INSERT INTO submissions (type, first_name, last_name, company, job_title, email, phone, mobile, country, city, subject, message, ip)
       VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)', [
        $type, $d['first_name'], $d['last_name'] ?: null, $d['company'] ?: null, $d['job_title'] ?: null, $d['email'],
        $d['phone'] ?: null, $d['mobile'] ?: null, $d['country'] ?: null, $d['city'] ?: null, $d['subject'] ?: null,
        $d['message'], client_ip(),
    ]);
    $id = (int)db()->lastInsertId();

    $label = $type === 'request' ? 'Business request' : 'Contact message';
    $lines = ["New {$label} from the Monitrade website", str_repeat('-', 50)];
    $names = ['first_name' => 'First name', 'last_name' => 'Last name', 'company' => 'Company', 'job_title' => 'Job title',
        'email' => 'E-mail', 'phone' => 'Phone', 'mobile' => 'Mobile', 'country' => 'Country', 'city' => 'City', 'subject' => 'Subject'];
    foreach ($names as $k => $n) {
        if ($d[$k] !== '') {
            $lines[] = str_pad($n . ':', 12) . ' ' . $d[$k];
        }
    }
    $lines[] = '';
    $lines[] = $d['message'];
    $lines[] = '';
    $lines[] = 'View in admin: ' . abs_url('admin/submission.php?id=' . $id);

    $to = enquiry_recipients();
    if ($to !== '') {
        $sent = send_mail($to, "[Monitrade] {$label}: " . ($d['subject'] ?: $d['first_name'] . ' ' . $d['last_name']),
            implode("\n", $lines), $d['email']);
        if ($sent) {
            q('UPDATE submissions SET mail_sent = 1 WHERE id = ?', [$id]);
        }
    }
    redirect('thank-you');
}
