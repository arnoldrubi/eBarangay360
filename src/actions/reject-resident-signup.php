<?php
require_once __DIR__ . '/../../config/bootstrap.php';

$id = $_POST['id'] ?? null;

if (!$id) {
  echo 'Missing ID';
  exit;
}

$stmt = $pdo->prepare("DELETE FROM residents WHERE id = ?");
$deleted = $stmt->execute([$id]);

echo $deleted ? 'success' : 'error';
