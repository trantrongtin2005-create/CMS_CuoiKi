<?php
/**
 * The template for displaying the News / Blog Posts Archive page.
 * Thiết kế chuẩn theo giao diện PDS NEWS:
 * - Banner Hero TOÀN CẢNH TRÀN VIỀN 100% màn hình với hình ảnh Tokyo hoàng hôn & tiêu đề "TIN TỨC & BÀI VIẾT".
 * - Tiêu đề phần nội dung: "BÀI VIẾT MỚI NHẤT".
 * - Lưới 2 cột các thẻ bài viết nằm ngang (Horizontal Card): Ảnh bên trái, tiêu đề, tóm tắt và link "Xem chi tiết" màu cam bên phải.
 * - Hỗ trợ nút "Sửa bài" và "Viết bài mới" trực quan cho Quản trị viên (sửa bài không cần code).
 * - Phân trang số trang chuẩn WordPress.
 * - Footer tích hợp sẵn dải đăng ký nhận bản tin (Newsletter) màu cam và chân trang đầy đủ.
 *
 * @package JobScout
 */

get_header();

$hero_title     = get_theme_mod( 'pds_news_hero_title', 'TIN TỨC & BÀI VIẾT' );
$hero_subtitle  = get_theme_mod( 'pds_news_hero_subtitle', 'Cập nhật tin tức thị trường, cẩm nang tuyển dụng & kiến thức nghề nghiệp mới nhất' );
$section_title  = get_theme_mod( 'pds_news_section_title', 'BÀI VIẾT MỚI NHẤT' );
$section_desc   = get_theme_mod( 'pds_news_section_desc', 'Khám phá các bài viết hữu ích về ngành dịch vụ, nhà hàng - khách sạn và định hướng nghề nghiệp' );

$current_page_id = (int) get_option( 'page_for_posts' );
$banner_bg       = get_theme_mod( 'pds_news_hero_image', '' );
if ( empty( $banner_bg ) && $current_page_id && has_post_thumbnail( $current_page_id ) ) {
    $banner_bg = get_the_post_thumbnail_url( $current_page_id, 'full' );
}
if ( empty( $banner_bg ) ) {
    $banner_bg = home_url( '/wp-content/uploads/2026/10/hinhnen-2.png' );
}
if ( function_exists( 'jobscout_normalize_media_host' ) ) {
    $banner_bg = jobscout_normalize_media_host( $banner_bg );
}
$banner_bg = add_query_arg( 'v', '20261028', $banner_bg );

$show_banner    = get_theme_mod( 'pds_news_show_banner', true );
$read_more_text = get_theme_mod( 'pds_news_read_more_text', 'Xem chi tiết' );
$hero_height    = get_theme_mod( 'pds_news_hero_height', '520px' );
?>

<!-- ========================================================
     CSS CHO TRANG TIN TỨC & BÀI VIẾT (PDS NEWS THEME)
     ======================================================== -->
<style id="pds-news-page-styles">
  /* ----------------------------------------------------
     PHÁ VỠ GIỚI HẠN CONTAINER ĐỂ HÌNH BANNER TRÀN VIỀN 100%
     ---------------------------------------------------- */
  body.blog .site-header,
  body.blog header.site-header {
    margin-bottom: 0 !important;
  }

  body.blog .breadcrumb-wrap,
  body.blog .breadcrumbs,
  body.blog header.page-header,
  body.blog .page-header,
  body.blog #secondary {
    display: none !important;
  }

  body.blog #page,
  body.blog #acc-content,
  body.blog #content,
  body.blog .site-content,
  body.blog #content.site-content,
  body.blog #content.site-content > .container,
  body.home #content.site-content,
  body.home #content.site-content > .container {
    max-width: 100% !important;
    width: 100% !important;
    padding-left: 0 !important;
    padding-right: 0 !important;
    padding-top: 0 !important;
    margin-top: 0 !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
  }

  body.blog #primary,
  body.blog #main,
  body.blog .site-main {
    width: 100% !important;
    max-width: 100% !important;
    float: none !important;
    padding: 0 !important;
    margin: 0 !important;
  }

  /* Font & Reset chung */
  .pds-news-page-wrap {
    font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
    background-color: #FAFAFA !important;
    width: 100% !important;
    margin: 0 !important;
    padding: 0 0 70px 0 !important;
    box-sizing: border-box !important;
    overflow-x: hidden !important;
  }

  .pds-news-page-wrap * {
    box-sizing: border-box !important;
  }

  /* ----------------------------------------------------
     1. HERO BANNER TRÀN VIỀN TOÀN MÀN HÌNH (100VW EDGE-TO-EDGE)
     ---------------------------------------------------- */
  .pds-news-hero {
    position: relative !important;
    width: 100vw !important;
    left: 50% !important;
    right: 50% !important;
    margin-left: -50vw !important;
    margin-right: -50vw !important;
    margin-top: 0 !important;
    height: <?php echo esc_attr( $hero_height ); ?> !important;
    min-height: 480px !important;
    background-image: linear-gradient(rgba(15, 23, 42, 0.40), rgba(15, 23, 42, 0.52)), url('<?php echo esc_url( $banner_bg ); ?>') !important;
    background-size: cover !important;
    background-position: center 36% !important;
    background-repeat: no-repeat !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    text-align: center !important;
    padding: 30px 20px !important;
    box-shadow: inset 0 -10px 25px rgba(0, 0, 0, 0.2) !important;
  }

  .pds-news-hero-content {
    max-width: 920px !important;
    margin: 0 auto !important;
    position: relative !important;
    z-index: 2 !important;
  }

  .pds-news-hero-title {
    font-family: 'Montserrat', sans-serif !important;
    color: #FFFFFF !important;
    font-size: 38px !important;
    font-weight: 800 !important;
    letter-spacing: 2px !important;
    text-transform: uppercase !important;
    margin: 0 0 12px 0 !important;
    line-height: 1.2 !important;
    text-shadow: 0 2px 14px rgba(0, 0, 0, 0.6) !important;
  }

  .pds-news-hero-subtitle {
    font-family: 'Montserrat', sans-serif !important;
    color: #F1F5F9 !important;
    font-size: 15px !important;
    font-weight: 500 !important;
    letter-spacing: 0.5px !important;
    margin: 0 !important;
    text-shadow: 0 1px 6px rgba(0, 0, 0, 0.5) !important;
  }

  /* Nút sửa tùy biến banner cho Admin */
  .pds-hero-admin-link {
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    margin-top: 14px !important;
    padding: 6px 14px !important;
    background: rgba(255, 255, 255, 0.2) !important;
    backdrop-filter: blur(4px) !important;
    border: 1px solid rgba(255, 255, 255, 0.4) !important;
    border-radius: 20px !important;
    color: #FFFFFF !important;
    font-size: 12px !important;
    font-weight: 600 !important;
    text-decoration: none !important;
    transition: all 0.2s ease !important;
  }

  .pds-hero-admin-link:hover {
    background: rgba(255, 255, 255, 0.35) !important;
    color: #FFFFFF !important;
  }

  /* ----------------------------------------------------
     2. THANH CÔNG CỤ QUẢN TRỊ TRỰC TIẾP (SỬA BÀI KHÔNG CẦN CODE)
     ---------------------------------------------------- */
  .pds-news-admin-toolbar {
    background: #FFFFFF !important;
    border: 1px solid #E2E8F0 !important;
    border-radius: 8px !important;
    padding: 12px 20px !important;
    margin: 24px auto 0 auto !important;
    max-width: 1160px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    flex-wrap: wrap !important;
    gap: 12px !important;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04) !important;
  }

  .pds-admin-info {
    font-size: 13.5px !important;
    color: #334155 !important;
    font-weight: 600 !important;
    display: flex !important;
    align-items: center !important;
    gap: 8px !important;
  }

  .pds-admin-info i {
    color: #E85A24 !important;
  }

  .pds-admin-actions {
    display: flex !important;
    align-items: center !important;
    gap: 10px !important;
  }

  .pds-admin-btn {
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    padding: 7px 16px !important;
    border-radius: 6px !important;
    font-size: 13px !important;
    font-weight: 700 !important;
    text-decoration: none !important;
    transition: all 0.2s ease !important;
  }

  .pds-btn-add {
    background-color: #E85A24 !important;
    color: #FFFFFF !important;
    border: 1px solid #E85A24 !important;
  }

  .pds-btn-add:hover {
    background-color: #c2410c !important;
    color: #FFFFFF !important;
  }

  .pds-btn-manage {
    background-color: #F8FAFC !important;
    color: #334155 !important;
    border: 1px solid #CBD5E1 !important;
  }

  .pds-btn-manage:hover {
    background-color: #E2E8F0 !important;
    color: #0F172A !important;
  }

  /* ----------------------------------------------------
     3. PHẦN BÀI VIẾT MỚI NHẤT (NEWEST BLOG ENTRIES)
     ---------------------------------------------------- */
  .pds-news-container {
    max-width: 1160px !important;
    margin: 0 auto !important;
    padding: 0 24px !important;
  }

  .pds-news-section-heading {
    text-align: center !important;
    margin: 44px 0 34px 0 !important;
  }

  .pds-news-section-title {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 27px !important;
    font-weight: 800 !important;
    color: #1E2022 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.8px !important;
    margin: 0 0 10px 0 !important;
    position: relative !important;
    display: inline-block !important;
  }

  .pds-news-section-desc {
    color: #64748B !important;
    font-size: 14px !important;
    margin: 0 auto !important;
    max-width: 600px !important;
  }

  /* Lưới 2 cột đối xứng chuẩn mẫu thiết kế */
  .pds-news-grid {
    display: grid !important;
    grid-template-columns: repeat(2, 1fr) !important;
    column-gap: 24px !important;
    row-gap: 32px !important;
    margin-bottom: 48px !important;
  }

  /* Thẻ bài viết nằm ngang (Horizontal Card) */
  .pds-news-card {
    background: #FFFFFF !important;
    border: 1px solid #EDEDED !important;
    border-radius: 4px !important;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04) !important;
    display: flex !important;
    flex-direction: row !important;
    align-items: stretch !important;
    overflow: hidden !important;
    position: relative !important;
    transition: all 0.28s cubic-bezier(0.4, 0, 0.2, 1) !important;
  }

  .pds-news-card:hover {
    box-shadow: 0 10px 24px rgba(0, 0, 0, 0.09) !important;
    transform: translateY(-2px) !important;
    border-color: #E2E8F0 !important;
  }

  /* Thông tin tác giả, ngày đăng, chuyên mục đồng bộ từ database */
  .pds-news-card-meta {
    display: flex !important;
    align-items: center !important;
    flex-wrap: wrap !important;
    gap: 6px 12px !important;
    font-size: 12px !important;
    color: #64748B !important;
    margin-bottom: 6px !important;
  }

  .pds-news-card-cat {
    color: #EB6324 !important;
    font-weight: 600 !important;
    background: #FFF7ED !important;
    padding: 2px 7px !important;
    border-radius: 4px !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 4px !important;
  }

  .pds-news-card-date,
  .pds-news-card-author {
    display: inline-flex !important;
    align-items: center !important;
    gap: 4px !important;
    color: #64748B !important;
  }

  /* Khung ảnh bài viết bên trái */
  .pds-news-thumb-link {
    flex: 0 0 170px !important;
    width: 170px !important;
    min-height: 155px !important;
    position: relative !important;
    overflow: hidden !important;
    background-color: #F1F5F9 !important;
    display: block !important;
  }

  .pds-news-thumb-img {
    width: 100% !important;
    height: 100% !important;
    object-fit: cover !important;
    display: block !important;
    transition: transform 0.45s ease !important;
  }

  .pds-news-card:hover .pds-news-thumb-img {
    transform: scale(1.06) !important;
  }

  /* Thân nội dung bài viết bên phải */
  .pds-news-card-body {
    flex: 1 1 auto !important;
    padding: 16px 20px 16px 18px !important;
    display: flex !important;
    flex-direction: column !important;
    justify-content: space-between !important;
    min-width: 0 !important;
    text-align: left !important;
  }

  .pds-news-card-title {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 15.5px !important;
    font-weight: 700 !important;
    line-height: 1.45 !important;
    color: #1E2022 !important;
    margin: 0 0 8px 0 !important;
    display: -webkit-box !important;
    -webkit-line-clamp: 2 !important;
    -webkit-box-orient: vertical !important;
    overflow: hidden !important;
  }

  .pds-news-card-title a {
    color: #1E2022 !important;
    text-decoration: none !important;
    transition: color 0.2s ease !important;
  }

  .pds-news-card-title a:hover {
    color: #E85A24 !important;
  }

  .pds-news-card-excerpt {
    font-family: 'Montserrat', -apple-system, sans-serif !important;
    color: #64748B !important;
    font-size: 13px !important;
    line-height: 1.6 !important;
    margin: 0 0 12px 0 !important;
    display: -webkit-box !important;
    -webkit-line-clamp: 3 !important;
    -webkit-box-orient: vertical !important;
    overflow: hidden !important;
    flex-grow: 1 !important;
  }

  .pds-news-card-footer {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    gap: 8px !important;
  }

  .pds-news-card-link {
    font-family: 'Montserrat', sans-serif !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    color: #E85A24 !important;
    font-size: 13px !important;
    font-weight: 700 !important;
    text-decoration: none !important;
    transition: color 0.2s ease, transform 0.2s ease !important;
    width: fit-content !important;
  }

  .pds-news-card-link:hover {
    color: #c2410c !important;
    transform: translateX(3px) !important;
  }

  /* Nút Sửa bài viết nhanh cho Admin (không cần code) */
  .pds-news-edit-btn {
    display: inline-flex !important;
    align-items: center !important;
    gap: 4px !important;
    font-size: 12px !important;
    font-weight: 600 !important;
    color: #64748B !important;
    background: #F1F5F9 !important;
    padding: 3px 8px !important;
    border-radius: 4px !important;
    text-decoration: none !important;
    transition: all 0.2s ease !important;
  }

  .pds-news-edit-btn:hover {
    background: #E2E8F0 !important;
    color: #0F172A !important;
  }

  /* ----------------------------------------------------
     4. PHÂN TRANG (PAGINATION)
     ---------------------------------------------------- */
  .pds-news-pagination {
    margin-top: 48px !important;
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
    gap: 10px !important;
    list-style: none !important;
    padding: 0 !important;
    border: none !important;
    box-shadow: none !important;
    background: transparent !important;
  }

  .pds-news-pagination ul,
  .pds-news-pagination ol,
  .pds-news-pagination li {
    list-style: none !important;
    list-style-type: none !important;
    margin: 0 !important;
    padding: 0 !important;
    border: none !important;
    display: inline-flex !important;
  }

  .pds-news-pagination li::before,
  .pds-news-pagination li::after,
  .pds-news-pagination ul::before,
  .pds-news-pagination ul::after {
    display: none !important;
    content: none !important;
  }

  .pds-news-pagination .page-numbers {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    min-width: 44px !important;
    height: 44px !important;
    padding: 0 14px !important;
    font-size: 15px !important;
    font-weight: 700 !important;
    color: #334155 !important;
    background: #FFFFFF !important;
    border: 1px solid #CBD5E1 !important;
    border-radius: 8px !important;
    text-decoration: none !important;
    transition: all 0.2s ease !important;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04) !important;
    margin: 0 !important;
    list-style: none !important;
  }

  .pds-news-pagination .page-numbers::before,
  .pds-news-pagination .page-numbers::after {
    display: none !important;
    content: none !important;
  }

  .pds-news-pagination .page-numbers:hover {
    color: #EB6324 !important;
    border-color: #EB6324 !important;
    background-color: #FFF7ED !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 10px rgba(235, 99, 36, 0.15) !important;
  }

  .pds-news-pagination .page-numbers.current {
    background-color: #EB6324 !important;
    border-color: #EB6324 !important;
    color: #FFFFFF !important;
    box-shadow: 0 4px 12px rgba(235, 99, 36, 0.3) !important;
  }

  .pds-news-pagination .page-numbers.dots {
    border: none !important;
    background: transparent !important;
    box-shadow: none !important;
    color: #94A3B8 !important;
    min-width: 28px !important;
  }

  /* ----------------------------------------------------
     5. RESPONSIVE MOBILE & TABLET
     ---------------------------------------------------- */
  @media (max-width: 860px) {
    .pds-news-grid {
      grid-template-columns: 1fr !important;
    }
    .pds-news-hero {
      height: 260px !important;
    }
    .pds-news-hero-title {
      font-size: 28px !important;
    }
  }

  @media (max-width: 520px) {
    .pds-news-card {
      flex-direction: column !important;
    }
    .pds-news-thumb-link {
      flex: 0 0 190px !important;
      width: 100% !important;
      height: 190px !important;
    }
    .pds-news-card-body {
      padding: 16px !important;
    }
    .pds-news-admin-toolbar {
      flex-direction: column !important;
      align-items: stretch !important;
    }
    .pds-admin-actions {
      flex-direction: column !important;
    }
  }
</style>

<div class="pds-news-page-wrap">

  <!-- HERO BANNER TRÀN VIỀN 100% TOÀN CẢNH TOKYO HOÀNG HÔN -->
  <?php if ( $show_banner ) : ?>
  <header class="pds-news-hero" id="pds-news-hero-banner" style="background-image: linear-gradient(rgba(15, 23, 42, 0.40), rgba(15, 23, 42, 0.52)), url('<?php echo esc_url( $banner_bg ); ?>') !important; background-position: center 36% !important; height: <?php echo esc_attr( $hero_height ); ?> !important; min-height: 480px !important;">
    <div class="pds-news-hero-content">
      <h1 class="pds-news-hero-title" id="pds-news-hero-title"><?php echo esc_html( $hero_title ); ?></h1>
      <p class="pds-news-hero-subtitle" id="pds-news-hero-subtitle"><?php echo esc_html( $hero_subtitle ); ?></p>
    </div>
  </header>
  <?php endif; ?>

  <!-- CONTAINER CHỨA NỘI DUNG CHÍNH -->
  <main class="pds-news-container">
    <div class="pds-news-section-heading">
      <h2 class="pds-news-section-title" id="pds-news-section-title"><?php echo esc_html( $section_title ); ?></h2>
      <p class="pds-news-section-desc" id="pds-news-section-desc"><?php echo esc_html( $section_desc ); ?></p>
    </div>

    <?php if ( have_posts() ) : ?>
      <div class="pds-news-grid">
        <?php
        while ( have_posts() ) : the_post();
          $has_thumb = has_post_thumbnail();
          $thumb_url = $has_thumb ? get_the_post_thumbnail_url( get_the_ID(), 'medium_large' ) : get_template_directory_uri() . '/images/tokyo-news-banner.jpg';
          $categories = get_the_category();
          $cat_name   = ! empty( $categories ) ? $categories[0]->name : '';
          $author_name = get_the_author();
          $post_date   = get_the_date();
        ?>
          <article id="post-<?php the_ID(); ?>" <?php post_class( 'pds-news-card' ); ?>>
            <a href="<?php the_permalink(); ?>" class="pds-news-thumb-link" aria-label="<?php the_title_attribute(); ?>">
              <img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php the_title_attribute(); ?>" class="pds-news-thumb-img" loading="lazy">
            </a>

            <div class="pds-news-card-body">
              <div class="pds-news-card-meta">
                <?php if ( ! empty( $cat_name ) ) : ?>
                  <span class="pds-news-card-cat"><i class="fas fa-folder-open"></i> <?php echo esc_html( $cat_name ); ?></span>
                <?php endif; ?>
                <span class="pds-news-card-date"><i class="far fa-calendar-alt"></i> <?php echo esc_html( $post_date ); ?></span>
                <?php if ( ! empty( $author_name ) ) : ?>
                  <span class="pds-news-card-author"><i class="far fa-user"></i> <?php echo esc_html( $author_name ); ?></span>
                <?php endif; ?>
              </div>

              <h3 class="pds-news-card-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
              </h3>

              <div class="pds-news-card-excerpt">
                <?php
                  $excerpt = get_the_excerpt();
                  if ( empty( $excerpt ) ) {
                    $excerpt = wp_strip_all_tags( get_the_content() );
                  }
                  echo esc_html( wp_trim_words( $excerpt, 20, '...' ) );
                ?>
              </div>

              <div class="pds-news-card-footer">
                <a href="<?php the_permalink(); ?>" class="pds-news-card-link">
                  <?php echo esc_html( $read_more_text ); ?> <i class="fas fa-arrow-right"></i>
                </a>
              </div>
            </div>
          </article>
        <?php endwhile; ?>
      </div>

      <!-- PHÂN TRANG BÀI VIẾT -->
      <nav class="pds-news-pagination" aria-label="Phân trang bài viết">
        <?php
          echo paginate_links( array(
            'prev_text' => '<i class="fas fa-chevron-left"></i>',
            'next_text' => '<i class="fas fa-chevron-right"></i>',
            'type'      => 'plain',
          ) );
        ?>
      </nav>

    <?php else : ?>
      <div style="text-align: center; padding: 60px 20px; background: #FFFFFF; border-radius: 8px; border: 1px solid #E2E8F0;">
        <i class="fas fa-newspaper" style="font-size: 48px; color: #94A3B8; margin-bottom: 16px;"></i>
        <h3 style="color: #1E2022; font-size: 20px; font-weight: 700; margin-bottom: 8px;">Chưa có bài viết nào</h3>
        <p style="color: #64748B; font-size: 14px;">Các bài viết mới sẽ sớm được cập nhật trên hệ thống. Vui lòng quay lại sau!</p>
      </div>
    <?php endif; ?>

  </main>
</div>

<?php
get_footer();
