<!-- Sidebar -->
<nav id="sidebar" class="col-lg-2 d-none d-md-block sidebar py-3 px-2 d-flex flex-column flex-shrink-0">
  <div class="text-center text-white mb-4">
    <h5 id="side-bar-title">eBarangay360</h5>
    <p>Lalangan Plaridel, Bulacan</p>
    <hr class="bg-white">
    <p>Welcome <?= $_SESSION['username'] ?? 'User Default' ?>!</p>
  </div>
  <ul class="nav flex-column">
    <?php    
      if($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'secretary') {
      ?>
    <li class="nav-item mb-2"><a href="?page=dashboard" class="nav-link <?php echo $page === 'dashboard' ? 'active' : ''; ?>"><i class="material-symbols-outlined md-18">dashboard</i>Dashboard</a></li>
    <li class="nav-item mb-2"><a href="?page=announcements" class="nav-link <?php echo $page === 'announcements' ? 'active' : ''; ?>"><i class="material-symbols-outlined md-18">campaign</i>Announcements</a></li>
    <li class="nav-item mb-2"><a href="?page=residents" class="nav-link <?php echo $page === 'residents' || $page === 'add-resident' ? 'active' : ''; ?>"><i class="material-symbols-outlined md-18">people</i>Resident Information</a></li>
    <li class="nav-item mb-2"><a href="?page=residents-registration" class="nav-link <?php echo $page === 'residents-registration' ? 'active' : ''; ?>"><i class="material-symbols-outlined md-18">people</i>Manage Resident Signups</a></li>
    <li class="nav-item mb-2"><a href="?page=households" class="nav-link <?php echo $page === 'households' || $page === 'add-household' || $page === 'add-household-members' ? 'active' : ''; ?>"><i class="material-symbols-outlined md-18">house</i>Households</a></li>
    <li class="nav-item mb-2"><a href="?page=blotter-reports" class="nav-link <?php echo $page === 'blotter-reports' || $page === 'add-new-blotter-report' || $page === 'edit-blotter-report'  || $page === 'view-blotter-report' ? 'active' : ''; ?>"><i class="material-symbols-outlined md-18">record_voice_over</i>Blotter/Incidents</a></li>
    <?php } ?>
        <li class="nav-item mb-2"><a href="?page=barangay-certificates" class="nav-link <?php echo $page === 'barangay-certificates' || $page === 'barangay-clearance' || $page === 'barangay-certificate-of-indigency' ? 'active' : ''; ?>"><i class="material-symbols-outlined md-18">approval</i>Certificates</a></li>
  </ul>

    <?php    
      if($_SESSION['role'] === 'admin') {
      ?>
        <hr class="text-light">
  
        <p class="text-light text-center">Site Admin</p>
        <ul class="nav flex-column">
          <li class="nav-item mb-2"><a href="?page=barangay-officials" class="nav-link <?php echo $page === 'barangay-officials' ? 'active' : ''; ?>"><i class="material-symbols-outlined md-18">gavel</i>Barangay Officials</a></li>
          <!-- <li class="nav-item mb-2"><a href="?page=site-settings" class="nav-link"><i class="material-symbols-outlined md-18">settings</i>Site Settings</a></li> -->
          <li class="nav-item mb-2"><a href="?page=manage-users" class="nav-link <?php echo $page === 'manage-users' || $page === 'add-new-user' ? 'active' : ''; ?>"><i class="material-symbols-outlined md-18">identity_platform</i>Manage Users</a></li>
        </ul>
      <?php
      }?>
  </ul>
</nav>

