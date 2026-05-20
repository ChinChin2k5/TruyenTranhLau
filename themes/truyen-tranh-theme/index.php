<?php get_header(); ?>
<style>
    /* Nhắm thẳng vào khối nội dung bọc bên trong Header của Twenty Twenty-Five */
    .wp-block-group.alignfull > .wp-block-group,
    .wp-block-group.alignfull .alignwide {
        max-width: 1200px !important;  
        margin-left: auto !important;  
        margin-right: auto !important; 
        padding-left: 16px !important; 
        padding-right: 16px !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }
</style>
<div class="container">
    <h1 class="page-title">Danh Sách Truyện Tranh</h1>

    <?php 
    // TÍNH TOÁN TRANG HIỆN TẠI VÀ LẤY TRUYỆN
    $paged = (get_query_var('paged')) ? get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : 1);

    $query = new WP_Query([
        'post_type'      => 'manga',
        'post_status'    => 'publish',
        'posts_per_page' => 20,       
        'paged'          => $paged,  
    ]);
    ?>

    <?php if ($query->have_posts()) : ?>
        <div class="truyen-list">
            <?php while ($query->have_posts()) : $query->the_post(); 
                
                // ĐẾM SỐ CHƯƠNG (Chuẩn hệ post_parent siêu tốc)
                $chuong_q = new WP_Query([
                    'post_type'      => 'manga-chapter',
                    'post_parent'    => get_the_ID(), // Nhận thẳng Truyện Cha
                    'post_status'    => 'publish',
                    'posts_per_page' => -1,
                    'fields'         => 'ids',
                ]);
            ?>
                <div class="truyen-item">
                    <a href="<?php the_permalink(); ?>" class="truyen-link">
                        
                        <div class="truyen-thumb-wrapper skeleton">
                            <?php if (has_post_thumbnail()) : 
                                $thumb_url = get_the_post_thumbnail_url(get_the_ID(), 'truyen-thumb');
                            ?>
                                <img 
                                    src="<?php echo esc_url($thumb_url); ?>" 
                                    class="truyen-thumb-img" 
                                    loading="lazy"
                                    alt="<?php the_title_attribute(); ?>"
                                />
                            <?php else : ?>
                                <div class="no-thumb" style="display: none;">
                                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/product-10.png" 
                                        alt="Chưa có ảnh bìa" 
                                        class="no-thumb-img" />
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

        <?php if ($query->max_num_pages > 1) : ?>
            <div class="pagination-container" style="text-align: center; margin: 30px 0; font-size: 18px;">
                <?php 
                echo paginate_links([
                    'total'     => $query->max_num_pages, 
                    'current'   => $paged,                
                    'prev_text' => '« Trước',
                    'next_text' => 'Tiếp »',
                    'mid_size'  => 2,
                ]);
                ?>
            </div>
        <?php endif; ?>

    <?php else : ?>
        <div class="no-post">
            <p>Chưa có truyện nào.</p>
        </div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>