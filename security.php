<?php
$pageTitle = 'Security | Quantum Scalp AI';
$pageDescription = 'Security engineered into the infrastructure.';
$currentPage = 'security';
include 'inc/public-start.php';
include 'header.php';

$posture = array(
    array('Encryption', 'Data in transit and at rest protected with strong cryptographic protocols.', 'lock'),
    array('Access Controls', 'Role-based access control and least-privilege principles across systems.', 'key'),
    array('API Security', 'Scoped keys, rate limiting, and monitored API surfaces.', 'shield'),
    array('Wallet Security', 'Segregation and controls around supported wallet infrastructure.', 'cube'),
    array('Smart-Contract Security', 'Careful review of on-chain interaction surfaces where applicable.', 'nodes'),
    array('Monitoring', 'Continuous observability across engine and infrastructure.', 'eye'),
    array('Anomaly Detection', 'Real-time detection of unusual behavior and execution conditions.', 'signal'),
    array('Fail-Safe Execution', 'Execution constraints and circuit-breaker style safeguards.', 'gauge'),
    array('Infrastructure Redundancy', 'Redundancy to reduce single points of failure.', 'db'),
    array('Authentication', 'Secure authentication flows and session handling.', 'fingerprint'),
    array('Audit Logging', 'System activity logged for traceability.', 'clipboard'),
);

function qs_sec_icon($name) {
    $icons = array(
        'lock' => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/></svg>',
        'key' => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="8" cy="15" r="4"/><path d="M11.5 13.5L20 5l2 2-2 2-2-1-1 2-2-1"/></svg>',
        'shield' => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 3l8 4v6c0 5-3.5 8.5-8 9.5C7.5 21.5 4 18 4 13V7l8-4z"/><path d="M9 12l2 2 4-4"/></svg>',
        'cube' => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 3l8 4.5v9L12 21 4 16.5v-9L12 3z"/><path d="M12 21V12M4 7.5L12 12l8-4.5"/></svg>',
        'nodes' => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="6" cy="8" r="2.2"/><circle cx="18" cy="8" r="2.2"/><circle cx="12" cy="16.5" r="2.2"/><path d="M8 9.2l2.4 5.1M16 9.2l-2.4 5.1"/></svg>',
        'eye' => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>',
        'signal' => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M8 9a8 8 0 0 1 8 0M6 6a11 11 0 0 1 12 0M10 13a4 4 0 0 1 4 0"/><circle cx="12" cy="17" r="1.2" fill="currentColor"/></svg>',
        'gauge' => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M5 18a9 9 0 1 1 14 0"/><path d="M12 13l4-4"/></svg>',
        'db' => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6"><ellipse cx="12" cy="6" rx="8" ry="3"/><path d="M4 6v6c0 1.7 3.6 3 8 3s8-1.3 8-3V6"/><path d="M4 12v6c0 1.7 3.6 3 8 3s8-1.3 8-3v-6"/></svg>',
        'fingerprint' => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 11a3 3 0 0 1 3 3v5"/><path d="M9 14v3"/><path d="M7 11a5 5 0 0 1 10 0"/><path d="M5 13a7 7 0 0 1 7-7 7 7 0 0 1 7 7"/><path d="M12 14v6"/></svg>',
        'clipboard' => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="7" y="4" width="10" height="16" rx="2"/><path d="M9 4V3h6v1M9 11h6M9 15h4"/></svg>',
    );
    return isset($icons[$name]) ? $icons[$name] : '';
}
?>
<main>
    <section class="qs-hero qs-section--tight">
        <div class="qs-wrap">
            <p class="qs-eyebrow">Security</p>
            <h1 class="qs-h1">Security engineered into the infrastructure.</h1>
            <p class="qs-lead qs-lead--wide">Security is foundational to how Q-Core is built and operated. Below is an overview of our security posture. We only display audit badges once they are independently verified.</p>
        </div>
    </section>

    <section class="qs-section">
        <div class="qs-wrap">
            <p class="qs-eyebrow">Posture</p>
            <h2 class="qs-h2">Defense across the stack.</h2>
            <div class="qs-grid-3" style="margin-top:48px;">
                <?php foreach ($posture as $item) { ?>
                    <article class="qs-card qs-beam">
                        <span class="qs-cap-icon"><?php echo qs_sec_icon($item[2]); ?></span>
                        <h3 class="qs-h3"><?php echo htmlspecialchars($item[0]); ?></h3>
                        <p class="qs-muted"><?php echo htmlspecialchars($item[1]); ?></p>
                    </article>
                <?php } ?>
            </div>
        </div>
    </section>
</main>
<?php include 'footer.php'; include 'inc/public-end.php'; ?>
