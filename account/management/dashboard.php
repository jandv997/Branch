<?php
session_start();

include('connection.php');


//check if session id is set if it is redirect to login
if(!isset($_SESSION['adminid'])){
    
    header("location:index");
    
}


$get_admin = mysqli_query($mysqli,"SELECT * FROM admins WHERE id='".$_SESSION['adminid']."' ");
$rows = mysqli_fetch_assoc($get_admin);



?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="description" content="Dashboard ">


    <!-- Favicon -->
    <link rel="icon" href="img/icon.png" type="image/x-icon" />

    <!-- Title -->
    <title>Dashboard </title>

    <!-- Bootstrap css-->
    <link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Icons css-->
    <link href="assets/web-fonts/icons.css" rel="stylesheet" />
    <link href="assets/web-fonts/font-awesome/font-awesome.min.css" rel="stylesheet">
    <link href="assets/web-fonts/plugin.css" rel="stylesheet" />

    <!-- Style css-->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/plugins.css" rel="stylesheet">


    <link rel="stylesheet" href="swal/sweetalert2.min.css">
    <!-- INTERNAL Switcher css -->
    <link href="assets/switcher/css/switcher.css" rel="stylesheet" />
    <link href="assets/switcher/demo.css" rel="stylesheet" />

</head>

<body class="main-body leftmenu ltr light-theme dark-menu">

    <!-- Loader -->
    <div id="global-loader">
        <img src="assets/img/loader.svg" class="loader-img" alt="Loader">
    </div>
    <!-- End Loader -->


    <!-- Page -->
    <div class="page">



        <?php include('header.php'); ?>



        <?php
//get all the needed info for dashboard
$getusers = mysqli_query($mysqli,"SELECT * FROM users");
$totalusers = mysqli_num_rows($getusers);

$getblocked = mysqli_query($mysqli,"SELECT * FROM users where status=0");
$blocked = mysqli_num_rows($getblocked);
//loop throughusers
$totalbalance=0;

while($count = mysqli_fetch_assoc($getusers)){
 $totalbalance += $count['wallet'];   
 
}

$getinvestment = mysqli_query($mysqli,"SELECT * FROM investment");
$totalinvestamount=0;
while($in = mysqli_fetch_assoc($getinvestment)){
 $totalinvestamount += $in['amount'];   
 

}

$getinvestmentpack = mysqli_query($mysqli,"SELECT * FROM investment_packages");
$totalinvestpack=mysqli_num_rows($getinvestmentpack);


$getwithdraw = mysqli_query($mysqli,"SELECT * FROM withdrawal WHERE status=1");
$totalwithamount=0;
while($out = mysqli_fetch_assoc($getwithdraw)){
 $totalwithamount += $out['amount'];   
 

}



$getwithdrawss = mysqli_query($mysqli,"SELECT * FROM withdrawal_method ");
$totalwithdraw=mysqli_num_rows($getwithdrawss);



?>

        <!-- Main Content-->
        <div class="main-content side-content pt-0">
            <div class="main-container container-fluid">
                <div class="inner-body">

                    <!-- Page Header -->
                    <div class="page-header">
                        <div>
                            <h2 class="main-content-title tx-24 mg-b-5">Dashboard </h2>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Break Down</li>
                            </ol>
                        </div>
                        <div class="d-flex">
                            <div class="justify-content-center">


                            </div>
                        </div>
                    </div>
                    <!-- End Page Header -->

                    <!--Row-->
                    <div class="row row-sm">
                        <div class="col-sm-12 col-lg-12 col-xl-12">

                            <!--Row-->
                            <div class="row row-sm">
                                <div class="col-sm-12 col-md-12 col-lg-4 col-xl-3 col-xxl-2">
                                    <div class="card custom-card">
                                        <div class="card-body">
                                            <div class="card-item">
                                                <div class="card-item-icon bg-success-transparent">
                                                    <svg class="text-primary wd-20 ht-20" fill="#19b159"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        enable-background="new 0 0 24 24" viewBox="0 0 24 24">
                                                        <path
                                                            d="M22.5,10h-4.0005493C18.2234497,10.0001831,17.9998169,10.223999,18,10.5v12.0005493C18.0001831,22.7765503,18.223999,23.0001831,18.5,23h4.0006104C22.7765503,22.9998169,23.0001831,22.776001,23,22.5V10.4993896C22.9998169,10.2234497,22.776001,9.9998169,22.5,10z M22,22h-3V11h3V22z M14.5,2h-4.0005493C10.2234497,2.0001831,9.9998169,2.223999,10,2.5v20.0005493C10.0001831,22.7765503,10.223999,23.0001831,10.5,23h4.0006104C14.7765503,22.9998169,15.0001831,22.776001,15,22.5V2.4993896C14.9998169,2.2234497,14.776001,1.9998169,14.5,2z M14,22h-3V3h3V22z M6.5,14H2.4993896C2.2234497,14.0001831,1.9998169,14.223999,2,14.5v8.0005493C2.0001831,22.7765503,2.223999,23.0001831,2.5,23h4.0006104C6.7765503,22.9998169,7.0001831,22.776001,7,22.5v-8.0006104C6.9998169,14.2234497,6.776001,13.9998169,6.5,14z M6,22H3v-7h3V22z" />
                                                    </svg>
                                                </div>
                                                <div class="card-item-title mb-2">
                                                    <label class="main-content-label tx-13 mb-1">Total Users</label>
                                                </div>
                                                <div class="card-item-body">
                                                    <div class="card-item-stat">
                                                        <h4 class="font-weight-normal">
                                                            <?php echo number_format($totalusers); ?></h4>
                                                        <small><b class="badge rounded-pill bg-success fs-11"><i
                                                                    class="fe fe-arrow-up"></i></b><span
                                                                class="px-1"></span></small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card custom-card">
                                        <div class="card-body">
                                            <div class="card-item">
                                                <div class="card-item-icon bg-warning-transparent">
                                                    <svg class="text-primary wd-20 ht-20" fill="#ff9b21"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        enable-background="new 0 0 24 24" viewBox="0 0 24 24">
                                                        <path
                                                            d="M17.5,9.0009766c0.0001831,0,0.0003662,0,0.0005493,0c0.276001-0.0001221,0.4996338-0.223999,0.4994507-0.5V8.0020142c0.0003662-0.1011963-0.0302734-0.2000732-0.0878906-0.2832642c-0.3666992-1.5889282-1.7803955-2.715271-3.4111328-2.7177734L12.5,5.0001831V2.5C12.5,2.223877,12.276123,2,12,2s-0.5,0.223877-0.5,0.5V5h-2C7.5679932,5.0023193,6.0023193,6.5679932,6,8.5v0.5009766c0.0025635,1.9315796,1.5674438,3.4968872,3.4990234,3.5L12,12.5020142c0.0001221,0,0.0001831-0.000061,0.0003052-0.000061h2.5006714c1.3795776,0.0023804,2.4971924,1.1204224,2.4990234,2.5v0.5009766c-0.0012817,1.380188-1.119812,2.4987183-2.5,2.5h-2.4854736C12.0093994,18.0027466,12.005127,18,12,18c-0.005249,0-0.0096436,0.0028076-0.0148315,0.0029907h-2.486145c-1.3795776-0.0023804-2.4971924-1.1204224-2.4990234-2.5c0-0.276123-0.223877-0.5-0.5-0.5s-0.5,0.223877-0.5,0.5v0.4990234c-0.0002441,0.1014404,0.0303955,0.2005005,0.0878906,0.2841187c0.3677979,1.5880737,1.7810059,2.713623,3.4111328,2.7167969H11.5V21.5c0,0.0001831,0,0.0003662,0,0.0005493C11.5001831,21.7765503,11.723999,22.0001831,12,22c0.0001831,0,0.0003662,0,0.0006104,0c0.2759399-0.0001831,0.4995728-0.223999,0.4993896-0.5v-2.4970703h2c1.9320068-0.0023193,3.4976196-1.5679321,3.5-3.499939v-0.5009766c-0.0025024-1.9315796-1.5674438-3.4969482-3.4990234-3.500061H12c-0.0001221,0-0.0001831,0.000061-0.0003052,0.000061l-2.5006714-0.0010376C8.1194458,11.4985962,7.0018311,10.3805542,7,9.0009766V8.5C7.0012817,7.119812,8.119812,6.0012817,9.5,6H12l2.5009766,0.0009766c1.3798828,0.001709,2.4978638,1.1201782,2.4990234,2.5c0,0.0001831,0,0.0004272,0,0.0006104C17.0001831,8.7775269,17.223999,9.0011597,17.5,9.0009766z" />
                                                    </svg>
                                                </div>
                                                <div class="card-item-title  mb-2">
                                                    <label class="main-content-label tx-13 mb-1">Total Purchase</label>
                                                </div>
                                                <div class="card-item-body">
                                                    <div class="card-item-stat">
                                                        <h4 class="font-weight-normal">
                                                            $<?php echo $totalinvestamount; ?></h4>
                                                        <small><b class="badge rounded-pill bg-success fs-11"><i
                                                                    class="fe fe-arrow-up"></i></b> <span
                                                                class="px-1"></span></small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="card custom-card">
                                        <div class="card-body">
                                            <div class="card-item">
                                                <div class="card-item-icon bg-info-transparent">
                                                    <svg class="text-primary wd-20 ht-20" fill="#01b8ff"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        enable-background="new 0 0 24 24" viewBox="0 0 24 24">
                                                        <path
                                                            d="M14.6650391,13.3672485C16.6381226,12.3842773,17.9974365,10.3535767,18,8c0-3.3137207-2.6862793-6-6-6S6,4.6862793,6,8c0,2.3545532,1.3595581,4.3865967,3.3334961,5.3690186c-3.6583862,1.0119019-6.5859375,4.0562134-7.2387695,8.0479736c-0.0002441,0.0013428-0.0004272,0.0026855-0.0006714,0.0040283c-0.0447388,0.272583,0.1399536,0.5297852,0.4125366,0.5745239c0.272522,0.0446777,0.5297241-0.1400146,0.5744629-0.4125366c0.624939-3.8344727,3.6308594-6.8403931,7.465332-7.465332c4.9257812-0.8027954,9.5697632,2.5395508,10.3725586,7.465332C20.9594727,21.8233643,21.1673584,21.9995117,21.4111328,22c0.0281372,0.0001831,0.0562134-0.0021362,0.0839844-0.0068359h0.0001831c0.2723389-0.0458984,0.4558716-0.303833,0.4099731-0.5761719C21.2677002,17.5184937,18.411377,14.3986206,14.6650391,13.3672485z M12,13c-2.7614136,0-5-2.2385864-5-5s2.2385864-5,5-5c2.7600708,0.0032349,4.9967651,2.2399292,5,5C17,10.7614136,14.7614136,13,12,13z" />
                                                    </svg>
                                                </div>
                                                <div class="card-item-title mb-2">
                                                    <label class="main-content-label tx-13 mb-1">Total Amount
                                                        Withdrawn</label>
                                                </div>

                                                <div class="card-item-body">
                                                    <div class="card-item-stat">
                                                        <h4 class="font-weight-normal">
                                                            $<?php echo $totalwithamount; ?></h4>
                                                        <small><b class="badge rounded-pill bg-info fs-11"><i
                                                                    class="fe fe-arrow-up"></i></b> <span
                                                                class="px-1"></b><span class="px-1"></span></small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>







                                <div class="col-sm-12 col-md-12 col-lg-8 col-xl-9 col-xxl-10">



                                <div class="row row-sm">
                                        <div class="col-sm-6 col-lg-6 col-xl-6">
                                            <div class="card custom-card">
                                                <div class="card-body text-center">
                                                    <div
                                                        class="icon-margin bg-secondary-transparent rounded-circle text-secondary">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            enable-background="new 0 0 24 24" viewBox="0 0 24 24">
                                                            <path fill="#f1388b"
                                                                d="M19.5,7H18V6c-0.0018311-1.6561279-1.3438721-2.9981689-3-3H4.5C3.119812,3.0012817,2.0012817,4.119812,2,5.5V18c0.0018311,1.6561279,1.3438721,2.9981689,3,3h14.5c1.380188-0.0012817,2.4987183-1.119812,2.5-2.5v-9C21.9987183,8.119812,20.880188,7.0012817,19.5,7z M4.5,4H15c1.1040039,0.0014038,1.9985962,0.8959961,2,2v1H4.5C3.6715698,7,3,6.3284302,3,5.5S3.6715698,4,4.5,4z M21,16h-2c-1.1045532,0-2-0.8954468-2-2s0.8954468-2,2-2h2V16z M21,11h-2c-1.6568604,0-3,1.3431396-3,3s1.3431396,3,3,3h2v1.5c-0.0009155,0.828064-0.671936,1.4990845-1.5,1.5H5c-1.1040039-0.0014038-1.9985962-0.8959961-2-2V7.4990234C3.4321899,7.8247681,3.9588013,8.0006714,4.5,8h15c0.828064,0.0009155,1.4990845,0.671936,1.5,1.5V11z" />
                                                        </svg>
                                                    </div>
                                                    <h6 class="mb-0">All Portfolio</h6>

                                                    <select class="form-control btn-outline-secondary mt-3"
                                                        name="debit_wallet" id="wallet" required>
                                                        <?php  $getsys2 = mysqli_query($mysqli,"SELECT * FROM investment_packages ");
                                            
                                            $i=0;
                                            $totalsysdaily =0;
                                            while($inn=mysqli_fetch_assoc($getsys2)){
                                                $i++;
                                               
                                            ?>

                                                        <option value="<?php echo $inn['name']; ?>">
                                                            <?php echo $inn['name']; ?> --
                                                          
                                                            ($<?php echo $inn['min_amount']; ?>)
                                                        </option>

                                                        <?php } ?>
                                                    </select>

                                                    <h2 class="mb-1 mt-3 tx-normal">$<span
                                                            class="counter"><?php echo number_format($totalinvestpack); ?></span></h2>
                                                    <p class="text-muted"><span class="mb-0 text-danger fs-13 ms-1"><i
                                                                class="fe fe-arrow-down"></i> </span> <span
                                                            class="fs-12"></span></p>

                                                </div>
                                            </div>
                                        </div>



                                        <div class="col-sm-6 col-lg-6 col-xl-6">
                                            <div class="card custom-card">
                                                <div class="card-body text-center">
                                                    <div
                                                        class="icon-margin bg-success-transparent rounded-circle text-success">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            enable-background="new 0 0 24 24" viewBox="0 0 24 24">
                                                            <path fill="#19b159"
                                                                d="M21.5,6h-6C15.223877,6,15,6.223877,15,6.5S15.223877,7,15.5,7h4.7929688l-7.2930298,7.2930298l-3.6464844-3.6464844c-0.1895142-0.1831055-0.4863281-0.1843262-0.680542-0.0091553c-0.0080566,0.0045776-0.0195312,0.0024414-0.0264282,0.0090942l-6.5,6.5c-0.09375,0.09375-0.1464233,0.2208862-0.1464233,0.3534546C2,17.776062,2.223877,17.999939,2.5,18c0.1326294,0.0001221,0.2598267-0.0526123,0.3534546-0.1465454l6.1464844-6.1464844l3.6465454,3.6465454C12.7369385,15.4440308,12.8619385,15.499939,13,15.5c0.1326294,0.0001221,0.2598267-0.0526123,0.3534546-0.1465454L21,7.7069092v4.7936401C21.0001831,12.7765503,21.223999,13.0001831,21.5,13h0.0006104C21.7765503,12.9998169,22.0001831,12.776001,22,12.5V6.4993896C21.9998169,6.2234497,21.776001,5.9998169,21.5,6z" />
                                                        </svg>
                                                    </div>
                                                    <h6 class="mb-0">Total Licenses</h6>


                                                   
                                                    <select class="form-control btn-outline-secondary mt-3"
                                                        name="debit_wallet" id="wallet" required>
                                                       
                                            
                                           

                                                        <option value=""></option>

                                                    </select>

                                                    <? 
                                                    //get the list of user with active licenses and sum them up
                                                    $getsys2 = mysqli_query($mysqli,"SELECT * FROM users where membership_status='active' ");
                                                    $totalLicense = mysqli_num_rows($getsys2);

                                                    ?>


                                                    <h2 class="mb-1 mt-2 tx-normal"><span
                                                            class="counter"><?php echo number_format($totalLicense); ?></span></h2>
                                                    <p class="text-muted"><span class="mb-0 text-success fs-13 ms-1"><i
                                                                class="fe fe-arrow-up"></i> </span> <span
                                                            class="fs-12"></span></p>

                                                </div>
                                            </div>
                                        </div>




                                     
















                                    </div>
                                











                                </div>










                                <div class="col-sm-12 col-lg-12 col-xl-12 col-xxl-3">
                                    <div class="row row-sm">
                                        <div class="col-sm-12 col-lg-12 col-xl-12">
                                            <div class="card custom-card overflow-hidden">
                                                <div class="card-body p-0">
                                                    <div class="row row-sm">
                                                        <div class="col-sm-4 col-md-4 border-end">
                                                            <div class="p-4">
                                                                <p class="revenuechart-container mb-3">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                        width="30px" height="30px" viewBox="0 0 30 30"
                                                                        version="1.1">
                                                                        <g id="surface1">
                                                                            <path
                                                                                style=" stroke:none;fill-rule:nonzero;fill:rgb(221 221 222);fill-opacity:1;"
                                                                                d="M 6.25 27.5 C 5.558594 27.5 5 26.941406 5 26.25 L 5 16.25 C 5 15.558594 5.558594 15 6.25 15 C 6.941406 15 7.5 15.558594 7.5 16.25 L 7.5 26.25 C 7.5 26.941406 6.941406 27.5 6.25 27.5 Z M 12.5 27.5 C 11.808594 27.5 11.25 26.941406 11.25 26.25 L 11.25 3.75 C 11.25 3.058594 11.808594 2.5 12.5 2.5 C 13.191406 2.5 13.75 3.058594 13.75 3.75 L 13.75 26.25 C 13.75 26.941406 13.191406 27.5 12.5 27.5 Z M 18.75 27.5 C 18.058594 27.5 17.5 26.941406 17.5 26.25 L 17.5 11.25 C 17.5 10.558594 18.058594 10 18.75 10 C 19.441406 10 20 10.558594 20 11.25 L 20 26.25 C 20 26.941406 19.441406 27.5 18.75 27.5 Z M 25 27.5 C 24.308594 27.5 23.75 26.941406 23.75 26.25 L 23.75 21.25 C 23.75 20.558594 24.308594 20 25 20 C 25.691406 20 26.25 20.558594 26.25 21.25 L 26.25 26.25 C 26.25 26.941406 25.691406 27.5 25 27.5 Z M 25 27.5 " />
                                                                        </g>
                                                                    </svg>
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                        class="chart2" width="30px" height="30px"
                                                                        viewBox="0 0 30 30" version="1.1">
                                                                        <g id="surface2">
                                                                            <path
                                                                                style=" stroke:none;fill-rule:nonzero;fill:#fd6074;fill-opacity:0.7;"
                                                                                d="M 6.25 27.5 C 5.558594 27.5 5 26.941406 5 26.25 L 5 16.25 C 5 15.558594 5.558594 15 6.25 15 C 6.941406 15 7.5 15.558594 7.5 16.25 L 7.5 26.25 C 7.5 26.941406 6.941406 27.5 6.25 27.5 Z M 12.5 27.5 C 11.808594 27.5 11.25 26.941406 11.25 26.25 L 11.25 3.75 C 11.25 3.058594 11.808594 2.5 12.5 2.5 C 13.191406 2.5 13.75 3.058594 13.75 3.75 L 13.75 26.25 C 13.75 26.941406 13.191406 27.5 12.5 27.5 Z M 18.75 27.5 C 18.058594 27.5 17.5 26.941406 17.5 26.25 L 17.5 11.25 C 17.5 10.558594 18.058594 10 18.75 10 C 19.441406 10 20 10.558594 20 11.25 L 20 26.25 C 20 26.941406 19.441406 27.5 18.75 27.5 Z M 25 27.5 C 24.308594 27.5 23.75 26.941406 23.75 26.25 L 23.75 21.25 C 23.75 20.558594 24.308594 20 25 20 C 25.691406 20 26.25 20.558594 26.25 21.25 L 26.25 26.25 C 26.25 26.941406 25.691406 27.5 25 27.5 Z M 25 27.5 " />
                                                                        </g>
                                                                    </svg>
                                                                    <span class="mb-0 fs-13 ms-2 result text-danger"><i
                                                                            class="fe fe-arrow-down"></i> </span>
                                                                </p>
                                                                <?php 
																	$get_fundss = mysqli_query($mysqli,"SELECT * FROM `withdrawal_method` "); 
																	
                                                                ?>
                                                                <h2 class="tx-normal"><span
                                                                        class="counter"><?php echo mysqli_num_rows($get_fundss); ?></span>
                                                                </h2>
                                                                <p class="tx-12 text-muted">Withdrawal Method</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-4 col-md-4 border-end">
                                                            <div class="p-4">
                                                                <p class="revenuechart-container mb-3">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                        width="30px" height="30px" viewBox="0 0 30 30"
                                                                        version="1.1">
                                                                        <g id="surface3">
                                                                            <path
                                                                                style=" stroke:none;fill-rule:nonzero;fill:rgb(221 221 222);fill-opacity:1;"
                                                                                d="M 6.25 27.5 C 5.558594 27.5 5 26.941406 5 26.25 L 5 16.25 C 5 15.558594 5.558594 15 6.25 15 C 6.941406 15 7.5 15.558594 7.5 16.25 L 7.5 26.25 C 7.5 26.941406 6.941406 27.5 6.25 27.5 Z M 12.5 27.5 C 11.808594 27.5 11.25 26.941406 11.25 26.25 L 11.25 3.75 C 11.25 3.058594 11.808594 2.5 12.5 2.5 C 13.191406 2.5 13.75 3.058594 13.75 3.75 L 13.75 26.25 C 13.75 26.941406 13.191406 27.5 12.5 27.5 Z M 18.75 27.5 C 18.058594 27.5 17.5 26.941406 17.5 26.25 L 17.5 11.25 C 17.5 10.558594 18.058594 10 18.75 10 C 19.441406 10 20 10.558594 20 11.25 L 20 26.25 C 20 26.941406 19.441406 27.5 18.75 27.5 Z M 25 27.5 C 24.308594 27.5 23.75 26.941406 23.75 26.25 L 23.75 21.25 C 23.75 20.558594 24.308594 20 25 20 C 25.691406 20 26.25 20.558594 26.25 21.25 L 26.25 26.25 C 26.25 26.941406 25.691406 27.5 25 27.5 Z M 25 27.5 " />
                                                                        </g>
                                                                    </svg>
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                        class="chart2" width="30px" height="30px"
                                                                        viewBox="0 0 30 30" version="1.1">
                                                                        <g id="surface4">
                                                                            <path
                                                                                style=" stroke:none;fill-rule:nonzero;fill:#19b159;fill-opacity:0.7;"
                                                                                d="M 6.25 27.5 C 5.558594 27.5 5 26.941406 5 26.25 L 5 16.25 C 5 15.558594 5.558594 15 6.25 15 C 6.941406 15 7.5 15.558594 7.5 16.25 L 7.5 26.25 C 7.5 26.941406 6.941406 27.5 6.25 27.5 Z M 12.5 27.5 C 11.808594 27.5 11.25 26.941406 11.25 26.25 L 11.25 3.75 C 11.25 3.058594 11.808594 2.5 12.5 2.5 C 13.191406 2.5 13.75 3.058594 13.75 3.75 L 13.75 26.25 C 13.75 26.941406 13.191406 27.5 12.5 27.5 Z M 18.75 27.5 C 18.058594 27.5 17.5 26.941406 17.5 26.25 L 17.5 11.25 C 17.5 10.558594 18.058594 10 18.75 10 C 19.441406 10 20 10.558594 20 11.25 L 20 26.25 C 20 26.941406 19.441406 27.5 18.75 27.5 Z M 25 27.5 C 24.308594 27.5 23.75 26.941406 23.75 26.25 L 23.75 21.25 C 23.75 20.558594 24.308594 20 25 20 C 25.691406 20 26.25 20.558594 26.25 21.25 L 26.25 26.25 C 26.25 26.941406 25.691406 27.5 25 27.5 Z M 25 27.5 " />
                                                                        </g>
                                                                    </svg>
                                                                    <span class="mb-0 fs-13 ms-2 result text-success"><i
                                                                            class="fe fe-arrow-up"></i> </span>
                                                                </p>
                                                                <?php
																	$get_investment_packeages = mysqli_query($mysqli,"SELECT * FROM investment_packages");
																	
                                                				?>
                                                                <h2 class="tx-normal"><span
                                                                        class="counter"><?php echo mysqli_num_rows($get_investment_packeages); ?></span>
                                                                </h2>
                                                                <p class="tx-12 text-muted">Portfolios</p>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-4 col-md-4">
                                                            <div class="p-4">
                                                                <p class="revenuechart-container mb-3">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                        width="30px" height="30px" viewBox="0 0 30 30"
                                                                        version="1.1">
                                                                        <g id="surface3">
                                                                            <path
                                                                                style=" stroke:none;fill-rule:nonzero;fill:rgb(221 221 222);fill-opacity:1;"
                                                                                d="M 6.25 27.5 C 5.558594 27.5 5 26.941406 5 26.25 L 5 16.25 C 5 15.558594 5.558594 15 6.25 15 C 6.941406 15 7.5 15.558594 7.5 16.25 L 7.5 26.25 C 7.5 26.941406 6.941406 27.5 6.25 27.5 Z M 12.5 27.5 C 11.808594 27.5 11.25 26.941406 11.25 26.25 L 11.25 3.75 C 11.25 3.058594 11.808594 2.5 12.5 2.5 C 13.191406 2.5 13.75 3.058594 13.75 3.75 L 13.75 26.25 C 13.75 26.941406 13.191406 27.5 12.5 27.5 Z M 18.75 27.5 C 18.058594 27.5 17.5 26.941406 17.5 26.25 L 17.5 11.25 C 17.5 10.558594 18.058594 10 18.75 10 C 19.441406 10 20 10.558594 20 11.25 L 20 26.25 C 20 26.941406 19.441406 27.5 18.75 27.5 Z M 25 27.5 C 24.308594 27.5 23.75 26.941406 23.75 26.25 L 23.75 21.25 C 23.75 20.558594 24.308594 20 25 20 C 25.691406 20 26.25 20.558594 26.25 21.25 L 26.25 26.25 C 26.25 26.941406 25.691406 27.5 25 27.5 Z M 25 27.5 " />
                                                                        </g>
                                                                    </svg>
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                        class="chart2" width="30px" height="30px"
                                                                        viewBox="0 0 30 30" version="1.1">
                                                                        <g id="surface4">
                                                                            <path
                                                                                style=" stroke:none;fill-rule:nonzero;fill:#fd6074;fill-opacity:0.7;"
                                                                                d="M 6.25 27.5 C 5.558594 27.5 5 26.941406 5 26.25 L 5 16.25 C 5 15.558594 5.558594 15 6.25 15 C 6.941406 15 7.5 15.558594 7.5 16.25 L 7.5 26.25 C 7.5 26.941406 6.941406 27.5 6.25 27.5 Z M 12.5 27.5 C 11.808594 27.5 11.25 26.941406 11.25 26.25 L 11.25 3.75 C 11.25 3.058594 11.808594 2.5 12.5 2.5 C 13.191406 2.5 13.75 3.058594 13.75 3.75 L 13.75 26.25 C 13.75 26.941406 13.191406 27.5 12.5 27.5 Z M 18.75 27.5 C 18.058594 27.5 17.5 26.941406 17.5 26.25 L 17.5 11.25 C 17.5 10.558594 18.058594 10 18.75 10 C 19.441406 10 20 10.558594 20 11.25 L 20 26.25 C 20 26.941406 19.441406 27.5 18.75 27.5 Z M 25 27.5 C 24.308594 27.5 23.75 26.941406 23.75 26.25 L 23.75 21.25 C 23.75 20.558594 24.308594 20 25 20 C 25.691406 20 26.25 20.558594 26.25 21.25 L 26.25 26.25 C 26.25 26.941406 25.691406 27.5 25 27.5 Z M 25 27.5 " />
                                                                        </g>
                                                                    </svg>
                                                                    <span class="mb-0 fs-13 ms-2 result text-danger"><i
                                                                            class="fe fe-arrow-down"></i> </span>
                                                                </p>
                                                                <?php $get_funds = mysqli_query($mysqli,"SELECT id FROM withdrawal WHERE status=1 "); ?>
                                                                <h2 class="tx-normal"><span
                                                                        class="counter"><?php echo mysqli_num_rows($get_funds); ?></span>
                                                                </h2>
                                                                <p class="tx-12 text-muted">Withdrawals </p>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>







                                
                                  







                                 </div>
                            </div>
                            <!--End row-->

                        </div>
                    </div>
                    <!-- End Row -->








                </div>
                <!-- End Row -->









            </div>
        </div>
    </div>
    <!-- End Main Content-->

    <!-- Main Footer-->
    <div class="main-footer text-center">
        <div class="container">
            <div class="row row-sm">
                <div class="col-md-12">
                    <span>Copyright © <?php echo date('Y'); ?>
                        All rights reserved.</span>
                </div>
            </div>
        </div>
    </div>
    <!--End Footer-->








    <!-- Country-selector modal-->
    <div class="modal fade" id="country-selector">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h6 class="modal-title">Choose Country</h6><button aria-label="Close" class="btn-close"
                        data-bs-dismiss="modal" type="button"></button>
                </div>
                <div class="modal-body">
                    <ul class="row p-3">
                        <li class="col-lg-6 mb-2">
                            <a href="#" class="btn btn-country btn-lg btn-block active">
                                <span class="country-selector"><img alt="" src="assets/img/flags/us_flag.jpg"
                                        class="me-3 language"></span>Usa
                            </a>
                        </li>
                        <li class="col-lg-6 mb-2">
                            <a href="#" class="btn btn-country btn-lg btn-block">
                                <span class="country-selector"><img alt="" src="assets/img/flags/italy_flag.jpg"
                                        class="me-3 language"></span>Italy
                            </a>
                        </li>
                        <li class="col-lg-6 mb-2">
                            <a href="#" class="btn btn-country btn-lg btn-block">
                                <span class="country-selector"><img alt="" src="assets/img/flags/spain_flag.jpg"
                                        class="me-3 language"></span>Spain
                            </a>
                        </li>
                        <li class="col-lg-6 mb-2">
                            <a href="#" class="btn btn-country btn-lg btn-block">
                                <span class="country-selector"><img alt="" src="assets/img/flags/india_flag.jpg"
                                        class="me-3 language"></span>India
                            </a>
                        </li>
                        <li class="col-lg-6 mb-2">
                            <a href="#" class="btn btn-country btn-lg btn-block">
                                <span class="country-selector"><img alt="" src="assets/img/flags/french_flag.jpg"
                                        class="me-3 language"></span>France
                            </a>
                        </li>
                        <li class="col-lg-6 mb-2">
                            <a href="#" class="btn btn-country btn-lg btn-block">
                                <span class="country-selector"><img alt="" src="assets/img/flags/mexico_flag.jpg"
                                        class="me-3 language"></span>Mexico
                            </a>
                        </li>
                        <li class="col-lg-6 mb-2">
                            <a href="#" class="btn btn-country btn-lg btn-block">
                                <span class="country-selector"><img alt="" src="assets/img/flags/poland_flag.jpg"
                                        class="me-3 language"></span>Poland
                            </a>
                        </li>
                        <li class="col-lg-6 mb-2">
                            <a href="#" class="btn btn-country btn-lg btn-block">
                                <span class="country-selector"><img alt="" src="assets/img/flags/austria_flag.jpg"
                                        class="me-3 language"></span>Austria
                            </a>
                        </li>
                        <li class="col-lg-6 mb-2">
                            <a href="#" class="btn btn-country btn-lg btn-block">
                                <span class="country-selector"><img alt="" src="assets/img/flags/russia_flag.jpg"
                                        class="me-3 language"></span>Russia
                            </a>
                        </li>
                        <li class="col-lg-6 mb-2">
                            <a href="#" class="btn btn-country btn-lg btn-block">
                                <span class="country-selector"><img alt="" src="assets/img/flags/germany_flag.jpg"
                                        class="me-3 language"></span>Germany
                            </a>
                        </li>
                        <li class="col-lg-6 mb-2">
                            <a href="#" class="btn btn-country btn-lg btn-block">
                                <span class="country-selector"><img alt="" src="assets/img/flags/argentina_flag.jpg"
                                        class="me-3 language"></span>Argentina
                            </a>
                        </li>
                        <li class="col-lg-6 mb-2">
                            <a href="#" class="btn btn-country btn-lg btn-block">
                                <span class="country-selector"><img alt="" src="assets/img/flags/uae_flag.jpg"
                                        class="me-3 language"></span>U.A.E
                            </a>
                        </li>
                        <li class="col-lg-6 mb-2">
                            <a href="#" class="btn btn-country btn-lg btn-block">
                                <span class="country-selector"><img alt="" src="assets/img/flags/malaysia_flag.jpg"
                                        class="me-3 language"></span>Malaysia
                            </a>
                        </li>
                        <li class="col-lg-6 mb-2">
                            <a href="#" class="btn btn-country btn-lg btn-block">
                                <span class="country-selector"><img alt="" src="assets/img/flags/canada_flag.jpg"
                                        class="me-3 language"></span>Canada
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Country-selector modal-->



    </div>
    <!-- End Page -->

    <!-- Back-to-top -->
    <a href="#top" id="back-to-top"><i class="fe fe-arrow-up"></i></a>

    <!-- Jquery js-->
    <script src="assets/plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap js-->
    <script src="assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

    <!-- Select2 js-->
    <script src="assets/plugins/select2/js/select2.min.js"></script>

    <!-- INTERNAL Data tables js-->
    <script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>
    <script src="assets/plugins/datatable/dataTables.responsive.min.js"></script>

    <!-- Perfect-scrollbar js -->
    <script src="assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/plugins/perfect-scrollbar/pscroll1.js"></script>


    <!-- Internal Light Gallery js-->
    <script src="assets/plugins/gallery/picturefill.js"></script>
    <script src="assets/plugins/gallery/lightgallery.js"></script>
    <script src="assets/plugins/gallery/lightgallery-1.js"></script>
    <script src="assets/plugins/gallery/lg-pager.js"></script>
    <script src="assets/plugins/gallery/lg-autoplay.js"></script>
    <script src="assets/plugins/gallery/lg-fullscreen.js"></script>
    <script src="assets/plugins/gallery/lg-zoom.js"></script>
    <script src="assets/plugins/gallery/lg-hash.js"></script>
    <script src="assets/plugins/gallery/lg-share.js"></script>
    <!-- Apex charts js -->
    <script src="assets/plugins/apexcharts/apexcharts.js"></script>

    <!-- Sidemenu js -->
    <script src="assets/plugins/sidemenu/sidemenu.js"></script>

    <!-- Sidebar js -->
    <script src="assets/plugins/sidebar/sidebar.js"></script>

    <!-- Sticky js -->
    <script src="assets/js/sticky.js"></script>

    <!-- Internal Dashboard js-->
    <script src="assets/js/index.js"></script>

    <!-- CHART-CIRCLE JS-->
    <script src="assets/js/circle-progress.min.js"></script>

    <!-- Color Theme js -->
    <script src="assets/js/themeColors.js"></script>

    <!-- swither styles js -->
    <script src="assets/js/swither-styles.js"></script>

    <script src="swal/sweetalert2.min.js"></script>

    <!-- Custom js -->
    <script src="assets/js/custom.js"></script>








</body>

</html>