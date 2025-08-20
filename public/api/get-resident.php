<?php
require_once '../../config/bootstrap.php';

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM residents WHERE id = ?");
$stmt->execute([$id]);
echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
