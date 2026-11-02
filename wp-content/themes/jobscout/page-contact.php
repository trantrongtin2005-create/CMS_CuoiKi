<?php
/**
 * Template Name: Contact Page (Trang Liên Hệ)
 *
 * Mẫu giao diện trang Liên hệ chuẩn 100% theo ảnh thiết kế:
 * 1. Header Navigation:
 *    - Menu điều hướng với gạch chân cam active duy nhất tại mục LIÊN HỆ (CONTACT) - không bị 2 đường gạch
 *    - Submenu dropdown tại VIỆC LÀM hiển thị chuẩn dọc, chữ 1 dòng không bị gãy từ
 * 2. Hero Banner: Hình cô gái Kimono Kyoto ô đỏ + Tiêu đề "LIÊN HỆ VỚI CHÚNG TÔI" (đã bỏ nút dịch)
 * 3. Section 1 (Nền trắng):<br>
 *    - "Địa chỉ trụ sở chính của chúng tôi"
 *    - "60 Nguyễn Văn Thủ, Phường Đa Kao, Quận 1, TP. Hồ Chí Minh, Việt Nam"
 * 4. Section 2 (Nền xám nhạt #F5F6F8):
 *    - Cột Trái: Dành cho Nhà tuyển dụng
 *      + Gọi cho Hotline kinh doanh của chúng tôi
 *      + TP. Hồ Chí Minh + SĐT Hotline (028) 3911 8333
 *      + Hà Nội + SĐT Hotline (024) 3911 8333
 *      + Đoạn thông điệp đồng hành phát triển
 *    - Cột Phải: Dành cho Người tìm việc
 *      + Đặt câu hỏi trên trang Facebook của chúng tôi (chữ Facebook màu cam có link)
 *      + Đọc các bài viết trên blog của chúng tôi về bí quyết phỏng vấn và mẹo viết CV (chữ bài viết trên blog màu cam có link)
 *      + Hotline hỗ trợ ứng viên: (028) 7300 8889
 * 5. Tự động kết nối Module Footer (Newsletter dải cam, Brand, 4 Icon MXH, Copyright)
 *
 * @package JobScout
 */

get_header(); ?>

<!-- ========================================================
     CSS STYLES DÀNH RIÊNG CHO TRANG LIÊN HỆ (CHUẨN 100% THEO HÌNH)
     ======================================================== -->
<style id="contact-page-custom-styles">
  @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&family=Open+Sans:wght@400;500;600;700&display=swap');

  /* 1. Reset khung trang & Loại bỏ sidebar, breadcrumb thừa (Tràn viền 100% cho khung chính, sát header) */
  body.page-template-page-contact,
  body.page-template-page-contact-php,
  .page-template-page-contact #page,
  .page-template-page-contact-php #page,
  .page-template-page-contact #content,
  .page-template-page-contact-php #content,
  .page-template-page-contact .site-main,
  .page-template-page-contact-php .site-main {
    background-color: #FFFFFF !important;
  }

  .page-template-page-contact .site-content,
  .page-template-page-contact-php .site-content,
  .page-contact .site-content {
    margin-top: 0 !important;
    margin-bottom: 0 !important;
    padding: 0 !important;
    max-width: 100% !important;
    width: 100% !important;
    background-color: #FFFFFF !important;
  }

  .page-template-page-contact .site-content > header.entry-header,
  .page-template-page-contact .site-content > header.page-header,
  .page-template-page-contact .entry-header,
  .page-template-page-contact .entry-title,
  .page-template-page-contact .edit-link,
  .page-template-page-contact .entry-footer,
  .page-template-page-contact #secondary,
  .page-template-page-contact .breadcrumbs,
  .page-template-page-contact .breadcrumb-wrap,
  .page-template-page-contact-php .site-content > header.entry-header,
  .page-template-page-contact-php .site-content > header.page-header,
  .page-template-page-contact-php .entry-header,
  .page-template-page-contact-php .entry-title,
  .page-template-page-contact-php .edit-link,
  .page-template-page-contact-php .entry-footer,
  .page-template-page-contact-php #secondary,
  .page-template-page-contact-php .breadcrumbs,
  .page-template-page-contact-php .breadcrumb-wrap {
    display: none !important;
  }

  .page-template-page-contact #primary,
  .page-template-page-contact-php #primary {
    width: 100% !important;
    float: none !important;
    max-width: 100% !important;
    padding: 0 !important;
    margin: 0 !important;
    background: transparent !important;
  }

  .page-template-page-contact .site-content > .container,
  .page-template-page-contact-php .site-content > .container,
  .page-template-page-contact .site-content .container,
  .page-template-page-contact-php .site-content .container {
    max-width: 100% !important;
    width: 100% !important;
    padding: 0 !important;
    margin: 0 !important;
  }

  .page-template-page-contact #main,
  .page-template-page-contact-php #main {
    padding: 0 !important;
    margin: 0 !important;
    width: 100% !important;
  }

  .contact-page-wrapper {
    font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    color: #333333;
    line-height: 1.6;
    background-color: #ffffff;
    overflow-x: hidden;
    width: 100%;
  }

  /* Đảm bảo mục Liên hệ trên Navbar luôn đổi sang chữ màu Cam và gạch cam đúng chuẩn như hình */
  body.page-template-page-contact .main-navigation ul.nav-menu > li.menu-item-133 > a,
  body.page-template-page-contact .main-navigation ul.nav-menu > li.current-menu-item > a,
  body.page-template-page-contact-php .main-navigation ul.nav-menu > li.menu-item-133 > a,
  body.page-template-page-contact-php .main-navigation ul.nav-menu > li.current-menu-item > a,
  body.page-id-989 .main-navigation ul.nav-menu > li.menu-item-133 > a,
  body.page-id-989 .main-navigation ul.nav-menu > li.current-menu-item > a {
    color: #ea580c !important;
    border-bottom: 3px solid #ea580c !important;
    text-decoration: none !important;
  }

  body.page-template-page-contact .main-navigation ul.nav-menu > li:not(.menu-item-133):not(.current-menu-item) > a,
  body.page-template-page-contact-php .main-navigation ul.nav-menu > li:not(.menu-item-133):not(.current-menu-item) > a,
  body.page-id-989 .main-navigation ul.nav-menu > li:not(.menu-item-133):not(.current-menu-item) > a {
    color: #1e293b !important;
    border-bottom: 3px solid transparent !important;
  }

  /* ========================================================
     3. HERO BANNER: ẢNH KYOTO CÔ GÁI KIMONO Ô ĐỎ + "LIÊN HỆ VỚI CHÚNG TÔI"
     ======================================================== */
  .cp-hero-banner {
    position: relative;
    width: 100%;
    min-height: 330px;
    background-color: #1a1a1a;
    background-image: url('<?php echo esc_url( get_template_directory_uri() . '/images/contact-banner-bg.jpg' ); ?>');
    background-position: center 38%;
    background-size: cover;
    background-repeat: no-repeat;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
  }

  .cp-hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(0, 0, 0, 0.32) 0%, rgba(0, 0, 0, 0.5) 100%);
    z-index: 1;
  }

  .cp-hero-content {
    position: relative;
    z-index: 2;
    text-align: center;
    padding: 40px 20px;
    max-width: 900px;
  }

  .cp-hero-title {
    font-family: 'Montserrat', sans-serif;
    font-size: 40px;
    font-weight: 800;
    color: #FFFFFF;
    text-transform: uppercase;
    letter-spacing: 2px;
    margin: 0;
    line-height: 1.2;
    text-shadow: 0 2px 14px rgba(0, 0, 0, 0.6);
  }

  /* ========================================================
     4. SECTION 1: NỀN TRẮNG - ĐỊA CHỈ TRỤ SỞ CHÍNH
     ======================================================== */
  .cp-section-address {
    background-color: #FFFFFF;
    padding: 85px 24px 55px;
    text-align: center;
    border-bottom: 1px solid #ECEFF2;
  }

  .cp-address-container {
    max-width: 820px;
    margin: 0 auto;
  }

  .cp-address-title {
    font-family: 'Montserrat', sans-serif;
    font-size: 21px;
    font-weight: 700;
    color: #1E2022;
    margin: 0 0 14px 0;
    letter-spacing: -0.2px;
  }

  .cp-address-text {
    font-family: 'Open Sans', sans-serif;
    font-size: 15px;
    color: #4B5563;
    line-height: 1.6;
    margin: 0;
    font-weight: 400;
  }

  /* ========================================================
     5. SECTION 2: NỀN XÁM NHẠT #F5F6F8 - KHỐI 2 CỘT THÔNG TIN
     ======================================================== */
  .cp-section-columns {
    background-color: #F5F6F8;
    padding: 68px 24px 78px;
    width: 100%;
  }

  .cp-columns-container {
    max-width: 980px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 1fr;
    column-gap: 80px;
    row-gap: 40px;
  }

  .cp-col {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
  }

  .cp-col-title {
    font-family: 'Montserrat', sans-serif;
    font-size: 20px;
    font-weight: 700;
    color: #1E2022;
    margin: 0 0 10px 0;
    letter-spacing: -0.2px;
  }

  .cp-col-subtitle {
    font-family: 'Open Sans', sans-serif;
    font-size: 14.5px;
    color: #4B5563;
    margin: 0 0 26px 0;
    line-height: 1.5;
  }

  /* Khối địa điểm & số hotline */
  .cp-phone-group {
    margin-bottom: 22px;
    width: 100%;
  }

  .cp-city-title {
    font-family: 'Montserrat', sans-serif;
    font-size: 16px;
    font-weight: 700;
    color: #1E2022;
    margin: 0 0 6px 0;
  }

  .cp-phone-text {
    font-family: 'Montserrat', sans-serif;
    font-size: 17px;
    font-weight: 700;
    color: #1E2022;
    text-decoration: none;
    letter-spacing: 0.5px;
    display: inline-block;
    cursor: default !important;
    pointer-events: none !important;
  }

  /* Thông điệp hỗ trợ phía dưới cột Nhà tuyển dụng */
  .cp-support-note {
    font-family: 'Open Sans', sans-serif;
    font-size: 14px;
    color: #4B5563;
    line-height: 1.6;
    margin: 18px 0 0 0;
    max-width: 360px;
  }

  .cp-support-note span {
    display: block;
  }

  /* Cột Người tìm việc: Liên kết cam */
  .cp-link-orange {
    color: #EB6324 !important;
    text-decoration: underline !important;
    text-underline-offset: 3px;
    font-weight: 600;
    transition: opacity 0.2s ease;
  }

  .cp-link-orange:hover {
    opacity: 0.82;
  }

  .cp-seeker-line {
    font-family: 'Open Sans', sans-serif;
    font-size: 14.5px;
    color: #374151;
    margin: 0 0 12px 0;
    line-height: 1.6;
  }

  .cp-seeker-call-title {
    font-family: 'Montserrat', sans-serif;
    font-size: 16px;
    font-weight: 700;
    color: #1E2022;
    margin: 26px 0 6px 0;
  }

  /* Responsive Mobile/Tablet */
  @media (max-width: 860px) {
    .cp-columns-container {
      grid-template-columns: 1fr;
      row-gap: 50px;
    }
    .cp-hero-title {
      font-size: 32px;
    }
  }

  @media (max-width: 991px) {
    .contact-hero-banner.wp-block-cover,
    .page-template-page-contact .contact-hero-banner.wp-block-cover,
    .page-template-page-contact-php .contact-hero-banner.wp-block-cover,
    .page-template-page-contact .contact-hero-banner,
    .page-template-page-contact .cp-hero-banner {
      min-height: 380px !important;
    }
  }

  @media (max-width: 600px) {
    .contact-hero-banner.wp-block-cover,
    .page-template-page-contact .contact-hero-banner.wp-block-cover,
    .page-template-page-contact-php .contact-hero-banner.wp-block-cover,
    .page-template-page-contact .contact-hero-banner,
    .page-template-page-contact .cp-hero-banner {
      min-height: 300px !important;
    }
    .contact-hero-title,
    .cp-hero-title {
      font-size: 26px !important;
    }
    .contact-section-address,
    .cp-section-address {
      padding: 42px 18px 38px !important;
    }
    .contact-section-columns,
    .cp-section-columns {
      padding: 48px 18px 56px !important;
    }
    .contact-info-columns,
    .cp-columns-container {
      flex-direction: column !important;
      gap: 36px !important;
    }
  }

  /* Gutenberg block styling support: Banner tràn viền 100% giống trang Về chúng tôi */
  .contact-hero-banner.wp-block-cover,
  .page-template-page-contact .contact-hero-banner.wp-block-cover,
  .page-template-page-contact-php .contact-hero-banner.wp-block-cover,
  .page-template-page-contact .contact-hero-banner,
  .page-template-page-contact-php .contact-hero-banner,
  .page-template-page-contact .cp-hero-banner,
  .page-template-page-contact-php .cp-hero-banner {
    min-height: 480px !important;
    padding: 40px 20px !important;
    position: relative !important;
    background-position: center 45% !important;
    background-size: cover !important;
    width: 100% !important;
    max-width: 100% !important;
    margin: 0 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    box-sizing: border-box !important;
  }

  .contact-hero-title,
  .page-template-page-contact .contact-hero-title,
  .page-template-page-contact-php .contact-hero-title,
  .page-template-page-contact .cp-hero-title,
  .page-template-page-contact-php .cp-hero-title {
    color: #ffffff;
    font-size: clamp(28px, 4.2vw, 44px) !important;
    font-weight: 700 !important;
    letter-spacing: 2px !important;
    text-transform: uppercase !important;
    line-height: 1.35 !important;
    margin: 0 !important;
    text-shadow: 0 3px 12px rgba(0, 0, 0, 0.65) !important;
    font-family: 'Montserrat', sans-serif !important;
    text-align: center !important;
  }

  .page-template-page-contact .contact-section-address,
  .page-template-page-contact-php .contact-section-address {
    background: #FFFFFF !important;
    padding: 85px 24px 60px !important;
    text-align: center !important;
    margin: 0 auto !important;
    width: 100% !important;
    box-sizing: border-box !important;
  }

  .page-template-page-contact .contact-section-address .wp-block-group__inner-container,
  .page-template-page-contact-php .contact-section-address .wp-block-group__inner-container {
    max-width: 820px !important;
    margin-left: auto !important;
    margin-right: auto !important;
    box-sizing: border-box !important;
  }

  .page-template-page-contact .contact-address-title {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 24px !important;
    font-weight: 700 !important;
    color: #1E2022;
    margin: 0 0 16px !important;
    padding-top: 10px !important;
  }

  .page-template-page-contact .contact-address-text {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 15.5px !important;
    color: #4A5568;
    max-width: 820px !important;
    margin: 0 auto !important;
    line-height: 1.6 !important;
  }

  .page-template-page-contact .contact-section-columns,
  .page-template-page-contact-php .contact-section-columns {
    background: #F5F6F8 !important;
    padding: 68px 24px 78px !important;
    margin: 0 auto !important;
    width: 100% !important;
    box-sizing: border-box !important;
  }

  .page-template-page-contact .contact-section-columns .wp-block-group__inner-container,
  .page-template-page-contact-php .contact-section-columns .wp-block-group__inner-container {
    max-width: 1140px !important;
    margin-left: auto !important;
    margin-right: auto !important;
    box-sizing: border-box !important;
  }

  .page-template-page-contact .contact-info-columns {
    max-width: 1140px !important;
    margin: 0 auto !important;
    display: flex !important;
    gap: 48px !important;
    align-items: flex-start !important;
  }

  .page-template-page-contact .contact-col-employers,
  .page-template-page-contact .contact-col-jobseekers {
    flex: 1 1 0 !important;
    text-align: center !important;
  }

  .page-template-page-contact .contact-col-title {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 22px !important;
    font-weight: 700 !important;
    color: #1E2022;
    margin: 0 0 12px !important;
  }

  .page-template-page-contact .contact-col-subtitle {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 14.5px !important;
    color: #64748B;
    margin: 0 0 24px !important;
  }

  .page-template-page-contact .contact-city-title {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 15px !important;
    font-weight: 700 !important;
    color: #1E2022;
    margin: 18px 0 6px !important;
  }

  .page-template-page-contact .contact-phone-item {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 20px !important;
    font-weight: 800 !important;
    color: #1E2022;
    margin: 0 0 16px !important;
    cursor: default !important;
    user-select: text !important;
    text-decoration: none !important;
  }

  .page-template-page-contact .contact-phone-item a,
  .page-template-page-contact .contact-phone-link {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 20px !important;
    font-weight: 800 !important;
    color: #1E2022 !important;
    text-decoration: none !important;
    pointer-events: none !important;
    cursor: default !important;
  }

  .page-template-page-contact .contact-support-note {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 14px !important;
    color: #64748B;
    line-height: 1.6 !important;
    margin: 28px auto 0 !important;
    max-width: 440px !important;
  }

  .page-template-page-contact .contact-seeker-line {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 15px !important;
    color: #4A5568;
    line-height: 1.6 !important;
    margin: 0 0 16px !important;
  }

  .page-template-page-contact .contact-seeker-call-title {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 16px !important;
    font-weight: 700 !important;
    color: #1E2022;
    margin: 30px 0 12px !important;
  }

  .page-template-page-contact .contact-orange-link {
    color: #EA580C !important;
    font-weight: 600 !important;
    text-decoration: underline !important;
  }

  .page-template-page-contact .contact-orange-link:hover {
    color: #c2410c !important;
  }
</style>

<div id="primary" class="content-area contact-page-wrapper">
  <main id="main" class="site-main">

    <?php
    while ( have_posts() ) : the_post();
        the_content();
    endwhile;
    ?>

  </main><!-- #main -->
</div><!-- #primary -->

<!-- ========================================================
     JAVASCRIPT LOGIC CHO TRANG LIÊN HỆ
     ======================================================== -->
<script id="contact-page-script">
(function() {
  // Đảm bảo chữ Liên hệ trên thanh menu đổi sang màu Cam và có gạch chân màu Cam chuẩn như hình
  document.querySelectorAll('.main-navigation ul.nav-menu > li').forEach(function(li) {
    var a = li.querySelector('a');
    if (!a) return;
    var txt = a.textContent.trim().toLowerCase();
    var href = (a.getAttribute('href') || '').toLowerCase();
    if (txt === 'liên hệ' || txt === 'contact' || href.indexOf('contact') !== -1 || li.classList.contains('menu-item-133')) {
      li.classList.add('current-menu-item', 'current_page_item');
      a.style.setProperty('color', '#ea580c', 'important');
      a.style.setProperty('border-bottom', '3px solid #ea580c', 'important');
    } else {
      li.classList.remove('current-menu-item', 'current_page_item');
      a.style.removeProperty('border-bottom');
    }
  });

  // Đảm bảo tất cả các số hotline chỉ là văn bản thường, không click chuyển trang
  document.querySelectorAll('.contact-phone-item, .contact-phone-item a, .cp-phone-text, .contact-phone-link').forEach(function(el) {
    el.style.cursor = 'default';
    if (el.tagName === 'A') {
      el.removeAttribute('href');
      el.addEventListener('click', function(e) { e.preventDefault(); });
    }
  });
})();
</script>

<?php
get_footer();
