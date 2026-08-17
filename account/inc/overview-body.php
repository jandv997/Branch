<?php
$qs_money = function ($n) {
	return '$' . number_format((float) $n, 2);
};
$qs_go = function ($url) {
	return $url;
};

$wallet = isset($rows['wallet']) ? (float) $rows['wallet'] : 0;
$staking = isset($rows['compound_profit']) ? (float) $rows['compound_profit'] : 0;
$referral = isset($rows['ref_wallet']) ? (float) $rows['ref_wallet'] : 0;
$profit = isset($rows['profit']) ? (float) $rows['profit'] : 0;

$investments = array();
$totalinvest = 0;
$toaldaily = 0;
$get_investment = mysqli_query($mysqli, "SELECT * FROM investment WHERE userid='" . $rows['id'] . "' ORDER BY id DESC");
if ($get_investment) {
	while ($row = mysqli_fetch_assoc($get_investment)) {
		$investments[] = $row;
		$totalinvest += (float) $row['amount'];
		$toaldaily += (float) $row['daily_roi'];
	}
}
$activeCount = count($investments);

$withdrawnTotal = 0;
$get_withdrawals = mysqli_query($mysqli, "SELECT * FROM withdrawal WHERE status=1 and userid='" . $rows['id'] . "' ORDER BY id DESC");
$withdrawCount = $get_withdrawals ? mysqli_num_rows($get_withdrawals) : 0;
if ($get_withdrawals) {
	while ($wd = mysqli_fetch_assoc($get_withdrawals)) {
		$withdrawnTotal += (float) $wd['amount'];
	}
}

$pendingTotal = 0;
$get_pending = mysqli_query($mysqli, "SELECT * FROM withdrawal WHERE status=0 and userid='" . $rows['id'] . "' ORDER BY id DESC");
if ($get_pending) {
	while ($wd = mysqli_fetch_assoc($get_pending)) {
		$pendingTotal += (float) $wd['amount'];
	}
}

$commission = 0;
$investment = mysqli_query($mysqli, "SELECT referal_link, id FROM `users` WHERE id='" . $rows['id'] . "'");
if ($investment) {
	while ($user = mysqli_fetch_assoc($investment)) {
		$getrefer = mysqli_query($mysqli, "SELECT id FROM users WHERE referred='" . $user['referal_link'] . "' ");
		if ($getrefer) {
			while ($refer = mysqli_fetch_assoc($getrefer)) {
				$get_ref_inv = mysqli_query($mysqli, "SELECT amount FROM investment WHERE userid='" . $refer['id'] . "'  ORDER BY id DESC");
				if ($get_ref_inv) {
					while ($in = mysqli_fetch_assoc($get_ref_inv)) {
						$commission += (float) $in['amount'];
					}
				}
			}
		}
	}
}

$news = array();
$curl = curl_init();
curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://finnhub.io/api/v1/news?category=general&token=c3no7o2ad3iabnjjem70',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 8,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'GET',
));
$newsRaw = curl_exec($curl);
curl_close($curl);
$newsDecoded = json_decode($newsRaw, true);
if (is_array($newsDecoded)) {
	$news = array_slice($newsDecoded, 0, 3);
}

$allocTotal = $wallet + $staking + $referral;
?>
<div class="qs-ov">

	<div class="qs-ov-wallets">
		<div class="qs-ov-card">
			<div class="qs-ov-card__label"><i class="fe fe-credit-card"></i> Main Wallet</div>
			<div class="qs-ov-card__value"><?php echo $qs_money($wallet); ?></div>
			<div class="qs-ov-card__hint">Available balance</div>
			<div class="qs-ov-card__split">
				<div class="qs-ov-mini"><span>Pending</span><strong><?php echo $qs_money($pendingTotal); ?></strong></div>
				<div class="qs-ov-mini"><span>Total withdrawn</span><strong><?php echo $qs_money($withdrawnTotal); ?></strong></div>
			</div>
		</div>
		<div class="qs-ov-card">
			<div class="qs-ov-card__label"><i class="fe fe-layers"></i> Staking Wallet</div>
			<div class="qs-ov-card__value"><?php echo $qs_money($staking); ?></div>
			<div class="qs-ov-card__hint">Available balance</div>
			<div class="qs-ov-card__split">
				<div class="qs-ov-mini"><span>Pending</span><strong><?php echo $qs_money(0); ?></strong></div>
				<div class="qs-ov-mini"><span>Total credited</span><strong><?php echo $qs_money($staking); ?></strong></div>
			</div>
		</div>
		<div class="qs-ov-card">
			<div class="qs-ov-card__label"><i class="fe fe-users"></i> Referral Wallet</div>
			<div class="qs-ov-card__value"><?php echo $qs_money($referral); ?></div>
			<div class="qs-ov-card__hint">Available balance</div>
			<div class="qs-ov-card__split">
				<div class="qs-ov-mini"><span>Pending</span><strong><?php echo $qs_money(0); ?></strong></div>
				<div class="qs-ov-mini"><span>Total credited</span><strong><?php echo $qs_money($referral); ?></strong></div>
			</div>
		</div>
		<div class="qs-ov-card">
			<div class="qs-ov-card__label"><i class="fe fe-briefcase"></i> Active Portfolios</div>
			<div class="qs-ov-card__value"><?php echo (int) $activeCount; ?></div>
			<div class="qs-ov-card__hint">Currently accruing</div>
			<a class="qs-ov-link" href="<?php echo $qs_go('marketplace'); ?>">Manage in Quantum Verse ↗</a>
		</div>
	</div>

	<div class="qs-ov-actions">
		<a class="qs-ov-action" href="<?php echo $qs_go('marketplace'); ?>"><i class="fe fe-grid"></i> Choose Portfolio</a>
		<a class="qs-ov-action" href="<?php echo $qs_go('make-withdrawal'); ?>"><i class="fe fe-arrow-down-circle"></i> Make Withdrawal</a>
		<a class="qs-ov-action" href="<?php echo $qs_go('active-purchase'); ?>"><i class="fe fe-briefcase"></i> Active Portfolios</a>
		<a class="qs-ov-action" href="<?php echo $qs_go('membership'); ?>"><i class="fe fe-award"></i> Membership</a>
		<a class="qs-ov-action" href="<?php echo $qs_go('referral-bonus'); ?>"><i class="fe fe-users"></i> Referral Bonus</a>
	</div>

	<div class="qs-ov-charts">
		<div class="qs-ov-card">
			<div class="qs-ov-card__head">
				<h3>Portfolio Performance</h3>
				<div class="qs-ov-ranges" role="tablist">
					<button type="button">7D</button>
					<button type="button" class="is-active">30D</button>
					<button type="button">90D</button>
					<button type="button">1Y</button>
					<button type="button">ALL</button>
				</div>
			</div>
			<?php if ($profit <= 0 && $activeCount === 0) { ?>
				<div class="qs-ov-empty">No earnings yet — purchase a portfolio to begin accruing.</div>
			<?php } else { ?>
				<canvas id="qsPerfChart" height="120"></canvas>
			<?php } ?>
		</div>
		<div class="qs-ov-card">
			<div class="qs-ov-card__head">
				<h3>Wallet Allocation</h3>
			</div>
			<div class="qs-ov-alloc">
				<canvas id="qsAllocChart" width="180" height="180"></canvas>
			</div>
			<div class="qs-ov-legend">
				<span><i style="background:#2DD4BF"></i>Main</span>
				<span><i style="background:#0E9E90"></i>Staking</span>
				<span><i style="background:#00E676"></i>Referral</span>
			</div>
		</div>
	</div>

	<div class="qs-ov-card qs-ov-pulse">
		<div class="qs-ov-card__head">
			<h3>Market Pulse</h3>
			<a class="qs-ov-link" href="<?php echo $qs_go('overview-core?tab=cex'); ?>">Open Live Terminal ↗</a>
		</div>
		<div class="qs-ov-pulse__grid">
			<div>
				<h4>Market News</h4>
				<?php
				if (count($news) === 0) {
					echo '<p class="muted">No market headlines right now.</p>';
				}
				foreach ($news as $item) {
					$headline = isset($item['headline']) ? $item['headline'] : (isset($item['source']) ? $item['source'] : 'Update');
					$url = isset($item['url']) ? $item['url'] : '#';
					$source = isset($item['source']) ? $item['source'] : '';
					echo '<a class="item" href="' . htmlspecialchars($url) . '" target="_blank" rel="noopener"><span>' . htmlspecialchars($headline) . '</span><span class="teal">' . htmlspecialchars($source) . '</span></a>';
				}
				?>
			</div>
			<div>
				<h4>Widest Spreads</h4>
				<div class="item"><span>LINK/USDT · OKX → Kraken</span><span class="teal">0.031%</span></div>
				<div class="item"><span>ETH/USDT · Gate → OKX</span><span class="teal">0.019%</span></div>
				<div class="item"><span>BTC/USDT · Kraken → Gate</span><span class="teal">0.028%</span></div>
			</div>
			<div>
				<h4>Movers 24H</h4>
				<div class="item"><span>ETH/USDT</span><span class="up">+0.72%</span></div>
				<div class="item"><span>BTC/USDT</span><span class="up">+0.56%</span></div>
				<div class="item"><span>ADA/USDT</span><span class="down">-1.22%</span></div>
			</div>
		</div>
	</div>

	<div class="qs-ov-split">
		<div class="qs-ov-card">
			<div class="qs-ov-card__head"><h3>Wallet Architecture</h3></div>
			<div class="qs-ov-arch">
				<div class="qs-ov-you"><i class="fe fe-user"></i></div>
				<div class="qs-ov-arch-line"></div>
				<div class="qs-ov-arch-row">
					<div class="qs-ov-arch-box"><strong>Main Wallet</strong><span>Daily Payouts</span></div>
					<div class="qs-ov-arch-box"><strong>Staking Wallet</strong><span>Staking Portfolios</span></div>
					<div class="qs-ov-arch-box"><strong>Referral Wallet</strong><span>Referrals &amp; Bonuses</span></div>
				</div>
			</div>
			<ul class="qs-ov-notes">
				<li>Chat with a customer support officer instantly by clicking the messaging icon.</li>
				<li>Always confirm all wallet addresses before making any deposits.</li>
				<li>Copy your referral link correctly from the sidebar.</li>
				<li>Enable 2-factor authentication for an extra layer of security.</li>
			</ul>
		</div>
		<div class="qs-ov-card">
			<div class="qs-ov-card__head"><h3>Portfolio Summary</h3></div>
			<div class="qs-ov-summary">
				<div class="qs-ov-tile"><span>Active Count</span><strong><?php echo (int) $activeCount; ?></strong></div>
				<div class="qs-ov-tile"><span>Active Value</span><strong><?php echo $qs_money($totalinvest); ?></strong></div>
				<div class="qs-ov-tile"><span>Deposits</span><strong><?php echo $qs_money($totalinvest); ?></strong></div>
				<div class="qs-ov-tile"><span>Daily ROI</span><strong><?php echo $qs_money($toaldaily); ?></strong></div>
				<div class="qs-ov-tile"><span>Total Profit</span><strong><?php echo $qs_money($profit); ?></strong></div>
				<div class="qs-ov-tile"><span>Withdrawn</span><strong><?php echo $qs_money($withdrawnTotal); ?></strong></div>
				<div class="qs-ov-tile qs-ov-tile--half"><span>Referral Earnings</span><strong><?php echo $qs_money($referral); ?></strong></div>
				<div class="qs-ov-tile qs-ov-tile--half"><span>Team Bonus</span><strong><?php echo $qs_money($commission); ?></strong></div>
			</div>
			<div class="panel-group1" id="accordion1" style="margin-top:16px;">
				<?php foreach ($investments as $row) { ?>
					<div class="panel panel-default mb-3 overflow-hidden br-7">
						<div class="panel-heading1">
							<h4 class="panel-title1">
								<a class="accordion-toggle collapsed bg-gradient-primary"
									data-bs-toggle="collapse" data-parent="#accordion"
									href="#collapse<?php echo $row['id']; ?>"
									aria-expanded="false"><?php echo htmlspecialchars($row['name']); ?> ||
									$<?php echo $row['amount']; ?></a>
							</h4>
						</div>
						<div id="collapse<?php echo $row['id']; ?>" class="panel-collapse collapse"
							role="tabpanel" aria-expanded="false">
							<div class="panel-body">
								<table class="table center-aligned-table">
									<thead class="thead-dark">
										<tr>
											<th>Days</th>
											<th>Amount</th>
											<th>Status</th>
										</tr>
									</thead>
									<tbody>
										<div id="tbody<?php echo $row['id']; ?>">
											<tr class="tt" id="mon<?php echo $row['id']; ?>">
												<td>Monday</td>
												<td>$ <?php echo $row['daily_roi']; ?></td>
												<td><?php if (date('N') >= 1) { ?><span class="badge tag-success ">Credited</span><?php } else { ?><span class="badge tag-danger ">Pending</span><?php } ?></td>
											</tr>
											<tr class="tt" id="tues<?php echo $row['id']; ?>">
												<td>Tuesday</td>
												<td>$ <?php echo $row['daily_roi']; ?></td>
												<td><?php if (date('N') >= 2) { ?><span class="badge tag-success ">Credited</span><?php } else { ?><span class="badge tag-danger ">Pending</span><?php } ?></td>
											</tr>
											<tr class="tt" id="wed<?php echo $row['id']; ?>">
												<td>Wednesday</td>
												<td>$ <?php echo $row['daily_roi']; ?></td>
												<td><?php if (date('N') >= 3) { ?><span class="badge tag-success ">Credited</span><?php } else { ?><span class="badge tag-danger ">Pending</span><?php } ?></td>
											</tr>
											<tr class="tt" id="thur<?php echo $row['id']; ?>">
												<td>Thursday</td>
												<td>$ <?php echo $row['daily_roi']; ?></td>
												<td><?php if (date('N') >= 4) { ?><span class="badge tag-success ">Credited</span><?php } else { ?><span class="badge tag-danger ">Pending</span><?php } ?></td>
											</tr>
											<tr class="tt" id="fri<?php echo $row['id']; ?>">
												<td>Friday</td>
												<td>$ <?php echo $row['daily_roi']; ?></td>
												<td><?php if (date('N') >= 5) { ?><span class="badge tag-success ">Credited</span><?php } else { ?><span class="badge tag-danger ">Pending</span><?php } ?></td>
											</tr>
										</div>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>

	<div class="qs-ov-card qs-ov-table">
		<div class="qs-ov-card__head">
			<h3>Latest Transactions</h3>
			<a class="qs-ov-link" href="transactions">View all activities ↗</a>
		</div>
		<div class="table-responsive">
			<table class="table text-nowrap mb-0" id="example1">
				<thead>
					<tr>
						<th>#</th>
						<th>Title</th>
						<th>Date</th>
						<th>Amount</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$getnote2 = mysqli_query($mysqli, "SELECT * FROM activity where userid='" . $rows['id'] . "' ORDER BY id DESC LIMIT 10");
					$i = 0;
					if ($getnote2) {
						while ($rr = mysqli_fetch_assoc($getnote2)) {
							$i++;
							$type = "badge-pill bg-secondary ";
							if ($rr['status'] == "Credited" || $rr['status'] == "Confirmed" || $rr['status'] == "Approved") {
								$type = "badge-pill bg-success ";
							} elseif ($rr['status'] == "Pending" || $rr['status'] == "Pending Confirmation") {
								$type = "badge-pill bg-danger ";
							}
							?>
							<tr>
								<td class="text-center">#<?php echo $i; ?></td>
								<td><?php echo htmlspecialchars($rr['action']); ?></td>
								<td><?php echo htmlspecialchars($rr['date']); ?></td>
								<td>$<?php echo $rr['amount']; ?></td>
								<td><span class="badge b<?php echo $type; ?>"><?php echo htmlspecialchars($rr['status']); ?></span></td>
							</tr>
							<?php
						}
					}
					if ($i === 0) {
						echo '<tr><td colspan="5" class="text-center" style="color:#64748b;padding:28px;">No transactions yet.</td></tr>';
					}
					?>
				</tbody>
			</table>
		</div>
	</div>

	<p class="qs-ov-foot">Figures derive from the immutable ledger. Trading involves risk; past performance does not guarantee future results.</p>
	<div id="statistics3" hidden></div>
	<div id="Viewers2" hidden></div>
</div>

<script>
window.qsOverviewData = {
	wallet: <?php echo json_encode($wallet); ?>,
	staking: <?php echo json_encode($staking); ?>,
	referral: <?php echo json_encode($referral); ?>,
	profit: <?php echo json_encode($profit); ?>,
	hasPerf: <?php echo ($profit > 0 || $activeCount > 0) ? 'true' : 'false'; ?>
};
</script>
