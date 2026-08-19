<?php
$pageTitle = 'License | Quantum Scalp AI';
$pageDescription = 'Choose your Q-Core license. License fees provide access to software and services.';
$currentPage = 'pricing';
include 'inc/public-start.php';
include 'header.php';
$dashboardUrl = 'account/dashboard';
?>
<main>
    <section class="qs-hero qs-section--tight">
        <div class="qs-wrap" style="text-align:center;">
            <p class="qs-eyebrow">Membership / License</p>
            <h1 class="qs-h1">Choose your Q-Core license.</h1>
            <p class="qs-lead" style="margin-left:auto;margin-right:auto;">License fees provide access to software and services. Plans are modular and can be updated by the company at any time.</p>
        </div>
    </section>
    <section class="qs-section">
        <div class="qs-wrap qs-price">
            <a class="qs-card qs-card--accent" href="<?php echo htmlspecialchars($dashboardUrl); ?>">
                <div class="qs-popular">★ MOST POPULAR</div>
                <h3 class="qs-h3">Q-Core License</h3>
                <p class="qs-muted">Core access to the Q-Core engine.</p>
                <div class="qs-price__amt">$50 <small>/ 3 Months</small></div>
                <ul class="qs-list">
                    <li>Q-Core software access</li>
                    <li>Trading technology access</li>
                    <li>Member dashboard</li>
                    <li>Strategy infrastructure</li>
                    <li>Technical support</li>
                    <li>Platform updates</li>
                </ul>
                <span class="qs-btn qs-btn--primary qs-btn--block">Become a Member</span>
            </a>
            <a class="qs-card" href="<?php echo htmlspecialchars($dashboardUrl); ?>">
                <h3 class="qs-h3">Q-Core Pro</h3>
                <p class="qs-muted">Extended access with priority support.</p>
                <div class="qs-price__amt">$150 <small>/ 12 Months</small></div>
                <ul class="qs-list">
                    <li>Everything in Q-Core License</li>
                    <li>Priority technical support</li>
                    <li>Extended data intelligence</li>
                    <li>Configurable risk parameters</li>
                    <li>Early feature access</li>
                </ul>
                <span class="qs-btn qs-btn--ghost qs-btn--block">Become a Member</span>
            </a>
            <a class="qs-card" href="<?php echo htmlspecialchars($dashboardUrl); ?>">
                <h3 class="qs-h3">Q-Core Enterprise</h3>
                <p class="qs-muted">For teams and high-volume license holders.</p>
                <div class="qs-price__amt">Custom</div>
                <ul class="qs-list">
                    <li>Everything in Q-Core Pro</li>
                    <li>Dedicated onboarding</li>
                    <li>Custom strategy configuration</li>
                    <li>API / data access where available</li>
                    <li>Executive support access</li>
                </ul>
                <span class="qs-btn qs-btn--ghost qs-btn--block">Contact Sales</span>
            </a>
        </div>
        <p class="qs-wrap qs-muted" style="margin-top:28px;text-align:center;">Trading activity involves risk. License fees provide access to software and services and do not represent an investment or guaranteed return.</p>
    </section>
</main>
<?php include 'footer.php'; include 'inc/public-end.php'; ?>
