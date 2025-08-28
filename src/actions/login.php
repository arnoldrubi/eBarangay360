<?php
require_once __DIR__ . '/../../config/database.php';

session_start();

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user && password_verify($_POST['password'], $user['password'])) {
    // ✅ login successful
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['full_name'] = $user['full_name'];
    $_SESSION['resident_id'] = $user['resident_id'] ?? null; // in case resident_id is not set  
    header("Location: ../../public/index.php?page=dashboard");
    exit;
}
if (!$user) {
    error_log("No user found for username: " . $_POST['username']);
    header('Location: ../../public/index.php?loginerror=1');
} elseif (!password_verify($_POST['password'], $user['password'])) {
    error_log("Password mismatch for user: " . $_POST['username']);
    header('Location: ../../public/index.php?loginerror=1');
} else {
    header('Location: ../../public/index.php?loginerror=1');
    exit;
}
