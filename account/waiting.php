<?php
session_start();
include('connection.php');

if (isset($_SESSION['id']) && isset($_SESSION['2fa']) && $_SESSION['2fa'] === 'yes') {
    header('location:dashboard');
    exit;
}

$waitingEmail = '';
if (!empty($_SESSION['waiting_email'])) {
    $waitingEmail = trim($_SESSION['waiting_email']);
} elseif (!empty($_GET['email'])) {
    $waitingEmail = trim(str_replace(' ', '+', $_GET['email']));
}

$state = 'verify';
$displayName = '';
$maskedEmail = '';

if ($waitingEmail !== '') {
    $emailEsc = mysqli_real_escape_string($mysqli, $waitingEmail);
    $lookup = mysqli_query($mysqli, "SELECT firstname, lastname, email, userstatus, status FROM users WHERE email='$emailEsc' LIMIT 1");
    $user = $lookup ? mysqli_fetch_assoc($lookup) : null;
    if ($user) {
        $_SESSION['waiting_email'] = $user['email'];
        $displayName = trim($user['firstname'] . ' ' . $user['lastname']);
        $maskedEmail = $user['email'];
        if ((int) $user['userstatus'] !== 1) {
            $state = 'verify';
        } elseif ((int) $user['status'] !== 1) {
            $state = 'approval';
        } else {
            $state = 'ready';
        }
        $_SESSION['waiting_state'] = $state;
    } elseif (!empty($_SESSION['waiting_state'])) {
        $state = $_SESSION['waiting_state'];
        $maskedEmail = $waitingEmail;
    }
} elseif (!empty($_SESSION['waiting_state'])) {
    $state = $_SESSION['waiting_state'];
}

$copy = array(
    'verify' => array(
        'title' => 'Check your email',
        'subtitle' => 'Your account is waiting on email verification.',
        'body' => 'We sent a verification link to your inbox. Open that email and confirm your address to continue. After verification, an administrator still needs to approve your account before you can sign in.',
        'cta' => 'Back to sign in',
    ),
    'approval' => array(
        'title' => 'Awaiting approval',
        'subtitle' => 'Your email is verified. Your account is in review.',
        'body' => 'Thanks for confirming your email. A Quantum Scalp administrator will review your registration. You will be able to sign in as soon as the account is approved.',
        'cta' => 'Back to sign in',
    ),
    'ready' => array(
        'title' => 'Account approved',
        'subtitle' => 'Your Q-Core account is ready.',
        'body' => 'Your registration has been approved. Sign in to access your dashboard.',
        'cta' => 'Sign in',
    ),
);

if (!isset($copy[$state])) {
    $state = 'verify';
}

$authPageTitle = 'Quantum Scalp | Account Status';
$authTitle = $copy[$state]['title'];
$authSubtitle = $copy[$state]['subtitle'];
$authNoLivechat = true;
include('inc/auth-head.php');
include('inc/auth-open.php');
?>
<div class="qs-wait-steps" aria-hidden="true">
    <div class="qs-wait-step<?php echo $state === 'verify' ? ' is-active' : ' is-done'; ?>">1. Register</div>
    <div class="qs-wait-step<?php echo $state === 'verify' ? ' is-active' : ($state === 'approval' || $state === 'ready' ? ' is-done' : ''); ?>">2. Verify email</div>
    <div class="qs-wait-step<?php echo $state === 'approval' ? ' is-active' : ($state === 'ready' ? ' is-done' : ''); ?>">3. Admin approval</div>
</div>
<?php if ($displayName !== '' || $maskedEmail !== '') { ?>
<p class="qs-wait-identity">
    <?php if ($displayName !== '') { ?><strong><?php echo htmlspecialchars($displayName); ?></strong><?php } ?>
    <?php if ($maskedEmail !== '') { ?><span><?php echo htmlspecialchars($maskedEmail); ?></span><?php } ?>
</p>
<?php } ?>
<p class="text-muted"><?php echo htmlspecialchars($copy[$state]['body']); ?></p>
<a class="btn btn-primary btn-block" href="index"><?php echo htmlspecialchars($copy[$state]['cta']); ?></a>
<p class="qs-auth-foot">Need help? <a href="../">Return to the public site</a></p>
<?php
include('inc/auth-close.php');
include('inc/auth-scripts.php');
?>
</body>
</html>
