<?php
require_once __DIR__ . '/../../config/bootstrap.php';

$householdId = $_GET['household_id'] ?? null;

if (!$householdId) {
  echo json_encode([]);
  exit;
}

$stmt = $pdo->prepare("SELECT resident_id, relationship FROM household_members WHERE household_id = ?");
$stmt->execute([$householdId]);
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($members);
