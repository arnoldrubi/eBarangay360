<?php
require_once __DIR__ . '/../../config/bootstrap.php';

// Collect form data
$user_id    = $_POST['user_id'] ?? '';
$username   = clean($_POST['username'] ?? '');
$full_name  = clean($_POST['full_name'] ?? '');
$role       = clean($_POST['role'] ?? '');
$email      = clean($_POST['email'] ?? '');
$password   = $_POST['password'] ?? ''; // raw input
$updated_at = date('Y-m-d H:i:s');

if (empty($user_id)) {
    die("Invalid user ID.");
}

try {
    // ✅ Check for duplicate username (excluding this user)
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username AND id != :id LIMIT 1");
    $stmt->execute([':username' => $username, ':id' => $user_id]);
    if ($stmt->fetch()) {
        die("Username already exists!");
    }

    // ✅ Build update query dynamically (only hash password if provided)
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $pdo->prepare("
            UPDATE users 
            SET username = :username,
                full_name = :full_name,
                email = :email,
                role = :role,
                password = :password,
                updated_at = :updated_at
            WHERE id = :id
        ");

        $stmt->execute([
            ':username'   => $username,
            ':full_name'  => $full_name,
            ':email'      => $email,
            ':role'       => $role,
            ':password'   => $hashed_password,
            ':updated_at' => $updated_at,
            ':id'         => $user_id,
        ]);

    } else {
        // ✅ Update without password
        $stmt = $pdo->prepare("
            UPDATE users 
            SET username = :username,
                full_name = :full_name,
                email = :email,
                role = :role,
                updated_at = :updated_at
            WHERE id = :id
        ");

        $stmt->execute([
            ':username'   => $username,
            ':full_name'  => $full_name,
            ':email'      => $email,
            ':role'       => $role,
            ':updated_at' => $updated_at,
            ':id'         => $user_id,
        ]);
    }

    header("Location: ../../public/index.php?page=manage-users&updated=1");
    exit;

} catch (PDOException $e) {
    die("Error updating user: " . $e->getMessage());
}
