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
                        <p>Effective Date: February 2026</p>
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
                        <h3 class="qs-h3">Quantum Scalp Affiliate Terms &amp; Conditions</h3>
                        <p>Effective Date: February 2026</p>
                        <p>These Affiliate Terms (“Affiliate Terms”) govern participation in the Quantum Scalp Affiliate Program (“Program”) operated by Quantum Scalp AI (“Quantum Scalp,” “Company,” “we,” “us,” or “our”).</p>
                        <p>By applying for or participating in the Program, you agree to these Affiliate Terms.</p>
                        <h3>1. Eligibility</h3>
                        <p>Affiliates must:</p>
                        <ul>
                            <li>be at least 18 years old;</li>
                            <li>have legal capacity to enter into an agreement;</li>
                            <li>provide accurate registration information;</li>
                            <li>comply with applicable laws;</li>
                            <li>complete any required verification; and</li>
                            <li>reside in a jurisdiction where participation is legally permitted.</li>
                        </ul>
                        <p>Quantum Scalp may reject or terminate an application at its discretion.</p>
                        <h3>2. Independent Relationship</h3>
                        <p>Affiliates are independent contractors and are not employees, agents, partners, representatives, or legal representatives of Quantum Scalp.</p>
                        <p>Affiliates may not represent that they have authority to bind the Company.</p>
                        <h3>3. Affiliate Links and Referrals</h3>
                        <p>Approved Affiliates may receive unique referral links, codes, or other tracking mechanisms.</p>
                        <p>Commissions are payable only on qualifying transactions that are properly attributed to the Affiliate under the Company’s tracking system.</p>
                        <p>The Company’s records will generally determine referral attribution unless there is demonstrable evidence of an error.</p>
                        <h3>4. Commission Structure</h3>
                        <p>The applicable commission structure will be displayed within the Affiliate Program materials or Affiliate dashboard.</p>
                        <p>Quantum Scalp reserves the right to modify commission rates, qualifying criteria, promotional campaigns, or program rules upon reasonable notice.</p>
                        <h3>5. No Guaranteed Earnings</h3>
                        <p>Affiliates must not make claims that participation in the Program will produce guaranteed income.</p>
                        <p>Affiliates must not represent that:</p>
                        <ul>
                            <li>a particular income level is guaranteed;</li>
                            <li>a particular number of customers is guaranteed;</li>
                            <li>trading profits are guaranteed;</li>
                            <li>users cannot lose money;</li>
                            <li>historical results guarantee future results; or</li>
                            <li>joining the Program is an investment that will generate guaranteed returns.</li>
                        </ul>
                        <h3>6. Marketing Standards</h3>
                        <p>Affiliates must represent Quantum Scalp accurately and professionally.</p>
                        <p>Affiliates must not:</p>
                        <ul>
                            <li>make false or misleading claims;</li>
                            <li>make unauthorized financial or investment recommendations;</li>
                            <li>fabricate trading results;</li>
                            <li>manipulate screenshots or performance information;</li>
                            <li>impersonate Quantum Scalp personnel;</li>
                            <li>use spam;</li>
                            <li>engage in deceptive advertising;</li>
                            <li>make guaranteed-income claims;</li>
                            <li>make guaranteed-profit claims; or</li>
                            <li>advertise in jurisdictions where the Company has prohibited marketing.</li>
                        </ul>
                        <h3>7. Use of Company Materials</h3>
                        <p>Affiliates may use approved Quantum Scalp marketing materials.</p>
                        <p>Affiliates must not alter official materials in a manner that changes their meaning or creates misleading claims.</p>
                        <h3>8. Prohibited Activities</h3>
                        <p>Affiliates may not use:</p>
                        <ul>
                            <li>misleading websites;</li>
                            <li>fake testimonials;</li>
                            <li>fake reviews;</li>
                            <li>misleading social-media accounts;</li>
                            <li>unauthorized paid advertisements;</li>
                            <li>spam;</li>
                            <li>phishing;</li>
                            <li>deceptive domain names;</li>
                            <li>false regulatory claims; or</li>
                            <li>misleading claims concerning the Company’s ownership, licensing, performance, or financial position.</li>
                        </ul>
                        <h3>9. Compliance</h3>
                        <p>Affiliates are responsible for complying with advertising, consumer-protection, financial-promotion, privacy, data-protection, AML, sanctions, and other applicable laws in the jurisdictions where they operate.</p>
                        <h3>10. Commission Reversals</h3>
                        <p>The Company may reverse commissions associated with:</p>
                        <ul>
                            <li>refunds;</li>
                            <li>chargebacks;</li>
                            <li>fraudulent transactions;</li>
                            <li>canceled transactions;</li>
                            <li>duplicate transactions;</li>
                            <li>regulatory violations; or</li>
                            <li>transactions that do not meet qualifying criteria.</li>
                        </ul>
                        <h3>11. Suspension</h3>
                        <p>Quantum Scalp may suspend or terminate an Affiliate account where the Affiliate violates these Terms or creates legal, regulatory, reputational, or financial risk for the Company.</p>
                        <h3>12. No Sub-Affiliate Entitlement</h3>
                        <p>Unless expressly provided within the published compensation plan, Affiliates have no entitlement to commissions outside the compensation structure officially provided by Quantum Scalp.</p>
                        <h3>13. Taxes</h3>
                        <p>Affiliates are responsible for their own tax obligations arising from commissions or other compensation.</p>
                        <h3>14. Amendments</h3>
                        <p>Quantum Scalp may amend these Terms from time to time. Continued participation after the effective date of an amendment constitutes acceptance.</p>
                        <h3>15. Governing Law</h3>
                        <p>These Terms shall be governed by the laws of the state of Wyoming.</p>
                    </article>

                    <article class="qs-docs__panel qs-legal" data-qs-doc-panel="aml">
                        <h3 class="qs-h3">Quantum Scalp Anti-Money Laundering (AML), Counter-Terrorist Financing (CTF) and Know Your Customer (KYC) Policy</h3>
                        <p>Effective Date: February 2026</p>
                        <h3>1. Purpose</h3>
                        <p>Quantum Scalp is committed to maintaining appropriate controls designed to prevent its platform and services from being used for money laundering, terrorist financing, fraud, sanctions evasion, or other financial crime.</p>
                        <p>This Policy establishes the Company’s framework for customer identification, risk assessment, transaction monitoring, sanctions screening, and suspicious activity escalation.</p>
                        <p>The controls described in this Policy are risk-based and may be strengthened where circumstances require.</p>
                        <h3>2. Customer Identification</h3>
                        <p>Quantum Scalp may require customers to provide information including:</p>
                        <ul>
                            <li>full legal name;</li>
                            <li>date of birth;</li>
                            <li>residential address;</li>
                            <li>nationality;</li>
                            <li>email address;</li>
                            <li>telephone number;</li>
                            <li>government-issued identification;</li>
                            <li>proof of address;</li>
                            <li>source-of-funds information;</li>
                            <li>source-of-wealth information where appropriate; and</li>
                            <li>other information reasonably necessary for compliance purposes.</li>
                        </ul>
                        <h3>3. Identity Verification</h3>
                        <p>The Company may use third-party identity-verification providers to verify customer information.</p>
                        <p>Verification may include:</p>
                        <ul>
                            <li>document authentication;</li>
                            <li>facial verification;</li>
                            <li>database checks;</li>
                            <li>sanctions screening;</li>
                            <li>politically exposed person screening;</li>
                            <li>adverse-media screening; and</li>
                            <li>other appropriate risk-based checks.</li>
                        </ul>
                        <h3>4. Risk-Based Approach</h3>
                        <p>Customers may be assigned different risk levels based on factors including:</p>
                        <ul>
                            <li>jurisdiction;</li>
                            <li>customer type;</li>
                            <li>transaction activity;</li>
                            <li>source of funds;</li>
                            <li>expected account activity;</li>
                            <li>use of third-party wallets;</li>
                            <li>sanctions exposure;</li>
                            <li>PEP status;</li>
                            <li>adverse media;</li>
                            <li>unusual transaction patterns; and</li>
                            <li>other relevant risk indicators.</li>
                        </ul>
                        <p>Enhanced Due Diligence may be required for higher-risk customers.</p>
                        <h3>5. Politically Exposed Persons</h3>
                        <p>Quantum Scalp may apply enhanced measures to customers identified as politically exposed persons (“PEPs”), their family members, or known close associates, where required by applicable law.</p>
                        <h3>6. Sanctions</h3>
                        <p>Quantum Scalp may screen customers and transactions against applicable sanctions and restricted-party lists.</p>
                        <p>The Company may refuse, freeze, suspend, or terminate activity where required or reasonably necessary to comply with applicable sanctions obligations.</p>
                        <h3>7. Source of Funds</h3>
                        <p>Quantum Scalp may request information or documentation concerning the source of funds or source of wealth associated with a customer’s activity.</p>
                        <p>Failure to provide satisfactory information may result in restricted access or termination.</p>
                        <h3>8. Transaction Monitoring</h3>
                        <p>The Company may monitor account and transaction activity for unusual or suspicious patterns.</p>
                        <p>Indicators may include:</p>
                        <ul>
                            <li>unusual transaction volumes;</li>
                            <li>rapid movement of assets;</li>
                            <li>activity inconsistent with the customer’s profile;</li>
                            <li>use of multiple accounts;</li>
                            <li>suspected fraud;</li>
                            <li>transactions involving high-risk jurisdictions;</li>
                            <li>attempts to circumvent platform controls; or</li>
                            <li>other suspicious behavior.</li>
                        </ul>
                        <h3>9. Suspicious Activity</h3>
                        <p>Where required by applicable law, Quantum Scalp may report suspicious activity to the appropriate authorities.</p>
                        <p>The Company may be prohibited from informing a customer that a suspicious activity report has been made.</p>
                        <h3>10. Record Keeping</h3>
                        <p>Quantum Scalp will maintain relevant customer and transaction records for the period required by applicable law.</p>
                        <h3>11. Cooperation With Authorities</h3>
                        <p>The Company may cooperate with law-enforcement, regulatory, judicial, and other competent authorities where legally required or permitted.</p>
                        <h3>12. Refusal of Service</h3>
                        <p>Quantum Scalp reserves the right to refuse onboarding or terminate a relationship where:</p>
                        <ul>
                            <li>identity cannot be adequately verified;</li>
                            <li>information is false or misleading;</li>
                            <li>the source of funds cannot be reasonably established;</li>
                            <li>sanctions concerns exist;</li>
                            <li>suspicious activity is identified;</li>
                            <li>the customer refuses required KYC procedures; or</li>
                            <li>continued service presents unacceptable legal or compliance risk.</li>
                        </ul>
                        <h3>13. Privacy</h3>
                        <p>Personal information collected through KYC procedures will be handled in accordance with the Company’s <a class="qs-text-link" href="privacy">Privacy Policy</a> and applicable data-protection laws.</p>
                        <h3>14. Updates</h3>
                        <p>This Policy may be updated periodically to reflect changes in applicable laws, regulations, regulatory guidance, and the Company’s risk environment.</p>
                        <p>The policy is intended to operate consistently with a risk-based AML/CFT framework; FATF’s current guidance specifically emphasizes risk assessment and appropriate controls for virtual-asset service providers.</p>
                    </article>

                    <article class="qs-docs__panel qs-legal" data-qs-doc-panel="cookies">
                        <h3 class="qs-h3">Quantum Scalp Cookie Policy</h3>
                        <p>Effective Date: February 2026</p>
                        <p>This Cookie Policy explains how Quantum Scalp AI, operating as Quantum Scalp (“Quantum Scalp,” “we,” “us,” or “our”), uses cookies and similar technologies when you visit our website or use our online services.</p>
                        <h3>1. What Are Cookies?</h3>
                        <p>Cookies are small text files placed on your device when you visit a website.</p>
                        <p>They allow websites to recognize a device, remember preferences, maintain sessions, understand website usage, and improve functionality.</p>
                        <h3>2. Types of Cookies We May Use</h3>
                        <h4>Strictly Necessary Cookies</h4>
                        <p>These cookies are required for core website functionality.</p>
                        <p>They may be used for:</p>
                        <ul>
                            <li>account authentication;</li>
                            <li>security;</li>
                            <li>session management;</li>
                            <li>fraud prevention;</li>
                            <li>load balancing; and</li>
                            <li>other essential functions.</li>
                        </ul>
                        <p>These cookies generally cannot be disabled through our cookie-management tools where they are technically necessary.</p>
                        <h4>Functional Cookies</h4>
                        <p>These cookies allow the website to remember choices and preferences, such as language or interface settings.</p>
                        <h4>Analytics Cookies</h4>
                        <p>Analytics technologies may help us understand:</p>
                        <ul>
                            <li>how visitors use the website;</li>
                            <li>which pages are visited;</li>
                            <li>how users navigate the platform;</li>
                            <li>website performance; and</li>
                            <li>technical errors.</li>
                        </ul>
                        <h4>Marketing Cookies</h4>
                        <p>Where used and legally permitted, marketing technologies may help measure advertising effectiveness and deliver more relevant promotional content.</p>
                        <h3>3. Third-Party Cookies</h3>
                        <p>Some cookies may be placed by third-party providers, such as:</p>
                        <ul>
                            <li>analytics providers;</li>
                            <li>security providers;</li>
                            <li>payment providers;</li>
                            <li>advertising providers;</li>
                            <li>customer-support providers; or</li>
                            <li>embedded content providers.</li>
                        </ul>
                        <p>Third-party providers may process information according to their own privacy policies.</p>
                        <h3>4. Cookie Consent</h3>
                        <p>Where required by applicable law, Quantum Scalp will request consent before placing non-essential cookies on your device.</p>
                        <p>You may withdraw or modify consent using the website’s cookie-management tools where available.</p>
                        <h3>5. Managing Cookies</h3>
                        <p>You can also control cookies through your browser settings.</p>
                        <p>Disabling certain cookies may affect the functionality of portions of the website.</p>
                        <h3>6. Similar Technologies</h3>
                        <p>Quantum Scalp may use technologies such as pixels, tags, local storage, device identifiers, and similar technologies for purposes comparable to cookies.</p>
                        <h3>7. Changes to This Policy</h3>
                        <p>We may update this Cookie Policy periodically to reflect changes in technology, law, or our services.</p>
                    </article>

                    <article class="qs-docs__panel qs-legal" data-qs-doc-panel="jurisdiction">
                        <h3 class="qs-h3">Quantum Scalp Jurisdiction Restrictions Policy</h3>
                        <p>Effective Date: February 2026</p>
                        <p>Quantum Scalp operates a compliance-based access model and may restrict access to its software, services, affiliate program, or other offerings in jurisdictions where providing or promoting those services may be prohibited, restricted, or subject to regulatory authorization.</p>
                        <h3>1. General Principle</h3>
                        <p>Nothing on the Quantum Scalp website is intended to constitute an offer, solicitation, recommendation, or provision of services in a jurisdiction where such activity is prohibited or requires authorization that Quantum Scalp does not hold.</p>
                        <h3>2. Restricted Jurisdictions</h3>
                        <p>Quantum Scalp may restrict or prohibit customers, affiliates, distributors, or other users located in jurisdictions identified by the Company as restricted.</p>
                        <p>The restricted-jurisdiction list may change periodically based on:</p>
                        <ul>
                            <li>applicable laws;</li>
                            <li>regulatory requirements;</li>
                            <li>sanctions;</li>
                            <li>licensing requirements;</li>
                            <li>AML considerations;</li>
                            <li>local financial-promotion restrictions;</li>
                            <li>advice from legal counsel; and</li>
                            <li>changes in the Company’s operations.</li>
                        </ul>
                        <h3>3. Geographic Restrictions</h3>
                        <p>The Company may refuse access to persons located in, ordinarily resident in, incorporated in, or operating from restricted jurisdictions.</p>
                        <p>The Company may also restrict access where it determines that a user is attempting to circumvent geographic restrictions.</p>
                        <h3>4. Affiliate Marketing Restrictions</h3>
                        <p>Affiliates must not actively market Quantum Scalp into a jurisdiction that the Company has designated as restricted.</p>
                        <p>Affiliates are responsible for understanding and complying with applicable local marketing and financial-promotion laws.</p>
                        <p>An Affiliate must not assume that because Quantum Scalp’s website is accessible from a particular country, Quantum Scalp is authorized to provide regulated services in that country.</p>
                        <h3>5. VPNs and Circumvention</h3>
                        <p>Users must not use VPNs, proxy servers, false addresses, false identity information, or other methods to circumvent geographic restrictions or KYC controls.</p>
                        <p>Where appropriate, Quantum Scalp may suspend or terminate accounts suspected of attempting to circumvent these controls.</p>
                        <h3>6. Regulatory Status</h3>
                        <p>Quantum Scalp does not represent that it is licensed or authorized in every jurisdiction in which its website may technically be accessible.</p>
                        <p>Users are responsible for determining whether accessing or using the Company’s services is lawful in their jurisdiction.</p>
                        <h3>7. Right to Refuse Service</h3>
                        <p>Quantum Scalp reserves the right to refuse, suspend, restrict, or terminate access where necessary to comply with applicable laws, regulations, sanctions, licensing requirements, or internal compliance policies.</p>
                        <h3>8. Changes to Restricted Jurisdictions</h3>
                        <p>The Company may add or remove jurisdictions from its restricted list without prior notice where necessary to address regulatory, legal, compliance, or operational requirements.</p>
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
