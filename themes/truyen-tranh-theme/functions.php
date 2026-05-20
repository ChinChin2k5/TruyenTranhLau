<?php
/**
 * Theme Functions
 */

// Enqueue theme scripts and styles
function truyen_tranh_theme_enqueue_assets() {
    // Enqueue manga front page styles
    wp_enqueue_style(
        'manga-front-page-style',
        get_template_directory_uri() . '/manga-front-page.css',
        array(),
        '1.0'
    );

    // Enqueue manga info styles
    wp_enqueue_style(
        'manga-info-style',
        get_template_directory_uri() . '/manga-info.css',
        array(),
        '1.0'
    );

    // Enqueue manga reader styles
    wp_enqueue_style(
        'manga-reader-style',
        get_template_directory_uri() . '/manga-reader.css',
        array(),
        '1.0'
    );

    // Enqueue 404 styles (only on 404 page)
    if (is_404()) {
        wp_enqueue_style(
            '404-style',
            get_template_directory_uri() . '/404.css',
            array(),
            '1.0'
        );
    }

    // Enqueue manga slider script
    wp_enqueue_script(
        'manga-slider-script',
        get_template_directory_uri() . '/manga-slider.js',
        array(),
        '1.0',
        true
    );

    // Enqueue manga reader script
    wp_enqueue_script(
        'manga-reader-script',
        get_template_directory_uri() . '/manga-reader.js',
        array(),
        '1.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'truyen_tranh_theme_enqueue_assets');

// Support featured images
add_theme_support('post-thumbnails');

// Custom excerpt length
function custom_excerpt_length($length) {
    return 15;
}
add_filter('excerpt_length', 'custom_excerpt_length');
