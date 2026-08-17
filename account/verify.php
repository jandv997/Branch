<?php


include('connection.php');

?>
<!DOCTYPE html>
<html lang="en">
	<head>

		<meta charset="UTF-8">
		<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
	

		<!-- Title -->
		<title> Quantum Group | Verify Account </title>

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

		<!--- Animations css-->
		<link href="assets/css/animate.css" rel="stylesheet">

		<script src="//code.tidio.co/boh34gato9oarfy1efgvdwn7x1rfiex5.js" async></script>

	</head>
	<body class="ltr error-page1 bg-primary">

		<!-- Loader -->
		<div id="global-loader">
        <img src="img/favicon.png" width="50" class="loader-img" alt="Loader">
		</div>
		<!-- /Loader -->

		<div class="square-box">
			<div></div>
			<div></div>
			<div></div>
			<div></div>
			<div></div>
			<div></div>
			<div></div>
			<div></div>
			<div></div>
			<div></div>
			<div></div>
			<div></div>
			<div></div>
			<div></div>
			<div></div>
		</div>

		<div class="page" >
		
		</div>

		<!-- JQuery min js -->
		<script src="assets/plugins/jquery/jquery.min.js"></script>

		<!-- Bootstrap js -->
		<script src="assets/plugins/bootstrap/js/popper.min.js"></script>
		<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

		<!-- Moment js -->
		<script src="assets/plugins/moment/moment.js"></script>

		<!-- eva-icons js -->
		<script src="assets/js/eva-icons.min.js"></script>

		<!-- generate-otp js -->
		<script src="assets/js/generate-otp.js"></script>


		    <!--Internal  Notify js -->
    <script src="assets/plugins/notify/js/notifIt.js"></script>
    <script src="assets/plugins/notify/js/notifit-custom.js"></script>


		<!--Internal  Perfect-scrollbar js -->
		<script src="assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>

		<!-- Theme Color js -->
		<script src="assets/js/themecolor.js"></script>

		<!-- custom js -->
		<script src="assets/js/custom.js"></script>







			<?php


// Include database connection
require_once 'connection.php';   // Update this to your DB connection file



// Check if email parameter exists
if (!isset($_GET['email']) || empty(trim($_GET['email']))) {
    ?>
    <script>
        notif({
            msg: "<b>Invalid Verification Link</b><br/>The verification link is invalid or has expired.",
            width: 300,
            position: "center",
            type: "error",
            fade: true
        });

        setTimeout(() => {
            location = 'index';
        }, 3000);
    </script>
    <?php
    exit;
}

$email = trim($_GET['email']);
$email = str_replace(' ', '+', $email);

// Look up the user
$stmt = $mysqli->prepare("SELECT id, firstname, lastname, email, userstatus FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {

    ?>
    <script>
        notif({
            msg: "<b>User Not Found</b><br/>No account exists for this verification link.",
            width: 300,
            position: "center",
            type: "error",
            fade: true
        });

        setTimeout(() => {
            location = 'index';
        }, 3000);
    </script>
    <?php

    exit;
}

$user = $result->fetch_assoc();

$stmt->close();


// Already verified?
if ($user['userstatus'] == 1) {

    ?>
    <script>
        notif({
            msg: "<b>Already Verified</b><br/>Your email has already been verified. Please sign in.",
            width: 300,
            position: "center",
            type: "info",
            fade: true
        });

        setTimeout(() => {
            location = 'index';
        }, 3000);
    </script>
    <?php

    exit;
}


// Update account
$update = $mysqli->prepare("UPDATE users SET userstatus = 1 WHERE email = ?");
$update->bind_param("s", $email);

if ($update->execute()) {

    $name = trim($user['firstname'] . " " . $user['lastname']);

 

    ?>

    <script>

    notif({
        msg: "<b>Verification Successful</b><br/>Your email has been verified successfully. You can now sign in.",
        width: 320,
        position: "center",
        type: "success",
        fade: true
    });

    setTimeout(() => {
        location = 'index';
    }, 2500);

    </script>

    <?php

} else {

    error_log("Verification Update Error: " . $mysqli->error);

    ?>

    <script>

    notif({
        msg: "<b>Verification Failed</b><br/>Unable to verify your account. Please try again later.",
        width: 320,
        position: "center",
        type: "error",
        fade: true
    });

    setTimeout(() => {
        location = 'index';
    }, 3000);

    </script>

    <?php
}

$update->close();
$mysqli->close();
?>






	</body>
</html>