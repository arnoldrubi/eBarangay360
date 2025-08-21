<?php
  require '../config/database.php';

?>

<main class="col-md-10 ms-sm-auto px-md-4 py-4">
  <div class="row mb-3">
    <h1 class="m-0">Household Management</h1>
    <hr>
  </div>
  <div class="row mb-4">
    <!-- Step 1 -->
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body text-center">
          <i class="material-symbols-outlined md-24 text-primary">person</i>
          <h5 class="card-title">Step 1</h5>
          <p class="card-text">Select a household head from the list of residents.</p>
        </div>
      </div>
    </div>

    <!-- Step 2 -->
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body text-center">
          <i class="material-symbols-outlined md-24 text-primary">group</i>
          <h5 class="card-title">Step 2</h5>
          <p class="card-text">Add members to the household. You can add multiple residents.</p>
        </div>
      </div>
    </div>

    <!-- Note -->
    <div class="col-md-4">
      <div class="card shadow-sm border-warning">
        <div class="card-body text-center">
          <i class="material-symbols-outlined md-24 text-warning mb-0">warning</i>
          <h5 class="card-title">Important</h5>
          <p class="card-text">Once assigned, a resident cannot be part of another household.</p>
        </div>
      </div>
    </div>
  </div>
  
  <section class="inner-content">
    <div class="container-fluid p-3">
      <h3 class="mb-4">Households List</h3>
      <p class="text-muted mb-3">Group registered residents into households for grouping and profiling.</p>
      
      <!-- Add Household Button -->
      <div class="row mb-3">
        <div class="col-md-7 gap-2 mb-3 d-flex align-items-center">
          <div class="" style="">
              <select class="form-select" id="age-filter" aria-label="Filter by age">
                  <option value="" selected="">Filter by Home Ownership</option>
                  <option value="seniors">Rented</option>
                  <option value="minors">Owned</option>
              </select>
          </div>
        </div>
        <div class="col-md-5 text-end">
          <a href="?page=add-household" class="btn btn-primary dashboard-btn-function"><i class="material-symbols-outlined md-24 text-light">note_add</i>Add Household</a>
          <a href="<?= ACTIONS_URL ?>export-households.php" class="btn btn-success dashboard-btn-function"><i class="material-symbols-outlined md-24 text-light">file_download</i>Export Households</a>
        </div>
      </div>
      <!-- Table -->
      <div class="table-responsive">
        <table id="household-table" class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>Household Code</th>
              <th>Household Head</th>
              <th>Street</th>
              <th>Zone</th>
              <th>Landmark</th>
              <th>Total Members</th>
              <th>Household Ownership Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $stmt = $pdo->query("
                SELECT
                    h.id,
                    h.head_id,
                    h.household_code,
                    CONCAT(r.last_name, ', ', r.first_name, ' ', COALESCE(r.middle_name, '')) AS household_head,
                    h.address_street,
                    h.address_zone,
                    h.address_landmark,
                    h.ownership_status,
                    COUNT(hm.resident_id) AS total_members
                  FROM households h
                  INNER JOIN residents r ON h.head_id = r.id
                  LEFT JOIN household_members hm ON h.id = hm.household_id
                  GROUP BY h.id, h.household_code, household_head, h.address_street, h.address_zone, h.address_landmark
                ");

                $households = $stmt->fetchAll();

                foreach ($households as $household): ?>
              <tr>
                <td><?= htmlspecialchars($household['household_code']) ?></td>
                <td><?= htmlspecialchars($household['household_head']) ?></td>
                <td><?= htmlspecialchars($household['address_street']) ?></td>
                <td><?= htmlspecialchars($household['address_zone']) ?></td>
                <td><?= htmlspecialchars($household['address_landmark']) ?></td>
                <td><?= $household['total_members'] + 1; //plus one to include the household head in the count ?></td>
                <td><?= htmlspecialchars($household['ownership_status']) ?></td>
                <td>
                  <a data-id="<?= $household['id'] ?>" data-bs-toggle="modal" data-bs-target="#editHouseholdModal" href="#" class="btn btn-sm btn-warning text-white edit-household-btn" title="Edit"><i class="material-symbols-outlined md-18">edit</i></a>
                  <a data-id="<?= $household['id'] ?>" data-bs-toggle="modal" data-bs-target="#manageMembersdModal" href="#" class="btn btn-sm btn-warning text-white manage-members-btn" title="Manage Members"><i class="material-symbols-outlined md-18">group</i></a>
                  <a data-id="<?= $household['id'] ?>" class="btn btn-sm btn-danger delete-btn"><i class="material-symbols-outlined md-18">delete</i></a>
                </td>
              </tr>
            <?php endforeach; ?>
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

<!-- Edit Household Info Modal -->
<div class="modal fade" id="editHouseholdModal" tabindex="-1" aria-labelledby="editHouseholdModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <form id="editHouseholdForm" class="needs-validation" novalidate action="<?= ACTIONS_URL ?>update-household.php" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="editHouseholdModalLabel">Edit Household Info</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body row g-3">
          <input type="hidden" name="household_id" id="edit_household_id">

          <!-- Household Code (readonly) -->
          <div class="col-md-6">
            <label for="edit_household_code" class="form-label">Household Code</label>
            <input type="text" class="form-control" id="edit_household_code" name="household_code" readonly>
          </div>

          <!-- Household Head -->
          <div class="col-md-6">
            <label for="edit_household_head" class="form-label">Household Head</label>
            <select name="edit_household_head_id" id="edit_household_head_id" class="form-select" required>
              <option value="">-- Select Resident --</option>
            </select>
          </div>

          <!-- Ownership -->
          <div class="col-md-6">
            <label class="form-label">Ownership</label>
            <select class="form-select" name="edit_ownership" id="edit_ownership" required>
              <option value="">Select</option>
              <option value="Owned">Owned</option>
              <option value="Rented">Rented</option>
            </select>
          </div>

          <!-- Street -->
          <div class="col-md-6">
            <label for="edit_street" class="form-label">Street</label>
            <input type="text" class="form-control" name="edit_street" id="edit_street">
          </div>

          <!-- Zone -->
          <div class="col-md-6">
            <label for="edit_zone" class="form-label">Zone</label>
            <input type="text" class="form-control" name="edit_zone" id="edit_zone">
          </div>

          <!-- Landmark -->
          <div class="col-md-6">
            <label for="edit_landmark" class="form-label">Landmark</label>
            <input type="text" class="form-control" name="edit_landmark" id="edit_landmark">
          </div>
          
          <!-- Notes text area -->
     
          <div class="col-md-12 p2">
            <label for="edit_notes" class="form-label">Notes</label>
              <textarea id="edit_notes" name="edit_notes" class="form-control" rows="3" placeholder="Additional information about the household..."></textarea>
          </div>
        </div>



        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Update Household Info</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>

    </div>
  </div>
</div>


<!-- Manage Members Modal -->
<div class="modal fade" id="manageMembersdModal" tabindex="-1" aria-labelledby="editHouseholdModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="<?= ACTIONS_URL ?>update-household-members.php" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="editHouseholdModalLabel">Manage Household Members</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>           
        <input type="hidden" name="member_household_id" id="member_household_id">
        <div class="modal-body row g-3">
          <div id="memberRepeaterContainer">
            <div id="resident-dropdown-group">
              <div class="mb-3 resident-dropdown row">
                <div class="col-md-6">
                  <label for="residents[]" class="form-label">Select Member</label>
                  <select name="residents[]" class="form-select resident-select mb-2" required>
                    <option value="">-- Select Resident --</option>
                    <?php
                      $residents = $pdo->query("
                        SELECT r.id, CONCAT(r.last_name, ', ', r.first_name, ' ', r.middle_name) AS full_name
                        FROM residents r
                        WHERE r.id NOT IN (
                          SELECT head_id FROM households
                          UNION
                          SELECT resident_id FROM household_members
                        )
                      ")->fetchAll();
                    if (!$residents) {
                        echo '<option value="">No available residents</option>';
                    }
                    else{
                      foreach ($residents as $resident): ?>
                      <option value="<?= $resident['id'] ?>"><?= $resident['full_name'] ?></option>
                    <?php endforeach;
                    } ?>
                  </select>
                  <div class="col-md-3">
                    <button type="button" class="btn btn-danger btn-sm remove-member"><i class="material-symbols-outlined md-18 text-light">contract_delete</i> Remove</button>
                  </div>
                </div>
                <div class="mb-3 resident-dropdown col-md-6">
                  <label for="relationship[]" class="form-label">Relationship With Head</label>
                  <select name="relationship[]" class="form-select" required>
                    <option value="">-- Select Relationship --</option>
                    <option value="Spouse">Spouse</option>
                    <option value="Child">Child</option>
                    <option value="Parent">Parent</option>
                    <option value="Sibling">Sibling</option>
                    <option value="Relative">Relative</option>
                    <option value="Other">Other</option>
                  </select>
                </div>
              </div>
            </div>
          </div>

          <button type="button" class="btn btn-outline-primary" id="addMore">
          <i class="material-symbols-outlined md-18 text-secondary">add_box</i> Add Member
          </button>
        </div>
        <div class="modal-footer">
          <div class="mt-4">
            <button type="submit" class="btn btn-primary">Save Members</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

</main>

<?php if (isset($_GET['success'])): ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {

    const messageDiv = document.getElementById('message');

    // 2. Set the content (you can use Bootstrap alert styles if you like)
    messageDiv.innerHTML = `
      <div class="alert alert-success" role="alert">
        New Household is added successfully!
      </div>`;

    const modal = new bootstrap.Modal(document.getElementById('submissionModal'));
    modal.show();
  });
</script>
<?php endif; ?>

<?php if (isset($_GET['household_updated'])): ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {

    const messageDiv = document.getElementById('message');

    // 2. Set the content (you can use Bootstrap alert styles if you like)
    messageDiv.innerHTML = `
      <div class="alert alert-success" role="alert">
        Household is updated!
      </div>`;

    const modal = new bootstrap.Modal(document.getElementById('submissionModal'));
    modal.show();
  });
</script>
<?php endif; ?>

<?php if (isset($_GET['deleted'])): ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {

    const messageDiv = document.getElementById('message');

    // 2. Set the content (you can use Bootstrap alert styles if you like)
    messageDiv.innerHTML = `
      <div class="alert alert-warning" role="alert">
        Household is deleted!
      </div>`;

    const modal = new bootstrap.Modal(document.getElementById('submissionModal'));
    modal.show();
  });
</script>
<?php endif; ?>