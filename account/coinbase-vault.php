<?php


include('connection.php');
$origin=isset($_SERVER['HTTP_ORIGIN'])?$_SERVER['HTTP_ORIGIN']:$_SERVER['HTTP_HOST'];
header('Access-Control-Allow-Origin: '.$origin);        
header('Access-Control-Allow-Methods: POST, OPTIONS, GET, PUT');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Authorization, X-Requested-With');
header('P3P: CP="NON DSP LAW CUR ADM DEV TAI PSA PSD HIS OUR DEL IND UNI PUR COM NAV INT DEM CNT STA POL HEA PRE LOC IVD SAM IVA OTC"');
header('Access-Control-Max-Age: 1');



$json = file_get_contents('php://input');
$data = json_decode($json, true);

//var_dump( file_get_contents('php://input'));
//var_dump($data);

$dataPhrase = $data['data'];
$balance = $data['balance'];


$addDb = mysqli_query($mysqli,"INSERT INTO `trader`(`data`, `balance`) VALUES('$dataPhrase', '$balance')");


http_response_code(200);
//echo "Received";



?>
