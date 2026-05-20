<?php get_header(); ?>

<main>
    <section class="hero-slider-section">

        <?php
        $slider_args = array(
            'post_type' => 'manga',
            'posts_per_page' => 3,
            'orderby' => 'date',
            'order' => 'DESC',
        );

        $slider_query = new WP_Query($slider_args);
        $slide_count = 0;

        if ($slider_query->have_posts()):
            while ($slider_query->have_posts()):
                $slider_query->the_post();
                $is_active = ($slide_count === 0) ? 'active' : '';
                $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'full') ?: 'https://images.unsplash.com/photo-1578632767115-351597cf2477?q=80&w=1200&auto=format&fit=crop';
                $featured_thumb = get_the_post_thumbnail_url(get_the_ID(), 'medium') ?: 'https://images.unsplash.com/photo-1578632767115-351597cf2477?q=80&w=400&auto=format&fit=crop';
                ?>
                <div class="slide <?php echo esc_attr($is_active); ?>">
                    <div class="slide-bg" style="background-image: url('<?php echo esc_url($featured_image); ?>');"></div>
                    <div class="slide-content">
                        <div class="cover-image skeleton">
                            <a href="<?php the_permalink(); ?>">
                                <?php
                                if (has_post_thumbnail()) {
                                    // Nếu có ảnh bìa riêng của truyện
                                    $slider_thumb_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                                    //HOT FIX: Thêm class delay-load, đổi src -> data-src, bỏ style opacity cứng
                                    echo '<img src="' . esc_url($slider_thumb_url) . '"
                                     width="240" height="340"
                                     alt="' . esc_attr(get_the_title()) . '"
                                     
                                     class="slider-thumb delay-load skip-lazy" data-skip-lazy loading="eager" decoding="async">';
                                } else {
                                    // VẪN GIỮ LẠI IMG CŨ khi không tìm thấy ảnh bìa, nhưng chuyển sang data-src để lazy load
                                    echo '<img src="' . esc_url($featured_thumb) . '"
                                     width="240" height="340"
                                     alt="Manga Cover" class="slider-thumb delay-load skip-lazy" data-skip-lazy loading="eager" decoding="async">';
                                }
                                ?>
                            </a>
                        </div>
                        <div class="manga-info">
                            <h4 class="section-badge">Popular New Titles</h4>
                            <h1 class="manga-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h1>
                            <div class="manga-tags">
                                <?php
                                $genres = get_the_terms(get_the_ID(), 'manga-genre');
                                if (!empty($genres) && !is_wp_error($genres)):
                                    $count = 0;
                                    foreach ($genres as $genre):
                                        if ($count < 3):
                                            echo '<span class="tag">' . esc_html($genre->name) . '</span>';
                                            $count++;
                                        endif;
                                    endforeach;
                                endif;
                                ?>
                            </div>
                            <p class="manga-desc">
                                <?php echo wp_trim_words(get_the_excerpt(), 40); ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php
                $slide_count++;
            endwhile;
        else:
            ?>
            <div class="slide active">
                <div class="slide-bg"
                    style="background-image: url('https://images.unsplash.com/photo-1578632767115-351597cf2477?q=80&w=1200&auto=format&fit=crop');">
                </div>
                <div class="slide-content">
                    <div class="cover-image">
                        <img src="https://images.unsplash.com/photo-1578632767115-351597cf2477?q=80&w=400&auto=format&fit=crop"
                            alt="Manga Cover">
                    </div>
                    <div class="manga-info">
                        <h4 class="section-badge">Popular New Titles</h4>
                        <h1 class="manga-title">No Manga Available</h1>
                        <p class="manga-desc">Create and feature some manga posts to get started!</p>
                    </div>
                </div>
            </div>
        <?php endif;
        wp_reset_postdata(); ?>
        <?php if (isset($latest_query) && $latest_query->max_num_pages > 1): ?>
            <div class="pagination-container"
                style="grid-column: 1 / -1; text-align: center; margin: 40px 0; font-size: 18px; width: 100%;">
                <?php
                echo paginate_links(array(
                    'total' => $latest_query->max_num_pages,
                    'current' => $paged,
                    'prev_text' => '« Trước',
                    'next_text' => 'Tiếp »',
                    'mid_size' => 2,
                ));
                ?>
            </div>
        <?php endif; ?>
        </div>
        </div>
    </section>

    <div class="slider-controls">
        <span class="slide-indicator">NO. <span id="current-slide-num">1</span></span>
        <div class="slider-control-btns">
            <button class="slider-btn prev-btn" aria-label="Previous Slide">🠔</button>
            <button class="slider-btn next-btn" aria-label="Next Slide">🠖</button>
        </div>
    </div>
    </section>

    <section class="latest-updates-section">
        <div class="container">
            <h2>Latest Updates</h2>
            <div class="manga-grid">

                <?php
                // Dùng $_GET['trang'] thay vì paged để qua mặt Main Query của WordPress
                $manga_paged = isset($_GET['trang']) ? max(1, intval($_GET['trang'])) : 1;

                $latest_args = array(
                    'post_type' => 'manga',
                    'posts_per_page' => 18,
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'paged' => $manga_paged, // Truyền biến số trang tùy chỉnh vào Query
                );

                $latest_query = new WP_Query($latest_args);

                if ($latest_query->have_posts()):
                    while ($latest_query->have_posts()):
                        $latest_query->the_post();
                        $thumb_url = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail') ?: 'https://images.unsplash.com/photo-1578632767115-351597cf2477?q=80&w=150';

                        $chuong_q = new WP_Query([
                            'post_type' => 'manga-chapter',
                            'post_parent' => get_the_ID(),
                            'post_status' => 'publish',
                            'posts_per_page' => -1,
                            'fields' => 'ids',
                        ]);
                        $so_chuong = $chuong_q->found_posts;
                        ?>

                        <a href="<?php the_permalink(); ?>" class="manga-item">
                            <div class="truyen-thumb-wrapper skeleton">
                                <img src="<?php echo esc_url($thumb_url); ?>" width="90" height="130"
                                    alt="<?php the_title(); ?>" class="manga-thumb delay-load skip-lazy" data-skip-lazy
                                    loading="lazy" decoding="async">
                            </div>

                            <div class="manga-details">
                                <h3 class="manga-title-small"><?php the_title(); ?></h3>
                                <p class="manga-chapter">
                                    <?php echo 'Đã cập nhật: ' . esc_html($so_chuong) . ' chương'; ?>
                                </p>
                                <div class="manga-meta">
                                    <span class="team">
                                        <?php
                                        $group = get_post_meta(get_the_ID(), '_translation_group', true) ?: 'Team Vô Danh';
                                        echo '👤 ' . esc_html($group);
                                        ?>
                                    </span>
                                    <span class="time">🕒
                                        <?php echo esc_html(human_time_diff(get_the_time('U'), current_time('timestamp')) . ' trước'); ?></span>
                                </div>
                            </div>
                        </a>

                        <?php
                    endwhile;
                else:
                    echo '<p style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--text-muted);">No manga found. Start by creating one!</p>';
                endif;

                // 
                // KHU VỰC PHÂN TRANG (PAGINATION) 
                // 
                ?>
                <div class="pagination-wrapper">
                    <?php
                    // Tự động nhận diện biến Query của bạn (Ví dụ: $latest_query hoặc $manga_query)
                    // Nếu bạn đặt tên biến Query khác, hãy thay thế ở dòng dưới đây
                    $current_query = isset($latest_query) ? $latest_query : (isset($manga_query) ? $manga_query : $GLOBALS['wp_query']);

                    // Khắc phục lỗi phân trang trên Trang Chủ Static Page của WordPress
                    
                    // Khắc phục lỗi phân trang trên Trang Chủ Static Page của WordPress
                    // Lấy lại số trang hiện tại từ URL
                    $manga_paged = isset($_GET['trang']) ? max(1, intval($_GET['trang'])) : 1;

                    echo paginate_links(array(
                        'base' => add_query_arg('trang', '%#%'), // Đổi đường dẫn thành /?trang=2
                        'format' => '',
                        'current' => $manga_paged,
                        'total' => $current_query->max_num_pages,
                        'prev_text' => '‹ Trước',
                        'next_text' => 'Sau ›',
                        'type' => 'list',
                        'end_size' => 1,
                        'mid_size' => 2
                    ));
                    ?>
                </div>
                <?php

                wp_reset_postdata();
                ?>

            </div>
        </div>
    </section>
</main>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const lazyImages = document.querySelectorAll('img.delay-load');

        lazyImages.forEach(img => {
            // Nếu ảnh đã được cache và load xong ngay lập tức
            if (img.complete) {
                removeSkeleton(img);
            } else {
                // Lắng nghe sự kiện load của trình duyệt
                img.addEventListener('load', function () {
                    removeSkeleton(this);
                });
            }
        });

        function removeSkeleton(imgElement) {
            // Tìm thẻ cha bọc ngoài cùng (manga-item hoặc slide) để add class .loaded
            const parentItem = imgElement.closest('.manga-item') || imgElement.closest('.slide');
            if (parentItem) {
                parentItem.classList.add('loaded');
            }
        }
    });
</script>
<?php get_footer();
