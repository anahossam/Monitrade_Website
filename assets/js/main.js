/* Monitrade — small progressive enhancements. The site works without JS. */
(function () {
  'use strict';

  // Mobile navigation
  var toggle = document.querySelector('.nav-toggle');
  var nav = document.getElementById('site-nav');
  if (toggle && nav) {
    var positionNav = function () {
      var header = document.querySelector('.site-header');
      if (header) nav.style.top = header.getBoundingClientRect().bottom + 'px';
    };
    toggle.addEventListener('click', function () {
      var open = toggle.getAttribute('aria-expanded') === 'true';
      toggle.setAttribute('aria-expanded', String(!open));
      positionNav();
      nav.classList.toggle('is-open', !open);
      document.body.style.overflow = open ? '' : 'hidden';
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && nav.classList.contains('is-open')) {
        toggle.click();
        toggle.focus();
      }
    });
    window.addEventListener('resize', function () {
      if (window.innerWidth > 960 && nav.classList.contains('is-open')) toggle.click();
    });
  }

  // Products sub-menu on mobile
  document.querySelectorAll('.sub-toggle').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var li = btn.closest('.has-sub');
      var open = li.classList.toggle('is-open');
      btn.setAttribute('aria-expanded', String(open));
    });
  });

  // Copy-link buttons
  document.querySelectorAll('[data-copy]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var input = document.querySelector(btn.getAttribute('data-copy'));
      if (!input) return;
      var label = btn.querySelector('span');
      var done = function () { if (label) { label.textContent = 'Copied!'; setTimeout(function () { label.textContent = 'Copy link'; }, 2000); } };
      if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(input.value).then(done, function () { input.select(); });
      } else {
        input.select();
        try { document.execCommand('copy'); done(); } catch (e) { /* user can copy manually */ }
      }
    });
  });

  // Focus the first invalid field after a failed submit
  var firstError = document.querySelector('.field--error input, .field--error textarea');
  if (firstError) firstError.focus();
})();
