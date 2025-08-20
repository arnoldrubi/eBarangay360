<?php
require_once '../../config/bootstrap.php';

$term = $_GET['term'] ?? '';

$stmt = $pdo->prepare("
    SELECT id, first_name, middle_name, last_name, date_of_birth, gender, civil_status, phone_number, email,
           present_province, present_city_municipality, present_barangay, present_zone, present_street, present_landmark
    FROM residents
    WHERE CONCAT(first_name, ' ', middle_name, ' ', last_name) LIKE :term
    LIMIT 10
");
$stmt->execute(['term' => "%$term%"]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($results);
