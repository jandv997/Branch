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

$activities = array();
$chartByDay = array();
$totalAmount = 0.0;
$creditedCount = 0;
$pendingCount = 0;
if (isset($rows['id'])) {
	$get = mysqli_query($mysqli,"SELECT * FROM activity WHERE userid='".$rows['id']."' ORDER BY id DESC");
	if ($get) {
		while ($row = mysqli_fetch_assoc($get)) {
			$activities[] = $row;
			$amount = isset($row['amount']) ? (float) $row['amount'] : 0;
			$totalAmount += $amount;
			$status = isset($row['status']) ? $row['status'] : '';
			if ($status === 'Credited' || $status === 'Confirmed' || $status === 'Successful') {
				$creditedCount++;
			} elseif ($status === 'Pending' || $status === 'Pending Confirmation') {
				$pendingCount++;
			}
			$dayKey = date('Y-m-d', strtotime($row['date']));
			if (!isset($chartByDay[$dayKey])) {
				$chartByDay[$dayKey] = 0.0;
			}
			$chartByDay[$dayKey] += $amount;
		}
	}
}

$chartLabels = array();
$chartValues = array();
for ($d = 13; $d >= 0; $d--) {
	$dayKey = date('Y-m-d', strtotime('-' . $d . ' days'));
	$chartLabels[] = date('M j', strtotime($dayKey));
	$chartValues[] = isset($chartByDay[$dayKey]) ? round($chartByDay[$dayKey], 2) : 0;
}

?>
<!DOCTYPE html>
<html lang="en">
	<head>

		<meta charset="UTF-8">
		<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
	

		<!-- Title -->
		<title>Activities | Quantum Scalp </title>

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

	</head>

	<body class="ltr main-body app sidebar-mini dark-theme">

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
				<div class="main-container container-fluid" >

					<div class="breadcrumb-header justify-content-between">
						<div class="left-content">
						  <span class="main-content-title mg-b-0 mg-b-lg-1">Account Activities</span>
						</div>
						<div class="justify-content-center mt-2">
							<ol class="breadcrumb">
								<li class="breadcrumb-item tx-15"><a href="javascript:void(0);">Quantum</a></li>
								<li class="breadcrumb-item active" aria-current="page">Activities </li>
							</ol>
						</div>
					</div>

					<div class="qs-act-kpis">
						<div class="qs-act-kpi"><span>Total records</span><strong><?php echo number_format(count($activities)); ?></strong></div>
						<div class="qs-act-kpi"><span>Credited</span><strong><?php echo number_format($creditedCount); ?></strong></div>
						<div class="qs-act-kpi"><span>Pending</span><strong><?php echo number_format($pendingCount); ?></strong></div>
						<div class="qs-act-kpi"><span>Volume</span><strong>$<?php echo number_format($totalAmount, 2); ?></strong></div>
					</div>

					<div class="row row-sm mb-4">
						<div class="col-xl-5 col-lg-5 col-md-12">
							<div class="qs-act-photo card custom-card">
								<img src="img/transactions.jpg" alt="Account activities">
							</div>
						</div>
						<div class="col-xl-7 col-lg-7 col-md-12">
							<div class="card custom-card qs-act-chart-card">
								<div class="card-body">
									<div class="qs-act-chart-head">
										<div>
											<h6 class="main-content-label mb-1">Activity volume</h6>
											<p class="text-muted card-sub-title mb-0">Last 14 days of recorded amounts</p>
										</div>
									</div>
									<div class="qs-act-chart-wrap">
										<canvas id="qsActivityChart" height="220"></canvas>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row row-sm">
						<div class="col-lg-12">
							<div class="card custom-card overflow-hidden qs-act-table-card">
								<div class="card-body">
									<div class="qs-act-table-head">
										<h6 class="main-content-label mb-1">Activity log</h6>
										<p class="text-muted card-sub-title">Search, sort, and export your account activity.</p>
									</div>
									<div class="table-responsive export-table">
										<table id="qs-activity-table" class="table table-bordered text-nowrap key-buttons border-bottom">
											<thead>
												<tr>
													<th class="border-bottom-0">S/N</th>
													<th class="border-bottom-0">Action</th>
													<th class="border-bottom-0">Date</th>
													<th class="border-bottom-0">Amount</th>
													<th class="border-bottom-0">Status</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$i = 0;
												foreach ($activities as $row) {
													$i++;
													$type = 'badge-secondary bg-secondary';
													if ($row['status']=="Credited" || $row['status']=="Confirmed" || $row['status']=="Successful") {
														$type ="badge-success bg-primary";
													} elseif ($row['status']=="Pending" || $row['status']=="Pending Confirmation") {
														$type ="badge-danger bg-danger";
													}
												?>
												<tr>
													<td><?php echo $i; ?></td>
													<td><?php echo htmlspecialchars($row['action']); ?></td>
													<td><?php echo htmlspecialchars($row['date']); ?></td>
													<td>$<?php echo number_format((float) $row['amount'], 2); ?></td>
													<td><span class="badge <?php echo $type; ?>"><?php echo htmlspecialchars($row['status']); ?></span></td>
												</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>
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

		<!-- Internal Chart.Bundle js-->
		<script src="assets/plugins/chart.js/Chart.bundle.min.js"></script>

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
		window.qsActivityChart = {
			labels: <?php echo json_encode($chartLabels); ?>,
			values: <?php echo json_encode($chartValues); ?>
		};
		$(function () {
			if (window.Chart && document.getElementById('qsActivityChart')) {
				new Chart(document.getElementById('qsActivityChart').getContext('2d'), {
					type: 'line',
					data: {
						labels: window.qsActivityChart.labels,
						datasets: [{
							label: 'Amount',
							data: window.qsActivityChart.values,
							borderColor: '#2dd4bf',
							backgroundColor: 'rgba(45,212,191,0.14)',
							borderWidth: 2,
							pointRadius: 3,
							pointBackgroundColor: '#2dd4bf',
							lineTension: 0.35
						}]
					},
					options: {
						legend: { display: false },
						maintainAspectRatio: false,
						tooltips: {
							callbacks: {
								label: function (item) {
									return '$' + Number(item.yLabel).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
								}
							}
						},
						scales: {
							xAxes: [{ gridLines: { color: 'rgba(255,255,255,0.04)' }, ticks: { fontColor: '#64748b' } }],
							yAxes: [{ gridLines: { color: 'rgba(255,255,255,0.04)' }, ticks: { fontColor: '#64748b', beginAtZero: true } }]
						}
					}
				});
			}

			if ($.fn.DataTable) {
				var table = $('#qs-activity-table').DataTable({
					responsive: true,
					pageLength: 10,
					order: [[0, 'asc']],
					buttons: ['copy', 'excel', 'pdf', 'colvis'],
					language: {
						searchPlaceholder: 'Search activities...',
						sSearch: '',
						lengthMenu: 'Show _MENU_ records',
						zeroRecords: 'No account activity yet.',
						info: 'Showing _START_ to _END_ of _TOTAL_ activities',
						infoEmpty: 'No activities to show'
					}
				});
				table.buttons().container().appendTo('#qs-activity-table_wrapper .col-md-6:eq(0)');
			}
		});
		</script>

	</body>
</html>
