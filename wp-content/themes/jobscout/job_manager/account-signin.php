<?php
/**
 * In job listing creation flow, this template shows above the job creation form (Vietnamese).
 *
 * @package JobScout
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<?php if ( is_user_logged_in() ) : ?>

	<fieldset class="fieldset-logged_in" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 18px 22px; margin-bottom: 25px;">
		<label style="font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block; font-size: 14.5px;"><?php esc_html_e( 'Tài khoản của bạn', 'jobscout' ); ?></label>
		<div class="field account-sign-in" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
			<?php
				$user = wp_get_current_user();
				printf( wp_kses_post( __( '<span style="color: #475569; font-size: 14px;">Bạn đang đăng nhập với tư cách: <strong style="color: #0f172a;">%s</strong></span>', 'jobscout' ) ), esc_html( $user->user_login ) );
			?>

			<a class="button" href="<?php echo esc_url( apply_filters( 'submit_job_form_logout_url', wp_logout_url( get_permalink() ) ) ); ?>" style="font-size: 13px !important; padding: 6px 14px !important; background: #fee2e2 !important; color: #dc2626 !important; border: 1px solid #fecaca !important; border-radius: 4px !important; text-decoration: none !important; font-weight: 600 !important;"><?php esc_html_e( 'Đăng xuất', 'jobscout' ); ?></a>
		</div>
	</fieldset>

<?php else :
	$account_required            = job_manager_user_requires_account();
	$registration_enabled        = job_manager_enable_registration();
	$registration_fields         = wpjm_get_registration_fields();
	$use_standard_password_email = wpjm_use_standard_password_setup_email();
	?>
	<fieldset class="fieldset-login_required" style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 18px 22px; margin-bottom: 25px;">
		<label style="font-weight: 700; color: #166534; margin-bottom: 6px; display: block; font-size: 14.5px;"><?php esc_html_e( 'Thông tin tài khoản nhà tuyển dụng', 'jobscout' ); ?></label>
		<div class="field account-sign-in" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
			<span style="font-size: 14px; color: #15803d;">
				<?php esc_html_e( 'Bạn có thể đăng tin ngay hoặc đăng nhập để quản lý tin tuyển dụng dễ dàng hơn.', 'jobscout' ); ?>
			</span>
			<a class="button" href="<?php echo esc_url( apply_filters( 'submit_job_form_login_url', wp_login_url( get_permalink() ) ) ); ?>" style="font-size: 13.5px !important; padding: 8px 18px !important; background: #27c96d !important; color: #ffffff !important; border-radius: 4px !important; text-decoration: none !important; font-weight: 700 !important; border: none !important;"><?php esc_html_e( 'Đăng nhập', 'jobscout' ); ?></a>
		</div>
	</fieldset>
	<?php
	if ( ! empty( $registration_fields ) ) {
		foreach ( $registration_fields as $key => $field ) {
			?>
			<fieldset class="fieldset-<?php echo esc_attr( $key ); ?>">
				<label
					for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field[ 'label' ] ) . wp_kses_post( apply_filters( 'submit_job_form_required_label', $field[ 'required' ] ? ' <span style="color: #ef4444;">*</span>' : ' <small style="color: #94a3b8;">(không bắt buộc)</small>', $field ) ); ?></label>
				<div class="field <?php echo $field[ 'required' ] ? 'required-field draft-required' : ''; ?>">
					<?php get_job_manager_template( 'form-fields/' . $field[ 'type' ] . '-field.php', [ 'key'   => $key, 'field' => $field ] ); ?>
				</div>
			</fieldset>
			<?php
		}
		do_action( 'job_manager_register_form' );
	}
	?>
<?php endif; ?>
