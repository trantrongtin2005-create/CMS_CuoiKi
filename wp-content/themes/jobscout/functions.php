<?php
/**
 * Các hàm và định nghĩa chức năng của giao diện JobScout
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package JobScout
 */

$jobscout_theme_data = wp_get_theme();
if( ! defined( 'JOBSCOUT_THEME_VERSION' ) ) define ( 'JOBSCOUT_THEME_VERSION', $jobscout_theme_data->get( 'Version' ) );
if( ! defined( 'JOBSCOUT_THEME_NAME' ) ) define( 'JOBSCOUT_THEME_NAME', $jobscout_theme_data->get( 'Name' ) );

/**
 * Khởi tạo các hàm nạp font cục bộ (Local Font Method).
 */
require get_template_directory() . '/inc/class-webfont-loader.php';

/**
 * Các hàm tuỳ biến chức năng giao diện (Custom Functions).
 */
require get_template_directory() . '/inc/custom-functions.php';

/**
 * Các hàm bổ trợ độc lập (Standalone Functions).
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Các hàm xử lý giao diện mẫu (Template Functions).
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Các hàm tuỳ biến cho tính năng làm mới có chọn lọc (Selective Refresh trong Customizer).
 */
require get_template_directory() . '/inc/partials.php';

if( jobscout_is_rara_theme_companion_activated() ) :
	/**
	 * Tuỳ biến các bộ lọc (filter hooks) của plugin Rara Theme Companion.
	 */
	require get_template_directory() . '/inc/rtc-filters.php';
endif;

/**
 * Bộ điều khiển tuỳ biến giao diện (Custom Controls).
 */
require get_template_directory() . '/inc/custom-controls/custom-control.php';

/**
 * Các thiết lập bổ sung trong bộ tuỳ biến giao diện (Customizer).
 */
require get_template_directory() . '/inc/customizer/customizer.php';

/**
 * Đăng ký và quản lý các Widget giao diện.
 */
require get_template_directory() . '/inc/widgets.php';

/**
 * Quản lý các trường dữ liệu Metabox.
 */
require get_template_directory() . '/inc/metabox.php';

/**
 * Bảng điều khiển hướng dẫn bắt đầu (Dashboard Getting Started).
 */
require get_template_directory() . '/inc/dashboard/dashboard.php';

/**
 * Gợi ý cài đặt các plugin cần thiết qua thư viện TGMPA.
 */
require get_template_directory() . '/inc/tgmpa/recommended-plugins.php';

/**
 * Tích hợp các hàm tương thích với WooCommerce nếu plugin đang hoạt động.
 */
if( jobscout_is_woocommerce_activated() ){
    require get_template_directory() . '/inc/woocommerce-functions.php';    
}

/**
 * Tuỳ biến các bộ lọc (filter hooks) của plugin WP Job Manager.
 */
if( jobscout_is_wp_job_manager_activated() ) :
	require get_template_directory() . '/inc/wp-job-manager-filters.php';
endif;

/**
 * Module xử lý đăng ký nhận bản tin Footer & tự động gửi email thông báo
 */
require get_template_directory() . '/inc/newsletter.php';

/**
 * Module xử lý nộp hồ sơ ứng tuyển việc làm (APPLY JOB) qua AJAX & wp_mail()
 */
require get_template_directory() . '/inc/job-application-handler.php';

/**
 * Tùy biến truy vấn trang Tin tức / Blog: Hiển thị 8 bài viết (4 hàng x 2 cột) chuẩn mẫu thiết kế
 */
add_action( 'pre_get_posts', function( $query ) {
	if ( ! is_admin() && $query->is_main_query() && ( $query->is_home() || $query->is_post_type_archive( 'post' ) ) ) {
		$query->set( 'posts_per_page', 8 );
	}
} );

/**
 * Hỗ trợ đường dẫn /news/ và /tin-tuc/ trỏ về trang Tin tức / Blog
 */
add_action( 'init', function() {
	add_rewrite_rule( '^news/?$', 'index.php?pagename=blog', 'top' );
	add_rewrite_rule( '^tin-tuc/?$', 'index.php?pagename=blog', 'top' );
} );

/**
 * Tự động đồng bộ giao diện trong Trình soạn thảo Gutenberg (wp-admin) cho trang Về chúng tôi
 */
function cms_about_page_editor_styles() {
    global $post;
    if ( ! $post || ( get_post_meta( $post->ID, '_wp_page_template', true ) !== 'page-about.php' && $post->post_name !== 'about' ) ) {
        return;
    }
    ?>
    <style id="cms-about-editor-styles">
        /* Đảm bảo toàn bộ khung editor căn giữa cân đối */
        .editor-styles-wrapper {
            margin-left: auto !important;
            margin-right: auto !important;
        }

        /* Khối nổi bật ở giữa: CĂN GIỮA TUYỆT ĐỐI TRÊN MÀN HÌNH SOẠN THẢO */
        .editor-styles-wrapper .about-highlight-section,
        .wp-block-group.about-highlight-section,
        .wp-block.about-highlight-section,
        div[data-type="core/group"].about-highlight-section,
        .wp-block[data-align="full"].about-highlight-section {
            background-color: #fafbfc !important;
            border-top: 1px solid #e2e8f0 !important;
            border-bottom: 1px solid #e2e8f0 !important;
            padding: 45px 30px !important;
            margin-top: 35px !important;
            margin-bottom: 35px !important;
            margin-left: auto !important;
            margin-right: auto !important;
            max-width: 900px !important;
            width: 100% !important;
            text-align: center !important;
            border-radius: 4px;
            box-sizing: border-box !important;
            float: none !important;
            clear: both !important;
        }

        .editor-styles-wrapper .about-highlight-section .wp-block-group__inner-container,
        .editor-styles-wrapper .about-highlight-section .wp-block-group {
            max-width: 100% !important;
            margin-left: auto !important;
            margin-right: auto !important;
            text-align: center !important;
        }

        .editor-styles-wrapper .about-highlight-section h2,
        .editor-styles-wrapper .about-highlight-section h3,
        .editor-styles-wrapper .about-highlight-section h4,
        .editor-styles-wrapper .about-highlight-subtitle {
            color: #EB6324 !important;
            font-size: 22px !important;
            font-weight: 700 !important;
            margin-top: 0 !important;
            margin-bottom: 16px !important;
            margin-left: auto !important;
            margin-right: auto !important;
            text-align: center !important;
        }

        .editor-styles-wrapper .about-highlight-section p {
            max-width: 860px !important;
            margin-left: auto !important;
            margin-right: auto !important;
            margin-bottom: 16px !important;
            font-size: 15.5px !important;
            color: #4A5568 !important;
            line-height: 1.75 !important;
            text-align: center !important;
        }

        /* Khối Tầm nhìn & Khối Facts căn giữa cân đối trong editor */
        .editor-styles-wrapper .about-vision-section,
        .editor-styles-wrapper .about-facts-section,
        .wp-block-group.about-vision-section,
        .wp-block-group.about-facts-section {
            max-width: 1100px !important;
            margin-left: auto !important;
            margin-right: auto !important;
            float: none !important;
        }

        /* Tiêu đề & giá trị cốt lõi */
        .editor-styles-wrapper .about-value-title {
            font-size: 19px !important;
            font-weight: 700 !important;
            color: #1E2022 !important;
        }
        .editor-styles-wrapper .about-value-quote {
            font-size: 15px !important;
            color: #4A5568 !important;
            font-style: italic !important;
        }
        /* Bảng thông tin doanh nghiệp */
        .editor-styles-wrapper .about-fact-label {
            font-size: 17.5px !important;
            font-weight: 700 !important;
            color: #1E2022 !important;
            text-align: center !important;
        }
        .editor-styles-wrapper .about-fact-value {
            font-size: 15px !important;
            color: #4A5568 !important;
            text-align: center !important;
        }
    </style>
    <?php
}
add_action( 'admin_head', 'cms_about_page_editor_styles' );

/**
 * Hiển thị tất cả bài viết và trang (100 mục) trên 1 màn hình quản trị
 */
add_filter( 'edit_posts_per_page', function( $per_page, $post_type = 'post' ) {
    if ( in_array( $post_type, array( 'post', 'page' ), true ) ) {
        return 100;
    }
    return $per_page;
}, 10, 2 );
