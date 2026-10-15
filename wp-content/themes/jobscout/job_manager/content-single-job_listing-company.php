<?php
/**
 * Single view Company information box (Vietnamese).
 *
 * Hooked into single_job_listing_start priority 30
 *
 * @package JobScout
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! get_the_company_name() ) {
	return;
}
?>
<div class="company">
	<?php the_company_logo( 'full' ); ?>

	<div class="company_header">
		<strong class="name"><?php the_company_name(); ?></strong>
		<?php the_company_tagline( '<p class="tagline">', '</p>' ); ?>
		<div class="company_links">
			<?php if ( $website = get_the_company_website() ) : ?>
				<a class="website" href="<?php echo esc_url( $website ); ?>" target="_blank" rel="nofollow noopener">
					<i class="fas fa-globe"></i> <?php esc_html_e( 'Website công ty', 'jobscout' ); ?>
				</a>
			<?php endif; ?>
			<?php the_company_twitter(); ?>
		</div>
	</div>

	<?php the_company_video(); ?>
</div>
