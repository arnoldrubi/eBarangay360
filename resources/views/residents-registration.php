<?php
  require '../config/database.php';

  // Total Pending
  $pending = $pdo->query("SELECT COUNT(*) AS total FROM residents WHERE is_deleted = 0 AND reg_status = 'pending'")->fetch()['total'];

  // Total approved
  $approved = $pdo->query("SELECT COUNT(*) AS total FROM residents WHERE is_deleted = 0 AND reg_status = 'approved'")->fetch()['total'];

  require_once '../src/helpers/utilities.php';
  requireRoles(['admin', 'secretary']);
?>

<main id="residents-dashboard" class="col-md-10 ms-sm-auto px-md-4 py-4">
  <div class="row mb-3">
    <h1 class="m-0">Manage Resident Signups</h1>
    <hr>
  </div>

  <!-- Demographics Summary -->
  <div class="row mb-4 justify-content-center d-flex">
    <div class="col-md-3">
      <div class="card text-center">
        <div id="card-minors" class="card-body residents-dashboard-card">
          <div class="icon p-3 icon-shape text-warning-emphasis rounded-circle bg-warning-subtle  shadow-sm">
            <i class="material-symbols-outlined md-36">pending</i>
          </div>
          <h6 class="mb-0">Awaiting Approval</h6>
          <h4 class="mt-0"><?= $pending ?></h4>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-center">
        <div class="card-body residents-dashboard-card">
          <div id="card-male" class="icon p-3 icon-shape bg-primary-subtle text-warning-emphasis rounded-circle shadow-sm">
            <i class="material-symbols-outlined md-36">check_small</i>
          </div>
          <h6 class="mb-0">Approved</h6>
          <h4 class="mt-0"><?= $approved ?></h4>
        </div>
      </div>
  </div>

  <section class="inner-content">
    <div class="container-fluid p-3">
      <h3 class="mb-4">Pending Residents List</h3>
      <p class="text-muted mb-3">Approve or delete the resident's registration.</p>
  <!-- Add Resident Button -->
  <div class="row mb-3">
    <div class="col-md-7 gap-2 mb-3 d-flex align-items-center">
      <div class="" style="">
          <!-- <select class="form-select" id="age-filter" aria-label="Filter by age">
              <option value="" selected="">Filter by Age</option>
              <option value="seniors">Senior</option>
              <option value="minors">Minor</option>
              <option value="adults">19 above and below 60</option>
          </select> -->
      </div>
      <div class="" style="">
        <!-- <select class="form-select" id="gender-filter" aria-label="Filter by gender">
            <option value="" selected="">Filter by Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select> -->
      </div>
    </div>
    <!-- <div class="col-md-5 text-end">
      <a href="?page=add-resident" class="btn btn-primary dashboard-btn-function"><i class="material-symbols-outlined md-24 text-light">note_add</i>Add Resident</a>
      <a href="<?= ACTIONS_URL ?>export-residents.php" class="btn btn-success dashboard-btn-function"><i class="material-symbols-outlined md-24 text-light">file_download</i>Export Residents</a>
    </div> -->
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
            <th>Alive / Deceased</th>
            <th>Approval Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          
          <?php

            $stmt = $pdo->query("SELECT id, first_name, last_name, middle_name, date_of_birth, present_zone, present_street, gender, alive, reg_status FROM residents WHERE is_deleted = 0 AND reg_status = 'pending'");
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
              <td class="text-center"><a data-id="<?= $row['id'] ?>" data-regstatus="<?= $row['reg_status'] ?>" href="#" class="btn btn-sm approve-reg <?= $row['reg_status']  === 'approved' ? 'btn-success' : 'btn-warning' ?> text-white edit-approval-btn" title="<?= $row['reg_status']  === 'approved' ? 'Unapprove' : 'Approve' ?>"><i class="material-symbols-outlined md-18"><?= $row['reg_status']  === 'approved' ? 'order_approve' : 'other_admission' ?></i></a></td>        
              <td>
                <a href="?page=edit-resident&resident_id=<?= $row['id'] ?>" class="btn btn-sm btn-warning text-white resident-edit-btn" title="Edit"><i class="material-symbols-outlined md-18">edit</i></a>
                <a data-id="<?= $row['id'] ?>" class="btn btn-sm btn-danger reject-btn"><i class="material-symbols-outlined md-18">delete</i></a>
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