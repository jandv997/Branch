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

$data = isset($response->data) ? $response->data : array();




?>
<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="UTF-8">
	<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">


	<!-- Title -->
	<title>Quantum Verse | Quantum Scalp </title>

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
	<link href="assets/css/qs-verse.css" rel="stylesheet">

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

				<div class="qs-verse">
					<?php
					$verseTab = 'purchase';
					include('inc/verse-tabs.php');
					?>

					<div class="qs-verse-grid">
						<?php
						$getinvest = mysqli_query($mysqli, "SELECT * FROM investment_packages WHERE `type`=1 ");
						$i = 0;
						while ($row = mysqli_fetch_assoc($getinvest)):
							$i++;
							$min = number_format((float) $row['min_amount'], 2);
							$max = number_format((float) (isset($row['max_amount']) ? $row['max_amount'] : 0), 2);
							$pct = number_format((float) $row['percent'], 2);
							$term = htmlspecialchars($row['duration']);
							?>
							<article class="qs-verse-card">
								<div class="qs-verse-card__icon"><?php echo qs_verse_planet(); ?></div>
								<h3 class="qs-verse-card__name"><?php echo htmlspecialchars($row['name']); ?></h3>
								<p class="qs-verse-card__sub"><?php echo nl2br(htmlspecialchars($row['info1'])); ?></p>
								<div class="qs-verse-meta">
									<div class="qs-verse-meta__row">Range <span>$<?php echo $min; ?>–$<?php echo $max; ?></span></div>
									<div class="qs-verse-meta__row">Term <span><?php echo $term; ?> months</span></div>
									<div class="qs-verse-meta__row">Daily upside cap <span class="qs-verse-cap">Up to <?php echo htmlspecialchars($pct); ?>%</span></div>
								</div>
								<div class="qs-verse-risk">
									Up to <?php echo htmlspecialchars($pct); ?>% daily (capped) — based on real Q-Core performance. Returns vary day to day and can be negative. Losses are not capped. Not guaranteed.
								</div>
								<button type="button" class="qs-verse-select" data-qs-buy
									data-id="<?php echo htmlspecialchars($row['id']); ?>"
									data-name="<?php echo htmlspecialchars($row['name']); ?>"
									data-min="<?php echo htmlspecialchars($row['min_amount']); ?>"
									data-max="<?php echo htmlspecialchars(isset($row['max_amount']) ? $row['max_amount'] : 0); ?>"
									data-percent="<?php echo htmlspecialchars($row['percent']); ?>"
									data-compound="<?php echo htmlspecialchars($row['compound_percent']); ?>"
									data-term="<?php echo htmlspecialchars($row['duration']); ?>">Select Portfolio</button>
							</article>
						<?php endwhile; ?>

						<?php if ($i === 0): ?>
							<div class="qs-verse-empty">
								<i class="fas fa-cubes"></i>
								<h5>No active investment packages available</h5>
								<p>New Quantum opportunities will appear soon.</p>
							</div>
						<?php endif; ?>
					</div>

					<details class="qs-verse-terms">
						<summary>
							<span class="qs-verse-terms__warn">
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
									<path d="M12 9v4"></path>
									<path d="M12 17h.01"></path>
									<path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h16.9a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"></path>
								</svg>
							</span>
							Full performance &amp; risk terms — how returns actually work
							<i class="fe fe-chevron-down qs-verse-terms__chevron"></i>
						</summary>
						<div class="qs-verse-terms__body">
							<p>Daily figures shown on each portfolio are an upside cap, not a guaranteed return. Actual performance tracks live Q-Core results and can be lower, including negative days. Losses are not capped.</p>
							<p>Term length, allocation range, and daily cap are defined by the selected package. Capital remains at risk for the full term. Past performance is not a reliable indicator of future results.</p>
							<p>Quantum Scalp is a technology licensing provider — not a broker or investment advisor. Review the site Disclaimer, Privacy Policy, and Terms of Service before deploying capital.</p>
						</div>
					</details>
				</div>

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
	<?php include('inc/verse-purchase-modal.php'); ?>
	<script src="assets/js/qs-verse-buy.js"></script>

</body>

</html>
