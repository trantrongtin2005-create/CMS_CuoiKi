<?php
/**
 * Site Title Setting
 *
 * @package jobscout
 */
if ( ! function_exists( 'jobscout_customize_register' ) ) :

function jobscout_customize_register( $wp_customize ) {
	
    if ( $wp_customize->get_setting( 'blogname' ) ) {
        $wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
    }
    if ( $wp_customize->get_setting( 'blogdescription' ) ) {
        $wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
    }
    if ( $wp_customize->get_setting( 'background_color' ) ) {
        $wp_customize->get_setting( 'background_color' )->transport = 'refresh';
    }
    if ( $wp_customize->get_setting( 'background_image' ) ) {
        $wp_customize->get_setting( 'background_image' )->transport = 'refresh';
    }
	
	if( isset( $wp_customize->selective_refresh ) ){
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => 'jobscout_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => 'jobscout_customize_partial_blogdescription',
		) );
	}

    /**
     * Tùy chỉnh màu sắc Logo chữ (Site Title)
     */
    $wp_customize->add_setting(
        'logo_color',
        array(
            'default'           => '#000000',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'logo_color',
            array(
                'label'       => __( 'Màu chữ Logo / Tên website', 'jobscout' ),
                'description' => __( 'Tùy chỉnh màu sắc chữ cho Logo / Tên thương hiệu (CMS_CuoiKi).', 'jobscout' ),
                'section'     => 'title_tagline',
                'priority'    => 50,
            )
        )
    );

    /**
     * Màu Logo khi di chuột (Hover)
     */
    $wp_customize->add_setting(
        'logo_hover_color',
        array(
            'default'           => '#ea580c',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'logo_hover_color',
            array(
                'label'       => __( 'Màu Logo khi di chuột (Hover)', 'jobscout' ),
                'description' => __( 'Màu sắc hiển thị khi người dùng di chuột qua Logo.', 'jobscout' ),
                'section'     => 'title_tagline',
                'priority'    => 51,
            )
        )
    );
    
}
endif;
add_action( 'customize_register', 'jobscout_customize_register' );

/**
 * Xuất mã CSS tùy biến màu Logo ra giao diện
 */
if ( ! function_exists( 'jobscout_logo_custom_css' ) ) :
function jobscout_logo_custom_css() {
    $logo_color = get_theme_mod( 'logo_color', '#000000' );
    $logo_hover = get_theme_mod( 'logo_hover_color', '#ea580c' );

    $custom_css = "
    .site-branding .site-title,
    .site-branding .site-title a {
        color: " . esc_attr( $logo_color ) . " !important;
        transition: color 0.25s ease;
    }
    .site-branding .site-title a:hover,
    .site-branding .site-title a:focus {
        color: " . esc_attr( $logo_hover ) . " !important;
    }
    ";
    wp_add_inline_style( 'jobscout', $custom_css );
}
add_action( 'wp_enqueue_scripts', 'jobscout_logo_custom_css', 30 );
endif;