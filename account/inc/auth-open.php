<?php
if (!isset($authTitle)) {
    $authTitle = 'Sign in';
}
if (!isset($authSubtitle)) {
    $authSubtitle = 'Access your Q-Core dashboard.';
}
if (!isset($authHome)) {
    $authHome = '../index';
}
$authFormClass = !empty($authWide) ? 'qs-auth-form qs-auth-form--wide' : 'qs-auth-form';
?>
<body class="qs-auth">
    <div id="global-loader">
        <img src="img/favicon-white.png" width="50" class="loader-img" alt="Loader">
    </div>
    <div class="qs-auth-split">
        <aside class="qs-auth-visual">
            <div class="qs-auth-stars" aria-hidden="true"></div>
            <div class="qs-auth-visual-inner">
                <a class="qs-auth-brand" href="<?php echo htmlspecialchars($authHome); ?>">
                    <img src="img/logo-white.png" alt="Quantum Scalp">
                </a>
                <div class="qs-auth-art" aria-hidden="true">
                    <img src="img/auth-hero.svg" alt="">
                </div>
                <div>
                    <h2>Understand. Verify. Then decide.</h2>
                    <p>Access the Q-Core platform. Trading involves risk — we prioritize transparency over hype.</p>
                </div>
            </div>
        </aside>
        <main class="qs-auth-panel">
            <div class="<?php echo $authFormClass; ?>">
                <a class="qs-auth-logo" href="<?php echo htmlspecialchars($authHome); ?>">
                    <img src="img/logo-white.png" alt="Quantum Scalp">
                </a>
                <a class="qs-auth-back" href="<?php echo htmlspecialchars($authHome); ?>">&larr; Back to site</a>
                <h1><?php echo htmlspecialchars($authTitle); ?></h1>
                <p class="qs-auth-sub"><?php echo htmlspecialchars($authSubtitle); ?></p>
                <div class="qs-auth-body">
