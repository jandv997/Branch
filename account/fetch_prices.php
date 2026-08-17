<?php
header('Content-Type: application/json');

$coins = [
    "solana",
    "cardano",
    "polkadot",
    "chainlink",
    "ripple",
    "dogecoin",
    "litecoin",
    "bitcoin-cash",
    "avalanche-2",
    "tron",
    "stellar"
];

$ids = implode(",", $coins);
$url = "https://api.coingecko.com/api/v3/simple/price?ids=$ids&vs_currencies=usd";

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 10,
]);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode([
        "error" => curl_error($ch)
    ]);
    exit;
}

curl_close($ch);

// 🔥 IMPORTANT: Decode and re-encode to ensure valid JSON
$data = json_decode($response, true);

if (!$data) {
    echo json_encode([
        "error" => "Invalid API response",
        "raw" => $response
    ]);
    exit;
}

echo json_encode($data);

?>