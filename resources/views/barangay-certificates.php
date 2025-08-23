<?php
  require '../config/database.php';
    $request_failed = false;
  if(isset($_GET['request_id']) && $_GET['success'] == 1) {
     // get the resident name and purpose from the resident table and barangay_certificate_requests table
     $requestId = $_GET['request_id'];
     $stmt = $pdo->prepare("
       SELECT r.last_name,r.first_name, r.middle_name, r.date_of_birth, r.gender, r.civil_status, r.present_zone, r.present_street, bcr.purpose, bcr.status, bcr.requested_at
       FROM residents r
       JOIN barangay_certificate_requests bcr ON r.id = bcr.resident_id
       WHERE bcr.id = :request_id
     ");
     $stmt->execute([':request_id' => $requestId]);
     $request = $stmt->fetch(PDO::FETCH_ASSOC);

    

     if ($request) {

        // set prefix
        $name_prefix = htmlspecialchars($request['gender']) == 'Male' ? 'Mr.' : 'Ms.';
        $name_pronoun = htmlspecialchars($request['gender']) == 'Male' ? 'He' : 'She';
        // set variables
        $civil_status = htmlspecialchars($request['civil_status']);
        $current_address = htmlspecialchars($request['present_zone']) . ', ' . htmlspecialchars($request['present_street']);
        $requester_full_name = htmlspecialchars($request['first_name']) . ', ' . htmlspecialchars($request['middle_name']) . ' ' . htmlspecialchars($request['last_name']);
        $requester_date_of_birth = htmlspecialchars($request['date_of_birth']);
        $requester_age = date_diff(date_create($requester_date_of_birth), date_create('today'))->y;
        $requester_purpose = htmlspecialchars($request['purpose']);
        $requester_status = htmlspecialchars($request['status']);
        $requester_date_requested = htmlspecialchars($request['requested_at']);
     } else {
        $request_failed = true;
     }  
  }

?>

<main id="residents-dashboard" class="col-md-10 ms-sm-auto px-md-4 py-4">
  <div class="px-3 py-5">
      <div class="row mb-3">
          <h1 class="m-0">Barangay Certificates</h1>
          <hr>
      </div>
      <div class="row mx-0">
          <section class="col-md-3">
              <div class="sticky-top py-1">
                  <div class="bg-dark text-white rounded">
                      <nav class="nav flex-column nav-pills py-3">
                          <p class="text-uppercase text-truncate ps-3">Certificates</p>
                          <ul class="list-unstyled">
                              <li class="nav-item">
                                  <a href="?page=barangay-certificates" class="nav-link text-white text-truncate <?php if ($page == 'barangay-certificates') echo 'active'; ?>" aria-current="page">
                                      Barangay Certificate
                                  </a>
                              </li>
                              <li class="nav-item">
                                  <a href="?page=barangay-clearance" class="nav-link text-white text-truncate <?php if ($page == 'barangay-clearance') echo 'active'; ?>" aria-current="page">
                                      Barangay Clearance
                                  </a>
                              </li>
                              <li class="nav-item">
                                  <a href="?page=barangay-certificate-of-indigency" class="nav-link text-white text-truncate <?php if ($page == 'barangay-certificate-of-indigency') echo 'active'; ?>" aria-current="page">
                                      Barangay Indigency
                                  </a>
                              </li>
                          </ul>
                      </nav>
                  </div>
              </div>
          </section>

          <section class="inner-content col-md-9 p-0 bg-transparent rounded" style="overflow: hidden;">
            <form method="POST" class="needs-validation" id="barangay-certificate-form" class="px-3 py-4" novalidate action="<?= ACTIONS_URL ?>add-request-barangay-certificate.php" enctype="multipart/form-data">
                <div class="card mb-4">
                    <div class="card-header fw-bold"><i class="add-resident-subheading-icon material-symbols-outlined md-18 text-dark">Info</i> Process Document Request</div>
                    <div class="card-body row g-3">
                        <div class="col-md-8"><input type="text" name="purpose" class="form-control" placeholder="Purpose" required>
                            <div class="invalid-feedback">Purpose is required.</div>
                        </div>
                        <div class="col-md-4 d-flex justify-content-end g-3 mb-3 align-items-end">
                            <input type="number" class="d-none" id="resident-id" name="resident_id" value="0">
                            <button type="button" class="btn btn-sm btn-outline-dark" data-bs-toggle="modal"  data-bs-target="#resident-search-modal">
                            <i class="material-symbols-outlined md-18 text-secondary">person_search</i> Search resident data </button>
                        </div>
                    </div>
                    <div class="card-body row g-3">
                        <div class="col-md-8">
                            <button type="submit" class="btn btn-primary">Save Request</button>
                        </div>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header fw-bold"><i class="add-resident-subheading-icon material-symbols-outlined md-18 text-dark">Info</i> Requester Information</div>
                    <div class="card-body row g-3">
                        <div class="col-md-4"><input type="text" readonly name="resident_first_name" class="form-control" placeholder="First name" required>
                        </div>
                        <div class="col-md-4"><input type="text" readonly name="resident_middle_name" class="form-control" placeholder="Middle name"></div>
                        <div class="col-md-4"><input type="text" readonly name="resident_last_name" class="form-control" placeholder="Last name" required>
                        </div>
                    </div>
                </div>
            </form>
              <!-- Table -->
            <div class="table-responsive">
            <table id="residents-table" class="table table-bordered align-middle">
                <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Requester</th>
                    <th>Zone / Street</th>
                    <th>Purpose</th>
                    <th>Status</th>
                    <th>Date Requested</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                
                <?php

                    $stmt = $pdo->query("SELECT r.last_name,r.first_name, r.middle_name, r.date_of_birth, r.gender, r.civil_status, r.present_zone, r.present_street, bcr.id, bcr.purpose, bcr.status, bcr.requested_at
                        FROM residents r
                        JOIN barangay_certificate_requests bcr ON r.id = bcr.resident_id");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $current_age = date_diff(date_create($row['date_of_birth']), date_create('today'))->y;
                    ?>
                    <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name']; ?></td>
                    <td><?php echo $row['present_zone']. ' ' . $row['present_street']; ?></td>
                    <td><?php echo $row['purpose']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td><?php echo $row['requested_at']; ?></td>
                    <td class="text-center">
                        <a data-id="<?= $row['id'] ?>" href="?page=barangay-certificates&success=1&request_id=<?= $row['id'] ?>" class="btn btn-sm btn-success text-white edit-btn" title="View Barangay Certificate"><i class="material-symbols-outlined md-18">article</i></a>
                    </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            </div>
          </section>
      </div>
  </div>       


  <!-- Certificate Module -->

<div class="modal fade" tabindex="-1" id="barangay-certificate-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Barangay Certificate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="ff-courier row m-0 a4" id="barangay-certificate-printable">
                    <div class="col-3 bg-success bg-opacity-25 py-3">
                        <img src="" alt="" class="img-fluid">
                        <div class="border border-success border-4 opacity-75 my-3 rounded"></div>
                            <div class="mb-5 text-center">
                                <u class="punong-barangay-placeholder"></u> <br>
                                <i><small>Punong Barangay</small></i>
                            </div>

                            <div class="mb-3 text-center">
                                <u class="kagawad-name-placeholder"></u> <br>
                                <i><small>Barangay Kagawad</small></i>
                            </div>
                        </div>
                    <div class="col-9">
                        <div class="text-center">
                            <b>Province of Bulacan</b><br>
                            <b>Municipality of Plaridel</b><br>
                            <b>Barangay Lalangan</b><br>
                            <h4 class="my-4 fw-bold">BARANGAY CERTIFICATE</h4>
                        </div>
                        <p style="text-indent: 50px; text-align: justify;" class="mb-4">
                            This is to certify that <u class="text-uppercase" id="certificate-resident-name-prefix"><?= $name_prefix ?></u> <u class="text-uppercase" id="certificate-resident-name"><?= $requester_full_name ?></u>, <u class="text-uppercase" id="certificate-resident-age"><?= $requester_age ?></u> years old, 
                            <u class="text-uppercase" id="certificate-resident-civil-status"><?= $civil_status ?></u>, with residence and postal address at <span class="text-uppercase" id="certificate-resident-barangay"><?= $current_address ?> BARANGAY  Lalangan</span>, <span class="text-uppercase" id="certificate-resident-municipality">Plaridel</span>, 
                            <span class="text-uppercase" id="certificate-resident-province">Bulacan</span>.
                        </p>
                        <p class="mt-2 mb-0" style="text-indent: 50px; text-align: justify;">
                           <?= $name_pronoun ?> is a law abiding citizen and has a good moral character. Records of this barangay has shown that he/she has not committed nor been involved in any kind of unlawful activities in this barangay. 
                        </p>
                        <br>
                        <span class="mt-2 mb-0" style="text-align: justify;">
                            <span style="margin-left: 50px;"></span> This certificate is hereby issued upon request for 
                        </span>

                        <div id="certificate_purpose" class="position-relative d-inline-block pointer text-justified" data-permit-requestor="">
                            <u id="certificate_purpose_reason" class="text-uppercase"></u>
                            <u id="certificate_purpose_placeholder" class="text-danger text-nowrap"><?= $requester_purpose ?></u>
                        
                        </div>. 

                        <p class="mt-4" style="text-indent: 50px; text-align: justify;">
                            Issued this <u><?= date("d")?><span class="small"><?php switch (intval(date("d")) % 10 && date("d")[0]!=1) {
                                        case 1:echo "st";
                                            break;
                                        case 2:echo "nd";
                                            break;
                                        case 3:echo "rd";
                                            break;
                                        default:echo "th";
                                            break;
                                    }
                                ?></span></u> day of <?= date("M")?>, <?= date("Y")?>.
                        </p>
                        <div class="float-end pt-5">
                            <div class="d-flex flex-column align-items-center py-5">
                                <div class="border-bottom border-dark border-1" style="color: transparent;"> _________________________</div>
                                <div class="text-center punong-barangay-placeholder"></div>
                                <div>Punong Barangay</div>
                            </div>
                        </div>
                    </div>
            <div class="modal-footer d-flex">
                <button type="button" class="btn btn-primary flex-grow-1" id="barangay-certificate-export">Export</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
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

  <!-- Alert Modal -->
<div class="modal fade" id="submissionModal" tabindex="-1" aria-labelledby="submissionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title text-light" id="submissionModalLabel">Error!</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div id="message" class="modal-body">
        No Data Found
      </div>
    </div>
  </div>
</div>

<?php 
    if($request_failed === true) { ?>
       <script>
        document.addEventListener('DOMContentLoaded', function () {

            const messageDiv = document.getElementById('message');

            // 2. Set the content (you can use Bootstrap alert styles if you like)
            messageDiv.innerHTML = `
            <div class="alert alert-danger" role="alert">
                Request not found.
            </div>`;

            const modal = new bootstrap.Modal(document.getElementById('submissionModal'));
            modal.show();
            });
        </script>
    <?php
    }
?>

<?php 
    if(isset($_GET['request_id']) && $_GET['success'] == 1 &&$requester_full_name && $requester_age && $requester_purpose) { ?>
       <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = new bootstrap.Modal(document.getElementById('barangay-certificate-modal'));
            modal.show();
            });

            // For barangay certificate
            document.getElementById("barangay-certificate-export").addEventListener("click", function () {
                printElementById("barangay-certificate-printable", "Barangay Certificate");
            });
        </script>
    <?php
    }
?>
