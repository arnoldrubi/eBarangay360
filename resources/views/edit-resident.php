<?php
  require '../config/database.php';
  require_once '../src/helpers/utilities.php';
  requireRoles(['admin', 'secretary']);

  $resident_id = $_GET['resident_id'] ?? null;

  if ($resident_id) {
      // Fetch the resident
    $stmt = $pdo->prepare("SELECT * FROM residents WHERE id = ?");
    $stmt->execute([$resident_id]);
    $resident = $stmt->fetch(PDO::FETCH_ASSOC);
      // Assign to variables
    extract($resident); // This creates variables like $first_name, $last_name, etc.

  }

?>
<main class="col-md-10 ms-sm-auto px-md-4 py-4">
  <div class="row mb-3">
    <h1 class="m-0">Edit Resident</h1>
    <hr>
  </div>

  <div class="container-fluid bg-light p-3">
    <form method="POST" class="needs-validation" novalidate action="<?= ACTIONS_URL ?>update-resident.php" enctype="multipart/form-data">
      <!-- Basic Information -->
      <div class="card mb-4">
        <div class="card-header fw-bold"><i class="add-resident-subheading-icon material-symbols-outlined md-18 text-dark">Info</i> Basic Information</div>
        <div class="card-body row g-3">
        <input type="text" name="resident_id" class="form-control" value="<?= $id ?>" hidden>
          <div class="col-md-4"><input type="text" name="edit_first_name" class="form-control" placeholder="First name" value="<?= $first_name ?>" required>
            <div class="invalid-feedback">First Name is required.</div>
          </div>
          <div class="col-md-4"><input type="text" name="edit_middle_name" class="form-control" placeholder="Middle name" value="<?= $middle_name ?>"></div>
          <div class="col-md-4"><input type="text" name="edit_last_name" class="form-control" placeholder="Last name" value="<?= $last_name ?>" required>
            <div class="invalid-feedback">Last Name is required.</div>
          </div>

          <div class="col-md-4">
              <label for="edit_birth_province" class="form-label">Place of Birth – Province</label>
              <select id="edit_birth_province" name="edit_birth_province" class="form-select" required>
                  <option>Select Province</option>
                  <?php
                  require '../config/database.php';
                  $stmt = $pdo->query("SELECT province_id, name FROM provinces ORDER BY name");
                  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                      $selected = ($row['province_id'] == $place_of_birth_province) ? 'selected' : '';
                      echo "<option $selected value=".strval($row['province_id']).">{$row['name']}</option>";
                  }
                  ?>
            </select>
            <div class="invalid-feedback">Place of Birth (Province) is required.</div>
          </div>
          <div class="col-md-4">
              <label for="birth_city" class="form-label">Place of Birth – City / Municipality</label>
              <select id="edit_birth_city" name="edit_birth_city" class="form-select" required>
                  <option>Select City</option>
              </select>
              <div class="invalid-feedback">Place of Birth (City or Municipality) is required.</div>
          </div>
          <div class="col-md-4">
              <label for="edit_birth_barangay" class="form-label">Place of Birth – Barangay</label>
              <select id="edit_birth_barangay" name="edit_birth_barangay" class="form-select" required>
                  <option>Select City</option>
              </select>
          </div>
          <div class="col-md-2">
            <label class="form-label">Date of Birth</label>
            <input name="birthdate" value="<?= $date_of_birth ?>"  id="birthdate" type="date" class="form-control" required>
            <div class="invalid-feedback">Date of Birth is required.</div>
          </div>
          <div class="col-md-2">
            <label class="form-label">Age</label>
            <input type="text" value="<?php echo date_diff(date_create($date_of_birth), date_create('today'))->y; ?>" id="age" name="age" class="form-control" placeholder="" readonly>
          </div>
          <div class="col-md-2">
            <label class="form-label">Gender</label>
            <select name="edit_gender" class="form-select" required>
                <option <?= $gender === 'Male' ? 'selected' : '' ?>>Male</option>
                <option <?= $gender === 'Female' ? 'selected' : '' ?>>Female</option>
            </select>
            <div class="invalid-feedback">Gender is required.</div>
          </div>
          <div class="col-md-2">
            <label class="form-label">Civil Status</label>
            <select class="form-select" name="edit_civil_status" required>
              <option <?= $civil_status === 'Single' ? 'selected' : '' ?>>Single</option>
              <option <?= $civil_status === 'Married' ? 'selected' : '' ?>>Married</option>
              <option <?= $civil_status === 'Widowed' ? 'selected' : '' ?>>Widowed</option>
            </select>
            <div class="invalid-feedback">Civil Status is required</div>
          </div>
          <div class="col-md-4">
            <label class="form-label">Phone Number</label>
            <input name="edit_phone" value="<?= $phone_number ?>" type="text" class="form-control" placeholder="0900-000-0000" required>
            <div class="invalid-feedback">Phone Number is required.</div>
          </div>
        </div>
      </div>

      <!-- Present Address -->
      <div class="card mb-4">
        <div class="card-header fw-bold"><i class= "add-resident-subheading-icon material-symbols-outlined md-18 text-dark">add_location</i> Present Address</div>
        <div class="card-body row g-3">
          <div class="col-md-4">
            <label class="form-label">Province</label>
            <select id="edit_present_province" name="edit_present_province" class="form-select" required>
              <option>Select Province</option>
              <?php
              require '../config/database.php';
              $stmt = $pdo->query("SELECT province_id, name FROM provinces ORDER BY name");
              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  $selected = ($row['province_id'] == $present_province) ? 'selected' : '';
                  echo "<option $selected value=".strval($row['province_id']).">{$row['name']}</option>";
              }
              ?>
            </select>
            <div class="invalid-feedback">Present Address (Province) is required.</div>
          </div>
          <div class="col-md-4">
            <label class="form-label">City / Municipality</label>
            <select id="edit_present_city" name="edit_present_city" class="form-select" required>

            </select>
            <div class="invalid-feedback">Present Address (City / Municipality) is required.</div>
          </div>
          <div class="col-md-4">
            <label class="form-label">Barangay</label>
              <select id="edit_present_barangay" name="edit_present_barangay" class="form-select" required>

              </select>
              <div class="invalid-feedback">Present Address (Barangay) is required.</div>
          </div>
          <div class="col-md-3">
            <input type="text" id="edit_present_zone" value="<?= $present_zone ?>" name="edit_present_zone" class="form-control" placeholder="Zone (Purok)">
            <div class="invalid-feedback">Zone (Purok) is required.</div>
          </div>
          <div class="col-md-6">
            <input type="text" id="edit_present_street" value="<?= $present_street ?>" name="edit_present_street" class="form-control" placeholder="Street">
          </div>
          <div class="col-md-3">
            <input type="text" id="edit_present_landmark" value="<?= $present_landmark ?>" name="edit_present_landmark" class="form-control" placeholder="Landmark">
          </div>
          <div class="col-md-5">
              <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="0" id="sameAsPresent">
                  <label class="form-check-label" for="flexCheckDefault">
                      Same as Permanent Address
                  </label>
                  <p><small class="text-muted">If your present address is the same as your permanent address, check this box and we’ll copy it for you.</small></p>
              </div>
          </div>
        </div>
      </div>

      <!-- Permanent Address -->
      <div id="permanentAddressBlock">
          <div class="card mb-4">
          <div class="card-header fw-bold"><i class="add-resident-subheading-icon material-symbols-outlined md-18 text-dark">add_location_alt</i> Permanent Address</div>
          <div class="card-body row g-3">
              <div class="col-md-4">
                <label class="form-label">Province</label>
                <select id="edit_permanent_province" name="edit_permanent_province" class="form-select" required>
                    <option>Select Province</option>
                    <?php
                    require '../config/database.php';
                    $stmt = $pdo->query("SELECT province_id, name FROM provinces ORDER BY name");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $selected = ($row['province_id'] == $permanent_province) ? 'selected' : '';
                        echo "<option $selected value=".strval($row['province_id']).">{$row['name']}</option>";
                    }
                    ?>
                </select>
                <div class="invalid-feedback">Permanent Address (Province) is required.</div>
              </div>
              <div class="col-md-4">
                <label class="form-label">City / Municipality</label>
                <select id="edit_permanent_city" name="edit_permanent_city" class="form-select" required>

                </select>
                <div class="invalid-feedback">Permanent Address (City / Municipality) is required.</div>
              </div>
              <div class="col-md-4">
              <label class="form-label">Barangay</label>
                  <select id="edit_permanent_barangay" name="edit_permanent_barangay" class="form-select" required>

                  </select>
                  <div class="invalid-feedback">Permanent Address (Barangay) is required.</div>
              </div>
              <div class="col-md-3">
              <input type="text" id="edit_permanent_zone" value="<?= $permanent_zone ?>" name="edit_permanent_zone" class="form-control" placeholder="Zone (Purok)">
              </div>
              <div class="col-md-6">
              <input type="text" id="edit_permanent_street" value="<?= $permanent_street ?>" name="edit_permanent_street" class="form-control" placeholder="Street">
              </div>
              <div class="col-md-3">
              <input type="text" id="edit_permanent_landmark" value="<?= $permanent_landmark ?>" name="edit_permanent_landmark" class="form-control" placeholder="Landmark">
              </div>
          </div>
          </div>
      </div>


      <!-- Other Information -->
      <div class="card mb-4">
        <div class="card-header fw-bold"><i class="add-resident-subheading-icon material-symbols-outlined md-18 text-dark">data_info_alert</i> Other Information</div>
        <div class="card-body row g-3">
          <div class="col-md-4">
            <input type="text" value="<?= $occupation ?>" name="edit_occupation" class="form-control" placeholder="Occupation">
            <div class="form-check form-switch mt-2">
              <input name="unemployed" class="form-check-input" type="checkbox" id="unemployedSwitch" <?= $unemployed ? 'checked' : '' ?>>
              <label class="form-check-label" for="unemployedSwitch">Unemployed</label>
            </div>
          </div>

          <div class="col-md-4"><input type="email" value="<?= $email ?>" name="edit_email" class="form-control" placeholder="email@example.com" required>
              <div class="invalid-feedback">Email is required.</div>
          </div>
          <div class="col-md-4"><input type="text" value="<?= $alias_nickname ?>" name="edit_alias_nickname" class="form-control" placeholder="Alias/Nickname"></div>

          <div class="col-md-3">
            <div class="form-check form-switch">
              <input name="edit_alive" class="form-check-input" type="checkbox" id="aliveSwitch" checked>
              <label class="form-check-label" for="aliveSwitch">Alive</label>
            </div>
          </div>
          <div class="col-md-5">
            <select name="edit_valid_id_type" class="form-select">
              <option>Select a Valid ID</option>
              <option <?= $valid_id_type === "Philippine Passport" ? 'selected' : '' ?> value="Philippine Passport">Philippine Passport</option>
              <option <?= $valid_id_type === "National ID (PhilSys ID/ePhilID)" ? 'selected' : '' ?> value="National ID (PhilSys ID/ePhilID)">National ID (PhilSys ID/ePhilID)</option>
              <option <?= $valid_id_type === "SSS ID/UMID Card" ? 'selected' : '' ?> value="SSS ID/UMID Card">SSS ID/UMID Card</option>
              <option <?= $valid_id_type === "GSIS ID/UMID Card" ? 'selected' : '' ?> value="GSIS ID/UMID Card">GSIS ID/UMID Card</option>
              <option <?= $valid_id_type === "Driver's License" ? 'selected' : '' ?> value="Driver's License">Driver's License</option>
              <option <?= $valid_id_type === "PRC ID" ? 'selected' : '' ?> value="PRC ID">PRC ID</option>
              <option <?= $valid_id_type === "OWWA/iDOLE Card" ? 'selected' : '' ?> value="OWWA/iDOLE Card">OWWA/iDOLE Card</option>
              <option <?= $valid_id_type === "Voter's ID/Voter's Certification" ? 'selected' : '' ?> value="Voter's ID/Voter's Certification">Voter's ID/Voter's Certification</option>
              <option <?= $valid_id_type === "Firearms License (PNP)" ? 'selected' : '' ?> value="Firearms License (PNP)">Firearms License (PNP)</option>
              <option <?= $valid_id_type === "Senior Citizen ID" ? 'selected' : '' ?> value="Senior Citizen ID">Senior Citizen ID</option>
              <option <?= $valid_id_type === "PWD ID" ? 'selected' : '' ?> value="PWD ID">PWD ID</option>
              <option <?= $valid_id_type === "NBI Clearance" ? 'selected' : '' ?> value="NBI Clearance">NBI Clearance</option>
              <option <?= $valid_id_type === "PhilHealth ID" ? 'selected' : '' ?> value="PhilHealth ID">PhilHealth ID</option>
              <option <?= $valid_id_type === "Postal ID" ? 'selected' : '' ?> value="Postal ID">Postal ID</option>
              <option <?= $valid_id_type === "School ID" ? 'selected' : '' ?> value="School ID">School ID</option>
              <option <?= $valid_id_type === "Company/Office ID" ? 'selected' : '' ?> value="Company/Office ID">Company/Office ID</option>
              <option <?= $valid_id_type === "Barangay ID/Certification" ? 'selected' : '' ?> value="Barangay ID/Certification">Barangay ID/Certification</option>
              <option <?= $valid_id_type === "Police Clearance/Police Clearance Certificate" ? 'selected' : '' ?> value="Police Clearance/Police Clearance Certificate">Police Clearance/Police Clearance Certificate</option>
              <option <?= $valid_id_type === "Seaman's Book/SIRB" ? 'selected' : '' ?> value="Seaman's Book/SIRB">Seaman's Book/SIRB</option>
              <option <?= $valid_id_type === "HDMF Transaction ID Card" ? 'selected' : '' ?> value="HDMF Transaction ID Card">HDMF Transaction ID Card</option>
              <option <?= $valid_id_type === "Solo Parent ID Card" ? 'selected' : '' ?> value="Solo Parent ID Card">Solo Parent ID Card</option>
              <option <?= $valid_id_type === "PhilSys ID" ? 'selected' : '' ?> value="PhilSys ID">PhilSys ID</option>
              <option <?= $valid_id_type === "ePhilID" ? 'selected' : '' ?> value="ePhilID">ePhilID</option>
              <option <?= $valid_id_type === "Professional Regulation Commission (PRC) card" ? 'selected' : '' ?> value="Professional Regulation Commission (PRC) card">Professional Regulation Commission (PRC) card</option>
              <option <?= $valid_id_type === "Seaman's Book (Seafarer's Identification and Record Book)" ? 'selected' : '' ?> value="Seaman's Book (Seafarer's Identification and Record Book)">Seaman's Book (Seafarer's Identification and Record Book)</option>
              <option <?= $valid_id_type === "PhilSys ID/ePhilID" ? 'selected' : '' ?> value="PhilSys ID/ePhilID">PhilSys ID/ePhilID</option>
              <option <?= $valid_id_type === "Philippine Health Insurance Corporation (PHIC) ID card/Member Data Record" ? 'selected' : '' ?> value="Philippine Health Insurance Corporation (PHIC) ID card/Member Data Record">Philippine Health Insurance Corporation (PHIC) ID card/Member Data Record</option>
              <option <?= $valid_id_type === "Seafarer's Registration Certificate issued by Philippine Overseas Employment Administration (POEA)" ? 'selected' : '' ?> value="Seafarer's Registration Certificate issued by Philippine Overseas Employment Administration (POEA)">Seafarer's Registration Certificate issued by Philippine Overseas Employment Administration (POEA)</option>
              <option <?= $valid_id_type === "Transcript of Records" ? 'selected' : '' ?> value="Transcript of Records">Transcript of Records</option>
              <option <?= $valid_id_type === "Student Permit issued by Land Transportation Office (LTO)" ? 'selected' : '' ?> value="Student Permit issued by Land Transportation Office (LTO)">Student Permit issued by Land Transportation Office (LTO)</option>
            </select>
          </div>
          <div class="col-md-4">
            <input type="text" class="form-control" value="<?= $resident['valid_id_number'] ?>" name="edit_valid_id_number" placeholder="Valid ID No.">
          </div>
        </div>
      </div>

      <!-- Change Photo -->
      <div class="card mb-4">
        <div class="card-header fw-bold"><i class="add-resident-subheading-icon material-symbols-outlined md-18 text-dark">camera_video</i> Change Photo</div>
        <div class="card-body row mb-3 g-3 align-items-center">
          <div class="col-md-6">
              <video id="cameraPreview" autoplay playsinline width="300" height="225" class="mt-3 border rounded d-none"></video>
              <canvas id="snapshotCanvas" width="300" height="225" class="img-thumbnail d-none"></canvas>
              <div class="mt-2">
                  <button type="button" class="btn btn-primary" id="captureBtn">Capture Photo</button>
              </div>
              <input type="hidden" name="captured_photo" id="captured_photo">
          </div>
          <div class="col-md-6">
            <input type="file" name="photo" accept="image/*" class="d-none form-control">
            <div>
              <p><img style="max-width: 350px" src="uploads/residents/<?= $resident['photo_filename'] ?>" id="resident-picture" class="img-thumbnail" alt="Profile"></p>
            </div>
            <small class="text-muted">Current Photo</small>
          </div>
        </div>
      </div>

      <div class="d-flex justify-content-end gap-2">
        <a class="btn btn-secondary" href="?page=residents">Cancel</a>
        <button class="btn btn-primary">Update Resident Info</button>
      </div>
    </form>
  </div>
</main>


<script>
        // for populating address chains on the individual edit resident page
    const birthProvince = document.getElementById('edit_birth_province');
    const birthCity = document.getElementById('edit_birth_city');
    const birthBarangay = document.getElementById('edit_birth_barangay');

    const presentProvince = document.getElementById('edit_present_province');
    const presentCity = document.getElementById('edit_present_city');
    const presentBarangay = document.getElementById('edit_present_barangay');

    const permanentProvince = document.getElementById('edit_permanent_province');
    const permanentCity = document.getElementById('edit_permanent_city');
    const permanentBarangay = document.getElementById('edit_permanent_barangay');

    setupForEditAddressChain(<?= $place_of_birth_province ?>, <?= $place_of_birth_city_municipality ?>, <?= $place_of_birth_barangay ?>, birthProvince, birthCity, birthBarangay);
    setupForEditAddressChain(<?= $present_province ?>, <?= $present_city_municipality ?>, <?= $present_barangay ?>, presentProvince, presentCity, presentBarangay);
    setupForEditAddressChain(<?= $permanent_province ?>, <?= $permanent_city_municipality ?>, <?= $permanent_barangay ?>, permanentProvince, permanentCity, permanentBarangay);


</script>