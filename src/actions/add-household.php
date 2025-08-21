<?php
require_once __DIR__ . '/../../config/bootstrap.php';
require_once BASE_PATH . '/src/helpers/utilities.php';


$householdCode = generateHouseholdCode($pdo);
$householdHead = (int) $_POST['household_head_id'];
$ownership = clean($_POST['ownership_status']);
$address_street = clean($_POST['present_street']);
$address_zone = clean($_POST['present_zone']);
$address_landmark = clean($_POST['present_landmark']);
$notes = clean($_POST['notes']);
$createdAt = date('Y-m-d H:i:s');

$required = [
  'household_code' => $householdCode,
  'household_head_id' => $householdHead,
  'ownership_status' => $ownership
];

validateRequiredFields($required);

try {
  $stmt = $pdo->prepare("
    INSERT INTO households (household_code, head_id, ownership_status, address_street, address_zone, address_landmark, notes, created_at)
    VALUES (:name, :head, :ownership,:address_street,:address_zone,:address_landmark, :notes, :created_at)
  ");
  $stmt->execute([
    ':name' => $householdCode,
    ':head' => $householdHead,
    ':ownership' => $ownership,
    ':address_street' => $address_street,
    ':address_zone' => $address_zone,
    ':address_landmark' => $address_landmark,
    ':notes' => $notes,
    ':created_at' => $createdAt
  ]);

  $household_id = $pdo->lastInsertId();

  header("Location: ../../public/index.php?page=add-household-members&success=1&household_id=$household_id");
  exit;

} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}
