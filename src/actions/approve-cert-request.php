<?php
require_once __DIR__ . '/../../config/bootstrap.php';
require_once '../helpers/validations.php';


if (!isset($_POST['id']) || !isset($_POST['cert_type'])) die("Invalid request");

$cert_type = $_POST['cert_type'];
$request_id = $_POST['id'];
$is_approved = $_POST['is_approved'] === 'approved' ? 'pending' : 'approved';
$db_name = '';

if($cert_type === 'barangay-clearance'){
    $db_name = 'barangay_clearance_requests';
} elseif($cert_type === 'barangay-certificate-of-indigency'){
    $db_name = 'barangay_indigency_requests';
} elseif($cert_type === 'barangay-certificate'){
    $db_name = 'barangay_certificate_requests';
}

try {
    $stmt = $pdo->prepare("UPDATE $db_name SET `status` = '$is_approved' WHERE id = :id");
    $stmt->execute([':id' => $request_id]);

    echo "success";
} catch (Exception $e) {
    die("Failed to approve request: " . $e->getMessage());
}
