<?php
require '../../config/database.php';
$province_id = $_GET['province_id'] ?? 0;
$stmt = $pdo->prepare("SELECT city_municipal_id, name FROM city_municipality WHERE province_id = ?");
$stmt->execute([$province_id]);
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
