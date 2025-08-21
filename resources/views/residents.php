<?php
  require '../config/database.php';

  // Total Senior Citizens
  $senior = $pdo->query("SELECT COUNT(*) AS total FROM residents WHERE TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) >= 60")->fetch()['total'];

  // Total Minors
  $minors = $pdo->query("SELECT COUNT(*) AS total FROM residents WHERE TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) < 18")->fetch()['total'];

  // Total Male
  $male = $pdo->query("SELECT COUNT(*) AS total FROM residents WHERE gender = 'Male'")->fetch()['total'];

  // Total Female
  $female = $pdo->query("SELECT COUNT(*) AS total FROM residents WHERE gender = 'Female'")->fetch()['total'];
?>

<main id="residents-dashboard" class="col-md-10 ms-sm-auto px-md-4 py-4">
  <div class="row mb-3">
    <h1 class="m-0">Resident Information</h1>
    <hr>
  </div>

  <!-- Demographics Summary -->
  <div class="row mb-4">
    <div class="col-md-3">
      <div class="card text-center">
        <div id="card-senior" class="card-body residents-dashboard-card">
          <div class="icon p-3 icon-shape text-warning-emphasis rounded-circle shadow-sm">
            <i class="material-symbols-outlined md-36">elderly</i>
          </div>
          <h6 class="mb-0">Total Senior Citizens</h6>
          <h4 class="mt-0"><?= $senior ?></h4>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-center">
        <div class="card-body residents-dashboard-card">
          <div id="card-minors" class="icon p-3 icon-shape bg-warning-subtle text-warning-emphasis rounded-circle shadow-sm">
            <i class="material-symbols-outlined md-36">child_care</i>
          </div>
          <h6 class="mb-0">Total Minors (<18)</h6>
          <h4 class="mt-0"><?= $minors ?></h4>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-center">
        <div id="card-male" class="card-body residents-dashboard-card">
          <div id="card-male" class="icon p-3 icon-shape bg-success-subtle text-success-emphasis rounded-circle shadow-sm">
            <i class="material-symbols-outlined md-36">male</i>
          </div>          
          <h6 class="mb-0">Total Male</h6>
          <h4 class="mt-0"><?= $male ?></h4>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-center">
        <div id="card-female" class="card-body residents-dashboard-card">
          <div id="card-female" class="icon p-3 icon-shape bg-danger-subtle text-danger-emphasis rounded-circle shadow-sm">
            <i class="material-symbols-outlined md-36">female</i>
          </div>
          <h6 class="mb-0">Total Female</h6>
          <h4 class="mt-0"><?= $female ?></h4>
        </div>
      </div>
    </div>
  </div>

  <section class="inner-content">
    <div class="container-fluid p-3">
      <h3 class="mb-4">Residents List</h3>
      <p class="text-muted mb-3">Here you can view and manage the list of residents in your barangay.</p>
  <!-- Add Resident Button -->
  <div class="row mb-3">
    <div class="col-md-7 gap-2 mb-3 d-flex align-items-center">
      <div class="" style="">
          <select class="form-select" id="age-filter" aria-label="Filter by age">
              <option value="" selected="">Filter by Age</option>
              <option value="seniors">Senior</option>
              <option value="minors">Minor</option>
              <option value="adults">19 above and below 60</option>
          </select>
      </div>
      <div class="" style="">
        <select class="form-select" id="gender-filter" aria-label="Filter by gender">
            <option value="" selected="">Filter by Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
      </div>
    </div>
    <div class="col-md-5 text-end">
      <a href="?page=add-resident" class="btn btn-primary dashboard-btn-function"><i class="material-symbols-outlined md-24 text-light">note_add</i>Add Resident</a>
      <a href="<?= ACTIONS_URL ?>export-residents.php" class="btn btn-success dashboard-btn-function"><i class="material-symbols-outlined md-24 text-light">file_download</i>Export Residents</a>
    </div>
  </div>

  <!-- Table -->
    <div class="table-responsive">
      <table id="residents-table" class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>First Name</th>
            <th>Middle Name</th>
            <th>Last Name</th>
            <th>Zone (Purok)</th>
            <th>Street</th>
            <th>Gender</th>
            <th>Age</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          
          <?php

            $stmt = $pdo->query("SELECT id, first_name, last_name, middle_name, date_of_birth, present_zone, present_street, gender, alive FROM residents WHERE is_deleted = 0");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              $current_age = date_diff(date_create($row['date_of_birth']), date_create('today'))->y;
            ?>
            <tr>
              <td><?php echo $row['id']; ?></td>
              <td><?php echo $row['first_name']; ?></td>
              <td><?php echo $row['last_name']; ?></td>
              <td><?php echo $row['middle_name']; ?></td>
              <td><?php echo $row['present_zone']; ?></td>
              <td><?php echo $row['present_street']; ?></td>
              <td><?php echo $row['gender']; ?></td>
              <td><?php echo $current_age; ?></td>
              <td><?php echo $row['alive'] == 1 ? 'Alive': 'Deceased'; ?></td>
              <td>
                <a data-id="<?= $row['id'] ?>" data-bs-toggle="modal" data-bs-target="#editResidentModal" href="#" class="btn btn-sm btn-warning text-white edit-btn" title="Edit"><i class="material-symbols-outlined md-18">edit</i></a>
                <a data-id="<?= $row['id'] ?>" class="btn btn-sm btn-danger delete-btn"><i class="material-symbols-outlined md-18">delete</i></a>
              </td>
            </tr>
            <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
  </section>


  <!-- Success Modal -->
<div class="modal fade" id="submissionModal" tabindex="-1" aria-labelledby="submissionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="submissionModalLabel">Success!</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div id="message" class="modal-body">
        The resident has been added successfully.
      </div>
    </div>
  </div>
</div>

<!-- Edit Resident Modal -->
<div class="modal fade" id="editResidentModal" tabindex="-1" aria-labelledby="editResidentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <form id="editResidentForm" enctype="multipart/form-data" method="POST" class="needs-validation" novalidate action="<?= ACTIONS_URL ?>update-resident.php">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title text-light" id="editResidentModalLabel"><i class="material-symbols-outlined md-24">edit_attributes</i> Edit Resident</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <!-- Form fields (same as your add-resident form) -->
          <input type="hidden" name="resident_id" id="edit_id">
          <div class="row">
            <div class="card-mb-4 d-flex align-items-center justify-content-center">
              <p><img style="max-width: 350px" id="resident-picture" class="img-thumbnail" alt="Profile"></p>
            </div>
          </div>
          <!-- Basic Information -->
          <div class="card mb-4">
            <input type="text" id="edit_id" name="id" class="form-control d-none" placeholder="id" required readonly>
            <div class="card-header fw-bold"><i class="add-resident-subheading-icon material-symbols-outlined md-18 text-dark">Info</i> Basic Information</div>
            <div class="card-body row g-3">
              <div class="col-md-4"><input type="text" id="edit_first_name" name="edit_first_name" class="form-control" placeholder="First name" required>
                <div class="invalid-feedback">First Name is required.</div>
              </div>
              <div class="col-md-4"><input type="text" id="edit_middle_name" name="edit_middle_name" class="form-control" placeholder="Middle name"></div>
              <div class="col-md-4"><input type="text" id="edit_last_name" name="edit_last_name" class="form-control" placeholder="Last name" required>
                <div class="invalid-feedback">Last Name is required.</div>
              </div>

              <div class="col-md-4">
                  <label for="birth_province" class="form-label">Place of Birth – Province</label>
                  <select id="edit_birth_province" name="edit_birth_province" class="form-select" required>
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
                  <select id="edit_birth_city" id="edit_birth_city" name="edit_birth_city" class="form-select" required>
                      <option>Select City</option>
                  </select>
                  <div class="invalid-feedback">Place of Birth (City or Municipality) is required.</div>
              </div>
              <div class="col-md-4">
                  <label for="birth_barangay" class="form-label">Place of Birth – Barangay</label>
                  <select id="edit_birth_barangay" id="edit_birth_barangay" name="edit_birth_barangay" class="form-select" required>
                      <option>Select City</option>
                  </select>
              </div>
              <div class="col-md-2">
                <label class="form-label">Date of Birth</label>
                <input name="edit_birthdate"  id="edit_birthdate" type="date" class="form-control" required>
                <div class="invalid-feedback">Date of Birth is required.</div>
              </div>
              <div class="col-md-2">
                <label class="form-label">Age</label>
                <input type="text" id="edit_age" name="age" class="form-control" placeholder="" readonly>
              </div>
              <div class="col-md-2">
                <label class="form-label">Gender</label>
                <select name="edit_gender" id="edit_gender" class="form-select" required><option>Male</option><option>Female</option></select>
                <div class="invalid-feedback">Gender is required.</div>
              </div>
              <div class="col-md-2">
                <label class="form-label">Civil Status</label>
                <select class="form-select" id="edit_civil_status" name="edit_civil_status" required><option>Single</option><option>Married</option><option>Widowed</option></select>
                <div class="invalid-feedback">Civil Status is required</div>
              </div>
              <div class="col-md-4">
                <label class="form-label">Phone Number</label>
                <input name="edit_phone_number" id="edit_phone_number" type="text" class="form-control" placeholder="0900-000-0000" required>
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
                      echo "<option value=".strval($row['province_id']).">{$row['name']}</option>";
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
                <input type="text" id="edit_present_zone" name="edit_present_zone" class="form-control" placeholder="Zone (Purok)">
              </div>
              <div class="col-md-6">
                <input type="text" id="edit_present_street" name="edit_present_street" class="form-control" placeholder="Street">
              </div>
              <div class="col-md-3">
                <input type="text" id="edit_present_landmark" name="edit_present_landmark" class="form-control" placeholder="Landmark">
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
                            echo "<option value=".strval($row['province_id']).">{$row['name']}</option>";
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
                  <input type="text" id="edit_permanent_zone" name="edit_permanent_zone" class="form-control" placeholder="Zone (Purok)">
                  </div>
                  <div class="col-md-6">
                  <input type="text" id="edit_permanent_street" name="edit_permanent_street" class="form-control" placeholder="Street">
                  </div>
                  <div class="col-md-3">
                  <input type="text" id="edit_permanent_landmark" name="edit_permanent_landmark" class="form-control" placeholder="Landmark">
                  </div>
              </div>
              </div>
          </div>


          <!-- Other Information -->
          <div class="card mb-4">
            <div class="card-header fw-bold"><i class="add-resident-subheading-icon material-symbols-outlined md-18 text-dark">data_info_alert</i> Other Information</div>
            <div class="card-body row g-3">
              <div class="col-md-4">
                <input type="text" name="edit_occupation" id="edit_occupation" class="form-control" placeholder="Occupation">
                  <div class="form-check form-switch">
                  <input name="edit_unemployed" class="form-check-input" type="checkbox" id="edit_unemployed">
                  <label class="form-check-label" for="unemployedSwitch">Unemployed</label>
                </div>
              </div>
              <div class="col-md-4"><input type="email" id="edit_email" name="edit_email" class="form-control" placeholder="email@example.com" required>
                  <div class="invalid-feedback">Email is required.</div>
              </div>
              <div class="col-md-4"><input type="text" id="edit_alias_nickname" name="edit_alias_nickname" class="form-control" placeholder="Alias/Nickname"></div>

              <div class="col-md-3">
                <div class="form-check form-switch">
                  <input name="edit_status" class="form-check-input" type="checkbox" id="edit_aliveSwitch">
                  <label class="form-check-label" for="aliveSwitch">Alive</label>
                </div>
              </div>
              <div class="col-md-5">
                <select id="edit_valid_id_type" name="edit_valid_id_type" class="form-select">
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
                <input type="text" class="form-control" id="edit_valid_id_number" name="edit_valid_id_number" placeholder="Valid ID No.">
              </div>
            </div>
          </div>

          <!-- Add Photo -->
          <div class="card mb-4">
            <div class="card-header fw-bold"><i class="add-resident-subheading-icon material-symbols-outlined md-18 text-dark">camera_video</i> Change Photo</div>
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
                  <input type="file" name="photo" accept="image/*" class="form-control">
                  <small class="text-muted">It is recommended when uploading a photo to select one with a 1x1 dimension</small>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save Changes</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>


<?php if (isset($_GET['success'])): ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {

    const messageDiv = document.getElementById('message');

    // 2. Set the content (you can use Bootstrap alert styles if you like)
    messageDiv.innerHTML = `
      <div class="alert alert-success" role="alert">
        New Resident is added successfully!
      </div>`;

    const modal = new bootstrap.Modal(document.getElementById('submissionModal'));
    modal.show();
  });
</script>
<?php endif; ?>

<?php if (isset($_GET['edit_success'])): ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {

    const messageDiv = document.getElementById('message');

    // 2. Set the content (you can use Bootstrap alert styles if you like)
    messageDiv.innerHTML = `
      <div class="alert alert-success" role="alert">
        Resident information updated successfully!
      </div>`;
    const modal = new bootstrap.Modal(document.getElementById('submissionModal'));
    modal.show();
  });
</script>
<?php endif; ?>

<?php if (isset($_GET['delete_success'])): ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {

    const messageDiv = document.getElementById('message');

    // 2. Set the content (you can use Bootstrap alert styles if you like)
    messageDiv.innerHTML = `
      <div class="alert alert-success" role="alert">
        Resident information deleted!
      </div>`;
    const modal = new bootstrap.Modal(document.getElementById('submissionModal'));
    modal.show();
  });
</script>
<?php endif; ?>