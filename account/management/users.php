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
<?php
//to login into a user account
if(isset($_POST['user-login'])){

    $id = $_POST['id'];

    $_SESSION['id'] = $id;

    ?>
<script>
window.open('../dashboard', '_blank');
</script>

<?php

}


?>
<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">


	

	<!-- Favicon -->
	<link rel="icon" href="img/icon.png" type="image/x-icon"/>

	<!-- Title -->
	<title>Users</title>

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
							<h2 class="main-content-title tx-24 mg-b-5">Users</h2>
							<ol class="breadcrumb">
							
								<li class="breadcrumb-item active" aria-current="page"> All Users</li>
							</ol>
						</div>
						<div class="d-flex">
							
						</div>
					</div>
					<!-- End Page Header -->


					<div class="col-lg-12 col-md-12 col-sm-12 mt-3">
                            <center>

                                <form id="navbar-search"  class="navbar-form search-form row">
									<div  class="col-6">
                                    <input required class="form-control" name="metadata" placeholder=" Search User..."
                                        type="text">
									</div>
									<div  class="col-2">
                                    <button type="submit" name="trigggerseach" class="btn btn-default btn-primary"><i
                                            class="icon-magnifier"></i></button>
											</div>
                                </form>

                            </center>

                    </div>

					
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
											$no_of_records_per_page = 30;
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
													<th>Fullname</th>
													<th>Email</th>
													<th>Phone Number</th>
													<th>Referred By</th>
													<th>Referral ID</th>
													<th>Wallet</th>
													<th>Status</th>

												
												
												</tr>
											</thead>
											<tbody>


												
											
                                            <?php



												if(isset($_GET['metadata'])){
													
													$data = $_GET['metadata'];

													//start the loop for see all users
													$get_users = mysqli_query($mysqli,"SELECT * FROM users WHERE  (email LIKE '%".$data."%' OR firstname LIKE '%".$data."%' )");

												}else{

													//start the loop for see all users
													$get_users = mysqli_query($mysqli,"SELECT * FROM users ORDER BY id DESC LIMIT $offset, $no_of_records_per_page");

												}




                                       

												$i=0;
												while($row= mysqli_fetch_assoc($get_users)){
													$i++;
	
													
	
													$getrefer = mysqli_query($mysqli,"SELECT * FROM users WHERE referal_link='".$row['referred']."' ");
													$rr = mysqli_fetch_assoc($getrefer);
												?>


<tr>
                                                <td><?php echo $row['firstname']." ".$row['lastname']; ?>
                                                </td>
                                                <td><?php echo $row['email']; ?></td>
                                                <td><?php echo $row['phone']; ?></td>
                                                <td><?php if(mysqli_num_rows($getrefer)>0){  echo $rr['email']."(".$rr['firstname'].")"; } ?>
                                                </td>
                                                <td><?php echo $row['referal_link']; ?></td>
                                                <td>$<?php echo $row['wallet']; ?></td>
                                                <td><?php if($row['status'] ==0){
                                                   echo '<span class=" badge badge-danger">Pending</span>'; 
                                                }else{
                                                     echo '<span class=" badge badge-success">Verified</span>';
                                                   
                                                } ?></td>
                                              






                                            </tr>













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


if(isset($_POST['approve-user'])){

// to Approve person account
$email = mysqli_real_escape_string($mysqli,$_POST['email']);
$id =  mysqli_real_escape_string($mysqli,$_POST['id']);

$approve = mysqli_query($mysqli,"UPDATE users SET status=1 WHERE id='$id' and email='$email' ");
    
if($approve){

        $pick = mysqli_query($mysqli,"SELECT * FROM users WHERE id='$id' and email='$email' ");
        $r = mysqli_fetch_assoc($pick);
		$name = $r['firstname']." ".$r['lastname'];
		//send the welcome email with verification link here
		//start email sending



        




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
                    "Email": "'.$email.'",
                    "Name": ""
                }
            ],
            
            "Subject": "Welcome to  Quantum Scalp",
            "TextPart": "",
            "HTMLPart": "<table align=\"center\" style=\"box-sizing:border-box;margin:0;padding:0;width:100%;height:100%;word-break:break-word;background-color:#efefef\"><tbody><tr><td align=\"center\" style=\"box-sizing:border-box;margin:0 auto;padding:0;vertical-align:top\" valign=\"top\"><table><tbody><tr><td width=\"600\" style=\"box-sizing:border-box;margin:0 auto;padding:0;vertical-align:top;font-family:&quot;display:block!important;max-width:600px!important\" valign=\"top\"><table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"box-sizing:border-box;margin:0;padding:0;font-family:&quot\"><tbody style=\"font-family:&quot\"><tr style=\"height:50px;font-family:&quot\"><td style=\"box-sizing:border-box;margin:0 auto;padding:8px;text-align:center;vertical-align:top;font-family:&quot\" align=\"center\" valign=\"top\"><div style=\"font-family:&quot\"><img src=\"https://quantumscalp.io/account/img/logo.png\" width=\"120px\" alt=\"Quantum Scalp\" style=\"font-family:&quot\"></div></td></tr><tr style=\"font-family:&quot\"><td style=\"box-sizing:border-box;margin:0 auto;vertical-align:top;font-family:&quot\" valign=\"top\"><table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"font-family:&quot\"><tbody style=\"font-family:&quot\"><tr style=\"font-family:&quot\"><td style=\"box-sizing:border-box;font-size:16px;line-height:1.7;margin:0 auto;padding:0;vertical-align:top;font-family:&quot\" valign=\"top\"><div style=\"display:block;border-radius:0;padding:20px;width:500px;margin:30px auto;font-family:&quot\"><h1 style=\"text-align:center;font-size:24px;font-weight:700;font-family:sans-serif;padding:5px;margin:0;color:#000\">Welcome to Quantum Scalp</h1><p style=\"margin:0;font-size:16px;padding:5px;font-family:&quot\">Hello <a style=\"font-family:&quot\"></a></p><p style=\"margin:0;padding:5px;font-size:16px;font-family:&quot\"> Thanks for making us a part of your financial objectives.<br /> We work using prompt services and powerful investing tools, from market tested investment strategies to simple and intuitive investment tools &ndash; you will find everything at Quantum Scalp. With our broad reach in the Financial market be rest assured that you will gain a very significant change in your Financial outlook.<br><br>  <b style=\"font-family:&quot\"></b></p><div style=\"display:block;font-family:&quot\"><div align=\"center\" style=\"margin:0 20px;font-family:&quot\"><a href=\"https://quantumscalp.com/account/\" style=\"width:270px;border-radius:4px;box-sizing:border-box;display:block;font-weight:300;line-height:2;margin-top:10px;padding:10px 15px;text-align:center;text-decoration:none;font-family:&quot;background-color:#000;color:#fff\" target=\"_blank\">Sign In</a></div></div><p style=\"font-size:14px;padding:5px;text-align:left;font-family:&quot\"><b style=\"font-family:&quot\">Thanks ,</b><br>Quantum Scalp Team</p></div></td></tr><tr style=\"margin:20px 0;font-family:&quot\"><td style=\"box-sizing:border-box;padding:0;vertical-align:top;font-family:&quot\" valign=\"top\"><p style=\"font-size:10px;padding:20px;text-align:center;font-family:&quot\"></p></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table><img src=\"\" style=\"width:1px;height:1px\" alt=\"\"><div style=\"text-align:center;padding-top:10px;padding-bottom:10px;font-size:8pt;font-family:sans-serif;background-color:#fff\"><a href=\"\" style=\"text-align:center;text-decoration:none;font-family:sans-serif;color:#666\" target=\"_blank\">UNSUBSCRIBE</a></div>",
        
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














    ?>
    <script>
    Swal.fire({
        icon: 'success',
        title: 'Approval Successful',
        text: 'Account with <?php echo $email; ?>, has been approved successfully.'
    });


    setTimeout(() => {
        location = location;
    }, 3000);
    </script>

    <?php


}



}


?>






<?php




if(isset($_POST['approve-kyc'])){
// to Approve person account
$email = mysqli_real_escape_string($mysqli,$_POST['email']);
$id =  mysqli_real_escape_string($mysqli,$_POST['id']);

$approve = mysqli_query($mysqli,"UPDATE users SET kycstatus=1 WHERE id='$id' and email='$email' ");
    
if($approve){

        $pick = mysqli_query($mysqli,"SELECT * FROM users WHERE id='$id' and email='$email' ");
        $r = mysqli_fetch_assoc($pick);
    $name = $r['firstname']." ".$r['lastname'];
//send the welcome email with verification link here
//start email sending









    ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Kyc Approval Successful',
    text: 'Account with <?php echo $email; ?>, has been approved successfully'
});


setTimeout(() => {
    location = location;
}, 3000);
</script>

<?php


}



}



if(isset($_POST['delete'])){
    // to Approve person account
  
    $id =  mysqli_real_escape_string($mysqli,$_POST['id']);
    
    $disbale = mysqli_query($mysqli,"DELETE FROM users WHERE id='$id'  ");
    
    if($disbale){
    
        ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Account Deleted',
    text: 'Account  has been deleted successfully.'
});

setTimeout(() => {
    location = location;
}, 3000);
</script>

<?php
    
    
    }
    
    
    
    }



    


    if(isset($_POST['activatehigher'])){
        // to Approve person account
      
        $id =  mysqli_real_escape_string($mysqli,$_POST['id']);
        
        $disbale = mysqli_query($mysqli,"UPDATE users SET higherbonus=1 WHERE id='$id'  ");
        
        if($disbale){
        
            ?>
    <script>
    Swal.fire({
        icon: 'success',
        title: 'Higher ROI Bonus Activated ',
        text: 'Account  has been Activated for higher bonus .'
    });
    
    setTimeout(() => {
        location = location;
    }, 3000);
    </script>
    
    <?php
        
        
        }
        
        
        
        }




if(isset($_POST['verify'])){
    // to Approve person account
  
    $id =  mysqli_real_escape_string($mysqli,$_POST['id']);
    
    $disbale = mysqli_query($mysqli,"UPDATE users SET userstatus=1 WHERE id='$id'  ");
    
    if($disbale){
    
        ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Account Verifed',
    text: 'Account  has been verified successfully.'
});

setTimeout(() => {
    location = location;
}, 3000);
</script>

<?php
    
    
    }
    
    
    
    }



    



if(isset($_POST['updateprofile'])){
    // to Approve person account
  
    $id =  mysqli_real_escape_string($mysqli,$_POST['id']);
    $firstname =  mysqli_real_escape_string($mysqli,$_POST['firstname']);
    $lastname =  mysqli_real_escape_string($mysqli,$_POST['lastname']);
    $email =  mysqli_real_escape_string($mysqli,$_POST['email']);




    $disbale = mysqli_query($mysqli,"UPDATE users SET firstname='$firstname', lastname='$lastname', email='$email' WHERE id='$id'  ");
    
    if($disbale){
    
        ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Account Updated',
    text: 'Account  has been Updated successfully.'
});

setTimeout(() => {
    location = location;
}, 3000);
</script>

<?php
    
    
    }
    
    
    
    }







//disbae the user account
if(isset($_POST['disable-user'])){
// to Approve person account
$email = mysqli_real_escape_string($mysqli,$_POST['email']);
$id =  mysqli_real_escape_string($mysqli,$_POST['id']);
$note =  mysqli_real_escape_string($mysqli,$_POST['note']);

$disbale = mysqli_query($mysqli,"UPDATE users SET `status`=0 WHERE id='$id' and email='$email' ");

if($disbale){

    ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Account Disabled',
    text: 'Account With <?php echo $email; ?>, has been disabled successfully'
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