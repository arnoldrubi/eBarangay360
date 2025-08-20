<?php
require '../../config/database.php';
$city_municipal_id = $_GET['city_municipal_id'] ?? 0;
$stmt = $pdo->prepare("SELECT barangay_id, name FROM barangays WHERE city_municipal_id = ?");
$stmt->execute([$city_municipal_id]);
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

