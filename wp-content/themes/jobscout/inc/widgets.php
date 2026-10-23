<?php
/**
 * Các khu vực Widget (Sidebars) của giao diện JobScout
 * 
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 * @package JobScout
 */

function jobscout_widgets_init(){    
    $sidebars = array(
        'sidebar'   => array(
            'name'        => __( 'Sidebar', 'jobscout' ),
            'id'          => 'sidebar', 
            'description' => __( 'Default Sidebar', 'jobscout' ),
        ),
        'cta' => array(
            'name'        => __( 'Call To Action Section', 'jobscout' ),
            'id'          => 'cta', 
            'description' => __( 'Add "Rara: Call To Action" widget for Call to Action section.', 'jobscout' ),
        ),
        'testimonial' => array(
            'name'        => __( 'Testimonial Section', 'jobscout' ),
            'id'          => 'testimonial', 
            'description' => __( 'Add "Rara: Testimonial" widget for testimonial section.', 'jobscout' ),
        ),
        'client' => array(
            'name'        => __( 'Client Section', 'jobscout' ),
            'id'          => 'client', 
            'description' => __( 'Add "Rara Client Logo" widget for client section.', 'jobscout' ),
        ),
        'footer-one'=> array(
            'name'        => __( 'Footer One', 'jobscout' ),
            'id'          => 'footer-one', 
            'description' => __( 'Add footer one widgets here.', 'jobscout' ),
        ),
        'footer-two'=> array(
            'name'        => __( 'Footer Two', 'jobscout' ),
            'id'          => 'footer-two', 
            'description' => __( 'Add footer two widgets here.', 'jobscout' ),
        ),
        'footer-three'=> array(
            'name'        => __( 'Footer Three', 'jobscout' ),
            'id'          => 'footer-three', 
            'description' => __( 'Add footer three widgets here.', 'jobscout' ),
        ),
        'footer-four'=> array(
            'name'        => __( 'Footer Four', 'jobscout' ),
            'id'          => 'footer-four', 
            'description' => __( 'Add footer four widgets here.', 'jobscout' ),
        ),
        'footer-module'=> array(
            'name'        => __( 'Footer Module (Plan Do See)', 'jobscout' ),
            'id'          => 'footer-module', 
            'description' => __( 'Khu vực quản lý Footer Module. Chỉnh sửa nội dung tại Widget [CMS] Module Footer.', 'jobscout' ),
        )
    );
    
    foreach( $sidebars as $sidebar ){
        $before_widget = '<section id="%1$s" class="widget %2$s">';
        $after_widget  = '</section>';
        $before_title  = '<h2 class="widget-title" itemprop="name">';
        $after_title   = '</h2>';

        if ( 'footer-module' === $sidebar['id'] ) {
            $before_widget = '<div id="%1$s" class="cms-footer-widget-container %2$s">';
            $after_widget  = '</div>';
            $before_title  = '<h3 class="screen-reader-text">';
            $after_title   = '</h3>';
        }

        register_sidebar( array(
    		'name'          => esc_html( $sidebar['name'] ),
    		'id'            => esc_attr( $sidebar['id'] ),
    		'description'   => esc_html( $sidebar['description'] ),
    		'before_widget' => $before_widget,
    		'after_widget'  => $after_widget,
    		'before_title'  => $before_title,
    		'after_title'   => $after_title,
    	) );
    }

    // Đăng ký Widget tuỳ biến cho Module Footer
    register_widget( 'CMS_Footer_Module_Widget' );
}
add_action( 'widgets_init', 'jobscout_widgets_init' );

// Tối ưu giao diện quản lý Widgets dạng cổ điển, dễ chỉnh sửa trong wp-admin
add_filter( 'use_widgets_block_editor', '__return_false' );

/**
 * Lớp Widget: CMS_Footer_Module_Widget
 * Cho phép chỉnh sửa toàn bộ nội dung của Module Footer trực tiếp trong wp-admin > Widgets
 */
class CMS_Footer_Module_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'cms_footer_module_widget',
            __( '[CMS] Module Footer - Plan Do See', 'jobscout' ),
            array(
                'description' => __( 'Tùy chỉnh nội dung Module Footer: Bản tin, Tên thương hiệu, Menu, Mạng xã hội và Bản quyền.', 'jobscout' ),
                'classname'   => 'widget_cms_footer_module',
            )
        );
    }

    public function widget( $args, $instance ) {
        $newsletter_title_1   = get_theme_mod( 'footer_newsletter_title_1', ! empty( $instance['newsletter_title_1'] ) ? $instance['newsletter_title_1'] : 'ĐĂNG KÝ NHẬN' );
        $newsletter_title_2   = get_theme_mod( 'footer_newsletter_title_2', ! empty( $instance['newsletter_title_2'] ) ? $instance['newsletter_title_2'] : 'BẢN TIN CỦA CHÚNG TÔI' );
        $placeholder          = get_theme_mod( 'footer_newsletter_placeholder', ! empty( $instance['placeholder'] ) ? $instance['placeholder'] : 'Nhập địa chỉ email của bạn...' );
        $btn_text             = get_theme_mod( 'footer_newsletter_btn_text', ! empty( $instance['btn_text'] ) ? $instance['btn_text'] : 'ĐĂNG KÝ' );
        $brand_name           = get_theme_mod( 'footer_brand_name', ! empty( $instance['brand_name'] ) ? $instance['brand_name'] : 'Plan Do See Global Inc.' );
        $brand_url            = get_theme_mod( 'footer_brand_url', ! empty( $instance['brand_url'] ) ? $instance['brand_url'] : home_url( '/' ) );
        $customizer_menu      = (int) get_theme_mod( 'footer_menu_select', 0 );
        $nav_menu             = ! empty( $instance['nav_menu'] ) ? (int) $instance['nav_menu'] : $customizer_menu;
        $facebook_url         = get_theme_mod( 'footer_facebook_url', ! empty( $instance['facebook_url'] ) ? $instance['facebook_url'] : 'https://facebook.com' );
        $google_url           = get_theme_mod( 'footer_google_url', ! empty( $instance['google_url'] ) ? $instance['google_url'] : 'https://google.com' );
        $line_url             = get_theme_mod( 'footer_line_url', ! empty( $instance['line_url'] ) ? $instance['line_url'] : 'https://line.me' );
        $twitter_url          = get_theme_mod( 'footer_twitter_url', ! empty( $instance['twitter_url'] ) ? $instance['twitter_url'] : 'https://twitter.com' );
        $copyright_text       = get_theme_mod( 'footer_copyright_text', ! empty( $instance['copyright_text'] ) ? $instance['copyright_text'] : '&copy; {year} Plan Do See Global Inc. All rights reserved.' );
        $copyright_text       = str_replace( '{year}', date( 'Y' ), $copyright_text );
        ?>

        <!-- 1. TOP BAR: SUBSCRIBE TO OUR NEWSLETTER (CAM) -->
        <section class="ft-newsletter-bar" aria-label="<?php echo esc_attr__( 'Đăng ký nhận bản tin', 'jobscout' ); ?>">
            <div class="ft-newsletter-container">
                <h2 class="ft-newsletter-title">
                    <span><?php echo esc_html( $newsletter_title_1 ); ?></span>
                    <span><?php echo esc_html( $newsletter_title_2 ); ?></span>
                </h2>

                <form class="ft-newsletter-form" id="ftWpNewsletterForm" novalidate>
                    <div class="ft-input-box" id="ftInputBox">
                        <span class="ft-mail-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                                <path d="m22 6.5-10 7-10-7"></path>
                            </svg>
                        </span>
                        <input 
                            type="email" 
                            name="email"
                            id="ftEmailInput"
                            class="ft-input" 
                            placeholder="<?php echo esc_attr( $placeholder ); ?>" 
                            required 
                            autocomplete="email"
                            aria-label="<?php echo esc_attr( $placeholder ); ?>"
                        >
                    </div>

                    <button type="submit" class="ft-btn-subscribe" id="ftBtnSubscribe">
                        <span class="ft-spinner" aria-hidden="true"></span>
                        <span class="ft-btn-text"><?php echo esc_html( $btn_text ); ?></span>
                    </button>
                </form>
            </div>
        </section>

        <!-- 2. MIDDLE SECTION: NỀN XÁM SÁNG #F5F6F8 -->
        <section class="ft-main-section">
            <div class="ft-main-container">
                <a href="<?php echo esc_url( $brand_url ); ?>" class="ft-brand" title="<?php echo esc_attr( $brand_name ); ?>">
                    <span class="ft-brand-title"><?php echo esc_html( $brand_name ); ?></span>
                </a>

                <nav class="ft-nav-wrap" aria-label="<?php echo esc_attr__( 'Footer Navigation', 'jobscout' ); ?>">
                    <?php echo jobscout_get_footer_nav_menu_html( $nav_menu, $instance ); ?>
                </nav>

                <div class="ft-social-row" aria-label="Social Media">
                    <?php echo jobscout_get_footer_social_row_html( $facebook_url, $google_url, $line_url, $twitter_url ); ?>
                </div>
            </div>
        </section>

        <!-- 3. BOTTOM BAR: DẢI ĐEN NÂU CĂN GIỮA BẢN QUYỀN CHUẨN XÁC -->
        <section class="ft-bottom-bar">
            <p class="ft-copyright">
                <?php echo wp_kses_post( $copyright_text ); ?>
            </p>
        </section>
        <?php
    }

    public function form( $instance ) {
        $newsletter_title_1 = ! empty( $instance['newsletter_title_1'] ) ? $instance['newsletter_title_1'] : 'ĐĂNG KÝ NHẬN';
        $newsletter_title_2 = ! empty( $instance['newsletter_title_2'] ) ? $instance['newsletter_title_2'] : 'BẢN TIN CỦA CHÚNG TÔI';
        $placeholder        = ! empty( $instance['placeholder'] ) ? $instance['placeholder'] : 'Nhập địa chỉ email của bạn...';
        $btn_text           = ! empty( $instance['btn_text'] ) ? $instance['btn_text'] : 'ĐĂNG KÝ';
        $brand_name         = ! empty( $instance['brand_name'] ) ? $instance['brand_name'] : 'Plan Do See Global Inc.';
        $brand_url          = ! empty( $instance['brand_url'] ) ? $instance['brand_url'] : home_url( '/' );
        $nav_menu           = ! empty( $instance['nav_menu'] ) ? (int) $instance['nav_menu'] : 0;
        $facebook_url       = ! empty( $instance['facebook_url'] ) ? $instance['facebook_url'] : 'https://facebook.com';
        $google_url         = ! empty( $instance['google_url'] ) ? $instance['google_url'] : 'https://google.com';
        $line_url           = ! empty( $instance['line_url'] ) ? $instance['line_url'] : 'https://line.me';
        $twitter_url        = ! empty( $instance['twitter_url'] ) ? $instance['twitter_url'] : 'https://twitter.com';
        $copyright_text     = ! empty( $instance['copyright_text'] ) ? $instance['copyright_text'] : '&copy; {year} Plan Do See Global Inc. All rights reserved.';
        
        $menus = wp_get_nav_menus();
        ?>
        <div style="padding: 10px 0;">
            <h4 style="margin: 8px 0 4px; color: #eb6324; font-weight: bold;">1. Dải Đăng Ký Bản Tin (Newsletter)</h4>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'newsletter_title_1' ) ); ?>">Tiêu đề dòng 1:</label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'newsletter_title_1' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'newsletter_title_1' ) ); ?>" type="text" value="<?php echo esc_attr( $newsletter_title_1 ); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'newsletter_title_2' ) ); ?>">Tiêu đề dòng 2:</label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'newsletter_title_2' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'newsletter_title_2' ) ); ?>" type="text" value="<?php echo esc_attr( $newsletter_title_2 ); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'placeholder' ) ); ?>">Placeholder ô nhập Email:</label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'placeholder' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'placeholder' ) ); ?>" type="text" value="<?php echo esc_attr( $placeholder ); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'btn_text' ) ); ?>">Chữ trên nút bấm:</label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'btn_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'btn_text' ) ); ?>" type="text" value="<?php echo esc_attr( $btn_text ); ?>">
            </p>

            <h4 style="margin: 16px 0 4px; color: #1e2022; font-weight: bold; border-top: 1px solid #ddd; padding-top: 10px;">2. Tên Thương Hiệu &amp; Menu Điều Hướng</h4>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'brand_name' ) ); ?>">Tên thương hiệu (Brand Title):</label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'brand_name' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'brand_name' ) ); ?>" type="text" value="<?php echo esc_attr( $brand_name ); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'brand_url' ) ); ?>">Đường dẫn Logo thương hiệu (URL):</label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'brand_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'brand_url' ) ); ?>" type="url" value="<?php echo esc_attr( $brand_url ); ?>">
            </p>
            <?php if ( ! empty( $menus ) ) : ?>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'nav_menu' ) ); ?>">Chọn Menu hiển thị (hoặc để mặc định 5 link):</label>
                <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'nav_menu' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'nav_menu' ) ); ?>">
                    <option value="0"><?php esc_html_e( '— Mặc định (VIỆC LÀM, CÔNG TY, BÀI VIẾT, GIỚI THIỆU, LIÊN HỆ) —', 'jobscout' ); ?></option>
                    <?php foreach ( $menus as $menu ) : ?>
                        <option value="<?php echo esc_attr( $menu->term_id ); ?>" <?php selected( $nav_menu, $menu->term_id ); ?>>
                            <?php echo esc_html( $menu->name ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </p>
            <?php endif; ?>

            <h4 style="margin: 16px 0 4px; color: #1e2022; font-weight: bold; border-top: 1px solid #ddd; padding-top: 10px;">2B. Tùy Chỉnh 5 Liên Kết Điều Hướng (Nếu không chọn Menu trên)</h4>
            <?php
            $default_link_labels = array(
                1 => array( 'text' => 'VIỆC LÀM', 'url' => home_url( '/jobs/' ) ),
                2 => array( 'text' => 'CÔNG TY', 'url' => home_url( '/jobs/' ) ),
                3 => array( 'text' => 'BÀI VIẾT', 'url' => home_url( '/blog/' ) ),
                4 => array( 'text' => 'GIỚI THIỆU', 'url' => home_url( '/about/' ) ),
                5 => array( 'text' => 'LIÊN HỆ', 'url' => home_url( '/contact/' ) ),
            );
            for ( $i = 1; $i <= 5; $i++ ) :
                $l_text = ! empty( $instance["link_{$i}_text"] ) ? $instance["link_{$i}_text"] : get_theme_mod( "footer_link_text_{$i}", $default_link_labels[$i]['text'] );
                $l_url  = ! empty( $instance["link_{$i}_url"] ) ? $instance["link_{$i}_url"] : get_theme_mod( "footer_link_url_{$i}", $default_link_labels[$i]['url'] );
            ?>
            <p style="margin-bottom: 8px;">
                <label><strong>Link <?php echo $i; ?> (Tên hiển thị &amp; URL):</strong></label><br>
                <input class="widefat" style="width: 48%; display: inline-block;" id="<?php echo esc_attr( $this->get_field_id( "link_{$i}_text" ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( "link_{$i}_text" ) ); ?>" type="text" value="<?php echo esc_attr( $l_text ); ?>" placeholder="Tên hiển thị">
                <input class="widefat" style="width: 49%; display: inline-block;" id="<?php echo esc_attr( $this->get_field_id( "link_{$i}_url" ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( "link_{$i}_url" ) ); ?>" type="url" value="<?php echo esc_attr( $l_url ); ?>" placeholder="Đường dẫn URL">
            </p>
            <?php endfor; ?>

            <h4 style="margin: 16px 0 4px; color: #1e2022; font-weight: bold; border-top: 1px solid #ddd; padding-top: 10px;">3. Liên Kết Mạng Xã Hội</h4>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'facebook_url' ) ); ?>">Link Facebook:</label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'facebook_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'facebook_url' ) ); ?>" type="url" value="<?php echo esc_attr( $facebook_url ); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'google_url' ) ); ?>">Link Google:</label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'google_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'google_url' ) ); ?>" type="url" value="<?php echo esc_attr( $google_url ); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'line_url' ) ); ?>">Link LINE:</label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'line_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'line_url' ) ); ?>" type="url" value="<?php echo esc_attr( $line_url ); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'twitter_url' ) ); ?>">Link Twitter / X:</label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'twitter_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'twitter_url' ) ); ?>" type="url" value="<?php echo esc_attr( $twitter_url ); ?>">
            </p>

            <h4 style="margin: 16px 0 4px; color: #1a1410; font-weight: bold; border-top: 1px solid #ddd; padding-top: 10px;">4. Dải Chân Trang (Bản Quyền)</h4>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'copyright_text' ) ); ?>">Dòng chữ bản quyền (Dùng {year} để lấy năm tự động):</label>
                <textarea class="widefat" rows="3" id="<?php echo esc_attr( $this->get_field_id( 'copyright_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'copyright_text' ) ); ?>"><?php echo esc_textarea( $copyright_text ); ?></textarea>
            </p>
        </div>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['newsletter_title_1'] = ! empty( $new_instance['newsletter_title_1'] ) ? sanitize_text_field( $new_instance['newsletter_title_1'] ) : '';
        $instance['newsletter_title_2'] = ! empty( $new_instance['newsletter_title_2'] ) ? sanitize_text_field( $new_instance['newsletter_title_2'] ) : '';
        $instance['placeholder']        = ! empty( $new_instance['placeholder'] ) ? sanitize_text_field( $new_instance['placeholder'] ) : '';
        $instance['btn_text']           = ! empty( $new_instance['btn_text'] ) ? sanitize_text_field( $new_instance['btn_text'] ) : '';
        $instance['brand_name']         = ! empty( $new_instance['brand_name'] ) ? sanitize_text_field( $new_instance['brand_name'] ) : '';
        $instance['brand_url']          = ! empty( $new_instance['brand_url'] ) ? esc_url_raw( $new_instance['brand_url'] ) : '';
        $instance['nav_menu']           = ! empty( $new_instance['nav_menu'] ) ? (int) $new_instance['nav_menu'] : 0;
        
        for ( $i = 1; $i <= 5; $i++ ) {
            $instance["link_{$i}_text"] = ! empty( $new_instance["link_{$i}_text"] ) ? sanitize_text_field( $new_instance["link_{$i}_text"] ) : '';
            $instance["link_{$i}_url"]  = ! empty( $new_instance["link_{$i}_url"] ) ? esc_url_raw( $new_instance["link_{$i}_url"] ) : '';
        }

        $instance['facebook_url']       = ! empty( $new_instance['facebook_url'] ) ? esc_url_raw( $new_instance['facebook_url'] ) : '';
        $instance['google_url']         = ! empty( $new_instance['google_url'] ) ? esc_url_raw( $new_instance['google_url'] ) : '';
        $instance['line_url']           = ! empty( $new_instance['line_url'] ) ? esc_url_raw( $new_instance['line_url'] ) : '';
        $instance['twitter_url']        = ! empty( $new_instance['twitter_url'] ) ? esc_url_raw( $new_instance['twitter_url'] ) : '';
        $instance['copyright_text']     = ! empty( $new_instance['copyright_text'] ) ? wp_kses_post( $new_instance['copyright_text'] ) : '';
        return $instance;
    }
}
