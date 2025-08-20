<?php
require_once __DIR__ . '/../../config/bootstrap.php';

$id = $_POST['household_id'] ?? null;
$head_id = $_POST['edit_household_head_id'] ?? null;
$zone = $_POST['edit_zone'] ?? null;
$street = $_POST['edit_street'] ?? null;
$landmark = $_POST['edit_landmark'] ?? null;
$ownership = $_POST['edit_ownership'] ?? null;

if (!$id) {
  echo 'Invalid household ID';
  exit;
}

$stmt = $pdo->prepare("
  UPDATE households SET
    head_id = :head_id,
    address_zone = :zone,
    address_street = :street,
    address_landmark = :landmark,
    ownership_status = :ownership,
    updated_at = NOW()
  WHERE id = :id
");

$stmt->execute([
  ':head_id' => $head_id,
  ':zone' => clean($zone),
  ':street' => clean($street),
  ':landmark' => clean($landmark),
  ':ownership' => $ownership,
  ':id' => $id
]);

header("Location: ../../public/index.php?page=households&household_updated=1");
exit;
