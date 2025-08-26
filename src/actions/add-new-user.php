<?php
require_once __DIR__ . '/../../config/bootstrap.php';


// Collect form data
$username   = clean($_POST['username'] ?? '');
$full_name  = clean($_POST['full_name'] ?? '');
$role      = clean($_POST['role'] ?? '');
$email      = clean($_POST['email'] ?? '');
$password   = $_POST['password'] ?? ''; // raw input
$created_at = date('Y-m-d H:i:s');

try {
    // Check for duplicate username
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username LIMIT 1");
    $stmt->execute([':username' => $username]);
    if ($stmt->fetch()) {
        die("Username already exists!");
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert user
    $stmt = $pdo->prepare("
        INSERT INTO users (username, full_name, email, role, password, created_at, updated_at)
        VALUES (:username, :full_name, :email, :role, :password, :created_at, :updated_at)
    ");

    $stmt->execute([
        ':username'   => $username,
        ':full_name'  => $full_name,
        ':email'      => $email,
        ':role'       => $role,
        ':password'   => $hashed_password,
        ':created_at' => $created_at,
        ':updated_at' => $created_at,
    ]);

    header("Location: ../../public/index.php?page=manage-users&success=1");
    exit;

} catch (PDOException $e) {
    die("Error updating user: " . $e->getMessage());
}

