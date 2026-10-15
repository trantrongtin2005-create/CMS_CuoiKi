<?php
/**
 * Job dashboard shortcode content (Vietnamese - Modern Enterprise Recruitment Portal).
 *
 * @package JobScout
 */

use WP_Job_Manager\UI\Notice;
use WP_Job_Manager\UI\UI_Elements;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$submit_job_form_page_id = get_option( 'job_manager_submit_job_form_page_id' );
$submit_job_url = $submit_job_form_page_id ? get_permalink( $submit_job_form_page_id ) : home_url( '/post-a-job/' );

// Vietnamese action dictionary
$vn_actions = array(
	'edit'            => array( 'label' => 'Chỉnh sửa', 'icon' => 'fas fa-edit', 'bg' => '#2563eb', 'color' => '#ffffff', 'border' => '#2563eb' ),
	'mark_filled'     => array( 'label' => 'Đã tuyển đủ', 'icon' => 'fas fa-check-circle', 'bg' => '#ffffff', 'color' => '#475569', 'border' => '#cbd5e1' ),
	'mark_not_filled' => array( 'label' => 'Đang tuyển', 'icon' => 'fas fa-sync-alt', 'bg' => '#ecfdf5', 'color' => '#059669', 'border' => '#a7f3d0' ),
	'duplicate'       => array( 'label' => 'Nhân bản', 'icon' => 'far fa-clone', 'bg' => '#ffffff', 'color' => '#475569', 'border' => '#cbd5e1' ),
	'delete'          => array( 'label' => 'Xóa tin', 'icon' => 'fas fa-trash-alt', 'bg' => '#ef4444', 'color' => '#ffffff', 'border' => '#ef4444' ),
	'relist'          => array( 'label' => 'Đăng lại', 'icon' => 'fas fa-redo', 'bg' => '#ea580c', 'color' => '#ffffff', 'border' => '#ea580c' ),
);
?>

<style>
	/* THÔNG BÁO DASHBOARD: HIỆN MÀU XANH LÁ (EMERALD GREEN) CHUYÊN NGHIỆP, RÕ RÀNG */
	.jm-notice,
	.jm-notice:not(.color-error),
	.jm-notice.color-success,
	.alignwide > .jm-notice,
	.alignwide .jm-notice,
	#job-manager-job-dashboard .jm-notice,
	.job-manager-message {
		background: #ecfdf5 !important;
		background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%) !important;
		border: 1.5px solid #a7f3d0 !important;
		border-left: 6px solid #10b981 !important;
		border-radius: 12px !important;
		padding: 16px 22px !important;
		margin: 20px auto 24px auto !important;
		max-width: 1200px !important;
		color: #065f46 !important;
		font-size: 15px !important;
		font-weight: 600 !important;
		line-height: 1.6 !important;
		box-shadow: 0 4px 16px rgba(16, 185, 129, 0.12) !important;
		display: flex !important;
		align-items: center !important;
		gap: 14px !important;
		list-style: none !important;
		box-sizing: border-box !important;
		width: 100% !important;
	}

	.jm-notice:not(.color-error) .jm-notice__message-wrap {
		display: flex !important;
		align-items: center !important;
		gap: 12px !important;
		color: #065f46 !important;
		width: 100% !important;
	}

	.jm-notice:not(.color-error) .jm-notice__message,
	.jm-notice:not(.color-error) .jm-notice__title {
		color: #065f46 !important;
		font-weight: 700 !important;
		font-size: 15.5px !important;
		line-height: 1.5 !important;
	}

	.jm-notice:not(.color-error) .jm-ui-icon,
	.jm-notice:not(.color-error) svg,
	.jm-notice:not(.color-error) [data-icon="check-circle"] {
		color: #10b981 !important;
		background-color: #10b981 !important;
		fill: #10b981 !important;
		stroke: #10b981 !important;
		width: 22px !important;
		height: 22px !important;
		min-width: 22px !important;
		flex-shrink: 0 !important;
	}

	.jm-notice.color-error {
		background: #fef2f2 !important;
		background: linear-gradient(135deg, #fff5f5 0%, #fef2f2 100%) !important;
		border: 1.5px solid #fecaca !important;
		border-left: 6px solid #ef4444 !important;
		color: #991b1b !important;
		box-shadow: 0 4px 14px rgba(239, 68, 68, 0.08) !important;
	}
	.jm-notice.color-error .jm-notice__message-wrap,
	.jm-notice.color-error .jm-notice__message,
	.jm-notice.color-error .jm-notice__title {
		color: #991b1b !important;
		font-weight: 700 !important;
	}
	.jm-notice.color-error .jm-ui-icon,
	.jm-notice.color-error svg {
		color: #ef4444 !important;
		background-color: #ef4444 !important;
		fill: #ef4444 !important;
	}

	/* Khung bảng cuộn ngang mượt mà nếu màn hình hẹp, đảm bảo không bao giờ bị cắt mất phần chỉnh sửa */
	.job-manager-jobs-custom {
		overflow-x: auto !important;
		-webkit-overflow-scrolling: touch !important;
	}

	/* Grid layout chuẩn: Phân chia 4 cột rõ ràng, canh giữa cân bằng, không rớt dòng */
	.jm-custom-header,
	.jm-custom-job-row {
		display: grid !important;
		grid-template-columns: minmax(280px, 1fr) 175px 115px 365px !important;
		gap: 16px !important;
		align-items: center !important;
		min-width: 950px !important;
	}

	.jm-custom-header {
		padding: 14px 24px !important;
		background: #f8fafc !important;
		border-bottom: 2px solid #e2e8f0 !important;
		font-weight: 700 !important;
		font-size: 13.5px !important;
		color: #334155 !important;
		text-transform: uppercase !important;
		letter-spacing: 0.5px !important;
	}

	.jm-custom-job-row {
		padding: 18px 24px !important;
		border-bottom: 1px solid #f1f5f9 !important;
		transition: background 0.15s ease !important;
	}

	.jm-custom-job-row:hover {
		background: #fafbfd !important;
	}

	/* THANH NÚT THAO TÁC: DÀN 1 HÀNG NGANG CHUẨN ĐẸP 100%, CANH GIỮA CỘT, ĐẦY ĐỦ VÀ KHÔNG BỊ CHE KHUẤT */
	.jm-job-actions-toolbar {
		display: flex !important;
		gap: 6px !important;
		justify-content: center !important;
		align-items: center !important;
		flex-wrap: nowrap !important;
		white-space: nowrap !important;
		min-width: 355px !important;
	}

	.jm-action-btn {
		height: 33px !important;
		padding: 0 11px !important;
		border-radius: 6px !important;
		font-size: 12.5px !important;
		font-weight: 600 !important;
		text-decoration: none !important;
		display: inline-flex !important;
		align-items: center !important;
		justify-content: center !important;
		gap: 5px !important;
		line-height: 1 !important;
		box-sizing: border-box !important;
		white-space: nowrap !important;
		box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05) !important;
		transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
		cursor: pointer !important;
	}

	.jm-action-btn:hover {
		transform: translateY(-1px) !important;
		box-shadow: 0 3px 8px rgba(0, 0, 0, 0.12) !important;
		filter: brightness(0.96) !important;
	}

	.jm-action-edit:hover {
		background: #1d4ed8 !important;
		border-color: #1d4ed8 !important;
		color: #ffffff !important;
	}

	.jm-action-delete:hover {
		background: #dc2626 !important;
		border-color: #dc2626 !important;
		color: #ffffff !important;
	}

	.jm-action-duplicate:hover,
	.jm-action-mark_filled:hover {
		background: #f1f5f9 !important;
		border-color: #94a3b8 !important;
		color: #0f172a !important;
	}

	.jm-action-mark_not_filled:hover {
		background: #d1fae5 !important;
		border-color: #6ee7b7 !important;
	}

	/* Màn hình Mobile nhỏ dưới 768px: chuyển thành dạng Card xem dọc */
	@media (max-width: 768px) {
		.jm-custom-header,
		.jm-custom-job-row {
			min-width: 0 !important;
		}
		.jm-custom-header {
			display: none !important;
		}
		.jm-custom-job-row {
			display: flex !important;
			flex-direction: column !important;
			align-items: stretch !important;
			gap: 12px !important;
			padding: 16px !important;
		}
		.jm-job-actions-toolbar {
			display: grid !important;
			grid-template-columns: repeat(2, 1fr) !important;
			width: 100% !important;
			min-width: 0 !important;
			margin-left: 0 !important;
		}
		.jm-action-btn {
			width: 100% !important;
			height: 36px !important;
			font-size: 13px !important;
		}
	}
</style>

<div id="job-manager-job-dashboard" class="alignwide jm-dashboard jm-ui career-dashboard-container" style="max-width: 1200px; margin: 30px auto 60px auto; padding: 0 15px;">

	<!-- DASHBOARD HEADER TOOLBAR -->
	<div class="jm-dashboard__intro" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; background: #ffffff; padding: 18px 24px; border: 1px solid #e2e8f0; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
		<div class="jm-dashboard__filters" style="flex: 1 1 320px; max-width: 450px;">
			<form method="GET" action="" class="jm-form">
				<div style="position: relative; display: flex; align-items: center;">
					<input type="search" name="search" class="jm-ui-input--search-icon"
						placeholder="Tìm kiếm tin tuyển dụng theo chức danh..."
						value="<?php echo esc_attr( $search_input ); ?>"
						aria-label="Tìm kiếm tin tuyển dụng"
						style="width: 100%; height: 42px; border: 1px solid #cbd5e1; border-radius: 6px; padding: 0 16px; font-size: 14px; outline: none; transition: border-color 0.2s;" />
				</div>
			</form>
		</div>
		
		<div class="jm-dashboard__actions" style="display: flex; align-items: center; gap: 14px;">
			<?php if ( $jobs ) : ?>
				<span style="font-size: 14px; color: #64748b; font-weight: 500;">
					Tổng cộng: <strong style="color: #0f172a;"><?php echo count( $jobs ); ?></strong> tin tuyển dụng
				</span>
			<?php endif; ?>

			<?php if ( job_manager_user_can_submit_job_listing() ) : ?>
				<a class="jm-ui-button job-dashboard-add-btn"
					href="<?php echo esc_url( $submit_job_url ); ?>"
					style="background: #27c96d !important; color: #ffffff !important; padding: 11px 24px !important; border-radius: 6px !important; font-weight: 700 !important; font-size: 14.5px !important; text-decoration: none !important; display: inline-flex !important; align-items: center !important; gap: 8px !important; box-shadow: 0 3px 10px rgba(39,201,109,0.25) !important;">
					<span style="font-size: 18px; line-height: 1;">+</span>
					<span>Đăng Tin Tuyển Dụng Mới</span>
				</a>
			<?php endif; ?>
		</div>
	</div>

	<!-- JOBS TABLE LIST -->
	<div class="job-manager-jobs-custom" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.04);">
		<?php if ( ! $jobs ) : ?>
			<div class="jm-dashboard-empty" style="text-align: center; padding: 60px 20px; background: #f8fafc;">
				<div style="font-size: 48px; color: #94a3b8; margin-bottom: 12px;">📋</div>
				<h3 style="font-size: 18px; font-weight: 700; color: #334155; margin-bottom: 8px;">
					<?php echo $search_input ? sprintf( 'Không tìm thấy tin tuyển dụng nào với từ khóa "%s".', esc_html( $search_input ) ) : 'Bạn chưa có tin tuyển dụng nào đang hiển thị.'; ?>
				</h3>
				<p style="font-size: 14.5px; color: #64748b; margin-bottom: 24px;">Hãy bắt đầu đăng tin tuyển dụng đầu tiên của doanh nghiệp bạn ngay hôm nay!</p>
				<a href="<?php echo esc_url( $submit_job_url ); ?>" style="background: #27c96d; color: #ffffff; padding: 12px 28px; border-radius: 6px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
					<span>+</span> Đăng tin tuyển dụng ngay
				</a>
			</div>
		<?php else : ?>
			
			<!-- TABLE HEADER -->
			<div class="jm-custom-header">
				<div>Vị trí & Thông tin tuyển dụng</div>
				<div style="text-align: center !important; white-space: nowrap !important;">Thời gian & Hạn nộp</div>
				<div style="text-align: center !important; white-space: nowrap !important;">Lượt xem</div>
				<div style="text-align: center !important; white-space: nowrap !important;">Thao tác quản lý</div>
			</div>

			<!-- TABLE ROWS -->
			<div class="jm-custom-rows">
				<?php foreach ( $jobs as $job ) : 
					$company_name = get_post_meta( $job->ID, '_company_name', true );
					if ( has_post_thumbnail( $job->ID ) ) {
						$logo_url = get_the_post_thumbnail_url( $job->ID, 'thumbnail' );
					} else {
						$logo_url = get_post_meta( $job->ID, '_company_logo', true );
					}
					if ( empty( $logo_url ) ) {
						$logo_url = 'http://wordpress.local/wp-content/uploads/2026/10/company-default.svg';
					}

					$is_filled   = is_position_filled( $job );
					$post_status = $job->post_status;
					$stats       = class_exists( 'WP_Job_Manager\Job_Listing_Stats' ) ? new WP_Job_Manager\Job_Listing_Stats( $job->ID ) : null;
					$views       = $stats ? $stats->get_event_total( WP_Job_Manager\Job_Listing_Stats::VIEW ) : 0;
					$impressions = $stats ? $stats->get_event_total( WP_Job_Manager\Job_Listing_Stats::SEARCH_IMPRESSION ) : 0;
					$expiration  = WP_Job_Manager_Post_Types::instance()->get_job_expiration( $job );
					$exp_timestamp = null;
					if ( $expiration instanceof \DateTimeInterface ) {
						$exp_timestamp = $expiration->getTimestamp();
					} elseif ( is_numeric( $expiration ) ) {
						$exp_timestamp = (int) $expiration;
					} elseif ( ! empty( $expiration ) && is_string( $expiration ) ) {
						$exp_timestamp = strtotime( $expiration );
					}
				?>
					<div class="jm-custom-job-row">
						
						<!-- CỘT 1: VỊ TRÍ, LOGO & BADGE TRẠNG THÁI -->
						<div style="display: flex; align-items: center; gap: 16px; min-width: 0;">
							<div style="width: 50px; height: 50px; min-width: 50px; border-radius: 6px; border: 1px solid #e2e8f0; background: #ffffff; display: flex; align-items: center; justify-content: center; padding: 3px; box-sizing: border-box; overflow: hidden;">
								<img src="<?php echo esc_url( $logo_url ); ?>" alt="" style="max-width: 100%; max-height: 100%; width: auto; height: auto; object-fit: contain; object-position: center; display: block; margin: auto;" />
							</div>
							<div style="min-width: 0; flex: 1;">
								<h4 style="margin: 0 0 6px 0; font-size: 15px; font-weight: 700; line-height: 1.4; color: #0f172a; word-break: break-word;">
									<a href="<?php echo esc_url( get_permalink( $job->ID ) ); ?>" style="color: #0f172a; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#ea580c';" onmouseout="this.style.color='#0f172a';">
										<?php echo esc_html( wpjm_get_the_job_title( $job ) ); ?>
									</a>
								</h4>
								<div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
									<?php if ( $company_name ) : ?>
										<span style="font-size: 12.5px; color: #64748b; font-weight: 500;"><?php echo esc_html( $company_name ); ?></span>
										<span style="color: #cbd5e1;">•</span>
									<?php endif; ?>
									
									<?php if ( $is_filled ) : ?>
										<span style="display: inline-flex; align-items: center; gap: 4px; background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; text-transform: uppercase;">
											✓ Đã tuyển đủ
										</span>
									<?php endif; ?>

									<?php if ( 'publish' === $post_status ) : ?>
										<span style="display: inline-flex; align-items: center; gap: 4px; background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; text-transform: uppercase;">
											Đang hiển thị
										</span>
									<?php elseif ( 'expired' === $post_status ) : ?>
										<span style="display: inline-flex; align-items: center; gap: 4px; background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; text-transform: uppercase;">
											Đã hết hạn
										</span>
									<?php elseif ( 'pending' === $post_status ) : ?>
										<span style="display: inline-flex; align-items: center; gap: 4px; background: #fef3c7; color: #b45309; border: 1px solid #fde68a; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; text-transform: uppercase;">
											Chờ duyệt
										</span>
									<?php else : ?>
										<span style="display: inline-flex; align-items: center; gap: 4px; background: #f1f5f9; color: #64748b; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; text-transform: uppercase;">
											Bản nháp
										</span>
									<?php endif; ?>
								</div>
							</div>
						</div>

						<!-- CỘT 2: THỜI GIAN & HẠN NỘP (CANH GIỮA, KHÔNG XUỐNG DÒNG) -->
						<div style="font-size: 13px; color: #475569; line-height: 1.5; text-align: center !important; white-space: nowrap !important;">
							<div><strong>Đăng ngày:</strong> <?php echo esc_html( get_the_date( 'd/m/Y', $job->ID ) ); ?></div>
							<?php if ( ! empty( $exp_timestamp ) && 'publish' === $post_status ) : ?>
								<div style="color: #64748b; font-size: 12px; margin-top: 3px;">
									<span style="color: #0284c7; font-weight: 500;">Hạn nộp:</span> còn <?php echo esc_html( jobscout_vietnamese_time_diff( time(), $exp_timestamp ) ); ?>
								</div>
							<?php endif; ?>
						</div>

						<!-- CỘT 3: LƯỢT XEM & TIẾP CẬN (CANH GIỮA, KHÔNG XUỐNG DÒNG) -->
						<div style="font-size: 13px; color: #475569; line-height: 1.5; text-align: center !important; white-space: nowrap !important;">
							<div style="font-weight: 700; color: #0f172a;"><?php echo esc_html( $views ); ?> lượt xem</div>
							<div style="font-size: 12px; color: #64748b;"><?php echo esc_html( $impressions ); ?> lượt tiếp cận</div>
						</div>

						<!-- CỘT 4: THAO TÁC - 1 HÀNG NGANG CHUẨN ĐẸP, KHÔNG SO LE LỘN XỘN -->
						<div class="jm-job-actions-toolbar">
							<?php
							if ( ! empty( $job_actions[ $job->ID ] ) ) {
								// Filter unique actions by name to strictly avoid any duplicate
								$seen_actions = array();
								foreach ( $job_actions[ $job->ID ] as $action ) {
									$act_name = $action['name'];
									if ( isset( $seen_actions[ $act_name ] ) ) {
										continue;
									}
									$seen_actions[ $act_name ] = true;

									$conf = isset( $vn_actions[ $act_name ] ) ? $vn_actions[ $act_name ] : array(
										'label'  => $action['label'],
										'icon'   => '',
										'bg'     => '#f1f5f9',
										'color'  => '#334155',
										'border' => '#cbd5e1'
									);

									$is_delete = ( 'delete' === $act_name );
									?>
									<a href="<?php echo esc_url( $action['url'] ); ?>"
										<?php if ( $is_delete ) : ?>
											data-delete-url="<?php echo esc_url( $action['url'] ); ?>"
											data-job-title="<?php echo esc_attr( wpjm_get_the_job_title( $job ) ); ?>"
										<?php endif; ?>
										class="jm-action-btn jm-action-<?php echo esc_attr( $act_name ); ?><?php echo $is_delete ? ' jm-btn-trigger-delete' : ''; ?>"
										title="<?php echo esc_attr( $conf['label'] ); ?>"
										style="background: <?php echo esc_attr( $conf['bg'] ); ?> !important; color: <?php echo esc_attr( $conf['color'] ); ?> !important; border: 1px solid <?php echo esc_attr( $conf['border'] ); ?> !important;">
										<?php if ( ! empty( $conf['icon'] ) ) : ?>
											<i class="<?php echo esc_attr( $conf['icon'] ); ?>"></i>
										<?php endif; ?>
										<span><?php echo esc_html( $conf['label'] ); ?></span>
									</a>
									<?php
								}
							}
							?>
						</div>

					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>

	<?php get_job_manager_template( 'pagination.php', [ 'max_num_pages' => $max_num_pages ] ); ?>
</div>

<!-- FORM / MODAL THÔNG BÁO XÁC NHẬN XÓA Ở GIỮA MÀN HÌNH (THAY THẾ CONFIRM MẶC ĐỊNH CỦA TRÌNH DUYỆT) -->
<div id="jobscout-delete-modal-overlay" class="jobscout-modal-overlay" style="display: none;">
	<div class="jobscout-modal-backdrop"></div>
	<div class="jobscout-modal-dialog">
		<button type="button" class="jobscout-modal-close" id="btn-modal-close" aria-label="Đóng">&times;</button>
		
		<div class="jobscout-modal-icon-box">
			<i class="fas fa-trash-alt"></i>
		</div>

		<h3 class="jobscout-modal-title">Xác nhận xóa tin tuyển dụng</h3>
		
		<p class="jobscout-modal-desc">
			Bạn có chắc chắn muốn xóa vĩnh viễn tin tuyển dụng này khỏi hệ thống không?
		</p>

		<div id="jobscout-modal-job-title" class="jobscout-modal-job-name"></div>

		<div class="jobscout-modal-warning-note">
			<i class="fas fa-exclamation-triangle"></i>
			<span>Hành động này là vĩnh viễn và không thể khôi phục lại dữ liệu.</span>
		</div>

		<div class="jobscout-modal-actions">
			<button type="button" id="btn-cancel-delete" class="jobscout-modal-btn btn-cancel">
				Hủy bỏ
			</button>
			<a href="#" id="btn-confirm-delete" class="jobscout-modal-btn btn-confirm-delete">
				<i class="fas fa-trash-alt"></i>
				<span>Xác nhận xóa</span>
			</a>
		</div>
	</div>
</div>

<style>
/* CSS CHO FORM / MODAL THÔNG BÁO XÁC NHẬN XÓA Ở GIỮA MÀN HÌNH */
.jobscout-modal-overlay {
	position: fixed !important;
	top: 0 !important;
	left: 0 !important;
	width: 100vw !important;
	height: 100vh !important;
	z-index: 999999 !important;
	display: flex !important;
	align-items: center !important;
	justify-content: center !important;
	opacity: 0;
	visibility: hidden;
	transition: opacity 0.25s ease, visibility 0.25s ease !important;
}

.jobscout-modal-overlay.is-active {
	opacity: 1 !important;
	visibility: visible !important;
}

.jobscout-modal-backdrop {
	position: absolute !important;
	top: 0 !important;
	left: 0 !important;
	width: 100% !important;
	height: 100% !important;
	background: rgba(15, 23, 42, 0.65) !important;
	backdrop-filter: blur(5px) !important;
	-webkit-backdrop-filter: blur(5px) !important;
}

.jobscout-modal-dialog {
	position: relative !important;
	z-index: 2 !important;
	background: #ffffff !important;
	border-radius: 16px !important;
	padding: 36px 32px 30px 32px !important;
	max-width: 480px !important;
	width: 90% !important;
	text-align: center !important;
	box-shadow: 0 25px 60px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(226, 232, 240, 0.8) !important;
	transform: scale(0.9) translateY(15px);
	transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
	font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Nunito Sans", sans-serif !important;
	box-sizing: border-box !important;
}

.jobscout-modal-overlay.is-active .jobscout-modal-dialog {
	transform: scale(1) translateY(0) !important;
}

.jobscout-modal-close {
	position: absolute !important;
	top: 14px !important;
	right: 18px !important;
	background: transparent !important;
	border: none !important;
	font-size: 26px !important;
	color: #94a3b8 !important;
	cursor: pointer !important;
	padding: 4px !important;
	line-height: 1 !important;
	transition: color 0.2s ease !important;
}

.jobscout-modal-close:hover {
	color: #0f172a !important;
}

.jobscout-modal-icon-box {
	width: 68px !important;
	height: 68px !important;
	border-radius: 50% !important;
	background: #fee2e2 !important;
	color: #ef4444 !important;
	font-size: 28px !important;
	display: flex !important;
	align-items: center !important;
	justify-content: center !important;
	margin: 0 auto 20px auto !important;
	box-shadow: 0 4px 16px rgba(239, 68, 68, 0.25) !important;
}

.jobscout-modal-title {
	margin: 0 0 10px 0 !important;
	font-size: 21px !important;
	font-weight: 800 !important;
	color: #0f172a !important;
	letter-spacing: -0.3px !important;
}

.jobscout-modal-desc {
	margin: 0 0 14px 0 !important;
	font-size: 15px !important;
	color: #475569 !important;
	line-height: 1.55 !important;
}

.jobscout-modal-job-name {
	background: #f8fafc !important;
	border: 1.5px solid #e2e8f0 !important;
	border-radius: 8px !important;
	padding: 11px 16px !important;
	font-weight: 700 !important;
	color: #1e293b !important;
	font-size: 14.5px !important;
	margin-bottom: 16px !important;
	word-break: break-word !important;
}

.jobscout-modal-warning-note {
	background: #fff1f2 !important;
	border: 1px solid #fecdd3 !important;
	border-radius: 8px !important;
	padding: 10px 14px !important;
	font-size: 13px !important;
	color: #be123c !important;
	font-weight: 600 !important;
	display: flex !important;
	align-items: center !important;
	gap: 8px !important;
	text-align: left !important;
	margin-bottom: 26px !important;
}

.jobscout-modal-actions {
	display: flex !important;
	gap: 12px !important;
	justify-content: center !important;
	align-items: center !important;
}

.jobscout-modal-btn {
	padding: 12px 24px !important;
	border-radius: 8px !important;
	font-size: 14.5px !important;
	font-weight: 700 !important;
	cursor: pointer !important;
	display: inline-flex !important;
	align-items: center !important;
	justify-content: center !important;
	gap: 8px !important;
	text-decoration: none !important;
	transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
	box-sizing: border-box !important;
}

.jobscout-modal-btn.btn-cancel {
	background: #ffffff !important;
	color: #475569 !important;
	border: 1.5px solid #cbd5e1 !important;
}

.jobscout-modal-btn.btn-cancel:hover {
	background: #f1f5f9 !important;
	border-color: #94a3b8 !important;
	color: #1e293b !important;
}

.jobscout-modal-btn.btn-confirm-delete {
	background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
	color: #ffffff !important;
	border: none !important;
	box-shadow: 0 4px 14px rgba(239, 68, 68, 0.35) !important;
}

.jobscout-modal-btn.btn-confirm-delete:hover {
	background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
	box-shadow: 0 6px 18px rgba(239, 68, 68, 0.45) !important;
	transform: translateY(-1px) !important;
}
</style>

<script type="text/javascript">
jQuery(document).ready(function($) {
	var $modal = $('#jobscout-delete-modal-overlay');
	var $titleEl = $('#jobscout-modal-job-title');
	var $confirmBtn = $('#btn-confirm-delete');

	function openDeleteModal(title, url) {
		$titleEl.text(title || 'Tin tuyển dụng');
		$confirmBtn.attr('href', url);
		$confirmBtn.html('<i class="fas fa-trash-alt"></i> <span>Xác nhận xóa</span>').css({'pointer-events': 'auto', 'opacity': '1'});
		$modal.css('display', 'flex');
		setTimeout(function() {
			$modal.addClass('is-active');
		}, 10);
		$('body').css('overflow', 'hidden');
	}

	function closeDeleteModal() {
		$modal.removeClass('is-active');
		$('body').css('overflow', '');
		setTimeout(function() {
			$modal.css('display', 'none');
		}, 250);
	}

	// Lắng nghe click nút Xóa tin trong bảng
	$(document).on('click', '.jm-btn-trigger-delete', function(e) {
		e.preventDefault();
		e.stopPropagation();
		var title = $(this).data('job-title') || $(this).closest('.jm-custom-job-row').find('h4 a').text().trim();
		var url = $(this).data('delete-url') || $(this).attr('href');
		openDeleteModal(title, url);
		return false;
	});

	// Đóng modal khi bấm Hủy bỏ hoặc nút X
	$('#btn-cancel-delete, #btn-modal-close').on('click', function(e) {
		e.preventDefault();
		closeDeleteModal();
	});

	// Đóng modal khi click ra vùng backdrop ngoài
	$('.jobscout-modal-backdrop').on('click', function() {
		closeDeleteModal();
	});

	// Đóng modal khi nhấn phím Escape
	$(document).on('keydown', function(e) {
		if (e.key === 'Escape' && $modal.hasClass('is-active')) {
			closeDeleteModal();
		}
	});

	// Khi bấm Xác nhận xóa: chuyển sang trạng thái đang xóa
	$confirmBtn.on('click', function() {
		$(this).html('<i class="fas fa-spinner fa-spin"></i> <span>Đang xóa...</span>').css({'pointer-events': 'none', 'opacity': '0.85'});
	});
});
</script>
