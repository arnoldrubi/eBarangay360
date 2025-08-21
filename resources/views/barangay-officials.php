<?php
  require '../config/database.php';

  //preload barangay official information

    $request_failed = false;
  if(isset($_GET['id']) && $_GET['action'] == 'edit') {
     $official_id = $_GET['id'];
     $mode = 'edit';
     $stmt = $pdo->prepare("
       SELECT * FROM barangay_officials WHERE id = :official_id
     ");
     $stmt->execute([':official_id' => $official_id]);
     $official = $stmt->fetch(PDO::FETCH_ASSOC);

     if ($official) {
        $official_first_name = $official['first_name'];
        $official_last_name = $official['last_name'];
        $official_middle_name = $official['middle_name'];
        $official_suffix = $official['suffix'];
        $official_position = $official['position'];
        $official_order = $official['order_no'];
     } else {
        $request_failed = true;
     }
  }

?>

<main id="residents-dashboard" class="col-md-10 ms-sm-auto px-md-4 py-4">
  <div class="px-3 py-5">
      <div class="row mb-3">
          <h1 class="m-0">Barangay Officials</h1>
          <hr>
      </div>
      <div class="row mx-0">
          <section class="col-md-3">
              <div class="sticky-top py-1">
                  <div class="bg-dark text-white rounded">
                      <nav class="nav flex-column nav-pills py-3">
                          <p class="text-uppercase text-truncate ps-3">Current Barangay Officials</p>
                          <ul class="list-unstyled">
                              <li class="nav-item">
                                <?php
                                    $stmt = $pdo->query("SELECT * FROM barangay_officials ORDER BY order_no ASC");
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    ?>
                                    <a href="#" class="nav-link text-white text-truncate" aria-current="page">
                                      <?= $row['first_name'] . ' ' . $row['last_name'] . ' ' . $row['suffix'] ?><br>
                                      <small class="text-light"><?= $row['position'] ?></small>
                                    </a>
                                    <?php } ?>
                              </li>
                          </ul>
                      </nav>
                  </div>
              </div>
          </section>

          <section class="inner-content col-md-9 p-0 bg-transparent rounded" style="overflow: hidden;">
            <form method="POST" class="needs-validation" id="barangay-officials-form" class="px-3 py-4" novalidate action="<?= ACTIONS_URL ?>process-barangay-officials.php" enctype="multipart/form-data">
                <input type="hidden" name="action" value="<?= $official_id ? 'update' : 'add' ?>">
                <input type="hidden" name="official_id" value="<?= $official_id ?? '' ?>">
                <div class="card mb-4">
                    <div class="card-header fw-bold"><i class="add-resident-subheading-icon material-symbols-outlined md-18 text-dark">Info</i> Add New Barangay Official</div>
                    <div class="card-body row g-3">
                        <div class="col-md-4"><input type="text" value="<?= $official_first_name ?? '' ?>" name="official_first_name" class="form-control" placeholder="First name" required>
                        </div>
                        <div class="col-md-4"><input type="text" value="<?= $official_middle_name ?? '' ?>" name="official_middle_name" class="form-control" placeholder="Middle name"></div>
                        <div class="col-md-4"><input type="text" value="<?= $official_last_name ?? '' ?>" name="official_last_name" class="form-control" placeholder="Last name" required>
                        </div>
                    </div>
                    <div class="card-body row g-3">
                        <div class="col-md-4">
                            <input type="text" value="<?= $official_suffix ?? '' ?>" name="official_suffix" class="form-control" placeholder="Suffix">
                            <div id="positionHelpBlock" class="form-text">
                               Example: Jr., II, III, etc. This is optional.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <input type="text" value="<?= $official_position ?? '' ?>" name="official_position" class="form-control" placeholder="Position">
                            <div id="positionHelpBlock" class="form-text">
                               Input the official designation (example: Barangay Captain, Barangay Kagawad, etc.). This is required.
                            </div>
                        </div>
                        <div class="col-md-4"> 
                            <input type="number" step="1" min="0" value="<?= $official_order ?? '' ?>" name="official_order" class="form-control" placeholder="Order" required>
                            <div id="orderHelpBlock" class="form-text">
                                Your order must be a positive integer. This is for ordering the officials by their rank with 1 reserved for the barangay captain.
                            </div>
                        </div>
                        
                    </div>
                    <div class="card-body row g-3">
                        <div class="col-md-8">
                            <button type="submit" class="btn btn-primary"><?= isset($official_id) ? 'Update' : 'Add' ?> Official</button>
                        </div>
                    </div>
                </div>
            </form>
              <!-- Table -->
            <div class="table-responsive">
            <table id="residents-table" class="table table-bordered align-middle">
                <thead class="table-light">
                <tr>
                    <th>Order #</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                    <th>Position</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                
                <?php

                    $stmt = $pdo->query("SELECT * FROM barangay_officials ORDER BY order_no ASC");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <tr>
                    <td><?php echo $row['order_no']; ?></td>
                    <td><?php echo $row['first_name']; ?></td>
                    <td><?php echo $row['middle_name']; ?></td>
                    <td><?php echo $row['last_name']; ?></td>
                    <td><?php echo $row['position']; ?></td>
                    <td class="text-center">
                        <a data-id="<?= $row['id'] ?>" href="?page=barangay-officials&action=edit&id=<?= $row['id'] ?>" class="btn btn-sm btn-warning text-white edit-btn" title="Edit"><i class="material-symbols-outlined md-18">edit</i></a>
                        <a data-id="<?= $row['id'] ?>" class="btn btn-sm btn-danger delete-btn"><i class="material-symbols-outlined md-18">delete</i></a>
                    </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            </div>
          </section>
      </div>
  </div>       


  <!-- Success Modal -->
<div class="modal fade" id="submissionModal" tabindex="-1" aria-labelledby="submissionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title text-light" id="submissionModalLabel">Success!</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div id="message" class="modal-body">
        The official has been updated successfully.
      </div>
    </div>
  </div>
</div>

<?php if (isset($_GET['updated'])): ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {

    const messageDiv = document.getElementById('message');

    // 2. Set the content (you can use Bootstrap alert styles if you like)
    messageDiv.innerHTML = `
      <div class="alert alert-success" role="alert">
        Official information updated successfully!
      </div>`;
    const modal = new bootstrap.Modal(document.getElementById('submissionModal'));
    modal.show();
  });
</script>
<?php endif; ?>

<?php if (isset($_GET['success'])): ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {

    const messageDiv = document.getElementById('message');

    // 2. Set the content (you can use Bootstrap alert styles if you like)
    messageDiv.innerHTML = `
      <div class="alert alert-success" role="alert">
        Official has been added successfully!
      </div>`;
    const modal = new bootstrap.Modal(document.getElementById('submissionModal'));
    modal.show();
  });
</script>
<?php endif; ?>