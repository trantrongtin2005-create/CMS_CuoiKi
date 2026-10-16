<?php
/**
 * Banner Section (Hero Section: FIND YOUR DREAM JOBS)
 * Chuẩn thiết kế 100% theo mẫu:
 * - Hình nền Núi Phú Sĩ bên trái và Chùa Chureito hoàng hôn bên phải
 * - Tiêu đề "FIND YOUR DREAM JOBS" không bị che khuất
 * - Đoạn mô tả công ty sắc nét, dễ đọc
 * - Khung tìm kiếm 3 ô: Từ khóa (kính lúp), Địa điểm Tokyo (ghim cam + mũi tên v), Nút SEARCH JOB màu cam
 * 
 * @package JobScout
 */

$ed_banner         = get_theme_mod( 'ed_banner_section', true );
$banner_title      = get_theme_mod( 'banner_title', 'FIND YOUR DREAM JOBS' );
$banner_subtitle   = get_theme_mod( 'banner_subtitle', "The secret behind our company is simple: to always put ourselves in the other person's shoes-employee, guest or customer. This allows us to see the world through their eyes, anticipate their needs and better understand their feelings." );
$find_a_job_link   = get_option( 'job_manager_jobs_page_id', 0 );

if( $ed_banner ){ 
    $header_image_url = get_header_image();
    if( ! $header_image_url ){
        $header_image_url = get_template_directory_uri() . '/images/fuji-pagoda-banner.jpg';
    }
?>

<!-- ========================================================
     CSS CHO HERO BANNER: FIND YOUR DREAM JOBS
     ======================================================== -->
<style id="hero-dream-jobs-styles">
  /* Font Montserrat cho Banner */
  @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap');

  .site-banner.hero-dream-banner {
    position: relative !important;
    width: 100% !important;
    min-height: 600px !important;
    background-size: cover !important;
    background-position: center right !important;
    background-repeat: no-repeat !important;
    display: flex !important;
    align-items: center !important;
    overflow: hidden !important;
    font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
    margin: 0 !important;
    padding: 0 !important;
  }

  /* Lớp phủ gradient làm dịu bên trái để nổi bật chữ trắng và khung tìm kiếm */
  .hero-dream-banner .hero-dream-overlay {
    position: absolute !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    bottom: 0 !important;
    background: linear-gradient(90deg, rgba(10, 8, 7, 0.78) 0%, rgba(15, 12, 10, 0.60) 42%, rgba(0, 0, 0, 0.15) 100%) !important;
    z-index: 1 !important;
  }

  /* Reset triệt để positioning từ style.css gốc của JobScout (tránh bị translateY(-50%) làm mất chữ ở trên) */
  .site-banner.hero-dream-banner .banner-caption,
  .hero-dream-banner .hero-dream-caption {
    position: relative !important;
    top: auto !important;
    left: auto !important;
    right: auto !important;
    bottom: auto !important;
    transform: none !important;
    -webkit-transform: none !important;
    z-index: 2 !important;
    width: 100% !important;
    padding: 135px 0 115px !important; /* Khoảng cách an toàn, rộng rãi bên dưới Header */
    margin: 0 !important;
    text-align: left !important;
    box-sizing: border-box !important;
  }

  .hero-dream-banner .hero-dream-container {
    max-width: 1240px !important;
    margin: 0 auto !important;
    padding: 0 32px !important;
    box-sizing: border-box !important;
  }

  .hero-dream-banner .hero-dream-inner {
    text-align: left !important;
    max-width: 820px !important;
    margin-left: 0 !important;
    margin-right: auto !important;
  }

  /* Tiêu đề chính FIND YOUR DREAM JOBS */
  .hero-dream-banner .hero-dream-title {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 38px !important;
    font-weight: 800 !important;
    color: #FFFFFF !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
    line-height: 1.25 !important;
    margin: 0 0 16px 0 !important;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.65) !important;
    display: block !important;
  }

  /* Mô tả 2 dòng dưới tiêu đề */
  .hero-dream-banner .hero-dream-subtitle p {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 14.5px !important;
    font-weight: 400 !important;
    line-height: 1.65 !important;
    color: rgba(255, 255, 255, 0.95) !important;
    margin: 0 0 28px 0 !important;
    max-width: 720px !important;
    text-shadow: 0 1px 6px rgba(0, 0, 0, 0.7) !important;
  }

  /* Khung bọc Form tìm kiếm bán trong suốt (Dark Translucent Bar) */
  .hero-dream-banner .hero-dream-search-box {
    background: rgba(0, 0, 0, 0.45) !important;
    backdrop-filter: blur(6px) !important;
    -webkit-backdrop-filter: blur(6px) !important;
    padding: 12px 14px !important;
    border-radius: 6px !important;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35) !important;
    max-width: 820px !important;
    width: 100% !important;
    box-sizing: border-box !important;
  }

  .hero-dream-banner .hero-dream-search-row {
    display: flex !important;
    align-items: center !important;
    gap: 12px !important;
    width: 100% !important;
  }

  /* Các ô nhập liệu màu trắng */
  .hero-dream-banner .hero-input-group {
    height: 50px !important;
    background: #FFFFFF !important;
    border-radius: 4px !important;
    display: flex !important;
    align-items: center !important;
    padding: 0 14px !important;
    gap: 10px !important;
    box-sizing: border-box !important;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08) !important;
  }

  .hero-dream-banner .hero-input-group-keyword {
    flex: 1.6 !important;
    min-width: 0 !important;
  }

  .hero-dream-banner .hero-input-group-location {
    flex: 0 0 210px !important;
    position: relative !important;
  }

  .hero-dream-banner .hero-icon {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    flex-shrink: 0 !important;
  }

  .hero-dream-banner .hero-input-field {
    border: none !important;
    outline: none !important;
    background: transparent !important;
    font-family: 'Montserrat', sans-serif !important;
    font-size: 14px !important;
    font-weight: 500 !important;
    color: #1E293B !important;
    width: 100% !important;
    height: 100% !important;
    padding: 0 !important;
    margin: 0 !important;
    box-shadow: none !important;
  }

  .hero-dream-banner .hero-input-field::placeholder {
    color: #94A3B8 !important;
    font-weight: 400 !important;
  }

  .hero-dream-banner .hero-input-location {
    cursor: pointer !important;
    padding-right: 24px !important;
    appearance: none !important;
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    background: transparent !important;
  }

  .hero-dream-banner .hero-chevron {
    position: absolute !important;
    right: 14px !important;
    pointer-events: none !important;
    display: flex !important;
    align-items: center !important;
  }

  /* Nút bấm SEARCH JOB màu cam chuẩn thương hiệu */
  .hero-dream-banner .hero-search-submit-wrap {
    flex: 0 0 auto !important;
  }

  .hero-dream-banner .hero-btn-search {
    height: 50px !important;
    padding: 0 32px !important;
    background: #EB6324 !important;
    color: #FFFFFF !important;
    border: none !important;
    border-radius: 4px !important;
    font-family: 'Montserrat', sans-serif !important;
    font-size: 14px !important;
    font-weight: 700 !important;
    letter-spacing: 0.5px !important;
    text-transform: uppercase !important;
    cursor: pointer !important;
    white-space: nowrap !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    transition: all 0.25s ease !important;
    box-shadow: 0 4px 14px rgba(235, 99, 36, 0.4) !important;
  }

  .hero-dream-banner .hero-btn-search:hover {
    background: #D45217 !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 6px 18px rgba(235, 99, 36, 0.5) !important;
  }

  .hero-dream-banner .hero-btn-search:active {
    transform: translateY(0) !important;
  }

  /* Responsive Mobile & Tablet */
  @media (max-width: 900px) {
    .hero-dream-banner {
      min-height: 520px !important;
      background-position: center center !important;
    }
    .hero-dream-banner .hero-dream-title {
      font-size: 30px !important;
    }
  }

  @media (max-width: 768px) {
    .hero-dream-banner {
      min-height: auto !important;
      padding: 60px 0 !important;
    }
    .hero-dream-banner .hero-dream-caption {
      padding: 0 !important;
    }
    .hero-dream-banner .hero-dream-title {
      font-size: 26px !important;
    }
    .hero-dream-banner .hero-dream-search-row {
      flex-direction: column !important;
      gap: 10px !important;
    }
    .hero-dream-banner .hero-input-group,
    .hero-dream-banner .hero-input-group-keyword,
    .hero-dream-banner .hero-input-group-location,
    .hero-dream-banner .hero-search-submit-wrap,
    .hero-dream-banner .hero-btn-search {
      width: 100% !important;
      flex: none !important;
    }
  }
</style>

<!-- ========================================================
     HTML MARKUP CHO HERO BANNER
     ======================================================== -->
<section id="banner-section" class="site-banner hero-dream-banner" style="background-image: url('<?php echo esc_url( $header_image_url ); ?>');">
    <div class="hero-dream-overlay"></div>

    <div class="banner-caption hero-dream-caption">
        <div class="container hero-dream-container">
            <div class="caption-inner hero-dream-inner">
                <?php 
                    if( $banner_title ) {
                        echo '<h1 class="hero-dream-title">' . esc_html( $banner_title ) . '</h1>';
                    }
                    if( $banner_subtitle ) {
                        echo '<div class="hero-dream-subtitle">' . wpautop( wp_kses_post( $banner_subtitle ) ) . '</div>';
                    }
                ?>
                <div class="form-wrap hero-dream-form-wrap">
                    <div class="search-filter-wrap">
                    <?php 
                        if ( jobscout_is_wp_job_manager_activated() ) { 
                            get_template_part( 'template-parts/header', 'form' );
                        } else {
                            get_search_form();
                        }
                    ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
}
