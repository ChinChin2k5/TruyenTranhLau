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
                <div class="cover-image">
                    <a href="<?php the_permalink(); ?>">
                        <?php 
                        if (has_post_thumbnail()) {
                            the_post_thumbnail('medium', array('alt' => get_the_title())); 
                        } else {
                            echo '<img src="'. esc_url($featured_thumb) .'" alt="Manga Cover">';
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
            <div class="slide-bg" style="background-image: url('https://images.unsplash.com/photo-1578632767115-351597cf2477?q=80&w=1200&auto=format&fit=crop');"></div>
            <div class="slide-content">
                <div class="cover-image">
                    <img src="https://images.unsplash.com/photo-1578632767115-351597cf2477?q=80&w=400&auto=format&fit=crop" alt="Manga Cover">
                </div>
                <div class="manga-info">
                    <h4 class="section-badge">Popular New Titles</h4>
                    <h1 class="manga-title">No Manga Available</h1>
                    <p class="manga-desc">Create and feature some manga posts to get started!</p>
                </div>
            </div>
        </div>
        <?php endif; wp_reset_postdata(); ?>
        <?php if ($latest_query->max_num_pages > 1): ?>
    <div class="pagination-container" style="grid-column: 1 / -1; text-align: center; margin: 40px 0; font-size: 18px; width: 100%;">
        <?php 
        echo paginate_links(array(
            'total'     => $latest_query->max_num_pages,
            'current'   => $paged,
            'prev_text' => '« Trước',
            'next_text' => 'Tiếp »',
            'mid_size'  => 2,
        ));
        ?>
    </div>
<?php endif; ?>
</div> </div>
    </section>

        <div class="slider-controls">
            <span class="slide-indicator">NO. <span id="current-slide-num">1</span></span>
            <div class="slider-control-btns">
                <button class="slider-btn prev-btn" aria-label="Previous Slide">➔</button>
                <button class="slider-btn next-btn" aria-label="Next Slide">➔</button>
            </div>
        </div>
    </section>

    <section class="latest-updates-section">
        <div class="container">
            <h2>Latest Updates</h2>
            <div class="manga-grid">
                
                <?php
                $paged = (get_query_var('page')) ? get_query_var('page') : ((get_query_var('paged')) ? get_query_var('paged') : 1);
                $latest_args = array(
                    'post_type' => 'manga',
    'posts_per_page' => 8, 
    'orderby' => 'date',
    'order' => 'DESC',
    'paged' => $paged, 
                );
                
                $latest_query = new WP_Query($latest_args);
                
                if ($latest_query->have_posts()):
                    while ($latest_query->have_posts()): 
                        $latest_query->the_post();
                        $thumb_url = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail') ?: 'https://images.unsplash.com/photo-1578632767115-351597cf2477?q=80&w=150';
                        
                        $chuong_q = new WP_Query([
                            'post_type'      => 'manga-chapter',
                            'post_parent'    => get_the_ID(),
                            'post_status'    => 'publish',
                            'posts_per_page' => -1,
                            'fields'         => 'ids',
                        ]);
                        $so_chuong = $chuong_q->found_posts;
                ?>
                
                <a href="<?php the_permalink(); ?>" class="manga-item">
                    <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php the_title(); ?>" class="manga-thumb">
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
                            <span class="time">🕒 <?php echo esc_html(human_time_diff(get_the_time('U'), current_time('timestamp')) . ' trước'); ?></span>
                        </div>
                    </div>
                </a>

                <?php
                    endwhile;
                else:
                    echo '<p style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--text-muted);">No manga found. Start by creating one!</p>';
                endif;
                
                wp_reset_postdata();
                ?>

            </div>
        </div>
    </section>
</main>

<?php get_footer();