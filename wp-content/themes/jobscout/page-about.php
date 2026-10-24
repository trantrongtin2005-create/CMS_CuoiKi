<?php
/**
 * Template Name: Về chúng tôi (About Us)
 *
 * Giao diện hiển thị trang "Về chúng tôi" (About Us) tương thích trình soạn thảo Gutenberg Block Editor.
 * Cho phép người quản trị dễ dàng tự chỉnh sửa mọi nội dung văn bản, tiêu đề và hình ảnh trực tiếp trong wp-admin.
 *
 * @package JobScout
 */

get_header(); ?>

<!-- ========================================================
     CSS CHO TRANG ABOUT US (TƯƠNG THÍCH GUTENBERG BLOCK EDITOR)
     ======================================================== -->
<style id="jobscout-page-about-styles">
  /* Ẩn thanh Secondary Top Bar và Breadcrumbs/Page-Header mặc định */
  .page-template-page-about .header-t,
  .page-about .header-t,
  .page-template-page-about .breadcrumb-wrap,
  .page-about .breadcrumb-wrap,
  .page-template-page-about .page-header,
  .page-about .page-header,
  .page-template-page-about .entry-header,
  .page-about .entry-header,
  .page-template-page-about .entry-title,
  .page-about .entry-title,
  .page-template-page-about .edit-link,
  .page-about .edit-link,
  .page-template-page-about .entry-footer,
  .page-about .entry-footer,
  .page-template-page-about #secondary,
  .page-about #secondary {
    display: none !important;
  }

  /* Tràn viền 100% cho khung nội dung chính */
  .page-template-page-about .site-content,
  .page-about .site-content {
    margin-top: 0 !important;
    margin-bottom: 0 !important;
    padding: 0 !important;
    max-width: 100% !important;
    width: 100% !important;
  }

  .page-template-page-about .site-content > .container,
  .page-about .site-content > .container {
    max-width: 100% !important;
    width: 100% !important;
    padding: 0 !important;
    margin: 0 !important;
  }

  .page-template-page-about #primary,
  .page-about #primary {
    width: 100% !important;
    max-width: 100% !important;
    float: none !important;
    margin: 0 !important;
    padding: 0 !important;
  }

  /* Reset font chữ & layout chuẩn cho About page */
  .about-page-wrapper {
    font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    color: #333333;
    line-height: 1.6;
    background-color: #ffffff;
    overflow-x: hidden;
  }

  /* ----------------------------------------------------
     1. KHỐI ẢNH BÌA HERO (COVER BLOCK)
     ---------------------------------------------------- */
  .about-hero-block.wp-block-cover {
    min-height: 480px;
    padding: 40px 20px;
    position: relative;
    background-position: center 45%;
  }

  .about-hero-headline {
    color: #ffffff !important;
    font-size: clamp(28px, 4.2vw, 44px) !important;
    font-weight: 700 !important;
    letter-spacing: 2px !important;
    text-transform: uppercase !important;
    line-height: 1.35 !important;
    margin: 0 !important;
    text-shadow: 0 3px 12px rgba(0, 0, 0, 0.65);
    font-family: 'Montserrat', sans-serif !important;
  }

  /* ----------------------------------------------------
     2. TIÊU ĐỀ: VỀ CHÚNG TÔI
     ---------------------------------------------------- */
  .about-main-title {
    font-size: 34px !important;
    font-weight: 800 !important;
    color: #1E2022 !important;
    letter-spacing: 1.5px !important;
    text-transform: uppercase !important;
    margin: 0 auto !important;
    font-family: 'Montserrat', sans-serif !important;
    text-align: center;
  }

  /* ----------------------------------------------------
     3. KHỐI TẦM NHÌN, SỨ MỆNH & GIÁ TRỊ CỐT LÕI
     ---------------------------------------------------- */
  .about-vision-section {
    max-width: 1180px;
    margin: 0 auto;
    padding: 20px 24px 50px 24px;
    box-sizing: border-box;
  }

  .about-vision-content {
    padding-left: 20px;
  }

  .about-value-box {
    margin-bottom: 28px;
  }

  .about-value-box:last-child {
    margin-bottom: 0;
  }

  .about-value-title {
    font-size: 19px !important;
    font-weight: 700 !important;
    color: #1E2022 !important;
    margin: 0 0 8px 0 !important;
    font-family: 'Montserrat', sans-serif !important;
  }

  .about-value-quote {
    font-size: 15px !important;
    color: #4A5568 !important;
    margin: 0 0 4px 0 !important;
    font-style: italic !important;
    line-height: 1.6 !important;
  }

  .about-value-text {
    font-size: 15px !important;
    color: #555555 !important;
    line-height: 1.65 !important;
    margin: 0 !important;
  }

  .about-feature-img {
    width: 100%;
    height: auto;
    display: block;
    border-radius: 4px;
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .about-feature-img:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
  }

  .about-feature-img img {
    width: 100%;
    height: auto;
    border-radius: 4px;
    display: block;
  }

  /* ----------------------------------------------------
     4. KHỐI NỔI BẬT Ở GIỮA (LĨNH VỰC HOẠT ĐỘNG)
     ---------------------------------------------------- */
  .about-highlight-section {
    padding: 60px 24px 65px 24px !important;
    text-align: center !important;
    background-color: #fafbfc !important;
    border-top: 1px solid #e2e8f0 !important;
    border-bottom: 1px solid #e2e8f0 !important;
    margin: 30px 0 45px 0 !important;
    width: 100% !important;
    box-sizing: border-box !important;
  }

  .about-highlight-section .wp-block-group,
  .about-highlight-section .wp-block-group__inner-container {
    max-width: 900px !important;
    margin-left: auto !important;
    margin-right: auto !important;
  }

  .about-highlight-section h2,
  .about-highlight-section h3,
  .about-highlight-section h4,
  .about-highlight-subtitle {
    color: #EB6324 !important;
    font-size: 22px !important;
    font-weight: 700 !important;
    margin: 0 0 20px 0 !important;
    letter-spacing: 0.3px !important;
    font-family: 'Montserrat', sans-serif !important;
    text-align: center !important;
  }

  .about-highlight-section p {
    font-size: 15.5px !important;
    color: #4A5568 !important;
    line-height: 1.75 !important;
    margin: 0 auto 16px auto !important;
    text-align: center !important;
    max-width: 900px !important;
  }

  .about-highlight-section p:last-child {
    margin-bottom: 0 !important;
  }

  /* ----------------------------------------------------
     5. KHỐI THÔNG TIN DOANH NGHIỆP (FACTS) & TOKYO TOWER
     ---------------------------------------------------- */
  .about-facts-section {
    max-width: 1180px;
    margin: 0 auto;
    padding: 20px 24px 80px 24px;
    box-sizing: border-box;
  }

  .about-facts-content {
    text-align: center;
    padding-right: 20px;
  }

  .about-fact-box {
    margin-bottom: 24px;
  }

  .about-fact-box:last-child {
    margin-bottom: 0;
  }

  .about-fact-label {
    font-size: 17.5px !important;
    font-weight: 700 !important;
    color: #1E2022 !important;
    margin: 0 0 4px 0 !important;
    font-family: 'Montserrat', sans-serif !important;
    text-align: center;
  }

  .about-fact-value {
    font-size: 15px !important;
    color: #4A5568 !important;
    margin: 0 !important;
    line-height: 1.6 !important;
    text-align: center;
  }

  /* Khắc phục Gutenberg columns layout */
  .about-page-wrapper .wp-block-columns {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 60px;
    margin-bottom: 0;
  }

  /* Khống chế kích thước ảnh khối thông tin doanh nghiệp (Facts) không bị quá to */
  .about-facts-section .wp-block-column:last-child {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
  }

  .about-facts-section .about-feature-img,
  .about-facts-section .wp-block-image {
    max-width: 320px !important;
    width: 100% !important;
    margin: 0 auto !important;
    text-align: center !important;
  }

  .about-facts-section .about-feature-img img,
  .about-facts-section .wp-block-image img {
    max-width: 100% !important;
    width: 100% !important;
    height: auto !important;
    max-height: 420px !important;
    object-fit: cover !important;
    border-radius: 8px !important;
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08) !important;
    display: block !important;
    margin: 0 auto !important;
  }

  /* ----------------------------------------------------
     TƯƠNG THÍCH TRÊN MÁY TÍNH BẢNG VÀ ĐIỆN THOẠI (RESPONSIVE)
     ---------------------------------------------------- */
  @media (max-width: 991px) {
    .about-hero-block.wp-block-cover {
      min-height: 380px;
    }
    .about-page-wrapper .wp-block-columns {
      flex-direction: column !important;
      gap: 40px !important;
    }
    .about-page-wrapper .wp-block-column {
      flex: 1 1 100% !important;
      width: 100% !important;
      max-width: 100% !important;
    }
    .about-vision-content {
      padding-left: 0 !important;
    }
    .about-facts-content {
      padding-right: 0 !important;
      order: 1;
    }
    .about-facts-section .wp-block-column:last-child {
      order: 2;
    }
    .about-feature-img {
      max-width: 520px;
      margin: 0 auto;
    }
  }

  @media (max-width: 600px) {
    .about-hero-block.wp-block-cover {
      min-height: 300px;
    }
    .about-main-title {
      font-size: 28px !important;
    }
    .about-highlight-section {
      padding: 45px 16px;
    }
    .about-highlight-subtitle {
      font-size: 18px !important;
    }
    .about-highlight-section p {
      font-size: 14.5px !important;
      text-align: left;
    }
    .about-facts-section {
      padding-bottom: 50px;
    }
  }
</style>

<div class="about-page-wrapper">
<?php
while ( have_posts() ) : the_post();
    the_content();
endwhile;
?>
</div><!-- .about-page-wrapper -->

<?php
get_footer();
