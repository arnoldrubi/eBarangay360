<?php
  require '../config/database.php';

  //preload user info
  $user_id = $_SESSION['user_id'] ?? null;
  $user_role = $_SESSION['role'] ?? null;

  if(isset($user_id)) {
     $stmt = $pdo->prepare("
       SELECT * FROM users WHERE id = :id LIMIT 1
     ");
     $stmt->execute([':id' => $user_id]);
     $user_info = $stmt->fetch(PDO::FETCH_ASSOC);

     if ($user_info) {
        $username = $user_info['username'];
        $email = $user_info['email'];
        $full_name = $user_info['full_name'];
        $role = $user_info['role'];
     } else {
        $request_failed = true;
     }
  }

?>

<main id="residents-dashboard" class="col-md-10 ms-sm-auto px-md-4 py-4">
  <div class="px-3 py-5">
      <div class="row mb-3">
          <h1 class="m-0">Manage Account</h1>
          <hr>
      </div>
      <div class="row mx-0">
          <section class="col-md-3">
              <div class="sticky-top py-1">
                  <div class="bg-dark text-white rounded">
                      <nav class="nav flex-column nav-pills py-3">
                          <p class="text-uppercase text-truncate ps-3">Current Role: <?= ucfirst($role) ?? 'User' ?></p>
                          <hr class="bg-white">
                          <p class="p-3"><?= $role === 'admin' ? 'Full control over all modules' : ($role === 'secretary' ? 'Can manage all module except for users and site settings' : 'Regular users can access limited features.') ?></p>
                      </nav>
                  </div>
              </div>
          </section>

          <section class="inner-content col-md-9 p-0 bg-transparent rounded" style="overflow: hidden;">
            <form method="POST" class="needs-validation" id="update-user-form" class="px-3 py-4" novalidate action="<?= ACTIONS_URL ?>update-user.php" enctype="multipart/form-data">
                <div class="card mb-4">
                    <input type="hidden" name="user_id" value="<?= $user_id ?? '' ?>">
                    <div class="card-header fw-bold"><i class="add-resident-subheading-icon material-symbols-outlined md-18 text-dark">Info</i> Manage Account Info</div>
                    <div class="card-body row g-3">
                        <div class="col-md-5">
                            <label for="username" class="form-label">Username</label>
                            <input readonly id="username" type="text" value="<?= $username ?? '' ?>" name="username" class="form-control" placeholder="Username" required>
                        </div>
                        <div class="col-md-7">
                            <label for="fullname" class="form-label">Full Name</label>
                            <input type="text" id="fullname" value="<?= $full_name ?? '' ?>" name="full_name" class="form-control" placeholder="Full Name" required>
                        </div>

                    </div>
                    <div class="card-body row g-3">
                        <div class="col-md-5">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" readonly id="email" value="<?= $email ?? '' ?>" name="email" class="form-control" placeholder="Email" required>
                        </div>
                        <div class="col-md-5">
                            <label for="role" class="form-label">Role</label>
                            <input type="text" readonly disabled id="role" value="<?= $role ?? '' ?>" name="role" class="form-control" placeholder="Role" required>
                        </div>                       
                    </div>
                    <div class="card-body row g-3">
                        <div class="col-md-12">
                            <h3>Change Password</h3>   
                            <hr>
                        </div>
                        <div class="col-md-6">             
                            <input type="password" id="password" value="" name="password" class="form-control" placeholder="Password">
                            <div id="positionHelpBlock" class="form-text">
                               Both passwords must match
                            </div>
                        </div>
                    </div>
                    <div class="card-body row g-3">
                        <div class="col-md-6"> 
                            <input type="password" id="repeat-password" value="" name="repeat_password" class="form-control" placeholder="Repeat Password">
                            <div id="passwordHelp" class="form-text text-danger d-none">Passwords do not match</div>
                        </div>
                        
                    </div>
                    <div class="card-body row g-3">
                        <div class="col-md-8">
                            <button type="submit" class="btn btn-primary">Update Account</button>
                        </div>
                    </div>
                </div>
            </form>
          </section>
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
        User Account Updated Successfully.
      </div>
    </div>
  </div>
</div>


  <script>
    document.getElementById('update-user-form').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('repeat-password').value;
    const passwordHelp = document.getElementById('passwordHelp');

    if (password !== confirmPassword) {
        e.preventDefault(); // Stop form submission
        passwordHelp.classList.remove('d-none');
    } else {
        passwordHelp.classList.add('d-none');
    }
    });
</script>

<?php if (isset($_GET['success'])): ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {

    const messageDiv = document.getElementById('message');

    // 2. Set the content (you can use Bootstrap alert styles if you like)
    messageDiv.innerHTML = `
      <div class="alert alert-success" role="alert">
       Your account has been updated successfully!
      </div>`;

    const modal = new bootstrap.Modal(document.getElementById('submissionModal'));
    modal.show();
  });
</script>
<?php endif; ?>