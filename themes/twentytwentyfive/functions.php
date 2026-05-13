<?php
/**
 * Twenty Twenty-Five functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Five
 * @since Twenty Twenty-Five 1.0
 */

// Adds theme support for post formats.
if ( ! function_exists( 'twentytwentyfive_post_format_setup' ) ) :
	/**
	 * Adds theme support for post formats.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_post_format_setup() {
		add_theme_support( 'post-formats', array( 'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video' ) );
	}
endif;
add_action( 'after_setup_theme', 'twentytwentyfive_post_format_setup' );

// Enqueues editor-style.css in the editors.
if ( ! function_exists( 'twentytwentyfive_editor_style' ) ) :
	/**
	 * Enqueues editor-style.css in the editors.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_editor_style() {
		add_editor_style( 'assets/css/editor-style.css' );
	}
endif;
add_action( 'after_setup_theme', 'twentytwentyfive_editor_style' );

// Enqueues the theme stylesheet on the front.
if ( ! function_exists( 'twentytwentyfive_enqueue_styles' ) ) :
	/**
	 * Enqueues the theme stylesheet on the front.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_enqueue_styles() {
		$suffix = SCRIPT_DEBUG ? '' : '.min';
		$src    = 'style' . $suffix . '.css';

		wp_enqueue_style(
			'twentytwentyfive-style',
			get_parent_theme_file_uri( $src ),
			array(),
			wp_get_theme()->get( 'Version' )
		);
		wp_style_add_data(
			'twentytwentyfive-style',
			'path',
			get_parent_theme_file_path( $src )
		);
	}
endif;
add_action( 'wp_enqueue_scripts', 'twentytwentyfive_enqueue_styles' );

// Registers custom block styles.
if ( ! function_exists( 'twentytwentyfive_block_styles' ) ) :
	/**
	 * Registers custom block styles.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_block_styles() {
		register_block_style(
			'core/list',
			array(
				'name'         => 'checkmark-list',
				'label'        => __( 'Checkmark', 'twentytwentyfive' ),
				'inline_style' => '
				ul.is-style-checkmark-list {
					list-style-type: "\2713";
				}

				ul.is-style-checkmark-list li {
					padding-inline-start: 1ch;
				}',
			)
		);
	}
endif;
add_action( 'init', 'twentytwentyfive_block_styles' );

// Registers pattern categories.
if ( ! function_exists( 'twentytwentyfive_pattern_categories' ) ) :
	/**
	 * Registers pattern categories.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_pattern_categories() {

		register_block_pattern_category(
			'twentytwentyfive_page',
			array(
				'label'       => __( 'Pages', 'twentytwentyfive' ),
				'description' => __( 'A collection of full page layouts.', 'twentytwentyfive' ),
			)
		);

		register_block_pattern_category(
			'twentytwentyfive_post-format',
			array(
				'label'       => __( 'Post formats', 'twentytwentyfive' ),
				'description' => __( 'A collection of post format patterns.', 'twentytwentyfive' ),
			)
		);
	}
endif;
add_action( 'init', 'twentytwentyfive_pattern_categories' );

// Registers block binding sources.
if ( ! function_exists( 'twentytwentyfive_register_block_bindings' ) ) :
	/**
	 * Registers the post format block binding source.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_register_block_bindings() {
		register_block_bindings_source(
			'twentytwentyfive/format',
			array(
				'label'              => _x( 'Post format name', 'Label for the block binding placeholder in the editor', 'twentytwentyfive' ),
				'get_value_callback' => 'twentytwentyfive_format_binding',
			)
		);
	}
endif;
add_action( 'init', 'twentytwentyfive_register_block_bindings' );

// Registers block binding callback function for the post format name.
if ( ! function_exists( 'twentytwentyfive_format_binding' ) ) :
	/**
	 * Callback function for the post format name block binding source.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return string|void Post format name, or nothing if the format is 'standard'.
	 */
	function twentytwentyfive_format_binding() {
		$post_format_slug = get_post_format();

		if ( $post_format_slug && 'standard' !== $post_format_slug ) {
			return get_post_format_string( $post_format_slug );
		}
	}
endif;
function tao_thuc_the_truyen_tranh() {
    $args = array(
        'labels' => array(
            'name'          => 'Truyện Tranh',      // Tên hiển thị ngoài menu
            'singular_name' => 'Truyện',            // Tên số ít
            'add_new'       => 'Thêm Truyện Mới',
            'add_new_item'  => 'Thêm một bộ truyện mới',
            'edit_item'     => 'Sửa thông tin truyện',
            'all_items'     => 'Tất cả Truyện',
        ),
        'public'        => true,                    // Cho phép hiển thị ra ngoài web
        'has_archive'   => true,                    // Có trang danh sách chứa tất cả truyện
        'menu_position' => 5,                       // Vị trí xuất hiện trên menu (5 là ngay dưới mục Posts)
        'menu_icon'     => 'dashicons-book-alt',    // Gắn cái icon Quyển Sách cho nó ngầu
        'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt' ), // Các trường nhập liệu hỗ trợ
        'rewrite'       => array( 'slug' => 'truyen' ), // Đường dẫn SEO (VD: localhost/truyentranh/truyen/conan)
    );

    // Lệnh gọi API của WordPress để đăng ký thực thể mới vào CSDL
    register_post_type( 'truyen', $args );
}

// Bắt WordPress phải chạy cái hàm này ngay khi hệ thống khởi động (hook vào 'init')
add_action( 'init', 'tao_thuc_the_truyen_tranh' );
function tao_thuc_the_chuong_truyen() {
    $args = array(
        'labels' => array(
            'name'          => 'Chương Truyện',
            'singular_name' => 'Chương',
            'add_new'       => 'Thêm Chương Mới',
            'add_new_item'  => 'Thêm một chương truyện mới',
            'edit_item'     => 'Sửa thông tin chương',
            'all_items'     => 'Tất cả các Chương',
        ),
        'public'        => true,
        'has_archive'   => false, // Không cần trang hiển thị lộn xộn tất cả các chương
        'menu_position' => 6,     // Nằm ngay dưới mục "Truyện Tranh"
        'menu_icon'     => 'dashicons-media-document', // Icon hình trang giấy cho đúng chất
        'supports'      => array( 'title', 'editor' ), // Chương thì chỉ cần Tiêu đề và Khung soạn thảo
        'rewrite'       => array( 'slug' => 'chuong' ),
    );

    register_post_type( 'chuong', $args );
}
// Bắt WordPress kích hoạt thằng Chương Truyện
add_action( 'init', 'tao_thuc_the_chuong_truyen' );
