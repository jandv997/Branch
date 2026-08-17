<?php
if (!function_exists('qs_flex_display_names')) {
	function qs_flex_display_names() {
		return array(
			'Explorer',
			'Analyst',
			'Strategist',
			'Architect',
			'Director',
			'Executive',
			'Partner',
			'Senior Partner',
			'Regional Leader',
			'Global Leader',
			'Ambassador',
			'Quantum Elite',
		);
	}

	function qs_flex_engine_bars() {
		return array(
			array('amount' => 3500, 'level1' => 1000, 'bonus' => 200, 'name' => 'Beginner', 'desc' => '1000 being from level 1 <br/>One time payment of 200'),
			array('amount' => 8000, 'level1' => 2500, 'bonus' => 500, 'name' => 'Promoter', 'desc' => '2,500 being from level 1 <br/>One time payment of 500'),
			array('amount' => 15000, 'level1' => 4500, 'bonus' => 800, 'name' => 'Elite', 'desc' => '4,500 being from level 1 <br/> One time payment of 800'),
			array('amount' => 35000, 'level1' => 10000, 'bonus' => 1750, 'name' => 'Leader', 'desc' => '10,000 being from level 1 <br/>One time payment of 1,750 <br/>lifetime weekly payment 70'),
			array('amount' => 70000, 'level1' => 20000, 'bonus' => 3500, 'name' => 'Mentor', 'desc' => '20,000 being from level 1 <br/>One time payment of 3,500 <br/>lifetime weekly payment 150'),
			array('amount' => 150000, 'level1' => 50000, 'bonus' => 7500, 'name' => 'Director', 'desc' => '50,000 being from level 1 <br/>One time payment of 7,500 <br/>lifetime weekly payment 350'),
			array('amount' => 250000, 'level1' => 100000, 'bonus' => 15000, 'name' => 'Ambassador', 'desc' => '100,000 being from level 1 <br/>One time payment of 15,000 <br/>lifetime weekly payment 550'),
			array('amount' => 500000, 'level1' => 200000, 'bonus' => 25000, 'name' => 'Master', 'desc' => '200,000 being from level 1 <br/>One time payment of 25,000 <br/>lifetime weekly payment 1000'),
			array('amount' => 1000000, 'level1' => 300000, 'bonus' => 50000, 'name' => 'Executive', 'desc' => '300,000 being from level 1 <br/>One time payment of 50,000 <br/>lifetime weekly payment 1750'),
			array('amount' => 2000000, 'level1' => 500000, 'bonus' => 150000, 'name' => 'Visionary', 'desc' => '500,000 being from level 1 <br/>One time payment 150,000 <br/>Lifetime daily payment 3,000'),
			array('amount' => 5000000, 'level1' => 750000, 'bonus' => 300000, 'name' => 'Legend', 'desc' => '750,000 being from level 1 <br/>One time payment 300,000 <br/>Lifetime daily payment 6,000'),
			array('amount' => 12000000, 'level1' => 1000000, 'bonus' => 700000, 'name' => 'Director X', 'desc' => '1,000,000 being from level 1 <br/>One time payment 700,000 <br/>Lifetime daily payment 10,000'),
		);
	}

	function qs_flex_ranks() {
		$names = qs_flex_display_names();
		$bars = qs_flex_engine_bars();
		$ranks = array();
		foreach ($bars as $i => $bar) {
			$benefits = array();
			foreach (explode('<br/>', $bar['desc']) as $line) {
				$line = trim($line);
				if ($line !== '') {
					$benefits[] = $line;
				}
			}
			if ($i === 0) {
				$benefits = array(
					'Access to 7-level referral commissions',
					'Referral wallet payouts',
				);
			}
			$ranks[] = array(
				'name' => $names[$i],
				'engine_name' => $bar['name'],
				'amount' => ($i === 0) ? 0.0 : (float) $bar['amount'],
				'level1' => ($i === 0) ? 0.0 : (float) $bar['level1'],
				'qualify_amount' => (float) $bar['amount'],
				'qualify_level1' => (float) $bar['level1'],
				'bonus' => (float) $bar['bonus'],
				'rewards' => ($i === 0) ? 'Platform access' : ('One-time $' . number_format((float) $bar['bonus'], 2)),
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
