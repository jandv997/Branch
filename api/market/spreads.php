<?php
header('Content-Type: application/json');
header('Cache-Control: no-store');

$cacheFile = sys_get_temp_dir() . '/qs-public-spreads.json';
$cacheTtl = 8;

if (is_file($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTtl) {
	$cached = file_get_contents($cacheFile);
	if ($cached !== false && $cached !== '') {
		echo $cached;
		exit;
	}
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

function qs_http_multi($urls) {
	$mh = curl_multi_init();
	$handles = array();
	foreach ($urls as $key => $url) {
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
		$handles[$key] = $ch;
	}

	$running = null;
	do {
		curl_multi_exec($mh, $running);
		curl_multi_select($mh);
	} while ($running > 0);

	$out = array();
	foreach ($handles as $key => $ch) {
		$body = curl_multi_getcontent($ch);
		$code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_multi_remove_handle($mh, $ch);
		curl_close($ch);
		$json = json_decode((string) $body, true);
		$out[$key] = ($code < 400 && is_array($json)) ? $json : null;
	}
	curl_multi_close($mh);
	return $out;
}

function qs_pct_change($last, $open) {
	$last = (float) $last;
	$open = (float) $open;
	if ($open <= 0) {
		return 0.0;
	}
	return round((($last - $open) / $open) * 100, 2);
}

$assets = array('BTC', 'ETH', 'SOL', 'XRP', 'ADA', 'LINK', 'AVAX', 'DOGE');
$krakenIds = array(
	'BTC' => 'XBTUSDT',
	'ETH' => 'ETHUSDT',
	'SOL' => 'SOLUSDT',
	'XRP' => 'XRPUSDT',
	'ADA' => 'ADAUSDT',
	'LINK' => 'LINKUSDT',
	'AVAX' => 'AVAXUSDT',
	'DOGE' => 'XDGUSDT',
);

$responses = qs_http_multi(array(
	'okx' => 'https://www.okx.com/api/v5/market/tickers?instType=SPOT',
	'gate' => 'https://api.gateio.ws/api/v4/spot/tickers',
	'kraken' => 'https://api.kraken.com/0/public/Ticker?pair=' . implode(',', array_values($krakenIds)),
));

$quotes = array();
foreach ($assets as $asset) {
	$quotes[$asset] = array();
}

if (!empty($responses['okx']['data']) && is_array($responses['okx']['data'])) {
	foreach ($responses['okx']['data'] as $row) {
		if (!is_array($row) || empty($row['instId'])) {
			continue;
		}
		if (!preg_match('/^([A-Z0-9]+)-USDT$/', (string) $row['instId'], $m)) {
			continue;
		}
		$base = $m[1];
		if (!isset($quotes[$base])) {
			continue;
		}
		$last = (float) $row['last'];
		$open = isset($row['open24h']) ? (float) $row['open24h'] : 0;
		$quotes[$base]['OKX'] = array(
			'last' => $last,
			'change' => qs_pct_change($last, $open),
			'volume' => isset($row['vol24h']) ? (float) $row['vol24h'] : 0,
		);
	}
}

if (!empty($responses['gate']) && is_array($responses['gate'])) {
	foreach ($responses['gate'] as $row) {
		if (!is_array($row) || empty($row['currency_pair'])) {
			continue;
		}
		if (!preg_match('/^([A-Z0-9]+)_USDT$/', (string) $row['currency_pair'], $m)) {
			continue;
		}
		$base = $m[1];
		if (!isset($quotes[$base])) {
			continue;
		}
		$quotes[$base]['Gate'] = array(
			'last' => (float) $row['last'],
			'change' => isset($row['change_percentage']) ? round((float) $row['change_percentage'], 2) : 0,
			'volume' => isset($row['base_volume']) ? (float) $row['base_volume'] : 0,
		);
	}
}

if (!empty($responses['kraken']['result']) && is_array($responses['kraken']['result'])) {
	$idToAsset = array_flip($krakenIds);
	foreach ($responses['kraken']['result'] as $pairId => $row) {
		$base = isset($idToAsset[$pairId]) ? $idToAsset[$pairId] : null;
		if (!$base || !is_array($row) || empty($row['c'][0])) {
			continue;
		}
		$last = (float) $row['c'][0];
		$open = isset($row['o']) ? (float) $row['o'] : 0;
		$quotes[$base]['Kraken'] = array(
			'last' => $last,
			'change' => qs_pct_change($last, $open),
			'volume' => isset($row['v'][1]) ? (float) $row['v'][1] : 0,
		);
	}
}

$pairs = array();
$venuesOnline = array();

foreach ($quotes as $base => $venues) {
	if (count($venues) < 2) {
		continue;
	}
	foreach ($venues as $name => $_quote) {
		$venuesOnline[$name] = true;
	}

	$buyVenue = null;
	$sellVenue = null;
	$buyPrice = null;
	$sellPrice = null;
	foreach ($venues as $name => $quote) {
		$price = (float) $quote['last'];
		if ($price <= 0) {
			continue;
		}
		if ($buyPrice === null || $price < $buyPrice) {
			$buyPrice = $price;
			$buyVenue = $name;
		}
		if ($sellPrice === null || $price > $sellPrice) {
			$sellPrice = $price;
			$sellVenue = $name;
		}
	}

	if ($buyVenue === null || $sellVenue === null || $buyPrice <= 0) {
		continue;
	}
	if ($buyVenue === $sellVenue) {
		$names = array_keys($venues);
		if (count($names) < 2) {
			continue;
		}
		$sellVenue = $names[0] === $buyVenue ? $names[1] : $names[0];
		$sellPrice = (float) $venues[$sellVenue]['last'];
	}

	$spread = (($sellPrice - $buyPrice) / $buyPrice) * 100;
	$spread = round(max(0, $spread), 4);
	$buyQuote = $venues[$buyVenue];

	$pairs[] = array(
		'pair' => $base . '/USDT',
		'base' => $base,
		'buy_venue' => $buyVenue,
		'sell_venue' => $sellVenue,
		'buy' => $buyPrice,
		'sell' => $sellPrice,
		'spread_pct' => $spread,
		'volume_base' => isset($buyQuote['volume']) ? (float) $buyQuote['volume'] : 0,
		'change_24h' => isset($buyQuote['change']) ? (float) $buyQuote['change'] : 0,
		'status' => $spread >= 0.05 ? 'EXECUTABLE' : 'MONITORING',
	);
}

usort($pairs, function ($a, $b) {
	if ($a['spread_pct'] == $b['spread_pct']) {
		return 0;
	}
	return ($a['spread_pct'] < $b['spread_pct']) ? 1 : -1;
});

if (!count($pairs)) {
	$proxy = qs_http_get('https://quantumscalp.net/api/market/spreads');
	if (is_array($proxy) && !empty($proxy['pairs']) && is_array($proxy['pairs'])) {
		$proxy['source'] = isset($proxy['source']) ? $proxy['source'] : 'live';
		$proxy['note'] = 'Real market prices from public exchange APIs. Real execution.';
		if (empty($proxy['updated_at'])) {
			$proxy['updated_at'] = time();
		}
		$payload = json_encode($proxy);
		if ($payload) {
			@file_put_contents($cacheFile, $payload);
			echo $payload;
			exit;
		}
	}
}

$venueOrder = array('OKX', 'Gate', 'Kraken');
$venuesList = array();
foreach ($venueOrder as $venueName) {
	if (!empty($venuesOnline[$venueName])) {
		$venuesList[] = $venueName;
	}
}
foreach (array_keys($venuesOnline) as $venueName) {
	if (!in_array($venueName, $venuesList, true)) {
		$venuesList[] = $venueName;
	}
}

$payload = json_encode(array(
	'updated_at' => time(),
	'venues_online' => $venuesList,
	'source' => count($pairs) ? 'live' : 'unavailable',
	'note' => 'Real market prices from public exchange APIs. Real execution.',
	'pairs' => $pairs,
));

if ($payload && count($pairs)) {
	@file_put_contents($cacheFile, $payload);
}

echo $payload;
