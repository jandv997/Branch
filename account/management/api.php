<?php
header('Content-Type: application/json');

$API_KEY = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJub25jZSI6IjE0YzE3NDQ5LWQyNGItNGI3ZS1hNWM5LTMyNjYxMTBkYjE1NiIsIm9yZ0lkIjoiNTExNTU0IiwidXNlcklkIjoiNTI2MzQ3IiwidHlwZUlkIjoiMDBmY2I5NzMtYjI2Yy00MzMxLWExMDItZThlNjcyNmRiNzMwIiwidHlwZSI6IlBST0pFQ1QiLCJpYXQiOjE3NzY3NDg5NzksImV4cCI6NDkzMjUwODk3OX0.1b_6R4ngALYUAoRv3SkQXIW5BJ58X7qSryvgchfqOZM";

$tokens = [
    "eth" => "0xC02aaA39b223FE8D0A0e5C4F27eAD9083C756Cc2",
    "bsc" => "0x2170Ed0880ac9A755fd29B2688956BD959F933F8",
    "arbitrum" => "0x82af49447d8a07e3bd95bd0d56f35241523fbab1"
];

$chain = $_GET['chain'] ?? 'eth';
$limit = 20;

if (!isset($tokens[$chain])) {
    echo json_encode(["error" => "Invalid chain"]);
    exit;
}

$token = $tokens[$chain];

$url = "https://deep-index.moralis.io/api/v2.2/erc20/$token/swaps?chain=$chain&limit=$limit";

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
    echo json_encode(["error" => curl_error($ch)]);
    exit;
}

curl_close($ch);

$data = json_decode($response, true);

if (!isset($data['result'])) {
    echo json_encode(["error" => "Invalid response", "raw" => $data]);
    exit;
}

$arbs = [];

foreach ($data['result'] as $swap) {

    $price = floatval($swap['usdPrice'] ?? 0);
    $amount = floatval($swap['amount'] ?? 0);

    // 🔥 Confidence logic (you can tweak this)
    $confidence = 0;

    if ($price > 0 && $amount > 0) {
        $confidence = min(99, ($amount / 1000) * 10 + rand(40, 60));
    }

    $arbs[] = [
        "type" => "DEX",
        "asset" => strtoupper($swap['sold']['symbol']."/".$swap['bought']['symbol']),
        // "asset" => strtoupper(substr($swap['sold']['symbol']."/".$swap['bought']['symbol'], 0, 6)),
        "exchange" =>$swap['exchangeName']."/".$chain,
        "price" => $price,
        "amount" => $amount,
        "confidence" => round($confidence, 2)
    ];
}

// ------------------ FETCH CEX ------------------

$coins = [
    "solana" => "SOL",

    "cardano" => "ADA",

    "polkadot" => "DOT",

    "chainlink" => "LINK",

    "ripple" => "XRP",

    "dogecoin" => "DOGE",

    "litecoin" => "LTC",

    "bitcoin-cash" => "BCH",

    "avalanche-2" => "AVAX",

    "tron" => "TRX",

    "stellar" => "XLM"

];

$ids = implode(",", array_keys($coins));

$cgUrl = "https://api.coingecko.com/api/v3/simple/price?ids=$ids&vs_currencies=usd";

$cgData = json_decode(file_get_contents($cgUrl), true);

$exchanges = ["BINANCE", "KRAKEN", "KUCOIN", "OKX", "HUOBI", "BITMEX", "BITFINEX"];

foreach ($coins as $id => $symbol) {

    if (!isset($cgData[$id]['usd'])) continue;

    $base = $cgData[$id]['usd'];

    // 🔥 simulate buy/sell across exchanges

    $buy = $base * (1 - mt_rand(1, 10) / 1000);

    $sell = $base * (1 + mt_rand(1, 10) / 1000);

    $spread = $sell - $buy;

    $volume = mt_rand(10, 1000);

    $confidence = min(99, ($spread * 1000) + rand(40, 60));

    $from = $exchanges[array_rand($exchanges)];

    $to = $exchanges[array_rand($exchanges)];

    $arbs[] = [

        "type" => "CEX",

        "asset" => $symbol,

        "exchange" => "$from/$to",

        "price" => $buy, // you can keep one price field

        "amount" => $volume,

        "confidence" => round($confidence, 2)

    ];

}

// Return clean structured data
echo json_encode([
    "status" => "success",
    "data" => $arbs
]);