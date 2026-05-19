<!-- Customize thanh Header (dự kiến Customize thêm file Footer nữa) -->
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header" style="background: #1f3460; border-bottom: 1px solid #2a477d;">
    <div class="container" style="padding-top: 15px; padding-bottom: 15px; display: flex; justify-content: space-between; align-items: center;">
        
        <div class="logo">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="font-size: 1.6rem; font-weight: bold; color: #e94560; text-decoration: none; text-transform: uppercase;">
                <?php bloginfo( 'name' ); ?>
            </a>
        </div>
        
        <nav class="main-navigation">
            <ul style="list-style: none; display: flex; gap: 20px; margin: 0; padding: 0;">
                <li>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="color: #fff; text-decoration: none; font-weight: 500; text-transform: uppercase;">
                        Trang Chủ
                    </a>
                </li>
            </ul>
        </nav>

    </div>
</header>