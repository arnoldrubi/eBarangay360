<?php
namespace App\Helpers;

class View {
    public static function render($view, $data = [], $layout = 'app') {
        extract($data);
        define('CURRENT_PAGE', $view);

        // Load layout files based on selected layout
        if ($layout === 'auth') {
            require BASE_PATH . "/resources/views/layouts/auth-header.php";
            require BASE_PATH . "/resources/views/{$view}.php";
            require BASE_PATH . "/resources/views/layouts/auth-footer.php";
        } else { // default to 'app' layout
            require BASE_PATH . "/resources/views/layouts/header.php";
            require BASE_PATH . "/resources/views/layouts/sidebar.php";
            require BASE_PATH . "/resources/views/{$view}.php";
            require BASE_PATH . "/resources/views/layouts/footer.php";
        }
    }
}
