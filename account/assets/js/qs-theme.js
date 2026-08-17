(function () {
	'use strict';

	var KEY = 'qs-theme';

	function currentTheme() {
		if (document.body.classList.contains('light-theme')) return 'light';
		if (document.body.classList.contains('dark-theme')) return 'dark';
		return 'dark';
	}

	function persist(theme) {
		try {
			localStorage.setItem(KEY, theme);
			if (theme === 'dark') {
				localStorage.setItem('nowadarkMode', 'true');
				localStorage.setItem('dashplexdarktheme', 'true');
				localStorage.removeItem('nowalightMode');
				localStorage.removeItem('dashplexlighttheme');
				localStorage.removeItem('nowatransparentMode');
			} else {
				localStorage.setItem('nowalightMode', 'true');
				localStorage.setItem('dashplexlighttheme', 'true');
				localStorage.removeItem('nowadarkMode');
				localStorage.removeItem('dashplexdarktheme');
				localStorage.removeItem('nowatransparentMode');
			}
		} catch (e) {}
	}

	function updateToggleLabels(theme) {
		var labels = document.querySelectorAll('.qs-theme-label');
		for (var i = 0; i < labels.length; i++) {
			labels[i].textContent = theme === 'dark' ? 'Light Mode' : 'Dark Mode';
		}
		document.body.setAttribute('data-qs-theme', theme);
	}

	function apply(theme) {
		theme = theme === 'light' ? 'light' : 'dark';
		document.body.classList.remove('dark-theme', 'light-theme', 'transparent-theme');
		document.body.classList.add(theme === 'light' ? 'light-theme' : 'dark-theme');
		persist(theme);
		updateToggleLabels(theme);
		try {
			if (window.jQuery) {
				if (theme === 'dark') {
					window.jQuery('#myonoffswitch2, #myonoffswitch5, #myonoffswitch8').prop('checked', true);
				} else {
					window.jQuery('#myonoffswitch1, #myonoffswitch3, #myonoffswitch6').prop('checked', true);
				}
			}
		} catch (e) {}
	}

	function toggle() {
		apply(currentTheme() === 'dark' ? 'light' : 'dark');
	}

	updateToggleLabels(currentTheme());

	document.addEventListener('click', function (e) {
		var node = e.target;
		if (node && node.nodeType !== 1) node = node.parentElement;
		var btn = node && node.closest ? node.closest('.layout-setting, .qs-theme-toggle') : null;
		if (!btn) return;
		e.preventDefault();
		e.stopImmediatePropagation();
		toggle();
	}, true);

	window.qsApplyTheme = apply;
})();
