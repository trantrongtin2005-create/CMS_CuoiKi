<?php
/**
 * CTA Section (CAREER WITH US MODULE)
 * Thiết kế chuẩn theo mẫu:
 * - Ảnh nền toàn chiều rộng (hoa anh đào + bảng gỗ thư pháp Nhật), hỗ trợ người dùng tự do thay đổi ảnh.
 * - Lớp phủ mờ tùy chỉnh độ tối (opacity) và màu sắc để giữ tương phản tuyệt đối cho văn bản.
 * - Tiêu đề trắng nổi bật: "CAREER WITH US".
 * - 2 đoạn mô tả chuẩn font Montserrat, sắc nét.
 * - Nút "MORE ABOUT US" viền trắng tối giản, hiệu ứng hover mượt mà.
 *
 * Người dùng có thể chỉnh sửa tại:
 * 1. Giao diện → Tùy biến → Front Page Settings → Career With Us Section (Xem trước trực tiếp).
 * 2. Trang → Home (Chỉnh sửa Trang chủ trong WP-Admin).
 *
 * @package JobScout
 */

$ed_cta_career     = jobscout_get_cta_career_option( 'ed_cta_section' );
$career_title      = jobscout_get_cta_career_option( 'cta_career_title' );
$career_text_1     = jobscout_get_cta_career_option( 'cta_career_text_1' );
$career_text_2     = jobscout_get_cta_career_option( 'cta_career_text_2' );
$career_btn_text   = jobscout_get_cta_career_option( 'cta_career_btn_text' );
$career_btn_url    = jobscout_get_cta_career_option( 'cta_career_btn_url' );
$career_btn_target = jobscout_get_cta_career_option( 'cta_career_btn_target' );
$career_bg_url     = jobscout_get_cta_career_bg_url();
$career_opacity    = (int) jobscout_get_cta_career_option( 'cta_career_overlay_opacity' );
$career_color      = jobscout_get_cta_career_option( 'cta_career_overlay_color' );
$career_align      = jobscout_get_cta_career_option( 'cta_career_alignment' );

if ( ! in_array( $career_align, array( 'left', 'right', 'center' ), true ) ) {
    $career_align = 'center';
}

if ( $ed_cta_career ) {
    // Tính toán độ phủ và màu overlay
    $alpha = max( 0, min( 0.95, round( $career_opacity / 100, 2 ) ) );
    $rgb   = function_exists( 'jobscout_cta_hex_to_rgb' ) ? jobscout_cta_hex_to_rgb( $career_color ) : array( 20, 12, 10 );
    $rgba_top    = 'rgba(' . $rgb[0] . ', ' . $rgb[1] . ', ' . $rgb[2] . ', ' . max( 0, round( $alpha * 0.9, 2 ) ) . ')';
    $rgba_bottom = 'rgba(' . $rgb[0] . ', ' . $rgb[1] . ', ' . $rgb[2] . ', ' . $alpha . ')';
?>

<!-- ========================================================
     CSS CHO MODULE CAREER WITH US
     ======================================================== -->
<style id="career-with-us-module-styles">
  #cta-section.career-module-section {
    position: relative !important;
    width: 100% !important;
    min-height: 320px !important;
    padding: 58px 0 54px !important;
    background-color: #140c0a !important;
    background-size: cover !important;
    background-position: center !important;
    background-repeat: no-repeat !important;
    font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
    overflow: hidden !important;
    box-sizing: border-box !important;
  }

  /* Lớp phủ động giúp tương phản hoàn hảo khi người dùng thay bất kỳ ảnh nền nào */
  .career-module-section .career-overlay {
    position: absolute !important;
    inset: 0 !important;
    background: linear-gradient(180deg, <?php echo esc_attr( $rgba_top ); ?> 0%, <?php echo esc_attr( $rgba_bottom ); ?> 100%) !important;
    z-index: 1 !important;
    pointer-events: none !important;
    transition: background 0.3s ease !important;
  }

  .career-module-section * {
    box-sizing: border-box !important;
  }

  .career-module-section .career-container {
    position: relative !important;
    z-index: 2 !important;
    max-width: 800px !important;
    margin: 0 auto !important;
    padding: 0 24px !important;
  }

  .career-module-section .career-container.text-align-center {
    text-align: center !important;
  }
  .career-module-section .career-container.text-align-left {
    text-align: left !important;
  }
  .career-module-section .career-container.text-align-right {
    text-align: right !important;
  }

  .career-module-section .career-heading {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 32px !important;
    font-weight: 700 !important;
    color: #FFFFFF !important;
    text-transform: uppercase !important;
    letter-spacing: 0.8px !important;
    line-height: 1.25 !important;
    margin: 0 0 24px 0 !important;
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.45) !important;
  }

  .career-module-section .career-text {
    font-size: 13.5px !important;
    font-weight: 400 !important;
    color: rgba(255, 255, 255, 0.92) !important;
    line-height: 1.8 !important;
    margin: 0 0 18px 0 !important;
    text-shadow: 0 1px 4px rgba(0, 0, 0, 0.35) !important;
  }

  .career-module-section .career-text:last-of-type {
    margin-bottom: 32px !important;
  }

  .career-module-section .career-btn-wrap {
    margin-top: 10px !important;
  }

  .career-module-section .career-btn {
    display: inline-block !important;
    background: transparent !important;
    border: 1.5px solid #FFFFFF !important;
    color: #FFFFFF !important;
    font-family: 'Montserrat', sans-serif !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.8px !important;
    padding: 10px 22px !important;
    border-radius: 2px !important;
    text-decoration: none !important;
    transition: all 0.28s cubic-bezier(0.4, 0, 0.2, 1) !important;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2) !important;
  }

  .career-module-section .career-btn:hover,
  .career-module-section .career-btn:focus {
    background: #FFFFFF !important;
    color: #140c0a !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.35) !important;
  }

  @media (max-width: 860px) {
    #cta-section.career-module-section {
      padding: 48px 0 !important;
      min-height: 280px !important;
    }
    .career-module-section .career-heading {
      font-size: 26px !important;
      margin-bottom: 20px !important;
    }
    .career-module-section .career-text {
      font-size: 13px !important;
      line-height: 1.75 !important;
    }
  }

  @media (max-width: 520px) {
    #cta-section.career-module-section {
      padding: 40px 0 !important;
    }
    .career-module-section .career-heading {
      font-size: 22px !important;
      letter-spacing: 0.5px !important;
    }
    .career-module-section .career-text {
      font-size: 12.5px !important;
      line-height: 1.7 !important;
      margin-bottom: 14px !important;
    }
    .career-module-section .career-text:last-of-type {
      margin-bottom: 24px !important;
    }
    .career-module-section .career-btn {
      padding: 9px 18px !important;
      font-size: 12px !important;
    }
  }
</style>

<!-- ========================================================
     HTML MARKUP CHO MODULE CAREER WITH US
     ======================================================== -->
<section id="cta-section" class="bg-cta-section career-module-section" style="background-image: url('<?php echo esc_url( $career_bg_url ); ?>');">
  <div class="career-overlay"></div>
  <div class="career-container <?php echo esc_attr( 'text-align-' . $career_align ); ?>">
    <?php if ( $career_title ) : ?>
      <h2 class="career-heading"><?php echo esc_html( $career_title ); ?></h2>
    <?php endif; ?>

    <?php if ( $career_text_1 ) : ?>
      <p class="career-text career-text-1"><?php echo esc_html( $career_text_1 ); ?></p>
    <?php endif; ?>

    <?php if ( $career_text_2 ) : ?>
      <p class="career-text career-text-2"><?php echo esc_html( $career_text_2 ); ?></p>
    <?php endif; ?>

    <?php if ( $career_btn_text && $career_btn_url ) : ?>
      <div class="career-btn-wrap">
        <a href="<?php echo esc_url( $career_btn_url ); ?>" class="career-btn" <?php echo $career_btn_target ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
          <?php echo esc_html( $career_btn_text ); ?>
        </a>
      </div>
    <?php endif; ?>
  </div>
</section> <!-- .bg-cta-section -->

<?php
}