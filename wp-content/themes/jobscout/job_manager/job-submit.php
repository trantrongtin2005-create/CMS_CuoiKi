<?php
/**
 * Content for job submission (`[submit_job_form]`) and job editing (`edit-job`) form.
 * Overridden in JobScout theme for full Vietnamese localization, modern enterprise UX and separated action buttons.
 *
 * @package JobScout
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $job_manager;
$captcha_version = WP_Job_Manager\WP_Job_Manager_Recaptcha::instance()->get_recaptcha_version();
$is_edit_mode    = ( 'edit-job' === $form );
?>
<form action="<?php echo esc_url( $action ); ?>" method="post" id="submit-job-form" class="job-manager-form" enctype="multipart/form-data" novalidate>

	<?php if ( $is_edit_mode && ! empty( $job_id ) ) : ?>
		<!-- THANH TIÊU ĐỀ QUẢN LÝ CHỈNH SỬA TIN TUYỂN DỤNG THỰC TẾ -->
		<div class="job-edit-header-bar" style="display: flex; justify-content: space-between; align-items: center; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 18px 24px; margin-bottom: 25px; flex-wrap: wrap; gap: 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
			<div>
				<span style="font-size: 12px; color: #2563eb; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; background: #eff6ff; padding: 3px 10px; border-radius: 4px; border: 1px solid #dbeafe;">Chế độ chỉnh sửa</span>
				<h3 style="margin: 8px 0 0; font-size: 18px; font-weight: 700; color: #0f172a;">
					<?php echo esc_html( get_the_title( $job_id ) ); ?>
				</h3>
			</div>
			<div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
				<a href="<?php echo esc_url( get_permalink( $job_id ) ); ?>" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; background: #ffffff; border: 1px solid #cbd5e1; color: #334155; padding: 9px 16px; border-radius: 6px; font-size: 13.5px; font-weight: 600; text-decoration: none;">
					<span>Xem tin thực tế</span> ↗
				</a>
				<a href="<?php echo esc_url( remove_query_arg( array( 'action', 'job_id' ) ) ); ?>" style="display: inline-flex; align-items: center; gap: 6px; background: #f8fafc; border: 1px solid #cbd5e1; color: #475569; padding: 9px 16px; border-radius: 6px; font-size: 13.5px; font-weight: 600; text-decoration: none;">
					← Quay lại Quản lý tin
				</a>
			</div>
		</div>
	<?php endif; ?>

	<?php
	if ( isset( $resume_edit ) && $resume_edit ) {
		printf( '<p class="job-manager-message"><strong>' . esc_html__( 'Bạn đang chỉnh sửa một tin tuyển dụng có sẵn. %s', 'jobscout' ) . '</strong></p>', '<a href="?job_manager_form=submit-job&new=1&key=' . esc_attr( $resume_edit ) . '">' . esc_html__( '+ Tạo một tin tuyển dụng mới', 'jobscout' ) . '</a>' );
	}
	?>

	<?php do_action( 'submit_job_form_start' ); ?>

	<?php if ( apply_filters( 'submit_job_form_show_signin', true ) ) : ?>

		<?php get_job_manager_template( 'account-signin.php' ); ?>

	<?php endif; ?>

	<?php if ( job_manager_user_can_post_job() || job_manager_user_can_edit_job( $job_id ) ) : ?>

		<!-- Thông tin tin tuyển dụng -->
		<h2 style="font-size: 20px; font-weight: 700; color: #1e293b; margin: 25px 0 15px 0; padding-bottom: 10px; border-bottom: 2px solid #f1f5f9;"><?php esc_html_e( 'Thông tin việc làm', 'jobscout' ); ?></h2>

		<?php do_action( 'submit_job_form_job_fields_start' ); ?>

		<?php foreach ( $job_fields as $key => $field ) : ?>
			<fieldset class="fieldset-<?php echo esc_attr( $key ); ?> fieldset-type-<?php echo esc_attr( $field['type'] ); ?>">
				<label for="<?php echo esc_attr( $key ); ?>"><?php echo wp_kses_post( $field['label'] ) . wp_kses_post( apply_filters( 'submit_job_form_required_label', $field['required'] ? ' <span style="color: #ef4444;">*</span>' : ' <small style="color: #94a3b8; font-weight: normal;">(không bắt buộc)</small>', $field ) ); ?></label>
				<div class="field <?php echo $field['required'] ? 'required-field' : ''; ?>">
					<?php get_job_manager_template( 'form-fields/' . $field['type'] . '-field.php', [ 'key' => $key, 'field' => $field ] ); ?>
				</div>
			</fieldset>
		<?php endforeach; ?>

		<?php do_action( 'submit_job_form_job_fields_end' ); ?>

		<!-- Thông tin công ty / doanh nghiệp -->
		<?php if ( $company_fields ) : ?>
			<h2 style="font-size: 20px; font-weight: 700; color: #1e293b; margin: 35px 0 15px 0; padding-bottom: 10px; border-bottom: 2px solid #f1f5f9;"><?php esc_html_e( 'Thông tin công ty / Doanh nghiệp', 'jobscout' ); ?></h2>

			<?php do_action( 'submit_job_form_company_fields_start' ); ?>

			<?php foreach ( $company_fields as $key => $field ) : ?>
				<fieldset class="fieldset-<?php echo esc_attr( $key ); ?> fieldset-type-<?php echo esc_attr( $field['type'] ); ?>">
					<label for="<?php echo esc_attr( $key ); ?>"><?php echo wp_kses_post( $field['label'] ) . wp_kses_post( apply_filters( 'submit_job_form_required_label', $field['required'] ? ' <span style="color: #ef4444;">*</span>' : ' <small style="color: #94a3b8; font-weight: normal;">(không bắt buộc)</small>', $field ) ); ?></label>
					<div class="field <?php echo $field['required'] ? 'required-field' : ''; ?>">
						<?php get_job_manager_template( 'form-fields/' . $field['type'] . '-field.php', [ 'key' => $key, 'field' => $field ] ); ?>
					</div>
				</fieldset>
			<?php endforeach; ?>

			<?php do_action( 'submit_job_form_company_fields_end' ); ?>
		<?php endif; ?>

		<?php do_action( 'submit_job_form_end' ); ?>

		<div class="job-submit-buttons-wrap" style="display: flex; gap: 16px; align-items: center; flex-wrap: wrap; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
			<input type="hidden" name="job_manager_form" value="<?php echo esc_attr( $form ); ?>" />
			<input type="hidden" name="job_id" value="<?php echo esc_attr( $job_id ); ?>" />
			<input type="hidden" name="step" value="<?php echo esc_attr( $step ); ?>" />
			
			<?php
			$btn_text = empty( $submit_button_text ) ? ( $is_edit_mode ? 'Lưu thay đổi' : 'Xem trước' ) : $submit_button_text;
			if ( 'Save changes' === $btn_text ) {
				$btn_text = 'Lưu thay đổi';
			}
			?>
			<!-- Nút Submit chính -->
			<input type="submit" name="submit_job" class="button btn-submit-job"
				<?php if ('v3' === $captcha_version) echo 'onclick="jm_job_submit_click(event)"'; ?>
				value="<?php echo esc_attr( $btn_text ); ?>"
				style="<?php echo $is_edit_mode ? 'background: #2563eb !important;' : ''; ?>"
			/>
			
			<!-- Nút phụ khi đang chỉnh sửa hoặc lưu nháp -->
			<?php if ( $is_edit_mode ) : ?>
				<a href="<?php echo esc_url( remove_query_arg( array( 'action', 'job_id' ) ) ); ?>" class="button secondary" style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; padding: 12px 22px; border-radius: 6px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center;">
					Hủy bỏ & Quay lại
				</a>
			<?php elseif ( isset( $can_continue_later ) && $can_continue_later ) : ?>
				<input type="submit" name="save_draft" class="button secondary save_draft btn-save-draft" value="<?php esc_attr_e( 'Lưu nháp', 'jobscout' ); ?>" formnovalidate />
			<?php endif; ?>

			<span class="spinner" style="background-image: url(<?php echo esc_url( includes_url( 'images/spinner.gif' ) ); ?>);"></span>
		</div>

	<?php else : ?>

		<?php do_action( 'submit_job_form_disabled' ); ?>

	<?php endif; ?>
</form>

<script type="text/javascript">
jQuery(document).ready(function($) {
	var $form = $('#submit-job-form');
	if (!$form.length) return;

	// Tắt hoàn toàn thông báo popup mặc định của trình duyệt
	$form.attr('novalidate', 'novalidate');

	// Hàm hỗ trợ gắn hoặc xóa thông báo lỗi inline dưới từng trường
	function setFieldError($fieldEl, errorMsg) {
		var $fieldset = $fieldEl.closest('fieldset');
		$fieldset.find('.jobscout-inline-error').remove();
		if (errorMsg) {
			$fieldset.find('.field').addClass('has-error');
			$fieldEl.css({'border-color': '#ef4444', 'background-color': '#fef2f2'});
			$fieldset.find('.field').append(
				'<div class="jobscout-inline-error" style="color: #dc2626; font-size: 13.5px; font-weight: 600; margin-top: 6px; display: flex; align-items: center; gap: 6px; animation: fadeIn 0.2s ease;">' +
				'<i class="fas fa-exclamation-circle" style="color: #ef4444;"></i> ' + errorMsg +
				'</div>'
			);
		} else {
			$fieldset.find('.field').removeClass('has-error');
			$fieldEl.css({'border-color': '', 'background-color': ''});
		}
	}

	// Tự động xóa lỗi khi người dùng bắt đầu nhập hoặc thay đổi dữ liệu
	$form.on('input change', 'input, select, textarea', function() {
		setFieldError($(this), null);
		if ($('.jobscout-inline-error').length === 0) {
			$('#jobscout-client-alert-box').slideUp(200);
		}
	});

	// Lắng nghe sự kiện gõ trong trình soạn thảo TinyMCE
	if (typeof tinyMCE !== 'undefined') {
		tinyMCE.on('AddEditor', function(e) {
			e.editor.on('input change keyup', function() {
				setFieldError($('#job_description'), null);
				if ($('.jobscout-inline-error').length === 0) {
					$('#jobscout-client-alert-box').slideUp(200);
				}
			});
		});
	}

	// 1. KIỂM TRA DỮ LIỆU FRONTEND CHUYÊN NGHIỆP TRƯỚC KHI SUBMIT
	$form.on('submit', function(e) {
		var isDraft = (document.activeElement && document.activeElement.name === 'save_draft');
		if (isDraft) {
			return true; // Cho phép lưu nháp không cần bắt buộc tất cả trường
		}

		var errors = [];
		$('.jobscout-inline-error').remove();
		$('.has-error').removeClass('has-error');
		$('#jobscout-client-alert-box').remove();

		// (1) Chức danh việc làm
		var $title = $('#job_title');
		if ($title.length && !$.trim($title.val())) {
			var msg = 'Vui lòng nhập Chức danh việc làm (ví dụ: Kỹ sư Phần mềm, Kế toán trưởng...).';
			errors.push(msg);
			setFieldError($title, msg);
		}

		// (2) Loại hình công việc
		var $type = $('#job_type');
		if ($type.length && (!$type.val() || $type.val() === '0' || $type.val() === '')) {
			var msg = 'Vui lòng chọn Loại hình công việc phù hợp.';
			errors.push(msg);
			setFieldError($type, msg);
		}

		// (3) Ngành nghề / Danh mục
		var $cat = $('#job_category');
		if ($cat.length && (!$cat.val() || $cat.val() === '0' || $cat.val() === '')) {
			var msg = 'Vui lòng chọn Ngành nghề / Danh mục việc làm.';
			errors.push(msg);
			setFieldError($cat, msg);
		}

		// (4) Địa điểm làm việc
		var $loc = $('#job_location');
		if ($loc.length && !$.trim($loc.val())) {
			var msg = 'Vui lòng nhập Địa điểm làm việc (ví dụ: Hà Nội, TP. Hồ Chí Minh...).';
			errors.push(msg);
			setFieldError($loc, msg);
		}

		// (5) Mô tả công việc
		var descText = '';
		if (typeof tinyMCE !== 'undefined' && tinyMCE.get('job_description') && !tinyMCE.get('job_description').isHidden()) {
			descText = $.trim(tinyMCE.get('job_description').getContent({ format: 'text' }));
		} else {
			descText = $.trim($('#job_description').val());
		}
		if (!descText) {
			var msg = 'Vui lòng nhập nội dung Mô tả chi tiết công việc và yêu cầu tuyển dụng.';
			errors.push(msg);
			setFieldError($('#job_description'), msg);
		}

		// (6) Phương thức nhận hồ sơ ứng tuyển
		var $app = $('#application');
		if ($app.length) {
			var appVal = $.trim($app.val());
			if (!appVal) {
				var msg = 'Vui lòng nhập Email hoặc Đường dẫn URL nhận hồ sơ ứng tuyển.';
				errors.push(msg);
				setFieldError($app, msg);
			} else if (appVal.indexOf('@') !== -1) {
				var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
				if (!emailRegex.test(appVal)) {
					var msg = 'Địa chỉ email nhận hồ sơ không đúng định dạng (ví dụ: tuyendung@congty.com).';
					errors.push(msg);
					setFieldError($app, msg);
				}
			} else {
				var urlRegex = /^(http|https):\/\/[^ "]+$/;
				if (!urlRegex.test(appVal)) {
					var msg = 'Đường dẫn website nộp hồ sơ phải bắt đầu bằng http:// hoặc https:// (ví dụ: https://congty.com/careers).';
					errors.push(msg);
					setFieldError($app, msg);
				}
			}
		}

		// (7) Tên công ty / Doanh nghiệp
		var $compName = $('#company_name');
		if ($compName.length && !$.trim($compName.val())) {
			var msg = 'Vui lòng nhập Tên công ty / Doanh nghiệp tuyển dụng.';
			errors.push(msg);
			setFieldError($compName, msg);
		}

		// (8) BẮT BUỘC NHẬP HÌNH ẢNH / LOGO CÔNG TY (Theo yêu cầu)
		var $logoInput = $('#company_logo');
		var hasLogo = false;
		if ($logoInput.length && $logoInput[0].files && $logoInput[0].files.length > 0) {
			hasLogo = true;
		} else if ($('.fieldset-company_logo .job-manager-uploaded-file').length > 0) {
			hasLogo = true;
		} else if ($('input[name="current_company_logo"]').val()) {
			hasLogo = true;
		} else if ($form.find('input[name="job_id"]').val() && $('.fieldset-company_logo img').length > 0) {
			hasLogo = true;
		}

		if (!hasLogo) {
			var msg = 'Vui lòng tải lên Logo hoặc Hình ảnh đại diện công ty (bắt buộc khi đăng tin tuyển dụng).';
			errors.push(msg);
			setFieldError($logoInput, msg);
		}

		// NẾU CÓ LỖI: HIỂN THỊ HỘP THÔNG BÁO TIẾNG VIỆT CHUYÊN NGHIỆP, CHẶN SUBMIT
		if (errors.length > 0) {
			e.preventDefault();
			e.stopPropagation();

			var errorListItems = '';
			for (var i = 0; i < errors.length; i++) {
				errorListItems += '<li style="margin-bottom: 6px;">' + errors[i] + '</li>';
			}

			var alertHtml = 
				'<div id="jobscout-client-alert-box" class="jobscout-alert-card error" style="background: #fef2f2; border: 1.5px solid #fecaca; border-left: 5px solid #ef4444; border-radius: 12px; padding: 22px 26px; margin: 15px 0 30px 0; box-shadow: 0 4px 16px rgba(239, 68, 68, 0.08); font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, sans-serif;">' +
					'<div style="display: flex; align-items: flex-start; gap: 16px;">' +
						'<div style="width: 40px; height: 40px; border-radius: 50%; background: #fee2e2; color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; margin-top: 2px;">' +
							'<i class="fas fa-exclamation-triangle"></i>' +
						'</div>' +
						'<div style="flex: 1;">' +
							'<h4 style="margin: 0 0 6px 0; font-size: 17px; font-weight: 800; color: #991b1b;">' +
								'Thông tin đăng tin chưa hoàn tất (' + errors.length + ' mục cần bổ sung)' +
							'</h4>' +
							'<p style="margin: 0 0 12px 0; font-size: 14.5px; color: #475569; line-height: 1.5;">' +
								'Vui lòng kiểm tra và bổ sung đầy đủ các thông tin bắt buộc dưới đây trước khi chuyển sang bước Xem trước:' +
							'</p>' +
							'<ul style="margin: 0; padding-left: 20px; font-size: 14px; color: #b91c1c; line-height: 1.6;">' +
								errorListItems +
							'</ul>' +
						'</div>' +
					'</div>' +
				'</div>';

			$form.prepend(alertHtml);

			// Cuộn mượt đến thông báo lỗi
			$('html, body').animate({
				scrollTop: $('#jobscout-client-alert-box').offset().top - 90
			}, 300);

			return false;
		}

		// Nếu hợp lệ: hiển thị hiệu ứng nút submit đang gửi
		var $submitBtn = $form.find('input[name="submit_job"]');
		$submitBtn.val('Đang xử lý tải lên...').css({'opacity': '0.85', 'pointer-events': 'none'});
	});

	// 2. Tự động cuộn mượt đến thông báo lỗi từ máy chủ trả về (nếu có)
	var $errorNotice = $('.job-manager-error');
	if ($errorNotice.length) {
		$('html, body').animate({
			scrollTop: $errorNotice.first().offset().top - 90
		}, 350);
	}

	// 3. Xem trước ảnh tức thì ngay khi chọn tệp logo
	$('#company_logo').on('change', function() {
		var file = this.files[0];
		if (file) {
			setFieldError($(this), null);
			var reader = new FileReader();
			reader.onload = function(e) {
				var $filesBox = $('.fieldset-company_logo .job-manager-uploaded-files');
				if ($filesBox.length) {
					$filesBox.html(
						'<div class="job-manager-uploaded-file" style="display: inline-flex; align-items: center; gap: 12px; background: #ffffff; border: 1.5px solid #bbf7d0; border-radius: 8px; padding: 8px 14px; margin-bottom: 10px; box-shadow: 0 2px 6px rgba(16, 185, 129, 0.1);">' +
						'<span class="job-manager-uploaded-file-preview" style="display: inline-flex; align-items: center; gap: 12px;">' +
						'<img src="' + e.target.result + '" style="width: 52px; height: 52px; object-fit: contain; border-radius: 6px; border: 1px solid #cbd5e1; background: #ffffff; padding: 2px;" />' +
						'<span style="font-size: 13.5px; font-weight: 600; color: #1e293b;">' + file.name + '</span>' +
						'</span>' +
						'</div>'
					);
				}
			};
			reader.readAsDataURL(file);
		}
	});
});
</script>
