<?php

require_once __DIR__ . '/../../config/bootstrap.php';
require_once BASE_PATH . '/src/helpers/utilities.php';


try {
$stmt = $pdo->prepare("
    UPDATE blotter_reports SET
        blotter_code = :blotter_code,
        complainant_resident_id = :complainant_resident_id,
        complainant_first_name = :complainant_first_name,
        complainant_middle_name = :complainant_middle_name,
        complainant_last_name = :complainant_last_name,
        complainant_dob = :complainant_dob,
        complainant_gender = :complainant_gender,
        complainant_civil_status = :complainant_civil_status,
        complainant_phone = :complainant_phone,
        complainant_email = :complainant_email,
        complainant_province = :complainant_province,
        complainant_city = :complainant_city,
        complainant_barangay = :complainant_barangay,
        complainant_zone = :complainant_zone,
        complainant_street = :complainant_street,
        complainant_landmark = :complainant_landmark,

        suspect_resident_id = :suspect_resident_id,
        suspect_first_name = :suspect_first_name,
        suspect_middle_name = :suspect_middle_name,
        suspect_last_name = :suspect_last_name,
        suspect_dob = :suspect_dob,
        suspect_gender = :suspect_gender,
        suspect_civil_status = :suspect_civil_status,
        suspect_phone = :suspect_phone,
        suspect_email = :suspect_email,
        suspect_province = :suspect_province,
        suspect_city = :suspect_city,
        suspect_barangay = :suspect_barangay,
        suspect_zone = :suspect_zone,
        suspect_street = :suspect_street,
        suspect_landmark = :suspect_landmark,

        victim_first_name = :victim_first_name,
        victim_middle_name = :victim_middle_name,
        victim_last_name = :victim_last_name,
        victim_age = :victim_age,

        involved_parties = :involved_parties,

        incident_type = :incident_type,
        date_of_incident = :date_of_incident,
        time_of_incident = :time_of_incident,
        incident_location = :incident_location,
        incident_description = :incident_description,

        attending_officer_first_name = :attending_officer_first_name,
        attending_officer_middle_name = :attending_officer_middle_name,
        attending_officer_last_name = :attending_officer_last_name,

        note_on_evidence = :note_on_evidence,

        resolution = :resolution,
        resolution_date = :resolution_date
    WHERE id = :blotter_id
");

$stmt->execute([
    ':blotter_code' => clean($_POST['blotter_code']), // Don't generate again — reuse existing
    ':complainant_resident_id' => $_POST['complainant_resident_id'] ?? null,
    ':complainant_first_name' => clean($_POST['complainant_first_name']),
    ':complainant_middle_name' => clean($_POST['complainant_middle_name']),
    ':complainant_last_name' => clean($_POST['complainant_last_name']),
    ':complainant_dob' => $_POST['complainant_dob'],
    ':complainant_gender' => $_POST['complainant_gender'],
    ':complainant_civil_status' => $_POST['complainant_civil_status'],
    ':complainant_phone' => clean($_POST['complainant_phone']),
    ':complainant_email' => clean($_POST['complainant_email']),
    ':complainant_province' => $_POST['complainant_province'],
    ':complainant_city' => $_POST['complainant_city_municipality'],
    ':complainant_barangay' => $_POST['complainant_barangay'],
    ':complainant_zone' => $_POST['complainant_zone'],
    ':complainant_street' => $_POST['complainant_street'],
    ':complainant_landmark' => $_POST['complainant_landmark'],

    ':suspect_resident_id' => $_POST['suspect_resident_id'] ?? null,
    ':suspect_first_name' => clean($_POST['suspect_first_name']),
    ':suspect_middle_name' => clean($_POST['suspect_middle_name']),
    ':suspect_last_name' => clean($_POST['suspect_last_name']),
    ':suspect_dob' => $_POST['suspect_dob'],
    ':suspect_gender' => $_POST['suspect_gender'],
    ':suspect_civil_status' => $_POST['suspect_civil_status'],
    ':suspect_phone' => clean($_POST['suspect_phone']),
    ':suspect_email' => clean($_POST['suspect_email']),
    ':suspect_province' => $_POST['suspect_province'],
    ':suspect_city' => $_POST['suspect_city_municipality'],
    ':suspect_barangay' => $_POST['suspect_barangay'],
    ':suspect_zone' => $_POST['suspect_zone'],
    ':suspect_street' => $_POST['suspect_street'],
    ':suspect_landmark' => $_POST['suspect_landmark'],

    ':victim_first_name' => clean($_POST['victim_first_name']),
    ':victim_middle_name' => clean($_POST['victim_middle_name']),
    ':victim_last_name' => clean($_POST['victim_last_name']),
    ':victim_age' => $_POST['victim_age'] ?? null,

    ':involved_parties' => clean($_POST['involved_parties']),

    ':incident_type' => clean($_POST['type_of_incident']),
    ':date_of_incident' => $_POST['date_of_incident'],
    ':time_of_incident' => $_POST['time_of_incident'],
    ':incident_location' => clean($_POST['incident_location']),
    ':incident_description' => clean($_POST['incident_description']),

    ':attending_officer_first_name' => clean($_POST['attending_officer_first_name']),
    ':attending_officer_middle_name' => clean($_POST['attending_officer_middle_name']),
    ':attending_officer_last_name' => clean($_POST['attending_officer_last_name']),

    ':note_on_evidence' => clean($_POST['note_on_evidence']),

    ':resolution' => clean($_POST['resolution']),
    ':resolution_date' => $_POST['date_of_resolution'] ?? null,

    ':blotter_id' => $_POST['blotter_id'] ?? null
]);

    $blotter_id = $_POST['blotter_id'] ?? null;
    if (!$blotter_id) {
        throw new Exception("Blotter ID is required for updating.");
    }

    // Handle multiple file uploads (photos/videos)
    $uploadDir = BASE_PATH . '/public/uploads/blotter_evidence/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    if (!empty($_FILES['evidence']['name'][0])) {
        foreach ($_FILES['evidence']['tmp_name'] as $i => $tmpPath) {
            if ($_FILES['evidence']['error'][$i] === UPLOAD_ERR_OK) {
                $originalName = basename($_FILES['evidence']['name'][$i]);
                $ext = pathinfo($originalName, PATHINFO_EXTENSION);
                $safeName = 'evidence_' . time() . '_' . uniqid() . '.' . $ext;

                move_uploaded_file($tmpPath, $uploadDir . $safeName);

                // Save to related table
                $stmt = $pdo->prepare("INSERT INTO blotter_evidence_files (blotter_id, file_name, uploaded_at) VALUES (?, ?, NOW())");
                $stmt->execute([$blotter_id, $safeName]);
            }
        }
    }

    header('Location: ../../public/index.php?page=blotter-reports&edit_success=1');
    exit;

} catch (Exception $e) {
    die("Failed to update blotter report: " . $e->getMessage());
}
