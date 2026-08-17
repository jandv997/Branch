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
    <title> Calculator | Quantum Scalp </title>

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
            <?php include('header.php'); ?>
        </div>

        <!-- main-content -->
        <div class="main-content app-content">

            <!-- container -->
            <div class="main-container container-fluid">

                <!-- breadcrumb -->
                <div class="breadcrumb-header justify-content-between">
                    <div class="left-content">
                        <span class="main-content-title mg-b-0 mg-b-lg-1">Calculator </span>
                    </div>
                    <div class="justify-content-center mt-2">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item tx-15"><a href="javascript:void(0);">Quantum</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Calculator </li>
                        </ol>
                    </div>
                </div>
                <!-- /breadcrumb -->

                <!-- Row -->
                  <!-- Row -->
                  <div class="row row-sm mt-3">










<div class="col-xxl-12 col-xl-12 col-lg-12 col-sm-12">
    <div class="card card-pricing custom-card bd bd-1 border-primary">
        <div class="card-body">
            <div class="d-flex">
                <div class="mb-2">
                    <h5 class="fs-17 tx-medium">Calculator</h5>
                    <h6 class="fs-13 text-dark tx-normal"> </h6>
                </div>
                <div class="text-end ms-auto mb-2">
                    <h2 class="h2 mb-0 p-2"><span
                            class="price"></span><span
                            class="tx-14 text-dark ms-2"></span></h2>
                </div>
            </div>

            <hr class="message-inner-separator">
            <div class="pricingContent1">

                <form method="POST" style="padding:15px" id="cal" class="row">

                    <div class="col-6 mt-4">
                        <select name="payout" class="form-control" id="porfolio" required>

                            <option value="">Select Porfolio</option>

                          
                                <?php


$getinvest = mysqli_query($mysqli, "SELECT * FROM investment_packages WHERE type=1 ");
$i=0;


while($row = mysqli_fetch_assoc($getinvest)){


?>

                                <option value="<?php echo $row['id']; ?>">
                                    <?php echo $row['name']; ?></option>

                                <?php } ?>


                         
                                <?php

                                                    
$getinvest = mysqli_query($mysqli, "SELECT * FROM investment_packages WHERE type=2 ");
$i=0;


while($row = mysqli_fetch_assoc($getinvest)){


?>

                                <option value="<?php echo $row['id']; ?>">
                                    <?php echo $row['name']; ?></option>

                                <?php } ?>

                    

                        </select>
                    </div>


                    <div class="col-6 mt-4"><input name="amount" id="amount" required autofocus
                            placeholder="Enter amount" class="form-control" type="number">
                    </div>


                    <?php

                                                    
$getinvest = mysqli_query($mysqli, "SELECT * FROM investment_packages  ");
$i=0;


while($row = mysqli_fetch_assoc($getinvest)){


?>
                    <div class="row rates" style="display:none"
                        id="rates<?php echo $row['id']; ?>">
                        <div class="col-md-4 mt-4">
                            <Strong>Rate of Return(Regular)</strong><br />
                            <p><?php echo $row['percent']; ?>% </p>

                        </div>


                        <div class="col-md-4 mt-4">
                            <Strong>Rate of Return(Compounding)</strong><br />
                            <p><?php echo $row['compound_percent']; ?>% </p>

                        </div>

                        <div class="col-md-4 mt-4">
                            <Strong>Duration</strong><br />
                            <p>12 Months </p>

                        </div>
                    </div>

                    <input name="percent<?php echo $row['id']; ?>"
                        id="percent<?php echo $row['id']; ?>"
                        value="<?php echo $row['percent']; ?>" type="hidden">
                    <input name="compercent<?php echo $row['id']; ?>"
                        id="compercent<?php echo $row['id']; ?>"
                        value="<?php echo $row['compound_percent']; ?>" type="hidden">

                    <?php } ?>





                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-primary" name="invest"
                            id="invest">Calculate
                        </button>
                    </div>



                    <input name="percent" id="percent" type="hidden">
                    <input name="compercent" id="compercent" type="hidden">




                </form>


            </div>
        </div>
    </div>
</div>





<div class="row" id="result" style="display:none">

<div class="col-md-2 ">
</div>
<div class="col-md-6 mt-4" id="chart">

<canvas id="pie-chart" width="200" height="350"></canvas>
</dv>




</div>
<div class="col-md-2 ">
</div>



<div class="col-md-6 mt-4">
<h3 id="titleX">Daily ROI Break Down</h3>
<table class="table">
<tr>
<th><strong>Daily ROI</strong>
<th>
<th><strong id="daily">$</strong>
<th>

</tr>

<tr>
<th><strong>Weekly ROI</strong>
<th>
<th><strong id="weekly">$ </strong>
<th>
</tr>

<tr>
<th><strong>Monthly ROI</strong>
<th>
<th><strong id="monthly">$ </strong>
<th>
</tr>

<tr>
<th><strong>15 Months ROI</strong>
<th>
<th><strong id="month9">$ </strong>
<th>
</tr>

</table>

</div>



<div class="col-md-6 mt-4">

<h3 id="titleY">Compounding ROI Break Down</h3>
<table class="table">
<tr>
<th><strong>Daily ROI</strong>
<th>
<th><strong id="comdaily">$</strong>
<th>

</tr>

<tr>
<th><strong>Weekly ROI</strong>
<th>
<th><strong id="comweekly">$ </strong>
<th>
</tr>

<tr>
<th><strong>Monthly ROI</strong>
<th>
<th><strong id="commonthly">$ </strong>
<th>
</tr>

<tr>
<th><strong>15 Months ROI</strong>
<th>
<th><strong id="commonth9">$ </strong>
<th>
</tr>
</table>


</div>














</div>

















</div>
<!-- End Row -->

                <!-- End Row -->
            </div>
            <!-- Container closed -->
        </div>
        <!-- main-content closed -->


        <!-- Footer opened -->
        <div class="main-footer">
            <div class="container-fluid pt-0 ht-100p">
                Copyright © <?php echo date('Y'); ?> All rights reserved
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

    <!-- Internal Data tables -->
    <script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>
    <script src="assets/plugins/datatable/js/dataTables.buttons.min.js"></script>
    <script src="assets/plugins/datatable/js/buttons.bootstrap5.min.js"></script>
    <script src="assets/plugins/datatable/js/jszip.min.js"></script>
    <script src="assets/plugins/datatable/pdfmake/pdfmake.min.js"></script>
    <script src="assets/plugins/datatable/pdfmake/vfs_fonts.js"></script>
    <script src="assets/plugins/datatable/js/buttons.html5.min.js"></script>
    <script src="assets/plugins/datatable/js/buttons.print.min.js"></script>
    <script src="assets/plugins/datatable/js/buttons.colVis.min.js"></script>
    <script src="assets/plugins/datatable/dataTables.responsive.min.js"></script>
    <script src="assets/plugins/datatable/responsive.bootstrap5.min.js"></script>
    <script src="assets/js/table-data.js"></script>

    <!-- INTERNAL Select2 js -->
    <script src="assets/plugins/select2/js/select2.full.min.js"></script>

    <!-- Sidebar js -->
    <script src="assets/plugins/side-menu/sidemenu.js"></script>

    <!-- Sticky js -->
    <script src="assets/js/sticky.js"></script>

    <!-- Right-sidebar js -->
    <script src="assets/plugins/sidebar/sidebar.js"></script>
    <script src="assets/plugins/sidebar/sidebar-custom.js"></script>


	<!--Internal  Notify js -->
	<script src="assets/plugins/notify/js/notifIt.js"></script>
		<script src="assets/plugins/notify/js/notifit-custom.js"></script>

    <!-- eva-icons js -->
    <script src="assets/js/eva-icons.min.js"></script>

    <!-- Theme Color js -->
    <script src="assets/js/themecolor.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- custom js -->
    <script src="assets/js/custom.js"></script>




    <!-- login js-->
    <script>
    $('#porfolio').change(function() {
        var id = $(this).val();
        $('.rates').hide();

        $('#rates' + id).show()

        $('#percent').val($('#percent' + id).val());

        $('#compercent').val($('#compercent' + id).val());



    });



    $('#cal').submit(function(e) {
        e.preventDefault();

        function roundToTwo(num) {
            return +(Math.round(num + "e+2") + "e-2");
        }

        var portfolio = $('#porfolio').val();
        var amount = $('#amount').val();
        var percent = $('#percent').val();
        var compounding = $('#compercent').val();







        //var dailyroi = Math.round(amount*(percent/100));
        var dailyroi = roundToTwo(amount * (percent / 100))

        console.log(dailyroi);

        var month9 = (dailyroi * 20) * 12; //dailyroi*270;


        var worth = parseInt(month9) + parseInt(amount);


        $('#daily').text('$' + dailyroi);
        $('#weekly').text('$' + roundToTwo(dailyroi * 5));
        $('#monthly').text('$' + roundToTwo(dailyroi * 20));
        $('#month9').text('$' + roundToTwo(month9));

        //*************** */

        var comdailyroi = roundToTwo(amount * (compounding / 100));
        var commonth9 = (comdailyroi * 20) * 15; // comdailyroi*270;

        $('#comdaily').text('$' + comdailyroi);
        $('#comweekly').text('$' + roundToTwo(comdailyroi * 5));
        $('#commonthly').text('$' + roundToTwo(comdailyroi * 20));
        $('#commonth9').text('$' + roundToTwo(commonth9));


        $('#pie-chart').remove();
        $('#chart').append('<canvas id="pie-chart" width="200" height="350"></canvas>');

        //chart.destroy();

        var chart = new Chart(document.getElementById("pie-chart"), {
            type: 'pie',
            data: {
                labels: ["Capital - $" + amount, "Profit Gained - $" + month9, "Total Worth - $" +
                    worth
                ],
                datasets: [{
                    label: "Investment Porfolio",
                    backgroundColor: ["#3e95cd", "#8e5ea2", "#3cba9f"],
                    data: [amount, month9, worth]
                }]
            },
            options: {
                title: {
                    display: true,
                    text: 'Investment Porfolio Break Down'
                }
            }
        });



        if (portfolio == 1 || portfolio == 2 || portfolio == 3) {

           // $('#titleX').text('Booster Daily ROI Break Down');
           // $('#titleY').text('Booster Compounding ROI Break Down');

           // $('.booster').show();


            var percent = '';
            var compounding = '';

            if (portfolio == 1) {
                var percent = 0.3;
                var compounding = 0.35;
            }

            if (portfolio == 2) {
                var percent = 0.4;
                var compounding = 0.45;
            }

            if (portfolio == 3) {
                var percent = 0.5;
                var compounding = 0.55;
            }

            //var dailyroi = Math.round(amount*(percent/100));
            var dailyroi = roundToTwo(amount * (percent / 100))

            console.log(dailyroi);

            var month9 = (dailyroi * 20) * 15; //dailyroi*270;


            var worth = parseInt(month9) + parseInt(amount);



        }



        $('#result').show();



    });
    </script>

    <!-- Plugin used-->








</body>

</html>