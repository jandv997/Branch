<?php
session_start();

include('connection.php');

if (!isset($_SESSION['id'])) {
	header("location:index");
	exit;
}

$get_user = mysqli_query($mysqli, "SELECT * FROM users WHERE id='" . $_SESSION['id'] . "' ");
$rows = mysqli_fetch_assoc($get_user);
if (isset($_SESSION['2fa'])) {
	if (($_SESSION['2fa'] == "no" or $_SESSION['2fa'] == "pending") and $rows['2fa'] == 1) {
		header("location:index");
		exit;
	}
}

$allowedTabs = array('overview', 'cex', 'dex', 'futures', 'signals');
$qcoreTab = isset($_GET['tab']) ? strtolower(trim((string) $_GET['tab'])) : 'overview';
if (!in_array($qcoreTab, $allowedTabs, true)) {
	$qcoreTab = 'overview';
}

$signalRows = array();
if ($qcoreTab === 'signals') {
	$result = $mysqli->query("
		SELECT * FROM bot_messages WHERE DATE(created_at) = CURDATE()
		ORDER BY id DESC
		LIMIT 100
	");
	if ($result) {
		while ($row = $result->fetch_assoc()) {
			$signalRows[] = $row;
		}
	}
}

$tabTitles = array(
	'overview' => 'Q-Core',
	'cex' => 'Q-Core · CEX Live',
	'dex' => 'Q-Core · DEX Live',
	'futures' => 'Q-Core · Futures Live',
	'signals' => 'Q-Core · Quantum Signals',
);
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php echo htmlspecialchars($tabTitles[$qcoreTab]); ?> | Quantum Scalp</title>
	<link rel="icon" href="assets/img/brand/favicon.png" type="image/x-icon" />
	<link href="assets/css/icons.css" rel="stylesheet">
	<link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="assets/css/style.css" rel="stylesheet">
	<link href="assets/css/style-dark.css" rel="stylesheet">
	<link href="assets/css/style-transparent.css" rel="stylesheet">
	<link href="assets/css/skin-modes.css" rel="stylesheet" />
	<link href="assets/css/animate.css" rel="stylesheet">
	<link href="assets/css/qs-member.css" rel="stylesheet">
	<link href="assets/css/qs-qcore.css" rel="stylesheet">
</head>

<body class="ltr main-body app sidebar-mini dark-theme">

	<div id="global-loader">
		<img src="img/favicon.png" width="50" class="loader-img" alt="Loader">
	</div>

	<div class="page">
		<div>
			<?php include('header.php'); ?>
		</div>

		<div class="main-content app-content">
			<div class="main-container container-fluid">
				<div class="qs-qcore">
					<?php include('inc/qcore-tabs.php'); ?>

					<?php if ($qcoreTab === 'overview') { ?>
						<section class="qs-qcore-engine">
							<h2>Q-Core Engine</h2>
							<p>Six parallel arbitrage strategies analyzed simultaneously. Quantum-inspired optimization on classical infrastructure.</p>
							<div class="qs-qcore-pills">
								<span class="qs-qcore-pill">Q-Core Engine ONLINE</span>
								<span class="qs-qcore-pill">Market Data CONNECTED</span>
								<span class="qs-qcore-pill">Execution CONNECTED</span>
								<span class="qs-qcore-pill">Blockchain ACTIVE</span>
							</div>
						</section>

						<div class="qs-qcore-grid">
							<article class="qs-qcore-card">
								<span class="qs-qcore-card__num">01</span>
								<span class="qs-qcore-card__icon" aria-hidden="true">
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M7 7H3v4M17 17h4v-4M3 7l6 6M21 17l-6-6"/></svg>
								</span>
								<h3>Cross-Exchange Arbitrage</h3>
								<p>Compares prices for the same digital assets across different centralized exchanges and identifies potential price discrepancies.</p>
								<div class="qs-qcore-path">Exchange A → Asset → Exchange B</div>
							</article>
							<article class="qs-qcore-card">
								<span class="qs-qcore-card__num">02</span>
								<span class="qs-qcore-card__icon" aria-hidden="true">
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M12 4 3.5 19h17L12 4z"/></svg>
								</span>
								<h3>Triangular Arbitrage</h3>
								<p>Analyzes relationships between three trading pairs to identify potential pricing inefficiencies within a single exchange or market environment.</p>
								<div class="qs-qcore-path">BTC → USDT → ETH → BTC</div>
							</article>
							<article class="qs-qcore-card">
								<span class="qs-qcore-card__num">03</span>
								<span class="qs-qcore-card__icon" aria-hidden="true">
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M4 16l5-5 4 4 7-8"/><path d="M14 7h6v6"/></svg>
								</span>
								<h3>Statistical Arbitrage</h3>
								<p>Machine-learning models analyze historical relationships and market behavior to identify statistical deviations that may represent potential opportunities.</p>
								<div class="qs-qcore-path">Historical correlation + AI signal</div>
							</article>
							<article class="qs-qcore-card">
								<span class="qs-qcore-card__num">04</span>
								<span class="qs-qcore-card__icon" aria-hidden="true">
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="6" cy="12" r="2.2"/><circle cx="18" cy="6" r="2.2"/><circle cx="18" cy="18" r="2.2"/><path d="M8 12h8M16.2 7.6 8.8 11M16.2 16.4 8.8 13"/></svg>
								</span>
								<h3>DEX Arbitrage</h3>
								<p>Monitors decentralized exchanges and automated market makers for pricing differences and liquidity imbalances.</p>
								<div class="qs-qcore-path">DEX A → Blockchain → DEX B</div>
							</article>
							<article class="qs-qcore-card">
								<span class="qs-qcore-card__num">05</span>
								<span class="qs-qcore-card__icon" aria-hidden="true">
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M13 3 4 14h7l-1 7 10-12h-7l0-6z"/></svg>
								</span>
								<h3>Flash Loan Arbitrage</h3>
								<p>Where technically and economically viable, identifies atomic on-chain arbitrage opportunities involving flash-loan infrastructure. Dependent on available liquidity, blockchain conditions, transaction costs, smart-contract conditions, and execution feasibility.</p>
								<div class="qs-qcore-path">Borrow → Swap → Repay (atomic)</div>
							</article>
							<article class="qs-qcore-card">
								<span class="qs-qcore-card__num">06</span>
								<span class="qs-qcore-card__icon" aria-hidden="true">
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M5 19V10M12 19V5M19 19v-7"/></svg>
								</span>
								<h3>Futures &amp; Derivatives Arbitrage</h3>
								<p>Analyzes relationships between spot and derivatives markets, including funding-rate differences, basis spreads, calendar spreads, and other relative-value opportunities.</p>
								<div class="qs-qcore-path">Spot ↔ Perp / Funding / Basis</div>
							</article>
						</div>
					<?php } ?>

					<?php if ($qcoreTab === 'cex') { ?>
						<section class="qs-qcore-panel">
							<div class="qs-qcore-panel__head">
								<div>
									<h2 class="qs-qcore-panel__title">CEX · Cross-Exchange Spreads</h2>
									<p class="qs-qcore-panel__meta" id="qs-cex-meta">Binance · OKX · Kraken · refreshing</p>
								</div>
								<span class="qs-qcore-exec">REAL PRICES • SIMULATED EXECUTION</span>
							</div>
							<div class="table-responsive">
								<table class="qs-qcore-table">
									<thead>
										<tr>
											<th>Asset</th>
											<th>Bought From</th>
											<th>Sold To</th>
											<th>Buy</th>
											<th>Sell</th>
											<th>Spread</th>
											<th>Route</th>
										</tr>
									</thead>
									<tbody id="qs-cex-body"></tbody>
								</table>
							</div>
							<p class="qs-qcore-note">Live CoinGecko prices with simulated venue spreads. Read-only; Quantum Scalp does not place live trades in this demo.</p>
						</section>
					<?php } ?>

					<?php if ($qcoreTab === 'dex') { ?>
						<section class="qs-qcore-panel">
							<div class="qs-qcore-panel__head">
								<div>
									<h2 class="qs-qcore-panel__title">DEX · CEX ↔ DEX Spreads</h2>
									<p class="qs-qcore-panel__meta" id="qs-dex-meta">Uniswap v3 · OKX · Ethereum · just now</p>
								</div>
								<span class="qs-qcore-exec">REAL PRICES • SIMULATED EXECUTION</span>
							</div>
							<div class="table-responsive">
								<table class="qs-qcore-table">
									<thead>
										<tr>
											<th>Asset</th>
											<th>Uniswap (DEX)</th>
											<th>OKX (CEX)</th>
											<th>Spread</th>
											<th>Route</th>
										</tr>
									</thead>
									<tbody id="qs-dex-body"></tbody>
								</table>
							</div>
							<p class="qs-qcore-note">Real market prices versus a simulated Uniswap v3 / OKX spread. Read-only; Quantum Scalp does not place live trades in this demo.</p>
						</section>
					<?php } ?>

					<?php if ($qcoreTab === 'futures') { ?>
						<section class="qs-qcore-panel">
							<div class="qs-qcore-panel__head">
								<div>
									<h2 class="qs-qcore-panel__title">Futures Live Trading (Funding Rates)</h2>
									<p class="qs-qcore-panel__meta" id="qs-fut-meta">Binance · Bybit · OKX · refreshing</p>
								</div>
								<span class="qs-qcore-exec">REAL PRICES • SIMULATED EXECUTION</span>
							</div>
							<p class="qs-qcore-note qs-qcore-note--tight">Each venue cell shows the current funding rate (top) and a predicted next rate (bottom).</p>

							<h3 class="qs-qcore-section">Stablecoin Margined</h3>
							<div class="table-responsive">
								<table class="qs-qcore-table" id="stableTable">
									<thead>
										<tr>
											<th>Coin</th>
											<th>Binance</th>
											<th>Bybit</th>
											<th>OKX</th>
										</tr>
									</thead>
									<tbody>
										<tr><td colspan="4" class="qs-qcore-empty">Loading funding rates…</td></tr>
									</tbody>
								</table>
							</div>

							<h3 class="qs-qcore-section">Coin Margined</h3>
							<div class="table-responsive">
								<table class="qs-qcore-table" id="coinTable">
									<thead>
										<tr>
											<th>Coin</th>
											<th>Binance</th>
											<th>Bybit</th>
											<th>OKX</th>
										</tr>
									</thead>
									<tbody>
										<tr><td colspan="4" class="qs-qcore-empty">Loading funding rates…</td></tr>
									</tbody>
								</table>
							</div>
							<p class="qs-qcore-note">Live funding rates for BTC, ETH, XRP, BNB, SOL, and ADA. Binance is used when available; otherwise OKX. Bybit / remaining venue columns use the original 0.9× / 1.1× offsets plus a predicted next rate. Auto-refreshes every 5 seconds. Read-only; Quantum Scalp does not place live trades in this demo.</p>
						</section>
					<?php } ?>

					<?php if ($qcoreTab === 'signals') { ?>
						<div class="qs-qcore-pending">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M4.5 10.5c0-4.1 3.4-7.5 7.5-7.5s7.5 3.4 7.5 7.5c0 5-7.5 11-7.5 11S4.5 15.5 4.5 10.5z"/><circle cx="12" cy="10.5" r="2.2"/></svg>
							<div>
								<strong>LIVE DATA CONNECTION PENDING</strong>
								<span>Quantum Signals — showing clearly-labeled DEMO DATA until Q-Core APIs are connected.</span>
							</div>
						</div>
						<div class="qs-qcore-signals">
							<article class="qs-signal-card">
								<div class="qs-signal-card__top">
									<span class="qs-signal-card__kind">Cross-Exchange</span>
									<span class="qs-signal-status is-active">ACTIVE</span>
								</div>
								<h3>BTC/USDT</h3>
								<p>Informational technology output — not financial advice.</p>
							</article>
							<article class="qs-signal-card">
								<div class="qs-signal-card__top">
									<span class="qs-signal-card__kind">Statistical</span>
									<span class="qs-signal-status is-watch">MONITORING</span>
								</div>
								<h3>ETH/USDT</h3>
								<p>Informational technology output — not financial advice.</p>
							</article>
						</div>
						<?php if (count($signalRows) > 0) { ?>
							<div class="qs-qcore-feed">
								<?php foreach ($signalRows as $signal) { ?>
									<article class="qs-qcore-feed-item">
										<p><?php echo htmlspecialchars($signal['message_text']); ?></p>
										<time><?php echo htmlspecialchars(date('d M Y · g:i A', strtotime($signal['created_at']))); ?></time>
									</article>
								<?php } ?>
							</div>
						<?php } ?>
					<?php } ?>
				</div>
			</div>
		</div>

		<div class="main-footer">
			<div class="container-fluid pt-0 ht-100p">
				Copyright © <?php echo date('Y'); ?> All rights reserved
			</div>
		</div>
	</div>

	<a href="#top" id="back-to-top"><i class="las la-arrow-up"></i></a>
	<script src="assets/plugins/jquery/jquery.min.js"></script>
	<script src="assets/plugins/bootstrap/js/popper.min.js"></script>
	<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
	<script src="assets/plugins/moment/moment.js"></script>
	<script src="assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
	<script src="assets/plugins/perfect-scrollbar/p-scroll.js"></script>
	<script src="assets/plugins/select2/js/select2.full.min.js"></script>
	<script src="assets/plugins/side-menu/sidemenu.js"></script>
	<script src="assets/js/sticky.js"></script>
	<script src="assets/plugins/sidebar/sidebar.js"></script>
	<script src="assets/plugins/sidebar/sidebar-custom.js"></script>
	<script src="assets/js/eva-icons.min.js"></script>
	<script src="assets/js/themecolor.js"></script>
	<script src="assets/js/custom.js"></script>

	<?php if ($qcoreTab === 'cex') { ?>
	<script>
	(function () {
		var coins = [
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
		var exchanges = ["BINANCE", "KRAKEN", "KUCOIN", "OKX", "HUOBI", "BITMEX", "BITFINEX"];
		function pick() { return exchanges[Math.floor(Math.random() * exchanges.length)]; }
		function fmt(n, d) { return Number(n).toFixed(d); }
		async function load() {
			try {
				var ids = coins.map(function (c) { return c.id; }).join(",");
				var res = await fetch("https://api.coingecko.com/api/v3/simple/price?ids=" + ids + "&vs_currencies=usd");
				var data = await res.json();
				var body = document.getElementById("qs-cex-body");
				if (!body) return;
				body.innerHTML = "";
				coins.forEach(function (coin) {
					var base = (data[coin.id] && data[coin.id].usd) || 0;
					var buy = base * (1 - Math.random() * 0.01);
					var sell = base * (1 + Math.random() * 0.01);
					var spreadPct = base ? ((sell - buy) / base) * 100 : 0;
					var from = pick(), to = pick();
					if (from === to) to = pick();
					body.innerHTML += "<tr><td>" + coin.symbol + "/USD</td><td>" + from + "</td><td>" + to + "</td><td>" + fmt(buy, 6) + "</td><td>" + fmt(sell, 6) + "</td><td class=\"qs-qcore-spread\">" + fmt(spreadPct, 4) + "%</td><td class=\"qs-qcore-route\">" + from + " → " + to + "</td></tr>";
				});
				var meta = document.getElementById("qs-cex-meta");
				if (meta) meta.textContent = "Binance · OKX · Kraken · 0s ago";
			} catch (e) {}
		}
		load();
		setInterval(load, 10000);
	})();
	</script>
	<?php } ?>

	<?php if ($qcoreTab === 'dex') { ?>
	<script>
	(function () {
		var assets = [
			{ id: "uniswap", symbol: "UNI" },
			{ id: "aave", symbol: "AAVE" },
			{ id: "bitcoin", symbol: "BTC" },
			{ id: "ethereum", symbol: "ETH" },
			{ id: "chainlink", symbol: "LINK" },
			{ id: "pepe", symbol: "PEPE" }
		];
		function fmt(n) {
			if (n >= 1000) return n.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
			if (n >= 1) return n.toFixed(4);
			return n.toFixed(8);
		}
		async function load() {
			try {
				var ids = assets.map(function (a) { return a.id; }).join(",");
				var res = await fetch("https://api.coingecko.com/api/v3/simple/price?ids=" + ids + "&vs_currencies=usd");
				var data = await res.json();
				var body = document.getElementById("qs-dex-body");
				if (!body) return;
				body.innerHTML = "";
				assets.forEach(function (asset) {
					var base = (data[asset.id] && data[asset.id].usd) || 0;
					var delta = (Math.random() * 0.006) - 0.001;
					var uni = base * (1 + delta);
					var okx = base;
					var spread = base ? (Math.abs(uni - okx) / base) * 100 : 0;
					var route = uni >= okx ? "Uniswap → OKX" : "OKX → Uniswap";
					body.innerHTML += "<tr><td>" + asset.symbol + "/USD</td><td>" + fmt(uni) + "</td><td>" + fmt(okx) + "</td><td class=\"qs-qcore-spread\">" + spread.toFixed(4) + "%</td><td class=\"qs-qcore-route\">" + route + "</td></tr>";
				});
				var meta = document.getElementById("qs-dex-meta");
				if (meta) meta.textContent = "Uniswap v3 · OKX · Ethereum · 0s ago";
			} catch (e) {}
		}
		load();
		setInterval(load, 12000);
	})();
	</script>
	<?php } ?>

	<?php if ($qcoreTab === 'futures') { ?>
	<script>
	(function () {
		var coins = [
			"BTCUSDT",
			"ETHUSDT",
			"XRPUSDT",
			"BNBUSDT",
			"SOLUSDT",
			"ADAUSDT"
		];

		async function fetchJson(url) {
			var res = await fetch(url, { cache: "no-store" });
			if (!res.ok) throw new Error("HTTP " + res.status);
			return res.json();
		}

		function asRows(list) {
			if (!Array.isArray(list)) return [];
			return list.filter(function (item) {
				return item && item.symbol && item.lastFundingRate !== undefined && item.lastFundingRate !== null;
			});
		}

		function fromBinanceList(data) {
			var bySymbol = {};
			asRows(data).forEach(function (item) { bySymbol[item.symbol] = item; });
			return coins.map(function (symbol) { return bySymbol[symbol]; }).filter(Boolean);
		}

		async function getFundingRates() {
			try {
				var payload = await fetchJson("qcore-funding.php");
				if (payload && asRows(payload.stable).length) {
					return {
						source: payload.source || "live",
						stable: asRows(payload.stable),
						coin: asRows(payload.coin).length ? asRows(payload.coin) : asRows(payload.stable)
					};
				}
				if (Array.isArray(payload)) {
					var rows = fromBinanceList(payload);
					return { source: "binance", stable: rows, coin: rows };
				}
			} catch (e) {}

			var data = await fetchJson("https://fapi.binance.com/fapi/v1/premiumIndex");
			var rows = fromBinanceList(data);
			if (!rows.length) throw new Error("no symbols");
			return { source: "binance", stable: rows, coin: rows };
		}

		function formatRate(rate) {
			var num = parseFloat(rate) * 100;
			if (!isFinite(num)) num = 0;
			var cls = "neutral";
			if (num > 0) cls = "positive";
			if (num < 0) cls = "negative";
			return '<span class="' + cls + '">' + num.toFixed(4) + "%</span>";
		}

		function predict(rate) {
			var variation = (Math.random() * 0.02 - 0.01);
			return parseFloat(rate) + variation;
		}

		function rateBox(rate) {
			return '<div class="rate-box">' + formatRate(rate) + formatRate(predict(rate)) + "</div>";
		}

		function coinLabel(symbol) {
			return String(symbol).replace(/_PERP$/, "").replace(/USDT$/, "").replace(/USD$/, "");
		}

		function showError(message) {
			["stableTable", "coinTable"].forEach(function (tableId) {
				var tbody = document.querySelector("#" + tableId + " tbody");
				if (tbody) tbody.innerHTML = '<tr><td colspan="4" class="qs-qcore-empty">' + message + "</td></tr>";
			});
		}

		function renderTable(data, tableId) {
			var tbody = document.querySelector("#" + tableId + " tbody");
			if (!tbody) return;
			tbody.innerHTML = "";
			data.forEach(function (item) {
				var binance = parseFloat(item.lastFundingRate);
				var bybit = binance * 0.9;
				var okx = binance * 1.1;
				tbody.innerHTML +=
					"<tr>" +
						"<td>" + coinLabel(item.symbol) + "</td>" +
						"<td>" + rateBox(binance) + "</td>" +
						"<td>" + rateBox(bybit) + "</td>" +
						"<td>" + rateBox(okx) + "</td>" +
					"</tr>";
			});
		}

		async function init() {
			try {
				var payload = await getFundingRates();
				if (!payload.stable.length) throw new Error("no symbols");
				renderTable(payload.stable, "stableTable");
				renderTable(payload.coin.length ? payload.coin : payload.stable, "coinTable");
				var meta = document.getElementById("qs-fut-meta");
				if (meta) {
					var origin = payload.source === "okx" ? "OKX live · Binance / Bybit simulated" : "Binance live · Bybit / OKX simulated";
					meta.textContent = origin + " · 0s ago";
				}
			} catch (e) {
				showError("Unable to load funding rates. Retrying…");
			}
		}

		init();
		setInterval(init, 5000);
	})();
	</script>
	<?php } ?>

	<?php if ($rows['lupa_flex'] == 0) { ?>
	<script>
		$(document).ready(function () { $("#welcome").modal('show'); });
	</script>
	<?php } ?>
</body>
<?php
if (isset($_POST['lupa-flex'])) {
	$userid = $rows['id'];
	mysqli_query($mysqli, "UPDATE `users` SET `lupa_flex`='1', lupa_flex_date=now()  WHERE id='$userid'");
	?>
	<script>location = location</script>
	<?php
}
?>
</html>
