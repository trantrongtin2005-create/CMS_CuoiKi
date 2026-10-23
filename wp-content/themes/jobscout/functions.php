<?php
/**
 * JobScout functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package JobScout
 */

$jobscout_theme_data = wp_get_theme();
if( ! defined( 'JOBSCOUT_THEME_VERSION' ) ) define ( 'JOBSCOUT_THEME_VERSION', $jobscout_theme_data->get( 'Version' ) );
if( ! defined( 'JOBSCOUT_THEME_NAME' ) ) define( 'JOBSCOUT_THEME_NAME', $jobscout_theme_data->get( 'Name' ) );

/**
 * Implement Local Font Method functions.
 */
require get_template_directory() . '/inc/class-webfont-loader.php';

/**
 * Custom Functions.
 */
require get_template_directory() . '/inc/custom-functions.php';

/**
 * Standalone Functions.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Template Functions.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Custom functions for selective refresh.
 */
require get_template_directory() . '/inc/partials.php';

if( jobscout_is_rara_theme_companion_activated() ) :
	/**
	 * Modify filter hooks of RTC plugin.
	 */
	require get_template_directory() . '/inc/rtc-filters.php';
endif;

/**
 * Custom Controls
 */
require get_template_directory() . '/inc/custom-controls/custom-control.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer/customizer.php';

/**
 * Widgets
 */
require get_template_directory() . '/inc/widgets.php';

/**
 * Metabox
 */
require get_template_directory() . '/inc/metabox.php';

/**
 * Getting Started
*/
require get_template_directory() . '/inc/dashboard/dashboard.php';

/**
 * Plugin Recommendation
*/
require get_template_directory() . '/inc/tgmpa/recommended-plugins.php';

/**
 * Add theme compatibility function for woocommerce if active
*/
if( jobscout_is_woocommerce_activated() ){
    require get_template_directory() . '/inc/woocommerce-functions.php';    
}

/**
 * Modify filter hooks of WP Job Manager plugin.
 */
if( jobscout_is_wp_job_manager_activated() ) :
	require get_template_directory() . '/inc/wp-job-manager-filters.php';
endif;

/**
 * AJAX Handler: Đăng ký nhận bản tin Footer Module
 */
function cms_handle_newsletter_subscription() {
    $email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
    
    if (empty($email) || !is_email($email)) {
        wp_send_json_error(array('message' => 'Địa chỉ email không hợp lệ!'));
    }

    $subscribers = get_option('cms_newsletter_subscribers', array());
    if (!is_array($subscribers)) {
        $subscribers = array();
    }

    if (in_array($email, $subscribers, true)) {
        wp_send_json_error(array('message' => 'Email này đã đăng ký trước đó!'));
    }

    $subscribers[] = $email;
    update_option('cms_newsletter_subscribers', $subscribers);

    wp_send_json_success(array('message' => 'Đăng ký nhận bản tin thành công!'));
}
add_action('wp_ajax_cms_subscribe_newsletter', 'cms_handle_newsletter_subscription');
add_action('wp_ajax_nopriv_cms_subscribe_newsletter', 'cms_handle_newsletter_subscription');

/**
 * Tự động đồng bộ giao diện trong Trình soạn thảo Gutenberg (wp-admin) cho trang Về chúng tôi
 */
function cms_about_page_editor_styles() {
    global $post;
    if ( ! $post || ( (int)$post->ID !== 1176 && get_post_meta($post->ID, '_wp_page_template', true) !== 'page-about.php' ) ) {
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
 * Hiển thị tất cả trang (100 trang) trên 1 màn hình danh sách Trang để người dùng không phải bấm sang Trang 2
 */
add_filter( 'edit_pages_per_page', function() {
    return 100;
} );
