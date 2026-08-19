<?php
if (!isset($currentPage)) {
    $currentPage = '';
}
function qs_nav_active($page) {
    global $currentPage;
    return $currentPage === $page ? ' is-active' : '';
}
?>
<div class="qs-banner" data-qs-banner>
    <span>Q-Core platform preview now live — Explore the technology, architecture, and strategies behind Quantum Scalp AI.</span>
    <button type="button" data-qs-banner-close aria-label="Dismiss">&times;</button>
</div>
<header class="qs-header">
    <div class="qs-header__inner">
        <a class="qs-logo" href="index">
            <img src="assets/img/logo/logo.png" alt="Quantum Scalp">
        </a>
        <nav class="qs-nav" data-qs-nav>
            <a class="<?php echo qs_nav_active('technology'); ?>" href="technology">Technology</a>
            <a class="<?php echo qs_nav_active('q-core'); ?>" href="q-core">Q-Core</a>
            <a class="<?php echo qs_nav_active('strategies'); ?>" href="strategies">Strategies</a>
            <a class="<?php echo qs_nav_active('signals'); ?>" href="signals">Signals</a>
            <a class="<?php echo qs_nav_active('pricing'); ?>" href="pricing">License</a>
            <div class="qs-more" data-qs-more>
                <button class="qs-more__btn" type="button" data-qs-more-btn aria-expanded="false" aria-haspopup="true">More ▾</button>
                <div class="qs-dropdown" data-qs-dropdown>
                    <a class="<?php echo qs_nav_active('vip'); ?>" href="vip">VIP Membership</a>
                    <a href="about">About</a>
                    <a href="team">Team</a>
                    <a class="<?php echo qs_nav_active('roadmap'); ?>" href="roadmap">Roadmap</a>
                    <a href="how-it-works">How It Works</a>
                    <a href="security">Security</a>
                    <a class="<?php echo qs_nav_active('verify'); ?>" href="verify">Verify on Chain</a>
                    <a href="faq">FAQ</a>
                    <a class="<?php echo qs_nav_active('partners'); ?>" href="partners">Partners</a>
                    <a href="leadership">Leadership</a>
                    <a href="events">Events</a>
                    <a href="contact">Contact</a>
                    <a href="compliance">Compliance</a>
                </div>
            </div>
        </nav>
        <div class="qs-header__actions">
            <a class="qs-signin" href="account/index">Sign In</a>
            <a class="qs-btn qs-btn--primary" href="account/index">Access Q-Core →</a>
            <button class="qs-menu-btn" type="button" data-qs-menu aria-label="Menu">☰</button>
        </div>
    </div>
</header>
