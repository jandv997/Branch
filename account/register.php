<?php
session_start();


//check if session id is set if it is redirect to dashboard
if(isset($_SESSION['id']) and (isset($_SESSION['2fa']) and $_SESSION['2fa'] == "yes")){
	
	header("location:dashboard");
}else{
     

    if(isset($_SESSION['2fa'])){

        if($_SESSION['2fa'] == "pending"){
        header("location:authenticate");
    }

    if($_SESSION['2fa'] == "no"){
        header("location:dashboard");
    }

    }

}


include('connection.php');

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">


    <!-- Title -->
    <title> Quantum Group | Sign Up </title>

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

    <!---Skin modes css-->
    <link href="assets/css/skin-modes.css" rel="stylesheet" />

    <!--- Animations css-->
    <link href="assets/css/animate.css" rel="stylesheet">

<!-- Start of LiveChat (www.livechat.com) code -->
<script>
    window.__lc = window.__lc || {};
    window.__lc.license = 19834219;
    window.__lc.integration_name = "manual_onboarding";
    window.__lc.product_name = "livechat";
    ;(function(n,t,c){function i(n){return e._h?e._h.apply(null,n):e._q.push(n)}var e={_q:[],_h:null,_v:"2.0",on:function(){i(["on",c.call(arguments)])},once:function(){i(["once",c.call(arguments)])},off:function(){i(["off",c.call(arguments)])},get:function(){if(!e._h)throw new Error("[LiveChatWidget] You can't use getters before load.");return i(["get",c.call(arguments)])},call:function(){i(["call",c.call(arguments)])},init:function(){var n=t.createElement("script");n.async=!0,n.type="text/javascript",n.src="https://cdn.livechatinc.com/tracking.js",t.head.appendChild(n)}};!n.__lc.asyncInit&&e.init(),n.LiveChatWidget=n.LiveChatWidget||e}(window,document,[].slice))
</script>
<noscript><a href="https://www.livechat.com/chat-with/19834219/" rel="nofollow">Chat with us</a>, powered by <a href="https://www.livechat.com/?welcome" rel="noopener nofollow" target="_blank">LiveChat</a></noscript>
<!-- End of LiveChat code -->

</head>

<body class="ltr error-page1 bg-primary">

    <!-- Loader -->
    <div id="global-loader">
        <img src="img/favicon.png" width="50" class="loader-img" alt="Loader">
    </div>
    <!-- /Loader -->

    <div class="square-box">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>

    <div class="page">
        <div class="page-single">
            <div class="container">
                <div class="row">
                    <div
                        class="col-xl-5 col-lg-6 col-md-8 col-sm-8 col-xs-10 card-sigin-main mx-auto my-auto py-4 justify-content-center">
                        <div class="card-sigin">
                            <!-- Demo content-->
                            <div class="main-card-signin d-md-flex">
                                <div class="wd-100p">
                                    <div class="d-flex mb-4"><a href="index"><img src="assets/img/brand/favicon.png"
                                                class="sign-favicon ht-40" alt="logo"></a></div>
                                    <div class="">
                                        <div class="main-signup-header">
                                            <h2>Sign Up !</h2>
                                            <h6 class="font-weight-semibold mb-4">Please Select Account Type</h6>
                                            <div class="panel panel-primary mt-3">



                                                <div class="row">

                                                    <div class="col-12 mt-4">

                                                        <div class="card text-center">
                                                            <div class="card-body">

                                                                <div class="mx-auto mb-4">
                                                                    <i class=" bx bx-buildings"
                                                                        style="font-size:50px"></i>
                                                                </div>
                                                                <h5 class="font-size-16 mb-1"><a href="#"
                                                                        class="text-dark">Business </a></h5>
                                                                <p class="text-muted mb-2">Account</p>

                                                            </div>

                                                            <div class="btn-group" role="group">
                                                                <a href="business<?php if(isset($_GET['refer'])){ echo "?refer=".$_GET['refer']; } ?>"
                                                                    class="btn btn-primary  text-truncate" style="background-color:#e49f37"  ><i
                                                                        class="uil uil-user me-1"></i> Sign Up</a>


                                                            </div>
                                                        </div>


                                                    </div>


                                                    <div class="col-12 mt-4">

                                                        <div class="card text-center">
                                                            <div class="card-body">


                                                                <div class="mx-auto mb-4">

                                                                    <i class=" bx bx-user" style="font-size:35px"></i>
                                                                </div>
                                                                <h5 class="font-size-16 mb-1"><a href="#"
                                                                        class="text-dark">Individual </a></h5>
                                                                <p class="text-muted mb-2">Account</p>

                                                            </div>

                                                            <div class="btn-group" role="group">
                                                                <a href="individual<?php if(isset($_GET['refer'])){ echo "?refer=".$_GET['refer']; } ?>"
                                                                    class="btn btn-primary text-truncate"><i
                                                                        class="uil uil-user me-1"></i> Sign Up</a>


                                                            </div>
                                                        </div>


                                                    </div>



                                                </div>



                                            </div>

                                            <div class="main-signin-footer text-center mt-3">

                                                <p>Already have an account <a href="index">Sign In</a></p>
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

    <!-- generate-otp js -->
    <script src="assets/js/generate-otp.js"></script>


    <!--Internal  Notify js -->
    <script src="assets/plugins/notify/js/notifIt.js"></script>
    <script src="assets/plugins/notify/js/notifit-custom.js"></script>

    <!--Internal  Perfect-scrollbar js -->
    <script src="assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>

    <!-- Theme Color js -->
    <script src="assets/js/themecolor.js"></script>

    <!-- custom js -->
    <script src="assets/js/custom.js"></script>








</body>

</html>