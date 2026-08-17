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
	<title>Market Place | Quantum Group </title>

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
						<span class="main-content-title mg-b-0 mg-b-lg-1">Market Place</span>
					</div>
					<div class="justify-content-center mt-2">
						<ol class="breadcrumb">
							<li class="breadcrumb-item tx-15"><a href="javascript:void(0);">Quantum</a></li>
							<li class="breadcrumb-item active" aria-current="page">Market Place </li>
						</ol>
					</div>
				</div>
				<!-- /breadcrumb -->


				<section class="hero">

					<div class="hero-text fade-in delay-1">

						<h1>Our <span>Market Place</span> </h1>
						<i class="fas fa-cube"></i>
							Quantum VERSE
							<i class="fas fa-waveform"></i>
							<div class="glow-line"></div>
						<p>Quantum Scalp provides portfolios designed to address the diverse goals of every client,
							<br /> ensuring tailored solutions for individual needs and goals.</p>


					</div>

					<!-- CARD -->

				</section>










				<div class="quantum-verse-container">
					<!-- Elegant header with Quantum VERSE branding -->
					<div class="verse-header">
					
					</div>

					<!-- Investment Packages Row (fully redesigned) -->
					<div class="row g-4">
						<?php
						// original query for investment packages where type = 1
						$getinvest = mysqli_query($mysqli, "SELECT * FROM investment_packages WHERE `type`=1 ");
						$i = 0;
						while ($row = mysqli_fetch_assoc($getinvest)):
							$i++;
							?>
							<div class="col-lg-4 col-md-6">
								<div class="verse-card">
									<!-- Dynamic image with elegant hover zoom -->
									<div class="card-img-wrapper">
										<img class="card-img-top-fixed"   src="<?php echo htmlspecialchars($row['img']); ?>"
											alt="<?php echo htmlspecialchars($row['name']); ?>">
										<div class="img-overlay-glow"></div>
									</div>

									<div class="verse-card-body">
										<!-- Title + badge -->
										<div class="d-flex justify-content-between align-items-start">
											<h4 class="verse-title"><?php echo htmlspecialchars($row['name']); ?></h4>
											<span class="verse-badge"><i class="fas fa-chart-line"></i> VERSE+</span>
										</div>

										<!-- Info1 / short description -->
										<div class="verse-description">
											<?php echo nl2br(htmlspecialchars($row['info1'])); ?>
										</div>

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
													<span
														style="font-weight:300; font-size:13px;">Up to</span> <?php echo htmlspecialchars($row['percent']); ?>%
												</div>
											</div>

											<div class="stat-item">
												<span class="stat-label"><i class="fas fa-percent"></i> Staking
													Returns</span>
												<div class="stat-value">
													<i class="fas fa-chart-simple"></i>
													<span
														style="font-weight:300; font-size:13px;">Up to</span> <?php echo htmlspecialchars($row['compound_percent']); ?>%
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
										</div>

										<!-- Purchase button animated -->
										<a href="purchase?id=<?php echo $row['id']; ?>" class="btn-quantum">
											<i class="fas fa-rocket"></i> Make Purchase <i class="fas fa-arrow-right"></i>
										</a>

										<div class="divider-custom"></div>

										<!-- Additional infohead (dynamic) with icon -->
										<div class="info-footer">
											<i class="fas fa-lightbulb me-2" style="color:#2ba0c9;"></i>
											<?php echo ($row['infohead1']); ?>
										</div>
									</div>
								</div>
							</div>
						<?php endwhile; ?>

						<!-- In case no packages exist, elegant empty state (optional) -->
						<?php if ($i === 0): ?>
							<div class="col-12 text-center py-5">
								<div class="p-5 bg-white rounded-4 shadow-sm">
									<i class="fas fa-cubes fa-3x text-secondary mb-3"></i>
									<h5>No active investment packages available</h5>
									<p class="text-muted">New Quantum opportunities will appear soon.</p>
								</div>
							</div>
						<?php endif; ?>
					</div>

					<!-- subtle brand watermark / futuristic footer -->
					<div class="text-center mt-5 pt-3">
						<small class="text-muted"><i class="fas fa-shield-alt"></i> Quantum VERSE • AI powered returns |
							dynamic allocation</small>
					</div>
				</div>

				<!-- micro interaction script for card hover enhancements -->
				<script>
					// Optional: add ripple on buttons? small effect
					document.querySelectorAll('.btn-quantum').forEach(btn => {
						btn.addEventListener('click', function (e) {
							// just adds a tiny ripple of class - no functionality broken
							let ripple = document.createElement('span');
							ripple.classList.add('ripple-effect');
							this.appendChild(ripple);
							setTimeout(() => ripple.remove(), 500);
						});
					});
					// style for dynamic ripple (if needed)
					const style = document.createElement('style');
					style.textContent = `
		.btn-quantum {
			position: relative;
			overflow: hidden;
		}
		.ripple-effect {
			position: absolute;
			border-radius: 50%;
			background-color: rgba(255,255,255,0.5);
			width: 100px;
			height: 100px;
			margin-top: -50px;
			margin-left: -50px;
			animation: ripple 0.5s linear forwards;
			pointer-events: none;
		}
		@keyframes ripple {
			0% { transform: scale(0); opacity: 0.6; }
			100% { transform: scale(2); opacity: 0; }
		}
	`;
					document.head.appendChild(style);
				</script>










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

	<!-- eva-icons js -->
	<script src="assets/js/eva-icons.min.js"></script>

	<!-- Theme Color js -->
	<script src="assets/js/themecolor.js"></script>

	<!-- custom js -->
	<script src="assets/js/custom.js"></script>

</body>

</html>