<?php
  session_start();
  if (!isset($_SESSION['user_id'])) {
      header("Location: index.php");
      exit;
  }

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - eBarangay360</title>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans&family=Roboto+Slab:wght@600&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
  <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link href="css/custom.css" rel="stylesheet">
  <link href="css/print.css" rel="stylesheet">
</head>
<body id="dashboard">
  <div class="container-fluid">
    <div class="row">
      <header id="header" class="bg-primary text-light col-12 d-flex justify-content-between align-items-center px-4 py-2">
        <div class="d-flex align-items-center">
          <h4 class="m-0 text-light">Barangay Information and Management System</h4>
        </div>
       
        <div class="d-flex align-items-center">
          <div class="dropdown">
            <button style="background: transparent; color: #fff !important; position: relative; top: 11px;" class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
             <span class="material-symbols-outlined md-24 me-2">account_circle</span>
             <span class="me-3 text-light"><?= $_SESSION['username'] ?? 'User Default' ?></span><br>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
              <li><a href="?page=manage-account" class="dropdown-item">Manage My Account</a></li>
              <li><a href="?page=logout" class="dropdown-item">Logout</a></li>
            </ul>
          </div>
          
        </div>
      </header>
    </div>
    <div class="row">