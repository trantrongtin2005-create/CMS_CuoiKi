<?php
/**
 * Footer Settings (MODULE FOOTER CHUẨN MẪU)
 *
 * Cung cấp các điều khiển và tính năng Selective Refresh (Cây bút xanh) cho Footer:
 * 1. Tiêu đề Newsletter & Nút đăng ký
 * 2. Tên thương hiệu & Đường dẫn
 * 3. Các liên kết mạng xã hội
 * 4. Bản quyền chân trang (Copyright)
 *
 * @package jobscout
 */

if ( ! function_exists( 'jobscout_customize_register_footer' ) ) :

function jobscout_customize_register_footer( $wp_customize ) {
    
    $wp_customize->add_section(
        'footer_settings',
        array(
            'title'      => __( 'Footer Settings', 'jobscout' ),
            'priority'   => 199,
            'capability' => 'edit_theme_options',
        )
    );
    
    /* ========================================================
     * 1. TOP BAR: NEWSLETTER SUBSCRIPTION
     * ======================================================== */
    $wp_customize->add_setting(
        'footer_newsletter_title_1',
        array(
            'default'           => 'ĐĂNG KÝ NHẬN',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control(
        'footer_newsletter_title_1',
        array(
            'label'   => __( 'Dòng tiêu đề Newsletter 1', 'jobscout' ),
            'section' => 'footer_settings',
            'type'    => 'text',
        )
    );

    $wp_customize->add_setting(
        'footer_newsletter_title_2',
        array(
            'default'           => 'BẢN TIN CỦA CHÚNG TÔI',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control(
        'footer_newsletter_title_2',
        array(
            'label'   => __( 'Dòng tiêu đề Newsletter 2', 'jobscout' ),
            'section' => 'footer_settings',
            'type'    => 'text',
        )
    );

    /** Cây bút cho Tiêu đề Newsletter */
    $wp_customize->selective_refresh->add_partial( 'footer_newsletter_title_partial', array(
        'selector'            => '.site-footer-module .ft-newsletter-title',
        'settings'            => array( 'footer_newsletter_title_1', 'footer_newsletter_title_2' ),
        'render_callback'     => 'jobscout_get_footer_newsletter_title',
        'container_inclusive' => false,
    ) );

    $wp_customize->add_setting(
        'footer_newsletter_placeholder',
        array(
            'default'           => 'Nhập địa chỉ email của bạn...',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    $wp_customize->add_control(
        'footer_newsletter_placeholder',
        array(
            'label'   => __( 'Gợi ý ô nhập email (Placeholder)', 'jobscout' ),
            'section' => 'footer_settings',
            'type'    => 'text',
        )
    );

    $wp_customize->add_setting(
        'footer_newsletter_btn_text',
        array(
            'default'           => 'ĐĂNG KÝ',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control(
        'footer_newsletter_btn_text',
        array(
            'label'   => __( 'Chữ trên nút đăng ký bản tin', 'jobscout' ),
            'section' => 'footer_settings',
            'type'    => 'text',
        )
    );

    // Cấu hình Gửi Email khi có người đăng ký
    $wp_customize->add_setting(
        'footer_newsletter_admin_email',
        array(
            'default'           => get_option( 'admin_email' ),
            'sanitize_callback' => 'sanitize_email',
        )
    );
    $wp_customize->add_control(
        'footer_newsletter_admin_email',
        array(
            'label'       => __( 'Email Admin nhận thông báo', 'jobscout' ),
            'description' => __( 'Địa chỉ email của Quản trị viên sẽ nhận thông tin khi có người nhập email đăng ký.', 'jobscout' ),
            'section'     => 'footer_settings',
            'type'        => 'email',
        )
    );

    $wp_customize->add_setting(
        'footer_newsletter_send_welcome',
        array(
            'default'           => 1,
            'sanitize_callback' => 'absint',
        )
    );
    $wp_customize->add_control(
        'footer_newsletter_send_welcome',
        array(
            'label'       => __( 'Gửi email chào mừng tới người đăng ký', 'jobscout' ),
            'description' => __( 'Tự động gửi email cảm ơn và giới thiệu việc làm Plan Do See tới hòm thư của khách hàng.', 'jobscout' ),
            'section'     => 'footer_settings',
            'type'        => 'checkbox',
        )
    );

    $wp_customize->add_setting(
        'footer_newsletter_admin_subject',
        array(
            'default'           => '[Plan Do See] Đăng ký nhận bản tin mới từ: {email}',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    $wp_customize->add_control(
        'footer_newsletter_admin_subject',
        array(
            'label'       => __( 'Tiêu đề email gửi Admin', 'jobscout' ),
            'description' => __( 'Dùng {email} để chèn địa chỉ email người đăng ký.', 'jobscout' ),
            'section'     => 'footer_settings',
            'type'        => 'text',
        )
    );

    $wp_customize->add_setting(
        'footer_newsletter_user_subject',
        array(
            'default'           => 'Chào mừng bạn đến với Bản tin việc làm - Plan Do See Global Inc.',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    $wp_customize->add_control(
        'footer_newsletter_user_subject',
        array(
            'label'   => __( 'Tiêu đề email gửi Người đăng ký', 'jobscout' ),
            'section' => 'footer_settings',
            'type'    => 'text',
        )
    );

    /* ========================================================
     * 2. MIDDLE BAR: BRAND & SOCIAL LINKS
     * ======================================================== */
    $wp_customize->add_setting(
        'footer_brand_name',
        array(
            'default'           => 'Plan Do See Global Inc.',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control(
        'footer_brand_name',
        array(
            'label'   => __( 'Tên thương hiệu ở Footer', 'jobscout' ),
            'section' => 'footer_settings',
            'type'    => 'text',
        )
    );

    $wp_customize->add_setting(
        'footer_brand_url',
        array(
            'default'           => home_url( '/' ),
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    $wp_customize->add_control(
        'footer_brand_url',
        array(
            'label'   => __( 'Đường dẫn thương hiệu (Link Logo/Brand)', 'jobscout' ),
            'section' => 'footer_settings',
            'type'    => 'url',
        )
    );

    /** Cây bút cho Tên thương hiệu */
    $wp_customize->selective_refresh->add_partial( 'footer_brand_partial', array(
        'selector'            => '.site-footer-module .ft-brand',
        'settings'            => array( 'footer_brand_name', 'footer_brand_url' ),
        'render_callback'     => 'jobscout_get_footer_brand',
        'container_inclusive' => false,
    ) );

    /* ========================================================
     * 2B. MIDDLE BAR: NAVIGATION MENU & 5 LIÊN KẾT ĐIỀU HƯỚNG
     * ======================================================== */
    $wp_menus = wp_get_nav_menus();
    $menu_choices = array( '0' => __( '— Mặc định (Tùy biến 5 liên kết bên dưới) —', 'jobscout' ) );
    if ( ! empty( $wp_menus ) && ! is_wp_error( $wp_menus ) ) {
        foreach ( $wp_menus as $menu_obj ) {
            $menu_choices[ $menu_obj->term_id ] = $menu_obj->name;
        }
    }

    $wp_customize->add_setting(
        'footer_menu_select',
        array(
            'default'           => 0,
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control(
        'footer_menu_select',
        array(
            'label'       => __( 'Menu chân trang (Footer Menu)', 'jobscout' ),
            'description' => __( 'Chọn một Menu đã tạo trong Giao diện > Menu, hoặc chọn Mặc định để chỉnh 5 liên kết bên dưới.', 'jobscout' ),
            'section'     => 'footer_settings',
            'type'        => 'select',
            'choices'     => $menu_choices,
        )
    );

    $default_footer_links = array(
        1 => array( 'text' => 'JOBS', 'url' => home_url( '/jobs/' ), 'title' => 'Liên kết 1' ),
        2 => array( 'text' => 'COMPANIES', 'url' => home_url( '/jobs/' ), 'title' => 'Liên kết 2' ),
        3 => array( 'text' => 'BLOG', 'url' => home_url( '/blog/' ), 'title' => 'Liên kết 3' ),
        4 => array( 'text' => 'ABOUT', 'url' => home_url( '/about/' ), 'title' => 'Liên kết 4' ),
        5 => array( 'text' => 'CONTACT', 'url' => home_url( '/contact/' ), 'title' => 'Liên kết 5' ),
    );

    $nav_refresh_settings = array( 'footer_menu_select' );

    foreach ( $default_footer_links as $i => $link_data ) {
        // Tên liên kết
        $wp_customize->add_setting(
            "footer_link_text_{$i}",
            array(
                'default'           => $link_data['text'],
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage',
            )
        );
        $wp_customize->add_control(
            "footer_link_text_{$i}",
            array(
                'label'   => sprintf( __( '%s - Tên hiển thị (Text)', 'jobscout' ), $link_data['title'] ),
                'section' => 'footer_settings',
                'type'    => 'text',
            )
        );

        // URL liên kết
        $wp_customize->add_setting(
            "footer_link_url_{$i}",
            array(
                'default'           => $link_data['url'],
                'sanitize_callback' => 'esc_url_raw',
                'transport'         => 'postMessage',
            )
        );
        $wp_customize->add_control(
            "footer_link_url_{$i}",
            array(
                'label'   => sprintf( __( '%s - Đường dẫn (URL)', 'jobscout' ), $link_data['title'] ),
                'section' => 'footer_settings',
                'type'    => 'url',
            )
        );

        $nav_refresh_settings[] = "footer_link_text_{$i}";
        $nav_refresh_settings[] = "footer_link_url_{$i}";
    }

    /** Cây bút cho Menu Điều Hướng Chân Trang */
    $wp_customize->selective_refresh->add_partial( 'footer_nav_menu_partial', array(
        'selector'            => '.site-footer-module .ft-nav-wrap',
        'settings'            => $nav_refresh_settings,
        'render_callback'     => 'jobscout_get_footer_nav_menu_html',
        'container_inclusive' => false,
    ) );

    // Mạng xã hội
    $wp_customize->add_setting(
        'footer_facebook_url',
        array(
            'default'           => 'https://facebook.com',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control(
        'footer_facebook_url',
        array(
            'label'   => __( 'Liên kết Facebook', 'jobscout' ),
            'section' => 'footer_settings',
            'type'    => 'url',
        )
    );

    $wp_customize->add_setting(
        'footer_google_url',
        array(
            'default'           => 'https://google.com',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control(
        'footer_google_url',
        array(
            'label'   => __( 'Liên kết Google', 'jobscout' ),
            'section' => 'footer_settings',
            'type'    => 'url',
        )
    );

    $wp_customize->add_setting(
        'footer_line_url',
        array(
            'default'           => 'https://line.me',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control(
        'footer_line_url',
        array(
            'label'   => __( 'Liên kết LINE', 'jobscout' ),
            'section' => 'footer_settings',
            'type'    => 'url',
        )
    );

    $wp_customize->add_setting(
        'footer_twitter_url',
        array(
            'default'           => 'https://twitter.com',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control(
        'footer_twitter_url',
        array(
            'label'   => __( 'Liên kết Twitter / X', 'jobscout' ),
            'section' => 'footer_settings',
            'type'    => 'url',
        )
    );

    /** Cây bút cho Hàng Icon Mạng Xã Hội */
    $wp_customize->selective_refresh->add_partial( 'footer_social_row_partial', array(
        'selector'            => '.site-footer-module .ft-social-row',
        'settings'            => array( 'footer_facebook_url', 'footer_google_url', 'footer_line_url', 'footer_twitter_url' ),
        'render_callback'     => 'jobscout_get_footer_social_row_html',
        'container_inclusive' => false,
    ) );

    /* ========================================================
     * 3. BOTTOM BAR: COPYRIGHT TEXT
     * ======================================================== */
    $wp_customize->add_setting(
        'footer_copyright_text',
        array(
            'default'           => '&copy; {year} Plan Do See Global Inc. All rights reserved.',
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control(
        'footer_copyright_text',
        array(
            'label'       => __( 'Nội dung bản quyền (Copyright)', 'jobscout' ),
            'description' => __( 'Dùng {year} để tự động hiển thị năm hiện tại.', 'jobscout' ),
            'section'     => 'footer_settings',
            'type'        => 'textarea',
        )
    );
    
    /** Cây bút cho Bản quyền Footer */
    $wp_customize->selective_refresh->add_partial( 'footer_copyright_partial', array(
        'selector'            => '.site-footer-module .ft-copyright',
        'settings'            => array( 'footer_copyright_text' ),
        'render_callback'     => 'jobscout_get_footer_copyright_text',
        'container_inclusive' => false,
    ) );

}
endif;
add_action( 'customize_register', 'jobscout_customize_register_footer' );

/**
 * Render Callbacks cho Selective Refresh ở Footer
 */
if ( ! function_exists( 'jobscout_get_footer_newsletter_title' ) ) :
function jobscout_get_footer_newsletter_title() {
    $t1 = get_theme_mod( 'footer_newsletter_title_1', 'Subscribe To' );
    $t2 = get_theme_mod( 'footer_newsletter_title_2', 'Our Newsletter' );
    return '<span>' . esc_html( $t1 ) . '</span> <span>' . esc_html( $t2 ) . '</span>';
}
endif;

if ( ! function_exists( 'jobscout_get_footer_brand' ) ) :
function jobscout_get_footer_brand() {
    $name = get_theme_mod( 'footer_brand_name', 'Plan Do See Global Inc.' );
    return '<span class="ft-brand-title">' . esc_html( $name ) . '</span>';
}
endif;

if ( ! function_exists( 'jobscout_get_footer_nav_menu_html' ) ) :
function jobscout_get_footer_nav_menu_html( $nav_menu = null, $instance = array() ) {
    if ( null === $nav_menu || 0 === $nav_menu ) {
        $customizer_menu = (int) get_theme_mod( 'footer_menu_select', 0 );
        if ( ! empty( $customizer_menu ) ) {
            $nav_menu = $customizer_menu;
        }
    }

    if ( ! empty( $nav_menu ) && wp_get_nav_menu_object( $nav_menu ) ) {
        return wp_nav_menu( array(
            'menu'        => $nav_menu,
            'container'   => false,
            'menu_class'  => 'ft-nav-menu',
            'fallback_cb' => false,
            'depth'       => 1,
            'echo'        => false,
        ) );
    }

    $default_texts = array(
        1 => 'VIỆC LÀM',
        2 => 'CÔNG TY',
        3 => 'BÀI VIẾT',
        4 => 'GIỚI THIỆU',
        5 => 'LIÊN HỆ',
    );

    $default_urls = array(
        1 => home_url( '/jobs/' ),
        2 => home_url( '/jobs/' ),
        3 => home_url( '/blog/' ),
        4 => home_url( '/about/' ),
        5 => home_url( '/contact/' ),
    );

    $output = '<ul class="ft-nav-menu">';
    for ( $i = 1; $i <= 5; $i++ ) {
        $inst_text = ! empty( $instance[ "link_{$i}_text" ] ) ? $instance[ "link_{$i}_text" ] : $default_texts[ $i ];
        $inst_url  = ! empty( $instance[ "link_{$i}_url" ] ) ? $instance[ "link_{$i}_url" ] : $default_urls[ $i ];

        $text = get_theme_mod( "footer_link_text_{$i}", $inst_text );
        $url  = get_theme_mod( "footer_link_url_{$i}", $inst_url );

        if ( ! empty( $text ) ) {
            $output .= '<li class="ft-nav-item"><a href="' . esc_url( $url ) . '" class="ft-nav-link">' . esc_html( $text ) . '</a></li>';
        }
    }
    $output .= '</ul>';

    return $output;
}
endif;

if ( ! function_exists( 'jobscout_get_footer_social_row_html' ) ) :
function jobscout_get_footer_social_row_html( $facebook_url = null, $google_url = null, $line_url = null, $twitter_url = null ) {
    if ( null === $facebook_url ) {
        $facebook_url = get_theme_mod( 'footer_facebook_url', 'https://facebook.com' );
    }
    if ( null === $google_url ) {
        $google_url = get_theme_mod( 'footer_google_url', 'https://google.com' );
    }
    if ( null === $line_url ) {
        $line_url = get_theme_mod( 'footer_line_url', 'https://line.me' );
    }
    if ( null === $twitter_url ) {
        $twitter_url = get_theme_mod( 'footer_twitter_url', 'https://twitter.com' );
    }

    $output = '';
    if ( ! empty( $facebook_url ) ) {
        $output .= '<a href="' . esc_url( $facebook_url ) . '" target="_blank" rel="noopener noreferrer" class="ft-social-btn ft-social-facebook" title="Facebook" aria-label="Facebook">';
        $output .= '<svg class="ft-social-svg" viewBox="0 0 24 24" fill="currentColor"><path d="M14 13.5h2.5l1-4H14v-2c0-1.03.3-1.75 1.75-1.75H17.5V2.31C16.85 2.22 15.65 2.1 14.28 2.1c-2.85 0-4.78 1.74-4.78 4.92v2.48H6.5v4h3v9.5h4.5v-9.5z"/></svg>';
        $output .= '</a>';
    }

    if ( ! empty( $google_url ) ) {
        $output .= '<a href="' . esc_url( $google_url ) . '" target="_blank" rel="noopener noreferrer" class="ft-social-btn ft-social-google" title="Google" aria-label="Google">';
        $output .= '<svg class="ft-social-svg" viewBox="0 0 24 24">';
        $output .= '<path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.665-5.17 3.665-9.17z"/>';
        $output .= '<path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.25v3.15C3.26 21.36 7.33 24 12 24z"/>';
        $output .= '<path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.13-1.55.38-2.27V6.58H1.25C.45 8.16 0 9.94 0 12s.45 3.84 1.25 5.42l4.03-3.15z"/>';
        $output .= '<path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.33 0 3.26 2.64 1.25 6.58l4.03 3.15c.95-2.83 3.6-4.98 6.72-4.98z"/>';
        $output .= '</svg>';
        $output .= '</a>';
    }

    if ( ! empty( $line_url ) ) {
        $output .= '<a href="' . esc_url( $line_url ) . '" target="_blank" rel="noopener noreferrer" class="ft-social-btn ft-social-line" title="LINE" aria-label="LINE">';
        $output .= '<svg class="ft-social-svg" viewBox="0 0 24 24" fill="currentColor">';
        $output .= '<path d="M21.9 10.5c0-4.69-4.88-8.5-10.9-8.5C4.98 2 0.1 5.81 0.1 10.5c0 4.2 3.86 7.72 9.09 8.37.35.08.83.23.95.53.11.27.07.7.04.97-.06.42-.27 1.65-.29 1.81-.08.47-.38 1.86 1.63 1.01 2.01-.84 5.43-3.2 7.41-5.48 1.97-2.22 2.97-4.49 2.97-7.21zm-14.7 2.22H5.66c-.3 0-.54-.24-.54-.54V8.41c0-.3.24-.54.54-.54h1.54c.3 0 .54.24.54.54 0 .3-.24.54-.54.54h-1v1.17h1c.3 0 .54.24.54.54 0 .3-.24.54-.54.54zm2.63-.54c0 .3-.24.54-.54.54s-.54-.24-.54-.54V8.41c0-.3.24-.54.54-.54s.54.24.54.54v3.77zm4.27 0c0 .24-.16.45-.39.52-.05.01-.1.02-.15.02-.19 0-.37-.1-.47-.26l-1.89-2.58v2.32c0 .3-.24.54-.54.54s-.54-.24-.54-.54V8.41c0-.24.16-.45.39-.52.05-.01.1-.02.15-.02.19 0 .37.1.47.26l1.89 2.58V8.41c0-.3.24-.54.54-.54s.54.24.54.54v3.77zm3.17-2.6h-1v.79h1c.3 0 .54.24.54.54 0 .3-.24.54-.54.54h-1.54c-.3 0-.54-.24-.54-.54V8.41c0-.3.24-.54.54-.54h1.54c.3 0 .54.24.54.54 0 .3-.24.54-.54.54h-1v.74h1c.3 0 .54.24.54.54 0 .3-.24.54-.54.54z"/>';
        $output .= '</svg>';
        $output .= '</a>';
    }

    if ( ! empty( $twitter_url ) ) {
        $output .= '<a href="' . esc_url( $twitter_url ) . '" target="_blank" rel="noopener noreferrer" class="ft-social-btn ft-social-twitter" title="Twitter / X" aria-label="Twitter / X">';
        $output .= '<svg class="ft-social-svg" viewBox="0 0 24 24" fill="currentColor">';
        $output .= '<path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.936 9.936 0 0024 4.59z"/>';
        $output .= '</svg>';
        $output .= '</a>';
    }

    return $output;
}
endif;

if ( ! function_exists( 'jobscout_get_footer_copyright_text' ) ) :
function jobscout_get_footer_copyright_text() {
    $cp = get_theme_mod( 'footer_copyright_text', '&copy; {year} Plan Do See Global Inc. All rights reserved.' );
    $cp = str_replace( '{year}', date( 'Y' ), $cp );
    return wp_kses_post( $cp );
}
endif;