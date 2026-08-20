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
        <a class="qs-logo" href="index.php">
            <img src="assets/img/logo/logo-white.png" alt="Quantum Scalp">
        </a>
        <nav class="qs-nav" data-qs-nav>
            <a class="<?php echo qs_nav_active('technology'); ?>" href="technology.php">Technology</a>
            <a class="<?php echo qs_nav_active('q-core'); ?>" href="q-core.php">Q-Core</a>
            <a class="<?php echo qs_nav_active('strategies'); ?>" href="strategies.php">Strategies</a>
            <a class="<?php echo qs_nav_active('signals'); ?>" href="signals.php">Signals</a>
            <a class="<?php echo qs_nav_active('pricing'); ?>" href="pricing.php">License</a>
            <div class="qs-more" data-qs-more>
                <button class="qs-more__btn" type="button" data-qs-more-btn aria-expanded="false" aria-haspopup="true">More ▾</button>
                <div class="qs-dropdown" data-qs-dropdown>
                    <div class="qs-dropdown__panel">
                    <a class="<?php echo qs_nav_active('vip'); ?>" href="vip.php">VIP Membership</a>
                    <a class="<?php echo qs_nav_active('about'); ?>" href="about.php">About</a>
                    <a class="<?php echo qs_nav_active('team'); ?>" href="team.php">Team</a>
                    <a class="<?php echo qs_nav_active('roadmap'); ?>" href="roadmap.php">Roadmap</a>
                    <a class="<?php echo qs_nav_active('how-it-works'); ?>" href="how-it-works.php">How It Works</a>
                    <a class="<?php echo qs_nav_active('security'); ?>" href="security.php">Security</a>
                    <a class="<?php echo qs_nav_active('verify'); ?>" href="verify.php">Verify on Chain</a>
                    <a class="<?php echo qs_nav_active('faq'); ?>" href="faq.php">FAQ</a>
                    <a class="<?php echo qs_nav_active('partners'); ?>" href="partners.php">Partners</a>
                    <a class="<?php echo qs_nav_active('leadership'); ?>" href="leadership.php">Leadership</a>
                    <a class="<?php echo qs_nav_active('events'); ?>" href="events.php">Events</a>
                    <a class="<?php echo qs_nav_active('contact'); ?>" href="contact.php">Contact</a>
                    <a class="<?php echo qs_nav_active('compliance'); ?>" href="compliance.php">Compliance</a>
                    </div>
                </div>
            </div>
        </nav>
        <div class="qs-header__actions">
            <?php if (!empty($qsPublicUser)) { ?>
            <a class="qs-nav-user" href="account/dashboard.php">
                <img src="<?php echo htmlspecialchars($qsPublicUser['img']); ?>" alt="">
                <span><?php echo htmlspecialchars($qsPublicUser['name']); ?></span>
            </a>
            <?php } else { ?>
            <a class="qs-signin" href="account/index.php">Sign In</a>
            <a class="qs-btn qs-btn--primary" href="account/index.php">Access Q-Core →</a>
            <?php } ?>
            <button class="qs-menu-btn" type="button" data-qs-menu aria-label="Menu">☰</button>
        </div>
    </div>
</header>
