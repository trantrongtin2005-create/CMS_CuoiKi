<?php
/**
 * Single Job Listing Template — Matched 100% to "5-job detail.jpg" Design
 *
 * Refined Features:
 * 1. Background #F5F5F5, flat design, border-less white cards with crisp 4px border-radius.
 * 2. Breadcrumb: Home / All Jobs / Job Detail (Orange links, Gray active).
 * 3. Job Header Box: Flat white card, Logo, Title, Date, 3 Badge pills, SHARE & APPLY buttons.
 * 4. Content (2:1):
 *    - Left: Narrative "Overview about Company" (dedicated company intro), "Our Key Skills" (bullet points),
 *            "Why You'll Love Working Here", "Location".
 *    - Right Sidebar:
 *        + "Staff Rating": Score 4.0 in orange #E87722, 4 filled orange stars + 5th star empty outline in orange.
 *        + "Company Photos": 1:1 aspect-square realistic Unsplash image with bg-black/40 overlay & bold "+5" in center.
 * 5. OTHER JOBS:
 *    - 3 separate badge tags (Fulltime, Category, Location) without "|".
 *    - Explicit round bullet dots on each of 3 description lines.
 *    - Reliable logo resolution including Viettel Cloud DevOps position.
 *
 * @package JobScout
 */

get_header();

while ( have_posts() ) : the_post();
	$job_id       = get_the_ID();
	$company_name = get_post_meta( $job_id, '_company_name', true );
	$company_web  = get_post_meta( $job_id, '_company_website', true );
	$company_tw   = get_post_meta( $job_id, '_company_twitter', true );
	$company_tag  = get_post_meta( $job_id, '_company_tagline', true );
	$job_salary   = get_post_meta( $job_id, '_job_salary', true );
	$job_expires  = get_post_meta( $job_id, '_job_expires', true );
	$application  = get_post_meta( $job_id, '_application', true );
	$location     = get_the_job_location( $job_id );
	$is_filled    = is_position_filled( $job_id );
	$post_status  = get_post_status( $job_id );
	$categories   = get_the_terms( $job_id, 'job_listing_category' );
	$types        = wpjm_get_the_job_types( $job_id );

	// Resolve Logo
	if ( has_post_thumbnail( $job_id ) ) {
		$logo_url = get_the_post_thumbnail_url( $job_id, 'full' );
	} else {
		$logo_url = get_post_meta( $job_id, '_company_logo', true );
	}
	if ( empty( $logo_url ) ) {
		if ( false !== stripos( get_the_title(), 'An Toàn Thông Tin' ) || false !== stripos( $company_name, 'Viettel' ) ) {
			$logo_url = home_url( '/wp-content/uploads/2026/10/viettel-logo.svg' );
		} else {
			$logo_url = get_template_directory_uri() . '/images/company-default.svg';
		}
	}
	if ( function_exists( 'jobscout_normalize_media_host' ) ) {
		$logo_url = jobscout_normalize_media_host( $logo_url );
	}

	// Expiry & Apply
	$exp_timestamp = ! empty( $job_expires ) ? strtotime( $job_expires ) : null;
	$can_apply = ! $is_filled && 'publish' === $post_status && ( empty( $exp_timestamp ) || $exp_timestamp > time() );

	$is_email = is_email( $application );
	if ( $is_email ) {
		$apply_url = 'mailto:' . esc_attr( $application ) . '?subject=' . rawurlencode( wp_specialchars_decode( 'Ứng tuyển vị trí ' . get_the_title() . ' - [Họ tên ứng viên]', ENT_QUOTES ) );
	} else {
		$apply_url = ! empty( $application ) ? esc_url( $application ) : '#apply-box';
	}

	// Tag Pills
	$type_name = 'Toàn thời gian';
	if ( ! empty( $types ) ) {
		$first_type = reset( $types );
		$type_name  = $first_type->name;
	}
	$cat_name = 'Công nghệ thông tin';
	if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
		$first_cat = reset( $categories );
		$cat_name  = $first_cat->name;
	}
	$loc_display = ! empty( $location ) ? trim( strip_tags( $location ) ) : 'Hồ Chí Minh';

	// Realistic Unsplash Company Photos
	$default_unsplash_photos = array(
		'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1200&q=80',
		'https://images.unsplash.com/photo-1497215728101-856f4ea42174?auto=format&fit=crop&w=1200&q=80',
		'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=1200&q=80',
		'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?auto=format&fit=crop&w=1200&q=80',
		'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80',
		'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=1200&q=80',
	);

	// Staff Rating: 4.0 default
	$staff_rating = 4.0;

	// Company Overview Generator (Narrative paragraph, not repeating Key Skills)
	function jd_custom_company_overview( $company, $title ) {
		$company_lower = mb_strtolower( $company, 'UTF-8' );
		if ( strpos( $company_lower, 'momo' ) !== false ) {
			return 'MoMo (Công ty Cổ phần Dịch vụ Di động Trực tuyến - M_Service) là một trong những siêu ứng dụng tài chính hàng đầu tại Việt Nam, phục vụ hơn 30 triệu người dùng với hệ sinh thái dịch vụ đa dạng từ thanh toán, chuyển tiền, tiết kiệm đến đầu tư tài chính số. Chúng tôi không ngừng đổi mới công nghệ và mở rộng hệ sinh thái số nhằm nâng cao chất lượng cuộc sống người Việt thông qua các giải pháp tài chính tiện lợi, an toàn và toàn diện.';
		}
		if ( strpos( $company_lower, 'vng' ) !== false ) {
			return 'VNG là công ty công nghệ hàng đầu Việt Nam và một trong những kỳ lân công nghệ nổi bật tại khu vực Đông Nam Á. Khởi đầu từ năm 2004, VNG hiện phát triển mạnh mẽ trên 4 mảng cốt lõi: Trò chơi trực tuyến, Nền tảng kết nối (Zalo), Tài chính số (ZaloPay) và Điện toán đám mây (VNG Cloud), mang lại những trải nghiệm số tuyệt vời cho hàng chục triệu người dùng mỗi ngày.';
		}
		if ( strpos( $company_lower, 'viettel' ) !== false ) {
			return 'Tập đoàn Công nghiệp - Viễn thông Quân đội (Viettel) là doanh nghiệp viễn thông và công nghệ thông tin lớn nhất Việt Nam, thuộc Top 15 doanh nghiệp viễn thông hàng đầu thế giới về số lượng thuê bao. Viettel cam kết kiến tạo xã hội số với hạ tầng mạng lưới hiện đại, giải pháp an ninh mạng tối tân và nền tảng điện toán đám mây tiên phong phục vụ công cuộc chuyển đổi số quốc gia.';
		}
		if ( strpos( $company_lower, 'shopee' ) !== false ) {
			return 'Shopee là nền tảng thương mại điện tử hàng đầu tại Đông Nam Á và Đài Loan. Ra mắt từ năm 2015, Shopee mang đến cho người dùng trải nghiệm mua sắm trực tuyến đơn giản, an toàn và liền mạch thông qua hệ thống thanh toán tiện ích cùng mạng lưới hậu cần vận hành chuyên nghiệp, kết nối hàng triệu người mua và người bán mỗi ngày.';
		}
		if ( strpos( $company_lower, 'fpt' ) !== false ) {
			return 'Tập đoàn FPT là tập đoàn công nghệ thông tin và viễn thông hàng đầu Việt Nam với hơn 35 năm hình thành và phát triển. FPT tiên phong trong cuộc cách mạng chuyển đổi số, cung cấp các giải pháp phần mềm, công nghệ trí tuệ nhân tạo (AI), điện toán đám mây và viễn thông cho các đối tác toàn cầu tại hơn 30 quốc gia trên thế giới.';
		}
		if ( strpos( $company_lower, 'vinmec' ) !== false ) {
			return 'Vinmec là hệ thống y tế phi lợi nhuận do Tập đoàn Vingroup đầu tư phát triển, tiên phong trong sứ mệnh chăm sóc sức khỏe chất lượng cao đạt chuẩn quốc tế tại Việt Nam. Với đội ngũ chuyên gia bác sĩ đầu ngành, cơ sở vật chất chuẩn JCI hiện đại và trang thiết bị y khoa tối tân, Vinmec mang đến trải nghiệm khám chữa bệnh toàn diện và tin cậy bậc nhất.';
		}
		if ( strpos( $company_lower, 'vingroup' ) !== false ) {
			return 'Tập đoàn Vingroup là tập đoàn kinh tế tư nhân đa ngành hàng đầu Việt Nam, tập trung vào 3 trụ cột cốt lõi: Công nghệ - Công nghiệp, Thương mại Dịch vụ và Thiện nguyện Xã hội. Vingroup không ngừng nỗ lực kiến tạo những công trình tầm cỡ, sản phẩm và dịch vụ đẳng cấp thế giới, góp phần nâng cao chất lượng cuộc sống người Việt và nâng tầm vị thế thương hiệu Việt Nam trên trường quốc tế.';
		}
		if ( strpos( $company_lower, 'coteccons' ) !== false ) {
			return 'Coteccons là nhà thầu xây dựng hàng đầu Việt Nam, ghi dấu ấn sâu sắc với hàng loạt công trình quy mô lớn mang tính biểu tượng quốc gia như Landmark 81. Với năng lực tổng thầu Thiết kế & Thi công (D&B) vượt trội, Coteccons cam kết mang đến những công trình chất lượng đỉnh cao, tiến độ thần tốc và an toàn tuyệt đối theo tiêu chuẩn quốc tế.';
		}
		if ( strpos( $company_lower, 'golden gate' ) !== false ) {
			return 'Golden Gate Group là tập đoàn tiên phong áp dụng mô hình chuỗi nhà hàng ẩm thực tại Việt Nam, sở hữu hơn 20 thương hiệu nổi tiếng được yêu thích rộng rãi (Manwah, Gogi House, Kichi-Kichi, Sumo BBQ,...). Với mạng lưới hàng trăm nhà hàng trên toàn quốc, Golden Gate không ngừng sáng tạo ẩm thực và nâng tầm trải nghiệm dịch vụ khách hàng.';
		}
		if ( strpos( $company_lower, 'vus' ) !== false ) {
			return 'Hệ thống Anh văn Hội Việt Mỹ (VUS) là tổ chức đào tạo Anh ngữ uy tín hàng đầu tại Việt Nam với chứng nhận chất lượng quốc tế NEAS. VUS đồng hành cùng các thế hệ học viên xây dựng nền tảng Anh ngữ vững chắc, trang bị kỹ năng thế kỷ 21 và mở ra cơ hội hội nhập toàn cầu thông qua các chương trình giáo dục tiên tiến.';
		}
		return 'Được thành lập với sứ mệnh tiên phong kiến tạo giá trị đột phá, ' . esc_html( $company ?: 'chúng tôi' ) . ' là một trong những đơn vị phát triển vững mạnh trong ngành. Với đội ngũ nhân sự tận tâm, môi trường làm việc sáng tạo cùng định hướng phát triển bền vững, công ty không ngừng mở rộng quy mô và mang lại giá trị thiết thực cho khách hàng và đối tác.';
	}
?>

<!-- ============================================================
     CSS: Job Detail Page — Matched 100% to "5-job detail.jpg"
     ============================================================ -->
<style id="jd-design-styles">
	/* Typography */
	@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap');

	/* 1. Global Reset & Flat Background #F5F5F5 */
	body.single-job_listing,
	.single-job_listing #page,
	.single-job_listing #content,
	.single-job_listing .site-content,
	.single-job_listing .site-main {
		background-color: #F5F5F5 !important;
	}

	.single-job_listing .site-content > header.entry-header { display: none !important; }
	.single-job_listing #secondary { display: none !important; }
	.single-job_listing #primary {
		width: 100% !important; float: none !important; max-width: 100% !important;
		padding: 0 !important; margin: 0 !important; background: transparent !important;
	}
	.single-job_listing .site-content .container { max-width: 100% !important; padding: 0 !important; }

	/* Page Container */
	.jd-page {
		max-width: 1200px;
		margin: 0 auto;
		padding: 0 20px 60px;
		font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
		box-sizing: border-box;
		color: #333333;
	}

	/* Breadcrumb */
	.jd-breadcrumb {
		padding: 20px 0 16px;
		font-size: 13px;
		font-weight: 500;
		display: flex;
		align-items: center;
		gap: 6px;
		flex-wrap: wrap;
	}
	.jd-breadcrumb a {
		color: #E87722;
		text-decoration: none;
		transition: color 0.2s;
	}
	.jd-breadcrumb a:hover {
		color: #c2410c;
		text-decoration: underline;
	}
	.jd-breadcrumb .jd-bc-sep {
		color: #9ca3af;
		margin: 0 2px;
	}
	.jd-breadcrumb .jd-bc-current {
		color: #9ca3af;
		font-weight: 500;
	}

	/* Job Header Box: Flat white card, NO border, crisp 4px radius */
	.jd-header-box {
		background: #ffffff;
		border: none;
		border-radius: 4px;
		box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
		padding: 28px 30px;
		display: flex;
		align-items: flex-start;
		gap: 24px;
		margin-bottom: 24px;
	}
	.jd-header-logo {
		width: 86px;
		height: 86px;
		min-width: 86px;
		background: #ffffff;
		border: 1px solid #e5e7eb;
		border-radius: 4px;
		display: flex;
		align-items: center;
		justify-content: center;
		overflow: hidden;
		box-sizing: border-box;
		padding: 8px;
	}
	.jd-header-logo img {
		max-width: 95%;
		max-height: 95%;
		width: 100%;
		height: 100%;
		object-fit: contain;
		display: block;
		margin: auto;
	}
	.jd-header-info {
		flex: 1 1 auto;
		min-width: 0;
	}
	.jd-header-title {
		font-size: 18px;
		font-weight: 800;
		color: #1a1a1a;
		text-transform: uppercase;
		letter-spacing: 0.3px;
		margin: 0 0 6px 0;
		line-height: 1.35;
		word-break: break-word;
	}
	.jd-header-date {
		font-size: 12px;
		color: #9ca3af;
		font-weight: 500;
		margin-bottom: 10px;
	}
	.jd-header-tags {
		display: flex;
		align-items: center;
		gap: 8px;
		flex-wrap: wrap;
	}
	.jd-tag {
		display: inline-block;
		background: #f3f4f6;
		border: 1px solid #e5e7eb;
		border-radius: 3px;
		padding: 4px 12px;
		font-size: 12px;
		color: #4b5563;
		font-weight: 500;
		white-space: nowrap;
	}

	/* SHARE & APPLY BUTTONS */
	.jd-header-actions {
		display: flex;
		flex-direction: column;
		gap: 10px;
		align-items: stretch;
		min-width: 150px;
		flex-shrink: 0;
	}
	.jd-btn-share {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		gap: 8px;
		padding: 9px 22px;
		border: 1px solid #1a1a1a;
		border-radius: 4px;
		background: #fff;
		color: #1a1a1a;
		font-size: 13px;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: 0.5px;
		cursor: pointer;
		text-decoration: none;
		transition: all 0.2s;
		font-family: inherit;
	}
	.jd-btn-share:hover {
		background: #1a1a1a;
		color: #fff;
	}
	.jd-btn-share svg { width: 14px; height: 14px; }

	.jd-btn-apply-top {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		gap: 8px;
		padding: 9px 22px;
		border: 1px solid #E87722;
		border-radius: 4px;
		background: #fff;
		color: #E87722;
		font-size: 13px;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: 0.5px;
		cursor: pointer;
		text-decoration: none;
		transition: all 0.2s;
		font-family: inherit;
	}
	.jd-btn-apply-top:hover {
		background: #E87722;
		color: #fff;
	}
	.jd-btn-apply-top.disabled {
		border-color: #9ca3af;
		color: #9ca3af;
		cursor: not-allowed;
		opacity: 0.6;
	}

	/* ===== TWO-COLUMN LAYOUT (2:1) ===== */
	.jd-layout {
		display: grid;
		grid-template-columns: 2fr 1fr;
		gap: 24px;
		align-items: start;
	}

	/* LEFT COLUMN: Flat White Card, NO border, 4px radius */
	.jd-content { min-width: 0; }
	.jd-content-card {
		background: #ffffff;
		border: none;
		border-radius: 4px;
		box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
		padding: 30px 32px;
	}
	.jd-section { margin-bottom: 28px; }
	.jd-section:last-child { margin-bottom: 0; }
	.jd-section-title {
		font-size: 16px;
		font-weight: 800;
		color: #1a1a1a;
		margin: 0 0 14px 0;
		line-height: 1.4;
	}
	.jd-section-body {
		font-size: 14px;
		line-height: 1.85;
		color: #4b5563;
		font-weight: 400;
	}
	.jd-section-body p { margin: 0 0 12px 0; }
	.jd-section-body p:last-child { margin-bottom: 0; }
	.jd-section-body ul, .jd-section-body ol {
		padding-left: 20px;
		margin: 8px 0;
	}
	.jd-section-body li {
		margin-bottom: 6px;
		line-height: 1.7;
	}

	/* RIGHT SIDEBAR */
	.jd-sidebar {
		display: flex;
		flex-direction: column;
		gap: 24px;
	}

	/* 2. Staff Rating Card: Flat White, NO border, 4px radius */
	.jd-rating-card {
		background: #ffffff;
		border: none;
		border-radius: 4px;
		box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
		padding: 22px 24px;
	}
	.jd-rating-label {
		font-size: 15px;
		font-weight: 800;
		color: #1a1a1a;
		margin: 0 0 14px 0;
	}
	.jd-rating-row {
		display: flex;
		align-items: center;
		gap: 8px;
	}
	.jd-stars {
		display: inline-flex;
		align-items: center;
		gap: 4px;
	}
	.jd-star {
		width: 22px;
		height: 22px;
		display: inline-flex;
		align-items: center;
		justify-content: center;
	}
	.jd-star svg {
		width: 22px;
		height: 22px;
		display: block;
	}
	/* 4.0 score matching design image (medium dark gray) */
	.jd-rating-score {
		font-size: 18px;
		font-weight: 700;
		color: #4b5563;
		margin-left: 8px;
		line-height: 1;
	}

	/* 1. Company Photos Card: Aspect Square with bg-black/40 overlay & white bold +5 */
	.jd-photos-card {
		background: #ffffff;
		border: none;
		border-radius: 4px;
		box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
		padding: 22px 24px;
	}
	.jd-photos-label {
		font-size: 15px;
		font-weight: 800;
		color: #1a1a1a;
		margin: 0 0 14px 0;
	}
	.jd-photo-single {
		position: relative;
		width: 100%;
		aspect-ratio: 1 / 1;
		border-radius: 4px;
		overflow: hidden;
		cursor: pointer;
		background: #e5e7eb;
	}
	.jd-photo-single img {
		width: 100%;
		height: 100%;
		object-fit: cover;
		display: block;
		transition: transform 0.35s ease;
	}
	.jd-photo-single:hover img {
		transform: scale(1.04);
	}
	/* Overlay: bg-black/40 with +5 white bold centered */
	.jd-photo-overlay {
		position: absolute;
		inset: 0;
		background: rgba(0, 0, 0, 0.4);
		display: flex;
		align-items: center;
		justify-content: center;
		color: #ffffff;
		font-size: 32px;
		font-weight: 800;
		letter-spacing: 0.5px;
		user-select: none;
		transition: background 0.25s ease;
	}
	.jd-photo-single:hover .jd-photo-overlay {
		background: rgba(0, 0, 0, 0.3);
	}

	/* ===== 3. OTHER JOBS SECTION ===== */
	.jd-other-jobs {
		margin-top: 50px;
		padding-top: 36px;
	}
	.jd-other-jobs-title {
		text-align: center;
		font-size: 22px;
		font-weight: 800;
		color: #1a1a1a;
		text-transform: uppercase;
		letter-spacing: 1px;
		margin: 0 0 32px 0;
	}
	.jd-other-jobs-grid {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 22px;
	}

	/* Flat Job Card, NO border, 4px radius */
	.jd-job-card {
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
	}
	.jd-job-card:hover {
		box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
		transform: translateY(-3px);
	}
	.jd-job-card-top {
		display: flex;
		align-items: flex-start;
		gap: 16px;
	}
	.jd-job-card-logo {
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
	}
	.jd-job-card:hover .jd-job-card-logo {
		border-color: #d1d5db;
	}
	.jd-job-card-logo img {
		max-width: 92%;
		max-height: 92%;
		width: 100%;
		height: 100%;
		object-fit: contain;
		display: block;
		margin: auto;
	}
	.jd-job-card-info {
		flex: 1 1 auto;
		min-width: 0;
	}
	.jd-job-card-name {
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
	.jd-job-card:hover .jd-job-card-name {
		color: #E87722;
	}
	.jd-job-card-date {
		font-size: 12px;
		color: #94a3b8;
		margin-bottom: 8px;
		font-weight: 500;
	}

	/* 3.1: 3 separate badge tags — Anti-wrap on a single line */
	.jd-job-card-tags {
		display: flex;
		align-items: center;
		gap: 5px;
		flex-wrap: nowrap;
		overflow: hidden;
	}
	.jd-job-card-tags .jd-tag {
		font-size: 11px;
		padding: 2px 7px;
		line-height: 1.35;
		white-space: nowrap;
		flex-shrink: 0;
	}

	/* 3.2: 3 bullet points with explicit round bullet dots */
	.jd-job-card-bottom {
		margin-top: 14px;
	}
	.jd-job-card-bullets {
		margin: 0;
		padding: 0;
		list-style: none;
		display: flex;
		flex-direction: column;
		gap: 6px;
	}
	.jd-job-card-bullets li {
		position: relative;
		padding-left: 15px;
		font-size: 13px;
		color: #4b5563;
		line-height: 1.5;
		margin: 0;
	}
	.jd-job-card-bullets li::before {
		content: "";
		position: absolute;
		left: 2px;
		top: 7px;
		width: 5px;
		height: 5px;
		border-radius: 50%;
		background-color: #4b5563;
	}

	/* Lightbox */
	.jd-lightbox-overlay {
		display: none;
		position: fixed;
		top: 0; left: 0; right: 0; bottom: 0;
		background: rgba(0, 0, 0, 0.85);
		z-index: 99999;
		align-items: center;
		justify-content: center;
	}
	.jd-lightbox-overlay.active { display: flex; }
	.jd-lightbox-inner {
		position: relative;
		max-width: 90vw;
		max-height: 90vh;
	}
	.jd-lightbox-img {
		max-width: 100%;
		max-height: 85vh;
		border-radius: 4px;
		box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
	}
	.jd-lightbox-close {
		position: absolute;
		top: -14px;
		right: -14px;
		width: 36px;
		height: 36px;
		background: #ffffff;
		border: none;
		border-radius: 50%;
		cursor: pointer;
		font-size: 20px;
		font-weight: 700;
		color: #333;
		display: flex;
		align-items: center;
		justify-content: center;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
	}
	.jd-lightbox-prev, .jd-lightbox-next {
		position: absolute;
		top: 50%;
		transform: translateY(-50%);
		width: 42px;
		height: 42px;
		background: rgba(255, 255, 255, 0.9);
		border: none;
		border-radius: 50%;
		cursor: pointer;
		font-size: 20px;
		color: #333;
		display: flex;
		align-items: center;
		justify-content: center;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
		transition: background 0.2s;
	}
	.jd-lightbox-prev:hover, .jd-lightbox-next:hover { background: #fff; }
	.jd-lightbox-prev { left: -54px; }
	.jd-lightbox-next { right: -54px; }

	/* Toast */
	.jd-share-toast {
		position: fixed;
		bottom: 30px;
		left: 50%;
		transform: translateX(-50%);
		background: #1a1a1a;
		color: #fff;
		padding: 12px 28px;
		border-radius: 4px;
		font-size: 14px;
		font-weight: 600;
		z-index: 99998;
		box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
		opacity: 0;
		transition: opacity 0.3s;
		pointer-events: none;
	}
	.jd-share-toast.show { opacity: 1; }

	/* Responsive */
	@media (max-width: 960px) {
		.jd-layout { grid-template-columns: 1fr; }
		.jd-header-box { flex-direction: column; gap: 16px; }
		.jd-header-actions { flex-direction: row; width: 100%; min-width: 0; }
		.jd-header-actions > * { flex: 1; }
		.jd-other-jobs-grid { grid-template-columns: 1fr; }
		.jd-lightbox-prev { left: 10px; }
		.jd-lightbox-next { right: 10px; }
	}
	@media (max-width: 640px) {
		.jd-header-actions { flex-direction: column; }
	}
</style>

<!-- ============================================================
     HTML MARKUP
     ============================================================ -->
<div class="jd-page">

	<!-- BREADCRUMB: Home / All Jobs / Job Detail -->
	<nav class="jd-breadcrumb" aria-label="Breadcrumb">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
		<span class="jd-bc-sep">/</span>
		<a href="<?php echo esc_url( home_url( '/jobs/' ) ); ?>">All Jobs</a>
		<span class="jd-bc-sep">/</span>
		<span class="jd-bc-current">Job Detail</span>
	</nav>

	<!-- JOB HEADER BOX: Flat white card, NO border, 4px radius -->
	<div class="jd-header-box">
		<!-- Logo -->
		<div class="jd-header-logo">
			<img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( $company_name ?: get_the_title() ); ?>" />
		</div>

		<!-- Center Info -->
		<div class="jd-header-info">
			<h1 class="jd-header-title"><?php the_title(); ?></h1>
			<div class="jd-header-date">Created: <?php echo esc_html( get_the_date( 'M d, Y' ) ); ?></div>
			<div class="jd-header-tags">
				<span class="jd-tag"><?php echo esc_html( $type_name ); ?></span>
				<span class="jd-tag"><?php echo esc_html( $cat_name ); ?></span>
				<span class="jd-tag"><?php echo esc_html( $loc_display ); ?></span>
			</div>
		</div>

		<!-- Right Buttons -->
		<div class="jd-header-actions">
			<button type="button" class="jd-btn-share" id="jd-share-btn" title="Copy link to clipboard">
				SHARE
			</button>
			<?php if ( $can_apply ) : ?>
				<a href="<?php echo esc_url( $apply_url ); ?>" class="jd-btn-apply-top" <?php echo ! $is_email ? 'target="_blank" rel="noopener"' : ''; ?>>
					APPLY JOB
				</a>
			<?php else : ?>
				<span class="jd-btn-apply-top disabled">CLOSED</span>
			<?php endif; ?>
		</div>
	</div>

	<!-- TWO-COLUMN LAYOUT -->
	<div class="jd-layout">

		<!-- LEFT COLUMN: Content Sections -->
		<div class="jd-content">
			<div class="jd-content-card">

				<!-- 4. Overview about Company: Narrative intro, not repeating Key Skills -->
				<div class="jd-section">
					<h2 class="jd-section-title">Overview about Company</h2>
					<div class="jd-section-body">
						<p><?php echo esc_html( jd_custom_company_overview( $company_name, get_the_title() ) ); ?></p>
					</div>
				</div>

				<!-- Our Key Skills: Technical bullet points from post content -->
				<div class="jd-section">
					<h2 class="jd-section-title">Our Key Skills</h2>
					<div class="jd-section-body">
						<?php
						$key_skills = get_post_meta( $job_id, '_key_skills', true );
						if ( ! empty( $key_skills ) ) {
							echo wp_kses_post( wpautop( $key_skills ) );
						} else {
							// Extract bullet points from content
							$raw = get_the_content();
							$skill_bullets = array();
							if ( preg_match_all( '/<li[^>]*>(.*?)<\/li>/is', $raw, $sm ) ) {
								foreach ( $sm[1] as $s ) {
									$cleaned = trim( strip_tags( $s ) );
									if ( ! empty( $cleaned ) ) {
										$skill_bullets[] = $cleaned;
									}
								}
							}
							if ( ! empty( $skill_bullets ) ) {
								echo '<ul>';
								foreach ( $skill_bullets as $sb ) {
									echo '<li>' . esc_html( $sb ) . '</li>';
								}
								echo '</ul>';
							} else {
								echo '<p>Thành thạo kỹ năng chuyên môn phù hợp với vị trí tuyển dụng, tinh thần làm việc nhóm tích cực và tư duy giải quyết vấn đề hiệu quả.</p>';
							}
						}
						?>
					</div>
				</div>

				<!-- Why You'll Love Working Here (kèm mức lương) -->
				<div class="jd-section">
					<h2 class="jd-section-title">Why You'll Love Working Here</h2>
					<div class="jd-section-body">
						<?php
						$why_work = get_post_meta( $job_id, '_why_work_here', true );
						if ( ! empty( $why_work ) ) {
							echo wp_kses_post( wpautop( $why_work ) );
						} else {
							echo '<p>Môi trường làm việc chuyên nghiệp, cởi mở và khuyến khích sáng tạo đột phá. Chế độ đãi ngộ cạnh tranh trên thị trường, xét tăng lương định kỳ cùng lộ trình thăng tiến rõ ràng.</p>';
						}
						if ( ! empty( $job_salary ) ) {
							echo '<p style="margin-top: 10px;">Mức lương / Salary: <strong style="color: #E87722;">' . esc_html( $job_salary ) . '</strong></p>';
						}
						?>
					</div>
				</div>

				<!-- Location -->
				<div class="jd-section">
					<h2 class="jd-section-title">Location</h2>
					<div class="jd-section-body">
						<p><?php echo esc_html( $loc_display ); ?></p>
						<?php if ( ! empty( $company_web ) ) : ?>
							<p>Website: <a href="<?php echo esc_url( $company_web ); ?>" target="_blank" rel="noopener" style="color: #E87722; text-decoration: underline;"><?php echo esc_html( $company_web ); ?></a></p>
						<?php endif; ?>
					</div>
				</div>

			</div>
		</div>

		<!-- RIGHT SIDEBAR -->
		<div class="jd-sidebar">

			<!-- 2. Staff Rating Card: 4.0, 5th star is light gray outline star matching design -->
			<div class="jd-rating-card">
				<h3 class="jd-rating-label">Staff Rating</h3>
				<div class="jd-rating-row">
					<div class="jd-stars">
						<?php
						// 4 filled orange stars (#E87722)
						$filled_star_svg = '<svg viewBox="0 0 24 24"><polygon fill="#E87722" points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>';
						// 5th star: outline star with subtle gray #9ca3af border matching screenshot
						$outline_star_svg = '<svg viewBox="0 0 24 24"><polygon fill="none" stroke="#9ca3af" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>';

						for ( $i = 0; $i < 4; $i++ ) {
							echo '<span class="jd-star">' . $filled_star_svg . '</span>';
						}
						echo '<span class="jd-star">' . $outline_star_svg . '</span>';
						?>
					</div>
					<span class="jd-rating-score">4.0</span>
				</div>
			</div>

			<!-- 1. Company Photos Card: aspect-square realistic Unsplash image with bg-black/40 overlay & bold "+5" -->
			<div class="jd-photos-card">
				<h3 class="jd-photos-label">Company Photos</h3>
				<div class="jd-photo-single" onclick="jdOpenLightbox(0)" title="Xem thêm ảnh công ty">
					<img src="<?php echo esc_url( $default_unsplash_photos[0] ); ?>" alt="Company Workplace" loading="lazy" />
					<div class="jd-photo-overlay">+5</div>
				</div>
			</div>

		</div>

	</div><!-- .jd-layout -->

	<!-- ===== 3. OTHER JOBS ===== -->
	<?php
	// Query 6 related jobs (excluding current)
	$related_args = array(
		'post_type'      => 'job_listing',
		'posts_per_page' => 6,
		'post_status'    => 'publish',
		'post__not_in'   => array( $job_id ),
		'orderby'        => 'rand',
	);
	if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
		$cat_ids = wp_list_pluck( $categories, 'term_id' );
		$related_args['tax_query'] = array(
			array(
				'taxonomy' => 'job_listing_category',
				'field'    => 'term_id',
				'terms'    => $cat_ids,
			),
		);
	}
	$related_jobs = new WP_Query( $related_args );

	// Fallback to fill up to 6 jobs if category has fewer
	if ( $related_jobs->post_count < 6 ) {
		$exclude_ids = array( $job_id );
		if ( $related_jobs->have_posts() ) {
			foreach ( $related_jobs->posts as $rp ) {
				$exclude_ids[] = $rp->ID;
			}
		}
		$fill_args = array(
			'post_type'      => 'job_listing',
			'posts_per_page' => 6 - $related_jobs->post_count,
			'post_status'    => 'publish',
			'post__not_in'   => $exclude_ids,
			'orderby'        => 'rand',
		);
		$fill_jobs = get_posts( $fill_args );
	} else {
		$fill_jobs = array();
	}

	$all_related = array();
	if ( $related_jobs->have_posts() ) {
		foreach ( $related_jobs->posts as $rp ) {
			$all_related[] = $rp;
		}
	}
	foreach ( $fill_jobs as $fj ) {
		$all_related[] = $fj;
	}
	$all_related = array_slice( $all_related, 0, 6 );
	wp_reset_postdata();

	if ( ! empty( $all_related ) ) :
	?>
	<div class="jd-other-jobs">
		<h2 class="jd-other-jobs-title">OTHER JOBS</h2>
		<div class="jd-other-jobs-grid">
			<?php foreach ( $all_related as $rjob ) :
				$rj_id       = $rjob->ID;
				$rj_company  = get_post_meta( $rj_id, '_company_name', true );
				$rj_cats     = get_the_terms( $rj_id, 'job_listing_category' );
				$rj_types    = wpjm_get_the_job_types( $rj_id );
				$rj_location = get_the_job_location( $rj_id );

				// Logo resolution with full fallback for Cloud DevOps position
				if ( has_post_thumbnail( $rj_id ) ) {
					$rj_logo = get_the_post_thumbnail_url( $rj_id, 'thumbnail' );
				} else {
					$rj_logo = get_post_meta( $rj_id, '_company_logo', true );
				}
				if ( empty( $rj_logo ) || false !== stripos( $rjob->post_title, 'An Toàn Thông Tin' ) || false !== stripos( $rj_company, 'Viettel' ) ) {
					$rj_logo = home_url( '/wp-content/uploads/2026/10/viettel-logo.svg' );
				}
				if ( empty( $rj_logo ) ) {
					$rj_logo = get_template_directory_uri() . '/images/company-default.svg';
				}
				if ( function_exists( 'jobscout_normalize_media_host' ) ) {
					$rj_logo = jobscout_normalize_media_host( $rj_logo );
				}

				// Date
				$rj_date = 'Created: ' . get_the_date( 'M d, Y', $rj_id );

				// 3 separate badges
				$rj_type_label = 'Toàn thời gian';
				if ( ! empty( $rj_types ) ) {
					$first_t = reset( $rj_types );
					$rj_type_label = $first_t->name;
				}
				$rj_cat_label = 'Công nghệ thông tin';
				if ( ! empty( $rj_cats ) && ! is_wp_error( $rj_cats ) ) {
					$first_c = reset( $rj_cats );
					$rj_cat_label = $first_c->name;
				}
				$rj_loc_label = ! empty( $rj_location ) ? trim( strip_tags( $rj_location ) ) : 'Hồ Chí Minh';

				// 3 bullet points
				$rj_content = $rjob->post_content;
				$rj_bullets = array();
				if ( preg_match_all( '/<li[^>]*>(.*?)<\/li>/is', $rj_content, $bm ) ) {
					foreach ( $bm[1] as $b ) {
						$bc = trim( strip_tags( $b ) );
						if ( ! empty( $bc ) ) {
							$rj_bullets[] = $bc;
							if ( count( $rj_bullets ) >= 3 ) break;
						}
					}
				}
				$rj_defaults = array(
					'Đảm bảo chất lượng công việc và hoàn thành xuất sắc các mục tiêu được giao.',
					'Môi trường làm việc năng động, chuyên nghiệp, chế độ đãi ngộ hấp dẫn.',
					'Chế độ bảo hiểm toàn diện và các khóa đào tạo nâng cao kỹ năng thường niên.',
				);
				while ( count( $rj_bullets ) < 3 ) {
					$rj_bullets[] = array_shift( $rj_defaults );
				}
			?>
			<a href="<?php echo esc_url( get_permalink( $rj_id ) ); ?>" class="jd-job-card">
				<div class="jd-job-card-top">
					<div class="jd-job-card-logo">
						<img src="<?php echo esc_url( $rj_logo ); ?>" alt="<?php echo esc_attr( $rj_company ?: $rjob->post_title ); ?>" />
					</div>
					<div class="jd-job-card-info">
						<h3 class="jd-job-card-name"><?php echo esc_html( $rjob->post_title ); ?></h3>
						<div class="jd-job-card-date"><?php echo esc_html( $rj_date ); ?></div>
						<!-- 3 separate badge tags, NO "|" separator -->
						<div class="jd-job-card-tags">
							<span class="jd-tag"><?php echo esc_html( $rj_type_label ); ?></span>
							<span class="jd-tag"><?php echo esc_html( $rj_cat_label ); ?></span>
							<span class="jd-tag"><?php echo esc_html( $rj_loc_label ); ?></span>
						</div>
					</div>
				</div>
				<!-- 3 bullet lines with round dots -->
				<div class="jd-job-card-bottom">
					<ul class="jd-job-card-bullets">
						<?php foreach ( array_slice( $rj_bullets, 0, 3 ) as $bullet ) : ?>
							<li><?php echo esc_html( $bullet ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			</a>
			<?php endforeach; ?>
		</div>
	</div>
	<?php endif; ?>

</div><!-- .jd-page -->

<!-- LIGHTBOX for Company Photos -->
<div class="jd-lightbox-overlay" id="jd-lightbox">
	<div class="jd-lightbox-inner">
		<button class="jd-lightbox-close" onclick="jdCloseLightbox()" aria-label="Close">&times;</button>
		<button class="jd-lightbox-prev" onclick="jdLightboxNav(-1)" aria-label="Previous">&#8249;</button>
		<img class="jd-lightbox-img" id="jd-lightbox-img" src="" alt="Company photo" />
		<button class="jd-lightbox-next" onclick="jdLightboxNav(1)" aria-label="Next">&#8250;</button>
	</div>
</div>

<!-- SHARE TOAST -->
<div class="jd-share-toast" id="jd-share-toast">✓ Link copied to clipboard!</div>

<!-- JavaScript: Share, Lightbox -->
<script>
(function(){
	// ===== SHARE =====
	var shareBtn = document.getElementById('jd-share-btn');
	if (shareBtn) {
		shareBtn.addEventListener('click', function() {
			var url = window.location.href;
			if (navigator.clipboard && navigator.clipboard.writeText) {
				navigator.clipboard.writeText(url).then(function() { showShareToast(); });
			} else {
				var ta = document.createElement('textarea');
				ta.value = url; ta.style.position = 'fixed'; ta.style.opacity = '0';
				document.body.appendChild(ta); ta.select();
				try { document.execCommand('copy'); showShareToast(); } catch(e) {}
				document.body.removeChild(ta);
			}
		});
	}
	function showShareToast() {
		var toast = document.getElementById('jd-share-toast');
		if (!toast) return;
		toast.classList.add('show');
		setTimeout(function() { toast.classList.remove('show'); }, 2200);
	}

	// ===== LIGHTBOX =====
	var photos = <?php echo wp_json_encode( $default_unsplash_photos ); ?>;
	var currentIdx = 0;
	window.jdOpenLightbox = function(idx) {
		currentIdx = idx;
		var overlay = document.getElementById('jd-lightbox');
		var img = document.getElementById('jd-lightbox-img');
		if (!overlay || !img || !photos[idx]) return;
		img.src = photos[idx];
		overlay.classList.add('active');
		document.body.style.overflow = 'hidden';
	};
	window.jdCloseLightbox = function() {
		var overlay = document.getElementById('jd-lightbox');
		if (overlay) overlay.classList.remove('active');
		document.body.style.overflow = '';
	};
	window.jdLightboxNav = function(dir) {
		currentIdx += dir;
		if (currentIdx < 0) currentIdx = photos.length - 1;
		if (currentIdx >= photos.length) currentIdx = 0;
		var img = document.getElementById('jd-lightbox-img');
		if (img && photos[currentIdx]) img.src = photos[currentIdx];
	};
	var overlay = document.getElementById('jd-lightbox');
	if (overlay) {
		overlay.addEventListener('click', function(e) {
			if (e.target === overlay) jdCloseLightbox();
		});
	}
	document.addEventListener('keydown', function(e) {
		if (e.key === 'Escape') jdCloseLightbox();
		if (e.key === 'ArrowLeft') jdLightboxNav(-1);
		if (e.key === 'ArrowRight') jdLightboxNav(1);
	});
})();
</script>

<?php
endwhile;
get_footer();
