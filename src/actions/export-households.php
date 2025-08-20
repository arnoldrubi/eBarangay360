<?php
require_once __DIR__ . '/../../config/bootstrap.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=households_export.csv');

// Open output stream
$output = fopen('php://output', 'w');

// CSV Header
fputcsv($output, ['Household Code', 'Household Head', 'Zone', 'Street', 'Landmark', 'Member Full Name', 'Relationship to Head']);

// Query households with their members
$stmt = $pdo->query("
  SELECT 
    h.household_code AS household_code,
    CONCAT(head.last_name, ', ', head.first_name, ' ', head.middle_name) AS household_head,
    h.address_zone AS address_zone,
    h.address_street AS address_street,
    h.address_landmark AS address_landmark,
    CONCAT(r.last_name, ', ', r.first_name, ' ', r.middle_name) AS member_full_name,
    hm.relationship_to_head
  FROM households h
  LEFT JOIN residents head ON h.head_id = head.id
  LEFT JOIN household_members hm ON h.id = hm.household_id
  LEFT JOIN residents r ON hm.resident_id = r.id
  ORDER BY h.id, hm.id
");

// Output each row
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, [
        $row['household_code'],
        $row['household_head'],
        $row['address_zone'],
        $row['address_street'],
        $row['address_landmark'],
        $row['member_full_name'],
        $row['relationship_to_head']
    ]);
}

fclose($output);
exit;
