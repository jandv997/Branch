<?php
$pageTitle = 'Spread Catch | Quantum Scalp AI';
$pageDescription = 'A tiny arcade game: catch teal spreads, dodge red dumps. Entertainment only — not trading.';
$currentPage = 'game';
$pageStyles = array('assets/css/qs-game.css');
$pageScripts = array('assets/js/qs-game.js');
include 'inc/public-start.php';
include 'header.php';
?>
<main>
    <section class="qs-hero qs-section--tight">
        <div class="qs-wrap">
            <p class="qs-eyebrow">Arcade</p>
            <h1 class="qs-h1">Spread Catch.</h1>
            <p class="qs-lead qs-lead--wide">Steer the probe. Catch teal ticks, skim amber boosts, and dodge red dumps. A tiny game — not a simulator, and not live trading.</p>
        </div>
    </section>

    <section class="qs-section qs-section--tight qs-game-section">
        <div class="qs-wrap qs-game-layout">
            <div class="qs-game-shell" data-qs-game>
                <div class="qs-game-hud">
                    <div>
                        <span class="qs-game-hud__label">Score</span>
                        <strong id="qs-game-score">0</strong>
                    </div>
                    <div>
                        <span class="qs-game-hud__label">Best</span>
                        <strong id="qs-game-best">0</strong>
                    </div>
                    <div>
                        <span class="qs-game-hud__label">Combo</span>
                        <strong id="qs-game-combo">x1</strong>
                    </div>
                    <div class="qs-game-lives" id="qs-game-lives" aria-label="Lives">
                        <span></span><span></span><span></span>
                    </div>
                </div>
                <div class="qs-game-stage">
                    <canvas id="qs-game-canvas" width="420" height="640" aria-label="Spread Catch playfield"></canvas>
                    <div class="qs-game-overlay" id="qs-game-overlay">
                        <p class="qs-game-overlay__kicker" id="qs-game-overlay-kicker">Q-Core Arcade</p>
                        <h2 id="qs-game-overlay-title">Spread Catch</h2>
                        <p id="qs-game-overlay-copy">Catch the teal spreads. Dodge the red dumps. Last as long as you can.</p>
                        <button class="qs-btn qs-btn--primary" type="button" id="qs-game-play">Play</button>
                    </div>
                </div>
            </div>
            <aside class="qs-game-help">
                <article class="qs-card">
                    <h2 class="qs-h3">How to play</h2>
                    <ul class="qs-game-keys">
                        <li><kbd>←</kbd> <kbd>→</kbd> or <kbd>A</kbd> <kbd>D</kbd> to move</li>
                        <li>Drag or follow the pointer on the playfield</li>
                        <li><kbd>P</kbd> or <kbd>Esc</kbd> to pause</li>
                    </ul>
                    <ul class="qs-game-legend">
                        <li><i class="qs-game-dot qs-game-dot--spread"></i> Teal spread — points + combo</li>
                        <li><i class="qs-game-dot qs-game-dot--boost"></i> Amber boost — shield + score</li>
                        <li><i class="qs-game-dot qs-game-dot--dump"></i> Red dump — lose a life</li>
                    </ul>
                </article>
                <p class="qs-muted qs-game-note">Entertainment only. Nothing here is a trading signal, backtest, or financial advice.</p>
            </aside>
        </div>
    </section>
</main>
<?php include 'footer.php'; include 'inc/public-end.php'; ?>
