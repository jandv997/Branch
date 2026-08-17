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
	<title>Futures Live Trading | Quantum Scalp </title>

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
			padding: 20px;
		}

		h2 {
			margin: 20px 0;
			font-size: 14px;
			color: #aaa;
		}

		.table-container {
			overflow-x: auto;
		}

		table {
			width: 100%;
			border-collapse: collapse;
		}

		th {
			text-align: left;
			font-size: 12px;
			color: #888;
			padding: 10px;
			border-bottom: 1px solid #222;
		}

		td {
			padding: 12px 10px;
			border-bottom: 1px solid #111;
			font-size: 13px;
		}

		/* ROW STYLE */
		tr:hover {
			background: rgba(255, 255, 255, 0.03);
		}

		/* COLORS */
		.positive {
			color: #00c853;
		}

		.negative {
			color: #ff3d00;
		}

		.neutral {
			color: #999;
		}

		/* TWO ROW VALUES (current + predicted) */
		.rate-box {
			display: flex;
			flex-direction: column;
			gap: 4px;
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
						<span class="main-content-title mg-b-0 mg-b-lg-1">Futures Live Trading</span>
					</div>
					<div class="justify-content-center mt-2">
						<ol class="breadcrumb">
							<li class="breadcrumb-item tx-15"><a href="javascript:void(0);">Quantum</a></li>
							<li class="breadcrumb-item active" aria-current="page">Futures Live Trading </li>
						</ol>
					</div>
				</div>
				<!-- /breadcrumb -->


				<div class="row justify-content-center">
					<div class="col-lg-6">
						<div class="text-center ">
							<h3 style="text-transform:uppercase">Futures Live Trading (Funding Rates)</h3>


						</div>
					</div>
				</div>


				<div class="row justify-content-center">
					<div class="col-lg-6">
						<div class="text-center mb-5">


							<p>
							</p>

						</div>
					</div>
				</div>



				<!-- Row -->
				<div class="container">

					<h2>STABLECOIN MARGINED</h2>
					<div class="table-container">
						<table id="stableTable">
							<thead>
								<tr>
									<th>Coin</th>
									<th>Binance</th>
									<th>Bybit</th>
									<th>OKX</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>

					<h2>COIN MARGINED</h2>
					<div class="table-container">
						<table id="coinTable">
							<thead>
								<tr>
									<th>Coin</th>
									<th>Binance</th>
									<th>Bybit</th>
									<th>OKX</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
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

	<!-- eva-icons js -->
	<script src="assets/js/eva-icons.min.js"></script>

	<!-- Theme Color js -->
	<script src="assets/js/themecolor.js"></script>

	<!-- custom js -->
	<script src="assets/js/custom.js"></script>



	<script>
		const coins = [
			"BTCUSDT",
			"ETHUSDT",
			"XRPUSDT",
			"BNBUSDT",
			"SOLUSDT",
			"ADAUSDT"
		];

		// FETCH BINANCE FUNDING
		async function getFundingRates() {
			const res = await fetch("https://fapi.binance.com/fapi/v1/premiumIndex");
			const data = await res.json();

			return data.filter(item => coins.includes(item.symbol));
		}

		// FORMAT COLORS
		function formatRate(rate) {
			const num = parseFloat(rate) * 100;

			let cls = "neutral";
			if (num > 0) cls = "positive";
			if (num < 0) cls = "negative";

			return `<span class="${cls}">${num.toFixed(4)}%</span>`;
		}

		// SIMULATE PREDICTED RATE
		function predict(rate) {
			let variation = (Math.random() * 0.02 - 0.01);
			return parseFloat(rate) + variation;
		}

		// BUILD TABLE
		function renderTable(data, tableId) {
			const tbody = document.querySelector(`#${tableId} tbody`);
			tbody.innerHTML = "";

			data.forEach(item => {

				let row = `
	  <tr>
		<td>${item.symbol.replace("USDT", "")}</td>

		<td>
		  <div class="rate-box">
			${formatRate(item.lastFundingRate)}
			${formatRate(predict(item.lastFundingRate))}
		  </div>
		</td>

		<td>
		  <div class="rate-box">
			${formatRate(item.lastFundingRate * 0.9)}
			${formatRate(predict(item.lastFundingRate * 0.9))}
		  </div>
		</td>

		<td>
		  <div class="rate-box">
			${formatRate(item.lastFundingRate * 1.1)}
			${formatRate(predict(item.lastFundingRate * 1.1))}
		  </div>
		</td>

	  </tr>
	`;

				tbody.innerHTML += row;
			});
		}

		// INIT
		async function init() {
			const data = await getFundingRates();

			renderTable(data, "stableTable");
			renderTable(data, "coinTable");
		}

		// AUTO REFRESH
		setInterval(init, 5000);

		init();
	</script>


</body>

</html>