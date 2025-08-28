<?php
require_once __DIR__ . '/../../config/bootstrap.php';
require_once '../helpers/validations.php';


if (!isset($_POST['id'])) die("Invalid request");

$id = $_POST['id'];
$cert_type = $_POST['cert_type'];
$db_name = '';
if($cert_type === 'barangay-clearance'){
    $db_name = 'barangay_clearance_requests';
} elseif($cert_type === 'barangay-certificate-of-indigency'){
    $db_name = 'barangay_indigency_requests';
} elseif($cert_type === 'barangay-certificate'){
    $db_name = 'barangay_certificate_requests';
}

try {
    $stmt = $pdo->prepare("DELETE FROM $db_name WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);

    echo "success";
} catch (Exception $e) {
    die("Failed to delete post: " . $e->getMessage());
}
