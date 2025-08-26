<?php
require_once __DIR__ . '/../../config/bootstrap.php';

try {
    $username   = 'admin';
    $password   = 'admin123'; // plain password
    $full_name  = 'System Administrator';
    $email      = 'admin@barangay.local';
    $created_at = date('Y-m-d H:i:s');

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if admin already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username LIMIT 1");
    $stmt->execute([':username' => $username]);
    $existing = $stmt->fetch();

    if ($existing) {
        echo "⚠️ User 'admin' already exists (id: {$existing['id']}).";
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO users (username, password, full_name, email, role, created_at)
            VALUES (:username, :password, :full_name, :email, :role, :created_at)
        ");
        $stmt->execute([
            ':username'   => $username,
            ':password'   => $hashed_password,
            ':full_name'  => $full_name,
            ':email'      => $email,
            ':role'       => 'admin', // you can use 'admin' / 'staff' / etc.
            ':created_at' => $created_at,
        ]);

        echo "✅ User 'admin' has been created successfully with password 'admin123'.";
    }

} catch (Exception $e) {
    echo "❌ Error seeding user: " . $e->getMessage();
}
