<?php 
/**
* JobScout Metabox for Sidebar Layout
*
* @package JobScout
*
*/ 

function jobscout_add_sidebar_layout_box(){
    $post_id   = isset( $_GET['post'] ) ? $_GET['post'] : '';
    $shop_id   = get_option( 'woocommerce_shop_page_id' );
    $template  = get_post_meta( $post_id, '_wp_page_template', true );
    $templates = array( 'templates/portfolio.php' );
    
    /**
     * Remove sidebar metabox in specific page template.
    */
    if( ! in_array( $template, $templates ) && ( $shop_id != $post_id ) ){
        add_meta_box( 
            'jobscout_sidebar_layout',
            __( 'Sidebar Layout', 'jobscout' ),
            'jobscout_sidebar_layout_callback', 
            'page',
            'normal',
            'high'
        );
    }

    //for post
    add_meta_box( 
        'jobscout_sidebar_layout',
        __( 'Sidebar Layout', 'jobscout' ),
        'jobscout_sidebar_layout_callback', 
        'post',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'jobscout_add_sidebar_layout_box' );


/**
 * Get sidebar layout data.
 *
 * @return array
 */
if( ! function_exists( 'jobscout_get_sidebar_layout_data' ) ){
    function jobscout_get_sidebar_layout_data(){
        return array(
            'default-sidebar'=> array(
                'value'     => 'default-sidebar',
                'label'     => __( 'Default Sidebar', 'jobscout' ),
                'thumbnail' => get_template_directory_uri() . '/images/default-sidebar.png'
            ),
            'no-sidebar'     => array(
                'value'     => 'no-sidebar',
                'label'     => __( 'Full Width', 'jobscout' ),
                'thumbnail' => get_template_directory_uri() . '/images/full-width.png'
            ),
            'left-sidebar' => array(
                'value'     => 'left-sidebar',
                'label'     => __( 'Left Sidebar', 'jobscout' ),
                'thumbnail' => get_template_directory_uri() . '/images/left-sidebar.png'         
            ),
            'right-sidebar' => array(
                'value'     => 'right-sidebar',
                'label'     => __( 'Right Sidebar', 'jobscout' ),
                'thumbnail' => get_template_directory_uri() . '/images/right-sidebar.png'         
            )    
        );
    }
}

function jobscout_sidebar_layout_callback(){
    global $post;
    $jobscout_sidebar_layout = jobscout_get_sidebar_layout_data();
    wp_nonce_field( basename( __FILE__ ), 'jobscout_nonce' ); ?>
    <table class="form-table">
        <tr>
            <td colspan="4"><em class="f13"><?php esc_html_e( 'Choose Sidebar Template', 'jobscout' ); ?></em></td>
        </tr>    
        <tr>
            <td>
                <?php  
                    foreach( $jobscout_sidebar_layout as $field ){  
                        $layout = get_post_meta( $post->ID, '_jobscout_sidebar_layout', true ); ?>
                        <div class="hide-radio radio-image-wrapper" style="float:left; margin-right:30px;">
                            <input id="<?php echo esc_attr( $field['value'] ); ?>" type="radio" name="jobscout_sidebar_layout" value="<?php echo esc_attr( $field['value'] ); ?>" <?php checked( $field['value'], $layout ); if( empty( $layout ) ){ checked( $field['value'], 'default-sidebar' ); }?>/>
                            <label class="description" for="<?php echo esc_attr( $field['value'] ); ?>">
                                <img src="<?php echo esc_url( $field['thumbnail'] ); ?>" alt="<?php echo esc_attr( $field['label'] ); ?>" />
                            </label>
                        </div>
                        <?php 
                    } // end foreach 
                ?>
                <div class="clear"></div>
            </td>
        </tr>  
    </table>
 <?php 
}

function jobscout_save_sidebar_layout( $post_id ){
    $jobscout_sidebar_layout = jobscout_get_sidebar_layout_data();


    // Verify the nonce before proceeding.
    if( !isset( $_POST[ 'jobscout_nonce' ] ) || !wp_verify_nonce( $_POST[ 'jobscout_nonce' ], basename( __FILE__ ) ) )
        return;
    
    // Stop WP from clearing custom fields on autosave
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )  
        return;

    if('page' == $_POST['post_type'] ){  
        if( ! current_user_can( 'edit_page', $post_id ) ) return $post_id;  
    }elseif( ! current_user_can( 'edit_post', $post_id ) ){  
        return $post_id;  
    }
    
    $layout = isset( $_POST['jobscout_sidebar_layout'] ) ? sanitize_key( $_POST['jobscout_sidebar_layout'] ) : 'default-sidebar';

    if( array_key_exists( $layout, $jobscout_sidebar_layout ) ){
        update_post_meta( $post_id, '_jobscout_sidebar_layout', $layout );
    }else{
        delete_post_meta( $post_id, '_jobscout_sidebar_layout' );
    }           
}
add_action( 'save_post' , 'jobscout_save_sidebar_layout' );

/**
 * ========================================================
 * META BOX: CẤU HÌNH HIỂN THỊ TOP JOBS TRANG CHỦ
 * ========================================================
 */
function jobscout_add_job_listing_top_jobs_box() {
    add_meta_box(
        'jobscout_top_jobs_meta',
        __( '⭐ Cấu hình hiển thị TOP JOBS (Trang chủ)', 'jobscout' ),
        'jobscout_top_jobs_meta_callback',
        'job_listing',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'jobscout_add_job_listing_top_jobs_box' );

function jobscout_top_jobs_meta_callback( $post ) {
    wp_nonce_field( 'jobscout_top_jobs_nonce_action', 'jobscout_top_jobs_nonce' );

    $is_featured = get_post_meta( $post->ID, '_featured', true );
    $b1 = get_post_meta( $post->ID, '_job_bullet_1', true );
    $b2 = get_post_meta( $post->ID, '_job_bullet_2', true );
    $b3 = get_post_meta( $post->ID, '_job_bullet_3', true );

    // Nếu các bullet rỗng nhưng trong post_content có thẻ <li> thì lấy gợi ý
    if ( empty( $b1 ) && empty( $b2 ) && empty( $b3 ) && ! empty( $post->post_content ) ) {
        if ( preg_match_all( '/<li[^>]*>(.*?)<\/li>/is', $post->post_content, $matches ) ) {
            if ( isset( $matches[1][0] ) ) $b1 = trim( wp_strip_all_tags( $matches[1][0] ) );
            if ( isset( $matches[1][1] ) ) $b2 = trim( wp_strip_all_tags( $matches[1][1] ) );
            if ( isset( $matches[1][2] ) ) $b3 = trim( wp_strip_all_tags( $matches[1][2] ) );
        }
    }
    ?>
    <div style="padding: 10px 0;">
        <p style="margin-bottom: 14px;">
            <label style="font-weight: 600; font-size: 14px; cursor: pointer;">
                <input type="checkbox" name="_featured" value="1" <?php checked( $is_featured, '1' ); ?> style="margin-right: 6px;" />
                <?php esc_html_e( 'Đánh dấu là Việc làm Nổi bật (Ưu tiên hiển thị trên mục TOP JOBS ở trang chủ)', 'jobscout' ); ?>
            </label>
        </p>

        <p style="font-weight: 600; margin-bottom: 6px;">
            <?php esc_html_e( '3 gạch đầu dòng quyền lợi / trách nhiệm hiển thị trên thẻ Top Jobs ở Trang chủ:', 'jobscout' ); ?>
        </p>
        <p class="description" style="margin-top: 0; margin-bottom: 10px; color: #64748B;">
            <?php esc_html_e( 'Nếu để trống, hệ thống sẽ tự động trích xuất từ nội dung bài viết hoặc dùng mặc định trong Tùy biến.', 'jobscout' ); ?>
        </p>

        <table class="form-table" style="margin-top: 0;">
            <tr>
                <th style="width: 140px; padding: 8px 10px 8px 0;"><label for="_job_bullet_1"><?php esc_html_e( '• Quyền lợi 1:', 'jobscout' ); ?></label></th>
                <td style="padding: 8px 0;">
                    <input type="text" id="_job_bullet_1" name="_job_bullet_1" value="<?php echo esc_attr( $b1 ); ?>" class="large-text" placeholder="<?php esc_attr_e( 'Ví dụ: Lương thưởng hấp dẫn, xét tăng lương định kỳ...', 'jobscout' ); ?>" />
                </td>
            </tr>
            <tr>
                <th style="width: 140px; padding: 8px 10px 8px 0;"><label for="_job_bullet_2"><?php esc_html_e( '• Quyền lợi 2:', 'jobscout' ); ?></label></th>
                <td style="padding: 8px 0;">
                    <input type="text" id="_job_bullet_2" name="_job_bullet_2" value="<?php echo esc_attr( $b2 ); ?>" class="large-text" placeholder="<?php esc_attr_e( 'Ví dụ: Chế độ bảo hiểm đầy đủ, du lịch hằng năm...', 'jobscout' ); ?>" />
                </td>
            </tr>
            <tr>
                <th style="width: 140px; padding: 8px 10px 8px 0;"><label for="_job_bullet_3"><?php esc_html_e( '• Quyền lợi 3:', 'jobscout' ); ?></label></th>
                <td style="padding: 8px 0;">
                    <input type="text" id="_job_bullet_3" name="_job_bullet_3" value="<?php echo esc_attr( $b3 ); ?>" class="large-text" placeholder="<?php esc_attr_e( 'Ví dụ: Môi trường làm việc năng động, cơ hội thăng tiến...', 'jobscout' ); ?>" />
                </td>
            </tr>
        </table>
    </div>
    <?php
}

function jobscout_save_top_jobs_meta( $post_id ) {
    if ( ! isset( $_POST['jobscout_top_jobs_nonce'] ) || ! wp_verify_nonce( $_POST['jobscout_top_jobs_nonce'], 'jobscout_top_jobs_nonce_action' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Cập nhật _featured
    $is_featured = isset( $_POST['_featured'] ) ? '1' : '0';
    update_post_meta( $post_id, '_featured', $is_featured );

    // Cập nhật 3 bullets
    if ( isset( $_POST['_job_bullet_1'] ) ) {
        update_post_meta( $post_id, '_job_bullet_1', sanitize_text_field( $_POST['_job_bullet_1'] ) );
    }
    if ( isset( $_POST['_job_bullet_2'] ) ) {
        update_post_meta( $post_id, '_job_bullet_2', sanitize_text_field( $_POST['_job_bullet_2'] ) );
    }
    if ( isset( $_POST['_job_bullet_3'] ) ) {
        update_post_meta( $post_id, '_job_bullet_3', sanitize_text_field( $_POST['_job_bullet_3'] ) );
    }
}
add_action( 'save_post_job_listing', 'jobscout_save_top_jobs_meta' );

/**
 * ========================================================
 * META BOX: CẤU HÌNH BÀI VIẾT HIỂN THỊ Ở TRANG CHỦ
 * ========================================================
 */
function jobscout_add_home_blog_metabox() {
    add_meta_box(
        'jobscout_home_blog_meta',
        __( '⭐ Cấu hình hiển thị NEWEST BLOG ENTRIES (Trang chủ)', 'jobscout' ),
        'jobscout_home_blog_meta_callback',
        'post',
        'side',
        'high'
    );
}
add_action( 'add_meta_boxes', 'jobscout_add_home_blog_metabox' );

function jobscout_home_blog_meta_callback( $post ) {
    wp_nonce_field( 'jobscout_home_blog_nonce_action', 'jobscout_home_blog_nonce' );
    $is_featured_blog = get_post_meta( $post->ID, '_featured_home_blog', true );
    ?>
    <p>
        <label style="font-weight: 600; cursor: pointer;">
            <input type="checkbox" name="_featured_home_blog" value="1" <?php checked( $is_featured_blog, '1' ); ?> style="margin-right: 6px;" />
            <?php esc_html_e( 'Ưu tiên hiển thị bài viết này ở mục "NEWEST BLOG ENTRIES" trên Trang chủ', 'jobscout' ); ?>
        </label>
    </p>
    <p class="description" style="color: #64748B; font-size: 12px; margin-top: 6px;">
        <?php esc_html_e( 'Bài viết cần có Ảnh đại diện (Featured Image) để hiển thị đầy đủ và đẹp nhất theo khung thẻ trang chủ.', 'jobscout' ); ?>
    </p>
    <?php
}

function jobscout_save_home_blog_meta( $post_id ) {
    if ( ! isset( $_POST['jobscout_home_blog_nonce'] ) || ! wp_verify_nonce( $_POST['jobscout_home_blog_nonce'], 'jobscout_home_blog_nonce_action' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    $is_featured = isset( $_POST['_featured_home_blog'] ) ? '1' : '0';
    update_post_meta( $post_id, '_featured_home_blog', $is_featured );
}
add_action( 'save_post_post', 'jobscout_save_home_blog_meta' );

/**
 * ========================================================
 * META BOX: CẤU HÌNH MODULE CAREER WITH US (TRANG CHỦ)
 * Cho phép người dùng chỉnh sửa văn bản và thay ảnh nền trực tiếp trong Trang quản trị
 * ========================================================
 */
function jobscout_enqueue_admin_career_media_scripts( $hook ) {
    if ( in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
        wp_enqueue_media();
    }
}
add_action( 'admin_enqueue_scripts', 'jobscout_enqueue_admin_career_media_scripts' );

function jobscout_add_home_career_metabox() {
    $post_id       = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : ( isset( $_POST['post_ID'] ) ? absint( $_POST['post_ID'] ) : 0 );
    $front_page_id = (int) get_option( 'page_on_front' );

    // Hiển thị khi quản trị viên chỉnh sửa Trang chủ (Home)
    if ( ( $post_id > 0 && $post_id === $front_page_id ) || ( $post_id === 1036 ) ) {
        add_meta_box(
            'jobscout_home_career_meta',
            __( '⭐ Cấu hình Module CAREER WITH US (Thay ảnh nền & Nội dung Trang chủ)', 'jobscout' ),
            'jobscout_home_career_meta_callback',
            'page',
            'normal',
            'high'
        );
    }
}
add_action( 'add_meta_boxes', 'jobscout_add_home_career_metabox' );

function jobscout_home_career_meta_callback( $post ) {
    wp_nonce_field( 'jobscout_home_career_nonce_action', 'jobscout_home_career_nonce' );

    $title    = function_exists( 'jobscout_get_cta_career_option' ) ? jobscout_get_cta_career_option( 'cta_career_title' ) : 'CAREER WITH US';
    $text_1   = function_exists( 'jobscout_get_cta_career_option' ) ? jobscout_get_cta_career_option( 'cta_career_text_1' ) : '';
    $text_2   = function_exists( 'jobscout_get_cta_career_option' ) ? jobscout_get_cta_career_option( 'cta_career_text_2' ) : '';
    $btn_text = function_exists( 'jobscout_get_cta_career_option' ) ? jobscout_get_cta_career_option( 'cta_career_btn_text' ) : 'MORE ABOUT US';
    $btn_url  = function_exists( 'jobscout_get_cta_career_option' ) ? jobscout_get_cta_career_option( 'cta_career_btn_url' ) : home_url( '/about/' );
    $bg_url   = function_exists( 'jobscout_get_cta_career_bg_url' ) ? jobscout_get_cta_career_bg_url() : get_template_directory_uri() . '/images/career-with-us-bg.jpg';
    $default_bg = get_template_directory_uri() . '/images/career-with-us-bg.jpg';
    if ( function_exists( 'jobscout_normalize_media_host' ) ) {
        $default_bg = jobscout_normalize_media_host( $default_bg );
    }
    $customize_url = admin_url( 'customize.php?autofocus[section]=cta_section' );
    ?>
    <div style="padding: 6px 0 16px;">
        <div style="background: #F8FAFC; border-left: 4px solid #E87722; padding: 12px 16px; margin-bottom: 20px; border-radius: 0 4px 4px 0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
            <div>
                <strong style="color: #1E293B; font-size: 13.5px;">Module CAREER WITH US đang hiển thị trên Trang chủ</strong>
                <p style="margin: 4px 0 0; color: #64748B; font-size: 12.5px;">Bạn có thể tải ảnh mới lên tại đây hoặc dùng công cụ Tùy biến trực quan để xem trực tiếp sự thay đổi.</p>
            </div>
            <a href="<?php echo esc_url( $customize_url ); ?>" class="button button-secondary" target="_blank" style="font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                <span class="dashicons dashicons-admin-appearance" style="font-size: 16px; line-height: 1.6;"></span> Mở trong Tùy biến (Customizer)
            </a>
        </div>

        <table class="form-table" style="margin-top: 0;">
            <!-- 1. Ảnh nền -->
            <tr>
                <th style="width: 170px; vertical-align: top; padding-top: 14px;">
                    <label style="font-weight: 600; font-size: 13.5px;"><?php esc_html_e( 'Ảnh nền module:', 'jobscout' ); ?></label>
                </th>
                <td>
                    <div style="display: flex; gap: 20px; align-items: flex-start; flex-wrap: wrap;">
                        <div style="position: relative; width: 280px; height: 130px; border: 2px solid #CBD5E1; border-radius: 6px; overflow: hidden; background: #0F172A; box-shadow: 0 2px 6px rgba(0,0,0,0.08);">
                            <img id="cta-career-bg-preview" src="<?php echo esc_url( $bg_url ); ?>" alt="Preview" style="width: 100%; height: 100%; object-fit: cover;" />
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 8px; justify-content: center; min-height: 130px;">
                            <input type="hidden" id="cta_career_bg" name="cta_career_bg" value="<?php echo esc_attr( $bg_url ); ?>" />
                            <div>
                                <button type="button" class="button button-primary" id="cta-career-choose-img-btn" style="background: #E87722; border-color: #D26514; font-weight: 600; padding: 4px 14px; height: auto;">
                                    📷 Chọn ảnh từ Thư viện / Tải ảnh lên
                                </button>
                                <button type="button" class="button button-secondary" id="cta-career-reset-img-btn" style="margin-left: 6px;">
                                    🔄 Về ảnh mặc định
                                </button>
                            </div>
                            <span class="description" style="color: #64748B; font-size: 12px;">
                                Định dạng JPG, PNG, WebP (Khuyên dùng ảnh phong cảnh ngang độ phân giải từ 1600px).
                            </span>
                        </div>
                    </div>
                </td>
            </tr>

            <!-- 2. Tiêu đề -->
            <tr>
                <th><label for="cta_career_title" style="font-weight: 600;"><?php esc_html_e( 'Tiêu đề module:', 'jobscout' ); ?></label></th>
                <td>
                    <input type="text" id="cta_career_title" name="cta_career_title" value="<?php echo esc_attr( $title ); ?>" class="large-text" placeholder="CAREER WITH US" />
                </td>
            </tr>

            <!-- 3. Đoạn văn 1 -->
            <tr>
                <th><label for="cta_career_text_1" style="font-weight: 600;"><?php esc_html_e( 'Đoạn văn 1:', 'jobscout' ); ?></label></th>
                <td>
                    <textarea id="cta_career_text_1" name="cta_career_text_1" rows="3" class="large-text" placeholder="Nhập nội dung đoạn 1..."><?php echo esc_textarea( $text_1 ); ?></textarea>
                </td>
            </tr>

            <!-- 4. Đoạn văn 2 -->
            <tr>
                <th><label for="cta_career_text_2" style="font-weight: 600;"><?php esc_html_e( 'Đoạn văn 2:', 'jobscout' ); ?></label></th>
                <td>
                    <textarea id="cta_career_text_2" name="cta_career_text_2" rows="2" class="large-text" placeholder="Nhập nội dung đoạn 2 (để trống nếu không dùng)..."><?php echo esc_textarea( $text_2 ); ?></textarea>
                </td>
            </tr>

            <!-- 5. Chữ trên nút & Link -->
            <tr>
                <th><label for="cta_career_btn_text" style="font-weight: 600;"><?php esc_html_e( 'Nút bấm (CTA Button):', 'jobscout' ); ?></label></th>
                <td>
                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                        <input type="text" id="cta_career_btn_text" name="cta_career_btn_text" value="<?php echo esc_attr( $btn_text ); ?>" style="width: 220px;" placeholder="Chữ trên nút (ví dụ: MORE ABOUT US)" />
                        <input type="url" id="cta_career_btn_url" name="cta_career_btn_url" value="<?php echo esc_attr( $btn_url ); ?>" style="flex: 1; min-width: 280px;" placeholder="Liên kết đích (ví dụ: http://.../about/)" />
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <script type="text/javascript">
    jQuery(document).ready(function($){
        var frame;
        var defaultImgUrl = <?php echo json_encode( $default_bg ); ?>;

        $('#cta-career-choose-img-btn').on('click', function(e){
            e.preventDefault();
            if (frame) {
                frame.open();
                return;
            }
            frame = wp.media({
                title: 'Chọn ảnh nền cho Module CAREER WITH US',
                button: { text: 'Sử dụng ảnh này làm ảnh nền' },
                multiple: false
            });
            frame.on('select', function(){
                var attachment = frame.state().get('selection').first().toJSON();
                var selectedUrl = attachment.url;
                if (attachment.sizes && attachment.sizes.large) {
                    selectedUrl = attachment.sizes.large.url;
                }
                $('#cta_career_bg').val(selectedUrl);
                $('#cta-career-bg-preview').attr('src', selectedUrl);
            });
            frame.open();
        });

        $('#cta-career-reset-img-btn').on('click', function(e){
            e.preventDefault();
            if (confirm('Khôi phục ảnh nền về ảnh mặc định của giao diện?')) {
                $('#cta_career_bg').val(defaultImgUrl);
                $('#cta-career-bg-preview').attr('src', defaultImgUrl);
            }
        });
    });
    </script>
    <?php
}

function jobscout_save_home_career_meta( $post_id ) {
    if ( ! isset( $_POST['jobscout_home_career_nonce'] ) || ! wp_verify_nonce( $_POST['jobscout_home_career_nonce'], 'jobscout_home_career_nonce_action' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_page', $post_id ) ) {
        return;
    }

    if ( isset( $_POST['cta_career_bg'] ) ) {
        set_theme_mod( 'cta_career_bg', esc_url_raw( $_POST['cta_career_bg'] ) );
    }
    if ( isset( $_POST['cta_career_title'] ) ) {
        set_theme_mod( 'cta_career_title', sanitize_text_field( $_POST['cta_career_title'] ) );
    }
    if ( isset( $_POST['cta_career_text_1'] ) ) {
        set_theme_mod( 'cta_career_text_1', sanitize_textarea_field( $_POST['cta_career_text_1'] ) );
    }
    if ( isset( $_POST['cta_career_text_2'] ) ) {
        set_theme_mod( 'cta_career_text_2', sanitize_textarea_field( $_POST['cta_career_text_2'] ) );
    }
    if ( isset( $_POST['cta_career_btn_text'] ) ) {
        set_theme_mod( 'cta_career_btn_text', sanitize_text_field( $_POST['cta_career_btn_text'] ) );
    }
    if ( isset( $_POST['cta_career_btn_url'] ) ) {
        set_theme_mod( 'cta_career_btn_url', esc_url_raw( $_POST['cta_career_btn_url'] ) );
    }
}
add_action( 'save_post_page', 'jobscout_save_home_career_meta' );