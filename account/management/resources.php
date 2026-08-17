<?php
session_start();

include('connection.php');
include('../inc/resources.php');

if (!isset($_SESSION['adminid'])) {
	header("location:index");
	exit;
}

$get_admin = mysqli_query($mysqli, "SELECT * FROM admins WHERE id='" . $_SESSION['adminid'] . "' ");
$rows = mysqli_fetch_assoc($get_admin);

qs_ensure_resources_table($mysqli);

$flash = '';
$flashType = 'success';
$categories = qs_resources_categories();
$docTypes = qs_resources_doc_types();
$iconTypes = qs_resources_icon_types();

function qs_resource_form_values($mysqli, $post) {
	$title = isset($post['title']) ? trim($post['title']) : '';
	$description = isset($post['description']) ? trim($post['description']) : '';
	$category = isset($post['category']) ? strtolower(trim($post['category'])) : 'technology';
	$doc_type = isset($post['doc_type']) ? strtoupper(trim($post['doc_type'])) : 'PRODUCT GUIDE';
	$icon_type = isset($post['icon_type']) ? strtolower(trim($post['icon_type'])) : 'book';
	$cats = qs_resources_categories();
	$icons = qs_resources_icon_types();
	if (!isset($cats[$category])) {
		$category = 'technology';
	}
	if (!in_array($icon_type, $icons, true)) {
		$icon_type = 'book';
	}
	if ($doc_type === '') {
		$doc_type = 'PRODUCT GUIDE';
	}
	return array(
		'title' => mysqli_real_escape_string($mysqli, $title),
		'description' => mysqli_real_escape_string($mysqli, $description),
		'category' => mysqli_real_escape_string($mysqli, $category),
		'doc_type' => mysqli_real_escape_string($mysqli, $doc_type),
		'icon_type' => mysqli_real_escape_string($mysqli, $icon_type),
		'raw_title' => $title,
	);
}

if (isset($_POST['create'])) {
	$vals = qs_resource_form_values($mysqli, $_POST);
	if ($vals['raw_title'] === '') {
		$flash = 'Title is required.';
		$flashType = 'warning';
	} else {
		$fileName = '';
		$filePath = '';
		$original = '';
		if (!empty($_FILES['resource_file']['tmp_name'])) {
			$saved = qs_save_resource_upload($_FILES['resource_file']);
			if (!$saved['ok']) {
				$flash = $saved['error'];
				$flashType = 'warning';
			} else {
				$fileName = mysqli_real_escape_string($mysqli, $saved['file_name']);
				$filePath = mysqli_real_escape_string($mysqli, $saved['file_path']);
				$original = mysqli_real_escape_string($mysqli, $saved['original_name']);
			}
		}
		if ($flash === '') {
			$create = mysqli_query($mysqli, "INSERT INTO resources (`title`, `description`, `category`, `doc_type`, `icon_type`, `file_name`, `file_path`, `original_name`) VALUES('{$vals['title']}', '{$vals['description']}', '{$vals['category']}', '{$vals['doc_type']}', '{$vals['icon_type']}', '$fileName', '$filePath', '$original')");
			if ($create) {
				$flash = 'Resource created.';
			} else {
				$flash = 'Could not create resource.';
				$flashType = 'error';
			}
		}
	}
}

if (isset($_POST['edit'])) {
	$id = (int) $_POST['id'];
	$existing = qs_resource_by_id($mysqli, $id);
	$vals = qs_resource_form_values($mysqli, $_POST);
	if (!$existing || $vals['raw_title'] === '') {
		$flash = 'Title is required.';
		$flashType = 'warning';
	} else {
		$fileName = mysqli_real_escape_string($mysqli, $existing['file_name']);
		$filePath = mysqli_real_escape_string($mysqli, $existing['file_path']);
		$original = mysqli_real_escape_string($mysqli, $existing['original_name']);
		if (!empty($_FILES['resource_file']['tmp_name'])) {
			$saved = qs_save_resource_upload($_FILES['resource_file']);
			if (!$saved['ok']) {
				$flash = $saved['error'];
				$flashType = 'warning';
			} else {
				qs_resource_delete_file($existing);
				$fileName = mysqli_real_escape_string($mysqli, $saved['file_name']);
				$filePath = mysqli_real_escape_string($mysqli, $saved['file_path']);
				$original = mysqli_real_escape_string($mysqli, $saved['original_name']);
			}
		}
		if ($flash === '') {
			$edit = mysqli_query($mysqli, "UPDATE resources SET `title`='{$vals['title']}', `description`='{$vals['description']}', `category`='{$vals['category']}', `doc_type`='{$vals['doc_type']}', `icon_type`='{$vals['icon_type']}', `file_name`='$fileName', `file_path`='$filePath', `original_name`='$original' WHERE id='$id'");
			if ($edit) {
				$flash = 'Resource updated.';
			} else {
				$flash = 'Could not update resource.';
				$flashType = 'error';
			}
		}
	}
}

if (isset($_POST['delete'])) {
	$id = (int) $_POST['id'];
	$existing = qs_resource_by_id($mysqli, $id);
	$del = mysqli_query($mysqli, "DELETE FROM resources WHERE id='$id'");
	if ($del) {
		if ($existing) {
			qs_resource_delete_file($existing);
		}
		$flash = 'Resource deleted.';
	} else {
		$flash = 'Could not delete resource.';
		$flashType = 'error';
	}
}

$resources = qs_resources_list($mysqli);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
	<link rel="icon" href="img/icon.png" type="image/x-icon"/>
	<title>Resources</title>
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
							<h2 class="main-content-title tx-24 mg-b-5">Resources</h2>
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>
								<li class="breadcrumb-item active" aria-current="page">Resources</li>
							</ol>
						</div>
					</div>

					<div class="row clearfix">
						<div class="col-lg-12">
							<div class="card custom-card overflow-hidden">
								<div class="card-body">
									<h5 class="mb-3">Add Resource</h5>
									<form method="POST" enctype="multipart/form-data">
										<div class="row">
											<div class="form-group col-md-6">
												<label>Title</label>
												<input type="text" name="title" class="form-control" required>
											</div>
											<div class="form-group col-md-3">
												<label>Category</label>
												<select name="category" class="form-control">
													<?php foreach ($categories as $slug => $label) { ?>
														<option value="<?php echo htmlspecialchars($slug); ?>"><?php echo htmlspecialchars($label); ?></option>
													<?php } ?>
												</select>
											</div>
											<div class="form-group col-md-3">
												<label>Document Type</label>
												<select name="doc_type" class="form-control">
													<?php foreach ($docTypes as $type) { ?>
														<option value="<?php echo htmlspecialchars($type); ?>"><?php echo htmlspecialchars($type); ?></option>
													<?php } ?>
												</select>
											</div>
											<div class="form-group col-md-3">
												<label>Icon</label>
												<select name="icon_type" class="form-control">
													<?php foreach ($iconTypes as $icon) { ?>
														<option value="<?php echo htmlspecialchars($icon); ?>"><?php echo htmlspecialchars(ucfirst($icon)); ?></option>
													<?php } ?>
												</select>
											</div>
											<div class="form-group col-md-5">
												<label>File</label>
												<input type="file" name="resource_file" class="form-control">
												<small class="text-muted">Saved to resources_upload. PDF, Office, and image files up to 20MB.</small>
											</div>
											<div class="form-group col-md-4">
												<label>Description</label>
												<input type="text" name="description" class="form-control" placeholder="Short summary">
											</div>
											<div class="form-group col-md-12">
												<button type="submit" name="create" class="btn btn-primary">Add Resource</button>
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
													<th>Title</th>
													<th>Category</th>
													<th>Type</th>
													<th>File</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody>
											<?php
											$i = 0;
											foreach ($resources as $item) {
												$i++;
												$url = qs_resource_public_url($item);
											?>
												<tr>
													<td><?php echo $i; ?></td>
													<td><?php echo htmlspecialchars($item['title']); ?></td>
													<td><?php echo htmlspecialchars(isset($categories[$item['category']]) ? $categories[$item['category']] : $item['category']); ?></td>
													<td><?php echo htmlspecialchars($item['doc_type']); ?></td>
													<td>
														<?php if ($url !== ''): ?>
															<a href="../<?php echo htmlspecialchars($url); ?>" target="_blank" rel="noopener"><?php echo htmlspecialchars($item['original_name'] !== '' ? $item['original_name'] : $item['file_path']); ?></a>
														<?php else: ?>
															<span class="text-muted">No file</span>
														<?php endif; ?>
													</td>
													<td>
														<button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editResource<?php echo (int) $item['id']; ?>">Edit</button>
														<form method="POST" style="display:inline" onsubmit="return confirm('Delete this resource?');">
															<input type="hidden" name="id" value="<?php echo (int) $item['id']; ?>">
															<button type="submit" name="delete" class="btn btn-sm btn-danger">Delete</button>
														</form>
													</td>
												</tr>
											<?php } ?>
											</tbody>
										</table>
									</div>
									<?php foreach ($resources as $item) { ?>
									<div class="modal fade" id="editResource<?php echo (int) $item['id']; ?>" tabindex="-1" aria-hidden="true">
										<div class="modal-dialog">
											<div class="modal-content">
												<form method="POST" enctype="multipart/form-data">
													<div class="modal-header">
														<h5 class="modal-title">Edit Resource</h5>
														<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
													</div>
													<div class="modal-body">
														<input type="hidden" name="id" value="<?php echo (int) $item['id']; ?>">
														<div class="form-group">
															<label>Title</label>
															<input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($item['title']); ?>" required>
														</div>
														<div class="form-group">
															<label>Description</label>
															<textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($item['description']); ?></textarea>
														</div>
														<div class="form-group">
															<label>Category</label>
															<select name="category" class="form-control">
																<?php foreach ($categories as $slug => $label) { ?>
																	<option value="<?php echo htmlspecialchars($slug); ?>"<?php echo $item['category'] === $slug ? ' selected' : ''; ?>><?php echo htmlspecialchars($label); ?></option>
																<?php } ?>
															</select>
														</div>
														<div class="form-group">
															<label>Document Type</label>
															<select name="doc_type" class="form-control">
																<?php
																$foundType = false;
																foreach ($docTypes as $type) {
																	$sel = ($item['doc_type'] === $type) ? ' selected' : '';
																	if ($sel) {
																		$foundType = true;
																	}
																	echo '<option value="' . htmlspecialchars($type) . '"' . $sel . '>' . htmlspecialchars($type) . '</option>';
																}
																if (!$foundType && $item['doc_type'] !== '') {
																	echo '<option value="' . htmlspecialchars($item['doc_type']) . '" selected>' . htmlspecialchars($item['doc_type']) . '</option>';
																}
																?>
															</select>
														</div>
														<div class="form-group">
															<label>Icon</label>
															<select name="icon_type" class="form-control">
																<?php foreach ($iconTypes as $icon) { ?>
																	<option value="<?php echo htmlspecialchars($icon); ?>"<?php echo $item['icon_type'] === $icon ? ' selected' : ''; ?>><?php echo htmlspecialchars(ucfirst($icon)); ?></option>
																<?php } ?>
															</select>
														</div>
														<div class="form-group">
															<label>Replace File</label>
															<input type="file" name="resource_file" class="form-control">
															<?php if ($item['file_path'] !== ''): ?>
																<small class="text-muted">Current: <?php echo htmlspecialchars($item['original_name'] !== '' ? $item['original_name'] : $item['file_path']); ?></small>
															<?php endif; ?>
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
