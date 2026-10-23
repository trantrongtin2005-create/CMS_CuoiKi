<?php
/**
 * Giao diện hiển thị trang Lưu trữ theo Hình thức làm việc (Job Type)
 *
 * @package JobScout
 */

get_header(); 
$current_term = get_queried_object();
$type_name    = ( $current_term && ! is_wp_error( $current_term ) ) ? $current_term->name : 'Hình Thức Làm Việc';
?>

    <!-- ẢNH BÌA BANNER CHUYÊN MỤC -->
    <div class="career-hero-banner">
        <div class="career-hero-overlay"></div>
        <div class="career-hero-content">
            <h1 class="career-hero-title"><?php echo esc_html( mb_strtoupper( $type_name, 'UTF-8' ) ); ?></h1>
        </div>
    </div>

    <div id="primary" class="content-area career-content-area">
        <main id="main" class="site-main">
            <div class="container">

                <div class="all-jobs-top-bar">
                    <div class="all-jobs-left">
                        <h2 class="all-jobs-heading"><?php echo esc_html( $type_name ); ?></h2>
                    </div>
                </div>

                <div class="job-listings-archive-wrapper career-jobs-wrapper">
                    <?php 
                    if ( $current_term && ! is_wp_error( $current_term ) ) {
                        echo do_shortcode( '[jobs job_types="' . esc_attr( $current_term->slug ) . '" show_filters="false" show_pagination="false" per_page="12"]' );
                    }
                    ?>
                </div>

                <div class="career-load-more-wrap">
                    <a href="#" class="career-load-more-btn" id="career-load-more-trigger">XEM THÊM VIỆC LÀM</a>
                </div>

            </div><!-- .container -->



        </main>
    </div>

<?php
get_footer();
