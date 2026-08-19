<?php
$qsInAccount = (strpos(str_replace('\\', '/', isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : ''), '/account/') !== false);
$qsPrivacyHref = $qsInAccount ? '../privacy' : 'privacy';
?>
<div class="qs-cookie" data-qs-cookie hidden>
    <div class="qs-cookie__inner">
        <p>We use cookies to operate the site, remember your preferences, and improve your experience. See our <a href="<?php echo htmlspecialchars($qsPrivacyHref); ?>">Privacy Policy</a>.</p>
        <div class="qs-cookie__actions">
            <button type="button" class="qs-cookie__btn qs-cookie__btn--ghost" data-qs-cookie-decline>Decline</button>
            <button type="button" class="qs-cookie__btn qs-cookie__btn--primary" data-qs-cookie-accept>Accept cookies</button>
        </div>
    </div>
</div>
<script>
(function () {
  var banner = document.querySelector('[data-qs-cookie]');
  if (!banner) return;
  var key = 'qs_cookie_consent';

  function readCookie(name) {
    var parts = ('; ' + document.cookie).split('; ' + name + '=');
    if (parts.length < 2) return '';
    return decodeURIComponent(parts.pop().split(';').shift());
  }
  function writeCookie(name, value) {
    var expires = new Date(Date.now() + 400 * 24 * 60 * 60 * 1000).toUTCString();
    document.cookie = name + '=' + encodeURIComponent(value) + '; expires=' + expires + '; path=/; SameSite=Lax';
  }
  function hasChoice() {
    try {
      if (localStorage.getItem(key)) return true;
    } catch (e) {}
    return readCookie(key) === 'accepted' || readCookie(key) === 'declined';
  }
  function remember(value) {
    try { localStorage.setItem(key, value); } catch (e) {}
    writeCookie(key, value);
    banner.hidden = true;
  }

  if (hasChoice()) return;
  banner.hidden = false;

  var accept = banner.querySelector('[data-qs-cookie-accept]');
  var decline = banner.querySelector('[data-qs-cookie-decline]');
  if (accept) accept.addEventListener('click', function () { remember('accepted'); });
  if (decline) decline.addEventListener('click', function () { remember('declined'); });
})();
</script>
