<?php
  require '../config/database.php';

?>

<main class="col-md-10 ms-sm-auto px-md-4 py-4">
  <div class="row mb-3">
    <h1 class="m-0">View and Manage Announcements</h1>
    <hr>
  </div>
  <div class="row mb-4">

  </div>
  
  <section class="inner-content">
    <div class="container-fluid p-3">
      <h3 class="mb-4">Announcements List</h3>
      <p class="text-muted mb-3">Manage and view announcements for the barangay. Barangay resolutions can be added also.</p>

      <!-- Add Announcement Button -->
      <div class="row mb-3">
        <div class="col-md-7 gap-2 mb-3 d-flex align-items-center">
          <div class="" style="">
              <select class="form-select" id="age-filter" aria-label="Filter by age">
                  <option value="" selected="">Filter Announcements</option>
                  <option value="seniors">Draft</option>
                  <option value="minors">Posted</option>
              </select>
          </div>
        </div>
        <div class="col-md-5 text-end">
          <a href="?page=add-announcement" class="btn btn-primary dashboard-btn-function"><i class="material-symbols-outlined md-24 text-light">note_add</i>Add Announcement</a>
        </div>
      </div>
      <!-- Table -->
      <div class="table-responsive">
        <table id="household-table" class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>Title</th>
              <th>Author</th>
              <th>Excerpt</th>
              <th>Status</th>
              <th>Post Date</th>
              <th>Pin Post</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $stmt = $pdo->query("
                SELECT * from announcements WHERE is_deleted  = 0
                ");

                $announcements = $stmt->fetchAll();

                foreach ($announcements as $announcement): ?>
              <tr>
                <td><?= htmlspecialchars($announcement['post_title']) ?></td>
                <td><?= htmlspecialchars($announcement['post_author']) ?></td>
                <td><?= htmlspecialchars($announcement['post_body']) ?></td>
                <td><?= ucfirst(htmlspecialchars($announcement['status'])) ?></td>
                <td><?= htmlspecialchars($announcement['post_date']) ?></td>
                <td class="text-center"><a data-id="<?= $announcement['id'] ?>" data-bs-toggle="modal" data-bs-target="" href="#" class="btn btn-sm pin-news <?= $announcement['is_pinned']  === 1 ? 'btn-success' : 'btn-warning' ?> text-white edit-announcement-btn" title="Pin Post"><i class="material-symbols-outlined md-18"><?= $announcement['is_pinned']  === 1 ? 'pinboard' : 'keep' ?></i></a></td>
                <td class="text-center">
                  <a data-id="<?= $announcement['id'] ?>" href="?page=edit-announcement&id=<?= $announcement['id'] ?>" class="btn btn-sm btn-warning text-white edit-household-btn" title="Edit"><i class="material-symbols-outlined md-18">edit</i></a>
                  <a data-id="<?= $announcement['id'] ?>" class="delete-news btn btn-sm btn-danger delete-btn"><i class="material-symbols-outlined md-18">delete</i></a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

  </section>



  <!-- Success Modal -->
<div class="modal fade" id="submissionModal" tabindex="-1" aria-labelledby="submissionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title text-light" id="submissionModalLabel">Success!</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div id="message" class="modal-body">
        The Announcement has been added successfully.
      </div>
    </div>
  </div>
</div>


</main>

<?php if (isset($_GET['success'])): ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {

    const messageDiv = document.getElementById('message');

    // 2. Set the content (you can use Bootstrap alert styles if you like)
    messageDiv.innerHTML = `
      <div class="alert alert-success" role="alert">
        New Announcement is saved successfully!
      </div>`;

    const modal = new bootstrap.Modal(document.getElementById('submissionModal'));
    modal.show();
  });
</script>
<?php endif; ?>

<?php if (isset($_GET['draftsuccess'])): ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {

    const messageDiv = document.getElementById('message');

    // 2. Set the content (you can use Bootstrap alert styles if you like)
    messageDiv.innerHTML = `
      <div class="alert alert-success" role="alert">
        Your announcement is now saved as draft!
      </div>`;

    const modal = new bootstrap.Modal(document.getElementById('submissionModal'));
    modal.show();
  });
</script>
<?php endif; ?>

<?php if (isset($_GET['postedsuccess'])): ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {

    const messageDiv = document.getElementById('message');

    // 2. Set the content (you can use Bootstrap alert styles if you like)
    messageDiv.innerHTML = `
      <div class="alert alert-success" role="alert">
        Your announcement is now posted!
      </div>`;

    const modal = new bootstrap.Modal(document.getElementById('submissionModal'));
    modal.show();
  });
</script>
<?php endif; ?>



<?php if (isset($_GET['deleted'])): ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {

    const messageDiv = document.getElementById('message');

    // 2. Set the content (you can use Bootstrap alert styles if you like)
    messageDiv.innerHTML = `
      <div class="alert alert-warning" role="alert">
        Household is deleted!
      </div>`;

    const modal = new bootstrap.Modal(document.getElementById('submissionModal'));
    modal.show();
  });
</script>
<?php endif; ?>

<?php if (isset($_GET['draftupdated'])): ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {

    const messageDiv = document.getElementById('message');

    // 2. Set the content (you can use Bootstrap alert styles if you like)
    messageDiv.innerHTML = `
      <div class="alert alert-warning" role="alert">
        Your announcement has been updated as a draft!
      </div>`;

    const modal = new bootstrap.Modal(document.getElementById('submissionModal'));
    modal.show();
  });
</script>
<?php endif; ?>

<?php if (isset($_GET['postedupdated'])): ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {

    const messageDiv = document.getElementById('message');

    // 2. Set the content (you can use Bootstrap alert styles if you like)
    messageDiv.innerHTML = `
      <div class="alert alert-warning" role="alert">
        Your announcement has been updated as a post!
      </div>`;

    const modal = new bootstrap.Modal(document.getElementById('submissionModal'));
    modal.show();
  });
</script>
<?php endif; ?>



<script>
document.querySelectorAll(".delete-news").forEach(btn => {
  btn.addEventListener("click", function () {
    if (confirm("Are you sure you want to delete this post?")) {
      const id = this.getAttribute("data-id");

      fetch("../src/actions/delete-news.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "id=" + id
      })
      .then(res => res.text())
      .then(data => {
        if (data.trim() === "success") {
          alert("Post deleted successfully.");
          location.reload();
        } else {
          alert("Error: " + data);
        }
      });
    }
  });
});

document.querySelectorAll(".pin-news").forEach(btn => {
  btn.addEventListener("click", function () {
    if (confirm("Are you sure you want to pin this post?")) {
      const id = this.getAttribute("data-id");

      fetch("../src/actions/pin-news.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "id=" + id
      })
      .then(res => res.text())
      .then(data => {
        if (data.trim() === "success") {
          alert("Post deleted successfully.");
          location.reload();
        } else {
          alert("Error: " + data);
        }
      });
    }
  });
});

</script>