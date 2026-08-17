<?php

include('connection.php');

$TOKEN = "8231332001:AAHnhdyRdJUo53mv1NpMCGjeIzv0DzsvwWE";
$SOURCE_CHAT_ID = -1002365081625;
// destination group id
$DEST_CHAT_ID   = -1003949251754;

$update = json_decode(file_get_contents("php://input"), true);

if (!isset($update["message"])) {
    exit;
}

$message = $update["message"];
$chat_id = $message["chat"]["id"];

// ONLY PROCESS SOURCE GROUP
if ($chat_id != $SOURCE_CHAT_ID) {
    exit;
}

// GET MESSAGE TEXT
$text = $message["text"] ?? "";

function shouldSkipMessage($text) {

    $blacklist = [

        'Bot',



        'iOS',

        'Android',

        'Telegram'

    ];

    foreach ($blacklist as $word) {

        if (stripos($text, $word) !== false) {

            return true; // skip this message

        }

    }

    return false;

}


function transformMessage($text) {

    // Replace anything inside [ArbitraDar.io]

    $text = preg_replace(

        '/\[ArbitraDar\.io\]/i',

        'QuantumScalp.io',

        $text

    );

    return $text;

}

function saveMessage($conn, $text) {

    $stmt = $conn->prepare("
        INSERT INTO bot_messages (message_text)
        VALUES (?)
    ");

    $stmt->bind_param("s", $text);
    $stmt->execute();
}

// 🚫 SKIP unwanted promo messages

if (shouldSkipMessage($text)) {

    exit; // stop script, don't send

}

// transform message
$text = transformMessage($text);

// OR custom cleanup
// $newText = preg_replace('/https?:\/\/\S+/', '', $text); // remove links

 saveMessage($mysqli, $text);

// SEND TO DESTINATION
$send = sendMessage($DEST_CHAT_ID, $text);
 echo $send;

function sendMessage($chat_id, $text) {
    global $TOKEN;

    $url = "https://api.telegram.org/bot$TOKEN/sendMessage";

    $data = [
        "chat_id" => $chat_id,
        "text" => $text,
        "parse_mode" => "HTML"
    ];


$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_POSTFIELDS =>'{
     "chat_id": '.$chat_id.',
        "text": "'.$text.'",
        "parse_mode": "HTML"
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
    return $response;

}

?>

