<?php
require_once '../../config/bootstrap.php';

$id = $_GET['id'] ?? 0;

// Get household data
$stmt = $pdo->prepare("SELECT * FROM households WHERE id = ? LIMIT 1");
$stmt->execute([$id]);
$household = $stmt->fetch(PDO::FETCH_ASSOC);

$head_id = $household['head_id'];
// Get eligible household head options
$stmt = $pdo->prepare("
  SELECT r.id, CONCAT(r.last_name, ', ', r.first_name, ' ', r.middle_name) AS full_name
  FROM residents r
  WHERE r.id NOT IN (
    SELECT head_id FROM households WHERE head_id IS NOT NULL AND head_id != :head_id
    UNION
    SELECT resident_id FROM household_members
  )
  OR r.id = :head_id
");

$stmt->execute(['head_id' => $head_id]);
$headOptions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get household members data
$stmt = $pdo->prepare("
  SELECT hm.id AS member_id, r.id AS resident_id, 
         CONCAT(r.last_name, ', ', r.first_name, ' ', r.middle_name) AS full_name,
         hm.relationship_to_head
  FROM household_members hm
  JOIN residents r ON hm.resident_id = r.id
  WHERE hm.household_id = ?
");
$stmt->execute([$id]);
$household_members = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get eligible household member options
$stmt = $pdo->prepare("
  SELECT r.id, CONCAT(r.last_name, ', ', r.first_name, ' ', r.middle_name) AS full_name
  FROM residents r
  WHERE r.id NOT IN (
    SELECT head_id FROM households WHERE head_id IS NOT NULL
  )
");

$stmt->execute();
$memberOptions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Combine both into one response
echo json_encode([
    'household' => $household,
    'headOptions' => $headOptions,
    'household_members' => $household_members,
    'household_members_options' => $memberOptions
]);
