<?php
$pageTitle = 'Technology | Quantum Scalp AI';
$pageDescription = 'AI-powered arbitrage infrastructure, accessible through software.';
$currentPage = 'technology';
include 'inc/public-start.php';
include 'header.php';

$venues = array(
    array('CEX', 'Binance'),
    array('CEX', 'Coinbase'),
    array('CEX', 'Kraken'),
    array('CEX', 'OKX'),
    array('CEX', 'Bybit'),
    array('DEX', 'Uniswap'),
    array('DEX', 'Curve'),
    array('DEX', 'SushiSwap'),
    array('DEX', 'Balancer'),
    array('DEX', 'PancakeSwap'),
    array('DEX', 'Avave'),
);
?>
<main>
    <section class="qs-hero qs-section--tight">
        <div class="qs-wrap">
            <p class="qs-eyebrow">Technology</p>
            <h1 class="qs-h1">AI-powered arbitrage infrastructure, accessible through software.</h1>
            <p class="qs-lead qs-lead--wide">Quantum Scalp is fundamentally a technology company. We combine AI, quantitative analysis, automated execution, and blockchain infrastructure into one software ecosystem — while transparently explaining the risks of automated trading.</p>
        </div>
    </section>

    <section class="qs-section">
        <div class="qs-wrap">
            <p class="qs-eyebrow">The Stack</p>
            <h2 class="qs-h2">One technology ecosystem.</h2>
            <div class="qs-grid-3" style="margin-top:48px;">
                <article class="qs-card qs-beam">
                    <div class="qs-kicker">01</div>
                    <h3 class="qs-h3">Artificial Intelligence</h3>
                    <p class="qs-muted">ML models for statistical deviations &amp; signal detection</p>
                </article>
                <article class="qs-card qs-beam">
                    <div class="qs-kicker">02</div>
                    <h3 class="qs-h3">Quantitative Analysis</h3>
                    <p class="qs-muted">Relative-value, basis, and correlation modeling</p>
                </article>
                <article class="qs-card qs-beam">
                    <div class="qs-kicker">03</div>
                    <h3 class="qs-h3">Automated Execution</h3>
                    <p class="qs-muted">Configurable execution against connected infrastructure</p>
                </article>
                <article class="qs-card qs-beam">
                    <div class="qs-kicker">04</div>
                    <h3 class="qs-h3">Blockchain Infrastructure</h3>
                    <p class="qs-muted">Multi-chain monitoring incl. Ethereum, Tron and more</p>
                </article>
                <article class="qs-card qs-beam">
                    <div class="qs-kicker">05</div>
                    <h3 class="qs-h3">Cloud Computing</h3>
                    <p class="qs-muted">Scalable classical compute for parallel analysis</p>
                </article>
                <article class="qs-card qs-beam">
                    <div class="qs-kicker">06</div>
                    <h3 class="qs-h3">Software Engineering</h3>
                    <p class="qs-muted">Reliability, observability, and continuous delivery</p>
                </article>
            </div>
        </div>
    </section>

    <section class="qs-section">
        <div class="qs-wrap">
            <p class="qs-eyebrow">Capability</p>
            <h2 class="qs-h2">Six parallel arbitrage strategies.</h2>
            <p class="qs-lead qs-lead--wide">Q-Core evaluates multiple arbitrage types simultaneously. Each represents a technology capability — not a promise of returns.</p>
            <div class="qs-grid-3 qs-strategy-grid" style="margin-top:48px;">
                <article class="qs-strategy" data-qs-strategy>
                    <div class="qs-strategy__top">
                        <span class="qs-kicker">01</span>
                        <span class="qs-strategy__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M7 7h10v4"/><path d="M17 7l-4 4"/><path d="M17 17H7v-4"/><path d="M7 17l4-4"/></svg>
                        </span>
                    </div>
                    <h3 class="qs-h3">Cross-Exchange Arbitrage</h3>
                    <p class="qs-muted">Compares prices for the same digital assets across different centralized exchanges and identifies potential price discrepancies.</p>
                    <div class="qs-code">Exchange A → Asset → Exchange B</div>
                </article>
                <article class="qs-strategy" data-qs-strategy>
                    <div class="qs-strategy__top">
                        <span class="qs-kicker">02</span>
                        <span class="qs-strategy__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M12 4l8 14H4z"/></svg>
                        </span>
                    </div>
                    <h3 class="qs-h3">Triangular Arbitrage</h3>
                    <p class="qs-muted">Analyzes relationships between three trading pairs to identify potential pricing inefficiencies within a single exchange or market environment.</p>
                    <div class="qs-code">BTC → USDT → ETH → BTC</div>
                </article>
                <article class="qs-strategy" data-qs-strategy>
                    <div class="qs-strategy__top">
                        <span class="qs-kicker">03</span>
                        <span class="qs-strategy__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M4 19V5"/><path d="M4 19l5-6 4 3 7-9"/></svg>
                        </span>
                    </div>
                    <h3 class="qs-h3">Statistical Arbitrage</h3>
                    <p class="qs-muted">Machine-learning models analyze historical relationships and market behavior to identify statistical deviations that may represent potential opportunities.</p>
                    <div class="qs-code">Historical correlation + AI signal</div>
                </article>
                <article class="qs-strategy" data-qs-strategy>
                    <div class="qs-strategy__top">
                        <span class="qs-kicker">04</span>
                        <span class="qs-strategy__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="7" cy="8" r="2.2"/><circle cx="17" cy="8" r="2.2"/><circle cx="12" cy="16.5" r="2.2"/><path d="M9 9.2l2.2 5.2M15 9.2l-2.2 5.2"/></svg>
                        </span>
                    </div>
                    <h3 class="qs-h3">DEX Arbitrage</h3>
                    <p class="qs-muted">Monitors decentralized exchanges and automated market makers for pricing differences and liquidity imbalances.</p>
                    <div class="qs-code">DEX A → Blockchain → DEX B</div>
                </article>
                <article class="qs-strategy" data-qs-strategy>
                    <div class="qs-strategy__top">
                        <span class="qs-kicker">05</span>
                        <span class="qs-strategy__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M13 3L6 13h6l-1 8 7-10h-6z"/></svg>
                        </span>
                    </div>
                    <h3 class="qs-h3">Flash Loan Arbitrage</h3>
                    <p class="qs-muted">Where technically and economically viable, identifies atomic on-chain arbitrage opportunities involving flash-loan infrastructure. Dependent on available liquidity, blockchain conditions, transaction costs, smart-contract conditions, and execution feasibility.</p>
                    <div class="qs-code">Borrow → Swap → Repay (atomic)</div>
                </article>
                <article class="qs-strategy" data-qs-strategy>
                    <div class="qs-strategy__top">
                        <span class="qs-kicker">06</span>
                        <span class="qs-strategy__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M4 16l5-5 4 3 7-8"/><path d="M15 6h5v5"/></svg>
                        </span>
                    </div>
                    <h3 class="qs-h3">Futures &amp; Derivatives Arbitrage</h3>
                    <p class="qs-muted">Analyzes relationships between spot and derivatives markets, including funding-rate differences, basis spreads, calendar spreads, and other relative-value opportunities.</p>
                    <div class="qs-code">Spot ↔ Perp / Funding / Basis</div>
                </article>
            </div>
        </div>
    </section>

    <section class="qs-section">
        <div class="qs-wrap">
            <p class="qs-eyebrow">Supported Venues</p>
            <h2 class="qs-h2">Markets the engine is designed to monitor.</h2>
            <p class="qs-lead qs-lead--wide">The list below is illustrative. Venue support is maintained by the company and must be independently verified before launch.</p>
            <div class="qs-venues" data-testid="exchange-list">
                <?php foreach ($venues as $venue) { ?>
                    <span class="qs-venue">
                        <em class="<?php echo $venue[0] === 'DEX' ? 'is-dex' : 'is-cex'; ?>"><?php echo htmlspecialchars($venue[0]); ?></em>
                        <?php echo htmlspecialchars($venue[1]); ?>
                        <small>· verified</small>
                    </span>
                <?php } ?>
            </div>
        </div>
    </section>

    <section class="qs-section qs-section--tight">
        <div class="qs-wrap">
            <div class="qs-cta-bar">
                <div>
                    <h2 class="qs-h2" style="font-size:clamp(24px,3vw,32px);">Go deeper into the engine.</h2>
                    <p class="qs-muted" style="margin:8px 0 0;">See how Q-Core analyzes, detects, and executes.</p>
                </div>
                <a class="qs-btn qs-btn--primary" href="q-core">Inside Q-Core →</a>
            </div>
        </div>
    </section>
</main>
<?php include 'footer.php'; include 'inc/public-end.php'; ?>
