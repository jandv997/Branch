<?php
if (!function_exists('qs_ensure_partner_applications_table')) {
	function qs_ensure_partner_applications_table($mysqli) {
		if (!$mysqli) {
			return false;
		}

		$ok = mysqli_query($mysqli, "CREATE TABLE IF NOT EXISTS `partner_applications` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`full_name` varchar(191) NOT NULL DEFAULT '',
			`email` varchar(191) NOT NULL DEFAULT '',
			`country` varchar(128) NOT NULL DEFAULT '',
			`phone` varchar(64) NOT NULL DEFAULT '',
			`program_type` varchar(64) NOT NULL DEFAULT 'Partner',
			`experience` text,
			`message` text,
			`created_at` datetime DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (`id`),
			KEY `email` (`email`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

		if (!$ok) {
			return false;
		}

		$columns = array(
			'full_name' => "ALTER TABLE `partner_applications` ADD `full_name` varchar(191) NOT NULL DEFAULT ''",
			'email' => "ALTER TABLE `partner_applications` ADD `email` varchar(191) NOT NULL DEFAULT ''",
			'country' => "ALTER TABLE `partner_applications` ADD `country` varchar(128) NOT NULL DEFAULT ''",
			'phone' => "ALTER TABLE `partner_applications` ADD `phone` varchar(64) NOT NULL DEFAULT ''",
			'program_type' => "ALTER TABLE `partner_applications` ADD `program_type` varchar(64) NOT NULL DEFAULT 'Partner'",
			'experience' => "ALTER TABLE `partner_applications` ADD `experience` text",
			'message' => "ALTER TABLE `partner_applications` ADD `message` text",
			'created_at' => "ALTER TABLE `partner_applications` ADD `created_at` datetime DEFAULT CURRENT_TIMESTAMP",
		);
		foreach ($columns as $name => $alter) {
			$col = mysqli_query($mysqli, "SHOW COLUMNS FROM `partner_applications` LIKE '" . mysqli_real_escape_string($mysqli, $name) . "'");
			if ($col && mysqli_num_rows($col) === 0) {
				mysqli_query($mysqli, $alter);
			}
		}

		return true;
	}

	function qs_save_partner_application($mysqli, $fields) {
		if (!qs_ensure_partner_applications_table($mysqli)) {
			return array('ok' => false, 'message' => 'Unable to save your application right now. Please try again.');
		}

		$full_name = trim((string) (isset($fields['full_name']) ? $fields['full_name'] : ''));
		$email = trim((string) (isset($fields['email']) ? $fields['email'] : ''));
		$country = trim((string) (isset($fields['country']) ? $fields['country'] : ''));
		$phone = trim((string) (isset($fields['phone']) ? $fields['phone'] : ''));
		$program_type = trim((string) (isset($fields['program_type']) ? $fields['program_type'] : 'Partner'));
		$experience = trim((string) (isset($fields['experience']) ? $fields['experience'] : ''));
		$message = trim((string) (isset($fields['message']) ? $fields['message'] : ''));

		if ($full_name === '' || $email === '') {
			return array('ok' => false, 'message' => 'Full name and email are required.');
		}
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return array('ok' => false, 'message' => 'Please enter a valid email address.');
		}

		$allowedPrograms = array('Partner', 'Affiliate', 'Regional Partner');
		if (!in_array($program_type, $allowedPrograms, true)) {
			$program_type = 'Partner';
		}

		$stmt = mysqli_prepare($mysqli, "INSERT INTO `partner_applications`
			(`full_name`, `email`, `country`, `phone`, `program_type`, `experience`, `message`)
			VALUES (?, ?, ?, ?, ?, ?, ?)");
		if (!$stmt) {
			return array('ok' => false, 'message' => 'Unable to save your application right now. Please try again.');
		}

		mysqli_stmt_bind_param($stmt, 'sssssss', $full_name, $email, $country, $phone, $program_type, $experience, $message);
		$saved = mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);

		if (!$saved) {
			return array('ok' => false, 'message' => 'Unable to save your application right now. Please try again.');
		}

		return array('ok' => true, 'message' => 'Application received. We will review it and follow up by email.');
	}
}
