<?php
/** @var array $errors @var string $captcha @var array $team */
partial('partials/page-head', ['heading' => 'Contact Us', 'crumbs' => ['Contact Us' => null],
    'lead' => 'Two offices to make sure we are always reachable — call, e-mail or send us a message.']);
$offices = [
    'cairo' => 'Cairo Office',
    'alex'  => 'Alexandria Office',
];
$email = setting('company_email');
?>
<section class="section">
  <div class="container">
    <div class="offices">
      <?php foreach ($offices as $k => $label): ?>
        <div class="office">
          <h2 class="h3"><?= e($label) ?></h2>
          <ul class="office__list">
            <li><?= icon('pin') ?><div><span class="office__k">Address</span><?= e(setting("{$k}_address")) ?></div></li>
            <?php if (setting("{$k}_phone")): ?><li><?= icon('phone') ?><div><span class="office__k">Tel</span>
              <?php foreach (array_map('trim', explode('/', setting("{$k}_phone"))) as $i => $ph): ?><?= $i ? ' / ' : '' ?><a href="<?= e(tel_href($ph)) ?>"><?= e($ph) ?></a><?php endforeach; ?>
            </div></li><?php endif; ?>
            <?php if (setting("{$k}_fax")): ?><li><?= icon('fax') ?><div><span class="office__k">Fax</span><?= e(setting("{$k}_fax")) ?></div></li><?php endif; ?>
            <?php if (setting("{$k}_mobile")): ?><li><?= icon('mobile') ?><div><span class="office__k">Mobile</span><a href="<?= e(tel_href(setting("{$k}_mobile"))) ?>"><?= e(setting("{$k}_mobile")) ?></a></div></li><?php endif; ?>
            <?php if ($email): ?><li><?= icon('mail') ?><div><span class="office__k">E-mail</span><a href="mailto:<?= e($email) ?>"><?= e($email) ?></a></div></li><?php endif; ?>
          </ul>
          <a class="link-arrow" target="_blank" rel="noopener" href="https://www.google.com/maps/search/?api=1&amp;query=<?= rawurlencode(setting("{$k}_address")) ?>">Open in Google Maps <?= icon('arrow', 'icon icon--sm') ?></a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section section--tint">
  <div class="container contact-grid">
    <div>
      <h2 class="section-title section-title--sm">Send us a message</h2>
      <p>For quotations on machines or spare parts, please use the <a href="<?= url('business-request') ?>">business request form</a>.</p>
      <?php if (!empty($errors['form'])): ?><div class="alert alert--error"><?= e($errors['form']) ?></div><?php endif; ?>
      <?php if ($errors && empty($errors['form'])): ?><div class="alert alert--error" role="alert">Please correct the highlighted fields.</div><?php endif; ?>
      <form class="form" method="post" action="<?= url('contact') ?>" novalidate>
        <?= csrf_field() ?>
        <div class="form__row">
          <?= form_field('first_name', 'Name', $errors, 'text', true, 'name') ?>
          <?= form_field('company', 'Company', $errors, 'text', false, 'organization') ?>
        </div>
        <div class="form__row">
          <?= form_field('email', 'E-mail', $errors, 'email', true, 'email') ?>
          <?= form_field('phone', 'Phone', $errors, 'tel', false, 'tel') ?>
        </div>
        <?= form_field('subject', 'Subject', $errors) ?>
        <?= form_field('message', 'Message', $errors, 'textarea', true) ?>
        <?= form_guard($captcha, $errors) ?>
        <button class="btn btn--primary" type="submit">Send message</button>
      </form>
    </div>
    <?php if ($team): ?>
    <aside class="contact-team">
      <h2 class="h4">Talk to our team</h2>
      <ul>
        <?php foreach ($team as $t): ?>
          <li>
            <strong><?= e($t['name']) ?></strong>
            <?php if ($t['title']): ?><span><?= e($t['title']) ?></span><?php endif; ?>
            <?php if ($t['phone']): ?><a href="<?= e(tel_href($t['phone'])) ?>"><?= icon('mobile', 'icon icon--sm') ?> <?= e($t['phone']) ?></a><?php endif; ?>
            <?php if ($t['email']): ?><a href="mailto:<?= e($t['email']) ?>"><?= icon('mail', 'icon icon--sm') ?> <?= e($t['email']) ?></a><?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </aside>
    <?php endif; ?>
  </div>
</section>
