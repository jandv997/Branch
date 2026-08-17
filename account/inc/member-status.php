<?php
if (!function_exists('qs_is_active_member')) {
	function qs_is_active_member($user) {
		if (!is_array($user)) {
			return false;
		}
		$status = strtolower(trim((string) (isset($user['membership_status']) ? $user['membership_status'] : '')));
		if (in_array($status, array('active', '1', 'yes', 'true'), true)) {
			return true;
		}
		$expires = isset($user['membership_expires']) ? trim((string) $user['membership_expires']) : '';
		if ($expires === '' || strpos($expires, '0000-00-00') === 0) {
			return false;
		}
		$ts = strtotime($expires);
		return $ts !== false && $ts > time();
	}
}
