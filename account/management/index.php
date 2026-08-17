<?php
session_start();


//check if session id is set if it is redirect to dashboard
if(isset($_SESSION['adminid']) ){
    
    header("location:dashboard");
}


include('connection.php');

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
    <title>Admin | Login</title>

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

</head>

<body class="main-body leftmenu ltr light-theme">

    <!-- Loader -->
    <div id="global-loader">
        <img src="assets/img/loader.svg" class="loader-img" alt="Loader">
    </div>
    <!-- End Loader -->

    <!-- Page -->
    <div class="page main-signin-wrapper">

        <!-- Row -->
        <div class="row signpages text-center">
            <div class="col-md-12">
                <div class="card border-0">
                    <div class="row row-sm">
                        <div class="col-lg-6 col-xl-6 col-xs-12 col-sm-12 login_form rounded-start-11">
                            <div class="container-fluid">
                                <div class="row row-sm">
                                    <div class="card-body mt-2 mb-2">
                                        <div class="mobilelogo">
                                            <img src="img/logo.png"
                                                class=" d-lg-none header-brand-img text-start float-start mb-4 dark-logo"
                                                alt="logo">
                                            <img src="img/logo.png"
                                                class=" d-lg-none header-brand-img text-start float-start mb-4 light-logo"
                                                alt="logo">
                                        </div>
                                        <div class="clearfix"></div>
                                        <form method="POST">


                                            <h2 class="text-start mb-2">Sign In</h2>

                                            <div class="panel desc-tabs border-0 p-0">
                                                <div class="tab-menu-heading">
                                                    <div class="tabs-menu ">
                                                        <ul class="nav panel-tabs">
                                                            <li class="">
                                                                <a href="#tab01" class="active"
                                                                    data-bs-toggle="tab">Email</a>
                                                            </li>

                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="panel-body tabs-menu-body mt-2">
                                                    <div class="tab-content">
                                                        <div class="tab-pane active" id="tab01">
                                                            <div class="form-group text-start">
                                                                <label class="tx-medium">Email</label>
                                                                <input class="form-control" name="email" required
                                                                    placeholder="Enter your email" type="email"
                                                                    autocomplete="email">
                                                            </div>
                                                            <div class="form-group text-start">
                                                                <label class="tx-medium">Password</label>
                                                                <input class="form-control border-end-0"
                                                                    placeholder="Enter your password" type="password"
                                                                    name="password" required adata-bs-toggle="password">
                                                            </div>
                                                            <button class="btn btn-primary btn-block" name="login"
                                                                type="submit">Sign In</button>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <div class="text-start mt-4 ms-0 mb-3">
                                            <div class="mb-1"><a href="forgot">Forgot password?</a></div>
                                            <div>Don't have an account? <a href="register">Register Here</a></div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xl-6 d-none d-lg-block text-center bg-primary details rounded-end-11">
                            <div class="mt-4 pt-4 p-2 pos-relative">
                                <img src="img/logo.png" class="header-brand-img mb-3 mt-3" alt="logo">
                                <div class="clearfix"></div>



                                <h2 class="mt-4 text-white tx-normal">Sign Into Account</h2>
                                <span class="tx-white-6 tx-13 mb-5 mt-xl-0"> &nbsp; &nbsp;</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Row -->

    </div>
    <!-- End Page -->

    <!-- Jquery js-->
    <script src="assets/plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap js-->
    <script src="assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

    <!-- Bootstrap Show Password js-->
    <script src="assets/js/bootstrap-show-password.min.js"></script>



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

    <!-- generate-otp js -->
    <script src="assets/js/generate-otp.js"></script>

    <!-- Perfect-scrollbar js -->
    <script src="assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>

    <!-- Select2 js-->
    <script src="assets/plugins/select2/js/select2.min.js"></script>

    <!-- Color Theme js -->
    <script src="assets/js/themeColors.js"></script>


    <script src="swal/sweetalert2.min.js"></script>


    <!-- swither styles js -->
    <script src="assets/js/swither-styles.js"></script>

    <!-- Custom js -->
    <script src="assets/js/custom.js"></script>




    <?php


if(isset($_POST['login'])){
//retrive the inut from user
$email = mysqli_real_escape_string($mysqli,$_POST['email']);
$password = mysqli_real_escape_string($mysqli,$_POST['password']);

$email = filter_var($email, FILTER_SANITIZE_EMAIL);

if (filter_var($email, FILTER_VALIDATE_EMAIL)){

//check if email exist aready
$check_email = mysqli_query($mysqli,"SELECT `id`, `password`, `status` FROM admins WHERE email='$email'");

$row = mysqli_fetch_assoc($check_email);

//if one it exist proceed to login
if(mysqli_num_rows($check_email) > 0){

    //check if password is correct
    if(password_verify($password, $row['password']) ){

       
            //check if admin has approved the account
            if($row['status'] !=0){

                
                    $_SESSION['adminid']=$row['id']; 
                     

                     ?>

                <script>
                location='dashboard';
                </script>

                <?php


            


            }else{
                //account not yet approved by admin

                ?>
<script>



  Swal.fire({
  icon: 'error',
  title: 'Unapproved Account',
  text: 'Your account has not been approved by Admin'
})


</script>

<?php  



            }




    }else{


?>
<script>



  Swal.fire({
  icon: 'error',
  title: 'Incorrect Password',
  text: 'The password supplied is wrong'
})

    

</script>

<?php




    }




}else{
//its zero email does not exit show error

?>
<script>


  Swal.fire({
  icon: 'error',
  title: 'Unregistered Email',
  text: 'This email is not yet registered with an account!'
})


</script>

<?php


}





}else{
    //its zero email does not exit show error
    
    ?>
     <script>
     
    
    
      Swal.fire({
      icon: 'error',
      title: 'Not a Valid Email Address',
      text: 'This is not a valid email Address!'
    })
     </script>
    
     <?php
    
    
    }
  



//
}


?>



</body>

</html>