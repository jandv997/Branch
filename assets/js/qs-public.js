(function () {
  var banner = document.querySelector('[data-qs-banner]');
  var close = document.querySelector('[data-qs-banner-close]');
  if (banner && close) {
    if (sessionStorage.getItem('qsBannerClosed') === '1') {
      banner.remove();
    }
    close.addEventListener('click', function () {
      sessionStorage.setItem('qsBannerClosed', '1');
      banner.remove();
    });
  }

  var toggle = document.querySelector('[data-qs-menu]');
  var nav = document.querySelector('[data-qs-nav]');
  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      nav.classList.toggle('is-open');
    });
  }
})();
