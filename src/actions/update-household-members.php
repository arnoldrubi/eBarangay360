<?php

require_once __DIR__ . '/../../config/bootstrap.php';


$household_id = $_POST['member_household_id'] ?? null;
$residents = $_POST['residents'] ?? [];
$relationships = $_POST['relationship'] ?? [];
$created_at = date('Y-m-d H:i:s');

if (!$household_id || empty($residents) || count($residents) !== count($relationships)) {
    die('Missing or mismatched data');
}

// Prevent duplicate resident entries
if (count($residents) !== count(array_unique($residents))) {
    die('Duplicate resident detected. Each resident can only be added once.');
}
// Prepare SQL for inserting household members

try {
    $pdo->beginTransaction();

    // Delete existing members for this household
    $deleteStmt = $pdo->prepare("DELETE FROM household_members WHERE household_id = ?");
    $deleteStmt->execute([$household_id]);

    // Prepare insert
    $insertStmt = $pdo->prepare("
        INSERT INTO household_members (household_id, resident_id, relationship_to_head, created_at) 
        VALUES (?, ?, ?, ?)
    ");

    // Insert each member
    foreach ($residents as $i => $resident_id) {
        $insertStmt->execute([
            $household_id,
            $resident_id,
            $relationships[$i],
            $created_at
        ]);
    }

    $pdo->commit();

    header("Location: ../../public/index.php?page=households&success=1");
    exit;

} catch (PDOException $e) {
    $pdo->rollBack();
    die("Failed to update household members: " . $e->getMessage());
}



