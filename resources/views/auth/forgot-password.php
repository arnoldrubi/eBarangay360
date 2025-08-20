<body id="login-page" style="">
  <div id="login-container" class="d-flex justify-content-center align-items-center">
    <div class="card container rounded-4 shadow">
      <div class="card-body p-0">
        <div class="row" style="min-height: 90vh;">
          <div class="col-12 col-md-8 rounded-start bg-primary row m-0 splash-screen-left">
            <div class="col-4 col-md-6 offset-0 offset-md-3 d-flex text-center justify-content-center align-items-center">
              <img style="max-width: 220px" src="css/image-assets/plaridel-logo.png" class="img-fluid my-auto" style="" alt="">
            </div>
            <div class="col-8 col-md-12 mt-0 text-center">
              <h1 class="fw-bold text-white">eBarangay360</h1>
              <p class="text-white">Barangay Information Management System</p>
              <h3 class=" text-white">Lalangan, Plaridel Bulacan</h3>
            </div>
          </div>

          <div class="col-12 col-md-4 border-start p-5 d-flex align-items-center">
            <div class="">
              <div class="mt-1 mb-2 text-center">
                <!-- <div class="w-25 p-1 mx-auto bg-secondary bg-opacity-10 rounded-circle">
                  <img src="" class="img-fluid">
                </div> -->
              </div>
              <h2 class="text-center">Forgot Password</h2>
              <div class="d-flex align-items-center my-4 text-muted">
                <hr class="w-100">
                <small class="card-title text-center flex-grow-1 text-nowrap mx-2">Add Registered Email</small>        
                <hr class="w-100">
              </div>
              <form id="forgot-password-form">
                  <div class="input-group mb-3">
                    <input type="text" class="form-control border-end-0" id="email" name="email" required placeholder="Email or Username"/>
                    <span class="input-group-text fw-bold bg-white text-primary text-opacity-75">@</span>
                    <small class="text-muted d-block text-center" style="font-size: 9pt;">Please contact our system admin if you forgot your registered email.</small>

                  </div>
                  <div style="visibility: hidden;" id="forgot-password-form-error-handler" class="alert alert-danger p-1 text-center alert-dismissible fade show" role="alert">
                    <small>
                    <i class="bi bi-exclamation-triangle-fill"></i>
                      Incorrect credentials
                    </small>
                  </div>     
                  <div class="text-center px-1">
                    <button id="forgot-password-button" type="submit" class="btn btn-outline-primary px-5 w-100">Send Reset Link</button>
                  </div>
                  <div class="row mt-4">
                    <div class="col-6"><small class="d-block text-left"><a href="index.php" class="text-primary">Return to Login</a></small></div>
                    <div class="col-6"><small class="d-block text-end"><a href="?page=sign-up" class="text-primary">Sign Up</a></small></div>
                  </div>  
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Forgot Password Form -->



