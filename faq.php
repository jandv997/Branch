<?php
$pageTitle = 'FAQ | Quantum Scalp AI';
$pageDescription = 'Clear answers about what Quantum Scalp is, what Q-Core does, and what to verify before using the platform.';
$currentPage = 'faq';
include 'inc/public-start.php';
include 'header.php';

$faqs = array(
    array(
        'What is Quantum Scalp?',
        'Quantum Scalp is a technology company. We provide AI-powered arbitrage software and related member tools — not brokerage services, investment products, or a trading venue.',
    ),
    array(
        'What is Q-Core?',
        'Q-Core is the software engine behind Quantum Scalp. It analyzes fragmented digital-asset markets across centralized exchanges, decentralized protocols, and derivatives, and coordinates parallel arbitrage strategies as infrastructure.',
    ),
    array(
        'Is Quantum Scalp an investment company?',
        'No. Quantum Scalp provides software and technology services. License fees provide access to software and services and do not represent an investment or a guaranteed return.',
    ),
    array(
        'Is Quantum Scalp a hedge fund?',
        'No. We do not pool customer capital, manage discretionary investment accounts, or operate as a fund. Members license technology; they remain responsible for their own activity.',
    ),
    array(
        'How does the software work?',
        'Q-Core monitors prices, liquidity, and related market data, then evaluates whether a strategy is feasible after fees, latency, and execution constraints. Where activity is recorded on-chain, hashes can be independently verified on public explorers.',
    ),
    array(
        'What types of arbitrage does Q-Core analyze?',
        'Six strategy families: CEX arbitrage, DEX arbitrage, futures/funding, cross-market, statistical, and related execution modules. Each is a technology capability subject to market conditions, liquidity, and fees.',
    ),
    array(
        'Do you custody customer funds?',
        'The platform is designed around a non-custodial architecture. Verification and on-chain checks never require your private keys. Where assets must be transferred to a trading venue for execution, that process is explained in the member dashboard.',
    ),
    array(
        'How are payments processed?',
        'License and membership payments are processed through the supported methods shown at checkout. Always confirm the wallet address displayed in your dashboard before sending funds.',
    ),
    array(
        'How do withdrawals work?',
        'Withdrawals are requested from the member dashboard and reviewed according to account status, method, and applicable terms. Processing times vary by network and payment rail.',
    ),
    array(
        'What exchanges are supported?',
        'The supported venue list is maintained by the company. See the <a class="qs-text-link" href="technology">Technology</a> page for the current, verified list.',
    ),
    array(
        'Can I verify blockchain transactions?',
        'Yes. Where activity is publicly recorded, you can paste a transaction hash on the <a class="qs-text-link" href="verify">Verify on Chain</a> page or open the official explorer for that network. We never take custody of your keys.',
    ),
    array(
        'Can I lose money?',
        'Yes. Automated cryptocurrency trading involves substantial risk, including the possible loss of capital. Spreads can disappear, fees can exceed the opportunity, and markets can move against a position.',
    ),
    array(
        'Are returns guaranteed?',
        'No. Nothing on this website or in the software guarantees profits. We do not use “guaranteed returns”, “risk-free”, or “never lose money”.',
    ),
    array(
        'What happens if trading experiences losses?',
        'Losses are possible. Q-Core is software infrastructure; it does not eliminate market, liquidity, operational, or execution risk. Review the <a class="qs-text-link" href="risk">Risk Disclosure</a> before using the platform.',
    ),
    array(
        'What does my license provide?',
        'Access to Q-Core software, strategy infrastructure, the member dashboard, support, and platform updates — depending on the plan. License fees are not an investment in a pooled product.',
    ),
    array(
        'Is this financial advice?',
        'No. Nothing on this website constitutes investment, financial, legal, or tax advice. You are solely responsible for your decisions and for complying with laws in your jurisdiction.',
    ),
    array(
        'How does the partner program work?',
        'Qualified partners may introduce users to the platform under an approved referral/affiliate program focused on technology adoption, education, and community — not recruitment. Compensation is subject to the official plan and geographic restrictions.',
    ),
    array(
        'Can anyone become a partner?',
        'Applications are reviewed and subject to approval. Apply on the <a class="qs-text-link" href="partners">Partners</a> page. Approval is not guaranteed.',
    ),
    array(
        'What countries are supported?',
        'Availability of products and services may vary by jurisdiction. Some regions may be restricted. Contact support if you need to confirm access for your location.',
    ),
    array(
        'How do I contact support?',
        'Use the <a class="qs-text-link" href="contact">Contact</a> page or email <a class="qs-text-link" href="mailto:info@quantumscalp.io">info@quantumscalp.io</a>. For licensed members, the dashboard messaging icon reaches support directly.',
    ),
);
?>
<main>
    <section class="qs-hero qs-section--tight">
        <div class="qs-wrap">
            <p class="qs-eyebrow">FAQ</p>
            <h1 class="qs-h1">Questions, answered directly.</h1>
            <p class="qs-lead qs-lead--wide">Clear answers about what Quantum Scalp is, what Q-Core does, and what to verify before using the platform.</p>
        </div>
    </section>

    <section class="qs-section qs-section--tight">
        <div class="qs-wrap">
            <div class="qs-faq" data-qs-faq>
                <?php foreach ($faqs as $item) { ?>
                    <details class="qs-faq__item">
                        <summary>
                            <span><?php echo htmlspecialchars($item[0]); ?></span>
                            <span class="qs-faq__icon" aria-hidden="true"></span>
                        </summary>
                        <div class="qs-faq__body"><?php echo $item[1]; ?></div>
                    </details>
                <?php } ?>
            </div>
        </div>
    </section>
</main>
<?php include 'footer.php'; include 'inc/public-end.php'; ?>
