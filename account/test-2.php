<?php
date_default_timezone_set('America/New_York');
include('connection.php');

/**
 * Check if a date is a weekend (Saturday or Sunday)
 */
function isWeekend($date) {
    $dayOfWeek = date('N', strtotime($date)); // 1=Monday, 7=Sunday
    return $dayOfWeek >= 6; // Saturday (6) or Sunday (7)
}

/**
 * Get all business days between two dates (excluding weekends)
 */
function getBusinessDays($startDate, $endDate) {
    $businessDays = [];
    $currentDate = strtotime($startDate);
    $endDateTime = strtotime($endDate);
    
    while ($currentDate <= $endDateTime) {
        $dateStr = date('Y-m-d', $currentDate);
        if (!isWeekend($dateStr)) {
            $businessDays[] = $dateStr;
        }
        $currentDate = strtotime('+1 day', $currentDate);
    }
    
    return $businessDays;
}

/**
 * Process referral bonuses for an investment
 * Processes from investment creation date to July 9, 2026 (excluding weekends)
 */
function processReferralBonuses($mysqli, $investment, $endDate = '2026-07-09') {
    $row1 = $investment;
    $id = $row1['id'];
    $userid = $row1['userid'];
    $daily_roi = $row1['daily_roi'];
    $investmentDate = $row1['created_at']; // Start date for this investment
    $investmentDateOnly = date('Y-m-d', strtotime($investmentDate));
    
    // Get the user
    $getuser = mysqli_query($mysqli, "SELECT * FROM `users` WHERE id='$userid' ");
    $rowuser = mysqli_fetch_assoc($getuser);
    
    if (!$rowuser) {
        return false;
    }
    
    // Define referral bonus percentages by level
    $levels = [
        1 => 0.10,   // 10%
        2 => 0.05,   // 5%
        3 => 0.025,  // 2.5%
        4 => 0.015,  // 1.5%
        5 => 0.01,   // 1%
        6 => 0.005,  // 0.5%
        7 => 0.0025  // 0.25%
    ];
    
    // Ensure the referral bonus is applied only to real portfolio and not bonus portfolio
    if ($row1['bonus'] == 0) {
        // Get all business days from investment creation date to end date
        $businessDays = getBusinessDays($investmentDateOnly, $endDate);
        
        if (empty($businessDays)) {
            return false;
        }
        
        $bonusesProcessed = 0;
        $currentRefLink = $rowuser['referred'];
        
        echo "  Investment #$id: Processing " . count($businessDays) . " business days from $investmentDateOnly to $endDate\n";
        
        // Process each business day
        foreach ($businessDays as $bonusDate) {
            // Check if this date has already been processed for this investment
            $checkDateProcessed = mysqli_query($mysqli, "SELECT * FROM referral_bonus_log 
                WHERE investment_id='$id' AND bonus_date='$bonusDate'");
            
            if (mysqli_num_rows($checkDateProcessed) > 0) {
                continue; // Skip if already processed for this date
            }
            
            // Process each level for this date
            $currentRefLink = $rowuser['referred']; // Reset for each date
            
            for ($level = 1; $level <= 7; $level++) {
                if ($currentRefLink == "") break; // no more uplines
                
                $bonus = $levels[$level] * $daily_roi;
                
                // Find the referrer
                $getrefer = mysqli_query($mysqli, "SELECT * FROM users WHERE referal_link='" . $currentRefLink . "' ");
                $refer = mysqli_fetch_assoc($getrefer);
                
                if ($refer) {
                    // Check if this specific bonus has already been paid
                    $checkPaid = mysqli_query($mysqli, "SELECT * FROM referral_bonus_log 
                        WHERE investment_id='$id' AND referrer_id='" . $refer['id'] . "' 
                        AND level='$level' AND bonus_date='$bonusDate'");
                    
                    if (mysqli_num_rows($checkPaid) == 0) {
                        // Process the bonus with the specific date
                        $act = "Referral Bonus of " . ($levels[$level] * 100) . "%, Level $level";
                        $desc = "Referral commission of $" . number_format($bonus, 2) . " from investment #$id for date $bonusDate";
                        
                        // Insert into activity with the bonus date
                        mysqli_query($mysqli, "INSERT INTO `activity`(`userid`, `action`, `describe`, `date`, `amount`, `status`) 
                            VALUES('" . $refer['id'] . "', '$act', '$desc', '$bonusDate 00:00:00', '$bonus', 'Credited')");
                        
                        // Insert into referral with the bonus date
                        mysqli_query($mysqli, "INSERT INTO `referal` (`claimerid`, `status`, `date`, `amount`, `detail`) 
                            VALUES('" . $refer['id'] . "', 1, '$bonusDate 00:00:00', '$bonus', '$act')");
                        
                        // Update referrer's wallet
                        $newwallet = $refer['wallet'] + $bonus;
                        mysqli_query($mysqli, "UPDATE users SET wallet='$newwallet' WHERE id='" . $refer['id'] . "' ");
                        
                        // Log this bonus payment with the specific date
                        mysqli_query($mysqli, "INSERT INTO referral_bonus_log 
                            (investment_id, referrer_id, level, amount, bonus_date, paid_at, investment_created_at) 
                            VALUES ('$id', '" . $refer['id'] . "', '$level', '$bonus', '$bonusDate', NOW(), '$investmentDate')");
                        
                        $bonusesProcessed++;
                    }
                }
                
                // Set up for the next loop (go one level higher)
                $currentRefLink = $refer['referred'] ?? "";
            }
        }
        
        // Mark this investment as processed
        if ($bonusesProcessed > 0) {
            mysqli_query($mysqli, "INSERT INTO referral_backlog_processed 
                (investment_id, user_id, processed_at, bonuses_count, investment_created_at, processed_until) 
                VALUES ('$id', '$userid', NOW(), '$bonusesProcessed', '$investmentDate', '$endDate')
                ON DUPLICATE KEY UPDATE 
                bonuses_count = bonuses_count + '$bonusesProcessed',
                processed_at = NOW(),
                processed_until = '$endDate'");
        }
        
        return $bonusesProcessed > 0;
    }
    
    return false;
}

/**
 * Main backlog processing function
 */
function processBacklogBonuses($mysqli, $endDate = '2026-07-09') {
    // Create necessary tables if they don't exist
    createBacklogTables($mysqli);
    
    // Get all investments with status=1
    $investmentQuery = mysqli_query($mysqli, "SELECT * FROM `investment` 
        WHERE status=1 
        AND created_at <= '$endDate 23:59:59'
        ORDER BY created_at ASC");
    
    if (!$investmentQuery) {
        echo "Error fetching investments: " . mysqli_error($mysqli) . "\n";
        return false;
    }
    
    $totalInvestments = mysqli_num_rows($investmentQuery);
    $processedCount = 0;
    $totalBonusesGiven = 0;
    $totalBonusAmount = 0;
    $errors = [];
    $dateStats = [];
    
    echo "=== BACKLOG PROCESSING STARTED ===\n";
    echo "End Date: $endDate\n";
    echo "Total investments to process: $totalInvestments\n";
    echo "Note: Only weekdays (Monday-Friday) will be processed\n\n";
    
    $investmentCounter = 0;
    
    while ($row1 = mysqli_fetch_assoc($investmentQuery)) {
        $investmentCounter++;
        $investmentId = $row1['id'];
        $investmentDate = $row1['created_at'];
        $investmentDateOnly = date('Y-m-d', strtotime($investmentDate));
        
        echo "[$investmentCounter/$totalInvestments] Processing investment #$investmentId (Created: $investmentDate)...\n";
        
        try {
            $result = processReferralBonuses($mysqli, $row1, $endDate);
            
            if ($result) {
                $processedCount++;
                
                // Get stats for this investment
                $statsQuery = mysqli_query($mysqli, "SELECT 
                    COUNT(*) as bonus_count,
                    SUM(amount) as total_amount
                    FROM referral_bonus_log 
                    WHERE investment_id='$investmentId'");
                $stats = mysqli_fetch_assoc($statsQuery);
                
                $totalBonusesGiven += $stats['bonus_count'];
                $totalBonusAmount += $stats['total_amount'];
                
                // Track date statistics
                $dateQuery = mysqli_query($mysqli, "SELECT DISTINCT bonus_date FROM referral_bonus_log 
                    WHERE investment_id='$investmentId'");
                while ($dateRow = mysqli_fetch_assoc($dateQuery)) {
                    $dateStats[$dateRow['bonus_date']] = ($dateStats[$dateRow['bonus_date']] ?? 0) + 1;
                }
                
                echo "  ✓ Processed - " . $stats['bonus_count'] . " bonuses totaling $" . number_format($stats['total_amount'], 2) . "\n";
            } else {
                echo "  ⏭ Skipped (no bonuses or already processed)\n";
            }
        } catch (Exception $e) {
            $errors[] = "Investment #$investmentId: " . $e->getMessage();
            echo "  ✗ Error: " . $e->getMessage() . "\n";
        }
    }
    
    // Get summary statistics
    $summary = getBacklogSummary($mysqli, $endDate);
    
    echo "\n=== BACKLOG PROCESSING COMPLETE ===\n";
    echo "Investments processed: $processedCount\n";
    echo "Total bonuses awarded: " . $summary['total_bonuses'] . "\n";
    echo "Total bonus amount: $" . number_format($summary['total_amount'], 2) . "\n";
    echo "Total unique referrers: " . $summary['unique_referrers'] . "\n";
    
    // Show date distribution
    if (!empty($dateStats)) {
        echo "\n=== BONUSES BY DATE ===\n";
        ksort($dateStats);
        $totalDays = 0;
        foreach ($dateStats as $date => $count) {
            $dayOfWeek = date('l', strtotime($date));
            echo "  $date ($dayOfWeek): $count bonuses\n";
            $totalDays += $count;
        }
        echo "Total business days processed: $totalDays\n";
    }
    
    if (!empty($errors)) {
        echo "\nErrors encountered:\n";
        foreach ($errors as $error) {
            echo "  - $error\n";
        }
    }
    
    return [
        'processed' => $processedCount,
        'bonuses_given' => $summary['total_bonuses'],
        'total_amount' => $summary['total_amount'],
        'date_distribution' => $dateStats,
        'errors' => $errors
    ];
}

/**
 * Create necessary tables for backlog tracking
 */
function createBacklogTables($mysqli) {
    // Table to track processed investments
    $sql1 = "CREATE TABLE IF NOT EXISTS referral_backlog_processed (
        id INT AUTO_INCREMENT PRIMARY KEY,
        investment_id INT NOT NULL,
        user_id INT NOT NULL,
        processed_at DATETIME NOT NULL,
        bonuses_count INT DEFAULT 0,
        investment_created_at DATETIME NOT NULL,
        processed_until DATE NOT NULL,
        UNIQUE KEY unique_investment (investment_id),
        INDEX idx_processed_date (processed_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    mysqli_query($mysqli, $sql1);
    
    // Table to log referral bonus payments
    $sql2 = "CREATE TABLE IF NOT EXISTS referral_bonus_log (
        id INT AUTO_INCREMENT PRIMARY KEY,
        investment_id INT NOT NULL,
        referrer_id INT NOT NULL,
        level INT NOT NULL,
        amount DECIMAL(15,2) NOT NULL,
        bonus_date DATE NOT NULL,
        paid_at DATETIME NOT NULL,
        investment_created_at DATETIME NOT NULL,
        INDEX idx_investment (investment_id),
        INDEX idx_referrer (referrer_id),
        INDEX idx_level (level),
        INDEX idx_bonus_date (bonus_date),
        INDEX idx_paid_date (paid_at),
        UNIQUE KEY unique_bonus (investment_id, referrer_id, level, bonus_date)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    mysqli_query($mysqli, $sql2);
    
    // Table for backlog summary by date
    $sql3 = "CREATE TABLE IF NOT EXISTS referral_backlog_summary (
        id INT AUTO_INCREMENT PRIMARY KEY,
        processed_date DATE NOT NULL,
        total_investments INT DEFAULT 0,
        total_bonuses INT DEFAULT 0,
        total_amount DECIMAL(15,2) DEFAULT 0,
        unique_referrers INT DEFAULT 0,
        UNIQUE KEY unique_date (processed_date)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    mysqli_query($mysqli, $sql3);
}

/**
 * Get backlog processing summary
 */
function getBacklogSummary($mysqli, $endDate) {
    $query = mysqli_query($mysqli, "SELECT 
        COUNT(DISTINCT id) as total_bonuses,
        SUM(amount) as total_amount,
        COUNT(DISTINCT referrer_id) as unique_referrers
        FROM referral_bonus_log 
        WHERE bonus_date <= '$endDate'");
    
    $result = mysqli_fetch_assoc($query);
    
    return [
        'total_bonuses' => $result['total_bonuses'] ?? 0,
        'total_amount' => $result['total_amount'] ?? 0,
        'unique_referrers' => $result['unique_referrers'] ?? 0
    ];
}

/**
 * Check backlog status with date breakdown
 */
function checkBacklogStatus($mysqli) {
    echo "\n=== BACKLOG STATUS ===\n";
    
    // Total investments
    $totalInv = mysqli_query($mysqli, "SELECT COUNT(*) as total FROM investment WHERE status=1");
    $total = mysqli_fetch_assoc($totalInv);
    echo "Total active investments: " . $total['total'] . "\n";
    
    // Processed investments
    $processedInv = mysqli_query($mysqli, "SELECT COUNT(*) as total FROM referral_backlog_processed");
    $processed = mysqli_fetch_assoc($processedInv);
    echo "Processed investments: " . $processed['total'] . "\n";
    
    // Unprocessed investments
    $unprocessed = $total['total'] - $processed['total'];
    echo "Unprocessed investments: " . $unprocessed . "\n";
    
    // Total bonuses given
    $bonusStats = mysqli_query($mysqli, "SELECT 
        COUNT(*) as total_bonuses,
        SUM(amount) as total_amount,
        COUNT(DISTINCT referrer_id) as unique_referrers
        FROM referral_bonus_log");
    $stats = mysqli_fetch_assoc($bonusStats);
    
    echo "\nTotal bonuses given: " . ($stats['total_bonuses'] ?? 0) . "\n";
    echo "Total bonus amount: $" . number_format($stats['total_amount'] ?? 0, 2) . "\n";
    echo "Unique referrers: " . ($stats['unique_referrers'] ?? 0) . "\n";
    
    // Breakdown by date (show only weekdays)
    echo "\n=== BONUSES BY DATE (Weekdays Only) ===\n";
    $dateBreakdown = mysqli_query($mysqli, "SELECT 
        bonus_date,
        COUNT(*) as bonus_count,
        SUM(amount) as total_amount
        FROM referral_bonus_log
        GROUP BY bonus_date
        ORDER BY bonus_date DESC
        LIMIT 20");
    
    while ($row = mysqli_fetch_assoc($dateBreakdown)) {
        $dayOfWeek = date('l', strtotime($row['bonus_date']));
        echo "  " . $row['bonus_date'] . " ($dayOfWeek): " . $row['bonus_count'] . " bonuses totaling $" . number_format($row['total_amount'], 2) . "\n";
    }
    
    // Show date range processed
    $dateRange = mysqli_query($mysqli, "SELECT 
        MIN(bonus_date) as earliest,
        MAX(bonus_date) as latest
        FROM referral_bonus_log");
    $range = mysqli_fetch_assoc($dateRange);
    if ($range['earliest'] && $range['latest']) {
        echo "\nDate range processed: " . $range['earliest'] . " to " . $range['latest'] . "\n";
        
        // Count business days in range
        $businessDays = getBusinessDays($range['earliest'], $range['latest']);
        echo "Total business days in range: " . count($businessDays) . "\n";
    }
}

/**
 * Process a single investment's backlog
 */
function processSingleInvestmentBacklog($mysqli, $investmentId, $endDate = '2026-07-09') {
    $investmentQuery = mysqli_query($mysqli, "SELECT * FROM `investment` 
        WHERE id='$investmentId' AND status=1");
    
    if (!$investmentQuery || mysqli_num_rows($investmentQuery) == 0) {
        echo "Investment #$investmentId not found or not active.\n";
        return false;
    }
    
    $investment = mysqli_fetch_assoc($investmentQuery);
    echo "Processing investment #$investmentId...\n";
    
    return processReferralBonuses($mysqli, $investment, $endDate);
}

// Execute the backlog processing
try {
    // Option 1: Process all investments up to July 9, 2026
    $result = processBacklogBonuses($mysqli, '2026-07-09');
    
    // Option 2: Process a single investment
    // $result = processSingleInvestmentBacklog($mysqli, 123, '2026-07-09');
    
    // Option 3: Process only investments created after a specific date
    // $result = processBacklogBonusesFromDate($mysqli, '2026-01-01', '2026-07-09');
    
} catch (Exception $e) {
    echo "Error processing backlog: " . $e->getMessage() . "\n";
    error_log("Backlog processing error: " . $e->getMessage());
}

// Close connection
mysqli_close($mysqli);

// Uncomment to check status
// checkBacklogStatus($mysqli);
?>