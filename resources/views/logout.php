<?php
session_start();
session_unset();
session_destroy();

// Prevent caching of old sidebar/header
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

// Redirect to login
header("Location: index.php");
exit;

?>

<script>

// refresh the page
location.reload();
</script>