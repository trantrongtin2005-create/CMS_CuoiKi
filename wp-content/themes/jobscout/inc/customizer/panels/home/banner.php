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
            'default'           => __( 'Aim Higher, Dream Bigger', 'jobscout' ),
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
        'selector' => '.site-banner .banner-caption h2.title',
        'render_callback' => 'jobscout_get_banner_title',
    ) );
    
    /** Sub Title */
    $wp_customize->add_setting(
        'banner_subtitle',
        array(
            'default'           => __( 'Each month, more than 7 million JobScout turn to website in their search for work, making over 160,000 applications every day.', 'jobscout' ),
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
        'selector'        => '.site-banner .banner-caption .description',
        'render_callback' => 'jobscout_get_banner_sub_title',
    ) );

    /** Search Keyword Placeholder */
    $wp_customize->add_setting(
        'banner_keyword_placeholder',
        array(
            'default'           => 'Search for jobs, companies, skills',
            'sanitize_callback' => 'sanitize_text_field',
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

    /** Search Location Default */
    $wp_customize->add_setting(
        'banner_location_default',
        array(
            'default'           => 'Tokyo',
            'sanitize_callback' => 'sanitize_text_field',
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

    /** Custom Locations List */
    $wp_customize->add_setting(
        'banner_locations_list',
        array(
            'default'           => "Tokyo\nOsaka\nKyoto\nYokohama\nNagoya\nFukuoka\nSapporo",
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
            'default'           => 'SEARCH JOB',
            'sanitize_callback' => 'sanitize_text_field',
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
    
}
endif;
add_action( 'customize_register', 'jobscout_customize_register_frontpage_banner' );