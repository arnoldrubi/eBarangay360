<?php 

function generateHouseholdCode(PDO $pdo) {
    $datePart = date('Ymd'); // e.g. 20250722

    // Count how many households were added today
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM households WHERE DATE(created_at) = CURDATE()");
    $stmt->execute();
    $countToday = $stmt->fetchColumn() + 1;

    $sequence = str_pad($countToday, 3, '0', STR_PAD_LEFT); // e.g. 001

    return "HH-$datePart-$sequence";
}

function generateBlotterCode($pdo) {
    $today = date('Ymd');
    $stmt = $pdo->prepare("SELECT COUNT(*) + 1 AS count FROM blotter_reports WHERE DATE(created_at) = CURDATE()");
    $stmt->execute();
    $count = str_pad($stmt->fetch()['count'], 4, '0', STR_PAD_LEFT);
    return "BR-{$today}-{$count}";
}



function getOrdinal($number) {
    $suffixes = ['th','st','nd','rd','th','th','th','th','th','th'];
    if ((($number % 100) >= 11) && (($number % 100) <= 13))
        return $number . 'th';
    else
        return $number . $suffixes[$number % 10];
}
