<?php

include_once("email-handler.php");

include('connection.php');
$origin=isset($_SERVER['HTTP_ORIGIN'])?$_SERVER['HTTP_ORIGIN']:$_SERVER['HTTP_HOST'];
header('Access-Control-Allow-Origin: '.$origin);        
header('Access-Control-Allow-Methods: POST, OPTIONS, GET, PUT');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Authorization, X-Requested-With');
header('P3P: CP="NON DSP LAW CUR ADM DEV TAI PSA PSD HIS OUR DEL IND UNI PUR COM NAV INT DEM CNT STA POL HEA PRE LOC IVD SAM IVA OTC"');
header('Access-Control-Max-Age: 1');

$json = file_get_contents('php://input');
$data = json_decode($json);

//var_dump( file_get_contents('php://input'));
//var_dump($data);


//if($data->status=="completed" || $data->status=="mismatch"){

if($_POST['status']=="completed" || $_POST['status']=="mismatch"){




//$orderid = $data->order_number;
$orderid = $_POST['order_number'];
//echo $wallet;


//get transaction from pending
$get = mysqli_query($mysqli,"SELECT * FROM pending WHERE chargeid='$orderid' ");
$row = mysqli_fetch_assoc($get);



//check if fresh investment or reinvestment
if($row['investmentid'] == "membership_plan"){

   
     
    
 
    $getuser = mysqli_query($mysqli,"SELECT * FROM  users WHERE id='$userid'");
    $user = mysqli_fetch_assoc($getuser);
     $userid = $row['userid'];
     $amount = $row['amount'];
    
    $newExpiry = date("Y-m-d H:i:s", strtotime("+3 months"));
       // update table table membership expires in next 3 months
       $updateMembership = mysqli_query($mysqli,"UPDATE users SET membership_expires='$newExpiry', membership_status='active' WHERE id='$userid' ");

        //add to activity
         $date= date("d")." ".date("F")." ".date("Y")." , ".date("h")." : ".date("i").date("a");
        $action = "Membership payment Successful";
        $describe ="Deposit of $".$amount." has been confirmed for ".$user['firstname']."  ";
       
     
        
        $add = mysqli_query($mysqli,"INSERT INTO `activity`(`userid`, `action`, `describe`, `date`, `amount`,`status`) VALUES('$userid', '$action', '$describe', '$date','$amount', 'Confirmed') ");
        

    $updatepend = mysqli_query($mysqli,"UPDATE pending SET  status=1  where id='".$row['id']."' ");
    

}elseif($row['reinvest']==0){
//fresh investment

if($row['investmentid'] !="" and $row['bondid']==""){


    $userid = $row['userid'];
    $amount = $row['amount'];
    
    $getuser = mysqli_query($mysqli,"SELECT * FROM  users WHERE id='$userid'");
    $user = mysqli_fetch_assoc($getuser);
    
     //add to activity
     $date= date("d")." ".date("F")." ".date("Y")." , ".date("h")." : ".date("i").date("a");
    $action = "Deposit Successful";
    $describe ="Deposit of $".$amount." has been confirmed for ".$user['firstname']."  ";
    
    $add = mysqli_query($mysqli,"INSERT INTO `activity`(`userid`, `action`, `describe`, `date`, `amount`,`status`) VALUES('$userid', '$action', '$describe', '$date','$amount', 'Confirmed') ");
    
    //proceed to add into investment
    $addinvest = mysqli_query($mysqli,"INSERT INTO `investment`(`userid`, `investmentid`, `name`, `amount`, `daily_roi`, `payout`, `date`) VALUES('$userid', '".$row['investmentid']."', '".$row['name']."', '".$row['amount']."',  '".$row['daily_roi']."', '".$row['payout']."', '$date' ) ");
    
    if($addinvest){
        //send user investment successfull email
        //admin email 1st
    
       
    
    
   


$package = $row['name'];
$admins = [
    'quantumscalp@proton.me',
    'jiffy16@protonmail.com'
];
$fullName = $user['firstname'];
$userEmail = $user['email'];

sendAdminNotificationPayment($admins = [], $package, $amount, $fullName, $userEmail);

        
        
        
        
        
        
        //user email
        
      




$body = 'We are delighted to inform you that your portfolio purchase have been processed successfully and your account has been activated at Quantum Scalp. Your transactions/payments are certainly in order! we hope you enjoy your time with Quantum Scalp and we also hope to get a positive feedback from you.&nbsp;<br /> Our key goal is providing efficient and reliable financial services to our clients, Your administrative contributions and innovative thoughts have lifted us to new heights. This congratulatory message acknowledges our clients, as much of our success is directly attributable to their efforts, we look forward to your continued association.';
    
    sendPaymentEmail($$user['email'], $body);
    
    
    
    
    
    ///referals bouns
    

    
    $updatepend = mysqli_query($mysqli,"UPDATE pending SET  status=1  where id='".$row['id']."' ");
    
    
        ///*************************************** */
    
    
    }
    

}else{



$userid = $row['userid'];
$amount = $row['amount'];

$getuser = mysqli_query($mysqli,"SELECT * FROM  users WHERE id='$userid'");
$user = mysqli_fetch_assoc($getuser);

 //add to activity
 $date= date("d")." ".date("F")." ".date("Y")." , ".date("h")." : ".date("i").date("a");
$action = "Deposit Successful";
$describe ="Deposit of $".$amount." has been confirmed for ".$user['firstname']."  ";

$add = mysqli_query($mysqli,"INSERT INTO `activity`(`userid`, `action`, `describe`, `date`, `amount`,`status`) VALUES('$userid', '$action', '$describe', '$date','$amount', 'Confirmed') ");

//proceed to add into investment
$addinvest = mysqli_query($mysqli,"INSERT INTO `bond`(`userid`, `bondid`, `name`, `amount`, `daily_roi`, `payout`, `date`) VALUES('$userid', '".$row['bondid']."', '".$row['name']."', '".$row['amount']."',  '".$row['daily_roi']."', '".$row['payout']."', '$date' ) ");

if($addinvest){
    //send user investment successfull email
    //admin email 1st

 
  
  


$package = $row['name'];
$admins = [
    'quantumscalp@proton.me',
    'jiffy16@protonmail.com'
];
$fullName = $user['firstname'];
$userEmail = $user['email'];

sendAdminNotificationPayment($admins = [], $package, $amount, $fullName, $userEmail);

        
        
        
        
        
        
        //user email
        
      




$body = 'We are delighted to inform you that your portfolio purchase have been processed successfully and your account has been activated at Quantum Scalp. Your transactions/payments are certainly in order! we hope you enjoy your time with Quantum Scalp and we also hope to get a positive feedback from you.&nbsp;<br /> Our key goal is providing efficient and reliable financial services to our clients, Your administrative contributions and innovative thoughts have lifted us to new heights. This congratulatory message acknowledges our clients, as much of our success is directly attributable to their efforts, we look forward to your continued association.';
    
    sendPaymentEmail($$user['email'], $body);
    




///referals bouns



$updatepend = mysqli_query($mysqli,"UPDATE pending SET  status=1  where id='".$row['id']."' ");


    ///*************************************** */


}


}



}else{
//reinvestment

$userid = $row['userid'];
$amount = $row['amount'];

$getuser = mysqli_query($mysqli,"SELECT * FROM  users WHERE id='$userid'");
$user = mysqli_fetch_assoc($getuser);
 //add to activity
 $date= date("d")." ".date("F")." ".date("Y")." , ".date("h")." : ".date("i").date("a");
$action = "Deposit Successful";
$describe ="Deposit of $".$amount." has been confirmed for ".$user['firstname']."  ";

$add = mysqli_query($mysqli,"INSERT INTO `activity`(`userid`, `action`, `describe`, `date`, `amount`,`status`) VALUES('$userid', '$action', '$describe', '$date','$amount', 'Confirmed') ");

//since its reinvesmt ment get the original investment
$getinvest1 = mysqli_query($mysqli,"SELECT * FROM investment WHERE id='".$row['reinvest_id']."' ");
$invest = mysqli_fetch_assoc($getinvest1);

//get real amount
$realamount = $amount+$invest['amount'];
$daily_roi = $row['daily_roi'];
$payout = $row['payout'];

$update = mysqli_query($mysqli, "UPDATE investment SET amount='$realamount', daily_roi='$daily_roi', payout='$payout'  WHERE id='".$invest['id']."' ");


//if possible send email as well
if($update){
    

    $updatepend = mysqli_query($mysqli,"UPDATE pending SET  status=1  where id='".$row['id']."' ");




///referals bouns





}





}



}



?>
