<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (!isset($pageTitle)) {
    $pageTitle = 'Quantum Scalp AI | Q-Core Engine — AI-Powered Crypto Arbitrage Software';
}
if (!isset($pageDescription)) {
    $pageDescription = 'Quantum Scalp AI — AI-powered cryptocurrency arbitrage software. The Q-Core engine analyzes cross-exchange, triangular, statistical, DEX, flash-loan, and derivatives arbitrage. Trading involves risk.';
}
if (!isset($currentPage)) {
    $currentPage = '';
}

$qsPublicUser = null;
if (isset($_SESSION['id']) && $_SESSION['id'] !== '') {
    require_once __DIR__ . '/../account/connection.php';
    $qsUid = (int) $_SESSION['id'];
    if ($qsUid > 0 && isset($mysqli) && $mysqli) {
        $qsUserRes = mysqli_query($mysqli, "SELECT firstname, lastname, email, img FROM users WHERE id='" . $qsUid . "' LIMIT 1");
        if ($qsUserRes) {
            $qsUserRow = mysqli_fetch_assoc($qsUserRes);
            if (is_array($qsUserRow)) {
                $qsName = trim((isset($qsUserRow['firstname']) ? $qsUserRow['firstname'] : '') . ' ' . (isset($qsUserRow['lastname']) ? $qsUserRow['lastname'] : ''));
                if ($qsName === '') {
                    $qsName = isset($qsUserRow['email']) && $qsUserRow['email'] !== '' ? $qsUserRow['email'] : 'Account';
                }
                $qsImg = isset($qsUserRow['img']) ? trim($qsUserRow['img']) : '';
                if ($qsImg === '') {
                    $qsImg = 'img/profile.png';
                }
                if (!preg_match('#^(https?:)?//#i', $qsImg) && strpos($qsImg, '/') !== 0) {
                    $qsImg = 'account/' . $qsImg;
                }
                $qsPublicUser = array(
                    'name' => $qsName,
                    'img' => $qsImg,
                );
            }
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#050914">
    <meta name="description" content="<?php echo htmlspecialchars($pageDescription); ?>">
    <meta name="keywords" content="AI crypto arbitrage, crypto arbitrage software, automated arbitrage software, Q-Core, Quantum Scalp">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="shortcut icon" href="assets/img/favicon.png" type="image/x-icon">
    <link rel="preconnect" href="https://api.fontshare.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@400,500,700,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/qs-theme.css?v=<?php echo is_file(__DIR__ . '/../assets/css/qs-theme.css') ? filemtime(__DIR__ . '/../assets/css/qs-theme.css') : time(); ?>">
    <link rel="stylesheet" href="assets/css/qs-cookie.css?v=<?php echo is_file(__DIR__ . '/../assets/css/qs-cookie.css') ? filemtime(__DIR__ . '/../assets/css/qs-cookie.css') : time(); ?>">
<?php
if (!empty($pageStyles)) {
    foreach ((array) $pageStyles as $qsPageStyle) {
        $qsPageStyleRel = ltrim((string) $qsPageStyle, '/');
        $qsPageStyleFile = __DIR__ . '/../' . $qsPageStyleRel;
        $qsPageStyleVer = is_file($qsPageStyleFile) ? filemtime($qsPageStyleFile) : time();
        echo '    <link rel="stylesheet" href="' . htmlspecialchars($qsPageStyleRel) . '?v=' . $qsPageStyleVer . '">' . "\n";
    }
}
?>
</head>
<body class="qs-body">
<div class="qs-stars" aria-hidden="true"></div>
