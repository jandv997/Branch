<?php
$pageTitle = 'Q-Core Engine | Quantum Scalp AI';
$pageDescription = 'The proprietary engine behind Quantum Scalp. Q-Core analyzes fragmented markets and evaluates strategies in parallel.';
$currentPage = 'q-core';
include 'inc/public-start.php';
include 'header.php';

$archStages = array(
    array(
        'key' => 'market',
        'tag' => '01 · Ingest',
        'label' => 'Market Data',
        'desc' => 'Q-Core continuously ingests fragmented market data — order books, liquidity pools, funding rates and on-chain state — across hundreds of venues and multiple chains.',
        'sub' => array('CEX order books', 'DEX pools', 'Futures curves', 'On-chain state'),
        'icon' => 'ingest',
    ),
    array(
        'key' => 'engine',
        'tag' => '02 · Analyze',
        'label' => 'Q-Core AI · Opportunity Engine',
        'desc' => 'The engine compares pricing relationships, liquidity depth and execution costs in parallel, scoring potential opportunities against configurable risk parameters. Quantum-inspired optimization on classical infrastructure.',
        'sub' => array('Pricing & spreads', 'Liquidity depth', 'Risk scoring', 'Feasibility check'),
        'icon' => 'engine',
    ),
    array(
        'key' => 'venues',
        'tag' => '03 · Route',
        'label' => 'CEX · DEX · Futures',
        'desc' => 'Qualifying opportunities are routed to the appropriate market layer — centralized exchanges, decentralized protocols or derivatives venues — depending on where the edge exists.',
        'sub' => array('Centralized', 'Decentralized', 'Derivatives'),
        'icon' => 'route',
        'chips' => array('CEX', 'DEX', 'Futures'),
    ),
    array(
        'key' => 'exec',
        'tag' => '04 · Act',
        'label' => 'Execution / Analysis',
        'desc' => 'Where configured and technically supported, strategies execute to their programmed logic with slippage and cost controls. Every action is recorded for post-trade analysis.',
        'sub' => array('Programmed logic', 'Slippage guards', 'Post-trade analysis'),
        'icon' => 'exec',
    ),
    array(
        'key' => 'portfolio',
        'tag' => '05 · Result',
        'label' => 'Portfolio',
        'desc' => 'Outcomes settle into your portfolio and are written to an immutable ledger. Payouts route to your wallets per your configuration — fully transparent and, where public, verifiable on-chain.',
        'sub' => array('Ledger entry', 'Payout routing', 'Transparency'),
        'icon' => 'portfolio',
    ),
);

function qs_qcore_icon($name) {
    $icons = array(
        'ingest' => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>',
        'engine' => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="4" y="4" width="7" height="7" rx="1"/><rect x="13" y="4" width="7" height="7" rx="1"/><rect x="4" y="13" width="7" height="7" rx="1"/><rect x="13" y="13" width="7" height="7" rx="1"/></svg>',
        'route' => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="15" width="6" height="6" rx="1"/><rect x="15" y="15" width="6" height="6" rx="1"/><rect x="9" y="3" width="6" height="6" rx="1"/><path d="M6 15v-3h12v3M12 12V9"/></svg>',
        'exec' => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M13 3L6 13h6l-1 8 7-10h-6z"/></svg>',
        'portfolio' => '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="7" width="18" height="12" rx="2"/><path d="M8 7V5h8v2"/></svg>',
        'detect' => '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>',
        'analyze' => '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="4" y="4" width="7" height="7" rx="1"/><rect x="13" y="4" width="7" height="7" rx="1"/><rect x="4" y="13" width="7" height="7" rx="1"/><rect x="13" y="13" width="7" height="7" rx="1"/></svg>',
        'evaluate' => '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="12" cy="12" r="8"/><circle cx="12" cy="12" r="3"/><path d="M12 4v2M12 18v2M4 12h2M18 12h2"/></svg>',
        'monitor' => '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M3 12c2-4 4-6 9-6s7 2 9 6c-2 4-4 6-9 6s-7-2-9-6z"/><path d="M3 12h18"/></svg>',
        'search' => '<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>',
        'stack' => '<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4 8l8-4 8 4-8 4-8-4z"/><path d="M4 12l8 4 8-4"/><path d="M4 16l8 4 8-4"/></svg>',
        'signal' => '<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 20V10"/><path d="M8 20V14"/><path d="M16 20V6"/><path d="M4 20v-4"/><path d="M20 20V8"/></svg>',
        'cpu' => '<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="7" y="7" width="10" height="10" rx="1"/><path d="M9 3v4M15 3v4M9 17v4M15 17v4M3 9h4M3 15h4M17 9h4M17 15h4"/></svg>',
        'shield' => '<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 3l8 4v6c0 5-3.5 8.5-8 9.5C7.5 21.5 4 18 4 13V7l8-4z"/></svg>',
        'db' => '<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.6"><ellipse cx="12" cy="6" rx="8" ry="3"/><path d="M4 6v6c0 1.7 3.6 3 8 3s8-1.3 8-3V6"/><path d="M4 12v6c0 1.7 3.6 3 8 3s8-1.3 8-3v-6"/></svg>',
        'flex' => '<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M8 7h8M8 12h8M8 17h5"/><path d="M4 5v14"/><path d="M20 8v8"/></svg>',
        'core' => '<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="6" width="18" height="12" rx="2"/><path d="M7 10h4M7 14h2"/><circle cx="16" cy="12" r="1.6"/></svg>',
    );
    return isset($icons[$name]) ? $icons[$name] : '';
}
?>
<main>
    <section class="qs-hero qs-section--tight">
        <div class="qs-wrap">
            <p class="qs-eyebrow">Inside Q-Core</p>
            <h1 class="qs-h1">The proprietary engine behind Quantum Scalp.</h1>
            <p class="qs-lead qs-lead--wide">Q-Core is the software engine that powers the Quantum Scalp platform. It analyzes fragmented markets, evaluates strategies in parallel, and — where configured — interacts with connected trading infrastructure.</p>
            <div class="qs-btn-row">
                <a class="qs-btn qs-btn--primary" href="account/index">Access Q-Core →</a>
                <button class="qs-btn qs-btn--ghost" type="button" data-qs-video>How it works</button>
            </div>
        </div>
    </section>

    <section class="qs-section">
        <div class="qs-wrap">
            <p class="qs-eyebrow">System Architecture</p>
            <h2 class="qs-h2">From market data to portfolio — end to end.</h2>
            <p class="qs-lead qs-lead--wide">Hover or tap any stage to understand how Q-Core moves from raw market data to a verifiable portfolio result.</p>
            <div class="qs-arch" data-qs-arch>
                <div class="qs-arch__list">
                    <?php foreach ($archStages as $i => $stage) { ?>
                        <button class="qs-arch__stage<?php echo $stage['key'] === 'engine' ? ' is-active' : ''; ?>" type="button" data-qs-arch-stage="<?php echo htmlspecialchars($stage['key']); ?>">
                            <span class="qs-arch__icon"><?php echo qs_qcore_icon($stage['icon']); ?></span>
                            <span>
                                <small><?php echo htmlspecialchars($stage['tag']); ?></small>
                                <strong><?php echo htmlspecialchars($stage['label']); ?></strong>
                            </span>
                            <?php if (!empty($stage['chips'])) { ?>
                                <span class="qs-arch__chips">
                                    <?php foreach ($stage['chips'] as $chip) { ?>
                                        <em><?php echo htmlspecialchars($chip); ?></em>
                                    <?php } ?>
                                </span>
                            <?php } ?>
                        </button>
                        <?php if ($i < count($archStages) - 1) { ?>
                            <span class="qs-arch__arrow" aria-hidden="true">
                                <svg width="24" height="28" viewBox="0 0 24 34"><line x1="12" y1="0" x2="12" y2="34" stroke="rgba(45,212,191,0.35)" stroke-width="1.5"/><polygon points="12,34 8,26 16,26" fill="rgba(45,212,191,0.5)"/></svg>
                            </span>
                        <?php } ?>
                    <?php } ?>
                </div>
                <div class="qs-arch__detail" data-qs-arch-detail>
                    <?php foreach ($archStages as $stage) { ?>
                        <article class="qs-arch__panel<?php echo $stage['key'] === 'engine' ? ' is-active' : ''; ?>" data-qs-arch-panel="<?php echo htmlspecialchars($stage['key']); ?>">
                            <span class="qs-arch__icon qs-arch__icon--lg"><?php echo qs_qcore_icon($stage['icon']); ?></span>
                            <p class="qs-kicker"><?php echo htmlspecialchars($stage['tag']); ?></p>
                            <h3 class="qs-h3"><?php echo htmlspecialchars($stage['label']); ?></h3>
                            <p class="qs-muted"><?php echo htmlspecialchars($stage['desc']); ?></p>
                            <div class="qs-arch__tags">
                                <?php foreach ($stage['sub'] as $tag) { ?>
                                    <span><?php echo htmlspecialchars($tag); ?></span>
                                <?php } ?>
                            </div>
                        </article>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>

    <section class="qs-section qs-section--center">
        <div class="qs-wrap">
            <p class="qs-eyebrow">Inside Q-Core</p>
            <h2 class="qs-h2">A deterministic pipeline, running continuously.</h2>
            <div class="qs-pipe" data-qs-pipe>
                <?php
                $pipe = array(
                    array('Detect', 'Market data streams enter Q-Core across venues.', 'detect'),
                    array('Analyze', 'Pricing, liquidity, spreads and conditions compared.', 'analyze'),
                    array('Evaluate', 'Opportunities scored against risk parameters.', 'evaluate'),
                    array('Execute', 'Qualifying strategies run to programmed logic.', 'exec'),
                    array('Monitor', 'Positions and outcomes continuously tracked.', 'monitor'),
                );
                foreach ($pipe as $i => $step) {
                ?>
                    <button class="qs-pipe__step<?php echo $i === 0 ? ' is-active' : ''; ?>" type="button" data-qs-pipe-step>
                        <span class="qs-pipe__meta">
                            <span class="qs-pipe__icon"><?php echo qs_qcore_icon($step[2]); ?></span>
                            <span class="qs-kicker">0<?php echo $i + 1; ?></span>
                        </span>
                        <strong><?php echo htmlspecialchars($step[0]); ?></strong>
                        <p><?php echo htmlspecialchars($step[1]); ?></p>
                    </button>
                <?php } ?>
            </div>
        </div>
    </section>

    <section class="qs-section">
        <div class="qs-wrap">
            <p class="qs-eyebrow">Core Capabilities</p>
            <h2 class="qs-h2">What the engine is designed to do.</h2>
            <div class="qs-grid-3" style="margin-top:48px;">
                <article class="qs-card">
                    <span class="qs-cap-icon"><?php echo qs_qcore_icon('search'); ?></span>
                    <h3 class="qs-h3">Multi-Market Analysis</h3>
                    <p class="qs-muted">Monitor fragmented cryptocurrency markets and analyze pricing relationships across CEXs, DEXs, derivatives, and chains.</p>
                </article>
                <article class="qs-card">
                    <span class="qs-cap-icon"><?php echo qs_qcore_icon('stack'); ?></span>
                    <h3 class="qs-h3">Parallel Strategy Analysis</h3>
                    <p class="qs-muted">Multiple arbitrage strategies can be evaluated simultaneously by the engine.</p>
                </article>
                <article class="qs-card">
                    <span class="qs-cap-icon"><?php echo qs_qcore_icon('signal'); ?></span>
                    <h3 class="qs-h3">Automated Opportunity Detection</h3>
                    <p class="qs-muted">The engine continuously evaluates market data for potential opportunities as conditions change.</p>
                </article>
                <article class="qs-card">
                    <span class="qs-cap-icon"><?php echo qs_qcore_icon('cpu'); ?></span>
                    <h3 class="qs-h3">Execution Infrastructure</h3>
                    <p class="qs-muted">Where configured and technically supported, the system can interact with connected trading infrastructure to execute qualifying strategies.</p>
                </article>
                <article class="qs-card">
                    <span class="qs-cap-icon"><?php echo qs_qcore_icon('shield'); ?></span>
                    <h3 class="qs-h3">Risk Parameters</h3>
                    <p class="qs-muted">The platform can incorporate configurable risk controls and execution constraints.</p>
                </article>
                <article class="qs-card">
                    <span class="qs-cap-icon"><?php echo qs_qcore_icon('db'); ?></span>
                    <h3 class="qs-h3">Data Intelligence</h3>
                    <p class="qs-muted">Continuous market data and system activity provide information that can be used to improve models and optimization.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="qs-section">
        <div class="qs-wrap">
            <div class="qs-term">
                <p class="qs-term__label">01 Terminology</p>
                <h2 class="qs-h2">Quantum-inspired optimization, running on classical computing infrastructure.</h2>
                <p class="qs-lead qs-lead--wide">Q-Core uses concepts inspired by quantum optimization while operating on conventional computing infrastructure. Q-Core is <strong>not</strong> a literal quantum computer. We avoid scientifically misleading statements and describe the technology as it actually works.</p>
            </div>
        </div>
    </section>

    <section class="qs-section">
        <div class="qs-wrap">
            <p class="qs-eyebrow">Deployment Configurations</p>
            <h2 class="qs-h2">Two ways to deploy your license.</h2>
            <p class="qs-lead">Choose how your Q-Core license operates. Both configurations involve trading risk.</p>
            <div class="qs-grid-2" style="margin-top:40px;">
                <article class="qs-deploy">
                    <div class="qs-deploy__head">
                        <span class="qs-cap-icon"><?php echo qs_qcore_icon('flex'); ?></span>
                        <div>
                            <h3 class="qs-h3">Flex Deploy</h3>
                            <p class="qs-muted">Access Q-Core on your own terms.</p>
                        </div>
                    </div>
                    <div class="qs-deploy__item"><strong>No long-term lock-in</strong><p>Configure deployment windows without long-term commitments — your license, your timeline.</p></div>
                    <div class="qs-deploy__item"><strong>Configurable strategy mix</strong><p>Tune your Q-Core deployment to match your preferred strategies and market focus.</p></div>
                    <div class="qs-deploy__item"><strong>Member-controlled activity</strong><p>Start, pause, and adjust deployment activity from your dashboard.</p></div>
                    <div class="qs-deploy__item"><strong>Transparent timing</strong><p>You choose when your configuration is active. Trading still involves risk.</p></div>
                </article>
                <article class="qs-deploy is-featured">
                    <div class="qs-deploy__head">
                        <span class="qs-cap-icon"><?php echo qs_qcore_icon('core'); ?></span>
                        <div>
                            <h3 class="qs-h3">Core Deploy</h3>
                            <p class="qs-muted">Continuous, automated Q-Core execution.</p>
                        </div>
                    </div>
                    <div class="qs-deploy__item"><strong>24/7 automated analysis</strong><p>Your licensed Q-Core configuration continuously scans six parallel arbitrage strategies.</p></div>
                    <div class="qs-deploy__item"><strong>Extended market coverage</strong><p>Longer active windows increase exposure to detected opportunities across markets.</p></div>
                    <div class="qs-deploy__item"><strong>Real-time opportunity detection</strong><p>Reacts to cross-exchange and on-chain inefficiencies as they are identified.</p></div>
                    <div class="qs-deploy__item"><strong>Market-neutral strategy profiles</strong><p>Emphasis on relative-value strategies. Market risk is not eliminated.</p></div>
                </article>
            </div>
        </div>
    </section>

    <section class="qs-cta">
        <div class="qs-wrap">
            <h2 class="qs-h2">Choose your license</h2>
            <div class="qs-btn-row" style="justify-content:center;">
                <a class="qs-btn qs-btn--primary" href="pricing">View License Options</a>
            </div>
        </div>
    </section>
</main>
<?php include 'footer.php'; include 'inc/public-end.php'; ?>
