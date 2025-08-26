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
        <?php
            if($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'secretary') {
        ?>
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
        <?php
            }
        ?>

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
                
                  <li><a href="?page=view-announcement&id=<?= $announcement['id'] ?>"><?= htmlspecialchars($announcement['post_title']) ?></a></li>

            <?php endforeach; ?>
                
              </div>
              <div class="card-footer">
                <a href="?page=all-announcements">Go to News and Announcements</a>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="card mb-4">
              <div class="card-header">
                <h6><i class="material-symbols-outlined md-24 text-secondary">map</i>  Location Map</h6>
              </div>
              <div class="card-body">
                <div class="bg-light text-center p-0 border rounded">
                  <iframe style="width: 509px; height: 553px;" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15424.332094537385!2d120.8410616996022!3d14.876635270287117!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3396538062aa82cd%3A0x1d243f4ee1031180!2sLalangan%2C%20Plaridel%2C%20Bulacan!5e0!3m2!1sen!2sph!4v1756043990035!5m2!1sen!2sph" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
              </div>
            </div>
          </div>
        </div>

