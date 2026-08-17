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


	<!-- Title -->
	<title> Dashboard | Quantum Scalp </title>

	<!-- Favicon -->
	<link rel="icon" href="assets/img/brand/favicon.png" type="image/x-icon" />

	<!-- Icons css -->
	<link href="assets/css/icons.css" rel="stylesheet">

	<!--  bootstrap css-->
	<link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

	<!-- style css -->
	<link href="assets/css/style.css" rel="stylesheet">
	<link href="assets/css/style-dark.css" rel="stylesheet">
	<link href="assets/css/style-transparent.css" rel="stylesheet">

	<!---Skinmodes css-->
	<link href="assets/css/skin-modes.css" rel="stylesheet" />
	<link rel="preconnect" href="https://api.fontshare.com">
	<link href="https://api.fontshare.com/v2/css?f[]=satoshi@400,500,700,900&display=swap" rel="stylesheet">
	<link href="assets/css/qs-member.css" rel="stylesheet">


	<style>
		.custom-modal {
			background: linear-gradient(135deg, #0f172a, #020617);
			color: #fff;
			border-radius: 16px;
			border: 1px solid rgba(255, 255, 255, 0.1);
		}

		.custom-modal p {
			font-size: 14px;
			color: #cbd5f5;
		}

		.btn-success {
			background: linear-gradient(90deg, #45ACAB, #6366f1);
			border: none;
		}

		.btn-outline-light {
			border: 1px solid rgba(255, 255, 255, 0.3);
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
						<span class="main-content-title mg-b-0 mg-b-lg-1">DASHBOARD</span>
					</div>
					<div class="justify-content-center mt-2">
						<ol class="breadcrumb">
							<li class="breadcrumb-item tx-15"><a href="javascript:void(0);">Dashboard</a></li>
							<li class="breadcrumb-item active" aria-current="page">Quantum Scalp</li>
						</ol>
					</div>
				</div>
				<!-- /breadcrumb -->

				<!-- row -->
				<div class="row">
					<div class="col-xl-7 col-lg-12 col-md-12 col-sm-12">
						<div class="card primary-custom-card1">
							<div class="card-body">
								<div class="row">
									<div class="col-xl-5 col-lg-6 col-md-12 col-sm-12">
										<div class="prime-card"><img class="img-fluid" src="../assets/img/bg/uo_bg.png"
												alt=""></div>
									</div>
									<div class="col-xl-7 col-lg-6 col-md-12 col-sm-12">
										<div class="text-justified align-items-center">
											<h2 class="text-dark font-weight-semibold mb-3 mt-2">Welcome back, <span
													class="text-primary"><?php echo $rows['firstname']; ?></span></h2>
											<p class="text-dark tx-17 mb-2 lh-3">Your Q-Core command center — wallets, portfolios, and activity.</p>
											<p class="font-weight-semibold tx-12 mb-4">For account related issues,
												contact us through support chat or mail us at info@quantumscalp.io </p>
											<a href="marketplace" class="btn btn-primary mb-3 shadow">Select a Plan</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-5 col-lg-12 col-md-12 col-sm-12">
						<!-- <div class="container"> -->
						<div class="row">
							<div class="col-xl-6 col-lg-6 col-md-6 col-xs-12">
								<div class="card sales-card circle-image1">
									<div class="row">
										<div class="col-8">
											<div class="ps-4 pt-4 pe-3 pb-4">
												<div class="">
													<h6 class="mb-2 tx-12 ">Staking Wallet</h6>
												</div>
												<div class="pb-0 mt-0">
													<div class="d-flex">
														<h4 class="tx-20 font-weight-semibold mb-2">
															$<?php echo $rows['compound_profit']; ?></h4>
													</div>
													<p class="mb-0 tx-12 text-muted"><i
															class="fa fa-caret-up mx-2 text-success"></i>
														<span class="text-success font-weight-semibold"> +</span>
													</p>
												</div>
											</div>
										</div>
										<div class="col-4">
											<div
												class="circle-icon bg-primary-transparent text-center align-self-center overflow-hidden">
												<i class="fe fe-shopping-bag tx-16 text-primary"></i>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-6 col-lg-6 col-md-6 col-xs-12">
								<div class="card sales-card circle-image2">
									<div class="row">
										<div class="col-8">
											<div class="ps-4 pt-4 pe-3 pb-4">
												<div class="">
													<h6 class="mb-2 tx-12">Wallet </h6>
												</div>
												<div class="pb-0 mt-0">
													<div class="d-flex">
														<h4 class="tx-20 font-weight-semibold mb-2">
															$<?php echo $rows['wallet']; ?></h4>
													</div>
													<p class="mb-0 tx-12 text-muted">Current<i
															class="fa fa-caret-down mx-2 text-danger"></i>
														<span class="font-weight-semibold text-info"> USD</span>
													</p>
												</div>
											</div>
										</div>
										<div class="col-4">
											<div
												class="circle-icon bg-info-transparent text-center align-self-center overflow-hidden">
												<i class="fe fe-dollar-sign tx-16 text-info"></i>
											</div>
										</div>
									</div>
								</div>
							</div>

							<?php $get_users = mysqli_query($mysqli, "SELECT * FROM users WHERE referred='" . $rows['referal_link'] . "' ORDER BY id DESC"); ?>
							<div class="col-xl-6 col-lg-6 col-md-6 col-xs-12">
								<div class="card sales-card circle-image3">
									<div class="row">
										<div class="col-8">
											<div class="ps-4 pt-4 pe-3 pb-4">
												<div class="">
													<h6 class="mb-2 tx-12">Referral Wallet</h6>
												</div>
												<div class="pb-0 mt-0">
													<div class="d-flex">
														<h4 class="tx-20 font-weight-semibold mb-2">
															$<?php echo $rows['ref_wallet']; ?></h4>
													</div>
													<p class="mb-0 tx-12 text-muted"><i
															class="fa fa-caret-up mx-2 text-success"></i>
														<span class=" text-success font-weight-semibold"> </span>
													</p>
												</div>
											</div>
										</div>
										<div class="col-4">
											<div
												class="circle-icon bg-secondary-transparent text-center align-self-center overflow-hidden">
												<i class="fe fe-external-link tx-16 text-secondary"></i>
											</div>
										</div>
									</div>
								</div>
							</div>
							<?php $my_investment = mysqli_query($mysqli, "SELECT * FROM investment WHERE userid='" . $rows['id'] . "' ORDER BY id DESC"); ?>
							<div class="col-xl-6 col-lg-6 col-md-6 col-xs-12">
								<div class="card sales-card circle-image4">
									<div class="row">
										<div class="col-8">
											<div class="ps-4 pt-4 pe-3 pb-4">
												<div class="">
													<h6 class="mb-2 tx-12">Active Portfolios</h6>
												</div>
												<div class="pb-0 mt-0">
													<div class="d-flex">
														<h4 class="tx-22 font-weight-semibold mb-2">
															<?php echo mysqli_num_rows($my_investment); ?></h4>
													</div>
													<p class="mb-0 tx-12  text-muted"><i
															class="fa fa-caret-down mx-2 text-info"></i>
														<span class="text-danger font-weight-semibold"> </span>
													</p>
												</div>
											</div>
										</div>
										<div class="col-4">
											<div
												class="circle-icon bg-warning-transparent text-center align-self-center overflow-hidden">
												<i class="fe fe-credit-card tx-16 text-warning"></i>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- </div> -->
				</div>
				<!-- row closed -->

				<!-- row -->
				<div class="row">



					<?php


					$curl = curl_init();

					curl_setopt_array($curl, array(
						CURLOPT_URL => 'https://finnhub.io/api/v1/news?category=general&token=c3no7o2ad3iabnjjem70',
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



					$response = json_decode($response, true);

					for ($i = 0; $i < 1; $i++) {

						?>


						<div class="col-sm-12 col-md-12 col-lg-12 col-xl-4">
							<div class="card text-center card-img-top-1">
								<img class="card-img-top w-100" src="<?php echo $response[$i]['image']; ?>" alt="">
								<div class="card-body">
									<h4 class="card-title mb-3"> </h4>
									<p class="card-text"><?php echo $response[$i]['source']; ?></p><a
										class="btn btn-primary btn-block" href="<?php echo $response[$i]['url']; ?>"
										target="_blank">Read More</a>
								</div>
							</div>



						</div>

					<?php } ?>





					<!-- Action Menu Cards - Redesigned -->
					<div class="col-sm-12 col-md-12 col-lg-12 col-xl-4">
						<div class="action-card">
							<!-- Card Header with Icon -->
							<!-- <div class="action-header">
								<div class="action-icon-wrapper">
									<i class="fe fe-grid action-main-icon"></i>
								</div>
								<h5 class="action-title">Quick Actions</h5>
								<p class="action-subtitle">Manage your Account</p>
							</div> -->

							<!-- Action Buttons List -->
							<div class="action-body">
								<!-- Make Deposit Button -->
								<a href="<?php echo $isActiveMember ? 'marketplace' : 'javascript:void(0);'; ?>" class="action-btn action-btn-deposit">
									<div class="action-btn-icon">
										<i class="fe fe-credit-card"></i>
									</div>
									<div class="action-btn-content">
										<span class="action-btn-title">Make Deposit</span>
										<span class="action-btn-desc">Add Portfolio to your account</span>
									</div>
									<div class="action-btn-arrow">
										<i class="fe fe-arrow-right"></i>
									</div>
								</a>

								<!-- Micro Withdrawal Button -->
								<a href="<?php echo $isActiveMember ? 'make-withdrawal' : 'javascript:void(0);'; ?>" class="action-btn action-btn-withdraw">
									<div class="action-btn-icon">
										<i class="fe fe-arrow-down-circle"></i>
									</div>
									<div class="action-btn-content">
										<span class="action-btn-title">Make Withdrawal</span>
										<span class="action-btn-desc">Withdraw from your account</span>
									</div>
									<div class="action-btn-arrow">
										<i class="fe fe-arrow-right"></i>
									</div>
								</a>

								<!-- View Active Portfolios Button -->
								<a href="<?php echo $isActiveMember ? 'active-purchase' : 'javascript:void(0);'; ?>" class="action-btn action-btn-portfolio">
									<div class="action-btn-icon">
										<i class="fe fe-briefcase"></i>
									</div>
									<div class="action-btn-content">
										<span class="action-btn-title">Active Portfolios</span>
										<span class="action-btn-desc">View your Portfolios</span>
									</div>
									<div class="action-btn-arrow">
										<i class="fe fe-arrow-right"></i>
									</div>
								</a>

								<!-- Investment History Button (Extra) -->
								<a href="<?php echo $isActiveMember ? 'membership' : 'javascript:void(0);'; ?>" class="action-btn action-btn-history">
									<div class="action-btn-icon">
										<i class="fe fe-clock"></i>
									</div>
									<div class="action-btn-content">
										<span class="action-btn-title">Membership</span>
										<span class="action-btn-desc">View your membership details</span>
									</div>
									<div class="action-btn-arrow">
										<i class="fe fe-arrow-right"></i>
									</div>
								</a>

								<!-- Referral Bonus Button (Extra) -->
								<a href="<?php echo $isActiveMember ? 'referral-bonus' : 'javascript:void(0);'; ?>" class="action-btn action-btn-referral">
									<div class="action-btn-icon">
										<i class="fe fe-users"></i>
									</div>
									<div class="action-btn-content">
										<span class="action-btn-title">Referral Bonus</span>
										<span class="action-btn-desc">Earn from referrals</span>
									</div>
									<div class="action-btn-arrow">
										<i class="fe fe-arrow-right"></i>
									</div>
								</a>
							</div>

						
						</div>
					</div>

					<style>
						/* Action Card Styles */
						.action-card {
							background: linear-gradient(180deg, #12131A, #0A0B10);
							border-radius: 16px;
							padding: 0;
							box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
							transition: all 0.3s ease;
							border: 1px solid rgba(255, 255, 255, 0.05);
							overflow: hidden;
							height: 100%;
						}

						.action-card:hover {
							transform: translateY(-5px);
							box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
						}

						/* Card Header */
						.action-header {
							padding: 20px 24px 16px;
							text-align: center;
							border-bottom: 1px solid rgba(255, 255, 255, 0.05);
							position: relative;
						}

						.action-icon-wrapper {
							width: 60px;
							height: 60px;
							margin: 0 auto 12px;
							border-radius: 50%;
							background: linear-gradient(135deg, rgba(45, 212, 191, 0.15), rgba(14, 158, 144, 0.15));
							display: flex;
							align-items: center;
							justify-content: center;
							position: relative;
						}

						.action-icon-wrapper::before {
							content: '';
							position: absolute;
							inset: -2px;
							border-radius: 50%;
							padding: 2px;
							background: linear-gradient(135deg, #2DD4BF, #0E9E90);
							-webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
							-webkit-mask-composite: xor;
							mask-composite: exclude;
						}

						.action-main-icon {
							font-size: 24px;
							color: #2DD4BF;
						}

						.action-title {
							color: #fff;
							font-weight: 700;
							font-size: 1.1rem;
							margin-bottom: 4px;
						}

						.action-subtitle {
							color: rgba(255, 255, 255, 0.4);
							font-size: 0.8rem;
							margin: 0;
						}

						/* Action Body */
						.action-body {
							padding: 16px 20px 20px;
						}

						/* Individual Action Buttons */
						.action-btn {
							display: flex;
							align-items: center;
							padding: 12px 16px;
							margin-bottom: 10px;
							border-radius: 12px;
							background: rgba(255, 255, 255, 0.03);
							border: 1px solid rgba(255, 255, 255, 0.05);
							text-decoration: none;
							transition: all 0.3s ease;
							position: relative;
							overflow: hidden;
						}

						.action-btn:last-child {
							margin-bottom: 0;
						}

						.action-btn::before {
							content: '';
							position: absolute;
							top: 0;
							left: 0;
							width: 3px;
							height: 100%;
							border-radius: 0 2px 2px 0;
							opacity: 0;
							transition: all 0.3s ease;
						}

						.action-btn:hover {
							transform: translateX(5px);
							background: rgba(255, 255, 255, 0.06);
							border-color: rgba(255, 255, 255, 0.1);
						}

						.action-btn:hover::before {
							opacity: 1;
						}

						/* Deposit Button */
						.action-btn-deposit::before {
							background: linear-gradient(180deg, #2DD4BF, #0E9E90);
						}

						.action-btn-deposit .action-btn-icon {
							background: rgba(45, 212, 191, 0.15);
							color: #2DD4BF;
						}

						.action-btn-deposit:hover .action-btn-icon {
							background: rgba(45, 212, 191, 0.25);
						}

						/* Withdraw Button */
						.action-btn-withdraw::before {
							background: linear-gradient(180deg, #ff6b6b, #dc2626);
						}

						.action-btn-withdraw .action-btn-icon {
							background: rgba(255, 107, 107, 0.15);
							color: #ff6b6b;
						}

						.action-btn-withdraw:hover .action-btn-icon {
							background: rgba(255, 107, 107, 0.25);
						}

						/* Portfolio Button */
						.action-btn-portfolio::before {
							background: linear-gradient(180deg, #4facfe, #00f2fe);
						}

						.action-btn-portfolio .action-btn-icon {
							background: rgba(79, 172, 254, 0.15);
							color: #4facfe;
						}

						.action-btn-portfolio:hover .action-btn-icon {
							background: rgba(79, 172, 254, 0.25);
						}

						/* History Button */
						.action-btn-history::before {
							background: linear-gradient(180deg, #f093fb, #f5576c);
						}

						.action-btn-history .action-btn-icon {
							background: rgba(240, 147, 251, 0.15);
							color: #f093fb;
						}

						.action-btn-history:hover .action-btn-icon {
							background: rgba(240, 147, 251, 0.25);
						}

						/* Referral Button */
						.action-btn-referral::before {
							background: linear-gradient(180deg, #ffecd2, #fcb69f);
						}

						.action-btn-referral .action-btn-icon {
							background: rgba(252, 182, 159, 0.15);
							color: #fcb69f;
						}

						.action-btn-referral:hover .action-btn-icon {
							background: rgba(252, 182, 159, 0.25);
						}

						.action-btn-icon {
							width: 40px;
							height: 40px;
							border-radius: 10px;
							display: flex;
							align-items: center;
							justify-content: center;
							font-size: 18px;
							flex-shrink: 0;
							transition: all 0.3s ease;
						}

						.action-btn-content {
							flex: 1;
							margin: 0 12px;
						}

						.action-btn-title {
							display: block;
							color: #fff;
							font-weight: 600;
							font-size: 0.9rem;
							transition: color 0.3s ease;
						}

						.action-btn:hover .action-btn-title {
							color: #2DD4BF;
						}

						.action-btn-desc {
							display: block;
							color: rgba(255, 255, 255, 0.3);
							font-size: 0.7rem;
							margin-top: 2px;
						}

						.action-btn-arrow {
							color: rgba(255, 255, 255, 0.2);
							font-size: 14px;
							transition: all 0.3s ease;
						}

						.action-btn:hover .action-btn-arrow {
							color: #2DD4BF;
							transform: translateX(3px);
						}

						/* Card Footer */
						.action-footer {
							padding: 12px 24px;
							border-top: 1px solid rgba(255, 255, 255, 0.05);
							text-align: center;
						}

						.action-footer-text {
							color: rgba(255, 255, 255, 0.2);
							font-size: 0.7rem;
						}

						/* Responsive */
						@media (max-width: 768px) {
							.action-card {
								margin-bottom: 20px;
							}

							.action-btn {
								padding: 10px 14px;
							}

							.action-btn-icon {
								width: 36px;
								height: 36px;
								font-size: 16px;
							}

							.action-btn-title {
								font-size: 0.85rem;
							}
						}

						@media (max-width: 576px) {
							.action-header {
								padding: 16px 18px 12px;
							}

							.action-body {
								padding: 12px 14px 16px;
							}

							.action-btn {
								padding: 8px 12px;
							}

							.action-btn-icon {
								width: 32px;
								height: 32px;
								font-size: 14px;
							}
						}
					</style>







					<div class="col-lg-12 col-xl-4">
						<div class="card overflow-hidden">
							<div class="card-header pb-1">
								<div class="card-title mb-2">Extra BreakDown </div>
							</div>
							<div class="card-body p-0">
								<div class="list-group projects-list border-0">


									<a href="javascript:void(0);"
										class="list-group-item list-group-item-action flex-column align-items-start border-0">
										<div class="d-flex w-100 justify-content-between">
											<p class="tx-13 mb-2 font-weight-semibold text-dark">Total Dividends</p>
											<h4 class="text-dark mb-0 font-weight-semibold text-dark tx-18">
												$<?php echo $rows['profit']; ?></h4>
										</div>
										<div class="d-flex w-100 justify-content-between">
											<span class="text-muted tx-12"><i
													class="fa fa-caret-up text-success me-1"></i> <span
													class="text-success"></span> </span>
											<span class="text-muted  tx-11">Current</span>
										</div>
									</a>
									<?php
									$get_withdrawals = mysqli_query($mysqli, "SELECT * FROM withdrawal WHERE status=1 and userid='" . $rows['id'] . "' ORDER BY id DESC");

									?>
									<a href="javascript:void(0);"
										class="list-group-item list-group-item-action flex-column align-items-start border-bottom-0  border-start-0 border-end-0 border-top">
										<div class="d-flex w-100 justify-content-between">
											<p class="tx-13 mb-2 font-weight-semibold text-dark">Total Withdrawals</p>
											<h4 class="text-dark mb-0 font-weight-semibold text-dark tx-18">
												<?php echo mysqli_num_rows($get_withdrawals); ?></h4>
										</div>
										<div class="d-flex w-100 justify-content-between">
											<span class="text-muted  tx-12"><i
													class="fa fa-caret-down text-danger me-1"></i><span
													class="text-danger"></span> </span>
											<span class="text-muted  tx-11">Current</span>
										</div>
									</a>
									<?php
									$get_investment = mysqli_query($mysqli, "SELECT * FROM investment WHERE userid='" . $rows['id'] . "' ORDER BY id DESC");
									$i = 0;
									$totalinvest = 0;
									$toaldaily = 0;
									while ($row = mysqli_fetch_assoc($get_investment)) {
										$i++;
										$totalinvest += $row['amount'];
										$toaldaily += $row['daily_roi'];


									}
									?>
									<a href="javascript:void(0);"
										class="list-group-item list-group-item-action flex-column align-items-start border-bottom-0  border-start-0 border-end-0 border-top">
										<div class="d-flex w-100 justify-content-between">
											<p class="tx-13 mb-2 font-weight-semibold text-dark">Total Deposit</p>
											<h4 class="text-dark mb-0 font-weight-semibold text-dark tx-18">
												$<?php echo $totalinvest; ?></h4>
										</div>
										<div class="d-flex w-100 justify-content-between">
											<span class="text-muted  tx-12"><i
													class="fa fa-caret-up text-success me-1"></i><span
													class="text-success"> </span> </span>
											<span class="text-muted  tx-11">Total</span>
										</div>
									</a>
									<?php
									$investment = mysqli_query($mysqli, "SELECT referal_link, id FROM `users` WHERE id='" . $rows['id'] . "'");
									$commission = 0;
									while ($user = mysqli_fetch_assoc($investment)) {


										$date = date("d") . " " . date("F") . " " . date("Y") . " , " . date("h") . " : " . date("i") . date("a");

										$userid = $user['id'];




										//find the person who refered himm account
										$getrefer = mysqli_query($mysqli, "SELECT id FROM users WHERE referred='" . $user['referal_link'] . "' ");


										while ($refer = mysqli_fetch_assoc($getrefer)) {

											$get_investment = mysqli_query($mysqli, "SELECT amount FROM investment WHERE userid='" . $refer['id'] . "'  ORDER BY id DESC");

											while ($in = mysqli_fetch_assoc($get_investment)) {

												$commission += $in['amount'];

											}






										}
									}

									?>
									<a href="javascript:void(0);"
										class="list-group-item list-group-item-action flex-column align-items-start border-bottom-0  border-start-0 border-end-0 border-top">
										<div class="d-flex w-100 justify-content-between">
											<p class="tx-13 mb-2 font-weight-semibold text-dark">Team Bonus
												<br />Commission Level </p>
											<h4 class="text-dark mb-0 font-weight-semibold text-dark tx-18">
												$<?php echo $commission; ?></h4>
										</div>
										<div class="d-flex w-100 justify-content-between">
											<span class="text-muted  tx-12"><i
													class="fa fa-caret-up text-success me-1"></i><span
													class="text-success"> </span> </span>
											<span class="text-muted  tx-11">Total</span>
										</div>
									</a>



								</div>
							</div>
						</div>




					</div>



				</div>




				<!-- row -->
				<div class="row row-sm">
					<div class="col-xl-6 col-md-12 col-lg-12">
						<div class="card">
							<div class="card-header bg-transparent pb-0">
								<div>
									<h3 class="card-title mb-2">Important Note</h3>
								</div>
							</div>
							<div class="card-body mt-0">
								<div class="latest-timeline mt-4">
									<ul class="timeline mb-0">
										<li>
											<div class="featured_icon1">
											</div>
										</li>
										<li class="mt-0 activity border br-5 p-2">
											<div><span class="tx-11 text-muted float-end"></span></div>
											<a href="javascript:void(0);" class="tx-12 text-dark">
												<p class="mb-1 font-weight-semibold text-dark tx-13">Chat with a
													customer support officer instantly </p>
											</a>
											<p class="text-muted mt-0 mb-0 tx-12">By clicking on the pop up messaging
												icon. </p>
										</li>

										<li>
											<div class="featured_icon1">
											</div>
										</li>
										<li class="mt-0 activity border br-5 p-2">
											<div><span class="tx-11 text-muted float-end"></span></div>
											<a href="javascript:void(0);" class="tx-12 text-dark">
												<p class="mb-1 font-weight-semibold text-dark tx-13">Always confirm all
													wallet addresses</p>
											</a>
											<p class="text-muted mt-0 mb-0 tx-12">
												before making any deposits </p>
										</li>

										<li>
											<div class="featured_icon1">
											</div>
										</li>
										<li class="mt-0 activity border br-5 p-2">
											<div><span class="tx-11 text-muted float-end"></span></div>
											<a href="javascript:void(0);" class="tx-12 text-dark">
												<p class="mb-1 font-weight-semibold text-dark">Ensure you copy your</p>
											</a>
											<p class="text-muted mt-0 mb-0 tx-12">referral link correctly. </p>
										</li>
										<li>
											<div class="featured_icon1">
											</div>
										</li>

										<li class="mt-0 activity border br-5 p-2 mb-3">
											<div><span class="tx-11 text-muted float-end"></span></div>
											<a href="javascript:void(0);" class="tx-12 text-dark">
												<p class="mb-1 font-weight-semibold text-dark tx-13">We recommend you
													enable the 2 factor Authenticator</p>
											</a>
											<p class="text-muted mt-0 mb-0 tx-12">security feature to serve as an extra
												layer of security for your account. </p>
										</li>


									</ul>
								</div>
							</div>
						</div>
					</div>




					<div class="col-xl-6 col-md-12 col-lg-6">
						<div class="card">
							<div class="card-header border-0">
								<h4 class="card-title">PORTFOLIO SHEET</h4>
								<div class="card-options">

								</div>
							</div>
							<div class="card-body">


								<div class="panel-group1" id="accordion1">

									<?php $my_investment = mysqli_query($mysqli, "SELECT * FROM investment WHERE userid='" . $rows['id'] . "' ORDER BY id DESC"); ?>
									<?php while ($row = mysqli_fetch_assoc($my_investment)) { ?>


										<div class="panel panel-default mb-4 overflow-hidden br-7">
											<div class="panel-heading1">
												<h4 class="panel-title1">
													<a class="accordion-toggle collapsed bg-gradient-primary"
														data-bs-toggle="collapse" data-parent="#accordion"
														href="#collapse<?php echo $row['id']; ?>"
														aria-expanded="false"><?php echo $row['name']; ?> ||
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
																	<td><?php if (date('N') >= 1) { ?><span
																				class="badge tag-success ">Credited</span>
																		<?php } else { ?><span
																				class="badge tag-danger ">Pending</span>
																		<?php } ?>
																	</td>
																</tr>

																<tr class="tt" id="tues<?php echo $row['id']; ?>">
																	<td>Tuesday</td>


																	<td>$ <?php echo $row['daily_roi']; ?></td>
																	<td><?php if (date('N') >= 2) { ?><span
																				class="badge tag-success ">Credited</span>
																		<?php } else { ?><span
																				class="badge tag-danger ">Pending</span>
																		<?php } ?>
																	</td>

																</tr>


																<tr class="tt" id="wed<?php echo $row['id']; ?>">
																	<td>Wednesday</td>


																	<td>$ <?php echo $row['daily_roi']; ?></td>
																	<td><?php if (date('N') >= 3) { ?><span
																				class="badge tag-success ">Credited</span>
																		<?php } else { ?><span
																				class="badge tag-danger ">Pending</span>
																		<?php } ?>
																	</td>

																</tr>

																<tr class="tt" id="thur<?php echo $row['id']; ?>">
																	<td>Thursday</td>


																	<td>$ <?php echo $row['daily_roi']; ?></td>
																	<td><?php if (date('N') >= 4) { ?><span
																				class="badge tag-success ">Credited</span>
																		<?php } else { ?><span
																				class="badge tag-danger ">Pending</span>
																		<?php } ?>
																	</td>

																</tr>

																<tr class="tt" id="fri<?php echo $row['id']; ?>">
																	<td>Friday</td>


																	<td>$ <?php echo $row['daily_roi']; ?></td>
																	<td><?php if (date('N') >= 5) { ?><span
																				class="badge tag-success ">Credited</span>
																		<?php } else { ?><span
																				class="badge tag-danger ">Pending</span>
																		<?php } ?>
																	</td>

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
					</div>



				</div>
				<!-- row closed -->

				<!-- row  -->
				<div class="row">
					<div class="col-12 col-sm-12">
						<div class="card">
							<div class="card-header">
								<h4 class="card-title">Lastest Transactions </h4>
							</div>
							<div class="card-body pt-0">
								<div class="table-responsive">
									<table class="table  table-bordered text-nowrap mb-0" id="example1">
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
											while ($rr = mysqli_fetch_assoc($getnote2)) {
												$i++;
												if ($rr['status'] == "Credited" || $rr['status'] == "Confirmed" || $rr['status'] == "Approved") {
													$type = "badge-pill bg-success ";
												} elseif ($rr['status'] == "Pending" || $rr['status'] == "Pending Confirmation") {
													$type = "badge-pill bg-danger ";
												}
												?>
												<tr>
													<td class="text-center">#<?php echo $i; ?></td>
													<td><?php echo $rr['action']; ?></td>
													<td><?php echo $rr['date']; ?></td>
													<td>$<?php echo $rr['amount']; ?></td>

													<td><span
															class="badge b<?php echo $type; ?>"><?php echo $rr['status']; ?></span>
													</td>
												</tr>

												<?php
											}
											?>


										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /row -->

			</div>
			<!-- /Container -->
		</div>
		<!-- /main-content -->




		<!-- DISCLAIMER MODAL -->
		<div class="modal fade" id="disclaimerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content custom-modal">

					<div class="modal-body">

						<h4 class="mb-3">Welcome to Quantum Scalp</h4>

						<p>
							By accessing or using this website, you acknowledge that you have read,
							understood, and agree to be bound by our <a href="../risk">Disclaimer</a>, <a
								href="../privacy">Privacy Policy</a>,
							and <a href="../terms">Terms of Service</a>.
						</p>

						<p>
							Quantum Scalp is a technology licensing provider — not a broker,
							investment advisor, or trading platform. We do not guarantee returns
							of any kind. All deployment activity carries risk, including the
							potential loss of capital.
						</p>

						<p>
							By proceeding, you confirm that you are solely responsible for your
							decisions and that your use of our services complies with all applicable
							laws in your jurisdiction.
						</p>

						<p class="text-warning">
							If you do not agree, please exit the website.
						</p>

						<div class="d-flex gap-2 mt-4">
							<button class="btn btn-success w-100" id="agreeBtn">I Agree</button>
							<button class="btn btn-outline-light w-100" id="exitBtn">Go Back</button>
						</div>

					</div>

				</div>
			</div>
		</div>






		<!-- Footer opened -->
		<div class="main-footer">
			<div class="col-md-12 col-sm-12 text-center">
				<div class="container-fluid pt-0 ht-100p">
					Copyright © <?php echo date('Y'); ?> All rights reserved
				</div>
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

	<!-- Internal Chart.Bundle js-->
	<script src="assets/plugins/chart.js/Chart.bundle.min.js"></script>

	<!-- Moment js -->
	<script src="assets/plugins/moment/moment.js"></script>

	<!-- INTERNAL Apexchart js -->
	<script src="assets/js/apexcharts.js"></script>

	<!--Internal Sparkline js -->
	<script src="assets/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>

	<!-- Moment js -->
	<script src="assets/plugins/raphael/raphael.min.js"></script>

	<!-- Internal Flot js -->
	<script src="assets/plugins/jquery.flot/jquery.flot.js"></script>
	<script src="assets/plugins/jquery.flot/jquery.flot.pie.js"></script>
	<script src="assets/plugins/jquery.flot/jquery.flot.resize.js"></script>

	<!-- Rating js-->
	<script src="assets/plugins/rating/jquery.rating-stars.js"></script>
	<script src="assets/plugins/rating/jquery.barrating.js"></script>

	<!--Internal  Perfect-scrollbar js -->
	<script src="assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
	<script src="assets/plugins/perfect-scrollbar/p-scroll.js"></script>

	<!-- Eva-icons js -->
	<script src="assets/js/eva-icons.min.js"></script>

	<!-- right-sidebar js -->
	<script src="assets/plugins/sidebar/sidebar.js"></script>
	<script src="assets/plugins/sidebar/sidebar-custom.js"></script>

	<!-- Sidebar js -->
	<script src="assets/plugins/side-menu/sidemenu.js"></script>

	<!-- Sticky js -->
	<script src="assets/js/sticky.js"></script>

	<!--Internal  index js -->
	<script src="assets/js/index2.js"></script>

	<!-- Internal Data tables -->
	<script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
	<script src="assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>
	<script src="assets/plugins/datatable/dataTables.responsive.min.js"></script>
	<script src="assets/plugins/datatable/responsive.bootstrap5.min.js"></script>

	<!-- INTERNAL Select2 js -->
	<script src="assets/plugins/select2/js/select2.full.min.js"></script>
	<script src="assets/js/select2.js"></script>

	<!-- Theme Color js -->
	<script src="assets/js/themecolor.js"></script>

	<!-- custom js -->
	<script src="assets/js/custom.js"></script>

</body>


<script>

	document.addEventListener("DOMContentLoaded", function () {

		const modalEl = document.getElementById('disclaimerModal');
		const modal = new bootstrap.Modal(modalEl);

		// Check if already agreed
		if (!localStorage.getItem("qs_disclaimer_agreed")) {
			modal.show();
		} else {
			//remove this part later, just for testing
			modal.show();
		}

		// Agree button
		document.getElementById("agreeBtn").addEventListener("click", function () {
			localStorage.setItem("qs_disclaimer_agreed", "true");
			modal.hide();
		});

		// Exit button
		document.getElementById("exitBtn").addEventListener("click", function () {
			window.location.href = "logout.php"; // change if needed
		});

	});

</script>

</html>