<?php
session_start();

include('connection.php');


//check if session id is set if it is redirect to login
if(!isset($_SESSION['id'])){
	
	header("location:index");
}else{

$get_user = mysqli_query($mysqli,"SELECT * FROM users WHERE id='".$_SESSION['id']."' ");
$rows = mysqli_fetch_assoc($get_user);
    if(isset($_SESSION['2fa'])){

        if( ($_SESSION['2fa'] =="no" or $_SESSION['2fa'] =="pending") and $rows['2fa']==1){
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
		<title>CEXs Live Trading(Arbitrage) | Quantum Scalp </title>

		<!-- Favicon -->
		<link rel="icon" href="assets/img/brand/favicon.png" type="image/x-icon"/>

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

		    table {
        width: 100%;
        border-collapse: collapse;
        background: #0f172a;
        border-radius: 8px;
        overflow: hidden;
    }

    thead {
        background: linear-gradient(90deg, #5b4ce6, #b8892c) !important;
        color: #1a1a1a;
        font-weight: bold;
    }

    th, td {
        padding: 14px;
        text-align: left;
        font-size: 14px;
    }

    tbody tr {
        border-bottom: 1px solid #1e293b;
    }

    tbody tr:hover {
        background: #1e293b;
    }

    th {
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 0.5px;
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
						  <span class="main-content-title mg-b-0 mg-b-lg-1">CEXs Live Trading(Arbitrage)</span>
						</div>
						<div class="justify-content-center mt-2">
							<ol class="breadcrumb">
								<li class="breadcrumb-item tx-15"><a href="javascript:void(0);">Quantum</a></li>
								<li class="breadcrumb-item active" aria-current="page">CEXs Live Trading(Arbitrage) </li>
							</ol>
						</div>
					</div>
					<!-- /breadcrumb -->

						<!-- Row -->
						<div class="row row-sm">
							<div class="col-lg-12">
								<div class="card custom-card overflow-hidden">
									<div class="card-body">
										<div>
											

										</div>
										<div class="table-responsive  export-table">
											<table  class="table table-bordered text-nowrap key-buttons border-bottom">
												<thead>
													<tr>
													
											
												<th class="border-bottom-0">Coins</th>
												<th class="border-bottom-0">Purchased From</th>
												<th class="border-bottom-0">Sold To</th>
												<th class="border-bottom-0">Value Bought</th>
												<th class="border-bottom-0">Value Sold</th>
												<th class="border-bottom-0">Spread (USDT)</th>
												<th class="border-bottom-0">Purchase Volume</th>
												<th class="border-bottom-0">Profit(USDT)</th>

													</tr>
												</thead>
												<tbody id="table-body">

													
                                      
		
												</tbody>
											</table>
										</div>
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
					Copyright © <?php echo date('Y'); ?>  All rights reserved
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
    { id: "solana", symbol: "SOL" },
    { id: "cardano", symbol: "ADA" },
    { id: "polkadot", symbol: "DOT" },
    { id: "chainlink", symbol: "LINK" },
    { id: "ripple", symbol: "XRP" },
    { id: "dogecoin", symbol: "DOGE" },
    { id: "litecoin", symbol: "LTC" },
    { id: "bitcoin-cash", symbol: "BCH" },
    { id: "avalanche-2", symbol: "AVAX" },
    { id: "tron", symbol: "TRX" },
    { id: "stellar", symbol: "XLM" }
];

const exchanges = ["BINANCE", "KRAKEN", "KUCOIN", "OKX", "HUOBI", "BITMEX", "BITFINEX"];

function randomExchange() {
    return exchanges[Math.floor(Math.random() * exchanges.length)];
}

async function fetchPrices() {
    const ids = coins.map(c => c.id).join(",");
    const url = `https://api.coingecko.com/api/v3/simple/price?ids=${ids}&vs_currencies=usd`;

    const res = await fetch(url);
    const data = await res.json();

    const tbody = document.getElementById("table-body");
    tbody.innerHTML = "";

    coins.forEach(coin => {
        const basePrice = data[coin.id]?.usd || 0;

        // Simulate arbitrage prices
        const buyPrice = basePrice * (1 - Math.random() * 0.01);
        const sellPrice = basePrice * (1 + Math.random() * 0.01);
        const spread = sellPrice - buyPrice;

        const volume = (Math.random() * 1000).toFixed(2);
        const interest = (spread * volume).toFixed(4);

        const row = `
            <tr>
                <td>${coin.symbol}</td>
                <td class="text-warning">${randomExchange()}</td>
                <td class="text-warning">${randomExchange()}</td>
                <td class="text-danger">${buyPrice.toFixed(6)}</td>
                <td class="text-danger">${sellPrice.toFixed(6)}</td>
                <td>${spread.toFixed(4)}</td>
                <td>${volume}</td>
                <td class="text-success">${interest}</td>
            </tr>
        `;

        tbody.innerHTML += row;
    });
}

// Load initially
fetchPrices();

// Refresh every 10 seconds
setInterval(fetchPrices, 10000);
</script>


	</body>
</html>