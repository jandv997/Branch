<?php
session_start();

header('Content-Type: application/json');
header('Cache-Control: no-store');

if (!isset($_SESSION['id'])) {
	http_response_code(401);
	echo json_encode(array('error' => 'auth'));
	exit;
}

$ids = 'solana,cardano,polkadot,chainlink,ripple,dogecoin,litecoin,bitcoin-cash,avalanche-2,tron,stellar';
$url = 'https://api.coingecko.com/api/v3/simple/price?ids=' . $ids . '&vs_currencies=usd';

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
$err = curl_error($ch);
$code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($body === false || $err !== '' || $code >= 400) {
	http_response_code(502);
	echo json_encode(array('error' => 'upstream', 'detail' => $err));
	exit;
}

$json = json_decode($body, true);
if (!is_array($json) || !$json) {
	http_response_code(502);
	echo json_encode(array('error' => 'payload'));
	exit;
}

echo $body;
