<!--//////////////////////////////////////////////////////////////////////////////////////////////// -->
<!-- Bắt đầu cập nhật của DUY -->
<?php get_header(); ?>
<style>
    /* Nhắm thẳng vào khối nội dung bọc bên trong Header của Twenty Twenty-Five */
    .wp-block-group.alignfull > .wp-block-group,
    .wp-block-group.alignfull .alignwide {
        max-width: 1200px !important;  /* Khớp chuẩn xác với .container bên dưới */
        margin-left: auto !important;  /* Căn giữa khối */
        margin-right: auto !important; /* Căn giữa khối */
        padding-left: 16px !important; /* Thụt lề trái bằng khít với danh sách truyện */
        padding-right: 16px !important;/* Thụt lề phải bằng khít với danh sách truyện */
        width: 100% !important;
        box-sizing: border-box !important;
    }
</style>
<div class="container">
    <h1 class="page-title">Danh Sách Truyện Tranh</h1>

    <?php
    $paged = max(1, get_query_var('paged'));
    $query = new WP_Query([
        'post_type'      => 'manga',
        'post_status'    => 'publish',
        'posts_per_page' => 20,
        'paged'          => $paged,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);
    ?>

    <?php if ($query->have_posts()) : ?>
        <div class="truyen-list">
            <?php while ($query->have_posts()) : $query->the_post(); 
                // Khởi tạo bộ đếm chương chuẩn xác cho từng truyện
                $chuong_q = new WP_Query([
                    'post_type'      => 'manga-chapter',
                    'post_status'    => 'publish',
                    'posts_per_page' => -1,
                    'fields'         => 'ids',
                    'meta_query'     => [
                        [
                            'key'     => 'chọn_bộ_truyện',
                            'value'   => get_the_ID(),
                            'compare' => '='
                        ]
                    ]
                ]);
            ?>
                <div class="truyen-item">
                    <a href="<?php the_permalink(); ?>" class="truyen-link">
                        
                        <div class="truyen-thumb-wrapper skeleton">
                            <?php if (has_post_thumbnail()) : 
                                $thumb_url = get_the_post_thumbnail_url(get_the_ID(), 'truyen-thumb');
                            ?>
                                <img 
                                    src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" 
                                    data-src="<?php echo esc_url($thumb_url); ?>" 
                                    class="truyen-thumb-img delay-load" 
                                    alt="<?php the_title_attribute(); ?>"
                                    style="opacity: 0; transition: opacity 0.4s ease-in-out;"
                                />
                                <?php else : ?>
                                    <div class="truyen-thumb-wrapper skeleton">
                                        <div class="no-thumb" style="display: none;">
                                            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/product-10.png" 
                                                alt="Chưa có ảnh bìa" 
                                                class="no-thumb-img" />
                                        </div>
                                    </div>
                                <?php endif; ?>
                        </div>
                        
                        <div class="truyen-meta">
                            <h3 class="truyen-title-text"><?php the_title(); ?></h3>
                            <div class="truyen-info-sub">
                                <span class="badge-chuong"><?php echo $chuong_q->found_posts; ?> chương</span>
                                <span class="truyen-date"><?php echo get_the_date('d/m/Y'); ?></span>
                            </div>
                        </div>

                    </a>
                </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>

        <?php
        if ($query->max_num_pages > 1) :
            echo '<nav class="pagination-wrap" aria-label="Phân trang">';
            echo paginate_links([
                'base'      => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                'format'    => '?paged=%#%',
                'current'   => $paged,
                'total'     => $query->max_num_pages,
                'prev_text' => '&laquo; Trước',
                'next_text' => 'Tiếp &raquo;',
                'type'      => 'plain',
                'mid_size'  => 2,
            ]);
            echo '</nav>';
        endif;
        ?>

    <?php else : ?>
        <div class="no-post">
            <p>Chưa có truyện nào.</p>
        </div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>

<!-- kết thúc cập nhật của DUY -->
<!--//////////////////////////////////////////////////////////////////////////////////////////////// -->

