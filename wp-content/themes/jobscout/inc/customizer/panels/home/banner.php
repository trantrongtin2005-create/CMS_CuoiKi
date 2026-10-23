<?php
/**
 * Banner Section
 *
 * @package jobscout
 */
if ( ! function_exists( 'jobscout_customize_register_frontpage_banner' ) ) :

function jobscout_customize_register_frontpage_banner( $wp_customize ) {
	
    $wp_customize->get_section( 'header_image' )->panel                    = 'frontpage_settings';
    $wp_customize->get_section( 'header_image' )->title                    = __( 'Banner Section', 'jobscout' );
    $wp_customize->get_section( 'header_image' )->priority                 = 10;
    $wp_customize->get_section( 'header_image' )->description              = '';                                               
    $wp_customize->get_setting( 'header_image' )->transport                = 'refresh';
    $wp_customize->get_setting( 'header_video' )->transport                = 'refresh';
    $wp_customize->get_setting( 'external_header_video' )->transport       = 'refresh';
    
    /** Banner Options */
    $wp_customize->add_setting(
		'ed_banner_section',
		array(
			'default'			=> true,
			'sanitize_callback' => 'jobscout_sanitize_checkbox'
		)
	);

	$wp_customize->add_control(
		new JobScout_Toggle_Control(
    		$wp_customize,
    		'ed_banner_section',
    		array(
                'label'       => __( 'Enable Banner Section', 'jobscout' ),
                'description' => __( 'Enable to show banner section.', 'jobscout' ),
                'section'     => 'header_image',
                'priority'    => 5	
     		)            
		)
	);
    
    /** Title */
    $wp_customize->add_setting(
        'banner_title',
        array(
            'default'           => __( 'TÌM KIẾM CÔNG VIỆC MƠ ƯỚC', 'jobscout' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'banner_title',
        array(
            'label'           => __( 'Title', 'jobscout' ),
            'section'         => 'header_image',
            'type'            => 'text',
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'banner_title', array(
        'selector'        => '.hero-dream-title',
        'render_callback' => 'jobscout_get_banner_title',
        'fallback_refresh'=> true,
    ) );
    
    /** Sub Title */
    $wp_customize->add_setting(
        'banner_subtitle',
        array(
            'default'           => __( 'Bí quyết đằng sau thành công của chúng tôi rất đơn giản: luôn đặt mình vào vị trí của người khác – dù là nhân viên, đối tác hay khách hàng. Điều đó giúp chúng tôi thấu hiểu sâu sắc, đón đầu nhu cầu và đồng hành trọn vẹn cùng bạn.', 'jobscout' ),
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'banner_subtitle',
        array(
            'label'           => __( 'Sub Title', 'jobscout' ),
            'section'         => 'header_image',
            'type'            => 'textarea',
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'banner_subtitle', array(
        'selector'        => '.hero-dream-subtitle',
        'render_callback' => 'jobscout_get_banner_sub_title',
        'fallback_refresh'=> true,
    ) );

    /** Search Keyword Placeholder */
    $wp_customize->add_setting(
        'banner_keyword_placeholder',
        array(
            'default'           => 'Tìm kiếm theo vị trí, công ty, kỹ năng...',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control(
        'banner_keyword_placeholder',
        array(
            'label'       => __( 'Placeholder ô Tìm việc', 'jobscout' ),
            'description' => __( 'Nhập placeholder ô tìm kiếm từ khóa', 'jobscout' ),
            'section'     => 'header_image',
            'type'        => 'text',
        )
    );

    $wp_customize->selective_refresh->add_partial( 'banner_keyword_placeholder', array(
        'selector'        => '.hero-input-group-keyword',
        'settings'        => array( 'banner_keyword_placeholder' ),
        'render_callback' => '__return_false',
        'fallback_refresh'=> true,
    ) );

    /** Search Location Default */
    $wp_customize->add_setting(
        'banner_location_default',
        array(
            'default'           => 'Hà Nội',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control(
        'banner_location_default',
        array(
            'label'       => __( 'Địa điểm chọn sẵn (Mặc định)', 'jobscout' ),
            'description' => __( 'Địa điểm hiển thị đầu tiên trên ô chọn (ví dụ: Tokyo)', 'jobscout' ),
            'section'     => 'header_image',
            'type'        => 'text',
        )
    );

    $wp_customize->selective_refresh->add_partial( 'banner_location_default', array(
        'selector'        => '.hero-input-group-location',
        'settings'        => array( 'banner_location_default', 'banner_locations_list' ),
        'render_callback' => '__return_false',
        'fallback_refresh'=> true,
    ) );

    /** Custom Locations List */
    $wp_customize->add_setting(
        'banner_locations_list',
        array(
            'default'           => "Hà Nội\nTP. Hồ Chí Minh\nĐà Nẵng\nTokyo\nOsaka\nKyoto\nYokohama\nNagoya\nFukuoka\nSapporo",
            'sanitize_callback' => 'sanitize_textarea_field',
        )
    );
    $wp_customize->add_control(
        'banner_locations_list',
        array(
            'label'       => __( 'Danh sách địa điểm trong Menu thả xuống', 'jobscout' ),
            'description' => __( 'Nhập danh sách địa điểm tùy ý tại đây (mỗi dòng một thành phố/địa điểm)', 'jobscout' ),
            'section'     => 'header_image',
            'type'        => 'textarea',
        )
    );

    /** Search Button Label */
    $wp_customize->add_setting(
        'banner_btn_text',
        array(
            'default'           => 'TÌM VIỆC NGAY',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control(
        'banner_btn_text',
        array(
            'label'       => __( 'Chữ trên nút tìm kiếm', 'jobscout' ),
            'description' => __( 'Ví dụ: SEARCH JOB', 'jobscout' ),
            'section'     => 'header_image',
            'type'        => 'text',
        )
    );

    $wp_customize->selective_refresh->add_partial( 'banner_btn_text', array(
        'selector'        => '.hero-search-submit-wrap',
        'settings'        => array( 'banner_btn_text' ),
        'render_callback' => function() {
            $btn_text = get_theme_mod( 'banner_btn_text', 'TÌM VIỆC NGAY' );
            return '<button type="submit" class="hero-btn-search">' . esc_html( $btn_text ) . '</button>';
        },
        'fallback_refresh'=> true,
    ) );

    /** Banner Image Shortcut */
    $wp_customize->selective_refresh->add_partial( 'banner_image_shortcut', array(
        'selector'        => '#banner-section',
        'settings'        => array( 'header_image' ),
        'render_callback' => '__return_false',
        'fallback_refresh'=> true,
    ) );
    
}
endif;
add_action( 'customize_register', 'jobscout_customize_register_frontpage_banner' );