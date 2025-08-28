<?php
require_once __DIR__ . '/../../config/bootstrap.php';

// utility functions for returning province, city and barangay name
function returnBarangayName($barangayId, PDO $pdo) {
    $stmt = $pdo->prepare("SELECT name FROM barangays WHERE barangay_id = :id");
    $stmt->execute(['id' => $barangayId]);
    return $stmt->fetchColumn();
}

function returnCityName($cityId, PDO $pdo) {
    $stmt = $pdo->prepare("SELECT name FROM city_municipality WHERE city_municipal_id = :id");
    $stmt->execute(['id' => $cityId]);
    return $stmt->fetchColumn();
}

function returnProvinceName($provinceId, PDO $pdo) {
    $stmt = $pdo->prepare("SELECT name FROM provinces WHERE province_id = :id");
    $stmt->execute(['id' => $provinceId]);
    return $stmt->fetchColumn();
}

// Set headers for CSV download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="blotter_incidents_export.csv"');

// Output buffer
$output = fopen('php://output', 'w');

// Column headers
fputcsv($output, [
  'Blotter Code',
  'Complainant First Name',
  'Complainant Middle Name',
  'Complainant Last Name',
  'Complainant Date of Birth',
  'Complainant Age',
  'Complainant Gender',
  'Complainant Civil Status',
  'Complainant Phone',
  'Complainant Email',
  'Complainant Province',
  'Complainant City',
  'Complainant Barangay',
  'Suspect First Name',
  'Suspect Middle Name',
  'Suspect Last Name',
  'Suspect Date of Birth',
  'Suspect Age',
  'Suspect Gender',
  'Suspect Civil Status',
  'Suspect Phone',
  'Suspect Email',
  'Suspect Province',
  'Suspect City',
  'Suspect Barangay',
  'Incident Type',
  'Date of Incident',
  'Time of Incident',
  'Incident Location',
  'Incident Description',
  'Status',
  'Resolution',
  'Resolution Date',
  'Attending Officer',
  'Date Created',
  'Date Updated',
]);

// Fetch residents (excluding deleted)
$stmt = $pdo->query("SELECT * FROM blotter_reports WHERE is_deleted = 0 ORDER BY updated_at ASC");

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  fputcsv($output, [
    $row['blotter_code'],
    $row['complainant_first_name'],
    $row['complainant_middle_name'],
    $row['complainant_last_name'],
    $row['complainant_dob'],
    date_diff(date_create($row['complainant_dob']), date_create('today'))->y, // Calculate age
    $row['complainant_gender'],
    $row['complainant_civil_status'],
    $row['complainant_phone'],
    $row['complainant_email'],
    returnProvinceName($row['complainant_province'], $pdo),
    returnCityName($row['complainant_city'], $pdo),
    returnBarangayName($row['complainant_barangay'], $pdo),
    $row['suspect_first_name'],
    $row['suspect_middle_name'],
    $row['suspect_last_name'],
    $row['suspect_dob'],
    date_diff(date_create($row['suspect_dob']), date_create('today'))->y, // Calculate age
    $row['suspect_gender'],
    $row['suspect_civil_status'],
    $row['suspect_phone'],
    $row['suspect_email'],
    returnProvinceName($row['suspect_province'], $pdo),
    returnCityName($row['suspect_city'], $pdo),
    returnBarangayName($row['suspect_barangay'], $pdo),
    $row['incident_type'],
    $row['date_of_incident'],
    $row['time_of_incident'],
    $row['incident_location'],
    $row['incident_description'],
    $row['status'],
    $row['resolution'],
    $row['resolution_date'],
    $row['attending_officer_first_name'] . ' ' . $row['attending_officer_last_name'],
    $row['created_at'],
    $row['updated_at']
  ]);
}

fclose($output);
exit;
