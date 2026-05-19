<h1>Web Truyện Tranh Đang Khởi Chạy</h1>
<?php get_header(); ?>

<div class="truyen-list">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <div class="truyen-item">
            <?php the_post_thumbnail(); ?>
            <h2><?php the_title(); ?></h2>
        </div>
    <?php endwhile; endif; ?>
</div>

<?php wp_pagenavi(); ?>

<?php get_footer(); ?>