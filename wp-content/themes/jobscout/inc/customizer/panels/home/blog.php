<?php
/**
 * Blog Section (NEWEST BLOG ENTRIES MODULE)
 *
 * Cung cấp các điều khiển trong Giao diện → Tùy biến → Front Page Settings → Blog Section:
 * - Bật/tắt mục NEWEST BLOG ENTRIES
 * - Tiêu đề mục (NEWEST BLOG ENTRIES)
 * - Mô tả phụ
 * - Chế độ hiển thị: Tự động (ưu tiên bài viết nổi bật & mới nhất có ảnh) hoặc Thủ công (chọn 4 bài)
 * - 4 Dropdown chọn bài viết từ hệ thống
 * - Chữ nút đọc tiếp (Read More)
 * - Chữ nút xem tất cả bài viết
 *
 * @package jobscout
 */

if ( ! function_exists( 'jobscout_customize_register_frontpage_blog' ) ) :

function jobscout_customize_register_frontpage_blog( $wp_customize ){

    /** Blog Section */
    $wp_customize->add_section(
        'blog_section',
        array(
            'title'    => __( 'Blog Section (Newest Entries)', 'jobscout' ),
            'priority' => 35,
            'panel'    => 'frontpage_settings',
        )
    );

    /** 1. Enable/Disable Section */
    $wp_customize->add_setting( 
        'ed_blog_section', 
        array(
            'default'           => true,
            'sanitize_callback' => 'jobscout_sanitize_checkbox',
        ) 
    );
    
    $wp_customize->add_control(
        new JobScout_Toggle_Control( 
            $wp_customize,
            'ed_blog_section',
            array(
                'section'     => 'blog_section',
                'label'       => __( 'Bật/Tắt mục NEWEST BLOG ENTRIES', 'jobscout' ),
                'description' => __( 'Bật để hiển thị danh sách bài viết mới ở Trang chủ.', 'jobscout' ),
            )
        )
    );

    /** 2. Blog Title */
    $wp_customize->add_setting(
        'blog_section_title',
        array(
            'default'           => 'NEWEST BLOG ENTRIES',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        )
    );
    
    $wp_customize->add_control(
        'blog_section_title',
        array(
            'section' => 'blog_section',
            'label'   => __( 'Tiêu đề mục', 'jobscout' ),
            'type'    => 'text',
        )
    );

    /** Selective refresh for blog title. */
    $wp_customize->selective_refresh->add_partial( 'blog_section_title', array(
        'selector'            => '.newest-blog-module-section .newest-blog-heading',
        'render_callback'     => 'jobscout_get_blog_section_title',
        'container_inclusive' => false,
        'fallback_refresh'    => true,
    ) );

    /** 3. Blog Description */
    $wp_customize->add_setting(
        'blog_section_subtitle',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'postMessage',
        )
    );
    
    $wp_customize->add_control(
        'blog_section_subtitle',
        array(
            'section'     => 'blog_section',
            'label'       => __( 'Mô tả phụ dưới tiêu đề', 'jobscout' ),
            'description' => __( 'Để trống nếu không muốn hiển thị mô tả.', 'jobscout' ),
            'type'        => 'text',
        )
    ); 

    /** Selective refresh for blog description. */
    $wp_customize->selective_refresh->add_partial( 'blog_section_subtitle', array(
        'selector'            => '.newest-blog-module-section .newest-blog-desc',
        'render_callback'     => 'jobscout_get_blog_section_description',
        'container_inclusive' => false,
        'fallback_refresh'    => true,
    ) );

    /** 4. Chế độ nguồn bài viết (Tự động vs Thủ công) */
    $wp_customize->add_setting(
        'blog_posts_source',
        array(
            'default'           => 'auto',
            'sanitize_callback' => 'sanitize_key',
        )
    );

    $wp_customize->add_control(
        'blog_posts_source',
        array(
            'section'     => 'blog_section',
            'label'       => __( 'Chế độ hiển thị bài viết', 'jobscout' ),
            'description' => __( 'Chọn "Tự động" để ưu tiên các bài viết đánh dấu Nổi bật / Mới nhất, hoặc chọn "Thủ công" để tự chọn 4 bài viết bên dưới.', 'jobscout' ),
            'type'        => 'select',
            'choices'     => array(
                'auto'   => __( 'Tự động (Ưu tiên bài viết Nổi bật & Mới nhất có ảnh)', 'jobscout' ),
                'manual' => __( 'Thủ công (Tự chọn 4 bài viết cụ thể)', 'jobscout' ),
            ),
        )
    );

    /** Lấy danh sách tất cả các bài viết để nạp vào choices dropdown */
    $post_choices = array( 0 => __( '-- Chọn bài viết --', 'jobscout' ) );
    $all_posts = get_posts( array(
        'post_type'      => 'post',
        'post_status'    => array( 'publish' ),
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
    ) );
    if ( ! empty( $all_posts ) ) {
        foreach ( $all_posts as $p ) {
            $has_thumb = has_post_thumbnail( $p->ID ) ? '' : ' [Chưa có ảnh đại diện]';
            $post_choices[ $p->ID ] = $p->post_title . ' (#' . $p->ID . ')' . $has_thumb;
        }
    }

    /** 5. Chọn thủ công 4 bài viết */
    for ( $i = 1; $i <= 4; $i++ ) {
        $wp_customize->add_setting(
            "blog_post_id_{$i}",
            array(
                'default'           => 0,
                'sanitize_callback' => 'absint',
            )
        );

        $wp_customize->add_control(
            "blog_post_id_{$i}",
            array(
                'section'     => 'blog_section',
                'label'       => sprintf( __( 'Thẻ bài viết số %d', 'jobscout' ), $i ),
                'description' => __( 'Hiển thị khi chế độ được chọn là "Thủ công".', 'jobscout' ),
                'type'        => 'select',
                'choices'     => $post_choices,
            )
        );
    }

    /** 6. Chữ trên link đọc tiếp */
    $wp_customize->add_setting(
        'blog_read_more_text',
        array(
            'default'           => 'Read More',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'blog_read_more_text',
        array(
            'section'     => 'blog_section',
            'label'       => __( 'Chữ trên liên kết đọc tiếp', 'jobscout' ),
            'description' => __( 'Mặc định: Read More (có thể đổi thành Đọc tiếp, Xem thêm...)', 'jobscout' ),
            'type'        => 'text',
        )
    );

    /** 7. Nút xem tất cả bài viết */
    $wp_customize->add_setting(
        'blog_view_all',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        )
    );
    
    $wp_customize->add_control(
        'blog_view_all',
        array(
            'label'           => __( 'Chữ trên nút Xem tất cả bài viết', 'jobscout' ),
            'description'     => __( 'Để trống nếu không muốn hiển thị nút này.', 'jobscout' ),
            'section'         => 'blog_section',
            'type'            => 'text',
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'blog_view_all', array(
        'selector'            => '.newest-blog-module-section .newest-blog-btn',
        'render_callback'     => 'jobscout_get_blog_view_all_btn',
        'container_inclusive' => false,
        'fallback_refresh'    => true,
    ) ); 
    /** Blog Section Ends */  

}
endif;
add_action( 'customize_register', 'jobscout_customize_register_frontpage_blog' );