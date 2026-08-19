<?php
if (!function_exists('qs_ensure_disclaimer_agreed_column')) {
	function qs_ensure_disclaimer_agreed_column($mysqli) {
		if (!$mysqli) {
			return false;
		}
		$col = mysqli_query($mysqli, "SHOW COLUMNS FROM `users` LIKE 'disclaimer_agreed'");
		if ($col && mysqli_num_rows($col) === 0) {
			mysqli_query($mysqli, "ALTER TABLE `users` ADD `disclaimer_agreed` tinyint(1) NOT NULL DEFAULT 0");
		}
		return true;
	}

	function qs_user_disclaimer_agreed($mysqli, $userId) {
		$userId = (int) $userId;
		if (!$mysqli || $userId < 1) {
			return false;
		}
		qs_ensure_disclaimer_agreed_column($mysqli);
		$res = mysqli_query($mysqli, "SELECT `disclaimer_agreed` FROM `users` WHERE id='$userId' LIMIT 1");
		$row = $res ? mysqli_fetch_assoc($res) : null;
		return $row && !empty($row['disclaimer_agreed']);
	}

	function qs_mark_disclaimer_agreed($mysqli, $userId) {
		$userId = (int) $userId;
		if (!$mysqli || $userId < 1) {
			return false;
		}
		qs_ensure_disclaimer_agreed_column($mysqli);
		return (bool) mysqli_query($mysqli, "UPDATE `users` SET `disclaimer_agreed`=1 WHERE id='$userId'");
	}

	function qs_set_disclaimer_cookie() {
		$secure = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
		setcookie('qs_disclaimer_agreed', '1', array(
			'expires' => time() + (400 * 24 * 60 * 60),
			'path' => '/',
			'secure' => $secure,
			'httponly' => false,
			'samesite' => 'Lax',
		));
		$_COOKIE['qs_disclaimer_agreed'] = '1';
	}
}
