<?php
/**
 * Job Posting Section (Vietnamese)
 * 100% matched to User Request & 4-all job 2.png
 * 
 * @package JobScout
 */

$job_title         = get_theme_mod( 'job_posting_section_title', __( 'Tin Tuyển Dụng Mới Nhất', 'jobscout' ) );
$ed_jobposting     = get_theme_mod( 'ed_jobposting', true );
$count_posts       = wp_count_posts('job_listing'); 
if ( $ed_jobposting && jobscout_is_wp_job_manager_activated() && $job_title  ) {
    ?>
    <section id="job-posting-section" class="top-job-section career-jobs-section">
        <div class="container">

            <!-- ALL JOBS / TIN TUYỂN DỤNG HEADER BAR (CÙNG HÀNG VỚI DROPDOWN LỌC) -->
            <div class="all-jobs-top-bar">
                <div class="all-jobs-left">
                    <h2 class="all-jobs-heading"><?php echo esc_html( $job_title ); ?></h2>
                </div>
                <div class="all-jobs-filters-right">
                    <!-- Dropdown Tất cả ngành nghề + Nút kính lúp màu xanh (Khớp 100% ảnh 3 người dùng gửi) -->
                    <form method="GET" action="<?php echo esc_url( home_url( '/jobs/' ) ); ?>" class="user-category-filter-form">
                        <div class="user-category-filter-box">
                            <select id="home-category-select" class="user-category-select" name="search_category">
                                <option value="">Tất cả ngành nghề</option>
                                <?php
                                $categories = get_terms( array( 'taxonomy' => 'job_listing_category', 'hide_empty' => false ) );
                                if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
                                    foreach ( $categories as $cat ) {
                                        $selected = ( isset( $_GET['search_category'] ) && $_GET['search_category'] === $cat->slug ) ? 'selected' : '';
                                        echo '<option value="' . esc_attr( $cat->slug ) . '" ' . $selected . '>' . esc_html( $cat->name ) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                            <button type="submit" id="home-category-btn" class="user-category-btn" title="Tìm kiếm">
                                <svg width="15" height="15" viewBox="0 0 512 512" fill="#ffffff"><path d="M508.5 468.9L387.1 347.5c-2.3-2.3-5.3-3.5-8.5-3.5h-13.2c31.5-36.5 50.6-84 50.6-136C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c52 0 99.5-19.1 136-50.6v13.2c0 3.2 1.3 6.2 3.5 8.5l121.4 121.4c4.7 4.7 12.3 4.7 17 0l22.6-22.6c4.7-4.7 4.7-12.3 0-17zM208 368c-88.4 0-160-71.6-160-160S119.6 48 208 48s160 71.6 160 160-71.6 160-160 160z"/></svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <?php if( jobscout_is_wp_job_manager_activated() && $count_posts->publish != 0 ){ ?>
                <!-- 2-COLUMN JOB GRID (MATCHING 4-all job 2.png) -->
                <div class="job-listings-archive-wrapper career-jobs-wrapper">
                    <?php echo do_shortcode('[jobs show_filters="false" show_pagination="false" post_status="publish" per_page="12"]'); ?>
                </div>

                <!-- LOAD MORE JOBS BUTTON -->
                <div class="career-load-more-wrap">
                    <a href="#" class="career-load-more-btn" id="home-load-more-trigger">LOAD MORE JOBS</a>
                </div>
            <?php } ?>

        </div>
    </section>
    <?php
}
