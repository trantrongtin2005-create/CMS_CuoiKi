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

  /* 1. Reset khung trang & Loại bỏ sidebar, breadcrumb thừa */
  body.page-template-page-contact,
  .page-template-page-contact #page,
  .page-template-page-contact #content,
  .page-template-page-contact .site-content,
  .page-template-page-contact .site-main {
    background-color: #FFFFFF !important;
    font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
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
  .page-template-page-contact .header-t {
    display: none !important;
  }

  .page-template-page-contact #primary {
    width: 100% !important;
    float: none !important;
    max-width: 100% !important;
    padding: 0 !important;
    margin: 0 !important;
    background: transparent !important;
  }

  .page-template-page-contact .site-content .container {
    max-width: 100% !important;
    padding: 0 !important;
    margin: 0 !important;
    width: 100% !important;
  }

  .page-template-page-contact #main {
    padding: 0 !important;
    margin: 0 !important;
  }

  /* 2. Header: Logo, Menu & Nút Submit Job */
  .page-template-page-contact .site-header {
    background: #FFFFFF !important;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    position: sticky;
    top: 0;
    z-index: 999;
  }

  .page-template-page-contact .header-main .container {
    max-width: 1260px !important;
    padding: 12px 24px !important;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .page-template-page-contact .site-branding .site-title a {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 23px !important;
    font-weight: 800 !important;
    color: #1A1D20 !important;
    letter-spacing: -0.3px;
    text-transform: none;
    text-decoration: none;
    line-height: 1.15;
    display: block;
  }

  .page-template-page-contact .site-branding .site-description {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 11px !important;
    font-weight: 700 !important;
    letter-spacing: 2px !important;
    text-transform: uppercase !important;
    color: #666666 !important;
    margin: 3px 0 0 0 !important;
  }

  /* CHỈ áp dụng flex cho menu cấp 1 (tránh làm vỡ submenu dropdown của Việc làm) */
  .page-template-page-contact .main-navigation > div > ul,
  .page-template-page-contact .main-navigation > ul {
    display: flex !important;
    align-items: center !important;
    gap: 8px !important;
    list-style: none !important;
    margin: 0 !important;
    padding: 0 !important;
  }

  /* Menu items cấp 1 */
  .page-template-page-contact .main-navigation > div > ul > li,
  .page-template-page-contact .main-navigation > ul > li {
    position: relative !important;
    list-style: none !important;
    margin: 0 !important;
    padding: 0 !important;
  }

  .page-template-page-contact .main-navigation > div > ul > li > a,
  .page-template-page-contact .main-navigation > ul > li > a {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 13.5px !important;
    font-weight: 700 !important;
    color: #1E2022 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
    padding: 12px 14px !important;
    position: relative !important;
    transition: color 0.2s ease, border-bottom 0.2s ease !important;
    text-decoration: none !important;
    border-bottom: 2px solid transparent !important;
    display: inline-block !important;
  }

  .page-template-page-contact .main-navigation > div > ul > li > a:hover,
  .page-template-page-contact .main-navigation > ul > li > a:hover {
    color: #ea580c !important;
  }

  /* XÓA BỎ HOÀN TOÀN TẤT CẢ ::after TRÊN TOÀN BỘ NAVIGATION MENU ĐỂ KHÔNG BỊ GẠCH KÉP */
  .page-template-page-contact .main-navigation a::after,
  .page-template-page-contact .main-navigation li a::after,
  .page-template-page-contact .main-navigation li::after {
    display: none !important;
    content: none !important;
  }

  /* DUY NHẤT MỤC LIÊN HỆ ACTIVE: CHỮ CAM + ĐÚNG 1 GẠCH CHÂN BORDER-BOTTOM 2PX GIỐNG HỆT HÌNH 2 */
  .page-template-page-contact .main-navigation li.active-contact > a,
  .page-template-page-contact .main-navigation li.menu-item-contact > a,
  .page-template-page-contact .main-navigation li.current-menu-item > a,
  .page-template-page-contact .main-navigation li.current_page_item > a {
    color: #ea580c !important;
    border-bottom: 2px solid #ea580c !important;
  }

  /* ========================================================
     SỬA HÌNH 1: SUBMENU DROPDOWN VIỆC LÀM (HIỂN THỊ DỌC CHUẨN ĐẸP)
     ======================================================== */
  .page-template-page-contact .main-navigation ul.sub-menu,
  .page-template-page-contact .main-navigation ul ul {
    display: none !important;
    position: absolute !important;
    top: 100% !important;
    left: 0 !important;
    margin-left: 0 !important;
    opacity: 1 !important;
    visibility: visible !important;
    flex-direction: column !important;
    background: #FFFFFF !important;
    border: 1px solid #E2E8F0 !important;
    border-radius: 8px !important;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12) !important;
    padding: 8px !important;
    min-width: 250px !important;
    width: max-content !important;
    z-index: 9999 !important;
    gap: 4px !important;
    list-style: none !important;
  }

  /* Chỉ hiển thị khi hover chuột hoặc focus vào menu cha */
  .page-template-page-contact .main-navigation li:hover > ul.sub-menu,
  .page-template-page-contact .main-navigation li.hover > ul.sub-menu,
  .page-template-page-contact .main-navigation li:focus-within > ul.sub-menu {
    display: flex !important;
  }

  .page-template-page-contact .main-navigation ul.sub-menu li {
    display: block !important;
    width: 100% !important;
    margin: 0 !important;
    padding: 0 !important;
    list-style: none !important;
  }

  /* Chữ submenu: hiển thị trên 1 hàng ngang trọn vẹn, không ngắt từng chữ một */
  .page-template-page-contact .main-navigation ul.sub-menu li a {
    display: block !important;
    width: 100% !important;
    box-sizing: border-box !important;
    padding: 10px 16px !important;
    font-family: 'Montserrat', sans-serif !important;
    font-size: 13.5px !important;
    font-weight: 600 !important;
    color: #334155 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.3px !important;
    white-space: nowrap !important;
    border-radius: 6px !important;
    border-bottom: none !important;
    background: transparent !important;
    transition: all 0.2s ease !important;
    text-align: left !important;
  }

  .page-template-page-contact .main-navigation ul.sub-menu li a:hover {
    background: #EB6324 !important;
    color: #FFFFFF !important;
    box-shadow: 0 2px 8px rgba(235, 99, 36, 0.25) !important;
  }

  /* Nút SUBMIT JOB / ĐĂNG TIN TUYỂN DỤNG cam */
  .page-template-page-contact .menu-wrap .btn-wrap a.btn,
  .page-template-page-contact .header-main a.btn-primary {
    background-color: #EB6324 !important;
    border-color: #EB6324 !important;
    color: #FFFFFF !important;
    font-family: 'Montserrat', sans-serif !important;
    font-size: 13px !important;
    font-weight: 700 !important;
    text-transform: uppercase !important;
    padding: 11px 22px !important;
    border-radius: 4px !important;
    letter-spacing: 0.5px;
    transition: all 0.25s ease;
  }

  .page-template-page-contact .menu-wrap .btn-wrap a.btn:hover,
  .page-template-page-contact .header-main a.btn-primary:hover {
    background-color: #d65115 !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(235, 99, 36, 0.25);
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

  @media (max-width: 600px) {
    .cp-hero-banner {
      min-height: 280px;
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

  /* Gutenberg block styling support */
  .page-template-page-contact .contact-hero-banner {
    position: relative !important;
    width: 100% !important;
    min-height: 330px !important;
    background-color: #1a1a1a !important;
    background-position: center 38% !important;
    background-size: cover !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 40px 20px !important;
    margin: 0 !important;
  }

  .page-template-page-contact .contact-hero-title {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 40px !important;
    font-weight: 800 !important;
    color: #FFFFFF !important;
    text-transform: uppercase !important;
    letter-spacing: 1px !important;
    margin: 0 !important;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.45) !important;
  }

  .page-template-page-contact .contact-section-address {
    background: #FFFFFF !important;
    padding: 100px 24px 60px !important;
    text-align: center !important;
    margin: 0 !important;
  }

  .page-template-page-contact .contact-address-title {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 24px !important;
    font-weight: 700 !important;
    color: #1E2022 !important;
    margin: 0 0 16px !important;
    padding-top: 10px !important;
  }

  .page-template-page-contact .contact-address-text {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 15.5px !important;
    color: #4A5568 !important;
    max-width: 820px !important;
    margin: 0 auto !important;
    line-height: 1.6 !important;
  }

  .page-template-page-contact .contact-section-columns {
    background: #F5F6F8 !important;
    padding: 65px 24px 75px !important;
    margin: 0 !important;
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
    color: #1E2022 !important;
    margin: 0 0 12px !important;
  }

  .page-template-page-contact .contact-col-subtitle {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 14.5px !important;
    color: #64748B !important;
    margin: 0 0 24px !important;
  }

  .page-template-page-contact .contact-city-title {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 15px !important;
    font-weight: 700 !important;
    color: #1E2022 !important;
    margin: 18px 0 6px !important;
  }

  .page-template-page-contact .contact-phone-item {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 20px !important;
    font-weight: 800 !important;
    color: #1E2022 !important;
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
    color: #64748B !important;
    line-height: 1.6 !important;
    margin: 28px auto 0 !important;
    max-width: 440px !important;
  }

  .page-template-page-contact .contact-seeker-line {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 15px !important;
    color: #4A5568 !important;
    line-height: 1.6 !important;
    margin: 0 0 16px !important;
  }

  .page-template-page-contact .contact-seeker-call-title {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 16px !important;
    font-weight: 700 !important;
    color: #1E2022 !important;
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
  // Đánh dấu Active gạch chân cam duy nhất cho mục Liên hệ trên Header
  const navItems = document.querySelectorAll('.page-template-page-contact .main-navigation li');
  navItems.forEach(li => {
    const link = li.querySelector('a');
    if (!link) return;
    const text = link.textContent.trim().toLowerCase();
    const href = link.getAttribute('href') || '';
    if (href.includes('contact') || href.includes('lien-he') || text === 'liên hệ' || text === 'contact') {
      li.classList.add('active-contact', 'current-menu-item', 'current_page_item');
    } else if (!li.closest('.sub-menu')) {
      li.classList.remove('active-contact', 'current-menu-item', 'current_page_item');
    }
  });

  // Tùy chỉnh Logo đồng bộ với hình mẫu
  const brandingTitle = document.querySelector('.page-template-page-contact .site-branding .site-title a');
  const brandingDesc = document.querySelector('.page-template-page-contact .site-branding .site-description');
  if (brandingTitle && brandingTitle.textContent.trim() === 'CMS_CuoiKi') {
    brandingTitle.textContent = 'Plan Do See Global Inc.';
  }
  if (brandingDesc && (brandingDesc.textContent.trim() === 'Canhr cu' || brandingDesc.textContent.trim() === 'Just another WordPress site')) {
    brandingDesc.textContent = 'RECRUITING';
  }

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
