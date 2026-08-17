<?php
include_once dirname(__FILE__) . '/payment-wallets.php';
$qsTopupCoins = qs_payment_wallet_options($mysqli);
?>
<div class="qs-toast-error" id="qs-toast-error" role="alert">
	<span class="qs-toast-error__icon">!</span>
	<span id="qs-toast-error-text">Insufficient wallet balance</span>
</div>
<div class="qs-buy-overlay" id="qs-topup-overlay"
	data-main="<?php echo htmlspecialchars(isset($rows['wallet']) ? $rows['wallet'] : 0); ?>"
	data-staking="<?php echo htmlspecialchars(isset($rows['compound_profit']) ? $rows['compound_profit'] : 0); ?>"
	data-referral="<?php echo htmlspecialchars(isset($rows['ref_wallet']) ? $rows['ref_wallet'] : 0); ?>">
	<div class="qs-buy-modal" role="dialog" aria-modal="true" aria-labelledby="qs-topup-title">
		<form method="POST" id="qs-topup-form">
			<input type="hidden" name="id" id="qs-topup-id">
			<input type="hidden" name="added_roi" id="qs-topup-added-roi">
			<input type="hidden" name="old_amount" id="qs-topup-old-amount">
			<input type="hidden" name="compound_percent" id="qs-topup-compound">
			<input type="hidden" name="percent" id="qs-topup-percent">
			<input type="hidden" name="payout" id="qs-topup-payout">
			<input type="hidden" name="name" id="qs-topup-name">
			<input type="hidden" name="amount" id="qs-topup-amount">

			<div class="qs-buy-head">
				<h3 id="qs-topup-title">Top Up</h3>
				<button type="button" class="qs-buy-close" data-qs-topup-close aria-label="Close">&times;</button>
			</div>

			<div class="qs-buy-field">
				<label class="qs-buy-label" for="qs-topup-new-amount">Amount (USD)</label>
				<input name="new_amount" id="qs-topup-new-amount" class="qs-buy-input" required type="number" step="0.01" min="10" placeholder="0.00">
			</div>

			<div class="qs-buy-field">
				<label class="qs-buy-label" for="qs-topup-platform">Funding Source</label>
				<select name="platform" id="qs-topup-platform" class="qs-buy-select" required>
					<option value="1">Direct Deposit</option>
					<option value="2">Main Wallet ($<?php echo number_format(isset($rows['wallet']) ? $rows['wallet'] : 0, 2); ?>)</option>
					<option value="3">Staking Wallet ($<?php echo number_format(isset($rows['compound_profit']) ? $rows['compound_profit'] : 0, 2); ?>)</option>
					<option value="5">Referral Wallet ($<?php echo number_format(isset($rows['ref_wallet']) ? $rows['ref_wallet'] : 0, 2); ?>)</option>
				</select>
			</div>

			<div class="qs-buy-field qs-buy-coin is-visible" id="qs-topup-coin">
				<label class="qs-buy-label" for="qs-topup-currency">Payment Wallet</label>
				<select name="currency" id="qs-topup-currency" class="qs-buy-select">
					<option value="">Select wallet</option>
					<?php echo $qsTopupCoins; ?>
				</select>
			</div>

			<button class="qs-buy-cta" name="update-info-update" type="submit">Confirm Top-Up</button>
		</form>
	</div>
</div>
