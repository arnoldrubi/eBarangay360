<?php
session_start();
session_destroy();
//unset session
unset($_SESSION['user_id']);
header("Location: index.php");
exit;