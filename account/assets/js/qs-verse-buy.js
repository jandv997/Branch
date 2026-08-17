(function () {
	var overlay = document.getElementById('qs-buy-overlay');
	if (!overlay) return;

	var form = document.getElementById('qs-buy-form');
	var payout = document.getElementById('payout');
	var duration = document.getElementById('duration');
	var amount = document.getElementById('amount');
	var currency = document.getElementById('currency');
	var step1 = document.getElementById('qs-buy-step-1');
	var step2 = document.getElementById('qs-buy-step-2');
	var title = document.getElementById('qs-buy-title');
	var range = document.getElementById('qs-buy-range');
	var monthsWrap = document.getElementById('qs-buy-months');
	var durationHint = document.getElementById('qs-buy-duration-hint');
	var bar = document.getElementById('qs-buy-bar');
	var legend = document.getElementById('qs-buy-legend');
	var pkg = {};

	function money(n) {
		return '$' + Number(n).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
	}

	function openBuy(data) {
		pkg = data || {};
		form.action = 'purchase?id=' + encodeURIComponent(pkg.id || '');
		title.textContent = 'Purchase — ' + (pkg.name || '');
		amount.min = pkg.min || 0;
		amount.max = pkg.max || '';
		amount.value = pkg.min || '';
		range.textContent = 'Min ' + money(pkg.min || 0) + ' • Max ' + money(pkg.max || 0);
		currency.selectedIndex = 0;
		document.getElementById('qs-buy-term1').checked = false;
		document.getElementById('qs-buy-term2').checked = false;
		setPayout('1');
		setDuration('183', '6');
		showStep(1);
		overlay.classList.add('is-open');
		document.body.style.overflow = 'hidden';
	}

	function closeBuy() {
		overlay.classList.remove('is-open');
		document.body.style.overflow = '';
	}

	function showStep(n) {
		step1.classList.toggle('is-active', n === 1);
		step2.classList.toggle('is-active', n === 2);
	}

	function setPayout(val) {
		payout.value = val;
		document.querySelectorAll('[data-qs-payout]').forEach(function (btn) {
			btn.classList.toggle('is-active', btn.getAttribute('data-qs-payout') === val);
		});
		var showMonths = val === '2' || val === '3';
		monthsWrap.classList.toggle('is-visible', showMonths);
		if (val === '1') {
			duration.value = '';
			bar.className = 'qs-buy-bar';
			bar.innerHTML = '<span style="width:100%"></span>';
			legend.innerHTML = '<span><i></i>100% → Main Wallet</span>';
		} else if (val === '2') {
			if (!duration.value) setDuration('183', '6');
			bar.className = 'qs-buy-bar';
			bar.innerHTML = '<span style="width:100%"></span>';
			legend.innerHTML = '<span><i></i>100% → Staking Wallet</span>';
		} else {
			if (!duration.value) setDuration('183', '6');
			bar.className = 'qs-buy-bar qs-buy-bar--hybrid';
			bar.innerHTML = '<span style="width:25%"></span><span style="width:75%"></span>';
			legend.innerHTML = '<span><i></i>25% → Main Wallet</span><span><i></i>75% → Staking Wallet</span>';
		}
		updateDurationHint();
	}

	function setDuration(days, months) {
		duration.value = days;
		document.querySelectorAll('[data-qs-duration]').forEach(function (btn) {
			btn.classList.toggle('is-active', btn.getAttribute('data-qs-duration') === String(days));
		});
		duration.dataset.months = months || '';
		updateDurationHint();
	}

	function updateDurationHint() {
		var months = duration.dataset.months || '6';
		var term = pkg.term || '12';
		durationHint.textContent = 'After ' + months + ' months, allocation reverts to 100% Main (' + term + '-month total term).';
	}

	function payoutLabel(val) {
		if (val === '2') return 'Staking';
		if (val === '3') return 'Hybrid';
		return 'Daily';
	}

	function fillConfirm() {
		var amt = parseFloat(amount.value || '0');
		var rate = payout.value === '1' ? Number(pkg.percent || 0) : Number(pkg.compound || pkg.percent || 0);
		var daily = amt * (rate / 100);
		var start = new Date();
		var end = new Date();
		end.setMonth(end.getMonth() + Number(pkg.term || 12));
		var fund = 'Direct Deposit';
		if (currency.value && currency.options[currency.selectedIndex]) {
			fund = 'Direct Deposit · ' + currency.options[currency.selectedIndex].text;
		}
		document.getElementById('qs-sum-name').textContent = pkg.name || '';
		document.getElementById('qs-sum-amount').textContent = money(amt);
		document.getElementById('qs-sum-payout').textContent = payoutLabel(payout.value);
		document.getElementById('qs-sum-alloc').textContent = (duration.dataset.months || '') + ' Months';
		document.getElementById('qs-sum-alloc-row').style.display = payout.value === '1' ? 'none' : '';
		document.getElementById('qs-sum-start').textContent = start.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
		document.getElementById('qs-sum-end').textContent = end.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
		document.getElementById('qs-sum-fund').textContent = fund;
		document.getElementById('qs-sum-daily').textContent = 'Up To ' + money(daily);
	}

	document.querySelectorAll('[data-qs-buy]').forEach(function (btn) {
		btn.addEventListener('click', function () {
			openBuy({
				id: btn.getAttribute('data-id'),
				name: btn.getAttribute('data-name'),
				min: btn.getAttribute('data-min'),
				max: btn.getAttribute('data-max'),
				percent: btn.getAttribute('data-percent'),
				compound: btn.getAttribute('data-compound'),
				term: btn.getAttribute('data-term')
			});
		});
	});

	document.querySelectorAll('[data-qs-buy-close]').forEach(function (btn) {
		btn.addEventListener('click', closeBuy);
	});
	overlay.addEventListener('click', function (e) {
		if (e.target === overlay) closeBuy();
	});
	document.addEventListener('keydown', function (e) {
		if (e.key === 'Escape' && overlay.classList.contains('is-open')) closeBuy();
	});

	document.querySelectorAll('[data-qs-payout]').forEach(function (btn) {
		btn.addEventListener('click', function () {
			setPayout(btn.getAttribute('data-qs-payout'));
		});
	});
	document.querySelectorAll('[data-qs-duration]').forEach(function (btn) {
		btn.addEventListener('click', function () {
			setDuration(btn.getAttribute('data-qs-duration'), btn.getAttribute('data-qs-months'));
		});
	});

	document.getElementById('qs-buy-review').addEventListener('click', function () {
		if (!amount.value || Number(amount.value) < Number(amount.min || 0)) {
			amount.reportValidity();
			return;
		}
		if (amount.max && Number(amount.value) > Number(amount.max)) {
			amount.reportValidity();
			return;
		}
		if (!currency.value) {
			currency.reportValidity();
			return;
		}
		if ((payout.value === '2' || payout.value === '3') && !duration.value) {
			return;
		}
		fillConfirm();
		showStep(2);
	});

	document.getElementById('qs-buy-back').addEventListener('click', function () {
		showStep(1);
	});

	form.addEventListener('submit', function (e) {
		if (!document.getElementById('qs-buy-term1').checked || !document.getElementById('qs-buy-term2').checked) {
			e.preventDefault();
			return;
		}
		if (payout.value === '1') {
			duration.value = '';
		}
	});

	var params = new URLSearchParams(window.location.search);
	var buyId = params.get('buy');
	if (buyId) {
		var match = document.querySelector('[data-qs-buy][data-id="' + buyId + '"]');
		if (match) match.click();
	}
})();
