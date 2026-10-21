<?php
/**
 * Module Xử Lý Đăng Ký Bản Tin, Gửi Email & Cấu Hình SMTP Tích Hợp
 *
 * Chức năng:
 * 1. Cấu hình SMTP trực tiếp (Gmail, Outlook, Custom SMTP) qua hook phpmailer_init.
 * 2. Tiếp nhận AJAX đăng ký nhận bản tin từ Footer Module.
 * 3. Tự động gửi Email thông báo tới Quản trị viên (Admin) và Người đăng ký (Subscriber).
 * 4. Bảng điều khiển quản trị trong wp-admin (Giao diện > Cấu hình SMTP & Bản tin):
 *    - Tab 1: Cấu hình SMTP & Gửi email kiểm tra (Test SMTP).
 *    - Tab 2: Danh sách người đăng ký nhận bản tin (Subscribers).
 *    - Tab 3: Nhật ký gửi email (Email Logs).
 *
 * @package jobscout
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * ========================================================
 * 1. CẤU HÌNH SMTP QUA WORDPRESS PHPMAILER (phpmailer_init)
 * ========================================================
 */
function cms_configure_smtp( $phpmailer ) {
    $smtp_enabled = (int) get_option( 'cms_smtp_enabled', 0 );
    if ( ! $smtp_enabled ) {
        return;
    }

    $host       = get_option( 'cms_smtp_host', 'smtp.gmail.com' );
    $port       = (int) get_option( 'cms_smtp_port', 587 );
    $encryption = get_option( 'cms_smtp_encryption', 'tls' );
    $username   = get_option( 'cms_smtp_username', '' );
    $password   = get_option( 'cms_smtp_password', '' );
    $from_email = get_option( 'cms_smtp_from_email', $username );
    $from_name  = get_option( 'cms_smtp_from_name', get_bloginfo( 'name' ) );

    // Nếu thiếu thông tin xác thực thì không kích hoạt SMTP
    if ( empty( $host ) || empty( $username ) || empty( $password ) ) {
        return;
    }

    $phpmailer->isSMTP();
    $phpmailer->Host       = $host;
    $phpmailer->SMTPAuth   = true;
    $phpmailer->Port       = $port;
    $phpmailer->Username   = $username;
    $phpmailer->Password   = $password;
    $phpmailer->SMTPSecure = ( 'ssl' === strtolower( $encryption ) ) ? 'ssl' : 'tls';
    $phpmailer->Timeout    = 15;

    if ( ! empty( $from_email ) && is_email( $from_email ) ) {
        $phpmailer->setFrom( $from_email, $from_name, false );
    }
}
add_action( 'phpmailer_init', 'cms_configure_smtp' );

/** Ghi nhận lỗi wp_mail để hiển thị trên màn hình kiểm tra */
add_action( 'wp_mail_failed', function( $wp_error ) {
    if ( is_wp_error( $wp_error ) ) {
        update_option( 'cms_smtp_last_error', $wp_error->get_error_message() );
    }
} );

/**
 * ========================================================
 * 2. AJAX HANDLER: ĐĂNG KÝ BẢN TIN VÀ GỬI EMAIL
 * ========================================================
 */
function cms_handle_newsletter_subscription() {
    $email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';

    if ( empty( $email ) || ! is_email( $email ) ) {
        wp_send_json_error( array( 'message' => __( 'Địa chỉ email không hợp lệ. Vui lòng kiểm tra lại!', 'jobscout' ) ) );
    }

    // 1. Kiểm tra danh sách đã đăng ký
    $subscribers_data = get_option( 'cms_newsletter_subscribers_data', array() );
    if ( ! is_array( $subscribers_data ) ) {
        $subscribers_data = array();
    }

    $existing_emails = array_map( 'strtolower', array_column( $subscribers_data, 'email' ) );
    if ( in_array( strtolower( $email ), $existing_emails, true ) ) {
        wp_send_json_error( array( 'message' => sprintf( __( 'Email "%s" đã được đăng ký nhận bản tin trước đó!', 'jobscout' ), esc_html( $email ) ) ) );
    }

    // 2. Lưu thông tin người đăng ký mới
    $new_entry = array(
        'email'      => $email,
        'date'       => current_time( 'mysql' ),
        'ip'         => isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( $_SERVER['REMOTE_ADDR'] ) : '127.0.0.1',
        'user_agent' => isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( substr( $_SERVER['HTTP_USER_AGENT'], 0, 120 ) ) : '',
    );
    $subscribers_data[] = $new_entry;
    update_option( 'cms_newsletter_subscribers_data', $subscribers_data );

    // Duy trì danh sách đơn giản cho tương thích ngược
    $simple_list = get_option( 'cms_newsletter_subscribers', array() );
    if ( ! is_array( $simple_list ) ) {
        $simple_list = array();
    }
    if ( ! in_array( $email, $simple_list, true ) ) {
        $simple_list[] = $email;
        update_option( 'cms_newsletter_subscribers', $simple_list );
    }

    // 3. Chuẩn bị thông tin người gửi
    $site_name  = get_bloginfo( 'name' );
    $from_email = get_option( 'cms_smtp_from_email', get_option( 'admin_email' ) );
    if ( empty( $from_email ) ) {
        $from_email = 'no-reply@' . ( isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( $_SERVER['HTTP_HOST'] ) : 'localhost' );
    }
    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . $site_name . ' <' . $from_email . '>',
    );

    // Xóa lỗi cũ trước khi gửi
    delete_option( 'cms_smtp_last_error' );

    // 4. Gửi Email thông báo tới Quản trị viên (Admin)
    $admin_recipient = get_theme_mod( 'footer_newsletter_admin_email', get_option( 'admin_email' ) );
    if ( empty( $admin_recipient ) ) {
        $admin_recipient = get_option( 'admin_email' );
    }
    $admin_subject = get_theme_mod( 'footer_newsletter_admin_subject', '[Plan Do See] Đăng ký nhận bản tin mới từ: ' . $email );
    $admin_subject = str_replace( '{email}', $email, $admin_subject );
    $admin_body    = cms_newsletter_get_admin_email_template( $email, $new_entry );

    $admin_mail_sent = @wp_mail( $admin_recipient, $admin_subject, $admin_body, $headers );
    cms_newsletter_log_email( $admin_recipient, $admin_subject, $admin_mail_sent ? 'Đã gửi qua SMTP' : 'Đã ghi nhận (Local)' );

    // 5. Gửi Email chào mừng tới Người đăng ký (Subscriber)
    $send_welcome = get_theme_mod( 'footer_newsletter_send_welcome', true );
    if ( $send_welcome ) {
        $user_subject = get_theme_mod( 'footer_newsletter_user_subject', 'Chào mừng bạn đến với Bản tin việc làm - Plan Do See Global Inc.' );
        $user_body    = cms_newsletter_get_subscriber_email_template( $email );

        $user_mail_sent = @wp_mail( $email, $user_subject, $user_body, $headers );
        cms_newsletter_log_email( $email, $user_subject, $user_mail_sent ? 'Đã gửi qua SMTP' : 'Đã ghi nhận (Local)' );
    }

    wp_send_json_success( array(
        'message' => sprintf( __( '🎉 Cảm ơn bạn! Đã đăng ký nhận bản tin việc làm và gửi email xác nhận tới: %s', 'jobscout' ), esc_html( $email ) ),
    ) );
}
add_action( 'wp_ajax_cms_subscribe_newsletter', 'cms_handle_newsletter_subscription' );
add_action( 'wp_ajax_nopriv_cms_subscribe_newsletter', 'cms_handle_newsletter_subscription' );

/**
 * ========================================================
 * 3. TEMPLATE HTML EMAIL CHO ADMIN & SUBSCRIBER
 * ========================================================
 */
function cms_newsletter_get_admin_email_template( $email, $data ) {
    $site_name = get_bloginfo( 'name' );
    $site_url  = home_url( '/' );
    $admin_url = admin_url( 'themes.php?page=cms-newsletter-subscribers&tab=subscribers' );
    $time_str  = current_time( 'd/m/Y H:i:s' );
    $ip        = ! empty( $data['ip'] ) ? $data['ip'] : '127.0.0.1';

    ob_start();
    ?>
    <!DOCTYPE html>
    <html lang="vi">
    <head>
        <meta charset="UTF-8">
        <title>Thông báo đăng ký bản tin mới</title>
    </head>
    <body style="margin: 0; padding: 0; background-color: #F5F6F8; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #1E2022; line-height: 1.6;">
        <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #F5F6F8; padding: 30px 15px;">
            <tr>
                <td align="center">
                    <table width="600" cellpadding="0" cellspacing="0" style="max-width: 600px; width: 100%; background-color: #FFFFFF; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,0.06);">
                        <tr>
                            <td style="background-color: #1A1410; padding: 28px 32px; text-align: center;">
                                <h1 style="margin: 0 0 6px; font-size: 22px; font-weight: 800; color: #EB6324; letter-spacing: 0.5px; text-transform: uppercase;">PLAN DO SEE GLOBAL INC.</h1>
                                <p style="margin: 0; font-size: 13px; color: #A0A4A8; letter-spacing: 0.3px;">HỆ THỐNG THÔNG BÁO QUẢN TRỊ VIÊN</p>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 36px 32px;">
                                <div style="display: inline-block; background-color: #FFF3EB; border: 1px solid #FFE0CC; border-radius: 4px; padding: 6px 14px; margin-bottom: 20px;">
                                    <span style="color: #EB6324; font-size: 13px; font-weight: 700;">THÔNG BÁO MỚI</span>
                                </div>
                                <h2 style="margin: 0 0 16px; font-size: 20px; font-weight: 700; color: #1E2022;">Có khách hàng vừa đăng ký nhận bản tin tuyển dụng!</h2>
                                <p style="margin: 0 0 24px; font-size: 15px; color: #4F5660;">Hệ thống vừa ghi nhận thêm một người dùng đăng ký theo dõi bản tin việc làm thông qua thanh đăng ký ở chân trang (Footer).</p>
                                
                                <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 6px; margin-bottom: 28px;">
                                    <tr>
                                        <td style="padding: 14px 18px; border-bottom: 1px solid #E5E7EB; width: 38%; font-size: 14px; font-weight: 600; color: #6B7280;">Địa chỉ Email:</td>
                                        <td style="padding: 14px 18px; border-bottom: 1px solid #E5E7EB; font-size: 15px; font-weight: 700; color: #EB6324;">
                                            <a href="mailto:<?php echo esc_attr( $email ); ?>" style="color: #EB6324; text-decoration: none;"><?php echo esc_html( $email ); ?></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 14px 18px; border-bottom: 1px solid #E5E7EB; font-size: 14px; font-weight: 600; color: #6B7280;">Thời gian đăng ký:</td>
                                        <td style="padding: 14px 18px; border-bottom: 1px solid #E5E7EB; font-size: 14px; color: #1E2022;"><?php echo esc_html( $time_str ); ?></td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 14px 18px; border-bottom: 1px solid #E5E7EB; font-size: 14px; font-weight: 600; color: #6B7280;">Địa chỉ IP:</td>
                                        <td style="padding: 14px 18px; border-bottom: 1px solid #E5E7EB; font-size: 14px; color: #1E2022;"><?php echo esc_html( $ip ); ?></td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 14px 18px; font-size: 14px; font-weight: 600; color: #6B7280;">Vị trí form:</td>
                                        <td style="padding: 14px 18px; font-size: 14px; color: #1E2022;">Dải nhận bản tin chân trang (Footer Newsletter Bar)</td>
                                    </tr>
                                </table>

                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td align="center">
                                            <a href="<?php echo esc_url( $admin_url ); ?>" style="display: inline-block; background-color: #EB6324; color: #FFFFFF; font-size: 15px; font-weight: 700; text-decoration: none; padding: 13px 32px; border-radius: 4px; text-transform: uppercase; letter-spacing: 0.5px;">Xem Danh Sách Trong Quản Trị</a>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="background-color: #F5F6F8; padding: 20px 32px; text-align: center; border-top: 1px solid #E5E7EB;">
                                <p style="margin: 0; font-size: 13px; color: #8F959E;">Email thông báo tự động từ hệ thống website <a href="<?php echo esc_url( $site_url ); ?>" style="color: #EB6324; text-decoration: none;"><?php echo esc_html( $site_name ); ?></a></p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
    </html>
    <?php
    return ob_get_clean();
}

function cms_newsletter_get_subscriber_email_template( $email ) {
    $site_name = get_bloginfo( 'name' );
    $site_url  = home_url( '/' );
    $jobs_url  = home_url( '/jobs/' );
    $year      = date( 'Y' );

    ob_start();
    ?>
    <!DOCTYPE html>
    <html lang="vi">
    <head>
        <meta charset="UTF-8">
        <title>Chào mừng bạn đến với Bản tin tuyển dụng</title>
    </head>
    <body style="margin: 0; padding: 0; background-color: #F5F6F8; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #1E2022; line-height: 1.6;">
        <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #F5F6F8; padding: 30px 15px;">
            <tr>
                <td align="center">
                    <table width="600" cellpadding="0" cellspacing="0" style="max-width: 600px; width: 100%; background-color: #FFFFFF; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,0.06);">
                        <tr>
                            <td style="background: linear-gradient(135deg, #EB6324 0%, #D45217 100%); padding: 36px 32px; text-align: center;">
                                <h1 style="margin: 0 0 8px; font-size: 24px; font-weight: 800; color: #FFFFFF; letter-spacing: 0.5px; text-transform: uppercase;">PLAN DO SEE GLOBAL INC.</h1>
                                <p style="margin: 0; font-size: 15px; color: #FFEADB; letter-spacing: 0.3px;">Chào Mừng Bạn Đến Với Bản Tin Việc Làm Hàng Đầu</p>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 36px 32px;">
                                <h2 style="margin: 0 0 16px; font-size: 20px; font-weight: 700; color: #1E2022;">Xin chào <span style="color: #EB6324;"><?php echo esc_html( $email ); ?></span>,</h2>
                                <p style="margin: 0 0 18px; font-size: 15px; color: #4F5660;">Cảm ơn bạn đã quan tâm và đăng ký nhận thông tin việc làm từ <strong>Plan Do See Global Inc.</strong></p>
                                <p style="margin: 0 0 24px; font-size: 15px; color: #4F5660;">Kể từ hôm nay, bạn sẽ là một trong những ứng viên đầu tiên nhận được:</p>
                                
                                <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #FFF9F5; border: 1px solid #FFE5D4; border-radius: 6px; padding: 18px 22px; margin-bottom: 28px;">
                                    <tr>
                                        <td style="padding: 8px 0; font-size: 14px; color: #1E2022;">
                                            <strong style="color: #EB6324;">✦ Top Jobs Mới Nhất:</strong> Các vị trí tuyển dụng lương cao, đãi ngộ tốt từ các doanh nghiệp Nhật Bản và đối tác quốc tế.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px 0; font-size: 14px; color: #1E2022;">
                                            <strong style="color: #EB6324;">✦ Hồ Sơ Doanh Nghiệp:</strong> Khám phá văn hóa làm việc và chế độ phúc lợi hấp dẫn tại các tập đoàn hàng đầu.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px 0; font-size: 14px; color: #1E2022;">
                                            <strong style="color: #EB6324;">✦ Cẩm Nang Nghề Nghiệp:</strong> Bí quyết phỏng vấn, kinh nghiệm viết CV và mẹo đàm phán lương hiệu quả.
                                        </td>
                                    </tr>
                                </table>

                                <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 12px;">
                                    <tr>
                                        <td align="center">
                                            <a href="<?php echo esc_url( $jobs_url ); ?>" style="display: inline-block; background-color: #EB6324; color: #FFFFFF; font-size: 15px; font-weight: 700; text-decoration: none; padding: 14px 34px; border-radius: 4px; text-transform: uppercase; letter-spacing: 0.5px; box-shadow: 0 4px 12px rgba(235,99,36,0.3);">🔍 Khám Phá Các Việc Làm Ngay</a>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="background-color: #1A1410; padding: 24px 32px; text-align: center; color: #8F959E;">
                                <p style="margin: 0 0 6px; font-size: 13px; color: #D1D5DB;"><strong>Plan Do See Global Inc.</strong> &bull; Đồng hành cùng sự nghiệp của bạn</p>
                                <p style="margin: 0; font-size: 12px; color: #6B7280;">&copy; <?php echo esc_html( $year ); ?> Plan Do See Global Inc. All rights reserved.</p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
    </html>
    <?php
    return ob_get_clean();
}

/**
 * ========================================================
 * 4. HÀM GHI NHẬT KÝ EMAIL (EMAIL LOGS)
 * ========================================================
 */
function cms_newsletter_log_email( $to, $subject, $status ) {
    $logs = get_option( 'cms_newsletter_mail_logs', array() );
    if ( ! is_array( $logs ) ) {
        $logs = array();
    }

    array_unshift( $logs, array(
        'time'    => current_time( 'd/m/Y H:i:s' ),
        'to'      => $to,
        'subject' => $subject,
        'status'  => $status,
    ) );

    $logs = array_slice( $logs, 0, 50 );
    update_option( 'cms_newsletter_mail_logs', $logs );
}

/**
 * ========================================================
 * 5. TRANG QUẢN TRỊ TRONG WORDPRESS: CẤU HÌNH SMTP & BẢN TIN
 * ========================================================
 */
function cms_newsletter_register_admin_page() {
    add_theme_page(
        __( 'Cấu hình SMTP & Bản tin', 'jobscout' ),
        __( 'Cấu hình SMTP & Bản tin', 'jobscout' ),
        'manage_options',
        'cms-newsletter-subscribers',
        'cms_newsletter_render_admin_page'
    );
}
add_action( 'admin_menu', 'cms_newsletter_register_admin_page' );

function cms_newsletter_render_admin_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $active_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'smtp';

    // 1. Xử lý Lưu Cấu hình SMTP
    if ( isset( $_POST['cms_save_smtp_settings'] ) && check_admin_referer( 'cms_save_smtp_nonce' ) ) {
        $enabled    = ! empty( $_POST['cms_smtp_enabled'] ) ? 1 : 0;
        $host       = sanitize_text_field( wp_unslash( $_POST['cms_smtp_host'] ?? 'smtp.gmail.com' ) );
        $port       = (int) ( $_POST['cms_smtp_port'] ?? 587 );
        $encryption = sanitize_text_field( wp_unslash( $_POST['cms_smtp_encryption'] ?? 'tls' ) );
        $username   = sanitize_text_field( wp_unslash( $_POST['cms_smtp_username'] ?? '' ) );
        $password   = sanitize_text_field( wp_unslash( $_POST['cms_smtp_password'] ?? '' ) );
        $from_email = sanitize_email( wp_unslash( $_POST['cms_smtp_from_email'] ?? $username ) );
        $from_name  = sanitize_text_field( wp_unslash( $_POST['cms_smtp_from_name'] ?? get_bloginfo( 'name' ) ) );

        update_option( 'cms_smtp_enabled', $enabled );
        update_option( 'cms_smtp_host', $host );
        update_option( 'cms_smtp_port', $port );
        update_option( 'cms_smtp_encryption', $encryption );
        update_option( 'cms_smtp_username', $username );
        if ( ! empty( $password ) ) {
            // Không xóa pass nếu user để trống khi đã lưu trước đó
            update_option( 'cms_smtp_password', $password );
        }
        update_option( 'cms_smtp_from_email', $from_email );
        update_option( 'cms_smtp_from_name', $from_name );

        // Đồng bộ Contact Form 7 Form 1129: Bỏ skip_mail nếu SMTP bật
        $cf7_id = 1129;
        if ( get_post( $cf7_id ) ) {
            if ( $enabled ) {
                update_post_meta( $cf7_id, '_additional_settings', '' );
            } else {
                update_post_meta( $cf7_id, '_additional_settings', "skip_mail: on\n" );
            }
        }

        echo '<div class="notice notice-success is-dismissible"><p>' . __( 'Đã lưu cấu hình SMTP thành công!', 'jobscout' ) . '</p></div>';
    }

    // 2. Xử lý Gửi Email Kiểm Tra SMTP (Test Real Email)
    $test_result_msg  = '';
    $test_result_type = '';
    if ( isset( $_POST['cms_test_smtp_mail'] ) && check_admin_referer( 'cms_test_smtp_nonce' ) ) {
        $test_to = sanitize_email( wp_unslash( $_POST['test_email'] ?? '' ) );
        if ( empty( $test_to ) || ! is_email( $test_to ) ) {
            $test_result_msg  = 'Vui lòng nhập địa chỉ email hợp lệ để nhận thử nghiệm!';
            $test_result_type = 'error';
        } else {
            delete_option( 'cms_smtp_last_error' );
            $site_name = get_bloginfo( 'name' );
            $headers   = array(
                'Content-Type: text/html; charset=UTF-8',
                'From: ' . $site_name . ' <' . ( get_option( 'cms_smtp_from_email' ) ?: get_option( 'admin_email' ) ) . '>',
            );
            $subject   = '[Plan Do See Test] Thử nghiệm kết nối SMTP thành công!';
            $body      = cms_newsletter_get_subscriber_email_template( $test_to );

            $sent = wp_mail( $test_to, $subject, $body, $headers );
            $last_error = get_option( 'cms_smtp_last_error', '' );

            if ( $sent ) {
                cms_newsletter_log_email( $test_to, $subject, 'Đã gửi qua SMTP (Test Thành công)' );
                $test_result_msg  = '🎉 THÀNH CÔNG! Email thử nghiệm đã được gửi thực tế tới "' . esc_html( $test_to ) . '" qua SMTP!';
                $test_result_type = 'success';
            } else {
                cms_newsletter_log_email( $test_to, $subject, 'Thất bại: ' . ( $last_error ?: 'Lỗi cấu hình' ) );
                $test_result_msg  = '❌ GỬI THẤT BẠI: ' . ( $last_error ?: 'Không thể kết nối đến máy chủ SMTP. Vui lòng kiểm tra lại Username, Mật khẩu ứng dụng hoặc Port!' );
                $test_result_type = 'error';
            }
        }
    }

    // 3. Xử lý Xóa 1 subscriber
    if ( isset( $_GET['action'] ) && 'delete' === $_GET['action'] && ! empty( $_GET['email'] ) && check_admin_referer( 'cms_delete_subscriber' ) ) {
        $del_email = sanitize_email( wp_unslash( $_GET['email'] ) );
        $subs = get_option( 'cms_newsletter_subscribers_data', array() );
        if ( is_array( $subs ) ) {
            $subs = array_values( array_filter( $subs, function( $item ) use ( $del_email ) {
                return strtolower( $item['email'] ) !== strtolower( $del_email );
            } ) );
            update_option( 'cms_newsletter_subscribers_data', $subs );
        }
        $simple = get_option( 'cms_newsletter_subscribers', array() );
        if ( is_array( $simple ) ) {
            $simple = array_values( array_diff( $simple, array( $del_email ) ) );
            update_option( 'cms_newsletter_subscribers', $simple );
        }
        echo '<div class="notice notice-success is-dismissible"><p>' . sprintf( __( 'Đã xóa email "%s" khỏi danh sách nhận bản tin!', 'jobscout' ), esc_html( $del_email ) ) . '</p></div>';
    }

    // Đọc các giá trị cài đặt
    $smtp_enabled   = (int) get_option( 'cms_smtp_enabled', 0 );
    $smtp_host      = get_option( 'cms_smtp_host', 'smtp.gmail.com' );
    $smtp_port      = (int) get_option( 'cms_smtp_port', 587 );
    $smtp_encryption= get_option( 'cms_smtp_encryption', 'tls' );
    $smtp_username  = get_option( 'cms_smtp_username', '' );
    $smtp_password  = get_option( 'cms_smtp_password', '' );
    $smtp_from_email= get_option( 'cms_smtp_from_email', $smtp_username ?: get_option( 'admin_email' ) );
    $smtp_from_name = get_option( 'cms_smtp_from_name', get_bloginfo( 'name' ) );

    $subscribers = get_option( 'cms_newsletter_subscribers_data', array() );
    if ( ! is_array( $subscribers ) ) {
        $subscribers = array();
    }
    $logs = get_option( 'cms_newsletter_mail_logs', array() );
    if ( ! is_array( $logs ) ) {
        $logs = array();
    }
    ?>
    <div class="wrap" style="max-width: 1100px;">
        <h1 style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
            <span style="color: #eb6324;">✉</span> <?php esc_html_e( 'Cấu Hình SMTP & Bản Tin Tuyển Dụng', 'jobscout' ); ?>
        </h1>

        <!-- Thanh Tabs -->
        <nav class="nav-tab-wrapper" style="margin-bottom: 25px;">
            <a href="?page=cms-newsletter-subscribers&tab=smtp" class="nav-tab <?php echo ( 'smtp' === $active_tab ) ? 'nav-tab-active' : ''; ?>">
                ⚙️ Cấu Hình SMTP (Gửi Mail Thật)
            </a>
            <a href="?page=cms-newsletter-subscribers&tab=subscribers" class="nav-tab <?php echo ( 'subscribers' === $active_tab ) ? 'nav-tab-active' : ''; ?>">
                👥 Danh Sách Đăng Ký (<?php echo count( $subscribers ); ?>)
            </a>
            <a href="?page=cms-newsletter-subscribers&tab=logs" class="nav-tab <?php echo ( 'logs' === $active_tab ) ? 'nav-tab-active' : ''; ?>">
                📜 Nhật Ký Gửi Thư (<?php echo count( $logs ); ?>)
            </a>
        </nav>

        <?php if ( ! empty( $test_result_msg ) ) : ?>
            <div class="notice notice-<?php echo esc_attr( $test_result_type ); ?> is-dismissible" style="padding: 12px 15px;">
                <p style="font-size: 15px; font-weight: 600; margin: 0;"><?php echo esc_html( $test_result_msg ); ?></p>
            </div>
        <?php endif; ?>

        <?php if ( 'smtp' === $active_tab ) : ?>
            <!-- TAB 1: CẤU HÌNH SMTP -->
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 25px; align-items: start;">
                <!-- Cột trái: Form cấu hình & Form test -->
                <div>
                    <div style="background: #fff; padding: 25px; border-radius: 8px; border: 1px solid #ccd0d4; margin-bottom: 25px; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
                        <h2 style="margin: 0 0 18px; font-size: 18px; color: #1e2022; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                            Thiết Lập Máy Chủ Gửi Thư (SMTP Settings)
                        </h2>

                        <form method="post" action="">
                            <?php wp_nonce_field( 'cms_save_smtp_nonce' ); ?>

                            <p style="margin-bottom: 20px;">
                                <label style="font-weight: 700; font-size: 15px; cursor: pointer;">
                                    <input type="checkbox" name="cms_smtp_enabled" value="1" <?php checked( $smtp_enabled, 1 ); ?> style="transform: scale(1.3); margin-right: 8px;">
                                    Bật tính năng gửi email qua SMTP
                                </label>
                                <br>
                                <span class="description" style="margin-left: 28px; color: #646970;">Khi bật, toàn bộ email trên website (Bản tin footer & Contact Form 7) sẽ được gửi qua máy chủ SMTP thực tế.</span>
                            </p>

                            <table class="form-table" style="margin-top: 0;">
                                <tr>
                                    <th scope="row"><label for="cms_smtp_host">Máy chủ SMTP (Host)</label></th>
                                    <td>
                                        <input name="cms_smtp_host" type="text" id="cms_smtp_host" value="<?php echo esc_attr( $smtp_host ); ?>" class="regular-text" placeholder="smtp.gmail.com">
                                        <p class="description">Gmail: <code>smtp.gmail.com</code> | Outlook: <code>smtp.office365.com</code></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="cms_smtp_port">Cổng (Port)</label></th>
                                    <td>
                                        <input name="cms_smtp_port" type="number" id="cms_smtp_port" value="<?php echo esc_attr( $smtp_port ); ?>" class="small-text" style="width: 80px;">
                                        <span class="description">Cổng thông dụng: <strong>587</strong> (TLS) hoặc <strong>465</strong> (SSL)</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="cms_smtp_encryption">Mã hóa (Encryption)</label></th>
                                    <td>
                                        <label style="margin-right: 15px;">
                                            <input type="radio" name="cms_smtp_encryption" value="tls" <?php checked( $smtp_encryption, 'tls' ); ?>> TLS (Khuyên dùng)
                                        </label>
                                        <label>
                                            <input type="radio" name="cms_smtp_encryption" value="ssl" <?php checked( $smtp_encryption, 'ssl' ); ?>> SSL
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="cms_smtp_username">Tài khoản SMTP (Email)</label></th>
                                    <td>
                                        <input name="cms_smtp_username" type="email" id="cms_smtp_username" value="<?php echo esc_attr( $smtp_username ); ?>" class="regular-text" placeholder="your_email@gmail.com">
                                        <p class="description">Địa chỉ email bạn dùng để đăng nhập và phát thư.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="cms_smtp_password">Mật khẩu ứng dụng (App Password)</label></th>
                                    <td>
                                        <input name="cms_smtp_password" type="password" id="cms_smtp_password" value="<?php echo esc_attr( $smtp_password ); ?>" class="regular-text" placeholder="16 ký tự mật khẩu ứng dụng Gmail">
                                        <p class="description"><strong>Lưu ý với Gmail:</strong> Sử dụng <em>Mật khẩu ứng dụng 16 ký tự</em> (xem hướng dẫn ở cột bên phải), không dùng mật khẩu đăng nhập tài khoản thường.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="cms_smtp_from_email">Email người gửi (From Email)</label></th>
                                    <td>
                                        <input name="cms_smtp_from_email" type="email" id="cms_smtp_from_email" value="<?php echo esc_attr( $smtp_from_email ); ?>" class="regular-text">
                                        <p class="description">Email hiển thị ở mục người gửi (thường trùng với Tài khoản SMTP).</p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="cms_smtp_from_name">Tên người gửi (From Name)</label></th>
                                    <td>
                                        <input name="cms_smtp_from_name" type="text" id="cms_smtp_from_name" value="<?php echo esc_attr( $smtp_from_name ); ?>" class="regular-text">
                                        <p class="description">Ví dụ: <strong>Plan Do See Global Inc.</strong></p>
                                    </td>
                                </tr>
                            </table>

                            <p class="submit" style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #eee;">
                                <button type="submit" name="cms_save_smtp_settings" class="button button-primary button-large">💾 Lưu Cấu Hình SMTP</button>
                            </p>
                        </form>
                    </div>

                    <!-- Khung Kiểm Tra Gửi Mail Thực Tế -->
                    <div style="background: #fff; padding: 25px; border-radius: 8px; border: 1px solid #ccd0d4; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
                        <h2 style="margin: 0 0 10px; font-size: 18px; color: #1e2022;">
                            🚀 Kiểm Tra Gửi Mail Qua SMTP (Test Email)
                        </h2>
                        <p style="color: #646970; margin-bottom: 18px;">Nhập một địa chỉ email bất kỳ của bạn để hệ thống gửi thư thử nghiệm và xác nhận kết nối SMTP.</p>

                        <form method="post" action="">
                            <?php wp_nonce_field( 'cms_test_smtp_nonce' ); ?>
                            <div style="display: flex; gap: 12px; align-items: center; max-width: 600px;">
                                <input type="email" name="test_email" class="regular-text" placeholder="Nhập email nhận thử nghiệm..." required value="<?php echo esc_attr( get_option( 'admin_email' ) ); ?>" style="flex: 1; height: 38px;">
                                <button type="submit" name="cms_test_smtp_mail" class="button button-secondary button-large" style="height: 38px;">Gửi Thử Ngay</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Cột phải: Hướng dẫn lấy Mật khẩu ứng dụng Gmail -->
                <div>
                    <div style="background: #FFF9F5; border: 1px solid #FFE0CC; border-radius: 8px; padding: 22px; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
                        <h3 style="margin: 0 0 12px; color: #eb6324; font-size: 16px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                            💡 Hướng dẫn cấu hình Gmail SMTP
                        </h3>
                        <p style="font-size: 13px; line-height: 1.6; color: #4b5563; margin-bottom: 12px;">
                            Để gửi email từ Gmail mà không bị Google chặn bảo mật, bạn chỉ cần tạo <strong>Mật khẩu ứng dụng (App Password)</strong>:
                        </p>
                        <ol style="font-size: 13px; line-height: 1.7; color: #4b5563; padding-left: 20px; margin-bottom: 15px;">
                            <li>Truy cập <a href="https://myaccount.google.com/security" target="_blank" rel="noopener noreferrer" style="color: #eb6324; font-weight: 600;">Tài khoản Google > Bảo mật</a>.</li>
                            <li>Bật tính năng <strong>Xác minh 2 bước</strong> (nếu chưa bật).</li>
                            <li>Tìm kiếm mục <strong>"Mật khẩu ứng dụng"</strong> (App Passwords) hoặc truy cập trực tiếp <a href="https://myaccount.google.com/apppasswords" target="_blank" rel="noopener noreferrer" style="color: #eb6324; font-weight: 600;">tại đây</a>.</li>
                            <li>Nhập tên ứng dụng là <code>WordPress</code> rồi nhấn <strong>Tạo</strong>.</li>
                            <li>Google sẽ cấp 1 chuỗi <strong>16 ký tự</strong> (ví dụ: <code>abcd efgh ijkl mnop</code>).</li>
                            <li>Sao chép chuỗi đó dán vào ô <strong>Mật khẩu ứng dụng</strong> bên trái và nhấn <strong>Lưu Cấu Hình</strong>.</li>
                        </ol>
                        <div style="background: #fff; padding: 10px 14px; border-radius: 6px; border: 1px dashed #eb6324; font-size: 12px; color: #1e2022;">
                            <strong>Thông số chuẩn Gmail:</strong><br>
                            &bull; Máy chủ: <code>smtp.gmail.com</code><br>
                            &bull; Cổng: <code>587</code> &bull; Mã hóa: <code>TLS</code>
                        </div>
                    </div>
                </div>
            </div>

        <?php elseif ( 'subscribers' === $active_tab ) : ?>
            <!-- TAB 2: DANH SÁCH SUBSCRIBERS -->
            <div style="background: #fff; padding: 25px; border-radius: 8px; border: 1px solid #ccd0d4; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
                    <h2 style="margin: 0; font-size: 18px;">Danh Sách Người Đăng Ký Nhận Bản Tin (<?php echo count( $subscribers ); ?>)</h2>
                </div>
                <?php if ( empty( $subscribers ) ) : ?>
                    <p style="color: #646970;">Chưa có lượt đăng ký nào. Hãy nhập email tại thanh Footer ở trang chủ!</p>
                <?php else : ?>
                    <table class="widefat striped" style="border: 1px solid #c3c4c7;">
                        <thead>
                            <tr>
                                <th style="width: 50px;">STT</th>
                                <th>Địa Chỉ Email</th>
                                <th>Ngày Đăng Ký</th>
                                <th>Địa Chỉ IP</th>
                                <th style="width: 100px;">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $i = 1;
                            foreach ( array_reverse( $subscribers ) as $sub ) : 
                                $delete_url = wp_nonce_url( admin_url( 'themes.php?page=cms-newsletter-subscribers&tab=subscribers&action=delete&email=' . urlencode( $sub['email'] ) ), 'cms_delete_subscriber' );
                            ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><strong style="color: #eb6324; font-size: 14px;"><?php echo esc_html( $sub['email'] ); ?></strong></td>
                                <td><?php echo esc_html( ! empty( $sub['date'] ) ? date( 'd/m/Y H:i:s', strtotime( $sub['date'] ) ) : '—' ); ?></td>
                                <td><?php echo esc_html( ! empty( $sub['ip'] ) ? $sub['ip'] : '127.0.0.1' ); ?></td>
                                <td>
                                    <a href="<?php echo esc_url( $delete_url ); ?>" class="button button-small button-link-delete" onclick="return confirm('Bạn có chắc muốn xóa email này khỏi danh sách?');">Xóa</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

        <?php elseif ( 'logs' === $active_tab ) : ?>
            <!-- TAB 3: NHẬT KÝ GỬI THƯ -->
            <div style="background: #fff; padding: 25px; border-radius: 8px; border: 1px solid #ccd0d4; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
                <h2 style="margin: 0 0 18px; font-size: 18px;">Nhật Ký Gửi Email Gần Nhất (Email Logs)</h2>
                <?php if ( empty( $logs ) ) : ?>
                    <p style="color: #646970;">Chưa có nhật ký gửi email nào.</p>
                <?php else : ?>
                    <table class="widefat striped" style="border: 1px solid #c3c4c7;">
                        <thead>
                            <tr>
                                <th style="width: 160px;">Thời Gian</th>
                                <th>Người Nhận (To)</th>
                                <th>Tiêu Đề Email</th>
                                <th style="width: 220px;">Trạng Thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ( $logs as $log ) : ?>
                            <tr>
                                <td><?php echo esc_html( $log['time'] ); ?></td>
                                <td><strong><?php echo esc_html( $log['to'] ); ?></strong></td>
                                <td><?php echo esc_html( $log['subject'] ); ?></td>
                                <td>
                                    <span style="display: inline-block; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600; <?php echo ( false !== strpos( $log['status'], 'Thành công' ) || false !== strpos( $log['status'], 'SMTP' ) ) ? 'background: #e7f5ea; color: #135e26;' : 'background: #f0f0f1; color: #3c434a;'; ?>">
                                        <?php echo esc_html( $log['status'] ); ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php
}
