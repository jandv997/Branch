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
	<title>Email</title>

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
							<h2 class="main-content-title tx-24 mg-b-5">Emails</h2>
							<ol class="breadcrumb">
							
								<li class="breadcrumb-item active" aria-current="page"> Send Emails</li>
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





                               

<div class="form-group">
<label>Select User</label>
		<select multiple name="email[]" class="form-control">
			<option value="all" > All (everyone)</option>
			<?php
	$getuser = mysqli_query($mysqli,"SELECT * FROM users");

	while($row = mysqli_fetch_assoc($getuser)){
	?>
	<option value="<?php echo $row['email']; ?>"><?php echo $row['firstname']."(".$row['email'].")"; ?></option>
	
	<?php
	}
	?>
		</select>
		
	</div>


	<div class="form-group">
	<label>Select Email</label>
		<select class="form-control"  name="support_email">
			<option value="info@lupagroup.com" >info@lupagroup.com</option>
		
		   
		</select>
	  
	</div>


	<div class="form-group">
	<label class="active" for="first_name2">Subject</label>
		<input id="first_name2" name="title" type="text" class="form-control">
	   
	</div>



	<div class="form-group">
		<textarea id="message" placeholder="Enter Message" name="message" class="form-control"></textarea>
		<label for="textarea2">Enter Message</label>
	</div>
	






<div class="input-field col s12">
<button type="submit"
	class="btn btn-info"
	 name="send">Send Email</button>
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

if(isset($_POST['send'])){

    $email = $_POST['email'];
	$support_email = $_POST['support_email'];
    $title = $_POST['title'];
    $message = $_POST['message'];

    echo $email;

    //main code now
foreach($_POST['email'] as $oneemail) //loop over values
{

    if($oneemail != "all"){


$getuser = mysqli_query($mysqli,"SELECT * FROM users WHERE email='$oneemail'");
        $g = mysqli_fetch_assoc($getuser);
//start email sending
          
	
			//construct and structure the way the welcome message will look
	//include the structure
	include_once("email_structure2.php");
		
		$actualmessage = welcome_mail($g['firstname'], $message, $title);
		
		//echo $actualmessage;
		
		
		
		//next is the smtp message attributes and sending the message den insert message into database
	
	include_once('mailer/class.phpmailer.php');
//include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

$mail             = new PHPMailer();


$mail->IsSMTP(); // telling the class to use SMTP
$mail->Host       = "lupagroup.com"; // SMTP server
$mail->SMTPDebug  = 1;                     // enables SMTP debug information (for testing)
                                           // 1 = errors and messages
                                           // 2 = messages only
$mail->SMTPAuth   = true;                  // enable SMTP authentication

$mail->Port       = 465;                    // set the SMTP port for the GMAIL server
$mail->Username   = "info@lupagroup.com"; // SMTP account username
$mail->Password   = "Tg(sxWq@48L=";        // SMTP account password

$mail->SetFrom('info@lupagroup.com', "lupagroup");

$mail->AddReplyTo('info@lupagroup.com', "lupagroup");

$mail->IsHTML =(true);

$mail->Subject    = $title;

$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);

$mail->SMTPSecure = 'ssl';

$mail->MsgHTML($actualmessage);

//destination for email.

$mail->AddAddress($oneemail, "lupagroup");


if(!$mail->Send()) {
  $_SESSION['MSG']="Mailer Error: " . $mail->ErrorInfo;
} else {
	
	$_SESSION['update_msg'] = "Email Sent";
	
}
	
	
	
	
		
	
	
}else{

//get all user 
        $getuser = mysqli_query($mysqli,"SELECT * FROM users");

        while($g=mysqli_fetch_assoc($getuser)){

          
	
	//start email sending
          
	
			//construct and structure the way the welcome message will look
	//include the structure
	include_once("email_structure2.php");
		
		$actualmessage = welcome_mail($g['firstname'], $message, $title);
		
		//echo $actualmessage;
		
		
		
		//next is the smtp message attributes and sending the message den insert message into database
	
	include_once('mailer/class.phpmailer.php');
//include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

$mail             = new PHPMailer();


$mail->IsSMTP(); // telling the class to use SMTP
$mail->Host       = "lupagroup.com"; // SMTP server
$mail->SMTPDebug  = 1;                     // enables SMTP debug information (for testing)
                                           // 1 = errors and messages
                                           // 2 = messages only
$mail->SMTPAuth   = true;                  // enable SMTP authentication

$mail->Port       = 465;                    // set the SMTP port for the GMAIL server
$mail->Username   = "info@lupagroup.com"; // SMTP account username
$mail->Password   = "Tg(sxWq@48L=";        // SMTP account password

$mail->SetFrom('info@lupagroup.com', "lupagroup");

$mail->AddReplyTo('info@lupagroup.com', "lupagroup");

$mail->IsHTML =(true);

$mail->Subject    = $title;

$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);

$mail->SMTPSecure = 'ssl';

$mail->MsgHTML($actualmessage);

//destination for email.

$mail->AddAddress($g['email'], "lupagroup");


if(!$mail->Send()) {
  $_SESSION['MSG']="Mailer Error: " . $mail->ErrorInfo;
} else {
	
	$_SESSION['update_msg'] = "Email Sent";
	
}
	
	
	
	

        }


}


//end of main
}
    


     ?>
<script>


Swal.fire({
  icon: 'success',
  title: 'Email Successfull',
  text: 'Email has been sent!'
})



</script>

<?php


    


}



?>




</body>

</html>