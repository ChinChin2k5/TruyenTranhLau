get_header();
?>

<div class="manga-banner">
    <div class="manga-banner-bg" style="background-image: url('<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'full') ?: 'https://images.unsplash.com/photo-1578632767115-351597cf2477?q=80&w=1200&auto=format&fit=crop'); ?>');"></div>
</div>

<div class="container">
    <div class="manga-header-wrapper">
        <div class="manga-cover">
            <?php if (has_post_thumbnail()): ?>
                <?php the_post_thumbnail('medium', array('alt' => get_the_title())); ?>
            <?php else: ?>
                <img src="https://images.unsplash.com/photo-1578632767115-351597cf2477?q=80&w=400&auto=format&fit=crop" alt="<?php the_title(); ?>">
            <?php endif; ?>
        </div>
        
        <div class="manga-info-top">
            <h1 class="manga-title"><?php the_title(); ?></h1>
            
            <div class="manga-author">
                <?php
                $author = get_post_meta(get_the_ID(), '_manga_author', true);
                echo $author ? esc_html($author) : 'Unknown Author';
                ?>
            </div>
            
            <div class="action-buttons">
                <button class="btn btn-library">📚 Add to Library</button>
                <?php
                // Get first chapter for reading
                $first_chapter = new WP_Query(array(
                    'post_type' => 'manga-chapter',
                    'post_parent' => get_the_ID(),
                    'posts_per_page' => 1,
                    'meta_key' => '_chapter_number',
                    'orderby' => 'meta_value_num',
                    'order' => 'ASC',
                ));
                
                if ($first_chapter->have_posts()):
                    $first_chapter->the_post();
                    $chapter_link = get_permalink();
                    wp_reset_postdata();
                    ?>
                    <a href="<?php echo esc_url($chapter_link); ?>" class="btn btn-read" style="cursor: pointer;">📖 Start Reading</a>
                    <?php
                else:
                    ?>
                    <button class="btn btn-read" disabled style="opacity: 0.5; cursor: not-allowed;">📖 Start Reading</button>
                    <?php
                endif;
                ?>
                <?php if (current_user_can('edit_posts')): ?>
                    <button class="btn btn-outline" onclick="window.location.href='<?php echo esc_url(get_edit_post_link()); ?>'">✎ Edit</button>
                <?php endif; ?>
            </div>

            <div class="tags-list">
                <?php
                $status = get_post_meta(get_the_ID(), '_manga_status', true) ?: 'N/A - ONGOING';
                echo '<span class="tag status">' . esc_html($status) . '</span>';
                
                $genres = get_the_terms(get_the_ID(), 'manga-genre');
                if (!empty($genres) && !is_wp_error($genres)):
                    foreach ($genres as $genre):
                        echo '<span class="tag">' . esc_html($genre->name) . '</span>';
                    endforeach;
                endif;
                ?>
            </div>

            <p class="manga-description">
                <?php the_excerpt(); ?>
            </p>
        </div>
    </div>

    <div class="manga-content-grid">
        
        <aside class="sidebar">
            <div class="sidebar-widget">
                <h4>Authors</h4>
                <div class="widget-tags">
                    <?php
                    $author = get_post_meta(get_the_ID(), '_manga_author', true);
                    if ($author):
                        echo '<span class="tag">' . esc_html($author) . '</span>';
                    endif;
                    ?>
                </div>
            </div>

            <div class="sidebar-widget">
                <h4>Genres</h4>
                <div class="widget-tags">
                    <?php
                    $genres = get_the_terms(get_the_ID(), 'manga-genre');
                    if (!empty($genres) && !is_wp_error($genres)):
                        foreach ($genres as $genre):
                            echo '<span class="tag">' . esc_html($genre->name) . '</span>';
                        endforeach;
                    endif;
                    ?>
                </div>
            </div>

            <div class="sidebar-widget">
                <h4>Themes</h4>
                <div class="widget-tags">
                    <?php
                    $themes = get_the_terms(get_the_ID(), 'manga-theme');
                    if (!empty($themes) && !is_wp_error($themes)):
                        foreach ($themes as $theme):
                            echo '<span class="tag">' . esc_html($theme->name) . '</span>';
                        endforeach;
                    endif;
                    ?>
                </div>
            </div>

            <div class="sidebar-widget">
                <h4>Alternative Titles</h4>
                <ul class="alt-titles">
                    <?php
                    $alt_titles = get_post_meta(get_the_ID(), '_manga_alt_titles', true);
                    if ($alt_titles):
                        $titles = explode("\n", $alt_titles);
                        foreach ($titles as $title):
                            if (trim($title)):
                                echo '<li>' . esc_html(trim($title)) . '</li>';
                            endif;
                        endforeach;
                    else:
                        echo '<li>' . the_title() . '</li>';
                    endif;
                    ?>
                </ul>
            </div>
        </aside>

        <main class="main-content">
            <div class="tabs">
                <button class="tab active">📖 Manga <span style="font-size: 0.8em; background:#333; padding:2px 5px; border-radius:10px;"><?php echo esc_html(get_post_meta(get_the_ID(), '_manga_chapter_count', true) ?: '0'); ?></span></button>
                <button class="tab">📄 Light Novel</button>
                <button class="tab">🎬 Anime</button>
            </div>

            <p class="filter-label">Ascending</p>
            <div class="chapter-filters">
                <button class="filter-btn active">Latest</button>
                <button class="filter-btn">Oldest</button>
            </div>

            <div class="chapter-list">
                <?php
                $args = array(
                    'post_type' => 'manga-chapter',
                    'posts_per_page' => -1,
                    'meta_key' => '_chapter_number',
                    'orderby' => 'meta_value_num',
                    'order' => 'DESC',
                    'post_parent' => get_the_ID(),
                );
                
                $chapters = new WP_Query($args);
                
                if ($chapters->have_posts()):
                    while ($chapters->have_posts()): $chapters->the_post();
                        $chapter_number = get_post_meta(get_the_ID(), '_chapter_number', true);
                        $chapter_group = get_post_meta(get_the_ID(), '_chapter_group', true) ?: 'NONE';
                        ?>
                        <div class="chapter-item">
                            <div class="chapter-left">
                                <h4>Ch.<?php echo esc_html($chapter_number); ?> <span style="font-weight: normal; color:#aaa;"><?php echo esc_html(get_post_meta(get_the_ID(), '_chapter_title', true) ?: 'None'); ?></span></h4>
                                <span><?php echo esc_html($chapter_group); ?></span>
                            </div>
                            <div class="chapter-right">
                                <span class="chapter-date">🕒 <?php echo get_the_date('d/m/Y'); ?></span>
                                <span class="chapter-group">👤 <?php echo esc_html($chapter_group); ?></span>
                            </div>
                        </div>
                        <?php
                    endwhile;
                else:
                    echo '<p style="color: var(--text-muted); text-align: center; padding: 30px;">No chapters found</p>';
                endif;
                
                wp_reset_postdata();
                ?>
            </div>
        </main>

    </div>
</div>

<?php get_footer();
