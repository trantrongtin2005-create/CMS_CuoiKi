<?php
/**
 * Job Listings Page Customizer Settings
 * Cho phép chỉnh sửa toàn bộ nội dung của trang Danh sách việc làm trực tiếp trong Tùy biến (Customizer)
 *
 * @package jobscout
 */

if ( ! function_exists( 'jobscout_customize_register_jobs_section' ) ) :

function jobscout_customize_register_jobs_section( $wp_customize ) {

    // 1. Thêm Section: Cấu hình Trang Danh sách việc làm (Jobs Page)
    $wp_customize->add_section(
        'jobscout_jobs_page_section',
        array(
            'title'       => __( '⭐ Trang Danh sách việc làm (Jobs Page)', 'jobscout' ),
            'priority'    => 40,
            'capability'  => 'edit_theme_options',
            'description' => __( 'Tùy chỉnh ảnh bìa Banner, tiêu đề và các nội dung hiển thị trên trang Việc làm trực tiếp trên giao diện.', 'jobscout' ),
        )
    );

    // 2. Setting & Control: Ảnh nền Banner (Banner Background Image)
    $wp_customize->add_setting(
        'jobs_page_banner_image',
        array(
            'default'           => get_template_directory_uri() . '/images/career-banner.jpg',
            'sanitize_callback' => 'esc_url_raw',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'jobs_page_banner_image',
            array(
                'label'       => __( 'Ảnh bìa Banner', 'jobscout' ),
                'description' => __( 'Tải lên hoặc chọn ảnh nền cho phần đầu trang việc làm.', 'jobscout' ),
                'section'     => 'jobscout_jobs_page_section',
                'priority'    => 10,
            )
        )
    );

    // 3. Setting & Control: Tiêu đề Banner (Banner Title)
    $wp_customize->add_setting(
        'jobs_page_banner_title',
        array(
            'default'           => 'CƠ HỘI NGHỀ NGHIỆP',
            'sanitize_callback' => 'sanitize_text_field',
            'capability'        => 'edit_theme_options',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control(
        'jobs_page_banner_title',
        array(
            'label'       => __( 'Tiêu đề Banner', 'jobscout' ),
            'description' => __( 'Tiêu đề lớn màu trắng hiển thị trên ảnh bìa.', 'jobscout' ),
            'section'     => 'jobscout_jobs_page_section',
            'type'        => 'text',
            'priority'    => 20,
        )
    );

    // 3b. Setting & Control: Mô tả phụ Banner (Banner Subtitle)
    $wp_customize->add_setting(
        'jobs_page_banner_subtitle',
        array(
            'default'           => 'Khám phá các vị trí tuyển dụng mới nhất năm 2026 và đồng hành phát triển sự nghiệp cùng chúng tôi.',
            'sanitize_callback' => 'sanitize_text_field',
            'capability'        => 'edit_theme_options',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control(
        'jobs_page_banner_subtitle',
        array(
            'label'       => __( 'Mô tả phụ Banner', 'jobscout' ),
            'description' => __( 'Đoạn mô tả màu trắng hiển thị bên dưới tiêu đề lớn trên ảnh bìa.', 'jobscout' ),
            'section'     => 'jobscout_jobs_page_section',
            'type'        => 'text',
            'priority'    => 25,
        )
    );

    // 4. Setting & Control: Tiêu đề danh sách (Section Heading)
    $wp_customize->add_setting(
        'jobs_page_section_title',
        array(
            'default'           => 'TẤT CẢ VIỆC LÀM',
            'sanitize_callback' => 'sanitize_text_field',
            'capability'        => 'edit_theme_options',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control(
        'jobs_page_section_title',
        array(
            'label'       => __( 'Tiêu đề danh sách công việc', 'jobscout' ),
            'description' => __( 'Tiêu đề phía trên lưới công việc (mặc định: TẤT CẢ VIỆC LÀM).', 'jobscout' ),
            'section'     => 'jobscout_jobs_page_section',
            'type'        => 'text',
            'priority'    => 30,
        )
    );

    // 4b. Setting & Control: Mô tả phụ danh sách (Section Description)
    $wp_customize->add_setting(
        'jobs_page_section_desc',
        array(
            'default'           => 'Khám phá các cơ hội nghề nghiệp hàng đầu từ các doanh nghiệp và tập đoàn uy tín năm 2026.',
            'sanitize_callback' => 'sanitize_text_field',
            'capability'        => 'edit_theme_options',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control(
        'jobs_page_section_desc',
        array(
            'label'       => __( 'Mô tả danh sách công việc', 'jobscout' ),
            'description' => __( 'Đoạn mô tả ngắn hiển thị bên dưới tiêu đề TẤT CẢ VIỆC LÀM.', 'jobscout' ),
            'section'     => 'jobscout_jobs_page_section',
            'type'        => 'text',
            'priority'    => 35,
        )
    );

    // 5. Setting & Control: Placeholder ô tìm kiếm (Search Placeholder)
    $wp_customize->add_setting(
        'jobs_page_search_placeholder',
        array(
            'default'           => 'Tìm kiếm theo vị trí, công ty, kỹ năng...',
            'sanitize_callback' => 'sanitize_text_field',
            'capability'        => 'edit_theme_options',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control(
        'jobs_page_search_placeholder',
        array(
            'label'       => __( 'Placeholder ô tìm kiếm', 'jobscout' ),
            'description' => __( 'Văn bản gợi ý hiển thị bên trong ô tìm kiếm việc làm.', 'jobscout' ),
            'section'     => 'jobscout_jobs_page_section',
            'type'        => 'text',
            'priority'    => 40,
        )
    );

    // 5b. Setting & Control: Chữ trên nút tìm kiếm (Search Button Text)
    $wp_customize->add_setting(
        'jobs_page_search_btn_text',
        array(
            'default'           => 'TÌM KIẾM',
            'sanitize_callback' => 'sanitize_text_field',
            'capability'        => 'edit_theme_options',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control(
        'jobs_page_search_btn_text',
        array(
            'label'       => __( 'Chữ trên nút Tìm kiếm', 'jobscout' ),
            'description' => __( 'Văn bản trên nút bấm tìm kiếm màu cam.', 'jobscout' ),
            'section'     => 'jobscout_jobs_page_section',
            'type'        => 'text',
            'priority'    => 45,
        )
    );

    // 6. Setting & Control: Chữ trên nút xem thêm (Load More Button Text)
    $wp_customize->add_setting(
        'jobs_page_load_more_text',
        array(
            'default'           => 'XEM THÊM VIỆC LÀM',
            'sanitize_callback' => 'sanitize_text_field',
            'capability'        => 'edit_theme_options',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control(
        'jobs_page_load_more_text',
        array(
            'label'       => __( 'Chữ trên nút Xem thêm', 'jobscout' ),
            'description' => __( 'Văn bản của nút phân trang tải thêm công việc.', 'jobscout' ),
            'section'     => 'jobscout_jobs_page_section',
            'type'        => 'text',
            'priority'    => 50,
        )
    );

    // 7. Setting & Control: Số công việc mỗi lần hiển thị
    $wp_customize->add_setting(
        'jobs_page_per_page',
        array(
            'default'           => 6,
            'sanitize_callback' => 'absint',
            'capability'        => 'edit_theme_options',
        )
    );
    $wp_customize->add_control(
        'jobs_page_per_page',
        array(
            'label'       => __( 'Số lượng việc làm mỗi trang', 'jobscout' ),
            'description' => __( 'Số công việc xuất hiện ban đầu và mỗi lần bấm nút LOAD MORE.', 'jobscout' ),
            'section'     => 'jobscout_jobs_page_section',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 2,
                'max'  => 50,
                'step' => 2,
            ),
            'priority'    => 55,
        )
    );

    // ========================================================
    // SELECTIVE REFRESH: CÁC NÚT CÂY BÚT CHỈNH SỬA TRỰC TIẾP TRÊN TRANG (EDIT SHORTCUTS)
    // ========================================================
    if ( isset( $wp_customize->selective_refresh ) ) {

        // 1. Cây bút cho Ảnh bìa Banner
        $wp_customize->selective_refresh->add_partial(
            'jobs_page_banner_image_shortcut',
            array(
                'selector'        => '.jobs-hero-block',
                'settings'        => array( 'jobs_page_banner_image' ),
                'render_callback' => '__return_false',
                'fallback_refresh'=> true,
            )
        );

        // 2. Cây bút cho Tiêu đề Banner
        $wp_customize->selective_refresh->add_partial(
            'jobs_page_banner_title_shortcut',
            array(
                'selector'        => '.jobs-banner-title',
                'settings'        => array( 'jobs_page_banner_title' ),
                'render_callback' => function() {
                    return esc_html( get_theme_mod( 'jobs_page_banner_title', 'CƠ HỘI NGHỀ NGHIỆP' ) );
                },
                'fallback_refresh'=> true,
            )
        );

        // 3. Cây bút cho Mô tả phụ Banner
        $wp_customize->selective_refresh->add_partial(
            'jobs_page_banner_subtitle_shortcut',
            array(
                'selector'        => '.jobs-banner-subtitle',
                'settings'        => array( 'jobs_page_banner_subtitle' ),
                'render_callback' => function() {
                    return esc_html( get_theme_mod( 'jobs_page_banner_subtitle', 'Khám phá các vị trí tuyển dụng mới nhất năm 2026 và đồng hành phát triển sự nghiệp cùng chúng tôi.' ) );
                },
                'fallback_refresh'=> true,
            )
        );

        // 4. Cây bút cho Tiêu đề danh sách công việc
        $wp_customize->selective_refresh->add_partial(
            'jobs_page_section_title_shortcut',
            array(
                'selector'        => '.jobs-section-heading',
                'settings'        => array( 'jobs_page_section_title' ),
                'render_callback' => function() {
                    return esc_html( get_theme_mod( 'jobs_page_section_title', 'TẤT CẢ VIỆC LÀM' ) );
                },
                'fallback_refresh'=> true,
            )
        );

        // 5. Cây bút cho Mô tả danh sách công việc
        $wp_customize->selective_refresh->add_partial(
            'jobs_page_section_desc_shortcut',
            array(
                'selector'        => '.jobs-section-desc',
                'settings'        => array( 'jobs_page_section_desc' ),
                'render_callback' => function() {
                    return esc_html( get_theme_mod( 'jobs_page_section_desc', 'Khám phá các cơ hội nghề nghiệp hàng đầu từ các doanh nghiệp và tập đoàn uy tín năm 2026.' ) );
                },
                'fallback_refresh'=> true,
            )
        );

        // 6. Cây bút cho Ô tìm kiếm từ khóa
        $wp_customize->selective_refresh->add_partial(
            'jobs_page_search_placeholder_shortcut',
            array(
                'selector'        => '.aj-field-keyword',
                'settings'        => array( 'jobs_page_search_placeholder' ),
                'render_callback' => '__return_false',
                'fallback_refresh'=> true,
            )
        );

        // 7. Cây bút cho Nút Tìm kiếm
        $wp_customize->selective_refresh->add_partial(
            'jobs_page_search_btn_shortcut',
            array(
                'selector'        => '#aj-search-btn',
                'settings'        => array( 'jobs_page_search_btn_text' ),
                'render_callback' => '__return_false',
                'fallback_refresh'=> true,
            )
        );

        // 8. Cây bút cho Nút Xem thêm việc làm
        $wp_customize->selective_refresh->add_partial(
            'jobs_page_load_more_shortcut',
            array(
                'selector'        => '#aj-load-more-btn',
                'settings'        => array( 'jobs_page_load_more_text' ),
                'render_callback' => function() {
                    return esc_html( get_theme_mod( 'jobs_page_load_more_text', 'XEM THÊM VIỆC LÀM' ) );
                },
                'fallback_refresh'=> true,
            )
        );

        // 9. Cây bút cho Số lượng việc làm hiển thị
        $wp_customize->selective_refresh->add_partial(
            'jobs_page_per_page_shortcut',
            array(
                'selector'        => '#aj-count-badge',
                'settings'        => array( 'jobs_page_per_page' ),
                'render_callback' => '__return_false',
                'fallback_refresh'=> true,
            )
        );
    }
}
endif;
add_action( 'customize_register', 'jobscout_customize_register_jobs_section' );