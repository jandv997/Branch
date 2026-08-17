<?php


date_default_timezone_set('america/new_york');

include('connection.php');



function calculateCommission($mysqli, $referralLink, $level, $maxLevels, &$commission)
{
    if ($level > $maxLevels)
        return; // Stop if max level reached

    $getRefer = mysqli_query($mysqli, "SELECT * FROM users WHERE referred='$referralLink'");

    while ($refer = mysqli_fetch_assoc($getRefer)) {
        $getInvestment = mysqli_query($mysqli, "SELECT * FROM investment WHERE userid='{$refer['id']}' AND bonus='0' ORDER BY id DESC");

        while ($in = mysqli_fetch_assoc($getInvestment)) {
            // Calculate main capital if investment type 8 or 9
           

            $id = $in['investmentid'];
            $amount = $in['amount'];

         
                // Default case
                $mainCapital = $amount;
            

            $commission += $mainCapital;
        }

        // Recur for the next level
        calculateCommission($mysqli, $refer['referal_link'], $level + 1, $maxLevels, $commission);
    }
}


// pick investment
$getusers = mysqli_query($mysqli, "SELECT * FROM `users` WHERE status=1");


while ($user = mysqli_fetch_assoc($getusers)) {


        $date = date("d F Y, h:i a");
        $userid = $user['id'];
        $commission = 0; // Initial commission



            $maxLevels = 7;

        

            // Start calculation from level 1
            calculateCommission($mysqli, $user['referal_link'], 1, $maxLevels, $commission);


            echo "User ID: $userid, Total Commission: $commission, Date: $date\n <br/>";

        }



?>
