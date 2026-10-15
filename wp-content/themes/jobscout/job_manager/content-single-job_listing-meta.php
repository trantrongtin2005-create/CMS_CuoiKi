<?php
/**
 * Single view job meta box (Vietnamese).
 *
 * Hooked into single_job_listing_start priority 20
 *
 * @package JobScout
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $post;
$job_id      = get_the_ID();
$job_salary  = get_post_meta( $job_id, '_job_salary', true );
$job_expires = get_post_meta( $job_id, '_job_expires', true );
$categories  = get_the_terms( $job_id, 'job_listing_category' );

do_action( 'single_job_listing_meta_before' ); ?>

<ul class="job-listing-meta meta">
	<?php do_action( 'single_job_listing_meta_start' ); ?>

	<?php if ( get_option( 'job_manager_enable_types' ) ) : ?>
		<?php $types = wpjm_get_the_job_types(); ?>
		<?php if ( ! empty( $types ) ) : foreach ( $types as $type ) : ?>
			<li class="job-type <?php echo esc_attr( sanitize_title( $type->slug ) ); ?>">
				<i class="fas fa-briefcase"></i> <?php echo esc_html( $type->name ); ?>
			</li>
		<?php endforeach; endif; ?>
	<?php endif; ?>

	<?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
		<li class="job-category">
			<i class="fas fa-folder"></i>
			<?php 
				$cat_links = array();
				foreach ( $categories as $cat ) {
					$cat_links[] = '<a href="' . esc_url( get_term_link( $cat ) ) . '">' . esc_html( $cat->name ) . '</a>';
				}
				echo implode( ', ', $cat_links );
			?>
		</li>
	<?php endif; ?>

	<li class="location">
		<i class="fas fa-map-marker-alt"></i> <?php the_job_location(); ?>
	</li>

	<?php if ( ! empty( $job_salary ) ) : ?>
		<li class="salary">
			<i class="fas fa-money-bill-wave"></i> <?php echo esc_html( $job_salary ); ?>
		</li>
	<?php endif; ?>

	<?php if ( ! empty( $job_expires ) ) : ?>
		<li class="job-deadline">
			<i class="far fa-calendar-alt"></i> <?php esc_html_e( 'Hạn nộp:', 'jobscout' ); ?> <?php echo esc_html( date_i18n( 'd/m/Y', strtotime( $job_expires ) ) ); ?>
		</li>
	<?php endif; ?>

	<li class="date-posted">
		<i class="far fa-clock"></i> 
		<?php 
			if ( function_exists( 'jobscout_vietnamese_time_diff' ) ) {
				echo esc_html( 'Đã đăng ' . jobscout_vietnamese_time_diff( get_post_time( 'U' ), current_time( 'timestamp' ) ) . ' trước' );
			} else {
				printf( esc_html__( 'Đã đăng %s trước', 'jobscout' ), human_time_diff( get_post_time( 'U' ), current_time( 'timestamp' ) ) ); 
			}
		?>
	</li>

	<?php if ( is_position_filled() ) : ?>
		<li class="position-filled"><i class="fas fa-check-circle"></i> <?php esc_html_e( 'Vị trí này đã tuyển đủ', 'jobscout' ); ?></li>
	<?php elseif ( ! candidates_can_apply() && 'preview' !== $post->post_status ) : ?>
		<li class="listing-expired"><i class="fas fa-times-circle"></i> <?php esc_html_e( 'Đã hết hạn nộp hồ sơ', 'jobscout' ); ?></li>
	<?php endif; ?>

	<?php do_action( 'single_job_listing_meta_end' ); ?>
</ul>

<?php do_action( 'single_job_listing_meta_after' ); ?>
