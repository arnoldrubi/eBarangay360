<?php
require_once __DIR__ . '/../../config/bootstrap.php';
require_once '../helpers/validations.php';


if (!isset($_POST['id']) || !isset($_POST['is_pinned'])) die("Invalid request");

try {
    $stmt = $pdo->prepare("UPDATE announcements SET is_pinned = :is_pinned, updated_at = NOW() WHERE id = :id");
    $stmt->execute([':id' => $_POST['id'], ':is_pinned' => $_POST['is_pinned']]);

    echo "success";
} catch (Exception $e) {
    die("Failed to pin post: " . $e->getMessage());
}
