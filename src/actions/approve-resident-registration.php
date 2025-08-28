<?php
require_once __DIR__ . '/../../config/bootstrap.php';
require_once '../helpers/validations.php';
require_once '../helpers/utilities.php';

if (!isset($_POST['id']) || !isset($_POST['reg_status'])) {
    die("Invalid request");
}

try {
    $resident_id = $_POST['id'];
    $reg_status  = $_POST['reg_status'] === 'pending' ? 'approved' : 'pending';

    // 1. Update resident status
    $stmt = $pdo->prepare("
        UPDATE residents 
        SET reg_status = :reg_status, updated_at = NOW() 
        WHERE id = :id
    ");
    $stmt->execute([
        ':id' => $resident_id,
        ':reg_status' => $reg_status
    ]);

    // 2. Fetch resident details
    $stmt = $pdo->prepare("SELECT * FROM residents WHERE id = ?");
    $stmt->execute([$resident_id]);
    $resident = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$resident) {
        throw new Exception("Resident not found.");
    }

    // Assign resident data
    extract($resident); // now you have $first_name, $last_name, $email, etc.

    // 3. Prepare user account data
    $random_number = rand(100, 999);
    $username   = strtolower($first_name . '.' . $last_name . $random_number);
    $full_name  = $first_name . ' ' . $last_name;
    $role       = 'user';
    $email      = $email; // from residents table
    $password   = 'password123'; // default password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // 4. Check duplicate username
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username LIMIT 1");
    $stmt->execute([':username' => $username]);
    if ($stmt->fetch()) {
        throw new Exception("Username already exists!");
    }

    // 5. Insert new user linked to resident
    $stmt = $pdo->prepare("
        INSERT INTO users (resident_id, username, full_name, email, role, password, created_at, updated_at)
        VALUES (:resident_id, :username, :full_name, :email, :role, :password, NOW(), NOW())
    ");

    $stmt->execute([
        ':resident_id' => $resident_id,
        ':username'   => $username,
        ':full_name'  => $full_name,
        ':email'      => $email,
        ':role'       => $role,
        ':password'   => $hashed_password,
    ]);

    echo "user registration success";

    // Welcome email to new user
    $subject = "Your eBarangay360 Account";
    $message = "
        <h3>Welcome to eBarangay360</h3>
        <p>Hello {$full_name},</p>
        <p>An account has been created for you.</p>
        <p><b>Username:</b> {$username}<br>
        <b>Password:</b> {$password} (please change it after login)</p>
        <p>You can login here: <a href='https://yourdomain.com/login.php'>Login</a></p>
        <br>
        <p>– eBarangay360 Team</p>
    ";

    sendMail($email, $subject, $message);


} catch (Exception $e) {
    die("Operation failed: " . $e->getMessage());
}
