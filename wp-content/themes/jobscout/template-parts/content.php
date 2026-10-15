<?php
/**
 * Template part for displaying posts & job listings (Vietnamese).
 *
 * When the item is a job_listing, display the standardized Job Card
 * with 60x60 TopCV logo and full job metadata instead of a giant blog thumbnail.
 *
 * @package JobScout
 */

if ( 'job_listing' === get_post_type() ) {
	get_job_manager_template_part( 'content', 'job_listing' );
	return;
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="https://schema.org/Blog">
	<?php 
		/**
		 * @hooked jobscout_post_thumbnail - 10
		 */
		do_action( 'jobscout_before_post_entry_content' );

		echo '<div class="content-wrap">';
		/**
		 * @hooked jobscout_entry_header  - 10 
		 * @hooked jobscout_entry_content - 15
		 * @hooked jobscout_entry_footer  - 20
		 */
		do_action( 'jobscout_post_entry_content' );
		
		echo '</div>';
	?>
</article><!-- #post-<?php the_ID(); ?> -->
