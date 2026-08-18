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
$signalCount = 0;
if ($qcoreTab === 'signals') {
	mysqli_query($mysqli, "CREATE TABLE IF NOT EXISTS `bot_messages` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`message_text` text,
		`created_at` datetime DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
	$result = $mysqli->query("
		SELECT * FROM bot_messages WHERE DATE(created_at) = CURDATE()
		ORDER BY id DESC
		LIMIT 100
	");
	if ($result) {
		while ($row = $result->fetch_assoc()) {
			$signalRows[] = $row;
		}
		$signalCount = count($signalRows);
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
								<div class="qs-qcore-card__media">
									<img src="assets/img/qcore/qcore-cross-exchange.jpg" alt="Cross-exchange arbitrage">
									<span class="qs-qcore-card__num">01</span>
								</div>
								<div class="qs-qcore-card__body">
									<h3>Cross-Exchange Arbitrage</h3>
									<p>Compares prices for the same digital assets across different centralized exchanges and identifies potential price discrepancies.</p>
									<div class="qs-qcore-path">Exchange A → Asset → Exchange B</div>
								</div>
							</article>
							<article class="qs-qcore-card">
								<div class="qs-qcore-card__media">
									<img src="assets/img/qcore/qcore-triangular.jpg" alt="Triangular arbitrage">
									<span class="qs-qcore-card__num">02</span>
								</div>
								<div class="qs-qcore-card__body">
									<h3>Triangular Arbitrage</h3>
									<p>Analyzes relationships between three trading pairs to identify potential pricing inefficiencies within a single exchange or market environment.</p>
									<div class="qs-qcore-path">BTC → USDT → ETH → BTC</div>
								</div>
							</article>
							<article class="qs-qcore-card">
								<div class="qs-qcore-card__media">
									<img src="assets/img/qcore/qcore-statistical.jpg" alt="Statistical arbitrage">
									<span class="qs-qcore-card__num">03</span>
								</div>
								<div class="qs-qcore-card__body">
									<h3>Statistical Arbitrage</h3>
									<p>Machine-learning models analyze historical relationships and market behavior to identify statistical deviations that may represent potential opportunities.</p>
									<div class="qs-qcore-path">Historical correlation + AI signal</div>
								</div>
							</article>
							<article class="qs-qcore-card">
								<div class="qs-qcore-card__media">
									<img src="assets/img/qcore/qcore-dex.jpg" alt="DEX arbitrage">
									<span class="qs-qcore-card__num">04</span>
								</div>
								<div class="qs-qcore-card__body">
									<h3>DEX Arbitrage</h3>
									<p>Monitors decentralized exchanges and automated market makers for pricing differences and liquidity imbalances.</p>
									<div class="qs-qcore-path">DEX A → Blockchain → DEX B</div>
								</div>
							</article>
							<article class="qs-qcore-card">
								<div class="qs-qcore-card__media">
									<img src="assets/img/qcore/qcore-flash-loan.jpg" alt="Flash loan arbitrage">
									<span class="qs-qcore-card__num">05</span>
								</div>
								<div class="qs-qcore-card__body">
									<h3>Flash Loan Arbitrage</h3>
									<p>Where technically and economically viable, identifies atomic on-chain arbitrage opportunities involving flash-loan infrastructure. Dependent on available liquidity, blockchain conditions, transaction costs, smart-contract conditions, and execution feasibility.</p>
									<div class="qs-qcore-path">Borrow → Swap → Repay (atomic)</div>
								</div>
							</article>
							<article class="qs-qcore-card">
								<div class="qs-qcore-card__media">
									<img src="assets/img/qcore/qcore-futures.jpg" alt="Futures and derivatives arbitrage">
									<span class="qs-qcore-card__num">06</span>
								</div>
								<div class="qs-qcore-card__body">
									<h3>Futures &amp; Derivatives Arbitrage</h3>
									<p>Analyzes relationships between spot and derivatives markets, including funding-rate differences, basis spreads, calendar spreads, and other relative-value opportunities.</p>
									<div class="qs-qcore-path">Spot ↔ Perp / Funding / Basis</div>
								</div>
							</article>
						</div>
					<?php } ?>

					<?php if ($qcoreTab === 'cex') { ?>
						<section class="qs-qcore-panel">
							<div class="qs-qcore-panel__head">
								<div>
									<h2 class="qs-qcore-panel__title">CEXs Live Trading (Arbitrage)</h2>
									<p class="qs-qcore-panel__meta" id="qs-cex-meta">Binance · Kraken · KuCoin · OKX · Huobi · BitMEX · Bitfinex · refreshing</p>
								</div>
								<span class="qs-qcore-exec">REAL PRICES • SIMULATED EXECUTION</span>
							</div>
							<div class="table-responsive">
								<table class="qs-qcore-table qs-qcore-table--cex">
									<thead>
										<tr>
											<th>Coins</th>
											<th>Purchased From</th>
											<th>Sold To</th>
											<th>Value Bought</th>
											<th>Value Sold</th>
											<th>Spread (USDT)</th>
											<th>Purchase Volume</th>
											<th>Profit(USDT)</th>
										</tr>
									</thead>
									<tbody id="table-body">
										<tr><td colspan="8" class="qs-qcore-empty">Loading live CEX prices…</td></tr>
									</tbody>
								</table>
							</div>
							<p class="qs-qcore-note">Live CoinGecko prices with simulated cross-exchange buy/sell, volume, and profit. Auto-refreshes every 10 seconds. Read-only; Quantum Scalp does not place live trades in this demo.</p>
						</section>
					<?php } ?>

					<?php if ($qcoreTab === 'dex') { ?>
						<section class="qs-qcore-panel">
							<div class="qs-qcore-panel__head">
								<div>
									<h2 class="qs-qcore-panel__title">DEXs Live Trading</h2>
									<p class="qs-qcore-panel__meta" id="qs-dex-meta">Ethereum · Uniswap · PancakeSwap · refreshing</p>
								</div>
								<span class="qs-qcore-exec">REAL PRICES • SIMULATED EXECUTION</span>
							</div>
							<div class="qs-dex-filters">
								<label class="qs-dex-field">
									<span>Network</span>
									<select id="network">
										<option value="eth">Ethereum</option>
										<option value="bsc">BSC</option>
										<option value="arbitrum">Arbitrum</option>
									</select>
								</label>
								<label class="qs-dex-field">
									<span>DEX</span>
									<select id="dex">
										<option value="all">All DEXs</option>
										<option value="uniswap">Uniswap</option>
										<option value="pancakeswap">PancakeSwap</option>
									</select>
								</label>
								<label class="qs-dex-field">
									<span>Min USD</span>
									<input type="number" id="minVolume" placeholder="e.g. 1000">
								</label>
								<label class="qs-dex-field">
									<span>Results</span>
									<input type="number" id="limit" value="20">
								</label>
								<button type="button" class="qs-dex-refresh" id="qs-dex-refresh" onclick="fetchTrades()">Refresh</button>
							</div>
							<div class="table-responsive">
								<table class="qs-qcore-table">
									<thead>
										<tr>
											<th>Time</th>
											<th>Network</th>
											<th>DEX</th>
											<th>Pair</th>
											<th>Side</th>
											<th>Price</th>
											<th>Amount</th>
											<th>Profit (USD)</th>
											<th>TX</th>
										</tr>
									</thead>
									<tbody id="tradeTable">
										<tr><td colspan="9" class="qs-qcore-empty">Loading DEX trades…</td></tr>
									</tbody>
								</table>
							</div>
							<p class="qs-qcore-note">Live Moralis swap feed for WETH on the selected network. Filter by DEX and minimum USD, then open the transaction on the explorer. Read-only; Quantum Scalp does not place live trades in this demo.</p>
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

					<?php if ($qcoreTab === 'signals') {
						$qsSignalSide = function ($text) {
							$u = strtoupper((string) $text);
							if (preg_match('/\b(SELL|SHORT|BEARISH)\b/', $u)) {
								return 'sell';
							}
							if (preg_match('/\b(BUY|LONG|BULLISH)\b/', $u)) {
								return 'buy';
							}
							return 'watch';
						};
						$qsSignalPair = function ($text) {
							if (preg_match('/\b([A-Z]{2,10}[\/\-][A-Z]{2,10})\b/', strtoupper((string) $text), $m)) {
								return $m[1];
							}
							if (preg_match('/\b([A-Z]{3,6}USDT|[A-Z]{3,6}USD|[A-Z]{3,6}BTC)\b/', strtoupper((string) $text), $m)) {
								return $m[1];
							}
							return '';
						};
					?>
						<section class="qs-signal-desk">
							<div class="qs-signal-desk__head">
								<div>
									<h2>Quantum Signals</h2>
									<p><?php echo (int) $signalCount; ?> live signal<?php echo $signalCount === 1 ? '' : 's'; ?> today · professional market intelligence</p>
								</div>
								<a class="qs-signal-cta" href="membership">Purchase a License</a>
							</div>

							<?php if ($signalCount > 0) { ?>
								<div class="qs-signal-feed">
									<?php foreach ($signalRows as $row) {
										$timestamp = strtotime($row['created_at']);
										$side = $qsSignalSide($row['message_text']);
										$pair = $qsSignalPair($row['message_text']);
										$sideLabel = $side === 'buy' ? 'BUY' : ($side === 'sell' ? 'SELL' : 'WATCH');
									?>
										<article class="qs-signal-ticket">
											<div class="qs-signal-ticket__time">
												<strong><?php echo htmlspecialchars(date('g:i A', $timestamp)); ?></strong>
												<span><?php echo htmlspecialchars(date('d M Y', $timestamp)); ?></span>
											</div>
											<div class="qs-signal-ticket__body">
												<div class="qs-signal-ticket__meta">
													<span class="qs-signal-side is-<?php echo htmlspecialchars($side); ?>"><?php echo $sideLabel; ?></span>
													<?php if ($pair !== '') { ?>
														<strong class="qs-signal-pair"><?php echo htmlspecialchars($pair); ?></strong>
													<?php } ?>
													<span class="qs-signal-live"><i></i> LIVE</span>
												</div>
												<p><?php echo nl2br(htmlspecialchars($row['message_text'])); ?></p>
											</div>
										</article>
									<?php } ?>
								</div>
							<?php } else { ?>
								<div class="qs-signal-empty">
									<h5>No signals available</h5>
									<p>Waiting for incoming trading intelligence.</p>
								</div>
							<?php } ?>
						</section>
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

		function randomExchange() {
			return exchanges[Math.floor(Math.random() * exchanges.length)];
		}

		async function fetchJson(url) {
			var res = await fetch(url, { cache: "no-store" });
			if (!res.ok) throw new Error("HTTP " + res.status);
			return res.json();
		}

		async function loadPrices() {
			try {
				return await fetchJson("qcore-cex.php");
			} catch (e) {
				var ids = coins.map(function (c) { return c.id; }).join(",");
				return await fetchJson("https://api.coingecko.com/api/v3/simple/price?ids=" + ids + "&vs_currencies=usd");
			}
		}

		async function fetchPrices() {
			var tbody = document.getElementById("table-body");
			if (!tbody) return;
			try {
				var data = await loadPrices();
				if (!data || typeof data !== "object") throw new Error("bad payload");
				tbody.innerHTML = "";
				coins.forEach(function (coin) {
					var basePrice = (data[coin.id] && data[coin.id].usd) || 0;
					var buyPrice = basePrice * (1 - Math.random() * 0.01);
					var sellPrice = basePrice * (1 + Math.random() * 0.01);
					var spread = sellPrice - buyPrice;
					var volume = (Math.random() * 1000).toFixed(2);
					var interest = (spread * volume).toFixed(4);
					tbody.innerHTML +=
						"<tr>" +
							"<td>" + coin.symbol + "</td>" +
							"<td class=\"qs-qcore-venue\">" + randomExchange() + "</td>" +
							"<td class=\"qs-qcore-venue\">" + randomExchange() + "</td>" +
							"<td class=\"qs-cex-bought\">" + buyPrice.toFixed(6) + "</td>" +
							"<td class=\"qs-cex-sold\">" + sellPrice.toFixed(6) + "</td>" +
							"<td>" + spread.toFixed(4) + "</td>" +
							"<td class=\"qs-cex-volume\">" + volume + "</td>" +
							"<td class=\"qs-qcore-profit\">" + interest + "</td>" +
						"</tr>";
				});
				var meta = document.getElementById("qs-cex-meta");
				if (meta) meta.textContent = "Binance · Kraken · KuCoin · OKX · Huobi · BitMEX · Bitfinex · 0s ago";
			} catch (e) {
				tbody.innerHTML = '<tr><td colspan="8" class="qs-qcore-empty">Unable to load CEX prices. Retrying…</td></tr>';
			}
		}

		fetchPrices();
		setInterval(fetchPrices, 10000);
	})();
	</script>
	<?php } ?>

	<?php if ($qcoreTab === 'dex') { ?>
	<script>
	async function fetchTrades() {
		var networkEl = document.getElementById("network");
		var dexEl = document.getElementById("dex");
		var minEl = document.getElementById("minVolume");
		var limitEl = document.getElementById("limit");
		var table = document.getElementById("tradeTable");
		if (!networkEl || !table) return;

		var chain = networkEl.value;
		var dexFilter = (dexEl.value || "all").toLowerCase();
		var minVolume = parseFloat(minEl.value || 0);
		var limit = parseInt(limitEl.value || 20, 10);
		if (!isFinite(minVolume) || minVolume < 0) minVolume = 0;
		if (!isFinite(limit) || limit < 1) limit = 20;

		table.innerHTML = '<tr><td colspan="9" class="qs-qcore-empty">Loading DEX trades…</td></tr>';

		try {
			var res = await fetch("fetch_trades.php?chain=" + encodeURIComponent(chain) + "&limit=" + encodeURIComponent(limit), { cache: "no-store" });
			var data = await res.json();
			if (!data || !data.result) {
				table.innerHTML = '<tr><td colspan="9" class="qs-qcore-empty">No data</td></tr>';
				return;
			}

			var trades = data.result;
			table.innerHTML = "";
			var count = 0;

			trades.forEach(function (t) {
				if (!t.bought || !t.sold) return;

				var dexName = (t.exchangeName || "Unknown").toLowerCase();
				if (dexFilter !== "all" && dexName.indexOf(dexFilter) === -1) return;

				var usdValue = Math.abs(parseFloat(t.totalValueUsd || 0));
				if (usdValue < minVolume) return;
				if (count >= limit) return;

				var type = t.transactionType === "buy" ? "BUY" : "SELL";
				var pair = t.pairLabel || ((t.bought.symbol || "") + "/" + (t.sold.symbol || ""));
				var amount = Math.abs(parseFloat(t.bought.amount || 0));
				var price = parseFloat(t.bought.usdPrice || 0);
				if (!isFinite(amount)) amount = 0;
				if (!isFinite(price)) price = 0;
				var time = t.blockTimestamp ? new Date(t.blockTimestamp).toLocaleTimeString() : "--";

				var txLink = "#";
				if (chain === "eth") txLink = "https://etherscan.io/tx/" + t.transactionHash;
				if (chain === "bsc") txLink = "https://bscscan.com/tx/" + t.transactionHash;
				if (chain === "arbitrum") txLink = "https://arbiscan.io/tx/" + t.transactionHash;

				table.innerHTML +=
					"<tr>" +
						"<td>" + time + "</td>" +
						"<td><span class=\"network\">" + chain.toUpperCase() + "</span></td>" +
						"<td><span class=\"dex\">" + (t.exchangeName || "Unknown") + "</span></td>" +
						"<td>" + pair + "</td>" +
						"<td><span class=\"" + (type === "BUY" ? "buy" : "sell") + "\">" + type + "</span></td>" +
						"<td>$" + price.toFixed(6) + "</td>" +
						"<td>" + amount.toFixed(4) + " " + (t.bought.symbol || "") + "</td>" +
						"<td class=\"qs-qcore-profit\">$" + usdValue.toFixed(2) + "</td>" +
						"<td><a class=\"qs-dex-view\" href=\"" + txLink + "\" target=\"_blank\" rel=\"noopener\">View</a></td>" +
					"</tr>";
				count++;
			});

			if (!count) {
				table.innerHTML = '<tr><td colspan="9" class="qs-qcore-empty">No trades match these filters.</td></tr>';
			}

			var meta = document.getElementById("qs-dex-meta");
			if (meta) {
				meta.textContent = chain.toUpperCase() + " · " + (dexEl.options[dexEl.selectedIndex] ? dexEl.options[dexEl.selectedIndex].text : "All DEXs") + " · " + count + " trades · 0s ago";
			}
		} catch (err) {
			table.innerHTML = '<tr><td colspan="9" class="qs-qcore-empty">Unable to load DEX trades. Retrying from Refresh.</td></tr>';
		}
	}

	document.getElementById("network").onchange = fetchTrades;
	document.getElementById("dex").onchange = fetchTrades;
	fetchTrades();
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
