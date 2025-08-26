<?php
  require '../config/database.php';

  require_once '../src/helpers/utilities.php';
  requireRoles(['admin']);

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
          <h1 class="m-0">Manage Users</h1>
          <hr>
      </div>
      <div class="row mx-0">
          <section class="inner-content col-md-12 p-0 bg-transparent rounded" style="overflow: hidden;">
            <div class="row d-flex align-items-center">
                <div class="col-md-9 mb-3">
                    <h3 class="mb-4"></h3>
                    <p class="text-muted mb-3">Add and manage current users in this module.</p>
                </div>
                    <!-- Add New User Button -->
                <div class="col-md-3 mb-3">
                    <div class="text-end">
                        <a href="?page=add-new-user" class="btn btn-primary dashboard-btn-function"><i class="material-symbols-outlined md-24 text-light">note_add</i>Add User</a>
                    </div>
                </div>
            </div>           
            <!-- Table -->
            <div class="table-responsive">
            <table id="residents-table" class="table table-bordered align-middle">
                <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Full Name</th>
                    <th>Role</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                
                <?php

                    $stmt = $pdo->query("SELECT * FROM users ORDER BY id ASC");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['full_name']; ?></td>
                    <td><?php echo $row['role']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td class="text-center">
                        <a data-id="<?= $row['id'] ?>" href="?page=edit-user&user_id=<?= $row['id'] ?>" class="btn btn-sm btn-warning text-white edit-btn" title="Edit"><i class="material-symbols-outlined md-18">edit</i></a>
                        <a data-user-id="<?= $row['id'] ?>" class="btn btn-sm btn-danger user-delete-btn"><i class="material-symbols-outlined md-18">delete</i></a>
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
        User has been updated successfully.
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
        New User has been added successfully!
      </div>`;
    const modal = new bootstrap.Modal(document.getElementById('submissionModal'));
    modal.show();
  });
</script>
<?php endif; ?>

<?php if (isset($_GET['updated'])): ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {

    const messageDiv = document.getElementById('message');

    // 2. Set the content (you can use Bootstrap alert styles if you like)
    messageDiv.innerHTML = `
      <div class="alert alert-success" role="alert">
        User has been updated successfully!
      </div>`;
    const modal = new bootstrap.Modal(document.getElementById('submissionModal'));
    modal.show();
  });
</script>
<?php endif; ?>
