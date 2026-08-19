<?php
if (!function_exists('qs_flex_display_names')) {
	function qs_flex_engine_bars() {
		return array(
			array('amount' => 3500, 'level1' => 1000, 'bonus' => 200, 'weekly' => 0, 'name' => 'Beginner', 'desc' => '1,000 being from level 1 <br/>One-time Payment of $200'),
			array('amount' => 8000, 'level1' => 2500, 'bonus' => 500, 'weekly' => 0, 'name' => 'Promoter', 'desc' => '2,500 being from level 1 <br/>One-time Payment of $500'),
			array('amount' => 15000, 'level1' => 4500, 'bonus' => 800, 'weekly' => 0, 'name' => 'Elite', 'desc' => '4,500 being from level 1 <br/>One-time Payment of $800'),
			array('amount' => 35000, 'level1' => 10000, 'bonus' => 1750, 'weekly' => 70, 'name' => 'Leader', 'desc' => '10,000 being from level 1 <br/>One-time Payment of $1,750 <br/>Lifetime weekly payment $70'),
			array('amount' => 70000, 'level1' => 20000, 'bonus' => 3500, 'weekly' => 150, 'name' => 'Mentor', 'desc' => '20,000 being from level 1 <br/>One-time Payment of $3,500 <br/>Lifetime weekly payment $150'),
			array('amount' => 150000, 'level1' => 35000, 'bonus' => 7500, 'weekly' => 350, 'name' => 'Director', 'desc' => '35,000 being from level 1 <br/>One-time Payment of $7,500 <br/>Lifetime weekly payment $350'),
			array('amount' => 250000, 'level1' => 50000, 'bonus' => 15000, 'weekly' => 550, 'name' => 'Ambassador', 'desc' => '50,000 being from level 1 <br/>One-time Payment of $15,000 <br/>Lifetime weekly payment $550'),
			array('amount' => 500000, 'level1' => 80000, 'bonus' => 25000, 'weekly' => 1000, 'name' => 'Master', 'desc' => '80,000 being from level 1 <br/>One-time Payment of $25,000 <br/>Lifetime weekly payment $1,000'),
			array('amount' => 1000000, 'level1' => 100000, 'bonus' => 50000, 'weekly' => 1750, 'name' => 'Executive', 'desc' => '100,000 being from level 1 <br/>One-time Payment of $50,000 <br/>Lifetime weekly payment $1,750'),
			array('amount' => 2000000, 'level1' => 300000, 'bonus' => 150000, 'weekly' => 3000, 'name' => 'Visionary', 'desc' => '300,000 being from level 1 <br/>One-time Payment of $150,000 <br/>Lifetime weekly payment $3,000'),
			array('amount' => 5000000, 'level1' => 500000, 'bonus' => 300000, 'weekly' => 6000, 'name' => 'Legend', 'desc' => '500,000 being from level 1 <br/>One-time Payment of $300,000 <br/>Lifetime weekly payment $6,000'),
			array('amount' => 12000000, 'level1' => 1000000, 'bonus' => 700000, 'weekly' => 10000, 'name' => 'Co-founder', 'desc' => '1,000,000 being from level 1 <br/>One-time Payment of $700,000 <br/>Lifetime weekly payment $10,000'),
		);
	}

	function qs_flex_display_names() {
		$names = array();
		foreach (qs_flex_engine_bars() as $bar) {
			$names[] = $bar['name'];
		}
		return $names;
	}

	function qs_flex_reward_label($bonus, $weekly) {
		$label = 'One-time Payment $' . number_format((float) $bonus);
		if ((float) $weekly > 0) {
			$label .= ' + $' . number_format((float) $weekly) . '/week Lifetime';
		}
		return $label;
	}

	function qs_flex_ranks() {
		$ranks = array();
		foreach (qs_flex_engine_bars() as $bar) {
			$weekly = isset($bar['weekly']) ? (float) $bar['weekly'] : 0.0;
			$benefits = array(
				'$' . number_format((float) $bar['level1']) . ' being from level 1',
				'One-time Payment $' . number_format((float) $bar['bonus']),
			);
			if ($weekly > 0) {
				$benefits[] = 'Lifetime weekly payment $' . number_format($weekly);
			}
			$ranks[] = array(
				'name' => $bar['name'],
				'engine_name' => $bar['name'],
				'amount' => (float) $bar['amount'],
				'level1' => (float) $bar['level1'],
				'qualify_amount' => (float) $bar['amount'],
				'qualify_level1' => (float) $bar['level1'],
				'bonus' => (float) $bar['bonus'],
				'weekly' => $weekly,
				'rewards' => qs_flex_reward_label($bar['bonus'], $weekly),
				'benefits' => $benefits,
			);
		}
		return $ranks;
	}

	function qs_flex_ensure_points_column($mysqli) {
		$col = mysqli_query($mysqli, "SHOW COLUMNS FROM users LIKE 'quantum_points_redeemed'");
		if ($col && mysqli_num_rows($col) === 0) {
			mysqli_query($mysqli, "ALTER TABLE users ADD `quantum_points_redeemed` int(11) NOT NULL DEFAULT 0");
		}
	}

	function qs_flex_point_rewards() {
		return array(
			'fee' => array('title' => '$10 Fee Credit', 'cost' => 1000, 'credit' => 10),
			'support' => array('title' => 'Pro Support (30d)', 'cost' => 5000, 'credit' => 0),
			'event' => array('title' => 'Event Pass', 'cost' => 15000, 'credit' => 0),
		);
	}

	function qs_flex_team_volume($mysqli, $referralLink, $maxLevels = 7) {
		$volume = 0.0;
		qs_flex_walk_downline($mysqli, $referralLink, 1, $maxLevels, function ($user, $level) use ($mysqli, &$volume) {
			$res = mysqli_query($mysqli, "SELECT COALESCE(SUM(amount),0) AS s FROM investment WHERE userid='" . (int) $user['id'] . "' AND bonus='0'");
			$row = $res ? mysqli_fetch_assoc($res) : null;
			$volume += $row ? (float) $row['s'] : 0;
		});
		return $volume;
	}

	function qs_flex_org_levels($mysqli, $referralLink, $maxLevels = 7) {
		$levels = array();
		for ($i = 1; $i <= $maxLevels; $i++) {
			$levels[$i] = array('members' => 0, 'active' => 0, 'sales' => 0.0);
		}
		qs_flex_walk_downline($mysqli, $referralLink, 1, $maxLevels, function ($user, $level) use ($mysqli, &$levels) {
			$levels[$level]['members']++;
			$res = mysqli_query($mysqli, "SELECT COALESCE(SUM(amount),0) AS s FROM investment WHERE userid='" . (int) $user['id'] . "' AND bonus='0'");
			$row = $res ? mysqli_fetch_assoc($res) : null;
			$sales = $row ? (float) $row['s'] : 0;
			$levels[$level]['sales'] += $sales;
			$isActive = $sales > 0;
			if (!$isActive && function_exists('qs_is_active_member')) {
				$isActive = qs_is_active_member($user);
			}
			if ($isActive) {
				$levels[$level]['active']++;
			}
		});
		return $levels;
	}

	function qs_flex_walk_downline($mysqli, $referralLink, $level, $maxLevels, $callback) {
		if ($level > $maxLevels || $referralLink === '' || $referralLink === null) {
			return;
		}
		$link = mysqli_real_escape_string($mysqli, $referralLink);
		$res = mysqli_query($mysqli, "SELECT * FROM users WHERE referred='$link'");
		if (!$res) {
			return;
		}
		while ($user = mysqli_fetch_assoc($res)) {
			$callback($user, $level);
			qs_flex_walk_downline($mysqli, $user['referal_link'], $level + 1, $maxLevels, $callback);
		}
	}
}
