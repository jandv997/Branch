<?php
header('Content-Type: application/json');
header('Cache-Control: no-store');

$cacheFile = sys_get_temp_dir() . '/qs-public-dex-trades.json';
$cacheTtl = 10;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 20;
if ($limit < 1) {
	$limit = 1;
}
if ($limit > 40) {
	$limit = 40;
}

if (is_file($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTtl) {
	$cached = file_get_contents($cacheFile);
	if ($cached !== false && $cached !== '') {
		echo $cached;
		exit;
	}
}

$API_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJub25jZSI6IjE0YzE3NDQ5LWQyNGItNGI3ZS1hNWM5LTMyNjYxMTBkYjE1NiIsIm9yZ0lkIjoiNTExNTU0IiwidXNlcklkIjoiNTI2MzQ3IiwidHlwZUlkIjoiMDBmY2I5NzMtYjI2Yy00MzMxLWExMDItZThlNjcyNmRiNzMwIiwidHlwZSI6IlBST0pFQ1QiLCJpYXQiOjE3NzY3NDg5NzksImV4cCI6NDkzMjUwODk3OX0.1b_6R4ngALYUAoRv3SkQXIW5BJ58X7qSryvgchfqOZM';

$chains = array(
	'eth' => array(
		'token' => '0xC02aaA39b223FE8D0A0e5C4F27eAD9083C756Cc2',
		'network' => 'Ethereum',
		'explorer' => 'https://etherscan.io/tx/',
	),
	'bsc' => array(
		'token' => '0x2170Ed0880ac9A755fd29B2688956BD959F933F8',
		'network' => 'BNB Chain',
		'explorer' => 'https://bscscan.com/tx/',
	),
	'arbitrum' => array(
		'token' => '0x82af49447d8a07e3bd95bd0d56f35241523fbab1',
		'network' => 'Arbitrum',
		'explorer' => 'https://arbiscan.io/tx/',
	),
);

$stables = array('USDT' => true, 'USDC' => true, 'DAI' => true, 'BUSD' => true, 'FDUSD' => true, 'USDE' => true);

function qs_dex_short_hash($hash) {
	$hash = (string) $hash;
	if (strlen($hash) <= 18) {
		return $hash;
	}
	return substr($hash, 0, 10) . '…' . substr($hash, -6);
}

function qs_dex_amount($trade, $stables) {
	$usd = abs(floatval(isset($trade['totalValueUsd']) ? $trade['totalValueUsd'] : 0));
	$bought = isset($trade['bought']['symbol']) ? strtoupper((string) $trade['bought']['symbol']) : '';
	$sold = isset($trade['sold']['symbol']) ? strtoupper((string) $trade['sold']['symbol']) : '';
	$symbol = '';
	if ($bought !== '' && isset($stables[$bought])) {
		$symbol = $bought;
	} elseif ($sold !== '' && isset($stables[$sold])) {
		$symbol = $sold;
	}
	if ($usd > 0 && $symbol !== '') {
		return number_format($usd, 2) . ' ' . $symbol;
	}
	if ($usd > 0) {
		return '$' . number_format($usd, 2);
	}
	$qty = isset($trade['bought']['amount']) ? abs(floatval($trade['bought']['amount'])) : 0;
	if ($qty > 0 && $bought !== '') {
		return rtrim(rtrim(number_format($qty, 4, '.', ','), '0'), '.') . ' ' . $bought;
	}
	return '—';
}

function qs_dex_timestamp($iso) {
	$ts = $iso ? strtotime((string) $iso) : false;
	if (!$ts) {
		return array('label' => '—', 'unix' => 0);
	}
	return array(
		'label' => gmdate('Y-m-d H:i:s', $ts) . ' UTC',
		'unix' => $ts,
	);
}

$mh = curl_multi_init();
$handles = array();
foreach ($chains as $chain => $meta) {
	$url = 'https://deep-index.moralis.io/api/v2.2/erc20/' . $meta['token'] . '/swaps?chain=' . $chain . '&limit=' . $limit;
	$ch = curl_init($url);
	curl_setopt_array($ch, array(
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_CONNECTTIMEOUT => 8,
		CURLOPT_TIMEOUT => 20,
		CURLOPT_HTTPHEADER => array(
			'X-API-Key: ' . $API_KEY,
			'Accept: application/json',
		),
	));
	curl_multi_add_handle($mh, $ch);
	$handles[$chain] = $ch;
}

$running = null;
do {
	curl_multi_exec($mh, $running);
	curl_multi_select($mh);
} while ($running > 0);

$settlements = array();
$perChain = array();
foreach ($handles as $chain => $ch) {
	$body = curl_multi_getcontent($ch);
	$code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_multi_remove_handle($mh, $ch);
	curl_close($ch);
	if ($code >= 400 || $body === false || $body === '') {
		continue;
	}
	$json = json_decode((string) $body, true);
	if (!is_array($json) || empty($json['result']) || !is_array($json['result'])) {
		continue;
	}
	$meta = $chains[$chain];
	$rows = array();
	foreach ($json['result'] as $trade) {
		if (!is_array($trade) || empty($trade['transactionHash'])) {
			continue;
		}
		if (empty($trade['bought']) || empty($trade['sold'])) {
			continue;
		}
		$hash = (string) $trade['transactionHash'];
		$time = qs_dex_timestamp(isset($trade['blockTimestamp']) ? $trade['blockTimestamp'] : '');
		$rows[] = array(
			'network' => $meta['network'],
			'network_id' => $chain,
			'hash' => $hash,
			'hash_short' => qs_dex_short_hash($hash),
			'timestamp' => $time['label'],
			'timestamp_unix' => $time['unix'],
			'amount' => qs_dex_amount($trade, $stables),
			'usd' => abs(floatval(isset($trade['totalValueUsd']) ? $trade['totalValueUsd'] : 0)),
			'exchange' => isset($trade['exchangeName']) ? (string) $trade['exchangeName'] : '',
			'pair' => isset($trade['pairLabel']) ? (string) $trade['pairLabel'] : '',
			'explorer' => $meta['explorer'] . $hash,
		);
	}
	$perChain[$chain] = $rows;
}
curl_multi_close($mh);

$maxPerChain = max(4, (int) ceil($limit / max(1, count($perChain))));
foreach ($perChain as $rows) {
	$settlements = array_merge($settlements, array_slice($rows, 0, $maxPerChain));
}

usort($settlements, function ($a, $b) {
	if ($a['timestamp_unix'] == $b['timestamp_unix']) {
		return 0;
	}
	return ($a['timestamp_unix'] < $b['timestamp_unix']) ? 1 : -1;
});

$seen = array();
$unique = array();
foreach ($settlements as $row) {
	$key = $row['network_id'] . ':' . strtolower($row['hash']);
	if (isset($seen[$key])) {
		continue;
	}
	$seen[$key] = true;
	$unique[] = $row;
}
$settlements = array_slice($unique, 0, $limit);

$payload = json_encode(array(
	'updated_at' => time(),
	'source' => count($settlements) ? 'dex-live' : 'unavailable',
	'settlements' => $settlements,
));

if ($payload && count($settlements)) {
	@file_put_contents($cacheFile, $payload);
}

echo $payload;
