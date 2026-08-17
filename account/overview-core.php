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
	<title>Quantum Core - Overview | Quantum Group </title>

	<!-- Favicon -->
	<link rel="icon" href="assets/img/brand/favicon.png" type="image/x-icon" />

	<!-- Icons css -->
	<link href="assets/css/icons.css" rel="stylesheet">

	<!-- bootstrap css-->
	<link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

	<!--- Style css --->
	<link href="assets/css/style.css" rel="stylesheet">
	<link href="assets/css/style-dark.css" rel="stylesheet">
	<link href="assets/css/style-transparent.css" rel="stylesheet">

	<!---Skin modes css-->
	<link href="assets/css/skin-modes.css" rel="stylesheet" />

	<!--- Animations css-->
	<link href="assets/css/animate.css" rel="stylesheet">


	<link href="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/css/ion.rangeSlider.min.css"
		rel="stylesheet" />


	<!-- Jquery js-->
	<script src="assets/plugins/jquery/jquery.min.js"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js"></script>

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
						<span class="main-content-title mg-b-0 mg-b-lg-1">Quantum Core </span>
					</div>
					<div class="justify-content-center mt-2">
						<ol class="breadcrumb">
							<li class="breadcrumb-item tx-15"><a href="javascript:void(0);">Quantum</a></li>
							<li class="breadcrumb-item active" aria-current="page">Overview </li>
						</ol>
					</div>
				</div>
				<!-- /breadcrumb -->


				<div class="row justify-content-center">
					<div class="col-lg-6">
						<div class="text-center ">
							<h3 style="text-transform:uppercase"></h3>

						</div>
					</div>
				</div>

				<section class="hero">

					<div class="hero-text fade-in delay-1">

						<h1> <span>Crypto arbitrage</span> </h1>
						<i class="fas fa-cube"></i>
							Quantum Core
							<i class="fas fa-waveform"></i>
							<div class="glow-line"></div>
						

					</div>

					<!-- CARD -->

				</section>



				<div class="row justify-content-center">
					<div class="col-lg-6">
						<div class="text-center mb-5">



						</div>
					</div>
				</div>


				<div class="row">

					<div class="col-md-12">

						<div class="card">
							<div class="card-body">
								<p>Crypto arbitrage is a trading strategy that profits from temporary price
									discrepancies for the same (or related) cryptocurrencies across different exchanges,
									platforms, trading pairs, or market segments. These inefficiencies arise due to the
									crypto market’s fragmentation, varying liquidity, regional demand differences (e.g.,
									the famous “Kimchi Premium” in South Korea), technological differences between
									centralized exchanges (CEXs) and decentralized exchanges (DEXs), regulatory
									barriers, and delays in price updates.
								</p>
								<p>Unlike traditional markets, crypto’s 24/7 nature and global, borderless trading
									create frequent (though often small and fleeting) opportunities. Traders—often using
									bots or scanners—buy low in one place and sell high in another, capturing the spread
									after fees. While marketed as “low-risk,” it carries real risks like execution
									delays, fees, slippage, and competition from sophisticated players.<br /><br />
									Here are the main types of crypto arbitrage traded by our proprietary technology:</p>

									<img src="img/bg-core.jpeg" />
							</div>
						</div>
					</div>


					<div class="col-md-12">

						<div class="card">
							<div class="card-body">
								<p>1. Spatial (Cross-Exchange / Simple) Arbitrage
									The most straightforward and common type. You buy a cryptocurrency on one exchange
									where it’s cheaper and sell it (nearly simultaneously) on another where it’s more
									expensive.
									Example: BTC trades at $60,000 on Binance but $60,150 on Coinbase. Buy on Binance,
									transfer or use pre-funded accounts, and sell on Coinbase for ~$150 profit per BTC
									(minus fees and any transfer time).
								</p>

								<img src="img/spatial-1.jpeg" />

							</div>
						</div>

					</div>




					<div class="col-md-12">

						<div class="card">
							<div class="card-body">
								<p>2. Triangular (Intra-Exchange) Arbitrage
								Exploits inconsistencies among three trading pairs on the same exchange. You trade in a closed loop (e.g., BTC → ETH → USDT → BTC) and end up with more of the starting asset than you began with. <br/>
								How it works: If the implied cross-rate doesn't match the direct rate (due to temporary imbalances), profit emerges. These are often automated because they last milliseconds.<br/>
								Example (simplified from common diagrams): <br/>
								•  Start with BTC <br/>
								•  Trade BTC for ETH (at a favorable rate) <br/>
								•  Trade ETH for USDT <br/>
								•  Trade USDT back to BTC → net positive BTC <br/>
								</p>

								<img src="img/triangle-1.jpeg" />

							</div>
						</div>

					</div>




					<div class="col-md-12">

						<div class="card">
							<div class="card-body">
								<p>3. Statistical Arbitrage (Stat Arb)
A quantitative, data-driven approach that uses algorithms, historical correlations, mean-reversion models, cointegration, or machine learning to identify and trade pricing anomalies across multiple assets or pairs. Positions may be held longer than pure arbitrage (minutes to hours/days). <br/><br/>
Example: Two correlated coins (e.g., BTC and ETH) diverge from their historical relationship → short the overperformer and long the underperformer, expecting convergence.
								</p>

								<img src="img/stats-1.jpeg" />

							</div>
						</div>

					</div>





					<div class="col-md-12">

						<div class="card">
							<div class="card-body">
								<p>4. Decentralized (DEX / CEX-DEX) Arbitrage <br/>
									Prices on DEXs (which use automated market makers/AMMs like Uniswap or Curve) often differ from CEX order books or other DEXs due to liquidity pool dynamics, impermanent loss, or bridging delays. <br/><br/>
									Traders buy low on one and sell high on the other, sometimes bridging assets across chains. This has grown with DeFi’s expansion.
								</p>

								<img src="img/dexs-1.jpeg" />

							</div>
						</div>

					</div>





					<div class="col-md-12">

						<div class="card">
							<div class="card-body">
								<p>5. Flash Loan Arbitrage (DeFi-Specific)
One of the most innovative types. You borrow a massive amount (millions of dollars) instantly via a flash loan on protocols like Aave or dYdX, execute arbitrage trades in a single atomic blockchain transaction (buy low → sell high), repay the loan + fee, and keep the profit—all within one block. No collateral or upfront capital is required (as long as the transaction succeeds). 
<br/><br/>
Example: Borrow $5M USDC via flash loan → buy an underpriced token on DEX A → sell on DEX B at a premium → repay loan + tiny fee → pocket the difference (e.g., $10k–$50k+ per trade in good conditions).
								</p>

								<img src="img/flash-1.jpeg" />

							</div>
						</div>

					</div>





					
					<div class="col-md-12">

						<div class="card">
							<div class="card-body">
								<p>6. Futures / Derivatives Arbitrage (Cash-and-Carry, Funding Rate, Basis Trading)
Exploit differences between spot prices and futures/perpetual contract prices, or funding rate payments in perpetual futures.
•  Cash-and-carry: Buy spot + short futures (or vice versa) when the basis (futures premium/discount) is mispriced; hold to convergence at expiry. <br/><br/>
•  Funding rate arb: Long or short perpetuals to collect (or pay) the periodic funding fee when rates are extreme.
								</p>

								<img src="img/future-1.jpeg" />

							</div>
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

	<script>
		<?php if ($rows['lupa_flex'] == 0) { ?>
			$(document).ready(function () {

				$("#welcome").modal('show');

			});

		<?php } ?>
	</script>
</body>

<?php

if (isset($_POST['lupa-flex'])) {

	$userid = $rows['id'];


	$updaetUsder = mysqli_query($mysqli, "UPDATE `users` SET `lupa_flex`='1', lupa_flex_date=now()  WHERE id='$userid'");

	?>
	<script>
		location = location
	</script>
	<?php

}



?>

</html>