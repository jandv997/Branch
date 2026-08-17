<?php
session_start();

include('connection.php');


//check if session id is set if it is redirect to login
if (!isset($_SESSION['id'])) {

	header("location:index");
} else {

	$get_user = mysqli_query($mysqli, "SELECT * FROM users WHERE id='" . $_SESSION['id'] . "' ");
	$rows = mysqli_fetch_assoc($get_user);
	if (isset($_SESSION['2fa'])) {

		if (($_SESSION['2fa'] == "no" or $_SESSION['2fa'] == "pending") and $rows['2fa'] == 1) {
			header("location:index");
		}


	}


}


// =========================

// FETCH USER MEMBERSHIP

// =========================

$stmt = $mysqli->prepare("SELECT membership_expires FROM users WHERE id=?");

$stmt->bind_param("i", $rows['id']);

$stmt->execute();

$stmt->bind_result($membershipExpires);

$stmt->fetch();

$stmt->close();

$now = time();

$isActive = false;

if ($membershipExpires && strtotime($membershipExpires) > $now) {

	$isActive = true;

	$remaining = strtotime($membershipExpires) - $now;
	//update it to 90 days from now if it is greater than 90 days
	if ($remaining > 90 * 24 * 60 * 60) {
		$remaining = 90 * 24 * 60 * 60;
	}

} else {

	$remaining = 0;

}





// =========================

// HANDLE MEMBERSHIP PURCHASE

// =========================

if (isset($_POST['buy_membership'])) {

	$userId = $rows['id'];

	$amount = 50;

	$name = "Membership Plan";

	$current = $_POST['currency'];
	$orderId = "QS-" . uniqid();// "";

	$userid = $rows['id'];

	$investmentid = 'membership_plan';





	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://plisio.net/api/v1/invoices/new?source_currency=USD&source_amount=' . $amount . '&order_number=' . $orderId . '&currency=' . $current . '&email=' . $rows['email'] . '&order_name=' . urlencode($name) . '&callback_url=https://quantumscalp.io/account/payment&api_key=VspBqpgF-tmQhKUQEHffoaqLTmLhLQLnydkT2R_CC9D45O15UGsmDBYrVpYTWnTd',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET',
	));

	$response = curl_exec($curl);

	curl_close($curl);
	//echo $response;
	$response = json_decode($response);

	//var_dump($response);


	$wallet = $response->data->wallet_hash;

	$crypto = $response->data->amount;

	$qrcode = $response->data->qr_code;

	$rates = "";





	//ad transactions

	//add to activity
	$date = date("d") . " " . date("F") . " " . date("Y") . " , " . date("h") . " : " . date("i") . date("a");
	$action = "Payment for Membership plan";
	$describe = "Payment for Membership plan has been initialized for " . $rows['firstname'] . "  ";




	$add = mysqli_query($mysqli, "INSERT INTO `activity`(`userid`, `action`, `describe`, `date`, `amount`,`status`) VALUES('$userid', '$action', '$describe', '$date','$amount', 'Pending') ");





	//insert into pending table 
	$addinvestment = mysqli_query($mysqli, "INSERT INTO `pending`(userid, chargeid, wallet, investmentid, name, amount, daily_roi, payout, qrcode, crypto, currency, date) VALUES('$userid', '$orderId', '$wallet', '$investmentid', '$name', '$amount', '$daily_roi', '$payout', '$qrcode', '$crypto', '$current', '$date') ");





	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => "https://api.mailjet.com/v3.1/send",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => '{
			"SandboxMode": false,
			"Messages": [
				{
					"From": {
						"Email": "info@quantumscalp.io",
						"Name": "Quantum Scalp"
					},
					
					"To": [
						{
							"Email": "' . $rows['email'] . '",
							"Name": ""
						}
					],
					
					"Subject": "Payment for Membership Generated",
					"TextPart": "",
					"HTMLPart": " <table align=\"center\" style=\"box-sizing:border-box;margin:0;padding:0;width:100%;height:100%;word-break:break-word;background-color:#efefef\"><tbody><tr><td align=\"center\" style=\"box-sizing:border-box;margin:0 auto;padding:0;vertical-align:top\" valign=\"top\"><table><tbody><tr><td width=\"600\" style=\"box-sizing:border-box;margin:0 auto;padding:0;vertical-align:top;font-family:&quot;display:block!important;max-width:600px!important\" valign=\"top\"><table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"box-sizing:border-box;margin:0;padding:0;font-family:&quot\"><tbody style=\"font-family:&quot\"><tr style=\"height:50px;font-family:&quot\"><td style=\"box-sizing:border-box;margin:0 auto;padding:8px;text-align:center;vertical-align:top;font-family:&quot\" align=\"center\" valign=\"top\"><div style=\"font-family:&quot\"><img src=\"https://quantumscalp.io/account/img/logo.png\" width=\"120px\" alt=\"Quantum Scalp\" style=\"font-family:&quot\"></div></td></tr><tr style=\"font-family:&quot\"><td style=\"box-sizing:border-box;margin:0 auto;vertical-align:top;font-family:&quot\" valign=\"top\"><table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"font-family:&quot\"><tbody style=\"font-family:&quot\"><tr style=\"font-family:&quot\"><td style=\"box-sizing:border-box;font-size:16px;line-height:1.7;margin:0 auto;padding:0;vertical-align:top;font-family:&quot\" valign=\"top\"><div style=\"display:block;border-radius:0;padding:20px;width:500px;margin:30px auto;font-family:&quot\"><h1 style=\"text-align:center;font-size:24px;font-weight:700;font-family:sans-serif;padding:5px;margin:0;color:#000\">Reset Password</h1><p style=\"margin:0;font-size:16px;padding:5px;font-family:&quot\">Hello <a style=\"font-family:&quot\">' . $rows['firstname'] . '</a></p><p style=\"margin:0;padding:5px;font-size:16px;font-family:&quot\">Order Generated, View details below.<br><br>  <strong>Package</strong> : ' . $name . ' </p>\n<p style=\"margin: 0; font-size: 14px; line-height: 1.5; word-break: break-word; text-align: left; mso-line-height-alt: 21px; margin-top: 0; margin-bottom: 0;\"><strong>Invoice Id</strong> : ' . $orderId . ' </p>\n\n<p style=\"margin: 0; font-size: 14px; line-height: 1.5; word-break: break-word; text-align: left; mso-line-height-alt: 21px; margin-top: 0; margin-bottom: 0;\"><strong>Wallet </strong> : ' . $wallet . ' </p>\n<p style=\"margin: 0; font-size: 14px; line-height: 1.5; word-break: break-word; text-align: left; mso-line-height-alt: 21px; margin-top: 0; margin-bottom: 0;\"><strong>Date </strong> : ' . $date . ' </p> <b style=\"font-family:&quot\"></b></p><div style=\"display:block;font-family:&quot\"><div align=\"center\" style=\"margin:0 20px;font-family:&quot\"><a href=\"https://quantumscalp.io/account/\" style=\"width:270px;border-radius:4px;box-sizing:border-box;display:block;font-weight:300;line-height:2;margin-top:10px;padding:10px 15px;text-align:center;text-decoration:none;font-family:&quot;background-color:#000;color:#fff\" target=\"_blank\">Sign In</a></div></div><p style=\"font-size:14px;padding:5px;text-align:left;font-family:&quot\"><b style=\"font-family:&quot\">Thanks ,</b><br>Quantum Scalp Team</p></div></td></tr><tr style=\"margin:20px 0;font-family:&quot\"><td style=\"box-sizing:border-box;padding:0;vertical-align:top;font-family:&quot\" valign=\"top\"><p style=\"font-size:10px;padding:20px;text-align:center;font-family:&quot\"></p></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table><img src=\"\" style=\"width:1px;height:1px\" alt=\"\"><div style=\"text-align:center;padding-top:10px;padding-bottom:10px;font-size:8pt;font-family:sans-serif;background-color:#fff\"><a href=\"\" style=\"text-align:center;text-decoration:none;font-family:sans-serif;color:#666\" target=\"_blank\">UNSUBSCRIBE</a></div>",
				
					"TemplateLanguage": true,
				
					"TrackOpens": "account_default",
					"TrackClicks": "account_default"
					
				}
			]
		}',
		CURLOPT_HTTPHEADER => array(
			"Content-Type: application/json",
			"Authorization: Basic NjIwMjNlMDUxZDlhNzMzNzU4MGY1NWU5OGZiMjczM2E6MzRmZmNjZjgxZDhmMDFjNDcwNzE1NjMwYzMyODhiZjE="
		),
	));

	$response = curl_exec($curl);

	curl_close($curl);









	if ($addinvestment) {
		//redrirect to payment page


		?>
		<script>
			location = "fund?currency=<?php echo $current; ?>&orderid=<?php echo $orderId; ?>&name=<?php echo $name; ?>"
		</script>

		<?php


	}



	exit;

}









$curl = curl_init();

curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://plisio.net/api/v1/currencies?api_key=VspBqpgF-tmQhKUQEHffoaqLTmLhLQLnydkT2R_CC9D45O15UGsmDBYrVpYTWnTd',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'GET',
));

$response = curl_exec($curl);

curl_close($curl);

$response = json_decode($response);

$data = $response->data;
if (!is_array($data)) {
	$data = array();
}

$qsReceipts = array();
$qsUid = isset($rows['id']) ? (int) $rows['id'] : 0;
if ($qsUid > 0) {
	$qsInv = mysqli_query($mysqli, "SELECT * FROM investment WHERE userid='$qsUid' ORDER BY id DESC LIMIT 25");
	if ($qsInv) {
		while ($qsRow = mysqli_fetch_assoc($qsInv)) {
			$qsKind = (isset($qsRow['investmentid']) && $qsRow['investmentid'] === 'membership_plan') ? 'License' : 'Portfolio';
			$qsReceipts[] = array(
				'title' => $qsRow['name'] !== '' && $qsRow['name'] !== null ? $qsRow['name'] : 'Quantum Core',
				'ref' => 'QS-P-A' . str_pad((string) $qsRow['id'], 7, '0', STR_PAD_LEFT),
				'date' => isset($qsRow['date']) ? $qsRow['date'] : '',
				'kind' => $qsKind,
				'amount' => (float) $qsRow['amount'],
				'status' => 'COMPLETED',
			);
		}
	}
	$qsPend = mysqli_query($mysqli, "SELECT * FROM pending WHERE userid='$qsUid' ORDER BY id DESC LIMIT 15");
	if ($qsPend) {
		while ($qsRow = mysqli_fetch_assoc($qsPend)) {
			$qsRef = isset($qsRow['chargeid']) && $qsRow['chargeid'] !== '' ? $qsRow['chargeid'] : ('QS-P-A' . str_pad((string) $qsRow['id'], 7, '0', STR_PAD_LEFT));
			$qsKind = (isset($qsRow['investmentid']) && $qsRow['investmentid'] === 'membership_plan') ? 'License' : 'Portfolio';
			$qsReceipts[] = array(
				'title' => $qsRow['name'] !== '' && $qsRow['name'] !== null ? $qsRow['name'] : 'Quantum Core',
				'ref' => $qsRef,
				'date' => isset($qsRow['date']) ? $qsRow['date'] : '',
				'kind' => $qsKind,
				'amount' => (float) $qsRow['amount'],
				'status' => (isset($qsRow['txn_hash']) && $qsRow['txn_hash'] !== '') ? 'COMPLETED' : 'PENDING',
			);
		}
	}
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Membership | Quantum Scalp </title>
	<link rel="icon" href="assets/img/brand/favicon.png" type="image/x-icon" />
	<link href="assets/css/icons.css" rel="stylesheet">
	<link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="assets/css/style.css" rel="stylesheet">
	<link href="assets/css/style-dark.css" rel="stylesheet">
	<link href="assets/css/style-transparent.css" rel="stylesheet">
	<link href="assets/css/skin-modes.css" rel="stylesheet" />
	<link href="assets/css/animate.css" rel="stylesheet">
	<link href="assets/css/qs-membership.css" rel="stylesheet">
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
				<div class="qs-mem">
					<section class="qs-mem-status">
						<div class="qs-mem-status__top">
							<h2>Membership Status</h2>
							<?php if ($isActive): ?>
								<span class="qs-mem-pill is-active">ACTIVE</span>
							<?php else: ?>
								<span class="qs-mem-pill is-watch">MONITORING</span>
							<?php endif; ?>
						</div>
						<?php if ($isActive): ?>
							<p>Your membership license is active and unlocks Q-Core platform access.</p>
							<div class="qs-mem-timer" id="countdown">Loading timer...</div>
						<?php else: ?>
							<p>No active membership. A membership license unlocks Q-Core platform access.</p>
						<?php endif; ?>
					</section>

					<h2 class="qs-mem-h">Plans</h2>
					<div class="qs-mem-plans">
						<article class="qs-mem-plan">
							<h3>Q-Core License</h3>
							<div class="qs-mem-price">$50 <span>/ 3 Months</span></div>
							<ul class="qs-mem-features">
								<li>Q-Core software access</li>
								<li>Trading technology access</li>
								<li>Quantum Verse access</li>
								<li>Quantum Flex access</li>
							</ul>
							<form method="POST" id="membershipForm">
								<div class="form-group">
									<label>Select currency</label>
									<select name="currency" id="currency" class="currency-select" required>
										<option value="">— Choose currency —</option>
										<?php
										for ($i = 0; $i < count($data); $i++) {
											$pick = '';
											if (isset($_GET['currency']) and $_GET['currency'] == $data[$i]->currency) {
												$pick = 'selected';
											}

											//if($data[$i]->currency !='USDC' && $data[$i]->currency != "USDC_BSC" && $data[$i]->currency != "USDC_BASE"){

											echo "<option " . $pick . " value=" . $data[$i]->currency . ">" . strtoupper($data[$i]->name) . "</option>";
												//}
										}
										?>
									</select>
								</div>
								<button class="qs-mem-btn" name="buy_membership" type="submit">
									<?php echo $isActive ? "Renew Membership" : "Become a Member"; ?>
								</button>
								<p class="qs-mem-note">Secure payment · instant access</p>
							</form>
						</article>

						<article class="qs-mem-plan">
							<h3>Q-Core Pro</h3>
							<div class="qs-mem-price">$120 <span>/ 6 Months</span></div>
							<ul class="qs-mem-features">
								<li>Everything in Q-Core License</li>
								<li>Priority technical support</li>
								<li>Early access to new tools</li>
								<li>Exclusive member resources</li>
							</ul>
							<button class="qs-mem-btn" type="button" onclick="var f=document.getElementById('membershipForm'); if(f){ f.scrollIntoView({behavior:'smooth',block:'center'}); var c=document.getElementById('currency'); if(c) c.focus(); }">Become a Member</button>
						</article>

						<article class="qs-mem-plan">
							<h3>Q-Core Enterprise</h3>
							<div class="qs-mem-price">Custom</div>
							<ul class="qs-mem-features">
								<li>Everything in Q-Core Pro</li>
								<li>Dedicated onboarding</li>
								<li>API access</li>
								<li>Priority technical support</li>
							</ul>
							<a class="qs-mem-btn" href="javascript:void(0);" onclick="if (window.LiveChatWidget) { LiveChatWidget.call('maximize'); }">Contact Sales</a>
						</article>
					</div>

					<section class="qs-mem-receipts">
						<div class="qs-mem-receipts__head">
							<h2>Receipts</h2>
							<span class="qs-mem-receipts__count"><?php echo count($qsReceipts); ?> RECORD<?php echo count($qsReceipts) === 1 ? '' : 'S'; ?></span>
						</div>
						<?php if (count($qsReceipts) === 0): ?>
							<p class="qs-mem-empty">No receipts yet.</p>
						<?php else: ?>
							<?php foreach ($qsReceipts as $qsRec): ?>
								<div class="qs-mem-row">
									<div class="qs-mem-row__icon" aria-hidden="true">
										<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="12" cy="12" r="5.2"/><ellipse cx="12" cy="12" rx="11" ry="3.6" transform="rotate(-22 12 12)"/></svg>
									</div>
									<div class="qs-mem-row__main">
										<strong><?php echo htmlspecialchars($qsRec['title']); ?></strong>
										<span><?php echo htmlspecialchars($qsRec['ref']); ?> · <?php echo htmlspecialchars($qsRec['date']); ?> · <?php echo htmlspecialchars($qsRec['kind']); ?></span>
									</div>
									<div class="qs-mem-row__amt">
										<strong>$<?php echo number_format((float) $qsRec['amount'], 2); ?></strong>
										<em class="<?php echo $qsRec['status'] === 'COMPLETED' ? 'is-done' : 'is-wait'; ?>"><?php echo htmlspecialchars($qsRec['status']); ?></em>
									</div>
								</div>
							<?php endforeach; ?>
						<?php endif; ?>
					</section>

					<p class="qs-mem-disclaimer">License fees provide access to software and services and do not represent an investment or guaranteed return.</p>
				</div>

				<div class="toast-modern" id="toast">
					<i class="fas fa-bell"></i> <span id="toastMsg"></span>
				</div>

				<script>
					<?php if ($isActive): ?>
						let remaining = <?php echo $remaining; ?>;

						function updateTimer() {
							let d = Math.floor(remaining / 86400);
							let h = Math.floor((remaining % 86400) / 3600);
							let m = Math.floor((remaining % 3600) / 60);
							let s = remaining % 60;
							let timerElement = document.getElementById("countdown");
							if (timerElement) {
								timerElement.innerHTML = `Expires in: ${d}d ${h}h ${m}m ${s}s`;
							}
							if (remaining <= 0) {
								location.reload();
							}
							remaining--;
						}
						setInterval(updateTimer, 1000);
						updateTimer();
					<?php endif; ?>

					function checkMembership() {
						let isActive = <?php echo $isActive ? 'true' : 'false'; ?>;
						if (!isActive) {
							showToast("⚠️ You do not have a valid membership. Please purchase to access protected features.");
							return false;
						}
						return true;
					}

					function showToast(msg) {
						let toastEl = document.getElementById("toast");
						let msgSpan = document.getElementById("toastMsg");
						if (msgSpan) msgSpan.innerText = msg;
						if (toastEl) {
							toastEl.style.display = "flex";
							setTimeout(() => {
								toastEl.style.opacity = "1";
							}, 10);
							setTimeout(() => {
								toastEl.style.display = "none";
							}, 3500);
						}
					}

					window.showToast = showToast;
				</script>
			</div>
		</div>

		<div class="main-footer">
			<div class="container-fluid pt-0 ht-100p">
				Copyright © <?php echo date('Y'); ?> All rights reserved
			</div>
		</div>
	</div>

	<a href="#top" id="back-to-top"><i class="las la-arrow-up"></i></a>

<!-- JQuery min js -->
	<script src="assets/plugins/jquery/jquery.min.js"></script>

	<!-- Bootstrap js -->
	<script src="assets/plugins/bootstrap/js/popper.min.js"></script>
	<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

	<!-- Moment js -->
	<script src="assets/plugins/moment/moment.js"></script>

	<!-- P-scroll js -->
	<script src="assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
	<script src="assets/plugins/perfect-scrollbar/p-scroll.js"></script>

	<!-- Internal Data tables -->
	<script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
	<script src="assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>
	<script src="assets/plugins/datatable/js/dataTables.buttons.min.js"></script>
	<script src="assets/plugins/datatable/js/buttons.bootstrap5.min.js"></script>
	<script src="assets/plugins/datatable/js/jszip.min.js"></script>
	<script src="assets/plugins/datatable/pdfmake/pdfmake.min.js"></script>
	<script src="assets/plugins/datatable/pdfmake/vfs_fonts.js"></script>
	<script src="assets/plugins/datatable/js/buttons.html5.min.js"></script>
	<script src="assets/plugins/datatable/js/buttons.print.min.js"></script>
	<script src="assets/plugins/datatable/js/buttons.colVis.min.js"></script>
	<script src="assets/plugins/datatable/dataTables.responsive.min.js"></script>
	<script src="assets/plugins/datatable/responsive.bootstrap5.min.js"></script>
	<script src="assets/js/table-data.js"></script>

	<!-- INTERNAL Select2 js -->
	<script src="assets/plugins/select2/js/select2.full.min.js"></script>

	<!-- Sidebar js -->
	<script src="assets/plugins/side-menu/sidemenu.js"></script>

	<!-- Sticky js -->
	<script src="assets/js/sticky.js"></script>

	<!-- Right-sidebar js -->
	<script src="assets/plugins/sidebar/sidebar.js"></script>
	<script src="assets/plugins/sidebar/sidebar-custom.js"></script>

	<!-- eva-icons js -->
	<script src="assets/js/eva-icons.min.js"></script>

	<!-- Theme Color js -->
	<script src="assets/js/themecolor.js"></script>

	<!-- SweetAlert -->

	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<!-- custom js -->
	<script src="assets/js/custom.js"></script>


</body>

</html>