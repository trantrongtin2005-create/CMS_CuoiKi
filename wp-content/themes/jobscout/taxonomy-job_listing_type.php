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

            <!-- NEWSLETTER SECTION -->
            <div class="career-newsletter-bar">
                <div class="container">
                    <div class="newsletter-inner">
                        <div class="newsletter-title">Đăng Ký Nhận<br>Bản Tin Tuyển Dụng</div>
                        <div class="newsletter-form-wrap">
                            <input type="email" placeholder="Nhập địa chỉ email của bạn..." class="newsletter-input" />
                            <button type="button" class="newsletter-submit-btn">ĐĂNG KÝ</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FOOTER LINKS & SOCIAL BAR -->
            <div class="career-footer-bar">
                <div class="container">
                    <nav class="career-footer-nav">
                        <a href="<?php echo esc_url( home_url( '/jobs/' ) ); ?>">VIỆC LÀM</a>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">DOANH NGHIỆP</a>
                        <a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">BÀI VIẾT</a>
                        <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">GIỚI THIỆU</a>
                        <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">LIÊN HỆ</a>
                    </nav>
                    <div class="career-footer-social">
                        <a href="#" class="social-circle fb" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-circle gg" title="Google"><i class="fab fa-google"></i></a>
                        <a href="#" class="social-circle line" title="LINE"><i class="fab fa-line"></i></a>
                        <a href="#" class="social-circle tw" title="Twitter"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
            </div>

        </main>
    </div>

<?php
get_footer();
