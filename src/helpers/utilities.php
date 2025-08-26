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

// function sendMail($to, $subject, $message, $from = "no-reply@yourdomain.com") {
//     // Basic headers
//     $headers  = "MIME-Version: 1.0" . "\r\n";
//     $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
//     $headers .= "From: eBarangay360 <{$from}>" . "\r\n";

//     // Send email
//     return mail($to, $subject, $message, $headers);
// }

// // copy to new user registration
// $subject = "Your eBarangay360 Account";
// $message = "
//     <h3>Welcome to eBarangay360</h3>
//     <p>Hello {$new_user['full_name']},</p>
//     <p>An account has been created for you.</p>
//     <p><b>Username:</b> {$new_user['username']}<br>
//        <b>Password:</b> {$plain_password} (please change it after login)</p>
//     <p>You can login here: <a href='https://yourdomain.com/login.php'>Login</a></p>
//     <br>
//     <p>– eBarangay360 Team</p>
// ";

// sendMail($new_user['email'], $subject, $message);
