<?php
require_once __DIR__ . '/../../config/bootstrap.php';
require_once BASE_PATH . '/src/helpers/utilities.php';


$resident_id = clean($_POST['resident_id']);
$purpose = clean($_POST['purpose']);
$status = 'approved';
$requested_at = date('Y-m-d H:i:s');

$required = [
  'resident_id' => $resident_id,
  'purpose' => $purpose,
  'status' => $status,
];

validateRequiredFields($required);

try {
  $stmt = $pdo->prepare("
    INSERT INTO barangay_clearance_requests (resident_id, purpose, status, requested_at)
    VALUES (:resident_id, :purpose, :status, :requested_at)
  ");
  $stmt->execute([
    ':resident_id' => $resident_id,
    ':purpose' => $purpose,
    ':status' => $status,
    ':requested_at' => $requested_at
  ]);

  //get the latest request
  $latestRequest = $pdo->lastInsertId();

  header("Location: ../../public/index.php?page=barangay-clearance&success=1&request_id=" . $latestRequest);
  exit;

} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}
