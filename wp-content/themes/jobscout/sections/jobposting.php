<?php
/**
 * Job Posting Section (TOP JOBS MODULE)
 * Chuẩn thiết kế 100% theo mẫu:
 * - Tiêu đề căn giữa: "TOP JOBS"
 * - Lưới 2 cột x 3 hàng = 6 thẻ việc làm (Cards)
 * - Mỗi thẻ gồm: Logo công ty đóng khung vuông bên trái, Tiêu đề việc làm, Ngày tạo (Created: Oct 28, 2022),
 *   3 Tag phân loại màu xám nhạt (Fulltime | Category Name | Ho Chi Minh City),
 *   3 gạch đầu dòng mô tả quyền lợi/nhiệm vụ.
 * - Nút "VIEW MORE JOBS" viền cam ở chân trang.
 * 
 * @package JobScout
 */

$ed_jobposting   = get_theme_mod( 'ed_jobposting_section', true );
$job_title       = get_theme_mod( 'job_posting_section_title', 'VIỆC LÀM HÀNG ĐẦU' );
$view_more_text  = get_theme_mod( 'top_jobs_btn_text', 'XEM THÊM VIỆC LÀM' );
$view_more_url   = get_theme_mod( 'top_jobs_btn_url', home_url( '/jobs/' ) );

if ( $ed_jobposting ) {
?>

<!-- ========================================================
     CSS CHO MODULE TOP JOBS (CHUẨN 100% THEO SCREENSHOT)
     ======================================================== -->
<style id="top-jobs-module-styles">
  @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap');

  #job-posting-section.top-jobs-module-section {
    width: 100% !important;
    background-color: #F4F5F8 !important;
    padding: 65px 0 75px !important;
    font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
    box-sizing: border-box !important;
  }

  .top-jobs-module-section * {
    box-sizing: border-box !important;
  }

  .top-jobs-module-section .top-jobs-container {
    max-width: 1180px !important;
    margin: 0 auto !important;
    padding: 0 24px !important;
  }

  /* Tiêu đề mục: TOP JOBS căn giữa */
  .top-jobs-module-section .top-jobs-heading-wrap {
    text-align: center !important;
    margin: 0 0 42px 0 !important;
  }

  .top-jobs-module-section .top-jobs-heading {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 30px !important;
    font-weight: 800 !important;
    color: #1E2022 !important;
    text-transform: uppercase !important;
    text-align: center !important;
    letter-spacing: 0.6px !important;
    margin: 0 !important;
    line-height: 1.2 !important;
    display: inline-block !important;
    position: relative !important;
  }

  /* Customizer shortcut pencil buttons */
  .top-jobs-module-section .customize-partial-edit-shortcut {
    z-index: 99999 !important;
  }
  .top-jobs-module-section .customize-partial-edit-shortcut button {
    z-index: 99999 !important;
    pointer-events: auto !important;
  }
  .top-jobs-module-section .top-jobs-heading .customize-partial-edit-shortcut button {
    left: -38px !important;
    top: 0 !important;
  }
  .top-jobs-module-section .top-jobs-btn-more {
    position: relative !important;
  }
  .top-jobs-module-section .top-jobs-btn-more .customize-partial-edit-shortcut button {
    left: auto !important;
    right: -12px !important;
    top: -12px !important;
  }

  /* Lưới 2 cột cho các thẻ việc làm */
  .top-jobs-module-section .top-jobs-grid {
    display: grid !important;
    grid-template-columns: repeat(2, 1fr) !important;
    gap: 24px !important;
    width: 100% !important;
  }

  /* Thẻ việc làm (Card) */
  .top-jobs-module-section .top-job-card {
    background: #FFFFFF !important;
    border-radius: 4px !important;
    padding: 22px 24px !important;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04) !important;
    border: 1px solid #EAEAEA !important;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
    display: flex !important;
    flex-direction: column !important;
    justify-content: space-between !important;
  }

  .top-jobs-module-section .top-job-card:hover {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.09) !important;
    border-color: #D6D8DC !important;
    transform: translateY(-2px) !important;
  }

  /* Phần đầu thẻ: Logo bên trái + Tiêu đề & Tag bên phải */
  .top-jobs-module-section .top-job-card-header {
    display: flex !important;
    align-items: flex-start !important;
    gap: 16px !important;
    width: 100% !important;
  }

  /* Khung vuông chứa Logo */
  .top-jobs-module-section .top-job-logo-box {
    width: 82px !important;
    height: 82px !important;
    flex-shrink: 0 !important;
    border: 1px solid #E2E8F0 !important;
    border-radius: 3px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    background: #FFFFFF !important;
    padding: 6px !important;
    overflow: hidden !important;
  }

  .top-jobs-module-section .top-job-logo-img {
    max-width: 100% !important;
    max-height: 100% !important;
    object-fit: contain !important;
    display: block !important;
  }

  .top-jobs-module-section .top-job-info-box {
    flex: 1 1 auto !important;
    min-width: 0 !important;
  }

  /* Tên chức danh việc làm */
  .top-jobs-module-section .top-job-title {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 15px !important;
    font-weight: 700 !important;
    color: #1E2022 !important;
    text-transform: uppercase !important;
    margin: 0 0 4px 0 !important;
    line-height: 1.35 !important;
    letter-spacing: -0.1px !important;
  }

  .top-jobs-module-section .top-job-title a {
    color: #1E2022 !important;
    text-decoration: none !important;
    transition: color 0.2s ease !important;
  }

  .top-jobs-module-section .top-job-title a:hover {
    color: #EB6324 !important;
  }

  /* Ngày tạo: Created: Oct 28, 2022 */
  .top-jobs-module-section .top-job-date {
    display: block !important;
    font-size: 11.5px !important;
    color: #94A3B8 !important;
    margin: 0 0 8px 0 !important;
    font-weight: 400 !important;
  }

  /* Hàng Tag Pills (Fulltime | Category Name | Ho Chi Minh City) */
  .top-jobs-module-section .top-job-badges {
    display: flex !important;
    align-items: center !important;
    gap: 6px !important;
    flex-wrap: wrap !important;
  }

  .top-jobs-module-section .top-job-badge {
    background: #F1F5F9 !important;
    color: #64748B !important;
    font-size: 11px !important;
    font-weight: 500 !important;
    padding: 3px 8px !important;
    border-radius: 3px !important;
    display: inline-block !important;
    line-height: 1.25 !important;
    letter-spacing: 0.1px !important;
    white-space: nowrap !important;
  }

  /* Phần gạch đầu dòng quyền lợi/mô tả ở dưới */
  .top-jobs-module-section .top-job-card-body {
    margin-top: 14px !important;
    padding-top: 14px !important;
    border-top: 1px solid #F3F4F6 !important;
    width: 100% !important;
  }

  .top-jobs-module-section .top-job-bullets {
    list-style: none !important;
    padding: 0 !important;
    margin: 0 !important;
  }

  .top-jobs-module-section .top-job-bullets li {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 12.5px !important;
    color: #64748B !important;
    line-height: 1.55 !important;
    position: relative !important;
    padding-left: 12px !important;
    margin-bottom: 3px !important;
    font-weight: 400 !important;
  }

  .top-jobs-module-section .top-job-bullets li::before {
    content: "•" !important;
    position: absolute !important;
    left: 0 !important;
    color: #94A3B8 !important;
    font-weight: bold !important;
  }

  /* Nút VIEW MORE JOBS ở chân trang */
  .top-jobs-module-section .top-jobs-footer {
    text-align: center !important;
    margin-top: 42px !important;
    width: 100% !important;
  }

  .top-jobs-module-section .top-jobs-btn-more {
    display: inline-block !important;
    background: #FFFFFF !important;
    border: 1px solid #EB6324 !important;
    color: #EB6324 !important;
    font-family: 'Montserrat', sans-serif !important;
    font-size: 13px !important;
    font-weight: 700 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.6px !important;
    padding: 12px 34px !important;
    border-radius: 2px !important;
    text-decoration: none !important;
    transition: all 0.25s ease !important;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04) !important;
  }

  .top-jobs-module-section .top-jobs-btn-more:hover {
    background: #EB6324 !important;
    color: #FFFFFF !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 6px 16px rgba(235, 99, 36, 0.25) !important;
  }

  /* Responsive: Màn hình nhỏ co về 1 cột */
  @media (max-width: 860px) {
    .top-jobs-module-section .top-jobs-grid {
      grid-template-columns: 1fr !important;
      gap: 16px !important;
    }
    .top-jobs-module-section {
      padding: 50px 0 60px !important;
    }
    .top-jobs-module-section .top-jobs-heading {
      font-size: 26px !important;
      margin-bottom: 28px !important;
    }
  }

  @media (max-width: 520px) {
    .top-jobs-module-section .top-job-card-header {
      flex-direction: column !important;
      align-items: flex-start !important;
    }
    .top-jobs-module-section .top-job-logo-box {
      width: 70px !important;
      height: 70px !important;
    }
  }
</style>

<!-- ========================================================
     HTML MARKUP CHO MODULE TOP JOBS
     ======================================================== -->
<section id="job-posting-section" class="top-jobs-module-section">
  <div class="top-jobs-container">

    <div class="top-jobs-heading-wrap">
      <h2 class="top-jobs-heading"><?php echo esc_html( $job_title ); ?></h2>
    </div>

    <div class="top-jobs-grid">
      <?php
      $source = get_theme_mod( 'top_jobs_source', 'auto' );
      $query  = null;

      // 1. Chế độ thủ công: Lấy theo các ID việc làm do người dùng chọn trong Customizer
      if ( 'manual' === $source ) {
          $manual_ids = array();
          for ( $i = 1; $i <= 6; $i++ ) {
              $jid = absint( get_theme_mod( "top_job_id_{$i}", 0 ) );
              if ( $jid > 0 && 'publish' === get_post_status( $jid ) ) {
                  $manual_ids[] = $jid;
              }
          }

          if ( ! empty( $manual_ids ) ) {
              $args = array(
                  'post_type'      => 'job_listing',
                  'post__in'       => $manual_ids,
                  'orderby'        => 'post__in',
                  'posts_per_page' => count( $manual_ids ),
                  'post_status'    => array( 'publish' ),
              );
              $query = new WP_Query( $args );
          }
      }

      // 2. Chế độ tự động hoặc khi thủ công chưa chọn đủ bài:
      // Ưu tiên các việc làm đánh dấu Nổi bật (_featured = 1)
      if ( empty( $query ) || ! $query->have_posts() ) {
          $args = array(
              'post_type'      => 'job_listing',
              'posts_per_page' => 6,
              'post_status'    => array( 'publish' ),
              'meta_key'       => '_featured',
              'orderby'        => array(
                  'meta_value_num' => 'DESC',
                  'ID'             => 'ASC',
              ),
          );
          $query = new WP_Query( $args );

          // Fallback nếu không có post nào có _featured
          if ( ! $query->have_posts() ) {
              $args = array(
                  'post_type'      => 'job_listing',
                  'posts_per_page' => 6,
                  'post_status'    => array( 'publish' ),
                  'orderby'        => 'ID',
                  'order'          => 'ASC',
              );
              $query = new WP_Query( $args );
          }
      }

      if ( $query && $query->have_posts() ) :
          while ( $query->have_posts() ) : $query->the_post();
              $post_id   = get_the_ID();
              $logo_url  = get_post_meta( $post_id, '_company_logo', true );
              $location  = get_post_meta( $post_id, '_job_location', true );
              if ( empty( $location ) ) $location = 'Ho Chi Minh City';
              
              // Lấy job type
              $type_name = 'Fulltime';
              if ( taxonomy_exists( 'job_listing_type' ) ) {
                  $types = wp_get_post_terms( $post_id, 'job_listing_type' );
                  if ( ! is_wp_error( $types ) && ! empty( $types ) ) {
                      $type_name = $types[0]->name;
                  }
              }

              // Lấy category
              $cat_name = 'Category Name';
              if ( taxonomy_exists( 'job_listing_category' ) ) {
                  $cats = wp_get_post_terms( $post_id, 'job_listing_category' );
                  if ( ! is_wp_error( $cats ) && ! empty( $cats ) ) {
                      $cat_name = $cats[0]->name;
                  }
              }

              // Nếu không có logo, dùng fallback
              if ( empty( $logo_url ) ) {
                  $logo_url = get_template_directory_uri() . '/images/companies/the-sodoh.svg';
              }

              // Trích xuất 3 gạch đầu dòng quyền lợi linh hoạt
              $bullets = jobscout_get_job_bullets( $post_id );
              ?>
              <div class="top-job-card">
                <div class="top-job-card-header">
                  <div class="top-job-logo-box">
                    <img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php the_title_attribute(); ?>" class="top-job-logo-img" loading="lazy" />
                  </div>
                  <div class="top-job-info-box">
                    <h3 class="top-job-title">
                      <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h3>
                    <span class="top-job-date">Ngày đăng: <?php echo get_the_date( 'd/m/Y' ); ?></span>
                    <div class="top-job-badges">
                      <span class="top-job-badge badge-type"><?php echo esc_html( $type_name ); ?></span>
                      <span class="top-job-badge badge-category"><?php echo esc_html( $cat_name ); ?></span>
                      <span class="top-job-badge badge-location"><?php echo esc_html( $location ); ?></span>
                    </div>
                  </div>
                </div>

                <div class="top-job-card-body">
                  <ul class="top-job-bullets">
                    <?php foreach ( $bullets as $bullet ) : ?>
                      <li><?php echo esc_html( $bullet ); ?></li>
                    <?php endforeach; ?>
                  </ul>
                </div>
              </div>
          <?php
          endwhile;
          wp_reset_postdata();
      else :
          // Dữ liệu mẫu chuẩn 100% nếu chưa có post nào được kích hoạt
          $sample_jobs = get_option( 'jobscout_top_jobs_seed', array() );
          foreach ( $sample_jobs as $job ) :
              ?>
              <div class="top-job-card">
                <div class="top-job-card-header">
                  <div class="top-job-logo-box">
                    <img src="<?php echo esc_url( $job['logo'] ); ?>" alt="<?php echo esc_attr( $job['title'] ); ?>" class="top-job-logo-img" loading="lazy" />
                  </div>
                  <div class="top-job-info-box">
                    <h3 class="top-job-title">
                      <a href="<?php echo esc_url( home_url( '/jobs/' ) ); ?>"><?php echo esc_html( $job['title'] ); ?></a>
                    </h3>
                    <span class="top-job-date">Ngày đăng: 28/10/2022</span>
                    <div class="top-job-badges">
                      <span class="top-job-badge badge-type"><?php echo esc_html( $job['type'] ); ?></span>
                      <span class="top-job-badge badge-category"><?php echo esc_html( $job['category'] ); ?></span>
                      <span class="top-job-badge badge-location"><?php echo esc_html( $job['location'] ); ?></span>
                    </div>
                  </div>
                </div>

                <div class="top-job-card-body">
                  <ul class="top-job-bullets">
                    <?php foreach ( $job['bullets'] as $bullet ) : ?>
                      <li><?php echo esc_html( $bullet ); ?></li>
                    <?php endforeach; ?>
                  </ul>
                </div>
              </div>
          <?php
          endforeach;
      endif;
      ?>
    </div>

    <?php if ( ! empty( $view_more_text ) && ! empty( $view_more_url ) ) : ?>
      <div class="top-jobs-footer">
        <a href="<?php echo esc_url( $view_more_url ); ?>" class="top-jobs-btn-more">
          <?php echo esc_html( $view_more_text ); ?>
        </a>
      </div>
    <?php endif; ?>

  </div>
</section>

<?php
}
