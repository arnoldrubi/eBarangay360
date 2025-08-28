<?php
require_once __DIR__ . '/../../config/bootstrap.php';

$id = $_POST['resident_id'] ?? null;
if (!$id) {
  echo 'Invalid resident ID';
  exit;
}

// Handle photo upload (optional)
$photoName = null;
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
  $tmpName = $_FILES['photo']['tmp_name'];
  $originalName = basename($_FILES['photo']['name']);
  $extension = pathinfo($originalName, PATHINFO_EXTENSION);
  $photoName = 'resident_' . time() . '.' . $extension;

  $uploadPath = BASE_PATH . '/public/uploads/residents/' . $photoName;
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

// Base update SQL
$sql = "
  UPDATE residents SET
    first_name = :first_name,
    middle_name = :middle_name,
    last_name = :last_name,
    date_of_birth = :birthdate,
    place_of_birth_province = :birth_province,
    place_of_birth_city_municipality = :birth_city,
    place_of_birth_barangay = :birth_barangay,
    gender = :gender,
    civil_status = :civil_status,
    phone_number = :phone,
    email = :email,
    alias_nickname = :alias,
    occupation = :occupation,
    present_province = :present_province,
    present_city_municipality = :present_city,
    present_barangay = :present_barangay,
    present_zone = :present_zone,
    present_street = :present_street,
    present_landmark = :present_landmark,
    permanent_province = :permanent_province,
    permanent_city_municipality = :permanent_city,
    permanent_barangay = :permanent_barangay,
    permanent_zone = :permanent_zone,
    permanent_street = :permanent_street,
    permanent_landmark = :permanent_landmark,
    alias_nickname = :alias_nickname,
    alive = :alive,
    unemployed = :unemployed,
    valid_id_type = :idtype,
    valid_id_number = :idnum,
    updated_at = NOW()";

// Add photo column if new file is uploaded
if ($photoName) {
  $sql .= ", photo_filename = :photo";
}

$sql .= " WHERE id = :id";

$stmt = $pdo->prepare($sql);

// Bind values
$params = [
  ':first_name' => clean($_POST['edit_first_name']),
  ':middle_name' => clean($_POST['edit_middle_name'] ?? ''),
  ':last_name' => clean($_POST['edit_last_name']),
  ':birthdate' => $_POST['birthdate'],
  ':birth_province' => $_POST['edit_birth_province'],
  ':birth_city' => $_POST['edit_birth_city'],
  ':birth_barangay' => $_POST['edit_birth_barangay'],
  ':gender' => $_POST['edit_gender'],
  ':civil_status' => $_POST['edit_civil_status'],
  ':phone' => clean($_POST['edit_phone']),
  ':email' => clean($_POST['edit_email']),
  ':alias' => clean($_POST['edit_alias'] ?? ''),
  ':occupation' => clean($_POST['edit_occupation']),
  ':present_province' => $_POST['edit_present_province'],
  ':present_city' => $_POST['edit_present_city'],
  ':present_barangay' => $_POST['edit_present_barangay'],
  ':present_zone' => $_POST['edit_present_zone'],
  ':present_street' => $_POST['edit_present_street'],
  ':present_landmark' => $_POST['edit_present_landmark'],
  ':permanent_province' => $_POST['edit_permanent_province'],
  ':permanent_city' => $_POST['edit_permanent_city'],
  ':permanent_barangay' => $_POST['edit_permanent_barangay'],
  ':permanent_zone' => $_POST['edit_permanent_zone'],
  ':permanent_street' => $_POST['edit_permanent_street'],
  ':permanent_landmark' => $_POST['edit_permanent_landmark'],
  ':alias_nickname' => $_POST['edit_alias_nickname'],
  ':alive' => isset($_POST['edit_alive']) ? '1' : '0',
  ':unemployed' => isset($_POST['edit_unemployed']) ? '1' : '0',
  ':idtype' => clean($_POST['edit_valid_id_type']),
  ':idnum' => clean($_POST['edit_valid_id_number']),
  ':id' => $id
];

// Add photo if present
if ($photoName) {
  $params[':photo'] = $photoName;
}

// Debugging output (optional)
// Uncomment the following lines to see the parameters and SQL query
// print_r($params);
// echo $sql;
// exit;

$stmt->execute($params);

header('Location: ../../public/index.php?page=residents&edit_success=1');
exit;
