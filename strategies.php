<?php
$pageTitle = 'Strategies | Quantum Scalp AI';
$pageDescription = 'Six arbitrage strategies. One engine.';
$currentPage = 'strategies';
include 'inc/public-start.php';
include 'header.php';
?>
<main>
    <section class="qs-hero qs-section--tight">
        <div class="qs-wrap">
            <p class="qs-eyebrow">Strategies</p>
            <h1 class="qs-h1">Six arbitrage strategies. One engine.</h1>
            <p class="qs-lead">Q-Core is designed to analyze multiple arbitrage opportunities simultaneously. Each strategy is a technology capability, subject to market conditions, liquidity, fees, and execution feasibility.</p>
        </div>
    </section>
    <section class="qs-section">
        <div class="qs-wrap">
            <p class="qs-eyebrow">Interactive Modules</p>
            <h2 class="qs-h2">Explore each strategy.</h2>
            <p class="qs-lead">Every module includes an example workflow and its key risk considerations.</p>
            <div class="qs-grid-2" style="margin-top:32px;">
                <article class="qs-card qs-card--accent">
                    <div class="qs-kicker">ARB-01</div>
                    <h3 class="qs-h3">CEX Arbitrage</h3>
                    <p class="qs-muted">Exploit transient price differences for the same asset across centralized exchanges.</p>
                    <p class="qs-muted">Workflow: detect a price gap → assess fees and latency → buy on the cheaper venue, sell on the richer venue → capture the net spread after costs.</p>
                    <p class="qs-muted">Risk: latency, transfer times, withdrawal limits and fees can erode or eliminate the spread.</p>
                </article>
                <article class="qs-card">
                    <div class="qs-kicker">ARB-02</div>
                    <h3 class="qs-h3">DEX Arbitrage</h3>
                    <p class="qs-muted">Capture pricing differences between decentralized liquidity pools and protocols.</p>
                    <p class="qs-muted">Risk: gas costs, slippage, pool depth and MEV competition affect feasibility.</p>
                </article>
                <article class="qs-card">
                    <div class="qs-kicker">ARB-03</div>
                    <h3 class="qs-h3">Futures Arbitrage</h3>
                    <p class="qs-muted">Trade the relationship between spot and derivatives (basis / funding) markets.</p>
                    <p class="qs-muted">Risk: funding rates change, positions require margin, and liquidation risk exists.</p>
                </article>
                <article class="qs-card">
                    <div class="qs-kicker">ARB-04</div>
                    <h3 class="qs-h3">Cross-Market Arbitrage</h3>
                    <p class="qs-muted">Connect opportunities that span multiple market types simultaneously.</p>
                </article>
                <article class="qs-card">
                    <div class="qs-kicker">ARB-05</div>
                    <h3 class="qs-h3">Statistical Arbitrage</h3>
                    <p class="qs-muted">Model correlated assets that temporarily diverge and tend to reconverge.</p>
                </article>
                <article class="qs-card">
                    <div class="qs-kicker">ARB-06</div>
                    <h3 class="qs-h3">MEV / On-chain Strategy</h3>
                    <p class="qs-muted">Identify on-chain opportunities arising from transaction ordering within blocks.</p>
                </article>
            </div>
        </div>
    </section>
    <section class="qs-section">
        <div class="qs-wrap">
            <h2 class="qs-h2">Trading Involves Risk.</h2>
            <p class="qs-lead">Automated trading does not eliminate market risk. Arbitrage opportunities can disappear quickly. Losses may occur.</p>
        </div>
    </section>
</main>
<?php include 'footer.php'; include 'inc/public-end.php'; ?>
