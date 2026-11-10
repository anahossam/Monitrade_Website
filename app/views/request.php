<?php
/** @var array $errors @var string $captcha @var array $products @var int $preselect */
partial('partials/page-head', ['heading' => 'Business Request', 'crumbs' => ['Business Request' => null],
    'lead' => 'Tell us what you need — machines, spare parts or technical support — and we will reply with a quotation.']);
$selCountry = (string)($_POST['country'] ?? 'Egypt');
$subjectDefault = '';
foreach ($products as $p) {
    if ((int)$p['id'] === $preselect) {
        $subjectDefault = 'Quotation: ' . $p['name'] . ($p['model'] && $p['model'] !== $p['name'] ? ' (' . $p['model'] . ')' : '');
    }
}
?>
<section class="section">
  <div class="container form-wrap">
    <p class="muted">Fields marked with <span class="req">*</span> are required.</p>
    <?php if (!empty($errors['form'])): ?><div class="alert alert--error"><?= e($errors['form']) ?></div><?php endif; ?>
    <?php if ($errors && empty($errors['form'])): ?><div class="alert alert--error" role="alert">Please correct the highlighted fields.</div><?php endif; ?>
    <form class="form form--card" method="post" action="<?= url('business-request') ?>" novalidate>
      <?= csrf_field() ?>
      <fieldset>
        <legend>Your details</legend>
        <div class="form__row">
          <?= form_field('first_name', 'First name', $errors, 'text', true, 'given-name') ?>
          <?= form_field('last_name', 'Last name', $errors, 'text', false, 'family-name') ?>
        </div>
        <div class="form__row">
          <?= form_field('company', 'Company', $errors, 'text', false, 'organization') ?>
          <?= form_field('job_title', 'Job title', $errors, 'text', false, 'organization-title') ?>
        </div>
        <div class="form__row">
          <?= form_field('email', 'E-mail', $errors, 'email', true, 'email') ?>
          <?= form_field('mobile', 'Mobile', $errors, 'tel', false, 'tel') ?>
        </div>
        <div class="form__row">
          <?= form_field('phone', 'Phone', $errors, 'tel', false, 'tel') ?>
          <div class="field">
            <label for="f-country">Country</label>
            <select id="f-country" name="country" autocomplete="country-name">
              <option value="">— Select —</option>
              <?php foreach (countries() as $c): ?><option<?= $c === $selCountry ? ' selected' : '' ?>><?= e($c) ?></option><?php endforeach; ?>
            </select>
          </div>
        </div>
        <?= form_field('city', 'City', $errors, 'text', false, 'address-level2') ?>
      </fieldset>
      <fieldset>
        <legend>Your request</legend>
        <?= form_field('subject', 'Subject', $errors, 'text', false, '', $subjectDefault) ?>
        <?= form_field('message', 'Request', $errors, 'textarea', true) ?>
        <?= form_guard($captcha, $errors) ?>
      </fieldset>
      <button class="btn btn--primary" type="submit">Send request</button>
    </form>
  </div>
</section>
