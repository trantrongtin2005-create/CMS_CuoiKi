<?php
/**
 * Apply by email content (Vietnamese).
 *
 * @package JobScout
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<p class="job-application-prompt">
	<i class="fas fa-envelope-open-text"></i> 
	<?php esc_html_e( 'Để ứng tuyển công việc này, vui lòng gửi CV và hồ sơ trực tiếp đến email:', 'jobscout' ); ?> 
	<a class="job_application_email" href="mailto:<?php echo esc_attr( $apply->email ); ?>?subject=<?php echo rawurlencode( wp_specialchars_decode( $apply->subject, ENT_QUOTES ) ); ?>"><?php echo esc_html( $apply->email ); ?></a>
</p>
