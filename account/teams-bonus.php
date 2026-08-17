<?php
session_start();

include('connection.php');
include_once('inc/member-status.php');
include_once('inc/flex.php');

if (!isset($_SESSION['id'])) {
	header("location:index");
	exit;
}

$get_user = mysqli_query($mysqli, "SELECT * FROM users WHERE id='" . $_SESSION['id'] . "' ");
$rows = mysqli_fetch_assoc($get_user);
if (isset($_SESSION['2fa'])) {
	if (($_SESSION['2fa'] == "no" or $_SESSION['2fa'] == "pending") and $rows['2fa'] == 1) {
		header("location:index");
		exit;
	}
}

qs_flex_ensure_points_column($mysqli);
$get_user = mysqli_query($mysqli, "SELECT * FROM users WHERE id='" . $_SESSION['id'] . "' ");
$rows = mysqli_fetch_assoc($get_user);

if (isset($_POST['lupa-flex'])) {
	$userid = $rows['id'];
	mysqli_query($mysqli, "UPDATE `users` SET `lupa_flex`='1', lupa_flex_date=now()  WHERE id='$userid'");
	header("location: teams-bonus");
	exit;
}

$ranks = qs_flex_ranks();
$pointRewards = qs_flex_point_rewards();
$teamVolume = qs_flex_team_volume($mysqli, $rows['referal_link'], 7);
$orgLevels = qs_flex_org_levels($mysqli, $rows['referal_link'], 7);
$pointsEarned = (int) floor($teamVolume);
$pointsRedeemed = isset($rows['quantum_points_redeemed']) ? (int) $rows['quantum_points_redeemed'] : 0;
$pointsAvailable = max(0, $pointsEarned - $pointsRedeemed);

if (isset($_POST['redeem-points'])) {
	$key = isset($_POST['reward']) ? $_POST['reward'] : '';
	if (!isset($pointRewards[$key])) {
		header("location: teams-bonus");
		exit;
	}
	$reward = $pointRewards[$key];
	if ($pointsAvailable < $reward['cost']) {
		header("location: teams-bonus?points=short");
		exit;
	}
	$userid = (int) $rows['id'];
	$cost = (int) $reward['cost'];
	$credit = (float) $reward['credit'];
	mysqli_query($mysqli, "UPDATE users SET quantum_points_redeemed = quantum_points_redeemed + $cost" . ($credit > 0 ? ", wallet = wallet + $credit" : "") . " WHERE id='$userid'");
	$date = date('Y-m-d H:i:s');
	$action = 'Quantum Points Redeemed';
	$describe = mysqli_real_escape_string($mysqli, $reward['title'] . ' for ' . $cost . ' points');
	mysqli_query($mysqli, "INSERT INTO activity(userid, action, `describe`, date, amount, status) VALUES('$userid', '$action', '$describe', '$date', '$credit', 'Credited')");
	header("location: teams-bonus?points=ok");
	exit;
}

$progressSummary = null;
$progressQuery = mysqli_query($mysqli, "SELECT * FROM user_progress_summary WHERE user_id='" . $rows['id'] . "'");
if ($progressQuery && mysqli_num_rows($progressQuery) > 0) {
	$progressSummary = mysqli_fetch_assoc($progressQuery);
}

$currentLevelIndex = $progressSummary ? intval($progressSummary['current_level']) : 0;
if ($currentLevelIndex < 0) {
	$currentLevelIndex = 0;
}
if ($currentLevelIndex > count($ranks) - 1) {
	$currentLevelIndex = count($ranks) - 1;
}

$currentRank = $ranks[$currentLevelIndex];
$nextIndex = $currentLevelIndex + 1;
$hasNext = $nextIndex < count($ranks);
$nextRank = $hasNext ? $ranks[$nextIndex] : null;
$progressPct = $progressSummary ? (float) $progressSummary['progress_percentage'] : 0;
$teamToGo = $hasNext ? max(0, $nextRank['amount'] - $teamVolume) : 0;
$personalSales = $progressSummary ? (float) $progressSummary['level1_total_contribution'] : 0;
$personalToGo = $hasNext ? max(0, $nextRank['level1'] - $personalSales) : 0;
if ($hasNext && $nextRank['amount'] > 0) {
	$progressPct = min(100, ($teamVolume / $nextRank['amount']) * 100);
}
$rankBonuses = (float) $rows['fast_start'] + (float) $rows['team_bonus'] + (float) $rows['direct_growth'];
$commissionsEarned = (float) $rows['ref_wallet'];
$pointsNotice = '';
if (isset($_GET['points']) && $_GET['points'] === 'ok') {
	$pointsNotice = 'Reward redeemed.';
} elseif (isset($_GET['points']) && $_GET['points'] === 'short') {
	$pointsNotice = 'Not enough Quantum Points for that reward.';
}

function qs_flex_icon_lock() {
	return '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/></svg>';
}
function qs_flex_icon_user() {
	return '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="3.2"/><path d="M5 19c1.2-3.2 3.8-5 7-5s5.8 1.8 7 5"/></svg>';
}
function qs_flex_icon_check() {
	return '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>';
}
function qs_flex_icon_chevron() {
	return '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Quantum Flex | Quantum Scalp</title>
	<link rel="icon" href="assets/img/brand/favicon.png" type="image/x-icon" />
	<link href="assets/css/icons.css" rel="stylesheet">
	<link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="assets/css/style.css" rel="stylesheet">
	<link href="assets/css/style-dark.css" rel="stylesheet">
	<link href="assets/css/style-transparent.css" rel="stylesheet">
	<link href="assets/css/skin-modes.css" rel="stylesheet" />
	<link href="assets/css/animate.css" rel="stylesheet">
	<script src="assets/plugins/jquery/jquery.min.js"></script>
</head>
<body class="ltr main-body app sidebar-mini dark-theme">
	<div id="global-loader">
		<img src="img/favicon.png" width="50" class="loader-img" alt="Loader">
	</div>
	<div class="page">
		<div>
			<?php include('header.php'); ?>
		</div>
		<div class="main-content app-content">
			<div class="main-container container-fluid">
				<div class="qs-flex">
					<?php if ($pointsNotice !== ''): ?>
						<div class="qs-flex-banner"><?php echo htmlspecialchars($pointsNotice); ?></div>
					<?php endif; ?>

					<section class="qs-flex-section">
						<h2 class="qs-flex-title">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M8 4h8l1 4H7l1-4z"/><path d="M7 8h10v2a5 5 0 0 1-10 0V8z"/><path d="M12 15v5M8 20h8"/></svg>
							Quantum Flex — Leadership
						</h2>
						<div class="qs-flex-kpis">
							<div class="qs-flex-kpi"><span>Current Rank</span><strong><?php echo htmlspecialchars($currentRank['name']); ?></strong></div>
							<div class="qs-flex-kpi"><span>Team Sales Volume</span><strong>$<?php echo number_format($teamVolume, 2); ?></strong></div>
							<div class="qs-flex-kpi"><span>Commissions Earned</span><strong>$<?php echo number_format($commissionsEarned, 2); ?></strong></div>
							<div class="qs-flex-kpi"><span>Rank Bonuses</span><strong>$<?php echo number_format($rankBonuses, 2); ?></strong></div>
						</div>
						<div class="qs-flex-lead-progress">
							<em><span><?php echo $hasNext ? htmlspecialchars($nextRank['name']) : 'Max rank'; ?></span><b><?php echo number_format($progressPct, 0); ?>%</b></em>
							<div class="qs-flex-bar"><i style="width: <?php echo max(0, min(100, $progressPct)); ?>%"></i></div>
						</div>
						<p class="qs-flex-reqline">
							<?php if ($hasNext): ?>
								Requires team sales $<?php echo number_format($nextRank['amount'], 2); ?> — personal sales $<?php echo number_format($nextRank['level1'], 2); ?>
							<?php else: ?>
								All 12 ranks unlocked.
							<?php endif; ?>
						</p>
						<div class="qs-flex-note">
							<strong>Driven by product &amp; license sales</strong>
							Rank advancement and commissions are calculated from verifiable product and license sales volume across your organization — not from simulated trading results.
						</div>
					</section>

					<section class="qs-flex-section">
						<h2 class="qs-flex-title">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3l3.2 6.4L22 10l-5 4.8L18.4 22 12 18.6 5.6 22 7 14.8 2 10l6.8-.6L12 3z"/></svg>
							Rank Ladder
						</h2>
						<div class="qs-flex-next">
							<span>Next rank: <?php echo $hasNext ? htmlspecialchars($nextRank['name']) . ' +$' . number_format($nextRank['bonus'], 2) . ' bonus' : 'Max rank'; ?></span>
							<b><?php echo number_format($progressPct, 0); ?>% qualified</b>
						</div>
						<div class="qs-flex-togo">
							<div><span>Team sales to go</span><strong>$<?php echo number_format($teamToGo, 2); ?></strong></div>
							<div><span>Personal sales to go</span><strong>$<?php echo number_format($personalToGo, 2); ?></strong></div>
						</div>
						<div class="qs-flex-ladder">
							<?php for ($i = count($ranks) - 1; $i >= 0; $i--):
								$isCurrent = ($i === $currentLevelIndex);
								$isAchieved = ($i < $currentLevelIndex);
							?>
							<div class="qs-flex-step<?php echo $isCurrent ? ' is-here' : ($isAchieved ? ' is-done' : ''); ?>">
								<span class="qs-flex-step__icon"><?php echo ($isCurrent || $isAchieved) ? qs_flex_icon_user() : qs_flex_icon_lock(); ?></span>
								<span class="qs-flex-step__main">
									<span class="qs-flex-step__name"><?php echo htmlspecialchars($ranks[$i]['name']); ?></span>
									<span class="qs-flex-step__meta">Rank <?php echo $i + 1; ?> — team $<?php echo number_format($ranks[$i]['amount'], 2); ?> — personal $<?php echo number_format($ranks[$i]['level1'], 2); ?></span>
								</span>
								<span class="qs-flex-step__bonus"><?php echo $ranks[$i]['bonus'] > 0 ? '+$' . number_format($ranks[$i]['bonus'], 2) : '—'; ?></span>
								<?php if ($isCurrent): ?><span class="qs-flex-here">YOU ARE HERE</span><?php endif; ?>
							</div>
							<?php endfor; ?>
						</div>
					</section>

					<section class="qs-flex-section">
						<h2 class="qs-flex-title">Leadership Bonuses</h2>
						<div class="qs-flex-bonus-grid">
							<div class="qs-flex-bonus-card">
								<h3>Fast Start Bonus</h3>
								<p>Rewarded on early qualifying activity from your first-level organization.</p>
								<strong>$<?php echo number_format((float) $rows['fast_start'], 2); ?></strong>
							</div>
							<div class="qs-flex-bonus-card">
								<h3>Team Volume Bonus</h3>
								<p>Scales with total verifiable volume across your downline.</p>
								<strong>$<?php echo number_format((float) $rows['team_bonus'], 2); ?></strong>
							</div>
							<div class="qs-flex-bonus-card">
								<h3>Direct Growth Bonus</h3>
								<p>Recognizes first-level expansion and personal sales contribution.</p>
								<strong>$<?php echo number_format((float) $rows['direct_growth'], 2); ?></strong>
							</div>
							<div class="qs-flex-bonus-card">
								<h3>Global Quantum Points</h3>
								<p>Points accrue from team production and can be redeemed below.</p>
								<strong><?php echo number_format($pointsEarned); ?> pts</strong>
							</div>
						</div>
					</section>

					<section class="qs-flex-section">
						<h2 class="qs-flex-title">Quantum Points</h2>
						<div class="qs-flex-points-stats">
							<div class="qs-flex-kpi"><span>Available</span><strong><?php echo number_format($pointsAvailable); ?></strong></div>
							<div class="qs-flex-kpi"><span>Earned</span><strong><?php echo number_format($pointsEarned); ?></strong></div>
							<div class="qs-flex-kpi"><span>Redeemed</span><strong><?php echo number_format($pointsRedeemed); ?></strong></div>
						</div>
						<div class="qs-flex-rewards">
							<?php foreach ($pointRewards as $key => $reward):
								$pct = $reward['cost'] > 0 ? min(100, ($pointsAvailable / $reward['cost']) * 100) : 0;
								$canRedeem = $pointsAvailable >= $reward['cost'];
							?>
							<div class="qs-flex-reward">
								<h3><?php echo htmlspecialchars($reward['title']); ?></h3>
								<p><?php echo number_format($reward['cost']); ?> pts</p>
								<div class="qs-flex-bar"><i style="width: <?php echo $pct; ?>%"></i></div>
								<form method="POST">
									<input type="hidden" name="reward" value="<?php echo htmlspecialchars($key); ?>">
									<button class="qs-flex-redeem" type="submit" name="redeem-points" <?php echo $canRedeem ? '' : 'disabled'; ?>>Redeem</button>
								</form>
							</div>
							<?php endforeach; ?>
						</div>
					</section>

					<section class="qs-flex-section">
						<h2 class="qs-flex-title">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 5v14M5 12h14"/><circle cx="12" cy="12" r="9"/></svg>
							Seven-Level Organization
						</h2>
						<div class="qs-flex-org">
							<?php for ($lvl = 1; $lvl <= 7; $lvl++):
								$stat = $orgLevels[$lvl];
								$open = ($lvl === 1) ? ' is-open' : '';
							?>
							<article class="qs-flex-lvl<?php echo $open; ?>" data-qs-flex-level>
								<button type="button" class="qs-flex-lvl__toggle" data-qs-flex-level-toggle>
									Level <?php echo $lvl; ?>
									<span><?php echo (int) $stat['members']; ?> members <?php echo qs_flex_icon_chevron(); ?></span>
								</button>
								<div class="qs-flex-lvl__body">
									<div class="qs-flex-lvl__stats">
										<div><span>Members</span><strong><?php echo (int) $stat['members']; ?></strong></div>
										<div><span>Active</span><strong><?php echo (int) $stat['active']; ?></strong></div>
										<div><span>Sales Vol</span><strong>$<?php echo number_format($stat['sales'], 2); ?></strong></div>
									</div>
									<?php if ((int) $stat['members'] === 0): ?>
										<p class="qs-flex-lvl__empty">No members at this level yet.</p>
									<?php endif; ?>
								</div>
							</article>
							<?php endfor; ?>
						</div>
					</section>

					<section class="qs-flex-section">
						<div class="qs-flex-head">
							<h2>12 Ranks</h2>
							<p>Expand any rank for qualification requirements, rewards and benefits.</p>
						</div>
						<div class="qs-flex-ranks">
							<?php
							for ($i = 0; $i < count($ranks); $i++):
								$isAchieved = ($i < $currentLevelIndex);
								$isCurrent = ($i === $currentLevelIndex);
								$isLocked = ($i > $currentLevelIndex);
								$levelProgress = 0;
								if ($isAchieved) {
									$levelProgress = 100;
								} elseif ($isCurrent) {
									$levelProgress = $progressPct;
								}
								$cardClass = 'qs-flex-rank';
								if ($isCurrent) {
									$cardClass .= ' is-current is-open';
								} elseif ($isAchieved) {
									$cardClass .= ' is-achieved';
								}
							?>
							<article class="<?php echo $cardClass; ?>" data-qs-flex-rank>
								<button type="button" class="qs-flex-rank__toggle" data-qs-flex-toggle>
									<span class="qs-flex-rank__icon"><?php echo $isLocked ? qs_flex_icon_lock() : qs_flex_icon_user(); ?></span>
									<span class="qs-flex-rank__meta">
										<span class="qs-flex-rank__kicker">Rank <?php echo $i + 1; ?></span>
										<span class="qs-flex-rank__name"><?php echo htmlspecialchars($ranks[$i]['name']); ?></span>
									</span>
									<?php if ($isCurrent): ?>
										<span class="qs-flex-badge is-current">CURRENT</span>
									<?php elseif ($isAchieved): ?>
										<span class="qs-flex-badge is-done">ACHIEVED</span>
									<?php endif; ?>
									<span class="qs-flex-chevron"><?php echo qs_flex_icon_chevron(); ?></span>
								</button>
								<div class="qs-flex-rank__body">
									<div class="qs-flex-reqs">
										<div class="qs-flex-req">
											<span>Team sales req.</span>
											<strong>$<?php echo number_format($ranks[$i]['amount'], 2); ?></strong>
										</div>
										<div class="qs-flex-req">
											<span>Personal sales req.</span>
											<strong>$<?php echo number_format($ranks[$i]['level1'], 2); ?></strong>
										</div>
									</div>
									<div class="qs-flex-award">Rewards: <?php echo htmlspecialchars($ranks[$i]['rewards']); ?></div>
									<ul class="qs-flex-benefits">
										<?php foreach ($ranks[$i]['benefits'] as $line): ?>
										<li><?php echo qs_flex_icon_check(); ?><span><?php echo htmlspecialchars($line); ?></span></li>
										<?php endforeach; ?>
									</ul>
									<?php if ($isCurrent && $progressSummary && (int) $progressSummary['blocked_by_level1'] === 1): ?>
										<div class="qs-flex-warn">Blocked: need $<?php echo number_format((float) $progressSummary['next_level_level1_required']); ?> level 1 contribution (current $<?php echo number_format((float) $progressSummary['level1_total_contribution']); ?>).</div>
									<?php endif; ?>
									<div class="qs-flex-progress">
										<span>Progress to next rank <b><?php echo number_format($levelProgress, 0); ?>%</b></span>
										<div class="qs-flex-bar"><i style="width: <?php echo max(0, min(100, $levelProgress)); ?>%"></i></div>
									</div>
								</div>
							</article>
							<?php endfor; ?>
						</div>
						<p class="qs-flex-foot">Rank qualification is calculated from verifiable product and license sales volume. Demo figures are not live trading results.</p>
					</section>
				</div>
			</div>
		</div>

		<div class="modal fade qs-flex-modal" id="welcome" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Welcome to Quantum Flex!!</h5>
					</div>
					<div class="modal-body">
						<img src="img/qflex.png" style="width:100%" alt="Quantum Flex" /><br /><br />
						<form method="POST">
							<p>Welcome to QuantumFLEX, an Epic company! Congratulations on your choice to become an Quantum FLEX promoter. With no cost to begin and no prior experience required, you have the freedom to work on your own schedule and tailor your business to your unique lifestyle and needs. <br /></p>
							<button class="btn btn-primary mt-3" name="lupa-flex" type="submit">Get Started</button>
						</form>
					</div>
				</div>
			</div>
		</div>

		<div class="main-footer">
			<div class="container-fluid pt-0 ht-100p">
				Copyright © <?php echo date('Y'); ?> All rights reserved
			</div>
		</div>
	</div>
	<a href="#top" id="back-to-top"><i class="las la-arrow-up"></i></a>
	<script src="assets/plugins/bootstrap/js/popper.min.js"></script>
	<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
	<script src="assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
	<script src="assets/plugins/perfect-scrollbar/p-scroll.js"></script>
	<script src="assets/plugins/side-menu/sidemenu.js"></script>
	<script src="assets/js/sticky.js"></script>
	<script src="assets/plugins/sidebar/sidebar.js"></script>
	<script src="assets/plugins/sidebar/sidebar-custom.js"></script>
	<script src="assets/js/eva-icons.min.js"></script>
	<script src="assets/js/themecolor.js"></script>
	<script src="assets/js/custom.js"></script>
	<script>
		document.querySelectorAll('[data-qs-flex-rank]').forEach(function (card) {
			var toggle = card.querySelector('[data-qs-flex-toggle]');
			if (!toggle) return;
			toggle.addEventListener('click', function () { card.classList.toggle('is-open'); });
		});
		document.querySelectorAll('[data-qs-flex-level]').forEach(function (card) {
			var toggle = card.querySelector('[data-qs-flex-level-toggle]');
			if (!toggle) return;
			toggle.addEventListener('click', function () { card.classList.toggle('is-open'); });
		});
		<?php if ($rows['lupa_flex'] == 0) { ?>
		$(document).ready(function () { $("#welcome").modal('show'); });
		<?php } ?>
	</script>
</body>
</html>
