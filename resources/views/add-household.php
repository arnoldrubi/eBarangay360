<main class="col-md-10 ms-sm-auto px-md-4 py-4">
  <div class="row mb-3">
    <h1 class="m-0">Add New Household</h1>
    <hr>
  </div>
      <!-- Household Info -->
  <form class="needs-validation" novalidate action="<?= ACTIONS_URL ?>add-household.php" method="POST">

    <div class="card mb-4">
      <div class="card-header fw-bold">Assign Head of Household</div>
      <div class="card-body row g-3">
        <div class="mb-3">
          <label for="household_head_id" class="form-label">Household Head</label>
          <select name="household_head_id" id="household_head_id" class="form-select" required>
            <option value="">-- Select Resident --</option>
            <?php
              // load PDO database connection
              require '../config/database.php';
              // You must fetch resident names from DB
              $stmt = $pdo->query("SELECT id, CONCAT(last_name, ', ', first_name) as full_name FROM residents WHERE alive = 1");
              while ($row = $stmt->fetch()) {
                  echo "<option value=\"{$row['id']}\">{$row['full_name']}</option>";
              }
            ?>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Ownership</label>
          <select name="ownership_status" class="form-select" required>
            <option value="Owned">Owned</option>
            <option value="Rented">Rented</option>
          </select>
        </div>
      </div>
    </div>

      <!-- Present Address -->
      <div class="card mb-4">
        <div class="card-header fw-bold">Present Address</div>
        <div class="card-body row g-3">
          <div class="col-md-3">
            <input type="text" readonly value="" id="present_zone" name="present_zone" class="form-control" placeholder="Zone (Purok)">
          </div>
          <div class="col-md-6">
            <input type="text" readonly value="" id="present_street" name="present_street" class="form-control" placeholder="Street">
          </div>
          <div class="col-md-3">
            <input type="text" readonly value="" id="present_landmark" name="present_landmark" class="form-control" placeholder="Landmark">
          </div>
        </div>
      </div>

    <button type="submit" class="btn btn-primary">Add Household</button>
    <a href="index.php?page=households" class="btn btn-secondary">Cancel</a>
  </form>

