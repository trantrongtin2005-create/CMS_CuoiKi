<?php
/**
 * CTA Section (CAREER WITH US MODULE)
 * Thiết kế theo mẫu:
 * - Ảnh nền toàn chiều rộng (hoa anh đào + bảng gỗ thư pháp Nhật), phủ lớp tối.
 * - Tiêu đề trắng căn giữa: "CAREER WITH US".
 * - 2 đoạn mô tả căn giữa.
 * - Nút "MORE ABOUT US" viền trắng.
 *
 * Nội dung chỉnh trong: Giao diện → Tùy biến → Front Page Settings → Career With Us Section.
 * Giá trị mặc định nằm ở inc/customizer/panels/home/cta.php (jobscout_get_cta_career_defaults).
 *
 * @package JobScout
 */

$ed_cta_career   = jobscout_get_cta_career_option( 'ed_cta_section' );
$career_title    = jobscout_get_cta_career_option( 'cta_career_title' );
$career_text_1   = jobscout_get_cta_career_option( 'cta_career_text_1' );
$career_text_2   = jobscout_get_cta_career_option( 'cta_career_text_2' );
$career_btn_text = jobscout_get_cta_career_option( 'cta_career_btn_text' );
$career_btn_url  = jobscout_get_cta_career_option( 'cta_career_btn_url' );
$career_bg       = jobscout_get_cta_career_option( 'cta_career_bg' );

if ( $ed_cta_career ) {
?>

<!-- ========================================================
     CSS CHO MODULE CAREER WITH US
     ======================================================== -->
<style id="career-with-us-module-styles">
  #cta-section.career-module-section {
    position: relative !important;
    width: 100% !important;
    min-height: 300px !important;
    padding: 50px 0 48px !important;
    background-color: #1a1210 !important;
    background-size: cover !important;
    background-position: center !important;
    background-repeat: no-repeat !important;
    font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
    overflow: hidden !important;
    box-sizing: border-box !important;
  }

  /* Lớp phủ tối giúp chữ trắng dễ đọc */
  #cta-section.career-module-section::before {
    content: "" !important;
    position: absolute !important;
    inset: 0 !important;
    background: linear-gradient(180deg, rgba(20, 12, 10, 0.62) 0%, rgba(20, 12, 10, 0.72) 100%) !important;
    z-index: 0 !important;
  }

  .career-module-section * {
    box-sizing: border-box !important;
  }

  .career-module-section .career-container {
    position: relative !important;
    z-index: 1 !important;
    max-width: 780px !important;
    margin: 0 auto !important;
    padding: 0 24px !important;
    text-align: center !important;
  }

  .career-module-section .career-heading {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 30px !important;
    font-weight: 600 !important;
    color: #FFFFFF !important;
    text-transform: uppercase !important;
    letter-spacing: 0.4px !important;
    line-height: 1.2 !important;
    margin: 0 0 22px 0 !important;
  }

  .career-module-section .career-text {
    font-size: 13px !important;
    font-weight: 400 !important;
    color: rgba(255, 255, 255, 0.9) !important;
    line-height: 1.75 !important;
    margin: 0 0 18px 0 !important;
  }

  .career-module-section .career-text:last-of-type {
    margin-bottom: 30px !important;
  }

  .career-module-section .career-btn {
    display: inline-block !important;
    background: transparent !important;
    border: 1px solid #FFFFFF !important;
    color: #FFFFFF !important;
    font-family: 'Montserrat', sans-serif !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.6px !important;
    padding: 9px 18px !important;
    border-radius: 2px !important;
    text-decoration: none !important;
    transition: all 0.25s ease !important;
  }

  .career-module-section .career-btn:hover,
  .career-module-section .career-btn:focus {
    background: #FFFFFF !important;
    color: #1E2022 !important;
    transform: translateY(-1px) !important;
  }

  @media (max-width: 860px) {
    #cta-section.career-module-section {
      padding: 44px 0 !important;
    }
    .career-module-section .career-heading {
      font-size: 26px !important;
    }
  }

  @media (max-width: 520px) {
    .career-module-section .career-heading {
      font-size: 22px !important;
    }
    .career-module-section .career-text {
      font-size: 12.5px !important;
    }
  }
</style>

<!-- ========================================================
     HTML MARKUP CHO MODULE CAREER WITH US
     ======================================================== -->
<section id="cta-section" class="bg-cta-section career-module-section" style="background-image: url('<?php echo esc_url( $career_bg ); ?>');">
  <div class="career-container">
    <h2 class="career-heading"><?php echo esc_html( $career_title ); ?></h2>

    <?php if ( $career_text_1 ) : ?>
      <p class="career-text"><?php echo esc_html( $career_text_1 ); ?></p>
    <?php endif; ?>

    <?php if ( $career_text_2 ) : ?>
      <p class="career-text"><?php echo esc_html( $career_text_2 ); ?></p>
    <?php endif; ?>

    <?php if ( $career_btn_text && $career_btn_url ) : ?>
      <a href="<?php echo esc_url( $career_btn_url ); ?>" class="career-btn"><?php echo esc_html( $career_btn_text ); ?></a>
    <?php endif; ?>
  </div>
</section> <!-- .bg-cta-section -->

<?php
}