<?php
session_start();
header("Content-Type: application/json");
header("Cache-Control: no-store");

if (!isset($_SESSION['id'])) {
	http_response_code(401);
	echo json_encode(array("error" => "auth"));
	exit;
}

$API_KEY = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJub25jZSI6IjE0YzE3NDQ5LWQyNGItNGI3ZS1hNWM5LTMyNjYxMTBkYjE1NiIsIm9yZ0lkIjoiNTExNTU0IiwidXNlcklkIjoiNTI2MzQ3IiwidHlwZUlkIjoiMDBmY2I5NzMtYjI2Yy00MzMxLWExMDItZThlNjcyNmRiNzMwIiwidHlwZSI6IlBST0pFQ1QiLCJpYXQiOjE3NzY3NDg5NzksImV4cCI6NDkzMjUwODk3OX0.1b_6R4ngALYUAoRv3SkQXIW5BJ58X7qSryvgchfqOZM";

$tokens = array(
	"eth" => "0xC02aaA39b223FE8D0A0e5C4F27eAD9083C756Cc2",
	"bsc" => "0x2170Ed0880ac9A755fd29B2688956BD959F933F8",
	"arbitrum" => "0x82af49447d8a07e3bd95bd0d56f35241523fbab1"
);

$chain = isset($_GET['chain']) ? strtolower(trim((string) $_GET['chain'])) : 'eth';
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 50;
if ($limit < 1) {
	$limit = 1;
}
if ($limit > 50) {
	$limit = 50;
}

if (!isset($tokens[$chain])) {
	echo json_encode(array("error" => "Invalid chain"));
	exit;
}

$token = $tokens[$chain];
$url = "https://deep-index.moralis.io/api/v2.2/erc20/$token/swaps?chain=$chain&limit=$limit";

$ch = curl_init();
curl_setopt_array($ch, array(
	CURLOPT_URL => $url,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_CONNECTTIMEOUT => 8,
	CURLOPT_TIMEOUT => 20,
	CURLOPT_HTTPHEADER => array(
		"X-API-Key: $API_KEY",
		"Accept: application/json"
	)
));

$response = curl_exec($ch);
$err = curl_error($ch);
$code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($response === false || $err !== '') {
	http_response_code(502);
	echo json_encode(array(
		"error" => "Curl error",
		"message" => $err
	));
	exit;
}

if ($code >= 400) {
	http_response_code($code >= 500 ? 502 : $code);
}

echo $response;
