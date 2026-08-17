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

if (filter_var($email, FILTER_VALIDATE_EMAIL)){


  $getacct = mysqli_query($mysqli,"SELECT email, firstname, lastname FROM `users` WHERE email='$email' ");

  if(mysqli_num_rows($getacct)>0){

    $r = mysqli_fetch_assoc($getacct);

    $fullname = $r['firstname']." ".$r['lastname'];


    header( 'Content-Type: application/json; charset=utf-8');
   
    $set['code']=array('message' => " Account Valid ",  "fullname"=>$fullname  , 'status'=>'1');
  
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
   
  $set['code']=array('message' => "Validation Error , Email is Invalid",'status'=>'0');

  $msg = json_encode($set);
  echo $msg;



}



     







}


?>

