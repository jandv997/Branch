<?php
session_start();

header('Content-Type: application/json');
header('Cache-Control: no-store');

if (!isset($_SESSION['id'])) {
	http_response_code(401);
	echo json_encode(array('error' => 'auth'));
	exit;
}

function qs_http_get($url) {
	$ch = curl_init($url);
	curl_setopt_array($ch, array(
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_CONNECTTIMEOUT => 8,
		CURLOPT_TIMEOUT => 12,
		CURLOPT_USERAGENT => 'QuantumScalp-QCore/1.0',
		CURLOPT_HTTPHEADER => array('Accept: application/json'),
	));
	$body = curl_exec($ch);
	$code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	if ($body === false || $code >= 400) {
		return null;
	}
	$json = json_decode($body, true);
	return is_array($json) ? $json : null;
}

function qs_base_asset($symbol) {
	$base = preg_replace('/_PERP$/', '', (string) $symbol);
	return preg_replace('/(USDT|USD)$/', '', $base);
}

function qs_pick_rows($items, $wanted) {
	$bySymbol = array();
	foreach ($items as $item) {
		if (!is_array($item) || empty($item['symbol'])) {
			continue;
		}
		$symbol = (string) $item['symbol'];
		if (!isset($wanted[$symbol])) {
			continue;
		}
		$rate = isset($item['lastFundingRate']) ? $item['lastFundingRate'] : (isset($item['last_funding_rate']) ? $item['last_funding_rate'] : null);
		if ($rate === null) {
			continue;
		}
		$bySymbol[$symbol] = array(
			'symbol' => $symbol,
			'lastFundingRate' => (string) $rate,
		);
	}
	$out = array();
	foreach ($wanted as $symbol => $_keep) {
		if (isset($bySymbol[$symbol])) {
			$out[] = $bySymbol[$symbol];
		}
	}
	return $out;
}

function qs_okx_rates($map) {
	$mh = curl_multi_init();
	$handles = array();
	foreach ($map as $symbol => $instId) {
		$url = 'https://www.okx.com/api/v5/public/funding-rate?instId=' . rawurlencode($instId);
		$ch = curl_init($url);
		curl_setopt_array($ch, array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_CONNECTTIMEOUT => 8,
			CURLOPT_TIMEOUT => 12,
			CURLOPT_USERAGENT => 'QuantumScalp-QCore/1.0',
			CURLOPT_HTTPHEADER => array('Accept: application/json'),
		));
		curl_multi_add_handle($mh, $ch);
		$handles[$symbol] = $ch;
	}

	$running = null;
	do {
		curl_multi_exec($mh, $running);
		curl_multi_select($mh);
	} while ($running > 0);

	$out = array();
	foreach ($handles as $symbol => $ch) {
		$body = curl_multi_getcontent($ch);
		$json = is_string($body) ? json_decode($body, true) : null;
		if ($json && !empty($json['data'][0]['fundingRate'])) {
			$out[] = array(
				'symbol' => $symbol,
				'lastFundingRate' => (string) $json['data'][0]['fundingRate'],
			);
		}
		curl_multi_remove_handle($mh, $ch);
		curl_close($ch);
	}
	curl_multi_close($mh);
	return $out;
}

$stableWanted = array(
	'BTCUSDT' => true,
	'ETHUSDT' => true,
	'XRPUSDT' => true,
	'BNBUSDT' => true,
	'SOLUSDT' => true,
	'ADAUSDT' => true,
);
$coinWanted = array(
	'BTCUSD_PERP' => true,
	'ETHUSD_PERP' => true,
	'XRPUSD_PERP' => true,
	'BNBUSD_PERP' => true,
	'SOLUSD_PERP' => true,
	'ADAUSD_PERP' => true,
);
$okxStable = array(
	'BTCUSDT' => 'BTC-USDT-SWAP',
	'ETHUSDT' => 'ETH-USDT-SWAP',
	'XRPUSDT' => 'XRP-USDT-SWAP',
	'BNBUSDT' => 'BNB-USDT-SWAP',
	'SOLUSDT' => 'SOL-USDT-SWAP',
	'ADAUSDT' => 'ADA-USDT-SWAP',
);
$okxCoin = array(
	'BTCUSD' => 'BTC-USD-SWAP',
	'ETHUSD' => 'ETH-USD-SWAP',
	'XRPUSD' => 'XRP-USD-SWAP',
	'BNBUSD' => 'BNB-USD-SWAP',
	'SOLUSD' => 'SOL-USD-SWAP',
	'ADAUSD' => 'ADA-USD-SWAP',
);

$source = 'okx';
$stable = array();
$coin = array();

$binanceUsdt = qs_http_get('https://fapi.binance.com/fapi/v1/premiumIndex');
if (is_array($binanceUsdt) && isset($binanceUsdt[0])) {
	$stable = qs_pick_rows($binanceUsdt, $stableWanted);
	if ($stable) {
		$source = 'binance';
	}
}

$binanceCoin = qs_http_get('https://dapi.binance.com/dapi/v1/premiumIndex');
if (is_array($binanceCoin) && isset($binanceCoin[0])) {
	$coin = qs_pick_rows($binanceCoin, $coinWanted);
}

if (!$stable) {
	$stable = qs_okx_rates($okxStable);
}

if (!$coin) {
	$coin = qs_okx_rates($okxCoin);
}

if ($stable) {
	$coinByBase = array();
	foreach ($coin as $row) {
		$base = qs_base_asset($row['symbol']);
		$coinByBase[$base] = $row;
	}
	$filled = array();
	foreach ($stable as $row) {
		$base = qs_base_asset($row['symbol']);
		$filled[] = isset($coinByBase[$base]) ? $coinByBase[$base] : $row;
	}
	$coin = $filled;
}

if (!$stable) {
	http_response_code(502);
	echo json_encode(array('error' => 'upstream'));
	exit;
}

echo json_encode(array(
	'source' => $source,
	'stable' => $stable,
	'coin' => $coin,
));
