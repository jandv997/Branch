<?php
session_start();

include('connection.php');
include_once __DIR__ . '/inc/disclaimer.php';
qs_ensure_disclaimer_agreed_column($mysqli);

if (isset($_POST['agree_disclaimer']) && isset($_SESSION['id'])) {
	qs_mark_disclaimer_agreed($mysqli, $_SESSION['id']);
	qs_set_disclaimer_cookie();
	header('Content-Type: application/json');
	header('Cache-Control: no-store');
	echo json_encode(array('ok' => true));
	exit;
}


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

$disclaimerAgreed = false;
if (isset($_SESSION['id']) && isset($rows) && is_array($rows)) {
	$disclaimerAgreed = !empty($rows['disclaimer_agreed']);
	if ($disclaimerAgreed) {
		qs_set_disclaimer_cookie();
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
	<title> Overview | Quantum Scalp </title>

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
	<link href="assets/css/qs-overview.css" rel="stylesheet">


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

				<?php include('inc/overview-body.php'); ?>

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
		if (!modalEl || typeof bootstrap === 'undefined') return;
		const modal = new bootstrap.Modal(modalEl);
		var agreedServer = <?php echo $disclaimerAgreed ? 'true' : 'false'; ?>;

		function qsHasDisclaimer() {
			if (agreedServer) return true;
			try {
				if (localStorage.getItem('qs_disclaimer_agreed') === 'true') return true;
			} catch (e) {}
			return document.cookie.indexOf('qs_disclaimer_agreed=1') !== -1;
		}

		function qsRememberDisclaimer() {
			try { localStorage.setItem('qs_disclaimer_agreed', 'true'); } catch (e) {}
			var expires = new Date(Date.now() + 400 * 24 * 60 * 60 * 1000).toUTCString();
			document.cookie = 'qs_disclaimer_agreed=1; expires=' + expires + '; path=/; SameSite=Lax';
			try {
				fetch('dashboard.php', {
					method: 'POST',
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
					body: 'agree_disclaimer=1',
					credentials: 'same-origin'
				});
			} catch (e) {}
		}

		if (qsHasDisclaimer()) {
			if (!agreedServer) qsRememberDisclaimer();
		} else {
			modal.show();
		}

		document.getElementById("agreeBtn").addEventListener("click", function () {
			qsRememberDisclaimer();
			modal.hide();
		});

		// Exit button
		document.getElementById("exitBtn").addEventListener("click", function () {
			window.location.href = "logout.php"; // change if needed
		});

		document.querySelectorAll('.qs-ov-ranges button').forEach(function (btn) {
			btn.addEventListener('click', function () {
				document.querySelectorAll('.qs-ov-ranges button').forEach(function (b) { b.classList.remove('is-active'); });
				btn.classList.add('is-active');
			});
		});

		if (window.Chart && window.qsOverviewData) {
			var d = window.qsOverviewData;
			var alloc = document.getElementById('qsAllocChart');
			if (alloc) {
				var w = d.wallet, s = d.staking, r = d.referral;
				if (w + s + r <= 0) { w = s = r = 1; }
				new Chart(alloc.getContext('2d'), {
					type: 'doughnut',
					data: {
						labels: ['Main', 'Staking', 'Referral'],
						datasets: [{ data: [w, s, r], backgroundColor: ['#2DD4BF', '#0E9E90', '#00E676'], borderWidth: 0 }]
					},
					options: {
						cutoutPercentage: 72,
						legend: { display: false },
						tooltips: { enabled: true }
					}
				});
			}
			var perf = document.getElementById('qsPerfChart');
			if (perf && d.hasPerf) {
				new Chart(perf.getContext('2d'), {
					type: 'line',
					data: {
						labels: ['W1', 'W2', 'W3', 'W4'],
						datasets: [{
							data: [0, d.profit * 0.35, d.profit * 0.7, d.profit],
							borderColor: '#2DD4BF',
							backgroundColor: 'rgba(45,212,191,0.12)',
							borderWidth: 2,
							pointRadius: 0
						}]
					},
					options: {
						legend: { display: false },
						scales: {
							xAxes: [{ gridLines: { color: 'rgba(255,255,255,0.04)' }, ticks: { fontColor: '#64748b' } }],
							yAxes: [{ gridLines: { color: 'rgba(255,255,255,0.04)' }, ticks: { fontColor: '#64748b' } }]
						}
					}
				});
			}
		}

	});

</script>

</html>