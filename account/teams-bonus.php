<?php
session_start();

include('connection.php');

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

if (isset($_POST['lupa-flex'])) {
	$userid = $rows['id'];
	mysqli_query($mysqli, "UPDATE `users` SET `lupa_flex`='1', lupa_flex_date=now()  WHERE id='$userid'");
	header("location: teams-bonus");
	exit;
}

$get_users = mysqli_query($mysqli, "SELECT * FROM users WHERE referred='" . $rows['referal_link'] . "' ORDER BY id DESC");

$progressSummary = null;
$progressQuery = mysqli_query($mysqli, "SELECT * FROM user_progress_summary WHERE user_id='" . $rows['id'] . "'");
if ($progressQuery && mysqli_num_rows($progressQuery) > 0) {
	$progressSummary = mysqli_fetch_assoc($progressQuery);
}

$commission = 0;
function calculateCommission($mysqli, $referralLink, $level, $maxLevels, &$commission) {
	if ($level > $maxLevels) {
		return;
	}
	$getRefer = mysqli_query($mysqli, "SELECT * FROM users WHERE referred='$referralLink'");
	while ($refer = mysqli_fetch_assoc($getRefer)) {
		$getInvestment = mysqli_query($mysqli, "SELECT * FROM investment WHERE userid='{$refer['id']}' AND bonus='0' ORDER BY id DESC");
		while ($in = mysqli_fetch_assoc($getInvestment)) {
			$commission += $in['amount'];
		}
		calculateCommission($mysqli, $refer['referal_link'], $level + 1, $maxLevels, $commission);
	}
}
$maxLevels = 5;
calculateCommission($mysqli, $rows['referal_link'], 1, $maxLevels, $commission);

$bars = [
	["amount" => 3500, "level1" => 1000, "bonus" => 200, "name" => "Beginner", "desc" => "1000 being from level 1 <br/>One time payment of 200"],
	["amount" => 8000, "level1" => 2500, "bonus" => 500, "name" => "Promoter", "desc" => "2,500 being from level 1 <br/>One time payment of 500"],
	["amount" => 15000, "level1" => 4500, "bonus" => 800, "name" => "Elite", "desc" => "4,500 being from level 1 <br/> One time payment of 800"],
	["amount" => 35000, "level1" => 10000, "bonus" => 1750, "name" => "Leader", "desc" => "10,000 being from level 1 <br/>One time payment of 1,750 <br/>lifetime weekly payment 70"],
	["amount" => 70000, "level1" => 20000, "bonus" => 3500, "name" => "Mentor", "desc" => "20,000 being from level 1 <br/>One time payment of 3,500 <br/>lifetime weekly payment 150"],
	["amount" => 150000, "level1" => 50000, "bonus" => 7500, "name" => "Director", "desc" => "50,000 being from level 1 <br/>One time payment of 7,500 <br/>lifetime weekly payment 350"],
	["amount" => 250000, "level1" => 100000, "bonus" => 15000, "name" => "Ambassador", "desc" => "100,000 being from level 1 <br/>One time payment of 15,000 <br/>lifetime weekly payment 550"],
	["amount" => 500000, "level1" => 200000, "bonus" => 25000, "name" => "Master", "desc" => "200,000 being from level 1 <br/>One time payment of 25,000 <br/>lifetime weekly payment 1000"],
	["amount" => 1000000, "level1" => 300000, "bonus" => 50000, "name" => "Executive", "desc" => "300,000 being from level 1 <br/>One time payment of 50,000 <br/>lifetime weekly payment 1750"],
	["amount" => 2000000, "level1" => 500000, "bonus" => 150000, "name" => "Visionary", "desc" => "500,000 being from level 1 <br/>One time payment 150,000 <br/>Lifetime daily payment 3,000"],
	["amount" => 5000000, "level1" => 750000, "bonus" => 300000, "name" => "Legend", "desc" => "750,000 being from level 1 <br/>One time payment 300,000 <br/>Lifetime daily payment 6,000"],
	["amount" => 12000000, "level1" => 1000000, "bonus" => 700000, "name" => "Director X", "desc" => "1,000,000 being from level 1 <br/>One time payment 700,000 <br/>Lifetime daily payment 10,000"],
];

$currentLevelIndex = $progressSummary ? intval($progressSummary['current_level']) : 0;
if ($currentLevelIndex < 0) {
	$currentLevelIndex = 0;
}
if ($currentLevelIndex > count($bars) - 1) {
	$currentLevelIndex = count($bars) - 1;
}
$currentLevelHuman = $progressSummary ? intval($progressSummary['current_level_human']) : 1;
$currentRankName = isset($bars[$currentLevelIndex]['name']) ? $bars[$currentLevelIndex]['name'] : ($progressSummary ? $progressSummary['current_level_human'] : 'Beginner');
$progressPct = $progressSummary ? (float) $progressSummary['progress_percentage'] : 0;
$nextLevelLabel = ($progressSummary && !empty($progressSummary['next_level_name'])) ? $progressSummary['next_level_name'] : 'Max rank';
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
					<div class="qs-flex-stats">
						<div class="qs-flex-stat">
							<span>Fast Start Bonus</span>
							<strong>$<?php echo number_format((float) $rows['fast_start'], 2); ?></strong>
							<em><?php echo (int) mysqli_num_rows($get_users); ?> active referrals</em>
						</div>
						<div class="qs-flex-stat">
							<span>Team Bonus</span>
							<strong>$<?php echo number_format((float) $rows['team_bonus'], 2); ?></strong>
							<em>Downline volume $<?php echo number_format($commission, 2); ?></em>
						</div>
						<div class="qs-flex-stat">
							<span>Direct Growth Bonus</span>
							<strong>$<?php echo number_format((float) $rows['direct_growth'], 2); ?></strong>
							<em>Current rank <?php echo htmlspecialchars((string) $currentRankName); ?></em>
						</div>
						<div class="qs-flex-stat">
							<span>Progress to next rank</span>
							<strong><?php echo number_format($progressPct, 0); ?>%</strong>
							<em><?php echo htmlspecialchars((string) $nextLevelLabel); ?><?php if ($progressSummary) { ?> · $<?php echo number_format((float) $progressSummary['amount_needed_for_next_level']); ?> needed<?php } ?></em>
						</div>
					</div>

					<div class="qs-flex-head">
						<h2>12 Ranks</h2>
						<p>Expand any rank for qualification requirements, rewards and benefits.</p>
					</div>

					<div class="qs-flex-ranks">
						<?php
						for ($i = 0; $i < count($bars); $i++):
							$humanLevel = $i + 1;
							$isAchieved = ($i < $currentLevelIndex);
							$isCurrent = ($i === $currentLevelIndex);
							$isLocked = ($i > $currentLevelIndex);
							$levelProgress = 0;
							$levelAmount = $bars[$i]['amount'];
							if ($isAchieved) {
								$levelProgress = 100;
							} elseif ($isCurrent && $progressSummary && $levelAmount > 0) {
								$levelProgress = min(100, ((float) $progressSummary['current_level_commission'] / $levelAmount) * 100);
							} elseif ($isCurrent) {
								$levelProgress = min(100, $progressPct);
							}
							$descLines = explode('<br/>', $bars[$i]['desc']);
							$cardClass = 'qs-flex-rank';
							if ($isCurrent) {
								$cardClass .= ' is-current is-open';
							} elseif ($isAchieved) {
								$cardClass .= ' is-achieved';
							}
						?>
						<article class="<?php echo $cardClass; ?>" data-qs-flex-rank>
							<button type="button" class="qs-flex-rank__toggle" data-qs-flex-toggle>
								<span class="qs-flex-rank__icon" aria-hidden="true">
									<?php if ($isLocked): ?>
										<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/></svg>
									<?php else: ?>
										<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="3.2"/><path d="M5 19c1.2-3.2 3.8-5 7-5s5.8 1.8 7 5"/></svg>
									<?php endif; ?>
								</span>
								<span class="qs-flex-rank__meta">
									<span class="qs-flex-rank__kicker">Rank <?php echo $humanLevel; ?></span>
									<span class="qs-flex-rank__name"><?php echo htmlspecialchars($bars[$i]['name']); ?></span>
								</span>
								<?php if ($isCurrent): ?>
									<span class="qs-flex-badge is-current">CURRENT</span>
								<?php elseif ($isAchieved): ?>
									<span class="qs-flex-badge is-done">ACHIEVED</span>
								<?php endif; ?>
								<span class="qs-flex-chevron" aria-hidden="true">
									<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
								</span>
							</button>
							<div class="qs-flex-rank__body">
								<div class="qs-flex-reqs">
									<div class="qs-flex-req">
										<span>Team sales req.</span>
										<strong>$<?php echo number_format((float) $bars[$i]['amount'], 2); ?></strong>
										<?php if ($isCurrent && $progressSummary): ?>
											<small>$<?php echo number_format((float) $progressSummary['current_level_commission'], 2); ?> current</small>
										<?php endif; ?>
									</div>
									<div class="qs-flex-req">
										<span>Personal sales req.</span>
										<strong>$<?php echo number_format((float) $bars[$i]['level1'], 2); ?></strong>
										<?php if ($isCurrent && $progressSummary): ?>
											<small>$<?php echo number_format((float) $progressSummary['level1_total_contribution'], 2); ?> level 1</small>
										<?php endif; ?>
									</div>
								</div>
								<div class="qs-flex-rewards">Rewards: One-time $<?php echo number_format((float) $bars[$i]['bonus'], 2); ?></div>
								<ul class="qs-flex-benefits">
									<?php foreach ($descLines as $line): if (trim($line) !== ''): ?>
									<li>
										<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
										<span><?php echo htmlspecialchars(trim($line)); ?></span>
									</li>
									<?php endif; endforeach; ?>
								</ul>
								<?php if ($isCurrent && $progressSummary && (int) $progressSummary['blocked_by_level1'] === 1): ?>
									<div class="qs-flex-warn">Blocked: need $<?php echo number_format((float) $progressSummary['next_level_level1_required']); ?> level 1 contribution (current $<?php echo number_format((float) $progressSummary['level1_total_contribution']); ?>).</div>
								<?php endif; ?>
								<div class="qs-flex-progress">
									<span>
										Progress to next rank
										<b><?php echo number_format($levelProgress, 0); ?>%</b>
									</span>
									<div class="qs-flex-bar" aria-hidden="true"><i style="width: <?php echo max(0, min(100, $levelProgress)); ?>%"></i></div>
								</div>
							</div>
						</article>
						<?php endfor; ?>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade qs-flex-modal" id="welcome" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="with" aria-hidden="true">
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
	<script src="assets/plugins/moment/moment.js"></script>
	<script src="assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
	<script src="assets/plugins/perfect-scrollbar/p-scroll.js"></script>
	<script src="assets/plugins/select2/js/select2.full.min.js"></script>
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
			toggle.addEventListener('click', function () {
				card.classList.toggle('is-open');
			});
		});
		<?php if ($rows['lupa_flex'] == 0) { ?>
		$(document).ready(function () {
			$("#welcome").modal('show');
		});
		<?php } ?>
	</script>
</body>
</html>
