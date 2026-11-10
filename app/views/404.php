<?php partial('partials/page-head', ['heading' => 'Page not found', 'crumbs' => ['404' => null]]); ?>
<section class="section">
  <div class="container narrow center">
    <p class="lead">Sorry, the page you were looking for does not exist or has moved.</p>
    <form class="searchbar" action="<?= url('search') ?>" method="get" role="search">
      <label class="sr-only" for="q-404">Search the site</label>
      <input id="q-404" type="search" name="q" placeholder="Search products, news and articles">
      <button class="btn btn--primary" type="submit">Search</button>
    </form>
    <p><a href="<?= url('/') ?>">Home</a> · <a href="<?= url('products') ?>">Products</a> · <a href="<?= url('contact') ?>">Contact us</a></p>
  </div>
</section>
