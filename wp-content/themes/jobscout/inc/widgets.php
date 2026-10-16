<?php
/**
 * JobScout Widget Areas
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

    // Đăng ký Custom Widget cho Footer Module
    register_widget( 'CMS_Footer_Module_Widget' );
}
add_action( 'widgets_init', 'jobscout_widgets_init' );

// Tối ưu giao diện quản lý Widgets trực quan, dễ chỉnh sửa trong wp-admin
add_filter( 'use_widgets_block_editor', '__return_false' );

/**
 * Class Widget: CMS_Footer_Module_Widget
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
        $newsletter_title_1   = ! empty( $instance['newsletter_title_1'] ) ? $instance['newsletter_title_1'] : 'Subscribe To';
        $newsletter_title_2   = ! empty( $instance['newsletter_title_2'] ) ? $instance['newsletter_title_2'] : 'Our Newsletter';
        $placeholder          = ! empty( $instance['placeholder'] ) ? $instance['placeholder'] : 'Input your email address';
        $btn_text             = ! empty( $instance['btn_text'] ) ? $instance['btn_text'] : 'SUBSCRIBE';
        $brand_name           = ! empty( $instance['brand_name'] ) ? $instance['brand_name'] : 'Plan Do See Global Inc.';
        $brand_url            = ! empty( $instance['brand_url'] ) ? $instance['brand_url'] : home_url( '/' );
        $nav_menu             = ! empty( $instance['nav_menu'] ) ? (int) $instance['nav_menu'] : 0;
        $facebook_url         = ! empty( $instance['facebook_url'] ) ? $instance['facebook_url'] : 'https://facebook.com';
        $google_url           = ! empty( $instance['google_url'] ) ? $instance['google_url'] : 'https://google.com';
        $line_url             = ! empty( $instance['line_url'] ) ? $instance['line_url'] : 'https://line.me';
        $twitter_url          = ! empty( $instance['twitter_url'] ) ? $instance['twitter_url'] : 'https://twitter.com';
        $copyright_text       = ! empty( $instance['copyright_text'] ) ? $instance['copyright_text'] : '&copy; {year} Plan Do See Global Inc. All rights reserved.';
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

                <nav aria-label="<?php echo esc_attr__( 'Footer Navigation', 'jobscout' ); ?>">
                    <?php 
                    if ( $nav_menu && wp_get_nav_menu_object( $nav_menu ) ) :
                        wp_nav_menu( array(
                            'menu'        => $nav_menu,
                            'container'   => false,
                            'menu_class'  => 'ft-nav-menu',
                            'fallback_cb' => false,
                            'depth'       => 1,
                        ) );
                    else : 
                    ?>
                    <ul class="ft-nav-menu">
                        <li class="ft-nav-item"><a href="<?php echo esc_url( home_url( '/jobs/' ) ); ?>" class="ft-nav-link">JOBS</a></li>
                        <li class="ft-nav-item"><a href="<?php echo esc_url( home_url( '/jobs/' ) ); ?>" class="ft-nav-link">COMPANIES</a></li>
                        <li class="ft-nav-item"><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" class="ft-nav-link">BLOG</a></li>
                        <li class="ft-nav-item"><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="ft-nav-link">ABOUT</a></li>
                        <li class="ft-nav-item"><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="ft-nav-link">CONTACT</a></li>
                    </ul>
                    <?php endif; ?>
                </nav>

                <div class="ft-social-row" aria-label="Social Media">
                    <?php if ( ! empty( $facebook_url ) ) : ?>
                    <a href="<?php echo esc_url( $facebook_url ); ?>" target="_blank" rel="noopener noreferrer" class="ft-social-btn ft-social-facebook" title="Facebook" aria-label="Facebook">
                        <svg class="ft-social-svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M14 13.5h2.5l1-4H14v-2c0-1.03.3-1.75 1.75-1.75H17.5V2.31C16.85 2.22 15.65 2.1 14.28 2.1c-2.85 0-4.78 1.74-4.78 4.92v2.48H6.5v4h3v9.5h4.5v-9.5z"/>
                        </svg>
                    </a>
                    <?php endif; ?>

                    <?php if ( ! empty( $google_url ) ) : ?>
                    <a href="<?php echo esc_url( $google_url ); ?>" target="_blank" rel="noopener noreferrer" class="ft-social-btn ft-social-google" title="Google" aria-label="Google">
                        <svg class="ft-social-svg" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.665-5.17 3.665-9.17z"/>
                            <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.25v3.15C3.26 21.36 7.33 24 12 24z"/>
                            <path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.13-1.55.38-2.27V6.58H1.25C.45 8.16 0 9.94 0 12s.45 3.84 1.25 5.42l4.03-3.15z"/>
                            <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.33 0 3.26 2.64 1.25 6.58l4.03 3.15c.95-2.83 3.6-4.98 6.72-4.98z"/>
                        </svg>
                    </a>
                    <?php endif; ?>

                    <?php if ( ! empty( $line_url ) ) : ?>
                    <a href="<?php echo esc_url( $line_url ); ?>" target="_blank" rel="noopener noreferrer" class="ft-social-btn ft-social-line" title="LINE" aria-label="LINE">
                        <svg class="ft-social-svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M21.9 10.5c0-4.69-4.88-8.5-10.9-8.5C4.98 2 0.1 5.81 0.1 10.5c0 4.2 3.86 7.72 9.09 8.37.35.08.83.23.95.53.11.27.07.7.04.97-.06.42-.27 1.65-.29 1.81-.08.47-.38 1.86 1.63 1.01 2.01-.84 5.43-3.2 7.41-5.48 1.97-2.22 2.97-4.49 2.97-7.21zm-14.7 2.22H5.66c-.3 0-.54-.24-.54-.54V8.41c0-.3.24-.54.54-.54h1.54c.3 0 .54.24.54.54 0 .3-.24.54-.54.54h-1v1.17h1c.3 0 .54.24.54.54 0 .3-.24.54-.54.54zm2.63-.54c0 .3-.24.54-.54.54s-.54-.24-.54-.54V8.41c0-.3.24-.54.54-.54s.54.24.54.54v3.77zm4.27 0c0 .24-.16.45-.39.52-.05.01-.1.02-.15.02-.19 0-.37-.1-.47-.26l-1.89-2.58v2.32c0 .3-.24.54-.54.54s-.54-.24-.54-.54V8.41c0-.24.16-.45.39-.52.05-.01.1-.02.15-.02.19 0 .37.1.47.26l1.89 2.58V8.41c0-.3.24-.54.54-.54s.54.24.54.54v3.77zm3.17-2.6h-1v.79h1c.3 0 .54.24.54.54 0 .3-.24.54-.54.54h-1.54c-.3 0-.54-.24-.54-.54V8.41c0-.3.24-.54.54-.54h1.54c.3 0 .54.24.54.54 0 .3-.24.54-.54.54h-1v.74h1c.3 0 .54.24.54.54 0 .3-.24.54-.54.54z"/>
                        </svg>
                    </a>
                    <?php endif; ?>

                    <?php if ( ! empty( $twitter_url ) ) : ?>
                    <a href="<?php echo esc_url( $twitter_url ); ?>" target="_blank" rel="noopener noreferrer" class="ft-social-btn ft-social-twitter" title="Twitter / X" aria-label="Twitter / X">
                        <svg class="ft-social-svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.936 9.936 0 0024 4.59z"/>
                        </svg>
                    </a>
                    <?php endif; ?>
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
        $newsletter_title_1 = ! empty( $instance['newsletter_title_1'] ) ? $instance['newsletter_title_1'] : 'Subscribe To';
        $newsletter_title_2 = ! empty( $instance['newsletter_title_2'] ) ? $instance['newsletter_title_2'] : 'Our Newsletter';
        $placeholder        = ! empty( $instance['placeholder'] ) ? $instance['placeholder'] : 'Input your email address';
        $btn_text           = ! empty( $instance['btn_text'] ) ? $instance['btn_text'] : 'SUBSCRIBE';
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
                    <option value="0"><?php esc_html_e( '— Mặc định (JOBS, COMPANIES, BLOG, ABOUT, CONTACT) —', 'jobscout' ); ?></option>
                    <?php foreach ( $menus as $menu ) : ?>
                        <option value="<?php echo esc_attr( $menu->term_id ); ?>" <?php selected( $nav_menu, $menu->term_id ); ?>>
                            <?php echo esc_html( $menu->name ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </p>
            <?php endif; ?>

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
        $instance['facebook_url']       = ! empty( $new_instance['facebook_url'] ) ? esc_url_raw( $new_instance['facebook_url'] ) : '';
        $instance['google_url']         = ! empty( $new_instance['google_url'] ) ? esc_url_raw( $new_instance['google_url'] ) : '';
        $instance['line_url']           = ! empty( $new_instance['line_url'] ) ? esc_url_raw( $new_instance['line_url'] ) : '';
        $instance['twitter_url']        = ! empty( $new_instance['twitter_url'] ) ? esc_url_raw( $new_instance['twitter_url'] ) : '';
        $instance['copyright_text']     = ! empty( $new_instance['copyright_text'] ) ? wp_kses_post( $new_instance['copyright_text'] ) : '';
        return $instance;
    }
}
