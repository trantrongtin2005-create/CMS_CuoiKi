<?php
/**
 * Apply using link to website (Vietnamese).
 *
 * @package JobScout
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<p class="job-application-prompt">
	<i class="fas fa-external-link-alt"></i> 
	<?php esc_html_e( 'Để ứng tuyển công việc này, vui lòng truy cập đường dẫn tuyển dụng chính thức tại:', 'jobscout' ); ?> 
	<a href="<?php echo esc_url( $apply->url ); ?>" target="_blank" rel="nofollow noopener"><?php echo esc_html( wp_parse_url( $apply->url, PHP_URL_HOST ) ); ?></a>.
</p>
