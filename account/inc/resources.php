<?php
if (!function_exists('qs_resources_upload_dir')) {
	function qs_resources_upload_dir() {
		return dirname(__DIR__) . '/resources_upload';
	}

	function qs_resources_categories() {
		return array(
			'compliance' => 'Compliance',
			'technology' => 'Technology',
			'partner' => 'Partner',
		);
	}

	function qs_resources_doc_types() {
		return array(
			'COMPLIANCE DOCUMENT',
			'PRODUCT GUIDE',
			'COMPENSATION PLAN',
			'USER GUIDE',
			'POLICY',
			'OTHER',
		);
	}

	function qs_resources_icon_types() {
		return array('shield', 'book', 'file');
	}

	function qs_ensure_resources_table($mysqli) {
		$uploadDir = qs_resources_upload_dir();
		if (!is_dir($uploadDir)) {
			@mkdir($uploadDir, 0755, true);
		}

		mysqli_query($mysqli, "CREATE TABLE IF NOT EXISTS `resources` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`title` varchar(191) NOT NULL DEFAULT '',
			`description` text,
			`category` varchar(64) NOT NULL DEFAULT 'technology',
			`doc_type` varchar(64) NOT NULL DEFAULT 'PRODUCT GUIDE',
			`icon_type` varchar(32) NOT NULL DEFAULT 'book',
			`file_name` varchar(191) NOT NULL DEFAULT '',
			`file_path` varchar(255) NOT NULL DEFAULT '',
			`original_name` varchar(191) NOT NULL DEFAULT '',
			`created_at` datetime DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

		$countRes = mysqli_query($mysqli, "SELECT COUNT(*) AS c FROM resources");
		$countRow = $countRes ? mysqli_fetch_assoc($countRes) : null;
		if (!$countRow || (int) $countRow['c'] !== 0) {
			return;
		}

		$guidePath = 'img/quantum-saclp-ai-complete-v8.pdf';
		$sourcePdf = dirname(__DIR__) . '/img/quantum-saclp-ai-complete-v8.pdf';
		$copied = 'q-core-product-guide.pdf';
		$copiedFull = $uploadDir . '/' . $copied;
		if (is_file($sourcePdf) && !is_file($copiedFull) && @copy($sourcePdf, $copiedFull)) {
			$guidePath = 'resources_upload/' . $copied;
		}

		$seeds = array(
			array(
				'title' => 'Risk Disclosure Document',
				'description' => 'Official risk disclosures for platform access, trading tools, and membership participation.',
				'category' => 'compliance',
				'doc_type' => 'COMPLIANCE DOCUMENT',
				'icon_type' => 'shield',
				'file_path' => '',
				'file_name' => '',
				'original_name' => '',
			),
			array(
				'title' => 'Q-Core Product Guide',
				'description' => 'Product overview, setup guidance, and how Quantum Scalp tools fit together.',
				'category' => 'technology',
				'doc_type' => 'PRODUCT GUIDE',
				'icon_type' => 'book',
				'file_path' => $guidePath,
				'file_name' => ($guidePath === 'resources_upload/' . $copied) ? $copied : '',
				'original_name' => 'quantum-saclp-ai-complete-v8.pdf',
			),
			array(
				'title' => 'Quantum Flex Compensation Plan',
				'description' => 'Rank qualifications, rewards, and team-sales requirements for Quantum Flex.',
				'category' => 'partner',
				'doc_type' => 'COMPENSATION PLAN',
				'icon_type' => 'file',
				'file_path' => '',
				'file_name' => '',
				'original_name' => '',
			),
		);

		foreach ($seeds as $seed) {
			$title = mysqli_real_escape_string($mysqli, $seed['title']);
			$desc = mysqli_real_escape_string($mysqli, $seed['description']);
			$cat = mysqli_real_escape_string($mysqli, $seed['category']);
			$doc = mysqli_real_escape_string($mysqli, $seed['doc_type']);
			$icon = mysqli_real_escape_string($mysqli, $seed['icon_type']);
			$path = mysqli_real_escape_string($mysqli, $seed['file_path']);
			$fname = mysqli_real_escape_string($mysqli, $seed['file_name']);
			$orig = mysqli_real_escape_string($mysqli, $seed['original_name']);
			mysqli_query($mysqli, "INSERT INTO resources (`title`, `description`, `category`, `doc_type`, `icon_type`, `file_name`, `file_path`, `original_name`) VALUES('$title', '$desc', '$cat', '$doc', '$icon', '$fname', '$path', '$orig')");
		}
	}

	function qs_resources_list($mysqli) {
		qs_ensure_resources_table($mysqli);
		$rows = array();
		$res = mysqli_query($mysqli, "SELECT * FROM resources ORDER BY id ASC");
		if ($res) {
			while ($row = mysqli_fetch_assoc($res)) {
				$rows[] = $row;
			}
		}
		return $rows;
	}

	function qs_resource_by_id($mysqli, $id) {
		qs_ensure_resources_table($mysqli);
		$id = (int) $id;
		if ($id <= 0) {
			return null;
		}
		$res = mysqli_query($mysqli, "SELECT * FROM resources WHERE id='$id' LIMIT 1");
		return $res ? mysqli_fetch_assoc($res) : null;
	}

	function qs_resource_public_url($row) {
		$path = isset($row['file_path']) ? trim($row['file_path']) : '';
		if ($path === '') {
			return '';
		}
		if (preg_match('#^https?://#i', $path)) {
			return $path;
		}
		return $path;
	}

	function qs_resource_delete_file($row) {
		$path = isset($row['file_path']) ? $row['file_path'] : '';
		if ($path === '' || strpos($path, 'resources_upload/') !== 0) {
			return;
		}
		$full = dirname(__DIR__) . '/' . $path;
		if (is_file($full)) {
			@unlink($full);
		}
	}

	function qs_save_resource_upload($file) {
		if (!isset($file) || !is_array($file) || empty($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
			return array('ok' => false, 'error' => 'No file uploaded.');
		}
		if (!empty($file['error']) && (int) $file['error'] !== UPLOAD_ERR_OK) {
			return array('ok' => false, 'error' => 'File upload failed.');
		}
		if ((int) $file['size'] > 20 * 1024 * 1024) {
			return array('ok' => false, 'error' => 'File must be 20MB or smaller.');
		}

		$original = isset($file['name']) ? $file['name'] : 'resource';
		$ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
		$allowed = array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'png', 'jpg', 'jpeg', 'webp', 'txt', 'csv');
		if (!in_array($ext, $allowed, true)) {
			return array('ok' => false, 'error' => 'That file type is not allowed.');
		}

		$dir = qs_resources_upload_dir();
		if (!is_dir($dir) && !@mkdir($dir, 0755, true)) {
			return array('ok' => false, 'error' => 'Could not create upload folder.');
		}

		$stored = date('YmdHis') . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
		$dest = $dir . '/' . $stored;
		if (!move_uploaded_file($file['tmp_name'], $dest)) {
			return array('ok' => false, 'error' => 'Could not save the uploaded file.');
		}

		return array(
			'ok' => true,
			'file_name' => $stored,
			'file_path' => 'resources_upload/' . $stored,
			'original_name' => $original,
		);
	}

	function qs_resource_icon_svg($type) {
		$type = strtolower((string) $type);
		if ($type === 'shield') {
			return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 3l8 3v6c0 5-3.4 8.4-8 9.5C7.4 20.4 4 17 4 12V6l8-3z"/><path d="M9.2 12.2l1.9 1.9 3.8-4.1"/></svg>';
		}
		if ($type === 'file') {
			return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M7 3h7l5 5v13H7z"/><path d="M14 3v5h5"/><path d="M9 13h8M9 17h6"/></svg>';
		}
		return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M5 5h6a3 3 0 0 1 3 3v13H8a3 3 0 0 0-3 3V5z"/><path d="M13 8h6a0 0 0 0 1 0 0v16a3 3 0 0 1-3-3h-3V8z"/></svg>';
	}
}
