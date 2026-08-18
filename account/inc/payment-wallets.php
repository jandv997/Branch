<?php
if (!function_exists('qs_ensure_payment_wallets_table')) {
	function qs_ensure_payment_wallets_table($mysqli) {
		mysqli_query($mysqli, "CREATE TABLE IF NOT EXISTS `payment_method` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`name` varchar(191) DEFAULT '',
			`code` varchar(64) NOT NULL,
			`wallet_address` varchar(255) DEFAULT '',
			PRIMARY KEY (`id`),
			UNIQUE KEY `code` (`code`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

		$pw = mysqli_query($mysqli, "SHOW TABLES LIKE 'payment_wallets'");
		if ($pw && mysqli_num_rows($pw) > 0) {
			$res = mysqli_query($mysqli, "SELECT `name`, `wallet_address` FROM payment_wallets");
			if ($res) {
				while ($row = mysqli_fetch_assoc($res)) {
					$name = isset($row['name']) ? trim($row['name']) : '';
					$addr = isset($row['wallet_address']) ? trim($row['wallet_address']) : '';
					if ($name === '' || $addr === '') {
						continue;
					}
					$nameEsc = mysqli_real_escape_string($mysqli, $name);
					$addrEsc = mysqli_real_escape_string($mysqli, $addr);
					$exists = mysqli_query($mysqli, "SELECT id FROM payment_method WHERE wallet_address='$addrEsc' OR `name`='$nameEsc' LIMIT 1");
					if ($exists && mysqli_num_rows($exists) > 0) {
						continue;
					}
					$code = qs_payment_method_make_code($mysqli, $name);
					$codeEsc = mysqli_real_escape_string($mysqli, $code);
					mysqli_query($mysqli, "INSERT INTO payment_method (`name`, `code`, `wallet_address`) VALUES('$nameEsc', '$codeEsc', '$addrEsc')");
				}
			}
		}

		$hashCol = mysqli_query($mysqli, "SHOW COLUMNS FROM pending LIKE 'txn_hash'");
		if ($hashCol && mysqli_num_rows($hashCol) === 0) {
			mysqli_query($mysqli, "ALTER TABLE pending ADD `txn_hash` varchar(191) DEFAULT ''");
		}
	}

	function qs_payment_method_make_code($mysqli, $name, $excludeId = 0) {
		$base = strtolower(trim((string) $name));
		$base = preg_replace('/[^a-z0-9]+/', '-', $base);
		$base = trim($base, '-');
		if ($base === '') {
			$base = 'wallet';
		}
		$code = $base;
		$n = 2;
		$excludeId = (int) $excludeId;
		while (true) {
			$esc = mysqli_real_escape_string($mysqli, $code);
			$sql = "SELECT id FROM payment_method WHERE `code`='$esc'";
			if ($excludeId > 0) {
				$sql .= " AND id!='$excludeId'";
			}
			$sql .= " LIMIT 1";
			$res = mysqli_query($mysqli, $sql);
			if (!$res || mysqli_num_rows($res) === 0) {
				return $code;
			}
			$code = $base . '-' . $n;
			$n++;
		}
	}

	function qs_payment_method_create($mysqli, $name, $wallet_address, $code = '') {
		qs_ensure_payment_wallets_table($mysqli);
		$name = trim((string) $name);
		$wallet_address = trim((string) $wallet_address);
		$code = trim((string) $code);
		if ($name === '' || $wallet_address === '') {
			return array('ok' => false, 'message' => 'Name and wallet address are required.');
		}
		if ($code === '') {
			$code = qs_payment_method_make_code($mysqli, $name);
		} else {
			$dup = mysqli_query($mysqli, "SELECT id FROM payment_method WHERE `code`='" . mysqli_real_escape_string($mysqli, $code) . "' LIMIT 1");
			if ($dup && mysqli_num_rows($dup) > 0) {
				return array('ok' => false, 'message' => 'That payment method code is already in use.');
			}
		}
		$nameEsc = mysqli_real_escape_string($mysqli, $name);
		$codeEsc = mysqli_real_escape_string($mysqli, $code);
		$addrEsc = mysqli_real_escape_string($mysqli, $wallet_address);
		$ok = mysqli_query($mysqli, "INSERT INTO payment_method (`name`, `code`, `wallet_address`) VALUES('$nameEsc', '$codeEsc', '$addrEsc')");
		if ($ok) {
			return array('ok' => true, 'message' => 'Payment method created.');
		}
		return array('ok' => false, 'message' => 'Could not create payment method.');
	}

	function qs_payment_method_update($mysqli, $id, $name, $wallet_address, $code = '') {
		qs_ensure_payment_wallets_table($mysqli);
		$id = (int) $id;
		$name = trim((string) $name);
		$wallet_address = trim((string) $wallet_address);
		$code = trim((string) $code);
		if ($id <= 0 || $name === '' || $wallet_address === '') {
			return array('ok' => false, 'message' => 'Name and wallet address are required.');
		}
		$nameEsc = mysqli_real_escape_string($mysqli, $name);
		$addrEsc = mysqli_real_escape_string($mysqli, $wallet_address);
		$sql = "UPDATE payment_method SET `name`='$nameEsc', `wallet_address`='$addrEsc'";
		if ($code !== '') {
			$dup = mysqli_query($mysqli, "SELECT id FROM payment_method WHERE `code`='" . mysqli_real_escape_string($mysqli, $code) . "' AND id!='$id' LIMIT 1");
			if ($dup && mysqli_num_rows($dup) > 0) {
				return array('ok' => false, 'message' => 'That payment method code is already in use.');
			}
			$sql .= ", `code`='" . mysqli_real_escape_string($mysqli, $code) . "'";
		}
		$sql .= " WHERE id='$id'";
		$ok = mysqli_query($mysqli, $sql);
		if ($ok) {
			return array('ok' => true, 'message' => 'Payment method updated.');
		}
		return array('ok' => false, 'message' => 'Could not update payment method.');
	}

	function qs_payment_method_delete($mysqli, $id) {
		qs_ensure_payment_wallets_table($mysqli);
		$id = (int) $id;
		if ($id <= 0) {
			return array('ok' => false, 'message' => 'Could not delete payment method.');
		}
		$ok = mysqli_query($mysqli, "DELETE FROM payment_method WHERE id='$id'");
		if ($ok) {
			return array('ok' => true, 'message' => 'Payment method deleted.');
		}
		return array('ok' => false, 'message' => 'Could not delete payment method.');
	}

	function qs_payment_wallets($mysqli) {
		qs_ensure_payment_wallets_table($mysqli);
		$rows = array();
		$res = mysqli_query($mysqli, "SELECT * FROM payment_method ORDER BY id ASC");
		if ($res) {
			while ($row = mysqli_fetch_assoc($res)) {
				$rows[] = $row;
			}
		}
		return $rows;
	}

	function qs_payment_wallet_by_id($mysqli, $id) {
		qs_ensure_payment_wallets_table($mysqli);
		$raw = trim((string) $id);
		if ($raw === '') {
			return null;
		}
		$esc = mysqli_real_escape_string($mysqli, $raw);
		if (ctype_digit($raw)) {
			$res = mysqli_query($mysqli, "SELECT * FROM payment_method WHERE id='$esc' LIMIT 1");
			$row = $res ? mysqli_fetch_assoc($res) : null;
			if ($row) {
				return $row;
			}
		}
		$res = mysqli_query($mysqli, "SELECT * FROM payment_method WHERE `code`='$esc' OR `name`='$esc' LIMIT 1");
		return $res ? mysqli_fetch_assoc($res) : null;
	}

	function qs_payment_wallet_options($mysqli, $selected = '') {
		$html = '';
		foreach (qs_payment_wallets($mysqli) as $wallet) {
			$value = (isset($wallet['code']) && $wallet['code'] !== '') ? $wallet['code'] : $wallet['id'];
			$sel = ((string) $value === (string) $selected || (string) $wallet['id'] === (string) $selected) ? ' selected' : '';
			$html .= '<option value="' . htmlspecialchars($value) . '"' . $sel . '>' . htmlspecialchars($wallet['name']) . '</option>';
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
