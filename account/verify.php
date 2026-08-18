<?php
session_start();

include('connection.php');

?>
<?php
$authPageTitle = 'Quantum Scalp | Verify Account';
$authTitle = 'Verify your account';
$authSubtitle = 'Confirming your Q-Core email.';
$authNoLivechat = true;
include('inc/auth-head.php');
?>
<script src="//code.tidio.co/boh34gato9oarfy1efgvdwn7x1rfiex5.js" async></script>
<?php include('inc/auth-open.php'); ?>
<p class="text-muted">Please wait while we verify your email.</p>
<?php
include('inc/auth-close.php');
include('inc/auth-scripts.php');
?>
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
$stmt = $mysqli->prepare("SELECT id, firstname, lastname, email, userstatus, status FROM users WHERE email = ?");
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
    $_SESSION['waiting_email'] = $user['email'];
    $_SESSION['waiting_state'] = ((int) $user['status'] === 1) ? 'ready' : 'approval';

    ?>
    <script>
        notif({
            msg: "<b>Already Verified</b><br/>Your email is verified. Continue to your account status.",
            width: 300,
            position: "center",
            type: "info",
            fade: true
        });

        setTimeout(() => {
            location = 'waiting';
        }, 1800);
    </script>
    <?php

    exit;
}


// Update account
$update = $mysqli->prepare("UPDATE users SET userstatus = 1 WHERE email = ?");
$update->bind_param("s", $email);

if ($update->execute()) {

    $name = trim($user['firstname'] . " " . $user['lastname']);
    $_SESSION['waiting_email'] = $user['email'];
    $_SESSION['waiting_state'] = 'approval';

    ?>

    <script>

    notif({
        msg: "<b>Verification Successful</b><br/>Your email is verified. Your account is now awaiting admin approval.",
        width: 320,
        position: "center",
        type: "success",
        fade: true
    });

    setTimeout(() => {
        location = 'waiting';
    }, 1800);

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