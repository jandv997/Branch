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
		<title>DEXs Live Trading | Quantum Scalp </title>

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
			 .controls {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }

        select,
        input,
        button {
            padding: 8px;
            background: #1b263b;
            border: none;
            color: #fff;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #1b263b;
        }

        th,
        td {
            padding: 12px;
            border-bottom: 1px solid #2a3f5f;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
        }

        .buy {
            background: rgba(0, 255, 0, 0.2);
            color: #00ff9d;
        }

        .sell {
            background: rgba(255, 0, 0, 0.2);
            color: #ff6b6b;
        }

        .dex {
            background: #ff007a;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .network {
            background: #3a5cff;
            padding: 5px 10px;
            border-radius: 5px;
        }

        a {
            color: #4da6ff;
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
						  <span class="main-content-title mg-b-0 mg-b-lg-1">DEXs Live Trading</span>
						</div>
						<div class="justify-content-center mt-2">
							<ol class="breadcrumb">
								<li class="breadcrumb-item tx-15"><a href="javascript:void(0);">Quantum</a></li>
								<li class="breadcrumb-item active" aria-current="page">DEXs Live Trading </li>
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
											 <div class="controls mb-4">
        <select id="network">
            <option value="eth">Ethereum</option>
            <option value="bsc">BSC</option>
            <option value="arbitrum">Arbitrum</option>
        </select>

        <select id="dex">
            <option value="all">All DEXs</option>
            <option value="uniswap">Uniswap</option>
            <option value="pancakeswap">PancakeSwap</option>
        </select>

        <input type="number" id="minVolume" placeholder="Min USD (e.g 1000)">
        <input type="number" id="limit" value="20">

        <button onclick="fetchTrades()">Refresh</button>
    </div>

										</div>
										<div class="table-responsive  export-table">
											<table id="file-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
												<thead>
													<tr>
													
												<th class="border-bottom-0">Time</th>
												<th class="border-bottom-0">Network</th>
												<th class="border-bottom-0">DEX</th>
												<th class="border-bottom-0">Pair</th>
												<th class="border-bottom-0">Side</th>
												<th class="border-bottom-0">Price</th>
												<th class="border-bottom-0">Amount</th>
												<th class="border-bottom-0">Profit (USD)</th>
												<th class="border-bottom-0">TX</th>

													</tr>
												</thead>
												<tbody id="tradeTable">

													
                                      
		
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

async function fetchTrades() {

    const chain = document.getElementById("network").value;
    const dexFilter = document.getElementById("dex").value.toLowerCase();
    const minVolume = parseFloat(document.getElementById("minVolume").value || 0);
    const limit = parseInt(document.getElementById("limit").value || 20);

    try {

        const res = await fetch(`fetch_trades.php?chain=${chain}&limit=${limit}`);

        const data = await res.json();

        if (!data || !data.result) {

            console.error("No data");

            return;

        }

        const trades = data.result;
		console.log(trades);
       

        const table = document.getElementById("tradeTable");
        table.innerHTML = "";

        let count = 0;

        trades.forEach(t => {

            if (!t.bought || !t.sold) return;

            const dexName = (t.exchangeName || "Unknown").toLowerCase();

            // FILTER DEX
            if (dexFilter !== "all" && !dexName.includes(dexFilter)) return;

            const usdValue = Math.abs(parseFloat(t.totalValueUsd || 0));

            // FILTER VOLUME
            if (usdValue < minVolume) return;

            if (count >= limit) return;

            const type = t.transactionType === "buy" ? "BUY" : "SELL";

            const pair = t.pairLabel || `${t.bought.symbol}/${t.sold.symbol}`;

            const amount = Math.abs(parseFloat(t.bought.amount || 0));

            const price = parseFloat(t.bought.usdPrice || 0);

            const time = new Date(t.blockTimestamp).toLocaleTimeString();

            // TX link per network
            let txLink = "#";
            if (chain === "eth") txLink = `https://etherscan.io/tx/${t.transactionHash}`;
            if (chain === "bsc") txLink = `https://bscscan.com/tx/${t.transactionHash}`;
            if (chain === "arbitrum") txLink = `https://arbiscan.io/tx/${t.transactionHash}`;

            const row = `
                <tr>
                    <td>${time}</td>
                    <td>${chain.toUpperCase()}</td>
                    <td>${t.exchangeName || "Unknown"}</td>
                    <td>${pair}</td>
                    <td class="${type === 'BUY' ? 'buy' : 'sell'}">${type}</td>
                    <td>$${price.toFixed(6)}</td>
                    <td>${amount.toFixed(4)} ${t.bought.symbol}</td>
                    <td class="buy">$${usdValue.toFixed(2)}</td>
                    <td><a href="${txLink}" target="_blank">View</a></td>
                </tr>
            `;

            table.innerHTML += row;
            count++;

        });

    } catch (err) {
        console.error("Error fetching trades:", err);
    }
}

// Events
document.getElementById("network").onchange = fetchTrades;
document.getElementById("dex").onchange = fetchTrades;

// Initial load
fetchTrades();
</script>


	</body>
</html>