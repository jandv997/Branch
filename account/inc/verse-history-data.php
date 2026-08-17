<?php
if (!function_exists('qs_parse_qs_date')) {
	function qs_parse_qs_date($dateStr) {
		if (!is_string($dateStr) || $dateStr === '') {
			return 0;
		}
		$normalized = trim($dateStr);
		$normalized = preg_replace('/\s*,\s*/', ' ', $normalized);
		$normalized = preg_replace('/\s+:\s+/', ':', $normalized);
		$normalized = preg_replace('/(\d)(am|pm)$/i', '$1 $2', $normalized);
		$ts = strtotime($normalized);
		return $ts ? (int) $ts : 0;
	}
}

if (!function_exists('qs_investment_start_ts')) {
	function qs_investment_start_ts($row) {
		if (!empty($row['created_at'])) {
			$ts = strtotime($row['created_at']);
			if ($ts) {
				return (int) $ts;
			}
		}
		if (!empty($row['date'])) {
			$ts = qs_parse_qs_date($row['date']);
			if ($ts) {
				return $ts;
			}
			$ts = strtotime(preg_replace('/\s*,.*$/', '', $row['date']));
			if ($ts) {
				return (int) $ts;
			}
		}
		return time();
	}
}

if (!function_exists('qs_activity_matches_investment')) {
	function qs_activity_matches_investment($act, $row) {
		$invId = (int) $row['id'];
		$actInv = isset($act['investmentid']) ? (int) $act['investmentid'] : 0;
		if ($actInv > 0) {
			return $actInv === $invId;
		}
		$name = isset($row['name']) ? $row['name'] : '';
		if ($name === '') {
			return false;
		}
		$action = isset($act['action']) ? $act['action'] : '';
		$describe = isset($act['describe']) ? $act['describe'] : '';
		return (strpos($action, $name) !== false) || (strpos($describe, $name) !== false);
	}
}

if (!function_exists('qs_history_funding_label')) {
	function qs_history_funding_label($act) {
		$status = strtolower(trim(isset($act['status']) ? $act['status'] : ''));
		$describe = strtolower(isset($act['describe']) ? $act['describe'] : '');
		if ($status === 'pending') {
			return 'direct';
		}
		if (strpos($describe, 'from main') !== false) {
			return 'main';
		}
		if (strpos($describe, 'from staking') !== false) {
			return 'staking';
		}
		if (strpos($describe, 'from referral') !== false) {
			return 'referral';
		}
		if (strpos($describe, 'from ') !== false) {
			return 'wallet';
		}
		return 'direct';
	}
}

if (!function_exists('qs_history_attr')) {
	function qs_history_attr($data) {
		return htmlspecialchars(
			json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
			ENT_QUOTES,
			'UTF-8'
		);
	}
}

if (!function_exists('qs_build_portfolio_history')) {
	function qs_build_portfolio_history($row, $activities) {
		$payout = (int) $row['payout'];
		$startTs = qs_investment_start_ts($row);
		$byDay = array();
		$topups = array();

		foreach ($activities as $act) {
			if (!qs_activity_matches_investment($act, $row)) {
				continue;
			}
			$action = isset($act['action']) ? $act['action'] : '';
			$amount = isset($act['amount']) ? (float) $act['amount'] : 0;
			$actTs = qs_parse_qs_date(isset($act['date']) ? $act['date'] : '');

			if (stripos($action, 'Return on Investment') === 0) {
				$dayKey = $actTs ? date('Y-m-d', $actTs) : (isset($act['date']) ? $act['date'] : uniqid('d', true));
				if (!isset($byDay[$dayKey])) {
					$dayNum = 1;
					if ($actTs && $startTs) {
						$dayNum = (int) floor(($actTs - $startTs) / 86400) + 1;
						if ($dayNum < 1) {
							$dayNum = 1;
						}
					}
					$byDay[$dayKey] = array(
						'day' => $dayNum,
						'ts' => $actTs,
						'm' => 0.0,
						's' => 0.0,
					);
				}
				$toStaking = (stripos($action, '(75%)') !== false)
					|| ($payout === 2 && stripos($action, '(25%)') === false);
				if ($toStaking) {
					$byDay[$dayKey]['s'] += $amount;
				} else {
					$byDay[$dayKey]['m'] += $amount;
				}
			} elseif (stripos($action, 'Reinvestment into') === 0 || stripos($action, 'Deposit into') === 0) {
				$labelDate = $actTs ? date('M j, Y', $actTs) : (isset($act['date']) ? $act['date'] : '');
				$topups[] = array(
					'when' => $labelDate . ' · ' . qs_history_funding_label($act),
					'amount' => $amount,
					'ts' => $actTs,
				);
			}
		}

		$days = array_values($byDay);
		usort($days, function ($a, $b) {
			if ($a['ts'] === $b['ts']) {
				return $a['day'] > $b['day'] ? -1 : ($a['day'] < $b['day'] ? 1 : 0);
			}
			return ($a['ts'] > $b['ts']) ? -1 : 1;
		});

		$parsed = false;
		foreach ($days as $d) {
			if (!empty($d['ts'])) {
				$parsed = true;
				break;
			}
		}
		if (!$parsed && count($days) > 0) {
			$n = count($days);
			for ($i = 0; $i < $n; $i++) {
				$days[$i]['day'] = $n - $i;
			}
		}

		$payouts = array();
		foreach ($days as $d) {
			$payouts[] = array(
				'day' => (int) $d['day'],
				'main' => (float) $d['m'],
				'staking' => (float) $d['s'],
			);
		}

		usort($topups, function ($a, $b) {
			if ($a['ts'] === $b['ts']) {
				return 0;
			}
			return ($a['ts'] > $b['ts']) ? -1 : 1;
		});
		$topupOut = array();
		foreach ($topups as $t) {
			$topupOut[] = array(
				'when' => $t['when'],
				'amount' => (float) $t['amount'],
			);
		}

		return array('payouts' => $payouts, 'topups' => $topupOut);
	}
}
