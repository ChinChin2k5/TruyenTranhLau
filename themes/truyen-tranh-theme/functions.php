
<?php
/**
 * Truyen Tranh Theme - Functions
 * Author: Chiến, Đức, Duy
 *
 * Lazy load: dùng thuần browser native (loading="lazy" + IntersectionObserver)
 * KHÔNG dùng lazysizes, KHÔNG dùng a3-lazy-load plugin
 */

// 
// 1. THEME SUPPORT
// 
add_theme_support('post-thumbnails');
add_theme_support('menus');
add_theme_support('title-tag');
add_image_size('truyen-thumb', 200, 280, true);

register_nav_menus(['primary' => 'Menu Chính']);

// 
// 2. ĐĂNG KÝ CUSTOM POST TYPE: truyen
// 
add_action('init', function () {

    // --- Post Type: Truyện ---
    register_post_type('manga', [
        'labels' => [
            'name'          => 'Truyện Tranh',
            'singular_name' => 'Bộ Truyện',
            'add_new_item'  => 'Thêm Bộ Truyện',
            'edit_item'     => 'Sửa Bộ Truyện',
            'view_item'     => 'Xem Bộ Truyện',
            'all_items'     => 'Tất cả Truyện',
            'search_items'  => 'Tìm Truyện',
        ],
        'public'              => true,
        'has_archive'         => true,
        'show_in_rest'        => true,
        'supports'            => ['title', 'editor', 'thumbnail', 'excerpt'],
        'menu_icon'           => 'dashicons-book-alt',
        'rewrite'             => ['slug' => 'truyen'],
    ]);

    // --- Post Type: Chương ---
    register_post_type('manga-chapter', [
        'labels' => [
            'name'          => 'Chương',
            'singular_name' => 'Chương',
            'add_new_item'  => 'Thêm Chương',
            'edit_item'     => 'Sửa Chương',
            'view_item'     => 'Xem Chương',
            'all_items'     => 'Tất cả Chương',
        ],
        'public'              => true,
        'has_archive'         => false,
        'show_in_rest'        => true,
        'hierarchical'        => true,
        'supports'            => ['title', 'editor', 'thumbnail', 'page-attributes'],
        'menu_icon'           => 'dashicons-media-document',
        'rewrite'             => ['slug' => 'chuong'],
    ]);

    // --- Taxonomy: Thể loại ---
    register_taxonomy('the_loai', ['truyen'], [
        'labels' => [
            'name'          => 'Thể loại',
            'singular_name' => 'Thể loại',
            'add_new_item'  => 'Thêm thể loại',
            'edit_item'     => 'Sửa thể loại',
            'search_items'  => 'Tìm thể loại',
            'all_items'     => 'Tất cả thể loại',
        ],
        'hierarchical'      => true,
        'public'            => true,
        'show_in_rest'      => true,
        'rewrite'           => ['slug' => 'the-loai'],
        'show_admin_column' => true,
    ]);

});

// Flush rewrite sau khi đăng ký (bỏ comment, load 1 lần, rồi comment lại)
// add_action('init', function(){ flush_rewrite_rules(); }, 99);

// 
// 3. SCRIPTS & STYLES
// 
add_action('wp_enqueue_scripts', function () {

    // CSS theme
    wp_enqueue_style('truyen-tranh-style', get_stylesheet_uri(), [], '1.2');

    // main.js - IntersectionObserver lazy load cho trang đọc truyện
    // KHÔNG enqueue lazysizes nữa — dùng native loading="lazy" + JS thuần
    wp_enqueue_script(
        'truyen-main',
        get_template_directory_uri() . '/js/main.js',
        [],   // không phụ thuộc lazysizes
        '1.2',
        true  // footer
    );
});

// 
// 4. BỎ FILTER truyen_add_lazy
//    - Trước đây filter này inject loading="lazy" vào the_content
//    - Gây conflict với a3-lazy-load và với markup <img loading="lazy"> có sẵn
//    - Giờ để native browser tự xử lý qua attribute loading="lazy" trong template
// 
// (Không còn hàm truyen_add_lazy ở đây nữa)

// 
// 5. SỐ TRUYỆN MỖI TRANG
// 
add_action('pre_get_posts', function ($q) {
    if (!is_admin() && $q->is_main_query() && $q->is_post_type_archive('truyen')) {
        $q->set('posts_per_page', 20);
    }
});
// SCRIPT CHẠY 1 LẦN ĐỂ TẠO 25 BỘ TRUYỆN + CHƯƠNG ĐỂ TEST PHÂN TRANG
// add_action('admin_init', function() {
//     // Kiểm tra xem đã tạo chưa để tránh trùng lặp khi F5
//     // if (get_option('data_test_da_tao') === 'yes') {
//     //     return; 
//     // }

//     for ($i = 1; $i <= 25; $i++) {
//         // 1. Tạo bộ truyện tranh
//         $truyen_id = wp_insert_post([
//             'post_title'   => 'Bộ Truyện Tranh Thử Nghiệm Số ' . $i,
//             'post_content' => 'Đây là nội dung giới thiệu chi tiết cho bộ truyện tranh thử nghiệm thứ ' . $i . '. Được tạo tự động để kiểm tra tính năng phân trang grid và hiệu ứng lazy load skeleton.',
//             'post_status'  => 'publish',
//             'post_type'    => 'truyen',
//         ]);

//         if (!is_wp_error($truyen_id) && $truyen_id) {
//             // 2. Tạo 3 chương truyện đính kèm cho bộ truyện này
//             for ($j = 1; $j <= 3; $j++) {
//                 $chuong_id = wp_insert_post([
//                     'post_title'   => 'Chương ' . $j . ' của truyện số ' . $i,
//                     'post_content' => '<p>Ảnh 1: <img src="https://picsum.photos/800/1200?random=' . $i . $j . '1" /></p>
//                                       <p>Ảnh 2: <img src="https://picsum.photos/800/1200?random=' . $i . $j . '2" /></p>',
//                     'post_status'  => 'publish',
//                     'post_type'    => 'chuong',
//                 ]);

//                 if (!is_wp_error($chuong_id)) {
//                     // Liên kết chương vào bộ truyện thông qua ACF custom field "chọn_bộ_truyện"
//                     update_post_meta($chuong_id, 'chọn_bộ_truyện', $truyen_id);
//                 }
//             }
//         }
//     }

//     // Đánh dấu đã tạo thành công
//     update_option('data_test_da_tao', 'yes');
// });




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
