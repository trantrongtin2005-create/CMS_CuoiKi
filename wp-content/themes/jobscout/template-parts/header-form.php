<?php
/**
 * Custom job search form for homepage (Hero Banner Search Bar)
 * Thiết kế chuẩn 100% theo mẫu:
 * 1. Ô tìm kiếm từ khóa với icon kính lúp màu cam: "Search for jobs, companies, skills"
 * 2. Ô chọn địa điểm với icon ghim màu cam: "Tokyo" có mũi tên v
 * 3. Nút bấm tìm kiếm màu cam nổi bật: "SEARCH JOB"
 *
 * @package JobScout
 */

$find_a_job_link = get_option( 'job_manager_jobs_page_id', 0 );
$post_slug       = get_post_field( 'post_name', $find_a_job_link );

if( $post_slug ){
    $action_page = home_url( '/' . $post_slug );
} else {
    $action_page = home_url( '/jobs/' );
}

$keyword_placeholder = get_theme_mod( 'banner_keyword_placeholder', 'Tìm kiếm vị trí, công ty, kỹ năng...' );
$location_default    = get_theme_mod( 'banner_location_default', 'Hà Nội' );
$button_label        = get_theme_mod( 'banner_btn_text', 'TÌM VIỆC NGAY' );
$locations_raw       = get_theme_mod( 'banner_locations_list', "Hà Nội\nTP. Hồ Chí Minh\nĐà Nẵng\nTokyo\nOsaka\nKyoto\nYokohama\nNagoya\nFukuoka\nSapporo" );

$locations_list = array_filter( array_map( 'trim', explode( "\n", str_replace( "\r", "", $locations_raw ) ) ) );
if ( empty( $locations_list ) ) {
    $locations_list = array( 'Hà Nội', 'TP. Hồ Chí Minh', 'Đà Nẵng', 'Tokyo', 'Osaka', 'Kyoto', 'Yokohama', 'Nagoya', 'Fukuoka', 'Sapporo' );
}
?>

<div class="hero-dream-search-box">
  <form class="hero-dream-search-form" method="GET" action="<?php echo esc_url( $action_page ); ?>">
    <div class="hero-dream-search-row">

      <!-- 1. Ô TÌM KIẾM TỪ KHÓA: Search for jobs, companies, skills -->
      <div class="hero-input-group hero-input-group-keyword">
        <span class="hero-icon hero-icon-search" aria-hidden="true">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#EB6324" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
        </span>
        <input 
          type="text" 
          id="search_keywords" 
          name="search_keywords" 
          class="hero-input-field" 
          placeholder="<?php echo esc_attr( $keyword_placeholder ); ?>" 
          autocomplete="off"
          aria-label="<?php echo esc_attr( $keyword_placeholder ); ?>"
        >
      </div>

      <!-- 2. Ô CHỌN ĐỊA ĐIỂM: Tokyo (Icon ghim cam + 1 mũi tên v duy nhất) -->
      <div class="hero-input-group hero-input-group-location">
        <span class="hero-icon hero-icon-pin" aria-hidden="true">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#EB6324" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
            <circle cx="12" cy="10" r="3"></circle>
          </svg>
        </span>
        <select 
          id="search_location" 
          name="search_location" 
          class="hero-input-field hero-input-location" 
          aria-label="Chọn địa điểm"
        >
          <?php foreach ( $locations_list as $loc ) : ?>
            <option value="<?php echo esc_attr( $loc ); ?>" <?php selected( $location_default, $loc ); ?>>
              <?php echo esc_html( $loc ); ?>
            </option>
          <?php endforeach; ?>
          <option value="" <?php selected( $location_default, '' ); ?>>Tất cả địa điểm</option>
        </select>
        <span class="hero-chevron" aria-hidden="true">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="6 9 12 15 18 9"></polyline>
          </svg>
        </span>
      </div>

      <!-- 3. NÚT TÌM KIẾM: SEARCH JOB (Màu cam #EB6324) -->
      <div class="hero-search-submit-wrap">
        <button type="submit" class="hero-btn-search">
          <?php echo esc_html( $button_label ); ?>
        </button>
      </div>

    </div>
  </form>
</div>
