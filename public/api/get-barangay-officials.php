<?php
require_once '../../config/bootstrap.php';

$stmt = $pdo->prepare("SELECT * FROM barangay_officials");
$stmt->execute();
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
