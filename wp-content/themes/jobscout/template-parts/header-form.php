<?php
/**
 * Custom job search form for homepage (Vietnamese)
 *
 * @package JobScout
 */
$find_a_job_link = get_option( 'job_manager_jobs_page_id', 0 );
$post_slug       = get_post_field( 'post_name', $find_a_job_link );
$ed_job_category = get_option( 'job_manager_enable_categories' );  

if( $post_slug ){
    $action_page =  home_url( '/'. $post_slug );
}else {
    $action_page =  home_url( '/' );
}
?>

<div class="job_listings">

  <form class="jobscout_job_filters" method="GET" action="<?php echo esc_url( $action_page ) ?>">
    <div class="search_jobs">

      <div class="search_keywords">
        <label for="search_keywords"><?php esc_html_e( 'Từ khóa', 'jobscout' ); ?></label>
        <input type="text" id="search_keywords" name="search_keywords" placeholder="<?php esc_attr_e( 'Nhập tên công việc, kỹ năng...', 'jobscout' ); ?>">
      </div>

      <div class="search_location">
        <label for="search_location"><?php esc_html_e( 'Địa điểm', 'jobscout' ); ?></label>
        <input type="text"  id="search_location" name="search_location" placeholder="<?php esc_attr_e( 'Nhập thành phố, địa điểm...', 'jobscout' ); ?>">
      </div>
      
      <div class="search_submit">
        <button type="submit" class="banner-search-submit-btn" title="Tìm kiếm">
          <svg width="16" height="16" viewBox="0 0 512 512" fill="#ffffff"><path d="M508.5 468.9L387.1 347.5c-2.3-2.3-5.3-3.5-8.5-3.5h-13.2c31.5-36.5 50.6-84 50.6-136C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c52 0 99.5-19.1 136-50.6v13.2c0 3.2 1.3 6.2 3.5 8.5l121.4 121.4c4.7 4.7 12.3 4.7 17 0l22.6-22.6c4.7-4.7 4.7-12.3 0-17zM208 368c-88.4 0-160-71.6-160-160S119.6 48 208 48s160 71.6 160 160-71.6 160-160 160z"/></svg>
        </button>
      </div>

    </div>
  </form>

</div>
