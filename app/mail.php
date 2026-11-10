<?php
declare(strict_types=1);

/**
 * Send a plain-text e-mail with PHP's mail(), which cPanel hosts route
 * through their local mail server. Returns false if sending failed — the
 * submission is still stored in the database either way.
 */
function send_mail(string $to, string $subject, string $body, ?string $replyTo = null): bool
{
    $m = $GLOBALS['config']['mail'] ?? [];
    $from = $m['from'] ?? 'no-reply@localhost';
    $fromName = $m['from_name'] ?? 'Website';

    $clean = static fn(string $s) => str_replace(["\r", "\n"], '', $s);

    $headers = [
        'MIME-Version: 1.0',
        'Content-Type: text/plain; charset=UTF-8',
        'Content-Transfer-Encoding: 8bit',
        'From: ' . mb_encode_mimeheader($clean($fromName)) . ' <' . $clean($from) . '>',
    ];
    if ($replyTo && filter_var($replyTo, FILTER_VALIDATE_EMAIL)) {
        $headers[] = 'Reply-To: ' . $clean($replyTo);
    }

    try {
        return @mail(
            $clean($to),
            mb_encode_mimeheader($clean($subject), 'UTF-8'),
            $body,
            implode("\r\n", $headers),
            filter_var($from, FILTER_VALIDATE_EMAIL) ? '-f' . $from : ''
        );
    } catch (Throwable $e) {
        error_log('Mail failed: ' . $e->getMessage());
        return false;
    }
}

/** Recipients for website enquiries. */
function enquiry_recipients(): string
{
    $to = trim((string)($GLOBALS['config']['mail']['to'] ?? ''));
    return $to !== '' ? $to : setting('company_email');
}
