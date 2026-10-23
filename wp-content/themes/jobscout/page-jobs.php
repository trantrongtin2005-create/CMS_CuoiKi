<?php
/**
 * Template Name: Danh sách việc làm (Jobs Listing)
 *
 * Giao diện hiển thị trang Danh sách việc làm theo thiết kế chuẩn.
 *
 * @package JobScout
 */

get_header(); ?>

    <!-- HERO BANNER: CƠ HỘI NGHỀ NGHIỆP -->
    <div class="career-hero-banner">
        <div class="career-hero-overlay"></div>
        <div class="career-hero-content">
            <h1 class="career-hero-title">CƠ HỘI NGHỀ NGHIỆP</h1>
        </div>
    </div>

    <div id="primary" class="content-area career-content-area">
        <main id="main" class="site-main">
            <div class="container">

                <!-- ALL JOBS HEADER & FILTERS -->
                <div class="all-jobs-top-bar">
                    <div class="all-jobs-left">
                        <h2 class="all-jobs-heading">TẤT CẢ VIỆC LÀM</h2>
                    </div>
                    <div class="all-jobs-filters-right">
                        <!-- Dropdown Tất cả ngành nghề + Nút kính lúp màu xanh -->
                        <div class="user-category-filter-box">
                            <select id="career-category-select" class="user-category-select" name="search_category">
                                <option value="">Tất cả ngành nghề</option>
                                <?php
                                $categories = get_terms( array( 'taxonomy' => 'job_listing_category', 'hide_empty' => false ) );
                                $selected_cat = isset( $_GET['search_category'] ) ? sanitize_text_field( wp_unslash( $_GET['search_category'] ) ) : '';
                                if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
                                    foreach ( $categories as $cat ) {
                                        $selected = ( $selected_cat === $cat->slug ) ? 'selected' : '';
                                        echo '<option value="' . esc_attr( $cat->slug ) . '" ' . $selected . '>' . esc_html( $cat->name ) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                            <button type="button" id="career-category-btn" class="user-category-btn" title="Tìm kiếm theo ngành nghề">
                                <svg width="15" height="15" viewBox="0 0 512 512" fill="#ffffff"><path d="M508.5 468.9L387.1 347.5c-2.3-2.3-5.3-3.5-8.5-3.5h-13.2c31.5-36.5 50.6-84 50.6-136C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c52 0 99.5-19.1 136-50.6v13.2c0 3.2 1.3 6.2 3.5 8.5l121.4 121.4c4.7 4.7 12.3 4.7 17 0l22.6-22.6c4.7-4.7 4.7-12.3 0-17zM208 368c-88.4 0-160-71.6-160-160S119.6 48 208 48s160 71.6 160 160-71.6 160-160 160z"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 2-COLUMN JOB GRID -->
                <div class="job-listings-archive-wrapper career-jobs-wrapper">
                    <?php
                    $search_keywords = isset( $_GET['search_keywords'] ) ? sanitize_text_field( wp_unslash( $_GET['search_keywords'] ) ) : '';
                    $search_location = isset( $_GET['search_location'] ) ? sanitize_text_field( wp_unslash( $_GET['search_location'] ) ) : '';
                    
                    $shortcode_parts = array(
                        'show_filters="false"',
                        'show_pagination="false"',
                        'per_page="12"',
                    );
                    if ( ! empty( $selected_cat ) ) {
                        $shortcode_parts[] = 'categories="' . esc_attr( $selected_cat ) . '"';
                    }
                    if ( ! empty( $search_keywords ) ) {
                        $shortcode_parts[] = 'keywords="' . esc_attr( $search_keywords ) . '"';
                    }
                    if ( ! empty( $search_location ) ) {
                        $shortcode_parts[] = 'location="' . esc_attr( $search_location ) . '"';
                    }
                    echo do_shortcode( '[jobs ' . implode( ' ', $shortcode_parts ) . ']' );
                    ?>
                </div>

                <!-- NÚT XEM THÊM VIỆC LÀM -->
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

        </main><!-- #main -->
    </div><!-- #primary -->

<?php
get_footer();
