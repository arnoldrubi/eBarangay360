<?php
require_once __DIR__ . '/../../config/bootstrap.php';
require_once '../helpers/validations.php';


if (!isset($_POST['id'])) die("Invalid request");

try {
    $stmt = $pdo->prepare("UPDATE announcements SET is_deleted = 1, updated_at = NOW() WHERE id = :id");
    $stmt->execute([':id' => $_POST['id']]);

    echo "success";
} catch (Exception $e) {
    die("Failed to delete post: " . $e->getMessage());
}
