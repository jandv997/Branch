<?php
$pageTitle = 'Strategies | Quantum Scalp AI';
$pageDescription = 'Six arbitrage strategies. One engine.';
$currentPage = 'strategies';
include 'inc/public-start.php';
include 'header.php';

function qs_strategy_svg($name) {
    $path = __DIR__ . '/assets/img/strategies/' . $name . '.svg';
    return is_file($path) ? file_get_contents($path) : '';
}
function qs_strategy_viz($slug) {
    $path = __DIR__ . '/assets/img/strategies/viz-' . $slug . '.svg';
    if (!is_file($path)) {
        return '';
    }
    return 'assets/img/strategies/viz-' . $slug . '.svg?v=' . filemtime($path);
}

$qsStrategies = array(
    array(
        'slug' => 'cex',
        'code' => 'ARB-01',
        'title' => 'CEX Arbitrage',
        'icon' => 'cex',
        'summary' => 'Exploit transient price differences for the same asset across centralized exchanges.',
        'flow' => array(
            'Detect price gap for an asset across two CEXs',
            'Assess fees, withdrawal limits and execution latency',
            'Buy on the cheaper venue, sell on the richer venue',
            'Capture the net spread after costs',
        ),
        'risk' => 'Latency, transfer times, exchange withdrawal limits and fees can erode or eliminate the spread. Prices can converge before execution completes.',
    ),
    array(
        'slug' => 'dex',
        'code' => 'ARB-02',
        'title' => 'DEX Arbitrage',
        'icon' => 'dex',
        'summary' => 'Capture pricing differences between decentralized liquidity pools and protocols.',
        'flow' => array(
            'Scan liquidity pools across DEXs and chains',
            'Model price impact and gas for a candidate route',
            'Swap through pools to realize the pricing gap',
            'Settle on-chain — verifiable by transaction hash',
        ),
        'risk' => 'Gas costs, slippage, pool depth and MEV competition affect feasibility. Failed or front-run transactions still incur network fees.',
    ),
    array(
        'slug' => 'futures',
        'code' => 'ARB-03',
        'title' => 'Futures Arbitrage',
        'icon' => 'futures',
        'summary' => 'Trade the relationship between spot and derivatives (basis / funding) markets.',
        'flow' => array(
            'Compare spot price against perpetual/futures price',
            'Evaluate funding rate and basis',
            'Take offsetting spot and futures positions',
            'Realize the basis as it normalizes',
        ),
        'risk' => 'Funding rates change, positions require margin, and liquidation risk exists. Basis can widen before it converges.',
    ),
    array(
        'slug' => 'cross-market',
        'code' => 'ARB-04',
        'title' => 'Cross-Market Arbitrage',
        'icon' => 'cross-market',
        'summary' => 'Connect opportunities that span multiple market types simultaneously.',
        'flow' => array(
            'Aggregate pricing across CEX, DEX, futures and chains',
            'Identify multi-leg opportunities through Q-Core',
            'Sequence execution across venues',
            'Reconcile the combined result',
        ),
        'risk' => 'Multi-leg execution increases operational complexity and the chance that one leg fails, changing the overall economics.',
    ),
    array(
        'slug' => 'statistical',
        'code' => 'ARB-05',
        'title' => 'Statistical Arbitrage',
        'icon' => 'statistical',
        'summary' => 'Model correlated assets that temporarily diverge and tend to reconverge.',
        'flow' => array(
            'Identify historically correlated pairs',
            'Detect a statistically significant divergence',
            'Take mean-reversion positions on both legs',
            'Exit as the relationship reconverges',
        ),
        'risk' => 'Correlations can break down; \'temporary\' divergence can persist or widen. Statistical relationships are not guarantees.',
    ),
    array(
        'slug' => 'mev',
        'code' => 'ARB-06',
        'title' => 'MEV / On-chain Strategy',
        'icon' => 'mev',
        'summary' => 'Identify on-chain opportunities arising from transaction ordering within blocks.',
        'flow' => array(
            'Observe the mempool and pending transactions',
            'Detect ordering-related opportunities',
            'Construct and submit qualifying transactions',
            'Verify settlement on-chain',
        ),
        'risk' => 'Highly competitive and time-sensitive. Reverts, gas wars and validator ordering mean many attempts fail. Regulatory and ethical considerations apply.',
    ),
);
?>
<main>
    <section class="qs-hero qs-section--tight">
        <div class="qs-wrap">
            <p class="qs-eyebrow">Strategies</p>
            <h1 class="qs-h1">Six arbitrage strategies. One engine.</h1>
            <p class="qs-lead">Q-Core is designed to analyze multiple arbitrage opportunities simultaneously. Each strategy is a technology capability, subject to market conditions, liquidity, fees, and execution feasibility. Expand any module to see how it works.</p>
        </div>
    </section>
    <section class="qs-section">
        <div class="qs-wrap">
            <p class="qs-eyebrow">Interactive Modules</p>
            <h2 class="qs-h2">Explore each strategy.</h2>
            <p class="qs-lead">Every module includes a market visualization, an example workflow, and its key risk considerations.</p>
            <div class="qs-mods" data-qs-mods>
                <?php foreach ($qsStrategies as $i => $s) {
                    $open = $i === 0;
                    $viz = qs_strategy_viz($s['slug']);
                ?>
                <article class="qs-mod<?php echo $open ? ' is-open' : ''; ?>" data-qs-mod="<?php echo htmlspecialchars($s['slug']); ?>">
                    <button class="qs-mod__btn" type="button" data-qs-mod-toggle aria-expanded="<?php echo $open ? 'true' : 'false'; ?>">
                        <span class="qs-mod__icon" aria-hidden="true"><?php echo qs_strategy_svg($s['icon']); ?></span>
                        <span class="qs-mod__copy">
                            <span class="qs-mod__code"><?php echo htmlspecialchars($s['code']); ?></span>
                            <span class="qs-mod__title"><?php echo htmlspecialchars($s['title']); ?></span>
                            <span class="qs-mod__sum"><?php echo htmlspecialchars($s['summary']); ?></span>
                        </span>
                        <span class="qs-mod__chevron" aria-hidden="true"><?php echo qs_strategy_svg('chevron'); ?></span>
                    </button>
                    <div class="qs-mod__panel">
                        <div class="qs-mod__panel-inner">
                            <div class="qs-mod__grid">
                                <div class="qs-mod__viz">
                                    <p class="qs-mod__label">Market Visualization</p>
                                    <?php if ($viz !== '') { ?>
                                    <img src="<?php echo htmlspecialchars($viz); ?>" alt="">
                                    <?php } ?>
                                </div>
                                <div>
                                    <p class="qs-mod__label">Example Workflow</p>
                                    <ol class="qs-mod__flow">
                                        <?php foreach ($s['flow'] as $step => $text) { ?>
                                        <li><span><?php echo (int) $step + 1; ?></span><?php echo htmlspecialchars($text); ?></li>
                                        <?php } ?>
                                    </ol>
                                    <div class="qs-mod__risk">
                                        <p class="qs-mod__label qs-mod__label--risk">Risk Considerations</p>
                                        <p><?php echo htmlspecialchars($s['risk']); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
                <?php } ?>
            </div>
        </div>
    </section>
    <section class="qs-section">
        <div class="qs-wrap">
            <div class="qs-risk">
                <div class="qs-risk__head">
                    <span class="qs-risk__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="22" height="22" fill="none">
                            <circle cx="12" cy="12" r="9.25" stroke="currentColor" stroke-width="1.7"/>
                            <path d="M12 7.4v6.1" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/>
                            <circle cx="12" cy="16.6" r="1" fill="currentColor"/>
                        </svg>
                    </span>
                    <h2 class="qs-h2">Trading Involves Risk.</h2>
                </div>
                <p class="qs-lead">Automated trading does not eliminate market risk. Arbitrage opportunities can disappear quickly. Losses may occur. We believe in transparency over hype — understand the risks before you decide.</p>
                <div class="qs-risk-grid">
                    <span>Market volatility</span><span>Slippage</span><span>Liquidity constraints</span><span>Network congestion</span>
                    <span>Exchange outages</span><span>Execution failures</span><span>Smart-contract vulnerabilities</span><span>Counterparty risk</span>
                    <span>API failures</span><span>Trading fees</span><span>Blockchain fees</span><span>Unexpected market conditions</span>
                </div>
                <p class="qs-risk__note">We never use “guaranteed returns”, “risk-free”, “guaranteed profit”, or “never lose money”.</p>
            </div>
        </div>
    </section>
</main>
<?php include 'footer.php'; include 'inc/public-end.php'; ?>
