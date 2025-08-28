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

function requireRoles(array $roles) {
    if (!isset($_SESSION['username'])) {
        header("Location: index.php?loginrequired=1");
        exit;
    }

    if (!in_array($_SESSION['role'], $roles)) {
        header("Location: index.php?page=dashboard&unauthorized=1");
        exit;
    }
}

function sendMail($to, $subject, $message, $from = "no-reply@yourdomain.com") {
    // Basic headers
    $headers  = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: eBarangay360 <{$from}>" . "\r\n";

    // Send email
    return mail($to, $subject, $message, $headers);
}

// utility functions for returning province, city and barangay name
function returnBarangayName($barangayId, PDO $pdo) {
    $stmt = $pdo->prepare("SELECT name FROM barangays WHERE barangay_id = :id");
    $stmt->execute(['id' => $barangayId]);
    return $stmt->fetchColumn();
}

function returnCityName($cityId, PDO $pdo) {
    $stmt = $pdo->prepare("SELECT name FROM city_municipality WHERE city_municipal_id = :id");
    $stmt->execute(['id' => $cityId]);
    return $stmt->fetchColumn();
}

function returnProvinceName($provinceId, PDO $pdo) {
    $stmt = $pdo->prepare("SELECT name FROM provinces WHERE province_id = :id");
    $stmt->execute(['id' => $provinceId]);
    return $stmt->fetchColumn();
}