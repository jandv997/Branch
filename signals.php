<?php
$pageTitle = 'Quantum Signals | Quantum Scalp AI';
$pageDescription = 'A standalone subscription to Q-Core live trading signals. Licensed information product. Not financial advice.';
$currentPage = 'signals';
include 'inc/public-start.php';
include 'header.php';
$registerUrl = 'account/register';
?>
<main>
    <section class="qs-hero qs-section--tight">
        <div class="qs-wrap">
            <p class="qs-eyebrow">Quantum Signals · Information Product</p>
            <h1 class="qs-h1">Real-time signal alerts, straight from the engine.</h1>
            <p class="qs-lead qs-lead--wide">A standalone subscription to Q-Core's live trading signals — asset, direction, and entry/exit levels delivered continuously. No deposit. No investment. Just the intelligence.</p>
            <div class="qs-btn-row">
                <a class="qs-btn qs-btn--primary" href="<?php echo htmlspecialchars($registerUrl); ?>">Subscribe — $99/mo ↗</a>
            </div>
        </div>
    </section>

    <section class="qs-section">
        <div class="qs-wrap qs-grid-4">
            <article class="qs-card qs-beam">
                <span class="qs-cap-icon">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 20V10"/><path d="M8 20V14"/><path d="M16 20V6"/><path d="M4 20v-4"/><path d="M20 20V8"/></svg>
                </span>
                <h3 class="qs-h3">Real-time alerts</h3>
                <p class="qs-muted">Asset, direction, entry/exit levels and live status — the moment Q-Core surfaces an opportunity.</p>
            </article>
            <article class="qs-card qs-beam">
                <span class="qs-cap-icon">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M3 3v16a2 2 0 0 0 2 2h16"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                </span>
                <h3 class="qs-h3">Derived from real markets</h3>
                <p class="qs-muted">Signals are computed from genuine cross-exchange and derivatives data, not hand-waving.</p>
            </article>
            <article class="qs-card qs-beam">
                <span class="qs-cap-icon">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="12" cy="12" r="8"/><path d="M12 8v4l2.5 1.5"/></svg>
                </span>
                <h3 class="qs-h3">Continuous feed</h3>
                <p class="qs-muted">A live, auto-updating stream inside your dashboard — no refreshing, no waiting.</p>
            </article>
            <article class="qs-card qs-beam">
                <span class="qs-cap-icon">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 3l8 4v6c0 5-3.5 8.5-8 9.5C7.5 21.5 4 18 4 13V7l8-4z"/></svg>
                </span>
                <h3 class="qs-h3">Standalone product</h3>
                <p class="qs-muted">A paid information product. No deposit, no investment, no custody of your funds.</p>
            </article>
        </div>
    </section>

    <section class="qs-section">
        <div class="qs-wrap">
            <div class="qs-signals-offer">
                <div>
                    <p class="qs-eyebrow">What you get</p>
                    <h2 class="qs-h2">Everything in the live feed.</h2>
                    <ul class="qs-check-list">
                        <li>Live signal cards with entry, target and stop</li>
                        <li>Direction (long/short) and strategy tag</li>
                        <li>Confidence indicator and source venue</li>
                        <li>Cross-exchange and perpetual-basis coverage</li>
                        <li>Cancel anytime — it's a subscription, not a commitment</li>
                    </ul>
                </div>
                <aside class="qs-signals-price">
                    <span class="qs-signals-bell" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="30" height="30" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M10.3 21a2 2 0 0 0 3.4 0"/><path d="M6 8a6 6 0 1 1 12 0c0 4.5 1.4 6 2.7 7.3A1 1 0 0 1 20 17H4a1 1 0 0 1-.7-1.7C4.6 14 6 12.5 6 8z"/></svg>
                    </span>
                    <p class="qs-signals-amt">$99<span>/month</span></p>
                    <a class="qs-btn qs-btn--primary qs-btn--block" href="<?php echo htmlspecialchars($registerUrl); ?>">Create account &amp; subscribe ↗</a>
                    <p class="qs-signals-note">Informational technology output. Not financial advice. Markets carry risk; results are not guaranteed.</p>
                </aside>
            </div>
        </div>
    </section>
</main>
<?php include 'footer.php'; include 'inc/public-end.php'; ?>
