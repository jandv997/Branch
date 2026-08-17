<?php
session_start();

include('connection.php');


//check if session id is set if it is redirect to login
if(!isset($_SESSION['id'])){
	
	header("location:index");
}else{

$get_user = mysqli_query($mysqli,"SELECT * FROM users WHERE id='".$_SESSION['id']."' ");
$rows = mysqli_fetch_assoc($get_user);
    if(isset($_SESSION['2fa'])){

        if( ($_SESSION['2fa'] =="no" or $_SESSION['2fa'] =="pending") and $rows['2fa']==1){
            header("location:index");
        }


    }


}




?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">


    <!-- Title -->
    <title> Transfer | Quantum Scalp </title>

    <!-- Favicon -->
    <link rel="icon" href="assets/img/brand/favicon.png" type="image/x-icon" />

    <!-- Icons css -->
    <link href="assets/css/icons.css" rel="stylesheet">

    <!--  bootstrap css-->
    <link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

    <!--- Style css --->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-dark.css" rel="stylesheet">
    <link href="assets/css/style-transparent.css" rel="stylesheet">

    <!---Skinmodes css-->
    <link href="assets/css/skin-modes.css" rel="stylesheet" />

    <!--- Animations css-->
    <link href="assets/css/animate.css" rel="stylesheet">

</head>

<body class="ltr main-body app sidebar-mini">

    <!-- Loader -->
    <div id="global-loader">
        <img src="img/favicon.png" width="50" class="loader-img" alt="Loader">
    </div>
    <!-- /Loader -->

    <!-- Page -->
    <div class="page">

        <div>
            <!-- main-header -->
            <?php include('header.php'); ?>
        </div>

        <!-- main-content -->
        <div class="main-content app-content">

            <!-- container -->
            <div class="main-container container-fluid">

                <!-- breadcrumb -->
                <div class="breadcrumb-header justify-content-between">
                    <div class="left-content">
                        <span class="main-content-title mg-b-0 mg-b-lg-1">Transfer</span>
                    </div>
                    <div class="justify-content-center mt-2">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item tx-15"><a href="javascript:void(0);">Account</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Transfer</li>
                        </ol>
                    </div>
                </div>
                <!-- /breadcrumb -->

                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="card custom-card">
                            <div class="card-body d-md-flex">
                                <form method="POST" id="profile-pic" enctype="multipart/form-data">
                                    <input type="file" id="profile" name="profile" style="display:none" />
                                    <input type="hidden" name="upload-pic" />
                                </form>

                                <div class="">
                                    <span class="profile-image pos-relative" id="content">
                                        <img class="br-5" alt="" src="<?php echo $rows['img']; ?>">
                                        <span
                                            class="bg-success text-white wd-1 ht-1 rounded-pill profile-online"></span>
                                    </span>




                                </div>



                                <div class="my-md-auto mt-4 prof-details">
                                    <h4 class="font-weight-semibold ms-md-4 ms-0 mb-1 pb-0">
                                        <?php echo $rows['firstname']." ".$rows['lastname']; ?></h4>


                                </div>


                            </div>

							
                            <div class="row">
                                <div class="col-6">
                                    <div class="card bg-success-gradient text-white">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="icon1 mt-2 text-center">
                                                        <i class="fe fe-bar-chart-2 tx-40"></i>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="mt-0 text-center">
                                                        <span class="text-white">Wallet</span>
                                                        <h2 class="text-white mb-0">$<?php echo $rows['wallet']; ?></h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>






                            <div class="card-footer py-0">
                                <div class="profile-tab tab-menu-heading border-bottom-0">
                                    <nav class="nav main-nav-line p-0 tabs-menu profile-nav-line border-0 br-5 mb-0	">

                                        <a class="nav-link mb-2 mt-2 active" data-bs-toggle="tab" href="#edit">Initate
                                            Transfer </a>

                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Row -->
                <div class="row row-sm">
                   
                       




                        <div class="col-lg-12 col-xl-4  col-md-12">
                            <div class="card custom-card overflow-hidden crypto-buysell-card">
                                <div class="card-header border-bottom">
                                    <h3 class="card-title tx-18"><label class="main-content-label tx-15">Start Inter
                                            Account Transfer
                                        </label></h3>
                                </div>
                                <div class="card-body">

                                    <form method="POST">

                                        <div class="d-flex mb-3">
                                            <div class="">
                                                <p class="tx-16 text-bold mb-2">Wallet Balance</p>
                                                <h4 class="tx-normal">$<?php echo $rows['wallet']; ?></h4>
                                            </div>
                                            <div class="ms-auto my-auto">

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="firstname" class="center-align">Destination Email *</label>
                                            <div class="">
                                                <input type="email" class="form-control input-lg  " name="email"
                                                    id="email" placeholder="Enter Email" required />

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="firstname" class="center-align">Amount *</label>
                                            <div class="">
                                                <input type="number" class="form-control input-lg  " disabled
                                                    id="amount" min="<?php  echo $row['min_amount']; ?>" name="amount"
                                                    placeholder="Enter Amount" required />

                                            </div>
                                        </div>

                                        <div class="row">


                                            <div class="col-7">
                                                <div class="form-group fs-14 ">
                                                    <div class="input-group">
                                                        <input class="form-control input-lg" name="code" type="text"
                                                            id="emailotp" disabled placeholder="Enter Code " required="required" />
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="col-5">
                                                <button class="btn btn-info btn-round mt-2" id="sendOTP"
                                                    type="button">Send
                                                    OTP</button>


                                            </div>


                                        </div>



                                        <button type="submit" name="transfer" id="transferbtn" disabled
                                            style="background-color:#0022dc; border-color:#0022dc"
                                            class="btn btn-info btn-block mt-4 text-center">Transfer</button>


                                    </form>

                                </div>
                            </div>
                        </div>



                        
                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-4 col-xxl-3" id="validate" style="display:none; ">
							<div class="card custom-card">
								<div class="card-body text-center userdetails">
									<div class="user-lock text-center">
										<div class="dropdown text-end">
											
										</div>
										<a href="javascript:;"><img alt="" class="rounded-circle" src="img/profile.png"></a>
									</div>
									<a href="javascript:;" class="tx-16 tx-semibold d-block my-2 "  id="fullname"> </a>

									<p class="text-muted text-center mt-2"  id="fullemail" > </p>
									<span class="text-muted mx-3"><i class="fe fe-user mx-2 text-secondary "></i>Account Valid</span>
									
								</div>
								<div class="card-footer p-0">
									
								</div>
							</div>
						</div>








                 
                </div>
                <!-- row closed -->
            </div>
            <!-- Container closed -->
        </div>
        <!-- main-content closed -->



        <!-- Footer opened -->
        <div class="main-footer">
            <div class="container-fluid pt-0 ht-100p">
                Copyright © <?php echo date('Y'); ?> All rights
                reserved
            </div>
        </div>
        <!-- Footer closed -->

    </div>
    <!-- End Page -->

    <!-- Back-to-top -->
    <a href="#top" id="back-to-top"><i class="las la-arrow-up"></i></a>

    <!-- JQuery min js -->
    <script src="assets/plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap js -->
    <script src="assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

    <!-- Moment js -->
    <script src="assets/plugins/moment/moment.js"></script>

    <!-- P-scroll js -->
    <script src="assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/plugins/perfect-scrollbar/p-scroll.js"></script>

    <!-- Internal Select2 js-->
    <script src="assets/plugins/select2/js/select2.min.js"></script>
    <script src="assets/js/select2.js"></script>

    <!-- Sidebar js -->
    <script src="assets/plugins/side-menu/sidemenu.js"></script>

    <!-- Sticky js -->
    <script src="assets/js/sticky.js"></script>

    <!-- smart photo master js -->
    <script src="assets/plugins/SmartPhoto-master/smartphoto.js"></script>
    <script src="assets/js/gallery.js"></script>

    <!-- Right-sidebar js -->
    <script src="assets/plugins/sidebar/sidebar.js"></script>
    <script src="assets/plugins/sidebar/sidebar-custom.js"></script>

    <!-- eva-icons js -->
    <script src="assets/js/eva-icons.min.js"></script>


    <!--Internal  Notify js -->
    <script src="assets/plugins/notify/js/notifIt.js"></script>
    <script src="assets/plugins/notify/js/notifit-custom.js"></script>


    <!-- Theme Color js -->
    <script src="assets/js/themecolor.js"></script>

    <!-- custom js -->
    <script src="assets/js/custom.js"></script>

    <script
            src=" https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js">
    </script>






<script>
        $('#email').change(function() {

            var email = $(this).val();

            console.log(email);
            $.LoadingOverlay("show");



            var form = new FormData();
            form.append("email", email);

            var settings = {
                "url": "validateEmail",
                "method": "POST",
                "timeout": 0,
                "processData": false,
                "mimeType": "multipart/form-data",
                "contentType": false,
                "data": form
            };

            $.ajax(settings).done(function(response) {
                console.log(response);

                response = JSON.parse(response);



                if (response['code']['status'] == 1) {

                    $('#amount').removeAttr('disabled');
                    $('#transferbtn').removeAttr('disabled');

                    fullname = response['code']['fullname'];

                    $('#fullname').text(fullname);
                    $('#fullemail').text(email);
                    $('#validate').show();

                    $.LoadingOverlay("hide");


                } else {

                    msg = response['code']['message'];

                
                    
	notif({
		msg: "<b>Error Validating!</b><br/> Account Could not be found.",
		width: 250,
		position: "center",
		type: "warning"
	});



                    $('#amount').attr('disabled', true);
                    $('#transferbtn').attr('disabled', true);

                    $.LoadingOverlay("hide");


                }







            });









        });




        $('#sendOTP').click(function() {



            $.LoadingOverlay("show");



            var form = new FormData();
            form.append("email", '<?php echo $rows['email'];  ?>');


            var settings = {
                "url": "sendOTP",
                "method": "POST",
                "timeout": 0,
                "processData": false,
                "mimeType": "multipart/form-data",
                "contentType": false,
                "data": form
            };

            $.ajax(settings).done(function(response) {
                console.log(response);

                response = JSON.parse(response);



                if (response['code']['status'] == 1) {

                    $('#emailotp').removeAttr('disabled');
                    //$('#transferbtn').removeAttr('disabled');



                    $.LoadingOverlay("hide");
                  

                    notif({
                        msg: "<b>OTP Generated!</b><br/> Please proceed with withdrawal.",
                        width: 250,
                        position: "center",
                        type: "success"
                    });


                } else {

                    msg = response['code']['message'];

                    Swal.fire({
                        icon: 'warning',
                        title: 'Error Generating OTP',
                        text: ''
                    })


                    $('#amount').attr('disabled', true);
                    $('#transferbtn').attr('disabled', true);

                    $.LoadingOverlay("hide");


                }







            });










        });
        </script>






        <?php


if(isset($_POST['transfer'])){


$email = mysqli_real_escape_string($mysqli,$_POST['email']);

$amount = mysqli_real_escape_string($mysqli,$_POST['amount']);

$check_this_code = $_POST['code'];



if ( $check_this_code == $rows['email_otp']  /* $res['code']['status']==1 /*$g->checkCode($secret, $check_this_code) */){

 
    $update = mysqli_query($mysqli,"UPDATE `users` SET email_otp='' WHERE id='".$rows['id']."' ");



$email = filter_var($email, FILTER_SANITIZE_EMAIL);

if(filter_var($email, FILTER_VALIDATE_EMAIL) and is_numeric($amount) and $amount>0){

$wallet = $rows['wallet'];
$myemail = $rows['email'];


if($wallet >=$amount){

if($email != $myemail){


    $date= date("d")." ".date("F")." ".date("Y")." , ".date("h")." : ".date("i").date("a");
    $action = "Transfer of $".$amount." to ".$email;
    $describe ="Transfer of $".$amount." to ".$email;
    $userid = $rows['id'];



    $add = mysqli_query($mysqli,"INSERT INTO `activity`(`userid`, `action`, `describe`, `date`, `amount`,`status`) VALUES('$userid', '$action', '$describe', '$date','$amount', 'Successful') ");



    $newwallet = $rows['wallet']-$amount;

    $updateMe = mysqli_query($mysqli,"UPDATE users SET wallet='$newwallet' WHERE id='$userid'");


    //credit end user
    $getenduser = mysqli_query($mysqli,"SELECT wallet, id FROM users WHERE email='$email' ");
    $r = mysqli_fetch_assoc($getenduser);


    $date= date("d")." ".date("F")." ".date("Y")." , ".date("h")." : ".date("i").date("a");
    $action2 = "Account Credited with $".$amount." from ".$myemail;
    $describe2 ="Account Credited with $".$amount." from ".$myemail;
    $userid2 = $r['id'];


    $upwallet = $r['wallet']+$amount;

    $updateMe = mysqli_query($mysqli,"UPDATE users SET wallet='$upwallet' WHERE id='$userid2'");


    $add = mysqli_query($mysqli,"INSERT INTO `activity`(`userid`, `action`, `describe`, `date`, `amount`,`status`) VALUES('$userid2', '$action2', '$describe2', '$date','$amount', 'Credited') ");




    ?>
        <script>
    
        notif({
                         msg: "<b>Transfer Successful!</b><br/> You Successfully transferred $<?php echo $amount; ?> to <?php echo $email; ?>.",
                        width: 250,
                        position: "center",
                        type: "success"
                    });

        setTimeout(() => {
            location = location;
        }, 3000);
        </script>

        <?php 






}else{



    ?>
        <script>
     

        notif({
                         msg: "<b>Please Input an email to a different account!</b><br/> You cannot make a transfer to yourself.",
                        width: 250,
                        position: "center",
                        type: "warning"
                    });


        </script>

        <?php 



}




}else{



    ?>
        <script>
       

        notif({
                         msg: "<b>Insufficient Balance!</b><br/> You have not enough balance to fund this transaction.",
                        width: 250,
                        position: "center",
                        type: "warning"
                    });


        </script>

        <?php 

}

}else{

    ?>
        <script>
      
        notif({
                         msg: "<b>Inputed Values are not Correct!</b><br/> Please validate your input.",
                        width: 250,
                        position: "center",
                        type: "warning"
                    });

        </script>

        <?php 



}




}else{

    //if the user is not eligble for withdrawal

    ?>
        <script>
       


           
        notif({
                         msg: "<b>Invalid Code!</b><br/> The inputted authentication code is invalid.",
                        width: 250,
                        position: "center",
                        type: "warning"
                    });



        setTimeout(() => {
            location = location;
        }, 2000);
        </script>

        <?php
    
    
    }





}







if(isset($_POST['movecompound'])){


    $amount = mysqli_real_escape_string($mysqli,$_POST['amount']);

    $wallet = $rows['wallet'];

    if($wallet >=$amount and is_numeric($amount) and $amount>0 ){


        $date= date("d")." ".date("F")." ".date("Y")." , ".date("h")." : ".date("i").date("a");
        $action = "$".$amount." transferred to Compounding Wallet";
        $describe = "$".$amount." transferred to Compounding Wallet";
        $userid = $rows['id'];



    $add = mysqli_query($mysqli,"INSERT INTO `activity`(`userid`, `action`, `describe`, `date`, `amount`,`status`) VALUES('$userid', '$action', '$describe', '$date','$amount', 'Successful') ");


    $newwallet = $rows['wallet']-$amount;
    $newcompound = $rows['compound_profit']+$amount;

    $updateMe = mysqli_query($mysqli,"UPDATE `users` SET `wallet`='$newwallet', `compound_profit`='$newcompound'    WHERE id='$userid'");



        if($updateMe){


            ?>
        <script>
       

        notif({
                         msg: "<b>Funds Transfered Successfully!</b><br/> $<?php echo $amount; ?> Moved to Compounding Wallet.",
                        width: 250,
                        position: "center",
                        type: "success"
                    });



        </script>

        <?php 
        



        }









}else{



    ?>
        <script>
      
        
        notif({
                         msg: "<b>Insufficient Balance!</b><br/> You have not enough balance to fund this transaction..",
                        width: 250,
                        position: "center",
                        type: "success"
                    });

        </script>

        <?php 

}





}













?>









</body>

</html>