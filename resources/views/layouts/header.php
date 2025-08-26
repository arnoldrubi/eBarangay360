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