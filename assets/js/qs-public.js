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

  var copyButtons = document.querySelectorAll('[data-qs-copy]');
  Array.prototype.forEach.call(copyButtons, function (button) {
    button.addEventListener('click', function () {
      var value = button.getAttribute('data-qs-copy') || '';
      var done = function () {
        button.classList.add('is-copied');
        window.setTimeout(function () {
          button.classList.remove('is-copied');
        }, 1600);
      };
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(value).then(done).catch(function () {
          fallbackCopy(value);
          done();
        });
      } else {
        fallbackCopy(value);
        done();
      }
    });
  });

  function fallbackCopy(value) {
    var input = document.createElement('textarea');
    input.value = value;
    input.setAttribute('readonly', '');
    input.style.position = 'absolute';
    input.style.left = '-9999px';
    document.body.appendChild(input);
    input.select();
    try { document.execCommand('copy'); } catch (err) {}
    document.body.removeChild(input);
  }

  var terminal = document.querySelector('[data-qs-spreads]');
  if (terminal) {
    var body = terminal.querySelector('[data-qs-spreads-body]');
    var meta = terminal.querySelector('[data-qs-spreads-meta]');
    var lastUpdated = 0;

    function formatPrice(value) {
      var n = Number(value);
      if (!isFinite(n)) return '—';
      if (n >= 1000) return n.toLocaleString(undefined, { maximumFractionDigits: 2 });
      if (n >= 1) return n.toFixed(3);
      return n.toFixed(5);
    }

    function formatAge(unix) {
      if (!unix) return '';
      var seconds = Math.max(0, Math.floor(Date.now() / 1000 - unix));
      return seconds < 60 ? seconds + 's ago' : Math.floor(seconds / 60) + 'm ago';
    }

    function escapeHtml(value) {
      return String(value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
    }

    function render(payload) {
      var pairs = payload && payload.pairs ? payload.pairs : [];
      lastUpdated = payload && payload.updated_at ? payload.updated_at : lastUpdated;
      var venues = payload && payload.venues_online ? payload.venues_online : [];
      var live = payload && payload.source === 'live';
      var bits = [];
      if (live && venues.length) bits.push(venues.join(' · '));
      if (lastUpdated) bits.push(formatAge(lastUpdated));
      if (meta) meta.textContent = bits.length ? '· ' + bits.join(' · ') : '';

      if (!pairs.length) {
        body.innerHTML = '<div class="qs-terminal__empty">Connecting to live market data…</div>';
        return;
      }

      body.innerHTML = pairs.map(function (row) {
        var change = Number(row.change_24h) || 0;
        var changeClass = change >= 0 ? 'qs-up' : 'qs-down';
        var changeText = (change >= 0 ? '+' : '') + change + '%';
        var statusClass = row.status === 'EXECUTABLE' ? 'qs-status--ok' : 'qs-status--warn';
        return (
          '<div class="qs-terminal__row">' +
            '<span class="qs-pair">' + escapeHtml(row.pair) + '</span>' +
            '<span>' + escapeHtml(row.buy_venue) + ' <span class="qs-px">@ ' + formatPrice(row.buy) + '</span> → ' +
              escapeHtml(row.sell_venue) + ' <span class="qs-px">@ ' + formatPrice(row.sell) + '</span></span>' +
            '<span class="' + changeClass + '">' + changeText + '</span>' +
            '<span class="qs-spread">' + row.spread_pct + '%</span>' +
            '<span class="qs-status ' + statusClass + '"><span>' + escapeHtml(row.status || 'MONITORING') + '</span></span>' +
          '</div>'
        );
      }).join('');
    }

    function loadSpreads() {
      fetch('api/market/spreads.php', { cache: 'no-store' })
        .then(function (res) { return res.ok ? res.json() : Promise.reject(); })
        .then(render)
        .catch(function () {
          if (body && !body.querySelector('.qs-terminal__row')) {
            body.innerHTML = '<div class="qs-terminal__empty">Connecting to live market data…</div>';
          }
        });
    }

    loadSpreads();
    window.setInterval(loadSpreads, 10000);
    window.setInterval(function () {
      if (!meta || !lastUpdated) return;
      var current = meta.textContent || '';
      meta.textContent = current.replace(/\d+[sm] ago/, formatAge(lastUpdated));
    }, 1000);
  }

  document.addEventListener('mousemove', function (event) {
    var card = event.target.closest('[data-qs-strategy]');
    if (!card) return;
    var box = card.getBoundingClientRect();
    card.style.setProperty('--x', (event.clientX - box.left) + 'px');
    card.style.setProperty('--y', (event.clientY - box.top) + 'px');
  });
})();
