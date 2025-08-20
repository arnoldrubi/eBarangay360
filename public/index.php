<?php
// public/index.php (Improved Router with layout switching)

require_once __DIR__ . '../../config/bootstrap.php';

global $view;
$view = function($page, $data = [], $layout = 'app') {
    extract($data);

    if ($layout === 'auth') {
        require BASE_PATH . "/resources/views/layouts/auth-header.php";
        require BASE_PATH . "/resources/views/{$page}.php";
        require BASE_PATH . "/resources/views/layouts/auth-footer.php";
    } else {
        require BASE_PATH . "/resources/views/layouts/header.php";
        require BASE_PATH . "/resources/views/layouts/sidebar.php";
        require BASE_PATH . "/resources/views/{$page}.php";
        require BASE_PATH . "/resources/views/layouts/footer.php";
    }
};

// Get the requested page from URL
$page = $_GET['page'] ?? 'login';

switch ($page) {
    case 'login':
        $view('auth/login', ['title' => 'Login - eBarangay360'], 'auth');
        break;
    case 'forgot-password':
        $view('auth/forgot-password', ['title' => 'Forgot Password - eBarangay360'], 'auth');
        break;
    case 'sign-up':
    case 'register':
        $view('auth/sign-up', ['title' => 'Sign Up - eBarangay360'], 'auth');
        break;
    case 'dashboard':
        $view('dashboard', ['title' => 'Dashboard - eBarangay360']);
        break;
    case 'residents':
        $view('residents', ['title' => 'Residents Module - eBarangay360']);
        break;
    case 'add-resident':
        $view('add-resident', ['title' => 'Add Resident - eBarangay360']);
        break;
    case 'households':
        $view('households', ['title' => 'Households Module - eBarangay360']);
        break;
    case 'add-household':
        $view('add-household', ['title' => 'Add Household - eBarangay360']);
        break;
    case 'add-household-members':
        $view('add-household-members', ['title' => 'Add Household Members - eBarangay360']);
        break;
    case 'blotter-reports':
        $view('blotter-reports', ['title' => 'Blotter Reports - eBarangay360']);
        break;
    case 'add-new-blotter-report':
        $view('add-new-blotter-report', ['title' => 'Add New Blotter Report - eBarangay360']);
        break;
    case 'edit-blotter-report':
        $view('edit-blotter-report', ['title' => 'Edit Blotter Report - eBarangay360']);
        break;
    default:
        http_response_code(404);
        echo "404 Page Not Found";
}
