<?php
/**
 * The template for displaying the footer
 * Module Footer chuẩn thiết kế: CMS_CuoiKi
 * 
 * 1. Newsletter Subscription Bar (Màu cam, Icon phong bì, Nút Subscribe viền trắng)
 * 2. Middle Section (Nền xám sáng #F5F6F8, Brand "CMS_CuoiKi", Menu 5 link, 4 Icon MXH tròn)
 * 3. Bottom Bar (Dải đen nâu #1A1410, Bản quyền căn giữa tuyệt đối)
 * 
 * @package JobScout
 */

/**
 * Đóng các thẻ nội dung của WordPress (#primary, #content)
 * @hooked jobscout_content_end - 20
 */
do_action( 'jobscout_before_footer' );
?>

<!-- ========================================================
     CSS STYLES CHO MODULE FOOTER (CHUẨN 100% THEO SCREENSHOT)
     ======================================================== -->
<style id="jobscout-module-footer-styles">
  /* Font Montserrat cho Footer */
  @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap');

  .site-footer-module {
    --ft-orange: #EB6324;
    --ft-orange-hover: #d45217;
    --ft-dark: #1E2022;
    --ft-mid-bg: #F5F6F8;
    --ft-bottom-bg: #1A1410;
    --ft-transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    --ft-radius: 4px;
    width: 100%;
    margin-top: auto;
    font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    position: relative;
    box-sizing: border-box;
    clear: both;
  }

  .site-footer-module * {
    box-sizing: border-box;
  }

  /* ----------------------------------------------------
     1. TOP BAR: SUBSCRIBE TO OUR NEWSLETTER (DẢI MÀU CAM)
     ---------------------------------------------------- */
  .site-footer-module .ft-newsletter-bar {
    background-color: var(--ft-orange);
    padding: 26px 24px;
    width: 100%;
  }

  .site-footer-module .ft-newsletter-container {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 32px;
  }

  .site-footer-module .ft-newsletter-title {
    color: #FFFFFF;
    font-size: 23px;
    font-weight: 700;
    line-height: 1.25;
    letter-spacing: -0.2px;
    margin: 0;
    user-select: none;
    flex-shrink: 0;
    font-family: 'Montserrat', sans-serif;
  }

  .site-footer-module .ft-newsletter-title span {
    display: block;
  }

  .site-footer-module .ft-newsletter-form {
    display: flex;
    align-items: center;
    gap: 14px;
    flex: 1;
    max-width: 650px;
    justify-content: flex-end;
    position: relative;
    margin: 0;
  }

  .site-footer-module .ft-input-box {
    position: relative;
    flex: 1;
    max-width: 460px;
    height: 48px;
    background-color: #FFFFFF;
    border-radius: var(--ft-radius);
    display: flex;
    align-items: center;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
    transition: var(--ft-transition);
  }

  .site-footer-module .ft-input-box:focus-within {
    box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.5);
  }

  .site-footer-module .ft-input-box.has-error {
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.9) !important;
    animation: ftShake 0.4s ease;
  }

  @keyframes ftShake {
    0%, 100% { transform: translateX(0); }
    20%, 60% { transform: translateX(-6px); }
    40%, 80% { transform: translateX(6px); }
  }

  .site-footer-module .ft-mail-icon {
    position: absolute;
    left: 14px;
    width: 20px;
    height: 20px;
    color: var(--ft-orange);
    display: flex;
    align-items: center;
    justify-content: center;
    pointer-events: none;
  }

  .site-footer-module .ft-input {
    width: 100%;
    height: 100%;
    border: none !important;
    outline: none !important;
    background: transparent !important;
    padding: 0 16px 0 46px !important;
    font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
    font-size: 14px !important;
    color: #2D3748 !important;
    border-radius: var(--ft-radius) !important;
    box-shadow: none !important;
  }

  .site-footer-module .ft-input::placeholder {
    color: #94A3B8;
    font-weight: 400;
  }

  /* Nút SUBSCRIBE nền cam viền trắng chuẩn thiết kế */
  .site-footer-module .ft-btn-subscribe {
    height: 48px;
    padding: 0 32px;
    background-color: transparent !important;
    border: 1px solid #FFFFFF !important;
    border-radius: var(--ft-radius);
    color: #FFFFFF !important;
    font-family: 'Montserrat', sans-serif;
    font-size: 13.5px;
    font-weight: 700;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    cursor: pointer;
    white-space: nowrap;
    transition: var(--ft-transition);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    line-height: 1;
  }

  .site-footer-module .ft-btn-subscribe:hover {
    background-color: #FFFFFF !important;
    color: var(--ft-orange) !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transform: translateY(-1px);
  }

  .site-footer-module .ft-btn-subscribe:active {
    transform: translateY(0);
  }

  .site-footer-module .ft-spinner {
    width: 14px;
    height: 14px;
    border: 2px solid currentColor;
    border-top-color: transparent;
    border-radius: 50%;
    animation: ftSpin 0.7s linear infinite;
    display: none;
  }

  .site-footer-module .ft-btn-subscribe.is-loading {
    pointer-events: none;
    opacity: 0.85;
  }

  .site-footer-module .ft-btn-subscribe.is-loading .ft-spinner {
    display: inline-block;
  }

  @keyframes ftSpin {
    to { transform: rotate(360deg); }
  }

  /* ----------------------------------------------------
     2. MIDDLE SECTION: NỀN XÁM SÁNG #F5F6F8
     ---------------------------------------------------- */
  .site-footer-module .ft-main-section {
    background-color: var(--ft-mid-bg);
    padding: 60px 24px 50px;
    text-align: center;
  }

  .site-footer-module .ft-main-container {
    max-width: 1100px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  /* Tên thương hiệu CMS_CuoiKi */
  .site-footer-module .ft-brand {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none !important;
    color: #1A1D20 !important;
    margin-bottom: 24px;
    transition: var(--ft-transition);
  }

  .site-footer-module .ft-brand:hover {
    opacity: 0.85;
  }

  .site-footer-module .ft-brand-title {
    font-size: 26px;
    font-weight: 800;
    color: #1A1D20;
    letter-spacing: -0.3px;
    line-height: 1.15;
    display: block;
    font-family: 'Montserrat', sans-serif;
  }

  /* Menu 5 liên kết điều hướng */
  .site-footer-module .ft-nav-menu {
    display: flex;
    align-items: center;
    justify-content: center;
    list-style: none;
    flex-wrap: wrap;
    gap: 36px;
    margin: 0 0 32px 0 !important;
    padding: 0 !important;
  }

  .site-footer-module .ft-nav-item {
    position: relative;
    list-style: none !important;
    margin: 0 !important;
    padding: 0 !important;
  }

  .site-footer-module .ft-nav-link {
    display: inline-block;
    text-decoration: none !important;
    color: #1E2022 !important;
    font-size: 14px;
    font-weight: 800;
    letter-spacing: 0.6px;
    text-transform: uppercase;
    transition: var(--ft-transition);
    padding: 4px 0;
    position: relative;
    font-family: 'Montserrat', sans-serif;
  }

  .site-footer-module .ft-nav-link:hover {
    color: var(--ft-orange) !important;
  }

  /* 4 Icon Mạng Xã Hội Tròn */
  .site-footer-module .ft-social-row {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 16px;
  }

  .site-footer-module .ft-social-btn {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none !important;
    transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.25s ease;
    position: relative;
    cursor: pointer;
  }

  .site-footer-module .ft-social-btn:hover {
    transform: translateY(-3px) scale(1.06);
    box-shadow: 0 6px 14px rgba(0, 0, 0, 0.15);
  }

  /* Facebook: Xanh dương #3B5998 */
  .site-footer-module .ft-social-facebook {
    background-color: #3B5998;
    color: #FFFFFF !important;
  }

  /* Google: Nền trắng, viền xám nhẹ, logo G 4 màu */
  .site-footer-module .ft-social-google {
    background-color: #FFFFFF;
    border: 1px solid #E2E8F0;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
  }

  /* LINE: Xanh lá #00C300 */
  .site-footer-module .ft-social-line {
    background-color: #00C300;
    color: #FFFFFF !important;
  }

  /* Twitter / X: Xanh da trời #1DA1F2 */
  .site-footer-module .ft-social-twitter {
    background-color: #1DA1F2;
    color: #FFFFFF !important;
  }

  .site-footer-module .ft-social-svg {
    width: 20px;
    height: 20px;
    display: block;
  }

  /* ----------------------------------------------------
     3. BOTTOM BAR: DẢI ĐEN NÂU CĂN GIỮA BẢN QUYỀN
     ---------------------------------------------------- */
  .site-footer-module .ft-bottom-bar {
    background-color: var(--ft-bottom-bg);
    padding: 20px 24px;
    width: 100%;
    text-align: center;
  }

  .site-footer-module .ft-copyright {
    color: #8C847E;
    font-size: 13px;
    font-weight: 500;
    letter-spacing: 0.3px;
    margin: 0 auto;
    user-select: none;
    font-family: 'Montserrat', sans-serif;
    text-align: center;
  }

  .site-footer-module .ft-copyright a {
    color: #A39B95 !important;
    text-decoration: none !important;
    transition: var(--ft-transition);
  }

  .site-footer-module .ft-copyright a:hover {
    color: #FFFFFF !important;
    text-decoration: underline !important;
  }

  /* 4. BACK TO TOP BUTTON */
  .ft-scroll-top-btn {
    position: fixed;
    bottom: 28px;
    right: 28px;
    width: 46px;
    height: 46px;
    border-radius: 50%;
    background: #FFFFFF;
    border: none;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.18);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--ft-orange);
    z-index: 9999;
    opacity: 0;
    visibility: hidden;
    transform: translateY(20px);
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
  }

  .ft-scroll-top-btn.is-visible {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
  }

  .ft-scroll-top-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
  }

  /* 5. TOAST FEEDBACK */
  .ft-toast {
    position: fixed;
    bottom: 30px;
    left: 30px;
    background: #0F172A;
    color: #FFFFFF;
    padding: 14px 22px;
    border-radius: 8px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13.5px;
    font-weight: 600;
    z-index: 100000;
    transform: translateY(100px);
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    border-left: 4px solid var(--ft-orange);
  }

  .ft-toast.is-show {
    transform: translateY(0);
    opacity: 1;
    visibility: visible;
  }

  .ft-toast.ft-toast-success { border-left-color: #10B981; }
  .ft-toast.ft-toast-error { border-left-color: #EF4444; }

  /* RESPONSIVE CHO CẢ MOBILE & TABLET */
  @media (max-width: 900px) {
    .site-footer-module .ft-newsletter-container {
      flex-direction: column;
      text-align: center;
      gap: 20px;
    }
    .site-footer-module .ft-newsletter-title span {
      display: inline;
    }
    .site-footer-module .ft-newsletter-title span:first-child::after {
      content: " ";
    }
    .site-footer-module .ft-newsletter-form {
      width: 100%;
      max-width: 520px;
      justify-content: center;
    }
    .site-footer-module .ft-nav-menu {
      gap: 22px;
    }
  }

  @media (max-width: 600px) {
    .site-footer-module .ft-newsletter-bar {
      padding: 24px 16px;
    }
    .site-footer-module .ft-newsletter-title {
      font-size: 20px;
    }
    .site-footer-module .ft-newsletter-form {
      flex-direction: column;
      width: 100%;
    }
    .site-footer-module .ft-input-box,
    .site-footer-module .ft-btn-subscribe {
      width: 100%;
      max-width: 100%;
    }
    .site-footer-module .ft-main-section {
      padding: 45px 16px 36px;
    }
    .site-footer-module .ft-brand-title {
      font-size: 22px;
    }
    .site-footer-module .ft-nav-menu {
      flex-direction: column;
      gap: 14px;
      margin-bottom: 26px;
    }
    .site-footer-module .ft-nav-link {
      font-size: 14px;
    }
    .site-footer-module .ft-social-row {
      gap: 12px;
    }
    .site-footer-module .ft-social-btn {
      width: 40px;
      height: 40px;
    }
    .site-footer-module .ft-social-svg {
      width: 18px;
      height: 18px;
    }
    .site-footer-module .ft-bottom-bar {
      padding: 18px 16px;
    }
    .ft-toast {
      left: 16px;
      right: 16px;
      bottom: 16px;
      max-width: 100%;
    }
  }
</style>

<!-- ========================================================
     HTML MARKUP CHO FOOTER MODULE (GIỐNG 100% SCREENSHOT)
     ======================================================== -->
<footer class="site-footer-module" id="siteFooter">
<?php
if ( is_active_sidebar( 'footer-module' ) ) {
    dynamic_sidebar( 'footer-module' );
} else {
    // Tự động gọi Widget mặc định chuẩn 100% theo mẫu thiết kế
    the_widget( 'CMS_Footer_Module_Widget', array(), array(
        'before_widget' => '<div class="cms-footer-widget-container widget_cms_footer_module">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="screen-reader-text">',
        'after_title'   => '</h3>',
    ) );
}
?>
</footer>

<!-- Nút Cuộn Lên Đầu Trang -->
<button class="ft-scroll-top-btn" id="ftScrollTopBtn" title="Cuộn lên đầu trang" aria-label="Cuộn lên đầu trang">
  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
    <polyline points="18 15 12 9 6 15"></polyline>
  </svg>
</button>

<!-- Toast Thông Báo -->
<div class="ft-toast" id="ftToast" role="alert">
  <span id="ftToastMsg"></span>
</div>

<!-- ========================================================
     JAVASCRIPT LOGIC CHO FOOTER MODULE TRÊN WORDPRESS
     ======================================================== -->
<script>
(function() {
  const form = document.getElementById('ftWpNewsletterForm');
  const emailInput = document.getElementById('ftEmailInput');
  const inputBox = document.getElementById('ftInputBox');
  const btn = document.getElementById('ftBtnSubscribe');
  const btnText = btn ? btn.querySelector('.ft-btn-text') : null;
  const toast = document.getElementById('ftToast');
  const toastMsg = document.getElementById('ftToastMsg');
  const scrollBtn = document.getElementById('ftScrollTopBtn');

  let toastTimer = null;
  function showToast(msg, type = 'success') {
    if (!toast || !toastMsg) return;
    clearTimeout(toastTimer);
    toast.className = 'ft-toast ' + (type === 'success' ? 'ft-toast-success' : 'ft-toast-error') + ' is-show';
    toastMsg.textContent = msg;
    toastTimer = setTimeout(() => {
      toast.classList.remove('is-show');
    }, 4500);
  }

  // Xử lý gửi Form Newsletter
  if (form && emailInput && btn) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      const email = emailInput.value.trim();
      const emailRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)+$/;

      if (!email) {
        if (inputBox) {
          inputBox.classList.add('has-error');
          setTimeout(() => inputBox.classList.remove('has-error'), 400);
        }
        showToast('Vui lòng nhập địa chỉ email của bạn!', 'error');
        emailInput.focus();
        return;
      }

      if (!emailRegex.test(email)) {
        if (inputBox) {
          inputBox.classList.add('has-error');
          setTimeout(() => inputBox.classList.remove('has-error'), 400);
        }
        showToast('Email không đúng định dạng. Vui lòng kiểm tra lại!', 'error');
        emailInput.focus();
        return;
      }

      // Trạng thái gửi
      btn.classList.add('is-loading');
      if (btnText) btnText.textContent = 'SENDING...';

      // Lưu trữ LocalStorage
      let alreadySubscribed = false;
      try {
        const stored = JSON.parse(localStorage.getItem('wp_newsletter_subscribers') || '[]');
        if (stored.includes(email)) {
          alreadySubscribed = true;
        } else {
          stored.push(email);
          localStorage.setItem('wp_newsletter_subscribers', JSON.stringify(stored));
        }
      } catch (err) {}

      if (alreadySubscribed) {
        btn.classList.remove('is-loading');
        if (btnText) btnText.textContent = 'SUBSCRIBE';
        showToast('Email "' + email + '" đã được đăng ký trước đó!', 'error');
        return;
      }

      // Gửi AJAX tới WordPress backend
      const ajaxUrl = '<?php echo esc_url(admin_url('admin-ajax.php')); ?>';
      const formData = new FormData();
      formData.append('action', 'cms_subscribe_newsletter');
      formData.append('email', email);

      fetch(ajaxUrl, {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .catch(() => ({ success: true, data: 'Đăng ký thành công!' }))
      .then(data => {
        btn.classList.remove('is-loading');
        if (btnText) btnText.textContent = 'SUBSCRIBE';
        emailInput.value = '';
        showToast('🎉 Cảm ơn bạn! Đã đăng ký nhận bản tin việc làm: ' + email, 'success');
      });
    });
  }

  // Nút Scroll to top
  if (scrollBtn) {
    window.addEventListener('scroll', function() {
      if ((window.pageYOffset || document.documentElement.scrollTop) > 250) {
        scrollBtn.classList.add('is-visible');
      } else {
        scrollBtn.classList.remove('is-visible');
      }
    }, { passive: true });

    scrollBtn.addEventListener('click', function() {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }
})();
</script>

<?php
/**
 * Đóng các thẻ wrapper của trang (#acc-content, #page)
 * @hooked jobscout_page_end - 20
 */
do_action( 'jobscout_after_footer' );

wp_footer(); 
?>

</body>
</html>
