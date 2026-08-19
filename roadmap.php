<?php
$pageTitle = 'Roadmap | Quantum Scalp AI';
$pageDescription = 'Build once. Learn continuously. Scale globally. Q-Core development direction — planned items are subject to development and regulatory considerations.';
$currentPage = 'roadmap';
include 'inc/public-start.php';
include 'header.php';

$phases = array(
    array(
        'phase' => 1,
        'title' => 'Core AI Research & Infrastructure',
        'copy' => 'Foundational AI research, data pipelines, and infrastructure for market analysis.',
        'status' => 'achieved',
    ),
    array(
        'phase' => 2,
        'title' => 'Q-Core Orchestration & Strategy Integration',
        'copy' => 'Orchestration layer coordinating parallel strategy analysis across market types.',
        'status' => 'achieved',
    ),
    array(
        'phase' => 3,
        'title' => 'Platform Expansion & Security',
        'copy' => 'Hardening security, scaling infrastructure, and expanding platform capabilities.',
        'status' => 'achieved',
    ),
    array(
        'phase' => 4,
        'title' => 'Mobile & Ecosystem Integrations',
        'copy' => 'Mobile experiences and additional ecosystem integrations.',
        'status' => 'achieved',
    ),
    array(
        'phase' => 5,
        'title' => 'Cross-Chain Expansion',
        'copy' => 'Expanded cross-chain monitoring and execution capabilities.',
        'status' => 'progress',
    ),
    array(
        'phase' => 6,
        'title' => 'Next-Generation Computing & Expanded Markets',
        'copy' => 'Research into next-generation computing approaches and expanded asset markets.',
        'status' => 'progress',
    ),
);
?>
<main>
    <section class="qs-hero qs-section--tight qs-section--center">
        <div class="qs-wrap">
            <p class="qs-eyebrow">Roadmap</p>
            <h1 class="qs-h1">Build once. Learn continuously. Scale globally.</h1>
            <p class="qs-lead" style="margin-left:auto;margin-right:auto;">Our development direction. All items are planned and subject to development and regulatory considerations — nothing here is guaranteed.</p>
        </div>
    </section>

    <section class="qs-section">
        <div class="qs-roadmap-wrap">
            <ol class="qs-roadmap">
                <?php foreach ($phases as $item) {
                    $isDone = $item['status'] === 'achieved';
                    $badge = $isDone ? 'Achieved' : 'In Progress';
                    $badgeClass = $isDone ? 'is-achieved' : 'is-progress';
                ?>
                    <li class="qs-roadmap__item <?php echo $badgeClass; ?>">
                        <span class="qs-roadmap__node" aria-hidden="true">
                            <?php if ($isDone) { ?>
                                <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 12.5l4 4 10-10"/></svg>
                            <?php } ?>
                        </span>
                        <article class="qs-roadmap__card">
                            <p class="qs-kicker">Phase <?php echo (int) $item['phase']; ?></p>
                            <h3 class="qs-h3"><?php echo htmlspecialchars($item['title']); ?></h3>
                            <p class="qs-muted"><?php echo htmlspecialchars($item['copy']); ?></p>
                            <span class="qs-roadmap__badge <?php echo $badgeClass; ?>"><?php echo $badge; ?></span>
                        </article>
                    </li>
                <?php } ?>
            </ol>
            <p class="qs-roadmap__note">Planned / subject to development and regulatory considerations.</p>
        </div>
    </section>
</main>
<?php include 'footer.php'; include 'inc/public-end.php'; ?>
