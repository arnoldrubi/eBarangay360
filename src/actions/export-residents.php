<?php
require_once __DIR__ . '/../../config/bootstrap.php';

// Set headers for CSV download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="residents_export.csv"');

// Output buffer
$output = fopen('php://output', 'w');

// Column headers
fputcsv($output, [
  'ID',
  'First Name',
  'Middle Name',
  'Last Name',
  'Date of Birth',
  'Age',
  'Gender',
  'Civil Status',
  'Phone',
  'Email',
  'Occupation',
  'Employment Status',
  'Status'
]);

// Fetch residents (excluding deleted)
$stmt = $pdo->query("SELECT * FROM residents WHERE is_deleted = 0 ORDER BY last_name ASC");

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  fputcsv($output, [
    $row['id'],
    $row['first_name'],
    $row['middle_name'],
    $row['last_name'],
    $row['date_of_birth'],
    date_diff(date_create($row['date_of_birth']), date_create('today'))->y, // Calculate age
    $row['gender'],
    $row['civil_status'],
    $row['phone_number'],
    $row['email'],
    $row['occupation'],
    $row['unemployed'] == 1 ? 'Unemployed' : 'Employed',
    $row['alive'] == 1 ? 'Alive' : 'Deceased'
  ]);
}

fclose($output);
exit;
