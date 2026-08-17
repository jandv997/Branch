<?php
date_default_timezone_set('America/New_York');
include('connection.php');

// Define the bars array with all levels, thresholds, and bonuses
// Array index 0 = Level 1 (Beginner), index 1 = Level 2 (Promoter), etc.
$bars = [
    ["amount" => 3500, "level1" => 1000, "bonus" => 200, "name" => "Beginner", "desc" => "1000 being from level 1 <br/>One time payment of 200"],
    ["amount" => 8000, "level1" => 2500, "bonus" => 500, "name" => "Promoter", "desc" => "2,500 being from level 1 <br/>One time payment of 500"],
    ["amount" => 15000, "level1" => 4500, "bonus" => 800, "name" => "Elite", "desc" => "4,500 being from level 1 <br/> One time payment of 800"],
    ["amount" => 35000, "level1" => 10000, "bonus" => 1750, "name" => "Leader", "desc" => "10,000 being from level 1 <br/>One time payment of 1,750 <br/>lifetime weekly payment 70"],
    ["amount" => 70000, "level1" => 20000, "bonus" => 3500, "name" => "Mentor", "desc" => "20,000 being from level 1 <br/>One time payment of 3,500 <br/>lifetime weekly payment 150"],
    ["amount" => 150000, "level1" => 50000, "bonus" => 7500, "name" => "Director", "desc" => "50,000 being from level 1 <br/>One time payment of 7,500 <br/>lifetime weekly payment 350"],
    ["amount" => 250000, "level1" => 100000, "bonus" => 15000, "name" => "Ambassador", "desc" => "100,000 being from level 1 <br/>One time payment of 15,000 <br/>lifetime weekly payment 550"],
    ["amount" => 500000, "level1" => 200000, "bonus" => 25000, "name" => "Master", "desc" => "200,000 being from level 1 <br/>One time payment of 25,000 <br/>lifetime weekly payment 1000"],
    ["amount" => 1000000, "level1" => 300000, "bonus" => 50000, "name" => "Executive", "desc" => "300,000 being from level 1 <br/>One time payment of 50,000 <br/>lifetime weekly payment 1750"],
    ["amount" => 2000000, "level1" => 500000, "bonus" => 150000, "name" => "Visionary", "desc" => "500,000 being from level 1 <br/>One time payment 150,000 <br/>Lifetime daily payment 3,000"],
    ["amount" => 5000000, "level1" => 750000, "bonus" => 300000, "name" => "Legend", "desc" => "750,000 being from level 1 <br/>One time payment 300,000 <br/>Lifetime daily payment 6,000"],
    ["amount" => 12000000, "level1" => 1000000, "bonus" => 700000, "name" => "Director X", "desc" => "1,000,000 being from level 1 <br/>One time payment 700,000 <br/>Lifetime daily payment 10,000"]
];

/**
 * Convert array index to human-readable level number
 */
function getHumanLevelNumber($arrayIndex) {
    return $arrayIndex + 1; // Index 0 = Level 1, Index 1 = Level 2, etc.
}

/**
 * Get the level name from array index
 */
function getLevelName($bars, $arrayIndex) {
    if ($arrayIndex < 0 || $arrayIndex >= count($bars)) {
        return 'All Levels Completed';
    }
    return $bars[$arrayIndex]['name'];
}

/**
 * Get the human level number from array index
 */
function getLevelDisplay($bars, $arrayIndex) {
    if ($arrayIndex < 0 || $arrayIndex >= count($bars)) {
        return 'All Levels Completed';
    }
    return 'Level ' . getHumanLevelNumber($arrayIndex) . ' - ' . $bars[$arrayIndex]['name'];
}

/**
 * Create the tracking tables if they don't exist
 */
function createTrackingTables($mysqli)
{
    // Main tracking table for investments
    $sql = "CREATE TABLE IF NOT EXISTS user_investment_tracking (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        investment_id INT NOT NULL,
        downline_user_id INT NOT NULL,
        level INT NOT NULL DEFAULT 1,
        amount DECIMAL(15,2) NOT NULL,
        scanned_at DATETIME NOT NULL,
        is_processed TINYINT(1) DEFAULT 0,
        used_for_level INT DEFAULT NULL,
        level_name VARCHAR(100) DEFAULT NULL,
        INDEX idx_user_level (user_id, level),
        INDEX idx_investment (investment_id),
        INDEX idx_processed (is_processed),
        UNIQUE KEY unique_tracking (user_id, investment_id, downline_user_id, level)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    mysqli_query($mysqli, $sql);
    
    // Table to track level resets and achievements
    $sql2 = "CREATE TABLE IF NOT EXISTS user_level_resets (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        level INT NOT NULL,
        reset_at DATETIME NOT NULL,
        commission_at_reset DECIMAL(15,2) NOT NULL,
        bonus_awarded DECIMAL(15,2) NOT NULL,
        remaining_balance DECIMAL(15,2) DEFAULT 0,
        level1_contribution DECIMAL(15,2) DEFAULT 0,
        total_commission_used DECIMAL(15,2) DEFAULT 0,
        INDEX idx_user_level (user_id, level)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    mysqli_query($mysqli, $sql2);
    
    // Table to track level1 contributions
    $sql3 = "CREATE TABLE IF NOT EXISTS user_level1_tracking (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        investment_id INT NOT NULL,
        downline_user_id INT NOT NULL,
        level INT NOT NULL,
        amount DECIMAL(15,2) NOT NULL,
        tracked_at DATETIME NOT NULL,
        UNIQUE KEY unique_level1 (user_id, investment_id, downline_user_id, level)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    mysqli_query($mysqli, $sql3);
    
    // Table to track user progress summary
    $sql4 = "CREATE TABLE IF NOT EXISTS user_progress_summary (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL UNIQUE,
        current_level INT DEFAULT 0,
        total_commission_all_levels DECIMAL(15,2) DEFAULT 0,
        current_level_commission DECIMAL(15,2) DEFAULT 0,
        amount_needed_for_next_level DECIMAL(15,2) DEFAULT 0,
        level1_total_contribution DECIMAL(15,2) DEFAULT 0,
        last_updated DATETIME NOT NULL,
        progress_percentage DECIMAL(5,2) DEFAULT 0,
        next_level_name VARCHAR(100) DEFAULT NULL,
        next_level_amount DECIMAL(15,2) DEFAULT 0,
        next_level_level1_required DECIMAL(15,2) DEFAULT 0,
        blocked_by_level1 TINYINT(1) DEFAULT 0,
        current_level_human INT DEFAULT 0,
        next_level_human INT DEFAULT 0,
        total_commission_used DECIMAL(15,2) DEFAULT 0,
        INDEX idx_user (user_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    mysqli_query($mysqli, $sql4);
}

/**
 * Get the highest level a user has achieved (array index, starts from 0)
 */
function getUserAchievedLevel($mysqli, $userId)
{
    $query = mysqli_query($mysqli, "SELECT MAX(level) as max_level FROM user_level_resets WHERE user_id='$userId'");
    $result = mysqli_fetch_assoc($query);
    // If no level achieved, return 0 (meaning Level 1 is next)
    return $result['max_level'] ? intval($result['max_level']) : 0;
}

/**
 * Get user's current progress summary
 */
function getUserProgressSummary($mysqli, $userId)
{
    $query = mysqli_query($mysqli, "SELECT * FROM user_progress_summary WHERE user_id='$userId'");
    if (!$query || mysqli_num_rows($query) == 0) {
        return null;
    }
    return mysqli_fetch_assoc($query);
}

/**
 * Update user progress summary
 */
function updateUserProgressSummary($mysqli, $userId, $currentLevel, $totalCommission, $currentLevelCommission, $amountNeeded, $level1Total, $bars, $blockedByLevel1 = false, $totalCommissionUsed = 0)
{
    $now = date('Y-m-d H:i:s');
    $nextLevelIndex = $currentLevel; // Current level is the next one to achieve
    
    $nextLevelName = null;
    $nextLevelAmount = 0;
    $nextLevelLevel1Required = 0;
    $progressPercentage = 0;
    $currentLevelHuman = getHumanLevelNumber($currentLevel);
    $nextLevelHuman = $currentLevelHuman + 1;
    
    // Get next level details if exists
    if ($nextLevelIndex < count($bars)) {
        $nextLevel = $bars[$nextLevelIndex];
        $nextLevelName = $nextLevel['name'];
        $nextLevelAmount = $nextLevel['amount'];
        $nextLevelLevel1Required = $nextLevel['level1'];
        
        // Calculate progress percentage
        if ($nextLevelAmount > 0) {
            $progressPercentage = min(100, ($currentLevelCommission / $nextLevelAmount) * 100);
        }
    } else {
        // User has achieved all levels
        $progressPercentage = 100;
        $nextLevelName = 'All Levels Completed';
        $nextLevelHuman = 0;
    }
    
    // Convert boolean to integer for database
    $blockedByLevel1Int = $blockedByLevel1 ? 1 : 0;
    
    // Insert or update
    $query = "INSERT INTO user_progress_summary 
        (user_id, current_level, total_commission_all_levels, current_level_commission, 
         amount_needed_for_next_level, level1_total_contribution, last_updated, 
         progress_percentage, next_level_name, next_level_amount, next_level_level1_required, 
         blocked_by_level1, current_level_human, next_level_human, total_commission_used)
        VALUES (
            '$userId', 
            '$currentLevel', 
            '$totalCommission', 
            '$currentLevelCommission', 
            '$amountNeeded', 
            '$level1Total', 
            '$now', 
            '$progressPercentage', 
            " . ($nextLevelName !== null ? "'$nextLevelName'" : "NULL") . ", 
            '$nextLevelAmount', 
            '$nextLevelLevel1Required', 
            '$blockedByLevel1Int', 
            '$currentLevelHuman', 
            '$nextLevelHuman',
            '$totalCommissionUsed'
        )
        ON DUPLICATE KEY UPDATE 
        current_level = '$currentLevel',
        total_commission_all_levels = '$totalCommission',
        current_level_commission = '$currentLevelCommission',
        amount_needed_for_next_level = '$amountNeeded',
        level1_total_contribution = '$level1Total',
        last_updated = '$now',
        progress_percentage = '$progressPercentage',
        next_level_name = " . ($nextLevelName !== null ? "'$nextLevelName'" : "NULL") . ",
        next_level_amount = '$nextLevelAmount',
        next_level_level1_required = '$nextLevelLevel1Required',
        blocked_by_level1 = '$blockedByLevel1Int',
        current_level_human = '$currentLevelHuman',
        next_level_human = '$nextLevelHuman',
        total_commission_used = '$totalCommissionUsed'";
    
    return mysqli_query($mysqli, $query);
}

/**
 * Check if investment has been tracked for a specific user and level
 */
function isInvestmentTracked($mysqli, $userId, $investmentId, $downlineUserId, $level)
{
    $query = mysqli_query($mysqli, "SELECT id FROM user_investment_tracking 
        WHERE user_id='$userId' AND investment_id='$investmentId' 
        AND downline_user_id='$downlineUserId' AND level='$level'");
    return mysqli_num_rows($query) > 0;
}

/**
 * Track investment for a user at a specific level
 */
function trackInvestment($mysqli, $userId, $investmentId, $downlineUserId, $level, $amount, $levelName = null)
{
    $now = date('Y-m-d H:i:s');
    $levelName = $levelName !== null ? "'$levelName'" : "NULL";
    $query = "INSERT INTO user_investment_tracking 
        (user_id, investment_id, downline_user_id, level, amount, scanned_at, is_processed, used_for_level, level_name) 
        VALUES ('$userId', '$investmentId', '$downlineUserId', '$level', '$amount', '$now', '1', '$level', $levelName)
        ON DUPLICATE KEY UPDATE 
        scanned_at='$now', 
        used_for_level='$level', 
        level_name=$levelName,
        is_processed='1'";
    return mysqli_query($mysqli, $query);
}

/**
 * Track level1 contributions separately
 */
function trackLevel1Contribution($mysqli, $userId, $investmentId, $downlineUserId, $level, $amount)
{
    $now = date('Y-m-d H:i:s');
    $query = "INSERT INTO user_level1_tracking 
        (user_id, investment_id, downline_user_id, level, amount, tracked_at) 
        VALUES ('$userId', '$investmentId', '$downlineUserId', '$level', '$amount', '$now')
        ON DUPLICATE KEY UPDATE tracked_at='$now'";
    return mysqli_query($mysqli, $query);
}

/**
 * Check if investment has been used for level1 requirement
 */
function isLevel1Tracked($mysqli, $userId, $investmentId, $downlineUserId, $level)
{
    $query = mysqli_query($mysqli, "SELECT id FROM user_level1_tracking 
        WHERE user_id='$userId' AND investment_id='$investmentId' 
        AND downline_user_id='$downlineUserId' AND level='$level'");
    return mysqli_num_rows($query) > 0;
}

/**
 * Mark investments as processed for a user at a specific level
 */
function markInvestmentsProcessed($mysqli, $userId, $level)
{
    $query = "UPDATE user_investment_tracking 
        SET is_processed='1' 
        WHERE user_id='$userId' AND level='$level' AND is_processed='0'";
    return mysqli_query($mysqli, $query);
}

/**
 * Record a level reset for a user
 */
function recordLevelReset($mysqli, $userId, $level, $commission, $bonus, $remainingBalance = 0, $level1Contribution = 0, $totalCommissionUsed = 0)
{
    $now = date('Y-m-d H:i:s');
    $query = "INSERT INTO user_level_resets 
        (user_id, level, reset_at, commission_at_reset, bonus_awarded, remaining_balance, level1_contribution, total_commission_used) 
        VALUES ('$userId', '$level', '$now', '$commission', '$bonus', '$remainingBalance', '$level1Contribution', '$totalCommissionUsed')";
    return mysqli_query($mysqli, $query);
}

/**
 * Get remaining balance from last level reset
 */
function getRemainingBalance($mysqli, $userId)
{
    $query = mysqli_query($mysqli, "SELECT remaining_balance FROM user_level_resets 
        WHERE user_id='$userId' ORDER BY id DESC LIMIT 1");
    $result = mysqli_fetch_assoc($query);
    return $result ? floatval($result['remaining_balance']) : 0;
}

/**
 * Get total commission across all levels for a user
 */
function getTotalCommissionAllLevels($mysqli, $userId)
{
    $query = mysqli_query($mysqli, "SELECT SUM(commission_at_reset) as total FROM user_level_resets WHERE user_id='$userId'");
    $result = mysqli_fetch_assoc($query);
    return $result ? floatval($result['total']) : 0;
}

/**
 * Get total commission used across all levels
 */
function getTotalCommissionUsed($mysqli, $userId)
{
    $query = mysqli_query($mysqli, "SELECT SUM(commission_at_reset) as total FROM user_level_resets WHERE user_id='$userId'");
    $result = mysqli_fetch_assoc($query);
    return $result ? floatval($result['total']) : 0;
}

/**
 * Get current level commission (commission accumulated for current level)
 */
function getCurrentLevelCommission($mysqli, $userId)
{
    $remaining = getRemainingBalance($mysqli, $userId);
    return $remaining;
}

/**
 * Get all used investment IDs for a user
 */
function getUsedInvestmentIds($mysqli, $userId)
{
    $usedIds = [];
    $query = mysqli_query($mysqli, "SELECT DISTINCT investment_id FROM user_investment_tracking WHERE user_id='$userId'");
    while ($row = mysqli_fetch_assoc($query)) {
        $usedIds[] = $row['investment_id'];
    }
    return $usedIds;
}

/**
 * Calculate total downline commission for a user (excluding already used investments)
 * Also tracks which investments are being counted
 */
function calculateTotalDownlineCommission($mysqli, $referralLink, $userId, $level, $maxLevels, &$totalCommission, &$level1Commission, &$trackedInvestments, $usedInvestmentIds = [])
{
    if ($level > $maxLevels) {
        return;
    }

    $getRefer = mysqli_query($mysqli, "SELECT * FROM users WHERE referred='$referralLink' AND status=1");
    
    while ($refer = mysqli_fetch_assoc($getRefer)) {
        $getInvestment = mysqli_query($mysqli, "SELECT * FROM investment 
            WHERE userid='{$refer['id']}' AND bonus='0' 
            ORDER BY id DESC");
        
        while ($in = mysqli_fetch_assoc($getInvestment)) {
            $investmentId = $in['id'];
            $mainCapital = $in['amount'];
            $downlineUserId = $refer['id'];
            
            // Check if this investment has already been used
            $isUsed = in_array($investmentId, $usedInvestmentIds);
            
            // Also check in tracking table to be safe
            if (!$isUsed) {
                $checkUsed = mysqli_query($mysqli, "SELECT id FROM user_investment_tracking 
                    WHERE user_id='$userId' AND investment_id='$investmentId' 
                    AND downline_user_id='$downlineUserId'");
                if (mysqli_num_rows($checkUsed) > 0) {
                    $isUsed = true;
                }
            }
            
            // Only count if not used before
            if (!$isUsed) {
                $totalCommission += $mainCapital;
                
                // Track level1 commission separately
                if ($level == 1) {
                    $level1Commission += $mainCapital;
                }
                
                // Store investment details for tracking
                $trackedInvestments[] = [
                    'investment_id' => $investmentId,
                    'downline_user_id' => $downlineUserId,
                    'amount' => $mainCapital,
                    'level' => $level
                ];
            }
        }
        
        calculateTotalDownlineCommission($mysqli, $refer['referal_link'], $userId, $level + 1, $maxLevels, $totalCommission, $level1Commission, $trackedInvestments, $usedInvestmentIds);
    }
}

/**
 * Process bonuses for a user with sequential deduction and level1 requirement
 */
function processUserBonusesSequential($mysqli, $user, $bars)
{
    $userId = $user['id'];
    
    // Get current level (array index, starts from 0)
    $currentLevel = getUserAchievedLevel($mysqli, $userId);
    
    // Get all investments that have already been used for previous levels
    $usedInvestmentIds = getUsedInvestmentIds($mysqli, $userId);
    
    // Track investments that will be counted in this run
    $trackedInvestments = [];
    $totalCommission = 0;
    $level1Commission = 0;
    
    // Calculate total downline commission (excluding already used investments)
    calculateTotalDownlineCommission($mysqli, $user['referal_link'], $userId, 1, 7, $totalCommission, $level1Commission, $trackedInvestments, $usedInvestmentIds);
    
    // Get the total commission already used for previous levels
    $totalCommissionUsed = getTotalCommissionUsed($mysqli, $userId);
    
    $results = [
        'user_id' => $userId,
        'bonuses_awarded' => [],
        'total_commission_raw' => $totalCommission,
        'level1_commission_raw' => $level1Commission,
        'used_commission' => 0,
        'remaining_commission' => $totalCommission,
        'current_level' => $currentLevel,
        'current_level_human' => getHumanLevelNumber($currentLevel),
        'levels_completed' => [],
        'total_bonus_awarded' => 0,
        'blocked_by_level1' => false,
        'amount_needed' => 0,
        'total_commission_used_overall' => $totalCommissionUsed,
        'investments_tracked' => count($trackedInvestments)
    ];
    
    // Start from current level (which is the next level to achieve)
    $levelIndex = $currentLevel;
    $remainingCommission = $totalCommission;
    $blockedByLevel1 = false;
    $commissionUsedThisRun = 0;
    $investmentsUsedThisRun = [];
    
    // Process levels sequentially from current level
    while ($levelIndex < count($bars) && $remainingCommission > 0) {
        $bar = $bars[$levelIndex];
        $level1Requirement = $bar['level1'];
        $humanLevelNumber = getHumanLevelNumber($levelIndex);
        $levelName = $bar['name'];
        
        // Check if level1 requirement is met
        if ($level1Commission < $level1Requirement) {
            // Level1 requirement not met, stop processing
            $blockedByLevel1 = true;
            break;
        }
        
        // Check if we have enough commission for this level
        if ($remainingCommission >= $bar['amount']) {
            // Award bonus for this level
            $bonusAmount = $bar['bonus'];
            
            // Update user wallet
            $newWallet = $user['ref_wallet'] + $bonusAmount;
            mysqli_query($mysqli, "UPDATE users SET ref_wallet='$newWallet' WHERE id='$userId'");
            
            // Deduct the threshold amount from remaining commission
            $remainingCommission -= $bar['amount'];
            $commissionUsedThisRun += $bar['amount'];
            
            // Track the investments used for this level
            // We need to track investments up to the amount used
            $amountToTrack = $bar['amount'];
            $trackedAmount = 0;
            $investmentsForThisLevel = [];
            
            // Process tracked investments in order
            foreach ($trackedInvestments as $investment) {
                if ($trackedAmount >= $amountToTrack) {
                    break;
                }
                
                // Check if this investment has already been used
                if (!in_array($investment['investment_id'], $usedInvestmentIds) && 
                    !in_array($investment['investment_id'], $investmentsUsedThisRun)) {
                    
                    // Track this investment for the current level
                    trackInvestment(
                        $mysqli,
                        $userId,
                        $investment['investment_id'],
                        $investment['downline_user_id'],
                        $humanLevelNumber,
                        $investment['amount'],
                        $levelName
                    );
                    
                    // Track level1 contribution if level is 1
                    if ($levelIndex == 0) {
                        trackLevel1Contribution(
                            $mysqli,
                            $userId,
                            $investment['investment_id'],
                            $investment['downline_user_id'],
                            $humanLevelNumber,
                            $investment['amount']
                        );
                    }
                    
                    $investmentsUsedThisRun[] = $investment['investment_id'];
                    $trackedAmount += $investment['amount'];
                    $investmentsForThisLevel[] = $investment['investment_id'];
                }
            }
            
            // Record the level reset (store the human level number)
            recordLevelReset($mysqli, $userId, $humanLevelNumber, $bar['amount'], $bonusAmount, $remainingCommission, $level1Commission, $totalCommissionUsed + $commissionUsedThisRun);
            
            // Log the activity
            $date = date('Y-m-d H:i:s');
            $action = "Level Bonus Achieved: {$bar['name']}";
            $describe = "User reached Level {$humanLevelNumber} - {$bar['name']}. " .
                       "Commission used: $" . number_format($bar['amount']) . 
                       ". Level1 contribution: $" . number_format($level1Commission) . 
                       ". Bonus: $" . number_format($bonusAmount) . 
                       ". Remaining: $" . number_format($remainingCommission) .
                       ". Investments tracked: " . count($investmentsForThisLevel);
            
            mysqli_query($mysqli, "INSERT INTO activity(userid, action, `describe`, date, amount, status) 
                VALUES('$userId', '$action', '$describe', '$date', '$bonusAmount', 'Credited')");
            
            // Store result
            $results['bonuses_awarded'][] = [
                'level' => $humanLevelNumber,
                'level_name' => $bar['name'],
                'bonus' => $bonusAmount,
                'commission_used' => $bar['amount'],
                'level1_contribution' => $level1Commission,
                'remaining_commission' => $remainingCommission,
                'investments_tracked' => count($investmentsForThisLevel)
            ];
            
            $results['levels_completed'][] = $humanLevelNumber;
            $results['used_commission'] += $bar['amount'];
            $results['total_bonus_awarded'] += $bonusAmount;
            $results['current_level'] = $levelIndex + 1; // Next level to achieve (array index)
            $results['current_level_human'] = getHumanLevelNumber($levelIndex + 1);
            
            // Update used investment IDs for next level
            $usedInvestmentIds = array_merge($usedInvestmentIds, $investmentsUsedThisRun);
            
            // Move to next level
            $levelIndex++;
            
            // If no more commission, stop
            if ($remainingCommission <= 0) {
                break;
            }
        } else {
            // Not enough commission for this level
            break;
        }
    }
    
    $results['remaining_commission'] = $remainingCommission;
    $results['current_level_commission'] = $remainingCommission;
    $results['blocked_by_level1'] = $blockedByLevel1;
    $results['total_commission_used_overall'] = $totalCommissionUsed + $commissionUsedThisRun;
    $results['investments_tracked_total'] = count($trackedInvestments);
    $results['investments_used_this_run'] = count($investmentsUsedThisRun);
    
    // Calculate amount needed for next level
    $nextLevelAmount = 0;
    $amountNeeded = 0;
    if ($levelIndex < count($bars)) {
        $nextLevelAmount = $bars[$levelIndex]['amount'];
        $amountNeeded = max(0, $nextLevelAmount - $remainingCommission);
        $results['amount_needed'] = $amountNeeded;
    } else {
        $results['amount_needed'] = 0;
    }
    
    // Update user progress summary
    $totalCommissionAllLevels = getTotalCommissionAllLevels($mysqli, $userId);
    updateUserProgressSummary(
        $mysqli, 
        $userId, 
        $levelIndex, // Current level index (next to achieve)
        $totalCommissionAllLevels, 
        $remainingCommission, 
        $amountNeeded, 
        $level1Commission, 
        $bars,
        $blockedByLevel1,
        $totalCommissionUsed + $commissionUsedThisRun
    );
    
    return $results;
}

/**
 * Get detailed user progress report with human-readable levels
 */
function getUserProgressReport($mysqli, $userId, $bars)
{
    $summary = getUserProgressSummary($mysqli, $userId);
    
    if (!$summary) {
        return null;
    }
    
    $currentLevel = $summary['current_level'];
    $blockedByLevel1 = $summary['blocked_by_level1'] == 1;
    $currentLevelHuman = $summary['current_level_human'];
    $nextLevelHuman = $summary['next_level_human'];
    
    $report = [
        'user_id' => $userId,
        'current_level' => $currentLevel,
        'current_level_human' => $currentLevelHuman,
        'current_level_name' => $currentLevel < count($bars) ? $bars[$currentLevel]['name'] : 'All Levels Completed',
        'total_commission_all_levels' => $summary['total_commission_all_levels'],
        'current_level_commission' => $summary['current_level_commission'],
        'amount_needed_for_next_level' => $summary['amount_needed_for_next_level'],
        'level1_total_contribution' => $summary['level1_total_contribution'],
        'progress_percentage' => $summary['progress_percentage'],
        'blocked_by_level1' => $blockedByLevel1,
        'total_commission_used' => $summary['total_commission_used'],
        'next_level' => [
            'level_number' => $nextLevelHuman,
            'name' => $summary['next_level_name'],
            'amount_needed' => $summary['next_level_amount'],
            'level1_required' => $summary['next_level_level1_required'],
            'bonus' => $currentLevel < count($bars) ? $bars[$currentLevel]['bonus'] : 0
        ],
        'last_updated' => $summary['last_updated']
    ];
    
    return $report;
}

/**
 * Display user progress in a human-readable format
 */
function displayUserProgress($report)
{
    if (!$report) {
        return "No progress data available.";
    }
    
    $output = "User ID: " . $report['user_id'] . " <br/> \n";
    $output .= "Current Level: " . $report['current_level_human'] . " - " . $report['current_level_name'] . " <br/> \n";
    $output .= "Total Commission Across All Levels: $" . number_format($report['total_commission_all_levels']) . " <br/> \n";
    $output .= "Commission for Current Level: $" . number_format($report['current_level_commission']) . " <br/> \n";
    $output .= "Progress to Next Level: " . number_format($report['progress_percentage'], 2) . "% <br/> \n";
    $output .= "Amount Needed for Next Level: $" . number_format($report['amount_needed_for_next_level']) . " <br/> \n";
    $output .= "Level1 Contribution: $" . number_format($report['level1_total_contribution']) . " <br/> \n";
    $output .= "Total Commission Used: $" . number_format($report['total_commission_used']) . " <br/> \n";
    
    if ($report['blocked_by_level1']) {
        $output .= "⚠️ BLOCKED: Level1 contribution requirement not met for next level.<br/> \n";
        $output .= "   Required Level1: $" . number_format($report['next_level']['level1_required']) . " <br/> \n";
    }
    
    $output .= "\nNext Level: " . $report['next_level']['level_number'] . " - " . $report['next_level']['name'] . " <br/> \n";
    $output .= "  - Total Required: $" . number_format($report['next_level']['amount_needed']) . " <br/> \n";
    $output .= "  - Level1 Required: $" . number_format($report['next_level']['level1_required']) . " <br/> \n";
    $output .= "  - Bonus: $" . number_format($report['next_level']['bonus']) . " <br/><br/> \n";
    
    return $output;
}


/**
 * Main cron job execution with sequential processing
 */
function runBonusCronSequential($mysqli, $bars)
{
    // Create tracking tables if they don't exist
    createTrackingTables($mysqli);
    
    // Get all active users with investments
    $getUsers = mysqli_query($mysqli, "SELECT * FROM users WHERE `status`=1");
    
    if (!$getUsers) {
        error_log("Error fetching users: " . mysqli_error($mysqli));
        return false;
    }
    
    $processed = 0;
    $bonusesAwarded = 0;
    $totalBonusAmount = 0;
    $logEntries = [];
    $progressReports = [];
    
    while ($user = mysqli_fetch_assoc($getUsers)) {
        // Process bonuses for this user with sequential deduction
        $result = processUserBonusesSequential($mysqli, $user, $bars);
        $processed++;
        
        // Get detailed progress report
        $progressReport = getUserProgressReport($mysqli, $user['id'], $bars);
        if ($progressReport) {
            $progressReports[] = $progressReport;
            
            // Display progress for debugging
            if ($processed <= 5) { // Show first 5 users for debugging
                echo "\n" . displayUserProgress($progressReport) . "\n";
            }
        }
        
        if (!empty($result['bonuses_awarded'])) {
            $bonusesAwarded += count($result['bonuses_awarded']);
            foreach ($result['bonuses_awarded'] as $bonus) {
                $totalBonusAmount += $bonus['bonus'];
            }
            
            $logEntries[] = [
                'user_id' => $user['id'],
                'username' => $user['username'] ?? $user['id'],
                'levels' => $result['bonuses_awarded'],
                'current_level' => $result['current_level_human'],
                'total_commission' => $result['total_commission_raw'],
                'remaining_commission' => $result['remaining_commission'],
                'total_bonus_awarded' => $result['total_bonus_awarded'],
                'blocked_by_level1' => $result['blocked_by_level1'],
                'investments_tracked' => $result['investments_tracked_total']
            ];
        }
    }
    
    // Log execution
    $logDate = date('Y-m-d H:i:s');
    $logMessage = "Cron executed at $logDate - Processed: $processed users, "
                 . "Bonuses awarded: $bonusesAwarded, "
                 . "Total bonus amount: $" . number_format($totalBonusAmount);
    
    error_log($logMessage);
    
    // Save detailed log to file
    $logData = [
        'timestamp' => $logDate,
        'summary' => [
            'users_processed' => $processed,
            'bonuses_awarded' => $bonusesAwarded,
            'total_bonus_amount' => $totalBonusAmount
        ],
        'details' => $logEntries,
        'progress_reports' => $progressReports
    ];
    
    // file_put_contents('bonus_cron_log_' . date('Y-m-d') . '.json', 
    //     json_encode($logData, JSON_PRETTY_PRINT) . PHP_EOL, 
    //     FILE_APPEND);
    
    return $logData;
}

// Execute the cron job with sequential processing
try {
    $result = runBonusCronSequential($mysqli, $bars);
    echo "\nCron completed successfully!\n";
    echo "Processed: " . $result['summary']['users_processed'] . " users\n";
    echo "Bonuses awarded: " . $result['summary']['bonuses_awarded'] . "\n";
    echo "Total bonus amount: $" . number_format($result['summary']['total_bonus_amount']) . "\n";
    
} catch (Exception $e) {
    error_log("Bonus Cron Error: " . $e->getMessage());
    echo "Error: " . $e->getMessage() . "\n";
}

// Close connection
mysqli_close($mysqli);
?>