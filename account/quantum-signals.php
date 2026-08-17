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
	<title>Quantum Signals | Quantum Scalp </title>

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
		/* container max-width and smooth */
		.signal-container {
			max-width: 1400px;
			margin: 0 auto;
		}

		/* header with pulse animation */
		.signal-header {
			text-align: center;
			margin-bottom: 2rem;
		}

		.signal-header h1 {
			font-size: 2.2rem;
			font-weight: 800;
			background: linear-gradient(135deg, #BEF264, #4ade80, #22c55e);
			-webkit-background-clip: text;
			background-clip: text;
			color: transparent;
			letter-spacing: -0.3px;
			display: inline-flex;
			align-items: center;
			gap: 12px;
		}

		.signal-header h1 i {
			background: none;
			-webkit-background-clip: unset;
			background-clip: unset;
			color: #4ade80;
			font-size: 2rem;
			filter: drop-shadow(0 0 6px #22c55e80);
			animation: pulse-glow 1.8s infinite;
		}

		@keyframes pulse-glow {
			0% {
				text-shadow: 0 0 0 #22c55e;
				opacity: 0.7;
			}

			50% {
				text-shadow: 0 0 12px #4ade80;
				opacity: 1;
			}

			100% {
				text-shadow: 0 0 0 #22c55e;
				opacity: 0.7;
			}
		}

		.signal-stats {
			display: flex;
			justify-content: center;
			gap: 1.5rem;
			margin-top: 0.8rem;
			font-size: 0.8rem;
			font-weight: 500;
			color: #9ca3af;
		}

		.signal-stats span i {
			margin-right: 6px;
			color: #4ade80;
		}

		/* modern compact grid - kill excess spacing */
		.signal-grid-custom {
			margin-top: 1rem;
		}

		.signal-card-compact {
			background: rgba(12, 20, 28, 0.85);
			backdrop-filter: blur(2px);
			border: 1px solid rgba(74, 222, 128, 0.18);
			border-radius: 1.25rem;
			transition: all 0.2s cubic-bezier(0.2, 0.9, 0.4, 1.1);
			height: 100%;
			display: flex;
			flex-direction: column;
			box-shadow: 0 8px 18px rgba(0, 0, 0, 0.3);
			position: relative;
			overflow: hidden;
		}

		/* subtle animated gradient border on hover */
		.signal-card-compact::before {
			content: '';
			position: absolute;
			inset: 0;
			border-radius: 1.25rem;
			padding: 1px;
			background: linear-gradient(125deg, rgba(74, 222, 128, 0.3), rgba(34, 197, 94, 0.05));
			-webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
			mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
			-webkit-mask-composite: xor;
			mask-composite: exclude;
			opacity: 0;
			transition: opacity 0.25s;
			pointer-events: none;
		}

		.signal-card-compact:hover::before {
			opacity: 1;
		}

		.signal-card-compact:hover {
			transform: translateY(-3px);
			border-color: rgba(74, 222, 128, 0.4);
			box-shadow: 0 18px 30px -8px rgba(0, 0, 0, 0.5);
			background: rgba(16, 26, 34, 0.95);
		}

		/* compact card body — kill spacing */
		.card-body-compact {
			padding: 1rem 1.1rem 1rem 1.1rem !important;
			display: flex;
			flex-direction: column;
			gap: 0.5rem;
		}

		/* badge + icon row */
		.signal-badge-row {
			display: flex;
			align-items: center;
			justify-content: space-between;
			margin-bottom: 0.25rem;
		}

		.signal-badge-new {
			background: linear-gradient(110deg, #1e2a2a, #132021);
			border-radius: 40px;
			font-size: 0.7rem;
			font-weight: 700;
			padding: 0.22rem 0.7rem;
			letter-spacing: 0.3px;
			color: #bef264;
			border: 1px solid rgba(74, 222, 128, 0.5);
			display: inline-flex;
			align-items: center;
			gap: 5px;
			backdrop-filter: blur(2px);
		}

		.signal-badge-new i {
			font-size: 0.65rem;
			color: #4ade80;
		}

		.live-dot {
			width: 8px;
			height: 8px;
			background-color: #22c55e;
			border-radius: 50%;
			display: inline-block;
			box-shadow: 0 0 6px #22c55e;
			animation: blink 1.2s infinite;
			margin-right: 5px;
		}

		@keyframes blink {

			0%,
			100% {
				opacity: 1;
				transform: scale(1);
			}

			50% {
				opacity: 0.4;
				transform: scale(0.9);
			}
		}

		/* signal text — compact but readable */
		.signal-text-compact {
			font-size: 0.8rem;
			line-height: 1.45;
			font-weight: 500;
			color: #e2e8f0;
			background: rgba(0, 20, 15, 0.3);
			padding: 0.55rem 0.7rem;
			border-radius: 16px;
			border-left: 2px solid #4ade80;
			margin: 0.2rem 0 0.2rem 0;
			word-break: break-word;
			white-space: pre-line;
		}

		/* timestamp area with icon compact */
		.signal-meta-compact {
			display: flex;
			align-items: center;
			justify-content: flex-end;
			gap: 8px;
			font-size: 0.65rem;
			font-weight: 500;
			color: #8ca3b5;
			letter-spacing: 0.2px;
			margin-top: 0.2rem;
			border-top: 1px dashed rgba(74, 222, 128, 0.2);
			padding-top: 0.6rem;
		}

		.signal-meta-compact i {
			font-size: 0.7rem;
			color: #4ade80;
		}

		/* column gutter smaller for compactness */
		.row-custom {
			--bs-gutter-x: 0.9rem;
			--bs-gutter-y: 0.9rem;
			display: flex;
			flex-wrap: wrap;
			margin-top: calc(-1 * var(--bs-gutter-y));
			margin-right: calc(-0.5 * var(--bs-gutter-x));
			margin-left: calc(-0.5 * var(--bs-gutter-x));
		}

		.row-custom>* {
			padding-right: calc(var(--bs-gutter-x) * 0.5);
			padding-left: calc(var(--bs-gutter-x) * 0.5);
			margin-top: var(--bs-gutter-y);
		}

		/* empty / loading state */
		.empty-state {
			text-align: center;
			padding: 3rem 1rem;
			background: rgba(12, 20, 28, 0.6);
			border-radius: 2rem;
			color: #9ca3af;
			backdrop-filter: blur(4px);
		}

		.empty-state i {
			font-size: 3rem;
			margin-bottom: 1rem;
			color: #4ade80;
			opacity: 0.6;
		}

		/* subtle scrollbar */
		::-webkit-scrollbar {
			width: 6px;
		}

		::-webkit-scrollbar-track {
			background: #0f1720;
		}

		::-webkit-scrollbar-thumb {
			background: #2b6e4e;
			border-radius: 10px;
		}

		@media (max-width: 768px) {
			.signal-text-compact {
				font-size: 0.75rem;
			}

			.card-body-compact {
				padding: 0.85rem !important;
			}
		}
	</style>


</head>

<body class="ltr main-body app sidebar-mini dark-theme">

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
						<span class="main-content-title mg-b-0 mg-b-lg-1">Quantum Signals</span>
					</div>
					<div class="justify-content-center mt-2">
						<ol class="breadcrumb">
							<li class="breadcrumb-item tx-15"><a href="javascript:void(0);">Quantum</a></li>
							<li class="breadcrumb-item active" aria-current="page"> Signals</li>
						</ol>
					</div>
				</div>
				<!-- /breadcrumb -->


				<div class="card primary-custom-card1">
					<div class="card-body">
						<div class="row">
							<div class="col-xl-5 col-lg-6 col-md-12 col-sm-12">
								<div class="prime-card"><img class="img-fluid" src="../assets/img/bg/uo_bg.png" alt="">
								</div>
							</div>
							<div class="col-xl-7 col-lg-6 col-md-12 col-sm-12">
								<div class="text-justified align-items-center">



									<div class="signal-header mt-4">
										<h1><i class="fas fa-chart-line"></i> Quantum Signal <i
												class="fas fa-waveform"></i></h1>
										<div class="signal-stats">
											<span><i class="fas fa-bolt"></i> Live Intelligence</span>
											<span><i class="fas fa-sync-alt"></i> Real-time updates</span>
											<span><i class="fas fa-database"></i> Last 100 signals</span>
										</div>

											<Br /><Br />

									<a href="membership"
										class="btn btn-primary mb-3 shadow text-center">Purchase A
										License</a>
									</div>



								
								</div>
							</div>
						</div>
					</div>
				</div>



				<div class="signal-container mb-5">
					<!-- catchy header with animation + stats (live feel) -->


					<!-- signal grid (compact, removed excess spacing, modern cards) -->
					<div class="signal-grid-custom">
						<?php
						// Original DB query preserved
						//pull record that falls withing today.
						$result = $mysqli->query("
            SELECT * FROM bot_messages WHERE DATE(created_at) = CURDATE()
            ORDER BY id DESC 
            LIMIT 100
        ");

						// Check if any rows exist
						$signalCount = $result->num_rows;
						?>

						<?php if ($signalCount > 0): ?>
							<div class="row row-custom">
								<?php while ($row = $result->fetch_assoc()):
									// Format date nicely
									$timestamp = strtotime($row['created_at']);
									$formattedDate = date("d M Y · g:i A", $timestamp);
									// Adding extra micro trend: random icon per signal? but we keep light variation
									$signalPreview = htmlspecialchars($row['message_text']);
									?>
									<div class="col-md-6 col-lg-4">
										<div class="signal-card-compact">
											<div class="card-body-compact">
												<div class="signal-badge-row">
													<span class="signal-badge-new">
														<i class="fas fa-broadcast-tower"></i>
														<span class="live-dot"></span> LIVE SIGNAL
													</span>
													<i class="fas fa-chart-simple"
														style="color:#4ade80; font-size:0.75rem; opacity:0.8;"></i>
												</div>
												<!-- signal text area with compact spacing -->
												<div class="signal-text-compact">
													<?= $signalPreview ?>
												</div>
												<!-- timestamp with icon and direction flair -->
												<div class="signal-meta-compact">
													<i class="far fa-clock"></i> <?= $formattedDate ?>
													<span style="flex:1"></span>
													<i class="fas fa-arrow-trend-up" style="font-size:0.65rem;"></i>
												</div>
											</div>
										</div>
									</div>
								<?php endwhile; ?>
							</div>
						<?php else: ?>
							<div class="empty-state">
								<i class="fas fa-satellite-dish"></i>
								<h5>No signals available</h5>
								<p>Waiting for incoming trading intelligence ...</p>
							</div>
						<?php endif; ?>
					</div>

					<!-- Live pulse footer (just for design) -->

				</div>

				<!-- optional auto-refresh effect mimicking live data (no auto-reload, but just micro interaction) -->
				<script>
					// optional hover micro-interaction with cursor, just for delight
					const cards = document.querySelectorAll('.signal-card-compact');
					cards.forEach(card => {
						card.addEventListener('mouseenter', (e) => {
							// subtle extra glow - purely aesthetic
							card.style.transition = 'all 0.18s ease-out';
						});
					});

				</script>




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
</body>
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

<!-- SweetAlert -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- custom js -->
<script src="assets/js/custom.js"></script>

</html>