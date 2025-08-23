<?php

require_once __DIR__ . '/../../config/bootstrap.php';
require_once '../helpers/validations.php';

$post_title   = clean($_POST['post_title'] ?? '');
$post_author  = clean($_POST['post_author'] ?? '');
$post_body    = clean($_POST['post_body'] ?? '');
$action       = $_POST['action'] ?? ''; // draft or publish
$created_at   = date('Y-m-d H:i:s');


// Status logic
$status = ($action === 'draft') ? 'draft' : 'posted';
$post_date  = ($action === 'posted') ? date('Y-m-d H:i:s') : null; // can modify this to accept user input if needed

// Validate required fields
$required = ['post_title', 'post_body', 'post_date'];
try {
    $stmt = $pdo->prepare("
        INSERT INTO announcements 
            (post_title, post_body, post_author, banner_filename, is_pinned, status, post_date, created_at, updated_at, is_deleted)
        VALUES 
            (:post_title, :post_body, :post_author, :banner_filename, :is_pinned, :status, :post_date, NOW(), NOW(), 0)
    ");

    $banner_filename = null;
    if (!empty($_FILES['banner_filename']['name'])) {
        $uploadDir = BASE_PATH . '/public/uploads/news_banners/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $ext = pathinfo($_FILES['banner_filename']['name'], PATHINFO_EXTENSION);
        $banner_filename = 'banner_' . time() . '.' . $ext;
        move_uploaded_file($_FILES['banner_filename']['tmp_name'], $uploadDir . $banner_filename);
    }

    $stmt->execute([
        ':post_title' => $post_title,
        ':post_body' => $post_body,
        ':post_author' => $post_author,
        ':banner_filename' => $banner_filename,
        ':is_pinned' => $_POST['is_pinned'] ?? 0,
        ':status' => $status,
        ':post_date' => $post_date,
    ]);

    header("Location: ../../public/index.php?page=announcements&{$status}success=1");
    exit;

} catch (Exception $e) {
    die("Failed to insert announcement: " . $e->getMessage());
}
