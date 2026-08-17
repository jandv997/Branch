<?php


date_default_timezone_set('america/new_york');

include('connection.php');

// pick investment
$investment=mysqli_query($mysqli,"SELECT * FROM `investment` WHERE status=1");

while($row1=mysqli_fetch_assoc($investment)){

    $id=$row1['id'];

    $userid= $row1['userid'];




$stmt = $mysqli->prepare("SELECT membership_expires FROM users WHERE id=?");

$stmt->bind_param("i", $userid);

$stmt->execute();

$stmt->bind_result($exp);

$stmt->fetch();

$stmt->close(); 

if (!$exp || strtotime($exp) < time()) {
    // no active membership
    continue; // skip to the next investment

}



$getuser = mysqli_query($mysqli,"SELECT * FROM `users` where id='$userid' ");
$rowuser = mysqli_fetch_assoc($getuser);




//make sure the user is eligble to earn and its not sunday
if($rowuser['can_earn'] ==1 && (date("l")!="Sunday" and date("l")!="Saturday") ){



    if($row1['auto_reinvest']=="1"){


      //get the porfolio 
      $getport = mysqli_query($mysqli,"SELECT * FROM `investment_packages` WHERE id='".$row1['investmentid']."' ");
      $port = mysqli_fetch_assoc($getport);
     

      $daily_roi = $row1['daily_roi'];

      //1st increment added_roi
      $update_added_roi = $row1['added_roi'] + $row1['daily_roi'];
  
  

      $newamount = $row1['amount']+$daily_roi;
      $newroi = $newamount * ($port['percent']/100);

      $payout = $row1['payout'];
      if($payout == 1){
        $newroi = $newamount*($port['percent']/100);
        
        }else{
            $newroi = $newamount*($port['compound_percent']/100);
        }

      $up = mysqli_query($mysqli, "UPDATE `investment` SET amount='$newamount', `daily_roi`='$newroi' WHERE id='$id' ");
  

      $date = date("d") . " " . date("F") . " " . date("Y") . " , " . date("h") . " : " . date("i") . date("a");
      $action = "Return on Investment for " . $row1['name'];
      $describe = "Investment profit of $" . $row1['daily_roi'] . " for " . $row1['name'] . "  ";
      $amount = $daily_roi;
      $investmentid = $row1['id'];

      $add = mysqli_query($mysqli, "INSERT INTO `activity`(`userid`, `investmentid`, `action`, `describe`, `date`, `amount`, `status`) VALUES('$userid', '$investmentid', '$action', '$describe', '$date','$amount', 'Credited') ");



      $date = date("d") . " " . date("F") . " " . date("Y") . " , " . date("h") . " : " . date("i") . date("a");
      $action = "Auto-Reinvestment Processed for " . $row1['name'];
      $describe = "Auto-Reinvestment Processed  for $" . $row1['daily_roi']. " on " . $row1['name'] . "  ";
      $amount = $row1['daily_roi'];
      $investmentid = $row1['id'];

      $add = mysqli_query($mysqli, "INSERT INTO `activity`(`userid`, `investmentid`, `action`, `describe`, `date`, `amount`, `status`) VALUES('$userid', '$investmentid', '$action', '$describe', '$date','$amount', 'Credited') ");




    $update_profit = $rowuser['profit']+$row1['daily_roi'];

    //run updatae
    $up1 = mysqli_query($mysqli,"UPDATE `users` SET `profit`='$update_profit' where id='$userid' ");









    }elseif($row1['payout'] == 1){


    $daily_roi = $row1['daily_roi'];

    //1st increment added_roi
    $update_added_roi =   $row1['added_roi']+$row1['daily_roi'];

    $update_wallet = $rowuser['wallet']+$row1['daily_roi'];
    $update_profit = $rowuser['profit']+$row1['daily_roi'];

    //run updatae
    $up1 = mysqli_query($mysqli,"UPDATE `users` SET `wallet`='$update_wallet', `profit`='$update_profit' where id='$userid' ");

    //2nd update 
    $up2 = mysqli_query($mysqli,"UPDATE `investment` SET `added_roi`='$update_added_roi' where id='$id' ");


    $date= date("d")." ".date("F")." ".date("Y")." , ".date("h")." : ".date("i").date("a");
    $action = "Return on Investment for ".$row1['name'];
    $describe ="Investment profit of $".$row1['daily_roi']." for ".$row1['name']."  ";
    $amount = $daily_roi;
    $investmentid=$row1['id'];

    $add = mysqli_query($mysqli,"INSERT INTO `activity`(`userid`, `investmentid`, `action`, `describe`, `date`, `amount`,`status`) VALUES('$userid', '$investmentid', '$action', '$describe', '$date','$amount', 'Credited') ");

    } elseif ($row1['payout'] == 2){
    //ehh if the person is compounding add money to his compound wallet


    $daily_roi = $row1['daily_roi'];

    //1st increment added_roi
    $update_added_roi =   $row1['added_roi']+$row1['daily_roi'];

    $update_compound = $rowuser['compound']+$row1['daily_roi'];
    $update_profit = $rowuser['compound_profit']+$row1['daily_roi'];

    //run updatae
    $up1 = mysqli_query($mysqli,"UPDATE `users` SET `compound_profit`='$update_profit', `profit`='$update_profit' where id='$userid' ");

    //2nd update 
    $up2 = mysqli_query($mysqli,"UPDATE `investment` SET `added_roi`='$update_added_roi' where id='$id' ");


    $date= date("d")." ".date("F")." ".date("Y")." , ".date("h")." : ".date("i").date("a");
    $action = "Return on Investment for ".$row1['name'];
    $describe ="Investment profit of $".$row1['daily_roi']." for ".$row1['name']."  ";
    $amount = $daily_roi;
    $investmentid=$row1['id'];

    $add = mysqli_query($mysqli,"INSERT INTO `activity`(`userid`, `investmentid`, `action`, `describe`, `date`, `amount`, `status`) VALUES('$userid', '$investmentid', '$action', '$describe', '$date','$amount', 'Credited') ");

    //end of compounding

    }elseif ($row1['payout'] == 3) {
        // 70% should go to compound and 30% should go to wallet
        $wallet_amount = $row1['daily_roi'] * 0.25;
        $compound_amount = $row1['daily_roi'] * 0.75;

        $daily_roi = $row1['daily_roi'];


        $update_wallet = $rowuser['wallet'] + $wallet_amount;
        $update_compound = $rowuser['compound_profit'] + $compound_amount;

        $up1 = mysqli_query($mysqli,"UPDATE `users` SET `wallet`='$update_wallet', `compound_profit`='$update_compound' where id='$userid' ");




        $update_added_roi =   $row1['added_roi']+$row1['daily_roi'];

        //2nd update 
        $up2 = mysqli_query($mysqli,"UPDATE `investment` SET `added_roi`='$update_added_roi' where id='$id' ");


    //input for 30% wallet amount
    $date= date("d")." ".date("F")." ".date("Y")." , ".date("h")." : ".date("i").date("a");
    $action = "Return on Investment (25%) for ".$row1['name'];
    $describe ="Investment profit of $".$row1['daily_roi']." for ".$row1['name']."  ";
    $amount = $wallet_amount;
    $investmentid=$row1['id'];

    $add = mysqli_query($mysqli,"INSERT INTO `activity`(`userid`, `investmentid`, `action`, `describe`, `date`, `amount`, `status`) VALUES('$userid', '$investmentid', '$action', '$describe', '$date','$wallet_amount', 'Credited') ");



    //input for 70% 
        $date= date("d")." ".date("F")." ".date("Y")." , ".date("h")." : ".date("i").date("a");
    $action = "Return on Investment (75%) for ".$row1['name'];
    $describe ="Investment profit of $".$row1['daily_roi']." for ".$row1['name']."  ";
    $amount = $compound_amount;
    $investmentid=$row1['id'];

    $add = mysqli_query($mysqli,"INSERT INTO `activity`(`userid`, `investmentid`, `action`, `describe`, `date`, `amount`, `status`) VALUES('$userid', '$investmentid', '$action', '$describe', '$date','$compound_amount', 'Credited') ");

    }




    //give referral bonus

    $price_amount = $daily_roi;


// Define referral bonus percentages by level
$levels = [
    1 => 0.10,   // 10%
    2 => 0.05,   // 5%
    3 => 0.025,  // 2.5%
    4 => 0.015,  // 1.5%
    5 => 0.01,    // 1%
    6 => 0.005,   // 0.5%
    7 => 0.0025  // 0.25%
];

// Start with the first referrer
$currentRefLink = $rowuser['referred'];

//ensure the referral bonus is applied only to real portfolio and not bonus portfolio
if($row1['bonus'] ==0){

for ($level = 1; $level <= 7; $level++) {
    if ($currentRefLink == "") break; // no more uplines

    // Check if referral bonus for this level has already been paid
    $payField = "pay_refer" . $level;

        $bonus = $levels[$level] * $price_amount;

        // Find the referrer
        $getrefer = mysqli_query($mysqli, "SELECT * FROM users WHERE referal_link='" . $currentRefLink . "' ");
        $refer = mysqli_fetch_assoc($getrefer);

        if ($refer) {
            $act  = "Referral Bonus of " . ($levels[$level] * 100) . "%, Level $level";
            $desc = "Referral commission of $" . $bonus;

            // Insert into activity
            mysqli_query($mysqli, "INSERT INTO `activity`(`userid`, `action`, `describe`, `date`, `amount`, `status`) 
                VALUES('" . $refer['id'] . "', '$act', '$desc', '$date', '$bonus', 'Credited')");

            // Insert into referral
            mysqli_query($mysqli, "INSERT INTO `referal` (`claimerid`, `status`, `date`, `amount`, `detail`) 
                VALUES('" . $refer['id'] . "', 1, '$date', '$bonus', '$act')");

            // Update referrer's wallet
            $newwallet = $refer['ref_wallet'] + $bonus;
            mysqli_query($mysqli, "UPDATE users SET ref_wallet='$newwallet' WHERE id='" . $refer['id'] . "' ");

            // Mark that this user has had this referral bonus paid
            //mysqli_query($mysqli, "UPDATE users SET $payField=0 WHERE id='" . $user['id'] . "' ");
        }

        // Set up for the next loop (go one level higher)
        $currentRefLink = $refer['referred'] ?? "";
 
}

}



} //end of check 




//adjust duration
$duration = $row1['duration'];

$newduration = $duration - 1;

if($row1['duration'] > 1){
    $upp = mysqli_query($mysqli,"UPDATE `investment` SET `duration`='$newduration' where id='$id' ");
}else{
    $upp = mysqli_query($mysqli,"UPDATE `investment` SET `status`=0, `duration`='$newduration' where id='$id' ");
}


//adjust Staking duration
$staking_duration = $row1['staking_duration'];

$new_staking_duration = $staking_duration - 1;

if($row1['staking_duration'] > 1){
    $upp = mysqli_query($mysqli,"UPDATE `investment` SET `staking_duration`='$new_staking_duration' where id='$id' ");
}else{
    $new_staking_duration =0;
    $upp = mysqli_query($mysqli,"UPDATE `investment` SET `payout`=1, `staking_duration`='$new_staking_duration' where id='$id' ");
}





}




















?>
