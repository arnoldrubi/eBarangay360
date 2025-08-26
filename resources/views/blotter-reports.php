<?php
  require '../config/database.php';
  require '../src/helpers/utilities.php';

  //Total pending reports
  $stmt = $pdo->query("SELECT COUNT(*) FROM blotter_reports WHERE status = 'Pending' AND is_deleted = 0");
  $totalPending = $stmt->fetchColumn();
  //Total ongoing reports
  $stmt = $pdo->query("SELECT COUNT(*) FROM blotter_reports WHERE status = 'For Schedule' AND is_deleted = 0");
  $totalForSchedule = $stmt->fetchColumn();
  //Total resolved reports
  $stmt = $pdo->query("SELECT COUNT(*) FROM blotter_reports WHERE status = 'Resolved' AND is_deleted = 0");
  $totalResolved = $stmt->fetchColumn();
  //Total for ongoing reports
  $stmt = $pdo->query("SELECT COUNT(*) FROM blotter_reports WHERE status = 'Ongoing' AND is_deleted = 0");
  $totalForOngoing = $stmt->fetchColumn();

  requireRoles(['admin', 'secretary']);

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
                                    <strong>For Schedule</strong>
                                </p>
                                <h3 class="card-title"><?= $totalForSchedule ?></h3>
                            </div>
                            <div class="col-auto">
                                <div class="icon p-3 icon-shape bg-warning-subtle text-warning-emphasis rounded-circle shadow-sm">
                                    <i class="material-symbols-outlined md-24 text-dark">calendar_month</i>
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
                                    <strong>Ongoing</strong>
                                </p>
                                <h3 class="card-title"><?= $totalForOngoing ?></h3>
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
                              <th>Letter</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody style="min-height: 100px;">
                            <?php

                              // Fetch blotter reports from the database
                              $countforrows = 0;

                              // Choices for status dropdown
                              $statusChoices = [
                                  'For Schedule' => 'For Schedule',
                                  'Ongoing' => 'Ongoing',
                                  'Pending' => 'Pending',
                                  'Resolved' => 'Resolved'                                  
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
                                <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="dropdown" aria-expanded="true">
                                <i class="add-resident-subheading-icon material-symbols-outlined md-18 text-light">export_notes</i>
                                </button>
                                <ul class="dropdown-menu blotter-actions" data-blotter-code="<?= $row['blotter_code'] ?>" data-blotter-id="<?= $row['id'] ?>" data-complainant-full-name="<?= $row['complainant_first_name'] . ' ' . $row['complainant_last_name'] ?>" data-suspect-full-name="<?= $row['suspect_first_name'] . ' ' . $row['suspect_last_name'] ?>" style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate(0px, 33.5px);" data-popper-placement="bottom-end">
                                  <li>
                                      <a class="dropdown-item small print-letter" data-bs-toggle="modal" data-bs-target="#summon-letter" href="#">
                                          Create a summon letter
                                      </a>
                                  </li>
                                  <li>
                                      <a class="dropdown-item small print-letter" data-bs-toggle="modal" data-bs-target="#subpoena" href="#">
                                          Issue Subpoena
                                      </a>
                                  </li>
                                  <li>
                                      <a class="dropdown-item small print-letter" data-bs-toggle="modal" data-bs-target="#file-action-letter" href="#">
                                          Generate Certificate to File Action
                                      </a>
                                  </li>
                                  <li>
                                      <a class="dropdown-item small print-letter" data-bs-toggle="modal" data-bs-target="#amicable-settlement" href="#">
                                          Amicable Settlement
                                      </a>
                                  </li>
                                  <li>
                                      <a class="dropdown-item small print-letter" data-bs-toggle="modal" data-bs-target="#failure-to-appear" href="#">
                                          Issue Failure to Appear
                                      </a>
                                  </li>
                                </ul>
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

<div class="modal fade modal-letter" id="summon-letter" tabindex="-1" aria-labelledby="letterModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title">Summon Letter</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body letter" id="summon-letter">
              <div class="text-center pb-2 mb-3" style="border-bottom: 3px double #777;">
                      Republic of the Philippines <br>
                      Province of Bulacan <br>
                      Municipality of Plaridel <br>
                      Barangay Lalangan <br>
                      <br>
                      <b>OFFICE OF THE LUPONG TAGAPAMAYAPA</b>
              </div>
              <div class="row">
                  <div class="col-6 px-5">
                      <div class="">
                          <div class="underlined-field">
                            <p class="text-center complainant-name-placeholder"></p>
                          </div>
                          <!-- <div class="underlined-field">Someone else</div> -->
                          <div class="text-center fw-semibold">Complainant/s</div>
                      </div>
                      <div class="text-center fw-semibold my-3">-against-</div>
                      <div class="">
                          <div class="underlined-field text-center">
                            <p class="suspect-name-placeholder"></p>
                          </div>
                          <!-- <div class="underlined-field">Someone else</div> -->
                          <div class="text-center fw-semibold">Respondent/s</div>
                      </div>
                  </div>
                  <div class="col-6">
                      <div class="d-flex">
                         <strong>Barangay Case No.: <div class="underlined-field summon-id blotter-code-placeholder"></div></strong>
                      </div>
                      <div class="d-flex">
                          For:  <div class="underlined-field">
                            <p class="text-center complainant-name-placeholder"></p>
                          </div>
                      </div>
                  </div>
              </div>

              <div class="text-center fw-bolder my-3 fs-5">S U M M O N S</div>

              <div class="w-50 d-flex mb-3">
                  TO: <div class="flex-grow-1 ms-2 me-5 px-1">
                      <div class="underlined-field">
                        <p class="text-center suspect-name-placeholder"></p>
                      </div>
                      <div class="text-center fw-semibold">Respondent/s</div>
                  </div>
              </div>

              <div class="paragraph">
                  <p class="indent text-justify">
                      You are hereby summoned to appear before me in person, together with your witnesses, on the
                      <span id="" class="d-inline text-nowrap letter-date-container">
                          <span class="letter-date-placeholder pointer text-danger fw-semibold" style="border-bottom: 1px dashed var(--bs-danger);">< pick a date > </span>
                          <span class="letter-date-value pointer" style="border-bottom: 1px dashed var(--bs-dark); display: none;">4th date of May</span>

                      </span> <span class="overflow-hidden d-inline-block letter-date" style="width: 0px !important; height: 0px !important;"><input id="letter-date-input" type="date"></span>, then and there to answer to a complaint made before me, copy of which is attached hereto, for
                      mediation/conciliation of your dispute with complainant/s.
                  </p>
                  <p class="indent text-justify">
                      You are hereby warned that if you refuse or willfully fail to appear in obedience to this summons,
                      you may be barred from filing any counterclaim arising from said complaint.
                  </p>
                  <p> FAIL NOT or else face punishment as for contempt of court. </p>
                  <p>
                    This <u><?= getOrdinal(date("j")) ?></u> day of <?= date("F") ?>, <?= date("Y") ?>.
                  </p>
              </div>
              
              <div class="">
              <div class="row">
                  <div class="offset-6 col-6">
                      <div class="text-center underlined-field punong-barangay-placeholder">
                          
                      </div>
                      <div class="fw-semibold text-center">Barangay Captain</div>
                      <div class="underlined-field lupon-ng-tagapamayapa-placeholder mt-3 text-center">
                          
                      </div>
                      <div class="fw-semibold text-center">Lupon ng Tagapamayapa</div>
                  </div>
              </div>
              </div>

          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-primary flex-grow-1 export-letter">Export</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
      </div>
  </div>
</div>


<div class="modal fade modal-letter" id="subpoena" tabindex="-1" aria-labelledby="letterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Subpoena Letter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body letter" id="subpoena">
                <div class="text-center pb-2 mb-3" style="border-bottom: 3px double #777;">
                        Republic of the Philippines <br>
                        Province of Bulacan <br>
                        Municipality of Plaridel <br>
                        Barangay Lalangan <br>
                        <br>
                        <b>OFFICE OF THE LUPONG TAGAPAMAYAPA</b>
                </div>
                <div class="row">
                    <div class="col-6 px-5">
                        <div class="">
                            <div class="underlined-field">
                              <p class="text-center complainant-name-placeholder"></p>
                            </div>
                            <!-- <div class="underlined-field">Someone else</div> -->
                            <div class="text-center fw-semibold">Complainant/s</div>
                        </div>
                        <div class="text-center fw-semibold my-3">-against-</div>
                        <div class="">
                            <div class="underlined-field text-center">
                              <p class="suspect-name-placeholder"></p>
                            </div>
                            <!-- <div class="underlined-field">Someone else</div> -->
                            <div class="text-center fw-semibold">Respondent/s</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex">
                            <strong>Barangay Case No.: <div class="underlined-field summon-id blotter-code-placeholder"></div></strong>
                        </div>
                        <div class="d-flex">
                            For: <div class="underlined-field">
                              <p class="text-center complainant-name-placeholder"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center fw-bolder my-3 fs-5">S U B P O E N A</div>

                <div class="w-50 d-flex mb-3">
                    TO: <div class="flex-grow-1 ms-2 me-5 px-1">
                        <div class="underlined-field">
                          <p class="text-center suspect-name-placeholder"></p>
                        </div>
                        <div class="text-center fw-semibold ">Respondent/s</div>
                    </div>
                </div>

                <div class="paragraph">
                    <p class="indent text-justify">
                        You are hereby commanded to appear before me on the 
                        <span id="" class="d-inline text-nowrap letter-date-container">
                            <span class="letter-date-placeholder pointer text-danger fw-semibold" style="border-bottom: 1px dashed var(--bs-danger);">< pick a date ></span>
                            <span class="letter-date-value pointer" style="border-bottom: 1px dashed var(--bs-dark); display: none;">4th date of May</span>
                            
                        </span> <span class="overflow-hidden d-inline-block letter-date" style="width: 0px !important; height: 0px !important;"><input type="date"></span>, then and there to testify in the hearing of the above-captioned case.
                        
                    </p>
                    <p> This <u><?=getOrdinal(date("d"))?></u> day of <?= date("M")?>, <?= date("Y")?>. </p>
                </div>
                
                <div class="">
                <div class="row">
                    <div class="offset-6 col-6">
                        <div class="underlined-field text-center punong-barangay-placeholder">
                          
                        </div>
                        <div class="fw-semibold text-center">Barangay Captain</div>
                        <div class="underlined-field mt-3 text-center lupon-ng-tagapamayapa-placeholder">
                            
                        </div>
                        <div class="fw-semibold text-center">Lupon ng Tagapamayapa</div>
                    </div>
                </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary flex-grow-1 export-letter">Export</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-letter" id="file-action-letter" tabindex="-1" aria-labelledby="letterModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title">Certification to File Action</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body letter" id="summon-letter">
              <div class="text-center pb-2 mb-3" style="border-bottom: 3px double #777;">
                      Republic of the Philippines <br>
                      Province of Bulacan <br>
                      Municipality of Plaridel <br>
                      Barangay Lalangan <br>
                      <br>
                      <b>OFFICE OF THE LUPONG TAGAPAMAYAPA</b>
              </div>
              <div class="row">
                  <div class="col-6 px-5">
                      <div class="">
                          <div class="underlined-field">
                            <p class="text-center complainant-name-placeholder"></p>
                          </div>
                          <!-- <div class="underlined-field">Someone else</div> -->
                          <div class="text-center fw-semibold">Complainant/s</div>
                      </div>
                      <div class="text-center fw-semibold my-3">-against-</div>
                      <div class="">
                            <div class="underlined-field text-center">
                              <p class="suspect-name-placeholder"></p>
                            </div>
                          <!-- <div class="underlined-field">Someone else</div> -->
                          <div class="text-center fw-semibold">Respondent/s</div>
                      </div>
                  </div>
                  <div class="col-6">
                      <div class="d-flex">
                        <strong>Barangay Case No.: <div class="underlined-field summon-id blotter-code-placeholder"></div></strong>
                      </div>
                      <div class="d-flex">
                          For: <div class="underlined-field">
                            <p class="text-center complainant-name-placeholder"></p>
                          </div>
                      </div>
                  </div>
              </div>

              <div class="text-center fw-bolder my-3 fs-5">C E R T I F I C A T I O N     T O     F I L E    A C T I O N</div>

              <div class="w-50 d-flex mb-3">
                  TO: <div class="flex-grow-1 ms-2 me-5 px-1">
                      <div class="underlined-field">
                        <p class="text-center suspect-name-placeholder"></p> 
                      </div>
                      <div class="text-center fw-semibold ">Respondent/s</div>
                  </div>
              </div>

              <div class="paragraph">
                  <p class="indent text-justify">THIS IS TO CERTIFY THAT:</p>
                  <ol class="indent text-justify">
                      <li>There has been a personal confrontation between the parties before the Punong Barangay
                      but mediation failed</li>
                      <li>The Pangkat ng Tagapagkasundo was constituted but the personal confrontation before the
                      Pangkat likewise did not result into a settlement; and</li>
                      <li>Therefore, the corresponding complaint for the dispute may now be filed in
                      court/government office.</li>
                  </ol>
                  <p class="indent text-justify">
                      This day of: 
                      <span id="" class="d-inline text-nowrap letter-date-container">
                          <span class="letter-date-placeholder pointer text-danger fw-semibold" style="border-bottom: 1px dashed var(--bs-danger);">< pick a date ></span>
                          <span class="letter-date-value pointer" style="border-bottom: 1px dashed var(--bs-dark); display: none;">4th date of May</span>
                          
                      </span> <span class="overflow-hidden d-inline-block letter-date" style="width: 0px !important; height: 0px !important;"><input type="date"></span>.
                      
                  </p>
                  <!-- <p> This <u><?=getOrdinal(date("d"))?></u> day of <?= date("M")?>, <?= date("Y")?>. </p> -->
              </div>
              
              <div class="">
              <div class="row">
                  <div class="offset-6 col-6">
                      <div class="underlined-field text-center punong-barangay-placeholder">
                        
                      </div>
                      <div class="fw-semibold text-center">Barangay Captain</div>
                      <div class="underlined-field mt-3 text-center lupon-ng-tagapamayapa-placeholder">

                      </div>
                      <div class="fw-semibold text-center">Lupon ng Tagapamayapa</div>
                  </div>
              </div>
              </div>

          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-primary flex-grow-1 export-letter">Export</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
      </div>
  </div>
</div>

<div class="modal fade modal-letter" id="amicable-settlement" tabindex="-1" aria-labelledby="letterModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title">Amicable Settlement Letter</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body letter" id="summon-letter">
              <div class="text-center pb-2 mb-3" style="border-bottom: 3px double #777;">
                      Republic of the Philippines <br>
                      Province of Bulacan <br>
                      Municipality of Plaridel <br>
                      Barangay Lalangan <br>
                      <br>
                      <b>OFFICE OF THE LUPONG TAGAPAMAYAPA</b>
              </div>
              <div class="row">
                  <div class="col-6 px-5">
                      <div class="">
                          <div class="underlined-field">
                            <p class="text-center complainant-name-placeholder"></p>
                          </div>
                          <!-- <div class="underlined-field">Someone else</div> -->
                          <div class="text-center fw-semibold">Complainant/s</div>
                      </div>
                      <div class="text-center fw-semibold my-3">-against-</div>
                      <div class="">
                            <div class="underlined-field text-center">
                              <p class="suspect-name-placeholder"></p>
                            </div>
                          <!-- <div class="underlined-field">Someone else</div> -->
                          <div class="text-center fw-semibold">Respondent/s</div>
                      </div>
                  </div>
                  <div class="col-6">
                      <div class="d-flex">
                        <strong>Barangay Case No.: <div class="underlined-field summon-id blotter-code-placeholder"></div></strong>
                      </div>
                      <div class="d-flex">
                          For: <div class="underlined-field">
                            <p class="text-center complainant-name-placeholder"></p>
                          </div>
                      </div>
                  </div>
              </div>

              <div class="text-center fw-bolder my-3 fs-5">AMICABLE SETTLEMENT</div>

              <div class="w-50 d-flex mb-3">
                  TO: <div class="flex-grow-1 ms-2 me-5 px-1">
                      <div class="underlined-field">
                        <p class="text-center suspect-name-placeholder"></p>
                      </div>
                      <div class="text-center fw-semibold ">Respondent/s</div>
                  </div>
              </div>

              <div class="paragraph">
                  <p class="indent text-justify">We, complainant/s and respondent/s in the above-captioned case, do hereby agree to settle our
                  dispute as follows:</p>
                  <table class="table table-bordered">
                      <tbody>
                          <tr>
                              <td class="p-0"><input type="text" class="form-control form-control-tranparent border-0 fw-bold letter-text" placeholder="< add details >"></td>
                          </tr>
                          <tr>
                              <td class="p-0"><input type="text" class="form-control form-control-tranparent border-0 fw-bold letter-text" placeholder="< add details >"></td>
                          </tr>
                          <tr>
                              <td class="p-0"><input type="text" class="form-control form-control-tranparent border-0 fw-bold letter-text" placeholder="< add details >"></td>
                          </tr>
                      </tbody>
                  </table>
                  <p class="indent text-justify">and bind ourselves to comply honestly and faithfully with the above terms of settlement.</p>
                  <p class="indent text-justify">
                      Entered this day of: 
                      <span id="" class="d-inline text-nowrap letter-date-container">
                          <span class="letter-date-placeholder pointer text-danger fw-semibold" style="border-bottom: 1px dashed var(--bs-danger);">< pick a date ></span>
                          <span class="letter-date-value pointer" style="border-bottom: 1px dashed var(--bs-dark); display: none;">4th date of May</span>
                          
                      </span> <span class="overflow-hidden d-inline-block letter-date" style="width: 0px !important; height: 0px !important;"><input type="date"></span>.
                      
                  </p>
                  <table class="table table-bordered">
                      <thead>
                          <tr>
                              <th scope="col">Complaint/s</th>
                              <th scope="col">Respondent</th>
                          </tr>
                      </thead>
                      <tbody>
                          <tr>
                              <td class="p-0"><input type="text" class="form-control form-control-tranparent border-0 fw-bold letter-text" placeholder="< add details >"></td>
                              <td class="p-0"><input type="text" class="form-control form-control-tranparent border-0 fw-bold letter-text" placeholder="< add details >"></td>
                          </tr>
                          <tr>
                              <td class="p-0"><input type="text" class="form-control form-control-tranparent border-0 fw-bold letter-text" placeholder="< add details >"></td>
                              <td class="p-0"><input type="text" class="form-control form-control-tranparent border-0 fw-bold letter-text" placeholder="< add details >"></td>
                          </tr>
                          <tr>
                              <td class="p-0"><input type="text" class="form-control form-control-tranparent border-0 fw-bold letter-text" placeholder="< add details >"></td>
                              <td class="p-0"><input type="text" class="form-control form-control-tranparent border-0 fw-bold letter-text" placeholder="< add details >"></td>
                          </tr>
                      </tbody>
                  </table>
                  <p class="indent text-justify">ATTESTATION</p>
                  <p class="indent text-justify">I hereby certify that the foregoing amicable settlement was entered into by the parties freely and
                  voluntarily, after I had explained to them the nature and consequence of such settlement.</p>
                  <!-- <p> This <u><?=getOrdinal(date("d"))?></u> day of <?= date("M")?>, <?= date("Y")?>. </p> -->
              </div>
              
              <div class="">
                <div class="row">
                    <div class="offset-6 col-6">
                        <div class="underlined-field text-center punong-barangay-placeholder">
                            
                        </div>
                        <div class="fw-semibold text-center">Barangay Captain</div>
                        <div class="underlined-field mt-3 text-center lupon-ng-tagapamayapa-placeholder">
                            
                        </div>
                        <div class="fw-semibold text-center">Lupon ng Tagapamayapa</div>
                    </div>
                </div>
              </div>

          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-primary flex-grow-1 export-letter">Export</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
      </div>
  </div>
</div>

<div class="modal fade modal-letter" id="failure-to-appear" tabindex="-1" aria-labelledby="letterModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title">Failure to Appear Letter</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body letter" id="summon-letter">
              <div class="text-center pb-2 mb-3" style="border-bottom: 3px double #777;">
                      Republic of the Philippines <br>
                      Province of Bulacan <br>
                      Municipality of Plaridel <br>
                      Barangay Lalangan <br>
                      <br>
                      <b>OFFICE OF THE LUPONG TAGAPAMAYAPA</b>
              </div>
              <div class="row">
                  <div class="col-6 px-5">
                      <div class="">
                          <div class="underlined-field">
                            <p class="text-center complainant-name-placeholder"></p>
                          </div>
                          <!-- <div class="underlined-field">Someone else</div> -->
                          <div class="text-center fw-semibold">Complainant/s</div>
                      </div>
                      <div class="text-center fw-semibold my-3">-against-</div>
                      <div class="">
                        <div class="underlined-field text-center">
                          <p class="suspect-name-placeholder"></p>
                        </div>
                          <!-- <div class="underlined-field">Someone else</div> -->
                        <div class="text-center fw-semibold">Respondent/s</div>
                      </div>
                  </div>
                  <div class="col-6">
                      <div class="d-flex">
                        <strong>Barangay Case No.: <div class="underlined-field summon-id blotter-code-placeholder"></div></strong>
                      </div>
                      <div class="d-flex">
                          For: <div class="underlined-field">
                            <p class="text-center complainant-name-placeholder"></p>
                          </div>
                      </div>
                  </div>
              </div>

              <div class="text-center fw-bolder my-3 fs-5">NOTICE OF HEARING<br>
                  (RE: FAILURE TO APPEAR)
              </div>

              <div class="w-50 d-flex mb-3">
                  TO: <div class="flex-grow-1 ms-2 me-5 px-1">
                      <div class="underlined-field">
                        <p class="suspect-name-placeholder"></p>
                      </div>
                      <div class="text-center fw-semibold ">Complainant/s</div>
                  </div>
              </div>

              <div class="paragraph">
                  <p class="text-justify"> <span class="d-inline-block" style="width: 50px;"></span> You are hereby required to appear before me/the Pangkat on the: 
                  <span class="d-inline-block text-center fw-semibold" style="min-width: 110px; height: 1rem;" contenteditable data-placeholder="< add details >"></span> 
                  at <span class="d-inline-block text-center fw-semibold" style="min-width: 110px; height: 1rem;" contenteditable data-placeholder="< add details >"></span> 
                  o’clock in the morning/ afternoon to explain why you failed to appear for
                  mediation/conciliation scheduled on 
                  <span class="d-inline-block text-center fw-semibold" style="min-width: 110px; height: 1rem;" contenteditable data-placeholder="< add details >"></span> 
                  at <span class="d-inline-block text-center fw-semibold" style="min-width: 110px; height: 1rem;" contenteditable data-placeholder="< add details >"></span>
                    and why your complaint should not be dismissed, a certificate to bar the filing of your action on court/government office should not be issued, and contempt proceedings should not be initiated in court for willful failure or refusal to appear before the Punong Barangay/ Pangkat ng Tagapagkasundo.</p>
                  <p class="indent text-justify">
                      Entered this day of: 
                      <span id="" class="d-inline text-nowrap letter-date-container">
                          <span class="letter-date-placeholder pointer text-danger fw-semibold" style="border-bottom: 1px dashed var(--bs-danger);">< pick a date ></span>
                          <span class="letter-date-value pointer" style="border-bottom: 1px dashed var(--bs-dark); display: none;">4th date of May</span>
                          
                      </span> <span class="overflow-hidden d-inline-block letter-date" style="width: 0px !important; height: 0px !important;"><input type="date"></span>.
                      
                  </p>
              </div>
              
              <div class="">
              <div class="row">
                  <div class="offset-6 col-6">
                      <div class="underlined-field text-center punong-barangay-placeholder">
                        
                      </div>
                      <div class="fw-semibold text-center">Barangay Captain</div>
                      <div class="underlined-field mt-3 text-center lupon-ng-tagapamayapa-placeholder">
                          Lupon ng Tagapamayapa [Name]
                      </div>
                      <div class="fw-semibold text-center">Lupon ng Tagapamayapa</div>
                  </div>
                  <div class="col-12">
                      <p class="indent text-justify"> Notified this 
                          <span id="" class="d-inline text-nowrap letter-date-container">
                                  <span class="letter-date-placeholder pointer text-danger fw-semibold" style="border-bottom: 1px dashed var(--bs-danger);">< pick a date ></span>
                                  <span class="letter-date-value pointer" style="border-bottom: 1px dashed var(--bs-dark); display: none;">4th date of May</span>
                              </span><span class="overflow-hidden d-inline-block letter-date" style="width: 0px !important; height: 0px !important;"><input type="date"></span>.
                          </span>
                      </p>
                  </div>
              </div>
              </div>

          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-primary flex-grow-1 export-letter">Export</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
      </div>
  </div>
</div>

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


<!-- Modal for Blotter Letters -->


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
