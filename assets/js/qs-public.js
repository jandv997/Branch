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

  var arch = document.querySelector('[data-qs-arch]');
  if (arch) {
    var stages = arch.querySelectorAll('[data-qs-arch-stage]');
    var panels = arch.querySelectorAll('[data-qs-arch-panel]');
    function showArch(key) {
      Array.prototype.forEach.call(stages, function (stage) {
        stage.classList.toggle('is-active', stage.getAttribute('data-qs-arch-stage') === key);
      });
      Array.prototype.forEach.call(panels, function (panel) {
        panel.classList.toggle('is-active', panel.getAttribute('data-qs-arch-panel') === key);
      });
    }
    Array.prototype.forEach.call(stages, function (stage) {
      var activate = function () { showArch(stage.getAttribute('data-qs-arch-stage')); };
      stage.addEventListener('mouseenter', activate);
      stage.addEventListener('focus', activate);
      stage.addEventListener('click', activate);
    });
  }

  var pipe = document.querySelector('[data-qs-pipe]');
  if (pipe) {
    var steps = pipe.querySelectorAll('[data-qs-pipe-step]');
    var pipeIndex = 0;
    var pipeTimer;
    function setPipe(index) {
      pipeIndex = index;
      Array.prototype.forEach.call(steps, function (step, i) {
        step.classList.toggle('is-active', i === index);
      });
    }
    function startPipe() {
      pipeTimer = window.setInterval(function () {
        setPipe((pipeIndex + 1) % steps.length);
      }, 2600);
    }
    Array.prototype.forEach.call(steps, function (step, index) {
      var activate = function () {
        window.clearInterval(pipeTimer);
        setPipe(index);
        startPipe();
      };
      step.addEventListener('mouseenter', activate);
      step.addEventListener('click', activate);
    });
    startPipe();
  }

  var videoModal = document.querySelector('[data-qs-video-modal]');
  var videoEl = videoModal ? videoModal.querySelector('video') : null;
  function openVideo() {
    if (!videoModal) return;
    videoModal.hidden = false;
    document.body.style.overflow = 'hidden';
    if (videoEl) {
      videoEl.currentTime = 0;
      var play = videoEl.play();
      if (play && play.catch) play.catch(function () {});
    }
  }
  function closeVideo() {
    if (!videoModal) return;
    videoModal.hidden = true;
    document.body.style.overflow = '';
    if (videoEl) videoEl.pause();
  }
  Array.prototype.forEach.call(document.querySelectorAll('[data-qs-video]'), function (trigger) {
    trigger.addEventListener('click', function (event) {
      event.preventDefault();
      openVideo();
    });
  });
  Array.prototype.forEach.call(document.querySelectorAll('[data-qs-video-close]'), function (close) {
    close.addEventListener('click', closeVideo);
  });
  document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') closeVideo();
  });

  var more = document.querySelector('[data-qs-more]');
  if (more) {
    var moreBtn = more.querySelector('[data-qs-more-btn]');
    var hideTimer = 0;
    function isCompactNav() {
      return window.matchMedia('(max-width: 980px)').matches;
    }
    function openMore() {
      window.clearTimeout(hideTimer);
      more.classList.add('is-open');
      if (moreBtn) moreBtn.setAttribute('aria-expanded', 'true');
    }
    function closeMore() {
      more.classList.remove('is-open');
      if (moreBtn) moreBtn.setAttribute('aria-expanded', 'false');
    }
    function scheduleClose() {
      if (isCompactNav()) return;
      window.clearTimeout(hideTimer);
      hideTimer = window.setTimeout(closeMore, 400);
    }
    more.addEventListener('mouseenter', openMore);
    more.addEventListener('mouseleave', scheduleClose);
    more.addEventListener('focusin', openMore);
    more.addEventListener('focusout', function (event) {
      if (!more.contains(event.relatedTarget)) scheduleClose();
    });
    if (moreBtn) {
      moreBtn.addEventListener('click', function (event) {
        event.preventDefault();
        event.stopPropagation();
        if (more.classList.contains('is-open') && !isCompactNav()) {
          closeMore();
        } else {
          openMore();
        }
      });
    }
    document.addEventListener('click', function (event) {
      if (isCompactNav()) return;
      if (!more.contains(event.target)) closeMore();
    });
  }

  var verifyPage = document.querySelector('[data-qs-verify-page]');
  if (verifyPage) {
    var form = verifyPage.querySelector('[data-qs-verify-form]');
    var hashInput = verifyPage.querySelector('[data-qs-verify-hash]');
    var networkSelect = verifyPage.querySelector('[data-qs-verify-network]');
    if (form && hashInput && networkSelect) {
      form.addEventListener('submit', function (event) {
        event.preventDefault();
        var hash = (hashInput.value || '').trim();
        var base = networkSelect.value || '';
        if (!hash || !base) {
          hashInput.focus();
          return;
        }
        window.open(base + encodeURIComponent(hash), '_blank', 'noopener,noreferrer');
      });
    }

    function escapeHtml(value) {
      return String(value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
    }

    var ledgerBody = verifyPage.querySelector('[data-qs-ledger-body]');
    var copySvg = '<svg class="qs-verify__copy-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="9" y="9" width="11" height="11" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg><svg class="qs-verify__copied-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12.5l4 4 10-10"/></svg>';
    var clockSvg = '<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="8"/><path d="M12 8v5l3 2"/></svg>';
    var extSvg = '<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 5h5v5"/><path d="M19 5L10 14"/><path d="M5 10v9h9"/></svg>';

    function networkIcon(id) {
      if (id === 'bsc') {
        return '<svg width="16" height="16" viewBox="0 0 16 16"><circle cx="8" cy="8" r="8" fill="#F0B90B"/><path d="M8 3.2L5.4 5.8 8 8.4l2.6-2.6L8 3.2zm-3.6 3.6L1.8 8.4 4.4 11 7 8.4 4.4 6.8zm7.2 0L9 8.4 11.6 11l2.6-2.6-2.6-1.6zM8 9.6L5.4 12.2 8 14.8l2.6-2.6L8 9.6z" fill="#0a0b10"/></svg>';
      }
      if (id === 'arbitrum') {
        return '<svg width="16" height="16" viewBox="0 0 16 16"><circle cx="8" cy="8" r="8" fill="#12AAFF"/><path d="M8.2 3.4L4.2 12h2.1l.7-1.6h2.5L10.2 12h2.2L8.2 3.4zm0 3.1l.8 1.9H7.4l.8-1.9z" fill="#fff"/></svg>';
      }
      return '<svg width="16" height="16" viewBox="0 0 16 16"><circle cx="8" cy="8" r="8" fill="#627EEA"/><path d="M8 2.8l-.1 3.6 3.2 1.4L8 2.8zm0 0L4.9 7.8 8 6.4V2.8zM8 11.1v2.1l3.2-4.4L8 11.1zm0 2.1v-2.1L4.8 8.8 8 13.2zM8 10.4l3.2-1.8L8 7.1v3.3zm-3.2-1.8L8 10.4V7.1L4.8 8.6z" fill="#fff"/></svg>';
    }

    function bindCopy(root) {
      Array.prototype.forEach.call(root.querySelectorAll('[data-qs-copy]'), function (button) {
        button.addEventListener('click', function () {
          var value = button.getAttribute('data-qs-copy') || '';
          var done = function () {
            button.classList.add('is-copied');
            window.setTimeout(function () { button.classList.remove('is-copied'); }, 1600);
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
    }

    function renderLedger(payload) {
      if (!ledgerBody) return;
      var rows = payload && payload.settlements ? payload.settlements : [];
      if (!rows.length) {
        ledgerBody.innerHTML = '<tr><td colspan="5" class="qs-ledger__empty">No live DEX settlements are available right now.</td></tr>';
        return;
      }
      ledgerBody.innerHTML = rows.map(function (row) {
        var hash = escapeHtml(row.hash || '');
        var shortHash = escapeHtml(row.hash_short || row.hash || '');
        var explorer = escapeHtml(row.explorer || '#');
        var network = escapeHtml(row.network || '');
        var amount = escapeHtml(row.amount || '—');
        var time = escapeHtml(row.timestamp || '—');
        var id = row.network_id || 'eth';
        return (
          '<tr>' +
            '<td><span class="qs-net-pill">' + networkIcon(id) + ' ' + network + '</span></td>' +
            '<td><span class="qs-hash"><a href="' + explorer + '" target="_blank" rel="noopener noreferrer">' + shortHash + '</a>' +
              '<button type="button" class="qs-verify__copy" data-qs-copy="' + hash + '" aria-label="Copy transaction hash">' + copySvg + '</button></span></td>' +
            '<td><span class="qs-time">' + clockSvg + ' ' + time + '</span></td>' +
            '<td class="qs-ledger__amount">' + amount + '</td>' +
            '<td><a class="qs-ledger__verify" href="' + explorer + '" target="_blank" rel="noopener noreferrer" aria-label="View transaction">' + extSvg + '</a></td>' +
          '</tr>'
        );
      }).join('');
      bindCopy(ledgerBody);
    }

    function loadLedger() {
      fetch('api/market/dex-trades.php', { cache: 'no-store' })
        .then(function (res) { return res.ok ? res.json() : Promise.reject(); })
        .then(renderLedger)
        .catch(function () {
          if (ledgerBody) {
            ledgerBody.innerHTML = '<tr><td colspan="5" class="qs-ledger__empty">Unable to load live DEX settlements. Retrying…</td></tr>';
          }
        });
    }

    loadLedger();
    window.setInterval(loadLedger, 15000);
  }
})();
