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
	<title>Referral Commsion</title>

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
							<h2 class="main-content-title tx-24 mg-b-5">Referral Commsion</h2>
							<ol class="breadcrumb">
							
								<li class="breadcrumb-item active" aria-current="page"> Commission</li>
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
									

								<form method="POST" enctype="multipart/form-data">


<div class="form-group col s12">
		<label>Downline Account</label>
		<select    class="form-control show-tick ms select2" data-placeholder="Select"  name="userid" id="userid">
			<option value=""  >Select Account</option>
			<?php  $getaccount = mysqli_query($mysqli,"SELECT id, email FROM `users` WHERE status=1 "); 
			while($row =mysqli_fetch_assoc($getaccount)){
			?>
			<option value="<?php echo $row['id']; ?>"><?php echo $row['email']; ?></option>
			<?php } ?>
			
		</select>

	</div>



	<div class="form-group col s12">
		<label for="phone">Amount </label>
		<input type="number" name="amount" class="form-control" autofocus
			placeholder="Enter amount"  required>

	</div>

	


   




	<div class="form-group">
		<button type="submit" class="btn btn-info" value="" name="create">Add Commssion to 3 Levels
			</button>
	</div>


</form>








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


	<!-- Select2 js-->
	<script src="assets/plugins/select2/js/select2.min.js"></script>

	<script src="assets/js/select2.js"></script>

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
//when click to creat new package
if(isset($_POST['create'])){
//retrive inputs


$amount =  mysqli_real_escape_string($mysqli,$_POST['amount']);




$userid = mysqli_real_escape_string($mysqli,$_POST['userid']);





$getuser = mysqli_query($mysqli,"SELECT * FROM  users WHERE id='$userid'");
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
Swal.fire({
    icon: 'success',
    title: 'Commsion  Added Successfully',
    text: '3 Level Commsion Added'
});

setTimeout(() => {
    location = location;
}, 3000);
</script>

<?php






}
//end of creating a new package










?>





</body>

</html>