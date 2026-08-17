<?php
header("Content-Type: application/json");

// ✅ YOUR MORALIS API KEY
$API_KEY = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJub25jZSI6IjE0YzE3NDQ5LWQyNGItNGI3ZS1hNWM5LTMyNjYxMTBkYjE1NiIsIm9yZ0lkIjoiNTExNTU0IiwidXNlcklkIjoiNTI2MzQ3IiwidHlwZUlkIjoiMDBmY2I5NzMtYjI2Yy00MzMxLWExMDItZThlNjcyNmRiNzMwIiwidHlwZSI6IlBST0pFQ1QiLCJpYXQiOjE3NzY3NDg5NzksImV4cCI6NDkzMjUwODk3OX0.1b_6R4ngALYUAoRv3SkQXIW5BJ58X7qSryvgchfqOZM";

// ✅ Token mapping
$tokens = [
    "eth" => "0xC02aaA39b223FE8D0A0e5C4F27eAD9083C756Cc2",
    "bsc" => "0x2170Ed0880ac9A755fd29B2688956BD959F933F8",
    "arbitrum" => "0x82af49447d8a07e3bd95bd0d56f35241523fbab1"
];

// ✅ Get params from JS
$chain = isset($_GET['chain']) ? $_GET['chain'] : 'eth';
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 50;

// Validate chain
if (!isset($tokens[$chain])) {
    echo json_encode(["error" => "Invalid chain"]);
    exit;
}

$token = $tokens[$chain];

// ✅ Moralis API URL
$url = "https://deep-index.moralis.io/api/v2.2/erc20/$token/swaps?chain=$chain&limit=$limit";

// ✅ CURL REQUEST
$ch = curl_init();

curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "X-API-Key: $API_KEY"
    ]
]);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode([
        "error" => "Curl error",
        "message" => curl_error($ch)
    ]);
    exit;
}

curl_close($ch);

// ✅ Return API response directly
echo $response;