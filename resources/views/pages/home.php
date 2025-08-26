<body class="d-flex justify-content-center align-items-center" id="login-page" style="
    background-image: url('<?=base_url()?>assets/media/bocaue-pagoda-<?=rand(0, 4)?>.jpg');
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
    backdrop-filter: grayscale(1);
    min-height: 100vh;
  ">
  <!-- Login Form -->
  <div class="card container rounded-4 shadow" >
    <div class="card-body p-0">
      <div class="row" style="min-height: 90vh;">
        <div class="col-12 col-md-8 rounded-start bg-primary row m-0">
          <div class="col-4 col-md-6 offset-0 offset-md-3 d-flex">
            <img src="assets/media/baliwag-logo-dark.png" class="img-fluid my-auto" style="" alt="">
          </div>
          <div class="col-8 col-md-12 text-center">
            <h1 class="fw-bold text-white">Barangay Management System</h1>
            <?php
              // Create connection
              $conn = new mysqli($servername, $username, $password, $dbname);
              // Check connection
              if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);
              }


              $sql = "SELECT * FROM `site_settings`";
              $result = $conn->query($sql);


              while($row = $result->fetch_assoc()) {

                $brgy = strtolower($row["barangay"]);
                $city_municipality = strtolower($row["city_municipality"]);
                $province = strtolower($row["province"]);
              }
              $conn->close();
            ?>

            <h3 class=" text-white"><?php echo ucwords($brgy)." ".ucwords($city_municipality). ", " .ucwords($province); ?></h3>
          </div>
        </div>

        <div class="col-12 col-md-4 border-start p-5 d-flex align-items-center">
          <div class="">
            <div class="mt-1 mb-2 text-center">
              <div class="w-25 p-1 mx-auto bg-secondary bg-opacity-10 rounded-circle">
                <img src="<?=base_url("assets/media/logo.png")?>" class="img-fluid">
              </div>
            </div>
            <h2 class="text-center">Welcome!</h2>
            <small class="text-muted d-block text-center" style="font-size: 9pt;">This system aims to improve the efficiency and effectiveness of barangay officials in managing various tasks and responsibilities, including maintaining records, handling transactions, and providing services to the community. <span class="text-danger text-opacity-25">♥</span></small>
            <div class="d-flex align-items-center my-4 text-muted">
              <hr class="w-100">
              <small class="card-title text-center flex-grow-1 text-nowrap mx-2">Log In</small>        
              <hr class="w-100">
            </div>
            <form id="login-form" method="POST" action="actions/login.php">
                <div class="input-group mb-3">
                  <input type="text" class="form-control border-end-0" id="username" name="username" required placeholder="Email"/>
                  <span class="input-group-text fw-bold bg-white text-primary text-opacity-75">@</span>
                </div>
                <div class="mb-1">
                  <div class="input-group">
                    <input type="password" class="form-control border-end-0" id="password" name="password" required placeholder="Password"/>
                    <span class="input-group-text fw-bold bg-white text-primary text-opacity-75"><i class="bi bi-unlock-fill"></i></span>
                  </div>
                </div>
                <div style="visibility: hidden;" id="login-form-error-handler" class="alert alert-danger p-1 text-center alert-dismissible fade show" role="alert">
                  <small>
                  <i class="bi bi-exclamation-triangle-fill"></i>
                    Incorrect credentials
                  </small>
                </div>
                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" onclick="checkboxTrue()">
                    <label class="text-left form-check-label small" for="flexCheckDefault">
                      I agree to BMS' <a href="privacy-policy.php" target="_blank">Privacy Policy</a> and <a href="term-of-use.php" target="_blank">Terms of Use</a>.
                    </label>
                </div>             
                
                <div class="text-center px-1">
                  <button id="login-button" disabled type="submit" class="btn btn-outline-primary px-5 w-100">Login</button>
                </div>
                <div class="row mt-4">
                  <div class="col-6"><small class="d-block text-left"><a href="<?=base_url()?>forgot-password.php" class="text-primary">Forgot password?</a></small></div>
                  <div class="col-6"><small class="d-block text-end"><a href="<?=base_url()?>public-registration.php" class="text-primary">Sign Up</a></small></div>
                </div>  
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
