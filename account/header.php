<?php
 //Get file name
 //so as to know which file is active
      $currentFile = $_SERVER["SCRIPT_NAME"];
	  //extract it from forward /
      $parts = Explode('/', $currentFile);
      $currentFile = $parts[count($parts) - 1]; 
	  

 
include_once __DIR__ . '/inc/member-status.php';
$isActiveMember = qs_is_active_member(isset($rows) ? $rows : array());

$qs_qcore_files = array('overview-core.php', 'live-trade-cex.php', 'live-trade.php', 'future-trade.php', 'quantum-signals.php');
$qs_verse_files = array('marketplace.php', 'active-purchase.php', 'expire-purchase.php');
$qs_on_qcore = in_array($currentFile, $qs_qcore_files, true);
$qs_on_verse = in_array($currentFile, $qs_verse_files, true);
$qs_on_membership = ($currentFile === 'membership.php');
$qs_on_flex = ($currentFile === 'teams-bonus.php');
$qs_on_resources = ($currentFile === 'resources.php');

$qs_theme_default = 'dark';
$qs_theme_js = 'assets/js/qs-theme.js';
include_once __DIR__ . '/inc/qs-theme-boot.php';
?>
<link rel="preconnect" href="https://api.fontshare.com">
<link href="https://api.fontshare.com/v2/css?f[]=satoshi@400,500,700,900&display=swap" rel="stylesheet">
<link href="assets/css/qs-member.css" rel="stylesheet">
<?php if ($qs_on_qcore) { ?>
<link href="assets/css/qs-qcore.css" rel="stylesheet">
<?php } ?>
<?php if ($qs_on_membership) { ?>
<link href="assets/css/qs-membership.css" rel="stylesheet">
<?php } ?>
<?php if ($qs_on_flex) { ?>
<link href="assets/css/qs-flex.css" rel="stylesheet">
<?php } ?>
<?php if ($qs_on_resources) { ?>
<link href="assets/css/qs-resources.css" rel="stylesheet">
<?php } ?>

<!-- Start of LiveChat (www.livechat.com) code -->
<script>
    window.__lc = window.__lc || {};
    window.__lc.license = 19834219;
    window.__lc.integration_name = "manual_onboarding";
    window.__lc.product_name = "livechat";
    ;(function(n,t,c){function i(n){return e._h?e._h.apply(null,n):e._q.push(n)}var e={_q:[],_h:null,_v:"2.0",on:function(){i(["on",c.call(arguments)])},once:function(){i(["once",c.call(arguments)])},off:function(){i(["off",c.call(arguments)])},get:function(){if(!e._h)throw new Error("[LiveChatWidget] You can't use getters before load.");return i(["get",c.call(arguments)])},call:function(){i(["call",c.call(arguments)])},init:function(){var n=t.createElement("script");n.async=!0,n.type="text/javascript",n.src="https://cdn.livechatinc.com/tracking.js",t.head.appendChild(n)}};!n.__lc.asyncInit&&e.init(),n.LiveChatWidget=n.LiveChatWidget||e}(window,document,[].slice))
</script>
<noscript><a href="https://www.livechat.com/chat-with/19834219/" rel="nofollow">Chat with us</a>, powered by <a href="https://www.livechat.com/?welcome" rel="noopener nofollow" target="_blank">LiveChat</a></noscript>
<!-- End of LiveChat code -->

<!-- <div class="gtranslate_wrapper"></div>
<script>window.gtranslateSettings = {"default_language":"en","wrapper_selector":".gtranslate_wrapper"}</script>
<script src="https://cdn.gtranslate.net/widgets/latest/float.js" defer></script> -->

	<!-- main-header -->
    <div class="main-header side-header sticky nav nav-item">
					<div class=" main-container container-fluid">
						<div class="main-header-left ">
							<div class="responsive-logo">
								<a href="index" class="header-logo">
									<img src="assets/img/brand/logo.png" width="50" class="mobile-logo logo-1" alt="logo">
									<img src="assets/img/brand/logo-white.png" width="50" class="mobile-logo dark-logo-1" alt="logo">
								</a>
							</div>
							<div class="app-sidebar__toggle" data-bs-toggle="sidebar">
								<a class="open-toggle" href="javascript:void(0);"><i class="header-icon fe fe-align-left" ></i></a>
								<a class="close-toggle" href="javascript:void(0);"><i class="header-icon fe fe-x"></i></a>
							</div>
							<div class="logo-horizontal">
								<a href="index" class="header-logo">
									<img src="assets/img/brand/logo.png" class="mobile-logo logo-1" alt="logo">
									<img src="assets/img/brand/logo-white.png" class="mobile-logo dark-logo-1" alt="logo">
								</a>
							</div>
							<?php if ($currentFile === 'dashboard.php') { ?>
								<span class="qs-page-title">Overview</span>
							<?php } elseif ($qs_on_qcore) { ?>
								<span class="qs-page-title">Q-Core</span>
							<?php } elseif ($qs_on_verse) { ?>
								<span class="qs-page-title">Quantum Verse</span>
							<?php } elseif ($qs_on_membership) { ?>
								<span class="qs-page-title">Membership</span>
							<?php } elseif ($qs_on_flex) { ?>
								<span class="qs-page-title">Quantum Flex</span>
							<?php } elseif ($qs_on_resources) { ?>
								<span class="qs-page-title">Resources</span>
							<?php } ?>

							
							
						</div>
						<div class="main-header-right">
							<button class="navbar-toggler navresponsive-toggler d-lg-none ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent-4" aria-controls="navbarSupportedContent-4" aria-expanded="false" aria-label="Toggle navigation">
								<span class="navbar-toggler-icon fe fe-more-vertical "></span>
							</button>
							<div class="mb-0 navbar navbar-expand-lg navbar-nav-right responsive-navbar navbar-dark p-0">
								<div class="collapse navbar-collapse" id="navbarSupportedContent-4">
									<ul class="nav nav-item header-icons navbar-nav-right ms-auto">
										<?php if ($qs_on_qcore || $qs_on_membership || $qs_on_flex || $qs_on_resources) { ?>
										<li class="nav-item d-none d-xl-flex align-items-center">
											<span class="qs-demo-badge">DEMO DATA — NOT LIVE TRADING RESULTS</span>
										</li>
										<?php } ?>
										<li class="dropdown nav-item">
											<a class="new nav-link theme-layout nav-link-bg layout-setting" href="javascript:void(0);" title="Toggle light / dark mode">
												<span class="dark-layout"><svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" width="24" height="24" viewBox="0 0 24 24"><path d="M20.742 13.045a8.088 8.088 0 0 1-2.077.271c-2.135 0-4.14-.83-5.646-2.336a8.025 8.025 0 0 1-2.064-7.723A1 1 0 0 0 9.73 2.034a10.014 10.014 0 0 0-4.489 2.582c-3.898 3.898-3.898 10.243 0 14.143a9.937 9.937 0 0 0 7.072 2.93 9.93 9.93 0 0 0 7.07-2.929 10.007 10.007 0 0 0 2.583-4.491 1.001 1.001 0 0 0-1.224-1.224zm-2.772 4.301a7.947 7.947 0 0 1-5.656 2.343 7.953 7.953 0 0 1-5.658-2.344c-3.118-3.119-3.118-8.195 0-11.314a7.923 7.923 0 0 1 2.06-1.483 10.027 10.027 0 0 0 2.89 7.848 9.972 9.972 0 0 0 7.848 2.891 8.036 8.036 0 0 1-1.484 2.059z"/></svg></span>
												<span class="light-layout"><svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" width="24" height="24" viewBox="0 0 24 24"><path d="M6.993 12c0 2.761 2.246 5.007 5.007 5.007s5.007-2.246 5.007-5.007S14.761 6.993 12 6.993 6.993 9.239 6.993 12zM12 8.993c1.658 0 3.007 1.349 3.007 3.007S13.658 15.007 12 15.007 8.993 13.658 8.993 12 10.342 8.993 12 8.993zM10.998 19h2v3h-2zm0-17h2v3h-2zm-9 9h3v2h-3zm17 0h3v2h-3zM4.219 18.363l2.12-2.122 1.415 1.414-2.12 2.122zM16.24 6.344l2.122-2.122 1.414 1.414-2.122 2.122zM6.342 7.759 4.22 5.637l1.415-1.414 2.12 2.122zm13.434 10.605-1.414 1.414-2.122-2.122 1.414-1.414z"/></svg></span>
											</a>
										</li>

										
                                        
                                        <li class="dropdown nav-item main-header-notification d-flex">
											<a class="new nav-link"  data-bs-toggle="dropdown" href="javascript:void(0);">
												<svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" width="24" height="24" viewBox="0 0 24 24"><path d="M19 13.586V10c0-3.217-2.185-5.927-5.145-6.742C13.562 2.52 12.846 2 12 2s-1.562.52-1.855 1.258C7.185 4.074 5 6.783 5 10v3.586l-1.707 1.707A.996.996 0 0 0 3 16v2a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-2a.996.996 0 0 0-.293-.707L19 13.586zM19 17H5v-.586l1.707-1.707A.996.996 0 0 0 7 14v-4c0-2.757 2.243-5 5-5s5 2.243 5 5v4c0 .266.105.52.293.707L19 16.414V17zm-7 5a2.98 2.98 0 0 0 2.818-2H9.182A2.98 2.98 0 0 0 12 22z"/></svg><span class=" pulse"></span>
											</a>
											<div class="dropdown-menu">
												<div class="menu-header-content text-start border-bottom">
													<div class="d-flex">
														<h6 class="dropdown-title mb-1 tx-15 font-weight-semibold">Notifications</h6>
														
													</div>
													<p class="dropdown-title-text subtext mb-0 op-6 pb-0 tx-12 "></p>
												</div>
												<div class="main-notification-list Notification-scroll">
												<?php
                                        //start the loop for see all users
                                        $get = mysqli_query($mysqli,"SELECT * FROM activity WHERE userid='".$rows['id']."' ORDER BY id DESC LIMIT 3");
                                            $i=0;
                                            while($rowx= mysqli_fetch_assoc($get)){
                                                $i++;

                                            ?>
													<a class="d-flex p-3 border-bottom" href="mail.html">
														<div class="notifyimg bg-info">
															<i class="far fa-folder-open text-white"></i>
														</div>
														<div class="ms-3">
															<h5 class="notification-label mb-1"><?php echo $rowx['action']; ?></h5>
															<div class="notification-subtext"><?php echo $rowx['date']; ?></div>
														</div>
														<div class="ms-auto" >
															<i class="las la-angle-right text-end text-muted"></i>
														</div>
													</a>

													<?php } ?>
													
													
												</div>
												<div class="dropdown-footer">
													<a class="btn btn-primary btn-sm btn-block" href="transactions">VIEW ALL</a>
												</div>
											</div>
										</li>
										<li class="nav-item full-screen fullscreen-button">
											<a class="new nav-link full-screen-link" href="javascript:void(0);"><svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" width="24" height="24" viewBox="0 0 24 24"><path d="M5 5h5V3H3v7h2zm5 14H5v-5H3v7h7zm11-5h-2v5h-5v2h7zm-2-4h2V3h-7v2h5z"/></svg></a>
										</li>

										

										<li class="nav-link search-icon d-lg-none d-block">
											<form class="navbar-form" role="search">
												<div class="input-group">
													<input type="text" class="form-control" placeholder="Search">
													<span class="input-group-btn">
														<button type="reset" class="btn btn-default">
															<i class="fas fa-times"></i>
														</button>
														<button type="submit" class="btn btn-default nav-link resp-btn">
															<svg xmlns="http://www.w3.org/2000/svg" height="24px" class="header-icon-svgs" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
														</button>
													</span>
												</div>
											</form>
										</li>
										
										<li class="dropdown main-profile-menu nav nav-item nav-link ps-lg-2">
											<a class="new nav-link profile-user d-flex" href="" data-bs-toggle="dropdown"><img alt="" src="<?php echo $rows['img']; ?>" class=""></a>
											<div class="dropdown-menu">
												<div class="menu-header-content p-3 border-bottom">
													<div class="d-flex wd-100p">
														<div class="main-img-user"><img alt="" src="<?php echo $rows['img']; ?>" class=""></div>
														<div class="ms-3 my-auto">
															<h6 class="tx-15 font-weight-semibold mb-0"><?php  echo $rows['firstname']." ".$rows['lastname'];  ?></h6><span class="dropdown-title-text subtext op-6  tx-12"><?php echo $rows['email']; ?></span>
														</div>
													</div>
												</div>
												<a class="dropdown-item" href="profile"><i class="far fa-user-circle"></i>Profile</a>
												
												<a class="dropdown-item" href="transactions"><i class="far fa-list"></i>  Transactions</a>
												<a class="dropdown-item" href="logout"><i class="far fa-arrow-alt-circle-left"></i> Sign Out</a>
											</div>
										</li>
									</ul>
								</div>
							</div>
						</div>



						
					</div>


			
					


				</div>
				<!-- /main-header -->

				

				<!-- main-sidebar -->
				<div class="sticky">
					<aside class="app-sidebar">
						<div class="main-sidebar-header active">
							<a class="header-logo active" href="index">
								<img src="assets/img/brand/logo.png" class="main-logo  desktop-logo" alt="logo">
								<img src="assets/img/brand/logo-white.png" class="main-logo  desktop-dark" alt="logo">
								<img src="assets/img/brand/favicon.png" class="main-logo  mobile-logo" alt="logo">
								<img src="assets/img/brand/favicon-white.png"  class="main-logo  mobile-dark" alt="logo">
							</a>
						</div>
						<div class="qs-qcore-status">
							<div class="qs-qcore-status__row">
								<span>Q-CORE STATUS</span>
								<span class="qs-qcore-status__dot" aria-hidden="true"></span>
							</div>
							<p class="qs-qcore-status__state">OPERATIONAL</p>
							<p class="qs-qcore-status__meta">Market • Execution • Chain</p>
						</div>
						<div class="main-sidemenu">
							<div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"><path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"/></svg></div>
							<ul class="side-menu">
								<li class="slide">
									<a class="side-menu__item<?php echo $currentFile === 'dashboard.php' ? ' active' : ''; ?>" href="dashboard"><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="24" height="24" viewBox="0 0 24 24"><path d="M3 13h1v7c0 1.103.897 2 2 2h12c1.103 0 2-.897 2-2v-7h1a1 1 0 0 0 .707-1.707l-9-9a.999.999 0 0 0-1.414 0l-9 9A1 1 0 0 0 3 13zm7 7v-5h4v5h-4zm2-15.586 6 6V15l.001 5H16v-5c0-1.103-.897-2-2-2h-4c-1.103 0-2 .897-2 2v5H6v-9.586l6-6z"/></svg><span class="side-menu__label">Overview</span></a>
								</li>
								<li class="slide">
									<a class="side-menu__item<?php echo $qs_on_qcore ? ' active' : ''; ?>" href="overview-core"><i class="fe fe-compass" style="padding-right:11px;"></i><span class="side-menu__label">Quantum Core</span></a>
								</li>
								<li class="slide">
									<a class="side-menu__item<?php echo $qs_on_verse ? ' active' : ''; ?>" href="marketplace"><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="24" height="24" viewBox="0 0 24 24"><path d="M22 7.999a1 1 0 0 0-.516-.874l-9.022-5a1.003 1.003 0 0 0-.968 0l-8.978 4.96a1 1 0 0 0-.003 1.748l9.022 5.04a.995.995 0 0 0 .973.001l8.978-5A1 1 0 0 0 22 7.999zm-9.977 3.855L5.06 7.965l6.917-3.822 6.964 3.859-6.918 3.852z"/><path d="M20.515 11.126 12 15.856l-8.515-4.73-.971 1.748 9 5a1 1 0 0 0 .971 0l9-5-.97-1.748z"/><path d="M20.515 15.126 12 19.856l-8.515-4.73-.971 1.748 9 5a1 1 0 0 0 .971 0l9-5-.97-1.748z"/></svg><span class="side-menu__label">Quantum Verse</span></a>
								</li>
								<li class="slide">
									<a class="side-menu__item<?php echo $currentFile === 'membership.php' ? ' active' : ''; ?>" href="membership"><i class="fe fe-award" style="padding-right:11px;"></i><span class="side-menu__label">Membership</span></a>
								</li>
								<li class="slide">
									<a class="side-menu__item<?php echo $currentFile === 'transactions.php' ? ' active' : ''; ?>" href="transactions"><i class="fe fe-list" style="padding-right:11px;"></i><span class="side-menu__label">Activities</span></a>
								</li>
								<li class="slide">
									<a class="side-menu__item<?php echo in_array($currentFile, array('make-withdrawal.php', 'withdrawal-history.php'), true) ? ' active' : ''; ?>" href="make-withdrawal"><i class="fe fe-briefcase" style="padding-right:11px;"></i><span class="side-menu__label">Withdrawals</span></a>
								</li>
								<li class="slide">
									<a class="side-menu__item<?php echo in_array($currentFile, array('referrals.php', 'referral-bonus.php'), true) ? ' active' : ''; ?>" href="referrals"><i class="fe fe-users" style="padding-right:11px;"></i><span class="side-menu__label">Referrals</span></a>
								</li>
								<li class="slide">
									<a class="side-menu__item<?php echo $currentFile === 'teams-bonus.php' ? ' active' : ''; ?>" href="teams-bonus"><i class="fe fe-user-plus" style="padding-right:11px;"></i><span class="side-menu__label">Quantum Flex</span></a>
								</li>
								<li class="slide">
									<a class="side-menu__item<?php echo $currentFile === 'virtual-card.php' ? ' active' : ''; ?>" href="virtual-card"><i class="fe fe-credit-card" style="padding-right:11px;"></i><span class="side-menu__label">Virtual Card</span></a>
								</li>
								<li class="slide">
									<a class="side-menu__item<?php echo $currentFile === 'resources.php' ? ' active' : ''; ?>" href="resources"><i class="fe fe-download" style="padding-right:11px;"></i><span class="side-menu__label">Resources</span></a>
								</li>
								<li class="slide">
									<a class="side-menu__item" href="javascript:void(0);" onclick="if (window.LiveChatWidget) { LiveChatWidget.call('maximize'); }"><i class="fe fe-headphones" style="padding-right:11px;"></i><span class="side-menu__label">Support</span></a>
								</li>
								<li class="slide">
									<a class="side-menu__item<?php echo in_array($currentFile, array('2fa.php', 'change-password.php'), true) ? ' active' : ''; ?>" href="2fa"><i class="fe fe-lock" style="padding-right:11px;"></i><span class="side-menu__label">Security</span></a>
								</li>
								<li class="slide">
									<a class="side-menu__item<?php echo $currentFile === 'profile.php' ? ' active' : ''; ?>" href="profile"><i class="fe fe-user" style="padding-right:11px;"></i><span class="side-menu__label">Account Profile</span></a>
								</li>
								<li class="slide">
									<a class="side-menu__item" href="../"><i class="fe fe-globe" style="padding-right:11px;"></i><span class="side-menu__label">Public Site</span></a>
								</li>
								<li class="slide">
									<a class="side-menu__item qs-theme-toggle" href="javascript:void(0);"><i class="fe fe-sun" style="padding-right:11px;"></i><span class="side-menu__label qs-theme-label">Light Mode</span></a>
								</li>
								<li class="slide">
									<a class="side-menu__item" href="logout"><i class="fe fe-unlock" style="padding-right:11px;"></i><span class="side-menu__label">Logout</span></a>
								</li>
							</ul>
							<div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"><path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"/></svg></div>
						</div>
					</aside>
				</div>
				<!-- main-sidebar -->

<?php if (!$isActiveMember) { ?>
				<div class="qs-license-banner" role="status">Limited access — purchase a license for full Q-Core features. <a href="membership">Purchase License</a></div>
<?php } ?>



				<input type="text" style="display:none"
    value="<?php echo $rows['referal_link']; ?>" id="refer-code" />
    <input type="text" style="display:none"
    value="https://quantumscalp.io/account/register?refer=<?php echo $rows['referal_link']; ?>" id="refer-link" />
<script>
function mycode() {
    /* Get the text field */
    var copyText = document.getElementById("refer-code");

    copyText.style = "display:block";
    /* Select the text field */
    copyText.select();

    /* Copy the text inside the text field */
    document.execCommand("copy");

    copyText.style = "display:none";

    alert('Referral Code Copied!');

}


function mylink() {
    /* Get the text field */
    var copyText = document.getElementById("refer-link");

    copyText.style = "display:block";
    /* Select the text field */
    copyText.select();

    /* Copy the text inside the text field */
    document.execCommand("copy");

    copyText.style = "display:none";

    alert('Referral Link Copied!');

}


</script>
