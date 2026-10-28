<?php
/**
 * News / Blog Page Customizer Settings
 * Cho phép chỉnh sửa toàn bộ giao diện của trang Tin tức & Bài viết trực tiếp trong Tùy biến (Customizer)
 *
 * @package jobscout
 */

if ( ! function_exists( 'jobscout_customize_register_news_section' ) ) :

function jobscout_customize_register_news_section( $wp_customize ) {

    // 1. Thêm Section: Cấu hình Trang Tin tức & Bài viết (News / Blog Page)
    $wp_customize->add_section(
        'jobscout_news_page_section',
        array(
            'title'       => __( '⭐ Trang Tin tức & Bài viết (Blog Page)', 'jobscout' ),
            'priority'    => 41,
            'capability'  => 'edit_theme_options',
            'description' => __( 'Tùy chỉnh trực quan toàn bộ giao diện trang Tin tức & Bài viết: Ảnh bìa Banner, Tiêu đề, Phụ đề, Tiêu đề danh sách bài viết...', 'jobscout' ),
        )
    );

    // 2. Setting & Control: Bật / Tắt Banner Hero
    $wp_customize->add_setting(
        'pds_news_show_banner',
        array(
            'default'           => true,
            'sanitize_callback' => 'jobscout_sanitize_checkbox',
            'capability'        => 'edit_theme_options',
        )
    );
    $wp_customize->add_control(
        'pds_news_show_banner',
        array(
            'label'       => __( 'Hiển thị Banner đầu trang', 'jobscout' ),
            'description' => __( 'Tích chọn để hiển thị Banner toàn màn hình ở đầu trang bài viết.', 'jobscout' ),
            'section'     => 'jobscout_news_page_section',
            'type'        => 'checkbox',
            'priority'    => 5,
        )
    );

    $current_page_id = (int) get_option( 'page_for_posts' );
    $page_thumb      = ( $current_page_id && has_post_thumbnail( $current_page_id ) ) ? get_the_post_thumbnail_url( $current_page_id, 'full' ) : '';
    $default_banner  = ! empty( $page_thumb ) ? $page_thumb : home_url( '/wp-content/uploads/2026/10/hinhnen-2.png' );

    // 3. Setting & Control: Ảnh nền Banner (Banner Background Image)
    $wp_customize->add_setting(
        'pds_news_hero_image',
        array(
            'default'           => $default_banner,
            'sanitize_callback' => 'esc_url_raw',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'pds_news_hero_image',
            array(
                'label'       => __( 'Ảnh bìa Banner', 'jobscout' ),
                'description' => __( 'Tải lên hoặc chọn ảnh nền cho phần đầu trang bài viết.', 'jobscout' ),
                'section'     => 'jobscout_news_page_section',
                'priority'    => 10,
            )
        )
    );

    // 3b. Setting & Control: Chiều cao Banner (Banner Height)
    $wp_customize->add_setting(
        'pds_news_hero_height',
        array(
            'default'           => '520px',
            'sanitize_callback' => 'sanitize_text_field',
            'capability'        => 'edit_theme_options',
        )
    );
    $wp_customize->add_control(
        'pds_news_hero_height',
        array(
            'label'       => __( 'Kích thước Chiều cao Banner', 'jobscout' ),
            'description' => __( 'Tùy chỉnh để lấy toàn bộ hình ảnh hay nhỏ gọn.', 'jobscout' ),
            'section'     => 'jobscout_news_page_section',
            'type'        => 'select',
            'choices'     => array(
                '520px' => __( 'Lớn - Lấy trọn vẹn toàn bộ hình (520px)', 'jobscout' ),
                '580px' => __( 'Toàn cảnh mở rộng (580px)', 'jobscout' ),
                '450px' => __( 'Trung bình (450px)', 'jobscout' ),
                '380px' => __( 'Nhỏ gọn (380px)', 'jobscout' ),
            ),
            'priority'    => 15,
        )
    );

    // 4. Setting & Control: Tiêu đề Banner (Banner Title)
    $wp_customize->add_setting(
        'pds_news_hero_title',
        array(
            'default'           => 'TIN TỨC & BÀI VIẾT',
            'sanitize_callback' => 'sanitize_text_field',
            'capability'        => 'edit_theme_options',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control(
        'pds_news_hero_title',
        array(
            'label'       => __( 'Tiêu đề Banner', 'jobscout' ),
            'description' => __( 'Tiêu đề lớn màu trắng hiển thị trên ảnh bìa.', 'jobscout' ),
            'section'     => 'jobscout_news_page_section',
            'type'        => 'text',
            'priority'    => 20,
        )
    );

    // 5. Setting & Control: Phụ đề Banner (Banner Subtitle)
    $wp_customize->add_setting(
        'pds_news_hero_subtitle',
        array(
            'default'           => 'Cập nhật tin tức thị trường, cẩm nang tuyển dụng & kiến thức nghề nghiệp mới nhất',
            'sanitize_callback' => 'sanitize_textarea_field',
            'capability'        => 'edit_theme_options',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control(
        'pds_news_hero_subtitle',
        array(
            'label'       => __( 'Phụ đề Banner', 'jobscout' ),
            'description' => __( 'Đoạn mô tả ngắn bên dưới tiêu đề banner.', 'jobscout' ),
            'section'     => 'jobscout_news_page_section',
            'type'        => 'textarea',
            'priority'    => 30,
        )
    );

    // 6. Setting & Control: Tiêu đề danh sách bài viết (Section Title)
    $wp_customize->add_setting(
        'pds_news_section_title',
        array(
            'default'           => 'BÀI VIẾT MỚI NHẤT',
            'sanitize_callback' => 'sanitize_text_field',
            'capability'        => 'edit_theme_options',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control(
        'pds_news_section_title',
        array(
            'label'       => __( 'Tiêu đề phần danh sách bài viết', 'jobscout' ),
            'description' => __( 'Tiêu đề hiển thị phía trên lưới các bài viết mới nhất.', 'jobscout' ),
            'section'     => 'jobscout_news_page_section',
            'type'        => 'text',
            'priority'    => 40,
        )
    );

    // 7. Setting & Control: Mô tả danh sách bài viết (Section Description)
    $wp_customize->add_setting(
        'pds_news_section_desc',
        array(
            'default'           => 'Khám phá các bài viết hữu ích về ngành dịch vụ, nhà hàng - khách sạn và định hướng nghề nghiệp',
            'sanitize_callback' => 'sanitize_textarea_field',
            'capability'        => 'edit_theme_options',
            'transport'         => 'postMessage',
        )
    );
    $wp_customize->add_control(
        'pds_news_section_desc',
        array(
            'label'       => __( 'Mô tả phần danh sách bài viết', 'jobscout' ),
            'description' => __( 'Đoạn mô tả ngắn dưới tiêu đề danh sách bài viết.', 'jobscout' ),
            'section'     => 'jobscout_news_page_section',
            'type'        => 'textarea',
            'priority'    => 50,
        )
    );

    // 8. Setting & Control: Chữ nút Xem chi tiết (Read More Button Text)
    $wp_customize->add_setting(
        'pds_news_read_more_text',
        array(
            'default'           => 'Xem chi tiết',
            'sanitize_callback' => 'sanitize_text_field',
            'capability'        => 'edit_theme_options',
        )
    );
    $wp_customize->add_control(
        'pds_news_read_more_text',
        array(
            'label'       => __( 'Văn bản nút xem chi tiết', 'jobscout' ),
            'description' => __( 'Chữ hiển thị trên nút liên kết đến từng bài viết.', 'jobscout' ),
            'section'     => 'jobscout_news_page_section',
            'type'        => 'text',
            'priority'    => 60,
        )
    );

    // 9. Selective Refresh cho việc xem trước trực tiếp khi gõ chữ (Live Preview)
    if ( isset( $wp_customize->selective_refresh ) ) {
        $wp_customize->selective_refresh->add_partial(
            'pds_news_hero_title',
            array(
                'selector'        => '#pds-news-hero-title',
                'render_callback' => function() {
                    return esc_html( get_theme_mod( 'pds_news_hero_title', 'TIN TỨC & BÀI VIẾT' ) );
                },
            )
        );
        $wp_customize->selective_refresh->add_partial(
            'pds_news_hero_subtitle',
            array(
                'selector'        => '#pds-news-hero-subtitle',
                'render_callback' => function() {
                    return esc_html( get_theme_mod( 'pds_news_hero_subtitle', 'Cập nhật tin tức thị trường, cẩm nang tuyển dụng & kiến thức nghề nghiệp mới nhất' ) );
                },
            )
        );
        $wp_customize->selective_refresh->add_partial(
            'pds_news_section_title',
            array(
                'selector'        => '#pds-news-section-title',
                'render_callback' => function() {
                    return esc_html( get_theme_mod( 'pds_news_section_title', 'BÀI VIẾT MỚI NHẤT' ) );
                },
            )
        );
        $wp_customize->selective_refresh->add_partial(
            'pds_news_section_desc',
            array(
                'selector'        => '#pds-news-section-desc',
                'render_callback' => function() {
                    return esc_html( get_theme_mod( 'pds_news_section_desc', 'Khám phá các bài viết hữu ích về ngành dịch vụ, nhà hàng - khách sạn và định hướng nghề nghiệp' ) );
                },
            )
        );
    }
}
endif;
add_action( 'customize_register', 'jobscout_customize_register_news_section' );

/**
 * Tự động đồng bộ 2 chiều: Customizer -> Trang bài viết (Featured Image)
 */
function jobscout_sync_news_customizer_to_page() {
    $current_page_id = (int) get_option( 'page_for_posts' );
    if ( ! $current_page_id ) return;

    $hero_img = get_theme_mod( 'pds_news_hero_image' );
    if ( ! empty( $hero_img ) ) {
        $attach_id = attachment_url_to_postid( $hero_img );
        if ( $attach_id ) {
            set_post_thumbnail( $current_page_id, $attach_id );
        }
    }
}
add_action( 'customize_save_after', 'jobscout_sync_news_customizer_to_page' );

/**
 * Tự động đồng bộ 2 chiều: Trang bài viết (Featured Image) -> Customizer
 */
function jobscout_sync_news_page_to_customizer( $post_id, $post ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    $blog_page_id = (int) get_option( 'page_for_posts' );
    if ( $blog_page_id !== (int) $post_id ) return;

    if ( has_post_thumbnail( $post_id ) ) {
        $thumb_url = get_the_post_thumbnail_url( $post_id, 'full' );
        if ( ! empty( $thumb_url ) ) {
            set_theme_mod( 'pds_news_hero_image', $thumb_url );
        }
    }
}
add_action( 'save_post_page', 'jobscout_sync_news_page_to_customizer', 30, 2 );
