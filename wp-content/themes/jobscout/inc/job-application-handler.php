<?php
/**
 * Module Xử Lý Nộp Đơn Ứng Tuyển Việc Làm (Job Application Handler)
 *
 * Chức năng:
 * 1. Tiếp nhận dữ liệu Form ứng tuyển qua AJAX (Họ tên, Email, Số điện thoại, Lời nhắn, File CV).
 * 2. Xác thực định dạng tệp CV (.pdf, .doc, .docx) và giới hạn dung lượng an toàn (tối đa 10MB).
 * 3. Lưu trữ tệp đính kèm an toàn vào thư mục tải lên của WordPress.
 * 4. Tự động gửi Email thông báo kèm tệp CV tới Nhà tuyển dụng / Quản trị viên bằng wp_mail().
 * 5. Lưu vết thông tin ứng tuyển vào cơ sở dữ liệu để phục vụ đối soát, thống kê.
 *
 * @package JobScout
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Xử lý AJAX nộp đơn ứng tuyển việc làm
 */
function cms_handle_job_application() {
	// 1. Kiểm tra mã bảo mật Nonce
	if ( ! check_ajax_referer( 'jobscout_apply_nonce', 'security', false ) ) {
		wp_send_json_error( array(
			'message' => __( 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang và thử lại!', 'jobscout' ),
		) );
	}

	// 2. Thu thập và làm sạch dữ liệu đầu vào
	$job_id    = isset( $_POST['job_id'] ) ? absint( $_POST['job_id'] ) : 0;
	$fullname  = isset( $_POST['fullname'] ) ? sanitize_text_field( wp_unslash( $_POST['fullname'] ) ) : '';
	$email     = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$phone     = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$message   = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';
	$job_title = $job_id ? get_the_title( $job_id ) : ( isset( $_POST['job_title'] ) ? sanitize_text_field( wp_unslash( $_POST['job_title'] ) ) : __( 'Vị trí tuyển dụng', 'jobscout' ) );
	$company   = $job_id ? get_post_meta( $job_id, '_company_name', true ) : '';

	// 3. Xác thực tính hợp lệ của thông tin
	if ( empty( $fullname ) ) {
		wp_send_json_error( array(
			'message' => __( 'Vui lòng nhập Họ và tên của bạn!', 'jobscout' ),
		) );
	}

	if ( empty( $email ) || ! is_email( $email ) ) {
		wp_send_json_error( array(
			'message' => __( 'Địa chỉ email không hợp lệ. Vui lòng kiểm tra lại!', 'jobscout' ),
		) );
	}

	if ( empty( $phone ) ) {
		wp_send_json_error( array(
			'message' => __( 'Vui lòng nhập Số điện thoại liên hệ!', 'jobscout' ),
		) );
	}

	// 4. Kiểm tra và xác thực tệp CV tải lên
	if ( empty( $_FILES['cv_file'] ) || empty( $_FILES['cv_file']['name'] ) ) {
		wp_send_json_error( array(
			'message' => __( 'Vui lòng đính kèm tập tin CV của bạn (.pdf, .doc, .docx)!', 'jobscout' ),
		) );
	}

	$file_data = $_FILES['cv_file'];
	if ( $file_data['error'] !== UPLOAD_ERR_OK ) {
		wp_send_json_error( array(
			'message' => __( 'Có lỗi xảy ra khi tải tập tin lên máy chủ. Vui lòng thử lại!', 'jobscout' ),
		) );
	}

	// Giới hạn dung lượng tối đa 10MB
	$max_size = 10 * 1024 * 1024; // 10 Megabytes
	if ( $file_data['size'] > $max_size ) {
		wp_send_json_error( array(
			'message' => __( 'Dung lượng tập tin CV vượt quá 10MB. Vui lòng chọn tệp nhỏ hơn!', 'jobscout' ),
		) );
	}

	// Kiểm tra phần mở rộng định dạng file
	$file_ext = strtolower( pathinfo( $file_data['name'], PATHINFO_EXTENSION ) );
	$allowed_exts = array( 'pdf', 'doc', 'docx' );
	if ( ! in_array( $file_ext, $allowed_exts, true ) ) {
		wp_send_json_error( array(
			'message' => __( 'Định dạng tập tin không được hỗ trợ. Chỉ chấp nhận các định dạng: .pdf, .doc, .docx', 'jobscout' ),
		) );
	}

	// 5. Lưu tệp đính kèm vào thư mục upload bảo mật của WordPress
	$upload_dir = wp_upload_dir();
	$target_sub_dir = $upload_dir['basedir'] . '/job-applications';
	$target_sub_url = $upload_dir['baseurl'] . '/job-applications';

	if ( ! file_exists( $target_sub_dir ) ) {
		wp_mkdir_p( $target_sub_dir );
		// Thêm tệp .htaccess để ngăn thực thi script trực tiếp
		$htaccess_file = $target_sub_dir . '/.htaccess';
		if ( ! file_exists( $htaccess_file ) ) {
			@file_put_contents( $htaccess_file, "<Files *.php>\ndeny from all\n</Files>" );
		}
	}

	$safe_filename = sanitize_file_name( pathinfo( $file_data['name'], PATHINFO_FILENAME ) );
	$unique_filename = 'CV_' . ( $job_id ? $job_id . '_' : '' ) . time() . '_' . wp_generate_password( 6, false ) . '.' . $file_ext;
	$destination_path = $target_sub_dir . '/' . $unique_filename;
	$file_url = $target_sub_url . '/' . $unique_filename;

	if ( ! move_uploaded_file( $file_data['tmp_name'], $destination_path ) ) {
		wp_send_json_error( array(
			'message' => __( 'Không thể lưu trữ tệp đính kèm trên máy chủ. Vui lòng thử lại sau!', 'jobscout' ),
		) );
	}

	// 6. Xác định người nhận email (Email nhà tuyển dụng hoặc Admin)
	$admin_email   = get_option( 'admin_email' );
	$job_app_meta  = $job_id ? get_post_meta( $job_id, '_application', true ) : '';
	$recipient_emails = array();

	if ( ! empty( $job_app_meta ) && is_email( $job_app_meta ) ) {
		$recipient_emails[] = $job_app_meta;
	}
	if ( ! empty( $admin_email ) && ! in_array( $admin_email, $recipient_emails, true ) ) {
		$recipient_emails[] = $admin_email;
	}
	if ( empty( $recipient_emails ) ) {
		$recipient_emails[] = $admin_email;
	}

	// 7. Tạo nội dung email thông báo HTML chuyên nghiệp
	$site_name = get_bloginfo( 'name' );
	$site_url  = home_url( '/' );
	$job_url   = $job_id ? get_permalink( $job_id ) : $site_url;

	$email_subject = sprintf( '[JobScout Ứng Tuyển] %s - Vị trí: %s', $fullname, $job_title );

	$email_body  = '<!DOCTYPE html><html><head><meta charset="UTF-8"><style>';
	$email_body .= 'body{font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;color:#333;margin:0;padding:20px;background:#f4f5f7;}';
	$email_body .= '.container{max-width:620px;margin:0 auto;background:#fff;border-radius:6px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.06);}';
	$email_body .= '.header{background:#E87722;color:#fff;padding:26px 30px;text-align:left;}';
	$email_body .= '.header h1{margin:0;font-size:20px;font-weight:700;}';
	$email_body .= '.header p{margin:6px 0 0 0;font-size:14px;opacity:0.9;}';
	$email_body .= '.content{padding:30px;}';
	$email_body .= '.field-group{margin-bottom:18px;}';
	$email_body .= '.label{font-size:12px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;}';
	$email_body .= '.value{font-size:15px;color:#111827;font-weight:600;}';
	$email_body .= '.message-box{background:#f9fafb;border-left:3px solid #E87722;padding:14px 18px;border-radius:0 4px 4px 0;font-size:14px;line-height:1.6;color:#374151;white-space:pre-wrap;}';
	$email_body .= '.attachment-box{background:#eef2ff;border:1px dashed #6366f1;padding:12px 18px;border-radius:4px;margin-top:20px;font-size:13.5px;color:#312e81;}';
	$email_body .= '.footer{background:#f9fafb;border-top:1px solid #e5e7eb;padding:16px 30px;font-size:12px;color:#9ca3af;text-align:center;}';
	$email_body .= '</style></head><body>';
	$email_body .= '<div class="container">';
	$email_body .= '<div class="header">';
	$email_body .= '<h1>Hồ Sơ Ứng Tuyển Mới</h1>';
	$email_body .= '<p>Hệ thống tuyển dụng ' . esc_html( $site_name ) . '</p>';
	$email_body .= '</div>';
	$email_body .= '<div class="content">';

	$email_body .= '<div class="field-group">';
	$email_body .= '<div class="label">Vị trí ứng tuyển:</div>';
	$email_body .= '<div class="value"><a href="' . esc_url( $job_url ) . '" style="color:#E87722;text-decoration:none;">' . esc_html( $job_title ) . '</a>' . ( $company ? ' (' . esc_html( $company ) . ')' : '' ) . '</div>';
	$email_body .= '</div>';

	$email_body .= '<div class="field-group">';
	$email_body .= '<div class="label">Họ và tên ứng viên:</div>';
	$email_body .= '<div class="value">' . esc_html( $fullname ) . '</div>';
	$email_body .= '</div>';

	$email_body .= '<div class="field-group">';
	$email_body .= '<div class="label">Địa chỉ Email:</div>';
	$email_body .= '<div class="value"><a href="mailto:' . esc_attr( $email ) . '" style="color:#2563eb;">' . esc_html( $email ) . '</a></div>';
	$email_body .= '</div>';

	$email_body .= '<div class="field-group">';
	$email_body .= '<div class="label">Số điện thoại liên hệ:</div>';
	$email_body .= '<div class="value"><a href="tel:' . esc_attr( $phone ) . '" style="color:#2563eb;">' . esc_html( $phone ) . '</a></div>';
	$email_body .= '</div>';

	if ( ! empty( $message ) ) {
		$email_body .= '<div class="field-group">';
		$email_body .= '<div class="label">Thư giới thiệu / Lời nhắn:</div>';
		$email_body .= '<div class="message-box">' . nl2br( esc_html( $message ) ) . '</div>';
		$email_body .= '</div>';
	}

	$email_body .= '<div class="attachment-box">';
	$email_body .= '📎 <strong>Tập tin CV:</strong> ' . esc_html( $file_data['name'] ) . ' (đã được đính kèm trực tiếp trong thư này)';
	$email_body .= '</div>';

	$email_body .= '</div>';
	$email_body .= '<div class="footer">';
	$email_body .= 'Thư được gửi tự động từ hệ thống JobScout vào lúc ' . current_time( 'H:i d/m/Y' );
	$email_body .= '</div>';
	$email_body .= '</div>';
	$email_body .= '</body></html>';

	$headers = array(
		'Content-Type: text/html; charset=UTF-8',
		'Reply-To: ' . $fullname . ' <' . $email . '>',
	);
	$attachments = array( $destination_path );

	// 8. Thực hiện gửi email qua wp_mail()
	$mail_sent = false;
	foreach ( $recipient_emails as $recipient ) {
		$res = wp_mail( $recipient, $email_subject, $email_body, $headers, $attachments );
		if ( $res ) {
			$mail_sent = true;
		}
	}

	// 9. Lưu vết thông tin ứng tuyển vào CSDL WordPress
	$application_record = array(
		'id'          => uniqid( 'app_' ),
		'job_id'      => $job_id,
		'job_title'   => $job_title,
		'company'     => $company,
		'fullname'    => $fullname,
		'email'       => $email,
		'phone'       => $phone,
		'message'     => $message,
		'file_name'   => $file_data['name'],
		'file_url'    => $file_url,
		'file_path'   => $destination_path,
		'date'        => current_time( 'mysql' ),
		'mail_status' => $mail_sent ? 'sent' : 'failed_or_local',
		'ip'          => isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( $_SERVER['REMOTE_ADDR'] ) : '127.0.0.1',
	);

	// Lưu vào meta của chính tin tuyển dụng
	if ( $job_id ) {
		$existing_job_apps = get_post_meta( $job_id, '_job_applications_data', true );
		if ( ! is_array( $existing_job_apps ) ) {
			$existing_job_apps = array();
		}
		$existing_job_apps[] = $application_record;
		update_post_meta( $job_id, '_job_applications_data', $existing_job_apps );
	}

	// Lưu vào danh sách tổng hợp hệ thống
	$all_applications = get_option( 'cms_job_applications_list', array() );
	if ( ! is_array( $all_applications ) ) {
		$all_applications = array();
	}
	$all_applications[] = $application_record;
	update_option( 'cms_job_applications_list', $all_applications );

	// 10. Trả về phản hồi JSON thành công
	wp_send_json_success( array(
		'message' => sprintf(
			__( 'Chúc mừng bạn! Hồ sơ ứng tuyển vị trí "%s" đã được gửi thành công đến nhà tuyển dụng. Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất!', 'jobscout' ),
			esc_html( $job_title )
		),
		'candidate' => esc_html( $fullname ),
	) );
}
add_action( 'wp_ajax_cms_submit_job_application', 'cms_handle_job_application' );
add_action( 'wp_ajax_nopriv_cms_submit_job_application', 'cms_handle_job_application' );
