<?php
/**
 * The template for displaying search results pages (Vietnamese).
 *
 * Job listings in search results are rendered using the standard Job Card
 * with 60x60 TopCV logo and full metadata, perfectly synchronized.
 *
 * @package JobScout
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title">
					<?php
					printf( esc_html__( 'Kết quả tìm kiếm cho: "%s"', 'jobscout' ), '<span>' . esc_html( get_search_query() ) . '</span>' );
					?>
				</h1>
			</header><!-- .page-header -->

			<div class="jobscout-search-results">
			<?php
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				if ( 'job_listing' === get_post_type() ) {
					get_job_manager_template_part( 'content', 'job_listing' );
				} else {
					get_template_part( 'template-parts/content', get_post_format() );
				}

			endwhile;
			?>
			</div>

			<?php
			the_posts_pagination( array(
				'prev_text' => '← Trước',
				'next_text' => 'Sau →',
			) );

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
