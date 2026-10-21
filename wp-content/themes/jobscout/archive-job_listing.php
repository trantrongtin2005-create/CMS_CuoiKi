<?php
/**
 * The template for displaying Job Listings Archive
 * Matched 100% to "4-all job 2.jpg" with complete Search Result Validation & Realtime Engine
 *
 * Requirements satisfied:
 * 1. ALL JOBS Grid:
 *    - 2-column grid on desktop (1-column on mobile).
 *    - Flat UI, white cards, 4px border-radius, logo in box with border-gray-200.
 *    - Logo, uppercase bold title, created date, 3 badges on 1 row, 3 bullet lines with round dots (•).
 *    - Result Counter: "ALL JOBS (Hiển thị X / Y công việc)" or "(Tìm thấy Y việc làm phù hợp)". Realtime update.
 *    - Active Filter Badges: "[Hà Nội ✕]", "[Dưới 15 triệu ✕]" with instant removal.
 * 2. Sorting & Pagination:
 *    - Dropdown: Latest Jobs (default), Oldest Jobs, Highest Salary, Lowest Salary. Realtime re-sorting.
 *    - "LOAD MORE JOBS" button: Loads next 6 jobs with smooth fade-in animation, auto-hides when X == Y.
 * 3. Empty State Validation:
 *    - When Y == 0: hides grid and load more, displays centered modern box with icon, bold message,
 *      subtitle suggestion, and prominent orange "Đặt lại bộ lọc" (Reset Filters) button.
 * 4. Navigation:
 *    - Entire job card is an anchor link pointing directly to the job detail view.
 *
 * @package JobScout
 */

get_header();

// Fetch all published jobs from DB to build the realtime client-side dataset
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

		// Parse Salary numeric values for accurate sorting & quick filtering
		$min_sal = 0;
		$max_sal = 0;
		if ( ! empty( $salary ) ) {
			if ( preg_match_all( '/(\d+(?:[.,]\d+)*)/', $salary, $sal_matches ) ) {
				$clean_nums = array();
				foreach ( $sal_matches[1] as $raw_num ) {
					$n = (int) str_replace( array( '.', ',' ), '', $raw_num );
					if ( false !== stripos( $salary, 'USD' ) || false !== stripos( $salary, '$' ) ) {
						if ( $n < 50000 ) {
							$n = $n * 25000;
						}
					}
					if ( $n > 0 ) {
						$clean_nums[] = $n;
					}
				}
				if ( ! empty( $clean_nums ) ) {
					$min_sal = min( $clean_nums );
					$max_sal = max( $clean_nums );
				}
			}
		}

		// Logo resolution with default and corporate fallback
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
		} elseif ( false !== stripos( $loc_label, 'Tokyo' ) ) {
			$loc_code  = 'tokyo';
			$loc_label = 'Tokyo';
		} else {
			$loc_code  = sanitize_title( $loc_label );
		}

		// 3 Bullets extraction
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
			'Be responsible for the effective operational management of the department / hotel.',
			'Excellent salary bonuses & recognition activities.',
			'Foreign language allowance and career development opportunities.',
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

// Initial GET search parameters
$init_keyword  = isset( $_GET['search_keywords'] ) ? sanitize_text_field( wp_unslash( $_GET['search_keywords'] ) ) : '';
$init_location = isset( $_GET['search_location'] ) ? sanitize_text_field( wp_unslash( $_GET['search_location'] ) ) : '';
$init_category = isset( $_GET['search_category'] ) ? sanitize_text_field( wp_unslash( $_GET['search_category'] ) ) : '';

$all_categories = get_terms( array( 'taxonomy' => 'job_listing_category', 'hide_empty' => false ) );
?>

<!-- ============================================================
     CSS: All Jobs Page & Search Result Validation
     ============================================================ -->
<style id="aj-design-styles">
	@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap');

	/* 1. Global Page Reset & Flat Background */
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
	.page-template-page-jobs .site-content > header.entry-header,
	.post-type-archive-job_listing #secondary,
	.page-template-page-jobs #secondary,
	.post-type-archive-job_listing .breadcrumbs,
	.page-template-page-jobs .breadcrumbs { 
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

	/* 2. Hero Banner: CAREER WITH US (Matched 100% to 4-all job 2.jpg) */
	.aj-hero-banner {
		position: relative;
		width: 100%;
		height: 300px;
		background-color: #1a1a1a;
		background: url('<?php echo esc_url( home_url( '/wp-content/uploads/2026/10/career-banner.jpg' ) ); ?>') center center / cover no-repeat,
		            url('https://images.unsplash.com/photo-1503899036084-c55cdd92da26?auto=format&fit=crop&w=1920&q=80') center center / cover no-repeat;
		display: flex;
		align-items: center;
		justify-content: center;
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
		padding: 0 20px;
	}
	.aj-hero-title {
		font-family: 'Montserrat', sans-serif;
		font-size: 38px;
		font-weight: 800;
		color: #ffffff;
		text-transform: uppercase;
		letter-spacing: 2px;
		margin: 0;
		line-height: 1.2;
		text-shadow: 0 2px 10px rgba(0,0,0,0.5);
	}

	/* Main Page Container */
	.aj-page-container {
		max-width: 1200px;
		margin: 0 auto;
		padding: 30px 20px 70px;
		font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
		box-sizing: border-box;
		color: #333333;
	}

	/* 3. Search & Quick Filters Box */
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

	/* Quick Filters Row */
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

	/* Active Filter Badges: [Hà Nội ✕] */
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
		display: flex;
		align-items: center;
		justify-content: center;
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

	/* 4. Top Bar: ALL JOBS + Dynamic Result Counter + Sorting */
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

	/* 5. 2-Column Job Grid (Flat UI, 4px border-radius, border-gray-200 logo) */
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
		border: 1px solid #e5e7eb; /* border-gray-200 */
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

	/* 3 separate badges on 1 row anti-wrap */
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

	/* 6. "LOAD MORE JOBS" button (Orange outline & text, matching 4-all job 2.jpg) */
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

	/* 7. Empty State Validation Box (Y == 0) */
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
		background: #f8fafc;
	}
	.aj-empty-title {
		font-size: 18px;
		font-weight: 800;
		color: #1e293b;
		margin: 0 0 10px 0;
		line-height: 1.4;
	}
	.aj-empty-desc {
		font-size: 14px;
		color: #64748b;
		margin: 0 0 24px 0;
		line-height: 1.6;
	}
	.aj-empty-reset-btn {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		padding: 10px 28px;
		background: #E87722;
		color: #ffffff;
		border: none;
		border-radius: 4px;
		font-size: 14px;
		font-weight: 700;
		cursor: pointer;
		transition: background 0.2s;
		font-family: inherit;
	}
	.aj-empty-reset-btn:hover {
		background: #c2410c;
	}

	/* Responsive */
	@media (max-width: 960px) {
		.aj-grid { grid-template-columns: 1fr; }
		.aj-quick-filters-row { flex-direction: column; align-items: flex-start; }
		.aj-hero-title { font-size: 28px; }
		.aj-search-row { flex-direction: column; }
		.aj-search-field, .aj-search-btn { width: 100%; }
	}
</style>

<!-- ============================================================
     HTML MARKUP: ALL JOBS PAGE (Matched 100% to 4-all job 2.jpg)
     ============================================================ -->
<!-- HERO BANNER: CAREER WITH US -->
<div class="aj-hero-banner">
	<div class="aj-hero-overlay"></div>
	<div class="aj-hero-content">
		<h1 class="aj-hero-title">CAREER WITH US</h1>
	</div>
</div>

<div class="aj-page-container">

	<!-- SEARCH & QUICK FILTERS CARD -->
	<div class="aj-search-filter-card">
		<div class="aj-search-row">
			<!-- 1. Keyword Input -->
			<div class="aj-search-field aj-field-keyword">
				<span class="aj-field-icon" aria-hidden="true">
					<svg viewBox="0 0 24 24" fill="none" stroke="#E87722" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
						<circle cx="11" cy="11" r="8"></circle>
						<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
					</svg>
				</span>
				<input 
					type="text" 
					id="aj-keyword-input" 
					placeholder="Tìm kiếm theo vị trí, công ty, kỹ năng..." 
					value="<?php echo esc_attr( $init_keyword ); ?>" 
					autocomplete="off" 
					aria-label="Từ khóa tìm kiếm"
				/>
				<button type="button" class="aj-input-clear" id="aj-keyword-clear" title="Xóa từ khóa" style="<?php echo empty( $init_keyword ) ? 'display: none;' : ''; ?>">&times;</button>
			</div>

			<!-- 2. Location Select -->
			<div class="aj-search-field aj-field-location">
				<span class="aj-field-icon" aria-hidden="true">
					<svg viewBox="0 0 24 24" fill="none" stroke="#E87722" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
						<circle cx="12" cy="10" r="3"></circle>
					</svg>
				</span>
				<select id="aj-location-select" aria-label="Chọn địa điểm">
					<option value="">Tất cả địa điểm</option>
					<option value="hn" <?php selected( $init_location, 'hn' ); ?>>Hà Nội</option>
					<option value="hcm" <?php selected( $init_location, 'hcm' ); ?>>Hồ Chí Minh</option>
					<option value="dn" <?php selected( $init_location, 'dn' ); ?>>Đà Nẵng</option>
					<option value="tokyo" <?php selected( $init_location, 'tokyo' ); ?>>Tokyo</option>
				</select>
			</div>

			<!-- 3. Category Select -->
			<div class="aj-search-field aj-field-category">
				<span class="aj-field-icon" aria-hidden="true">
					<svg viewBox="0 0 24 24" fill="none" stroke="#E87722" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
						<rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
						<path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
					</svg>
				</span>
				<select id="aj-category-select" aria-label="Chọn ngành nghề">
					<option value="">Tất cả ngành nghề</option>
					<?php
					if ( ! empty( $all_categories ) && ! is_wp_error( $all_categories ) ) {
						foreach ( $all_categories as $cat ) {
							$selected = ( $init_category === $cat->slug ) ? 'selected' : '';
							echo '<option value="' . esc_attr( $cat->slug ) . '" ' . $selected . '>' . esc_html( $cat->name ) . '</option>';
						}
					}
					?>
				</select>
			</div>

			<!-- 4. Submit Search Button -->
			<button type="button" id="aj-search-btn" class="aj-search-btn">TÌM KIẾM</button>
		</div>

		<!-- Quick Filters: Hình thức & Mức lương -->
		<div class="aj-quick-filters-row">
			<div class="aj-qf-group" id="qf-type-group">
				<span class="aj-qf-label">Hình thức:</span>
				<button type="button" class="aj-qf-pill active" data-type="all">Tất cả</button>
				<button type="button" class="aj-qf-pill" data-type="toan-thoi-gian">Toàn thời gian</button>
				<button type="button" class="aj-qf-pill" data-type="ban-thoi-gian">Bán thời gian</button>
				<button type="button" class="aj-qf-pill" data-type="remote">Remote</button>
			</div>

			<div class="aj-qf-group" id="qf-salary-group">
				<span class="aj-qf-label">Mức lương:</span>
				<button type="button" class="aj-qf-pill active" data-salary="all">Tất cả</button>
				<button type="button" class="aj-qf-pill" data-salary="under-15">&lt; 15 triệu</button>
				<button type="button" class="aj-qf-pill" data-salary="15-30">15 - 30 triệu</button>
				<button type="button" class="aj-qf-pill" data-salary="above-30">&gt; 30 triệu</button>
			</div>
		</div>

		<!-- Active Filter Badges: [Hà Nội ✕], [Dưới 15 triệu ✕] -->
		<div class="aj-active-filters-bar" id="aj-active-filters-bar" style="display: none;">
			<span class="aj-af-label">Đang lọc:</span>
			<div class="aj-af-tags" id="aj-af-tags"></div>
			<button type="button" class="aj-af-clear-all" id="aj-af-clear-all">Xóa tất cả</button>
		</div>
	</div>

	<!-- TOP BAR: ALL JOBS + Result Counter + Sorting Dropdown -->
	<div class="aj-top-bar">
		<div class="aj-top-left">
			<h2 class="aj-heading">ALL JOBS</h2>
			<span class="aj-count-badge" id="aj-count-badge">(Hiển thị 0 / 0 công việc)</span>
		</div>
		<div class="aj-top-right">
			<select id="aj-sort-select" class="aj-sort-select" aria-label="Sắp xếp kết quả">
				<option value="latest" selected>Mới nhất</option>
				<option value="oldest">Cũ nhất</option>
				<option value="salary-desc">Lương cao nhất</option>
				<option value="salary-asc">Lương thấp nhất</option>
			</select>
		</div>
	</div>

	<!-- 2-COLUMN JOB GRID -->
	<div class="aj-grid" id="aj-grid"></div>

	<!-- "LOAD MORE JOBS" BUTTON -->
	<div class="aj-load-more-wrap" id="aj-load-more-wrap">
		<button type="button" class="aj-load-more-btn" id="aj-load-more-btn">LOAD MORE JOBS</button>
	</div>

	<!-- EMPTY STATE VALIDATION BOX -->
	<div class="aj-empty-state" id="aj-empty-state" style="display: none;">
		<div class="aj-empty-icon">
			<svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
				<circle cx="11" cy="11" r="8"></circle>
				<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
				<line x1="8" y1="11" x2="14" y2="11"></line>
			</svg>
		</div>
		<h3 class="aj-empty-title">Không tìm thấy công việc phù hợp với tiêu chí của bạn</h3>
		<p class="aj-empty-desc">Hãy thử thay đổi từ khóa hoặc điều chỉnh lại các bộ lọc</p>
		<button type="button" class="aj-empty-reset-btn" id="aj-empty-reset-btn">Đặt lại bộ lọc</button>
	</div>

</div><!-- .aj-page-container -->

<!-- ============================================================
     JAVASCRIPT: Realtime Search, Filter, Sort, Pagination Engine
     ============================================================ -->
<script>
(function() {
	var rawJobs = <?php echo wp_json_encode( $jobs_dataset ); ?>;
	var currentKeyword  = <?php echo wp_json_encode( $init_keyword ); ?>;
	var currentLocation = <?php echo wp_json_encode( $init_location ); ?>;
	var currentCategory = <?php echo wp_json_encode( $init_category ); ?>;
	var currentType     = 'all';
	var currentSalary   = 'all';
	var currentSort     = 'latest';
	var itemsPerPage    = 6;
	var visibleCount    = itemsPerPage;

	// DOM Elements
	var keywordInput     = document.getElementById('aj-keyword-input');
	var keywordClearBtn  = document.getElementById('aj-keyword-clear');
	var locationSelect   = document.getElementById('aj-location-select');
	var categorySelect   = document.getElementById('aj-category-select');
	var searchBtn        = document.getElementById('aj-search-btn');
	var sortSelect       = document.getElementById('aj-sort-select');
	var gridEl           = document.getElementById('aj-grid');
	var countBadgeEl     = document.getElementById('aj-count-badge');
	var loadMoreWrapEl   = document.getElementById('aj-load-more-wrap');
	var loadMoreBtn      = document.getElementById('aj-load-more-btn');
	var emptyEl          = document.getElementById('aj-empty-state');
	var resetBtn         = document.getElementById('aj-empty-reset-btn');
	var clearAllBtn      = document.getElementById('aj-af-clear-all');
	var afBarEl          = document.getElementById('aj-active-filters-bar');
	var afTagsEl         = document.getElementById('aj-af-tags');

	// Filtering Logic
	function getFilteredJobs() {
		return rawJobs.filter(function(job) {
			// 1. Keyword
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

			// 4. Type (support either 'remote' or 'lam-tu-xa')
			if (currentType !== 'all') {
				if (currentType === 'remote' || currentType === 'lam-tu-xa') {
					if (job.type_slug !== 'remote' && job.type_slug !== 'lam-tu-xa') {
						return false;
					}
				} else if (job.type_slug !== currentType) {
					return false;
				}
			}

			// 5. Salary
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
	}

	// Sorting Logic
	function sortJobs(jobs) {
		if (currentSort === 'latest') {
			jobs.sort(function(a, b) { return b.date_timestamp - a.date_timestamp; });
		} else if (currentSort === 'oldest') {
			jobs.sort(function(a, b) { return a.date_timestamp - b.date_timestamp; });
		} else if (currentSort === 'salary-desc' || currentSort === 'salary') {
			jobs.sort(function(a, b) {
				var maxB = b.max_sal || b.min_sal || 0;
				var maxA = a.max_sal || a.min_sal || 0;
				return maxB - maxA;
			});
		} else if (currentSort === 'salary-asc') {
			jobs.sort(function(a, b) {
				var minA = a.min_sal > 0 ? a.min_sal : 999999999;
				var minB = b.min_sal > 0 ? b.min_sal : 999999999;
				return minA - minB;
			});
		}
		return jobs;
	}

	// Render Active Filter Badges: [Hà Nội ✕], [Dưới 15 triệu ✕]
	function renderActiveFilters() {
		if (!afBarEl || !afTagsEl) return;
		var badges = [];

		if (currentKeyword) {
			badges.push({
				key: 'keyword',
				label: 'Từ khóa: "' + escapeHtml(currentKeyword) + '"'
			});
		}
		if (currentLocation) {
			var locText = locationSelect ? locationSelect.options[locationSelect.selectedIndex].text : currentLocation;
			badges.push({
				key: 'location',
				label: 'Địa điểm: ' + escapeHtml(locText)
			});
		}
		if (currentCategory) {
			var catText = categorySelect ? categorySelect.options[categorySelect.selectedIndex].text : currentCategory;
			badges.push({
				key: 'category',
				label: 'Ngành: ' + escapeHtml(catText)
			});
		}
		if (currentType !== 'all') {
			var typeMap = {
				'toan-thoi-gian': 'Toàn thời gian',
				'ban-thoi-gian': 'Bán thời gian',
				'remote': 'Remote',
				'lam-tu-xa': 'Làm từ xa'
			};
			badges.push({
				key: 'type',
				label: 'Hình thức: ' + (typeMap[currentType] || currentType)
			});
		}
		if (currentSalary !== 'all') {
			var salMap = {
				'under-15': 'Dưới 15 triệu',
				'15-30': '15 - 30 triệu',
				'above-30': 'Trên 30 triệu'
			};
			badges.push({
				key: 'salary',
				label: 'Lương: ' + (salMap[currentSalary] || currentSalary)
			});
		}

		if (badges.length === 0) {
			afBarEl.style.display = 'none';
			afTagsEl.innerHTML = '';
		} else {
			afBarEl.style.display = 'flex';
			var html = '';
			badges.forEach(function(b) {
				html += '<span class="aj-af-tag" data-filter-key="' + b.key + '">' +
					b.label + ' <button type="button" class="aj-af-tag-remove" data-filter-key="' + b.key + '" aria-label="Xóa bộ lọc">&times;</button>' +
				'</span>';
			});
			afTagsEl.innerHTML = html;
		}
	}

	// Main Render Function
	function render() {
		var filtered = getFilteredJobs();
		var sorted = sortJobs(filtered.slice());
		var totalMatches = sorted.length;

		renderActiveFilters();

		// Empty State Validation (Y == 0)
		if (totalMatches === 0) {
			gridEl.style.display = 'none';
			gridEl.innerHTML = '';
			loadMoreWrapEl.style.display = 'none';
			emptyEl.style.display = 'block';
			countBadgeEl.textContent = '(Tìm thấy 0 việc làm phù hợp)';
			return;
		}

		// Results found
		emptyEl.style.display = 'none';
		gridEl.style.display = 'grid';

		var toDisplay = sorted.slice(0, visibleCount);

		var html = '';
		toDisplay.forEach(function(job) {
			var bulletsHtml = '';
			if (job.bullets && job.bullets.length) {
				job.bullets.forEach(function(b) {
					bulletsHtml += '<li>' + escapeHtml(b) + '</li>';
				});
			}

			// Each card is an anchor link pointing directly to the job detail page
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

		// Realtime Result Counter
		var showingCount = Math.min(visibleCount, totalMatches);
		countBadgeEl.textContent = '(Hiển thị ' + showingCount + ' / ' + totalMatches + ' công việc)';

		// "LOAD MORE JOBS" button visibility: automatically hide when X == Y
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

	function onFilterChange() {
		visibleCount = itemsPerPage;
		render();
	}

	// Reset All Filters (Used by "Đặt lại bộ lọc" and "Xóa tất cả")
	function resetAllFilters() {
		currentKeyword  = '';
		currentLocation = '';
		currentCategory = '';
		currentType     = 'all';
		currentSalary   = 'all';
		currentSort     = 'latest';

		if (keywordInput) keywordInput.value = '';
		if (keywordClearBtn) keywordClearBtn.style.display = 'none';
		if (locationSelect) locationSelect.value = '';
		if (categorySelect) categorySelect.value = '';
		if (sortSelect) sortSelect.value = 'latest';

		var typePills = document.querySelectorAll('#qf-type-group .aj-qf-pill');
		typePills.forEach(function(p) {
			p.classList.toggle('active', p.getAttribute('data-type') === 'all');
		});

		var salPills = document.querySelectorAll('#qf-salary-group .aj-qf-pill');
		salPills.forEach(function(p) {
			p.classList.toggle('active', p.getAttribute('data-salary') === 'all');
		});

		onFilterChange();
	}

	// Remove single filter badge via 'x' click
	function removeFilterByKey(key) {
		if (key === 'keyword') {
			currentKeyword = '';
			if (keywordInput) keywordInput.value = '';
			if (keywordClearBtn) keywordClearBtn.style.display = 'none';
		} else if (key === 'location') {
			currentLocation = '';
			if (locationSelect) locationSelect.value = '';
		} else if (key === 'category') {
			currentCategory = '';
			if (categorySelect) categorySelect.value = '';
		} else if (key === 'type') {
			currentType = 'all';
			var typePills = document.querySelectorAll('#qf-type-group .aj-qf-pill');
			typePills.forEach(function(p) {
				p.classList.toggle('active', p.getAttribute('data-type') === 'all');
			});
		} else if (key === 'salary') {
			currentSalary = 'all';
			var salPills = document.querySelectorAll('#qf-salary-group .aj-qf-pill');
			salPills.forEach(function(p) {
				p.classList.toggle('active', p.getAttribute('data-salary') === 'all');
			});
		}
		onFilterChange();
	}

	// Event Listeners
	// Keyword input debounce & clear button
	var debounceTimer = null;
	if (keywordInput) {
		keywordInput.addEventListener('input', function() {
			if (keywordClearBtn) {
				keywordClearBtn.style.display = keywordInput.value.length ? 'block' : 'none';
			}
			clearTimeout(debounceTimer);
			debounceTimer = setTimeout(function() {
				currentKeyword = keywordInput.value;
				onFilterChange();
			}, 250);
		});
		keywordInput.addEventListener('keydown', function(e) {
			if (e.key === 'Enter') {
				clearTimeout(debounceTimer);
				currentKeyword = keywordInput.value;
				onFilterChange();
			}
		});
	}

	if (keywordClearBtn) {
		keywordClearBtn.addEventListener('click', function() {
			keywordInput.value = '';
			keywordClearBtn.style.display = 'none';
			currentKeyword = '';
			onFilterChange();
		});
	}

	// Dropdowns change (realtime)
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

	// Search button (instant apply)
	if (searchBtn) {
		searchBtn.addEventListener('click', function() {
			currentKeyword  = keywordInput ? keywordInput.value : '';
			currentLocation = locationSelect ? locationSelect.value : '';
			currentCategory = categorySelect ? categorySelect.value : '';
			onFilterChange();
		});
	}

	// Sorting Dropdown (instant re-sort without page reload)
	if (sortSelect) {
		sortSelect.addEventListener('change', function() {
			currentSort = sortSelect.value;
			render();
		});
	}

	// Quick Filters: Type
	var typePills = document.querySelectorAll('#qf-type-group .aj-qf-pill');
	typePills.forEach(function(pill) {
		pill.addEventListener('click', function() {
			typePills.forEach(function(p) { p.classList.remove('active'); });
			pill.classList.add('active');
			currentType = pill.getAttribute('data-type');
			onFilterChange();
		});
	});

	// Quick Filters: Salary
	var salPills = document.querySelectorAll('#qf-salary-group .aj-qf-pill');
	salPills.forEach(function(pill) {
		pill.addEventListener('click', function() {
			salPills.forEach(function(p) { p.classList.remove('active'); });
			pill.classList.add('active');
			currentSalary = pill.getAttribute('data-salary');
			onFilterChange();
		});
	});

	// Active Filter Tag 'x' click delegation
	if (afTagsEl) {
		afTagsEl.addEventListener('click', function(e) {
			var removeBtn = e.target.closest('.aj-af-tag-remove');
			if (removeBtn) {
				var key = removeBtn.getAttribute('data-filter-key');
				removeFilterByKey(key);
			}
		});
	}

	// Clear all active filters
	if (clearAllBtn) {
		clearAllBtn.addEventListener('click', function() {
			resetAllFilters();
		});
	}

	// Empty State "Đặt lại bộ lọc" button
	if (resetBtn) {
		resetBtn.addEventListener('click', function() {
			resetAllFilters();
		});
	}

	// "LOAD MORE JOBS" button: load next 6 jobs with smooth animation
	if (loadMoreBtn) {
		loadMoreBtn.addEventListener('click', function(e) {
			e.preventDefault();
			visibleCount += 6;
			render();
		});
	}

	// Initial Render
	render();
})();
</script>

<?php
get_footer();
