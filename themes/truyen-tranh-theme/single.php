<?php
/**
 * single.php - WordPress fallback router
 * Tự động gọi đúng template theo post_type
 * Thứ tự ưu tiên: single-{post_type}.php → single.php → index.php
 */

// // Đường dẫn trung gian để trỏ đến link giao diện Preview truyện (file "sigle-truyen.php")
// tránh lỗi trường hợp vòng lặp bị kẹt fallback về giao diện trang chủ (file "index.php")
$post_type = get_post_type();

if ($post_type === 'truyen') {
    // Trang chi tiết bộ truyện
    get_template_part('single', 'truyen');

} elseif ($post_type === 'chuong') {
    // Trang đọc chương
    get_template_part('single', 'chuong');

} else {
    // Bài viết thông thường (post, page...)
    get_header(); ?>
    <div class="container">
        <?php if (have_posts()) : the_post(); ?>
            <h1><?php the_title(); ?></h1>
            <div><?php the_content(); ?></div>
        <?php endif; ?>
    </div>
    <?php get_footer();
}
// Đường dẫn trung gian để trỏ đến link giao diện Preview truyện (file "sigle-truyen.php")
// tránh lỗi trường hợp vòng lặp bị kẹt fallback về giao diện trang chủ (file "index.php")
