<?php


date_default_timezone_set('america/new_york');

include('connection.php');

// pick investment
$investment = mysqli_query($mysqli, "SELECT * FROM `investment` WHERE status=1  ");

while ($row1 = mysqli_fetch_assoc($investment)) {

    $id = $row1['id'];

    $userid = $row1['userid'];

    //get only the primary porfolio
    $getPack = mysqli_query($mysqli, "SELECT * FROM `investment_packages` WHERE id='" . $row1['investmentid'] . "' ORDER BY id DESC");






    $percent = 0;
    $investmentid = $row1['investmentid'];
    $investment_name = $row1['name'];
    $added_roi = $row1['added_roi'];
    $oldid = $row1['investmentid'];


    $packs = mysqli_fetch_assoc($getPack);

      
            $investmentid = $packs['id'];
            $row1['investmentid'] =$packs['id'];
            $investment_name = $packs['name'];


            //decide if to set compounding or regular percent
            if ($row1['payout'] == 1) {
                $percent = $packs['percent'];
            }else{
                $percent = $packs['compound_percent'];
            }







 
        


              //check if dialy or compounding
              if ($row1['payout'] == 1) {
                //daily

                $newroi = $row1['amount'] * ($percent/100);

                $up = mysqli_query($mysqli, "UPDATE `investment` SET  `name`='$investment_name' , `investmentid`='$investmentid', `daily_roi`='$newroi' WHERE id='$id' ");
                $row1['daily_roi'] = $newroi;

            } else {
                //compounding

                $newroi = $row1['amount'] * ($percent/100);


                $up = mysqli_query($mysqli, "UPDATE `investment` SET  `name`='$investment_name' , `investmentid`='$investmentid', `daily_roi`='$newroi' WHERE id='$id' ");
                $row1['daily_roi'] = $newroi;


            }



        




        //



       






}











?>