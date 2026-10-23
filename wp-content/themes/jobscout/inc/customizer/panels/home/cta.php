<?php
/**
 * Call To Action Section (CAREER WITH US MODULE)
 *
 * Cung cấp đầy đủ công cụ quản trị trong:
 * Giao diện → Tùy biến → Front Page Settings → Career With Us Section
 * Giúp người dùng dễ dàng thay đổi ảnh nền, màu phủ tối, căn chỉnh và toàn bộ văn bản.
 *
 * @package jobscout
 */

if ( ! function_exists( 'jobscout_get_cta_career_defaults' ) ) :
/**
 * Giá trị mặc định của module CAREER WITH US.
 *
 * @return array
 */
function jobscout_get_cta_career_defaults(){
    return array(
        'ed_cta_section'             => true,
        'cta_career_title'           => 'CƠ HỘI NGHỀ NGHIỆP',
        'cta_career_text_1'          => 'Plan Do See Global là tập đoàn dịch vụ - khách sạn được thành lập tại Nhật Bản với triết lý cốt lõi "Omotenashi" – nghệ thuật hiếu khách tận tâm. Chúng tôi không ngừng kiến tạo những trải nghiệm tinh tế và đáng nhớ, thấu hiểu văn hóa bản địa sâu sắc, mang đến dịch vụ ấm áp, chu đáo nhưng luôn tôn trọng không gian riêng tư nhằm đáp ứng mọi kỳ vọng của khách hàng. Đó chính là sứ mệnh và tôn chỉ hoạt động duy nhất của chúng tôi.',
        'cta_career_text_2'          => 'Chúng tôi là những chuyên gia giàu kinh nghiệm trong mọi giai đoạn phát triển dự án: từ xây dựng ý tưởng, thiết kế, triển khai cho đến quản lý vận hành chuyên nghiệp. Chúng tôi luôn đam mê tìm kiếm những hướng đi sáng tạo để mang đến những trải nghiệm vượt trội, làm hài lòng mọi đối tác và khách hàng.',
        'cta_career_btn_text'        => 'VỀ CHÚNG TÔI',
        'cta_career_btn_url'         => home_url( '/about/' ),
        'cta_career_btn_target'      => false,
        'cta_career_bg'              => get_template_directory_uri() . '/images/career-with-us-bg.jpg',
        'cta_career_overlay_opacity' => 65,
        'cta_career_overlay_color'   => '#140c0a',
        'cta_career_alignment'       => 'center',
    );
}
endif;

if ( ! function_exists( 'jobscout_get_cta_career_option' ) ) :
/**
 * Lấy giá trị đã lưu trong Customizer, nếu chưa có thì trả về mặc định.
 *
 * @param string $key Tên setting.
 * @return mixed
 */
function jobscout_get_cta_career_option( $key ){
    $defaults = jobscout_get_cta_career_defaults();
    $default  = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';
    return get_theme_mod( $key, $default );
}
endif;

if ( ! function_exists( 'jobscout_get_cta_career_bg_url' ) ) :
/**
 * Lấy URL ảnh nền hợp lệ (xử lý cả URL trực tiếp lẫn Attachment ID từ Media Library,
 * tự động chuẩn hóa hostname để tránh lỗi vỡ ảnh giữa localhost và server).
 *
 * @return string URL ảnh nền
 */
function jobscout_get_cta_career_bg_url(){
    $defaults = jobscout_get_cta_career_defaults();
    $raw      = get_theme_mod( 'cta_career_bg', $defaults['cta_career_bg'] );

    if ( empty( $raw ) ) {
        $raw = $defaults['cta_career_bg'];
    }

    // Nếu người dùng chọn ảnh qua Media uploader và lưu ID
    if ( is_numeric( $raw ) && (int) $raw > 0 ) {
        $img_url = wp_get_attachment_image_url( (int) $raw, 'full' );
        if ( ! empty( $img_url ) ) {
            $raw = $img_url;
        }
    }

    // Chuẩn hóa tên miền để hiển thị mượt mà trên cả localhost và domain khác
    if ( function_exists( 'jobscout_normalize_media_host' ) ) {
        $raw = jobscout_normalize_media_host( $raw );
    }

    return esc_url( $raw );
}
endif;

if ( ! function_exists( 'jobscout_cta_hex_to_rgb' ) ) :
/**
 * Chuyển đổi mã màu HEX sang RGB mảng [r, g, b].
 *
 * @param string $hex Mã màu hex.
 * @return array
 */
function jobscout_cta_hex_to_rgb( $hex ) {
    $hex = ltrim( (string) $hex, '#' );
    if ( strlen( $hex ) === 3 ) {
        $r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
        $g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
        $b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
    } elseif ( strlen( $hex ) === 6 ) {
        $r = hexdec( substr( $hex, 0, 2 ) );
        $g = hexdec( substr( $hex, 2, 2 ) );
        $b = hexdec( substr( $hex, 4, 2 ) );
    } else {
        return array( 20, 12, 10 );
    }
    return array( $r, $g, $b );
}
endif;

if ( ! function_exists( 'jobscout_customize_register_frontpage_cta' ) ) :

function jobscout_customize_register_frontpage_cta( $wp_customize ){

    $defaults = jobscout_get_cta_career_defaults();

    /** Career With Us Section */
    $wp_customize->add_section(
        'cta_section',
        array(
            'title'    => __( 'Career With Us Section', 'jobscout' ),
            'priority' => 30,
            'panel'    => 'frontpage_settings',
        )
    );

    /** 1. Bật/Tắt module */
    $wp_customize->add_setting(
        'ed_cta_section',
        array(
            'default'           => $defaults['ed_cta_section'],
            'sanitize_callback' => 'jobscout_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        new JobScout_Toggle_Control(
            $wp_customize,
            'ed_cta_section',
            array(
                'section'     => 'cta_section',
                'label'       => __( 'Bật/Tắt module Career With Us', 'jobscout' ),
                'description' => __( 'Hiển thị module Career With Us ở trang chủ (dưới Top Jobs).', 'jobscout' ),
            )
        )
    );

    /** 2. Ảnh nền (Background Image) */
    $wp_customize->add_setting(
        'cta_career_bg',
        array(
            'default'           => $defaults['cta_career_bg'],
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'cta_career_bg',
            array(
                'section'     => 'cta_section',
                'label'       => __( 'Ảnh nền (Background Image)', 'jobscout' ),
                'description' => __( 'Chọn ảnh từ Thư viện Media hoặc tải lên ảnh mới từ máy tính. Khuyên dùng ảnh ngang độ phân giải từ 1600px.', 'jobscout' ),
            )
        )
    );

    /** 3. Độ tối lớp phủ (Overlay Opacity) */
    $wp_customize->add_setting(
        'cta_career_overlay_opacity',
        array(
            'default'           => $defaults['cta_career_overlay_opacity'],
            'sanitize_callback' => 'jobscout_sanitize_number_absint',
        )
    );

    $wp_customize->add_control(
        new JobScout_Slider_Control(
            $wp_customize,
            'cta_career_overlay_opacity',
            array(
                'section'     => 'cta_section',
                'label'       => __( 'Độ tối lớp phủ (Overlay Opacity %)', 'jobscout' ),
                'description' => __( 'Tăng độ tối nếu ảnh nền sáng để chữ trắng và tiêu đề luôn sắc nét, dễ đọc.', 'jobscout' ),
                'choices'     => array(
                    'min'  => 0,
                    'max'  => 95,
                    'step' => 5,
                ),
            )
        )
    );

    /** 4. Màu lớp phủ (Overlay Color) */
    $wp_customize->add_setting(
        'cta_career_overlay_color',
        array(
            'default'           => $defaults['cta_career_overlay_color'],
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'cta_career_overlay_color',
            array(
                'section'     => 'cta_section',
                'label'       => __( 'Màu lớp phủ', 'jobscout' ),
                'description' => __( 'Mặc định tông đen / nâu trầm (#140c0a) tạo chiều sâu điện ảnh.', 'jobscout' ),
            )
        )
    );

    /** 5. Tiêu đề */
    $wp_customize->add_setting(
        'cta_career_title',
        array(
            'default'           => $defaults['cta_career_title'],
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'cta_career_title',
        array(
            'section' => 'cta_section',
            'label'   => __( 'Tiêu đề module', 'jobscout' ),
            'type'    => 'text',
        )
    );

    $wp_customize->selective_refresh->add_partial( 'cta_career_title', array(
        'selector'        => '.career-module-section .career-heading',
        'render_callback' => 'jobscout_get_cta_career_title',
        'fallback_refresh'=> true,
    ) );

    /** 6. Đoạn văn 1 */
    $wp_customize->add_setting(
        'cta_career_text_1',
        array(
            'default'           => $defaults['cta_career_text_1'],
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'cta_career_text_1',
        array(
            'section' => 'cta_section',
            'label'   => __( 'Đoạn văn 1', 'jobscout' ),
            'type'    => 'textarea',
        )
    );

    $wp_customize->selective_refresh->add_partial( 'cta_career_text_1', array(
        'selector'        => '.career-module-section .career-text-1',
        'render_callback' => 'jobscout_get_cta_career_text_1',
        'fallback_refresh'=> true,
    ) );

    /** 7. Đoạn văn 2 */
    $wp_customize->add_setting(
        'cta_career_text_2',
        array(
            'default'           => $defaults['cta_career_text_2'],
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'cta_career_text_2',
        array(
            'section'     => 'cta_section',
            'label'       => __( 'Đoạn văn 2', 'jobscout' ),
            'description' => __( 'Để trống nếu chỉ muốn hiển thị 1 đoạn văn.', 'jobscout' ),
            'type'        => 'textarea',
        )
    );

    $wp_customize->selective_refresh->add_partial( 'cta_career_text_2', array(
        'selector'        => '.career-module-section .career-text-2',
        'render_callback' => 'jobscout_get_cta_career_text_2',
        'fallback_refresh'=> true,
    ) );

    /** 8. Chữ trên nút */
    $wp_customize->add_setting(
        'cta_career_btn_text',
        array(
            'default'           => $defaults['cta_career_btn_text'],
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'cta_career_btn_text',
        array(
            'section'     => 'cta_section',
            'label'       => __( 'Chữ trên nút (Button Text)', 'jobscout' ),
            'description' => __( 'Để trống nếu muốn ẩn nút bấm.', 'jobscout' ),
            'type'        => 'text',
        )
    );

    $wp_customize->selective_refresh->add_partial( 'cta_career_btn_text', array(
        'selector'        => '.career-module-section .career-btn',
        'render_callback' => 'jobscout_get_cta_career_btn_text',
        'fallback_refresh'=> true,
    ) );

    /** 9. Link của nút */
    $wp_customize->add_setting(
        'cta_career_btn_url',
        array(
            'default'           => $defaults['cta_career_btn_url'],
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'cta_career_btn_url',
        array(
            'section' => 'cta_section',
            'label'   => __( 'Liên kết nút (Button URL)', 'jobscout' ),
            'type'    => 'url',
        )
    );

    /** 10. Mở link trong tab mới */
    $wp_customize->add_setting(
        'cta_career_btn_target',
        array(
            'default'           => $defaults['cta_career_btn_target'],
            'sanitize_callback' => 'jobscout_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'cta_career_btn_target',
        array(
            'section' => 'cta_section',
            'label'   => __( 'Mở link nút trong tab mới (target="_blank")', 'jobscout' ),
            'type'    => 'checkbox',
        )
    );

    /** 11. Căn lề nội dung */
    $wp_customize->add_setting(
        'cta_career_alignment',
        array(
            'default'           => $defaults['cta_career_alignment'],
            'sanitize_callback' => 'jobscout_sanitize_radio',
        )
    );

    $wp_customize->add_control(
        'cta_career_alignment',
        array(
            'section' => 'cta_section',
            'label'   => __( 'Căn lề nội dung', 'jobscout' ),
            'type'    => 'radio',
            'choices' => array(
                'center' => __( 'Căn giữa (Mặc định chuẩn theo mẫu)', 'jobscout' ),
                'left'   => __( 'Căn trái', 'jobscout' ),
                'right'  => __( 'Căn phải', 'jobscout' ),
            ),
        )
    );
    /** Career With Us Section Ends */

}
endif;
add_action( 'customize_register', 'jobscout_customize_register_frontpage_cta' );