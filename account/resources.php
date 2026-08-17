<?php
session_start();

include('connection.php');
include_once('inc/resources.php');

if (!isset($_SESSION['id'])) {
	header("location:index");
	exit;
}

$get_user = mysqli_query($mysqli, "SELECT * FROM users WHERE id='" . $_SESSION['id'] . "' ");
$rows = mysqli_fetch_assoc($get_user);
if (isset($_SESSION['2fa'])) {
	if (($_SESSION['2fa'] == "no" or $_SESSION['2fa'] == "pending") and $rows['2fa'] == 1) {
		header("location:index");
		exit;
	}
}

$resourceList = qs_resources_list($mysqli);
$categoryLabels = qs_resources_categories();
$categoryCounts = array('all' => count($resourceList));
foreach ($categoryLabels as $slug => $label) {
	$categoryCounts[$slug] = 0;
}
foreach ($resourceList as $item) {
	$slug = strtolower($item['category']);
	if (!isset($categoryCounts[$slug])) {
		$categoryCounts[$slug] = 0;
	}
	$categoryCounts[$slug]++;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Resources | Quantum Scalp</title>
	<link rel="icon" href="assets/img/brand/favicon.png" type="image/x-icon" />
	<link href="assets/css/icons.css" rel="stylesheet">
	<link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="assets/css/style.css" rel="stylesheet">
	<link href="assets/css/style-dark.css" rel="stylesheet">
	<link href="assets/css/style-transparent.css" rel="stylesheet">
	<link href="assets/css/skin-modes.css" rel="stylesheet" />
	<link href="assets/css/animate.css" rel="stylesheet">
</head>
<body class="ltr main-body app sidebar-mini dark-theme">
	<div id="global-loader">
		<img src="img/favicon.png" width="50" class="loader-img" alt="Loader">
	</div>
	<div class="page">
		<div>
			<?php include('header.php'); ?>
		</div>
		<div class="main-content app-content">
			<div class="main-container container-fluid">
				<div class="qs-res">
					<input type="search" class="qs-res-search" data-qs-res-search placeholder="Search the resource library..." autocomplete="off">
					<div class="qs-res-pills">
						<button type="button" class="qs-res-pill is-active" data-qs-res-cat="all">All (<?php echo (int) $categoryCounts['all']; ?>)</button>
						<?php foreach ($categoryLabels as $slug => $label): ?>
							<button type="button" class="qs-res-pill" data-qs-res-cat="<?php echo htmlspecialchars($slug); ?>"><?php echo htmlspecialchars($label); ?> (<?php echo (int) $categoryCounts[$slug]; ?>)</button>
						<?php endforeach; ?>
					</div>
					<div class="qs-res-grid" data-qs-res-grid>
						<?php if (count($resourceList) === 0): ?>
							<p class="qs-res-empty">No resources have been published yet.</p>
						<?php else: ?>
							<?php foreach ($resourceList as $item):
								$cat = strtolower($item['category']);
								$catLabel = isset($categoryLabels[$cat]) ? $categoryLabels[$cat] : ucfirst($item['category']);
								$url = qs_resource_public_url($item);
								$searchBlob = strtolower($item['title'] . ' ' . $item['description'] . ' ' . $catLabel . ' ' . $item['doc_type']);
							?>
							<article class="qs-res-card" data-qs-res-card data-category="<?php echo htmlspecialchars($cat); ?>" data-search="<?php echo htmlspecialchars($searchBlob); ?>">
								<span class="qs-res-type"><?php echo htmlspecialchars($item['doc_type']); ?></span>
								<div class="qs-res-icon"><?php echo qs_resource_icon_svg($item['icon_type']); ?></div>
								<p class="qs-res-cat"><?php echo htmlspecialchars($catLabel); ?></p>
								<h3><?php echo htmlspecialchars($item['title']); ?></h3>
								<p><?php echo htmlspecialchars($item['description']); ?></p>
								<?php if ($url !== ''): ?>
									<a class="qs-res-open" href="<?php echo htmlspecialchars($url); ?>" target="_blank" rel="noopener">
										Open resource
										<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 17L17 7"/><path d="M8 7h9v9"/></svg>
									</a>
								<?php endif; ?>
							</article>
							<?php endforeach; ?>
						<?php endif; ?>
						<p class="qs-res-empty" data-qs-res-empty hidden>No matching resources.</p>
					</div>
				</div>
			</div>
		</div>
		<div class="main-footer">
			<div class="container-fluid pt-0 ht-100p">
				Copyright © <?php echo date('Y'); ?> All rights reserved
			</div>
		</div>
	</div>
	<a href="#top" id="back-to-top"><i class="las la-arrow-up"></i></a>
	<script src="assets/plugins/jquery/jquery.min.js"></script>
	<script src="assets/plugins/bootstrap/js/popper.min.js"></script>
	<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
	<script src="assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
	<script src="assets/plugins/perfect-scrollbar/p-scroll.js"></script>
	<script src="assets/plugins/side-menu/sidemenu.js"></script>
	<script src="assets/js/sticky.js"></script>
	<script src="assets/plugins/sidebar/sidebar.js"></script>
	<script src="assets/plugins/sidebar/sidebar-custom.js"></script>
	<script src="assets/js/eva-icons.min.js"></script>
	<script src="assets/js/themecolor.js"></script>
	<script src="assets/js/custom.js"></script>
	<script>
		(function () {
			var search = document.querySelector('[data-qs-res-search]');
			var pills = document.querySelectorAll('[data-qs-res-cat]');
			var cards = document.querySelectorAll('[data-qs-res-card]');
			var empty = document.querySelector('[data-qs-res-empty]');
			var cat = 'all';
			function apply() {
				var q = search ? search.value.toLowerCase().trim() : '';
				var shown = 0;
				cards.forEach(function (card) {
					var matchCat = cat === 'all' || card.getAttribute('data-category') === cat;
					var matchQ = !q || (card.getAttribute('data-search') || '').indexOf(q) !== -1;
					var on = matchCat && matchQ;
					card.hidden = !on;
					if (on) shown++;
				});
				if (empty) empty.hidden = shown > 0 || cards.length === 0;
			}
			pills.forEach(function (pill) {
				pill.addEventListener('click', function () {
					cat = pill.getAttribute('data-qs-res-cat') || 'all';
					pills.forEach(function (p) { p.classList.toggle('is-active', p === pill); });
					apply();
				});
			});
			if (search) search.addEventListener('input', apply);
		})();
	</script>
</body>
</html>
