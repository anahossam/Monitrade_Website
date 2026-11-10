<?php
declare(strict_types=1);

const UPLOAD_MAX_BYTES = 5 * 1024 * 1024;
const UPLOAD_TYPES = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/gif'  => 'gif',
    'image/webp' => 'webp',
];

/**
 * Validate and store an uploaded image in uploads/{$folder}.
 * Returns the stored path relative to uploads/ (e.g. "products/abc.jpg"),
 * null when no file was sent, or throws RuntimeException with a
 * user-friendly message.
 */
function handle_image_upload(string $field, string $folder): ?string
{
    $f = $_FILES[$field] ?? null;
    if (!$f || ($f['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if ($f['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('The image could not be uploaded (error ' . (int)$f['error'] . ').');
    }
    if ($f['size'] > UPLOAD_MAX_BYTES) {
        throw new RuntimeException('The image is larger than 5 MB.');
    }
    $info = @getimagesize($f['tmp_name']);
    $mime = $info['mime'] ?? '';
    if (!isset(UPLOAD_TYPES[$mime])) {
        throw new RuntimeException('Only JPG, PNG, GIF or WebP images are allowed.');
    }
    $folder = preg_replace('~[^a-z]~', '', $folder);
    $dir = ROOT . '/uploads/' . $folder;
    if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
        throw new RuntimeException('Upload folder is not writable.');
    }
    $base = slugify(pathinfo((string)$f['name'], PATHINFO_FILENAME));
    $name = substr($base, 0, 50) . '-' . bin2hex(random_bytes(4)) . '.' . UPLOAD_TYPES[$mime];
    if (!move_uploaded_file($f['tmp_name'], "$dir/$name")) {
        throw new RuntimeException('The image could not be saved.');
    }
    @chmod("$dir/$name", 0644);
    return "$folder/$name";
}

/** Delete a previously uploaded file (only inside uploads/). */
function delete_upload(?string $rel): void
{
    if (!$rel || str_contains($rel, '..')) {
        return;
    }
    $path = ROOT . '/uploads/' . $rel;
    if (is_file($path)) {
        @unlink($path);
    }
}
