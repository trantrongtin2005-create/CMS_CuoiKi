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