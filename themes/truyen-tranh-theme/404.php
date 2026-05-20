<?php
/**
 * 404 Error Page Template
 * 
 * Displays when a page is not found
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php bloginfo('name'); ?> - 404</title>
    <?php wp_head(); ?>
    <link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri() . '/404.css'); ?>">
</head>
<body <?php body_class('error404'); ?>>
    <?php wp_body_open(); ?>

    <div class="error-container">
        <div class="icon-box">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="6"></circle>
                <line x1="21" y1="21" x2="15.24" y2="15.24"></line>
                <line x1="6" y1="18" x2="10" y2="22"></line>
                <line x1="10" y1="18" x2="6" y2="22"></line>
            </svg>
        </div>

        <h1 class="error-code">404</h1>
        <h2 class="error-title">Trang không tồn tại</h2>
        <p class="error-desc">Trang bạn tìm kiếm đã bị xoá, đổi tên, hoặc chưa<br>từng tồn tại trong hệ thống này.</p>

        <div class="button-group">
            <button onclick="history.back()" class="btn btn-outline">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Quay lại
            </button>
            
            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-gradient">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                </svg>
                Trang Chủ
            </a>
        </div>
    </div>

    <?php wp_footer(); ?>
</body>
</html>
