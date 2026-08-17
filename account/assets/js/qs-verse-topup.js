(function () {
	window.qsShowError = function (message) {
		var toast = document.getElementById('qs-toast-error');
		var text = document.getElementById('qs-toast-error-text');
		if (!toast || !text) return;
		text.textContent = message;
		toast.classList.add('is-open');
		clearTimeout(window.__qsToastTimer);
		window.__qsToastTimer = setTimeout(function () {
			toast.classList.remove('is-open');
		}, 4200);
	};

	var overlay = document.getElementById('qs-topup-overlay');
	if (!overlay) return;

	var form = document.getElementById('qs-topup-form');
	var platform = document.getElementById('qs-topup-platform');
	var currency = document.getElementById('qs-topup-currency');
	var coinWrap = document.getElementById('qs-topup-coin');
	var amount = document.getElementById('qs-topup-new-amount');

	function money(n) {
		return '$' + Number(n).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
	}

	function walletBalance(code) {
		if (code === '2') return Number(overlay.getAttribute('data-main') || 0);
		if (code === '3') return Number(overlay.getAttribute('data-staking') || 0);
		if (code === '5') return Number(overlay.getAttribute('data-referral') || 0);
		return 0;
	}

	function walletName(code) {
		if (code === '2') return 'main wallet';
		if (code === '3') return 'staking wallet';
		if (code === '5') return 'referral wallet';
		return 'wallet';
	}

	function setFunding(code) {
		var isDeposit = code === '1';
		coinWrap.classList.toggle('is-visible', isDeposit);
		currency.required = isDeposit;
		if (!isDeposit) currency.value = '';
	}

	function openTopup(btn) {
		document.getElementById('qs-topup-id').value = btn.getAttribute('data-id') || '';
		document.getElementById('qs-topup-name').value = btn.getAttribute('data-name') || '';
		document.getElementById('qs-topup-amount').value = btn.getAttribute('data-old-amount') || '';
		document.getElementById('qs-topup-old-amount').value = btn.getAttribute('data-old-amount') || '';
		document.getElementById('qs-topup-added-roi').value = btn.getAttribute('data-added-roi') || '0';
		document.getElementById('qs-topup-percent').value = btn.getAttribute('data-percent') || '';
		document.getElementById('qs-topup-compound').value = btn.getAttribute('data-compound') || '';
		document.getElementById('qs-topup-payout').value = btn.getAttribute('data-payout') || '1';
		document.getElementById('qs-topup-title').textContent = 'Top Up — ' + (btn.getAttribute('data-name') || '');
		amount.value = '';
		amount.min = 10;
		var max = btn.getAttribute('data-max');
		if (max) amount.max = max;
		else amount.removeAttribute('max');
		platform.value = '1';
		currency.selectedIndex = 0;
		setFunding('1');
		overlay.classList.add('is-open');
		document.body.style.overflow = 'hidden';
	}

	function closeTopup() {
		overlay.classList.remove('is-open');
		document.body.style.overflow = '';
	}

	document.querySelectorAll('[data-qs-topup]').forEach(function (btn) {
		btn.addEventListener('click', function () { openTopup(btn); });
	});
	document.querySelectorAll('[data-qs-topup-close]').forEach(function (btn) {
		btn.addEventListener('click', closeTopup);
	});
	overlay.addEventListener('click', function (e) {
		if (e.target === overlay) closeTopup();
	});
	document.addEventListener('keydown', function (e) {
		if (e.key === 'Escape' && overlay.classList.contains('is-open')) closeTopup();
	});
	platform.addEventListener('change', function () {
		setFunding(platform.value);
	});

	form.addEventListener('submit', function (e) {
		var code = platform.value;
		if (code === '1' && !currency.value) {
			e.preventDefault();
			currency.reportValidity();
			return;
		}
		if (code === '2' || code === '3' || code === '5') {
			var needed = Number(amount.value || 0);
			var bal = walletBalance(code);
			if (needed > bal) {
				e.preventDefault();
				window.qsShowError('Insufficient ' + walletName(code) + ' balance');
			}
		}
	});
})();
