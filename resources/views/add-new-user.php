<main id="residents-dashboard" class="col-md-10 ms-sm-auto px-md-4 py-4">
  <div class="px-3 py-5">
      <div class="row mb-3">
          <h1 class="m-0">Add New User</h1>
          <hr>
      </div>
      <div class="row mx-0">
          <section class="inner-content col-md-12p-0 bg-transparent rounded" style="overflow: hidden;">
            <form method="POST" class="needs-validation" id="update-user-form" class="px-3 py-4" novalidate action="<?= ACTIONS_URL ?>add-new-user.php" enctype="multipart/form-data">
                <div class="card mb-4">
                    <input type="hidden" name="user_id" value="<?= $user_id ?? '' ?>">
                    <div class="card-header fw-bold"><i class="add-resident-subheading-icon material-symbols-outlined md-18 text-dark">Info</i> Add Account Info</div>
                    <div class="card-body row g-3">
                        <div class="col-md-5">
                            <label for="username" class="form-label">Username</label>
                            <input id="username" type="text" name="username" class="form-control" placeholder="Username" required>
                        </div>
                        <div class="col-md-7">
                            <label for="fullname" class="form-label">Full Name</label>
                            <input type="text" id="fullname" name="full_name" class="form-control" placeholder="Full Name" required>
                        </div>

                    </div>
                    <div class="card-body row g-3">
                        <div class="col-md-5">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="Email" required>
                        </div>
                        <div class="col-md-5">
                            <label for="role" class="form-label">Role</label>
                            <select name="role" class="form-select form-control" required>
                                <option value="" disabled selected>Select Role</option>
                                <option value="admin">Admin</option>
                                <option value="secretary">Secretary</option>
                                <option value="user">User</option>
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
                            <button type="submit" class="btn btn-primary">Add New User</button>
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