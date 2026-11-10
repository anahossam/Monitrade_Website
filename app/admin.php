<?php
declare(strict_types=1);

/** Bootstrap for every admin page. */
require_once __DIR__ . '/bootstrap.php';
header('X-Robots-Tag: noindex, nofollow');

/** Wrap the markup buffered since ob_start() in the admin layout. */
function admin_render(string $title): void
{
    $content = ob_get_clean();
    $admin = current_admin();
    require APP . '/views/admin/layout.php';
}

function is_post(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

/** Field helpers for admin forms. */
function a_input(string $name, string $label, ?string $value, string $type = 'text', array $o = []): string
{
    $id = 'a-' . $name;
    $req = !empty($o['required']) ? ' required' : '';
    $hint = isset($o['hint']) ? '<small class="hint">' . e($o['hint']) . '</small>' : '';
    $extra = $o['attrs'] ?? '';
    if ($type === 'textarea') {
        $rows = (int)($o['rows'] ?? 6);
        $field = "<textarea id=\"$id\" name=\"" . e($name) . "\" rows=\"$rows\"$req $extra>" . e($value) . '</textarea>';
    } else {
        $field = "<input id=\"$id\" type=\"" . e($type) . '" name="' . e($name) . '" value="' . e($value) . "\"$req $extra>";
    }
    return '<div class="afield"><label for="' . $id . '">' . e($label) . ($req ? ' <span class="req">*</span>' : '') . "</label>$field$hint</div>";
}

function a_select(string $name, string $label, ?string $value, array $options, bool $allowEmpty = false, string $emptyLabel = '—'): string
{
    $id = 'a-' . $name;
    $html = '<div class="afield"><label for="' . $id . '">' . e($label) . "</label><select id=\"$id\" name=\"" . e($name) . '">';
    if ($allowEmpty) {
        $html .= '<option value="">' . e($emptyLabel) . '</option>';
    }
    foreach ($options as $k => $v) {
        $html .= '<option value="' . e((string)$k) . '"' . ((string)$k === (string)$value ? ' selected' : '') . '>' . e($v) . '</option>';
    }
    return $html . '</select></div>';
}

function a_check(string $name, string $label, bool $checked): string
{
    return '<label class="acheck"><input type="hidden" name="' . e($name) . '" value="0"><input type="checkbox" name="'
        . e($name) . '" value="1"' . ($checked ? ' checked' : '') . '> ' . e($label) . '</label>';
}

/** Image field with preview and "remove" checkbox. */
function a_image(string $name, string $label, ?string $current): string
{
    $html = '<div class="afield"><label for="a-' . e($name) . '">' . e($label) . '</label>';
    if ($current) {
        $html .= '<div class="aimg"><img src="' . e(upload_url($current)) . '" alt=""><label class="acheck"><input type="checkbox" name="remove_'
            . e($name) . '" value="1"> Remove image</label></div>';
    }
    return $html . '<input id="a-' . e($name) . '" type="file" name="' . e($name) . '" accept="image/jpeg,image/png,image/gif,image/webp">'
        . '<small class="hint">JPG, PNG, GIF or WebP, up to 5 MB.</small></div>';
}

/**
 * Apply an image upload / removal for a record. Returns the new stored path.
 * Throws RuntimeException with a message for the user on a bad upload.
 */
function apply_image(string $field, string $folder, ?string $current): ?string
{
    $new = handle_image_upload($field, $folder);
    if ($new !== null) {
        delete_upload($current);
        return $new;
    }
    if (!empty($_POST['remove_' . $field])) {
        delete_upload($current);
        return null;
    }
    return $current;
}

/** Handle the standard POST "delete" action on list pages. */
function handle_delete(string $table, string $back, ?string $imageCol = null): void
{
    if (!is_post() || ($_POST['action'] ?? '') !== 'delete') {
        return;
    }
    csrf_check();
    $allowed = ['posts', 'products', 'brands', 'team_members', 'submissions'];
    if (!in_array($table, $allowed, true)) {
        return;
    }
    $id = (int)($_POST['id'] ?? 0);
    if ($imageCol) {
        $row = q_one("SELECT `$imageCol` FROM `$table` WHERE id = ?", [$id]);
        if ($row) {
            delete_upload($row[$imageCol]);
        }
    }
    q("DELETE FROM `$table` WHERE id = ?", [$id]);
    flash('success', 'Deleted.');
    redirect($back);
}

function delete_button(int $id, string $what): string
{
    return '<form method="post" class="inline" onsubmit="return confirm(\'Delete this ' . e($what) . '? This cannot be undone.\')">'
        . csrf_field() . '<input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="' . $id
        . '"><button type="submit" class="abtn abtn--danger abtn--sm">Delete</button></form>';
}

/** Unique slug for a table. */
function unique_slug(string $table, string $base, int $ignoreId = 0): string
{
    $slug = slugify($base);
    $try = $slug;
    $n = 2;
    while (q_one("SELECT id FROM `$table` WHERE slug = ? AND id <> ?", [$try, $ignoreId])) {
        $try = $slug . '-' . $n++;
    }
    return $try;
}

function unread_count(): int
{
    return (int)q_one('SELECT COUNT(*) c FROM submissions WHERE is_read = 0')['c'];
}
