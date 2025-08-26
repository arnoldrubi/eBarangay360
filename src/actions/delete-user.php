<?php
require_once __DIR__ . '/../../config/bootstrap.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'] ?? '';

    if (empty($user_id)) {
        die("Invalid request.");
    }

    try {
        // Get username first
        $stmt = $pdo->prepare("SELECT username FROM users WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            die("User not found.");
        }

        // Prevent admin deletion
        if (strtolower($user['username']) === 'admin') {
            die("The admin account cannot be deleted.");
        }

        // Delete the user
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute([':id' => $user_id]);
        echo $stmt ? 'success' : 'error';

    } catch (Exception $e) {
        die("Failed to delete user: " . $e->getMessage());
    }
} else {
    header("Location: ../../public/index.php?page=manage-users");
    exit;
}
