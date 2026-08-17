<?php
$qsBuyCurrencyOptions = '';
if (isset($data) && $data) {
	for ($qi = 0; $qi < count($data); $qi++) {
		$qsBuyCurrencyOptions .= '<option value="' . htmlspecialchars($data[$qi]->currency) . '">' . htmlspecialchars(strtoupper($data[$qi]->name)) . '</option>';
	}
}
?>
<div class="qs-buy-overlay" id="qs-buy-overlay">
	<div class="qs-buy-modal" role="dialog" aria-modal="true" aria-labelledby="qs-buy-title">
		<form method="POST" action="purchase" id="qs-buy-form">

			<select name="payout" id="payout" class="qs-buy-native" required>
				<option value="1" selected>Daily Payout (100% Main Wallet)</option>
				<option value="2">Staking Payout (100% Staking Wallet)</option>
				<option value="3">Hybrid Payout (25% Regular Wallet, 75% Staking Wallet)</option>
			</select>
			<select name="duration" id="duration" class="qs-buy-native">
				<option value="">Select Duration </option>
				<option value="61">2 Months</option>
				<option value="122">4 Months</option>
				<option value="183">6 Months</option>
				<option value="244">8 Months</option>
				<option value="305">10 Months</option>
				<option value="365">12 Months</option>
			</select>

			<div class="qs-buy-step is-active" id="qs-buy-step-1">
				<div class="qs-buy-head">
					<h3 id="qs-buy-title">Purchase</h3>
					<button type="button" class="qs-buy-close" data-qs-buy-close aria-label="Close">&times;</button>
				</div>

				<div class="qs-buy-field">
					<label class="qs-buy-label" for="amount">Amount (USD)</label>
					<input name="amount" id="amount" class="qs-buy-input" required type="number" step="0.01" min="0" placeholder="Enter amount">
					<p class="qs-buy-hint" id="qs-buy-range">Min $0.00 • Max $0.00</p>
				</div>

				<div class="qs-buy-field">
					<div class="qs-buy-label">Payout Type</div>
					<div class="qs-buy-payout" role="group" aria-label="Payout Type">
						<button type="button" class="is-active" data-qs-payout="1">Daily</button>
						<button type="button" data-qs-payout="2">Staking</button>
						<button type="button" data-qs-payout="3">Hybrid</button>
					</div>
					<div class="qs-buy-route">
						<div class="qs-buy-route__label">PAYOUT ROUTING</div>
						<div class="qs-buy-bar" id="qs-buy-bar"><span style="width:100%"></span></div>
						<div class="qs-buy-legend" id="qs-buy-legend"><span><i></i>100% → Main Wallet</span></div>
					</div>
				</div>

				<div class="qs-buy-field qs-buy-months" id="qs-buy-months">
					<div class="qs-buy-label">Allocation Duration (months)</div>
					<div class="qs-buy-month-row">
						<button type="button" data-qs-duration="61" data-qs-months="2">2</button>
						<button type="button" data-qs-duration="122" data-qs-months="4">4</button>
						<button type="button" data-qs-duration="183" data-qs-months="6">6</button>
						<button type="button" data-qs-duration="244" data-qs-months="8">8</button>
						<button type="button" data-qs-duration="305" data-qs-months="10">10</button>
						<button type="button" data-qs-duration="365" data-qs-months="12">12</button>
					</div>
					<p class="qs-buy-hint" id="qs-buy-duration-hint">After 6 months, allocation reverts to 100% Main (12-month total term).</p>
				</div>

				<div class="qs-buy-field">
					<label class="qs-buy-label" for="currency">Funding Source</label>
					<select name="currency" id="currency" class="qs-buy-select" required>
						<option value="">Direct Deposit</option>
						<optgroup label="Direct Deposit">
						<?php echo $qsBuyCurrencyOptions; ?>
						</optgroup>
					</select>
				</div>

				<button type="button" class="qs-buy-cta" id="qs-buy-review">Review Purchase</button>
			</div>

			<div class="qs-buy-step" id="qs-buy-step-2">
				<div class="qs-buy-head">
					<h3>Confirm Purchase</h3>
					<button type="button" class="qs-buy-close" data-qs-buy-close aria-label="Close">&times;</button>
				</div>
				<div class="qs-buy-rows">
					<div class="qs-buy-row">Portfolio <b id="qs-sum-name"></b></div>
					<div class="qs-buy-row">Amount <b id="qs-sum-amount"></b></div>
					<div class="qs-buy-row">Currency <b>USD</b></div>
					<div class="qs-buy-row">Payout Type <b id="qs-sum-payout"></b></div>
					<div class="qs-buy-row" id="qs-sum-alloc-row">Allocation Duration <b id="qs-sum-alloc"></b></div>
					<div class="qs-buy-row">Start Date <b id="qs-sum-start"></b></div>
					<div class="qs-buy-row">End Date <b id="qs-sum-end"></b></div>
					<div class="qs-buy-row">Funding Source <b id="qs-sum-fund"></b></div>
					<div class="qs-buy-row">Max Daily (capped) <b id="qs-sum-daily"></b></div>
				</div>
				<div class="qs-buy-warn">
					The daily figure is the <em>capped upside</em>, not a guarantee. Real Q-Core results vary within a ±3% band on trading days and <em>can be negative — losses are not capped.</em>
				</div>
				<label class="qs-buy-check"><input type="checkbox" id="qs-buy-term1"> I understand the portfolio terms.</label>
				<label class="qs-buy-check"><input type="checkbox" id="qs-buy-term2"> I understand that trading involves risk.</label>
				<div class="qs-buy-actions">
					<button type="button" class="qs-buy-back" id="qs-buy-back">Back</button>
					<button class="qs-buy-confirm" name="invest" id="invest" type="submit">Confirm Purchase</button>
				</div>
			</div>
		</form>
	</div>
</div>
