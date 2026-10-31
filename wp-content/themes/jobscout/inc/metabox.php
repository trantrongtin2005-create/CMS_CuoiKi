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

    $title    = function_exists( 'jobscout_get_cta_career_option' ) ? jobscout_get_cta_career_option( 'cta_career_title' ) : 'CƠ HỘI NGHỀ NGHIỆP';
    $text_1   = function_exists( 'jobscout_get_cta_career_option' ) ? jobscout_get_cta_career_option( 'cta_career_text_1' ) : '';
    $text_2   = function_exists( 'jobscout_get_cta_career_option' ) ? jobscout_get_cta_career_option( 'cta_career_text_2' ) : '';
    $btn_text = function_exists( 'jobscout_get_cta_career_option' ) ? jobscout_get_cta_career_option( 'cta_career_btn_text' ) : 'VỀ CHÚNG TÔI';
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
                    <input type="text" id="cta_career_title" name="cta_career_title" value="<?php echo esc_attr( $title ); ?>" class="large-text" placeholder="CƠ HỘI NGHỀ NGHIỆP" />
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
                        <input type="text" id="cta_career_btn_text" name="cta_career_btn_text" value="<?php echo esc_attr( $btn_text ); ?>" style="width: 220px;" placeholder="Chữ trên nút (ví dụ: VỀ CHÚNG TÔI)" />
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

/**
 * ========================================================
 * META BOX: CẤU HÌNH TRANG DANH SÁCH VIỆC LÀM (JOBS LISTING)
 * Cho phép chỉnh sửa toàn bộ nội dung: Banner, Tiêu đề, Nút xem thêm
 * ========================================================
 */
function jobscout_add_jobs_page_metabox( $post_type = '', $post = null ) {
    if ( ! ( $post instanceof WP_Post ) ) {
        $post_id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : ( isset( $_POST['post_ID'] ) ? absint( $_POST['post_ID'] ) : 0 );
        if ( $post_id > 0 ) {
            $post = get_post( $post_id );
        }
    }
    if ( ! $post ) return;

    $template = get_post_meta( $post->ID, '_wp_page_template', true );
    $slug     = $post->post_name;
    $content  = $post->post_content;

    if ( 986 === (int) $post->ID || 'page-jobs.php' === $template || in_array( $slug, array( 'jobs', 'all-jobs', 'danh-sach-viec-lam', 'viec-lam' ), true ) || false !== strpos( $content, '[jobs]' ) ) {
        add_meta_box(
            'jobscout_jobs_page_meta',
            __( '⭐ Cấu hình Trang Danh sách Việc làm (Jobs Listing)', 'jobscout' ),
            'jobscout_jobs_page_meta_callback',
            'page',
            'normal',
            'high'
        );
    }
}
add_action( 'add_meta_boxes', 'jobscout_add_jobs_page_metabox', 10, 2 );

function jobscout_jobs_page_meta_callback( $post ) {
    wp_nonce_field( 'jobscout_jobs_page_nonce_action', 'jobscout_jobs_page_nonce' );

    $banner_img  = get_post_meta( $post->ID, '_jobs_banner_image', true );
    if ( empty( $banner_img ) ) {
        $banner_img = get_theme_mod( 'jobs_page_banner_image', get_template_directory_uri() . '/images/career-banner.jpg' );
    }
    $banner_title = get_post_meta( $post->ID, '_jobs_banner_title', true );
    if ( empty( $banner_title ) ) {
        $banner_title = get_theme_mod( 'jobs_page_banner_title', 'CƠ HỘI NGHỀ NGHIỆP' );
    }
    $banner_subtitle = get_post_meta( $post->ID, '_jobs_banner_subtitle', true );
    if ( empty( $banner_subtitle ) ) {
        $banner_subtitle = get_theme_mod( 'jobs_page_banner_subtitle', 'Khám phá các vị trí tuyển dụng mới nhất năm 2026 và đồng hành phát triển sự nghiệp cùng chúng tôi.' );
    }
    $sec_title   = get_post_meta( $post->ID, '_jobs_section_title', true );
    if ( empty( $sec_title ) ) {
        $sec_title = get_theme_mod( 'jobs_page_section_title', 'TẤT CẢ VIỆC LÀM' );
    }
    $btn_text    = get_post_meta( $post->ID, '_jobs_load_more_text', true );
    if ( empty( $btn_text ) ) {
        $btn_text = get_theme_mod( 'jobs_page_load_more_text', 'XEM THÊM VIỆC LÀM' );
    }

    $default_bg    = get_template_directory_uri() . '/images/career-banner.jpg';
    $customize_url = admin_url( 'customize.php?autofocus[section]=jobscout_jobs_page_section' );
    ?>
    <div style="padding: 6px 0 16px;">
        <div style="background: #F8FAFC; border-left: 4px solid #E87722; padding: 12px 16px; margin-bottom: 20px; border-radius: 0 4px 4px 0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
            <div>
                <strong style="color: #1E293B; font-size: 13.5px;">Quản lý nội dung Trang Việc làm (Jobs Listing)</strong>
                <p style="margin: 4px 0 0; color: #64748B; font-size: 12.5px;">Bạn có thể thay đổi ảnh bìa, tiêu đề trực tiếp tại đây hoặc mở Tùy biến để xem trực tiếp sự thay đổi.</p>
            </div>
            <a href="<?php echo esc_url( $customize_url ); ?>" class="button button-secondary" target="_blank" style="font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                <span class="dashicons dashicons-admin-appearance" style="font-size: 16px; line-height: 1.6;"></span> Mở trong Tùy biến (Customizer)
            </a>
        </div>

        <table class="form-table" style="margin-top: 0;">
            <!-- 1. Ảnh bìa Banner -->
            <tr>
                <th style="width: 170px; vertical-align: top; padding-top: 14px;">
                    <label style="font-weight: 600; font-size: 13.5px;"><?php esc_html_e( 'Ảnh bìa Banner:', 'jobscout' ); ?></label>
                </th>
                <td>
                    <div style="display: flex; gap: 20px; align-items: flex-start; flex-wrap: wrap;">
                        <div style="position: relative; width: 320px; height: 120px; border: 2px solid #CBD5E1; border-radius: 6px; overflow: hidden; background: #0F172A; box-shadow: 0 2px 6px rgba(0,0,0,0.08);">
                            <img id="jobs-banner-preview" src="<?php echo esc_url( $banner_img ); ?>" style="width: 100%; height: 100%; object-fit: cover; display: block;" alt="Banner Preview" />
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <input type="hidden" name="jobs_banner_image" id="jobs_banner_image" value="<?php echo esc_attr( $banner_img ); ?>" />
                            <button type="button" class="button button-primary" id="jobs-banner-select-btn" style="font-weight: 600;">
                                <span class="dashicons dashicons-upload" style="font-size: 16px; line-height: 1.7;"></span> Chọn ảnh từ Thư viện Media
                            </button>
                            <button type="button" class="button" id="jobs-banner-reset-btn" style="color: #64748B;">
                                <span class="dashicons dashicons-image-rotate" style="font-size: 16px; line-height: 1.7;"></span> Khôi phục ảnh mặc định
                            </button>
                            <span class="description" style="margin-top: 4px; color: #64748B;">Khuyến nghị: Ảnh tỉ lệ rộng (khoảng 1920x600px), định dạng JPG/PNG chất lượng cao.</span>
                        </div>
                    </div>
                </td>
            </tr>

            <!-- 2. Tiêu đề Banner -->
            <tr>
                <th><label for="jobs_banner_title" style="font-weight: 600;"><?php esc_html_e( 'Tiêu đề Banner:', 'jobscout' ); ?></label></th>
                <td>
                    <input type="text" name="jobs_banner_title" id="jobs_banner_title" value="<?php echo esc_attr( $banner_title ); ?>" class="large-text" placeholder="CƠ HỘI NGHỀ NGHIỆP" />
                    <p class="description"><?php esc_html_e( 'Tiêu đề lớn màu trắng hiển thị chính giữa ảnh bìa banner (mặc định: CƠ HỘI NGHỀ NGHIỆP).', 'jobscout' ); ?></p>
                </td>
            </tr>

            <!-- 2b. Mô tả ngắn Banner -->
            <tr>
                <th><label for="jobs_banner_subtitle" style="font-weight: 600;"><?php esc_html_e( 'Mô tả ngắn Banner:', 'jobscout' ); ?></label></th>
                <td>
                    <input type="text" name="jobs_banner_subtitle" id="jobs_banner_subtitle" value="<?php echo esc_attr( $banner_subtitle ); ?>" class="large-text" placeholder="Khám phá các vị trí tuyển dụng mới nhất năm 2026 và đồng hành phát triển sự nghiệp cùng chúng tôi." />
                    <p class="description"><?php esc_html_e( 'Đoạn mô tả phụ hiển thị bên dưới tiêu đề lớn trên ảnh bìa.', 'jobscout' ); ?></p>
                </td>
            </tr>

            <!-- 3. Tiêu đề danh sách -->
            <tr>
                <th><label for="jobs_section_title" style="font-weight: 600;"><?php esc_html_e( 'Tiêu đề danh sách:', 'jobscout' ); ?></label></th>
                <td>
                    <input type="text" name="jobs_section_title" id="jobs_section_title" value="<?php echo esc_attr( $sec_title ); ?>" class="large-text" placeholder="TẤT CẢ VIỆC LÀM" />
                    <p class="description"><?php esc_html_e( 'Tiêu đề phía trên lưới công việc (mặc định: TẤT CẢ VIỆC LÀM).', 'jobscout' ); ?></p>
                </td>
            </tr>

            <!-- 4. Chữ nút Xem thêm -->
            <tr>
                <th><label for="jobs_load_more_text" style="font-weight: 600;"><?php esc_html_e( 'Chữ nút Tải thêm:', 'jobscout' ); ?></label></th>
                <td>
                    <input type="text" name="jobs_load_more_text" id="jobs_load_more_text" value="<?php echo esc_attr( $btn_text ); ?>" class="regular-text" placeholder="XEM THÊM VIỆC LÀM" />
                    <p class="description"><?php esc_html_e( 'Chữ trên nút phân trang tải thêm việc làm (mặc định: XEM THÊM VIỆC LÀM).', 'jobscout' ); ?></p>
                </td>
            </tr>
        </table>
    </div>

    <script>
    jQuery(document).ready(function($) {
        var defaultImg = <?php echo wp_json_encode( $default_bg ); ?>;
        $('#jobs-banner-select-btn').on('click', function(e) {
            e.preventDefault();
            var frame = wp.media({
                title: 'Chọn ảnh bìa cho Trang Việc làm',
                button: { text: 'Sử dụng ảnh này' },
                multiple: false
            });
            frame.on('select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                var selectedUrl = attachment.url;
                $('#jobs_banner_image').val(selectedUrl);
                $('#jobs-banner-preview').attr('src', selectedUrl);
            });
            frame.open();
        });

        $('#jobs-banner-reset-btn').on('click', function(e) {
            e.preventDefault();
            if (confirm('Khôi phục ảnh bìa về ảnh chuẩn của giao diện?')) {
                $('#jobs_banner_image').val(defaultImg);
                $('#jobs-banner-preview').attr('src', defaultImg);
            }
        });
    });
    </script>
    <?php
}

function jobscout_save_jobs_page_meta( $post_id ) {
    if ( ! isset( $_POST['jobscout_jobs_page_nonce'] ) || ! wp_verify_nonce( $_POST['jobscout_jobs_page_nonce'], 'jobscout_jobs_page_nonce_action' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_page', $post_id ) ) {
        return;
    }

    if ( isset( $_POST['jobs_banner_image'] ) ) {
        $val = esc_url_raw( $_POST['jobs_banner_image'] );
        update_post_meta( $post_id, '_jobs_banner_image', $val );
        set_theme_mod( 'jobs_page_banner_image', $val );
    }
    if ( isset( $_POST['jobs_banner_title'] ) ) {
        $val = sanitize_text_field( $_POST['jobs_banner_title'] );
        update_post_meta( $post_id, '_jobs_banner_title', $val );
        set_theme_mod( 'jobs_page_banner_title', $val );
    }
    if ( isset( $_POST['jobs_banner_subtitle'] ) ) {
        $val = sanitize_text_field( $_POST['jobs_banner_subtitle'] );
        update_post_meta( $post_id, '_jobs_banner_subtitle', $val );
        set_theme_mod( 'jobs_page_banner_subtitle', $val );
    }
    if ( isset( $_POST['jobs_section_title'] ) ) {
        $val = sanitize_text_field( $_POST['jobs_section_title'] );
        update_post_meta( $post_id, '_jobs_section_title', $val );
        set_theme_mod( 'jobs_page_section_title', $val );
    }
    if ( isset( $_POST['jobs_load_more_text'] ) ) {
        $val = sanitize_text_field( $_POST['jobs_load_more_text'] );
        update_post_meta( $post_id, '_jobs_load_more_text', $val );
        set_theme_mod( 'jobs_page_load_more_text', $val );
    }
}
add_action( 'save_post_page', 'jobscout_save_jobs_page_meta' );

/**
 * Đồng bộ hai chiều: Khi lưu trong Tùy biến (Customizer) -> Tự động đồng bộ vào meta trang Việc làm
 */
function jobscout_sync_customizer_to_jobs_page() {
    $jobs_page = get_page_by_path( 'jobs' );
    $jobs_page_id = $jobs_page ? $jobs_page->ID : 986;
    if ( $jobs_page_id > 0 ) {
        $banner_img = get_theme_mod( 'jobs_page_banner_image' );
        if ( $banner_img ) {
            update_post_meta( $jobs_page_id, '_jobs_banner_image', esc_url_raw( $banner_img ) );
        }
        $banner_title = get_theme_mod( 'jobs_page_banner_title' );
        if ( $banner_title ) {
            update_post_meta( $jobs_page_id, '_jobs_banner_title', sanitize_text_field( $banner_title ) );
        }
        $banner_subtitle = get_theme_mod( 'jobs_page_banner_subtitle' );
        if ( $banner_subtitle ) {
            update_post_meta( $jobs_page_id, '_jobs_banner_subtitle', sanitize_text_field( $banner_subtitle ) );
        }
        $section_title = get_theme_mod( 'jobs_page_section_title' );
        if ( $section_title ) {
            update_post_meta( $jobs_page_id, '_jobs_section_title', sanitize_text_field( $section_title ) );
        }
        $load_more = get_theme_mod( 'jobs_page_load_more_text' );
        if ( $load_more ) {
            update_post_meta( $jobs_page_id, '_jobs_load_more_text', sanitize_text_field( $load_more ) );
        }

        // Cập nhật nội dung khối Gutenberg của trang Việc làm để khi mở trang soạn thảo thấy đúng thông tin mới nhất
        $page_record = get_post( $jobs_page_id );
        if ( $page_record && ! empty( $page_record->post_content ) ) {
            $updated_content = $page_record->post_content;
            if ( $banner_title ) {
                $updated_content = preg_replace(
                    '/(<h1[^>]*class="[^"]*jobs-banner-title[^"]*"[^>]*>).*?(<\/h1>)/s',
                    '${1}' . esc_html( $banner_title ) . '${2}',
                    $updated_content
                );
            }
            if ( $banner_subtitle ) {
                $updated_content = preg_replace(
                    '/(<p[^>]*class="[^"]*jobs-banner-subtitle[^"]*"[^>]*>).*?(<\/p>)/s',
                    '${1}' . esc_html( $banner_subtitle ) . '${2}',
                    $updated_content
                );
            }
            if ( $section_title ) {
                $updated_content = preg_replace(
                    '/(<h2[^>]*class="[^"]*jobs-section-heading[^"]*"[^>]*>).*?(<\/h2>)/s',
                    '${1}' . esc_html( $section_title ) . '${2}',
                    $updated_content
                );
            }
            if ( $banner_img ) {
                $updated_content = preg_replace(
                    '/(wp:cover\s*\{[^}]*"url":")[^"]*(")/s',
                    '${1}' . esc_url( $banner_img ) . '${2}',
                    $updated_content
                );
                $updated_content = preg_replace(
                    '/(<img[^>]*class="[^"]*wp-block-cover__image-background[^"]*"[^>]*src=")[^"]*(")/s',
                    '${1}' . esc_url( $banner_img ) . '${2}',
                    $updated_content
                );
            }
            if ( $updated_content !== $page_record->post_content ) {
                remove_action( 'save_post_page', 'jobscout_sync_jobs_gutenberg_editor_save', 25 );
                wp_update_post( array(
                    'ID'           => $jobs_page_id,
                    'post_content' => $updated_content,
                ) );
                add_action( 'save_post_page', 'jobscout_sync_jobs_gutenberg_editor_save', 25, 2 );
            }
        }
    }
}
add_action( 'customize_save_after', 'jobscout_sync_customizer_to_jobs_page' );

/**
 * Tự động đồng bộ khi người dùng chỉnh sửa trực tiếp các khối Gutenberg (Cover, Tiêu đề) trên trang Việc làm
 */
function jobscout_sync_jobs_gutenberg_editor_save( $post_id, $post ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( wp_is_post_revision( $post_id ) ) return;
    if ( ! current_user_can( 'edit_page', $post_id ) ) return;

    $slug = $post->post_name;
    if ( 986 !== (int) $post_id && 'jobs' !== $slug ) return;

    if ( ! function_exists( 'parse_blocks' ) ) return;

    $blocks = parse_blocks( $post->post_content );
    foreach ( $blocks as $block ) {
        if ( 'core/cover' === $block['blockName'] ) {
            if ( ! empty( $block['attrs']['url'] ) ) {
                $cover_url = esc_url_raw( $block['attrs']['url'] );
                update_post_meta( $post_id, '_jobs_banner_image', $cover_url );
                set_theme_mod( 'jobs_page_banner_image', $cover_url );
            }
            if ( ! empty( $block['innerBlocks'] ) ) {
                foreach ( $block['innerBlocks'] as $inner ) {
                    if ( 'core/heading' === $inner['blockName'] ) {
                        $raw_title = wp_strip_all_tags( render_block( $inner ) );
                        $clean_title = sanitize_text_field( trim( $raw_title ) );
                        if ( ! empty( $clean_title ) ) {
                            update_post_meta( $post_id, '_jobs_banner_title', $clean_title );
                            set_theme_mod( 'jobs_page_banner_title', $clean_title );
                        }
                    } elseif ( 'core/paragraph' === $inner['blockName'] ) {
                        $raw_sub = wp_strip_all_tags( render_block( $inner ) );
                        $clean_sub = sanitize_text_field( trim( $raw_sub ) );
                        if ( ! empty( $clean_sub ) ) {
                            update_post_meta( $post_id, '_jobs_banner_subtitle', $clean_sub );
                            set_theme_mod( 'jobs_page_banner_subtitle', $clean_sub );
                        }
                    }
                }
            }
        } elseif ( 'core/group' === $block['blockName'] && ! empty( $block['innerBlocks'] ) ) {
            foreach ( $block['innerBlocks'] as $inner ) {
                if ( 'core/heading' === $inner['blockName'] ) {
                    $raw_heading = wp_strip_all_tags( render_block( $inner ) );
                    $clean_heading = sanitize_text_field( trim( $raw_heading ) );
                    if ( ! empty( $clean_heading ) && false === stripos( $clean_heading, 'CƠ HỘI NGHỀ NGHIỆP' ) ) {
                        update_post_meta( $post_id, '_jobs_section_title', $clean_heading );
                        set_theme_mod( 'jobs_page_section_title', $clean_heading );
                    }
                } elseif ( 'core/paragraph' === $inner['blockName'] ) {
                    $raw_desc = wp_strip_all_tags( render_block( $inner ) );
                    $clean_desc = sanitize_text_field( trim( $raw_desc ) );
                    if ( ! empty( $clean_desc ) && false === stripos( $clean_desc, 'Khám phá các vị trí tuyển dụng' ) ) {
                        update_post_meta( $post_id, '_jobs_section_desc', $clean_desc );
                        set_theme_mod( 'jobs_page_section_desc', $clean_desc );
                    }
                }
            }
        } elseif ( 'core/heading' === $block['blockName'] ) {
            $raw_heading = wp_strip_all_tags( render_block( $block ) );
            $clean_heading = sanitize_text_field( trim( $raw_heading ) );
            if ( ! empty( $clean_heading ) && false === stripos( $clean_heading, 'CƠ HỘI NGHỀ NGHIỆP' ) ) {
                update_post_meta( $post_id, '_jobs_section_title', $clean_heading );
                set_theme_mod( 'jobs_page_section_title', $clean_heading );
            }
        }
    }
}
add_action( 'save_post_page', 'jobscout_sync_jobs_gutenberg_editor_save', 25, 2 );



