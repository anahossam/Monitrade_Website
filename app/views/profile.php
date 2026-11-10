<?php
/** @var array $team */
$who = preg_split("~\R{2,}~", trim(setting('profile_who')));
$why = array_filter(array_map('trim', preg_split("~\R~", setting('profile_why'))));
partial('partials/page-head', ['heading' => 'Company Profile', 'crumbs' => ['Company Profile' => null],
    'lead' => setting('tagline') . ' — since ' . setting('founded_year', '1989') . '.']);
?>
<section class="section">
  <div class="container profile">
    <div class="profile__main">
      <h2 class="section-title">Who is Monitrade?</h2>
      <?php foreach ($who as $i => $para): ?><p<?= $i === 0 ? ' class="lead"' : '' ?>><?= e($para) ?></p><?php endforeach; ?>

      <h2 class="section-title">Why Monitrade?</h2>
      <ol class="why">
        <?php foreach ($why as $w): ?><li><span class="why__tick"><?= icon('check') ?></span><?= e($w) ?></li><?php endforeach; ?>
      </ol>
    </div>
    <aside class="profile__side">
      <figure class="profile__photo">
        <img src="<?= asset('assets/img/yarn-cones.jpg') ?>" alt="Yarn cones" width="180" height="276">
      </figure>
      <div class="fact-card">
        <p class="fact-card__big"><?= e(setting('founded_year', '1989')) ?></p>
        <p>Serving the Egyptian textile industry since then</p>
        <?php if (setting('chairman')): ?><p>Chairman: <strong><?= e(setting('chairman')) ?></strong></p><?php endif; ?>
      </div>
    </aside>
  </div>
</section>

<?php if ($team): ?>
<section class="section section--tint">
  <div class="container">
    <p class="eyebrow">Our people</p>
    <h2 class="section-title">Management &amp; technical team</h2>
    <div class="team">
      <?php foreach ($team as $t): ?>
        <div class="person">
          <span class="person__avatar" aria-hidden="true"><?= icon('user') ?></span>
          <div>
            <h3 class="person__name"><?= e($t['name']) ?></h3>
            <?php if ($t['title']): ?><p class="person__title"><?= e($t['title']) ?></p><?php endif; ?>
            <?php if ($t['phone']): ?><p class="person__contact"><?= icon('mobile', 'icon icon--sm') ?> <a href="<?= e(tel_href($t['phone'])) ?>"><?= e($t['phone']) ?></a></p><?php endif; ?>
            <?php if ($t['email']): ?><p class="person__contact"><?= icon('mail', 'icon icon--sm') ?> <a href="mailto:<?= e($t['email']) ?>"><?= e($t['email']) ?></a></p><?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php partial('partials/cta'); ?>
