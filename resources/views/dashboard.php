<?php
    require '../config/database.php';

  // load all stats from different tables
  $stmt = $pdo->query("SELECT COUNT(*) FROM residents");
  $totalResidents = $stmt->fetchColumn();

  $stmt = $pdo->query("SELECT COUNT(*) FROM households");
  $totalHouseholds = $stmt->fetchColumn();

  $stmt = $pdo->query("SELECT COUNT(*) FROM blotter_reports");
  $totalBlotterRecords = $stmt->fetchColumn();

?>
     <!-- Main Content -->
      <main class="col-md-10 ms-sm-auto px-md-4 py-4">
        <h2>Dashboard</h2>

        <div class="row mb-4">
          <div class="col-md-4">
            <div class="card bg-primary card-stats">
              <div class="card-body">
                <h5 class="text-light"><i class="material-symbols-outlined md-36 text-light">groups_3</i> <?= $totalResidents ?> Total Residents</h5>
                <a href="?page=residents" class="btn btn-light btn-sm">Go to Residents Module</a>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card bg-success card-stats">
              <div class="card-body">
                <h5 class="text-light"><i class="material-symbols-outlined md-36 text-light">holiday_village</i> <?= $totalHouseholds ?> Households</h5>
                <a href="?page=households" class="btn btn-light btn-sm">Go to Households Module</a>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card bg-danger card-stats">
              <div class="card-body">
                <h5 class="text-light"><i class="material-symbols-outlined md-36 text-light">note_stack</i> <?= $totalBlotterRecords ?> Blotter Records Filed</h5>
                <a href="?page=blotter-reports" class="btn btn-light btn-sm">Go to Blotters Module</a>
              </div>
            </div>
          </div>
        </div>

        <div class="card mb-4">
          <div class="card-header">
            <h5><i class="material-symbols-outlined md-24 text-secondary">sticky_note</i> Pinned Announcements</h5>
          </div>
            <?php
              $stmt = $pdo->query("
                SELECT * from announcements WHERE is_deleted  = 0 AND is_pinned = 1 ORDER BY post_date DESC
                ");

                $announcements = $stmt->fetchAll();

                foreach ($announcements as $announcement): ?>
                <div class="card-body">
                  <h4><?= htmlspecialchars($announcement['post_title']) ?></h4>
                  <p><small>Post Author: <?= htmlspecialchars($announcement['post_author']) ?> | <?= htmlspecialchars($announcement['post_date']) ?></small></p>
                  <img alt="<?= htmlspecialchars($announcement['post_title']) ?> Photo" src="uploads/news_banners/<?= htmlspecialchars($announcement['banner_filename']) ?>" class="img-fluid border-danger">

                  <p><?= htmlspecialchars($announcement['post_body']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="card mb-4">
              <div class="card-header">
                <h6><i class="material-symbols-outlined md-12 text-secondary">pinboard_unread</i>  Other Announcements</h6>
              </div>
              <div class="card-body">
              <?php
              $stmt = $pdo->query("
                SELECT * from announcements WHERE is_deleted  = 0 AND is_pinned = 0 ORDER BY post_date DESC
                ");

                $announcements = $stmt->fetchAll();

                foreach ($announcements as $announcement): ?>
                <div class="card-body">
                  <li><?= htmlspecialchars($announcement['post_title']) ?></li>
                </div>
            <?php endforeach; ?>
                <a href="#">Go to News and Announcements</a>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="card mb-4">
              <div class="card-header">
                <h6><i class="material-symbols-outlined md-24 text-secondary">map</i>  Location Map</h6>
              </div>
              <div class="card-body">
                <div class="bg-light text-center p-5 border rounded">
                  <p>Map Placeholder</p>
                  <p class="text-muted">Google Maps integration here</p>
                </div>
              </div>
            </div>
          </div>
        </div>

