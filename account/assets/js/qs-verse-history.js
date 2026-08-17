(function () {
	var overlay = document.getElementById('qs-history-overlay');
	if (!overlay) return;

	var payoutsEl = document.getElementById('qs-history-payouts');
	var topupsEl = document.getElementById('qs-history-topups');

	function money(n) {
		return '$' + Number(n).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
	}

	function parseJson(el, attr) {
		try {
			return JSON.parse(el.getAttribute(attr) || '[]');
		} catch (e) {
			return [];
		}
	}

	function emptyState(message) {
		var p = document.createElement('p');
		p.className = 'qs-history-empty';
		p.textContent = message;
		return p;
	}

	function renderPayouts(rows) {
		payoutsEl.innerHTML = '';
		if (!rows || !rows.length) {
			payoutsEl.appendChild(emptyState('No payouts yet'));
			return;
		}
		rows.forEach(function (row) {
			var item = document.createElement('div');
			item.className = 'qs-history-row';
			var day = document.createElement('span');
			day.className = 'qs-history-day';
			day.textContent = 'Day ' + row.day;
			var vals = document.createElement('span');
			vals.className = 'qs-history-ms';
			vals.textContent = 'M ' + money(row.main) + ' · S ' + money(row.staking);
			item.appendChild(day);
			item.appendChild(vals);
			payoutsEl.appendChild(item);
		});
	}

	function renderTopups(rows) {
		topupsEl.innerHTML = '';
		if (!rows || !rows.length) {
			topupsEl.appendChild(emptyState('No top-ups yet'));
			return;
		}
		rows.forEach(function (row) {
			var item = document.createElement('div');
			item.className = 'qs-history-topup';
			var when = document.createElement('span');
			when.className = 'qs-history-topup-when';
			when.textContent = row.when;
			var amt = document.createElement('span');
			amt.className = 'qs-history-topup-amt';
			amt.textContent = money(row.amount);
			item.appendChild(when);
			item.appendChild(amt);
			topupsEl.appendChild(item);
		});
	}

	function setTab(name) {
		overlay.querySelectorAll('[data-qs-history-tab]').forEach(function (tab) {
			var on = tab.getAttribute('data-qs-history-tab') === name;
			tab.classList.toggle('is-active', on);
			tab.setAttribute('aria-selected', on ? 'true' : 'false');
		});
		overlay.querySelectorAll('[data-qs-history-panel]').forEach(function (panel) {
			panel.classList.toggle('is-active', panel.getAttribute('data-qs-history-panel') === name);
		});
	}

	function openHistory(btn) {
		renderPayouts(parseJson(btn, 'data-payouts'));
		renderTopups(parseJson(btn, 'data-topups'));
		setTab('payouts');
		overlay.classList.add('is-open');
		document.body.style.overflow = 'hidden';
	}

	function closeHistory() {
		overlay.classList.remove('is-open');
		document.body.style.overflow = '';
	}

	document.querySelectorAll('[data-qs-history]').forEach(function (btn) {
		btn.addEventListener('click', function () {
			openHistory(btn);
		});
	});
	document.querySelectorAll('[data-qs-history-close]').forEach(function (btn) {
		btn.addEventListener('click', closeHistory);
	});
	overlay.querySelectorAll('[data-qs-history-tab]').forEach(function (tab) {
		tab.addEventListener('click', function () {
			setTab(tab.getAttribute('data-qs-history-tab'));
		});
	});
	overlay.addEventListener('click', function (e) {
		if (e.target === overlay) closeHistory();
	});
	document.addEventListener('keydown', function (e) {
		if (e.key === 'Escape' && overlay.classList.contains('is-open')) closeHistory();
	});
})();
