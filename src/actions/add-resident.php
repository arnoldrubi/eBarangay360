<?php

require_once __DIR__ . '/../../config/database.php';

require_once '../helpers/validations.php';


// Validate required fields`

$required = [
  'first_name', 'last_name', 'birthdate', 'gender',
  'civil_status', 'present_province', 'present_city',
  'present_barangay', 'present_zone', 'phone', 'permanent_province',
  'permanent_city', 'permanent_barangay', 'email'
];

$missingFields = validateRequiredFields($required);

if (!empty($missingFields)) {
  // Optional: save old input or display a message
  echo 'Missing fields: ' . implode(', ', $missingFields);
  exit;
}

function clean($value) {
  return htmlspecialchars(trim($value));
}

// 1. Handle file upload

$photoName = null;

if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
  $tmpName = $_FILES['photo']['tmp_name'];
  $originalName = basename($_FILES['photo']['name']);
  $extension = pathinfo($originalName, PATHINFO_EXTENSION);
  $photoName = 'resident_' . time() . '.' . $extension;

  
  $uploadPath = '../../public/uploads/residents/' . $photoName;
  move_uploaded_file($tmpName, $uploadPath);
}

// Case 2: Captured webcam photo (base64)
elseif (!empty($_POST['captured_photo'])) {
  $base64 = $_POST['captured_photo'];
  if (preg_match('/^data:image\/(\w+);base64,/', $base64, $matches)) {
    $type = $matches[1]; // e.g., png
    $data = substr($base64, strpos($base64, ',') + 1);
    $data = base64_decode($data);

    if ($data !== false) {
      $photoName = 'captured_' . time() . '.' . $type;
      file_put_contents('../../public/uploads/residents/' . $photoName, $data);
    }
  }
}

else{
  // No photo uploaded or captured
  $photoName = 'default.jpg'; // Use a default image if no photo is provided
}

// 2. Prepare insert statement
$stmt = $pdo->prepare("
  INSERT INTO residents (
    first_name, middle_name, last_name,
    date_of_birth, gender, civil_status, phone_number,
    email, alias_nickname, occupation,
    place_of_birth_province, place_of_birth_city_municipality, place_of_birth_barangay,
    permanent_province, permanent_city_municipality, permanent_barangay, permanent_street, permanent_zone, permanent_landmark,
    present_province, present_city_municipality, present_barangay, present_street, present_zone, present_landmark,
    alive, valid_id_type, valid_id_number, photo_filename, created_at, unemployed, status, registration_source
  ) VALUES (
    :fname, :mname, :lname,
    :birthdate, :gender, :cstatus, :phone,
    :email, :alias, :occupation,
    :birth_prov, :birth_city, :birth_barangay,
    :perm_prov, :perm_city, :perm_brgy, :perm_street, :perm_zone, :perm_landmark,
    :pres_prov, :pres_city, :pres_brgy, :pres_street, :pres_zone, :pres_landmark,
    :status, :idtype, :idnum, :photo, :created_at, :unemployed, :status, :registration_source
  )
");


// 3. Bind parameters
$stmt->execute([
  ':fname' => clean($_POST['first_name']),
  ':mname' => clean($_POST['middle_name']),
  ':lname' => clean($_POST['last_name']),
  ':birthdate' => $_POST['birthdate'],
  ':birth_prov' => $_POST['birth_province'],
  ':birth_city' => $_POST['birth_city'],
  ':birth_barangay' => $_POST['birth_barangay'],
  ':gender' => $_POST['gender'],
  ':cstatus' => $_POST['civil_status'],
  ':phone' => clean($_POST['phone']),
  ':email' => clean($_POST['email']),
  ':alias' => clean($_POST['alias']),
  ':occupation' => clean($_POST['occupation']),
  ':pres_prov' => $_POST['present_province'],
  ':pres_city' => $_POST['present_city'],
  ':pres_brgy' => $_POST['present_barangay'],
  ':pres_zone' => $_POST['present_zone'],
  ':pres_street' => $_POST['present_street'],
  ':pres_landmark' => $_POST['present_landmark'],
  ':perm_prov' => $_POST['permanent_province'],
  ':perm_city' => $_POST['permanent_city'],
  ':perm_brgy' => $_POST['permanent_barangay'],
  ':perm_zone' => $_POST['permanent_zone'],
  ':perm_street' => $_POST['permanent_street'],
  ':perm_landmark' => $_POST['permanent_landmark'],
  ':status' => isset($_POST['status']) ? '1' : '0', // Use '1' for Alive, '0' for Deceased
  ':unemployed' => isset($_POST['unemployed']) ? '1' : '0', //
  ':idtype' => clean($_POST['valid_id_type']),
  ':idnum' => clean($_POST['valid_id_number']),
  ':photo' => $photoName,
  ':created_at' => date('Y-m-d H:i:s'),
  ':registration_source' => 'admin',
  ':status' => 'approved'
]);

header('Location: ../../public/index.php?page=residents&success=1');
exit;
