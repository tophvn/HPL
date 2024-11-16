<?php
// Đường dẫn gốc của website
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/ShopThoiTrang/');

// Đường dẫn đến thư mục "views"
define('VIEWS_URL', BASE_URL . 'views/');

// Đường dẫn đến thư mục "assets"
define('ASSETS_URL', BASE_URL . 'assets/');

// Đường dẫn đến thư mục "auth"
define('AUTH_URL', VIEWS_URL . 'auth/');

// Đường dẫn đến thư mục "includes"
define('INCLUDES_URL', BASE_URL . 'includes/');
?>