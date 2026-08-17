<?php
session_start();

include('connection.php');


//check if session id is set if it is redirect to login
if(!isset($_SESSION['id'])){
	
	header("location:index");
}else{

$get_user = mysqli_query($mysqli,"SELECT * FROM users WHERE id='".$_SESSION['id']."' ");
$rows = mysqli_fetch_assoc($get_user);
    if(isset($_SESSION['2fa'])){

        if( ($_SESSION['2fa'] =="no" or $_SESSION['2fa'] =="pending") and $rows['2fa']==1){
            header("location:index");
        }


    }


}




?>
<!DOCTYPE html>
<html lang="en">
	<head>

		<meta charset="UTF-8">
		<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
	

		<!-- Title -->
		<title>Virtual Cards| Quantum Scalp </title>

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
<style>
	/* HERO */

	.dark-theme .card {
  background-color: #2a2e3f00 !important;
  border-color: #40435300 !important;
  box-shadow: 0 0 10px rgba(28, 39, 60, 0.1);
}

.hero {
  position: relative;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 70px 30px;
  border-radius: 32px;
  background: radial-gradient(circle at top left, rgba(59,130,246,0.25), transparent 28%),
              radial-gradient(circle at bottom right, rgba(168,85,247,0.22), transparent 24%),
              linear-gradient(135deg, rgba(15,23,42,0.95), rgba(30,41,59,0.98));
  overflow: hidden;
  margin-bottom: 40px;
}

.hero::before,
.hero::after {
  content: "";
  position: absolute;
  border-radius: 50%;
  opacity: 0.35;
  filter: blur(36px);
}

.hero::before {
  width: 210px;
  height: 210px;
  background: rgba(34,211,238,0.32);
  top: -40px;
  left: 10px;
}

.hero::after {
  width: 220px;
  height: 220px;
  background: rgba(168,85,247,0.28);
  bottom: -60px;
  right: 20px;
}

.hero:hover .card {
  transform: translateY(-10px) rotateY(0);
}

.hero-text h1 {

  font-size: 48px;

  line-height: 1.1;

}

.hero-text span {

  background: linear-gradient(90deg, #22d3ee, #c084fc, #f472b6);

  -webkit-background-clip: text;

  -webkit-text-fill-color: transparent;

}

.hero-text p {

  margin-top: 15px;

  color: #d1d5db;

}

.hero-text button {
  border-radius: 999px;
  padding: 12px 28px;
  font-weight: 600;
  box-shadow: 0 16px 40px rgba(99,102,241,0.24);
}

/* CARD WRAPPER */

.card-wrapper {

  perspective: 1200px;

}

/* CARD */

.card {

  width: 320px;

  height: 200px;

  position: relative;

  transform-style: preserve-3d;

  transition: transform 0.8s;

  cursor: pointer;

}

.card.flipped {

  transform: rotateY(180deg);

}

/* BOTH SIDES */

.card-face {

  position: absolute;

  width: 100%;

  height: 100%;

  border-radius: 20px;

  padding: 20px;

  backface-visibility: hidden;

  backdrop-filter: blur(20px);

  background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.02));

  border: 1px solid rgba(255, 255, 255, 0);

}

/* FRONT */

.front {

  display: flex;

  flex-direction: column;

  justify-content: space-between;

}

/* BACK */

.back {

  transform: rotateY(180deg);

}

/* CHIP */

.chip {

  width: 40px;

  height: 30px;

  border-radius: 6px;

  background: linear-gradient(45deg, gold, orange);

}

.logo {

  font-size: 24px;

}

.card-top {

  display: flex;

  justify-content: space-between;

}

/* NUMBER */

.card-number {

  letter-spacing: 3px;

  font-size: 18px;

  margin-top: 20px;

}

/* BOTTOM */

.card-bottom {

  display: flex;

  justify-content: space-between;

}

.card-bottom small {

  font-size: 10px;

  color: #94a3b8;

}

.card-bottom p {

  font-size: 14px;

}

/* STATUS */

.status {

  position: absolute;

  bottom: 10px;

  right: 15px;

  font-size: 12px;

  color: #22d3ee;

}

/* BACK SIDE */

.stripe {

  height: 40px;

  background: #000;

  margin-bottom: 20px;

}

.cvv-box {

  display: flex;

  justify-content: space-between;

  align-items: center;

}

.cvv {

  background: white;

  color: black;

  padding: 5px 10px;

  border-radius: 5px;

}

.back-text {

  margin-top: 20px;

  font-size: 12px;

  color: #94a3b8;

}


.card:hover {
  box-shadow: 0 20px 50px rgba(34, 211, 238, 0.3);
}

.card-face {
  background: linear-gradient(270deg, #22d3ee, #6366f1, #a78bfa);
  background-size: 600% 600%;
  animation: gradientMove 8s ease infinite;
}

@keyframes gradientMove {
  0% { background-position: 0% }
  50% { background-position: 100% }
  100% { background-position: 0% }
}







/* FEATURE BOX */

.feature-box {

  position: relative;

  padding: 28px 24px 24px 24px;

  border-radius: 22px;

  background: rgba(15,23,42,0.95);

  border: 1px solid rgba(255,255,255,0.12);

  backdrop-filter: blur(18px);

  height: 100%;

  transition: 0.35s ease;

}

.feature-box:hover {

  transform: translateY(-8px);

  box-shadow: 0 28px 60px rgba(59,130,246,0.18);

}

.feature-box p {

  color: #cbd5f5;

  font-size: 14px;

}

.feature-box .box-icon {
  width: 52px;
  height: 52px;
  border-radius: 16px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 18px;
  font-size: 22px;
  box-shadow: 0 14px 40px rgba(59,130,246,0.18);
}

.feature-box .box-icon.blue { background: rgba(59,130,246,0.16); color: #60a5fa; }
.feature-box .box-icon.cyan { background: rgba(6,182,212,0.16); color: #22d3ee; }
.feature-box .box-icon.purple { background: rgba(168,85,247,0.16); color: #a855f7; }
.feature-box .box-icon.green { background: rgba(34,197,94,0.16); color: #4ade80; }
.feature-box .box-icon.amber { background: rgba(251,191,36,0.14); color: #fbbf24; }
.feature-box .box-icon.pink { background: rgba(236,72,153,0.14); color: #f472b6; }

.info-box {
  padding: 25px;
  border-radius: 18px;
  background: rgba(15,23,42,0.92);
  border: 1px solid rgba(255,255,255,0.12);
  backdrop-filter: blur(16px);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.info-box:hover {
  transform: translateY(-6px);
  box-shadow: 0 28px 44px rgba(168,85,247,0.14);
}

.info-box h6 {
  margin-top: 16px;
}

.info-box p {
  color: #cbd5f5;
  font-size: 14px;
}

.info-box .box-icon {
  width: 46px;
  height: 46px;
  border-radius: 14px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  margin-right: 10px;
}

.steps .step {
  display: flex;
  gap: 18px;
  align-items: flex-start;
  margin-bottom: 20px;
  padding: 20px;
  border-radius: 18px;
  background: rgba(15,23,42,0.92);
  border: 1px solid rgba(255,255,255,0.08);
}

.step span {
  width: 42px;
  height: 42px;
  background: linear-gradient(135deg, #22d3ee, #8b5cf6);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-weight: 700;
}

.step i {
  margin-right: 10px;
  font-size: 18px;
  color: #22d3ee;
}

.step h6 {
  margin-bottom: 6px;
}

.step p {
  font-size: 14px;
  color: #cbd5f5;
}

.timeline {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
  gap: 18px;
}

.phase {
  padding: 24px;
  border-radius: 18px;
  background: linear-gradient(180deg, rgba(59,130,246,0.15), rgba(99,102,241,0.06));
  border: 1px solid rgba(99,102,241,0.16);
}

.phase h6 {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 16px;
}



.phase .box-icon {
  width: 46px;
  height: 46px;
  border-radius: 14px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  margin-right: 10px;
}

.section-title {
  color: #e2e8f0;
  font-size: 24px;
  margin-bottom: 24px;
}

.title {
  font-size: 42px;
}

.subtitle {
  color: #cbd5f5;
}

.card .card-top {
  position: relative;
}

.card .contactless {
  position: absolute;
  right: 0;
  top: 0;
  display: flex;
  align-items: center;
  gap: 4px;
  font-size: 12px;
  color: rgba(255,255,255,0.84);
}

.card .contactless i {
  font-size: 18px;
}

.card .logo img {
  filter: drop-shadow(0 0 12px rgba(255,255,255,0.2));
}

.card-number {
  letter-spacing: 3px;
  font-size: 18px;
  margin-top: 18px;
  color: rgba(255,255,255,0.95);
}

.card-bottom p {
  font-size: 14px;
  color: #f8fafc;
}

.card-bottom small {
  font-size: 11px;
  color: rgba(248,250,252,0.75);
}

.status {
  position: absolute;
  bottom: 15px;
  right: 20px;
  font-size: 12px;
  color: #a5f3fc;
  font-weight: 600;
}

.back .stripe {
  height: 48px;
  background: linear-gradient(90deg, rgba(255,255,255,0.95), rgba(255,255,255,0.2));
  margin-bottom: 24px;
}

.back .cvv-box span {
  letter-spacing: 1px;
  color: rgba(255,255,255,0.75);
}

.back .cvv {
  background: rgba(255,255,255,0.18);
  color: #fff;
  padding: 8px 14px;
  border-radius: 10px;
  font-weight: 700;
}

.back-text {
  margin-top: 24px;
  font-size: 13px;
  color: rgba(248,250,252,0.75);
}

.custom-input {
  background: rgba(255,255,255,0.06);
  border: 1px solid rgba(255,255,255,0.16);
  color: #fff;
}

.custom-input:focus {
  background: rgba(255,255,255,0.12);
  border-color: #22d3ee;
  box-shadow: 0 0 20px rgba(34,211,238,0.12);
}

.btn-gradient {
  background: linear-gradient(90deg, #22d3ee, #6366f1);
  border: none;
  color: white;
  border-radius: 999px;
  padding: 12px 24px;
  transition: transform 0.25s ease, box-shadow 0.25s ease;
}

.btn-gradient:hover {
  transform: translateY(-2px);
  box-shadow: 0 18px 30px rgba(34,211,238,0.25);
}

.custom-modal {
  background: linear-gradient(180deg, rgba(15,23,42,0.97), rgba(8,15,27,0.98));
  border-radius: 20px;
  border: 1px solid rgba(255,255,255,0.1);
  color: #fff;
}

.btn-primary.apply-btn {
  background: linear-gradient(90deg, #22d3ee, #8b5cf6);
  border: none;
}

.card-wrapper,
.feature-box,
.info-box,
.steps .step,
.phase {
  animation: fadeInUp 0.9s ease forwards;
}

/* ICONS */

.icon {
  width: 45px;
  height: 45px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 12px;
  font-size: 20px;
}

.green { background: rgba(34,197,94,0.15); color: #22c55e; }
.blue { background: rgba(59,130,246,0.15); color: #3b82f6; }
.purple { background: rgba(168,85,247,0.15); color: #a855f7; }

.progress-box {
  padding: 25px;
  border-radius: 16px;
  background: linear-gradient(135deg, rgba(255,255,255,0.06), rgba(255,255,255,0.02));
  border: 1px solid rgba(255,255,255,0.08);
}

/* BUTTON */
.apply-btn {
  background: linear-gradient(90deg, #22d3ee, #8b5cf6);
  border: none;
  color: #fff;
  padding: 12px 28px;
  border-radius: 999px;
  font-weight: 600;
}

.bg-purple {
  background-color: #a855f7 !important;
  color: #fff !important;
}

.custom-modal {
  background: linear-gradient(180deg, rgba(15,23,42,0.98), rgba(8,15,27,0.95));
  border-radius: 20px;
  border: 1px solid rgba(255,255,255,0.1);
  color: #fff;
}

.custom-input {
  background: rgba(255,255,255,0.08);
  border: 1px solid rgba(255,255,255,0.16);
  color: #fff;
}

.custom-input:focus {
  background: rgba(255,255,255,0.14);
  border-color: #22d3ee;
  box-shadow: 0 0 18px rgba(34,211,238,0.15);
}

.btn-gradient {
  background: linear-gradient(90deg, #22d3ee, #6366f1);
  border: none;
  color: white;
  border-radius: 999px;
  padding: 12px 24px;
  transition: transform 0.25s ease, box-shadow 0.25s ease;
}

.btn-gradient:hover {
  transform: translateY(-2px);
  box-shadow: 0 18px 30px rgba(34,211,238,0.25);
}

.title {
  font-size: 40px;
}

.subtitle {
  color: #cbd5f5;
}

.section-title {
  color: #e2e8f0;
  font-size: 24px;
  margin-bottom: 24px;
}

.card-visual {
  width: 300px;
  height: 180px;
  background: linear-gradient(135deg, #45ACAB, #6366f1);
  border-radius: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 40px;
}

.feature-box, .info-box {
  padding: 24px;
  border-radius: 20px;
  background: rgba(15,23,42,0.94);
  border: 1px solid rgba(255,255,255,0.12);
  backdrop-filter: blur(16px);
  height: 100%;
}

.feature-box:hover, .info-box:hover {
  transform: translateY(-6px);
}

.steps .step {
  display: flex;
  gap: 18px;
  align-items: flex-start;
  margin-bottom: 20px;
  padding: 20px;
  border-radius: 18px;
  background: rgba(15,23,42,0.92);
  border: 1px solid rgba(255,255,255,0.08);
}

.step span {
  width: 42px;
  height: 42px;
  background: linear-gradient(135deg, #22d3ee, #8b5cf6);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-weight: 700;
}

.timeline {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
  gap: 18px;
}

.phase {
  padding: 24px;
  border-radius: 18px;
  background: linear-gradient(180deg, rgba(59,130,246,0.16), rgba(99,102,241,0.06));
  border: 1px solid rgba(99,102,241,0.16);
}

.phase h6 {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 16px;
}


.card .card-top {
  position: relative;
}

.card .contactless {
  position: absolute;
  right: 0;
  top: 0;
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 12px;
  color: rgba(255,255,255,0.85);
}

.card .contactless i {
  font-size: 18px;
}

.card .logo img {
  filter: drop-shadow(0 0 12px rgba(255,255,255,0.2));
}

.card-number {
  letter-spacing: 3px;
  font-size: 18px;
  margin-top: 18px;
  color: rgba(255,255,255,0.95);
}

.card-bottom p {
  font-size: 14px;
  color: #f8fafc;
}

.card-bottom small {
  font-size: 11px;
  color: rgba(248,250,252,0.75);
}

.back .stripe {
  height: 48px;
  background: linear-gradient(90deg, rgba(255,255,255,0.95), rgba(255,255,255,0.2));
  margin-bottom: 24px;
}

.back .cvv-box span {
  letter-spacing: 1px;
  color: rgba(255,255,255,0.75);
}

.back .cvv {
  background: rgba(255,255,255,0.18);
  color: #fff;
  padding: 8px 14px;
  border-radius: 10px;
  font-weight: 700;
}

.back-text {
  margin-top: 24px;
  font-size: 13px;
  color: rgba(248,250,252,0.75);
}

.card-wrapper,
.feature-box,
.info-box,
.steps .step,
.phase {
  animation: fadeInUp 0.9s ease forwards;
}

.accordion-item {
  background: rgba(15,23,42,0.92);
  border: 1px solid rgba(255,255,255,0.12);
  border-radius: 12px;
  margin-bottom: 12px;
}

.accordion-button {
  background: transparent;
  color: #e2e8f0;
  border: none;
  padding: 16px 20px;
  font-weight: 500;
}

.accordion-button:focus {
  box-shadow: none;
  border-color: #22d3ee;
}

.accordion-button:not(.collapsed) {
  background: rgba(34,211,238,0.1);
  color: #22d3ee;
}

.accordion-body {
  background: rgba(15,23,42,0.95);
  color: #cbd5f5;
  padding: 16px 20px;
  border-top: 1px solid rgba(255,255,255,0.08);
}

@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(18px); }
  to { opacity: 1; transform: translateY(0); }
}

</style>
	
	</head>

	<body class="ltr main-body app sidebar-mini">

		<!-- Loader -->
		<div id="global-loader">
			<img src="img/favicon.png" width="50" class="loader-img" alt="Loader">
		</div>
		<!-- /Loader -->

		<!-- Page -->
		<div class="page">

			<div>
				<?php include('header.php'); ?>
			</div>

			<!-- main-content -->
			<div class="main-content app-content">

				<!-- container -->
				<div class="main-container container-fluid">

					<!-- breadcrumb -->
					<div class="breadcrumb-header justify-content-between">
						<div class="left-content">
						  <span class="main-content-title mg-b-0 mg-b-lg-1">Virtual Cards</span>
						</div>
						<div class="justify-content-center mt-2">
							<ol class="breadcrumb">
								<li class="breadcrumb-item tx-15"><a href="javascript:void(0);">Quantum</a></li>
								<li class="breadcrumb-item active" aria-current="page">Virtual Cards </li>
							</ol>
						</div>
					</div>
					<!-- /breadcrumb -->




<div class="container">

  <!-- HERO -->

  <section class="hero">

    <div class="hero-text fade-in delay-1">

      <h1>Your <span>Virtual Card</span> <br/> is Coming Soon</h1>

      <p>Experience smarter spending with your Quantum Scalp <br/> card—built with full control, top-tier security, and real-time insights. <br/> We’re finalizing the details to bring you a seamless in-app experience.</p>

      <div class="d-flex flex-wrap gap-3 mt-4">
        <span class="badge bg-info rounded-pill px-3 py-2">Instant activation</span>
        <span class="badge bg-primary rounded-pill px-3 py-2">Secure payments</span>
        <span class="badge bg-purple rounded-pill px-3 py-2">Live alerts</span>
      </div>

      <button class="btn btn-primary apply-btn mt-4" data-bs-toggle="modal" data-bs-target="#applyModal">Apply for Card</button>

    </div>

    <!-- CARD -->

    <div class="card-wrapper fade-in delay-2">

      <div class="card" id="card">

        <!-- FRONT -->

        <div class="card-face front">

          <div class="card-top">

            <div class="chip"></div>

            <div class="logo"><img src="img/icon.png" width="40" /></div>

            <!-- <div class="contactless"><i class="las la-wifi"></i>Contactless</div> -->

          </div>

          <div class="card-number">

            •••• •••• •••• <?php echo substr($rows['referal_link'], -4); ?>

          </div>

          <div class="card-bottom">

            <div>

              <small class="text-white">Card Holder</small>

              <p><?php echo $rows['firstname']." ".$rows['lastname']; ?></p>

            </div>

            <div>

              <small class="text-white"><i class="las la-bolt"></i> Ready Soon</small>

              <p>Quantum Scalp</p>

            </div>

          </div>

          <div class="status"></div>

        </div>

        <!-- BACK -->

        <div class="card-face back">

          <div class="stripe"></div>

          <div class="cvv-box">

            <span>CVV</span>

            <div class="cvv">•••</div>

          </div>

          <p class="back-text text-white">

            This card is issued by Quantum Scalp.

          </p>

        </div>

      </div>

    </div>






  </section>

  



<div class="container py-5">

  <!-- FEATURES -->
  <h3 class="section-title">Core Features</h3>

  <div class="row g-4">

    <div class="col-md-4">
      <div class="feature-box">
        <div class="box-icon cyan"><i class="las la-sliders-h"></i></div>
        <h5>Customizable Spending Limits</h5>
        <p>Set personalized limits for shopping, subscriptions, or emergencies.</p>
      </div>
    </div>

    <div class="col-md-4">
      <div class="feature-box">
        <div class="box-icon blue"><i class="las la-exchange-alt"></i></div>
        <h5>Instant Transfers</h5>
        <p>Move funds instantly between your card and accounts.</p>
      </div>
    </div>

    <div class="col-md-4">
      <div class="feature-box">
        <div class="box-icon purple"><i class="las la-bell"></i></div>
        <h5>Smart Alerts</h5>
        <p>Get real-time notifications for transactions and balances.</p>
      </div>
    </div>

    <div class="col-md-4">
      <div class="feature-box">
        <div class="box-icon green"><i class="las la-globe"></i></div>
        <h5>Multi-Currency Support</h5>
        <p>Spend globally without worrying about conversions.</p>
      </div>
    </div>

    <div class="col-md-4">
      <div class="feature-box">
        <div class="box-icon amber"><i class="las la-chart-line"></i></div>
        <h5>Budgeting Tools</h5>
        <p>Track spending and make smarter financial decisions.</p>
      </div>
    </div>

    <div class="col-md-4">
      <div class="feature-box">
        <div class="box-icon pink"><i class="las la-headset"></i></div>
        <h5>24/7 Support</h5>
        <p>Always available whenever you need help.</p>
      </div>
    </div>

  </div>

  <!-- BENEFITS -->
  <h3 class="section-title mt-5">Why Choose Quantum Scalp</h3>

  <div class="row g-4">

    <div class="col-md-4">
      <div class="info-box">
        <div class="box-icon blue"><i class="las la-mobile-alt"></i></div>
        <h6>App Integration</h6>
        <p>Connect with your favorite apps and services effortlessly.</p>
      </div>
    </div>

    <div class="col-md-4">
      <div class="info-box">
        <div class="box-icon purple"><i class="las la-shield-alt"></i></div>
        <h6>Fraud Protection</h6>
        <p>Advanced encryption and monitoring keep your funds safe.</p>
      </div>
    </div>

    <div class="col-md-4">
      <div class="info-box">
        <div class="box-icon amber"><i class="las la-sliders-h"></i></div>
        <h6>Full Customization</h6>
        <p>Control alerts, limits, and card behavior your way.</p>
      </div>
    </div>

  </div>

  <!-- HOW IT WORKS -->
  <h3 class="section-title mt-5">How It Works</h3>

  <div class="steps">

    <div class="step">
      <span>1</span>
      <div>
        <h6><i class="las la-user-check"></i> Easy Signup</h6>
        <p>Sign up in minutes and get started instantly.</p>
      </div>
    </div>

    <div class="step">
      <span>2</span>
      <div>
        <h6><i class="las la-credit-card"></i> Access Your Card</h6>
        <p>View card details anytime in your dashboard.</p>
      </div>
    </div>

    <div class="step">
      <span>3</span>
      <div>
        <h6><i class="las la-wallet"></i> Instant Funding</h6>
        <p>Add funds quickly from supported accounts.</p>
      </div>
    </div>

    <div class="step">
      <span>4</span>
      <div>
        <h6><i class="las la-eye"></i> Real-Time Monitoring</h6>
        <p>Track every transaction live.</p>
      </div>
    </div>

    <div class="step">
      <span>5</span>
      <div>
        <h6><i class="las la-cogs"></i> Full Control</h6>
        <p>Manage limits and settings anytime.</p>
      </div>
    </div>

  </div>

  <!-- SECURITY -->
  <h3 class="section-title mt-5">Security & Privacy</h3>

  <div class="row g-4">

    <div class="col-md-4">
      <div class="info-box">
        <div class="box-icon purple"><i class="las la-lock"></i></div>
        <h6>End-to-End Encryption</h6>
        <p>Your data is always protected.</p>
      </div>
    </div>

    <div class="col-md-4">
      <div class="info-box">
        <div class="box-icon blue"><i class="las la-robot"></i></div>
        <h6>AI Risk Monitoring</h6>
        <p>Detect fraud in real time.</p>
      </div>
    </div>

    <div class="col-md-4">
      <div class="info-box">
        <div class="box-icon blue"><i class="las la-fingerprint"></i></div>
        <h6>Biometric Login</h6>
        <p>Secure access with fingerprint or face ID.</p>
      </div>
    </div>

  </div>

  <!-- ROLLOUT -->
  <h3 class="section-title mt-5">Phased Rollout</h3>

  <div class="timeline">

    <div class="phase">
      <div class="box-icon  mb-3"><i class="las la-user-clock"></i></div>
      <h6>Phase 1</h6>
      <p>Early access for selected users.</p>
    </div>

    <div class="phase">
      <div class="box-icon cyan mb-3"><i class="las la-users"></i></div>
      <h6>Phase 2</h6>
      <p>Expanded access to more users.</p>
    </div>

    <div class="phase">
      <div class="box-icon  mb-3"><i class="las la-gift"></i></div>
      <h6>Phase 3</h6>
      <p>Full launch with rewards and features.</p>
    </div>

  </div>

  <!-- WHAT’S NEXT -->
  <h3 class="section-title mt-5">What’s Next?</h3>
  <div class="info-box">
    <p>We know you're eager to get your hands on the Quantum Scalp card, and we are, too! While we're not quite there yet, we’re committed to delivering a high-quality, reliable experience once everything is ready. Stay tuned for updates, and be the first to know when access opens up.</p>
  </div>

  <!-- STAY CONNECTED -->
  <h3 class="section-title mt-5">Stay Connected</h3>
  <div class="info-box">
    <p>Sign up for updates and be among the first to hear when the Quantum Scalp card is ready for you. We'll also notify you about exclusive early access opportunities!</p>
    <button class="btn btn-gradient mt-3" data-bs-toggle="modal" data-bs-target="#applyModal">Sign Up for Updates</button>
  </div>

  <!-- FAQ -->
  <h3 class="section-title mt-5">Frequently Asked Questions</h3>
  <div class="accordion" id="faqAccordion">
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingOne">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
          When will the Quantum Scalp card be available?
        </button>
      </h2>
      <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
          The card is in development and will be released in phases. We expect early access in the coming months, followed by full availability once we’ve completed testing and optimizations.
        </div>
      </div>
    </div>
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingTwo">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          Is the card ready for use now?
        </button>
      </h2>
      <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
          Not yet! We're in the final stages of development, working hard to make sure everything works perfectly. We’ll keep you updated with the latest news as we get closer to launch.
        </div>
      </div>
    </div>
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingThree">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
          Can I sign up for the card now?
        </button>
      </h2>
      <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
          You can sign up for updates, and we’ll notify you when the card is ready for use or available for early access.
        </div>
      </div>
    </div>
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingFour">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
          Why the phased rollout?
        </button>
      </h2>
      <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
          The phased rollout allows us to ensure that the card and app work perfectly across different systems, making sure all features are stable and secure before making it available to everyone.
        </div>
      </div>
    </div>
  </div>

</div>


<div class="modal fade" id="applyModal" tabindex="-1">

  <div class="modal-dialog modal-dialog-centered">

    <div class="modal-content custom-modal">

      <div class="modal-header border-0">

        <h5 class="modal-title">Apply for Virtual Card</h5>

        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>

      </div>

      <div class="modal-body">

        <form id="cardForm">

          <div class="mb-3">

            <label class="form-label">Full Name</label>

            <input type="text" name="fullname" value="<?php echo $rows['firstname']." ".$rows['lastname']; ?>" class="form-control custom-input" required>

          </div>

          <div class="mb-3">

            <label class="form-label">Email</label>

            <input type="email" name="email" value="<?php echo $rows['email']; ?>" class="form-control custom-input" required>

          </div>

          <div class="mb-3">

            <label class="form-label">Preferred Card Name</label>

            <input type="text" name="cardname" class="form-control custom-input" placeholder="Name on card" required>

          </div>

          <div class="mb-3">

            <label class="form-label">Currency</label>

            <select class="form-control custom-input" required>

              <option value="">Select</option>

              <option>USD</option>

              <option>EUR</option>

              <option>GBP</option>

            </select>

          </div>

          <button type="submit" class="btn btn-gradient w-100 mt-2">

            Submit Application

          </button>

        </form>

      </div>

    </div>

  </div>

</div>



				</div>
				<!-- Container closed -->
			</div>
			<!-- main-content closed -->

			
			<!-- Footer opened -->
			<div class="main-footer">
				<div class="container-fluid pt-0 ht-100p">
					Copyright © <?php echo date('Y'); ?>  All rights reserved
				</div>
			</div>
			<!-- Footer closed -->

		</div>
		<!-- End Page -->

		<!-- Back-to-top -->
		<a href="#top" id="back-to-top"><i class="las la-arrow-up"></i></a>

		<!-- JQuery min js -->
		<script src="assets/plugins/jquery/jquery.min.js"></script>

		<!-- Bootstrap js -->
		<script src="assets/plugins/bootstrap/js/popper.min.js"></script>
		<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

		<!-- Moment js -->
		<script src="assets/plugins/moment/moment.js"></script>

		<!-- P-scroll js -->
		<script src="assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
		<script src="assets/plugins/perfect-scrollbar/p-scroll.js"></script>

		<!-- Internal Data tables -->
		<script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
		<script src="assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>
		<script src="assets/plugins/datatable/js/dataTables.buttons.min.js"></script>
		<script src="assets/plugins/datatable/js/buttons.bootstrap5.min.js"></script>
		<script src="assets/plugins/datatable/js/jszip.min.js"></script>
		<script src="assets/plugins/datatable/pdfmake/pdfmake.min.js"></script>
		<script src="assets/plugins/datatable/pdfmake/vfs_fonts.js"></script>
		<script src="assets/plugins/datatable/js/buttons.html5.min.js"></script>
		<script src="assets/plugins/datatable/js/buttons.print.min.js"></script>
		<script src="assets/plugins/datatable/js/buttons.colVis.min.js"></script>
		<script src="assets/plugins/datatable/dataTables.responsive.min.js"></script>
		<script src="assets/plugins/datatable/responsive.bootstrap5.min.js"></script>
		<script src="assets/js/table-data.js"></script>

		<!-- INTERNAL Select2 js -->
		<script src="assets/plugins/select2/js/select2.full.min.js"></script>

		<!-- Sidebar js -->
		<script src="assets/plugins/side-menu/sidemenu.js"></script>

		<!-- Sticky js -->
		<script src="assets/js/sticky.js"></script>

		<!-- Right-sidebar js -->
		<script src="assets/plugins/sidebar/sidebar.js"></script>
		<script src="assets/plugins/sidebar/sidebar-custom.js"></script>

		<!-- eva-icons js -->
		<script src="assets/js/eva-icons.min.js"></script>

		<!-- Theme Color js -->
		<script src="assets/js/themecolor.js"></script>

		<!-- SweetAlert -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

		<!-- custom js -->
		<script src="assets/js/custom.js"></script>

<script>
 const card = document.getElementById("card");

card.addEventListener("click", () => {
  card.classList.toggle("flipped");
});
</script>

<script>
document.getElementById("cardForm").addEventListener("submit", function(e) {
  e.preventDefault();

  // Close modal
  let modal = bootstrap.Modal.getInstance(document.getElementById('applyModal'));
  modal.hide();

  // Show success alert
  Swal.fire({
    icon: 'success',
    title: 'Application Submitted!',
    text: 'You’ve been added to the card queue. We’ll notify you when processing begins and when your card is ready.',
    confirmButtonColor: '#6366f1'
  });

  // OPTIONAL: Reset form
  this.reset();
});
</script>

	</body>
</html>