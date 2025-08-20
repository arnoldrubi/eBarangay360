<?php
require_once __DIR__ . '/../../config/bootstrap.php';

$id = $_POST['id'] ?? null;

if (!$id) {
  echo 'Missing ID';
  exit;
}

$stmt = $pdo->prepare("DELETE FROM households WHERE id = ?");
$deleted = $stmt->execute([$id]);

// delete household members
$deleteStmt = $pdo->prepare("DELETE FROM household_members WHERE household_id = ?");
$deleteStmt->execute([$id]);

echo $deleted ? 'success' : 'error';
