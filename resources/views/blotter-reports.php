<?php
  require '../config/database.php';

  //Total pending reports
  $stmt = $pdo->query("SELECT COUNT(*) FROM blotter_reports WHERE status = 'Pending' AND is_deleted = 0");
  $totalPending = $stmt->fetchColumn();
  //Total ongoing reports
  $stmt = $pdo->query("SELECT COUNT(*) FROM blotter_reports WHERE status = 'Under Investigation' AND is_deleted = 0");
  $totalUnderInvestigation = $stmt->fetchColumn();
  //Total resolved reports
  $stmt = $pdo->query("SELECT COUNT(*) FROM blotter_reports WHERE status = 'Resolved' AND is_deleted = 0");
  $totalResolved = $stmt->fetchColumn();
  //Total for mediation reports
  $stmt = $pdo->query("SELECT COUNT(*) FROM blotter_reports WHERE status = 'For Mediation' AND is_deleted = 0");
  $totalForMediation = $stmt->fetchColumn();

?>

<main class="col-md-10 ms-sm-auto px-md-4 py-4">
  <div class="row">
    <div class="col-12">
        <div id="group-cards-blotter" class="row g-3 mb-3">
            <div class="row mb-3">
              <h1 class="m-0">Blotter / Incidents Management</h1>
              <hr>
            </div>
            <div class="col-lg-3 col-3">
                <div class="card h-100 rounded-0 border-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <p class="card-text">
                                    <strong>Investigating</strong>
                                </p>
                                <h3 class="card-title"><?= $totalUnderInvestigation ?></h3>
                            </div>
                            <div class="col-auto">
                                <div class="icon p-3 icon-shape bg-warning-subtle text-warning-emphasis rounded-circle shadow-sm">
                                    <i class="material-symbols-outlined md-24 text-dark">mystery</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-3">
                <div class="card h-100 rounded-0 border-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <p class="card-text">
                                    <strong>Resolved</strong>
                                </p>
                                <h3 class="card-title"><?= $totalResolved ?></h3>
                            </div>
                            <div class="col-auto">
                                <div class="icon p-3 icon-shape bg-success-subtle text-success-emphasis rounded-circle shadow-sm">
                                    <i class="material-symbols-outlined md-24 text-dark">data_check</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-3">
                <div class="card h-100 rounded-0 border-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <p class="card-text">
                                    <strong>Mediating</strong>
                                </p>
                                <h3 class="card-title"><?= $totalForMediation ?></h3>
                            </div>
                            <div class="col-auto">
                                <div class="icon p-3 icon-shape bg-danger-subtle text-danger-emphasis rounded-circle shadow-sm">
                                    <i class="material-symbols-outlined md-24 text-dark">communication</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-3">
                <div class="card h-100 rounded-0 border-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <p class="card-text">
                                    <strong>Pending</strong>
                                </p>
                                <h3 class="card-title"><?= $totalPending ?></h3>
                            </div>
                            <div class="col-auto">
                                <div class="icon p-3 icon-shape bg-primary-subtle text-primary-emphasis rounded-circle shadow-sm">
                                    <i class="material-symbols-outlined md-24 text-dark">pending_actions</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>

  <section class="inner-content">
    <div class="container-fluid p-3">
      <div class="row">
        <div class="col-12">
          <div class="row">
            <div class="gap-2 col-12 mb-3 text-end">
              <a href="?page=add-new-blotter-report" class="btn btn-primary fw-semibold btn-with-icon">
                <i class="material-symbols-outlined md-24 text-light">note_add</i>File New Blotter / Report </a>
              <button type="button" class="btn btn-success btn-with-icon fw-semibold" id="export-blotter-data">
                <i class="material-symbols-outlined md-24 text-light">file_download</i>Export Data to CSV </button>
            </div>
          </div>
          <div class="row">
            <div class="gap-2 col-12-lg mb-3 small">
              <div id="blotters-table_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                <div class="row">
                  <div class="col-sm-12 col-md-6">
                    <div class="dataTables_length" id="blotters-table_length">
                      <label>Show <select name="blotters-table_length" aria-controls="blotters-table" class="form-select form-select-sm">
                          <option value="10">10</option>
                          <option value="25">25</option>
                          <option value="50">50</option>
                          <option value="100">100</option>
                        </select> entries </label>
                    </div>
                  </div>
                  <div class="col-sm-12 col-md-6">
                    <div id="blotters-table_filter" class="dataTables_filter">
                      <label>Search: <input type="search" class="form-control form-control-sm" placeholder="" aria-controls="blotters-table">
                      </label>
                    </div>
                  </div>
                </div>
                <div class="row dt-row">
                  <div class="col-sm-12">
                    <div class="dataTables_scroll">
                      <div class="dataTables_scrollHead" style="overflow: hidden; position: relative; border: 0px; width: 100%;">
                        <div class="dataTables_scrollHeadInner" style="box-sizing: content-box; width: 923.75px; padding-right: 17px;">

                        </div>
                      </div>
                      <div class="table-responsive">
                        <table id="blotters-table" class="mt-2 table table-bordered align-middle">
                          <thead class="table-light">
                            <tr>
                              <th>Date Filed</th>
                              <th>Complainant</th>
                              <th>Suspect</th>
                              <th>Incident</th>
                              <th>Status</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody style="min-height: 100px;">
                            <?php

                              // Fetch blotter reports from the database
                              $countforrows = 0;

                              // Choices for status dropdown
                              $statusChoices = [
                                  'Under Investigation' => 'Under Investigation',
                                  'Resolved' => 'Resolved',
                                  'Pending' => 'Pending',
                                  'For Mediation' => 'For Mediation'
                              ];

                              $stmt = $pdo->query("SELECT * FROM blotter_reports WHERE is_deleted = 0 ORDER BY created_at DESC");
                              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                                $countforrows++;

                            ?>

                            <tr class="<?php echo $countforrows % 2 == 0 ? 'even' : 'odd'; ?>">
                              <td class="border-end sorting_1">
                                <span class="d-none"><?= $row['created_at'] ?></span>
                                <span class="text-nowrap text-muted pe-2"><?= $row['created_at'] ?></span>
                              </td>
                              <td>
                                <span class="text-nowrap"><?= $row['complainant_first_name'] .' ' .$row['complainant_middle_name']. ' '.$row['complainant_last_name']  ?></span>
                              </td>
                              <td>
                                <span class="text-nowrap"><?= $row['suspect_first_name'] .' ' .$row['suspect_middle_name']. ' '.$row['suspect_last_name']  ?></span>
                              </td>
                              <td style="max-width: 200px;" class="text-truncate"><?= $row['incident_description'] ?></td>
                              <td>
                                <div class="dropdown">
                                  <button type="button" class="btn btn-sm text-warning-emphasis bg-warning-subtle text-nowrap dropdown-toggle text-start w-100 d-flex justify-content-between align-items-center" data-bs-toggle="dropdown" aria-expanded="false"><?= $row['status'] ?></button>
                                  <ul class="dropdown-menu" data-blotter-id="<?= $row['id'] ?>">
                                    <?php foreach ($statusChoices as $status): ?>
                                      <li>
                                        <button class="dropdown-item small status-update <?php echo $row['status'] === $status ? 'active' : ''; ?>" type="button" data-status="<?= $status ?>"><?= $status ?></button>
                                      </li>
                                    <?php endforeach; ?>
                                  </ul>
                                </div>
                              </td>
                              <td class="text-center">
                                <div class="d-flex gap-2">
                                  <a data-id="<?= $row['id'] ?>" href="?page=edit-blotter-report&blotter_id=<?= $row['id'] ?>" class="btn btn-sm btn-warning text-white edit-btn" title="Edit"><i class="material-symbols-outlined md-18">edit</i></a>
                                  <a data-id="<?= $row['id'] ?>" class="btn btn-sm btn-danger blotter-delete-btn"><i class="material-symbols-outlined md-18">delete</i></a>
                                </div>
                              </td>
                            </tr>

                            <?php } ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12 col-md-5">
                    <div class="dataTables_info" id="blotters-table_info" role="status" aria-live="polite">Showing 1 to 8 of 8 entries</div>
                  </div>
                  <div class="col-sm-12 col-md-7">
                    <div class="dataTables_paginate paging_simple_numbers" id="blotters-table_paginate">
                      <ul class="pagination">
                        <li class="paginate_button page-item previous disabled" id="blotters-table_previous">
                          <a aria-controls="blotters-table" aria-disabled="true" aria-role="link" data-dt-idx="previous" tabindex="0" class="page-link">Previous</a>
                        </li>
                        <li class="paginate_button page-item active">
                          <a href="#" aria-controls="blotters-table" aria-role="link" aria-current="page" data-dt-idx="0" tabindex="0" class="page-link">1</a>
                        </li>
                        <li class="paginate_button page-item next disabled" id="blotters-table_next">
                          <a aria-controls="blotters-table" aria-disabled="true" aria-role="link" data-dt-idx="next" tabindex="0" class="page-link">Next</a>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
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
        The Blotter Report has been added successfully.
      </div>
    </div>
  </div>
</div>

<!-- Edit Resident Modal -->
<div class="modal fade" id="editResidentModal" tabindex="-1" aria-labelledby="editResidentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="editResidentForm" enctype="multipart/form-data" method="POST" class="needs-validation" novalidate action="<?= ACTIONS_URL ?>update-resident.php">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="editResidentModalLabel">Edit Resident</h5>
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
            <div class="card-header fw-bold">Basic Information</div>
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
            <div class="card-header fw-bold">Present Address</div>
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
              <div class="card-header fw-bold">Permanent Address</div>
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
            <div class="card-header fw-bold">Other Information</div>
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
            <div class="card-header fw-bold">Change Photo</div>
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
          <button type="submit" class="btn btn-success">Save Changes</button>
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
        New Blotter / Report is added successfully!
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
        Blotter information updated successfully!
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