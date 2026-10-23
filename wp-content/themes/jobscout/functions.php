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

