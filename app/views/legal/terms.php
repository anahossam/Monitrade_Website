<?php partial('partials/page-head', ['heading' => 'Terms and Conditions', 'crumbs' => ['Terms and Conditions' => null]]); ?>
<section class="section">
  <div class="container narrow prose">
    <p class="lead">These terms apply to your use of the Monitrade website. By using the site you accept them.</p>
    <h2>About this website</h2>
    <p>This website is operated by Monitrade, a textile agency based at <?= e(setting('cairo_address')) ?>. It provides information about the companies we represent and the products and services we offer.</p>
    <h2>Product information</h2>
    <p>Product descriptions, specifications and images are provided for general information and are supplied by, or based on information from, the respective manufacturers. They may change without notice and do not constitute a binding offer. Prices, availability, delivery times and technical details are confirmed only in a written quotation from Monitrade.</p>
    <h2>Trademarks</h2>
    <p>Brand and product names shown on this site are trademarks of their respective owners and are used only to identify the products we supply.</p>
    <h2>Use of content</h2>
    <p>You may view and print pages for your own business use. You may not copy, republish or redistribute the content of this website for commercial purposes without our written permission.</p>
    <h2>Liability</h2>
    <p>We take care to keep the information on this website accurate and up to date, but we cannot guarantee that it is complete or error-free. Monitrade is not liable for any loss arising from reliance on information published on this site. Links to other websites are provided for convenience; we are not responsible for their content.</p>
    <h2>Governing law</h2>
    <p>These terms are governed by the laws of the Arab Republic of Egypt.</p>
    <h2>Contact</h2>
    <p>Questions about these terms can be sent to <a href="mailto:<?= e(setting('company_email')) ?>"><?= e(setting('company_email')) ?></a>.</p>
  </div>
</section>
