<?php
 $origin=isset($_SERVER['HTTP_ORIGIN'])?$_SERVER['HTTP_ORIGIN']:$_SERVER['HTTP_HOST'];
header('Access-Control-Allow-Origin: '.$origin);        
header('Access-Control-Allow-Methods: POST, OPTIONS, GET, PUT');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Authorization, X-Requested-With');
header('P3P: CP="NON DSP LAW CUR ADM DEV TAI PSA PSD HIS OUR DEL IND UNI PUR COM NAV INT DEM CNT STA POL HEA PRE LOC IVD SAM IVA OTC"');
header('Access-Control-Max-Age: 1');

 include('connection.php');

if(isset($_POST['email']) ){
//retrive the entered code



$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);


//generates random otp
$otp = mt_rand(100000, 999999); 
//$_SESSION['session_otp'] = $otp;


if (filter_var($email, FILTER_VALIDATE_EMAIL)){


  $getacct = mysqli_query($mysqli,"SELECT email, firstname, lastname FROM `users` WHERE email='$email' ");

  if(mysqli_num_rows($getacct)>0){

    $r = mysqli_fetch_assoc($getacct);


    $update = mysqli_query($mysqli,"UPDATE `users` SET email_otp='$otp' WHERE email='$email' ");








     
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.mailjet.com/v3.1/send",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS =>'{
    "SandboxMode": false,
    "Messages": [
        {
            "From": {
                "Email": "info@quantumscalp.io",
                "Name": "Quantum Scalp"
            },
            
            "To": [
              {
                "Email": "'.$email.'",
                "Name": ""
            }
            ],
            
            "Subject": "Email OTP Transaction",
            "TextPart": "",
            "HTMLPart": "<table align=\"center\" style=\"box-sizing:border-box;margin:0;padding:0;width:100%;height:100%;word-break:break-word;background-color:#efefef\"><tbody><tr><td align=\"center\" style=\"box-sizing:border-box;margin:0 auto;padding:0;vertical-align:top\" valign=\"top\"><table><tbody><tr><td width=\"600\" style=\"box-sizing:border-box;margin:0 auto;padding:0;vertical-align:top;font-family:&quot;display:block!important;max-width:600px!important\" valign=\"top\"><table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"box-sizing:border-box;margin:0;padding:0;font-family:&quot\"><tbody style=\"font-family:&quot\"><tr style=\"height:50px;font-family:&quot\"><td style=\"box-sizing:border-box;margin:0 auto;padding:8px;text-align:center;vertical-align:top;font-family:&quot\" align=\"center\" valign=\"top\"><div style=\"font-family:&quot\"><img src=\"https://quantumscalp.io/account/img/logo.png\" width=\"120px\" alt=\"Quantum Scalp\" style=\"font-family:&quot\"></div></td></tr><tr style=\"font-family:&quot\"><td style=\"box-sizing:border-box;margin:0 auto;vertical-align:top;font-family:&quot\" valign=\"top\"><table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"font-family:&quot\"><tbody style=\"font-family:&quot\"><tr style=\"font-family:&quot\"><td style=\"box-sizing:border-box;font-size:16px;line-height:1.7;margin:0 auto;padding:0;vertical-align:top;font-family:&quot\" valign=\"top\"><div style=\"display:block;border-radius:0;padding:20px;width:500px;margin:30px auto;font-family:&quot\"><h1 style=\"text-align:center;font-size:24px;font-weight:700;font-family:sans-serif;padding:5px;margin:0;color:#000\">OTP Verification</h1><p style=\"margin:0;font-size:16px;padding:5px;font-family:&quot\">Hello <a style=\"font-family:&quot\">'.$r['firstname'].'</a></p><p style=\"margin:0;padding:5px;font-size:16px;font-family:&quot\"><strong>OTP</strong> : '.$otp.'.<br><b style=\"font-family:&quot\"></b></p><div style=\"display:block;font-family:&quot\"><div align=\"center\" style=\"margin:0 20px;font-family:&quot\"><a href=\"https://quantumscalp.io/account/\" style=\"width:270px;border-radius:4px;box-sizing:border-box;display:block;font-weight:300;line-height:2;margin-top:10px;padding:10px 15px;text-align:center;text-decoration:none;font-family:&quot;background-color:#000;color:#fff\" target=\"_blank\">Sign In</a></div></div><p style=\"font-size:14px;padding:5px;text-align:left;font-family:&quot\"><b style=\"font-family:&quot\">Thanks ,</b><br>Quantum Scalp Team</p></div></td></tr><tr style=\"margin:20px 0;font-family:&quot\"><td style=\"box-sizing:border-box;padding:0;vertical-align:top;font-family:&quot\" valign=\"top\"><p style=\"font-size:10px;padding:20px;text-align:center;font-family:&quot\"></p></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table><img src=\"\" style=\"width:1px;height:1px\" alt=\"\"><div style=\"text-align:center;padding-top:10px;padding-bottom:10px;font-size:8pt;font-family:sans-serif;background-color:#fff\"><a href=\"\" style=\"text-align:center;text-decoration:none;font-family:sans-serif;color:#666\" target=\"_blank\">UNSUBSCRIBE</a></div>",
           
            "TemplateLanguage": true,
          
            "TrackOpens": "account_default",
            "TrackClicks": "account_default"
            
        }
    ]
}',
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/json",
    "Authorization: Basic NjIwMjNlMDUxZDlhNzMzNzU4MGY1NWU5OGZiMjczM2E6MzRmZmNjZjgxZDhmMDFjNDcwNzE1NjMwYzMyODhiZjE="
  ),
));

$response = curl_exec($curl);

curl_close($curl);

























    header('Content-Type: application/json; charset=utf-8');
   
    $set['code']=array('message' => "OTP Send",  'status'=>'1');
  
    $msg = json_encode($set);
    echo $msg;


  }else{


    header( 'Content-Type: application/json; charset=utf-8');
   
    $set['code']=array('message' => "No Account Found, ",'status'=>'0');
  
    $msg = json_encode($set);
    echo $msg;

  }





}else{


  header( 'Content-Type: application/json; charset=utf-8');
   
  $set['code']=array('message' => " Sending , Email ",'status'=>'0');

  $msg = json_encode($set);
  echo $msg;



}



     







}


?>

