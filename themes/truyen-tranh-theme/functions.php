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
        'show_in_rest'        => true, // Bộ truyện giữ giao diện mới để viết mô tả dễ dàng
        'supports'            => ['title', 'editor', 'thumbnail', 'excerpt'],
        'menu_icon'           => 'dashicons-book-alt',
        'rewrite'             => ['slug' => 'manga'],
    ]);

    // --- Post Type: Chương ---
    register_post_type('manga-chapter', [
        'labels' => [
            'name'          => 'Chương',
            'singular_name' => 'Chương',
            'add_new_item'  => 'Thêm Chương Mới',
            'edit_item'     => 'Sửa Chương',
            'view_item'     => 'Xem Chương',
            'all_items'     => 'Tất cả Chương',
        ],
        'public'              => true,
        'has_archive'         => false,
        'show_in_rest'        => false, // ĐỔI THÀNH FALSE: Chuyển về giao diện Classic để hiện ô nhập ảnh trực quan nhất
        'hierarchical'        => false,
        'supports'            => ['title'], // Chương chỉ cần nhập Tiêu đề, ảnh đã có ô riêng dưới đây
        'menu_icon'           => 'dashicons-media-document',
        'rewrite'             => ['slug' => 'manga-chapter'],
    ]);

    // --- Taxonomy: Thể loại ---
    register_taxonomy('manga-genre', ['manga'], [
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
// 3. ENQUEUE SCRIPTS & STYLES
// 
function truyen_tranh_theme_enqueue_assets() {
    wp_enqueue_style('truyen-tranh-style', get_stylesheet_uri(), [], '1.2');
    wp_enqueue_style('manga-front-page-style', get_template_directory_uri() . '/manga-front-page.css', array(), '1.0');
    wp_enqueue_style('manga-info-style', get_template_directory_uri() . '/manga-info.css', array(), '1.0');
    wp_enqueue_style('manga-reader-style', get_template_directory_uri() . '/manga-reader.css', array(), '1.0');

    if (is_404()) {
        wp_enqueue_style('404-style', get_template_directory_uri() . '/404.css', array(), '1.0');
    }

    wp_enqueue_script('truyen-main', get_template_directory_uri() . '/js/main.js', [], '1.2', true);
    wp_enqueue_script('manga-slider-script', get_template_directory_uri() . '/manga-slider.js', array(), '1.0', true);
    wp_enqueue_script('manga-reader-script', get_template_directory_uri() . '/manga-reader.js', array(), '1.0', true);
}
add_action('wp_enqueue_scripts', 'truyen_tranh_theme_enqueue_assets');

add_action('wp_enqueue_scripts', function () {
    wp_dequeue_style('jquery-lazyloadxt-spinner-css');
    wp_deregister_style('jquery-lazyloadxt-spinner-css');
    wp_dequeue_script('jquery-lazyloadxt-js');
    wp_deregister_script('jquery-lazyloadxt-js');
    wp_dequeue_script('jquery-lazyloadxt-srcset-js');
    wp_deregister_script('jquery-lazyloadxt-srcset-js');
    wp_dequeue_script('jquery-lazyloadxt-extend-js');
    wp_deregister_script('jquery-lazyloadxt-extend-js');
}, 100);

// 
// 4. CẤU HÌNH BỔ TRỢ
// 
add_action('pre_get_posts', function ($q) {
    if (!is_admin() && $q->is_main_query() && $q->is_post_type_archive('manga')) {
        $q->set('posts_per_page', 12);
    }
});

add_filter('excerpt_length', function($length) {
    return 15;
});

// 
// 5. HIỂN THỊ CÁC Ô CÀI ĐẶT THÔNG TIN CHƯƠNG (NẰM BÊN PHẢI MÀN HÌNH)
// 
add_action('add_meta_boxes', function() {
    add_meta_box(
        'manga_chapter_settings', 
        '⚙️ Cài đặt Thông tin Chương', 
        'render_manga_chapter_settings_box', 
        'manga-chapter', 
        'side', 
        'high'
    );
});

function render_manga_chapter_settings_box($post) {
    $mangas = get_posts([
        'post_type' => 'manga', 
        'numberposts' => -1,
        'post_status' => 'publish'
    ]);
    
    // 1. Chọn Truyện Cha
    echo '<p><label style="font-weight:bold; display:block; margin-bottom:5px;">📚 Thuộc Bộ Truyện (Bắt buộc):</label>';
    echo '<select name="custom_manga_parent" style="width:100%; padding: 5px;" required>';
    echo '<option value="0">-- Chọn bộ truyện cha --</option>';
    foreach ($mangas as $manga) {
        $selected = ($post->post_parent == $manga->ID) ? 'selected' : '';
        echo '<option value="' . esc_attr($manga->ID) . '" ' . $selected . '>' . esc_html($manga->post_title) . '</option>';
    }
    echo '</select></p>';

    // 2. Số thứ tự chương
    $chapter_number = get_post_meta($post->ID, '_chapter_number', true);
    echo '<p><label style="font-weight:bold; display:block; margin-bottom:5px;">🔢 Số thứ tự chương:</label>';
    echo '<input type="number" step="0.1" name="custom_chapter_number" value="' . esc_attr($chapter_number) . '" style="width:100%; padding: 5px;" placeholder="Ví dụ: 1 hoặc 1.5" required></p>';
    
    // 3. Tên chương
    $chapter_title = get_post_meta($post->ID, '_chapter_title', true);
    echo '<p><label style="font-weight:bold; display:block; margin-bottom:5px;">📝 Tên chương phụ (Tùy chọn):</label>';
    echo '<input type="text" name="custom_chapter_title" value="' . esc_attr($chapter_title) . '" style="width:100%; padding: 5px;" placeholder="Ví dụ: Khởi Đầu Mới"></p>';
    
    // 4. Nhóm dịch
    $chapter_group = get_post_meta($post->ID, '_chapter_group', true);
    echo '<p><label style="font-weight:bold; display:block; margin-bottom:5px;">👤 Nhóm dịch (Tùy chọn):</label>';
    echo '<input type="text" name="custom_chapter_group" value="' . esc_attr($chapter_group) . '" style="width:100%; padding: 5px;" placeholder="Ví dụ: Team Vô Danh"></p>';
}

// 
// 6. HIỂN THỊ KHUNG NHẬP DANH SÁCH LINK ẢNH TRUYỆN (NẰM CHÍNH GIỮA MÀN HÌNH)
// 
add_action('add_meta_boxes', function() {
    add_meta_box(
        'manga_chapter_images_box', 
        '🖼️ DANH SÁCH LINK ẢNH TRONG CHƯƠNG (Bắt buộc)', 
        function($post) {
            $images = get_post_meta($post->ID, 'chapter_images', true);
            echo '<p style="margin-bottom:8px; color:#666; font-size:13px;">Dán danh sách đường dẫn ảnh trực tiếp tại đây. <strong style="color:#d63638;">Mỗi dòng tương ứng với 1 ảnh</strong> (Link chuẩn đuôi .jpg, .png, .webp):</p>';
            echo '<textarea name="custom_chapter_images" rows="12" style="width:100%; font-family:monospace; padding:10px; background:#f9f9f9; border:1px solid #ccc; border-radius:4px; font-size:13px;" placeholder="http://localhost/truyen/wp-content/uploads/2026/05/01.jpg&#10;http://localhost/truyen/wp-content/uploads/2026/05/02.jpg">' . esc_textarea($images) . '</textarea>';
        }, 
        'manga-chapter', 
        'normal', 
        'high'
    );
});

// 
// 7. HÀM LƯU DỮ LIỆU TẤT CẢ CÁC TRƯỜNG KHI BẤM CẬP NHẬT
// 
add_action('save_post', function($post_id, $post) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (empty($post) || $post->post_type !== 'manga-chapter') return;
    
    if (isset($_POST['custom_chapter_number'])) {
        update_post_meta($post_id, '_chapter_number', sanitize_text_field($_POST['custom_chapter_number']));
    }
    if (isset($_POST['custom_chapter_title'])) {
        update_post_meta($post_id, '_chapter_title', sanitize_text_field($_POST['custom_chapter_title']));
    }
    if (isset($_POST['custom_chapter_group'])) {
        update_post_meta($post_id, '_chapter_group', sanitize_text_field($_POST['custom_chapter_group']));
    }
    if (isset($_POST['custom_chapter_images'])) {
        update_post_meta($post_id, 'chapter_images', $_POST['custom_chapter_images']);
    }

    if (isset($_POST['custom_manga_parent'])) {
        global $wpdb;
        $parent_id = intval($_POST['custom_manga_parent']);
        
        $wpdb->update(
            $wpdb->posts, 
            ['post_parent' => $parent_id], 
            ['ID' => $post_id]
        );
        
        if ($parent_id > 0) {
            $chapter_count = $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'manga-chapter' AND post_parent = %d AND post_status = 'publish'", 
                $parent_id
            ));
            update_post_meta($parent_id, '_manga_chapter_count', $chapter_count);
        }
        clean_post_cache($post_id);
    }
}, 10, 2);