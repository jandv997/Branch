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
	<title>Resources | Quantum Scalp </title>

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
		.resource-hero {
			background: linear-gradient(135deg, #1e3a8a 0%, #0ea5e9 100%);
			color: #f8fafc;
		}

		.resource-hero .btn-light {
			color: #0f172a;
			font-weight: 600;
		}

		.resource-card {
			transition: transform 0.2s ease, box-shadow 0.2s ease;
		}

		.resource-card:hover {
			transform: translateY(-4px);
			box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
		}

		.resource-icon {
			width: 48px;
			height: 48px;
			line-height: 48px;
		}
	</style>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/css/ion.rangeSlider.min.css"
		rel="stylesheet" />


	<!-- Jquery js-->
	<script src="assets/plugins/jquery/jquery.min.js"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js"></script>



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
						<span class="main-content-title mg-b-0 mg-b-lg-1">Resources </span>
					</div>
					<div class="justify-content-center mt-2">
						<ol class="breadcrumb">
							<li class="breadcrumb-item tx-15"><a href="javascript:void(0);">Quantum</a></li>
							<li class="breadcrumb-item active" aria-current="page">Resources </li>
						</ol>
					</div>
				</div>
				<!-- /breadcrumb -->

				<div class="row">
					<div class="col-12">
						<div class="card resource-hero overflow-hidden border-0 mb-4">
							<div class="card-body p-4 p-lg-5">
								<div class="row align-items-center">
									<div class="col-lg-8">
										<h1 class="mb-2">Quantum Scalp Resources Hub</h1>
										<p class="mb-3 text-white-75">
											Find official product documentation, safety tips, and quick-start guidance for your Quantum Scalp experience.
											These resources are designed to help you understand the platform, identify smart trading habits, and keep your account secure.
										</p>
										<ul class="list-unstyled mb-0 text-white-75">
											<li class="mb-2"><i class="fe fe-check-circle text-white me-2"></i>Official user guide for Quantum Scalp AI</li>
											<li class="mb-2"><i class="fe fe-check-circle text-white me-2"></i>Step-by-step setup and account management advice</li>
											<li><i class="fe fe-check-circle text-white me-2"></i>Available in different languages.</li>
										</ul>
									</div>
									<div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
										<a href="img/quantum-saclp-ai-complete-v8.pdf" class="btn btn-light btn-lg">Download official guide</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-xl-6 col-md-6">
						<div class="card resource-card mb-4">
							<div class="card-body">
								<div class="d-flex align-items-center mb-4">
									<div class="avatar-xs resource-icon me-3 rounded-circle bg-primary bg-soft text-primary d-flex align-items-center justify-content-center">
										<i class="fe fe-book-open"></i>
									</div>
									<div>
										<h5 class="font-size-16 mb-1">Official Quantum Scalp AI Guide - English</h5>
										<!-- <p class="text-muted mb-0">Keep your trading strategy clear with the latest product handbook, including market overview, account setup, and safe usage recommendations.</p> -->
									</div>
								</div>
								<p class="text-muted">This document is the central reference for understanding how Quantum Scalp AI works, what tools are available, and what strategies we use.</p>
								<div class="d-flex align-items-center justify-content-between mt-4">
									<span class="badge badge-soft-success font-size-12">PDF Document</span>
									<a href="img/quantum-saclp-ai-complete-v8.pdf" class="btn btn-primary">Download now</a>
								</div>
							</div>
						</div>
					</div>

					<!-- <div class="col-xl-6 col-md-6">
						<div class="card resource-card mb-4">
							<div class="card-body">
								<div class="d-flex align-items-center mb-4">
									<div class="avatar-xs resource-icon me-3 rounded-circle bg-success bg-soft text-success d-flex align-items-center justify-content-center">
										<i class="fe fe-info"></i>
									</div>
									<div>
										<h5 class="font-size-16 mb-1">Why this matters</h5>
										<p class="text-muted mb-0">Understand the full value of your Quantum dashboard and avoid common onboarding mistakes.</p>
									</div>
								</div>
								<ul class="list-unstyled mb-0">
									<li class="mb-3"><i class="fe fe-check text-success me-2"></i>Learn how signals are interpreted</li>
									<li class="mb-3"><i class="fe fe-check text-success me-2"></i>Discover secure withdrawal best practices</li>
									<li><i class="fe fe-check text-success me-2"></i>Review account rules before trading</li>
								</ul>
								<div class="mt-4 p-3 rounded-3 bg-light">
									<h6 class="mb-2">Need more help?</h6>
									<p class="mb-0 text-muted">Visit your dashboard help center or contact our support team for step-by-step assistance with your account tools.</p>
								</div>
							</div>
						</div>
					</div> -->

				</div>

				<div class="row">
					<div class="col-12">
						<div class="card resource-card mb-4">
							<div class="card-body">
								<h5 class="mb-3">Quick guide sections included</h5>
								<div class="row">
									<div class="col-sm-6 mb-3">
										<div class="d-flex">
											<div class="me-3 text-primary"><i class="fe fe-cpu"></i></div>
											<div>
												<strong>AI strategy overview</strong>
												<p class="mb-0 text-muted">How Quantum Scalp uses AI to analyze market behavior.</p>
											</div>
										</div>
									</div>
									<div class="col-sm-6 mb-3">
										<div class="d-flex">
											<div class="me-3 text-primary"><i class="fe fe-shield"></i></div>
											<div>
												<strong>Account security</strong>
												<p class="mb-0 text-muted">Essential checks to keep your funds and login secure.</p>
											</div>
										</div>
									</div>
									<div class="col-sm-6 mb-3">
										<div class="d-flex">
											<div class="me-3 text-primary"><i class="fe fe-award"></i></div>
											<div>
												<strong>Using the dashboard</strong>
												<p class="mb-0 text-muted">Learn where to monitor trades, balances, and performance.</p>
											</div>
										</div>
									</div>
									<div class="col-sm-6 mb-3">
										<div class="d-flex">
											<div class="me-3 text-primary"><i class="fe fe-headphones"></i></div>
											<div>
												<strong>Support channels</strong>
												<p class="mb-0 text-muted">Quick access to assistance when you need answers fast.</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
<!-- Row -->

				<!-- <div class="row mt-3">



					<div class="col-xl-6 col-md-6">
						<h4 class="mt-3 mb-3">How to Make Referrals</h4>

						<video width="" height="" controls style="width:100%">
							<source src="img/how-to-referral.mp4" type="video/mp4">

							Your browser does not support the video tag.
						</video>

					</div>



					<div class="col-xl-6 col-md-6">
						<h4 class="mt-3 mb-3">How to Sign Up</h4>

						<video width="" height="" controls style="width:100%">
							<source src="img/how-to-sign-in.mp4" type="video/mp4">

							Your browser does not support the video tag.
						</video>

					</div>



				</div> -->
				<!-- end row -->





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

	<!-- Bootstrap js -->
	<script src="assets/plugins/bootstrap/js/popper.min.js"></script>
	<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

	<!-- Moment js -->
	<script src="assets/plugins/moment/moment.js"></script>

	<!-- P-scroll js -->
	<script src="assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
	<script src="assets/plugins/perfect-scrollbar/p-scroll.js"></script>



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

	<!-- custom js -->
	<script src="assets/js/custom.js"></script>

</body>

</html>