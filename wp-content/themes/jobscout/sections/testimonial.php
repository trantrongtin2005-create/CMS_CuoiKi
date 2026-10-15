<?php
/**
 * Testimonial Section (Vietnamese)
 * 
 * @package JobScout
 */

$section_title  = get_theme_mod( 'testimonial_section_title', __( 'Đánh Giá Từ Khách Hàng', 'jobscout' ) );
$section_desc   = get_theme_mod( 'testimonial_section_subtitle', __( 'Cảm nhận từ các ứng viên và nhà tuyển dụng đồng hành cùng chúng tôi.', 'jobscout' ) );
$ed_testimonial =  get_theme_mod( 'ed_hometestimonial', true );
if( $ed_testimonial && ( $section_title || $section_desc ||  is_active_sidebar( 'testimonial' ) ) ){ ?>
	<section id="testimonial-section" class="testimonial-section">
		<?php if( $section_title || $section_desc ){ ?>
			<div class="container">
				<?php 
					if( $section_title ) echo '<h2 class="section-title">'. esc_html( $section_title ) .'</h2>';
					if( $section_desc ) echo '<div class="section-desc">'. wpautop( wp_kses_post( $section_desc ) ) .'</div>';
				?>
			</div>
		<?php } 
		if( is_active_sidebar( 'testimonial' ) ) { ?>
			<div class="widgets-wrap owl-carousel">
		   		<?php dynamic_sidebar( 'testimonial' ); ?>
		   </div><!-- .widgets-wrap -->
		<?php } ?>

	</section> <!-- .testimonial-section -->
	<?php
}
