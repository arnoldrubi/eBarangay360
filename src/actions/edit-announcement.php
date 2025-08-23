<?php
require_once __DIR__ . '/../../config/bootstrap.php';
require_once '../helpers/validations.php';

$announcement_id = $_POST['announcement_id'] ?? null;
$post_title      = clean($_POST['post_title'] ?? '');
$post_author     = clean($_POST['post_author'] ?? '');
$post_body       = clean($_POST['post_body'] ?? '');
$action          = $_POST['action'] ?? ''; // draft or publish
$updated_at      = date('Y-m-d H:i:s');

// Status logic
$status    = ($action === 'draft') ? 'draft' : 'posted';
$post_date = ($status === 'posted') ? date('Y-m-d H:i:s') : null;

if (!$announcement_id) {
    die("Invalid request: Missing announcement_id.");
}

try {
    // First, get the current banner filename (so we don’t overwrite unless user uploads new file)
    $stmt = $pdo->prepare("SELECT banner_filename FROM announcements WHERE id = ?");
    $stmt->execute([$announcement_id]);
    $current_banner = $stmt->fetchColumn();

    $banner_filename = $current_banner;

    // Handle banner re-upload
    if (!empty($_FILES['banner_filename']['name'])) {
        $uploadDir = BASE_PATH . '/public/uploads/news_banners/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $ext = pathinfo($_FILES['banner_filename']['name'], PATHINFO_EXTENSION);
        $banner_filename = 'banner_' . time() . '.' . $ext;

        move_uploaded_file($_FILES['banner_filename']['tmp_name'], $uploadDir . $banner_filename);

        // Optional: delete old file
        if ($current_banner && file_exists($uploadDir . $current_banner)) {
            unlink($uploadDir . $current_banner);
        }
    }

    // Update announcement
    $stmt = $pdo->prepare("
        UPDATE announcements SET
            post_title = :post_title,
            post_body = :post_body,
            post_author = :post_author,
            banner_filename = :banner_filename,
            is_pinned = :is_pinned,
            status = :status,
            post_date = :post_date,
            updated_at = :updated_at
        WHERE id = :id
    ");

    $stmt->execute([
        ':post_title' => $post_title,
        ':post_body' => $post_body,
        ':post_author' => $post_author,
        ':banner_filename' => $banner_filename,
        ':is_pinned' => $_POST['is_pinned'] ?? 0,
        ':status' => $status,
        ':post_date' => $post_date,
        ':updated_at' => $updated_at,
        ':id' => $announcement_id
    ]);

    header("Location: ../../public/index.php?page=announcements&{$status}updated=1");
    exit;

} catch (Exception $e) {
    die("Failed to update announcement: " . $e->getMessage());
}
