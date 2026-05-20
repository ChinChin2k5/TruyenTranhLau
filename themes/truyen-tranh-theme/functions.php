<?php
/**
 * Truyen Tranh Theme - Functions
 * Author: Chiến, Đức, Duy
 *
 * Tối ưu: Dùng thuần chuẩn post_parent (Hệ Parent/Son của Đức)
 * Lazy load: dùng thuần browser native (loading="lazy" + IntersectionObserver)
 */

// 
// 1. THEME SUPPORT & MENU
// 
add_theme_support('post-thumbnails');
add_theme_support('menus');
add_theme_support('title-tag');
add_image_size('truyen-thumb', 200, 280, true);

register_nav_menus(['primary' => 'Menu Chính']);

// 
// 2. ĐĂNG KÝ CUSTOM POST TYPE & TAXONOMY
// 
add_action('init', function () {

    // --- Post Type: Bộ Truyện ---
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
        'rewrite'             => ['slug' => 'manga'], // Đồng bộ slug tiếng Anh
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
        'hierarchical'        => false, // CHÌA KHÓA FIX 404: Đổi thành false để URL phẳng, không bị lỗi nhận nhầm cha cùng loài
        'supports'            => ['title', 'editor', 'thumbnail'], // XOÁ page-attributes vì đã có hộp chọn custom ở dưới
        'menu_icon'           => 'dashicons-media-document',
        'rewrite'             => ['slug' => 'manga-chapter'], // Đồng bộ slug tiếng Anh
    ]);

    // --- Taxonomy: Thể loại ---
    register_taxonomy('manga-genre', ['manga'], [ // Đổi từ 'truyen' sang 'manga' cho khớp dữ liệu
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
        'rewrite'           => ['slug' => 'manga-genre'],
        'show_admin_column' => true,
    ]);

});

// 
// 3. ENQUEUE SCRIPTS & STYLES (Gộp sạch của cả Duy và Đức)
// 
function truyen_tranh_theme_enqueue_assets() {
    // CSS gốc của Theme và các file CSS giao diện của Đức
    wp_enqueue_style('truyen-tranh-style', get_stylesheet_uri(), [], '1.2');
    wp_enqueue_style('manga-front-page-style', get_template_directory_uri() . '/manga-front-page.css', array(), '1.0');
    wp_enqueue_style('manga-info-style', get_template_directory_uri() . '/manga-info.css', array(), '1.0');
    wp_enqueue_style('manga-reader-style', get_template_directory_uri() . '/manga-reader.css', array(), '1.0');

    if (is_404()) {
        wp_enqueue_style('404-style', get_template_directory_uri() . '/404.css', array(), '1.0');
    }

    // JS Lazy Load tối ưu của Duy
    wp_enqueue_script('truyen-main', get_template_directory_uri() . '/js/main.js', [], '1.2', true);

    // JS hiệu ứng Slider và Reader của Đức
    wp_enqueue_script('manga-slider-script', get_template_directory_uri() . '/manga-slider.js', array(), '1.0', true);
    wp_enqueue_script('manga-reader-script', get_template_directory_uri() . '/manga-reader.js', array(), '1.0', true);
}
add_action('wp_enqueue_scripts', 'truyen_tranh_theme_enqueue_assets');

// 
// 4. CẤU HÌNH BỔ TRỢ (Query & Excerpt)
// 
add_action('pre_get_posts', function ($q) {
    if (!is_admin() && $q->is_main_query() && $q->is_post_type_archive('manga')) {
        $q->set('posts_per_page', 1);
    }
});

add_filter('excerpt_length', function($length) {
    return 15;
});

// 
// 5. HACK GIAO DIỆN ADMIN: ÉP CHƯƠNG NHẬN TRUYỆN LÀM CHA (LƯU VÀO POST_PARENT)
// 
add_action('add_meta_boxes', function() {
    add_meta_box(
        'manga_parent_selector', 
        '📚 Chọn Truyện Cha (Bắt buộc)', 
        'render_manga_parent_box', 
        'manga-chapter', 
        'side', 
        'high'
    );
});

function render_manga_parent_box($post) {
    $mangas = get_posts([
        'post_type' => 'manga', 
        'numberposts' => -1,
        'post_status' => 'publish'
    ]);
    
    echo '<select name="custom_manga_parent" style="width:100%; padding: 5px;">';
    echo '<option value="0">-- Hãy chọn bộ truyện --</option>';
    
    foreach ($mangas as $manga) {
        $selected = ($post->post_parent == $manga->ID) ? 'selected' : '';
        echo '<option value="' . esc_attr($manga->ID) . '" ' . $selected . '>' . esc_html($manga->post_title) . '</option>';
    }
    echo '</select>';
    echo '<p style="font-size:11px; color:#666; margin-top:5px;">Chọn đúng bộ truyện cha để kích hoạt tính năng chuyển chương ngoài Frontend.</p>';
}

add_action('save_post', function($post_id, $post) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if ($post->post_type !== 'manga-chapter') return;
    
    if (isset($_POST['custom_manga_parent'])) {
        global $wpdb;
        $parent_id = intval($_POST['custom_manga_parent']);
        
        $wpdb->update(
            $wpdb->posts, 
            ['post_parent' => $parent_id], 
            ['ID' => $post_id]
        );
        clean_post_cache($post_id);
    }
}, 10, 2);