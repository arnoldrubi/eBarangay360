<?php
  require '../config/database.php';

  //preload user info
  $user_id = $_GET['user_id'] ?? null;

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

  require_once '../src/helpers/utilities.php';
  requireRoles(['admin']);


?>


<main id="residents-dashboard" class="col-md-10 ms-sm-auto px-md-4 py-4">
  <div class="px-3 py-5">
      <div class="row mb-3">
          <h1 class="m-0">Edit User <?= htmlspecialchars($full_name ?? '') ?></h1>
          <hr>
      </div>
      <div class="row mx-0">
          <section class="inner-content col-md-12p-0 bg-transparent rounded" style="overflow: hidden;">
            <form method="POST" class="needs-validation" id="update-user-form" class="px-3 py-4" novalidate action="<?= ACTIONS_URL ?>edit-user.php" enctype="multipart/form-data">
                <div class="card mb-4">
                    <input type="hidden" name="user_id" value="<?= $user_id ?? '' ?>">
                    <div class="card-header fw-bold"><i class="add-resident-subheading-icon material-symbols-outlined md-18 text-dark">Info</i> Edit Account Info</div>
                    <div class="card-body row g-3">
                        <div class="col-md-5">
                            <label for="username" class="form-label">Username</label>
                            <input readonly id="username" type="text" name="username" class="form-control" placeholder="Username" value = "<?= htmlspecialchars($username ?? '') ?>">
                        </div>
                        <div class="col-md-7">
                            <label for="fullname" class="form-label">Full Name</label>
                            <input type="text" id="fullname" name="full_name" class="form-control" placeholder="Full Name" value="<?= htmlspecialchars($full_name ?? '') ?>" required>
                        </div>

                    </div>
                    <div class="card-body row g-3">
                        <div class="col-md-5">
                            <label for="email" class="form-label">Email</label>
                            <input value="<?= htmlspecialchars($email ?? '') ?>" type="email" id="email" name="email" class="form-control" placeholder="Email" required>
                        </div>
                        <div class="col-md-5">
                            <label for="role" class="form-label">Role</label>
                            <select name="role" class="form-select form-control" required>
                                <option value="" disabled selected>Select Role</option>
                                <option value="Admin" <?= (isset($role) && $role === 'admin') ? 'selected' : '' ?>>Admin</option>
                                <option value="Secretary" <?= (isset($role) && $role === 'secretary') ? 'selected' : '' ?>>Secretary</option>
                                <option value="User" <?= (isset($role) && $role === 'user') ? 'selected' : '' ?>>User</option>
                            </select>
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
                            <button type="submit" class="btn btn-primary">Update User</button>
                        </div>
                    </div>
                </div>
            </form>
          </section>
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