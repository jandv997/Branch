<?php
session_start();

include('connection.php');


//check if session id is set if it is redirect to login
if (!isset($_SESSION['id'])) {

    header("location:index");

} else {

    $get_user = mysqli_query($mysqli, "SELECT * FROM users WHERE id='" . $_SESSION['id'] . "' ");
    $rows = mysqli_fetch_assoc($get_user);
    if (isset($_SESSION['2fa'])) {

        if ($_SESSION['2fa'] == "no" and $row['2fa'] == 1) {
            header("location:index");
        }

        if ($_SESSION['2fa'] == "yes" and $row['2fa'] == 1) {
            header("location:dashboard");
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
    <title>2FA | Quantum Scalp </title>

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

    <script src="//code.tidio.co/boh34gato9oarfy1efgvdwn7x1rfiex5.js" async></script>


</head>

<body class="ltr error-page1 bg-primary">

    <!-- Loader -->
    <div id="global-loader">
    <img src="img/favicon.png" width="50" class="loader-img" alt="Loader">
    </div>
    <!-- /Loader -->

    <div class="page">
        <div class="page-single">
            <div class="container">
                <div class="row">
                    <div
                        class="col-xl-5 col-lg-6 col-md-8 col-sm-8 col-xs-10 card-sigin-main py-4 justify-content-center mx-auto">
                        <div class="card-sigin">
                            <!-- Demo content-->
                            <div class="main-card-signin d-md-flex">
                                <div class="wd-100p">
                                    <div class="mb-3 d-flex"> </div>
                                    <div class="main-card-signin d-md-flex bg-white">


                                        <div class="wd-100p">
                                            <div class="d-flex mx-auto"> <a href="javascript:;"
                                                    class="mx-auto d-flex"><img src="assets/img/brand/favicon.png"
                                                        class="sign-favicon ht-40 mx-auto" alt="logo">
                                                    <h1 class="main-logo1 ms-1 me-0 my-auto tx-28 text-dark ms-2">
                                                       Quantum Scalp</h1>
                                                </a></div>
                                            <div class="main-card-signin d-md-flex bg-white">
                                                <div class="p-4 wd-100p">
                                                    <div class="main-signin-header">
                                                        <div
                                                            class="avatar avatar-xxl avatar-xxl mx-auto text-center mb-2">
                                                            <img alt=""
                                                                class="avatar avatar-xxl rounded-circle  mt-2 mb-2 "
                                                                src="<?php echo $rows['img']; ?>"></div>
                                                        <div class="mx-auto text-center mt-4 mg-b-20">
                                                            <h5 class="mg-b-10 tx-16"><?php echo $rows['firstname']." ".$rows['lastname']; ?></h5>
                                                            <p class="tx-13 text-muted">Enter Your OTP to View your
                                                                Dashboard</p>
                                                        </div>
                                                        <form method="POST">
                                                            <div class="form-group">
                                                                <input class="form-control"
                                                                    placeholder="Enter your otp" type="text"  id="code" name="code"
                                                                    value="">
                                                            </div>
                                                            <button  name="validate"  type="submit" class="btn btn-primary btn-block">Unlock</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- JQuery min js -->
    <script src="assets/plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap js -->
    <script src="assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

    <!-- Moment js -->
    <script src="assets/plugins/moment/moment.js"></script>

    <!-- eva-icons js -->
    <script src="assets/js/eva-icons.min.js"></script>


    
        		<!--Internal  Notify js -->
				<script src="assets/plugins/notify/js/notifIt.js"></script>
		<script src="assets/plugins/notify/js/notifit-custom.js"></script>
		<!--Internal  Perfect-scrollbar js -->

    <!--Internal  Perfect-scrollbar js -->
    <script src="assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>

    <!-- Theme Color js -->
    <script src="assets/js/themecolor.js"></script>

    <!-- custom js -->
    <script src="assets/js/custom.js"></script>

</body>





<?php

if(isset($_POST['validate'])){
//retrive the entered code

$check_this_code = $_POST['code'];


require_once("google_authenticator/index.php"); 

$g = new \Google\Authenticator\GoogleAuthenticator();

$secret=$rows['2fa_key'];



if ($g->checkCode($secret, $check_this_code)) {
 

     $_SESSION['2fa']='yes';

      ?>

<script>
  location = 'dashboard';
</script>

<?php



} else {
 ?>
<script>
  



 

notif({
		msg: "<b>Invalid Code</b> <br/> The inputted authentication code is invalid!",
		width: 250,
		position: "center",
		type: "error",
		fade: true
	});

</script>

<?php


}





}


?>







</html>