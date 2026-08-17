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


$id = $_GET['id'];


$getinvests = mysqli_query($mysqli, "SELECT * FROM `investment_packages` WHERE id='$id' ");
$row = mysqli_fetch_assoc($getinvests);


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





?>
<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="UTF-8">
	<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">


	<!-- Title -->
	<title>Make Purchase | Quantum Scalp</title>

	<!-- Favicon -->
	<link rel="icon" href="assets/img/brand/favicon.png" type="image/x-icon" />

	<!-- Icons css -->
	<link href="assets/css/icons.css" rel="stylesheet">

	<!--  bootstrap css-->
	<link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

	<!--- Style css --->
	<link href="assets/css/style.css" rel="stylesheet">
	<link href="assets/css/style-dark.css" rel="stylesheet">
	<link href="assets/css/style-transparent.css" rel="stylesheet">

	<!---Skinmodes css-->
	<link href="assets/css/skin-modes.css" rel="stylesheet" />

	<!--- Animations css-->
	<link href="assets/css/animate.css" rel="stylesheet">

	<style>
		.container {
			max-width: 1400px;
			margin: 0 auto;
		}

		/* ========= HERO SECTION (modern, glassmorphic) ========= */
		.hero-modern {
			text-align: center;
			margin-bottom: 3rem;
			padding: 3rem 1.5rem;

			backdrop-filter: blur(12px);
			border-radius: 64px;
			box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.08), inset 0 1px 0 rgba(255, 255, 255, 0.8);
			border: 1px solid rgba(255, 255, 255, 0.7);
		}

		.hero-modern h1 {
			font-size: 3.2rem;
			font-weight: 800;
			background: linear-gradient(135deg, #0b2b3b, #1c6e8f, #2aa9c9);
			background-clip: text;
			-webkit-background-clip: text;
			color: transparent;
			letter-spacing: -0.02em;
			margin-bottom: 0.5rem;
		}

		.hero-modern .tagline {
			font-size: 1.2rem;
			font-weight: 500;

			max-width: 650px;
			margin: 0.75rem auto 0;
			opacity: 0.9;
		}

		/* two column layout: left (benefits + faq) , right (membership card) */
		.membership-layout {
			display: grid;
			grid-template-columns: 1fr 420px;
			gap: 2.5rem;
			align-items: start;
		}

		/* LEFT PANEL — rich content */
		.content-panel {

			border-radius: 40px;
			box-shadow: 0 25px 45px -12px rgba(0, 0, 0, 0.12);
			padding: 2rem 2rem 2.5rem;
			transition: all 0.2s ease;
			border: 1px solid rgba(156, 180, 194, 0.3);
		}

		/* membership card (right) redesigned but preserving form logic */
		.membership-card {

			border-radius: 48px;
			box-shadow: 0 30px 50px -20px rgba(0, 32, 54, 0.25);
			padding: 2rem 1.8rem;
			position: sticky;
			top: 2rem;
			border: 1px solid rgba(66, 153, 184, 0.2);
			backdrop-filter: blur(2px);
			transition: transform 0.2s;
		}

		.membership-card:hover {
			transform: translateY(-4px);
		}

		.card-badge {
			background: linear-gradient(110deg, #d6f0fa, #b6e2f0);
			padding: 0.3rem 1rem;
			border-radius: 60px;
			display: inline-block;
			font-size: 0.75rem;
			font-weight: 700;
			letter-spacing: 0.5px;
			color: #136b8c;
			margin-bottom: 1.2rem;
		}

		.plan-title {
			font-size: 2rem;
			font-weight: 800;
			margin: 0.5rem 0 0.25rem;
			color: #0a2e3f;
		}

		.price {
			font-size: 2.8rem;
			font-weight: 800;
			color: #1c6e8f;
			letter-spacing: -1px;
			margin: 0.75rem 0;
			display: flex;
			align-items: baseline;
			gap: 6px;
		}

		.price span {
			font-size: 1rem;
			font-weight: 500;
			color: #5f7f90;
		}

		.status {
			margin: 1rem 0;
		}

		.active-status,
		.inactive-status {
			font-weight: 700;
			padding: 0.6rem 0;
			border-radius: 40px;
			text-align: center;
			font-size: 1rem;
		}

		.active-status {
			background: #dff9e6;
			color: #117f4a;
			border-left: 4px solid #2bcc7a;
		}

		.inactive-status {
			background: #fff0e5;
			color: #c2410c;
			border-left: 4px solid #f97316;
		}

		.timer {
			background: #0a2f40;
			color: #b9f3ff;
			font-family: monospace;
			font-size: 1.25rem;
			font-weight: 600;
			text-align: center;
			padding: 0.7rem;
			border-radius: 60px;
			margin: 1rem 0;
			letter-spacing: 1px;
			background: linear-gradient(105deg, #0a2f40, #124f69);
			box-shadow: inset 0 1px 3px #2d7f9b, 0 5px 10px rgba(0, 0, 0, 0.05);
		}

		/* modern form group */
		.form-group {
			margin: 1.6rem 0 1.2rem;
		}

		.form-group label {
			font-weight: 600;
			font-size: 0.85rem;
			text-transform: uppercase;
			letter-spacing: 0.5px;
			color: #2c6b87;
			margin-bottom: 0.5rem;
			display: block;
		}

		.currency-select {
			width: 100%;
			padding: 1rem 1.2rem;
			font-size: 1rem;
			font-weight: 500;
			border-radius: 100px;
			border: 1.5px solid #cfdfe8;
			background: white;
			transition: all 0.2s;
			cursor: pointer;
			font-family: 'Inter', monospace;
			appearance: none;
			background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="%231c6e8f" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>');
			background-repeat: no-repeat;
			background-position: right 1.2rem center;
		}

		.currency-select:focus {
			outline: none;
			border-color: #2aa9c9;
			box-shadow: 0 0 0 3px rgba(42, 169, 201, 0.2);
		}

		.btn-membership {
			width: 100%;
			background: linear-gradient(100deg, #136b8c, #1f8aad);
			border: none;
			padding: 1rem;
			font-weight: 700;
			font-size: 1.1rem;
			color: white;
			border-radius: 100px;
			cursor: pointer;
			transition: all 0.3s ease;
			margin-top: 0.5rem;
			box-shadow: 0 8px 18px rgba(19, 107, 140, 0.25);
			display: flex;
			align-items: center;
			justify-content: center;
			gap: 12px;
		}

		.btn-membership:hover {
			background: linear-gradient(100deg, #0e5875, #117a9c);
			transform: scale(0.98);
			box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
		}

		/* benefits & FAQ styles */
		.section-title {
			font-size: 1.8rem;
			font-weight: 700;
			margin: 1.2rem 0 1rem 0;
			letter-spacing: -0.3px;
			background: linear-gradient(135deg, #1b5a74, #2290b3);
			background-clip: text;
			-webkit-background-clip: text;
			color: transparent;
			display: inline-block;
		}

		.benefits-grid {
			display: grid;
			grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
			gap: 1.2rem;
			margin: 1.5rem 0 2rem;
		}

		.benefit-item {

			padding: 1rem 1.2rem;
			border-radius: 28px;
			display: flex;
			align-items: center;
			gap: 1rem;
			transition: all 0.2s;
			border: 1px solid #e5f0f5;
		}

		.benefit-item i {
			font-size: 2rem;

			min-width: 40px;
			text-align: center;
		}

		.benefit-text strong {
			display: block;
			font-weight: 800;
			margin-bottom: 0.2rem;
		}

		.benefit-text p {
			font-size: 0.85rem;
			color: #395f72;
			line-height: 1.3;
		}

		.howitworks,
		.future-plans,
		.faq-section {
			margin-top: 2rem;
			border-top: 1px solid #e2edf2;
			padding-top: 1.5rem;
		}

		.howitworks p,
		.future-plans p {
			margin: 0.8rem 0;
			line-height: 1.5;

		}

		.cta-quote {

			padding: 1.2rem 1.8rem;
			border-radius: 32px;
			margin: 1.8rem 0;
			text-align: center;
			font-weight: 600;
			border-left: 5px solid #27b0cf;
			border-right: 5px solid #27b0cf;
		}

		.faq-item {
			margin-bottom: 1.25rem;
		}

		.faq-question {
			font-weight: 800;

			display: flex;
			align-items: center;
			gap: 0.6rem;
			font-size: 1rem;
		}

		.faq-question i {
			font-size: 0.9rem;

		}

		.faq-answer {
			margin-top: 0.4rem;
			padding-left: 1.8rem;

			font-size: 0.9rem;
		}

		/* toast modern */
		.toast-modern {
			position: fixed;
			bottom: 2rem;
			left: 50%;
			transform: translateX(-50%);
			background: #1e2a36e6;
			backdrop-filter: blur(12px);
			color: white;
			padding: 0.8rem 2rem;
			border-radius: 60px;
			font-weight: 500;
			z-index: 1000;
			display: none;
			align-items: center;
			gap: 12px;
			box-shadow: 0 20px 30px -10px rgba(0, 0, 0, 0.2);
		}

		@media (max-width: 900px) {
			.membership-layout {
				grid-template-columns: 1fr;
				gap: 2rem;
			}

			.membership-card {
				position: static;
				order: -1;
			}

			.hero-modern h1 {
				font-size: 2.3rem;
			}

			.content-panel {
				padding: 1.5rem;
			}
		}

		hr {
			margin: 1rem 0;
			border: 0;
			height: 2px;
			background: linear-gradient(to right, #cbe4ee, transparent);
		}

		.small-note {
			font-size: 0.7rem;
			text-align: center;
			margin-top: 1rem;

		}


		/* stats grid (investment horizon, returns, region, min buy) */
		.stats-grid {
			display: grid;
			grid-template-columns: 1fr 1fr;
			gap: 1rem;
			background: #f8fdfe;
			padding: 0.9rem;
			border-radius: 1.2rem;
			margin: 0.75rem 0;
			border: 1px solid #e2f0f5;
		}

		.stat-item {
			display: flex;
			flex-direction: column;
		}

		.stat-label {
			font-size: 0.7rem;
			text-transform: uppercase;
			font-weight: 700;
			color: #5e8da5;
			letter-spacing: 0.5px;
		}

		.stat-value {
			font-size: 1.1rem;
			font-weight: 800;
			color: #1a627f;
			margin-top: 0.2rem;
			display: flex;
			align-items: baseline;
			gap: 4px;
		}

		.stat-value i {
			font-size: 0.8rem;
			color: #2ba0c9;
		}

		.highlight-number {
			font-size: 1.2rem;
			font-weight: 800;
			color: #2a7f9c;
		}

		.btn-quantum {
			background: linear-gradient(105deg, #136b8c, #1f8aad);
			border: none;
			border-radius: 100px;
			padding: 0.75rem 0;
			font-weight: 700;
			font-size: 0.9rem;
			color: white;
			transition: all 0.25s;
			display: flex;
			align-items: center;
			justify-content: center;
			gap: 10px;
			margin: 0.8rem 0 1rem 0;
			box-shadow: 0 4px 12px rgba(31, 138, 173, 0.25);
		}

		.btn-quantum:hover {
			transform: scale(0.98);
			background: linear-gradient(105deg, #0f5775, #177a9b);
			color: white;
			box-shadow: 0 8px 18px rgba(31, 138, 173, 0.35);
		}
	</style>

</head>

<body class="ltr main-body app sidebar-mini">

	<!-- Loader -->
	<div id="global-loader">
		<img src="img/favicon.png" width="50" class="loader-img" alt="Loader">
	</div>
	<!-- /Loader -->

	<!-- Page -->
	<div class="page">

		<div>
			<?php include('header.php'); ?>
		</div>

		<!-- main-content -->
		<div class="main-content app-content">

			<!-- container -->
			<div class="main-container container-fluid">

				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="left-content">
						<span class="main-content-title mg-b-0 mg-b-lg-1">Make Purchase</span>
					</div>
					<div class="justify-content-center mt-2">
						<ol class="breadcrumb">
							<li class="breadcrumb-item tx-15"><a href="javascript:void(0);">Quantum</a></li>
							<li class="breadcrumb-item active" aria-current="page">Make Purchase</li>
						</ol>
					</div>
				</div>
				<!-- /breadcrumb -->



				<div class="container">
					<!-- modern HERO with branding -->


					<div class="membership-layout">
						<!-- LEFT side: all benefits, how it works, future plans, FAQ -->
						<div class="content-panel">
							<img class="card-img-top w-100" src="<?php echo $row['img']; ?>" alt=""> <br /><br />


							<h4 class="card-title mb-3"><?php echo $row['name']; ?></h4>
							<p class="card-text"><?php echo $row['info1']; ?></p><br /><br />



							<!-- Stats Grid: replaces old row/col layout -->
							<div class="stats-grid">
								<div class="stat-item">
									<span class="stat-label"><i class="far fa-calendar-alt"></i> Q Verse
										Horizon</span>
									<div class="stat-value">
										<i class="fas fa-clock"></i>
										<?php echo htmlspecialchars($row['duration']); ?> <span
											style="font-size:0.75rem;">MONTHS</span>
									</div>
								</div>
								<div class="stat-item">
									<span class="stat-label"><i class="fas fa-percent"></i> Daily
										Returns</span>
									<div class="stat-value">
										<i class="fas fa-chart-simple"></i>
										<span style="font-weight:300; font-size:13px;">Up to</span>
										<?php echo htmlspecialchars($row['percent']); ?>%
									</div>
								</div>

								<div class="stat-item">
									<span class="stat-label"><i class="fas fa-percent"></i> Staking
										Returns</span>
									<div class="stat-value">
										<i class="fas fa-chart-simple"></i>
										<span style="font-weight:300; font-size:13px;">Up to</span>
										<?php echo htmlspecialchars($row['compound_percent']); ?>%
									</div>
								</div>

								<div class="stat-item">
									<span class="stat-label"><i class="fas fa-globe-americas"></i> Region</span>
									<div class="stat-value">
										<i class="fas fa-earth-americas"></i> Global
									</div>
								</div>
								<div class="stat-item">
									<span class="stat-label"><i class="fas fa-dollar-sign"></i> Min. Buy</span>
									<div class="stat-value">
										<i class="fas fa-coins"></i>
										$<?php echo number_format($row['min_amount'], 2); ?>
									</div>
								</div>

								<div class="stat-item">
									<span class="stat-label"> Return Duration</span>
									<div class="stat-value">
										<i class="fas fa-coins"></i>
										5 Days (Monday - Friday)
									</div>
								</div>




								<div class="stat-item">
									<span class="stat-label"> Start At</span>
									<div class="stat-value">
										<i class="fas fa-coins"></i>
										Today (<?php echo date("d-m-Y"); ?>)
									</div>
								</div>


								<?php

								$now = time(); // Current timestamp
								$your_date = strtotime("today"); // Replace 'your_date' with the actual date variable
								
								$num_days = $row['duration'] * 30;

								// Calculate end date
								$end_date_timestamp = $your_date + ($num_days * 24 * 60 * 60);

								$end_date = date("d-m-Y", $end_date_timestamp);

								// Now $end_date should contain the correct end date
								
								?>
								<div class="stat-item">
									<span class="stat-label">End At</span>
									<div class="stat-value">
										<i class="fas fa-coins"></i>
										<?php echo $end_date; ?>
									</div>
								</div>




							</div>









						</div>

						<!-- RIGHT panel: Membership card with existing form logic (PHP variables remain functional) -->
						<div class="membership-card">
							<div class="card-badge"><i class="fas fa-crown"></i> <?php echo $row['name']; ?></div>
							<div class="plan-title">Make Purchase</div>
							<div class="price">$<?php echo $row['min_amount']; ?> <span>/
									<?php echo $row['duration']; ?> Months</span></div>

							<div class="status">
								<?php if ($isActive): ?>
									<div class="active-status"><i class="fas fa-check-circle"></i> Active Membership</div>
								<?php else: ?>
									<div class="inactive-status"><i class="fas fa-exclamation-triangle"></i> No Active
										Membership</div>
								<?php endif; ?>
							</div>

							<?php if ($isActive): ?>
								<!-- <div class="timer" id="countdown"><i class="fas fa-hourglass-half"></i> Loading timer...
								</div> -->
							<?php endif; ?>

							<form method="POST">
								<div class="form-group">
									<input name="amount" id="amount" required autofocus placeholder="Enter amount"
										class=" currency-select" min="<?php echo $row['min_amount']; ?>" type="number">

								</div>


								<div class="form-group">
									<label><i class="fas fa-coins"></i> Select currency</label>
									<select name="currency" id="currency" class="currency-select" required>
										<option value="">— Choose currency —</option>
										<?php
										// original dynamic generation stays intact & works with backend data
										for ($i = 0; $i < count($data); $i++) {
											if (isset($_GET['currency']) and $_GET['currency'] == $data[$i]->currency) {
												$pick = 'selected';
											}
											echo "<option " . $pick . " value=" . $data[$i]->currency . ">" . strtoupper($data[$i]->name) . "</option>";
										}
										?>
									</select>
								</div>

								<div class="form-group">
									<select name="payout" class=" currency-select" id="payout" required>
										<option value="">Select Payout</option>
										<option value="1">Daily Payout (100% Main Wallet)</option>
										<option value="2">Staking Payout (100% Staking Wallet)</option>
										<option value="3">Hybrid Payout (25% Regular Wallet, 75% Staking Wallet)</option>


									</select>
								</div>
								<br />

								<div class="form-group">
									<select name="duration" style="display:none" class="currency-select" id="duration">
										<option value="">Select Duration </option>
										
										<option value="61">2 Months</option>

										<option value="122">4 Months</option>

										<option value="183">6 Months</option>

										<option value="244">8 Months</option>

										<option value="365">12 Months</option>


									</select>
								</div>


								<button class="btn-membership" name="invest" id="invest" type="submit">
									<i class="fas fa-bolt"></i>
									Get Started
								</button>
								<div class="small-note"><i class="fas fa-lock"></i> Secure payment • instant access
								</div>
							</form>
						</div>
					</div>
				</div>




				<!-- End Row -->
			</div>
			<!-- Container closed -->
		</div>
		<!-- main-content closed -->


		<!-- Footer opened -->
		<div class="main-footer">
			<div class="container-fluid pt-0 ht-100p">
				Copyright © <?php echo date('Y'); ?> All rights reserved
			</div>
		</div>
		<!-- Footer closed -->

	</div>
	<!-- End Page -->

	<!-- Back-to-top -->
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


	<!--Internal  Notify js -->
	<script src="assets/plugins/notify/js/notifIt.js"></script>
	<script src="assets/plugins/notify/js/notifit-custom.js"></script>

	<!-- eva-icons js -->
	<script src="assets/js/eva-icons.min.js"></script>

	<!-- Theme Color js -->
	<script src="assets/js/themecolor.js"></script>

	<!-- custom js -->
	<script src="assets/js/custom.js"></script>


	<script>

		$('#payout').change(function () {

			if ($(this).val() == 2 || $(this).val() == 3 ) {
				$('#duration').show()
			} else {
				$('#duration').hide()
			}
		});
	</script>



	<?php

	include('phpqrcode/qrlib.php');

	include_once("email-handler.php");



	if (isset($_POST['invest'])) {


		$amount = mysqli_real_escape_string($mysqli, $_POST['amount']);
		$payout = mysqli_real_escape_string($mysqli, $_POST['payout']);
		$name = $row['name'];
		$investmentid = $id;
		$userid = $rows['id'];
		$date = date("d") . " " . date("F") . " " . date("Y") . " , " . date("h") . " : " . date("i") . date("a");
		$daily_roi = 0;
		$current = $_POST['currency'];
		$orderId = "QS-" . uniqid();// "";
		$duration = mysqli_real_escape_string($mysqli, $_POST['duration']);
	

$stmt = $mysqli->prepare("SELECT membership_expires FROM users WHERE id=?");
$stmt->bind_param("i", $userid);
$stmt->execute();
$stmt->bind_result($exp);
$stmt->fetch();
$stmt->close(); // important

if (!$exp || strtotime($exp) < time()) {
    ?>
    <script>
        notif({
            msg: "<b>Please get an active membership</b><br/> to make an investment.",
            width: 250,
            position: "center",
            type: "warning"
        });

        setTimeout(() => {
            location = location;
        }, 2000);
    </script>
    <?php
    exit;
}



		if ($payout == 1) {
			$daily_roi = $amount * ($row['percent'] / 100);
		} else {
			$daily_roi = $amount * ($row['compound_percent'] / 100);
		}



		if ($current == "btc" or $current == "eth" or $current == "ltc" or $current == "usdt(trc20)" or $current == "usdt(erc20)") {




			//get walletaddress
			$getwallet = mysqli_query($mysqli, "SELECT * FROM `payment_method` WHERE `code`='$current'  ");
			$curr = mysqli_fetch_assoc($getwallet);

			$wallet = $curr['wallet_address'];
			$crypto = $amount;





			ob_start();
			QRCode::png($wallet, null);
			$imageString = base64_encode(ob_get_contents());
			ob_end_clean();


			$qrcode = "data:image/png;base64," . $imageString;

			$currency = strtoupper($current);




			//add transactions
	
			//add to activity
			$date = date("d") . " " . date("F") . " " . date("Y") . " , " . date("h") . " : " . date("i") . date("a");
			$action = "Deposit into " . $name;
			$describe = "Deposit of $" . $amount . " has been initialised for " . $rows['firstname'] . "  ";



			$add = mysqli_query($mysqli, "INSERT INTO `activity`(`userid`, `action`, `describe`, `date`, `amount`,`status`) VALUES('$userid', '$action', '$describe', '$date','$amount', 'Pending') ");



			//insert into pending table 
			$addinvestment = mysqli_query($mysqli, "INSERT INTO `pending`(userid, chargeid, wallet, investmentid, `name`, amount, daily_roi, payout, qrcode, crypto, currency, date, duration)  VALUES('$userid', '$orderId', '$wallet', '$investmentid', '$name', '$amount', '$daily_roi', '$payout', '$qrcode', '$crypto', '$current', '$date', '$duration') ");








sendInvoiceEmail(
    $email,

    $name,
    $wallet,
	$orderId,
	$date
);
   






			if ($addinvestment) {
				//redrirect to payment page
				?>
				<script>
					location = "fund?currency=<?php echo $current; ?>&orderid=<?php echo $orderId; ?>&name=<?php echo $name; ?>"
				</script>

				<?php


			}



		} else {





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


			$wallet = $response->data->wallet_hash;

			$crypto = $response->data->amount;

			$qrcode = $response->data->qr_code;

			$rates = "";





			//ad transactions
	
			//add to activity
			$date = date("d") . " " . date("F") . " " . date("Y") . " , " . date("h") . " : " . date("i") . date("a");
			$action = "Deposit into " . $name;
			$describe = "Deposit of $" . $amount . " has been initialised for " . $rows['firstname'] . "  ";




			$add = mysqli_query($mysqli, "INSERT INTO `activity`(`userid`, `action`, `describe`, `date`, `amount`,`status`) VALUES('$userid', '$action', '$describe', '$date','$amount', 'Pending') ");





			//insert into pending table 
			$addinvestment = mysqli_query($mysqli, "INSERT INTO `pending`(userid, chargeid, wallet, investmentid, name, amount, daily_roi, payout, qrcode, crypto, currency, date, duration) VALUES('$userid', '$orderId', '$wallet', '$investmentid', '$name', '$amount', '$daily_roi', '$payout', '$qrcode', '$crypto', '$current', '$date', '$duration') ");





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
					
					"Subject": "Order Generated",
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





		}





	}


	?>




</body>

</html>