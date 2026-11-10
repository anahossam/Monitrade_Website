<?php
/** @var string $link */
partial('partials/page-head', ['heading' => 'Send to a Friend', 'crumbs' => ['Send to a Friend' => null],
    'lead' => 'Share this page with a colleague using your own e-mail or messaging app.']);
$subject = 'Have a look at this page on the Monitrade website';
?>
<section class="section">
  <div class="container narrow">
    <div class="share-card">
      <label for="share-link" class="share-card__label">Page link</label>
      <div class="share-card__row">
        <input id="share-link" type="text" readonly value="<?= e($link) ?>">
        <button class="btn btn--dark" type="button" data-copy="#share-link"><?= icon('link', 'icon icon--sm') ?> <span>Copy link</span></button>
      </div>
      <div class="share-card__buttons">
        <a class="btn btn--primary" href="mailto:?subject=<?= rawurlencode($subject) ?>&amp;body=<?= rawurlencode($link) ?>"><?= icon('mail', 'icon icon--sm') ?> Send by e-mail</a>
        <a class="btn btn--ghost" target="_blank" rel="noopener" href="https://wa.me/?text=<?= rawurlencode($subject . ': ' . $link) ?>"><?= icon('whatsapp', 'icon icon--sm') ?> WhatsApp</a>
        <a class="btn btn--ghost" target="_blank" rel="noopener" href="https://www.linkedin.com/sharing/share-offsite/?url=<?= rawurlencode($link) ?>">LinkedIn</a>
      </div>
    </div>
  </div>
</section>
