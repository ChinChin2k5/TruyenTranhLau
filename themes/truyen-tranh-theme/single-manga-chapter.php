<?php
/**
 * Single Chapter Reader Template
 * 
 * Displays manga chapter with images/pages
 */

get_header();

$manga_id = get_the_ID();
$chapter_number = get_post_meta($manga_id, '_chapter_number', true);
$chapter_title = get_post_meta($manga_id, '_chapter_title', true);
$manga_title = get_post_parent();

// Get chapter images from gallery or ACF field
// HOTFIX HỆ "CƠ KHÍ":
$raw_images = get_field('chapter_images', $manga_id); // Lấy cục text chứa các link ảnh

if (!empty($raw_images)) {
    // Băm cục text ra thành nhiều mảnh, cứ thấy dấu xuống dòng là chặt 1 nhát
    // str_replace("\r", "", ...) để tránh lỗi ký tự ẩn trên Windows/Mac
    $chapter_images = explode("\n", str_replace("\r", "", $raw_images));
    
    // Xóa những dòng trống (trường hợp bị dư dấu enter)
    $chapter_images = array_filter($chapter_images, 'trim'); 
} else {
    $chapter_images = array();
}

// Get previous and next chapters
$current_post = get_post($manga_id);
$parent_id = $current_post->post_parent;

// --- BẮT ĐẦU ĐOẠN CODE SỬA LỖI ---
// Tìm chương trước (số nhỏ hơn chương hiện tại)
$prev_chapter_query = new WP_Query(array(
    'post_type' => 'manga-chapter',
    'post_parent' => $parent_id,
    'posts_per_page' => 1,
    'meta_query' => array(
        array('key' => '_chapter_number', 'value' => $chapter_number, 'compare' => '<', 'type' => 'NUMERIC')
    ),
    'orderby' => 'meta_value_num',
    'order' => 'DESC' // Lấy số gần nhất (ví dụ: đang ở 3 thì lấy 2)
));
$prev_chapter = $prev_chapter_query->have_posts() ? $prev_chapter_query->next_post() : null;
wp_reset_postdata();

// Tìm chương sau (số lớn hơn chương hiện tại)
$next_chapter_query = new WP_Query(array(
    'post_type' => 'manga-chapter',
    'post_parent' => $parent_id,
    'posts_per_page' => 1,
    'meta_query' => array(
        array('key' => '_chapter_number', 'value' => $chapter_number, 'compare' => '>', 'type' => 'NUMERIC')
    ),
    'orderby' => 'meta_value_num',
    'order' => 'ASC' // Lấy số gần nhất (ví dụ: đang ở 3 thì lấy 4)
));
$next_chapter = $next_chapter_query->have_posts() ? $next_chapter_query->next_post() : null;
wp_reset_postdata();
// --- KẾT THÚC ĐOẠN CODE SỬA LỖI ---

// Get all chapters for dropdown
$all_chapters = new WP_Query(array(
    'post_type' => 'manga-chapter',
    'post_parent' => $parent_id,
    'posts_per_page' => -1,
    'meta_key' => '_chapter_number',
    'orderby' => 'meta_value_num',
    'order' => 'DESC',
));
?>

<header class="reader-header" id="reader-header">
    <div class="header-left">
        <a href="<?php echo esc_url(get_permalink($parent_id)); ?>" class="btn btn-back" title="Quay lại chi tiết truyện">⬅</a>
    </div>
    
    <div class="header-center">
        <?php if ($parent_id): ?>
            <span class="manga-title-nav"><?php echo esc_html(get_the_title($parent_id)); ?></span>
        <?php endif; ?>
        <span class="chapter-title-nav">Chương <?php echo esc_html($chapter_number); ?></span>
    </div>

    <div class="header-right">
        <select class="chapter-select">
            <?php
            if ($all_chapters->have_posts()):
                while ($all_chapters->have_posts()):
                    $all_chapters->the_post();
                    $ch_number = get_post_meta(get_the_ID(), '_chapter_number', true);
                    $is_current = (get_the_ID() === $manga_id) ? 'selected' : '';
                    ?>
                    <option value="<?php echo esc_url(get_permalink()); ?>" <?php echo esc_attr($is_current); ?>>
                        Chương <?php echo esc_html($ch_number); ?>
                    </option>
                    <?php
                endwhile;
            endif;
            wp_reset_postdata();
            ?>
        </select>
        <button class="btn" title="Cài đặt (Đang phát triển)">⚙</button>
    </div>
</header>

<main class="reader-container">
    <?php
    if (!empty($chapter_images)):
        if (is_array($chapter_images)):
            foreach ($chapter_images as $image_url):
                ?>
                <img class="page-image" src="<?php echo esc_url($image_url); ?>" alt="Page">
                <?php
            endforeach;
        else:
            // If it's a single image
            ?>
            <img class="page-image" src="<?php echo esc_url($chapter_images); ?>" alt="Page">
            <?php
        endif;
    else:
        echo '<p style="text-align: center; color: var(--text-muted); padding: 40px;">Không có trang nào được tải</p>';
    endif;
    ?>
</main>

<footer class="reader-footer">
    <?php if ($prev_chapter): ?>
        <a href="<?php echo esc_url(get_permalink($prev_chapter->ID)); ?>" class="btn">
            ⬅ Chương <?php echo esc_html(get_post_meta($prev_chapter->ID, '_chapter_number', true)); ?>
        </a>
    <?php else: ?>
        <div class="btn" style="opacity: 0.5; cursor: not-allowed;">
            ⬅ Chương trước
        </div>
    <?php endif; ?>
    
    <button class="btn btn-primary" onclick="window.scrollTo({top: 0, behavior: 'smooth'});">
        Lên đầu trang ⬆
    </button>

    <?php if ($next_chapter): ?>
        <a href="<?php echo esc_url(get_permalink($next_chapter->ID)); ?>" class="btn btn-primary">
            Chương <?php echo esc_html(get_post_meta($next_chapter->ID, '_chapter_number', true)); ?> ➡
        </a>
    <?php else: ?>
        <div class="btn btn-primary" style="opacity: 0.5; cursor: not-allowed;">
            Chương sau ➡
        </div>
    <?php endif; ?>
</footer>


<?php get_footer();
