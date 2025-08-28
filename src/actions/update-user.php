<?php
require_once __DIR__ . '/../../config/bootstrap.php';

$id = $_POST['user_id'] ?? null;
if (!$id) {
  echo 'Invalid user ID';
  exit;
}

// Collect form data
$username   = clean($_POST['username'] ?? '');
$full_name  = clean($_POST['full_name'] ?? '');
$email      = clean($_POST['email'] ?? '');
$password   = $_POST['password'] ?? ''; // raw input
$updated_at = date('Y-m-d H:i:s');

try {
    // Build base query
    $query = "
        UPDATE users SET
            full_name = :full_name,
            updated_at = :updated_at
    ";

    $params = [
        ':full_name'  => $full_name,
        ':updated_at' => $updated_at,
        ':id'         => $id
    ];

    // If password is filled, update it too
    if (!empty($password)) {
        $query .= ", password = :password";
        $params[':password'] = password_hash($password, PASSWORD_DEFAULT);
    }

    $query .= " WHERE id = :id";

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    // Update session data so changes reflect immediately
    $_SESSION['user']['username']  = $username;
    $_SESSION['user']['full_name'] = $full_name;
    $_SESSION['user']['email']     = $email;

    header("Location: ../../public/index.php?page=manage-account&success=1");
    exit;

} catch (PDOException $e) {
    die("Error updating user: " . $e->getMessage());
}
