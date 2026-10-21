<?php
/**
 * Job Posting Section (TOP JOBS MODULE)
 *
 * Cung cấp các điều khiển trong Giao diện → Tùy biến → Front Page Settings → Job Posting Section:
 * - Bật/tắt mục TOP JOBS
 * - Tiêu đề mục (TOP JOBS)
 * - Chế độ hiển thị: Tự động (Ưu tiên việc làm Nổi bật) hoặc Tự chọn 6 công việc cụ thể
 * - 6 Dropdown chọn việc làm từ hệ thống
 * - Nút "VIEW MORE JOBS" (Chữ & Link)
 * - 3 gạch đầu dòng quyền lợi mặc định
 *
 * @package jobscout
 */

if ( ! function_exists( 'jobscout_customize_register_frontpage_job_posting' ) ) :

function jobscout_customize_register_frontpage_job_posting( $wp_customize ){

    $wpjob_manager_activated = jobscout_is_wp_job_manager_activated();

    /** Job Posting Section */
    $wp_customize->add_section(
        'job_posting_section',
        array(
            'title'    => __( 'Top Jobs Section', 'jobscout' ),
            'priority' => 20,
            'panel'    => 'frontpage_settings',
        )
    );

    /** 1. Enable/Disable Section */
    $wp_customize->add_setting( 
        'ed_jobposting_section', 
        array(
            'default'           => true,
            'sanitize_callback' => 'jobscout_sanitize_checkbox',
        ) 
    );
    
    $wp_customize->add_control(
        new JobScout_Toggle_Control( 
            $wp_customize,
            'ed_jobposting_section',
            array(
                'section'     => 'job_posting_section',
                'label'       => __( 'Bật/Tắt mục TOP JOBS', 'jobscout' ),
                'description' => __( 'Bật để hiển thị danh sách các việc làm nổi bật ở Trang chủ.', 'jobscout' ),
            )
        )
    );

    /** 2. Job Posting Title */
    $wp_customize->add_setting(
        'job_posting_section_title',
        array(
            'default'           => 'TOP JOBS',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        )
    );
    
    $wp_customize->add_control(
        'job_posting_section_title',
        array(
            'section' => 'job_posting_section',
            'label'   => __( 'Tiêu đề mục', 'jobscout' ),
            'type'    => 'text',
        )
    );

    $wp_customize->selective_refresh->add_partial( 'job_posting_section_title', array(
        'selector'        => '.top-jobs-heading',
        'render_callback' => 'jobscout_get_job_posting_section_title',
    ) );

    /** 3. Chế độ nguồn dữ liệu hiển thị (Tự động vs Thủ công) */
    $wp_customize->add_setting(
        'top_jobs_source',
        array(
            'default'           => 'auto',
            'sanitize_callback' => 'sanitize_key',
        )
    );

    $wp_customize->add_control(
        'top_jobs_source',
        array(
            'section'     => 'job_posting_section',
            'label'       => __( 'Chế độ hiển thị danh sách', 'jobscout' ),
            'description' => __( 'Chọn "Tự động" để ưu tiên các việc làm đánh dấu Nổi bật (Featured), hoặc chọn "Thủ công" để tự chọn 6 công việc bên dưới.', 'jobscout' ),
            'type'        => 'select',
            'choices'     => array(
                'auto'   => __( 'Tự động (Ưu tiên việc làm Nổi bật & Mới nhất)', 'jobscout' ),
                'manual' => __( 'Thủ công (Tự chọn 6 công việc cụ thể)', 'jobscout' ),
            ),
        )
    );

    /** Lấy danh sách việc làm để đưa vào choices dropdown */
    $job_choices = array( 0 => __( '-- Chọn việc làm --', 'jobscout' ) );
    $job_posts = get_posts( array(
        'post_type'      => 'job_listing',
        'post_status'    => array( 'publish' ),
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
    ) );
    if ( ! empty( $job_posts ) ) {
        foreach ( $job_posts as $j ) {
            $job_choices[ $j->ID ] = $j->post_title . ' (#' . $j->ID . ')';
        }
    }

    /** 4. Chọn thủ công 6 công việc */
    for ( $i = 1; $i <= 6; $i++ ) {
        $wp_customize->add_setting(
            "top_job_id_{$i}",
            array(
                'default'           => 0,
                'sanitize_callback' => 'absint',
            )
        );

        $wp_customize->add_control(
            "top_job_id_{$i}",
            array(
                'section'     => 'job_posting_section',
                'label'       => sprintf( __( 'Thẻ việc làm số %d', 'jobscout' ), $i ),
                'description' => __( 'Hiển thị khi chế độ được chọn là "Thủ công".', 'jobscout' ),
                'type'        => 'select',
                'choices'     => $job_choices,
            )
        );
    }

    /** 5. Nút Xem Thêm (Chữ & Link) */
    $wp_customize->add_setting(
        'top_jobs_btn_text',
        array(
            'default'           => 'VIEW MORE JOBS',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'top_jobs_btn_text',
        array(
            'section'     => 'job_posting_section',
            'label'       => __( 'Chữ trên nút xem thêm', 'jobscout' ),
            'description' => __( 'Mặc định: VIEW MORE JOBS. Để trống nếu muốn ẩn nút.', 'jobscout' ),
            'type'        => 'text',
        )
    );

    $wp_customize->add_setting(
        'top_jobs_btn_url',
        array(
            'default'           => home_url( '/jobs/' ),
            'sanitize_callback' => 'esc_url_raw',
        )
    );

    $wp_customize->add_control(
        'top_jobs_btn_url',
        array(
            'section' => 'job_posting_section',
            'label'   => __( 'Liên kết nút xem thêm', 'jobscout' ),
            'type'    => 'url',
        )
    );

    /** 6. 3 Gạch đầu dòng quyền lợi mặc định */
    $wp_customize->add_setting(
        'top_jobs_default_bullet_1',
        array(
            'default'           => 'Be responsible for the effective operational management of the hotel',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    $wp_customize->add_control(
        'top_jobs_default_bullet_1',
        array(
            'section'     => 'job_posting_section',
            'label'       => __( '• Quyền lợi mặc định 1', 'jobscout' ),
            'description' => __( 'Áp dụng khi tin tuyển dụng chưa nhập quyền lợi riêng.', 'jobscout' ),
            'type'        => 'text',
        )
    );

    $wp_customize->add_setting(
        'top_jobs_default_bullet_2',
        array(
            'default'           => 'Excellent salary bonuses & recognition activities',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    $wp_customize->add_control(
        'top_jobs_default_bullet_2',
        array(
            'section' => 'job_posting_section',
            'label'   => __( '• Quyền lợi mặc định 2', 'jobscout' ),
            'type'    => 'text',
        )
    );

    $wp_customize->add_setting(
        'top_jobs_default_bullet_3',
        array(
            'default'           => 'Foreign language allowance (up to 500 USD/month)',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    $wp_customize->add_control(
        'top_jobs_default_bullet_3',
        array(
            'section' => 'job_posting_section',
            'label'   => __( '• Quyền lợi mặc định 3', 'jobscout' ),
            'type'    => 'text',
        )
    );

    if( ! $wpjob_manager_activated ){
        $wp_customize->add_setting(
            'job_posting_section_note', array(
                'sanitize_callback' => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(
            new JobScout_Plugin_Recommend_Control(
                $wp_customize, 'job_posting_section_note', array(
                    'label'       => __( 'Hướng dẫn', 'jobscout' ),
                    'section'     => 'job_posting_section',
                    'capability'  => 'install_plugins',
                    'plugin_slug' => 'wp-job-manager',
                    'description' => __( 'Vui lòng cài đặt và kích hoạt plugin "WP Job Manager".', 'jobscout' ),
                )
            )
        );
    }

}
endif;
add_action( 'customize_register', 'jobscout_customize_register_frontpage_job_posting' );