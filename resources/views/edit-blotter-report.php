<?php
  require '../config/database.php';

  $blotter_id = $_GET['blotter_id'] ?? null;

  if (!$blotter_id) {
      die("Missing blotter ID.");
  }

  // Fetch the blotter record
  $stmt = $pdo->prepare("SELECT * FROM blotter_reports WHERE id = ?");
  $stmt->execute([$blotter_id]);
  $blotter = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$blotter) {
      die("Blotter record not found.");
  }

  // Assign to variables
  extract($blotter); // This creates variables like $complainant_first_name, $suspect_city, etc.

  // get blotter evidence from the database
  $stmt = $pdo->prepare("SELECT * FROM blotter_evidence_files WHERE blotter_id = ?");
  $stmt->execute([$blotter_id]);
  $evidence = $stmt->fetchAll(PDO::FETCH_ASSOC);

  require_once '../src/helpers/utilities.php';
  requireRoles(['admin', 'secretary']);

?>

<main class="col-md-10 ms-sm-auto px-md-4 py-4">

<div class="px-3 py-5">
  <div class="row mb-3">
    <h2 class="m-0">Edit Blotter / Incident <?= htmlspecialchars($blotter_code) ?></h2>
    <hr>
  </div>
  <section class="inner-content">
    <div class="container-fluid p-3">
        <form method="POST" class="needs-validation" id="blotter-form" class="px-3 py-4" novalidate action="<?= ACTIONS_URL ?>edit-blotter-report.php" enctype="multipart/form-data">
        <input type="hidden" name="blotter_id" value="<?= htmlspecialchars($blotter_id) ?>">
         <input type="hidden" name="blotter_code" value="<?= htmlspecialchars($blotter_code) ?>">
        <div class="tab position-relative active-tab" data-tab-index="0">
          <div class="row mb-3">
            <label class="form-label"><i class="material-symbols-outlined md-24 text-dark">person</i> Complainant Information</label>
            <input type="hidden" name="complainant_resident_id" id="complainant_resident_id" value="<?= htmlspecialchars($complainant_resident_id) ?>"> 
            <hr>
          </div>
          <div class="d-flex justify-content-end g-3 mb-3 align-items-end">
            <input type="number" class="d-none" id="complainant-id" name="complainant_id" value="0">
            <button type="button" class="btn btn-sm btn-outline-dark" data-bs-toggle="modal"  data-bs-target="#resident-search-modal">
              <i class="material-symbols-outlined md-18 text-secondary">person_search</i> Search resident data </button>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-4">
              <input type="text" value="<?= htmlspecialchars($complainant_first_name) ?>" required class="form-control" placeholder="First name" aria-label="First name" id="complainant_first_name" name="complainant_first_name">
            </div>
            <div class="col-md-4">
              <input type="text" value="<?= htmlspecialchars($complainant_last_name) ?>" required class="form-control" placeholder="Last name" aria-label="Last name" id="complainant_last_name" name="complainant_last_name">
            </div>
            <div class="col-md-4">
              <input type="text" value="<?= htmlspecialchars($complainant_middle_name) ?>" class="form-control" placeholder="Middle name" aria-label="Middle name" id="complainant_middle_name" name="complainant_middle_name">
            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-lg-2">
              <label for="date-of-birth" class="form-label">Date of Birth</label>
              <input name="complainant_dob" value="<?= htmlspecialchars($complainant_dob) ?>" id="complainant_dob" type="date" class="form-control" placeholder="" aria-label="date of birth">
            </div>
            <div class="col-lg-1">
              <label for="age" class="form-label">Age</label>
              <input name="complainant_age" value="<?= (new DateTime())->diff(new DateTime($complainant_dob))->y ?>" id="complainant_age" maxlength="1" type="number" class="form-control" placeholder="" aria-label="age">
            </div>
            <div class="col-lg-1">
              <label for="gender" class="form-label">Gender</label>
              <select name="complainant_gender" value="<?= htmlspecialchars($complainant_gender) ?>" id="complainant_gender" class="form-control" placeholder="" aria-label="gender">
                <option value="Male">Male</option>
                <option value="Female">Female</option>
              </select>
            </div>
            <div class="col-lg-2">
              <label for="civil-status" class="form-label">Civil Status</label>
              <!-- Here -->
              <select name="complainant_civil_status" value="<?= htmlspecialchars($complainant_civil_status) ?>" id="complainant_civil_status" class="form-control" placeholder="" aria-label="civil-status">
                <option value="Single" <?= $complainant_civil_status === "Single" ? "selected" : "" ?>>Single</option>
                <option value="Married" <?= $complainant_civil_status === "Married" ? "selected" : "" ?>>Married</option>
                <option value="Separated" <?= $complainant_civil_status === "Separated" ? "selected" : "" ?>>Separated</option>
                <option value="Widow" <?= $complainant_civil_status === "Widow" ? "selected" : "" ?>>Widow</option>
                <option value="Divorced" <?= $complainant_civil_status === "Divorced" ? "selected" : "" ?>>Divorced</option>
              </select>
            </div>
            <div class="col-lg-2">
              <label for="phone-number" class="form-label">Phone Number</label>
              <input type="text" required id="complainant_phone" name="complainant_phone" value="<?= htmlspecialchars($complainant_phone) ?>" class="form-control" placeholder="0900-000-0000" aria-label="Phone Number">
            </div>
            <div class="col-lg-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" name="complainant_email" value="<?= htmlspecialchars($complainant_email) ?>" id="complainant_email" class="form-control" placeholder="email@example.com" aria-label="email">
            </div>
          </div>
          <div class="row mb-3 mt-5">
            <label class="form-label"><i class="material-symbols-outlined md-24 text-dark">person_pin_circle</i> Address</label>
            <hr>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-4">
              <label for="complainant-province" class="form-label">Province</label>
              <select name="complainant_province" id="complainant-province" class="form-select pointer select2-hidden-accessible" data-select2-id="select2-data-complainant-province" tabindex="-1" aria-hidden="true">
                <option>Select Province</option>
                  <?php
                  require '../config/database.php';
                  $stmt = $pdo->query("SELECT province_id, name FROM provinces ORDER BY name");
                  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                      echo $complainant_province === $row['province_id'] ? "<option value=".strval($row['province_id'])." selected>{$row['name']}</option>" : "<option value=".strval($row['province_id']).">{$row['name']}</option>";
                  }
                  ?>
              </select>
              <span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="select2-data-1-aycg" style="width: 289.25px;">
                <span class="selection">
                  <span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-complainant-province-container" aria-controls="select2-complainant-province-container">
                    <span class="select2-selection__rendered" id="select2-complainant-province-container" role="textbox" aria-readonly="true" title="Select a Province">Select a Province</span>
                    <span class="select2-selection__arrow" role="presentation">
                      <b role="presentation"></b>
                    </span>
                  </span>
                </span>
                <span class="dropdown-wrapper" aria-hidden="true"></span>
              </span>
            </div>
            <div class="col-md-4">
              <label for="complainant-municipality-city" class="form-label">Municipality/City</label>
              <select name="complainant_city_municipality" id="complainant-municipality-city" class="form-select pointer select2-hidden-accessible" data-select2-id="select2-data-complainant-municipality-city" tabindex="-1" aria-hidden="true">
                <option value="" selected="" data-select2-id="select2-data-4-l59x">Select a Municipality/City</option>
              </select>
              <span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="select2-data-3-es53" style="width: 289.25px;">
                <span class="selection">
                  <span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-complainant-municipality-city-container" aria-controls="select2-complainant-municipality-city-container">
                    <span class="select2-selection__rendered" id="select2-complainant-municipality-city-container" role="textbox" aria-readonly="true" title="Select a Municipality/City">Select a Municipality/City</span>
                    <span class="select2-selection__arrow" role="presentation">
                      <b role="presentation"></b>
                    </span>
                  </span>
                </span>
                <span class="dropdown-wrapper" aria-hidden="true"></span>
              </span>
            </div>
            <div class="col-md-4">
              <label for="complainant-barangay" class="form-label">Barangay</label>
              <select name="complainant_barangay" id="complainant_barangay" class="form-select pointer select2-hidden-accessible" data-select2-id="select2-data-complainant-barangay" tabindex="-1" aria-hidden="true">
                <option value="" selected="" data-select2-id="select2-data-6-ro06">Select a Barangay</option>
              </select>
              <span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="select2-data-5-nnzr" style="width: 289.25px;">
                <span class="selection">
                  <span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-complainant-barangay-container" aria-controls="select2-complainant-barangay-container">
                    <span class="select2-selection__rendered" id="select2-complainant-barangay-container" role="textbox" aria-readonly="true" title="Select a Barangay">Select a Barangay</span>
                    <span class="select2-selection__arrow" role="presentation">
                      <b role="presentation"></b>
                    </span>
                  </span>
                </span>
                <span class="dropdown-wrapper" aria-hidden="true"></span>
              </span>
            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-4">
              <label for="complainant-zone" class="form-label">Zone (Purok)</label>
              <input type="text" name="complainant_zone" value="<?= htmlspecialchars($complainant_zone) ?>" id="complainant-zone" class="form-control" placeholder="Zone Example" aria-label="zone">
            </div>
            <div class="col-md-4">
              <label for="complainant-street" class="form-label">Street</label>
              <input type="text" name="complainant_street" value="<?= htmlspecialchars($complainant_street) ?>" id="complainant-street" class="form-control" placeholder="Street Example" aria-label="street">
            </div>
            <div class="col-md-4">
              <label for="complainant-landmark" class="form-label">Landmark</label>
              <input type="text" name="complainant_landmark" value="<?= htmlspecialchars($complainant_landmark) ?>" id="complainant-landmark" class="form-control" placeholder="Landmark" aria-label="landmark">
            </div>
          </div>
        </div>
        <div class="tab mt-4" data-tab-index="1">
          <div class="row mb-3">
            <label class="form-label"><i class="material-symbols-outlined md-24 text-dark">person</i> Suspect Information</label>
            <hr>
          </div>
          <div class="d-flex justify-content-end mb-3">
            <input type="number" class="d-none" id="suspect-id" name="suspect_id" value="<?= htmlspecialchars($suspect_resident_id) ?>">
            <button type="button" class="btn btn-sm btn-outline-dark float-end" data-bs-toggle="modal" data-bs-target="#resident-search-modal-suspect">
              <i class="material-symbols-outlined md-18 text-secondary">person_search</i> Search resident data </button>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-4">
              <input type="text" required class="form-control" placeholder="First name" aria-label="First name" name="suspect_first_name" value="<?= htmlspecialchars($suspect_first_name) ?>" id="suspect-first-name">
            </div>
            <div class="col-md-4">
              <input type="text" required class="form-control" placeholder="Last name" aria-label="Last name" name="suspect_last_name" value="<?= htmlspecialchars($suspect_last_name) ?>" id="suspect-last-name">
            </div>
            <div class="col-md-4">
              <input type="text" class="form-control" placeholder="Middle name" aria-label="Middle name" name="suspect_middle_name" value="<?= htmlspecialchars($suspect_middle_name) ?>" id="suspect-middle-name">
            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-lg-2">
              <label for="date-of-birth" class="form-label">Date of Birth</label>
              <input name="suspect_dob" value="<?= htmlspecialchars($suspect_dob) ?>" id="suspect_dob" type="date" class="form-control" placeholder="" aria-label="date of birth">
            </div>
            <div class="col-lg-1">
              <label for="age" class="form-label">Age</label>
              <input name="suspect_age" value="<?= $suspect_dob ? (date('Y') - date('Y', strtotime($suspect_dob))) : '' ?>" id="suspect_age" maxlength="1" type="number" class="form-control" placeholder="" aria-label="age">
            </div>
            <div class="col-lg-1">
              <label for="gender" class="form-label">Gender</label>
              <select name="suspect_gender" value="" id="suspect_gender" class="form-control" placeholder="" aria-label="gender">
                <option value="Male" <?= $suspect_gender === 'Male' ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= $suspect_gender === 'Female' ? 'selected' : '' ?>>Female</option>
              </select>
            </div>
            <div class="col-lg-2">
              <label for="civil-status" class="form-label">Civil Status</label>
              <!-- Here -->
              <select name="suspect_civil_status" value="<?= htmlspecialchars($suspect_civil_status) ?>" id="suspect_civil_status" class="form-control" placeholder="" aria-label="civil-status">
                <option value="Single" <?= $suspect_civil_status === 'Single' ? 'selected' : '' ?>>Single</option>
                <option value="Married" <?= $suspect_civil_status === 'Married' ? 'selected' : '' ?>>Married</option>
                <option value="Separated" <?= $suspect_civil_status === 'Separated' ? 'selected' : '' ?>>Separated</option>
                <option value="Widow" <?= $suspect_civil_status === 'Widow' ? 'selected' : '' ?>>Widow</option>
                <option value="Divorced" <?= $suspect_civil_status === 'Divorced' ? 'selected' : '' ?>>Divorced</option>
              </select>
            </div>
            <div class="col-lg-2">
              <label for="phone-number" class="form-label">Phone Number</label>
              <input type="text" id="suspect_phone" name="suspect_phone" value="<?= htmlspecialchars($suspect_phone) ?>" class="form-control" placeholder="0900-000-0000" aria-label="Phone Number">
            </div>
            <div class="col-lg-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" name="suspect_email" value="<?= htmlspecialchars($suspect_email) ?>" id="suspect_email" class="form-control" placeholder="email@example.com" aria-label="email">
            </div>
          </div>
          <div class="row mb-3 mt-5">
            <label class="form-label"><i class="material-symbols-outlined md-24 text-dark">person_pin_circle</i> Suspect Address</label>
            <hr>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-4">
              <label for="suspect-province" class="form-label">Province</label>
              <select name="suspect_province" id="suspect-province" class="form-select pointer select2-hidden-accessible" data-select2-id="select2-data-suspect-province" tabindex="-1" aria-hidden="true">
                <option>Select Province</option>
                  <?php
                  require '../config/database.php';
                  $stmt = $pdo->query("SELECT province_id, name FROM provinces ORDER BY name");
                  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo $suspect_province === $row['province_id'] ? "<option value=".strval($row['province_id'])." selected>{$row['name']}</option>" : "<option value=".strval($row['province_id']).">{$row['name']}</option>";

                  }
                  ?>
              </select>
              <span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="select2-data-7-a3r7" style="width: 289.25px;">
                <span class="selection">
                  <span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-suspect-province-container" aria-controls="select2-suspect-province-container">
                    <span class="select2-selection__rendered" id="select2-suspect-province-container" role="textbox" aria-readonly="true" title="Select a Province">Select a Province</span>
                    <span class="select2-selection__arrow" role="presentation">
                      <b role="presentation"></b>
                    </span>
                  </span>
                </span>
                <span class="dropdown-wrapper" aria-hidden="true"></span>
              </span>
            </div>
            <div class="col-md-4">
              <label for="suspect-municipality-city" class="form-label">Municipality/City</label>
              <select name="suspect_city_municipality" id="suspect-municipality-city" class="form-select pointer select2-hidden-accessible" data-select2-id="select2-data-suspect-municipality-city" tabindex="-1" aria-hidden="true">
                <option value="" selected="" data-select2-id="select2-data-10-1szq">Select a Municipality/City</option>
              </select>
              <span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="select2-data-9-vw0h" style="width: 289.25px;">
                <span class="selection">
                  <span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-suspect-municipality-city-container" aria-controls="select2-suspect-municipality-city-container">
                    <span class="select2-selection__rendered" id="select2-suspect-municipality-city-container" role="textbox" aria-readonly="true" title="Select a Municipality/City">Select a Municipality/City</span>
                    <span class="select2-selection__arrow" role="presentation">
                      <b role="presentation"></b>
                    </span>
                  </span>
                </span>
                <span class="dropdown-wrapper" aria-hidden="true"></span>
              </span>
            </div>
            <div class="col-md-4">
              <label for="suspect-barangay" class="form-label">Barangay</label>
              <select name="suspect_barangay" id="suspect-barangay" class="form-select pointer select2-hidden-accessible" data-select2-id="select2-data-suspect-barangay" tabindex="-1" aria-hidden="true">
                <option value="" selected="" data-select2-id="select2-data-12-ukt2">Select a Barangay</option>
              </select>
              <span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="select2-data-11-ec57" style="width: 289.25px;">
                <span class="selection">
                  <span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-suspect-barangay-container" aria-controls="select2-suspect-barangay-container">
                    <span class="select2-selection__rendered" id="select2-suspect-barangay-container" role="textbox" aria-readonly="true" title="Select a Barangay">Select a Barangay</span>
                    <span class="select2-selection__arrow" role="presentation">
                      <b role="presentation"></b>
                    </span>
                  </span>
                </span>
                <span class="dropdown-wrapper" aria-hidden="true"></span>
              </span>
            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-4">
              <label for="suspect-zone" class="form-label">Zone (Purok)</label>
              <input type="text" name="suspect_zone" value="<?= htmlspecialchars($suspect_zone) ?>" id="suspect-zone" class="form-control" placeholder="Zone Example" aria-label="zone">
            </div>
            <div class="col-md-4">
              <label for="suspect-street" class="form-label">Street</label>
              <input type="text" name="suspect_street" value="<?= htmlspecialchars($suspect_street) ?>" id="suspect-street" class="form-control" placeholder="Street Example" aria-label="street">
            </div>
            <div class="col-md-4">
              <label for="suspect-landmark" class="form-label">Landmark</label>
              <input type="text" name="suspect_landmark" value="<?= htmlspecialchars($suspect_landmark) ?>" id="suspect-landmark" class="form-control" placeholder="Landmark" aria-label="landmark">
            </div>
          </div>
        </div>
        <div class="tab mt-4" data-tab-index="2">
          <div class="row mb-3">
            <label class="form-label"><i class="material-symbols-outlined md-24 text-dark">breaking_news_alt_1</i> Incident Information</label>
            <hr>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-4">
              <label for="date-of-incident" class="form-label">Date of Incident</label>
              <input require name="date_of_incident" value="<?= htmlspecialchars($date_of_incident) ?>" id="date-of-incident" type="date" class="form-control" placeholder="" aria-label="date of incident">
            </div>
            <div class="col-md-4">
              <label for="time-of-incident" class="form-label">Time of Incident</label>
              <input name="time_of_incident" value="<?= htmlspecialchars($time_of_incident) ?>" id="time-of-incident" type="time" class="form-control" placeholder="" aria-label="date of incident">
            </div>
            <div class="col-md-4">
              <label for="type-of-incident" class="form-label">Type of Incident/Complaint</label>
              <div class="position-relative">
                <select required id="type-of-incident" name="type_of_incident" class="form-select" aria-label="Default select example">
                  <option selected="">Choose an Incident Type</option>
                  <option value="Crime" <?= $incident_type  === 'Crime' ? 'selected' : '' ?>>Crime</option>
                  <option value="Noise" <?= $incident_type  === 'Noise' ? 'selected' : '' ?>>Noise</option>
                  <option value="Disputes" <?= $incident_type  === 'Disputes' ? 'selected' : '' ?>>Disputes</option>
                  <option value="Public Disturbances" <?= $incident_type  === 'Public Disturbances' ? 'selected' : '' ?>>Public Disturbances</option>
                  <option value="Maintenance Issues" <?= $incident_type  === 'Maintenance Issues' ? 'selected' : '' ?>>Maintenance Issues</option>
                  <option value="Others" <?= $incident_type  === 'Others' ? 'selected' : '' ?>>Others</option>
                </select>
                <div class="position-absolute top-0 start-0" style="width: calc(100% - 40px); display: none;" id="others-incident-type-container">
                  <input type="text" class="form-control rounded-end-0 border-end-0" placeholder="Others" id="others-incident-type" value="">
                </div>
              </div>
            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-4">
              <label for="victim-first-name" class="form-label">Victim's First Name</label>
              <input type="text" class="form-control" id="victim-first-name" name="victim_first_name" value="<?= htmlspecialchars($victim_first_name) ?>"  placeholder="First name" aria-label="First name">
            </div>
            <div class="col-md-4">
              <label for="victim-middle-name" class="form-label">Victim's Middle Name</label>
              <input type="text" class="form-control" id="victim-middle-name" name="victim_middle_name" value="<?= htmlspecialchars($victim_middle_name) ?>" placeholder="Middle name" aria-label="Middle name">
            </div>
            <div class="col-md-4">
              <label for="victim-last-name" class="form-label">Victim's Last Name</label>
              <input type="text" class="form-control" id="victim-last-name" name="victim_last_name" value="<?= htmlspecialchars($victim_last_name) ?>" placeholder="Last name" aria-label="Last name">
            </div>
          </div>
          <div class="d-flex mb-3">
            <div class="me-3">
              <label for="victim-age" class="form-label">Victim's Age</label>
              <input name="victim_age" value="<?= htmlspecialchars($victim_age) ?>" maxlength="1" type="number" class="form-control" placeholder="" aria-label="age">
            </div>
            <div class="">
              <label for="incident-location" class="form-label">Incident's Location</label>
              <input type="text" class="form-control" id="incident-location" name="incident_location" value="<?= htmlspecialchars($incident_location) ?>" placeholder="Location" aria-label="Location">
            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-12">
              <label for="involved-parties" class="form-label">Involved Parties</label>
              <textarea class="form-control" id="involved-parties" name="involved_parties" rows="5"><?= htmlspecialchars($involved_parties) ?></textarea>
            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-12">
              <label for="narrative" class="form-label">Narrative</label>
              <textarea required class="form-control" id="incident-narrative" name="incident_description" rows="5"><?= htmlspecialchars($incident_description) ?></textarea>
            </div>
          </div>
          <div class="row mb-3 mt-5">
            <label class="form-label">Upload Photo for Evidence</label>
            <hr>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-12 mt-0">
              <div class="mb-3">
                <label for="evidence" id="evidence-label" class="form-label bg-secondary bg-opacity-10 border border-2 rounded w-100 text-muted text-center pointer p-3"
                      style="border-style: dashed !important; max-height: 300px; overflow-y: auto; cursor: pointer;">
                  <small class="my-3 text-center d-block">
                    <i class="bi bi-image fs-1"></i><br>
                    <i class="material-symbols-outlined md-18 text-secondary">image_arrow_up</i> Upload photo or video evidence
                  </small>
                  <div id="preview-container" class="d-flex flex-wrap justify-content-center gap-2">
                    <?php if (!empty($evidence)) : ?>
                      <?php foreach ($evidence as $file) : ?>
                        <div class="position-relative">
                          <img src="uploads/blotter_evidence/<?= htmlspecialchars($file['file_name']) ?>" alt="Evidence" class="img-thumbnail" style="max-width: 150px; max-height: 150px;">
                          <button type="button" class="btn-close position-absolute top-0 end-0" aria-label="Close" onclick="removeEvidence(this)"></button>
                        </div>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </div>
                </label>

                <!-- multiple file input -->
                <input type="file" name="evidence[]" id="evidence" accept="image/*,video/*" multiple hidden>
              </div>

            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-12">
              <label for="note-on-evidence" class="form-label">Note on Evidence</label>
              <textarea class="form-control" id="note-on-evidence" name="note_on_evidence" rows="5"><?= htmlspecialchars($note_on_evidence) ?></textarea>
            </div>
          </div>
          <hr>
          <div class="row g-3 mb-3">
            <div class="col-md-12">
              <label for="resolution" class="form-label">Resolution</label>
              <textarea class="form-control" id="resolution" name="resolution" rows="5"><?= htmlspecialchars($resolution) ?></textarea>
            </div>
            <div class="col-md-3">
              <label for="date-of-resolution" class="form-label">Date of Resolution </label>
              <input name="date_of_resolution" value="<?= $resolution_date ?>" id="date-of-resolution" type="date" class="form-control" placeholder="" aria-label="date of resolution">
            </div>
            <div class="col-md-3">
              <label for="attending-officer-first-name" class="form-label">Attending Officer First Name</label>
              <input type="text" required class="form-control" id="attending-officer-first-name" name="attending_officer_first_name" value="<?= htmlspecialchars($attending_officer_first_name) ?>" placeholder="Officer's First Name" aria-label="First Name">
            </div>
            <div class="col-md-3">
              <label for="attending-officer-middle-name" class="form-label">Attending Officer Middle Name</label>
              <input type="text" class="form-control" id="attending-officer-middle-name" name="attending_officer_middle_name" value="<?= htmlspecialchars($attending_officer_middle_name) ?>" placeholder="Officer's Middle Name" aria-label="First Name">
            </div>
            <div class="col-md-3">
              <label for="attending-officer-last-name" class="form-label">Attending Officer Last Name</label>
              <input type="text" required class="form-control" id="attending-officer-last-name" name="attending_officer_last_name" value="<?= htmlspecialchars($attending_officer_last_name) ?>" placeholder="Officer's Last Name" aria-label="First Name">
            </div>
          </div>
        </div>
        <div class="d-flex justify-content-between border-top p-2">
          <a href="#" class="btn btn-secondary px-3" id="tab-back"><i class="material-symbols-outlined md-18 text-secondary">arrow_circle_up</i> Back to Top</a>
          <button type="submit" class="btn btn-primary px-5" id="tab-submit">Submit</button>
        </div>
      </form>
    </div>
  </section>
</div>

<!-- Search Resident Modal Complainant -->

<div class="modal fade" id="resident-search-modal" tabindex="-1" aria-labelledby="submissionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title text-light" id="submissionModalLabel">Search from Existing Residents</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div id="message" class="modal-body">
        <div class="mb-3">
          <label for="residentSearch" class="form-label">Search resident data</label>
          <input type="text" class="form-control" id="residentSearch" placeholder="Enter name...">
          <div id="residentSuggestions" class="list-group mt-1"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Search Resident Modal Suspect -->

<div class="modal fade" id="resident-search-modal-suspect" tabindex="-1" aria-labelledby="submissionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title text-light" id="submissionModalLabel">Suspect Field Group: Search from Existing Residents</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div id="message" class="modal-body">
        <div class="mb-3">
          <label for="residentSearch-suspect" class="form-label">Search resident data</label>
          <input type="text" class="form-control" id="residentSearch-suspect" placeholder="Enter name...">
          <div id="residentSuggestions-suspect" class="list-group mt-1"></div>
        </div>
      </div>
    </div>
  </div>
</div>