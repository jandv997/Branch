<?php
$pageTitle = 'Security | Quantum Scalp AI';
$pageDescription = 'Security engineered into the infrastructure.';
$currentPage = 'security';
include 'inc/public-start.php';
include 'header.php';
?>
<main>
    <section class="qs-hero qs-section--tight">
        <div class="qs-wrap">
            <p class="qs-eyebrow">Security</p>
            <h1 class="qs-h1">Security engineered into the infrastructure.</h1>
            <p class="qs-lead">Security is foundational to how Q-Core is built and operated. Below is an overview of our security posture. We only display audit badges once they are independently verified.</p>
        </div>
    </section>
    <section class="qs-section">
        <div class="qs-wrap qs-grid-3">
            <article class="qs-card"><h3 class="qs-h3">Non-custodial design</h3><p class="qs-muted">Quantum Scalp is designed around a non-custodial architecture. We explain when assets must move to supported venues for execution.</p></article>
            <article class="qs-card"><h3 class="qs-h3">Access controls</h3><p class="qs-muted">Member accounts support authenticator-based 2FA and backup codes for account and withdrawal security.</p></article>
            <article class="qs-card"><h3 class="qs-h3">Operational monitoring</h3><p class="qs-muted">Reliability, observability, and continuous delivery are part of the software-engineering stack behind Q-Core.</p></article>
        </div>
    </section>
</main>
<?php include 'footer.php'; include 'inc/public-end.php'; ?>
