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

		if (($_SESSION['2fa'] == "no" or $_SESSION['2fa'] == "pending") and $rows['2fa'] == 1) {
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
	<title>Quantum FLEX | Quantum Group </title>

	<!-- Favicon -->
	<link rel="icon" href="assets/img/brand/favicon.png" type="image/x-icon" />

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


	<link href="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/css/ion.rangeSlider.min.css"
		rel="stylesheet" />


	<!-- Jquery js-->
	<script src="assets/plugins/jquery/jquery.min.js"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js"></script>

<style>
	  .compensation-wrapper {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* elegant header section with animated gradient */
        .elegant-header {
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
        }
        .elegant-header h2 {
            font-size: 2.2rem;
            font-weight: 800;
            background: linear-gradient(125deg, #0B2B40, #1B7B4E, #2DCB8A);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            letter-spacing: -0.3px;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            gap: 12px;
        }
        .elegant-header h2 i {
            background: none;
            color: #1f8a4c;
            font-size: 2rem;
            animation: floatGlow 3s ease-in-out infinite;
        }
        @keyframes floatGlow {
            0% { transform: translateY(0px); text-shadow: 0 0 0 #2dcb8a; }
            50% { transform: translateY(-5px); text-shadow: 0 0 8px #2dcb8a80; }
            100% { transform: translateY(0px); text-shadow: 0 0 0 #2dcb8a; }
        }
        .header-glow {
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, transparent, #2dcb8a, #1B7B4E, transparent);
            margin: 0.8rem auto 0;
            border-radius: 4px;
        }
        .subhead-text {
            max-width: 680px;
            margin: 1rem auto 0;
            color: #3c6578;
            font-weight: 500;
            font-size: 1rem;
        }

        /* modern card stats (bonus cards) */
        .stat-card {
            
            border: none;
            border-radius: 32px;
            transition: all 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
            box-shadow: 0 10px 25px -8px rgba(0,0,0,0.08);
            backdrop-filter: blur(2px);
            overflow: hidden;
            position: relative;
			border-top: 2px solid #2dcb8a;
			 border-bottom: 2px solid #2dcb8a;
        }
        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 35px -12px rgba(0,0,0,0.2);
            border-bottom: 2px solid #2dcb8a;
        }
        .card-icon-glow {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #eef9f0, #e0f2e9);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
        }
        .bonus-value {
            font-size: 2rem;
            font-weight: 800;
            color: #1f5e43;
            letter-spacing: -0.5px;
        }
        .progress-gentle {
            height: 6px;
            border-radius: 10px;
           
        }
        .progress-bar-grad {
            background: linear-gradient(90deg, #1f8a5c, #44d692);
            border-radius: 10px;
        }

        /* referral & investment cards */
        .glass-card {
            
            border-radius: 28px;
            padding: 1.2rem 1.5rem;
            transition: all 0.25s;
            box-shadow: 0 10px 20px -5px rgba(0,0,0,0.05);
            border: 1px solid #1f8a5c;
        }
        .glass-card:hover {
            transform: scale(1.01);
           
            box-shadow: 0 20px 30px -12px rgba(29, 78, 54, 0.12);
        }

        /* rank cards (plan-box redesign) */
        .rank-card {
           
            border-radius: 2rem;
            transition: all 0.25s ease;
            border: 1px solid rgba(45, 203, 138, 0.2);
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .rank-card:hover {
            transform: translateY(-5px);
            border-color: #2dcb8a70;
            box-shadow: 0 22px 35px -12px rgba(0,0,0,0.12);
        }
        .rank-header {
            background: linear-gradient(105deg, #f8fdfa, #f1faf5);
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e0f0e8;
        }
        .rank-name {
            font-weight: 800;
            font-size: 1.3rem;
            color: #1e4a38;
            letter-spacing: -0.2px;
        }
        .status-badge {
            background: #d9f0e6;
            color: #1b6e48;
            padding: 0.2rem 1rem;
            border-radius: 100px;
            font-size: 0.7rem;
            font-weight: 700;
        }
        .rank-cap {
            font-size: 1.8rem;
            font-weight: 800;
            color: #146b45;
        }
        .feature-row {
            padding: 0.6rem 0.8rem;
            display: flex;
            align-items: baseline;
            gap: 10px;
            border-bottom: 1px dashed #eef3ef;
        }
        .feature-icon {
            min-width: 28px;
            color: #2dcb8a;
            font-size: 0.9rem;
        }
        .feature-text {
            font-size: 0.8rem;
            line-height: 1.35;
           
        }

 


        .slider-container {
            padding: 0.8rem 0.5rem;
         
            border-radius: 20px;
            margin-top: 0.5rem;
        }
        .active-rank-btn {
            background: linear-gradient(95deg, #1c6e48, #25a06a);
            border: none;
            border-radius: 60px;
            padding: 0.3rem 1rem;
            font-weight: 600;
            font-size: 0.7rem;
            color: white;
            display: inline-block;
            text-align: center;
        }
        .inactive-rank-marker {
            background: #eef2f0;
            color: #8aa99b;
            border-radius: 60px;
            padding: 0.3rem 1rem;
            font-size: 0.7rem;
        }
        .mt-auto-end {
            margin-top: auto;
        }
        .desc-list {
            padding-left: 0;
        }
        hr {
            opacity: 0.2;
        }
        @media (max-width: 768px) {
            .bonus-value { font-size: 1.6rem; }
            .rank-name { font-size: 1.1rem; }
        }


        /* Change the filled bar color */
.irs--round .irs-bar {
    background-color: #cbb62d;
}

/* Change the handle (knob) color */
.irs--round .irs-handle {
    border: 4px solid #cbb62d;
}

/* Change the 'from' label background (if you use it) */
.irs--round .irs-from {
    background-color: #cbb62d;
}

/* Change the pointer arrow on the label */
.irs--round .irs-from:before {
    border-top-color: #cbb62d;
}

.irs--round  .irs-single{
     background-color: #cbb62d;
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
						<span class="main-content-title mg-b-0 mg-b-lg-1">Quantum FLEX </span>
					</div>
					<div class="justify-content-center mt-2">
						<ol class="breadcrumb">
							<li class="breadcrumb-item tx-15"><a href="javascript:void(0);">Quantum</a></li>
							<li class="breadcrumb-item active" aria-current="page">Flex </li>
						</ol>
					</div>
				</div>
				<!-- /breadcrumb -->


		<div class="compensation-wrapper">
    <!-- Elegant header with animation -->
    <div class="elegant-header">
        <h2><i class="fas fa-crown"></i> QUALIFICATIONS & COMPENSATIONS <i class="fas fa-chart-line"></i></h2>
        <div class="header-glow"></div>
        <p class="subhead-text">
            QuantumFLEX’s compensation plan is designed to maximize your earning potential. 
            Industry-leading bonus programs & multiple income streams for every Brand Influencer.
        </p>
    </div>

    <!-- BONUS CARDS ROW (fast start, team, direct growth, global points) -->
    <div class="row g-4 mb-5">
        <div class="col-sm-6 col-lg-6 col-xl-3">
            <div class="stat-card p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted text-uppercase small fw-semibold">FAST START BONUS</span>
                        <div class="bonus-value mt-1">$<?php echo $rows['fast_start']; ?><span class="fs-6 text-success ms-1">(+%)</span></div>
                    </div>
                    <div class="card-icon-glow"><i class="fas fa-rocket text-success"></i></div>
                </div>
                <div class="mt-3">
                    <p class="mb-1 small fw-semibold">Total Bonus</p>
                    <div class="progress progress-gentle">
                        <div class="progress-bar progress-bar-grad w-75" role="progressbar"></div>
                    </div>
                    <small class="text-muted d-flex justify-content-between mt-1"><span>1st Week</span><span><i class="far fa-calendar-alt"></i> Sign Up</span></small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-6 col-xl-3">
            <div class="stat-card p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted text-uppercase small fw-semibold">TEAM BONUS</span>
                        <div class="bonus-value mt-1">$<?php echo $rows['team_bonus']; ?><span class="fs-6 text-success ms-1">(+35%)</span></div>
                    </div>
                    <div class="card-icon-glow"><i class="fas fa-users text-primary"></i></div>
                </div>
                <div class="mt-3">
                    <p class="mb-1 small fw-semibold">Total Bonus Pool</p>
                    <div class="progress progress-gentle">
                        <div class="progress-bar progress-bar-grad w-50" style="background: linear-gradient(90deg,#2c7da0,#48bfe3);" role="progressbar"></div>
                    </div>
                    <small class="text-muted">Per Portfolio <i class="fas fa-chart-simple float-end"></i></small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-6 col-xl-3">
            <div class="stat-card p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted text-uppercase small fw-semibold">DIRECT GROWTH BONUS</span>
                        <div class="bonus-value mt-1">$<?php echo $rows['direct_growth']; ?><span class="fs-6 text-success ms-1">(%)</span></div>
                    </div>
                    <div class="card-icon-glow"><i class="fas fa-chart-simple text-warning"></i></div>
                </div>
                <div class="mt-3">
                    <p class="mb-1 small fw-semibold">Current Percentage</p>
                    <div class="progress progress-gentle">
                        <div class="progress-bar bg-secondary wd-50" style="background: linear-gradient(90deg,#a1832c,#e6b422);" role="progressbar"></div>
                    </div>
                    <small class="text-muted">Referral Commission</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-6 col-xl-3">
            <div class="stat-card p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted text-uppercase small fw-semibold">GLOBAL QUANTUM POINTS</span>
                        <div class="bonus-value mt-1">0</div>
                    </div>
                    <div class="card-icon-glow"><i class="fas fa-globe-americas text-info"></i></div>
                </div>
                <div class="mt-3">
                    <p class="mb-1 small fw-semibold">Current Points</p>
                    <div class="progress progress-gentle">
                        <div class="progress-bar bg-danger wd-30" role="progressbar"></div>
                    </div>
                    <small class="text-muted">Per Downline <i class="fas fa-arrow-down float-end"></i></small>
                </div>
            </div>
        </div>
    </div>


    <!-- Referrals & Downline Investment Cards -->
<?php 
$get_users = mysqli_query($mysqli, "SELECT * FROM users WHERE referred='" . $rows['referal_link'] . "' ORDER BY id DESC"); 

// Get user progress summary from the tracking table
$progressSummary = null;
$progressQuery = mysqli_query($mysqli, "SELECT * FROM user_progress_summary WHERE user_id='" . $rows['id'] . "'");
if ($progressQuery && mysqli_num_rows($progressQuery) > 0) {
    $progressSummary = mysqli_fetch_assoc($progressQuery);
}

// Calculate total downline commission
$commission = 0;
function calculateCommission($mysqli, $referralLink, $level, $maxLevels, &$commission) {
    if ($level > $maxLevels) return;
    $getRefer = mysqli_query($mysqli, "SELECT * FROM users WHERE referred='$referralLink'");
    while ($refer = mysqli_fetch_assoc($getRefer)) {
        $getInvestment = mysqli_query($mysqli, "SELECT * FROM investment WHERE userid='{$refer['id']}' AND bonus='0' ORDER BY id DESC");
        while ($in = mysqli_fetch_assoc($getInvestment)) {
            $mainCapital = $in['amount'];
            $commission += $mainCapital;
        }
        calculateCommission($mysqli, $refer['referal_link'], $level + 1, $maxLevels, $commission);
    }
}
$maxLevels = 5;
calculateCommission($mysqli, $rows['referal_link'], 1, $maxLevels, $commission);
?>
<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="glass-card d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-user-plus fa-2x text-success opacity-75"></i>
                <h4 class="mt-2 fw-bold"><?php echo mysqli_num_rows($get_users); ?></h4>
                <span class="text-muted small"><i class="fas fa-star-of-life text-warning"></i> Active Referrals</span>
            </div>
            <div class="text-end">
                <i class="fas fa-chart-line fa-2x text-secondary"></i>
                <div class="mt-2 badge bg-soft-success fs-6">+ growth</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="glass-card d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-coins fa-2x text-primary opacity-75"></i>
                <h4 class="mt-2 fw-bold">$<?php echo number_format($commission, 2); ?></h4>
                <span class="text-muted small"><i class="fas fa-chart-pie"></i> Total Downline Investment</span>
            </div>
            <div class="text-end">
                <i class="fas fa-wallet fa-2x text-info"></i>
                <div class="mt-2 badge bg-soft-primary">portfolio volume</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="glass-card d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-trophy fa-2x text-warning opacity-75"></i>
                <h4 class="mt-2 fw-bold">
                    <?php 
                    if ($progressSummary) {
                        echo $progressSummary['current_level_human'];
                    } else {
                        echo '1';
                    }
                    ?>
                </h4>
                <span class="text-muted small"><i class="fas fa-medal"></i> Current Level</span>
            </div>
            <div class="text-end">
                <i class="fas fa-arrow-up fa-2x text-success"></i>
                <div class="mt-2 badge bg-soft-warning">
                    <?php 
                    if ($progressSummary && $progressSummary['next_level_name']) {
                        echo 'Next: ' . $progressSummary['next_level_name'];
                    } else {
                        echo 'Max Level';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="glass-card d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-percent fa-2x text-info opacity-75"></i>
                <h4 class="mt-2 fw-bold">
                    <?php 
                    if ($progressSummary) {
                        echo number_format($progressSummary['progress_percentage'], 1) . '%';
                    } else {
                        echo '0%';
                    }
                    ?>
                </h4>
                <span class="text-muted small"><i class="fas fa-chart-simple"></i> Progress to Next Level</span>
            </div>
            <div class="text-end">
                <i class="fas fa-circle-check fa-2x text-success"></i>
                <div class="mt-2 badge bg-soft-info">
                    $<?php 
                    if ($progressSummary) {
                        echo number_format($progressSummary['amount_needed_for_next_level']);
                    } else {
                        echo '0';
                    }
                    ?> needed
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Level Progress Bar -->
<?php if ($progressSummary): ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="glass-card p-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <h6 class="mb-0">
                        <i class="fas fa-flag-checkered text-success me-2"></i>
                        Level <?php echo $progressSummary['current_level_human']; ?> - <?php echo $progressSummary['next_level_name']; ?> Progress
                    </h6>
                </div>
                <div>
                    <span class="badge bg-soft-success">
                        $<?php echo number_format($progressSummary['current_level_commission']); ?> / $<?php echo number_format($progressSummary['next_level_amount']); ?>
                    </span>
                </div>
            </div>
            <div class="progress" style="height: 25px; background: rgba(255,255,255,0.1); border-radius: 12px; overflow: hidden;">
                <div class="progress-bar progress-bar-striped progress-bar-animated" 
                     role="progressbar" 
                     style="width: <?php echo $progressSummary['progress_percentage']; ?>%; background: linear-gradient(90deg, #2dcb8a, #1a9b6a);"
                     aria-valuenow="<?php echo $progressSummary['progress_percentage']; ?>" 
                     aria-valuemin="0" 
                     aria-valuemax="100">
                    <?php if ($progressSummary['progress_percentage'] > 10): ?>
                        <?php echo number_format($progressSummary['progress_percentage'], 1); ?>%
                    <?php endif; ?>
                </div>
            </div>
            <?php if ($progressSummary['blocked_by_level1'] == 1): ?>
                <div class="mt-2">
                    <span class="badge bg-warning text-dark">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Blocked: Need $<?php echo number_format($progressSummary['next_level_level1_required']); ?> Level 1 contribution (Current: $<?php echo number_format($progressSummary['level1_total_contribution']); ?>)
                    </span>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- RANKS / PLAN CARDS -->
<div class="row g-4 mt-2">
    <?php 
    $bars = [
        ["amount" => 3500, "level1"=>1000, "bonus"=>200, "name" => "Beginner", "desc" => "1000 being from level 1 <br/>One time payment of 200"],
        ["amount" => 8000, "level1"=>2500, "bonus"=>500, "name" => "Promoter", "desc" => "2,500 being from level 1 <br/>One time payment of 500"],
        ["amount" => 15000, "level1"=>4500, "bonus"=>800, "name" => "Elite", "desc" => "4,500 being from level 1 <br/> One time payment of 800"],
        ["amount" => 35000, "level1"=>10000, "bonus"=>1750, "name" => "Leader", "desc" => "10,000 being from level 1 <br/>One time payment of 1,750 <br/>lifetime weekly payment 70"],
        ["amount" => 70000, "level1"=>20000, "bonus"=>3500, "name" => "Mentor", "desc" => "20,000 being from level 1 <br/>One time payment of 3,500 <br/>lifetime weekly payment 150"],
        ["amount" => 150000, "level1"=>50000, "bonus"=>7500, "name" => "Director", "desc" => "50,000 being from level 1 <br/>One time payment of 7,500 <br/>lifetime weekly payment 350"],
        ["amount" => 250000, "level1"=>100000, "bonus"=>15000, "name" => "Ambassador", "desc" => "100,000 being from level 1 <br/>One time payment of 15,000 <br/>lifetime weekly payment 550"],
        ["amount" => 500000, "level1"=>200000, "bonus"=>25000, "name" => "Master", "desc" => "200,000 being from level 1 <br/>One time payment of 25,000 <br/>lifetime weekly payment 1000"],
        ["amount" => 1000000, "level1"=>300000, "bonus"=>50000, "name" => "Executive", "desc" => "300,000 being from level 1 <br/>One time payment of 50,000 <br/>lifetime weekly payment 1750"],
        ["amount" => 2000000, "level1"=>500000, "bonus"=>150000, "name" => "Visionary", "desc" => "500,000 being from level 1 <br/>One time payment 150,000 <br/>Lifetime daily payment 3,000"],
        ["amount" => 5000000, "level1"=>750000, "bonus"=>300000, "name" => "Legend", "desc" => "750,000 being from level 1 <br/>One time payment 300,000 <br/>Lifetime daily payment 6,000"],
        ["amount" => 12000000, "level1"=>1000000, "bonus"=>700000, "name" => "Director X", "desc" => "1,000,000 being from level 1 <br/>One time payment 700,000 <br/>Lifetime daily payment 10,000"]
    ];
    
    // Get the user's current level from progress summary
    $currentLevelIndex = $progressSummary ? intval($progressSummary['current_level']) : 0;
    $currentLevelHuman = $progressSummary ? intval($progressSummary['current_level_human']) : 1;
    
    for ($i = 0; $i < count($bars); $i++): 
        $humanLevel = $i + 1;
        $isAchieved = ($progressSummary && $i < $currentLevelIndex);
        $isCurrent = ($progressSummary && $i == $currentLevelIndex);
        $isLocked = ($i > $currentLevelIndex);
        
        // Calculate progress for this specific level
        $levelProgress = 0;
        $levelAmount = $bars[$i]['amount'];
        if ($i == $currentLevelIndex && $progressSummary) {
            $levelProgress = min(100, ($progressSummary['current_level_commission'] / $levelAmount) * 100);
        } elseif ($i < $currentLevelIndex) {
            $levelProgress = 100;
        }
    ?>
    <div class="col-xl-6 col-md-6">
        <div class="rank-card <?php echo $isAchieved ? 'achieved' : ($isCurrent ? 'current' : 'locked'); ?>" style="border-left: 4px solid <?php echo $isAchieved ? '#2dcb8a' : ($isCurrent ? '#ffc107' : '#6c757d'); ?>;">
            <div class="rank-header d-flex justify-content-between align-items-center">
                <div>
                    <span class="rank-name">
                        <i class="fas fa-medal me-2 <?php echo $isAchieved ? 'text-success' : ($isCurrent ? 'text-warning' : 'text-muted'); ?>"></i>
                        Level <?php echo $humanLevel; ?>: <?php echo $bars[$i]['name']; ?>
                    </span>
                </div>
                <?php if($isAchieved): ?>
                    <span class="status-badge"><i class="fas fa-check-circle"></i> ACHIEVED</span>
                <?php elseif($isCurrent): ?>
                    <span class="status-badge bg-warning text-dark"><i class="fas fa-arrow-right"></i> IN PROGRESS</span>
                <?php else: ?>
                    <span class="inactive-rank-marker"><i class="fas fa-lock"></i> LOCKED</span>
                <?php endif; ?>
            </div>
            <div class="p-4">
                <div class="d-flex justify-content-between align-items-end">
                    <div>
                        <small class="text-muted text-uppercase">Portfolio Cap</small>
                        <div class="rank-cap">$<?php echo number_format($bars[$i]['amount']); ?></div>
                        <?php if ($i < $currentLevelIndex): ?>
                            <small class="text-success"><i class="fas fa-check-circle"></i> Completed</small>
                        <?php elseif ($i == $currentLevelIndex && $progressSummary): ?>
                            <small class="text-warning">
                                $<?php echo number_format($progressSummary['current_level_commission']); ?> / $<?php echo number_format($bars[$i]['amount']); ?>
                            </small>
                        <?php endif; ?>
                    </div>
                    <div class="text-end">
                        <i class="fas fa-chart-line fa-2x opacity-25"></i>
                        <?php if ($isAchieved): ?>
                            <div class="mt-1 text-success"><i class="fas fa-trophy"></i> Bonus: $<?php echo number_format($bars[$i]['bonus']); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Progress bar for this level -->
                <div class="mt-3">
                    <div class="progress" style="height: 8px; background: <?php echo $isLocked ? 'rgba(255,255,255,0.05)' : 'rgba(255,255,255,0.1)'; ?>; border-radius: 4px; overflow: hidden;">
                        <div class="progress-bar" 
                             role="progressbar" 
                             style="width: <?php echo $levelProgress; ?>%; background: <?php echo $isAchieved ? '#2dcb8a' : ($isCurrent ? '#ffc107' : '#6c757d'); ?>;"
                             aria-valuenow="<?php echo $levelProgress; ?>" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                        </div>
                    </div>
                </div>
                
                <!-- Description with icons -->
                <div class="mt-3 desc-list">
                    <?php 
                        $descLines = explode('<br/>', $bars[$i]['desc']);
                        foreach($descLines as $line): if(trim($line) != ""): ?>
                            <div class="feature-row">
                                <div class="feature-icon"><i class="fas fa-circle-check <?php echo $isAchieved ? 'text-success' : ($isCurrent ? 'text-warning' : 'text-muted'); ?>"></i></div>
                                <div class="feature-text"><?php echo $line; ?></div>
                            </div>
                    <?php endif; endforeach; ?>
                    
                    <!-- Level1 requirement display -->
                    <div class="feature-row">
                        <div class="feature-icon"><i class="fas fa-users <?php echo $isAchieved ? 'text-success' : ($isCurrent ? 'text-warning' : 'text-muted'); ?>"></i></div>
                        <div class="feature-text">
                            Level 1 Required: $<?php echo number_format($bars[$i]['level1']); ?>
                            <?php if ($i == $currentLevelIndex && $progressSummary): ?>
                                <span class="badge bg-<?php echo $progressSummary['level1_total_contribution'] >= $bars[$i]['level1'] ? 'success' : 'warning'; ?> ms-2">
                                    Current: $<?php echo number_format($progressSummary['level1_total_contribution']); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <?php if($isAchieved): ?>
                    <div class="mt-3 text-center">
                        <span class="active-rank-btn"><i class="fas fa-trophy"></i> Achieved - Bonus $<?php echo number_format($bars[$i]['bonus']); ?></span>
                    </div>
                <?php elseif($isCurrent): ?>
                    <div class="mt-3 text-center">
                        <span class="badge bg-warning text-dark p-2">
                            <i class="fas fa-arrow-up"></i> 
                            <?php 
                            if ($progressSummary && $progressSummary['blocked_by_level1'] == 1) {
                                echo 'Blocked: Need more Level 1 contributions';
                            } else {
                                echo $levelProgress >= 100 ? 'Ready to unlock!' : 'Progressing...';
                            }
                            ?>
                        </span>
                    </div>
                <?php else: ?>
                    <div class="mt-3 text-center">
                        <span class="inactive-rank-marker">Requires $<?php echo number_format($bars[$i]['amount']); ?> cap</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endfor; ?>
</div>

<!-- Milestones Achieved Summary -->
<?php if ($progressSummary && $progressSummary['current_level'] > 0): ?>
<div class="row mt-4">
    <div class="col-12">
        <div class="glass-card p-4">
            <h5 class="mb-3"><i class="fas fa-trophy text-warning me-2"></i>Milestones Achieved</h5>
            <div class="d-flex flex-wrap gap-2">
                <?php 
                $achievedLevels = [];
                for ($i = 0; $i < $progressSummary['current_level']; $i++) {
                    if ($i < count($bars)) {
                        $achievedLevels[] = $bars[$i];
                    }
                }
                foreach ($achievedLevels as $index => $level): 
                ?>
                <span class="badge bg-success p-2">
                    <i class="fas fa-check-circle me-1"></i>
                    Level <?php echo $index + 1; ?>: <?php echo $level['name']; ?> 
                    (+$<?php echo number_format($level['bonus']); ?>)
                </span>
                <?php endforeach; ?>
                
                <?php if (empty($achievedLevels)): ?>
                    <span class="text-muted">No milestones achieved yet. Start building your downline!</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="mt-5 text-center small text-muted mb-4">
    <i class="fas fa-chart-simple"></i> Compensation values updated in real‑time • Unlock higher tiers by growing your downline volume
</div>

<script>
// Reinitialize sliders for all ranks
$(document).ready(function() {
    <?php for ($i = 0; $i < count($bars); $i++): 
        $levelProgress = 0;
        if ($i < $currentLevelIndex) {
            $levelProgress = 100;
        } elseif ($i == $currentLevelIndex && $progressSummary) {
            $levelProgress = min(100, ($progressSummary['current_level_commission'] / $bars[$i]['amount']) * 100);
        }
    ?>
    $("#range_<?php echo $i; ?>").ionRangeSlider({
        skin: "round",
        min: 0,
        max: <?php echo $bars[$i]['amount']; ?>,
        from: <?php echo ($levelProgress / 100) * $bars[$i]['amount']; ?>,
        from_fixed: true,
        to_fixed: true,
        hide_min_max: true,
        grid: false,
        color: {
            from: '<?php echo $i < $currentLevelIndex ? '#2dcb8a' : ($i == $currentLevelIndex ? '#ffc107' : '#6c757d'); ?>',
            to: '<?php echo $i < $currentLevelIndex ? '#2dcb8a' : ($i == $currentLevelIndex ? '#ffc107' : '#6c757d'); ?>',
            range: '<?php echo $i < $currentLevelIndex ? '#2dcb8a' : ($i == $currentLevelIndex ? '#ffc107' : '#6c757d'); ?>'
        }
    });
    <?php endfor; ?>
});
</script>

<style>
/* Enhanced Rank Card Styles */
.rank-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border-radius: 16px;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.08);
    overflow: hidden;
}

.rank-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
}

.rank-card.achieved {
    border-color: #2dcb8a !important;
    background: rgba(45, 203, 138, 0.08);
}

.rank-card.current {
    border-color: #ffc107 !important;
    background: rgba(255, 193, 7, 0.08);
}

.rank-card.locked {
    opacity: 0.7;
}

.rank-header {
    padding: 15px 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.rank-name {
    font-weight: 600;
    font-size: 1.1rem;
}

.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    background: rgba(45, 203, 138, 0.15);
    color: #2dcb8a;
}

.inactive-rank-marker {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    background: rgba(108, 117, 125, 0.15);
    color: #6c757d;
}

.rank-cap {
    font-size: 1.5rem;
    font-weight: 700;
    margin-top: 4px;
}

.desc-list {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.feature-row {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 0.85rem;
}

.feature-icon {
    width: 20px;
    text-align: center;
}

.active-rank-btn {
    display: inline-block;
    padding: 6px 20px;
    border-radius: 20px;
    background: linear-gradient(135deg, #2dcb8a, #1a9b6a);
    color: white;
    font-weight: 600;
    font-size: 0.85rem;
}

.glass-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border-radius: 16px;
    padding: 20px;
    border: 1px solid rgba(255, 255, 255, 0.08);
    transition: all 0.3s ease;
}

.glass-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.progress {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
}

.bg-soft-success {
    background: rgba(45, 203, 138, 0.15);
    color: #2dcb8a;
}

.bg-soft-primary {
    background: rgba(13, 110, 253, 0.15);
    color: #0d6efd;
}

.bg-soft-warning {
    background: rgba(255, 193, 7, 0.15);
    color: #ffc107;
}

.bg-soft-info {
    background: rgba(13, 202, 240, 0.15);
    color: #0dcaf0;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .rank-cap {
        font-size: 1.2rem;
    }
    .rank-name {
        font-size: 0.95rem;
    }
}
</style>

</div>


				<!-- End Row -->
			</div>
			<!-- Container closed -->
		</div>
		<!-- main-content closed -->







		<div class="modal fade" id="welcome" tabindex="-1" data-bs-backdrop='static' data-bs-keyboard="false"
			role="dialog" aria-labelledby="with" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id=""> Welcome to Quantum Flex!!</h5>
						<!-- <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button> -->
					</div>
					<div class="modal-body">

						<img src="img/qflex.png" style="width:100%" /><br /><br />

						<form method="POST">



							<p>Welcome to QuantumFLEX, an Epic company! Congratulations on your choice to become an
								Quantum FLEX promoter. With no cost to begin and no prior experience required, you have
								the freedom to work on your own schedule and tailor your business to your unique
								lifestyle and needs. <br /></p>


							<button class="btn btn-primary mt-3" name="lupa-flex" type="submit">Get Started</button>






						</form>

					</div>

				</div>
			</div>
		</div>




		<!-- Footer opened -->
		<div class="main-footer">
			<div class="container-fluid pt-0 ht-100p">
				Copyright © <?php echo date('Y'); ?> All rights reserved
			</div>
		</div>
		<!-- Footer closed -->

	</div>
	<!-- End Page -->

	<!-- Back-to-top -->
	<a href="#top" id="back-to-top"><i class="las la-arrow-up"></i></a>

	<!-- JQuery min js -->

	<!-- Bootstrap js -->
	<script src="assets/plugins/bootstrap/js/popper.min.js"></script>
	<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

	<!-- Moment js -->
	<script src="assets/plugins/moment/moment.js"></script>

	<!-- P-scroll js -->
	<script src="assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
	<script src="assets/plugins/perfect-scrollbar/p-scroll.js"></script>



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

	<!-- custom js -->
	<script src="assets/js/custom.js"></script>

	<script>
		<?php if ($rows['lupa_flex'] == 0) { ?>
			$(document).ready(function () {

				$("#welcome").modal('show');

			});

		<?php } ?>
	</script>
</body>

<?php

if (isset($_POST['lupa-flex'])) {

	$userid = $rows['id'];


	$updaetUsder = mysqli_query($mysqli, "UPDATE `users` SET `lupa_flex`='1', lupa_flex_date=now()  WHERE id='$userid'");

	?>
	<script>
		location = location
	</script>
	<?php

}



?>

</html>