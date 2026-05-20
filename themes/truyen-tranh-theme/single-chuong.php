<!-- Bắt đầu cập nhật của DUY -->
<!--//////////////////////////////////////////////////////////////////////////////////////////////// -->
<?php get_header(); ?>
<?php
/*
 * Trang đọc chương (post_type = chuong)
 *
 * Lazy load chiến lược:
 *  - 5 ảnh đầu: loading="eager" → hiển thị ngay, không chờ
 *  - Còn lại: loading="lazy" + data-src, JS (IntersectionObserver)
 *    tự swap data-src → src khi gần viewport
 *  - KHÔNG dùng base64 placeholder / lazysizes / a3-lazy-load
 */

if (!have_posts()) {
    echo '<p>Không tìm thấy chương.</p>';
    get_footer();
    exit;
}
the_post();
$chuong_id = get_the_ID();

// ID bộ truyện cha
$truyen_id = get_post_meta($chuong_id, 'chọn_bộ_truyện', true);
$truyen    = $truyen_id ? get_post($truyen_id) : null;

// Tất cả chương của bộ (để nav + dropdown)
$all_chuong = [];
if ($truyen_id) {
    $all_q = new WP_Query([
        'post_type'      => 'chuong',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'ASC',
        'fields'         => 'ids',
        'meta_query'     => [[
            'key'   => 'chọn_bộ_truyện',
            'value' => $truyen_id,
        ]],
    ]);
    $all_chuong = $all_q->posts;
}

// Chương trước / sau
$cur_idx = array_search($chuong_id, $all_chuong);
$prev_id = ($cur_idx > 0) ? $all_chuong[$cur_idx - 1] : null;
$next_id = ($cur_idx !== false && $cur_idx < count($all_chuong) - 1)
           ? $all_chuong[$cur_idx + 1] : null;

// Ảnh các trang
$trang_anh = get_attached_media('image', $chuong_id);
if (!empty($trang_anh)) {
    usort($trang_anh, fn($a, $b) => $a->menu_order - $b->menu_order);
}

// Số trang load ngay (eager) — đủ để màn hình đầu hiển thị nhanh
define('EAGER_PAGES', 5);
?>

<div class="reader-wrap">

    <!-- HEADER -->
    <div class="reader-header" id="reader-header">
        <div class="container reader-header-inner">
            <?php if ($truyen) : ?>
                <a href="<?php echo get_permalink($truyen_id); ?>" class="back-link">
                    ← <?php echo esc_html($truyen->post_title); ?>
                </a>
            <?php endif; ?>

            <h1 class="reader-title"><?php the_title(); ?></h1>

            <?php if (!empty($all_chuong)) : ?>
            <select class="chapter-select" onchange="window.location.href=this.value">
                <?php foreach ($all_chuong as $i => $cid) :
                    $c   = get_post($cid);
                    $sel = ($cid == $chuong_id) ? 'selected' : '';
                ?>
                    <option value="<?php echo get_permalink($cid); ?>" <?php echo $sel; ?>>
                        Chương <?php echo ($i + 1); ?>: <?php echo esc_html($c->post_title); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php endif; ?>
        </div>
    </div>

    <!-- NAV TRÊN -->
    <div class="chapter-nav container">
        <?php if ($prev_id) : ?>
            <a href="<?php echo get_permalink($prev_id); ?>" class="nav-btn prev">← Chương trước</a>
        <?php else : ?>
            <span class="nav-btn disabled">← Chương trước</span>
        <?php endif; ?>

        <?php if ($truyen) : ?>
            <a href="<?php echo get_permalink($truyen_id); ?>" class="nav-btn home">☰ Mục lục</a>
        <?php endif; ?>

        <?php if ($next_id) : ?>
            <a href="<?php echo get_permalink($next_id); ?>" class="nav-btn next">Chương tiếp →</a>
        <?php else : ?>
            <span class="nav-btn disabled">Chương tiếp →</span>
        <?php endif; ?>
    </div>

    <!-- CÁC TRANG ẢNH -->
    <div class="reader-pages" id="reader-pages">
        <?php if (!empty($trang_anh)) : ?>
            <?php foreach ($trang_anh as $idx => $trang) :
                $img_url    = wp_get_attachment_url($trang->ID);
                $is_eager   = $idx < EAGER_PAGES;
                $page_label = 'Trang ' . ($trang->menu_order + 1);
            ?>
                <?php if ($is_eager) : ?>
                    <?php /* 5 trang đầu: load ngay, không cần JS */ ?>
                    <img
                        class="page-img"
                        src="<?php echo esc_url($img_url); ?>"
                        loading="eager"
                        decoding="async"
                        alt="<?php echo esc_attr($page_label); ?>"
                    />
                <?php else : ?>
                    <?php /* Phần còn lại: JS sẽ swap data-src → src */ ?>
                    <img
                        class="page-img page-lazy"
                        src=""
                        data-src="<?php echo esc_url($img_url); ?>"
                        loading="lazy"
                        decoding="async"
                        alt="<?php echo esc_attr($page_label); ?>"
                    />
                <?php endif; ?>
            <?php endforeach; ?>

        <?php elseif (get_the_content()) : ?>
            <?php
            /*
             * Fallback: nội dung dạng text/html (không phải ảnh attachment)
             * Thêm loading="lazy" decoding="async" vào mọi <img> trong content
             * KHÔNG dùng filter wp hook — xử lý tại chỗ, tránh double-filter
             */
            $content = get_the_content();
            $content = preg_replace(
                '/<img(?![^>]*\bloading\b)([^>]*)(\/?>)/i',
                '<img loading="lazy" decoding="async"$1$2',
                $content
            );
            echo '<div class="reader-content-text">' . $content . '</div>';
            ?>

        <?php else : ?>
            <div class="no-pages"><p>Chương này chưa có trang ảnh.</p></div>
        <?php endif; ?>
    </div>

    <!-- NAV DƯỚI -->
    <div class="chapter-nav container" style="margin:24px auto">
        <?php if ($prev_id) : ?>
            <a href="<?php echo get_permalink($prev_id); ?>" class="nav-btn prev">← Chương trước</a>
        <?php else : ?>
            <span class="nav-btn disabled">← Chương trước</span>
        <?php endif; ?>

        <?php if ($truyen) : ?>
            <a href="<?php echo get_permalink($truyen_id); ?>" class="nav-btn home">☰ Mục lục</a>
        <?php endif; ?>

        <?php if ($next_id) : ?>
            <a href="<?php echo get_permalink($next_id); ?>" class="nav-btn next">Chương tiếp →</a>
        <?php else : ?>
            <span class="nav-btn disabled">Chương tiếp →</span>
        <?php endif; ?>
    </div>

</div>

<?php get_footer(); ?>
<!-- kết thúc cập nhật của DUY -->
<!--//////////////////////////////////////////////////////////////////////////////////////////////// -->
