<?php
/**
 * Job dashboard shortcode content if user is not logged in.
 *
 * @package JobScout
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$submit_page_id = get_option( 'job_manager_submit_job_form_page_id' );
$submit_url     = $submit_page_id ? get_permalink( $submit_page_id ) : home_url( '/post-a-job/' );
?>

<div id="job-manager-job-dashboard" class="career-dashboard-wrap">
	<div class="career-dashboard-hero-box" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 36px 30px; text-align: center; box-shadow: 0 4px 16px rgba(0,0,0,0.04); max-width: 680px; margin: 30px auto;">
		<div style="width: 64px; height: 64px; background: #e0f2fe; color: #0284c7; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 18px auto; font-size: 26px;">
			<i class="fas fa-tasks"></i>
		</div>
		<h2 style="font-size: 24px; font-weight: 700; color: #1e293b; margin-bottom: 10px;">Quản Lý Tin Tuyển Dụng (CRUD)</h2>
		<p style="color: #64748b; font-size: 15px; margin-bottom: 24px; line-height: 1.6;">
			Bạn đang ở trang quản lý, chỉnh sửa và xóa tin tuyển dụng. Để quản lý các tin tuyển dụng của mình, vui lòng đăng nhập vào tài khoản.
		</p>
		
		<div style="display: flex; gap: 14px; justify-content: center; flex-wrap: wrap;">
			<a href="<?php echo esc_url( wp_login_url( get_permalink() ) ); ?>" class="button" style="background: #2563eb; color: #ffffff; padding: 12px 28px; border-radius: 6px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
				<i class="fas fa-sign-in-alt"></i> Đăng Nhập Quản Trị
			</a>
			<a href="<?php echo esc_url( $submit_url ); ?>" class="button" style="background: #27c96d; color: #ffffff; padding: 12px 28px; border-radius: 6px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
				<i class="fas fa-plus-circle"></i> + Đăng Tin Mới (Không Cần Đăng Nhập)
			</a>
		</div>

		<div style="margin-top: 24px; padding-top: 20px; border-top: 1px solid #f1f5f9; font-size: 13.5px; color: #64748b;">
			<span style="font-weight: 600; color: #334155;">Tài khoản Demo Admin:</span> <code>TrongTin</code> | Mật khẩu: <code>admin</code> (hoặc tài khoản quản trị của bạn)
		</div>
	</div>
</div>
