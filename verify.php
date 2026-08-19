<?php
$pageTitle = 'On-Chain Transparency | Quantum Scalp AI';
$pageDescription = 'Don\'t trust. Verify. Independent on-chain settlement records from Q-Core DEX Live, with official block-explorer links.';
$currentPage = 'verify';
include 'inc/public-start.php';
include 'header.php';

$networks = array(
    array('id' => 'eth', 'label' => 'Ethereum · ERC-20', 'explorer' => 'https://etherscan.io/tx/'),
    array('id' => 'tron', 'label' => 'Tron · TRC-20', 'explorer' => 'https://tronscan.org/#/transaction/'),
    array('id' => 'arbitrum', 'label' => 'Arbitrum · L2', 'explorer' => 'https://arbiscan.io/tx/'),
    array('id' => 'base', 'label' => 'Base · L2', 'explorer' => 'https://basescan.org/tx/'),
    array('id' => 'bsc', 'label' => 'BNB Chain · BEP-20', 'explorer' => 'https://bscscan.com/tx/'),
);
?>
<main data-qs-verify-page>
    <section class="qs-hero qs-section--tight qs-section--center">
        <div class="qs-wrap">
            <p class="qs-eyebrow">On-Chain Transparency</p>
            <h1 class="qs-h1">Don't trust. Verify.</h1>
            <p class="qs-lead" style="margin-left:auto;margin-right:auto;">Activity is recorded on public blockchains. Paste a hash, open the official explorer, and independently confirm what settled on-chain.</p>
        </div>
    </section>

    <section class="qs-section qs-section--tight">
        <div class="qs-wrap">
            <form class="qs-onchain-widget" data-qs-verify-form>
                <p class="qs-kicker">Verify a Transaction</p>
                <div class="qs-onchain-bar">
                    <label class="qs-onchain-net">
                        <span class="qs-sr">Network</span>
                        <select name="network" data-qs-verify-network>
                            <?php foreach ($networks as $network) { ?>
                                <option value="<?php echo htmlspecialchars($network['explorer']); ?>"><?php echo htmlspecialchars($network['label']); ?></option>
                            <?php } ?>
                        </select>
                    </label>
                    <input type="text" name="hash" data-qs-verify-hash spellcheck="false" autocomplete="off" placeholder="Paste a transaction hash (0x... or Tron ID)">
                    <button class="qs-btn qs-btn--primary qs-onchain-go" type="submit">
                        Verify
                        <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 5h5v5"/><path d="M19 5L10 14"/><path d="M5 10v9h9"/></svg>
                    </button>
                </div>
                <p class="qs-onchain-note">Opens the official block explorer for the selected network in a new tab. We never take custody of your keys.</p>
            </form>
        </div>
    </section>

    <section class="qs-section">
        <div class="qs-wrap">
            <p class="qs-eyebrow">Recent Activity</p>
            <h2 class="qs-h2">Recent on-chain settlements.</h2>
            <p class="qs-lead qs-lead--wide">Live DEX swap records from the same feed used in Q-Core DEX Live. Open any hash to view the transaction on the official explorer.</p>

            <div class="qs-ledger" data-qs-ledger>
                <div class="qs-ledger__bar">
                    <strong>Settlement Ledger</strong>
                    <span class="qs-ledger-badge">Live Trades</span>
                </div>
                <div class="qs-ledger__table-wrap">
                    <table class="qs-ledger__table">
                        <thead>
                            <tr>
                                <th>Network</th>
                                <th>Transaction Hash</th>
                                <th>Timestamp</th>
                                <th>Amount</th>
                                <th>Verify</th>
                            </tr>
                        </thead>
                        <tbody data-qs-ledger-body>
                            <tr>
                                <td colspan="5" class="qs-ledger__empty">Loading live DEX settlements…</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="qs-grid-3" style="margin-top:36px;">
                <article class="qs-card">
                    <span class="qs-cap-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 3l8 4v6c0 5-3.5 8.5-8 9.5C7.5 21.5 4 18 4 13V7l8-4z"/><path d="M9 12l2 2 4-4"/></svg>
                    </span>
                    <h3 class="qs-h3">Non-custodial</h3>
                    <p class="qs-muted">Verification never requires your private keys. You confirm records independently on public explorers.</p>
                </article>
                <article class="qs-card">
                    <span class="qs-cap-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="12" cy="12" r="9"/><path d="M8 12.5l2.5 2.5L16 9"/></svg>
                    </span>
                    <h3 class="qs-h3">Publicly auditable</h3>
                    <p class="qs-muted">On-chain settlements carry an immutable hash anyone can inspect on the relevant network.</p>
                </article>
                <article class="qs-card">
                    <span class="qs-cap-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="4" y="4" width="12" height="12" rx="2"/><path d="M10 16h8a2 2 0 0 0 2-2V8"/><path d="M16 4l4 4-4 4"/></svg>
                    </span>
                    <h3 class="qs-h3">Official explorers</h3>
                    <p class="qs-muted">Links route to the canonical explorer for each network — Etherscan, BscScan, Arbiscan and more.</p>
                </article>
            </div>
        </div>
    </section>
</main>
<?php include 'footer.php'; include 'inc/public-end.php'; ?>
