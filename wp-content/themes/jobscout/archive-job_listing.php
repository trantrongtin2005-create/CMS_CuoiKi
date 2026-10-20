<?php
/**
 * The template for displaying Job Listings Archive
 * Matched 100% to "4-all job 2.jpg" & inherited Flat Design from Job Detail
 *
 * Features:
 * 1. Hero Banner: Dark overlay with centered bold "CAREER WITH US".
 * 2. Flat UI: Background #F5F5F5, borderless white cards, crisp 4px radius, #E87722 brand color.
 * 3. Search & Filter Bar:
 *    - Main row: Keyword search, Location dropdown, Category dropdown, Search button (#E87722).
 *    - Quick filters row: Work Type (Full-time, Part-time, Remote), Salary (<15M, 15-30M, >30M), Clear Filters button.
 * 4. All Jobs Header:
 *    - "ALL JOBS (Found X jobs)" on the left.
 *    - Sort dropdown on the right ("Latest Jobs", "Highest Salary", "Oldest").
 * 5. 2-Column Job Grid:
 *    - 100% sync with Job Detail OTHER JOBS card (subtle logo border, 3 badges on 1 row anti-wrap, bullet points).
 * 6. Realtime / Instant Search & Filtering with smooth animation.
 * 7. Empty State with "Đặt lại bộ lọc" button.
 * 8. "LOAD MORE JOBS" pagination (6 items per batch).
 * 9. Newsletter Section & Footer matching design.
 *
 * @package JobScout
 */

get_header();

// Fetch all published jobs from DB
$all_jobs_query = new WP_Query( array(
	'post_type'      => 'job_listing',
	'post_status'    => 'publish',
	'posts_per_page' => -1,
	'orderby'        => 'date',
	'order'          => 'DESC',
) );

$jobs_dataset = array();

if ( $all_jobs_query->have_posts() ) {
	while ( $all_jobs_query->have_posts() ) {
		$all_jobs_query->the_post();
		$jid      = get_the_ID();
		$title    = get_the_title();
		$company  = get_post_meta( $jid, '_company_name', true );
		$salary   = get_post_meta( $jid, '_job_salary', true );
		$location = get_the_job_location( $jid );
		$types    = wpjm_get_the_job_types( $jid );
		$cats     = get_the_terms( $jid, 'job_listing_category' );

		// Parse Salary
		$min_sal = 0;
		$max_sal = 0;
		if ( ! empty( $salary ) && preg_match_all( '/(\d{1,3}(?:\.\d{3})+)/', $salary, $sal_matches ) ) {
			if ( ! empty( $sal_matches[1] ) ) {
				$nums    = array_map( function( $v ) { return (int) str_replace( '.', '', $v ); }, $sal_matches[1] );
				$min_sal = min( $nums );
				$max_sal = max( $nums );
			}
		}

		// Logo resolution
		if ( has_post_thumbnail( $jid ) ) {
			$logo = get_the_post_thumbnail_url( $jid, 'thumbnail' );
		} else {
			$logo = get_post_meta( $jid, '_company_logo', true );
		}
		if ( empty( $logo ) || false !== stripos( $title, 'An Toàn Thông Tin' ) || false !== stripos( $company, 'Viettel' ) ) {
			$logo = home_url( '/wp-content/uploads/2026/10/viettel-logo.svg' );
		}
		if ( empty( $logo ) ) {
			$logo = get_template_directory_uri() . '/images/company-default.svg';
		}
		if ( function_exists( 'jobscout_normalize_media_host' ) ) {
			$logo = jobscout_normalize_media_host( $logo );
		}

		// Type
		$type_label = 'Toàn thời gian';
		$type_slug  = 'toan-thoi-gian';
		if ( ! empty( $types ) ) {
			$first_t    = reset( $types );
			$type_label = $first_t->name;
			$type_slug  = $first_t->slug;
		}

		// Category
		$cat_label = 'Công nghệ thông tin';
		$cat_slug  = 'cong-nghe-thong-tin';
		if ( ! empty( $cats ) && ! is_wp_error( $cats ) ) {
			$first_c   = reset( $cats );
			$cat_label = $first_c->name;
			$cat_slug  = $first_c->slug;
		}

		// Location
		$loc_label = ! empty( $location ) ? trim( strip_tags( $location ) ) : 'Hồ Chí Minh';
		$loc_code  = 'hcm';
		if ( false !== stripos( $loc_label, 'Hà Nội' ) || false !== stripos( $loc_label, 'Ha Noi' ) ) {
			$loc_code  = 'hn';
			$loc_label = 'Hà Nội';
		} elseif ( false !== stripos( $loc_label, 'Đà Nẵng' ) || false !== stripos( $loc_label, 'Da Nang' ) ) {
			$loc_code  = 'dn';
			$loc_label = 'Đà Nẵng';
		} else {
			$loc_label = 'Hồ Chí Minh';
		}

		// 3 Bullets
		$raw_content = get_the_content();
		$bullets     = array();
		if ( preg_match_all( '/<li[^>]*>(.*?)<\/li>/is', $raw_content, $bm ) ) {
			foreach ( $bm[1] as $b ) {
				$bc = trim( strip_tags( $b ) );
				if ( ! empty( $bc ) ) {
					$bullets[] = $bc;
					if ( count( $bullets ) >= 3 ) break;
				}
			}
		}
		$def_bullets = array(
			'Đảm bảo chất lượng công việc và hoàn thành xuất sắc các mục tiêu được giao.',
			'Môi trường làm việc năng động, chuyên nghiệp, chế độ đãi ngộ hấp dẫn.',
			'Chế độ bảo hiểm toàn diện và các khóa đào tạo nâng cao kỹ năng thường niên.',
		);
		while ( count( $bullets ) < 3 ) {
			$bullets[] = array_shift( $def_bullets );
		}

		$jobs_dataset[] = array(
			'id'             => $jid,
			'title'          => html_entity_decode( $title, ENT_QUOTES, 'UTF-8' ),
			'permalink'      => get_permalink( $jid ),
			'company'        => html_entity_decode( $company ?: $title, ENT_QUOTES, 'UTF-8' ),
			'logo'           => $logo,
			'date_formatted' => get_the_date( 'M d, Y', $jid ),
			'date_timestamp' => get_post_time( 'U', true, $jid ),
			'salary_raw'     => $salary,
			'min_sal'        => $min_sal,
			'max_sal'        => $max_sal,
			'type_label'     => $type_label,
			'type_slug'      => $type_slug,
			'cat_label'      => html_entity_decode( $cat_label, ENT_QUOTES, 'UTF-8' ),
			'cat_slug'       => $cat_slug,
			'loc_label'      => $loc_label,
			'loc_code'       => $loc_code,
			'bullets'        => $bullets,
			'search_text'    => mb_strtolower( $title . ' ' . $company . ' ' . $cat_label . ' ' . $loc_label . ' ' . strip_tags( $raw_content ), 'UTF-8' ),
		);
	}
	wp_reset_postdata();
}
?>

<!-- ============================================================
     CSS: All Jobs Page — Matched 100% to "4-all job 2.jpg"
     ============================================================ -->
<style id="aj-archive-styles">
	@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap');

	/* 1. Global Reset & Flat Background #F5F5F5 */
	body.post-type-archive-job_listing,
	body.page-template-page-jobs,
	.post-type-archive-job_listing #page,
	.post-type-archive-job_listing #content,
	.post-type-archive-job_listing .site-content,
	.post-type-archive-job_listing .site-main,
	.page-template-page-jobs #page,
	.page-template-page-jobs #content,
	.page-template-page-jobs .site-content,
	.page-template-page-jobs .site-main {
		background-color: #F5F5F5 !important;
	}

	.post-type-archive-job_listing .site-content > header.entry-header,
	.post-type-archive-job_listing .site-content > header.page-header,
	.page-template-page-jobs .site-content > header.entry-header {
		display: none !important;
	}
	.post-type-archive-job_listing #secondary,
	.page-template-page-jobs #secondary {
		display: none !important;
	}
	.post-type-archive-job_listing #primary,
	.page-template-page-jobs #primary {
		width: 100% !important; float: none !important; max-width: 100% !important;
		padding: 0 !important; margin: 0 !important; background: transparent !important;
	}
	.post-type-archive-job_listing .site-content .container,
	.page-template-page-jobs .site-content .container {
		max-width: 100% !important; padding: 0 !important;
	}

	/* Common Typography */
	.aj-page {
		font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
		color: #333333;
		box-sizing: border-box;
	}
	.aj-container {
		max-width: 1200px;
		margin: 0 auto;
		padding: 0 20px;
		box-sizing: border-box;
	}

	/* ============================================================
	   1. HERO BANNER: CAREER WITH US
	   ============================================================ */
	.aj-hero-banner {
		position: relative;
		width: 100%;
		height: 280px;
		background: url('<?php echo esc_url( home_url( '/wp-content/uploads/2026/10/career-banner.jpg' ) ); ?>') center center / cover no-repeat;
		display: flex;
		align-items: center;
		justify-content: center;
		margin-bottom: 30px;
		overflow: hidden;
	}
	.aj-hero-overlay {
		position: absolute;
		inset: 0;
		background: rgba(0, 0, 0, 0.45);
	}
	.aj-hero-content {
		position: relative;
		z-index: 2;
		text-align: center;
	}
	.aj-hero-title {
		color: #ffffff;
		font-size: 36px;
		font-weight: 800;
		letter-spacing: 3px;
		text-transform: uppercase;
		margin: 0;
		text-shadow: 0 2px 12px rgba(0, 0, 0, 0.5);
	}

	/* ============================================================
	   2. SEARCH & FILTER MODULE (FLAT WHITE CARD, 4PX RADIUS)
	   ============================================================ */
	.aj-search-card {
		background: #ffffff;
		border: none;
		border-radius: 4px;
		box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
		padding: 24px 28px;
		margin-bottom: 30px;
		box-sizing: border-box;
	}
	.aj-search-row {
		display: grid;
		grid-template-columns: 2fr 1.3fr 1.5fr 140px;
		gap: 14px;
		align-items: center;
	}
	.aj-input-group {
		position: relative;
		display: flex;
		align-items: center;
	}
	.aj-input-icon {
		position: absolute;
		left: 14px;
		width: 18px;
		height: 18px;
		color: #E87722;
		pointer-events: none;
		display: flex;
		align-items: center;
		justify-content: center;
		z-index: 2;
	}
	.aj-input-icon svg {
		width: 18px;
		height: 18px;
		stroke: #E87722;
		display: block;
	}

	.aj-search-card input[type="text"].aj-input-text,
	.aj-search-card .aj-input-text {
		width: 100% !important;
		height: 46px !important;
		padding-left: 44px !important;
		padding-right: 14px !important;
		border: 1px solid #e5e7eb !important;
		border-radius: 4px !important;
		background: #ffffff !important;
		font-size: 13.5px !important;
		font-family: inherit !important;
		color: #1e293b !important;
		outline: none !important;
		box-sizing: border-box !important;
		transition: border-color 0.2s, box-shadow 0.2s !important;
	}
	.aj-search-card select.aj-select,
	.aj-search-card .aj-select {
		width: 100% !important;
		height: 46px !important;
		padding-left: 44px !important;
		padding-right: 36px !important;
		border: 1px solid #e5e7eb !important;
		border-radius: 4px !important;
		background-color: #ffffff !important;
		font-size: 13.5px !important;
		font-family: inherit !important;
		color: #1e293b !important;
		outline: none !important;
		box-sizing: border-box !important;
		cursor: pointer !important;
		appearance: none !important;
		background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e") !important;
		background-repeat: no-repeat !important;
		background-position: right 14px center !important;
		background-size: 16px !important;
		transition: border-color 0.2s, box-shadow 0.2s !important;
	}
	.aj-search-card input[type="text"].aj-input-text:focus,
	.aj-search-card .aj-input-text:focus,
	.aj-search-card select.aj-select:focus,
	.aj-search-card .aj-select:focus {
		border-color: #E87722 !important;
		box-shadow: 0 0 0 2px rgba(232, 119, 34, 0.15) !important;
	}
	.aj-btn-search {
		height: 46px;
		background: #E87722;
		color: #ffffff;
		border: none;
		border-radius: 4px;
		font-size: 13.5px;
		font-weight: 700;
		letter-spacing: 0.5px;
		text-transform: uppercase;
		cursor: pointer;
		transition: background 0.2s, transform 0.1s;
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 8px;
		font-family: inherit;
	}
	.aj-btn-search:hover {
		background: #cf6619;
	}
	.aj-btn-search:active {
		transform: scale(0.98);
	}

	/* Quick Filters Row */
	.aj-quick-filters {
		margin-top: 18px;
		padding-top: 16px;
		border-top: 1px solid #f1f5f9;
		display: flex;
		flex-direction: column;
		gap: 12px;
	}
	.aj-qf-group {
		display: flex;
		align-items: center;
		gap: 8px;
		flex-wrap: wrap;
	}
	.aj-qf-label {
		font-size: 12px;
		font-weight: 700;
		color: #64748b;
		text-transform: uppercase;
		letter-spacing: 0.5px;
		min-width: 125px;
	}
	.aj-qf-items {
		display: flex;
		align-items: center;
		gap: 6px;
		flex-wrap: wrap;
	}
	.aj-qf-btn {
		background: #f8fafc;
		border: 1px solid #e2e8f0;
		border-radius: 3px;
		padding: 4px 12px;
		font-size: 12px;
		font-weight: 500;
		color: #475569;
		cursor: pointer;
		transition: all 0.18s;
		font-family: inherit;
		user-select: none;
	}
	.aj-qf-btn:hover {
		background: #f1f5f9;
		border-color: #cbd5e1;
		color: #1e293b;
	}
	.aj-qf-btn.active {
		background: #fff7ed;
		border-color: #E87722;
		color: #E87722;
		font-weight: 700;
	}
	.aj-qf-clear-btn {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		background: none;
		border: none;
		color: #E87722;
		font-size: 12px;
		font-weight: 700;
		cursor: pointer;
		padding: 4px 8px;
		transition: color 0.2s;
		font-family: inherit;
		margin-left: auto;
	}
	.aj-qf-clear-btn:hover {
		color: #c2410c;
		text-decoration: underline;
	}

	/* ============================================================
	   3. ALL JOBS HEADER (TITLE & SORT DROPDOWN)
	   ============================================================ */
	.aj-top-bar {
		display: flex;
		align-items: center;
		justify-content: space-between;
		margin: 0 0 24px 0;
		padding-bottom: 12px;
		border-bottom: 2px solid #e5e7eb;
	}
	.aj-heading {
		font-size: 22px;
		font-weight: 800;
		color: #1a1a1a;
		text-transform: uppercase;
		letter-spacing: 0.8px;
		margin: 0;
		display: flex;
		align-items: center;
		gap: 10px;
	}
	.aj-count-badge {
		font-size: 14px;
		font-weight: 600;
		color: #6b7280;
		text-transform: none;
		letter-spacing: normal;
	}
	.aj-sort-wrap {
		position: relative;
	}
	.aj-sort-select {
		height: 38px;
		padding: 0 32px 0 12px;
		border: 1px solid #d1d5db;
		border-radius: 4px;
		background: #ffffff;
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
	}
	.aj-sort-select:focus {
		border-color: #E87722;
	}

	/* ============================================================
	   4. TWO-COLUMN JOB GRID (SYNCED WITH JOB DETAIL OTHER JOBS)
	   ============================================================ */
	.aj-job-grid {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 22px;
		min-height: 200px;
	}

	/* Job Card: Flat white card, NO border, 4px radius */
	.aj-job-card {
		background: #ffffff;
		border: none;
		border-radius: 4px;
		box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
		padding: 24px;
		display: flex;
		flex-direction: column;
		justify-content: space-between;
		transition: transform 0.25s ease, box-shadow 0.25s ease, opacity 0.25s ease;
		min-height: 240px;
		box-sizing: border-box;
		text-decoration: none;
		color: inherit;
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
		border-color: #d1d5db;
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

	/* 3 separate badge tags on 1 line (Anti-wrap) */
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
		line-height: 1.35;
		flex-shrink: 0;
	}

	/* 3 bullet lines with round dots */
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

	/* ============================================================
	   5. EMPTY STATE
	   ============================================================ */
	.aj-empty-state {
		display: none;
		grid-column: 1 / -1;
		background: #ffffff;
		border-radius: 4px;
		box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
		padding: 60px 30px;
		text-align: center;
	}
	.aj-empty-icon {
		width: 56px;
		height: 56px;
		margin: 0 auto 16px;
		color: #cbd5e1;
	}
	.aj-empty-icon svg { width: 100%; height: 100%; }
	.aj-empty-title {
		font-size: 17px;
		font-weight: 700;
		color: #1e293b;
		margin: 0 0 8px 0;
	}
	.aj-empty-desc {
		font-size: 14px;
		color: #64748b;
		margin: 0 0 20px 0;
	}
	.aj-btn-reset-empty {
		display: inline-block;
		background: #E87722;
		color: #ffffff;
		border: none;
		border-radius: 4px;
		padding: 10px 24px;
		font-size: 13px;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: 0.5px;
		cursor: pointer;
		transition: background 0.2s;
	}
	.aj-btn-reset-empty:hover { background: #cf6619; }

	/* ============================================================
	   6. LOAD MORE BUTTON
	   ============================================================ */
	.aj-load-more-wrap {
		margin-top: 40px;
		text-align: center;
	}
	.aj-btn-load-more {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		gap: 8px;
		padding: 12px 38px;
		border: 1.5px solid #E87722;
		border-radius: 4px;
		background: #ffffff;
		color: #E87722;
		font-size: 13px;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: 0.8px;
		cursor: pointer;
		transition: all 0.2s;
		font-family: inherit;
	}
	.aj-btn-load-more:hover {
		background: #E87722;
		color: #ffffff;
		box-shadow: 0 4px 14px rgba(232, 119, 34, 0.25);
	}

	/* ============================================================
	   7. NEWSLETTER SECTION
	   ============================================================ */
	.aj-newsletter-bar {
		background: #E87722;
		margin-top: 60px;
		padding: 40px 0;
	}
	.aj-newsletter-inner {
		max-width: 1200px;
		margin: 0 auto;
		padding: 0 20px;
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 30px;
		box-sizing: border-box;
	}
	.aj-newsletter-title {
		color: #ffffff;
		font-size: 26px;
		font-weight: 800;
		line-height: 1.25;
		margin: 0;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}
	.aj-newsletter-form {
		display: flex;
		align-items: center;
		gap: 12px;
		flex: 1;
		max-width: 580px;
	}
	.aj-newsletter-input {
		flex: 1;
		height: 48px;
		padding: 0 16px 0 42px;
		border: none;
		border-radius: 4px;
		background: #ffffff url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%239ca3af' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpath d='M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z'%3e%3c/path%3e%3cpolyline points='22,6 12,13 2,6'%3e%3c/polyline%3e%3c/svg%3e") no-repeat 14px center / 18px;
		font-size: 14px;
		outline: none;
		font-family: inherit;
	}
	.aj-newsletter-btn {
		height: 48px;
		padding: 0 28px;
		border: 1.5px solid #ffffff;
		border-radius: 4px;
		background: transparent;
		color: #ffffff;
		font-size: 13px;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: 0.5px;
		cursor: pointer;
		transition: all 0.2s;
		font-family: inherit;
	}
	.aj-newsletter-btn:hover {
		background: #ffffff;
		color: #E87722;
	}

	/* ============================================================
	   8. FOOTER BAR & SOCIAL
	   ============================================================ */
	.aj-footer-bar {
		background: #ffffff;
		padding: 28px 0;
		border-top: 1px solid #e5e7eb;
	}
	.aj-footer-inner {
		max-width: 1200px;
		margin: 0 auto;
		padding: 0 20px;
		display: flex;
		flex-direction: column;
		align-items: center;
		gap: 18px;
		box-sizing: border-box;
	}
	.aj-footer-nav {
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 26px;
		flex-wrap: wrap;
	}
	.aj-footer-nav a {
		color: #4b5563;
		text-decoration: none;
		font-size: 13px;
		font-weight: 700;
		letter-spacing: 0.5px;
		text-transform: uppercase;
		transition: color 0.2s;
	}
	.aj-footer-nav a:hover {
		color: #E87722;
	}
	.aj-footer-social {
		display: flex;
		align-items: center;
		gap: 12px;
	}
	.aj-social-circle {
		width: 38px;
		height: 38px;
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
		color: #ffffff;
		text-decoration: none;
		font-size: 15px;
		transition: transform 0.2s, opacity 0.2s;
	}
	.aj-social-circle:hover {
		transform: scale(1.08);
		opacity: 0.9;
	}
	.aj-social-circle.fb { background: #3b5998; }
	.aj-social-circle.gg { background: #ea4335; }
	.aj-social-circle.line { background: #00c300; }
	.aj-social-circle.tw { background: #1da1f2; }

	/* Responsive */
	@media (max-width: 960px) {
		.aj-search-row { grid-template-columns: 1fr 1fr; }
		.aj-job-grid { grid-template-columns: 1fr; }
		.aj-newsletter-inner { flex-direction: column; text-align: center; }
		.aj-newsletter-form { width: 100%; }
	}
	@media (max-width: 640px) {
		.aj-search-row { grid-template-columns: 1fr; }
		.aj-top-bar { flex-direction: column; align-items: flex-start; gap: 12px; }
	}
</style>

<!-- ============================================================
     HTML MARKUP
     ============================================================ -->
<div class="aj-page">

	<!-- 1. HERO BANNER: CAREER WITH US -->
	<div class="aj-hero-banner">
		<div class="aj-hero-overlay"></div>
		<div class="aj-hero-content">
			<h1 class="aj-hero-title">CAREER WITH US</h1>
		</div>
	</div>

	<div class="aj-container">

		<!-- 2. SEARCH & FILTER MODULE -->
		<div class="aj-search-card">
			<!-- Main Row -->
			<div class="aj-search-row">
				<!-- Keyword Input -->
				<div class="aj-input-group">
					<div class="aj-input-icon">
						<svg viewBox="0 0 24 24" fill="none" stroke="#E87722" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"></circle><line x1="21" y1="21" x2="16.5" y2="16.5"></line></svg>
					</div>
					<input type="text" id="aj-filter-keyword" class="aj-input-text" placeholder="Tìm theo vị trí, kỹ năng, công ty..." autocomplete="off" />
				</div>

				<!-- Location Dropdown -->
				<div class="aj-input-group">
					<div class="aj-input-icon">
						<svg viewBox="0 0 24 24" fill="none" stroke="#E87722" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
					</div>
					<select id="aj-filter-location" class="aj-select">
						<option value="">Tất cả địa điểm</option>
						<option value="hcm">TP. Hồ Chí Minh</option>
						<option value="hn">Hà Nội</option>
						<option value="dn">Đà Nẵng</option>
					</select>
				</div>

				<!-- Category Dropdown -->
				<div class="aj-input-group">
					<div class="aj-input-icon">
						<svg viewBox="0 0 24 24" fill="none" stroke="#E87722" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"></path></svg>
					</div>
					<select id="aj-filter-category" class="aj-select">
						<option value="">Tất cả ngành nghề</option>
						<option value="cong-nghe-thong-tin">Công nghệ thông tin (IT)</option>
						<option value="nha-hang-thuc-pham">Nhà hàng &amp; Thực phẩm</option>
						<option value="ke-toan-tai-chinh">Kế toán / Tài chính</option>
						<option value="xay-dung-co-so-ha-tang">Xây dựng &amp; Cơ sở hạ tầng</option>
						<option value="thiet-ke-web-marketing">Thiết kế Web &amp; Marketing</option>
						<option value="y-te-suc-khoe">Y tế &amp; Chăm sóc sức khỏe</option>
						<option value="quan-tri-nhan-su">Quản trị nhân sự</option>
						<option value="giao-duc-dao-tao">Giáo dục &amp; Đào tạo</option>
					</select>
				</div>

				<!-- Search Button -->
				<button type="button" id="aj-btn-search" class="aj-btn-search">
					TÌM KIẾM
				</button>
			</div>

			<!-- Quick Filters Row -->
			<div class="aj-quick-filters">
				<!-- Hình thức làm việc -->
				<div class="aj-qf-group">
					<span class="aj-qf-label">Hình thức:</span>
					<div class="aj-qf-items" id="qf-type-container">
						<button type="button" class="aj-qf-btn active" data-type="all">Tất cả</button>
						<button type="button" class="aj-qf-btn" data-type="toan-thoi-gian">Toàn thời gian (Full-time)</button>
						<button type="button" class="aj-qf-btn" data-type="ban-thoi-gian">Bán thời gian (Part-time)</button>
						<button type="button" class="aj-qf-btn" data-type="lam-tu-xa">Làm từ xa (Remote)</button>
					</div>
				</div>

				<!-- Mức lương -->
				<div class="aj-qf-group">
					<span class="aj-qf-label">Mức lương:</span>
					<div class="aj-qf-items" id="qf-salary-container">
						<button type="button" class="aj-qf-btn active" data-salary="all">Tất cả</button>
						<button type="button" class="aj-qf-btn" data-salary="under-15">Dưới 15 triệu</button>
						<button type="button" class="aj-qf-btn" data-salary="15-30">15 - 30 triệu</button>
						<button type="button" class="aj-qf-btn" data-salary="above-30">Trên 30 triệu</button>
					</div>

					<!-- Clear Filters Button -->
					<button type="button" id="aj-btn-clear-filters" class="aj-qf-clear-btn" style="display: none;">
						✕ Xóa bộ lọc
					</button>
				</div>
			</div>
		</div>

		<!-- 3. ALL JOBS TOP BAR (TITLE + SORT DROPDOWN) -->
		<div class="aj-top-bar">
			<h2 class="aj-heading">
				ALL JOBS
				<span id="aj-count-badge" class="aj-count-badge">(Hiển thị 6 / <?php echo count( $jobs_dataset ); ?> công việc)</span>
			</h2>
			<div class="aj-sort-wrap">
				<select id="aj-sort-select" class="aj-sort-select">
					<option value="latest">Latest Jobs</option>
					<option value="salary">Highest Salary</option>
					<option value="oldest">Oldest</option>
				</select>
			</div>
		</div>

		<!-- 4. TWO-COLUMN JOB GRID -->
		<div class="aj-job-grid" id="aj-job-grid">
			<!-- Rendered dynamically by JavaScript with initial fallback -->
		</div>

		<!-- 5. EMPTY STATE -->
		<div class="aj-empty-state" id="aj-empty-state">
			<div class="aj-empty-icon">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line><line x1="8" y1="11" x2="14" y2="11"></line></svg>
			</div>
			<h3 class="aj-empty-title">Không tìm thấy công việc phù hợp</h3>
			<p class="aj-empty-desc">Rất tiếc, hiện tại không có vị trí nào khớp với tiêu chí tìm kiếm hoặc bộ lọc bạn vừa chọn.</p>
			<button type="button" id="aj-btn-reset-empty" class="aj-btn-reset-empty">Đặt lại bộ lọc</button>
		</div>

		<!-- 6. LOAD MORE BUTTON -->
		<div class="aj-load-more-wrap" id="aj-load-more-wrap">
			<button type="button" id="aj-btn-load-more" class="aj-btn-load-more">
				LOAD MORE JOBS
			</button>
		</div>

	</div><!-- .aj-container -->

	<!-- 7. NEWSLETTER SECTION -->
	<div class="aj-newsletter-bar">
		<div class="aj-newsletter-inner">
			<h3 class="aj-newsletter-title">Subscribe To<br>Our Newsletter</h3>
			<div class="aj-newsletter-form">
				<input type="email" id="aj-nl-input" class="aj-newsletter-input" placeholder="Input your email address" />
				<button type="button" id="aj-nl-btn" class="aj-newsletter-btn">SUBSCRIBE</button>
			</div>
		</div>
	</div>

	<!-- 8. FOOTER BAR & SOCIAL -->
	<div class="aj-footer-bar">
		<div class="aj-footer-inner">
			<nav class="aj-footer-nav">
				<a href="<?php echo esc_url( home_url( '/jobs/' ) ); ?>">JOBS</a>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">COMPANIES</a>
				<a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">BLOG</a>
				<a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">ABOUT</a>
				<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">CONTACT</a>
			</nav>
			<div class="aj-footer-social">
				<a href="#" class="aj-social-circle fb" title="Facebook"><i class="fab fa-facebook-f"></i></a>
				<a href="#" class="aj-social-circle gg" title="Google"><i class="fab fa-google"></i></a>
				<a href="#" class="aj-social-circle line" title="LINE"><i class="fab fa-line"></i></a>
				<a href="#" class="aj-social-circle tw" title="Twitter"><i class="fab fa-twitter"></i></a>
			</div>
		</div>
	</div>

</div><!-- .aj-page -->

<!-- ============================================================
     JAVASCRIPT: REALTIME SEARCH, FILTER, SORT & LOAD MORE
     ============================================================ -->
<script>
(function() {
	var rawJobs = <?php echo wp_json_encode( $jobs_dataset ); ?>;
	var itemsPerPage = 6;
	var visibleCount = itemsPerPage;

	// State
	var currentKeyword = '';
	var currentLocation = '';
	var currentCategory = '';
	var currentType = 'all';
	var currentSalary = 'all';
	var currentSort = 'latest';

	// DOM Elements
	var gridEl = document.getElementById('aj-job-grid');
	var emptyEl = document.getElementById('aj-empty-state');
	var countBadgeEl = document.getElementById('aj-count-badge');
	var loadMoreWrapEl = document.getElementById('aj-load-more-wrap');
	var loadMoreBtn = document.getElementById('aj-btn-load-more');
	var clearFiltersBtn = document.getElementById('aj-btn-clear-filters');
	var keywordInput = document.getElementById('aj-filter-keyword');
	var locationSelect = document.getElementById('aj-filter-location');
	var categorySelect = document.getElementById('aj-filter-category');
	var sortSelect = document.getElementById('aj-sort-select');
	var searchBtn = document.getElementById('aj-btn-search');
	var emptyResetBtn = document.getElementById('aj-btn-reset-empty');

	// Helper to check if any non-default filter is active
	function isFilteringActive() {
		return currentKeyword.trim() !== '' ||
			currentLocation !== '' ||
			currentCategory !== '' ||
			currentType !== 'all' ||
			currentSalary !== 'all';
	}

	function updateClearFiltersVisibility() {
		if (clearFiltersBtn) {
			clearFiltersBtn.style.display = isFilteringActive() ? 'inline-flex' : 'none';
		}
	}

	// Filter & Sort Logic
	function getFilteredJobs() {
		var filtered = rawJobs.filter(function(job) {
			// 1. Keyword (title, company, category, location, skills)
			if (currentKeyword) {
				var kw = currentKeyword.toLowerCase().trim();
				if (job.search_text.indexOf(kw) === -1) {
					return false;
				}
			}

			// 2. Location
			if (currentLocation) {
				if (job.loc_code !== currentLocation) {
					return false;
				}
			}

			// 3. Category
			if (currentCategory) {
				if (job.cat_slug !== currentCategory) {
					return false;
				}
			}

			// 4. Type (Quick Filter)
			if (currentType !== 'all') {
				if (job.type_slug !== currentType) {
					return false;
				}
			}

			// 5. Salary (Quick Filter)
			if (currentSalary !== 'all') {
				var minSal = job.min_sal || 0;
				var maxSal = job.max_sal || minSal;
				if (currentSalary === 'under-15') {
					if (minSal > 15000000 && minSal > 0) return false;
				} else if (currentSalary === '15-30') {
					if (maxSal < 15000000 || (minSal > 30000000 && minSal > 0)) return false;
				} else if (currentSalary === 'above-30') {
					if (maxSal < 30000000) return false;
				}
			}

			return true;
		});

		// Sorting
		if (currentSort === 'latest') {
			filtered.sort(function(a, b) { return b.date_timestamp - a.date_timestamp; });
		} else if (currentSort === 'oldest') {
			filtered.sort(function(a, b) { return a.date_timestamp - b.date_timestamp; });
		} else if (currentSort === 'salary') {
			filtered.sort(function(a, b) {
				var maxB = b.max_sal || b.min_sal || 0;
				var maxA = a.max_sal || a.min_sal || 0;
				return maxB - maxA;
			});
		}

		return filtered;
	}

	// Render Job Cards into DOM
	function render() {
		var filteredJobs = getFilteredJobs();
		var totalMatches = filteredJobs.length;

		updateClearFiltersVisibility();

		if (totalMatches === 0) {
			gridEl.innerHTML = '';
			emptyEl.style.display = 'block';
			loadMoreWrapEl.style.display = 'none';
			countBadgeEl.textContent = '(Tìm thấy 0 công việc)';
			return;
		}

		emptyEl.style.display = 'none';

		var toDisplay = filteredJobs.slice(0, visibleCount);

		// Render HTML
		var html = '';
		toDisplay.forEach(function(job) {
			var bulletsHtml = '';
			if (job.bullets && job.bullets.length) {
				job.bullets.forEach(function(b) {
					bulletsHtml += '<li>' + escapeHtml(b) + '</li>';
				});
			}

			html += '<a href="' + escapeHtml(job.permalink) + '" class="aj-job-card">' +
				'<div class="aj-job-card-top">' +
					'<div class="aj-job-card-logo">' +
						'<img src="' + escapeHtml(job.logo) + '" alt="' + escapeHtml(job.company) + '" loading="lazy" />' +
					'</div>' +
					'<div class="aj-job-card-info">' +
						'<h3 class="aj-job-card-name">' + escapeHtml(job.title) + '</h3>' +
						'<div class="aj-job-card-date">Created: ' + escapeHtml(job.date_formatted) + '</div>' +
						'<div class="aj-job-card-tags">' +
							'<span class="aj-tag">' + escapeHtml(job.type_label) + '</span>' +
							'<span class="aj-tag">' + escapeHtml(job.cat_label) + '</span>' +
							'<span class="aj-tag">' + escapeHtml(job.loc_label) + '</span>' +
						'</div>' +
					'</div>' +
				'</div>' +
				'<div class="aj-job-card-bottom">' +
					'<ul class="aj-job-card-bullets">' +
						bulletsHtml +
					'</ul>' +
				'</div>' +
			'</a>';
		});

		gridEl.innerHTML = html;

		// Update Counter
		var showingCount = Math.min(visibleCount, totalMatches);
		countBadgeEl.textContent = '(Hiển thị ' + showingCount + ' / ' + totalMatches + ' công việc)';

		// Load More Visibility
		if (visibleCount >= totalMatches) {
			loadMoreWrapEl.style.display = 'none';
		} else {
			loadMoreWrapEl.style.display = 'block';
		}
	}

	function escapeHtml(str) {
		if (!str) return '';
		return String(str)
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;');
	}

	// Reset to first page
	function onFilterChange() {
		visibleCount = itemsPerPage;
		render();
	}

	// Event Listeners
	// Keyword input debounce
	var debounceTimer = null;
	if (keywordInput) {
		keywordInput.addEventListener('input', function() {
			clearTimeout(debounceTimer);
			debounceTimer = setTimeout(function() {
				currentKeyword = keywordInput.value;
				onFilterChange();
			}, 200);
		});
	}

	// Dropdowns
	if (locationSelect) {
		locationSelect.addEventListener('change', function() {
			currentLocation = locationSelect.value;
			onFilterChange();
		});
	}
	if (categorySelect) {
		categorySelect.addEventListener('change', function() {
			currentCategory = categorySelect.value;
			onFilterChange();
		});
	}
	if (sortSelect) {
		sortSelect.addEventListener('change', function() {
			currentSort = sortSelect.value;
			render();
		});
	}

	// Search button (immediate trigger)
	if (searchBtn) {
		searchBtn.addEventListener('click', function() {
			currentKeyword = keywordInput ? keywordInput.value : '';
			currentLocation = locationSelect ? locationSelect.value : '';
			currentCategory = categorySelect ? categorySelect.value : '';
			onFilterChange();
		});
	}

	// Quick Filters: Type
	var typeBtns = document.querySelectorAll('#qf-type-container .aj-qf-btn');
	typeBtns.forEach(function(btn) {
		btn.addEventListener('click', function() {
			typeBtns.forEach(function(b) { b.classList.remove('active'); });
			btn.classList.add('active');
			currentType = btn.getAttribute('data-type');
			onFilterChange();
		});
	});

	// Quick Filters: Salary
	var salBtns = document.querySelectorAll('#qf-salary-container .aj-qf-btn');
	salBtns.forEach(function(btn) {
		btn.addEventListener('click', function() {
			salBtns.forEach(function(b) { b.classList.remove('active'); });
			btn.classList.add('active');
			currentSalary = btn.getAttribute('data-salary');
			onFilterChange();
		});
	});

	// Reset All Filters
	function resetFilters() {
		currentKeyword = '';
		currentLocation = '';
		currentCategory = '';
		currentType = 'all';
		currentSalary = 'all';
		currentSort = 'latest';

		if (keywordInput) keywordInput.value = '';
		if (locationSelect) locationSelect.value = '';
		if (categorySelect) categorySelect.value = '';
		if (sortSelect) sortSelect.value = 'latest';

		typeBtns.forEach(function(b) {
			b.classList.toggle('active', b.getAttribute('data-type') === 'all');
		});
		salBtns.forEach(function(b) {
			b.classList.toggle('active', b.getAttribute('data-salary') === 'all');
		});

		visibleCount = itemsPerPage;
		render();
	}

	if (clearFiltersBtn) {
		clearFiltersBtn.addEventListener('click', resetFilters);
	}
	if (emptyResetBtn) {
		emptyResetBtn.addEventListener('click', resetFilters);
	}

	// Load More Action
	if (loadMoreBtn) {
		loadMoreBtn.addEventListener('click', function() {
			visibleCount += itemsPerPage;
			render();
		});
	}

	// Newsletter Toast Demo
	var nlBtn = document.getElementById('aj-nl-btn');
	var nlInput = document.getElementById('aj-nl-input');
	if (nlBtn && nlInput) {
		nlBtn.addEventListener('click', function() {
			var email = nlInput.value.trim();
			if (!email || email.indexOf('@') === -1) {
				alert('Vui lòng nhập địa chỉ email hợp lệ!');
				return;
			}
			alert('Cảm ơn bạn! Email "' + email + '" đã được đăng ký nhận bản tin thành công.');
			nlInput.value = '';
		});
	}

	// Initial Render
	render();
})();
</script>

<?php
get_footer();
