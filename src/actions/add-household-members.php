<?php

require_once __DIR__ . '/../../config/bootstrap.php';


$household_id = $_POST['household_id'] ?? null;
$residents = $_POST['residents'] ?? [];
$relationships = $_POST['relationship'] ?? [];
$created_at = date('Y-m-d H:i:s');

if (!$household_id || empty($residents) || count($residents) !== count($relationships)) {
    die('Missing or mismatched data');
}

$stmt = $pdo->prepare("INSERT INTO household_members (household_id, resident_id, relationship_to_head, created_at) VALUES (?, ?, ?, ?)");

for ($i = 0; $i < count($residents); $i++) {
    $stmt->execute([$household_id, $residents[$i], $relationships[$i], $created_at]);
}

header("Location: ../../public/index.php?page=households&success=1");
exit;
