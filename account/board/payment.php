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


	

	<!-- Favicon -->
	<link rel="icon" href="img/icon.png" type="image/x-icon"/>

	<!-- Title -->
	<title>Payment</title>

	<!-- Bootstrap css-->
	<link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

	<!-- Icons css-->
	<link href="assets/web-fonts/icons.css" rel="stylesheet"/>
	<link href="assets/web-fonts/font-awesome/font-awesome.min.css" rel="stylesheet">
	<link href="assets/web-fonts/plugin.css" rel="stylesheet"/>

	<!-- Style css-->
	<link href="assets/css/style.css" rel="stylesheet">
	<link href="assets/css/plugins.css" rel="stylesheet">

	<link rel="stylesheet" href="swal/sweetalert2.min.css">

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

		<!-- Main Content-->
		<div class="main-content side-content pt-0">
			<div class="main-container container-fluid">
				<div class="inner-body">

					<!-- Page Header -->
					<div class="page-header">
						<div>
							<h2 class="main-content-title tx-24 mg-b-5">Payments</h2>
							<ol class="breadcrumb">
							
								<li class="breadcrumb-item active" aria-current="page"> Approve Payments</li>
							</ol>
						</div>
						<div class="d-flex">
							
						</div>
					</div>
					<!-- End Page Header -->


					

					
					<!-- Row -->
					<div class="row row-sm mt-3 ">
						<div class="col-lg-12">
							<div class="card custom-card overflow-hidden">
								<div class="card-body">
									<div>
									
									<?php

											if (isset($_GET['pageno'])) {
												$pageno = $_GET['pageno'];
											} else {
												$pageno = 1;
											}
											$no_of_records_per_page = 100;
											$offset = ($pageno-1) * $no_of_records_per_page;



											$total_pages_sql = "SELECT COUNT(*) FROM users";
											$result = mysqli_query($mysqli,$total_pages_sql);
											$total_rows = mysqli_fetch_array($result)[0];
											$total_pages = ceil($total_rows / $no_of_records_per_page);



                        			?>



                                <ul class="pagination">
                                    <li><a class="btn btn-primary m-2" style="color:white" href="?pageno=1">First</a>
                                    </li>
                                    <li class="<?php if($pageno <= 1){ echo 'disabled'; } ?>">
                                        <a class="btn btn-primary m-2" style="color:white"
                                            href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pageno=".($pageno - 1); } ?>">Prev</a>
                                    </li>
                                    <li class="<?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
                                        <a class="btn btn-primary m-2" style="color:white"
                                            href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pageno=".($pageno + 1); } ?>">Next</a>
                                    </li>
                                    <li><a class="btn btn-primary m-2" style="color:white"
                                            href="?pageno=<?php echo $total_pages; ?>">Last</a></li>
                                </ul>
										
									</div>
									<div class="table-responsive">
										<table id="file-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
											<thead class="">
												<tr>
                                                            <th>S/N</th>
                                                            <th>Portfolio</th> 
                                                            <th>Email</th>
                                                            <th>Invoice</th>
                                                            <th>Full-name</th>
                                                            <th>Currency</th>
                                                             <th>Date</th>
                                                           
                                                            <th>Amount </th>
                                                            <th>Daily ROI </th>
                                                            <th>Payout Method </th>
                                                            
                                                            <th>Wallet Address</th>
                                                           
                                                            <th>Type of Payment</th>
                                                            <th>Action</th>
												</tr>
											</thead>
											<tbody>


											
                                            <?php
                                        //start the loop for see all users
                                        $get_funds = mysqli_query($mysqli,"SELECT * FROM pending WHERE status=0  ORDER BY id DESC LIMIT $offset, $no_of_records_per_page");
                                            $i=0;
                                            while($row= mysqli_fetch_assoc($get_funds)){
                                                $i++;

                                                

                                                 $getuser= mysqli_query($mysqli,"SELECT * FROM users WHERE id='".$row['userid']."'");
                                                $user = mysqli_fetch_assoc($getuser);
                                            ?>



                                            <tr>
                                                <td><?php echo $i; ?></td>

                                                <td><?php echo $row['name']; ?></td>
                                                <td><?php echo $user['email']; ?></td>
                                                <td><?php echo $row['chargeid']; ?></td>
                                                <td><?php echo $user['firstname']." ".$user['secondname']." ".$user['lastname']; ?>
                                                </td>
                                                <td><?php echo $row['currency']; ?></td>
                                                 <td><?php echo $row['date']; ?></td>
                                               
                                                <td>$<?php echo $row['amount']; ?></td>
                                                <td>$<?php echo $row['daily_roi']; ?></td>
                                                <td><?php if($row['payout']==1){
                                                                    echo "Daily Payout";
                                                                }elseif($row['payout']==5){
                                                                      echo "6 Months";
                                                                }elseif($row['payout']==6){
                                                                      echo "7  Months";
                                                                }elseif($row['payout']==7){
                                                                      echo "8  Months";
                                                                }elseif($row['payout']==8){
                                                                    echo "9  Months";
                                                              } ?> </td>
                                              
                                                <td><?php echo $row['wallet']; ?></td>
                                             

                                                <td><?php if($row['reinvest'] ==0){
                                                   echo "<span class='badge bg-success' >Fresh Investment </span>"; 
                                                }else{
                                                     echo "<span class='badge bg-info' >Re-investment</span>";
                                                   
                                                } ?></td>

                                                <td><?php if($row['status'] ==0){
                                                   echo " <button type='button' onclick="."document.getElementById('form-approve-".$row['id']."').submit()"." class='btn btn-success'>Approve</button>";


                                                   echo " <button type='button' onclick="."document.getElementById('form-delete-".$row['id']."').submit()"." class='btn btn-danger'>Delete</button>";
                                                }else{
                                                   
                                                    
                                                } ?></td>
                                            </tr>


                                                <div class="modal">
                                                            <div class="modal-content">
                                            <form method="POST" id="form-approve-<?php echo $row['id']; ?>">

                                                            <input type="hidden" name="id"
                                                                value="<?php echo $row['id']; ?>" />
                                                            <input type="hidden" name="approve-payment" />

                                                        </form>

                                                        <form method="POST" id="form-delete-<?php echo $row['id']; ?>">
                                                        <input type="hidden" value="<?php echo $row['id']; ?>"
                                                            name="id" />
                                                      
                                                        <br />
                                                       
                                                        <button type="submit" name="delete"
                                                            class="btn btn-danger ">Delete
                                                            Transaction</button>
                                                      
                                                    </form>
                                            </div>
                                            </div>





                                            <?php

                                            }

                                             ?>


												
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
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

	<!-- Internal Data Table js -->
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

	<!-- Perfect-scrollbar js -->
	<script src="assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
	<script src="assets/plugins/perfect-scrollbar/pscroll1.js"></script>

	<!-- Sidemenu js -->
	<script src="assets/plugins/sidemenu/sidemenu.js"></script>

	<!-- Sidebar js -->
	<script src="assets/plugins/sidebar/sidebar.js"></script>

	<!-- Color Theme js -->
	<script src="assets/js/themeColors.js"></script>

	<!-- Sticky js -->
	<script src="assets/js/sticky.js"></script>

	<!-- swither styles js -->
	<script src="assets/js/swither-styles.js"></script>

	<!-- Custom js -->
	<script src="assets/js/custom.js"></script>


	<script src="swal/sweetalert2.min.js"></script>



    <?php

    include_once("../email-handler.php");


if(isset($_POST['approve-payment'])){
//start of approval

$invoiceid = $_POST['id'];
//echo $wallet;


//get transaction from pending
$get = mysqli_query($mysqli,"SELECT * FROM pending WHERE id = '$invoiceid' ");
$row = mysqli_fetch_assoc($get);


if(mysqli_num_rows($get) >0 ){


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
    
    
    
    
        
    ?>
    <script>
    
      
    
    
      Swal.fire({
      icon: 'success',
      title: 'Payment Approved Successful',
      text: 'Porfolio Activated Successfully'
    })
    
        setTimeout(() => {
            location=location;
        }, 3000);
    
    </script>
    
    <?php
    
    
    
    }
    
    







//end of approval
}















if(isset($_POST['delete'])){
    // to Approve person account
  
    $id =  mysqli_real_escape_string($mysqli,$_POST['id']);
    
    $disbale = mysqli_query($mysqli,"DELETE FROM pending WHERE id='$id'  ");
    
    if($disbale){
    
        ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Payment Transaction Deleted',
    text: 'Transaction has been deleted successfully.'
});

setTimeout(() => {
    location = location;
}, 3000);
</script>

<?php
    
    
    }
    
    
    
    }











?>

</body>

</html>