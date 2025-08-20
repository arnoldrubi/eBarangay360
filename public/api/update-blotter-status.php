<?php
require_once '../../config/bootstrap.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$blotterId = $data['blotter_id'] ?? null;
$status = $data['status'] ?? null;

if (!$blotterId || $status === null) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE blotter_reports SET status = ? WHERE id = ?");
    $stmt->execute([$status, $blotterId]);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
