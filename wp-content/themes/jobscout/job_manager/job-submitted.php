<?php
/**
 * Modern Enterprise Notice when job has been submitted (Vietnamese).
 * Handles both success (publish/pending) and failure states with 100% Vietnamese notifications.
 *
 * @package JobScout
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $wp_post_types;

$job_obj = ( isset( $job ) && $job instanceof WP_Post ) ? $job : ( isset( $job->ID ) ? get_post( $job->ID ) : null );
$dashboard_url = job_manager_get_permalink( 'job_dashboard' );
$post_new_url  = home_url( '/post-a-job/' );
$home_url      = home_url( '/' );

if ( ! $job_obj || 'trash' === $job_obj->post_status ) : ?>

	<!-- MÀN HÌNH THÔNG BÁO THẤT BẠI / PHÁT SINH LỖI -->
	<div class="job-submitted-error-box" style="max-width: 860px; margin: 40px auto 70px auto; background: #ffffff; border: 1.5px solid #fecaca; border-radius: 16px; padding: 45px 36px; text-align: center; box-shadow: 0 10px 30px rgba(239, 68, 68, 0.08); font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Nunito Sans', sans-serif;">
		<div style="width: 72px; height: 72px; background: #fee2e2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto; color: #ef4444; font-size: 34px; box-shadow: 0 4px 14px rgba(239, 68, 68, 0.2);">
			<i class="fas fa-exclamation-triangle"></i>
		</div>

		<h2 style="font-size: 26px; font-weight: 800; color: #991b1b; margin: 0 0 12px 0;">
			<?php esc_html_e( 'Không thể hoàn tất đăng tin tuyển dụng', 'jobscout' ); ?>
		</h2>

		<p style="font-size: 16px; color: #475569; max-width: 620px; margin: 0 auto 32px auto; line-height: 1.6;">
			<?php esc_html_e( 'Hệ thống không tìm thấy thông tin tin tuyển dụng hoặc đã xảy ra lỗi trong quá trình lưu dữ liệu. Vui lòng kiểm tra lại danh sách tin hoặc thử tạo lại tin mới.', 'jobscout' ); ?>
		</p>

		<div style="display: flex; gap: 14px; justify-content: center; align-items: center; flex-wrap: wrap;">
			<a href="<?php echo esc_url( $post_new_url ); ?>" style="background: linear-flex; background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); color: #ffffff; padding: 13px 26px; border-radius: 8px; font-weight: 700; font-size: 15px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);">
				<i class="fas fa-redo"></i> <?php esc_html_e( 'Thử đăng lại tin mới', 'jobscout' ); ?>
			</a>

			<?php if ( $dashboard_url ) : ?>
				<a href="<?php echo esc_url( $dashboard_url ); ?>" style="background: #ffffff; color: #334155; padding: 12px 24px; border-radius: 8px; font-weight: 600; font-size: 15px; text-decoration: none; border: 1.5px solid #cbd5e1; display: inline-flex; align-items: center; gap: 8px;">
					<i class="fas fa-list-alt"></i> <?php esc_html_e( 'Kiểm tra Quản lý tin', 'jobscout' ); ?>
				</a>
			<?php endif; ?>

			<a href="<?php echo esc_url( $home_url ); ?>" style="background: #f8fafc; color: #64748b; padding: 12px 22px; border-radius: 8px; font-weight: 600; font-size: 15px; text-decoration: none; border: 1px solid #e2e8f0; display: inline-flex; align-items: center; gap: 8px;">
				<i class="fas fa-home"></i> <?php esc_html_e( 'Về trang chủ', 'jobscout' ); ?>
			</a>
		</div>
	</div>

<?php else :
	$job_id       = $job_obj->ID;
	$post_status  = $job_obj->post_status;
	$job_url      = get_permalink( $job_id );
	$job_title    = get_the_title( $job_id );
	$company_name = get_post_meta( $job_id, '_company_name', true );
	$location     = get_the_job_location( $job_id );
	$salary       = get_post_meta( $job_id, '_job_salary', true );
	$expires      = get_post_meta( $job_id, '_job_expires', true );
	$types        = wpjm_get_the_job_types( $job_id );
	$categories   = get_the_terms( $job_id, 'job_listing_category' );

	// Resolve company logo
	if ( function_exists( 'jobscout_get_preview_or_job_logo' ) ) {
		$logo_url = jobscout_get_preview_or_job_logo( $job_id );
	} elseif ( has_post_thumbnail( $job_id ) ) {
		$logo_url = get_the_post_thumbnail_url( $job_id, 'full' );
	} else {
		$logo_url = get_post_meta( $job_id, '_company_logo', true );
	}
	if ( empty( $logo_url ) ) {
		$upload_dir = wp_upload_dir();
		$logo_url   = $upload_dir['baseurl'] . '/2026/10/company-default.svg';
	}
	if ( function_exists( 'jobscout_normalize_media_host' ) ) {
		$logo_url = jobscout_normalize_media_host( $logo_url );
	}

	$is_live = ( 'publish' === $post_status );
	do_action( 'job_manager_job_submitted_content_before', $job_obj );
?>

	<!-- MÀN HÌNH XÁC NHẬN ĐĂNG TIN THÀNH CÔNG 100% TIẾNG VIỆT -->
	<div class="job-submitted-success-box" style="max-width: 880px; margin: 35px auto 65px auto; background: #ffffff; border: 1.5px solid <?php echo $is_live ? '#bbf7d0' : '#fef08a'; ?>; border-radius: 16px; padding: 42px 36px; text-align: center; box-shadow: 0 12px 32px <?php echo $is_live ? 'rgba(16, 185, 129, 0.08)' : 'rgba(234, 179, 8, 0.08)'; ?>; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Nunito Sans', sans-serif;">
		
		<!-- ICON HOẠT HỌA TRẠNG THÁI -->
		<div style="width: 76px; height: 76px; background: <?php echo $is_live ? '#dcfce7' : '#fef9c3'; ?>; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto; color: <?php echo $is_live ? '#10b981' : '#ca8a04'; ?>; font-size: 36px; box-shadow: 0 6px 18px <?php echo $is_live ? 'rgba(16, 185, 129, 0.25)' : 'rgba(234, 179, 8, 0.25)'; ?>;">
			<?php if ( $is_live ) : ?>
				<i class="fas fa-check"></i>
			<?php else : ?>
				<i class="fas fa-hourglass-half"></i>
			<?php endif; ?>
		</div>

		<!-- TIÊU ĐỀ CHÚC MỪNG -->
		<h2 style="font-size: 26px; font-weight: 800; color: <?php echo $is_live ? '#065f46' : '#854d0e'; ?>; margin: 0 0 10px 0;">
			<?php if ( $is_live ) : ?>
				<?php esc_html_e( '🎉 ĐĂNG TIN TUYỂN DỤNG THÀNH CÔNG!', 'jobscout' ); ?>
			<?php else : ?>
				<?php esc_html_e( '⏳ TIẾP NHẬN TIN TUYỂN DỤNG THÀNH CÔNG!', 'jobscout' ); ?>
			<?php endif; ?>
		</h2>

		<!-- HUY HIỆU TRẠNG THÁI -->
		<div style="margin-bottom: 14px;">
			<?php if ( $is_live ) : ?>
				<span style="background: #dcfce7; color: #166534; font-size: 13px; font-weight: 700; padding: 4px 14px; border-radius: 20px; display: inline-flex; align-items: center; gap: 6px; border: 1px solid #bbf7d0;">
					<span style="width: 8px; height: 8px; background: #16a34a; border-radius: 50%; display: inline-block;"></span>
					<?php esc_html_e( 'Đang hiển thị trực tuyến', 'jobscout' ); ?>
				</span>
			<?php else : ?>
				<span style="background: #fef9c3; color: #854d0e; font-size: 13px; font-weight: 700; padding: 4px 14px; border-radius: 20px; display: inline-flex; align-items: center; gap: 6px; border: 1px solid #fde047;">
					<span style="width: 8px; height: 8px; background: #eab308; border-radius: 50%; display: inline-block;"></span>
					<?php esc_html_e( 'Đang chờ xét duyệt từ Quản trị viên', 'jobscout' ); ?>
				</span>
			<?php endif; ?>
		</div>

		<!-- LỜI NHẮN CHI TIẾT -->
		<p style="font-size: 15.5px; color: #475569; max-width: 660px; margin: 0 auto 30px auto; line-height: 1.65;">
			<?php if ( $is_live ) : ?>
				<?php printf( esc_html__( 'Tin tuyển dụng "%s" đã được xuất bản và đang hiển thị công khai trên hệ thống Tìm việc JobScout.', 'jobscout' ), '<strong>' . esc_html( $job_title ) . '</strong>' ); ?>
			<?php else : ?>
				<?php printf( esc_html__( 'Tin tuyển dụng "%s" đã được gửi thành công. Ban quản trị hệ thống sẽ duyệt nội dung và kích hoạt hiển thị trong thời gian sớm nhất.', 'jobscout' ), '<strong>' . esc_html( $job_title ) . '</strong>' ); ?>
			<?php endif; ?>
		</p>

		<!-- CARD TÓM TẮT TIN TUYỂN DỤNG VỪA ĐĂNG -->
		<div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 24px 28px; text-align: left; margin-bottom: 34px; box-shadow: 0 4px 12px rgba(0,0,0,0.02);">
			<div style="display: flex; align-items: center; gap: 20px; flex-wrap: wrap;">
				<!-- KHUNG LOGO (THẤY TRỌN VẸN 100% ẢNH KHÔNG BỊ CẮT) -->
				<div style="width: 85px; height: 85px; min-width: 85px; max-width: 85px; border-radius: 10px; border: 1.5px solid #e2e8f0; background: #ffffff; display: flex; align-items: center; justify-content: center; overflow: hidden; padding: 5px; box-sizing: border-box; flex-shrink: 0; box-shadow: 0 2px 6px rgba(0,0,0,0.04);">
					<img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( $company_name ?: $job_title ); ?>" style="max-width: 100%; max-height: 100%; width: auto; height: auto; object-fit: contain; object-position: center; display: block; margin: auto;" />
				</div>

				<div style="flex: 1 1 auto; min-width: 0;">
					<h3 style="font-size: 19px; font-weight: 700; color: #0f172a; margin: 0 0 6px 0;">
						<?php echo esc_html( $job_title ); ?>
					</h3>

					<div style="font-size: 14.5px; font-weight: 600; color: #334155; display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 10px;">
						<span><?php echo esc_html( $company_name ?: 'Doanh nghiệp tuyển dụng' ); ?></span>
						<span style="background: #e0f2fe; color: #0369a1; font-size: 11.5px; font-weight: 600; padding: 2px 8px; border-radius: 14px; display: inline-flex; align-items: center; gap: 4px;">
							<i class="fas fa-check-circle" style="color: #0284c7;"></i> <?php esc_html_e( 'Doanh nghiệp xác thực', 'jobscout' ); ?>
						</span>
					</div>

					<!-- BADGES PHÂN LOẠI -->
					<div style="display: flex; flex-wrap: wrap; gap: 8px;">
						<?php if ( ! empty( $types ) ) : foreach ( $types as $type ) : ?>
							<span style="background: #dcfce7; color: #166534; font-size: 12.5px; font-weight: 600; padding: 4px 10px; border-radius: 5px;">
								<i class="fas fa-briefcase"></i> <?php echo esc_html( $type->name ); ?>
							</span>
						<?php endforeach; endif; ?>

						<?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : foreach ( $categories as $cat ) : ?>
							<span style="background: #e0e7ff; color: #3730a3; font-size: 12.5px; font-weight: 600; padding: 4px 10px; border-radius: 5px;">
								<i class="fas fa-folder"></i> <?php echo esc_html( $cat->name ); ?>
							</span>
						<?php endforeach; endif; ?>

						<span style="background: #ffffff; color: #475569; font-size: 12.5px; font-weight: 600; padding: 4px 10px; border-radius: 5px; border: 1px solid #e2e8f0;">
							<i class="fas fa-map-marker-alt" style="color: #ef4444;"></i> <?php echo esc_html( $location ?: 'Toàn quốc' ); ?>
						</span>

						<?php if ( ! empty( $salary ) ) : ?>
							<span style="background: #fef3c7; color: #92400e; font-size: 12.5px; font-weight: 600; padding: 4px 10px; border-radius: 5px;">
								<i class="fas fa-money-bill-wave"></i> <?php echo esc_html( $salary ); ?>
							</span>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<!-- THÔNG TIN MÃ TIN VÀ THỜI GIAN -->
			<div style="display: flex; justify-content: space-between; align-items: center; margin-top: 18px; padding-top: 14px; border-top: 1px solid #e2e8f0; font-size: 13px; color: #64748b; flex-wrap: wrap; gap: 10px;">
				<div>
					<strong>Mã số tin đăng:</strong> #<?php echo esc_html( $job_id ); ?>
				</div>
				<div>
					<i class="far fa-clock"></i> <strong>Thời gian gửi:</strong> <?php echo esc_html( date_i18n( 'd/m/Y - H:i', strtotime( $job_obj->post_date ) ) ); ?>
				</div>
			</div>
		</div>

		<!-- HỘP ĐẾM NGƯỢC TỰ ĐỘNG CHUYỂN HƯỚNG ĐẾN TIN TUYỂN DỤNG VỪA ĐĂNG -->
		<?php if ( $is_live ) : 
			$destination_job_url = add_query_arg( 'submitted', '1', $job_url );
		?>
			<div style="margin: 0 auto 30px auto; max-width: 650px; background: #ecfdf5; border: 1.5px solid #a7f3d0; border-radius: 12px; padding: 18px 24px; text-align: center; box-shadow: 0 4px 14px rgba(16, 185, 129, 0.08);">
				<div style="display: flex; align-items: center; justify-content: center; gap: 10px; color: #065f46; font-size: 15.5px; font-weight: 700; margin-bottom: 12px;">
					<i class="fas fa-paper-plane" style="color: #10b981; font-size: 18px;"></i>
					<span>Đang đưa bạn đến trang tin tuyển dụng trong <strong id="redirect-counter" style="color: #047857; font-size: 20px; font-weight: 900; background: #d1fae5; padding: 2px 8px; border-radius: 6px;">2</strong> giây...</span>
				</div>
				<div style="width: 100%; height: 6px; background: #d1fae5; border-radius: 10px; overflow: hidden;">
					<div id="redirect-progress-bar" style="height: 100%; width: 100%; background: linear-gradient(90deg, #10b981, #059669); border-radius: 10px; transition: width 0.8s linear;"></div>
				</div>
			</div>
		<?php endif; ?>

		<!-- HÀNG NÚT ĐIỀU HƯỚNG CHÍNH -->
		<div style="display: flex; gap: 14px; justify-content: center; align-items: center; flex-wrap: wrap;">
			<?php if ( $is_live ) : ?>
				<a href="<?php echo esc_url( $destination_job_url ); ?>" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #ffffff; padding: 14px 32px; border-radius: 9px; font-weight: 800; font-size: 16px; text-decoration: none; display: inline-flex; align-items: center; gap: 10px; box-shadow: 0 6px 18px rgba(16, 185, 129, 0.4); transition: all 0.25s ease;">
					<i class="fas fa-eye"></i> <?php esc_html_e( 'Xem ngay tin tuyển dụng ➔', 'jobscout' ); ?>
				</a>
			<?php endif; ?>

			<a href="<?php echo esc_url( home_url( '/jobs/' ) ); ?>" style="background: #eff6ff; color: #1d4ed8; padding: 13px 24px; border-radius: 8px; font-weight: 700; font-size: 15px; text-decoration: none; border: 1.5px solid #bfdbfe; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s ease;">
				<i class="fas fa-th-list"></i> <?php esc_html_e( 'Xem danh sách việc làm', 'jobscout' ); ?>
			</a>

			<?php if ( $dashboard_url ) : ?>
				<a href="<?php echo esc_url( $dashboard_url ); ?>" style="background: #ffffff; color: #334155; padding: 13px 24px; border-radius: 8px; font-weight: 600; font-size: 15px; text-decoration: none; border: 1.5px solid #cbd5e1; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s ease;">
					<i class="fas fa-tasks"></i> <?php esc_html_e( 'Quản lý tin tuyển dụng', 'jobscout' ); ?>
				</a>
			<?php endif; ?>

			<a href="<?php echo esc_url( $post_new_url ); ?>" style="background: #fff7ed; color: #ea580c; padding: 13px 24px; border-radius: 8px; font-weight: 700; font-size: 15px; text-decoration: none; border: 1.5px solid #fdba74; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s ease;">
				<i class="fas fa-plus-circle"></i> <?php esc_html_e( 'Đăng thêm tin tuyển dụng khác', 'jobscout' ); ?>
			</a>

			<a href="<?php echo esc_url( $home_url ); ?>" style="background: #f8fafc; color: #64748b; padding: 13px 20px; border-radius: 8px; font-weight: 600; font-size: 15px; text-decoration: none; border: 1px solid #e2e8f0; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s ease;">
				<i class="fas fa-home"></i> <?php esc_html_e( 'Trang chủ', 'jobscout' ); ?>
			</a>
		</div>

		<?php if ( $is_live ) : ?>
		<script type="text/javascript">
		(function() {
			var seconds = 2;
			var counterEl = document.getElementById('redirect-counter');
			var progressEl = document.getElementById('redirect-progress-bar');
			var targetUrl = <?php echo wp_json_encode( $destination_job_url ); ?>;

			var interval = setInterval(function() {
				seconds--;
				if (counterEl) {
					counterEl.textContent = seconds;
				}
				if (progressEl) {
					progressEl.style.width = ((seconds / 2) * 100) + '%';
				}
				if (seconds <= 0) {
					clearInterval(interval);
					window.location.href = targetUrl;
				}
			}, 1000);
		})();
		</script>
		<?php endif; ?>

	</div>

	<?php
	do_action( 'job_manager_job_submitted_content_after', sanitize_title( $post_status ), $job_obj );
endif;
?>
