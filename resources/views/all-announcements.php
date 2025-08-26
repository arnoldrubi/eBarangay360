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
    <h2 class="m-0">All Announcements</h2>
    <hr>
  </div>
  <section class="inner-content">
    <div class="container-fluid p-3">

        <?php
            $stmt = $pdo->query("
            SELECT * from announcements WHERE is_deleted  = 0 AND is_pinned = 1 ORDER BY post_date DESC
            ");

            $announcements = $stmt->fetchAll();

            foreach ($announcements as $announcement): ?>
            <div class="col-12 row mx-0 my-5 rounded">
                <div class="col-12 col-md-8 bg-white p-0 position-relative big-news-and-updates-image" style="overflow: hidden; background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url(&quot;http://localhost/bms/assets/media/announcement_images/children-characters-cleaning-garden-garbage-600nw-2056855682.png&quot;); height: 402.615px;" id="big-news-and-updates">
                    <figure class="image pinned-news-body-image">
                        <!-- <img style="aspect-ratio: 600 / 210; max-height: 402.615px;" src="uploads/news_banners/<?= htmlspecialchars($announcement['banner_filename']) ?>" width="600" height="210" class="img-fluid"> -->
                    </figure>
                </div>
                <div class="col-12 col-md-4 rounded-0 text-start position-relative" style="overflow-y: hidden; display: block; height: 402.615px;">
                <div class="p-2" id="big-news-and-updates-article">
                <div class=" mb-3">
                    <h2 class=" fw-bold text-start ff-noir text-gray-900" style="word-break:;"><?= htmlspecialchars($announcement['post_title']) ?></h2>
                </div>
                <div class="text-start text-gray-900 d-none d-md-block small opacity-75">
                    <b> Post Author: </b><?= htmlspecialchars($announcement['post_author']) ?>
                </div>
                <div class="text-start text-gray-600 d-none d-md-block small opacity-75">
                    <i class="material-symbols-outlined md-18 text-secondary">nest_clock_farsight_analog</i>  <?= htmlspecialchars($announcement['post_date']) ?>
                </div>
                <hr>
                <div id="pinned-news-body" class="py-2" style="">
                    <div>
                        <?= htmlspecialchars($announcement['post_body']) ?>
                    </div>
                </div>
                </div>
                <div class="position-absolute bottom-0 start-0 bg-white w-100 text-center py-2" style="" id="big-news-and-updates-see-more">
                    <a href="?page=view-announcement&id=<?= htmlspecialchars($announcement['id']) ?>" class="d-block text-end pe-3 pb-1">Learn More</a>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="row gy-3">
        <?php
            $stmt = $pdo->query("
            SELECT * from announcements WHERE is_deleted  = 0 AND is_pinned = 0 ORDER BY post_date DESC
            ");

            $announcements = $stmt->fetchAll();

            foreach ($announcements as $announcement): ?>
   
        
            <div class="col-4">
                <div class="card border-0 rounded-0 h-100 d-flex flex-column" style="min-height:250px;">
                <div class="post-body flex-grow-1 overflow-hidden" style="min-height:150px;">
                    <?= htmlspecialchars($announcement['post_body']) ?>
                    <figure class="image">
                        <img src="uploads/news_banners/<?= htmlspecialchars($announcement['banner_filename']) ?>">
                    </figure>
                </div>
                <div class="card-body flex-shrink-1 d-flex flex-column justify-content-between">
                    <div class="">
                    <h5 class="card-title text-truncate-2 fw-semibold"><?= htmlspecialchars($announcement['post_title']) ?></h5>
                    <span>Author: <?= htmlspecialchars($announcement['post_author']) ?></span>
                    <br>
                    <small>
                        <i class="bi bi-clock"></i> <?= htmlspecialchars($announcement['post_date']) ?></small>
                    </div>
                    <a href="?page=view-announcement&id=<?= htmlspecialchars($announcement['id']) ?>" class="d-block text-end">Learn more</a>
                </div>
                </div>
            </div>
        
        <?php endforeach; ?>
        </div>
        <div class="text-end">
            <a href="?page=dashboard" class="btn btn-secondary"><i class="material-symbols-outlined md-18 text-secondary">arrow_back</i>Back to Dashboard</a>
        </div>
    </div>
  </section>
</div>


