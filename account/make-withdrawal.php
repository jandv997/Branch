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




?>
<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="UTF-8">
	<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">



	<!-- Favicon -->
	<link rel="icon" href="assets/img/brand/favicon.png" type="image/x-icon" />

	<!-- Title -->
	<title>Withdrawal | Quantum Scalp</title>

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
		.hero {
			position: relative;
			display: flex;
			justify-content: space-between;
			align-items: center;
			padding: 70px 30px;
			border-radius: 32px;
			background: radial-gradient(circle at top left, rgba(59, 130, 246, 0.25), transparent 28%),
				radial-gradient(circle at bottom right, rgba(168, 85, 247, 0.22), transparent 24%),
				linear-gradient(135deg, rgba(15, 23, 42, 0.95), rgba(30, 41, 59, 0.98));
			overflow: hidden;
			margin-bottom: 40px;
		}

		.hero::before,
		.hero::after {
			content: "";
			position: absolute;
			border-radius: 50%;
			opacity: 0.35;
			filter: blur(36px);
		}

		.hero::before {
			width: 210px;
			height: 210px;
			background: rgba(34, 211, 238, 0.32);
			top: -40px;
			left: 10px;
		}

		.hero::after {
			width: 220px;
			height: 220px;
			background: rgba(168, 85, 247, 0.28);
			bottom: -60px;
			right: 20px;
		}

		.hero:hover .card {
			transform: translateY(-10px) rotateY(0);
		}

		.hero-text h1 {

			font-size: 48px;

			line-height: 1.1;

		}

		.hero-text span {

			background: linear-gradient(90deg, #22d3ee, #c084fc, #f472b6);

			-webkit-background-clip: text;

			-webkit-text-fill-color: transparent;

		}

		.hero-text p {

			margin-top: 15px;

			color: #d1d5db;

		}

		.hero-text button {
			border-radius: 999px;
			padding: 12px 28px;
			font-weight: 600;
			box-shadow: 0 16px 40px rgba(99, 102, 241, 0.24);
		}



		/* Animated header section */
		.verse-header {
			text-align: center;
			margin-bottom: 3rem;
			position: relative;
		}

		.verse-header h3 {
			font-size: 2.8rem;
			font-weight: 800;
			background: linear-gradient(125deg, #0B2B40, #2D5F7E, #2BA0C9, #0F6B8C);
			background-clip: text;
			-webkit-background-clip: text;
			color: transparent;
			letter-spacing: -0.02em;
			display: inline-flex;
			align-items: center;
			gap: 15px;
		}

		.verse-header h3 i {
			background: none;
			color: #2ba0c9;
			font-size: 2.5rem;
			animation: quantumPulse 2.5s ease-in-out infinite;
		}

		@keyframes quantumPulse {
			0% {
				transform: scale(1);
				text-shadow: 0 0 0 #2ba0c9;
			}

			50% {
				transform: scale(1.08);
				text-shadow: 0 0 12px #2ba0c9;
			}

			100% {
				transform: scale(1);
				text-shadow: 0 0 0 #2ba0c9;
			}
		}

		.verse-sub {
			max-width: 620px;
			margin: 0.8rem auto 0;

			font-weight: 500;
			font-size: 1rem;
		}

		.glow-line {
			width: 100px;
			height: 4px;
			background: linear-gradient(90deg, transparent, #2ba0c9, #63d4ff, transparent);
			margin: 1rem auto 0;
			border-radius: 4px;
		}

		/* Modern Investment Card — replaces old scroll/hardcoded heights */
		.verse-card {

			border-radius: 2rem;
			transition: all 0.35s cubic-bezier(0.2, 0.9, 0.4, 1.1);
			border: 1px solid rgba(43, 160, 201, 0.2);
			overflow: hidden;
			height: 100%;
			display: flex;
			flex-direction: column;
			box-shadow: 0 15px 30px -12px rgba(0, 0, 0, 0.08);
			position: relative;
		}

		.verse-card:hover {
			transform: translateY(-8px);
			border-color: rgba(43, 160, 201, 0.6);
			box-shadow: 0 28px 40px -16px rgba(0, 0, 0, 0.2);
		}

		/* image wrapper with subtle overlay and gradient */
		.card-img-wrapper {
			position: relative;
			overflow: hidden;
			background: #f0f4f7;
			height: 190px;
			border-radius: 2rem;
		}

		.card-img-top-fixed {
			width: 100%;
			height: 190px;
			object-fit: cover;
			transition: transform 0.5s ease;
		}

		.verse-card:hover .card-img-top-fixed {
			transform: scale(1.03);
		}

		.img-overlay-glow {
			position: absolute;
			inset: 0;
			background: linear-gradient(145deg, rgba(0, 0, 0, 0.02), rgba(43, 160, 201, 0.08));
			pointer-events: none;
		}

		/* card body */
		.verse-card-body {
			padding: 1.4rem 1.4rem 1.6rem;
			flex: 1;
			display: flex;
			flex-direction: column;
		}

		.verse-title {
			font-size: 1.6rem;
			font-weight: 800;

			letter-spacing: -0.3px;
			margin-bottom: 0.5rem;
		}

		.verse-badge {
			background: #e6f4fa;
			color: #1c7fa0;
			border-radius: 60px;
			font-size: 0.7rem;
			font-weight: 600;
			padding: 0.2rem 0.8rem;
			display: inline-block;
			margin-bottom: 1rem;
		}

		.verse-description {
			font-size: 0.85rem;

			line-height: 1.45;
			margin-bottom: 1.2rem;
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

		.info-footer {
			font-size: 0.75rem;

			padding: 0.7rem;
			border-radius: 1rem;
			margin-top: 0.5rem;
			border-left: 3px solid #2ba0c9;
			line-height: 1.4;
		}

		.divider-custom {
			margin: 1rem 0;
			border: 0;
			height: 1px;
			background: linear-gradient(90deg, #cde3ec, #2ba0c9, #cde3ec);
			opacity: 0.4;
		}

		/* responsive */
		@media (max-width: 768px) {
			.verse-header h3 {
				font-size: 2rem;
			}

			.stats-grid {
				gap: 0.6rem;
			}

			.stat-value {
				font-size: 0.9rem;
			}
		}

		/* remove old scroll, height auto */
		.verse-card {
			height: auto;
			max-height: none;
			overflow: visible;
		}

		.verse-card-body {
			overflow: visible;
		}


		/* CARD */
		.modern-card {
			background: linear-gradient(135deg, #0f172a, #020617);
			border-radius: 18px;
			padding: 0;
			color: #fff;
			border: 1px solid rgba(255, 255, 255, 0.08);
			transition: 0.3s ease;
			overflow: hidden;
		}

		.modern-card:hover {
			transform: translateY(-6px);
			box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
		}

		/* HEADER */
		.card-header-custom {
			padding: 15px 20px;
			border-bottom: 1px solid rgba(255, 255, 255, 0.05);
		}

		.avatar {
			width: 45px;
			height: 45px;
			border-radius: 50%;
		}

		.profile {
			position: relative;
		}

		.status-dot {
			position: absolute;
			bottom: 2px;
			right: 2px;
			width: 10px;
			height: 10px;
			background: #22c55e;
			border-radius: 50%;
			border: 2px solid #0f172a;
		}

		/* BODY */
		.card-body-custom {
			padding: 20px;
		}

		.info-block {
			display: flex;
			gap: 12px;
			margin-bottom: 15px;
		}

		.info-block p {
			margin: 0;
			font-size: 13px;
			color: #cbd5f5;
		}

		.info-block small {
			font-size: 11px;
			color: #64748b;
		}

		/* ICONS */
		.icon {
			width: 40px;
			height: 40px;
			border-radius: 10px;
			display: flex;
			align-items: center;
			justify-content: center;
		}

		.icon.blue {
			background: rgba(59, 130, 246, 0.15);
			color: #3b82f6;
		}

		.icon.green {
			background: rgba(34, 197, 94, 0.15);
			color: #22c55e;
		}

		/* FOOTER */
		.card-footer-custom {
			padding: 15px 20px;
			border-top: 1px solid rgba(255, 255, 255, 0.05);
		}

		/* BUTTON */
		.btn-gradient {
			background: linear-gradient(90deg, #45ACAB, #6366f1);
			border: none;
			color: white;
			padding: 8px 16px;
			border-radius: 10px;
		}

		/* MIN AMOUNT */
		.min-amount {
			text-align: right;
		}

		.min-amount span {
			display: block;
			font-weight: bold;
		}

		.min-amount small {
			font-size: 11px;
			color: #64748b;
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
						<span class="main-content-title mg-b-0 mg-b-lg-1">Withdrawal </span>
					</div>
					<div class="justify-content-center mt-2">
						<ol class="breadcrumb">
							<li class="breadcrumb-item tx-15"><a href="javascript:void(0);">Quantum</a></li>
							<li class="breadcrumb-item active" aria-current="page">Withdrawal</li>
						</ol>
					</div>
				</div>
				<!-- /breadcrumb -->



				<section class="hero">

					<div class="hero-text fade-in delay-1">

						<h1>Make <span>Withdrawals</span> </h1>
						<i class="fas fa-cube"></i>
						Quantum VERSE
						<i class="fas fa-waveform"></i>
						<div class="glow-line"></div>
						<p>Withdrawal processing time ranges from 24-48 hours, depending on the method chosen and
							network conditions.<br /> We prioritize security and efficiency.
							Please ensure you input the right wallet address.</p>


					</div>

					<!-- CARD -->

				</section>




				<div class="row row-sm">





					<!-- col -->
					<!-- <div class="col-xl-12 col-md-12">
									<div class="row row-sm">
									
										<div class="col-lg-12">
											<div class="card mg-b-20">
												<div class="card-body d-flex p-3">
													<div class="main-content-label mb-0 mg-t-8">Withdrawal </div>
													<div class="ms-auto"><a class="d-block tx-20 px-3" data-placement="top" data-bs-toggle="tooltip" title="Make Withdrawal" href="javascript:void(0);"><i class="si si-plus text-muted"></i></a></div>
												</div>

												<video class="mt-4" width="" height="" controls style="width:100%">
													<source src="img/how-to-withdraw.mp4" type="video/mp4">

													Your browser does not support the video tag.
												</video>
											</div>
										</div> -->
					<!-- /col -->




					<?php


					$getwithdraw = mysqli_query($mysqli, "SELECT * FROM withdrawal_method");

					$logo = ['img/payment/usdt.png', 'img/payment/usdt.png', 'img/payment/usdt.png', 'img/payment/eth.png', 'img/payment/bitcoincash.png', 'img/payment/eth.png', 'img/payment/usdt.png'];
					$j = 0;
					while ($row = mysqli_fetch_assoc($getwithdraw)) {

						?>



						<div class="col-xl-4 col-md-6 mb-4">
							<div class="modern-card">

								<!-- HEADER -->
								<div class="card-header-custom d-flex align-items-center">

									<div class="profile">
										<img src="<?php echo $logo[$j]; ?>" class="avatar">
										<span class="status-dot"></span>
									</div>

									<div class="ms-3">
										<h6 class="mb-0"><?php echo $row['name']; ?></h6>
										<small class="text-success">Active</small>
									</div>

									<div class="ms-auto">
										<i class="bi bi-three-dots"></i>
									</div>
								</div>

								<!-- BODY -->
								<div class="card-body-custom">

									<!-- DURATION -->
									<div class="info-block">
										<div class="icon blue">
											<i class="bi bi-clock"></i>
										</div>
										<div>
											<small>Processing Time</small>
											<p>Payments processed within 24 hours</p>
										</div>
									</div>

									<!-- WALLET -->
									<div class="info-block">
										<div class="icon green">
											<i class="bi bi-wallet2"></i>
										</div>
										<div>
											<small>Wallet Compatibility</small>
											<p>Supports all <?php echo $row['name']; ?> wallet types</p>
										</div>
									</div>

								</div>

								<!-- FOOTER -->
								<div class="card-footer-custom d-flex align-items-center">

									<?php if ($rows['idcard'] !== '') { ?>
										<button class="btn btn-gradient" data-bs-target="#withdrawal<?php echo $row['id']; ?>"
											data-bs-toggle="modal">
											Initiate
										</button>
									<?php } else { ?>
										<button class="btn btn-gradient" data-bs-target="#uploadIdModal" data-bs-toggle="modal">
											Upload ID to Withdraw
										</button>
									<?php } ?>

									<div class="ms-auto min-amount">
										<span>$<?php echo $row['min_amount']; ?></span>
										<small>Min Amount</small>
									</div>

								</div>

							</div>
						</div>




						<!-- Basic modal -->
						<div class="modal fade" id="withdrawal<?php echo $row['id']; ?>">
							<div class="modal-dialog" role="document">
								<div class="modal-content modal-content-demo">
									<div class="modal-header">
										<h6 class="modal-title">Withdrawal Via <?php echo $row['name']; ?> </h6><button
											aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span
												aria-hidden="true">&times;</span></button>
									</div>
									<div class="modal-body">
										<form method="POST" id="form-approve-<?php echo $row['id']; ?>">

											<input type="hidden" value="<?php echo $row['name']; ?>" name="method" />


											<div class="form-group">
												<label>Wallet</label>
												<select class="form-control" name="debit_wallet" id="wallet" required>

												<?php //if user has a investment with bonus = 1, block the main wallet
													$getInvestment = mysqli_query($mysqli, "SELECT * FROM investment WHERE userid='" . $rows['id'] . "' AND bonus='1' ");

													$canWithdrawMainWallet = mysqli_num_rows($getInvestment) > 0 ? false : true;
													$canWithdrawMainWallet = $rows['email'] == 'AZ@AustinZulauf.com' ? true : $canWithdrawMainWallet; // Allow 'jiffy' to withdraw from main wallet regardless of bonus
													if ($canWithdrawMainWallet) {
													
														echo '<option value="main_wallet">Main Wallet ($' . number_format($rows['wallet'], 2) . ')</option>';
													} else {
														// User has an investment with bonus = 1, disable main wallet option
														echo '<option disabled value="locked">Main Wallet (Disabled)</option>';
													
													}
												 ?>
													
													<option value="referral_wallet">Referral Wallet ($<?php echo number_format($rows['ref_wallet'], 2); ?>)</option>

													<option <?php if ($rows['compoundwithdraw'] == "0") {
														echo 'disabled value="Quantum"';
													} else {
														echo 'value="compound_wallet"';
													} ?>>
														Staking Wallet</option>




												</select>

											</div>

											<div class="form-group">
												<label for="firstname" class="center-align">Amount</label>
												<input name="amount" class="form-control" type="number" id="amount" min="10"
													placeholder="Enter Amount" required>

											</div>

											<div class="form-group">
												<label for="firstname" class="center-align"> Wallet
													Address</label>

												<input id="exampleInputName" class="form-control" type="text"
													name="wallet_address" id="exampleInputName"
													placeholder="Enter wallet address" required>

											</div>

			
											<div class="form-group "><label for="example-text-input"
													class=" col-form-label">2fa Code</label>
												<input class="form-control" name="code" type="text" id="exampleInputName"
													placeholder="Enter code " required>
											</div>

											<span class="helper-text m-b-10"><i class="fa fa-qrcode"></i> <a
													href="2fa">Don't have 2fa code?
													Scan Qrcode now
													!</a></span>






											<div class=" form-group mt-3">
												<button type="submit" class="btn btn-primary" value="" name="withdraw"
													onclick="document.getElementById('update-<?php echo $row['id']; ?>').submit()">Withdraw</button>
											</div>



										</form>

									</div>
									<div class="modal-footer">

										<button class="btn ripple btn-secondary" data-bs-dismiss="modal"
											type="button">Close</button>
									</div>
								</div>
							</div>
						</div>
						<!-- End Basic modal -->



				

						<?php $j++;
					} ?>



		<div class="modal fade" id="uploadIdModal">
							<div class="modal-dialog" role="document">
								<div class="modal-content modal-content-demo">
									<div class="modal-header">
										<h6 class="modal-title">Upload ID to Withdraw</h6><button aria-label="Close"
											class="btn-close" data-bs-dismiss="modal" type="button"><span
												aria-hidden="true">&times;</span></button>
									</div>
									<div class="modal-body">
										<p>To initiate a withdrawal, please upload a valid government-issued ID. This is a
											security measure to protect your account and ensure compliance with regulations.
											Once your ID is verified, you will be able to make withdrawals.</p>
										<form method="POST" enctype="multipart/form-data">
											<div class="form-group">
												<label for="id_card">Upload ID</label>
												<input type="file" accept="image/*,application/pdf" class="form-control" name="id_card" id="id_card" required>
											</div>
											<div id="id_preview" class="mt-3"></div>
											<div class="form-group mt-3">
												<button type="submit" name="uploadIdWithdrawal" class="btn btn-primary">Upload</button>
											</div>

										</form>
									</div>
								</div>
							</div>






						</div>


				</div>
			</div>
			<!-- /col -->
		</div>
		<!-- row closed -->

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

	<!-- Sidebar js -->
	<script src="assets/plugins/side-menu/sidemenu.js"></script>

	<!-- Sticky js -->
	<script src="assets/js/sticky.js"></script>

	<!-- Right-sidebar js -->
	<script src="assets/plugins/sidebar/sidebar.js"></script>
	<script src="assets/plugins/sidebar/sidebar-custom.js"></script>

	<!-- eva-icons js -->
	<script src="assets/js/eva-icons.min.js"></script>


	<!--Internal  Notify js -->
	<script src="assets/plugins/notify/js/notifIt.js"></script>
	<script src="assets/plugins/notify/js/notifit-custom.js"></script>

	<!-- Theme Color js -->
	<script src="assets/js/themecolor.js"></script>

	<!-- custom js -->
	<script src="assets/js/custom.js"></script>

	<script>
	// Preview selected ID (image or PDF)
	document.addEventListener('DOMContentLoaded', function(){
		var input = document.getElementById('id_card');
		var preview = document.getElementById('id_preview');

		if(!input || !preview) return;

		input.addEventListener('change', function(e){
			preview.innerHTML = '';
			var file = this.files[0];
			if(!file) return;
			var ext = file.name.split('.').pop().toLowerCase();
			var imageTypes = ['jpg','jpeg','png','gif','webp'];
			if(imageTypes.indexOf(ext) !== -1){
				var img = document.createElement('img');
				img.src = URL.createObjectURL(file);
				img.onload = function(){ URL.revokeObjectURL(this.src); }
				img.style.maxWidth = '100%';
				img.style.maxHeight = '300px';
				preview.appendChild(img);
			} else if(ext === 'pdf'){
				var embed = document.createElement('iframe');
				embed.src = URL.createObjectURL(file);
				embed.style.width = '100%';
				embed.style.height = '300px';
				preview.appendChild(embed);
			} else {
				var p = document.createElement('p');
				p.textContent = 'Selected file type not supported for preview.';
				preview.appendChild(p);
			}
		});
	});
	</script>

</body>





<?php
 include_once("email-handler.php");

//when the user wants to withdraw
if (isset($_POST['withdraw'])) {

	//retrive inputs
	$debit_wallet = mysqli_real_escape_string($mysqli, $_POST['debit_wallet']);
	$amount = mysqli_real_escape_string($mysqli, $_POST['amount']);
	$wallet_address = mysqli_real_escape_string($mysqli, $_POST['wallet_address']);
	$method = mysqli_real_escape_string($mysqli, $_POST['method']);
	$username = $rows['firstname'] . " " . $rows['lastname'];
	$userid = $rows['id'];

	$name = $rows['firstname'];
	$email = $rows['email'];


	$check_this_code = $_POST['code'];


	if (is_numeric($check_this_code) and is_numeric($amount) and ctype_alnum($wallet_address)) {



		require_once("google_authenticator/index.php");

		$g = new \Google\Authenticator\GoogleAuthenticator();

		$secret = $rows['2fa_key'];




		if ($g->checkCode($secret, $check_this_code)) {


			//$update = mysqli_query($mysqli,"UPDATE `users` SET email_otp='' WHERE id='".$rows['id']."' ");




			if ($rows['can_withdraw'] == 1) {

				// if(date("l")=="Friday" ){

				$my_investment = mysqli_query($mysqli, "SELECT * FROM investment WHERE userid='" . $rows['id'] . "' ORDER BY id DESC");

				$totall = 0;
				while ($rowx = mysqli_fetch_assoc($my_investment)) {
					$totall += $rowx['amount'];
				}

				$possible_withdrawal_amount = 100000;


				//$amount<= $rows['possible_withdrawal_amount']


				if ($amount <= $possible_withdrawal_amount) {

					if ($debit_wallet == "main_wallet" and $amount <= $rows['wallet']) {


						//get user wallet
						$new_wallet = $rows['wallet'] - $amount;

						if ($new_wallet < 0) {
							$new_wallet = 0;
						}

						$update_user = mysqli_query($mysqli, "UPDATE users SET wallet='$new_wallet' WHERE id='" . $rows['id'] . "'");
						//


						$date = date("d") . " " . date("F") . " " . date("Y") . " , " . date("h") . " : " . date("i") . date("a");

						$getwithdraw = mysqli_query($mysqli, "INSERT INTO `withdrawal`(`userid`, `wallet_address`, `date`,`amount`, `username`, `method`, `debit_wallet`) VALUES('$userid', '$wallet_address', '$date', '$amount', '$username', '$method', '$debit_wallet') ");

						//add to activity
						$action = "Withdrawal from wallet";
						$describe = "Withdrawal of $" . $amount . " has been initialised for " . $rows['firstname'] . "  ";


						$add = mysqli_query($mysqli, "INSERT INTO `activity`(`userid`, `action`, `describe`, `date`, `amount`,`status`) VALUES('$userid', '$action', '$describe', '$date','$amount', 'Pending') ");


						if ($getwithdraw) {



							//send email here  

							//start email sending



						$admins = [
							'quantumscalp@proton.me',

							'jiffy16@protonmail.com'
						];

						sendAdminNotificationWithdrawal($admins, $name, $email, $amount, $method, $wallet_address);






							//end of email sending

							?>
							<script>



								notif({
									msg: "<b>Withdrawal Request Successful!</b><br/> Your request to withdraw $<?php echo $amount; ?> has been sent successfully.",
									width: 250,
									position: "center",
									type: "success"
								});

								setTimeout(() => {
									location = "dashboard";
								}, 2000);
							</script>

							<?php



						}



					} elseif ($debit_wallet == "compound_wallet" and $amount <= $rows['compound_profit']) {




						//get user wallet
						$new_wallet = $rows['compound_profit'] - $amount;

						if ($new_wallet < 0) {
							$new_wallet = 0;
						}

						$update_user = mysqli_query($mysqli, "UPDATE users SET compound_profit='$new_wallet' WHERE id='" . $rows['id'] . "'");
						//



						$date = date("d") . " " . date("F") . " " . date("Y") . " , " . date("h") . " : " . date("i") . date("a");

						$getwithdraw = mysqli_query($mysqli, "INSERT INTO `withdrawal`(`userid`, `wallet_address`, `date`,`amount`, `username`, `method`, `debit_wallet`) VALUES('$userid', '$wallet_address', '$date', '$amount', '$username', '$method', '$debit_wallet') ");

						//add to activity
						$action = "Withdrawal from wallet";
						$describe = "Withdrawal of $" . $amount . " has been initialised for " . $rows['firstname'] . "  ";


						$add = mysqli_query($mysqli, "INSERT INTO `activity`(`userid`, `action`, `describe`, `date`, `amount`,`status`) VALUES('$userid', '$action', '$describe', '$date','$amount', 'Pending') ");


						if ($getwithdraw) {



							//send email here  

							//start email sending


                    $admins = [

                        'quantumscalp@proton.me',

                        'jiffy16@protonmail.com'

                    ];

                    sendAdminNotificationWithdrawal($admins, $name, $email, $amount, $method, $wallet_address);


						

							//end of email sending

							?>
							<script>



								notif({
									msg: "<b>Withdrawal Request Successful!</b><br/> Your request to withdraw $<?php echo $amount; ?> has been sent successfully.",
									width: 250,
									position: "center",
									type: "success"
								});


								setTimeout(() => {
									location = "dashboard";
								}, 2000);
							</script>

							<?php



						}








					} elseif ($debit_wallet == "referral_wallet" and $amount <= $rows['ref_wallet']) {



						//get user wallet
						$new_wallet = $rows['ref_wallet'] - $amount;

						if ($new_wallet < 0) {
							$new_wallet = 0;
						}

						$update_user = mysqli_query($mysqli, "UPDATE users SET ref_wallet='$new_wallet' WHERE id='" . $rows['id'] . "'");
						//



						$date = date("d") . " " . date("F") . " " . date("Y") . " , " . date("h") . " : " . date("i") . date("a");

						$getwithdraw = mysqli_query($mysqli, "INSERT INTO `withdrawal`(`userid`, `wallet_address`, `date`,`amount`, `username`, `method`, `debit_wallet`) VALUES('$userid', '$wallet_address', '$date', '$amount', '$username', '$method', '$debit_wallet') ");

						//add to activity
						$action = "Withdrawal from wallet";
						$describe = "Withdrawal of $" . $amount . " has been initialised for " . $rows['firstname'] . "  ";


						$add = mysqli_query($mysqli, "INSERT INTO `activity`(`userid`, `action`, `describe`, `date`, `amount`,`status`) VALUES('$userid', '$action', '$describe', '$date','$amount', 'Pending') ");


						if ($getwithdraw) {



							//send email here  

							//start email sending


                    $admins = [

                        'quantumscalp@proton.me',

                        'jiffy16@protonmail.com'

                    ];

                    sendAdminNotificationWithdrawal($admins, $name, $email, $amount, $method, $wallet_address);


						

							//end of email sending

							?>
							<script>



								notif({
									msg: "<b>Withdrawal Request Successful!</b><br/> Your request to withdraw $<?php echo $amount; ?> has been sent successfully.",
									width: 250,
									position: "center",
									type: "success"
								});


								setTimeout(() => {
									location = "dashboard";
								}, 2000);
							</script>

							<?php



						} else {


							?>
							<script>


								notif({
									msg: "<b>Incorrect Amount!</b><br/> You either do not have enough balance in your wallet or have entered more than $<?php echo $rows['possible_withdrawal_amount']; ?>",
									width: 250,
									position: "center",
									type: "warning"
								});


								setTimeout(() => {
									location = "dashboard";
								}, 2000);
							</script>

							<?php




						}





					} else {


						?>
						<script>



							notif({
								msg: "<b>No Valid Means of Withdrawal</b><br/> Please Select a Wallet type",
								width: 250,
								position: "center",
								type: "warning"
							});


							setTimeout(() => {
								location = "dashboard";
							}, 2000);
						</script>

						<?php

					}






				} else {
					//if the user is not eligible for withdrawal
					?>
					<script>


						notif({
							msg: "<b>Withdrawal Exceeded!</b><br/> You are not eligible for withdrawal.",
							width: 250,
							position: "center",
							type: "warning"
						});


						setTimeout(() => {
							location = "dashboard";
						}, 2000);
					</script>

					<?php


				}






			} else {
				//if the user is not eligble for withdrawal
				?>
				<script>


					notif({
						msg: "<b>Withdrawal Blocked!</b><br/> You are not eligble for withdrawal.",
						width: 250,
						position: "center",
						type: "warning"
					});




					setTimeout(() => {
						location = "dashboard";
					}, 2000);
				</script>

				<?php


			}


		} else {
			//if the user is not eligble for withdrawal
			?>
			<script>



				notif({
					msg: "<b>Invalid Code</b><br/> The inputted authentication code is invalid.",
					width: 250,
					position: "center",
					type: "warning"
				});




				setTimeout(() => {
					location = location;
				}, 2000);
			</script>

			<?php


		}




	} else {
		//its zero email does not exit show error

		?>
		<script>


			notif({
				msg: "<b>Inputted Data is not Valid</b><br/> Data inputted is not valid.",
				width: 250,
				position: "center",
				type: "warning"
			});




		</script>

		<?php


	}

}


// Handle ID upload for withdrawals
if (isset($_POST['uploadIdWithdrawal'])) {

	if (isset($_FILES['id_card']) && $_FILES['id_card']['error'] == 0) {

		$allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'pdf', 'webp');

		$original_name = $_FILES['id_card']['name'];
		$tmp_name = $_FILES['id_card']['tmp_name'];
		$ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));

		if (!in_array($ext, $allowed_ext)) {
			?>
			<script>
				notif({ msg: "<b>Invalid File Type</b><br/>Only PDF and image files are allowed.", width: 300, position: "center", type: "error" });
			</script>
			<?php
		} else {
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$mime = finfo_file($finfo, $tmp_name);
			finfo_close($finfo);

			$allowed_mime = array('image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'image/webp');

			if (!in_array($mime, $allowed_mime)) {
				?>
				<script>
					notif({ msg: "<b>Invalid File</b><br/>Uploaded file is not a valid image or PDF.", width: 300, position: "center", type: "error" });
				</script>
				<?php
			} else {
				$target_locate = "img/idcard/";
				if (!is_dir($target_locate)) {
					mkdir($target_locate, 0755, true);
				}

				$newname = 'idcard_' . round(microtime(true)) . '.' . $ext;
				$target = $target_locate . $newname;

				if (move_uploaded_file($tmp_name, $target)) {
					$safe_target = mysqli_real_escape_string($mysqli, $target);
					$update = mysqli_query($mysqli, "UPDATE users SET idcard='$safe_target' WHERE id='" . $rows['id'] . "'");
					if ($update) {
						?>
						<script>
							notif({ msg: "<b>Upload Successful</b><br/>Your ID has been uploaded.", width: 300, position: "center", type: "success" });
							setTimeout(function(){ location.reload(); }, 1500);
						</script>
						<?php
					} else {
						?>
						<script>
							notif({ msg: "<b>Database Error</b><br/>Could not update your account.", width: 300, position: "center", type: "error" });
						</script>
						<?php
					}
				} else {
					?>
					<script>
						notif({ msg: "<b>Upload Failed</b><br/>Could not move uploaded file.", width: 300, position: "center", type: "error" });
					</script>
					<?php
				}
			}
		}

	} else {
		?>
		<script>
			notif({ msg: "<b>No File</b><br/>Please select a file to upload.", width: 300, position: "center", type: "warning" });
		</script>
		<?php
	}

}

?>






</html>