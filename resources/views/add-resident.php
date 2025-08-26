<?php
  require_once '../src/helpers/utilities.php';
  requireRoles(['admin', 'secretary']);
?>
<main class="col-md-10 ms-sm-auto px-md-4 py-4">
  <div class="row mb-3">
    <h1 class="m-0">Add New Resident</h1>
    <hr>
  </div>

  <div class="container-fluid bg-light p-3">
    <form method="POST" class="needs-validation" novalidate action="<?= ACTIONS_URL ?>add-resident.php" enctype="multipart/form-data">
      <!-- Basic Information -->
      <div class="card mb-4">
        <div class="card-header fw-bold"><i class="add-resident-subheading-icon material-symbols-outlined md-18 text-dark">Info</i> Basic Information</div>
        <div class="card-body row g-3">
          <div class="col-md-4"><input type="text" name="first_name" class="form-control" placeholder="First name" required>
            <div class="invalid-feedback">First Name is required.</div>
          </div>
          <div class="col-md-4"><input type="text" name="middle_name" class="form-control" placeholder="Middle name"></div>
          <div class="col-md-4"><input type="text" name="last_name" class="form-control" placeholder="Last name" required>
            <div class="invalid-feedback">Last Name is required.</div>
          </div>

          <div class="col-md-4">
              <label for="birth_province" class="form-label">Place of Birth – Province</label>
              <select id="birth_province" name="birth_province" class="form-select" required>
                  <option>Select Province</option>
                  <?php
                  require '../config/database.php';
                  $stmt = $pdo->query("SELECT province_id, name FROM provinces ORDER BY name");
                  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                      echo "<option value=".strval($row['province_id']).">{$row['name']}</option>";
                  }
                  ?>
            </select>
            <div class="invalid-feedback">Place of Birth (Province) is required.</div>
          </div>
          <div class="col-md-4">
              <label for="birth_city" class="form-label">Place of Birth – City / Municipality</label>
              <select id="birth_city" name="birth_city" class="form-select" required>
                  <option>Select City</option>
              </select>
              <div class="invalid-feedback">Place of Birth (City or Municipality) is required.</div>
          </div>
          <div class="col-md-4">
              <label for="birth_barangay" class="form-label">Place of Birth – Barangay</label>
              <select id="birth_barangay" name="birth_barangay" class="form-select" required>
                  <option>Select City</option>
              </select>
          </div>
          <div class="col-md-2">
            <label class="form-label">Date of Birth</label>
            <input name="birthdate"  id="birthdate" type="date" class="form-control" required>
            <div class="invalid-feedback">Date of Birth is required.</div>
          </div>
          <div class="col-md-2">
            <label class="form-label">Age</label>
            <input type="text" id="age" name="age" class="form-control" placeholder="" readonly>
          </div>
          <div class="col-md-2">
            <label class="form-label">Gender</label>
            <select name="gender" class="form-select" required><option>Male</option><option>Female</option></select>
            <div class="invalid-feedback">Gender is required.</div>
          </div>
          <div class="col-md-2">
            <label class="form-label">Civil Status</label>
            <select class="form-select" name="civil_status" required><option>Single</option><option>Married</option><option>Widowed</option></select>
            <div class="invalid-feedback">Civil Status is required</div>
          </div>
          <div class="col-md-4">
            <label class="form-label">Phone Number</label>
            <input name="phone" type="text" class="form-control" placeholder="0900-000-0000" required>
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
            <select id="present_province" name="present_province" class="form-select" required>
              <option>Select Province</option>
              <?php
              require '../config/database.php';
              $stmt = $pdo->query("SELECT province_id, name FROM provinces ORDER BY name");
              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  echo "<option value=".strval($row['province_id']).">{$row['name']}</option>";
              }
              ?>
            </select>
            <div class="invalid-feedback">Present Address (Province) is required.</div>
          </div>
          <div class="col-md-4">
            <label class="form-label">City / Municipality</label>
            <select id="present_city" name="present_city" class="form-select" required>

            </select>
            <div class="invalid-feedback">Present Address (City / Municipality) is required.</div>
          </div>
          <div class="col-md-4">
            <label class="form-label">Barangay</label>
              <select id="present_barangay" name="present_barangay" class="form-select" required>

              </select>
              <div class="invalid-feedback">Present Address (Barangay) is required.</div>
          </div>
          <div class="col-md-3">
            <input type="text" id="present_zone" name="present_zone" class="form-control" placeholder="Zone (Purok)">
            <div class="invalid-feedback">Zone (Purok) is required.</div>
          </div>
          <div class="col-md-6">
            <input type="text" id="present_street" name="present_street" class="form-control" placeholder="Street">
          </div>
          <div class="col-md-3">
            <input type="text" id="present_landmark" name="present_landmark" class="form-control" placeholder="Landmark">
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
                <select id="permanent_province" name="permanent_province" class="form-select" required>
                    <option>Select Province</option>
                    <?php
                    require '../config/database.php';
                    $stmt = $pdo->query("SELECT province_id, name FROM provinces ORDER BY name");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value=".strval($row['province_id']).">{$row['name']}</option>";
                    }
                    ?>
                </select>
                <div class="invalid-feedback">Permanent Address (Province) is required.</div>
              </div>
              <div class="col-md-4">
                <label class="form-label">City / Municipality</label>
                <select id="permanent_city" name="permanent_city" class="form-select" required>

                </select>
                <div class="invalid-feedback">Permanent Address (City / Municipality) is required.</div>
              </div>
              <div class="col-md-4">
              <label class="form-label">Barangay</label>
                  <select id="permanent_barangay" name="permanent_barangay" class="form-select" required>

                  </select>
                  <div class="invalid-feedback">Permanent Address (Barangay) is required.</div>
              </div>
              <div class="col-md-3">
              <input type="text" id="permanent_zone" name="permanent_zone" class="form-control" placeholder="Zone (Purok)">
              </div>
              <div class="col-md-6">
              <input type="text" id="permanent_street" name="permanent_street" class="form-control" placeholder="Street">
              </div>
              <div class="col-md-3">
              <input type="text" id="permanent_landmark" name="permanent_landmark" class="form-control" placeholder="Landmark">
              </div>
          </div>
          </div>
      </div>


      <!-- Other Information -->
      <div class="card mb-4">
        <div class="card-header fw-bold"><i class="add-resident-subheading-icon material-symbols-outlined md-18 text-dark">data_info_alert</i> Other Information</div>
        <div class="card-body row g-3">
          <div class="col-md-4">
            <input type="text" name="occupation" class="form-control" placeholder="Occupation">
            <div class="form-check form-switch mt-2">
              <input name="unemployed" class="form-check-input" type="checkbox" id="unemployedSwitch">
              <label class="form-check-label" for="unemployedSwitch">Unemployed</label>
            </div>
          </div>

          <div class="col-md-4"><input type="email" name="email" class="form-control" placeholder="email@example.com" required>
              <div class="invalid-feedback">Email is required.</div>
          </div>
          <div class="col-md-4"><input type="text" name="alias" class="form-control" placeholder="Alias/Nickname"></div>

          <div class="col-md-3">
            <div class="form-check form-switch">
              <input name="status" class="form-check-input" type="checkbox" id="aliveSwitch" checked>
              <label class="form-check-label" for="aliveSwitch">Alive</label>
            </div>
          </div>
          <div class="col-md-5">
            <select name="valid_id_type" class="form-select">
              <option>Select a Valid ID</option>
              <option value="Philippine Passport">Philippine Passport</option>
              <option value="National ID (PhilSys ID/ePhilID)">National ID (PhilSys ID/ePhilID)</option>
              <option value="SSS ID/UMID Card">SSS ID/UMID Card</option>
              <option value="GSIS ID/UMID Card">GSIS ID/UMID Card</option>
              <option value="Driver's License">Driver's License</option>
              <option value="PRC ID">PRC ID</option>
              <option value="OWWA/iDOLE Card">OWWA/iDOLE Card</option>
              <option value="Voter's ID/Voter's Certification">Voter's ID/Voter's Certification</option>
              <option value="Firearms License (PNP)">Firearms License (PNP)</option>
              <option value="Senior Citizen ID">Senior Citizen ID</option>
              <option value="PWD ID">PWD ID</option>
              <option value="NBI Clearance">NBI Clearance</option>
              <option value="PhilHealth ID">PhilHealth ID</option>
              <option value="Postal ID">Postal ID</option>
              <option value="School ID">School ID</option>
              <option value="Company/Office ID">Company/Office ID</option>
              <option value="Barangay ID/Certification">Barangay ID/Certification</option>
              <option value="Police Clearance/Police Clearance Certificate">Police Clearance/Police Clearance Certificate</option>
              <option value="Seaman's Book/SIRB">Seaman's Book/SIRB</option>
              <option value="HDMF Transaction ID Card">HDMF Transaction ID Card</option>
              <option value="Solo Parent ID Card">Solo Parent ID Card</option>
              <option value="PhilSys ID">PhilSys ID</option>
              <option value="ePhilID">ePhilID</option>
              <option value="Professional Regulation Commission (PRC) card">Professional Regulation Commission (PRC) card</option>
              <option value="Seaman's Book (Seafarer's Identification and Record Book)">Seaman's Book (Seafarer's Identification and Record Book)</option>
              <option value="PhilSys ID/ePhilID">PhilSys ID/ePhilID</option>
              <option value="Philippine Health Insurance Corporation (PHIC) ID card/Member Data Record">Philippine Health Insurance Corporation (PHIC) ID card/Member Data Record</option>
              <option value="Seafarer's Registration Certificate issued by Philippine Overseas Employment Administration (POEA)">Seafarer's Registration Certificate issued by Philippine Overseas Employment Administration (POEA)</option>
              <option value="Transcript of Records">Transcript of Records</option>
              <option value="Student Permit issued by Land Transportation Office (LTO)">Student Permit issued by Land Transportation Office (LTO)</option>
            </select>
          </div>
          <div class="col-md-4">
            <input type="text" class="form-control" name="valid_id_number" placeholder="Valid ID No.">
          </div>
        </div>
      </div>

      <!-- Add Photo -->
      <div class="card mb-4">
        <div class="card-header fw-bold"><i class="add-resident-subheading-icon material-symbols-outlined md-18 text-dark">camera_video</i> Add Photo</div>
        <div class="card-body row g-3 align-items-center">
          <div class="mb-3">
              <video id="cameraPreview" autoplay playsinline width="300" height="225" class="border rounded d-none"></video>
              <canvas id="snapshotCanvas" width="300" height="225" class="d-none"></canvas>
              <div class="mt-2">
                  <button type="button" class="btn btn-primary" id="captureBtn">Capture Photo</button>
              </div>
              <input type="hidden" name="captured_photo" id="captured_photo">
          </div>

          <div class="col-md-6">
              <input type="file" name="photo" accept="image/*" class="d-none form-control">
              <small class="text-muted d-none">It is recommended when uploading a photo to select one with a 1x1 dimension</small>
          </div>
        </div>
      </div>

      <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" id="createUser">
        <label class="form-check-label" for="createUser">
          Create user account on submit
        </label>
      </div>

      <div class="d-flex justify-content-end gap-2">
        <a class="btn btn-secondary" href="?page=residents">Cancel</a>
        <button class="btn btn-primary">Save Resident Info</button>
      </div>
    </form>
  </div>
</main>
