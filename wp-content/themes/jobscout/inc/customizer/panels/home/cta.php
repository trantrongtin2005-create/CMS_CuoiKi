<?php
/**
 * Call To Action Section (CAREER WITH US MODULE)
 *
 * Tạo các ô nhập liệu trong Giao diện → Tùy biến → Front Page Settings → Career With Us Section
 * để chỉnh nội dung module ở sections/cta.php mà không cần sửa code.
 *
 * @package jobscout
 */

if ( ! function_exists( 'jobscout_get_cta_career_defaults' ) ) :
/**
 * Giá trị mặc định của module CAREER WITH US.
 * Dùng chung cho Customizer và sections/cta.php để không bị lặp nội dung.
 *
 * @return array
 */
function jobscout_get_cta_career_defaults(){
    return array(
        'ed_cta_section'      => true,
        'cta_career_title'    => 'CAREER WITH US',
        'cta_career_text_1'   => 'Plan Do See Global is a hospitality group founded in Japan and rooted in "Omotenashi", the Japanese principle of selfless hospitality. We strive to deliver unforgettable and bespoke experiences, to understand local cultures like natives, to provide service that is warm but not intrusive, and to foresee our guests\' every need at all times. That is our sole mission and purpose.',
        'cta_career_text_2'   => 'We are experts in all stages of project development: concept, design, implementation and management. We love to find unique ways to create experiences that surprise and delight guests and customers.',
        'cta_career_btn_text' => 'MORE ABOUT US',
        'cta_career_btn_url'  => home_url( '/about/' ),
        'cta_career_bg'       => get_template_directory_uri() . '/images/career-with-us-bg.jpg',
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

    /** Bật/Tắt module */
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

    /** Tiêu đề */
    $wp_customize->add_setting(
        'cta_career_title',
        array(
            'default'           => $defaults['cta_career_title'],
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'cta_career_title',
        array(
            'section' => 'cta_section',
            'label'   => __( 'Tiêu đề', 'jobscout' ),
            'type'    => 'text',
        )
    );

    /** Đoạn văn 1 */
    $wp_customize->add_setting(
        'cta_career_text_1',
        array(
            'default'           => $defaults['cta_career_text_1'],
            'sanitize_callback' => 'sanitize_textarea_field',
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

    /** Đoạn văn 2 */
    $wp_customize->add_setting(
        'cta_career_text_2',
        array(
            'default'           => $defaults['cta_career_text_2'],
            'sanitize_callback' => 'sanitize_textarea_field',
        )
    );

    $wp_customize->add_control(
        'cta_career_text_2',
        array(
            'section'     => 'cta_section',
            'label'       => __( 'Đoạn văn 2', 'jobscout' ),
            'description' => __( 'Để trống nếu không muốn hiển thị.', 'jobscout' ),
            'type'        => 'textarea',
        )
    );

    /** Chữ trên nút */
    $wp_customize->add_setting(
        'cta_career_btn_text',
        array(
            'default'           => $defaults['cta_career_btn_text'],
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'cta_career_btn_text',
        array(
            'section'     => 'cta_section',
            'label'       => __( 'Chữ trên nút', 'jobscout' ),
            'description' => __( 'Để trống để ẩn nút.', 'jobscout' ),
            'type'        => 'text',
        )
    );

    /** Link của nút */
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
            'label'   => __( 'Link của nút', 'jobscout' ),
            'type'    => 'url',
        )
    );

    /** Ảnh nền */
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
                'label'       => __( 'Ảnh nền', 'jobscout' ),
                'description' => __( 'Nên dùng ảnh ngang, tối màu, tối thiểu 1600px chiều rộng.', 'jobscout' ),
            )
        )
    );
    /** Career With Us Section Ends */

}
endif;
add_action( 'customize_register', 'jobscout_customize_register_frontpage_cta' );