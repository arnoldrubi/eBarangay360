<?php
    require '../config/database.php';
  
    $announcementId = isset($_GET['id']) ? $_GET['id'] : null;

    if ($announcementId) {
        // Fetch announcement details from the database
        $stmt = $pdo->prepare("SELECT * FROM announcements WHERE id = :id");
        $stmt->execute(['id' => $announcementId]);
        $announcement = $stmt->fetch();

        $post_title = $announcement['post_title'];
        $post_author = $announcement['post_author'];
        $post_body = $announcement['post_body'];
        $banner_filename = $announcement['banner_filename'];
    }
    else{
        // Handle case where announcement is not found
        $post_title = '';
        $post_author = '';
        $post_body = '';
        $banner_filename = '';
        }

?>
<main class="col-md-10 ms-sm-auto px-md-4 py-4">

<div class="px-3 py-5">
  <div class="row mb-3">
    <h2 class="m-0">View Announcement Details</h2>
    <hr>
  </div>
  <section class="inner-content">
    <div class="container-fluid p-3">
   
        <div class="card mb-4">
            <div class="card-body">
            <h4><?= htmlspecialchars($post_title) ?></h4>
            <p><small>Post Author: <?= htmlspecialchars($post_author) ?> | <?= htmlspecialchars($announcement['post_date']) ?></small></p>
            <img alt="<?= htmlspecialchars($post_title) ?> Photo" src="uploads/news_banners/<?= htmlspecialchars($banner_filename) ?>" class="img-fluid border-danger mb-3">
    
            <p><?= nl2br(htmlspecialchars($post_body)) ?></p>
            </div>
        </div>
    
        <div class="text-end">
            <a href="?page=all-announcements" class="btn btn-secondary"><i class="material-symbols-outlined md-18 text-secondary">arrow_back</i>Back to Announcements List</a>
        </div>
    </div>
  </section>
</div>


