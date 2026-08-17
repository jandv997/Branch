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


$curl = curl_init();

curl_setopt_array($curl, array(
CURLOPT_URL => 'https://plisio.net/api/v1/currencies?api_key=sEhbpaXTi3YZNt5exXFgrBb5NXCdYD6MhR-T0lywD1I7brQn8wU3fNBPWfOYNCOA',
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => '',
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 0,
CURLOPT_FOLLOWLOCATION => true,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => 'GET',
));

$response = curl_exec($curl);

curl_close($curl);

$response= json_decode($response);

$data =$response->data;








?>
<!DOCTYPE html>
<html lang="en">
	<head>

		<meta charset="UTF-8">
		<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
	

		<!-- Title -->
		<title>Quantum Verse | Quantum Scalp </title>

		<!-- Favicon -->
		<link rel="icon" href="assets/img/brand/favicon.png" type="image/x-icon"/>

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

		<link rel="stylesheet" href="swal/sweetalert2.min.css">

		<!--- Animations css-->
		<link href="assets/css/animate.css" rel="stylesheet">
		<link href="assets/css/qs-verse.css" rel="stylesheet">
<style>
    /* empty / loading state */
		.empty-state {
			text-align: center;
			padding: 3rem 1rem;
			background: rgba(12, 20, 28, 0.6);
			border-radius: 2rem;
			color: #9ca3af;
			backdrop-filter: blur(4px);
		}

		.empty-state i {
			font-size: 3rem;
			margin-bottom: 1rem;
			color: #4ade80;
			opacity: 0.6;
		}


/* Portfolio Card Styles */
.portfolio-card {
    background: linear-gradient(145deg, #1a1a2e, #16213e);
    border-radius: 16px;
    padding: 0;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.05);
    overflow: hidden;
}

.portfolio-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
    border-color: rgba(255, 255, 255, 0.1);
}

/* Card Header */
.portfolio-header {
    padding: 18px 20px;
    background: rgba(255, 255, 255, 0.03);
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.portfolio-icon {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    background: linear-gradient(135deg, #2dcb8a, #1a9b6a);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    color: #fff;
    font-size: 18px;
}

.portfolio-name {
    color: #fff;
    font-weight: 600;
    font-size: 1rem;
}

.status-badge {
    padding: 4px 14px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
}

.status-active {
    background: rgba(45, 203, 138, 0.15);
    color: #2dcb8a;
}

.status-inactive {
    background: rgba(255, 59, 48, 0.15);
    color: #ff3b30;
}

/* Card Body */
.portfolio-body {
    padding: 20px;
}

/* Amount Section */
.amount-section {
    text-align: center;
    margin-bottom: 20px;
}

.amount-label {
    font-size: 0.8rem;
    color: rgba(255, 255, 255, 0.5);
    text-transform: uppercase;
    letter-spacing: 1px;
}

.amount-value {
    font-size: 2rem;
    font-weight: 700;
    color: #fff;
    margin: 4px 0 8px;
}

.portfolio-progress {
    height: 4px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 4px;
    overflow: hidden;
}

.portfolio-progress .progress-bar {
    background: linear-gradient(90deg, #2dcb8a, #1a9b6a);
    border-radius: 4px;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-bottom: 20px;
}

.stat-item {
    background: rgba(255, 255, 255, 0.03);
    border-radius: 10px;
    padding: 12px;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s ease;
}

.stat-item:hover {
    background: rgba(255, 255, 255, 0.06);
}

.stat-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.05);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.stat-info {
    flex: 1;
}

.stat-label {
    font-size: 0.7rem;
    color: rgba(255, 255, 255, 0.4);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: block;
}

.stat-value {
    font-size: 0.95rem;
    font-weight: 600;
    color: #fff;
    display: block;
}

/* Timeline Section */
.timeline-section {
    background: rgba(255, 255, 255, 0.03);
    border-radius: 10px;
    padding: 12px 14px;
    margin-top: 4px;
}

.timeline-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 6px;
}

.timeline-label {
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.5);
}

.timeline-percentage {
    font-size: 0.75rem;
    font-weight: 600;
    color: #2dcb8a;
}

.timeline-dates {
    display: flex;
    justify-content: space-between;
    margin-top: 6px;
    font-size: 0.7rem;
    color: rgba(255, 255, 255, 0.3);
}

/* Card Footer */
.portfolio-footer {
    padding: 16px 20px;
    background: rgba(255, 255, 255, 0.02);
    border-top: 1px solid rgba(255, 255, 255, 0.05);
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.btn-action {
    flex: 1;
    padding: 8px 16px;
    border: none;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    transition: all 0.3s ease;
    cursor: pointer;
    min-width: 100px;
}

.btn-action:hover {
    transform: translateY(-2px);
}

.btn-topup {
    background: linear-gradient(135deg, #2dcb8a, #1a9b6a);
    color: #fff;
}

.btn-topup:hover {
    box-shadow: 0 4px 15px rgba(45, 203, 138, 0.3);
}

.btn-restart {
    background: linear-gradient(135deg, #ffc107, #f59e0b);
    color: #1a1a2e;
}

.btn-restart:hover {
    box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
}

.btn-withdraw {
    background: linear-gradient(135deg, #ff3b30, #dc2626);
    color: #fff;
}

.btn-withdraw:hover {
    box-shadow: 0 4px 15px rgba(255, 59, 48, 0.3);
}

/* Responsive */
@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr 1fr;
    }
    
    .amount-value {
        font-size: 1.5rem;
    }
    
    .btn-action {
        font-size: 0.7rem;
        padding: 6px 12px;
        min-width: 70px;
    }
}

@media (max-width: 576px) {
    .portfolio-footer {
        flex-direction: column;
    }
    
    .btn-action {
        width: 100%;
    }
}
</style>


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

					<div class="qs-verse">
					<?php
					$verseTab = 'active';
					include('inc/verse-tabs.php');
					?>

						<!-- Row -->
						<div class="qs-verse-grid">


						<?php

                                                                                                                    
							$getinvest = mysqli_query($mysqli, "SELECT * FROM investment WHERE userid='".$rows['id']."'");
							$i=0;


							while($row = mysqli_fetch_assoc($getinvest)){
							$i++;



							$getp = mysqli_query($mysqli,"SELECT * FROM investment_packages where id ='".$row['investmentid']."'");
							$invest = mysqli_fetch_assoc($getp);



						?>



							


<div class="qs-verse-slot">
<article class="qs-verse-card">
        <div class="qs-verse-owned__top">
            <div>
                <div class="qs-verse-card__icon"><?php echo qs_verse_planet(); ?></div>
                <h3 class="qs-verse-card__name"><?php echo htmlspecialchars($row['name']); ?></h3>
                <div class="qs-verse-owned__date"><?php echo htmlspecialchars($row['date']); ?></div>
            </div>
        </div>
        <div class="qs-verse-owned__amount">$<?php echo number_format($row['amount'], 2); ?></div>
        <div class="qs-verse-owned__stats">
            <div class="qs-verse-owned__stat">
                <small>Expected Daily ROI</small>
                <b>$<?php echo number_format($row['daily_roi'], 2); ?></b>
            </div>
            <div class="qs-verse-owned__stat">
                <small>Duration</small>
                <b><?php echo htmlspecialchars($row['duration']); ?> Days</b>
            </div>
            <div class="qs-verse-owned__stat">
                <small>Total Return</small>
                <b>$<?php echo number_format($row['added_roi'], 2); ?></b>
            </div>
            <div class="qs-verse-owned__stat">
                <small>Status</small>
                <b class="<?php echo $row['status'] == 1 ? 'qs-verse-status-on' : 'qs-verse-status-off'; ?>">
                    <?php echo $row['status'] == 1 ? 'Active' : 'Inactive'; ?>
                </b>
            </div>
        </div>
        <div class="qs-verse-owned__timeline">
            <div class="qs-verse-owned__timeline-row">
                <span>Progress Timeline</span>
                <span><?php echo $row['status'] == 1 ? '100%' : '0%'; ?></span>
            </div>
            <div class="qs-verse-owned__bar">
                <span style="width: <?php echo $row['status'] == 1 ? '100%' : '0%'; ?>"></span>
            </div>
            <div class="qs-verse-owned__dates">
                <span>Started: <?php echo date('M d, Y', strtotime($row['created_at'])); ?></span>
                <span>Ends: <?php echo date('M d, Y', strtotime($row['created_at'] . ' + ' . $row['duration'] . ' days')); ?></span>
            </div>
        </div>
        <div class="qs-verse-actions">
            <?php if($row['status'] == 1): ?>
                <button data-bs-target="#invest<?php echo $row['id']; ?>"
                        data-bs-toggle="modal"
                        class="btn-action btn-topup">
                    <i class="fe fe-plus-circle"></i> Top Up
                </button>
            <?php endif; ?>

            <?php if($row['status'] == 0): ?>
                <button data-bs-target="#restart<?php echo $row['id']; ?>"
                        data-bs-toggle="modal"
                        class="btn-action btn-restart">
                    <i class="fe fe-refresh-cw"></i> Re-Initiate
                </button>
                <button data-bs-target="#withdraw<?php echo $row['id']; ?>"
                        data-bs-toggle="modal"
                        class="btn-action btn-withdraw">
                    <i class="fe fe-arrow-down"></i> Withdraw
                </button>
            <?php endif; ?>
        </div>
</article>












    <div class="modal fade" id="invest<?php echo $row['id'] ?>" tabindex="-1" role="dialog"
        aria-labelledby="with<?php echo $row['id'].$i; ?>" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="with<?php echo $row['id'].$i; ?>">Top into
                        <?php echo $row['name']; ?></h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">



                    <form method="POST" id="re-invest-<?php echo $row['id']; ?>">


                        <input type="hidden" value="<?php echo $row['id']; ?>" name="id"
                            id="id<?php echo $row['id']; ?>" />
                        <input type="hidden" value="<?php echo $row['added_roi']; ?>" name="added_roi"
                            id="id<?php echo $row['id']; ?>" />
                        <input type="hidden" value="<?php echo $row['amount']; ?>" name="old_amount"
                            id="old-amount<?php echo $row['id']; ?>" />

                        <input type="hidden" value="<?php echo $invest['compound_percent']; ?>" name="compound_percent"
                            id="compound_percent<?php echo $row['id']; ?>" />

                        <input type="hidden" value="<?php echo $invest['percent']; ?>" name="percent"
                            id="percent-<?php echo $row['id']; ?>" />

                        <input type="hidden" name="payout" value="<?php echo $row['payout']; ?>" />



                        <div class="form-group mt-3" id="packname<?php echo $row['id']; ?>">

                            <label for="phone">Investment Portfolio</label>
                            <input value="<?php echo $row['name']; ?>" class="form-control" name="name" readonly
                                type="text">

                        </div>

                        <div class="form-group mt-3" id="past<?php echo $row['id']; ?>">
                            <label for="phone">Amount Invested</label>
                            <input name="amount" id="exampleInputName" placeholder="Enter amount" class="form-control"
                                value="<?php echo $row['amount']; ?>" readonly required type="number">

                        </div>

                        <div class="form-group mt-3" id="current<?php echo $row['id']; ?>">
                            <label for="phone">Added Amount</label>
                            <input name="new_amount" id="amount" min="50" max="<?php echo $invest['max_amount']; ?>" placeholder="Add more funds to this portfolio"
                                required type="number" class="form-control">

                        </div>



                        <div class="form-group mt-3 platform" id="platform<?php echo $row['id']; ?>">
                            <label>Select Payment Platform</label>
                            <select name="platform" class="form-control" id="platform">
                                <option value="1">Direct Deposit</option>
                                <option value="2">Main Wallet (<?php echo number_format($rows['wallet'], 2); ?>)</option>
                                <option value="3">Staking Wallet (<?php echo number_format($rows['compound_profit'], 2); ?>)</option>
                              


                            </select>

                        </div>



                        <div class="form-group mt-3 currency" id="cur<?php echo $row['id']; ?>">
                            <label>Select Currency</label>
                            <select name="currency" class="form-control" id="currency">

                            <?php 
                                                                

                                for($i=0; $i<count($data); $i++){
                                                                                        
                                    if(isset($_GET['currency']) and $_GET['currency']==$data[$i]->currency){ $pick = 'selected'; } 
                                                                    
                                        echo"<option ".$pick." value=".$data[$i]->currency.">".strtoupper($data[$i]->name)."</option>";
                                                         
                                                                
                                }
                                                                


                                                        ?>

                


                            </select>

                        </div>









                        <div class="row mt-3" id="btn<?php echo $row['id']; ?>">
                            <div class="input-field col s12">
                                <button type="submit" class="btn btn-primary waves-effect "
                                    name="update-info-update">Update</button>
                            </div>
                        </div>



                    </form>






                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>






    <div class="modal fade" id="withdraw<?php echo $row['id'] ?>" tabindex="-1" role="dialog"
        aria-labelledby="with<?php echo $row['id'].$i; ?>" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="with<?php echo $row['id'].$i; ?>">Withdraw Capital
                        <?php echo $row['name']; ?></h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">



                    <form method="POST" id="">


                        <input type="hidden" value="<?php echo $row['id']; ?>" name="id"
                            id="id<?php echo $row['id']; ?>" />
                       


                        <div class="row mt-3" id="btn<?php echo $row['id']; ?>">
                            <div class="input-field col s12">
                                <button type="submit" class="btn btn-primary waves-effect"
                                    name="withdraw-update">Withdraw</button>
                            </div>
                        </div>



                    </form>






                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="restart<?php echo $row['id'] ?>" tabindex="-1" role="dialog"
        aria-labelledby="with<?php echo $row['id'].$i; ?>" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="with<?php echo $row['id'].$i; ?>">Restart Capital
                        <?php echo $row['name']; ?></h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">



                    <form method="POST" id="">


                        <input type="hidden" value="<?php echo $row['id']; ?>" name="id"
                            id="id<?php echo $row['id']; ?>" />
                       


                        <div class="row mt-3" id="btn<?php echo $row['id']; ?>">
                            <div class="input-field col s12">
                                <button type="submit" class="btn btn-primary waves-effect"
                                    name="restart-update">Restart</button>
                            </div>
                        </div>



                    </form>






                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>

						</div>
						<?php } ?>



						</div>


<?php if(mysqli_num_rows($getinvest) == 0) { ?>
                        	<div class="qs-verse-empty">
								<i class="fas fa-satellite-dish"></i>
								<h5>No Active Purchase</h5>
								<p>Waiting for you to make purchase ...</p>
							</div>
						<?php } ?>


						<!-- End Row -->
					</div>
				</div>
				<!-- Container closed -->
			</div>
			<!-- main-content closed -->

			
			<!-- Footer opened -->
			<div class="main-footer">
				<div class="container-fluid pt-0 ht-100p">
					Copyright © <?php echo date('Y'); ?>  All rights reserved
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

		<!-- eva-icons js -->
		<script src="assets/js/eva-icons.min.js"></script>

		<!-- Theme Color js -->
		<script src="assets/js/themecolor.js"></script>


        <!--Internal  Notify js -->
		<script src="assets/plugins/notify/js/notifIt.js"></script>
		<script src="assets/plugins/notify/js/notifit-custom.js"></script>



		<script src="swal/sweetalert2.min.js"></script>

		<script src="assets/js/custom.js"></script>

	</body>





    <?php



if(isset($_POST['update-info-update'])){
    //start of update
    $id = mysqli_real_escape_string($mysqli,$_POST['id']);
    $name = mysqli_real_escape_string($mysqli,$_POST['name']);
    $old_amount = mysqli_real_escape_string($mysqli,$_POST['old_amount']);
    $new_amount = mysqli_real_escape_string($mysqli,$_POST['new_amount']);
    $payout = mysqli_real_escape_string($mysqli,$_POST['payout']);
    $added_roi = mysqli_real_escape_string($mysqli,$_POST['added_roi']);
    $real_amount = $old_amount + $new_amount;
    $userid = $rows['id'];
    $date= date("d")." ".date("F")." ".date("Y")." , ".date("h")." : ".date("i").date("a");
    $daily_roi =0;
    $current = $_POST['currency'];
    $platform =$_POST['platform'];
    $orderr = "QS-".uniqid();// "";



if(is_numeric($new_amount) and  is_numeric($old_amount) and $new_amount >= 10 and $old_amount >=10 ){
    
    
    if($payout == 1){
        $daily_roi = $real_amount*($_POST['percent'])/100;
    }else{
         $daily_roi =  $real_amount*($_POST['compound_percent'])/100;
    }
    
    

    
    
    //check if the users wallet balance is bigger than the amount to b invested
    
    if($platform == 1){
    


        if($current =="trx"  ){ 

            //get walletaddress
            $getwallet = mysqli_query($mysqli,"SELECT * FROM `payment_method` WHERE `code`='$current'  ");
            $curr = mysqli_fetch_assoc($getwallet);
    
            $wallet = $curr['wallet_address'];
            $crypto = "";
    
            $qrcode = "";
    
            $currency = strtoupper($current);
        



    
     //add to activity
     $date= date("d")." ".date("F")." ".date("Y")." , ".date("h")." : ".date("i").date("a");
     $action = "Reinvestment into ".$name;
     $describe ="Reinvestment of $".$new_amount." has been initialised for ".$rows['firstname']."  ";
     
     
     
     
     $add = mysqli_query($mysqli,"INSERT INTO `activity`(`userid`, `action`, `describe`, `date`, `amount`,`status`) VALUES('$userid', '$action', '$describe', '$date','$new_amount', 'Pending') ");
     
     
     
      $updateinvestment = mysqli_query($mysqli,"INSERT INTO `pending`(`userid`, `chargeid`, `wallet`, `name`, `amount`, `daily_roi`, `payout`, `qrcode`, `crypto`, `currency`, `date`, `reinvest`, `reinvest_id`)  VALUES('$userid', '$orderr', '$wallet',  '$name', '$new_amount', '$daily_roi', '$payout',  '$qrcode', '$crypto', '$currency', '$date', 1, '$id') ");
     
     
    
    
    
     
 if($updateinvestment){
 
 
 
    $curl = curl_init();
    
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.mailjet.com/v3.1/send",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS =>'{
        "SandboxMode": false,
        "Messages": [
            {
                "From": {
                    "Email": "info@quantumscalp.io",
                    "Name": "Quantum Scalp"
                },
                
                "To": [
                    {
                        "Email": "'.$rows['email'].'",
                        "Name": ""
                    }
                ],
                
                "Subject": "Transaction Generated",
                "TextPart": "",
                "HTMLPart": " <table align=\"center\" style=\"box-sizing:border-box;margin:0;padding:0;width:100%;height:100%;word-break:break-word;background-color:#efefef\"><tbody><tr><td align=\"center\" style=\"box-sizing:border-box;margin:0 auto;padding:0;vertical-align:top\" valign=\"top\"><table><tbody><tr><td width=\"600\" style=\"box-sizing:border-box;margin:0 auto;padding:0;vertical-align:top;font-family:&quot;display:block!important;max-width:600px!important\" valign=\"top\"><table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"box-sizing:border-box;margin:0;padding:0;font-family:&quot\"><tbody style=\"font-family:&quot\"><tr style=\"height:50px;font-family:&quot\"><td style=\"box-sizing:border-box;margin:0 auto;padding:8px;text-align:center;vertical-align:top;font-family:&quot\" align=\"center\" valign=\"top\"><div style=\"font-family:&quot\"><img src=\"https://quantumscalp.io/account/img/logo.png\" width=\"120px\" alt=\"Quantum Scalp\" style=\"font-family:&quot\"></div></td></tr><tr style=\"font-family:&quot\"><td style=\"box-sizing:border-box;margin:0 auto;vertical-align:top;font-family:&quot\" valign=\"top\"><table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"font-family:&quot\"><tbody style=\"font-family:&quot\"><tr style=\"font-family:&quot\"><td style=\"box-sizing:border-box;font-size:16px;line-height:1.7;margin:0 auto;padding:0;vertical-align:top;font-family:&quot\" valign=\"top\"><div style=\"display:block;border-radius:0;padding:20px;width:500px;margin:30px auto;font-family:&quot\"><h1 style=\"text-align:center;font-size:24px;font-weight:700;font-family:sans-serif;padding:5px;margin:0;color:#000\">Reset Password</h1><p style=\"margin:0;font-size:16px;padding:5px;font-family:&quot\">Hello <a style=\"font-family:&quot\">'.$rows['firstname'].'</a></p><p style=\"margin:0;padding:5px;font-size:16px;font-family:&quot\">Order Generated, View details below.<br><br>  <strong>Package</strong> : '.$name.' </p>\n<p style=\"margin: 0; font-size: 14px; line-height: 1.5; word-break: break-word; text-align: left; mso-line-height-alt: 21px; margin-top: 0; margin-bottom: 0;\"><strong>Invoice Id</strong> : '.$orderId.' </p>\n\n<p style=\"margin: 0; font-size: 14px; line-height: 1.5; word-break: break-word; text-align: left; mso-line-height-alt: 21px; margin-top: 0; margin-bottom: 0;\"><strong>Wallet </strong> : '.$wallet.' </p>\n<p style=\"margin: 0; font-size: 14px; line-height: 1.5; word-break: break-word; text-align: left; mso-line-height-alt: 21px; margin-top: 0; margin-bottom: 0;\"><strong>Date </strong> : '.$date.' </p> <b style=\"font-family:&quot\"></b></p><div style=\"display:block;font-family:&quot\"><div align=\"center\" style=\"margin:0 20px;font-family:&quot\"><a href=\"https://quantumscalp.io/account/\" style=\"width:270px;border-radius:4px;box-sizing:border-box;display:block;font-weight:300;line-height:2;margin-top:10px;padding:10px 15px;text-align:center;text-decoration:none;font-family:&quot;background-color:#000;color:#fff\" target=\"_blank\">Sign In</a></div></div><p style=\"font-size:14px;padding:5px;text-align:left;font-family:&quot\"><b style=\"font-family:&quot\">Thanks ,</b><br>Quantum Scalp Team</p></div></td></tr><tr style=\"margin:20px 0;font-family:&quot\"><td style=\"box-sizing:border-box;padding:0;vertical-align:top;font-family:&quot\" valign=\"top\"><p style=\"font-size:10px;padding:20px;text-align:center;font-family:&quot\"></p></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table><img src=\"\" style=\"width:1px;height:1px\" alt=\"\"><div style=\"text-align:center;padding-top:10px;padding-bottom:10px;font-size:8pt;font-family:sans-serif;background-color:#fff\"><a href=\"\" style=\"text-align:center;text-decoration:none;font-family:sans-serif;color:#666\" target=\"_blank\">UNSUBSCRIBE</a></div>",
               
                "TemplateLanguage": true,
              
                "TrackOpens": "account_default",
                "TrackClicks": "account_default"
                
            }
        ]
    }',
      CURLOPT_HTTPHEADER => array(
        "Content-Type: application/json",
        "Authorization: Basic NjIwMjNlMDUxZDlhNzMzNzU4MGY1NWU5OGZiMjczM2E6MzRmZmNjZjgxZDhmMDFjNDcwNzE1NjMwYzMyODhiZjE="
      ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
    
    
    
    
    
    
    
    
    //redrirect to payment page
   
    ?>
    <script>
    location = "fund?currency=<?php echo $currency;?>&orderid=<?php echo $orderr;?>&name=<?php echo $name;?>"
    </script>

    <?php
    
    
    }
    
    
    




    }elseif(($current =="ETH" and $new_amount >200) or $current !="ETH" ){


      
       

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://plisio.net/api/v1/invoices/new?source_currency=USD&source_amount='.$amount.'&order_number='.$orderr.'&currency='.$current.'&email='.$rows['email'].'&order_name='.urlencode($name).'&callback_url=https://quantumscalp.io/account/payment&api_key=sEhbpaXTi3YZNt5exXFgrBb5NXCdYD6MhR-T0lywD1I7brQn8wU3fNBPWfOYNCOA',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        //echo $response;
        $response = json_decode($response);
        
        
        $wallet = $response->data->wallet_hash;
        
        $crypto = $response->data->amount;
        
        $qrcode = $response->data->qr_code;
        
        $rates ="";




  










    
     //add to activity
     $date= date("d")." ".date("F")." ".date("Y")." , ".date("h")." : ".date("i").date("a");
    $action = "Reinvestment into ".$name;
    $describe ="Reinvestment of $".$new_amount." has been initialised for ".$rows['firstname']."  ";
    
    
    
    
    $add = mysqli_query($mysqli,"INSERT INTO `activity`(`userid`, `action`, `describe`, `date`, `amount`,`status`) VALUES('$userid', '$action', '$describe', '$date','$new_amount', 'Pending') ");
    
    
    
     $updateinvestment = mysqli_query($mysqli,"INSERT INTO `pending`(`userid`, `chargeid`, `wallet`, `name`, `amount`, `daily_roi`, `payout`, `qrcode`, `crypto`, `currency`, `date`, `reinvest`, `reinvest_id`)  VALUES('$userid', '$orderr', '$wallet',  '$name', '$new_amount', '$daily_roi', '$payout',  '$qrcode', '$crypto', '$current', '$date', 1, '$id') ");
    
    
    
    
    
    
    
    
    $curl = curl_init();
    
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.mailjet.com/v3.1/send",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS =>'{
        "SandboxMode": false,
        "Messages": [
            {
                "From": {
                    "Email": "info@quantumscalp.io",
                    "Name": "Quantum Scalp"
                },
                
                "To": [
                    {
                        "Email": "'.$rows['email'].'",
                        "Name": ""
                    }
                ],
                
                "Subject": "Transaction Generated",
                "TextPart": "",
                "HTMLPart": " <table align=\"center\" style=\"box-sizing:border-box;margin:0;padding:0;width:100%;height:100%;word-break:break-word;background-color:#efefef\"><tbody><tr><td align=\"center\" style=\"box-sizing:border-box;margin:0 auto;padding:0;vertical-align:top\" valign=\"top\"><table><tbody><tr><td width=\"600\" style=\"box-sizing:border-box;margin:0 auto;padding:0;vertical-align:top;font-family:&quot;display:block!important;max-width:600px!important\" valign=\"top\"><table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"box-sizing:border-box;margin:0;padding:0;font-family:&quot\"><tbody style=\"font-family:&quot\"><tr style=\"height:50px;font-family:&quot\"><td style=\"box-sizing:border-box;margin:0 auto;padding:8px;text-align:center;vertical-align:top;font-family:&quot\" align=\"center\" valign=\"top\"><div style=\"font-family:&quot\"><img src=\"https://quantumscalp.io/account/img/logo.png\" width=\"120px\" alt=\"Quantum Scalp\" style=\"font-family:&quot\"></div></td></tr><tr style=\"font-family:&quot\"><td style=\"box-sizing:border-box;margin:0 auto;vertical-align:top;font-family:&quot\" valign=\"top\"><table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"font-family:&quot\"><tbody style=\"font-family:&quot\"><tr style=\"font-family:&quot\"><td style=\"box-sizing:border-box;font-size:16px;line-height:1.7;margin:0 auto;padding:0;vertical-align:top;font-family:&quot\" valign=\"top\"><div style=\"display:block;border-radius:0;padding:20px;width:500px;margin:30px auto;font-family:&quot\"><h1 style=\"text-align:center;font-size:24px;font-weight:700;font-family:sans-serif;padding:5px;margin:0;color:#000\">Reset Password</h1><p style=\"margin:0;font-size:16px;padding:5px;font-family:&quot\">Hello <a style=\"font-family:&quot\">'.$rows['firstname'].'</a></p><p style=\"margin:0;padding:5px;font-size:16px;font-family:&quot\">Order Generated, View details below.<br><br>  <strong>Package</strong> : '.$name.' </p>\n<p style=\"margin: 0; font-size: 14px; line-height: 1.5; word-break: break-word; text-align: left; mso-line-height-alt: 21px; margin-top: 0; margin-bottom: 0;\"><strong>Invoice Id</strong> : '.$orderId.' </p>\n\n<p style=\"margin: 0; font-size: 14px; line-height: 1.5; word-break: break-word; text-align: left; mso-line-height-alt: 21px; margin-top: 0; margin-bottom: 0;\"><strong>Wallet </strong> : '.$wallet.' </p>\n<p style=\"margin: 0; font-size: 14px; line-height: 1.5; word-break: break-word; text-align: left; mso-line-height-alt: 21px; margin-top: 0; margin-bottom: 0;\"><strong>Date </strong> : '.$date.' </p> <b style=\"font-family:&quot\"></b></p><div style=\"display:block;font-family:&quot\"><div align=\"center\" style=\"margin:0 20px;font-family:&quot\"><a href=\"https://quantumscalp.io/account/\" style=\"width:270px;border-radius:4px;box-sizing:border-box;display:block;font-weight:300;line-height:2;margin-top:10px;padding:10px 15px;text-align:center;text-decoration:none;font-family:&quot;background-color:#000;color:#fff\" target=\"_blank\">Sign In</a></div></div><p style=\"font-size:14px;padding:5px;text-align:left;font-family:&quot\"><b style=\"font-family:&quot\">Thanks ,</b><br>Quantum Scalp Team</p></div></td></tr><tr style=\"margin:20px 0;font-family:&quot\"><td style=\"box-sizing:border-box;padding:0;vertical-align:top;font-family:&quot\" valign=\"top\"><p style=\"font-size:10px;padding:20px;text-align:center;font-family:&quot\"></p></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table><img src=\"\" style=\"width:1px;height:1px\" alt=\"\"><div style=\"text-align:center;padding-top:10px;padding-bottom:10px;font-size:8pt;font-family:sans-serif;background-color:#fff\"><a href=\"\" style=\"text-align:center;text-decoration:none;font-family:sans-serif;color:#666\" target=\"_blank\">UNSUBSCRIBE</a></div>",
               
                "TemplateLanguage": true,
              
                "TrackOpens": "account_default",
                "TrackClicks": "account_default"
                
            }
        ]
    }',
      CURLOPT_HTTPHEADER => array(
        "Content-Type: application/json",
        "Authorization: Basic NjIwMjNlMDUxZDlhNzMzNzU4MGY1NWU5OGZiMjczM2E6MzRmZmNjZjgxZDhmMDFjNDcwNzE1NjMwYzMyODhiZjE="
      ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
    
    
    
    
    
    
    
    
    
    
    
     if($updateinvestment){
    
    
    
    ?>
    <script>
    location = "fund?currency=<?php echo $current;?>&orderid=<?php echo $orderr;?>&name=<?php echo $name;?>"
    </script>

    <?php
    

    
     }
    
    


    }else{


        ?>
    <script>
    Swal.fire({
        icon: 'warning',
        title: 'Low amount for Etherum!',
        text: 'Please deposit $200 or above for etherum payment.'
    })

    setTimeout(() => {
        location = location;
    }, 3000);
    </script>
    <?php




    }
    
    
    
    
    
    }elseif($platform == 2){
    
    
    
     if( $rows['wallet'] >=  $new_amount){
    
     $updateinvestment = mysqli_query($mysqli,"UPDATE `investment` SET amount='$real_amount', daily_roi='$daily_roi', payout='$payout' WHERE id='$id' ");


  //add to activity
     $date= date("d")." ".date("F")." ".date("Y")." , ".date("h")." : ".date("i").date("a");
    $action = "Reinvestment into ".$name;
    $describe ="Reinvestment of $".$new_amount." has been initialised for ".$rows['firstname']."  ";
    
    
    
    $add = mysqli_query($mysqli,"INSERT INTO `activity`(`userid`, `action`, `describe`, `date`, `amount`,`status`) VALUES('$userid', '$action', '$describe', '$date','$new_amount', 'Credited') ");
    
    



    $getuser = mysqli_query($mysqli,"SELECT * FROM  users WHERE id='".$rows['id']."'");
    $user = mysqli_fetch_assoc($getuser);

    ///referals bouns
    
    
    $price_amount = $new_amount;
    
    //next check if the person was refered and den update the referer with 10% bouns
    //the person was refered and the 
    
    if($user['referred'] !="" and $user['pay_refer1']==0 ){
    
        $bouns = 0.10*$price_amount;
    
        echo $bouns;
    
    
        //find the person who refered himm account
    $getrefer = mysqli_query($mysqli,"SELECT * FROM users WHERE referal_link='".$user['referred']."' ");
    $refer = mysqli_fetch_assoc($getrefer);
    
    
    $act ="Referral Bonus of 10%, 1st level";
    $desc ="Referral commission of $".$bouns;
    //now update the referer acc by adding it to referals table
    //status is zero unles user claim it
    $updaterefer = mysqli_query($mysqli,"INSERT INTO `activity`(`userid`, `action`, `describe`, `date`, `amount`,`status`) VALUES('".$refer['id']."', '$act', '$desc', '$date','$bouns', 'Credited')");
    
    $newwallet = $refer['wallet']+$bouns;
    
    $updateuser = mysqli_query($mysqli,"UPDATE users SET  wallet='$newwallet'  where id='".$refer['id']."' ");
    
    $updaterefer = mysqli_query($mysqli,"INSERT INTO `referal` (`claimerid`, `status`, `date`, `amount`, `detail`) VALUES('".$refer['id']."', 1, '$date', '$bouns', '$act'  )  ");
    
    //now update the referee that bouns has been given to his referer step 1
    $updateuser = mysqli_query($mysqli,"UPDATE users SET  pay_refer1=0  where id='".$user['id']."' ");
    
    
    
    
    //step 1 is done move over to step 2 of referal bouns
    if($user['referred']!="" and $user['pay_refer2']==0){
    
        $bouns2 = 0.05*$price_amount;
        //find the person who refered himm account
    $getrefer2 = mysqli_query($mysqli,"SELECT * FROM users WHERE referal_link='".$refer['referred']."' ");
    $refer2 = mysqli_fetch_assoc($getrefer2);


    $act2 ="Referral Bonus of 5%, 2nd level";
    $desc2 ="Referral commission of $".$bouns2;
    //now update the referer acc by adding it to referals table
    //status is zero unles user claim it
    $updaterefer = mysqli_query($mysqli,"INSERT INTO `activity`(`userid`, `action`, `describe`, `date`, `amount`,`status`) VALUES('".$refer2['id']."', '$act2', '$desc2', '$date','$bouns2', 'Credited')");
    
    $details2 ="Referral Funding Bonus of 5%, 2nd level";
    //now update the referer acc by adding it to referals table
    //status is zero unles user claim it
    $updaterefer = mysqli_query($mysqli,"INSERT INTO `referal` (`claimerid`, `status`, `date`, `amount`, `detail`) VALUES('".$refer2['id']."', 1, '$date', '$bouns2', '$details2'  )  ");
    
    //now update the referee that bouns has been given to his referer step 2
    $updateuser = mysqli_query($mysqli,"UPDATE users SET  pay_refer2=0  where id='".$user['id']."' ");
    
    $newwallet2 = $refer2['wallet']+$bouns2;
    
    $updateuser = mysqli_query($mysqli,"UPDATE users SET  wallet='$newwallet2'  where id='".$refer2['id']."' ");
    
    
    //step 2 is done move over to step 3 of referal bouns
    if($user['referred']!="" and $user['pay_refer3'] ==0){
    
        $bouns3 = 0.025*$price_amount;
    
        //find the person who refered himm account
    $getrefer3 = mysqli_query($mysqli,"SELECT * FROM users WHERE referal_link='".$refer2['referred']."' ");
    $refer3 = mysqli_fetch_assoc($getrefer3);


    $act3 ="Referral Bonus of 2.5%, 3rd level";
    $desc3 ="Referral commission of $".$bouns3;
    //now update the referer acc by adding it to referals table
    //status is zero unles user claim it
    $updaterefer = mysqli_query($mysqli,"INSERT INTO `activity`(`userid`, `action`, `describe`, `date`, `amount`,`status`) VALUES('".$refer3['id']."', '$act3', '$desc3', '$date','$bouns3', 'Credited')");
    
    $details3 ="Referral Funding Bonus of 2.5%, 3rd level";
    //now update the referer acc by adding it to referals table
    //status is zero unles user claim it
    $updaterefer = mysqli_query($mysqli,"INSERT INTO `referal` (`claimerid`, `status`, `date`, `amount`, `detail`) VALUES('".$refer3['id']."', 1, '$date', '$bouns3', '$details3'  )  ");
    
    //now update the referee that bouns has been given to his referer step 2
    $updateuser = mysqli_query($mysqli,"UPDATE users SET  pay_refer3=0  where id='".$user['id']."' ");
    
    $newwallet3 = $refer3['wallet']+$bouns3;
    
    $updateuser = mysqli_query($mysqli,"UPDATE users SET  wallet='$newwallet3'  where id='".$refer3['id']."' ");
    
    }
    
    
    
    }
    
    
    
    
    }
    
    /////////////


    
     if($updateinvestment){
    
        $newwallet = $rows['wallet']-$new_amount;
    
            //update the users wallet
        $update = mysqli_query($mysqli,"UPDATE users SET wallet='$newwallet' WHERE id='$userid' ");
    
    

    
    
    ?>
    <script>
    Swal.fire({
        icon: 'success',
        title: 'Successfull!',
        text: 'You have Upgrade your Investment successfuly.'
    })



    setTimeout(() => {
        location = location;
    }, 3000);
    </script>

    <?php
    
    
    
    
     }
    
    
    
     }else{
         
        ?>
    <script>
    Swal.fire({
        icon: 'warning',
        title: 'insufficient Wallet!',
        text: 'You don\'t have enough wallet balance to invest with this amount.'
    })
    </script>

    <?php
    
    
    
     }
    
    
    }elseif($platform == 3){



    
    
     if( $rows['compound_profit'] >=  $new_amount){
    
     $updateinvestment = mysqli_query($mysqli,"UPDATE `investment` SET amount='$real_amount', daily_roi='$daily_roi', payout='$payout' WHERE id='$id' ");



 //add to activity
     $date= date("d")." ".date("F")." ".date("Y")." , ".date("h")." : ".date("i").date("a");
    $action = "Reinvestment into ".$name;
    $describe ="Reinvestment of $".$new_amount." has been initialised for ".$rows['firstname']."  ";
    
    
    
    $add = mysqli_query($mysqli,"INSERT INTO `activity`(`userid`, `action`, `describe`, `date`, `amount`,`status`) VALUES('$userid', '$action', '$describe', '$date','$new_amount', 'Credited') ");
    
    



    $getuser = mysqli_query($mysqli,"SELECT * FROM  users WHERE id='".$rows['id']."'");
    $user = mysqli_fetch_assoc($getuser);

    ///referals bouns
    
    
    $price_amount = $new_amount;
    
    //next check if the person was refered and den update the referer with 10% bouns
    //the person was refered and the 
    
    if($user['referred'] !="" and $user['pay_refer1']==0 ){
    
        $bouns = 0.10*$price_amount;
    
        echo $bouns;
    
    
        //find the person who refered himm account
    $getrefer = mysqli_query($mysqli,"SELECT * FROM users WHERE referal_link='".$user['referred']."' ");
    $refer = mysqli_fetch_assoc($getrefer);
    
    
    $act ="Referral Bonus of 10%, 1st level";
    $desc ="Referral commission of $".$bouns;
    //now update the referer acc by adding it to referals table
    //status is zero unles user claim it
    $updaterefer = mysqli_query($mysqli,"INSERT INTO `activity`(`userid`, `action`, `describe`, `date`, `amount`,`status`) VALUES('".$refer['id']."', '$act', '$desc', '$date','$bouns', 'Credited')");
    
    $newwallet = $refer['wallet']+$bouns;
    
    $updateuser = mysqli_query($mysqli,"UPDATE users SET  wallet='$newwallet'  where id='".$refer['id']."' ");
    
    $updaterefer = mysqli_query($mysqli,"INSERT INTO `referal` (`claimerid`, `status`, `date`, `amount`, `detail`) VALUES('".$refer['id']."', 1, '$date', '$bouns', '$act'  )  ");
    
    //now update the referee that bouns has been given to his referer step 1
    $updateuser = mysqli_query($mysqli,"UPDATE users SET  pay_refer1=0  where id='".$user['id']."' ");
    
    
    
    
    //step 1 is done move over to step 2 of referal bouns
    if($user['referred']!="" and $user['pay_refer2']==0){
    
        $bouns2 = 0.05*$price_amount;
        //find the person who refered himm account
    $getrefer2 = mysqli_query($mysqli,"SELECT * FROM users WHERE referal_link='".$refer['referred']."' ");
    $refer2 = mysqli_fetch_assoc($getrefer2);


    $act2 ="Referral Bonus of 5%, 2nd level";
    $desc2 ="Referral commission of $".$bouns2;
    //now update the referer acc by adding it to referals table
    //status is zero unles user claim it
    $updaterefer = mysqli_query($mysqli,"INSERT INTO `activity`(`userid`, `action`, `describe`, `date`, `amount`,`status`) VALUES('".$refer2['id']."', '$act2', '$desc2', '$date','$bouns2', 'Credited')");
    
    $details2 ="Referral Funding Bonus of 5%, 2nd level";
    //now update the referer acc by adding it to referals table
    //status is zero unles user claim it
    $updaterefer = mysqli_query($mysqli,"INSERT INTO `referal` (`claimerid`, `status`, `date`, `amount`, `detail`) VALUES('".$refer2['id']."', 1, '$date', '$bouns2', '$details2'  )  ");
    
    //now update the referee that bouns has been given to his referer step 2
    $updateuser = mysqli_query($mysqli,"UPDATE users SET  pay_refer2=0  where id='".$user['id']."' ");
    
    $newwallet2 = $refer2['wallet']+$bouns2;
    
    $updateuser = mysqli_query($mysqli,"UPDATE users SET  wallet='$newwallet2'  where id='".$refer2['id']."' ");
    
    
    //step 2 is done move over to step 3 of referal bouns
    if($user['referred']!="" and $user['pay_refer3'] ==0){
    
        $bouns3 = 0.025*$price_amount;
    
        //find the person who refered himm account
    $getrefer3 = mysqli_query($mysqli,"SELECT * FROM users WHERE referal_link='".$refer2['referred']."' ");
    $refer3 = mysqli_fetch_assoc($getrefer3);


    $act3 ="Referral Bonus of 2.5%, 3rd level";
    $desc3 ="Referral commission of $".$bouns3;
    //now update the referer acc by adding it to referals table
    //status is zero unles user claim it
    $updaterefer = mysqli_query($mysqli,"INSERT INTO `activity`(`userid`, `action`, `describe`, `date`, `amount`,`status`) VALUES('".$refer3['id']."', '$act3', '$desc3', '$date','$bouns3', 'Credited')");
    
    $details3 ="Referral Funding Bonus of 2.5%, 3rd level";
    //now update the referer acc by adding it to referals table
    //status is zero unles user claim it
    $updaterefer = mysqli_query($mysqli,"INSERT INTO `referal` (`claimerid`, `status`, `date`, `amount`, `detail`) VALUES('".$refer3['id']."', 1, '$date', '$bouns3', '$details3'  )  ");
    
    //now update the referee that bouns has been given to his referer step 2
    $updateuser = mysqli_query($mysqli,"UPDATE users SET  pay_refer3=0  where id='".$user['id']."' ");
    
    $newwallet3 = $refer3['wallet']+$bouns3;
    
    $updateuser = mysqli_query($mysqli,"UPDATE users SET  wallet='$newwallet3'  where id='".$refer3['id']."' ");
    
    }
    
    
    
    }
    
    
    
    
    }
    /////////////


    
     if($updateinvestment){

    
        $newwallet = $rows['compound_profit']-$new_amount;
        
                //update the users wallet
        $update = mysqli_query($mysqli,"UPDATE users SET compound_profit='$newwallet' WHERE id='$userid' ");
        
    
    
  
    
    
    
    
    
    
    ?>
    <script>
    Swal.fire({
        icon: 'success',
        title: 'Successfull!',
        text: 'You have Upgrade your Investement successfuly.'
    })



    setTimeout(() => {
        location = location;
    }, 3000);
    </script>

    <?php
    
    
    
    
     }
    
    
    
     }else{
         
        ?>
    <script>
    Swal.fire({
        icon: 'warning',
        title: 'insufficient Wallet!',
        text: 'You don\'t have enough wallet balance to invest with this amount.'
    })
    </script>

    <?php
    
    
    
     }



        
    }elseif($platform == 4){



    
    
        if( $added_roi >=  $new_amount){
    

            $new_added_roi = round( $added_roi-$new_amount, 2);

            $updateinvestment = mysqli_query($mysqli,"UPDATE `investment` SET amount='$real_amount', daily_roi='$daily_roi',  added_roi='$new_added_roi' ,  payout='$payout' WHERE id='$id' ");
       
       
       
        //add to activity
            $date= date("d")." ".date("F")." ".date("Y")." , ".date("h")." : ".date("i").date("a");
           $action = "Reinvestment into ".$name;
           $describe ="Reinvestment of $".$new_amount." has been initialised for ".$rows['firstname']."  ";
           
           
           
           $add = mysqli_query($mysqli,"INSERT INTO `activity`(`userid`, `action`, `describe`, `date`, `amount`,`status`) VALUES('$userid', '$action', '$describe', '$date','$new_amount', 'Credited') ");
           
           
       
       
       
           $getuser = mysqli_query($mysqli,"SELECT * FROM  users WHERE id='".$rows['id']."'");
           $user = mysqli_fetch_assoc($getuser);
       
           ///referals bouns
           
           
           $price_amount = $new_amount;
           
           //next check if the person was refered and den update the referer with 10% bouns
           //the person was refered and the 
           
           if($user['referred'] !="" and $user['pay_refer1']==0 ){
           
               $bouns = 0.10*$price_amount;
           
               echo $bouns;
           
           
               //find the person who refered himm account
           $getrefer = mysqli_query($mysqli,"SELECT * FROM users WHERE referal_link='".$user['referred']."' ");
           $refer = mysqli_fetch_assoc($getrefer);
           
           
           $act ="Referral Bonus of 10%, 1st level";
           $desc ="Referral commission of $".$bouns;
           //now update the referer acc by adding it to referals table
           //status is zero unles user claim it
           $updaterefer = mysqli_query($mysqli,"INSERT INTO `activity`(`userid`, `action`, `describe`, `date`, `amount`,`status`) VALUES('".$refer['id']."', '$act', '$desc', '$date','$bouns', 'Credited')");
           
           $newwallet = $refer['wallet']+$bouns;
           
           $updateuser = mysqli_query($mysqli,"UPDATE users SET  wallet='$newwallet'  where id='".$refer['id']."' ");
           
           $updaterefer = mysqli_query($mysqli,"INSERT INTO `referal` (`claimerid`, `status`, `date`, `amount`, `detail`) VALUES('".$refer['id']."', 1, '$date', '$bouns', '$act'  )  ");
           
           //now update the referee that bouns has been given to his referer step 1
           $updateuser = mysqli_query($mysqli,"UPDATE users SET  pay_refer1=0  where id='".$user['id']."' ");
           
           
           
           
           //step 1 is done move over to step 2 of referal bouns
           if($user['referred']!="" and $user['pay_refer2']==0){
           
               $bouns2 = 0.05*$price_amount;
               //find the person who refered himm account
           $getrefer2 = mysqli_query($mysqli,"SELECT * FROM users WHERE referal_link='".$refer['referred']."' ");
           $refer2 = mysqli_fetch_assoc($getrefer2);
       
       
           $act2 ="Referral Bonus of 5%, 2nd level";
           $desc2 ="Referral commission of $".$bouns2;
           //now update the referer acc by adding it to referals table
           //status is zero unles user claim it
           $updaterefer = mysqli_query($mysqli,"INSERT INTO `activity`(`userid`, `action`, `describe`, `date`, `amount`,`status`) VALUES('".$refer2['id']."', '$act2', '$desc2', '$date','$bouns2', 'Credited')");
           
           $details2 ="Referral Funding Bonus of 5%, 2nd level";
           //now update the referer acc by adding it to referals table
           //status is zero unles user claim it
           $updaterefer = mysqli_query($mysqli,"INSERT INTO `referal` (`claimerid`, `status`, `date`, `amount`, `detail`) VALUES('".$refer2['id']."', 1, '$date', '$bouns2', '$details2'  )  ");
           
           //now update the referee that bouns has been given to his referer step 2
           $updateuser = mysqli_query($mysqli,"UPDATE users SET  pay_refer2=0  where id='".$user['id']."' ");
           
           $newwallet2 = $refer2['wallet']+$bouns2;
           
           $updateuser = mysqli_query($mysqli,"UPDATE users SET  wallet='$newwallet2'  where id='".$refer2['id']."' ");
           
           
           //step 2 is done move over to step 3 of referal bouns
           if($user['referred']!="" and $user['pay_refer3'] ==0){
           
               $bouns3 = 0.025*$price_amount;
           
               //find the person who refered himm account
           $getrefer3 = mysqli_query($mysqli,"SELECT * FROM users WHERE referal_link='".$refer2['referred']."' ");
           $refer3 = mysqli_fetch_assoc($getrefer3);
       
       
           $act3 ="Referral Bonus of 2.5%, 3rd level";
           $desc3 ="Referral commission of $".$bouns3;
           //now update the referer acc by adding it to referals table
           //status is zero unles user claim it
           $updaterefer = mysqli_query($mysqli,"INSERT INTO `activity`(`userid`, `action`, `describe`, `date`, `amount`,`status`) VALUES('".$refer3['id']."', '$act3', '$desc3', '$date','$bouns3', 'Credited')");
           
           $details3 ="Referral Funding Bonus of 2.5%, 3rd level";
           //now update the referer acc by adding it to referals table
           //status is zero unles user claim it
           $updaterefer = mysqli_query($mysqli,"INSERT INTO `referal` (`claimerid`, `status`, `date`, `amount`, `detail`) VALUES('".$refer3['id']."', 1, '$date', '$bouns3', '$details3'  )  ");
           
           //now update the referee that bouns has been given to his referer step 2
           $updateuser = mysqli_query($mysqli,"UPDATE users SET  pay_refer3=0  where id='".$user['id']."' ");
           
           $newwallet3 = $refer3['wallet']+$bouns3;
           
           $updateuser = mysqli_query($mysqli,"UPDATE users SET  wallet='$newwallet3'  where id='".$refer3['id']."' ");
           
           }
           
           
           
           }
           
           
           
           
           }
           /////////////
       
       
           
            if($updateinvestment){
           
         
           
           
           
           
           
           ?>
    <script>
    Swal.fire({
        icon: 'success',
        title: 'Successfull!',
        text: 'You have Upgrade your Investement successfuly.'
    })



    setTimeout(() => {
        location = location;
    }, 3000);
    </script>

    <?php
           
           
           
           
            }
           
           
           
            }else{
                
               ?>
    <script>
    Swal.fire({
        icon: 'warning',
        title: 'Insuffienct Wallet!',
        text: 'You don\'t have enuogh wallet balance to invest with this amount.'
    })
    </script>

    <?php
           
           
           
            }
       








    }



}else{


    ?>
    <script>
    Swal.fire({
        icon: 'warning',
        title: 'Input is not valid ',
        text: 'The Inputed Value is not valid'
    });
    </script>

    <?php
   




}



    
    
    //end of update
    }
    
    
    
    
    
    
    
    







//withdraw Capital
if(isset($_POST['withdraw-update'])){

    $id = mysqli_real_escape_string($mysqli,$_POST['id']);
   

if(is_numeric($id)){


    $get = mysqli_query($mysqli,"SELECT * FROM `investment` WHERE id='$id' ");
    
    $row = mysqli_fetch_assoc($get);
   
    $amount = $row['amount'];
   
    $wallet = $rows['wallet'];
   
    $new = $wallet+$amount;




   
    //update wallet
    $update = mysqli_query($mysqli,"UPDATE users SET wallet='$new'  WHERE id='".$rows['id']."' ");
   
   
     //add to activity
     $userid=$rows['id'];
     $date= date("d")." ".date("F")." ".date("Y")." , ".date("h")." : ".date("i").date("a");
     $action = "Investment Capital Reimbursed ";
     $describe ="Investment capital of $".$amount." has been Reimbursed for ".$rows['firstname']."  ";
     
     
    
     
     $add_old = mysqli_query($mysqli,"INSERT INTO `investment_old` (`userid`, `investmentid`, `name`,  `amount`, `daily_roi`, `added_roi`, `duration`, `payout`, `date`, `status`) VALUES('".$row['userid']."', '".$row['investmentid']."', '".$row['name']."', '".$row['amount']."','".$row['daily_roi']."', '".$row['added_roi']."', '".$row['duration']."', '".$row['payout']."','".$row['date']."', '".$row['status']."'    ) ");

     $add = mysqli_query($mysqli,"INSERT INTO `activity`(`userid`, `action`, `describe`, `date`, `amount`,`status`) VALUES('$userid', '$action', '$describe', '$date','$amount', 'Successful') ");
   
   
     //delete 
   
     $del = mysqli_query($mysqli,"DELETE FROM `investment` WHERE id='$id' ");
   
   
   
   if($del and  $update ){
   
   ?>
    <script>
   


    
	notif({
		msg: "<b>Successful!</b><br/> You have Withdrawn your Investement capital and portfolio ended.",
		width: 250,
		position: "center",
		type: "success"
	});

    setTimeout(() => {
        location = "dashboard";
    }, 2000);

    </script>

    <?php
   
   
   
   
   }




}else{


  
    ?>
    <script>
 
    notif({
		msg: "<b>Input is not valid!</b><br/> The Inputed Value is not valid.",
		width: 250,
		position: "center",
		type: "warning"
	});

    setTimeout(() => {
        location = "dashboard";
    }, 2000);
    </script>

    <?php
   




}
   
   
   
   
   }
   
  
   










//restart investment
if(isset($_POST['restart-update'])){


    $id = mysqli_real_escape_string($mysqli,$_POST['id']);

    if(is_numeric($id)){

    $get = mysqli_query($mysqli,"SELECT * FROM `investment` WHERE id='$id' ");
    
    $row = mysqli_fetch_assoc($get);
    $date= date("d")." ".date("F")." ".date("Y")." , ".date("h")." : ".date("i").date("a");
    


    $add_old = mysqli_query($mysqli,"INSERT INTO `investment_old` (`userid`, `investmentid`, `name`,  `amount`, `daily_roi`, `added_roi`, `duration`, `payout`, `date`, `status`) VALUES('".$row['userid']."', '".$row['investmentid']."', '".$row['name']."', '".$row['amount']."','".$row['daily_roi']."', '".$row['added_roi']."', '".$row['duration']."', '".$row['payout']."','".$row['date']."', '".$row['status']."'    ) ");



 $update = mysqli_query($mysqli,"UPDATE investment SET `duration`='270', `date`='$date', `status`='1'  WHERE id='$id' ");



 

 $amount =$row['amount'];



   //add to activity
   $userid=$rows['id'];
   $date= date("d")." ".date("F")." ".date("Y")." , ".date("h")." : ".date("i").date("a");
   $action = "Investment  Re-initiate ";
   $describe ="You Investment  of $".$amount." has been Re-initiate.  ";

   $add = mysqli_query($mysqli,"INSERT INTO `activity`(`userid`, `action`, `describe`, `date`, `amount`,`status`) VALUES('$userid', '$action', '$describe', '$date','$amount', 'Successful') ");


   if( $update){

//give referal bonus


$getuser = mysqli_query($mysqli,"SELECT * FROM  users WHERE id='".$rows['id']."'");
$user = mysqli_fetch_assoc($getuser);

///referals bouns


$price_amount = $amount;

//next check if the person was refered and den update the referer with 10% bouns
//the person was refered and the 

if($user['referred'] !="" and $user['pay_refer1']==0 ){

    $bouns = 0.10*$price_amount;

    echo $bouns;


    //find the person who refered himm account
$getrefer = mysqli_query($mysqli,"SELECT * FROM users WHERE referal_link='".$user['referred']."' ");
$refer = mysqli_fetch_assoc($getrefer);


$act ="Referral Bonus of 10%, 1st level";
$desc ="Referral commission of $".$bouns;
//now update the referer acc by adding it to referals table
//status is zero unles user claim it
$updaterefer = mysqli_query($mysqli,"INSERT INTO `activity`(`userid`, `action`, `describe`, `date`, `amount`,`status`) VALUES('".$refer['id']."', '$act', '$desc', '$date','$bouns', 'Credited')");

$newwallet = $refer['wallet']+$bouns;

$updateuser = mysqli_query($mysqli,"UPDATE users SET  wallet='$newwallet'  where id='".$refer['id']."' ");

$updaterefer = mysqli_query($mysqli,"INSERT INTO `referal` (`claimerid`, `status`, `date`, `amount`, `detail`) VALUES('".$refer['id']."', 1, '$date', '$bouns', '$act'  )  ");

//now update the referee that bouns has been given to his referer step 1
$updateuser = mysqli_query($mysqli,"UPDATE users SET  pay_refer1=0  where id='".$user['id']."' ");




//step 1 is done move over to step 2 of referal bouns
if($user['referred']!="" and $user['pay_refer2']==0){

    $bouns2 = 0.05*$price_amount;
    //find the person who refered himm account
$getrefer2 = mysqli_query($mysqli,"SELECT * FROM users WHERE referal_link='".$refer['referred']."' ");
$refer2 = mysqli_fetch_assoc($getrefer2);


$act2 ="Referral Bonus of 5%, 2nd level";
$desc2 ="Referral commission of $".$bouns2;
//now update the referer acc by adding it to referals table
//status is zero unles user claim it
$updaterefer = mysqli_query($mysqli,"INSERT INTO `activity`(`userid`, `action`, `describe`, `date`, `amount`,`status`) VALUES('".$refer2['id']."', '$act2', '$desc2', '$date','$bouns2', 'Credited')");

$details2 ="Referral Funding Bonus of 5%, 2nd level";
//now update the referer acc by adding it to referals table
//status is zero unles user claim it
$updaterefer = mysqli_query($mysqli,"INSERT INTO `referal` (`claimerid`, `status`, `date`, `amount`, `detail`) VALUES('".$refer2['id']."', 1, '$date', '$bouns2', '$details2'  )  ");

//now update the referee that bouns has been given to his referer step 2
$updateuser = mysqli_query($mysqli,"UPDATE users SET  pay_refer2=0  where id='".$user['id']."' ");

$newwallet2 = $refer2['wallet']+$bouns2;

$updateuser = mysqli_query($mysqli,"UPDATE users SET  wallet='$newwallet2'  where id='".$refer2['id']."' ");


//step 2 is done move over to step 3 of referal bouns
if($user['referred']!="" and $user['pay_refer3'] ==0){

    $bouns3 = 0.025*$price_amount;

    //find the person who refered himm account
$getrefer3 = mysqli_query($mysqli,"SELECT * FROM users WHERE referal_link='".$refer2['referred']."' ");
$refer3 = mysqli_fetch_assoc($getrefer3);


$act3 ="Referral Bonus of 2.5%, 3rd level";
$desc3 ="Referral commission of $".$bouns3;
//now update the referer acc by adding it to referals table
//status is zero unles user claim it
$updaterefer = mysqli_query($mysqli,"INSERT INTO `activity`(`userid`, `action`, `describe`, `date`, `amount`,`status`) VALUES('".$refer3['id']."', '$act3', '$desc3', '$date','$bouns3', 'Credited')");

$details3 ="Referral Funding Bonus of 2.5%, 3rd level";
//now update the referer acc by adding it to referals table
//status is zero unles user claim it
$updaterefer = mysqli_query($mysqli,"INSERT INTO `referal` (`claimerid`, `status`, `date`, `amount`, `detail`) VALUES('".$refer3['id']."', 1, '$date', '$bouns3', '$details3'  )  ");

//now update the referee that bouns has been given to his referer step 2
$updateuser = mysqli_query($mysqli,"UPDATE users SET  pay_refer3=0  where id='".$user['id']."' ");

$newwallet3 = $refer3['wallet']+$bouns3;

$updateuser = mysqli_query($mysqli,"UPDATE users SET  wallet='$newwallet3'  where id='".$refer3['id']."' ");

}



}




}









    ?>
    <script>
  

 

    notif({
		msg: "<b>Successful!</b><br/> You have Re-initiate your Investement  portfolio.",
		width: 250,
		position: "center",
		type: "success"
	});

    setTimeout(() => {
        location = "dashboard";
    }, 2000);


    </script>

    <?php
    



   }
   
   
}else{


    ?>
    <script>
 
    
    notif({
		msg: "<b>Input is not valid!</b><br/> The Inputed Value is not valid.",
		width: 250,
		position: "center",
		type: "warning"
	});

    setTimeout(() => {
        location = "dashboard";
    }, 2000);

    </script>

    <?php
   




}
  



}

   

?>



</html>