<?php
// config/bootstrap.php

// 1. Define BASE_PATH if not already defined
if (!defined('BASE_PATH')) {
  define('BASE_PATH', dirname(__DIR__)); // one level above /config
}

// 2. Define BASE_URL if you're using it for assets or links
if (!defined('BASE_URL')) {
  define('BASE_URL', '/eBarangay360/public/'); // adjust to match your XAMPP/host setup
}

// 3. Load database connection
require_once BASE_PATH . '/config/database.php';

// 4. Load core helper functions
require_once BASE_PATH . '/src/helpers/validations.php';
require_once BASE_PATH . '/src/helpers/view.php';

// Path to actions (for forms)
define('ACTIONS_URL', '/eBarangay360/src/actions/');

// Path to helpers (for forms)
define('HELPERS_URL', '/eBarangay360/src/helpers/');

// Optional additional helpers
// require_once BASE_PATH . '/src/helpers/auth.php';
// require_once BASE_PATH . '/src/helpers/logger.php';
// require_once BASE_PATH . '/src/helpers/layouts/alerts.php';

// 5. (Optional) Set timezone
date_default_timezone_set('Asia/Manila');

// clean function to sanitize input
function clean($val) {
  return htmlspecialchars(trim($val));
}