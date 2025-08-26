<?php
  require_once '../src/helpers/utilities.php';
  requireRoles(['admin', 'secretary']);
?>
<main class="col-md-10 ms-sm-auto px-md-4 py-4">

<div class="px-3 py-5">
  <div class="row mb-3">
    <h2 class="m-0">Add New Announcement</h2>
    <hr>
  </div>
  <section class="inner-content">
    <div class="container-fluid p-3">
        <form method="POST" class="needs-validation" id="announcement-form" class="px-3 py-4" novalidate action="<?= ACTIONS_URL ?>add-announcement.php" enctype="multipart/form-data">
            <div class="">
                <div class="form-floating mb-2">
                    <input type="text" class="form-control border-info fw-bold text-dark fs-4" required id="post-title" name="post_title" placeholder="Post Title" value=""/>
                    <label for="post-title" class="fw-bold text-info"> Post Title </label>
                </div>
                <div class="row m-0">
                    <div class="col-10 col-md-5 p-0">
                        <div class="input-group input-group-sm w-100">
                            <span class="input-group-text border-info text-info">Author:</span>
                            <input type="text" class="form-control border-info" required placeholder="Author name" aria-label="Author" aria-describedby="basic-addon1" name="post_author" value=""/>
                        </div>
                    </div>
                </div>
                <hr>
                <textarea class="" name="post_body" id="post-body" placeholder="Type the content here!">

                </textarea>
                <input hidden type="checkbox" name="status" id="status" checked>
                <div class="row my-3">
                    <div class="col-md-6">
                        <input type="file" name="banner_filename" accept="image/*" class="form-control">
                        <small class="text-muted">Upload featured photo.</small>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between mt-3">
                <a href="?page=announcements" class="btn btn-secondary my-6 mx-2 d-flex align-items-center">Cancel</a>
                <div class="d-flex">
                    <button type="submit" name="action" value="draft" id="draft-post" class="btn btn-primary my-6 mx-2 d-flex align-items-center"><i class="material-symbols-outlined md-18 text-light">save</i> Save Draft</button>
                    <button type="submit" name="action" value="post" id="upload-post" class="btn btn-primary my-6 mx-2 d-flex align-items-center"><i class="material-symbols-outlined md-18 text-light">post</i> Post Announcement</button>
                </div>
            </div>
      </form>
    </div>
  </section>
</div>


