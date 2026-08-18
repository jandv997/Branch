<?php

//Get file name
//so as to know which file is active
     $currentFile = $_SERVER["SCRIPT_NAME"];
     //extract it from forward /
     $parts = Explode('/', $currentFile);
     $currentFile = $parts[count($parts) - 1];

$qs_theme_default = 'light';
$qs_theme_js = '../assets/js/qs-theme.js';
include_once __DIR__ . '/../inc/qs-theme-boot.php';

?>



<!-- Main Header-->
<div class="main-header side-header sticky">
    <div class="main-container container-fluid">
        <div class="main-header-left">
            <a class="main-header-menu-icon" href="javascript:;" id="mainSidebarToggle">
                <svg class="header-menu-icon" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24"
                    viewBox="0 0 24 24">
                    <path
                        d="M2.5,10.5h11c0.276123,0,0.5-0.223877,0.5-0.5s-0.223877-0.5-0.5-0.5h-11C2.223877,9.5,2,9.723877,2,10S2.223877,10.5,2.5,10.5z M2.5,6.5h19C21.776123,6.5,22,6.276123,22,6s-0.223877-0.5-0.5-0.5h-19C2.223877,5.5,2,5.723877,2,6S2.223877,6.5,2.5,6.5z M21.8446045,9.3519897C21.609314,9.0689697,21.189209,9.0303345,20.90625,9.265625l-2.6660156,2.2226562c-0.0315552,0.0261841-0.0606079,0.0552368-0.086792,0.086792c-0.2346802,0.2826538-0.1958008,0.7019653,0.086792,0.9366455L20.90625,14.734375c0.1194458,0.1005249,0.2706299,0.1555176,0.4267578,0.1552734c0.1973267-0.0002441,0.3843994-0.0878906,0.5109253-0.2393188c0.236145-0.2826538,0.1984863-0.7032471-0.0841675-0.9393921L19.7080078,12l2.0517578-1.7109375C22.0414429,10.0534668,22.0794067,9.6343384,21.8446045,9.3519897z M2.5,14.5h11c0.276123,0,0.5-0.223877,0.5-0.5s-0.223877-0.5-0.5-0.5h-11C2.223877,13.5,2,13.723877,2,14S2.223877,14.5,2.5,14.5z M21.5,17.5h-19C2.223877,17.5,2,17.723877,2,18s0.223877,0.5,0.5,0.5h19c0.276123,0,0.5-0.223877,0.5-0.5S21.776123,17.5,21.5,17.5z" />
                </svg>
            </a>
            <div class="hor-logo">
                <a class="main-logo" href="dashboard">
                    <img src="img/logo.png" class="header-brand-img desktop-logo" alt="logo">
                    <img src="img/logo.png" class="header-brand-img desktop-logo-dark" alt="logo">
                </a>
            </div>
        </div>
        <div class="main-header-center">
            <div class="responsive-logo">
                <a href="dashboard"><img src="img/logo.png" width="100" class="mobile-logo" alt="logo"></a>
                <a href="dashboard"><img src="img/logo.png" width="100" class="mobile-logo-dark" alt="logo"></a>
            </div>

            <form action="users">
            <div class="input-group">
               
						<input type="search" class="form-control rounded-0" name="metadata" placeholder="Search for Users...">
						<button class="btn search-btn" name="trigggerseach"><i class="fe fe-search"></i></button>
                    
					</div>
                    </form>

        </div>
        <div class="main-header-right">
            <button class="navbar-toggler navresponsive-toggler d-lg-none ms-auto" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent-4"
                aria-controls="navbarSupportedContent-4" aria-expanded="false" aria-label="Toggle navigation">
                <svg class="header-icons navbar-toggler-icon" xmlns="http://www.w3.org/2000/svg"
                    enable-background="new 0 0 24 24" viewBox="0 0 24 24">
                    <path
                        d="M12,7c1.1040039-0.0014038,1.9985962-0.8959961,2-2c0-1.1045532-0.8954468-2-2-2s-2,0.8954468-2,2S10.8954468,7,12,7z M12,4c0.552124,0.0003662,0.9996338,0.447876,1,1c0,0.5523071-0.4476929,1-1,1s-1-0.4476929-1-1S11.4476929,4,12,4z M12,10c-1.1045532,0-2,0.8954468-2,2s0.8954468,2,2,2c1.1040039-0.0014038,1.9985962-0.8959961,2-2C14,10.8954468,13.1045532,10,12,10z M12,13c-0.5523071,0-1-0.4476929-1-1s0.4476929-1,1-1c0.552124,0.0003662,0.9996338,0.447876,1,1C13,12.5523071,12.5523071,13,12,13z M12,17c-1.1045532,0-2,0.8954468-2,2s0.8954468,2,2,2c1.1040039-0.0014038,1.9985962-0.8959961,2-2C14,17.8954468,13.1045532,17,12,17z M12,20c-0.5523071,0-1-0.4476929-1-1s0.4476929-1,1-1c0.552124,0.0003662,0.9996338,0.447876,1,1C13,19.5523071,12.5523071,20,12,20z" />
                </svg>
            </button>
            <div class="navbar navbar-expand-lg navbar-collapse responsive-navbar">
                <div class="collapse navbar-collapse" id="navbarSupportedContent-4">
                    <div class="d-flex order-lg-2 ms-auto">

                        <!--search-->
                        <div class="dropdown d-flex main-header-theme">
                            <a class="nav-link icon layout-setting" href="javascript:void(0);" title="Toggle light / dark mode">
                                <span class="dark-layout">
                                    <svg class="header-icons" xmlns="http://www.w3.org/2000/svg"
                                        enable-background="new 0 0 24 24" viewBox="0 0 24 24">
                                        <path
                                            d="M5.6356812,17.6572876l-0.7069702,0.7069702c-0.09375,0.09375-0.1463623,0.2208862-0.1464233,0.3534546c0,0.276123,0.2238159,0.5,0.499939,0.500061c0.1326294,0.0001221,0.2598267-0.0526123,0.3534546-0.1464844l0.7070312-0.7070312c0.1904907-0.194397,0.1904907-0.5054932,0-0.6998901C6.1494141,17.4671631,5.8328857,17.4639893,5.6356812,17.6572876z M12,4h0.0006104C12.2765503,3.9998169,12.5001831,3.776001,12.5,3.5v-1C12.5,2.223877,12.276123,2,12,2s-0.5,0.223877-0.5,0.5v1.0006104C11.5001831,3.7765503,11.723999,4.0001831,12,4z M5.6357422,6.3427734c0.0936279,0.0939331,0.2208862,0.1466675,0.3535156,0.1464844v0.000061c0.1325073-0.000061,0.2596436-0.0527344,0.3533936-0.1464233c0.1953125-0.1952515,0.1953125-0.5118408,0.000061-0.7071533L5.6357422,4.928772c-0.194397-0.1904907-0.5054321-0.1904907-0.6998291,0C4.7387085,5.1220093,4.7354736,5.4385376,4.9287109,5.6357422L5.6357422,6.3427734z M3.5,11.5h-1C2.223877,11.5,2,11.723877,2,12s0.223877,0.5,0.5,0.5h1C3.776123,12.5,4,12.276123,4,12S3.776123,11.5,3.5,11.5z M12,20c-0.276123,0-0.5,0.223877-0.5,0.5v1.0005493C11.5001831,21.7765503,11.723999,22.0001831,12,22h0.0006104c0.2759399-0.0001831,0.4995728-0.223999,0.4993896-0.5v-1C12.5,20.223877,12.276123,20,12,20z M12,6c-3.3137207,0-6,2.6862793-6,6s2.6862793,6,6,6c3.3121948-0.0036011,5.9963989-2.6878052,6-6C18,8.6862793,15.3137207,6,12,6z M12,17c-2.7614136,0-5-2.2385864-5-5s2.2385864-5,5-5c2.7600708,0.0032349,4.9967651,2.2399292,5,5C17,14.7614136,14.7614136,17,12,17z M21.5,11.5h-1c-0.276123,0-0.5,0.223877-0.5,0.5s0.223877,0.5,0.5,0.5h1c0.276123,0,0.5-0.223877,0.5-0.5S21.776123,11.5,21.5,11.5z M18.3642578,4.9287109l-0.7070312,0.7070312c-0.09375,0.09375-0.1463623,0.2208862-0.1464233,0.3534546c0,0.276123,0.2238159,0.5,0.499939,0.500061c0.1326294,0.0001221,0.2598267-0.0526123,0.3535156-0.1465454l0.7069702-0.7069702c0.0023804-0.0023804,0.0047607-0.0046997,0.0071411-0.0071411c0.1932373-0.1971436,0.1900635-0.5137329-0.0071411-0.7069702C18.8740234,4.7283325,18.5574951,4.7315674,18.3642578,4.9287109z M18.3642578,17.6572876c-0.194397-0.1905518-0.5055542-0.1905518-0.6999512,0c-0.1971436,0.1932983-0.2003174,0.5098267-0.007019,0.7069702l0.7069702,0.7070312c0.0936279,0.0939331,0.2208252,0.1466675,0.3534546,0.1464844c0.1325684,0,0.2597046-0.0526733,0.3534546-0.1463623c0.1953125-0.1952515,0.1953125-0.5118408,0.0001221-0.7071533L18.3642578,17.6572876z" />
                                    </svg>
                                </span>
                                <span class="light-layout">
                                    <svg class="header-icons" xmlns="http://www.w3.org/2000/svg"
                                        enable-background="new 0 0 24 24" viewBox="0 0 24 24">
                                        <path
                                            d="M22.0482178,13.2746582c-0.1265259-0.2453003-0.4279175-0.3416138-0.6732178-0.2150879C20.1774902,13.6793823,18.8483887,14.0019531,17.5,14c-0.8480835-0.0005493-1.6913452-0.1279297-2.50177-0.3779297c-4.4887085-1.3847046-7.0050049-6.1460571-5.6203003-10.6347656c0.0320435-0.1033325,0.0296021-0.2142944-0.0068359-0.3161621C9.2781372,2.411377,8.9921875,2.2761841,8.7324219,2.3691406C4.6903076,3.800293,1.9915771,7.626709,2,11.9146729C2.0109863,17.4956055,6.5440674,22.0109863,12.125,22c4.9342651,0.0131226,9.1534424-3.5461426,9.9716797-8.4121094C22.1149292,13.4810181,22.0979614,13.3710327,22.0482178,13.2746582z M16.0877075,20.0958252c-4.5321045,2.1853027-9.9776611,0.2828979-12.1630249-4.2492065S3.6417236,5.8689575,8.1738281,3.6835938C8.0586548,4.2776489,8.0004272,4.8814087,8,5.4865723C7.9962769,10.7369385,12.2495728,14.9962769,17.5,15c1.1619263,0.0023193,2.3140869-0.2119751,3.3974609-0.6318359C20.1879272,16.8778076,18.4368896,18.9630127,16.0877075,20.0958252z" />
                                    </svg>
                                </span>
                            </a>
                        </div><!-- Theme-Layout -->
                        <div class="dropdown d-flex main-header-fullscreen">
                            <a class="nav-link icon full-screen-link" href="javascript:;">
                                <svg class="header-icons fullscreen-button fullscreen"
                                    xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M10.1464844,13.1464844L3,20.2929688V16.5C3,16.223877,2.776123,16,2.5,16S2,16.223877,2,16.5v5.0005493C2.0001831,21.7765503,2.223999,22.0001831,2.5,22h5C7.776123,22,8,21.776123,8,21.5S7.776123,21,7.5,21H3.7069092l7.1465454-7.1465454c0.1871338-0.1937256,0.1871338-0.5009155,0-0.6947021C10.6616211,12.960144,10.3450928,12.9546509,10.1464844,13.1464844z M3.7068481,3H7.5C7.776123,3,8,2.776123,8,2.5S7.776123,2,7.5,2H2.4993896C2.2234497,2.0001831,1.9998169,2.223999,2,2.5v5.0006104C2.0001831,7.7765503,2.223999,8.0001831,2.5,8h0.0006104C2.7765503,7.9998169,3.0001831,7.776001,3,7.5V3.7071533l7.1524658,7.1524658c0.1937866,0.1871948,0.5009766,0.1871948,0.6947632,0c0.1986084-0.1918335,0.2041016-0.5083618,0.0122681-0.7069702L3.7068481,3z M21.5,2h-5C16.223877,2,16,2.223877,16,2.5S16.223877,3,16.5,3h3.7930908l-7.1526489,7.1526489c-0.1871948,0.1937256-0.1871948,0.5009766,0,0.6947021c0.1918335,0.1986084,0.5083618,0.2041016,0.7069702,0.0122681L21,3.7070312v3.7935791C21.0001831,7.7765503,21.223999,8.0001831,21.5,8h0.0006104C21.7765503,7.9998169,22.0001831,7.776001,22,7.5V2.4993896C21.9998169,2.2234497,21.776001,1.9998169,21.5,2z M21.5,16c-0.276123,0-0.5,0.223877-0.5,0.5v3.7930908l-7.1465454-7.1465454c-0.1937256-0.1871338-0.5009155-0.1871338-0.6947021,0c-0.1986084,0.1918335-0.2041016,0.5083618-0.0122681,0.7069702L20.2929688,21H16.5c-0.276123,0-0.5,0.223877-0.5,0.5s0.223877,0.5,0.5,0.5h5.0006104C21.7765503,21.9998169,22.0001831,21.776001,22,21.5v-5C22,16.223877,21.776123,16,21.5,16z" />
                                </svg>
                                <svg class="header-icons fullscreen-button exit-fullscreen"
                                    xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M7.5,16h-5C2.223877,16,2,16.223877,2,16.5S2.223877,17,2.5,17H7v4.5005493C7.0001831,21.7765503,7.223999,22.0001831,7.5,22h0.0006104C7.7765503,21.9998169,8.0001831,21.776001,8,21.5v-5.0006104C7.9998169,16.2234497,7.776001,15.9998169,7.5,16z M16.5,8h5C21.776123,8,22,7.776123,22,7.5S21.776123,7,21.5,7H17V2.5C17,2.223877,16.776123,2,16.5,2S16,2.223877,16,2.5v5.0006104C16.0001831,7.7765503,16.223999,8.0001831,16.5,8z M7.5,2C7.223877,2,7,2.223877,7,2.5V7H2.5C2.223877,7,2,7.223877,2,7.5S2.223877,8,2.5,8h5.0006104C7.7765503,7.9998169,8.0001831,7.776001,8,7.5v-5C8,2.223877,7.776123,2,7.5,2z M21.5,16h-5.0005493C16.2234497,16.0001831,15.9998169,16.223999,16,16.5v5.0005493C16.0001831,21.7765503,16.223999,22.0001831,16.5,22h0.0006104C16.7765503,21.9998169,17.0001831,21.776001,17,21.5V17h4.5c0.276123,0,0.5-0.223877,0.5-0.5S21.776123,16,21.5,16z" />
                                </svg>
                            </a>
                        </div><!-- full screen -->
                        <div class="dropdown main-header-notification flag-dropdown">
                            <a class="nav-link icon text-center" data-bs-target="#country-selector"
                                data-bs-toggle="modal">
                                <img class="header-icons" alt="" src="assets/img/flags/us_flag.jpg">
                            </a>
                        </div><!-- country flag -->
                        


                        <div class="dropdown d-flex main-profile-menu">
                            <a class="d-flex" href="javascript:;">
                                <span class="main-img-user">
                                    <img alt="avatar" src="img/profile.png">
                                </span>
                            </a>
                            <div class="dropdown-menu">
                                <div class="header-navheading">
                                    <h6 class="main-notification-title">
                                        <?php echo $rows['username']; ?></h6>
                                    <p class="main-notification-text"><?php echo $rows['email']; ?></p>
                                </div>
                               
                                <a class="dropdown-item" href="logout">
                                    <i class="fe fe-power"></i> Sign Out
                                </a>
                            </div>
                        </div><!-- profile -->

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Main Header-->



<!-- Sidemenu -->
<div class="sticky">
    <div class="main-menu main-sidebar main-sidebar-sticky side-menu">
        <div class="main-sidebar-header main-container-1 active">
            <div class="sidemenu-logo">
                <a class="main-logo" href="dashboard">
                    <img src="img/logo-white.png" class="header-brand-img desktop-logo-dark" style="width:90px" alt="logo">
                    <img src="img/favicon.png" class="header-brand-img icon-logo-dark" alt="logo">
                    <img src="img/favicon.png" class="header-brand-img desktop-logo" alt="logo">
                    <img src="img/favicon.png" class="header-brand-img icon-logo" alt="logo">
                </a>
            </div>

            <div class="main-sidebar-body main-body-1">
                <div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#c9bebe"
                        width="24" height="24" viewBox="0 0 24 24">
                        <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
                    </svg></div>
                <ul class="menu-nav nav">
                    <li class="nav-item">
                        <a class="nav-link with-sub" href="dashboard">
                            <svg class="sidemenu-icon menu-icon" xmlns="http://www.w3.org/2000/svg"
                                enable-background="new 0 0 24 24" viewBox="0 0 24 24">
                                <path
                                    d="M10.5,13h-7C3.2,13,3,13.2,3,13.5v7C3,20.8,3.2,21,3.5,21h7c0.3,0,0.5-0.2,0.5-0.5v-7C11,13.2,10.8,13,10.5,13z M10,20H4v-6h6V20z M10.5,3h-7C3.2,3,3,3.2,3,3.5v7C3,10.8,3.2,11,3.5,11h7c0.3,0,0.5-0.2,0.5-0.5v-7C11,3.2,10.8,3,10.5,3z M10,10H4V4h6V10z M20.5,3h-7C13.2,3,13,3.2,13,3.5v7c0,0.3,0.2,0.5,0.5,0.5h7c0.3,0,0.5-0.2,0.5-0.5v-7C21,3.2,20.8,3,20.5,3z M20,10h-6V4h6V10z M20.5,16.5h-3v-3c0-0.3-0.2-0.5-0.5-0.5s-0.5,0.2-0.5,0.5v3h-3c-0.3,0-0.5,0.2-0.5,0.5s0.2,0.5,0.5,0.5h3v3c0,0.3,0.2,0.5,0.5,0.5h0c0.3,0,0.5-0.2,0.5-0.5v-3h3c0.3,0,0.5-0.2,0.5-0.5S20.8,16.5,20.5,16.5z" />
                            </svg>
                            <span class="sidemenu-label">Dashboard</span>
                            <i class="angle fe fe-chevron-right"></i>
                        </a>

                    </li>

                    
                    

                    <li class="nav-header"><span class="nav-label">Accounts</span></li>
                   

                    <li class="nav-item">
                        <a class="nav-link with-sub" href="javascript:;">
                        <div class="sidemenu-icon menu-icon">
                                <i class=" fe fe-user"></i>
                            </div>
                            <span class="sidemenu-label">All Users</span>
                            <i class="angle fe fe-chevron-right"></i></a>
                        <ul class="nav-sub">
                          
                            <li class="nav-sub-item">
                                <a class="nav-sub-link" href="users">Users Account</a>
                            </li>
                            <li class="nav-sub-item">
                                <a class="nav-sub-link" href="users-fund">Users Fund Management</a>
                            </li>
                            <li class="nav-sub-item">
                                <a class="nav-sub-link" href="users-access">Users Access Management</a>
                            </li>
                           

                            <li class="nav-sub-item">
                                <a class="nav-sub-link" href="export-emails">Export Users Email</a>
                            </li>
                           
                            
                        </ul>
                    </li>



                   

                    <li class="nav-header"><span class="nav-label">Activity</span></li>



                    <li class="nav-item">
                        <a class="nav-link with-sub" href="javascript:;">
                        <div class="sidemenu-icon menu-icon">
                                <i class=" fe fe-server"></i>
                            </div>
                            <span class="sidemenu-label">Withdrawal</span>
                            <i class="angle fe fe-chevron-right"></i></a>
                        <ul class="nav-sub">
                          
                            <li class="nav-sub-item">
                                <a class="nav-sub-link" href="approve-withdrawal">Approve Withdrawals</a>
                            </li>
                            <li class="nav-sub-item">
                                <a class="nav-sub-link" href="withdrawal-history">Withdrawal History</a>
                            </li>

                            <li class="nav-sub-item">
                                <a class="nav-sub-link" href="withdrawal-method">Withdrawal Method</a>
                            </li>
                           
                            
                        </ul>
                    </li>



                    <li class="nav-item">
                        <a class="nav-link with-sub" href="javascript:;">
                        <div class="sidemenu-icon menu-icon">
                                <i class=" fe fe-box"></i>
                            </div>
                            <span class="sidemenu-label">Investments</span>
                            <i class="angle fe fe-chevron-right"></i></a>
                        <ul class="nav-sub">
                          
                            <li class="nav-sub-item">
                                <a class="nav-sub-link" href="view-investment">Manage Active Investments</a>
                            </li>
                            <li class="nav-sub-item">
                                <a class="nav-sub-link" href="investment-package">Investment Packages</a>
                            </li>
                           
                            
                        </ul>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link with-sub" href="javascript:;">
                        <div class="sidemenu-icon menu-icon">
                                <i class=" fe fe-dollar-sign"></i>
                            </div>
                            <span class="sidemenu-label">Payments</span>
                            <i class="angle fe fe-chevron-right"></i></a>
                        <ul class="nav-sub">
                          
                            <li class="nav-sub-item">
                                <a class="nav-sub-link" href="payment">Approve Payments</a>
                            </li>
                            <li class="nav-sub-item">
                                <a class="nav-sub-link" href="payment-wallets">Payment Wallets</a>
                            </li>
                            <li class="nav-sub-item">
                                <a class="nav-sub-link" href="payment-history">Payment History</a>
                            </li>
                           
                            
                        </ul>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link " href="trans-activity">
                        <div class="sidemenu-icon menu-icon">
                                <i class=" fe fe-layers"></i>
                        </div>
                            <span class="sidemenu-label">Create Transactions</span>
                            <i class="angle fe fe-chevron-right"></i></a>
                       
                    </li>

                    <li class="nav-item">
                        <a class="nav-link " href="referral-comm">
                        <div class="sidemenu-icon menu-icon">
                                <i class=" fe fe-send"></i>
                        </div>
                            <span class="sidemenu-label">Referral Commissions </span>
                            <i class="angle fe fe-chevron-right"></i></a>
                       
                    </li>






                    <li class="nav-item">
                        <a class="nav-link" href="resources">
                            <div class="sidemenu-icon menu-icon">
                                <i class="fe fe-download"></i>
                            </div>
                            <span class="sidemenu-label">Resources</span>
                        </a>
                    </li>

                    <li class="nav-header"><span class="nav-label">Core</span></li>

                    <li class="nav-item"><a class="nav-link" href="javascript:;" id="terminalLink">
                        <div class="sidemenu-icon menu-icon">
                            <i class="fe fe-terminal"></i>
                        </div>
                        <span class="sidemenu-label">Q-Core Terminal</span></a>
                    </li>

                    <li class="nav-header"><span class="nav-label">More</span></li>



                   

                    <li  class="nav-item"><a class="nav-link " href="email" > <div class="sidemenu-icon menu-icon">
                                <i class=" fe fe-message-square"></i>
                            </div>
                            <span class="sidemenu-label">Send Emails </span></a>

                    </li>

                    <li  class="nav-item"><a class="nav-link " href="logout" > <div class="sidemenu-icon menu-icon">
                                <i class=" fe fe-log-out"></i>
                            </div>
                            <span class="sidemenu-label"> Logout </span></a>

                    </li>




                    

                </ul>
                <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#c9bebe"
                        width="24" height="24" viewBox="0 0 24 24">
                        <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
                    </svg></div>
            </div>
        </div>
    </div>
</div>
<!-- Sensitive access modal -->
<div class="modal fade" id="terminalAuthModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sensitive Area Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="terminalAuthForm" action="terminal" method="get">
                <div class="modal-body">
                    <p>You are about to enter a sensitive section of the system and must validate your access again.</p>
                    <div class="mb-3">
                        <label for="authCode" class="form-label">Authorization Code</label>
                        <input type="text" class="form-control" id="authCode" name="auth_code" placeholder="Enter authorization code" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Validate and Enter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(function() {
    var terminalLink = document.getElementById('terminalLink');
    var authModal = document.getElementById('terminalAuthModal');
    if (terminalLink && authModal) {
        terminalLink.addEventListener('click', function(event) {
            event.preventDefault();
            var modal = new bootstrap.Modal(authModal);
            modal.show();
        });
    }
})();
</script>

<!-- End Sidemenu -->


