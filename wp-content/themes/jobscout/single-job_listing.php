<?php
/**
 * Modern Enterprise Single Job Listing Template (Chuyên biệt cho chức năng Tuyển dụng / Việc làm)
 * Hiển thị chuẩn xác, đầy đủ toàn bộ thông tin bên trong khớp 100% với mô tả và hình ảnh bên ngoài.
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

	// 1. Logo công ty chuẩn xác, trùng khớp với ảnh bên ngoài (Ưu tiên ảnh đại diện mới được cập nhật)
	if ( function_exists( 'jobscout_get_preview_or_job_logo' ) ) {
		$logo_url = jobscout_get_preview_or_job_logo( $job_id );
	} elseif ( has_post_thumbnail( $job_id ) ) {
		$logo_url = get_the_post_thumbnail_url( $job_id, 'full' );
	} else {
		$logo_url = get_post_meta( $job_id, '_company_logo', true );
	}
	if ( empty( $logo_url ) ) {
		$logo_url = 'http://wordpress.local/wp-content/uploads/2026/10/company-default.svg';
	}
	if ( function_exists( 'jobscout_normalize_media_host' ) ) {
		$logo_url = jobscout_normalize_media_host( $logo_url );
	}

	// 2. Tính toán hạn nộp hồ sơ
	$exp_timestamp = null;
	if ( ! empty( $job_expires ) ) {
		$exp_timestamp = strtotime( $job_expires );
	}

	$can_apply = ! $is_filled && 'publish' === $post_status && ( empty( $exp_timestamp ) || $exp_timestamp > time() );

	// Phân loại hình thức ứng tuyển
	$is_email = is_email( $application );
	if ( $is_email ) {
		$apply_url = 'mailto:' . esc_attr( $application ) . '?subject=' . rawurlencode( 'Ứng tuyển vị trí ' . get_the_title() . ' - [Họ tên ứng viên]' );
	} else {
		$apply_url = ! empty( $application ) ? esc_url( $application ) : '#apply-box';
	}
?>

<style>
	/* Ẩn header mặc định và sidebar thừa của theme để dành 100% không gian cho trang việc làm */
	.single-job_listing .site-content > header.entry-header {
		display: none !important;
	}
	.single-job_listing #secondary {
		display: none !important;
	}
	.single-job_listing #primary {
		width: 100% !important;
		float: none !important;
		max-width: 100% !important;
		padding: 0 !important;
		margin: 0 !important;
	}
	.single-job-page-container {
		max-width: 1200px !important;
		margin: 25px auto 60px auto !important;
		padding: 0 15px !important;
		font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Nunito Sans", sans-serif !important;
		box-sizing: border-box !important;
	}

	/* BREADCRUMB & BACK BUTTON */
	.sjb-nav-bar {
		display: flex !important;
		align-items: center !important;
		justify-content: space-between !important;
		margin-bottom: 20px !important;
		flex-wrap: wrap !important;
		gap: 12px !important;
	}
	.sjb-back-link {
		display: inline-flex !important;
		align-items: center !important;
		gap: 8px !important;
		color: #2563eb !important;
		font-weight: 600 !important;
		font-size: 14.5px !important;
		text-decoration: none !important;
		background: #eff6ff !important;
		padding: 8px 16px !important;
		border-radius: 6px !important;
		border: 1px solid #bfdbfe !important;
		transition: all 0.2s ease !important;
	}
	.sjb-back-link:hover {
		background: #2563eb !important;
		color: #ffffff !important;
		transform: translateX(-2px) !important;
	}
	.sjb-breadcrumbs {
		font-size: 13.5px !important;
		color: #64748b !important;
	}
	.sjb-breadcrumbs a {
		color: #64748b !important;
		text-decoration: none !important;
	}
	.sjb-breadcrumbs a:hover {
		color: #2563eb !important;
	}

	/* HERO HEADER CARD: NỔI BẬT, ĐẦY ĐỦ LOGO, TIÊU ĐỀ & METADATA */
	.sjb-hero-card {
		background: #ffffff !important;
		border: 1px solid #e2e8f0 !important;
		border-radius: 12px !important;
		padding: 28px 32px !important;
		box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04) !important;
		margin-bottom: 28px !important;
		display: flex !important;
		gap: 28px !important;
		align-items: flex-start !important;
		flex-wrap: wrap !important;
	}
	.sjb-hero-logo {
		width: 100px !important;
		height: 100px !important;
		min-width: 100px !important;
		border-radius: 10px !important;
		border: 1px solid #e2e8f0 !important;
		background: #ffffff !important;
		display: flex !important;
		align-items: center !important;
		justify-content: center !important;
		padding: 6px !important;
		box-sizing: border-box !important;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04) !important;
		overflow: hidden !important;
	}
	.sjb-hero-logo img {
		max-width: 100% !important;
		max-height: 100% !important;
		width: auto !important;
		height: auto !important;
		object-fit: contain !important;
		object-position: center !important;
		display: block !important;
		margin: auto !important;
		padding: 0 !important;
	}
	.sjb-hero-body {
		flex: 1 1 500px !important;
		min-width: 0 !important;
	}
	.sjb-hero-title {
		font-size: 24px !important;
		font-weight: 800 !important;
		color: #0f172a !important;
		line-height: 1.35 !important;
		margin: 0 0 10px 0 !important;
	}
	.sjb-hero-company {
		font-size: 16px !important;
		font-weight: 600 !important;
		color: #475569 !important;
		display: flex !important;
		align-items: center !important;
		gap: 8px !important;
		margin-bottom: 14px !important;
		flex-wrap: wrap !important;
	}
	.sjb-hero-company a {
		color: #2563eb !important;
		text-decoration: none !important;
	}
	.sjb-hero-badges {
		display: flex !important;
		align-items: center !important;
		gap: 8px !important;
		flex-wrap: wrap !important;
		margin-bottom: 16px !important;
	}
	.sjb-badge {
		display: inline-flex !important;
		align-items: center !important;
		gap: 6px !important;
		padding: 4px 12px !important;
		border-radius: 6px !important;
		font-size: 12.5px !important;
		font-weight: 600 !important;
		line-height: 1.2 !important;
	}
	.sjb-badge-type {
		background: #eff6ff !important;
		color: #1d4ed8 !important;
		border: 1px solid #bfdbfe !important;
	}
	.sjb-badge-category {
		background: #f8fafc !important;
		color: #475569 !important;
		border: 1px solid #e2e8f0 !important;
	}
	.sjb-badge-status-publish {
		background: #dcfce7 !important;
		color: #15803d !important;
		border: 1px solid #bbf7d0 !important;
	}
	.sjb-badge-status-filled {
		background: #f1f5f9 !important;
		color: #475569 !important;
		border: 1px solid #cbd5e1 !important;
	}

	/* QUICK STATS ROW: LƯƠNG, ĐỊA ĐIỂM, HẠN NỘP, NGÀY ĐĂNG */
	.sjb-stats-grid {
		display: grid !important;
		grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)) !important;
		gap: 14px !important;
		padding-top: 14px !important;
		border-top: 1px solid #f1f5f9 !important;
	}
	.sjb-stat-item {
		display: flex !important;
		align-items: center !important;
		gap: 10px !important;
	}
	.sjb-stat-icon {
		width: 38px !important;
		height: 38px !important;
		min-width: 38px !important;
		border-radius: 8px !important;
		display: flex !important;
		align-items: center !important;
		justify-content: center !important;
		font-size: 15px !important;
	}
	.sjb-stat-icon.salary { background: #ecfdf5 !important; color: #059669 !important; }
	.sjb-stat-icon.location { background: #fef2f2 !important; color: #dc2626 !important; }
	.sjb-stat-icon.deadline { background: #fff7ed !important; color: #ea580c !important; }
	.sjb-stat-icon.posted { background: #f0fdf4 !important; color: #16a34a !important; }
	.sjb-stat-content {
		font-size: 12px !important;
		color: #64748b !important;
		line-height: 1.3 !important;
	}
	.sjb-stat-content strong {
		display: block !important;
		font-size: 14px !important;
		color: #0f172a !important;
		font-weight: 700 !important;
	}

	/* HERO ACTION: NÚT ỨNG TUYỂN */
	.sjb-hero-actions {
		display: flex !important;
		flex-direction: column !important;
		gap: 10px !important;
		justify-content: center !important;
		align-items: flex-end !important;
		min-width: 200px !important;
	}
	.sjb-btn-apply {
		background: #27c96d !important;
		color: #ffffff !important;
		font-size: 16px !important;
		font-weight: 700 !important;
		padding: 14px 28px !important;
		border-radius: 8px !important;
		text-decoration: none !important;
		display: inline-flex !important;
		align-items: center !important;
		justify-content: center !important;
		gap: 10px !important;
		box-shadow: 0 4px 14px rgba(39, 201, 109, 0.35) !important;
		transition: all 0.2s ease !important;
		width: 100% !important;
		box-sizing: border-box !important;
		text-align: center !important;
	}
	.sjb-btn-apply:hover {
		background: #21b360 !important;
		transform: translateY(-2px) !important;
		box-shadow: 0 6px 18px rgba(39, 201, 109, 0.45) !important;
		color: #ffffff !important;
	}
	.sjb-btn-apply.disabled {
		background: #94a3b8 !important;
		box-shadow: none !important;
		cursor: not-allowed !important;
	}

	/* BỐ CỤC 2 CỘT: NỘI DUNG CHÍNH (70%) + SIDEBAR THÔNG TIN (30%) */
	.sjb-main-layout {
		display: grid !important;
		grid-template-columns: 1fr 340px !important;
		gap: 28px !important;
		align-items: start !important;
	}
	@media (max-width: 960px) {
		.sjb-main-layout {
			grid-template-columns: 1fr !important;
		}
		.sjb-hero-actions {
			align-items: stretch !important;
			width: 100% !important;
		}
		.sjb-success-alert-banner {
			flex-direction: column !important;
			align-items: flex-start !important;
		}
	}

	/* CARD NỘI DUNG */
	.sjb-card {
		background: #ffffff !important;
		border: 1px solid #e2e8f0 !important;
		border-radius: 12px !important;
		padding: 28px !important;
		box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03) !important;
		margin-bottom: 24px !important;
	}
	.sjb-card-title {
		font-size: 18px !important;
		font-weight: 700 !important;
		color: #0f172a !important;
		margin: 0 0 18px 0 !important;
		padding-bottom: 12px !important;
		border-bottom: 2px solid #f1f5f9 !important;
		display: flex !important;
		align-items: center !important;
		gap: 10px !important;
	}
	.sjb-card-title i {
		color: #2563eb !important;
	}

	/* NỘI DUNG MÔ TẢ VIỆC LÀM */
	.sjb-description-content {
		font-size: 15px !important;
		line-height: 1.8 !important;
		color: #334155 !important;
	}
	.sjb-description-content ul,
	.sjb-description-content ol {
		padding-left: 20px !important;
		margin: 14px 0 !important;
	}
	.sjb-description-content li {
		margin-bottom: 10px !important;
		position: relative !important;
	}

	/* KHUNG NỘP HỒ SƠ ỨNG TUYỂN */
	.sjb-apply-box {
		background: #f8fafc !important;
		border: 1px dashed #cbd5e1 !important;
		border-radius: 10px !important;
		padding: 22px !important;
		margin-top: 24px !important;
		text-align: center !important;
	}
	.sjb-apply-box h4 {
		margin: 0 0 8px 0 !important;
		font-size: 17px !important;
		color: #0f172a !important;
		font-weight: 700 !important;
	}
	.sjb-apply-box p {
		margin: 0 0 14px 0 !important;
		font-size: 14px !important;
		color: #64748b !important;
	}

	/* SIDEBAR ITEM */
	.sjb-side-item {
		display: flex !important;
		justify-content: space-between !important;
		padding: 12px 0 !important;
		border-bottom: 1px solid #f1f5f9 !important;
		font-size: 14px !important;
	}
	.sjb-side-item:last-child {
		border-bottom: none !important;
	}
	.sjb-side-label {
		color: #64748b !important;
		display: flex !important;
		align-items: center !important;
		gap: 8px !important;
	}
	.sjb-side-val {
		color: #0f172a !important;
		font-weight: 600 !important;
		text-align: right !important;
	}
</style>

<div class="single-job-page-container">

	<!-- THANH ĐIỀU HƯỚNG BREADCRUMB & QUAY LẠI -->
	<div class="sjb-nav-bar">
		<a href="<?php echo esc_url( home_url( '/jobs/' ) ); ?>" class="sjb-back-link">
			<i class="fas fa-arrow-left"></i>
			<span>Quay lại danh sách việc làm</span>
		</a>
		<div class="sjb-breadcrumbs">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Trang chủ</a> &rsaquo;
			<a href="<?php echo esc_url( home_url( '/jobs/' ) ); ?>">Việc làm</a>
			<?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : 
				$first_cat = reset( $categories ); ?>
				&rsaquo; <a href="<?php echo esc_url( get_term_link( $first_cat ) ); ?>"><?php echo esc_html( $first_cat->name ); ?></a>
			<?php endif; ?>
			&rsaquo; <span><?php the_title(); ?></span>
		</div>
	</div>

	<?php if ( isset( $_GET['submitted'] ) || isset( $_GET['success'] ) ) : ?>
		<!-- THÔNG BÁO XÁC NHẬN ĐĂNG TIN THÀNH CÔNG TRỰC TIẾP TRÊN TRANG TIN TUYỂN DỤNG -->
		<div class="sjb-success-alert-banner" style="background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border: 1.5px solid #6ee7b7; border-radius: 12px; padding: 18px 24px; margin-bottom: 24px; box-shadow: 0 4px 16px rgba(16, 185, 129, 0.12); display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
			<div style="display: flex; align-items: center; gap: 14px;">
				<div style="width: 44px; height: 44px; border-radius: 50%; background: #10b981; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; box-shadow: 0 3px 8px rgba(16, 185, 129, 0.35);">
					<i class="fas fa-check"></i>
				</div>
				<div>
					<h4 style="margin: 0; font-size: 17px; font-weight: 800; color: #065f46;">🎉 Đăng tin tuyển dụng thành công!</h4>
					<p style="margin: 3px 0 0 0; font-size: 14px; color: #047857;">Tin tuyển dụng của bạn đã được xuất bản và đang hiển thị công khai trên hệ thống Việc làm.</p>
				</div>
			</div>
			<div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
				<a href="<?php echo esc_url( home_url( '/jobs/' ) ); ?>" style="background: #ffffff; color: #065f46; border: 1.5px solid #a7f3d0; padding: 8px 16px; border-radius: 7px; font-size: 13.5px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
					<i class="fas fa-th-list"></i> Xem danh sách việc làm
				</a>
				<a href="<?php echo esc_url( home_url( '/post-a-job/' ) ); ?>" style="background: #10b981; color: #ffffff; padding: 8px 16px; border-radius: 7px; font-size: 13.5px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 6px rgba(16, 185, 129, 0.25);">
					<i class="fas fa-plus"></i> Đăng thêm tin mới
				</a>
			</div>
		</div>
	<?php endif; ?>

	<!-- HERO HEADER CARD: KHỚP 100% VỚI THẺ BÊN NGOÀI -->
	<div class="sjb-hero-card">
		<div class="sjb-hero-logo">
			<img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( $company_name ?: get_the_title() ); ?>" />
		</div>

		<div class="sjb-hero-body">
			<h1 class="sjb-hero-title"><?php the_title(); ?></h1>

			<div class="sjb-hero-company">
				<i class="fas fa-building" style="color: #64748b;"></i>
				<span><?php echo esc_html( $company_name ?: 'Đơn vị tuyển dụng' ); ?></span>
				<?php if ( ! empty( $company_web ) ) : ?>
					<span style="color: #cbd5e1;">•</span>
					<a href="<?php echo esc_url( $company_web ); ?>" target="_blank" rel="nofollow noopener">
						<i class="fas fa-globe"></i> Website công ty
					</a>
				<?php endif; ?>
			</div>

			<div class="sjb-hero-badges">
				<?php if ( ! empty( $types ) ) : foreach ( $types as $t ) : ?>
					<span class="sjb-badge sjb-badge-type">
						<i class="fas fa-briefcase"></i> <?php echo esc_html( $t->name ); ?>
					</span>
				<?php endforeach; endif; ?>

				<?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : foreach ( $categories as $cat ) : ?>
					<span class="sjb-badge sjb-badge-category">
						<i class="fas fa-folder"></i> <?php echo esc_html( $cat->name ); ?>
					</span>
				<?php endforeach; endif; ?>

				<?php if ( $is_filled ) : ?>
					<span class="sjb-badge sjb-badge-status-filled">
						<i class="fas fa-check-circle"></i> ĐÃ TUYỂN ĐỦ
					</span>
				<?php elseif ( 'publish' === $post_status ) : ?>
					<span class="sjb-badge sjb-badge-status-publish">
						<i class="fas fa-check"></i> ĐANG TUYỂN DỤNG
					</span>
				<?php endif; ?>
			</div>

			<!-- THÔNG SỐ NHANH -->
			<div class="sjb-stats-grid">
				<div class="sjb-stat-item">
					<div class="sjb-stat-icon salary"><i class="fas fa-money-bill-wave"></i></div>
					<div class="sjb-stat-content">
						<span>Mức lương:</span>
						<strong><?php echo esc_html( $job_salary ?: 'Thỏa thuận' ); ?></strong>
					</div>
				</div>

				<div class="sjb-stat-item">
					<div class="sjb-stat-icon location"><i class="fas fa-map-marker-alt"></i></div>
					<div class="sjb-stat-content">
						<span>Địa điểm:</span>
						<strong><?php echo esc_html( $location ?: 'Toàn quốc' ); ?></strong>
					</div>
				</div>

				<div class="sjb-stat-item">
					<div class="sjb-stat-icon posted"><i class="far fa-clock"></i></div>
					<div class="sjb-stat-content">
						<span>Ngày đăng:</span>
						<strong><?php echo esc_html( get_the_date( 'd/m/Y' ) ); ?></strong>
					</div>
				</div>

				<div class="sjb-stat-item">
					<div class="sjb-stat-icon deadline"><i class="far fa-calendar-alt"></i></div>
					<div class="sjb-stat-content">
						<span>Hạn nộp hồ sơ:</span>
						<strong><?php echo ! empty( $exp_timestamp ) ? esc_html( date( 'd/m/Y', $exp_timestamp ) ) : 'Không giới hạn'; ?></strong>
					</div>
				</div>
			</div>
		</div>

		<!-- NÚT ỨNG TUYỂN -->
		<div class="sjb-hero-actions">
			<?php if ( $can_apply ) : ?>
				<a href="<?php echo esc_url( $apply_url ); ?>" class="sjb-btn-apply" <?php echo ! $is_email ? 'target="_blank" rel="noopener"' : ''; ?>>
					<i class="fas fa-paper-plane"></i>
					<span>ỨNG TUYỂN NGAY</span>
				</a>
			<?php else : ?>
				<div class="sjb-btn-apply disabled">
					<i class="fas fa-lock"></i>
					<span>ĐÃ DỪNG NHẬN CV</span>
				</div>
			<?php endif; ?>
			<span style="font-size: 12.5px; color: #64748b; text-align: center; width: 100%;">Ứng tuyển nhanh chóng & bảo mật</span>
		</div>
	</div>

	<!-- BỐ CỤC 2 CỘT -->
	<div class="sjb-main-layout">

		<!-- CỘT TRÁI: MÔ TẢ CHI TIẾT CÔNG VIỆC -->
		<div class="sjb-main-content">
			
			<div class="sjb-card">
				<h3 class="sjb-card-title">
					<i class="fas fa-file-alt"></i>
					<span>Mô tả chi tiết công việc</span>
				</h3>
				<div class="sjb-description-content">
					<?php the_content(); ?>
				</div>
			</div>

			<!-- KHU VỰC HƯỚNG DẪN ỨNG TUYỂN -->
			<div class="sjb-card" id="apply-box">
				<h3 class="sjb-card-title">
					<i class="fas fa-envelope-open-text"></i>
					<span>Cách thức nộp hồ sơ ứng tuyển</span>
				</h3>

				<?php if ( $can_apply ) : ?>
					<div class="sjb-apply-box">
						<h4>Sẵn sàng gia nhập đội ngũ cùng <?php echo esc_html( $company_name ?: 'chúng tôi' ); ?>?</h4>
						<?php if ( $is_email ) : ?>
							<p>Vui lòng gửi CV và các chứng chỉ liên quan trực tiếp đến hòm thư tuyển dụng:</p>
							<div style="margin: 16px 0;">
								<a href="mailto:<?php echo esc_attr( $application ); ?>" style="font-size: 17px; font-weight: 700; color: #2563eb; background: #ffffff; padding: 10px 20px; border-radius: 6px; border: 1px solid #bfdbfe; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
									<i class="fas fa-envelope"></i> <?php echo esc_html( $application ); ?>
								</a>
							</div>
							<p style="font-size: 13px; color: #64748b; margin-top: 10px;">Tiêu đề email ghi rõ: <strong>[Ứng Tuyển] <?php the_title(); ?> - [Họ Và Tên]</strong></p>
						<?php elseif ( ! empty( $application ) ) : ?>
							<p>Nhấn vào đường dẫn bên dưới để đi đến cổng nộp hồ sơ trực tuyến của doanh nghiệp:</p>
							<a href="<?php echo esc_url( $application ); ?>" target="_blank" rel="noopener nofollow" class="sjb-btn-apply" style="max-width: 280px; margin: 10px auto;">
								<i class="fas fa-external-link-alt"></i> Đến trang nộp hồ sơ
							</a>
						<?php else : ?>
							<p>Vui lòng liên hệ trực tiếp với bộ phận nhân sự của công ty để hoàn tất hồ sơ ứng tuyển.</p>
						<?php endif; ?>
					</div>
				<?php else : ?>
					<div class="sjb-apply-box" style="background: #fef2f2; border-color: #fecaca;">
						<h4 style="color: #dc2626;">Vị trí này hiện đã dừng nhận hồ sơ</h4>
						<p>Vị trí tuyển dụng đã tuyển đủ hoặc đã quá hạn nộp hồ sơ. Bạn hãy tham khảo các việc làm tương tự đang mở tuyển bên dưới nhé!</p>
					</div>
				<?php endif; ?>
			</div>

		</div>

		<!-- CỘT PHẢI: TỔNG QUAN VIỆC LÀM & NHÀ TUYỂN DỤNG -->
		<div class="sjb-sidebar-content">
			
			<!-- CARD TỔNG QUAN VIỆC LÀM -->
			<div class="sjb-card">
				<h3 class="sjb-card-title">
					<i class="fas fa-info-circle"></i>
					<span>Tổng quan việc làm</span>
				</h3>

				<div class="sjb-side-item">
					<span class="sjb-side-label"><i class="fas fa-money-bill-wave"></i> Mức thu nhập:</span>
					<span class="sjb-side-val" style="color: #059669;"><?php echo esc_html( $job_salary ?: 'Thỏa thuận' ); ?></span>
				</div>

				<div class="sjb-side-item">
					<span class="sjb-side-label"><i class="fas fa-map-marker-alt"></i> Nơi làm việc:</span>
					<span class="sjb-side-val"><?php echo esc_html( $location ?: 'Toàn quốc' ); ?></span>
				</div>

				<div class="sjb-side-item">
					<span class="sjb-side-label"><i class="fas fa-briefcase"></i> Hình thức:</span>
					<span class="sjb-side-val">
						<?php 
						if ( ! empty( $types ) ) {
							$type_names = wp_list_pluck( $types, 'name' );
							echo esc_html( implode( ', ', $type_names ) );
						} else {
							echo 'Toàn thời gian';
						}
						?>
					</span>
				</div>

				<div class="sjb-side-item">
					<span class="sjb-side-label"><i class="fas fa-folder"></i> Ngành nghề:</span>
					<span class="sjb-side-val">
						<?php 
						if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
							$cat_names = wp_list_pluck( $categories, 'name' );
							echo esc_html( implode( ', ', $cat_names ) );
						} else {
							echo 'Tuyển dụng chung';
						}
						?>
					</span>
				</div>

				<div class="sjb-side-item">
					<span class="sjb-side-label"><i class="far fa-clock"></i> Ngày đăng:</span>
					<span class="sjb-side-val"><?php echo esc_html( get_the_date( 'd/m/Y' ) ); ?></span>
				</div>

				<div class="sjb-side-item">
					<span class="sjb-side-label"><i class="far fa-calendar-alt"></i> Hạn nộp:</span>
					<span class="sjb-side-val" style="color: #ea580c;">
						<?php echo ! empty( $exp_timestamp ) ? esc_html( date( 'd/m/Y', $exp_timestamp ) ) : 'Không giới hạn'; ?>
					</span>
				</div>

				<div class="sjb-side-item">
					<span class="sjb-side-label"><i class="fas fa-shield-alt"></i> Trạng thái:</span>
					<span class="sjb-side-val">
						<?php echo $is_filled ? 'Đã tuyển đủ' : 'Đang tuyển'; ?>
					</span>
				</div>
			</div>

			<!-- CARD THÔNG TIN NHÀ TUYỂN DỤNG -->
			<div class="sjb-card">
				<h3 class="sjb-card-title">
					<i class="fas fa-building"></i>
					<span>Về nhà tuyển dụng</span>
				</h3>

				<div style="text-align: center; margin-bottom: 16px;">
					<div style="width: 70px; height: 70px; margin: 0 auto 10px auto; border-radius: 8px; border: 1px solid #e2e8f0; background: #ffffff; padding: 6px; box-sizing: border-box; display: flex; align-items: center; justify-content: center;">
						<img src="<?php echo esc_url( $logo_url ); ?>" alt="" style="max-width: 100%; max-height: 100%; object-fit: contain;" />
					</div>
					<h4 style="margin: 0 0 6px 0; font-size: 16px; font-weight: 700; color: #0f172a;"><?php echo esc_html( $company_name ?: 'Đơn vị tuyển dụng' ); ?></h4>
					<?php if ( ! empty( $company_tag ) ) : ?>
						<p style="margin: 0; font-size: 13px; color: #64748b; font-style: italic;"><?php echo esc_html( $company_tag ); ?></p>
					<?php endif; ?>
				</div>

				<?php if ( ! empty( $company_web ) ) : ?>
					<div class="sjb-side-item">
						<span class="sjb-side-label"><i class="fas fa-globe"></i> Website:</span>
						<span class="sjb-side-val">
							<a href="<?php echo esc_url( $company_web ); ?>" target="_blank" rel="nofollow noopener" style="color: #2563eb; text-decoration: none;">Xem website</a>
						</span>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $company_tw ) ) : ?>
					<div class="sjb-side-item">
						<span class="sjb-side-label"><i class="fas fa-share-alt"></i> Mạng xã hội:</span>
						<span class="sjb-side-val">
							<a href="<?php echo esc_url( $company_tw ); ?>" target="_blank" rel="nofollow noopener" style="color: #2563eb; text-decoration: none;">Theo dõi</a>
						</span>
					</div>
				<?php endif; ?>

				<div class="sjb-side-item">
					<span class="sjb-side-label"><i class="fas fa-map-pin"></i> Khu vực:</span>
					<span class="sjb-side-val"><?php echo esc_html( $location ?: 'Việt Nam' ); ?></span>
				</div>
			</div>

		</div>

	</div>

</div>

<?php 
endwhile;

get_footer();
