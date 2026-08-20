(function () {
  var canvas = document.getElementById('qs-game-canvas');
  if (!canvas || !canvas.getContext) return;

  var overlay = document.getElementById('qs-game-overlay');
  var overlayKicker = document.getElementById('qs-game-overlay-kicker');
  var overlayTitle = document.getElementById('qs-game-overlay-title');
  var overlayCopy = document.getElementById('qs-game-overlay-copy');
  var playBtn = document.getElementById('qs-game-play');
  var scoreEl = document.getElementById('qs-game-score');
  var bestEl = document.getElementById('qs-game-best');
  var comboEl = document.getElementById('qs-game-combo');
  var livesEl = document.getElementById('qs-game-lives');

  var STORAGE_KEY = 'qsSpreadCatchBest';
  var WORLD_W = 420;
  var WORLD_H = 640;
  var ctx = canvas.getContext('2d');
  var dpr = Math.min(window.devicePixelRatio || 1, 2);
  var cssW = WORLD_W;
  var cssH = WORLD_H;

  var mode = 'idle';
  var raf = 0;
  var lastTs = 0;
  var score = 0;
  var combo = 1;
  var lives = 3;
  var best = 0;
  var spawnAcc = 0;
  var elapsed = 0;
  var invuln = 0;
  var shield = 0;
  var pointerX = null;
  var pointerActive = false;

  var ship = { x: WORLD_W / 2, y: WORLD_H - 72, vx: 0 };
  var keys = { left: false, right: false };
  var items = [];
  var particles = [];
  var stars = [];

  try {
    best = parseInt(localStorage.getItem(STORAGE_KEY) || '0', 10) || 0;
  } catch (err) {
    best = 0;
  }

  function rand(min, max) {
    return min + Math.random() * (max - min);
  }

  function makeStars() {
    stars = [];
    var i;
    for (i = 0; i < 48; i += 1) {
      stars.push({
        x: Math.random() * WORLD_W,
        y: Math.random() * WORLD_H,
        s: rand(0.4, 1.6),
        v: rand(12, 46),
        a: rand(0.18, 0.55)
      });
    }
  }

  function resize() {
    var wrap = canvas.parentElement;
    var maxW = wrap ? wrap.clientWidth : WORLD_W;
    cssW = Math.max(280, maxW);
    cssH = Math.round(cssW * (WORLD_H / WORLD_W));
    dpr = Math.min(window.devicePixelRatio || 1, 2);
    canvas.width = Math.round(cssW * dpr);
    canvas.height = Math.round(cssH * dpr);
    canvas.style.width = cssW + 'px';
    canvas.style.height = cssH + 'px';
    ctx.setTransform(dpr * cssW / WORLD_W, 0, 0, dpr * cssH / WORLD_H, 0, 0);
  }

  function setHud() {
    if (scoreEl) scoreEl.textContent = String(score);
    if (bestEl) bestEl.textContent = String(best);
    if (comboEl) comboEl.textContent = 'x' + combo;
    if (livesEl) {
      var dots = livesEl.querySelectorAll('span');
      var i;
      for (i = 0; i < dots.length; i += 1) {
        dots[i].classList.toggle('is-on', i < lives);
      }
    }
  }

  function burst(x, y, color, count) {
    var i;
    for (i = 0; i < count; i += 1) {
      var ang = Math.random() * Math.PI * 2;
      var sp = rand(40, 180);
      particles.push({
        x: x,
        y: y,
        vx: Math.cos(ang) * sp,
        vy: Math.sin(ang) * sp,
        life: rand(0.28, 0.7),
        max: 0.7,
        color: color,
        r: rand(1.2, 2.8)
      });
    }
  }

  function spawnItem() {
    var roll = Math.random();
    var type = 'spread';
    if (roll > 0.86) type = 'boost';
    else if (roll > 0.58) type = 'dump';
    var speed = 110 + elapsed * 7 + Math.random() * 50;
    items.push({
      type: type,
      x: rand(28, WORLD_W - 28),
      y: -18,
      vy: Math.min(340, speed),
      r: type === 'boost' ? 9 : type === 'dump' ? 8 : 7.5
    });
  }

  function resetRun() {
    score = 0;
    combo = 1;
    lives = 3;
    spawnAcc = 0;
    elapsed = 0;
    invuln = 0;
    shield = 0;
    items = [];
    particles = [];
    ship.x = WORLD_W / 2;
    ship.vx = 0;
    setHud();
  }

  function saveBest() {
    if (score > best) {
      best = score;
      try { localStorage.setItem(STORAGE_KEY, String(best)); } catch (err) {}
    }
    setHud();
  }

  function showOverlay(kicker, title, copy, action) {
    if (overlayKicker) overlayKicker.textContent = kicker;
    if (overlayTitle) overlayTitle.textContent = title;
    if (overlayCopy) overlayCopy.textContent = copy;
    if (playBtn) playBtn.textContent = action;
    if (overlay) overlay.hidden = false;
  }

  function hideOverlay() {
    if (overlay) overlay.hidden = true;
  }

  function hitDump() {
    if (invuln > 0) return;
    if (shield > 0) {
      shield = 0;
      invuln = 0.55;
      burst(ship.x, ship.y, '#f59e0b', 14);
      return;
    }
    lives -= 1;
    combo = 1;
    invuln = 1.1;
    burst(ship.x, ship.y, '#f43f5e', 18);
    setHud();
    if (lives <= 0) {
      mode = 'over';
      saveBest();
      showOverlay('Run complete', 'Game over', 'Score ' + score + '  ·  Best ' + best + '. Catch another round of spreads.', 'Play again');
    }
  }

  function update(dt) {
    var i;
    for (i = 0; i < stars.length; i += 1) {
      stars[i].y += stars[i].v * dt;
      if (stars[i].y > WORLD_H) {
        stars[i].y = 0;
        stars[i].x = Math.random() * WORLD_W;
      }
    }

    for (i = particles.length - 1; i >= 0; i -= 1) {
      var p = particles[i];
      p.life -= dt;
      p.x += p.vx * dt;
      p.y += p.vy * dt;
      p.vy += 40 * dt;
      if (p.life <= 0) particles.splice(i, 1);
    }

    if (mode !== 'play') return;

    elapsed += dt;
    if (invuln > 0) invuln -= dt;
    if (shield > 0) shield -= dt;

    var accel = 1800;
    if (keys.left) ship.vx -= accel * dt;
    if (keys.right) ship.vx += accel * dt;
    if (pointerActive && pointerX !== null) {
      var target = pointerX;
      ship.vx += (target - ship.x) * 14 * dt * 60 * 0.018;
      ship.x += (target - ship.x) * Math.min(1, dt * 14);
    }
    ship.vx *= Math.pow(0.0008, dt);
    ship.x += ship.vx * dt;
    if (ship.x < 22) { ship.x = 22; ship.vx = 0; }
    if (ship.x > WORLD_W - 22) { ship.x = WORLD_W - 22; ship.vx = 0; }

    spawnAcc += dt;
    var interval = Math.max(0.28, 0.92 - elapsed * 0.018);
    while (spawnAcc >= interval) {
      spawnAcc -= interval;
      spawnItem();
      if (elapsed > 18 && Math.random() < 0.22) spawnItem();
    }

    for (i = items.length - 1; i >= 0; i -= 1) {
      var it = items[i];
      it.y += it.vy * dt;
      var dx = it.x - ship.x;
      var dy = it.y - ship.y;
      var hitR = it.r + 14;
      if (dx * dx + dy * dy < hitR * hitR) {
        if (it.type === 'dump') {
          hitDump();
        } else if (it.type === 'boost') {
          shield = 3.2;
          score += 50 * combo;
          combo = Math.min(12, combo + 1);
          burst(it.x, it.y, '#f59e0b', 16);
        } else {
          score += 10 * combo;
          combo = Math.min(12, combo + 1);
          burst(it.x, it.y, '#2dd4bf', 12);
        }
        items.splice(i, 1);
        setHud();
        continue;
      }
      if (it.y > WORLD_H + 24) {
        if (it.type === 'spread') combo = 1;
        items.splice(i, 1);
        setHud();
      }
    }
  }

  function drawShip() {
    var blink = invuln > 0 && Math.floor(invuln * 12) % 2 === 0;
    if (blink) return;
    ctx.save();
    ctx.translate(ship.x, ship.y);
    if (shield > 0) {
      ctx.beginPath();
      ctx.arc(0, 0, 22, 0, Math.PI * 2);
      ctx.strokeStyle = 'rgba(245, 158, 11, 0.85)';
      ctx.lineWidth = 2;
      ctx.stroke();
      ctx.beginPath();
      ctx.arc(0, 0, 22, 0, Math.PI * 2);
      ctx.fillStyle = 'rgba(245, 158, 11, 0.08)';
      ctx.fill();
    }
    ctx.beginPath();
    ctx.moveTo(0, -16);
    ctx.lineTo(13, 12);
    ctx.lineTo(0, 6);
    ctx.lineTo(-13, 12);
    ctx.closePath();
    ctx.fillStyle = '#2dd4bf';
    ctx.shadowColor = '#2dd4bf';
    ctx.shadowBlur = 16;
    ctx.fill();
    ctx.shadowBlur = 0;
    ctx.fillStyle = '#04110f';
    ctx.beginPath();
    ctx.moveTo(0, -8);
    ctx.lineTo(5, 4);
    ctx.lineTo(-5, 4);
    ctx.closePath();
    ctx.fill();
    ctx.restore();
  }

  function drawItem(it) {
    ctx.save();
    ctx.translate(it.x, it.y);
    if (it.type === 'dump') {
      ctx.rotate(Math.PI / 4);
      ctx.fillStyle = '#f43f5e';
      ctx.shadowColor = '#f43f5e';
      ctx.shadowBlur = 12;
      ctx.fillRect(-it.r, -it.r, it.r * 2, it.r * 2);
    } else {
      ctx.beginPath();
      ctx.arc(0, 0, it.r, 0, Math.PI * 2);
      ctx.fillStyle = it.type === 'boost' ? '#f59e0b' : '#2dd4bf';
      ctx.shadowColor = ctx.fillStyle;
      ctx.shadowBlur = 14;
      ctx.fill();
    }
    ctx.restore();
  }

  function draw() {
    ctx.clearRect(0, 0, WORLD_W, WORLD_H);
    ctx.fillStyle = '#030305';
    ctx.fillRect(0, 0, WORLD_W, WORLD_H);

    var g = ctx.createRadialGradient(WORLD_W / 2, 80, 20, WORLD_W / 2, 120, 280);
    g.addColorStop(0, 'rgba(45, 212, 191, 0.12)');
    g.addColorStop(1, 'rgba(3, 3, 5, 0)');
    ctx.fillStyle = g;
    ctx.fillRect(0, 0, WORLD_W, WORLD_H);

    var i;
    for (i = 0; i < stars.length; i += 1) {
      ctx.fillStyle = 'rgba(255,255,255,' + stars[i].a + ')';
      ctx.fillRect(stars[i].x, stars[i].y, stars[i].s, stars[i].s);
    }

    for (i = 0; i < items.length; i += 1) drawItem(items[i]);

    for (i = 0; i < particles.length; i += 1) {
      var p = particles[i];
      ctx.globalAlpha = Math.max(0, p.life / p.max);
      ctx.fillStyle = p.color;
      ctx.beginPath();
      ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
      ctx.fill();
    }
    ctx.globalAlpha = 1;

    drawShip();
  }

  function loop(ts) {
    if (!lastTs) lastTs = ts;
    var dt = Math.min(0.033, (ts - lastTs) / 1000);
    lastTs = ts;
    update(dt);
    draw();
    raf = window.requestAnimationFrame(loop);
  }

  function canvasX(event) {
    var rect = canvas.getBoundingClientRect();
    var clientX = event.clientX;
    if (event.touches && event.touches[0]) clientX = event.touches[0].clientX;
    return ((clientX - rect.left) / rect.width) * WORLD_W;
  }

  function startGame() {
    resetRun();
    mode = 'play';
    hideOverlay();
    canvas.focus();
  }

  function togglePause() {
    if (mode === 'play') {
      mode = 'pause';
      showOverlay('Paused', 'Hold', 'Spreads are frozen. Resume when you are ready.', 'Resume');
    } else if (mode === 'pause') {
      mode = 'play';
      hideOverlay();
      lastTs = 0;
    }
  }

  function onKey(event, down) {
    var code = event.code || '';
    var key = event.key || '';
    if (code === 'ArrowLeft' || key === 'a' || key === 'A') keys.left = down;
    if (code === 'ArrowRight' || key === 'd' || key === 'D') keys.right = down;
    if (down && (code === 'ArrowLeft' || code === 'ArrowRight' || key === ' ' || key === 'ArrowLeft' || key === 'ArrowRight')) {
      event.preventDefault();
    }
    if (down && (code === 'KeyP' || code === 'Escape')) {
      event.preventDefault();
      togglePause();
    }
    if (down && (code === 'Enter' || code === 'Space') && mode !== 'play') {
      startGame();
    }
  }

  if (playBtn) {
    playBtn.addEventListener('click', function () {
      if (mode === 'pause') {
        mode = 'play';
        hideOverlay();
        lastTs = 0;
        return;
      }
      startGame();
    });
  }

  window.addEventListener('keydown', function (event) { onKey(event, true); });
  window.addEventListener('keyup', function (event) { onKey(event, false); });

  canvas.addEventListener('pointerdown', function (event) {
    pointerActive = true;
    pointerX = canvasX(event);
    if (canvas.setPointerCapture) canvas.setPointerCapture(event.pointerId);
  });
  canvas.addEventListener('pointermove', function (event) {
    pointerX = canvasX(event);
    if (event.pointerType === 'mouse') pointerActive = true;
  });
  canvas.addEventListener('pointerup', function () {
    pointerActive = false;
  });
  canvas.addEventListener('pointerleave', function () {
    pointerActive = false;
  });

  window.addEventListener('resize', resize);
  canvas.setAttribute('tabindex', '0');

  makeStars();
  resize();
  setHud();
  raf = window.requestAnimationFrame(loop);
})();
