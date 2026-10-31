<?php
/**
 * Template Name: Danh sách việc làm (Jobs Listing)
 *
 * Giao diện hiển thị trang "Danh sách việc làm" (Jobs Listing) tương thích 100% trình soạn thảo Gutenberg Block Editor.
 * Cho phép người quản trị dễ dàng tự chỉnh sửa mọi nội dung văn bản, thay thế hình ảnh và thay đổi vị trí qua lại giữa tất cả các khối nội dung trực tiếp trong wp-admin.
 *
 * @package JobScout
 */

get_header(); ?>

<!-- ========================================================
     CSS CHO TRANG JOBS LISTING (TƯƠNG THÍCH GUTENBERG BLOCK EDITOR)
     ======================================================== -->
<style id="jobscout-page-jobs-styles">
  @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap');

  /* 1. Ẩn Breadcrumbs/Page-Header mặc định và widget sidebar */
  .page-template-page-jobs .breadcrumb-wrap,
  .page-jobs .breadcrumb-wrap,
  .post-type-archive-job_listing .breadcrumb-wrap,
  .page-template-page-jobs .page-header,
  .page-jobs .page-header,
  .post-type-archive-job_listing .page-header,
  .page-template-page-jobs .entry-header,
  .page-jobs .entry-header,
  .post-type-archive-job_listing .entry-header,
  .page-template-page-jobs .entry-title,
  .page-jobs .entry-title,
  .page-template-page-jobs .edit-link,
  .page-jobs .edit-link,
  .page-template-page-jobs .entry-footer,
  .page-jobs .entry-footer,
  .page-template-page-jobs #secondary,
  .page-jobs #secondary,
  .post-type-archive-job_listing #secondary {
    display: none !important;
  }

  /* 2. Tràn viền 100% cho khung nội dung chính */
  body.page-template-page-jobs,
  body.post-type-archive-job_listing,
  .page-template-page-jobs #page,
  .post-type-archive-job_listing #page,
  .page-template-page-jobs #content,
  .post-type-archive-job_listing #content,
  .page-template-page-jobs .site-content,
  .post-type-archive-job_listing .site-content,
  .page-template-page-jobs .site-main,
  .post-type-archive-job_listing .site-main {
    background-color: #F5F5F5 !important;
  }

  .page-template-page-jobs .site-content,
  .page-jobs .site-content,
  .post-type-archive-job_listing .site-content {
    margin-top: 0 !important;
    margin-bottom: 0 !important;
    padding: 0 !important;
    max-width: 100% !important;
    width: 100% !important;
  }

  .page-template-page-jobs .site-content > .container,
  .page-jobs .site-content > .container,
  .post-type-archive-job_listing .site-content > .container {
    max-width: 100% !important;
    width: 100% !important;
    padding: 0 !important;
    margin: 0 !important;
  }

  .page-template-page-jobs #primary,
  .page-jobs #primary,
  .post-type-archive-job_listing #primary {
    width: 100% !important;
    max-width: 100% !important;
    float: none !important;
    margin: 0 !important;
    padding: 0 !important;
    background: transparent !important;
  }

  /* 3. Reset wrapper font chữ & layout chuẩn cho Jobs page */
  .jobs-page-wrapper {
    font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    color: #333333;
    line-height: 1.6;
    background-color: #F5F5F5;
    overflow-x: hidden;
    padding-bottom: 70px;
  }

  /* 4. Khối Cover Banner Hero (Chỉnh sửa ảnh và chữ thoải mái trong Gutenberg) */
  .jobs-page-wrapper .jobs-hero-block.wp-block-cover,
  .jobs-page-wrapper .wp-block-cover.alignfull,
  .jobs-page-wrapper .wp-block-cover {
    min-height: 380px;
    padding: 60px 20px;
    position: relative;
    background-position: center 52%;
    background-size: cover;
    box-sizing: border-box;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
  }

  .jobs-page-wrapper .wp-block-cover .wp-block-cover__image-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    z-index: 0;
  }

  .jobs-page-wrapper .wp-block-cover .wp-block-cover__background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
  }

  .jobs-page-wrapper .jobs-hero-block .wp-block-cover__inner-container,
  .jobs-page-wrapper .wp-block-cover .wp-block-cover__inner-container {
    max-width: 900px;
    margin: 0 auto;
    text-align: center;
    position: relative;
    z-index: 2;
    padding: 10px;
    box-sizing: border-box;
  }

  .jobs-page-wrapper .jobs-banner-title,
  .jobs-page-wrapper .jobs-hero-block h1,
  .jobs-page-wrapper .jobs-hero-block .wp-block-heading,
  .jobs-page-wrapper .wp-block-cover h1,
  .jobs-page-wrapper .wp-block-cover .wp-block-heading {
    font-family: 'Montserrat', sans-serif !important;
    font-size: clamp(26px, 3.8vw, 40px) !important;
    font-weight: 800 !important;
    letter-spacing: 2px !important;
    text-transform: uppercase !important;
    color: #ffffff !important;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.65);
    margin: 0 0 10px 0 !important;
    line-height: 1.25 !important;
    text-align: center;
  }

  .jobs-page-wrapper .jobs-banner-subtitle,
  .jobs-page-wrapper .jobs-hero-block p,
  .jobs-page-wrapper .wp-block-cover p {
    font-size: 16px !important;
    color: rgba(255, 255, 255, 0.95) !important;
    max-width: 720px;
    margin: 0 auto !important;
    text-align: center;
    line-height: 1.6 !important;
    text-shadow: 0 1px 4px rgba(0, 0, 0, 0.5);
    font-weight: 500;
  }

  /* 5. Căn lề và độ rộng cho các khối tiêu chuẩn */
  .jobs-page-wrapper > *:not(.alignfull):not(.wp-block-cover),
  .jobs-page-wrapper .wp-block-group:not(.alignfull),
  .jobs-page-wrapper .wp-block-columns:not(.alignfull),
  .jobs-page-wrapper .jobs-heading-section {
    max-width: 1200px !important;
    margin-left: auto !important;
    margin-right: auto !important;
    padding-left: 20px;
    padding-right: 20px;
    box-sizing: border-box;
  }

  .jobs-page-wrapper .wp-block-buttons {
    display: flex;
    gap: 12px;
    margin: 16px 0;
  }

  .jobs-page-wrapper .wp-block-button__link {
    background-color: #E87722 !important;
    color: #ffffff !important;
    font-weight: 700 !important;
    border-radius: 4px !important;
    padding: 12px 28px !important;
    font-family: inherit !important;
    text-decoration: none !important;
    display: inline-block !important;
    transition: background 0.2s ease !important;
  }

  .jobs-page-wrapper .wp-block-button__link:hover {
    background-color: #C2410C !important;
  }

  /* 6. Tiêu đề khối & Mô tả */
  .jobs-section-heading {
    font-size: 26px !important;
    font-weight: 800 !important;
    letter-spacing: 1px !important;
    text-transform: uppercase !important;
    color: #1E293B !important;
    margin: 0 0 6px 0 !important;
    line-height: 1.3 !important;
  }

  .jobs-section-desc {
    font-size: 15px !important;
    color: #64748B !important;
    margin: 0 0 24px 0 !important;
    line-height: 1.6 !important;
  }

  /* ========================================================
     7. CSS CHO KHỐI DANH SÁCH VIỆC LÀM TƯƠNG TÁC (TÌM KIẾM & BỘ LỌC)
     ======================================================== */
  .aj-page-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px 20px 40px;
    box-sizing: border-box;
    color: #333333;
  }

  .aj-search-filter-card {
    background: #ffffff;
    border-radius: 4px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    padding: 24px 28px;
    margin-bottom: 28px;
  }

  .aj-search-row {
    display: flex;
    gap: 12px;
    align-items: center;
    flex-wrap: wrap;
  }

  .aj-search-field {
    position: relative;
    display: flex;
    align-items: center;
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 4px;
    transition: border-color 0.2s, background 0.2s;
  }

  .aj-search-field:focus-within {
    border-color: #E87722;
    background: #ffffff;
  }

  .aj-field-icon {
    position: absolute;
    left: 12px;
    display: flex;
    align-items: center;
    pointer-events: none;
  }

  .aj-field-icon svg {
    width: 18px;
    height: 18px;
  }

  .aj-search-field input,
  .aj-search-field select {
    width: 100%;
    height: 44px;
    border: none;
    background: transparent;
    padding: 0 14px 0 40px;
    font-size: 14px;
    color: #1e293b;
    outline: none;
    font-family: inherit;
    box-sizing: border-box;
  }

  .aj-search-field select {
    appearance: none;
    cursor: pointer;
    padding-right: 32px;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 14px;
  }

  .aj-field-keyword { flex: 2 1 280px; }
  .aj-field-location { flex: 1.2 1 180px; }
  .aj-field-category { flex: 1.4 1 200px; }

  .aj-input-clear {
    position: absolute;
    right: 10px;
    background: none;
    border: none;
    font-size: 18px;
    color: #94a3b8;
    cursor: pointer;
    padding: 4px;
    line-height: 1;
  }
  .aj-input-clear:hover { color: #1e293b; }

  .aj-search-btn {
    height: 44px;
    padding: 0 28px;
    background: #E87722;
    color: #ffffff;
    border: none;
    border-radius: 4px;
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    cursor: pointer;
    transition: background 0.2s;
    font-family: inherit;
    flex-shrink: 0;
  }
  .aj-search-btn:hover {
    background: #c2410c;
  }

  .aj-quick-filters-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-top: 18px;
    padding-top: 16px;
    border-top: 1px solid #f1f5f9;
    flex-wrap: wrap;
  }

  .aj-qf-group {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
  }

  .aj-qf-label {
    font-size: 13px;
    font-weight: 700;
    color: #64748b;
    margin-right: 2px;
  }

  .aj-qf-pill {
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #475569;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    font-family: inherit;
  }

  .aj-qf-pill:hover {
    border-color: #cbd5e1;
    background: #f8fafc;
  }

  .aj-qf-pill.active {
    border-color: #E87722;
    background: #fff7ed;
    color: #E87722;
    font-weight: 700;
  }

  .aj-active-filters-bar {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 14px;
    padding-top: 12px;
    border-top: 1px dashed #e2e8f0;
    flex-wrap: wrap;
  }

  .aj-af-label {
    font-size: 12px;
    font-weight: 700;
    color: #E87722;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .aj-af-tags {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
  }

  .aj-af-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #fff7ed;
    border: 1px solid #fed7aa;
    color: #c2410c;
    padding: 3px 10px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
  }

  .aj-af-tag-remove {
    background: none;
    border: none;
    color: #c2410c;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    padding: 0;
    line-height: 1;
  }
  .aj-af-tag-remove:hover { color: #9a3412; }

  .aj-af-clear-all {
    background: none;
    border: none;
    color: #94a3b8;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: underline;
    margin-left: 6px;
    padding: 0;
  }
  .aj-af-clear-all:hover { color: #ef4444; }

  .aj-top-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    gap: 16px;
    flex-wrap: wrap;
  }

  .aj-top-left {
    display: flex;
    align-items: baseline;
    gap: 12px;
    flex-wrap: wrap;
  }

  .aj-heading {
    font-size: 24px;
    font-weight: 800;
    color: #1a1a1a;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    margin: 0;
    line-height: 1;
  }

  .aj-count-badge {
    font-size: 14px;
    font-weight: 600;
    color: #64748b;
  }

  .aj-top-right {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .aj-sort-select {
    height: 38px;
    border: 1px solid #d1d5db;
    border-radius: 4px;
    background: #ffffff;
    padding: 0 32px 0 14px;
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    cursor: pointer;
    outline: none;
    font-family: inherit;
    appearance: none;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%234b5563' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 10px center;
    background-size: 14px;
    transition: border-color 0.2s;
  }

  .aj-sort-select:focus {
    border-color: #E87722;
  }

  .aj-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 22px;
  }

  .aj-job-card {
    background: #ffffff;
    border: none;
    border-radius: 4px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    padding: 24px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    min-height: 240px;
    box-sizing: border-box;
    text-decoration: none;
    color: inherit;
    cursor: pointer;
    animation: ajCardFadeIn 0.3s ease forwards;
  }

  @keyframes ajCardFadeIn {
    from { opacity: 0; transform: translateY(8px); }
    to { opacity: 1; transform: translateY(0); }
  }

  .aj-job-card:hover {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    transform: translateY(-3px);
  }

  .aj-job-card-top {
    display: flex;
    align-items: flex-start;
    gap: 16px;
  }

  .aj-job-card-logo {
    width: 80px;
    height: 80px;
    min-width: 80px;
    flex: 0 0 80px;
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    padding: 6px;
    box-sizing: border-box;
    transition: border-color 0.2s;
  }

  .aj-job-card:hover .aj-job-card-logo {
    border-color: #cbd5e1;
  }

  .aj-job-card-logo img {
    max-width: 92%;
    max-height: 92%;
    width: 100%;
    height: 100%;
    object-fit: contain;
    display: block;
    margin: auto;
  }

  .aj-job-card-info {
    flex: 1 1 auto;
    min-width: 0;
  }

  .aj-job-card-name {
    font-size: 15px;
    font-weight: 700;
    color: #1e293b;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    line-height: 1.35;
    margin: 0 0 6px 0;
    word-break: break-word;
    transition: color 0.2s;
  }

  .aj-job-card:hover .aj-job-card-name {
    color: #E87722;
  }

  .aj-job-card-date {
    font-size: 12px;
    color: #94a3b8;
    margin-bottom: 8px;
    font-weight: 500;
  }

  .aj-job-card-tags {
    display: flex;
    align-items: center;
    gap: 5px;
    flex-wrap: nowrap;
    overflow: hidden;
  }

  .aj-tag {
    display: inline-block;
    background: #f3f4f6;
    border: 1px solid #e5e7eb;
    border-radius: 3px;
    padding: 2px 7px;
    font-size: 11px;
    color: #4b5563;
    font-weight: 500;
    white-space: nowrap;
    flex-shrink: 0;
    line-height: 1.35;
  }

  .aj-job-card-bottom {
    margin-top: 14px;
  }

  .aj-job-card-bullets {
    margin: 0;
    padding: 0;
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: 6px;
  }

  .aj-job-card-bullets li {
    position: relative;
    padding-left: 15px;
    font-size: 13px;
    color: #4b5563;
    line-height: 1.5;
    margin: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
  }

  .aj-job-card-bullets li::before {
    content: "";
    position: absolute;
    left: 2px;
    top: 7px;
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background-color: #4b5563;
  }

  .aj-load-more-wrap {
    margin-top: 45px;
    text-align: center;
  }

  .aj-load-more-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 12px 36px;
    border: 1.5px solid #E87722;
    border-radius: 4px;
    background: #ffffff;
    color: #E87722;
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.2s ease;
    font-family: inherit;
  }

  .aj-load-more-btn:hover {
    background: #E87722;
    color: #ffffff;
    box-shadow: 0 4px 14px rgba(232, 119, 34, 0.25);
  }

  .aj-empty-state {
    background: #ffffff;
    border-radius: 4px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    padding: 60px 30px;
    text-align: center;
    max-width: 600px;
    margin: 30px auto 40px;
  }

  .aj-empty-icon {
    margin-bottom: 18px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: #f1f5f9;
    margin: 0 auto 16px;
  }

  .aj-empty-title {
    font-size: 18px;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 8px 0;
  }

  .aj-empty-desc {
    font-size: 14px;
    color: #64748b;
    margin: 0 0 20px 0;
  }

  .aj-empty-reset-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 10px 24px;
    background: #E87722;
    color: #ffffff;
    border: none;
    border-radius: 4px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.2s;
    font-family: inherit;
  }

  .aj-empty-reset-btn:hover {
    background: #c2410c;
  }

  /* Responsive Mobile & Tablet */
  @media (max-width: 960px) {
    .aj-grid { grid-template-columns: 1fr; }
    .aj-quick-filters-row { flex-direction: column; align-items: flex-start; }
    .aj-search-row { flex-direction: column; }
    .aj-search-field, .aj-search-btn { width: 100%; }
    .jobs-page-wrapper .jobs-hero-block.wp-block-cover { min-height: 280px; }
    .jobs-page-wrapper .wp-block-columns { flex-direction: column !important; gap: 24px !important; }
    .jobs-page-wrapper .wp-block-column { flex: 1 1 100% !important; width: 100% !important; }
  }
</style>

<div class="jobs-page-wrapper">
<?php
$jobs_page_id = get_option( 'job_manager_jobs_page_id', 986 );
if ( ! $jobs_page_id ) {
	$jobs_page = get_page_by_path( 'jobs' );
	$jobs_page_id = $jobs_page ? $jobs_page->ID : 986;
}

if ( is_page() ) {
	while ( have_posts() ) : the_post();
		$content = get_the_content();
		echo apply_filters( 'the_content', $content );

		if ( false === strpos( $content, '[jobs]' ) && false === strpos( $content, '[jobscout_jobs]' ) ) {
			echo do_shortcode( '[jobs]' );
		}
	endwhile;
} elseif ( $jobs_page_id && ( $jobs_post = get_post( $jobs_page_id ) ) ) {
	$GLOBALS['post'] = $jobs_post;
	setup_postdata( $jobs_post );
	$content = $jobs_post->post_content;
	echo apply_filters( 'the_content', $content );

	if ( false === strpos( $content, '[jobs]' ) && false === strpos( $content, '[jobscout_jobs]' ) ) {
		echo do_shortcode( '[jobs]' );
	}
	wp_reset_postdata();
} else {
	while ( have_posts() ) : the_post();
		the_content();
	endwhile;
}
?>
</div><!-- .jobs-page-wrapper -->

<?php
get_footer();
