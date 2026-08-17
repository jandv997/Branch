<?php
session_start();

include('connection.php');


//check if session id is set if it is redirect to login
if (!isset($_SESSION['id'])) {

    header("location:index");

} else {

    $get_user = mysqli_query($mysqli, "SELECT * FROM users WHERE id='" . $_SESSION['id'] . "' ");
    $rows = mysqli_fetch_assoc($get_user);
    if (isset($_SESSION['2fa'])) {

        if ($_SESSION['2fa'] == "no" and $row['2fa'] == 1) {
            header("location:index");
        }

        if ($_SESSION['2fa'] == "yes" and $row['2fa'] == 1) {
            header("location:dashboard");
        }


    }


}


?>
<?php
$authPageTitle = 'Quantum Scalp | Two-factor authentication';
$authTitle = 'Verify it\'s you';
$authSubtitle = 'Enter your authenticator code.';
$authNoLivechat = true;
include('inc/auth-head.php');
?>
<script src="//code.tidio.co/boh34gato9oarfy1efgvdwn7x1rfiex5.js" async></script>
<?php include('inc/auth-open.php'); ?>

<div class="text-center mb-4">
    <img alt="" class="avatar avatar-xxl rounded-circle" src="<?php echo $rows['img']; ?>" style="width:88px;height:88px;object-fit:cover;">
    <h5 class="mt-3 mb-1"><?php echo $rows['firstname']." ".$rows['lastname']; ?></h5>
    <p class="text-muted mb-0">Enter your authenticator code to continue.</p>
</div>
<form method="POST">
                                                            <div class="form-group">
                                                                <input class="form-control"
                                                                    placeholder="Enter your otp" type="text"  id="code" name="code"
                                                                    value="">
                                                            </div>
                                                            <button  name="validate"  type="submit" class="btn btn-primary btn-block">Unlock</button>
                                                        </form>
<?php
include('inc/auth-close.php');
include('inc/auth-scripts.php');
?>
<?php

if(isset($_POST['validate'])){
//retrive the entered code

$check_this_code = $_POST['code'];


require_once("google_authenticator/index.php"); 

$g = new \Google\Authenticator\GoogleAuthenticator();

$secret=$rows['2fa_key'];



if ($g->checkCode($secret, $check_this_code)) {
 

     $_SESSION['2fa']='yes';

      ?>

<script>
  location = 'dashboard';
</script>

<?php



} else {
 ?>
<script>
  



 

notif({
		msg: "<b>Invalid Code</b> <br/> The inputted authentication code is invalid!",
		width: 250,
		position: "center",
		type: "error",
		fade: true
	});

</script>

<?php


}





}


?>







</body>
</html>