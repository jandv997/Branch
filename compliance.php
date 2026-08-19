<?php
$pageTitle = 'Compliance Center | Quantum Scalp AI';
$pageDescription = 'Transparency, compliance, and verifiable activity. Independently verify published Ethereum wallets and review the documents that govern the platform.';
$currentPage = 'compliance';
include 'inc/public-start.php';
include 'header.php';

$primaryUrl = 'https://etherscan.io/address/0xbD32122bAD41A09f2405Bb374A83877d8245079C';
$primaryAddr = '0xbD32122bAD41A09f2405Bb374A83877d8245079C';
$treasuryUrl = 'https://etherscan.io/address/0xC46fcd651Bd6AC11255886FEAbDceBd58b870C86';
$treasuryAddr = '0xC46fcd651Bd6AC11255886FEAbDceBd58b870C86';

$copySvg = '<svg class="qs-verify__copy-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="9" y="9" width="11" height="11" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg><svg class="qs-verify__copied-icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12.5l4 4 10-10"/></svg>';

$docs = array(
    'terms' => 'Terms of Service',
    'privacy' => 'Privacy Policy',
    'risk' => 'Risk Disclosure',
    'license' => 'Software License Agreement',
    'affiliate' => 'Affiliate / Partner Terms',
    'aml' => 'AML / KYC Policy',
    'cookies' => 'Cookie Policy',
    'jurisdiction' => 'Jurisdiction Restrictions',
);
?>
<main>
    <section class="qs-hero qs-section--tight">
        <div class="qs-wrap">
            <p class="qs-eyebrow">Compliance Center</p>
            <h1 class="qs-h1">Transparency, compliance, and verifiable activity.</h1>
            <p class="qs-lead qs-lead--wide">Quantum Scalp is committed to operating as a software and technology company in line with applicable legal and regulatory requirements. Where activity is recorded on public blockchains, it can be independently verified.</p>
        </div>
    </section>

    <section class="qs-section qs-section--tight">
        <div class="qs-wrap">
            <p class="qs-eyebrow">Transparency</p>
            <h2 class="qs-h2">Don't Take Our Word For It.</h2>
            <p class="qs-lead qs-lead--wide">Where applicable, blockchain-based activity provides independently verifiable transaction records. We publish verified wallet addresses and transaction references — we never invent them.</p>

            <div class="qs-comp-wallets">
                <div class="qs-comp-wallets__list">
                    <div class="qs-wallet">
                        <div class="qs-wallet__head">
                            <strong>Primary Activity Wallet</strong>
                            <span class="qs-wallet__net">Ethereum</span>
                        </div>
                        <div class="qs-wallet__field">
                            <code><a href="<?php echo htmlspecialchars($primaryUrl); ?>" target="_blank" rel="noopener noreferrer"><?php echo htmlspecialchars($primaryAddr); ?></a></code>
                            <button type="button" class="qs-verify__copy" data-qs-copy="<?php echo htmlspecialchars($primaryAddr); ?>" aria-label="Copy primary wallet address"><?php echo $copySvg; ?></button>
                        </div>
                    </div>
                    <div class="qs-wallet">
                        <div class="qs-wallet__head">
                            <strong>Treasury Wallet</strong>
                            <span class="qs-wallet__net">Ethereum</span>
                        </div>
                        <div class="qs-wallet__field">
                            <code><a href="<?php echo htmlspecialchars($treasuryUrl); ?>" target="_blank" rel="noopener noreferrer"><?php echo htmlspecialchars($treasuryAddr); ?></a></code>
                            <button type="button" class="qs-verify__copy" data-qs-copy="<?php echo htmlspecialchars($treasuryAddr); ?>" aria-label="Copy treasury wallet address"><?php echo $copySvg; ?></button>
                        </div>
                    </div>
                </div>

                <article class="qs-card qs-comp-verify">
                    <span class="qs-cap-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M8 8l-3 3 3 3M16 8l3 3-3 3"/><path d="M14 7l-4 10"/></svg>
                    </span>
                    <h3 class="qs-h3">Verify On-Chain Activity</h3>
                    <p class="qs-muted">Public blockchain transactions, wallet addresses, transaction hashes, trade history, and exportable reports — verifiable wherever technically possible.</p>
                    <a class="qs-btn qs-btn--primary" href="<?php echo htmlspecialchars($primaryUrl); ?>" target="_blank" rel="noopener noreferrer">
                        Verify On-Chain Activity
                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 5h5v5"/><path d="M19 5L10 14"/><path d="M5 10v9h9"/></svg>
                    </a>
                    <a class="qs-text-link qs-comp-verify__addr" href="<?php echo htmlspecialchars($primaryUrl); ?>" target="_blank" rel="noopener noreferrer"><?php echo htmlspecialchars($primaryAddr); ?></a>
                </article>
            </div>
        </div>
    </section>

    <section class="qs-section" id="documents">
        <div class="qs-wrap">
            <p class="qs-eyebrow">Legal Documents</p>
            <h2 class="qs-h2">The documents that govern the platform.</h2>

            <div class="qs-docs" data-qs-docs>
                <div class="qs-docs__nav" role="tablist">
                    <?php $first = true; foreach ($docs as $id => $label) { ?>
                        <button type="button" class="qs-docs__tab<?php echo $first ? ' is-active' : ''; ?>" data-qs-doc="<?php echo htmlspecialchars($id); ?>" role="tab" aria-selected="<?php echo $first ? 'true' : 'false'; ?>">
                            <span class="qs-docs__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M7 3h7l5 5v13H7z"/><path d="M14 3v5h5"/></svg>
                            </span>
                            <?php echo htmlspecialchars($label); ?>
                        </button>
                    <?php $first = false; } ?>
                </div>

                <div class="qs-docs__panels">
                    <article class="qs-docs__panel qs-legal is-active" data-qs-doc-panel="terms">
                        <h3 class="qs-h3">Terms of Service</h3>
                        <p>By creating an account you acknowledge that trading involves risk and that license fees do not represent an investment or guaranteed return.</p>
                        <h3>Software access, not an investment</h3>
                        <p>License fees provide access to software and services. They do not represent an investment, deposit, or guaranteed return. Quantum Scalp provides software and technology services only.</p>
                        <h3>No financial advice</h3>
                        <p>Nothing on this website constitutes investment, financial, legal, or tax advice. Availability of products and services may vary by jurisdiction.</p>
                        <h3>Risk</h3>
                        <p>Automated cryptocurrency trading involves substantial risk, including the possible loss of capital. No trading strategy or technology guarantees profits. See our <a class="qs-text-link" href="risk">Risk Disclosure</a>.</p>
                        <h3>Accounts</h3>
                        <p>You are responsible for credentials, 2FA, and activity under your account. We may suspend access for abuse, legal risk, or terms violations.</p>
                        <p>The full Software License Agreement is in the adjacent tab and is incorporated by reference.</p>
                    </article>

                    <article class="qs-docs__panel qs-legal" data-qs-doc-panel="privacy">
                        <h3 class="qs-h3">Privacy Policy</h3>
                        <p>We use privacy-conscious analytics to understand usage. You can accept or decline non-essential tracking.</p>
                        <h3>Who we are</h3>
                        <p>Quantum Scalp provides software and technology services, including the Q-Core engine and related member tools, via this website and associated applications.</p>
                        <h3>Information we process</h3>
                        <p>Account details you submit (such as name, email, and authentication data), technical logs needed to operate the service, and optional analytics if you consent. Payment processing may be handled by third-party providers.</p>
                        <h3>How we use it</h3>
                        <p>To operate licenses and dashboards, secure accounts, respond to support, meet legal obligations, and — only where permitted — understand product usage.</p>
                        <h3>Sharing</h3>
                        <p>We do not sell personal information. We may share data with infrastructure, payment, KYC, and communications vendors acting on our instructions, or when required by law.</p>
                        <h3>Contact</h3>
                        <p>Privacy questions: <a class="qs-text-link" href="mailto:info@quantumscalp.io">info@quantumscalp.io</a> or via the <a class="qs-text-link" href="contact">contact form</a>.</p>
                    </article>

                    <article class="qs-docs__panel qs-legal" data-qs-doc-panel="risk">
                        <h3 class="qs-h3">Risk Disclosure</h3>
                        <p>Automated trading does not eliminate market risk. Arbitrage opportunities can disappear quickly. Losses may occur. We believe in transparency over hype — understand the risks before you decide.</p>
                        <p>We never use “guaranteed returns”, “risk-free”, “guaranteed profit”, or “never lose money”. License fees provide access to software and services and do not represent an investment or guaranteed return. Past performance does not guarantee future results. Nothing here is financial advice.</p>
                    </article>

                    <article class="qs-docs__panel qs-legal" data-qs-doc-panel="license">
                        <h3 class="qs-h3">Quantum Scalp Software License Agreement</h3>
                        <p>Effective Date: August 19, 2026</p>
                        <p>This Software License Agreement (“Agreement”) is entered into between Quantum Scalp AI, operating under the brand name Quantum Scalp (“Quantum Scalp,” “Company,” “we,” “us,” or “our”), and the individual or legal entity accessing or using the Quantum Scalp software and services (“User,” “you,” or “your”).</p>
                        <p>By creating an account, purchasing a license, accessing, or using the Quantum Scalp platform, you acknowledge that you have read, understood, and agreed to this Agreement.</p>
                        <h3>1. Software License</h3>
                        <p>Quantum Scalp grants you a limited, non-exclusive, non-transferable, revocable license to access and use the Quantum Scalp software and related platform services for the duration of your applicable license term.</p>
                        <p>The license is provided solely for the User’s lawful use of the software and does not transfer ownership of any software, algorithms, intellectual property, source code, trading strategies, or proprietary technology to the User.</p>
                        <h3>2. Nature of the Software</h3>
                        <p>Quantum Scalp is a technology platform designed to provide software tools and automated or algorithm-assisted functionality relating to digital-asset and market-data activities.</p>
                        <p>The software may use algorithms, automation, market data, trading signals, execution technology, or other technological processes.</p>
                        <p>Past software performance, trading results, simulations, demonstrations, or historical data should not be interpreted as a guarantee of future results.</p>
                        <h3>3. No Guarantee of Performance</h3>
                        <p>The Company does not guarantee:</p>
                        <ul>
                            <li>profitability;</li>
                            <li>a particular rate of return;</li>
                            <li>successful trading results;</li>
                            <li>uninterrupted software operation;</li>
                            <li>that any particular trading strategy will remain effective;</li>
                            <li>that losses will not occur; or</li>
                            <li>that historical or simulated results will be repeated.</li>
                        </ul>
                        <p>Digital-asset markets can be highly volatile and involve substantial risk.</p>
                        <h3>4. User Responsibility</h3>
                        <p>The User is responsible for determining whether use of the software is appropriate for their circumstances and for complying with all applicable laws and regulations.</p>
                        <p>Users must not use the platform for unlawful activity, market manipulation, fraud, money laundering, terrorist financing, sanctions evasion, or any other prohibited activity.</p>
                        <h3>5. Account Security</h3>
                        <p>Users are responsible for maintaining the confidentiality of their account credentials and for all activity conducted through their account.</p>
                        <p>The Company may suspend or restrict access where it reasonably suspects unauthorized activity, fraud, abuse, regulatory concerns, or violation of this Agreement.</p>
                        <h3>6. Intellectual Property</h3>
                        <p>All software, algorithms, interfaces, designs, trademarks, branding, documentation, databases, technology, content, and other intellectual property associated with Quantum Scalp remain the property of the Company or its licensors.</p>
                        <p>Except where expressly permitted in writing, Users may not:</p>
                        <ul>
                            <li>copy the software;</li>
                            <li>reverse engineer the software;</li>
                            <li>decompile or disassemble the software;</li>
                            <li>reproduce proprietary algorithms;</li>
                            <li>distribute or resell the software;</li>
                            <li>attempt to obtain source code;</li>
                            <li>remove proprietary notices; or</li>
                            <li>create derivative works based on the software.</li>
                        </ul>
                        <h3>7. Third-Party Services</h3>
                        <p>Quantum Scalp may integrate with third-party exchanges, payment providers, technology providers, blockchain networks, APIs, custodians, or other service providers.</p>
                        <p>The Company is not responsible for outages, failures, delays, restrictions, or changes imposed by third-party providers.</p>
                        <h3>8. Fees</h3>
                        <p>Applicable license fees are displayed at the time of purchase or enrollment.</p>
                        <p>Unless otherwise expressly stated, fees are for software access and related services and are not deposits, investments, securities, or capital contributions to the Company.</p>
                        <h3>9. Suspension and Termination</h3>
                        <p>The Company may suspend or terminate access where:</p>
                        <ul>
                            <li>the User violates this Agreement;</li>
                            <li>the User engages in fraudulent or unlawful activity;</li>
                            <li>required KYC information is not provided;</li>
                            <li>AML or sanctions concerns arise;</li>
                            <li>the User is located in a restricted jurisdiction;</li>
                            <li>required payments are not made; or</li>
                            <li>continued access would create legal, regulatory, security, or operational risk.</li>
                        </ul>
                        <h3>10. Risk Disclosure</h3>
                        <p>Use of technology involving digital assets and financial markets carries risk, including the potential loss of funds.</p>
                        <p>Nothing contained within the Quantum Scalp platform constitutes personalized financial, investment, tax, or legal advice unless expressly provided by an appropriately authorized professional.</p>
                        <h3>11. Limitation of Liability</h3>
                        <p>To the maximum extent permitted by applicable law, Quantum Scalp shall not be liable for indirect, incidental, consequential, special, or punitive damages arising from use of or inability to use the platform.</p>
                        <p>Nothing in this Agreement excludes liability that cannot lawfully be excluded under applicable law.</p>
                        <h3>12. Changes</h3>
                        <p>Quantum Scalp may update this Agreement from time to time. Updated versions will be posted on the website and become effective upon publication unless otherwise stated.</p>
                        <h3>13. Governing Law</h3>
                        <p>This Agreement shall be governed by the laws of the state of Wyoming, subject to applicable mandatory consumer and regulatory protections.</p>
                    </article>

                    <article class="qs-docs__panel qs-legal" data-qs-doc-panel="affiliate">
                        <h3 class="qs-h3">Affiliate / Partner Terms</h3>
                        <p>Qualified partners may introduce users to the platform under an approved referral or affiliate program focused on technology adoption, education, and community — not recruitment.</p>
                        <p>Compensation is configurable and subject to the official, legally approved compensation plan, applicable terms, and geographic restrictions. Applications are reviewed and subject to approval; approval is not guaranteed.</p>
                        <p>Partners must not make claims of guaranteed returns, risk-free trading, or regulatory approvals that Quantum Scalp has not verified. See the <a class="qs-text-link" href="partners">Partners</a> page to apply.</p>
                    </article>

                    <article class="qs-docs__panel qs-legal" data-qs-doc-panel="aml">
                        <h3 class="qs-h3">AML / KYC Policy</h3>
                        <p>Quantum Scalp may require identity verification (KYC) as part of onboarding, withdrawals, or risk review. We may suspend or restrict access where required information is not provided, or where AML, fraud, or sanctions concerns arise.</p>
                        <p>Users must not use the platform for money laundering, terrorist financing, sanctions evasion, market manipulation, or other unlawful activity. We may share information with vendors and authorities when required by law.</p>
                    </article>

                    <article class="qs-docs__panel qs-legal" data-qs-doc-panel="cookies">
                        <h3 class="qs-h3">Cookie Policy</h3>
                        <p>We use cookies to operate the site, remember your preferences (including cookie consent and certain dashboard acknowledgements), and improve your experience.</p>
                        <p>You can accept or decline non-essential tracking via the cookie banner. Essential cookies needed to run the site may still be used. See the <a class="qs-text-link" href="privacy">Privacy Policy</a> for how we handle information.</p>
                    </article>

                    <article class="qs-docs__panel qs-legal" data-qs-doc-panel="jurisdiction">
                        <h3 class="qs-h3">Jurisdiction Restrictions</h3>
                        <p>Availability of products and services may vary by jurisdiction. Users should only access the platform where legally permitted. The Company may suspend or terminate access where the User is located in a restricted jurisdiction or where continued access would create legal or regulatory risk.</p>
                        <p>This website and the Software License Agreement are governed by the laws of the state of Wyoming, subject to applicable mandatory consumer and regulatory protections.</p>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <section class="qs-section qs-section--tight">
        <div class="qs-wrap qs-grid-2">
            <article class="qs-card">
                <span class="qs-cap-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.8 3 4.2 6 4.2 9S14.8 18 12 21c-2.8-3-4.2-6-4.2-9S9.2 6 12 3z"/></svg>
                </span>
                <h3 class="qs-h3">United States / International Expansion</h3>
                <p class="qs-muted">Quantum Scalp operates internationally and takes jurisdiction-specific compliance seriously. Different jurisdictions may have different rules concerning crypto, software, automated trading, referral programs, marketing, payments, and digital assets. Users should only access the platform where legally permitted.</p>
            </article>
            <article class="qs-card">
                <span class="qs-cap-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 3l8 4v6c0 5-3.5 8.5-8 9.5C7.5 21.5 4 18 4 13V7l8-4z"/><path d="M9 12l2 2 4-4"/></svg>
                </span>
                <h3 class="qs-h3">What We Do Not Claim</h3>
                <p class="qs-muted">We do not claim to be “fully regulated globally”, “SEC approved”, “EU approved”, or “licensed everywhere” unless independently verified. Availability of products and services may vary by jurisdiction.</p>
            </article>
        </div>
    </section>
</main>
<?php include 'footer.php'; include 'inc/public-end.php'; ?>
