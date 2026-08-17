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
	<title> Account Profile | Quantum Scalp </title>

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
						<span class="main-content-title mg-b-0 mg-b-lg-1">PROFILE</span>
					</div>
					<div class="justify-content-center mt-2">
						<ol class="breadcrumb">
							<li class="breadcrumb-item tx-15"><a href="javascript:void(0);">Account</a></li>
							<li class="breadcrumb-item active" aria-current="page">Profile</li>
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
										<span class="bg-success text-white wd-1 ht-1 rounded-pill profile-online"></span>
									</span>

									<a href="javascript:;" class="btn btn-primary" id="select-pic"><i class="mdi mdi-pencil"></i> Select Profile</a>

									<button class="btn ripple btn-primary"  id="update-pic">
										<i class="fa fa-plus me-1"></i>
													<span>Upload</span>
									</button>
																		
								</div>
							


								<div class="my-md-auto mt-4 prof-details">
									<h4 class="font-weight-semibold ms-md-4 ms-0 mb-1 pb-0"><?php echo $rows['firstname']." ".$rows['lastname']; ?></h4>
									<p class="tx-13 text-muted ms-md-4 ms-0 mb-2 pb-2 ">
										
										<span class="me-3"><i class="fa fa-taxi me-2"></i><?php echo $rows['address']; ?></span>
										<span><i class="far fa-flag me-2"></i></span>
									</p>
									<p class="text-muted ms-md-4 ms-0 mb-2"><span><i
												class="fa fa-phone me-2"></i></span><span
											class="font-weight-semibold me-2">Phone:</span><span><?php echo $rows['phone']; ?></span>
									</p>
									<p class="text-muted ms-md-4 ms-0 mb-2"><span><i
												class="fa fa-envelope me-2"></i></span><span
											class="font-weight-semibold me-2">Email:</span><span><?php echo $rows['email']; ?></span>
									</p>
									
								</div>
							</div>
							<div class="card-footer py-0">
								<div class="profile-tab tab-menu-heading border-bottom-0">
									<nav class="nav main-nav-line p-0 tabs-menu profile-nav-line border-0 br-5 mb-0	">
										
										<a class="nav-link mb-2 mt-2 active" data-bs-toggle="tab" href="#edit">Edit Profile</a>
										
									</nav>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- Row -->
				<div class="row row-sm">
					<div class="col-lg-12 col-md-12">
						<div class="custom-card main-content-body-profile">
							<div class="tab-content">
							
							<div class="main-content-body tab-pane border-top-0 active" id="edit">
									<div class="card">
										<div class="card-body border-0">
											<div class="mb-4 main-content-label">Personal Information</div>
											<div class="mb-4 main-content-label">Name</div>

											<form method="POST">
                                                    <div class="row clearfix">
                                                        <div class="col-lg-12 col-md-12">
                                                            <div class="form-group">
                                                                <input type="text"
                                                                    value="<?php echo $rows['firstname']; ?>"
                                                                    name="firstname" class="form-control"
                                                                    placeholder="First Name">
                                                            </div>
                                                            <div class="form-group">
                                                                <input type="text"
                                                                    value="<?php echo $rows['lastname']; ?>"
                                                                    name="lastname" class="form-control"
                                                                    placeholder="Last Name">
                                                            </div>


                                                        </div>
                                                        <div class="col-lg-12 col-md-12">
                                                            <div class="form-group">
                                                                <input type="email"
                                                                    value="<?php echo $rows['email']; ?>" name="email"
                                                                    disabled class="form-control"
                                                                    placeholder="Email Address ">
                                                            </div>
                                                            <div class="form-group">
                                                                <input type="number" name="phone"
                                                                    value="<?php echo $rows['phone']; ?>"
                                                                    class="form-control" placeholder="Phone Number ">
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <button type="submit" name="update-info"
                                                        class="btn btn-primary">Update</button> &nbsp;&nbsp;
                                                </form>


												<hr/><br/>
												<div class="mb-4 main-content-label">Contact Information</div>


											<form method="POST">
                                                    <div class="row clearfix">

                                                        <div class="col-lg-12 col-md-12">
                                                            <h6>Contact Information</h6>
                                                            <div class="form-group">
                                                                <input type="text"
                                                                    value="<?php echo $rows['address']; ?>"
                                                                    name="address" class="form-control"
                                                                    placeholder="Address">
                                                            </div>
                                                            <div class="form-group">
                                                                <input type="text" name="city"
                                                                    value="<?php echo $rows['city']; ?>"
                                                                    class="form-control" placeholder="City">
                                                            </div>
                                                            <div class="form-group">
                                                                <input type="text" name="state"
                                                                    value="<?php echo $rows['state']; ?>"
                                                                    class="form-control" placeholder="State ">
                                                            </div>
                                                            <div class="form-group">
                                                                <input type="text" name="zip"
                                                                    value="<?php echo $rows['zip']; ?>"
                                                                    class="form-control" placeholder="Zip Code ">
                                                            </div>
                                                        </div>


                                                    </div>
                                                    <button type="submit" name="update-contact"
                                                        class="btn btn-primary">Update</button> &nbsp;&nbsp;
                                                </form>




										
										</div>
									</div>
							</div>
								
								
								
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




    <script>
    $('#content >img, #select-pic').click(function() {
        $('#profile').trigger('click');
    });

    $('#update-pic').click(function() {
        $('#profile-pic').submit();
    });
    </script>

    <script>
    //************************************************ */
    //get the content of image
    var input = document.querySelector('input[type=file]');

    input.onchange = function() {
        var file = input.files[0];

        //trying to validate image before upload


        img = new Image();
        var imgwidth = 0;
        var imgheight = 0;

        img.src = URL.createObjectURL(file);


        //function to prepare image
        drawOnCanvas(file);

        //function to display image
        displayAsImage(file);






    };




    function drawOnCanvas(file) {
        var reader = new FileReader();

        reader.onload = function(e) {

            var dataURL = e.target.result,
                c = document.querySelector('canvas'),
                ctx = c.getContext('2d'),
                img = new Image();

            img.onload = function() {

                c.width = img.width;
                c.height = img.height;
                ctx.drawImage(img, 0, 0);

            };

            img.src = dataURL;


        };


        reader.readAsDataURL(file);


    }


    function displayAsImage(file) {
        var imgURL = URL.createObjectURL(file),
            img = document.createElement('img');

        img.onload = function() {

            URL.revokeObjectURL(imgURL);
        };

        img.src = imgURL;
        //img.className = "img-fluid";
        //img.style = "width:100%";


        //adding the image into content for preview
        document.getElementById('content').innerHTML = "";

        document.getElementById('content').append(img);

		document.getElementById('content').innerHTML('<a href="javascript:;" id="select-pic"><i class="mdi mdi-pencil"></i></a>');

		

    }
    </script>



    <?php

//when upload of new profile pic
if(isset($_POST['upload-pic'])){

$target_locate = "img/profile/";
	
//the image with full path
$pic = $target_locate.basename($_FILES["profile"]["name"]);

if(move_uploaded_file($_FILES["profile"]["tmp_name"],$pic)){

$update = mysqli_query($mysqli,"UPDATE users SET img='$pic'  WHERE email='".$rows['email']."' ");

if($update){

    ?>
    <script>
  
	
	notif({
		msg: "<b>Profile Picture Updated</b><br/> Your profile picture has been updated.",
		width: 250,
		position: "center",
		type: "success"
	});

    setTimeout(() => {
        location = 'profile';
    }, 3000);
    </script>

    <?php

}


}


}
//end of uploading picture





//start of personal info update
if(isset($_POST['update-info'])){


    $firstname =  mysqli_real_escape_string($mysqli,$_POST['firstname']);
    
    $lastname =  mysqli_real_escape_string($mysqli,$_POST['lastname']);
    $phone = mysqli_real_escape_string($mysqli,$_POST['phone']);
    
    
    
    //run update
    
    $updated = mysqli_query($mysqli,"UPDATE users SET firstname='$firstname', lastname='$lastname', phone='$phone' WHERE email='".$rows['email']."' ");
    
    if($updated){
    
    
    ?>
    <script>
 

	notif({
		msg: "<b>Account Details Updated!</b><br/> Your personal details has been updated.",
		width: 250,
		position: "center",
		type: "success"
	});
    </script>

    <?php
    
    
    }
    
    
    
    
    }
    //end of updating info
    
    
    //start of contact update
    if(isset($_POST['update-contact'])){
    
    $address = mysqli_real_escape_string($mysqli,$_POST['address']);
    $city = mysqli_real_escape_string($mysqli,$_POST['city']);
    $state = mysqli_real_escape_string($mysqli,$_POST['state']);
    $zip = mysqli_real_escape_string($mysqli,$_POST['zip']);
    
    
    
    //run update
    
    $update = mysqli_query($mysqli,"UPDATE users SET address='$address', city='$city', state='$state', zip='$zip' WHERE email='".$rows['email']."' ");
    
    if($update){
    
    
    ?>
    <script>
  

	notif({
		msg: "<b>Contact Details Updated!</b><br/> Your contact details has been updated.",
		width: 250,
		position: "center",
		type: "success"
	});


    setTimeout(() => {
        location = 'profile';
    }, 3000);
    </script>

    </script>

    <?php
    
    
    }
    
    
    
    }
    //end of contact update
    
    
    


?>







</body>

</html>