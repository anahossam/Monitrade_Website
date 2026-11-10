<?php partial('partials/page-head', ['heading' => 'Thank you', 'crumbs' => ['Thank you' => null]]); ?>
<section class="section">
  <div class="container narrow center">
    <span class="big-check" aria-hidden="true"><?= icon('check') ?></span>
    <h2 class="section-title">Your message has been received</h2>
    <p class="lead">Thank you for contacting Monitrade. A member of our team will get back to you as soon as possible.</p>
    <p>If your request is urgent, please call our Cairo office on <a href="<?= e(tel_href(trim(explode('/', setting('cairo_phone'))[0]))) ?>"><?= e(trim(explode('/', setting('cairo_phone'))[0])) ?></a>.</p>
    <p><a class="btn btn--primary" href="<?= url('/') ?>">Back to the home page</a></p>
  </div>
</section>
