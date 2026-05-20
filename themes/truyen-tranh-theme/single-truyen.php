<!-- kết thúc cập nhật của DUY -->
<!--//////////////////////////////////////////////////////////////////////////////////////////////// -->
<?php
if (function_exists('ini_set')) {
    ini_set('memory_limit', '256M');
}

get_header();

if (!have_posts()) {
    echo '<div class="container"><p>Không tìm thấy truyện.</p></div>';
    get_footer();
    exit;
}

the_post();
$truyen_id = get_the_ID();

$tac_gia    = get_post_meta($truyen_id, 'tac_gia',    true);
$tinh_trang = get_post_meta($truyen_id, 'tinh_trang', true);
$nhom_dich  = get_post_meta($truyen_id, 'nhom_dich',  true);
$the_loai   = get_the_terms($truyen_id, 'the_loai');

$danh_sach_chuong = get_posts([
    'post_type'      => 'chuong',
    'post_status'    => 'publish',
    'numberposts'    => -1,
    'orderby'        => 'date',
    'order'          => 'ASC',
    'meta_key'       => 'chọn_bộ_truyện',
    'meta_value'     => $truyen_id,
    'fields'         => 'ids',
]);

$so_chuong = count($danh_sach_chuong);
$first_id  = !empty($danh_sach_chuong) ? $danh_sach_chuong[0] : null;
?>

<div class="container">

    <div class="truyen-detail-header">

        <!-- ẢNH BÌA: native lazy, không base64, không lazysizes -->
        <div class="truyen-cover">
            <?php if (has_post_thumbnail()) : ?>
                <img
                    src="<?php echo esc_url(get_the_post_thumbnail_url($truyen_id, 'large')); ?>"
                    loading="lazy"
                    decoding="async"
                    class="cover-img"
                    alt="<?php the_title_attribute(); ?>"
                />
            <?php else : ?>
                <div class="cover-img no-cover">
                    <span>Chưa có ảnh bìa</span></div>
            <?php endif; ?>
        </div>

        <div class="truyen-detail-info">
            <h1 class="truyen-detail-title"><?php the_title(); ?></h1>

            <table class="info-table">
                <?php if ($tac_gia) : ?>
                <tr><th>Tác giả</th><td><?php echo esc_html($tac_gia); ?></td></tr>
                <?php endif; ?>

                <?php if ($nhom_dich) : ?>
                <tr><th>Nhóm dịch</th><td><?php echo esc_html($nhom_dich); ?></td></tr>
                <?php endif; ?>

                <?php if ($tinh_trang) : ?>
                <tr>
                    <th>Tình trạng</th>
                    <td>
                        <span class="badge-status <?php echo (strpos($tinh_trang, 'hoàn') !== false) ? 'done' : 'ongoing'; ?>">
                            <?php echo esc_html($tinh_trang); ?>
                        </span>
                    </td>
                </tr>
                <?php endif; ?>

                <tr><th>Số chương</th><td><?php echo $so_chuong; ?> chương</td></tr>
                <tr><th>Cập nhật</th><td><?php echo get_the_modified_date('d/m/Y'); ?></td></tr>

                <?php if ($the_loai && !is_wp_error($the_loai)) : ?>
                <tr>
                    <th>Thể loại</th>
                    <td class="tag-row">
                        <?php foreach ($the_loai as $tl) : ?>
                            <a href="<?php echo get_term_link($tl); ?>" class="tag"><?php echo esc_html($tl->name); ?></a>
                        <?php endforeach; ?>
                    </td>
                </tr>
                <?php endif; ?>
            </table>

            <?php if ($first_id) : ?>
                <a href="<?php echo get_permalink($first_id); ?>" class="btn-read">Đọc từ chương 1</a>
            <?php endif; ?>
        </div>

    </div>

    <?php if (get_the_content()) : ?>
    <div class="truyen-description">
        <h2 class="section-title">Giới thiệu</h2>
        <div class="desc-content"><?php the_content(); ?></div>
    </div>
    <?php endif; ?>

    <div class="chapter-list-section">
        <h2 class="section-title">
            Danh sách chương <span class="count">(<?php echo $so_chuong; ?>)</span>
        </h2>

        <?php if (!empty($danh_sach_chuong)) : ?>
            <ul class="chapter-list">
                <?php foreach ($danh_sach_chuong as $i => $cid) :
                    $chuong_title = get_the_title($cid);
                    $chuong_url   = get_permalink($cid);
                    $chuong_date  = get_the_date('d/m/Y', $cid);
                ?>
                    <li class="chapter-item">
                        <a href="<?php echo esc_url($chuong_url); ?>" class="chapter-link">
                            <span class="chap-num">Chương <?php echo ($i + 1); ?></span>
                            <span class="chap-title"><?php echo esc_html($chuong_title); ?></span>
                            <span class="chap-date"><?php echo $chuong_date; ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <p class="no-chapter">Chưa có chương nào được đăng.</p>
        <?php endif; ?>
    </div>

</div>

<?php get_footer(); ?>
<!-- kết thúc cập nhật của DUY -->
<!--//////////////////////////////////////////////////////////////////////////////////////////////// -->