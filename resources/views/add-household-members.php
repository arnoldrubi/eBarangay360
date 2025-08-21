<?php
  // load PDO database connection

  require '../config/database.php';
  $present_zone = '';
  $present_street = '';
  $present_landmark = '';
  $householdHeadName = '';
  $ownershipStatus = '';
  $household_id = $_GET['household_id'] ?? null;
  if ($household_id) {
      $stmt = $pdo->prepare("
          SELECT 
              h.*, 
              CONCAT(r.last_name, ', ', r.first_name, ' ', r.middle_name) AS head_full_name
          FROM 
              households h
          JOIN 
              residents r ON h.head_id = r.id
          WHERE 
              h.id = ?
      ");
      $stmt->execute([$household_id]);
      $household = $stmt->fetch();

      // Use $household['household_code'] and $household['head_full_name'] in your HTML
      $householdHeadName = $household['head_full_name'] ?? '';
      $householdId = $household['id'] ?? '';
      $ownershipStatus = $household['ownership_status'] ?? '';
      $present_zone = $household['address_zone'] ?? '';
      $present_street = $household['address_street'] ?? '';
      $present_landmark = $household['address_landmark'] ?? '';
  }
?>
<main class="col-md-10 ms-sm-auto px-md-4 py-4">
  <div class="row mb-3">
    <h1 class="m-0">Assign Household Members</h1>
    <hr>
  </div>
  <form class="needs-validation">
    <!-- Household Head Info -->
    <div class="card mb-4">
      <div class="card-header fw-bold">Current Head of Household</div>
        <div class="card-body row g-3">
          <div class="mb-3">
            <label for="household_head_id" class="form-label">Household Head</label>       
            <input type="text" readonly id="household_head" name="household_head" class="form-control" value="<?= $householdHeadName; ?>" placeholder="Household Head" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Ownership</label>
            <input type="text" readonly id="ownership_status" name="ownership_status" class="form-control" value="<?= $ownershipStatus; ?>" placeholder="" required>
          </div>
        </div>
    </div>
    <!-- Present Address -->
    <div class="card mb-4">
      <div class="card-header fw-bold">Present Address</div>
      <div class="card-body row g-3">
        <div class="col-md-3">
          <input type="text" readonly value="<?= $present_zone ?>" id="present_zone" name="present_zone" class="form-control" placeholder="Zone (Purok)">
        </div>
        <div class="col-md-6">
          <input type="text" readonly value="<?= $present_street; ?>" id="present_street" name="present_street" class="form-control" placeholder="Street">
        </div>
        <div class="col-md-3">
          <input type="text" readonly value="<?= $present_landmark; ?>" id="present_landmark" name="present_landmark" class="form-control" placeholder="Landmark">
        </div>
      </div>
    </div>

  </form>
    <!-- Add Household Members -->

  <div class="card mb-4">
    <div class="card-header fw-bold">Assign Household Members</div>
      <div class="card-body row g-3">
        <div class="container mt-4">
          <form action="<?= ACTIONS_URL ?>add-household-members.php" method="POST">
            <input type="hidden" name="household_id" value="<?= $household_id ?>">
            <div id="resident-dropdown-group">
              <div class="mb-3 resident-dropdown row">
                <div class="col-md-6">
                  <label for="residents[]" class="form-label">Select Member</label>
                  <select name="residents[]" class="form-select resident-select">
                    <option value="">-- Select Resident --</option>
                    <?php
                      $residents = $pdo->query("
                        SELECT r.id, CONCAT(r.last_name, ', ', r.first_name, ' ', r.middle_name) AS full_name
                        FROM residents r
                        WHERE r.id NOT IN (
                          SELECT head_id FROM households
                          UNION
                          SELECT resident_id FROM household_members
                        ) AND alive = 1
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
                  <div class="col-md-2">
                    <button type="button" class="mt-2 btn btn-danger btn-sm remove-member"><i class="material-symbols-outlined md-18 text-light">contract_delete</i> Remove</button>
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

            <button type="button" class="btn btn-outline-primary" id="addMore">
              <i class="material-symbols-outlined md-18 text-secondary">add_box</i> Add Member
            </button>

            <div class="mt-4">
              <button type="submit" class="btn btn-primary">Save Members</button>
            </div>
          </form>
        </div>
      </div>
    </div>

  <div class="modal fade" id="submissionModal" tabindex="-1" aria-labelledby="submissionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title" id="submissionModalLabel">Success!</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div id="message" class="modal-body">
          
        </div>
      </div>
    </div>
  </div>
</main>

<?php if (isset($_GET['success'])): ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {

    const messageDiv = document.getElementById('message');

    messageDiv.innerHTML = `
      <div class="alert alert-success" role="alert">
        New Household is added successfully!
      </div>`;

    const modal = new bootstrap.Modal(document.getElementById('submissionModal'));
    modal.show();
  });
</script>
<?php endif; ?>
