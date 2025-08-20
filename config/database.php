<?php 

$pdo = new PDO('mysql:host=localhost;dbname=ebarangay360_db', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  
// This file is used to connect to the database.
// It is included in other files to establish a database connection.    
// Example usage:
// require 'config/database.php';
// $pdo = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
// $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// Make sure to replace 'your_database', 'username', and 'password' with your actual database credentials.
// If you need to change the database connection settings, do it here.
if (!$pdo) {
    die("Database connection failed: " . $pdo->errorInfo());
}