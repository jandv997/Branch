<?php
session_start();

include('connection.php');
include('../inc/payment-wallets.php');

if (!isset($_SESSION['adminid'])) {
	header("location:index");
	exit;
}

$get_admin = mysqli_query($mysqli, "SELECT * FROM admins WHERE id='" . $_SESSION['adminid'] . "' ");
$rows = mysqli_fetch_assoc($get_admin);

qs_ensure_payment_wallets_table($mysqli);

$flash = '';
$flashType = 'success';

if (isset($_POST['create'])) {
	$result = qs_payment_method_create(
		$mysqli,
		isset($_POST['name']) ? $_POST['name'] : '',
		isset($_POST['wallet_address']) ? $_POST['wallet_address'] : '',
		isset($_POST['code']) ? $_POST['code'] : ''
	);
	$flash = $result['message'];
	$flashType = $result['ok'] ? 'success' : 'warning';
}

if (isset($_POST['edit'])) {
	$result = qs_payment_method_update(
		$mysqli,
		isset($_POST['id']) ? $_POST['id'] : 0,
		isset($_POST['name']) ? $_POST['name'] : '',
		isset($_POST['wallet_address']) ? $_POST['wallet_address'] : '',
		isset($_POST['code']) ? $_POST['code'] : ''
	);
	$flash = $result['message'];
	$flashType = $result['ok'] ? 'success' : 'warning';
}

if (isset($_POST['delete'])) {
	$result = qs_payment_method_delete($mysqli, isset($_POST['id']) ? $_POST['id'] : 0);
	$flash = $result['message'];
	$flashType = $result['ok'] ? 'success' : 'warning';
}

$wallets = qs_payment_wallets($mysqli);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
	<link rel="icon" href="img/icon.png" type="image/x-icon"/>
	<title>Payment Wallets</title>
	<link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="assets/web-fonts/icons.css" rel="stylesheet"/>
	<link href="assets/web-fonts/font-awesome/font-awesome.min.css" rel="stylesheet">
	<link href="assets/web-fonts/plugin.css" rel="stylesheet"/>
	<link href="assets/css/style.css" rel="stylesheet">
	<link href="assets/css/plugins.css" rel="stylesheet">
	<link rel="stylesheet" href="swal/sweetalert2.min.css">
</head>
<body class="main-body leftmenu ltr light-theme dark-menu">
	<div id="global-loader">
		<img src="assets/img/loader.svg" class="loader-img" alt="Loader">
	</div>
	<div class="page">
		<?php include('header.php'); ?>
		<div class="main-content side-content pt-0">
			<div class="main-container container-fluid">
				<div class="inner-body">
					<div class="page-header">
						<div>
							<h2 class="main-content-title tx-24 mg-b-5">Payment Wallets</h2>
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="payment-history">Payments</a></li>
								<li class="breadcrumb-item active" aria-current="page">Payment Wallets</li>
							</ol>
						</div>
					</div>

					<div class="row clearfix">
						<div class="col-lg-12">
							<div class="card custom-card overflow-hidden">
								<div class="card-body">
									<h5 class="mb-3">Add Payment Wallet</h5>
									<form method="POST">
										<div class="row">
											<div class="form-group col-md-3">
												<label>Name</label>
												<input type="text" name="name" class="form-control" placeholder="e.g. USDT TRC20" required>
											</div>
											<div class="form-group col-md-2">
												<label>Code</label>
												<input type="text" name="code" class="form-control" placeholder="e.g. usdt(trc20)">
											</div>
											<div class="form-group col-md-5">
												<label>Wallet Address</label>
												<input type="text" name="wallet_address" class="form-control" placeholder="Enter wallet address" required>
											</div>
											<div class="form-group col-md-2 d-flex align-items-end">
												<button type="submit" name="create" class="btn btn-primary w-100">Add Wallet</button>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>

					<div class="row row-sm mt-3">
						<div class="col-lg-12">
							<div class="card custom-card overflow-hidden">
								<div class="card-body">
									<div class="table-responsive">
										<table class="table table-bordered text-nowrap key-buttons border-bottom">
											<thead>
												<tr>
													<th>S/N</th>
													<th>Name</th>
													<th>Code</th>
													<th>Wallet Address</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody>
											<?php
											$i = 0;
											foreach ($wallets as $wallet) {
												$i++;
											?>
												<tr>
													<td><?php echo $i; ?></td>
													<td><?php echo htmlspecialchars($wallet['name']); ?></td>
													<td><?php echo htmlspecialchars(isset($wallet['code']) ? $wallet['code'] : ''); ?></td>
													<td><?php echo htmlspecialchars($wallet['wallet_address']); ?></td>
													<td>
														<button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editWallet<?php echo (int) $wallet['id']; ?>">Edit</button>
														<form method="POST" style="display:inline" onsubmit="return confirm('Delete this payment wallet?');">
															<input type="hidden" name="id" value="<?php echo (int) $wallet['id']; ?>">
															<button type="submit" name="delete" class="btn btn-sm btn-danger">Delete</button>
														</form>
													</td>
												</tr>
											<?php } ?>
											</tbody>
										</table>
									</div>
									<?php foreach ($wallets as $wallet) { ?>
									<div class="modal fade" id="editWallet<?php echo (int) $wallet['id']; ?>" tabindex="-1" aria-hidden="true">
										<div class="modal-dialog">
											<div class="modal-content">
												<form method="POST">
													<div class="modal-header">
														<h5 class="modal-title">Edit Payment Wallet</h5>
														<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
													</div>
													<div class="modal-body">
														<input type="hidden" name="id" value="<?php echo (int) $wallet['id']; ?>">
														<div class="form-group">
															<label>Name</label>
															<input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($wallet['name']); ?>" required>
														</div>
														<div class="form-group">
															<label>Code</label>
															<input type="text" name="code" class="form-control" value="<?php echo htmlspecialchars(isset($wallet['code']) ? $wallet['code'] : ''); ?>">
														</div>
														<div class="form-group">
															<label>Wallet Address</label>
															<input type="text" name="wallet_address" class="form-control" value="<?php echo htmlspecialchars($wallet['wallet_address']); ?>" required>
														</div>
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
														<button type="submit" name="edit" class="btn btn-primary">Save</button>
													</div>
												</form>
											</div>
										</div>
									</div>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="main-footer text-center">
			<div class="container">
				<div class="row row-sm">
					<div class="col-md-12">
						<span>Copyright © <?php echo date('Y'); ?> All rights reserved.</span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<a href="#top" id="back-to-top"><i class="fe fe-arrow-up"></i></a>
	<script src="assets/plugins/jquery/jquery.min.js"></script>
	<script src="assets/plugins/bootstrap/js/popper.min.js"></script>
	<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
	<script src="assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
	<script src="assets/plugins/perfect-scrollbar/pscroll1.js"></script>
	<script src="assets/plugins/sidemenu/sidemenu.js"></script>
	<script src="assets/plugins/sidebar/sidebar.js"></script>
	<script src="assets/js/themeColors.js"></script>
	<script src="assets/js/sticky.js"></script>
	<script src="assets/js/custom.js"></script>
	<script src="swal/sweetalert2.min.js"></script>
	<?php if ($flash !== '') { ?>
	<script>
		Swal.fire({
			icon: '<?php echo $flashType === 'error' ? 'error' : ($flashType === 'warning' ? 'warning' : 'success'); ?>',
			title: '<?php echo addslashes($flash); ?>'
		});
	</script>
	<?php } ?>
</body>
</html>
