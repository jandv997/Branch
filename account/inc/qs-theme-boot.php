<?php
if (!isset($qs_theme_default)) {
	$qs_theme_default = 'dark';
}
if (!isset($qs_theme_js)) {
	$qs_theme_js = 'assets/js/qs-theme.js';
}
?>
<script>
(function () {
	var stored = null;
	try { stored = localStorage.getItem('qs-theme'); } catch (e) { stored = null; }
	if (!stored) {
		try {
			if (localStorage.getItem('nowadarkMode') || localStorage.getItem('dashplexdarktheme')) stored = 'dark';
			else if (localStorage.getItem('nowalightMode') || localStorage.getItem('dashplexlighttheme')) stored = 'light';
		} catch (e) {}
	}
	if (stored !== 'light' && stored !== 'dark') {
		stored = <?php echo json_encode($qs_theme_default); ?>;
	}
	document.body.classList.remove('dark-theme', 'light-theme', 'transparent-theme');
	document.body.classList.add(stored === 'light' ? 'light-theme' : 'dark-theme');
	document.body.setAttribute('data-qs-theme', stored);
})();
</script>
<script src="<?php echo htmlspecialchars($qs_theme_js, ENT_QUOTES, 'UTF-8'); ?>"></script>
