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
              <h2 class="text-center">Welcome!</h2>
              <small class="text-muted d-block text-center" style="font-size: 9pt;">
                This system helps barangay officials efficiently and effectively manage their tasks and responsibilities. It allows them to better maintain records, handle transactions, and provide services to their community.
                <span class="text-danger text-opacity-25">♥</span>
              </small>
              <div class="d-flex align-items-center my-4 text-muted">
                <hr class="w-100">
                <small class="card-title text-center flex-grow-1 text-nowrap mx-2">Log In</small>        
                <hr class="w-100">
              </div>
              <form id="login-form">
                  <div class="input-group mb-3">
                    <input type="text" class="form-control border-end-0" id="email" name="email" required placeholder="Email or Username"/>
                    <span class="input-group-text fw-bold bg-white text-primary text-opacity-75"><i class="material-symbols-outlined md-24 text-primary">alternate_email</i></span>
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
                        I agree to eBarangay360' <a href="#" data-bs-toggle="modal" data-bs-target="#privacy-policy-modal">Privacy Policy</a> and <a href="#" data-bs-toggle="modal" data-bs-target="#tos-modal">Terms of Use</a>.
                      </label>
                  </div>             
                  
                  <div class="text-center px-1">
                    <button id="login-button" disabled type="submit" class="btn btn-outline-primary px-5 w-100">Login</button>
                  </div>
                  <div class="row mt-4">
                    <div class="col-6"><small class="d-block text-left"><a href="?page=forgot-password" class="text-primary">Forgot password?</a></small></div>
                    <div class="col-6"><small class="d-block text-end"><a href="?page=sign-up" class="text-primary">Sign Up</a></small></div>
                  </div>  
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Login Form -->

  <!-- Modal for Term of Use -->
<div class="modal fade" id="tos-modal" tabindex="-1" aria-labelledby="tosModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Terms of Use</h5>
        <button type="button" class="close btn" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="md-col-12">
            <p>Welcome to eBarangay360. These Terms of Use govern your use of our system. By accessing or using eBarangay360, you agree to be bound by these terms.</p>

            <h4>1. Use of System</h4>
            <p>This system is provided for official barangay transactions and community management purposes only. Unauthorized use is strictly prohibited.</p>

            <h4>2. User Accounts</h4>
            <p>You are responsible for maintaining the confidentiality of your account and password. You agree to accept responsibility for all activities that occur under your account.</p>

            <h4>3. Data Accuracy</h4>
            <p>You are responsible for providing accurate, current, and complete information during registration and use of the system.</p>

            <h4>4. Restrictions</h4>
            <p>You may not misuse the system by introducing malicious code, attempting unauthorized access, or engaging in any activity that disrupts the system's functionality.</p>

            <h4>5. Changes to the Terms</h4>
            <p>We reserve the right to modify these terms at any time. Continued use of the system constitutes your acceptance of any changes.</p>

            <h4>6. Termination</h4>
            <p>We may suspend or terminate your access if you violate these terms or engage in unauthorized use of the system.</p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

  <!-- Modal for Privacy Policy -->
<div class="modal fade" id="privacy-policy-modal" tabindex="-1" aria-labelledby="privacyPolicyModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Privacy Policy</h5>
        <button type="button" class="close btn" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>This Privacy Policy explains how we collect, use, and protect your personal information when you use eBarangay360.</p>        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla facilisi. Sed at purus non mauris dictum efficitur. Cras vehicula bibendum nisl, vel ullamcorper libero. Suspendisse potenti.</p>

        <h4>1. Information We Collect</h4>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur euismod, massa eget consequat facilisis, velit magna malesuada sapien, nec accumsan risus turpis non orci.</p>

        <h4>2. How We Use Your Information</h4>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin ac arcu ac metus congue fermentum. Fusce imperdiet lacinia orci, nec sollicitudin nulla consequat in.</p>

        <h4>3. Data Security</h4>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam erat volutpat. Nam ut nibh id lacus tristique posuere at ac mauris.</p>

        <h4>4. Third-Party Disclosure</h4>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In a facilisis urna, sed aliquam enim. Duis blandit bibendum est.</p>

        <h4>5. Your Consent</h4>
        <p>By using our Barangay Management System (eBarangay360), you consent to our Privacy Policy.</p>

        <h4>6. Changes to This Policy</h4>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed imperdiet magna in nulla aliquam, et tincidunt lacus vestibulum.</p>
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


  <script>
    function checkboxTrue() {
  // Get the checkbox
  let checkBox = document.querySelector("#flexCheckDefault");
  // Get the output button
  let submitButton = document.querySelector("#login-button");

  // If the checkbox is checked, display the output text
  if (checkBox.checked == true){
    submitButton.disabled = false;
  } else {
    submitButton.disabled = true;
  }
} 
  </script>
