<div class="qs-buy-overlay" id="qs-history-overlay">
	<div class="qs-buy-modal qs-history-modal" role="dialog" aria-modal="true" aria-labelledby="qs-history-title">
		<div class="qs-buy-head">
			<h3 id="qs-history-title">Portfolio History</h3>
			<button type="button" class="qs-buy-close" data-qs-history-close aria-label="Close">&times;</button>
		</div>
		<div class="qs-history-tabs" role="tablist" aria-label="Portfolio history">
			<button type="button" class="qs-history-tab is-active" data-qs-history-tab="payouts" role="tab" aria-selected="true">Payouts</button>
			<button type="button" class="qs-history-tab" data-qs-history-tab="topups" role="tab" aria-selected="false">Top-ups</button>
		</div>
		<div class="qs-history-panel is-active" data-qs-history-panel="payouts" role="tabpanel">
			<div class="qs-history-list" id="qs-history-payouts"></div>
		</div>
		<div class="qs-history-panel" data-qs-history-panel="topups" role="tabpanel">
			<div class="qs-history-topups" id="qs-history-topups"></div>
		</div>
	</div>
</div>
