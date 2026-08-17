<?php
if (!function_exists('qs_ensure_payment_wallets_table')) {
	function qs_ensure_payment_wallets_table($mysqli) {
		mysqli_query($mysqli, "CREATE TABLE IF NOT EXISTS `payment_wallets` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`name` varchar(191) NOT NULL DEFAULT '',
			`wallet_address` varchar(255) NOT NULL DEFAULT '',
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

		$countRes = mysqli_query($mysqli, "SELECT COUNT(*) AS c FROM payment_wallets");
		$countRow = $countRes ? mysqli_fetch_assoc($countRes) : null;
		if ($countRow && (int) $countRow['c'] === 0) {
			$pm = mysqli_query($mysqli, "SELECT name, wallet_address FROM payment_method");
			if ($pm) {
				while ($row = mysqli_fetch_assoc($pm)) {
					$name = mysqli_real_escape_string($mysqli, isset($row['name']) ? $row['name'] : '');
					$addr = mysqli_real_escape_string($mysqli, isset($row['wallet_address']) ? $row['wallet_address'] : '');
					if ($name !== '' && $addr !== '') {
						mysqli_query($mysqli, "INSERT INTO payment_wallets (`name`, `wallet_address`) VALUES('$name', '$addr')");
					}
				}
			}
		}

		$hashCol = mysqli_query($mysqli, "SHOW COLUMNS FROM pending LIKE 'txn_hash'");
		if ($hashCol && mysqli_num_rows($hashCol) === 0) {
			mysqli_query($mysqli, "ALTER TABLE pending ADD `txn_hash` varchar(191) DEFAULT ''");
		}
	}

	function qs_payment_wallets($mysqli) {
		qs_ensure_payment_wallets_table($mysqli);
		$rows = array();
		$res = mysqli_query($mysqli, "SELECT * FROM payment_wallets ORDER BY id ASC");
		if ($res) {
			while ($row = mysqli_fetch_assoc($res)) {
				$rows[] = $row;
			}
		}
		return $rows;
	}

	function qs_payment_wallet_by_id($mysqli, $id) {
		qs_ensure_payment_wallets_table($mysqli);
		$id = (int) $id;
		if ($id <= 0) {
			return null;
		}
		$res = mysqli_query($mysqli, "SELECT * FROM payment_wallets WHERE id='$id' LIMIT 1");
		return $res ? mysqli_fetch_assoc($res) : null;
	}

	function qs_payment_wallet_options($mysqli, $selected = '') {
		$html = '';
		foreach (qs_payment_wallets($mysqli) as $wallet) {
			$sel = ((string) $wallet['id'] === (string) $selected) ? ' selected' : '';
			$html .= '<option value="' . htmlspecialchars($wallet['id']) . '"' . $sel . '>' . htmlspecialchars($wallet['name']) . '</option>';
		}
		return $html;
	}

	function qs_wallet_qr_data_uri($address) {
		if ($address === '' || $address === null) {
			return '';
		}
		$qrlib = dirname(__FILE__) . '/../phpqrcode/qrlib.php';
		if (!is_file($qrlib)) {
			return '';
		}
		if (!class_exists('QRcode')) {
			include_once $qrlib;
		}
		if (!class_exists('QRcode')) {
			return '';
		}
		ob_start();
		QRcode::png($address, null, QR_ECLEVEL_L, 6, 2);
		$bin = ob_get_clean();
		if ($bin === '' || $bin === false) {
			return '';
		}
		return 'data:image/png;base64,' . base64_encode($bin);
	}

	function qs_pending_columns($mysqli) {
		static $cols = null;
		if ($cols !== null) {
			return $cols;
		}
		$cols = array();
		$res = mysqli_query($mysqli, "SHOW COLUMNS FROM pending");
		if ($res) {
			while ($col = mysqli_fetch_assoc($res)) {
				$cols[$col['Field']] = true;
			}
		}
		return $cols;
	}

	function qs_insert_pending_payment($mysqli, $fields) {
		$allowed = qs_pending_columns($mysqli);
		$cols = array();
		$vals = array();
		foreach ($fields as $key => $value) {
			if (!isset($allowed[$key])) {
				continue;
			}
			$cols[] = '`' . $key . '`';
			$vals[] = "'" . mysqli_real_escape_string($mysqli, (string) $value) . "'";
		}
		if (!$cols) {
			return false;
		}
		return mysqli_query($mysqli, 'INSERT INTO pending (' . implode(',', $cols) . ') VALUES (' . implode(',', $vals) . ')');
	}
}
