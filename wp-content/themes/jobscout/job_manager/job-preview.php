<?php
/**
 * Modern Enterprise Job listing preview template (Vietnamese).
 * Shows the complete uploaded logo inside the frame, with high-end polished card layout.
 *
 * @package JobScout
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$job_id       = $form->get_job_id();
$job          = get_post( $job_id );
$company_name = get_post_meta( $job_id, '_company_name', true );
$company_web  = get_post_meta( $job_id, '_company_website', true );
$company_tag  = get_post_meta( $job_id, '_company_tagline', true );
$company_tw   = get_post_meta( $job_id, '_company_twitter', true );
$job_salary   = get_post_meta( $job_id, '_job_salary', true );
$job_expires  = get_post_meta( $job_id, '_job_expires', true );
$important_info = get_post_meta( $job_id, '_job_important_info', true );
$application  = get_post_meta( $job_id, '_application', true );
$location     = get_the_job_location( $job_id );
$categories   = get_the_terms( $job_id, 'job_listing_category' );
$types        = wpjm_get_the_job_types( $job_id );

// Resolve company logo (Guaranteed to retrieve newly uploaded logo from upload folder if not yet bound)
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

// Format Deadline
$exp_date_text = '';
if ( ! empty( $job_expires ) ) {
	$exp_time = strtotime( $job_expires );
	if ( $exp_time ) {
		$exp_date_text = date_i18n( 'd/m/Y', $exp_time );
	}
}
?>

<style>
	/* Ẩn link Edit thừa ở chân trang khi preview */
	.post-edit-link,
	.edit-link {
		display: none !important;
	}
	.job-preview-wrapper {
		max-width: 1040px;
		margin: 20px auto 50px auto;
		font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Nunito Sans", sans-serif;
	}
	.job-preview-notice {
		background: linear-gradient(135deg, #f0fdf4 0%, #e0f2fe 100%);
		border: 1px solid #bbf7d0;
		border-radius: 12px;
		padding: 18px 24px;
		margin-bottom: 25px;
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 20px;
		flex-wrap: wrap;
		box-shadow: 0 2px 8px rgba(0,0,0,0.03);
	}
	.job-preview-btn-submit {
		background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
		color: #ffffff !important;
		border: none !important;
		border-radius: 8px !important;
		padding: 13px 28px !important;
		font-weight: 700 !important;
		font-size: 15px !important;
		cursor: pointer !important;
		box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35) !important;
		transition: all 0.25s ease !important;
		display: inline-flex !important;
		align-items: center !important;
		gap: 8px !important;
	}
	.job-preview-btn-submit:hover {
		background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
		transform: translateY(-1px) !important;
		box-shadow: 0 6px 18px rgba(16, 185, 129, 0.45) !important;
	}
	.job-preview-btn-edit {
		background: #ffffff !important;
		color: #334155 !important;
		border: 1.5px solid #cbd5e1 !important;
		border-radius: 8px !important;
		padding: 12px 24px !important;
		font-weight: 600 !important;
		font-size: 15px !important;
		cursor: pointer !important;
		transition: all 0.2s ease !important;
		display: inline-flex !important;
		align-items: center !important;
		gap: 8px !important;
	}
	.job-preview-btn-edit:hover {
		background: #f8fafc !important;
		border-color: #94a3b8 !important;
		color: #0f172a !important;
	}
	.preview-main-card {
		background: #ffffff;
		border: 1px solid #e2e8f0;
		border-radius: 14px;
		padding: 36px 40px;
		box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05), 0 8px 10px -6px rgba(0,0,0,0.03);
	}
	.preview-header-flex {
		display: flex;
		align-items: flex-start;
		gap: 24px;
	}
	.preview-logo-box {
		width: 105px;
		height: 105px;
		min-width: 105px;
		max-width: 105px;
		border-radius: 12px;
		border: 1.5px solid #e2e8f0;
		background: #ffffff;
		display: flex;
		align-items: center;
		justify-content: center;
		overflow: hidden;
		padding: 6px;
		box-sizing: border-box;
		box-shadow: 0 4px 12px rgba(0,0,0,0.04);
		flex-shrink: 0;
	}
	.preview-logo-box img {
		max-width: 100% !important;
		max-height: 100% !important;
		width: auto !important;
		height: auto !important;
		object-fit: contain !important;
		object-position: center !important;
		display: block !important;
		margin: auto !important;
	}
	.preview-badges-wrap {
		display: flex;
		flex-wrap: wrap;
		gap: 10px;
		margin-top: 24px;
		padding-top: 20px;
		border-top: 1px solid #f1f5f9;
	}
	.preview-badge-pill {
		font-size: 13.5px;
		font-weight: 600;
		padding: 7px 15px;
		border-radius: 6px;
		display: inline-flex;
		align-items: center;
		gap: 7px;
	}
	.preview-badge-type { background: #dcfce7; color: #166534; }
	.preview-badge-cat { background: #e0e7ff; color: #3730a3; }
	.preview-badge-loc { background: #f1f5f9; color: #334155; }
	.preview-badge-salary { background: #fef3c7; color: #92400e; }
	.preview-badge-deadline { background: #fee2e2; color: #991b1b; }
	.preview-badge-info { background: #f3e8ff; color: #6b21a8; }
	.preview-badge-date { background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }

	.preview-content-box {
		background: #ffffff;
		border: 1px solid #e2e8f0;
		border-radius: 12px;
		padding: 30px 34px;
		margin-top: 24px;
		box-shadow: 0 4px 15px rgba(0,0,0,0.03);
	}
	.preview-content-box h3 {
		font-size: 18.5px;
		font-weight: 700;
		color: #0f172a;
		margin-top: 0;
		margin-bottom: 20px;
		padding-bottom: 12px;
		border-bottom: 2px solid #e2e8f0;
		display: flex;
		align-items: center;
		gap: 10px;
	}
	.preview-content-body {
		font-size: 15.5px;
		line-height: 1.8;
		color: #334155;
	}
	.preview-content-body p { margin-bottom: 16px; }
	.preview-content-body ul, .preview-content-body ol {
		padding-left: 22px;
		margin-bottom: 18px;
	}
	.preview-content-body li {
		margin-bottom: 8px;
	}
	@media (max-width: 768px) {
		.preview-main-card { padding: 22px; }
		.preview-header-flex { flex-direction: column; align-items: center; text-align: center; }
		.preview-badges-wrap { justify-content: center; }
		.job-preview-actions { width: 100%; justify-content: center; }
	}
</style>

<div class="job-preview-wrapper">
	<form method="post" id="job_preview" class="job-preview-form" action="<?php echo esc_url( $form->get_action() ); ?>">
		<?php do_action( 'preview_job_form_start' ); ?>

		<!-- THANH THÔNG BÁO XEM TRƯỚC -->
		<div class="job-preview-notice">
			<div>
				<div style="display: flex; align-items: center; gap: 8px;">
					<span style="background: #10b981; color: #ffffff; width: 22px; height: 22px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 11px;">
						<i class="fas fa-eye"></i>
					</span>
					<strong style="font-size: 16.5px; color: #065f46;"><?php esc_html_e( 'Bản xem trước tin tuyển dụng', 'jobscout' ); ?></strong>
				</div>
				<p style="margin: 4px 0 0 30px; font-size: 13.5px; color: #047857;">
					<?php esc_html_e( 'Đây là giao diện thực tế ứng viên sẽ nhìn thấy. Vui lòng kiểm tra kỹ hình ảnh và thông tin trước khi xuất bản.', 'jobscout' ); ?>
				</p>
			</div>

			<div class="job-preview-actions" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
				<button type="submit" name="continue" value="<?php echo esc_attr( apply_filters( 'submit_job_step_preview_submit_text', __( 'Xác nhận & Đăng tin tuyển dụng', 'jobscout' ) ) ); ?>" id="job_preview_submit_button" class="button job-manager-button-submit-listing job-preview-btn-submit">
					<i class="fas fa-check-circle"></i> <?php echo esc_html( apply_filters( 'submit_job_step_preview_submit_text', __( 'Xác nhận & Đăng tin tuyển dụng', 'jobscout' ) ) ); ?>
				</button>
				<?php if ( ! WP_Job_Manager_Helper_Renewals::is_renew_action() ) : ?>
					<button type="submit" name="edit_job" value="<?php esc_attr_e( 'Quay lại chỉnh sửa', 'jobscout' ); ?>" class="button job-manager-button-edit-listing job-preview-btn-edit">
						<i class="fas fa-edit"></i> <?php esc_html_e( 'Quay lại chỉnh sửa', 'jobscout' ); ?>
					</button>
				<?php endif; ?>
			</div>
		</div>

		<!-- KHUNG NỘI DUNG CHÍNH CỦA TIN TUYỂN DỤNG -->
		<div class="preview-main-card">
			<div class="preview-header-flex">
				<!-- KHUNG LOGO CÔNG TY: HIỂN THỊ TOÀN BỘ HÌNH ẢNH 100% -->
				<div class="preview-logo-box" title="<?php echo esc_attr( $company_name ?: get_the_title( $job_id ) ); ?>">
					<img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( $company_name ?: get_the_title( $job_id ) ); ?>" />
				</div>

				<!-- THÔNG TIN VỊ TRÍ & CÔNG TY -->
				<div style="flex: 1 1 auto; min-width: 0;">
					<h1 style="font-size: 26px; font-weight: 800; color: #0f172a; margin: 0 0 10px 0; line-height: 1.35;">
						<?php echo esc_html( get_the_title( $job_id ) ); ?>
					</h1>

					<div style="font-size: 16px; font-weight: 700; color: #1e293b; display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
						<span><?php echo esc_html( $company_name ?: 'Doanh nghiệp tuyển dụng' ); ?></span>
						<span style="background: #e0f2fe; color: #0369a1; font-size: 12px; font-weight: 600; padding: 3px 10px; border-radius: 20px; display: inline-flex; align-items: center; gap: 5px;">
							<i class="fas fa-check-circle" style="color: #0284c7;"></i> <?php esc_html_e( 'Doanh nghiệp xác thực', 'jobscout' ); ?>
						</span>
					</div>

					<?php if ( ! empty( $company_tag ) ) : ?>
						<p style="color: #64748b; font-size: 14px; margin: 6px 0 0 0; font-style: italic;">
							<?php echo esc_html( $company_tag ); ?>
						</p>
					<?php endif; ?>

					<?php if ( ! empty( $company_web ) ) : ?>
						<div style="margin-top: 8px;">
							<a href="<?php echo esc_url( $company_web ); ?>" target="_blank" rel="nofollow noopener" style="color: #2563eb; font-size: 14px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
								<i class="fas fa-globe"></i> <?php esc_html_e( 'Website công ty', 'jobscout' ); ?>
							</a>
						</div>
					<?php endif; ?>
				</div>
			</div>

			<!-- CÁC THẺ META THÔNG TIN CHÍNH -->
			<div class="preview-badges-wrap">
				<?php if ( ! empty( $types ) ) : foreach ( $types as $type ) : ?>
					<span class="preview-badge-pill preview-badge-type">
						<i class="fas fa-briefcase"></i> <?php echo esc_html( $type->name ); ?>
					</span>
				<?php endforeach; else : ?>
					<span class="preview-badge-pill preview-badge-type">
						<i class="fas fa-briefcase"></i> <?php esc_html_e( 'Toàn thời gian', 'jobscout' ); ?>
					</span>
				<?php endif; ?>

				<?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : foreach ( $categories as $cat ) : ?>
					<span class="preview-badge-pill preview-badge-cat">
						<i class="fas fa-folder"></i> <?php echo esc_html( $cat->name ); ?>
					</span>
				<?php endforeach; endif; ?>

				<span class="preview-badge-pill preview-badge-loc">
					<i class="fas fa-map-marker-alt" style="color: #ef4444;"></i> <?php echo esc_html( $location ?: 'Toàn quốc' ); ?>
				</span>

				<?php if ( ! empty( $job_salary ) ) : ?>
					<span class="preview-badge-pill preview-badge-salary">
						<i class="fas fa-money-bill-wave"></i> <?php echo esc_html( $job_salary ); ?>
					</span>
				<?php else : ?>
					<span class="preview-badge-pill preview-badge-salary">
						<i class="fas fa-money-bill-wave"></i> <?php esc_html_e( 'Mức lương thỏa thuận', 'jobscout' ); ?>
					</span>
				<?php endif; ?>

				<?php if ( ! empty( $exp_date_text ) ) : ?>
					<span class="preview-badge-pill preview-badge-deadline">
						<i class="far fa-calendar-alt"></i> <?php esc_html_e( 'Hạn nộp:', 'jobscout' ); ?> <?php echo esc_html( $exp_date_text ); ?>
					</span>
				<?php endif; ?>

				<?php if ( ! empty( $important_info ) ) : ?>
					<span class="preview-badge-pill preview-badge-info">
						<i class="fas fa-star"></i> <?php echo esc_html( $important_info ); ?>
					</span>
				<?php endif; ?>

				<span class="preview-badge-pill preview-badge-date">
					<i class="far fa-clock"></i> <?php esc_html_e( 'Đã đăng Vừa xong trước', 'jobscout' ); ?>
				</span>
			</div>
		</div>

		<!-- CHI TIẾT MÔ TẢ CÔNG VIỆC -->
		<div class="preview-content-box">
			<h3>
				<i class="fas fa-file-alt" style="color: #2563eb;"></i>
				<?php esc_html_e( 'Chi tiết công việc & Yêu cầu tuyển dụng', 'jobscout' ); ?>
			</h3>

			<div class="preview-content-body">
				<?php
				$content = $job ? $job->post_content : '';
				if ( ! empty( $content ) ) {
					echo apply_filters( 'the_content', $content );
				} else {
					echo '<p style="color: #94a3b8; font-style: italic;">Chưa có nội dung mô tả chi tiết.</p>';
				}
				?>
			</div>
		</div>

		<!-- HỘP THÔNG TIN ỨNG TUYỂN / LIÊN HỆ -->
		<?php if ( ! empty( $application ) ) : ?>
			<div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 22px 28px; margin-top: 24px; display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
				<div style="display: flex; align-items: center; gap: 14px;">
					<div style="width: 44px; height: 44px; border-radius: 50%; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;">
						<i class="fas fa-paper-plane"></i>
					</div>
					<div>
						<div style="font-size: 13.5px; font-weight: 700; color: #1e293b; text-transform: uppercase; letter-spacing: 0.3px;">
							<?php esc_html_e( 'Hình thức ứng tuyển', 'jobscout' ); ?>
						</div>
						<div style="font-size: 15px; color: #475569; font-weight: 600; margin-top: 2px;">
							<?php echo esc_html( $application ); ?>
						</div>
					</div>
				</div>

				<div>
					<?php if ( is_email( $application ) ) : ?>
						<a href="mailto:<?php echo esc_attr( $application ); ?>" style="background: #2563eb; color: #ffffff; padding: 11px 22px; border-radius: 7px; font-size: 14.5px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 2px 6px rgba(37,99,235,0.25);">
							<i class="fas fa-envelope"></i> <?php esc_html_e( 'Gửi hồ sơ qua Email', 'jobscout' ); ?>
						</a>
					<?php else : ?>
						<a href="<?php echo esc_url( $application ); ?>" target="_blank" rel="nofollow noopener" style="background: #2563eb; color: #ffffff; padding: 11px 22px; border-radius: 7px; font-size: 14.5px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 2px 6px rgba(37,99,235,0.25);">
							<i class="fas fa-external-link-alt"></i> <?php esc_html_e( 'Ứng tuyển qua Website', 'jobscout' ); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>

		<!-- CÁC TRƯỜNG HIDDEN CỦA WP JOB MANAGER -->
		<input type="hidden" name="job_id" value="<?php echo esc_attr( $form->get_job_id() ); ?>" />
		<input type="hidden" name="step" value="<?php echo esc_attr( $form->get_step() ); ?>" />
		<input type="hidden" name="job_manager_form" value="<?php echo esc_attr( $form->get_form_name() ); ?>" />

		<!-- HÀNG NÚT BẤM DƯỚI CÙNG -->
		<div style="display: flex; gap: 14px; align-items: center; margin-top: 32px; justify-content: flex-end; padding-top: 22px; border-top: 1.5px solid #e2e8f0; flex-wrap: wrap;">
			<button type="submit" name="continue" value="<?php echo esc_attr( apply_filters( 'submit_job_step_preview_submit_text', __( 'Xác nhận & Đăng tin tuyển dụng', 'jobscout' ) ) ); ?>" id="job_preview_submit_button_bottom" class="button job-manager-button-submit-listing job-preview-btn-submit">
				<i class="fas fa-check-circle"></i> <?php echo esc_html( apply_filters( 'submit_job_step_preview_submit_text', __( 'Xác nhận & Đăng tin tuyển dụng', 'jobscout' ) ) ); ?>
			</button>
			<?php if ( ! WP_Job_Manager_Helper_Renewals::is_renew_action() ) : ?>
				<button type="submit" name="edit_job" value="<?php esc_attr_e( 'Quay lại chỉnh sửa', 'jobscout' ); ?>" class="button job-manager-button-edit-listing job-preview-btn-edit">
					<i class="fas fa-edit"></i> <?php esc_html_e( 'Quay lại chỉnh sửa', 'jobscout' ); ?>
				</button>
			<?php endif; ?>
		</div>

		<?php do_action( 'preview_job_form_end' ); ?>
	</form>

	<script type="text/javascript">
	jQuery(document).ready(function($) {
		var isSubmitting = false;

		// Xử lý sự kiện click nút Xác nhận & Đăng tin tuyển dụng
		$(document).on('click', '#job_preview_submit_button, #job_preview_submit_button_bottom, .job-preview-btn-submit', function(e) {
			if (isSubmitting) {
				e.preventDefault();
				return false;
			}

			var $form = $('#job_preview');
			$form.find('input[name="edit_job"]').remove();

			// Đảm bảo trường continue luôn luôn hiện diện với giá trị khác rỗng
			var continueVal = '<?php echo esc_attr( apply_filters( 'submit_job_step_preview_submit_text', __( 'Xác nhận & Đăng tin tuyển dụng', 'jobscout' ) ) ); ?>';
			if (!$form.find('input[name="continue"]').length) {
				$form.append('<input type="hidden" name="continue" value="' + continueVal + '" />');
			} else {
				$form.find('input[name="continue"]').val(continueVal);
			}

			isSubmitting = true;
			var $btn = $(this);
			$btn.html('<i class="fas fa-spinner fa-spin"></i> <?php echo esc_js( __( 'Đang xuất bản tin tuyển dụng...', 'jobscout' ) ); ?>');
			$btn.css({'opacity': '0.85', 'pointer-events': 'none'});
			$form.trigger('submit');
		});

		// Xử lý sự kiện click nút Quay lại chỉnh sửa
		$(document).on('click', '.job-preview-btn-edit, .job-manager-button-edit-listing', function(e) {
			var $form = $('#job_preview');
			$form.find('input[name="continue"]').remove();
			if (!$form.find('input[name="edit_job"]').length) {
				$form.append('<input type="hidden" name="edit_job" value="1" />');
			} else {
				$form.find('input[name="edit_job"]').val('1');
			}
		});
	});
	</script>
</div>
