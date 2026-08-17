<?php
session_start();


//check if session id is set if it is redirect to dashboard
if(isset($_SESSION['id']) and (isset($_SESSION['2fa']) and $_SESSION['2fa'] == "yes")){
	
	header("location:dashboard");
}else{
     

    if(isset($_SESSION['2fa'])){

        if($_SESSION['2fa'] == "pending"){
        header("location:authenticate");
    }

    if($_SESSION['2fa'] == "no"){
        header("location:dashboard");
    }

    }

}


include('connection.php');

?>
<?php
$authPageTitle = 'Quantum Scalp | Sign Up';
$authTitle = 'Create your account';
$authSubtitle = 'Become a member and access Q-Core.';
include('inc/auth-head.php');
include('inc/auth-open.php');
$referQs = isset($_GET['refer']) ? ('?refer=' . htmlspecialchars($_GET['refer'], ENT_QUOTES)) : '';
if (isset($_GET['refer']) && $_GET['refer'] !== '') {
    echo '<div class="qs-ref-notice">You were invited with code <strong>' . htmlspecialchars($_GET['refer']) . '</strong></div>';
}
?>
<a class="qs-choice" href="individual<?php echo $referQs; ?>">
    <div class="qs-choice-icon"><i class="fe fe-user"></i></div>
    <h5>Individual</h5>
    <p>Personal Q-Core membership</p>
    <span class="btn btn-primary">Continue</span>
</a>
<a class="qs-choice" href="business<?php echo $referQs; ?>">
    <div class="qs-choice-icon"><i class="fe fe-briefcase"></i></div>
    <h5>Business</h5>
    <p>Company Q-Core membership</p>
    <span class="btn btn-primary">Continue</span>
</a>
<p class="qs-auth-foot">Already a member? <a href="index">Sign in</a></p>
<?php
include('inc/auth-close.php');
include('inc/auth-scripts.php');
?>
</body>
</html>
