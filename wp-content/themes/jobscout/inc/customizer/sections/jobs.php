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

    // 5. Setting & Control: Chữ trên nút xem thêm (Load More Button Text)
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
            'priority'    => 40,
        )
    );

    // 6. Setting & Control: Số công việc mỗi lần hiển thị
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
            'priority'    => 50,
        )
    );

    // Selective Refresh cho việc xem trước trực tiếp khi gõ chữ
    if ( isset( $wp_customize->selective_refresh ) ) {
        $wp_customize->selective_refresh->add_partial(
            'jobs_page_banner_title',
            array(
                'selector'        => '#aj-hero-banner-title',
                'render_callback' => function() {
                    return esc_html( get_theme_mod( 'jobs_page_banner_title', 'CƠ HỘI NGHỀ NGHIỆP' ) );
                },
            )
        );
        $wp_customize->selective_refresh->add_partial(
            'jobs_page_banner_subtitle',
            array(
                'selector'        => '#aj-hero-banner-subtitle',
                'render_callback' => function() {
                    return esc_html( get_theme_mod( 'jobs_page_banner_subtitle', 'Khám phá các vị trí tuyển dụng mới nhất năm 2026 và đồng hành phát triển sự nghiệp cùng chúng tôi.' ) );
                },
            )
        );
        $wp_customize->selective_refresh->add_partial(
            'jobs_page_section_title',
            array(
                'selector'        => '#aj-section-title',
                'render_callback' => function() {
                    return esc_html( get_theme_mod( 'jobs_page_section_title', 'TẤT CẢ VIỆC LÀM' ) );
                },
            )
        );
        $wp_customize->selective_refresh->add_partial(
            'jobs_page_load_more_text',
            array(
                'selector'        => '#aj-load-more-btn',
                'render_callback' => function() {
                    return esc_html( get_theme_mod( 'jobs_page_load_more_text', 'XEM THÊM VIỆC LÀM' ) );
                },
            )
        );
    }
}
endif;
add_action( 'customize_register', 'jobscout_customize_register_jobs_section' );
