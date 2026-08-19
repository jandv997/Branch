<?php
$pageTitle = 'VIP Membership | Quantum Scalp AI';
$pageDescription = 'VIP unlocks live seminars, training recordings, strategy write-ups, and Quantum Signals.';
$currentPage = 'vip';
include 'inc/public-start.php';
include 'header.php';
$registerUrl = 'account/register';
?>
<main>
    <section class="qs-hero qs-section--tight">
        <div class="qs-wrap">
            <p class="qs-eyebrow">VIP Membership</p>
            <h1 class="qs-h1">Go deeper. Learn live. Get everything.</h1>
            <p class="qs-lead qs-lead--wide">VIP unlocks live seminars, the full training-recording library, in-depth strategy write-ups — and bundles Quantum Signals access. Education and intelligence for serious members.</p>
            <div class="qs-btn-row">
                <a class="qs-btn qs-btn--primary" href="<?php echo htmlspecialchars($registerUrl); ?>">Become VIP — $499/mo ↗</a>
            </div>
        </div>
    </section>

    <section class="qs-section">
        <div class="qs-wrap qs-grid-4">
            <article class="qs-card qs-beam">
                <span class="qs-cap-icon qs-cap-icon--gold">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M8 3v4M16 3v4M3 10h18"/></svg>
                </span>
                <h3 class="qs-h3">Live Seminars</h3>
                <p class="qs-muted">A schedule of live, interactive sessions with Q-Core research.</p>
            </article>
            <article class="qs-card qs-beam">
                <span class="qs-cap-icon qs-cap-icon--gold">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6"><polygon points="6 3 20 12 6 21 6 3"/></svg>
                </span>
                <h3 class="qs-h3">Recording Library</h3>
                <p class="qs-muted">Full back-catalogue of past trainings, on demand.</p>
            </article>
            <article class="qs-card qs-beam">
                <span class="qs-cap-icon qs-cap-icon--gold">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M6 4h9l3 3v13H6z"/><path d="M9 12h6M9 16h6"/></svg>
                </span>
                <h3 class="qs-h3">Strategy Write-ups</h3>
                <p class="qs-muted">In-depth educational material on strategy and risk.</p>
            </article>
            <article class="qs-card qs-beam">
                <span class="qs-cap-icon qs-cap-icon--gold">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 20V10"/><path d="M8 20V14"/><path d="M16 20V6"/><path d="M4 20v-4"/><path d="M20 20V8"/></svg>
                </span>
                <h3 class="qs-h3">Signals Included</h3>
                <p class="qs-muted">VIP bundles full Quantum Signals access at no extra cost.</p>
            </article>
        </div>
    </section>

    <section class="qs-section">
        <div class="qs-wrap">
            <div class="qs-vip-offer">
                <div>
                    <p class="qs-eyebrow">Membership includes</p>
                    <h2 class="qs-h2">The complete VIP experience.</h2>
                    <ul class="qs-check-list">
                        <li>Live seminar schedule with reminders</li>
                        <li>On-demand training recording library</li>
                        <li>Strategy &amp; education write-ups</li>
                        <li>Full Quantum Signals feed included</li>
                        <li>Priority recognition across the platform</li>
                    </ul>
                </div>
                <aside class="qs-signals-price">
                    <span class="qs-vip-crown" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="30" height="30" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M3 18h18l-2-10-4 4-3-7-3 7-4-4-2 10z"/><path d="M5 18h14v2H5z"/></svg>
                    </span>
                    <p class="qs-signals-amt">$499<span>/month</span></p>
                    <a class="qs-btn qs-btn--gold qs-btn--block" href="<?php echo htmlspecialchars($registerUrl); ?>">Create account &amp; join ↗</a>
                    <p class="qs-signals-note">Educational membership. Not financial advice. Markets carry risk; results are not guaranteed.</p>
                </aside>
            </div>
        </div>
    </section>
</main>
<?php include 'footer.php'; include 'inc/public-end.php'; ?>
