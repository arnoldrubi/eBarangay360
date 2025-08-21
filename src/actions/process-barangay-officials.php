<?php
require_once __DIR__ . '/../../config/bootstrap.php';
require_once BASE_PATH . '/src/helpers/utilities.php';

$action = $_POST['action'] ?? '';
$official_id = $_POST['official_id'] ?? '';
$official_first_name = $_POST['official_first_name'] ?? '';
$official_last_name = $_POST['official_last_name'] ?? '';
$official_middle_name = $_POST['official_middle_name'] ?? '';
$official_suffix = $_POST['official_suffix'] ?? '';
$official_position = $_POST['official_position'] ?? '';
$official_order = $_POST['official_order'] ?? '';
$created_at = date('Y-m-d H:i:s');

$required = [
  'official_first_name' => $official_first_name,
  'official_last_name' => $official_last_name,
  'official_position' => $official_position,
];

validateRequiredFields($required);

try {
    if ($action === 'update') {
        $stmt = $pdo->prepare("
            UPDATE barangay_officials SET
                first_name = :first_name,
                last_name = :last_name,
                middle_name = :middle_name,
                suffix = :suffix,
                position = :position,
                order_no = :order_no,
                updated_at = :updated_at
            WHERE id = :official_id
        ");
        $stmt->execute([
            ':first_name' => $official_first_name,
            ':last_name' => $official_last_name,
            ':middle_name' => $official_middle_name,
            ':suffix' => $official_suffix,
            ':position' => $official_position,
            ':order_no' => $official_order,
            ':updated_at' => $created_at,
            ':official_id' => $official_id,
        ]);
        header("Location: ../../public/index.php?page=barangay-officials&updated=1");
        exit;
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO barangay_officials 
                (first_name, last_name, middle_name, suffix, position, order_no, created_at) 
            VALUES 
                (:first_name, :last_name, :middle_name, :suffix, :position, :order_no, :created_at)
        ");
        $stmt->execute([
            ':first_name' => $official_first_name,
            ':last_name' => $official_last_name,
            ':middle_name' => $official_middle_name,
            ':suffix' => $official_suffix,
            ':position' => $official_position,
            ':order_no' => $official_order,
            ':created_at' => $created_at,
        ]);
        header("Location: ../../public/index.php?page=barangay-officials&success=1");
        exit;
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
