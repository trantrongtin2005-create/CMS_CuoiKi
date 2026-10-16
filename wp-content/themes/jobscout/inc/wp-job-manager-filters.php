<?php
/**
 * Filter to modify functionality of WP Job Manager plugin.
 *
 * @package JobScout 
 */

add_action( 'after_setup_theme', 'jobscout_job_manager_setup' );
function jobscout_job_manager_setup() {
	add_theme_support( 'job-manager-templates' );
}

if ( ! function_exists( 'job_board_taxonomy_type_modified_filter' ) ) {
	function job_board_taxonomy_type_modified_filter( $args ) {
		if ( is_array( $args ) ) {
			$args['public'] = true;
			$args['publicly_queryable'] = true;
			$args['show_ui'] = true;
			$args['show_in_nav_menus'] = true;
			$args['rewrite'] = array( 'slug' => 'job-type', 'with_front' => false );
		}
		return $args;
	}
}
add_filter( 'register_taxonomy_job_listing_type_args', 'job_board_taxonomy_type_modified_filter' );

if ( ! function_exists( 'job_board_taxonomy_category_modified_filter' ) ) {
	function job_board_taxonomy_category_modified_filter( $args ) {
		if ( is_array( $args ) ) {
			$args['public'] = true;
			$args['publicly_queryable'] = true;
			$args['show_ui'] = true;
			$args['show_in_nav_menus'] = true;
			$args['rewrite'] = array( 'slug' => 'job-category', 'with_front' => false );
		}
		return $args;
	}
}
add_filter( 'register_taxonomy_job_listing_category_args', 'job_board_taxonomy_category_modified_filter' );

/**
 * Filter archive titles for jobs, categories and types in Vietnamese
 */
add_filter( 'get_the_archive_title', 'jobscout_vietnamese_archive_titles', 20 );
function jobscout_vietnamese_archive_titles( $title ) {
	if ( is_post_type_archive( 'job_listing' ) ) {
		return 'Danh Sách Việc Làm';
	}
	if ( is_tax( 'job_listing_category' ) ) {
		return sprintf( 'Ngành nghề: %s', single_term_title( '', false ) );
	}
	if ( is_tax( 'job_listing_type' ) ) {
		return sprintf( 'Loại hình công việc: %s', single_term_title( '', false ) );
	}
	return $title;
}

/**
 * Filter archive descriptions for jobs in Vietnamese
 */
add_filter( 'get_the_archive_description', 'jobscout_vietnamese_archive_descriptions', 20 );
function jobscout_vietnamese_archive_descriptions( $description ) {
	if ( is_post_type_archive( 'job_listing' ) ) {
		return '<p>Khám phá các cơ hội nghề nghiệp hàng đầu từ các doanh nghiệp và tập đoàn uy tín.</p>';
	}
	if ( is_tax( 'job_listing_category' ) && empty( $description ) ) {
		return '<p>' . sprintf( 'Các việc làm đang tuyển dụng thuộc ngành nghề %s.', single_term_title( '', false ) ) . '</p>';
	}
	if ( is_tax( 'job_listing_type' ) && empty( $description ) ) {
		return '<p>' . sprintf( 'Các việc làm đang tuyển dụng theo hình thức %s.', single_term_title( '', false ) ) . '</p>';
	}
	return $description;
}

/**
 * Vietnamese time diff calculation
 */
if ( ! function_exists( 'jobscout_vietnamese_time_diff' ) ) {
	function jobscout_vietnamese_time_diff( $from, $to = '' ) {
		if ( empty( $to ) ) {
			$to = time();
		}
		$diff = abs( $to - $from );
		if ( $diff < MINUTE_IN_SECONDS ) {
			return 'Vừa xong';
		} elseif ( $diff < HOUR_IN_SECONDS ) {
			$mins = max( 1, round( $diff / MINUTE_IN_SECONDS ) );
			return $mins . ' phút';
		} elseif ( $diff < DAY_IN_SECONDS ) {
			$hours = max( 1, round( $diff / HOUR_IN_SECONDS ) );
			return $hours . ' giờ';
		} elseif ( $diff < WEEK_IN_SECONDS ) {
			$days = max( 1, round( $diff / DAY_IN_SECONDS ) );
			return $days . ' ngày';
		} elseif ( $diff < MONTH_IN_SECONDS ) {
			$weeks = max( 1, round( $diff / MONTH_IN_SECONDS ) );
			return $weeks . ' tuần';
		} elseif ( $diff < YEAR_IN_SECONDS ) {
			$months = max( 1, round( $diff / MONTH_IN_SECONDS ) );
			return $months . ' tháng';
		} else {
			$years = max( 1, round( $diff / YEAR_IN_SECONDS ) );
			return $years . ' năm';
		}
	}
}

/**
 * Filter gettext for Vietnamese translations across all domains
 */
add_filter( 'gettext', 'jobscout_vietnamese_text_translations', 20, 3 );
function jobscout_vietnamese_text_translations( $translated_text, $text, $domain ) {
	static $dictionary = array(
		'Keywords'                    => 'Từ khóa',
		'Location'                    => 'Địa điểm / Thành phố',
		'Category'                    => 'Ngành nghề',
		'Search Jobs'                 => 'Tìm việc làm',
		'Search'                      => 'Tìm kiếm',
		'Search for:'                 => 'Tìm kiếm:',
		'Any category'                => 'Tất cả ngành nghề',
		'Remote positions only'       => 'Chỉ việc làm từ xa',
		'Featured'                    => 'Nổi bật',
		'Posted %s ago'               => 'Đã đăng %s trước',
		'%s ago'                      => '%s trước',
		'Job Title'                   => 'Chức danh tuyển dụng',
		'Job type'                    => 'Loại hình công việc',
		'Job types'                   => 'Loại hình công việc',
		'Job Types'                   => 'Loại hình công việc',
		'Job category'                => 'Ngành nghề',
		'Job Categories'              => 'Danh mục ngành nghề',
		'Description'                 => 'Mô tả công việc',
		'Application email/URL'       => 'Email hoặc Link nộp hồ sơ',
		'Company name'                => 'Tên công ty',
		'Website'                     => 'Website công ty',
		'Tagline'                     => 'Giới thiệu ngắn / Khẩu hiệu',
		'Video'                       => 'Video giới thiệu công ty',
		'Twitter username'            => 'Tài khoản mạng xã hội',
		'Logo'                        => 'Logo / Hình ảnh công ty',
		'%s is a required field'      => 'Vui lòng điền thông tin cho trường bắt buộc: %s.',
		'%s is invalid'               => 'Thông tin nhập vào cho trường %s không hợp lệ.',
		'Please enter a valid application email address' => 'Vui lòng nhập địa chỉ email hợp lệ để nhận hồ sơ ứng tuyển.',
		'Please enter a valid application URL' => 'Vui lòng nhập đường dẫn URL website hợp lệ để nhận hồ sơ ứng tuyển.',
		'Please enter a valid application email address or URL' => 'Vui lòng nhập Email hoặc Đường dẫn URL website hợp lệ để nhận hồ sơ.',
		'You must be logged in to upload files using this method.' => 'Bạn cần đăng nhập để tải lên tệp tin.',
		'The company logo exceeds the maximum allowed file size of %s.' => 'Logo công ty vượt quá dung lượng tối đa cho phép %s.',
		'File exceeds maximum upload size.' => 'Tệp tin vượt quá dung lượng tải lên tối đa.',
		'Specified file failed upload test.' => 'Tệp tin tải lên không hợp lệ.',
		'Internal Server Error'       => 'Lỗi máy chủ nội bộ',
		'Submit Job'                  => 'Gửi tin tuyển dụng',
		'Preview'                     => 'Xem trước',
		'Save Draft'                  => 'Lưu nháp',
		'Load more jobs'              => 'Xem thêm việc làm',
		'No more jobs found.'         => 'Không tìm thấy việc làm phù hợp.',
		'There are no listings matching your search.' => 'Không tìm thấy việc làm nào phù hợp với tìm kiếm của bạn.',
		'Your job details'            => 'Thông tin tin tuyển dụng',
		'Company Details'             => 'Thông tin công ty',
		'Enter an email address or website URL where applicants can apply' => 'Nhập email hoặc đường dẫn website để ứng viên nộp hồ sơ',
		'Choose a category…'          => 'Chọn ngành nghề…',
		'Choose a job type…'          => 'Chọn loại hình công việc…',
		'e.g. "Designer"'             => 'Ví dụ: Lập trình viên PHP, Kế toán trưởng...',
		'e.g. "London"'               => 'Ví dụ: TP. Hồ Chí Minh, Hà Nội, Đà Nẵng...',
		'Apply for job'               => 'Ứng tuyển ngay',
		'Application'                 => 'Ứng tuyển',
		'Reset'                       => 'Đặt lại bộ lọc',
		'Reset filter'                => 'Xóa bộ lọc',
		'Salary'                      => 'Mức lương',
		'Salary:'                     => 'Mức lương:',
		'Salary: '                    => 'Mức lương: ',
		'Important Information'       => 'Thông tin quan trọng',
		'Important Information: '     => 'Thông tin quan trọng: ',
		'Bachelor Degree Qualification' => 'Bằng Cử nhân / Đại học trở lên',
		'This position has been filled' => 'Vị trí này đã tuyển đủ',
		'Applications have closed'    => 'Đã hết hạn nộp hồ sơ',
		'This listing has expired.'   => 'Tin tuyển dụng này đã hết hạn.',
		'To apply for this job please visit' => 'Để ứng tuyển công việc này, vui lòng truy cập',
		'About Author'                => 'Giới thiệu tác giả',
		'Leave a Comment'             => 'Để lại bình luận',
		'Latest Articles'             => 'Tin tức & Bài viết mới nhất',
		'Clients Testimonials'        => 'Đánh giá từ khách hàng',
		'Popular Posts'               => 'Bài viết phổ biến',
		'Recent Posts'                => 'Bài viết gần đây',
		'Categories'                  => 'Chuyên mục',
		'Learn More'                  => 'Tìm hiểu thêm',
		'See More Posts'              => 'Xem thêm bài viết',
		'Read More'                   => 'Đọc thêm',
		'Get in Touch'                => 'Thông tin liên hệ',
		'Subscribe and Follow'        => 'Theo dõi chúng tôi',
		'All Rights Reserved'         => 'Bảo lưu mọi quyền',
		'Post Jobs'                   => 'Đăng tin tuyển dụng',
		'Post a Job'                  => 'Đăng tin tuyển dụng',
		'Older posts'                 => 'Việc làm cũ hơn',
		'Newer posts'                 => 'Việc làm mới hơn',
		'Previous Article'            => 'Bài viết trước',
		'Next Article'                => 'Bài viết tiếp theo',
		'Archives: %s'                => '%s',
		'Archives'                    => 'Danh sách',
		'Jobs'                        => 'Việc làm',
		'Edit'                        => 'Chỉnh sửa',
		'Delete'                      => 'Xóa tin',
		'Mark filled'                 => 'Đã tuyển đủ',
		'Mark not filled'             => 'Đang tuyển',
		'Duplicate'                   => 'Nhân bản',
		'Relist'                      => 'Đăng lại',
		'Add Job'                     => '+ Đăng tin mới',
		'Actions'                     => 'Thao tác',
		'Status'                      => 'Trạng thái',
		'Expires'                     => 'Hạn nộp',
		'Date Posted'                 => 'Ngày đăng',
		'Filled'                      => 'Đã tuyển đủ',
		'Active'                      => 'Đang hiển thị',
		'Expired'                     => 'Đã hết hạn',
		'Pending approval'            => 'Chờ duyệt',
		'Draft'                       => 'Bản nháp',
		'You do not have any active listings.' => 'Bạn chưa có tin tuyển dụng nào đang hiển thị.',
		'Submit Listing'              => 'Xác nhận & Đăng tin tuyển dụng',
		'Edit listing'                => '← Chỉnh sửa lại',
		'Your account'                => 'Tài khoản của bạn',
		'Have an account?'            => 'Thông tin tài khoản nhà tuyển dụng',
		'Sign in'                     => 'Đăng nhập',
		'Sign out'                    => 'Đăng xuất',
		'Create A New Job'            => '+ Tạo tin tuyển dụng mới',
		'You are editing an existing job.' => 'Bạn đang chỉnh sửa một tin tuyển dụng có sẵn.',
		'optionally'                  => 'không bắt buộc',
		'(optional)'                  => '(không bắt buộc)',
		'LOAD MORE JOBS'              => 'XEM THÊM VIỆC LÀM',
		'Load More Jobs'              => 'Xem thêm việc làm',
		'Showing all %s jobs'         => 'Hiển thị tất cả %s việc làm',
		'Square format recommended (1:1 ratio).' => 'Hỗ trợ mọi định dạng và kích thước ảnh (JPG, PNG, WEBP, GIF, SVG...).',
		'Maximum file size: %s.'      => 'Dung lượng tải lên tối đa: %s.',
		'Expires in %s'               => 'Hạn nộp: còn %s',
		'Expires in'                  => 'Hạn nộp:',
		'in %s'                       => 'còn %s',
		'%s has been filled'          => '%s đã được đánh dấu là tuyển đủ',
		'%s has been marked as not filled' => '%s đã được chuyển sang trạng thái đang tuyển',
		'%s has been deleted'         => '%s đã được xóa thành công',
		'Your changes have been saved.' => 'Các thay đổi của bạn đã được cập nhật thành công.',
		'View &rarr;'                 => 'Xem tin tuyển dụng &rarr;',
		'Your changes have been submitted and your listing will be visible again once approved.' => 'Các thay đổi đã được gửi và tin sẽ hiển thị lại sau khi được phê duyệt.',
		'Search impressions'          => 'Lượt tiếp cận',
		'Views'                       => 'Lượt xem',
		'ACTIVE'                      => 'Đang hiển thị',
		'FILLED'                      => 'Đã tuyển đủ',
		'EXPIRED'                     => 'Đã hết hạn',
		'Your changes have been saved.' => 'Các thay đổi đã được lưu thành công.',
		'View &rarr;'                 => 'Xem tin tuyển dụng &rarr;',
		'View'                        => 'Xem tin tuyển dụng',
		'Save changes'                => 'Lưu thay đổi',
		'Submit changes for approval' => 'Gửi phê duyệt thay đổi',
		'Your changes have been submitted and your listing will be visible again once approved.' => 'Thay đổi đã được gửi và tin tuyển dụng sẽ hiển thị lại sau khi được phê duyệt.',
		'The company logo exceeds the maximum allowed file size of %s.' => 'Logo công ty vượt quá dung lượng tối đa cho phép là %s.',
		'Invalid listing'             => 'Tin tuyển dụng không hợp lệ hoặc bạn không có quyền chỉnh sửa.',
		'remove'                      => 'Xóa ảnh',
		'Remove'                      => 'Xóa',
		'Upload'                      => 'Tải lên',
		'Are you sure you want to delete this listing?' => 'Bạn có chắc chắn muốn xóa vĩnh viễn tin tuyển dụng này?',
		'Are you sure?'               => 'Bạn có chắc chắn muốn thực hiện?',
	);

	if ( isset( $dictionary[ $text ] ) ) {
		return $dictionary[ $text ];
	}

	return $translated_text;
}

/**
 * Filter ngettext for plural phrases & time units
 */
add_filter( 'ngettext', 'jobscout_vietnamese_ngettext_translations', 20, 5 );
function jobscout_vietnamese_ngettext_translations( $translation, $single, $plural, $number, $domain ) {
	// Lượt xem & Lượt tiếp cận
	if ( '%1d view' === $single || '%1d views' === $plural ) {
		return sprintf( '%d lượt xem', $number );
	}
	if ( '%1d impression' === $single || '%1d impressions' === $plural ) {
		return sprintf( '%d lượt tiếp cận', $number );
	}

	// Đơn vị thời gian chuẩn tiếng Việt
	if ( '%s year' === $single || '%s years' === $plural ) {
		return sprintf( '%s năm', $number );
	}
	if ( '%s month' === $single || '%s months' === $plural ) {
		return sprintf( '%s tháng', $number );
	}
	if ( '%s week' === $single || '%s weeks' === $plural ) {
		return sprintf( '%s tuần', $number );
	}
	if ( '%s day' === $single || '%s days' === $plural ) {
		return sprintf( '%s ngày', $number );
	}
	if ( '%s hour' === $single || '%s hours' === $plural ) {
		return sprintf( '%s giờ', $number );
	}
	if ( '%s min' === $single || '%s mins' === $plural || '%s minute' === $single || '%s minutes' === $plural ) {
		return sprintf( '%s phút', $number );
	}
	if ( '%s sec' === $single || '%s secs' === $plural || '%s second' === $single || '%s seconds' === $plural ) {
		return sprintf( '%s giây', $number );
	}

	if ( 'Search completed. Found %d matching record.' === $single || 'Search completed. Found %d matching records.' === $plural ) {
		return sprintf( 'Tìm kiếm hoàn tất. Tìm thấy %d việc làm phù hợp.', $number );
	}
	if ( 'Showing all %s jobs' === $single || 'Showing all %s jobs' === $plural ) {
		return sprintf( 'Hiển thị tất cả %s việc làm', $number );
	}
	return $translation;
}

/**
 * Filter custom text for search results notice in AJAX
 */
add_filter( 'job_manager_get_listings_custom_filter_text', 'jobscout_custom_listings_filter_text', 20, 2 );
function jobscout_custom_listings_filter_text( $message, $search_values ) {
	if ( ! empty( $message ) ) {
		$message = preg_replace( '/Search completed\. Found (\d+) matching records?\./i', 'Tìm kiếm hoàn tất. Tìm thấy $1 việc làm phù hợp.', $message );
	}
	return $message;
}

/**
 * Default company logo fallback filter
 */
add_filter( 'job_manager_default_company_logo', 'jobscout_default_company_logo_fallback' );
function jobscout_default_company_logo_fallback( $logo_url ) {
	$upload_dir = wp_upload_dir();
	$default_svg = $upload_dir['baseurl'] . '/2026/10/company-default.svg';
	return $default_svg;
}

/**
 * Register standard company logo size (preserve aspect ratio)
 */
add_action( 'after_setup_theme', 'jobscout_register_company_logo_size', 20 );
function jobscout_register_company_logo_size() {
	add_image_size( 'jobscout-company-logo', 160, 160, false );
}

/**
 * Filter the company logo URL to guarantee valid fallback if image is empty or invalid
 */
add_filter( 'the_company_logo', 'jobscout_enforce_valid_company_logo', 20, 2 );
function jobscout_enforce_valid_company_logo( $logo_url, $post ) {
	if ( empty( $logo_url ) ) {
		return jobscout_default_company_logo_fallback( '' );
	}
	return $logo_url;
}

/**
 * Automatically sync post thumbnail (Featured Image) to `_company_logo` meta whenever a job is saved or updated.
 * This guarantees 100% synchronization whether updated from WP Admin or Frontend form.
 */
add_action( 'save_post_job_listing', 'jobscout_sync_job_thumbnail_to_logo', 20, 2 );
function jobscout_sync_job_thumbnail_to_logo( $post_id, $post ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( wp_is_post_revision( $post_id ) ) {
		return;
	}
	if ( has_post_thumbnail( $post_id ) ) {
		$thumb_url = wp_get_attachment_image_url( get_post_thumbnail_id( $post_id ), 'full' );
		if ( $thumb_url ) {
			update_post_meta( $post_id, '_company_logo', $thumb_url );
		}
	}
}

/**
 * Automatically normalize posted company logo URL host before WPJM validation and create_attachment().
 * This ensures WPJM core's internal str_replace($upload_dir['baseurl'], ...) succeeds 100% whether accessed from localhost, IP, or domain.
 */
add_filter( 'job_manager_get_posted_fields', 'jobscout_normalize_posted_company_logo', 10, 2 );
function jobscout_normalize_posted_company_logo( $values, $fields ) {
	if ( ! empty( $values['company']['company_logo'] ) && is_string( $values['company']['company_logo'] ) ) {
		$raw_logo = $values['company']['company_logo'];
		$upload_dir = wp_upload_dir();
		if ( preg_match( '#/wp-content/uploads/(.+)$#i', $raw_logo, $matches ) ) {
			$values['company']['company_logo'] = trailingslashit( $upload_dir['baseurl'] ) . $matches[1];
		}
	}
	return $values;
}

/**
 * Robust Logo Resolver for Single Job & Preview Screens.
 * Discovers the uploaded image from thumbnail, meta, attachment records, or recent uploads directory.
 */
function jobscout_get_preview_or_job_logo( $job_id ) {
	$logo_url = '';

	// 1. Featured image (thumbnail)
	if ( has_post_thumbnail( $job_id ) ) {
		$logo_url = get_the_post_thumbnail_url( $job_id, 'full' );
	}

	// 2. Post meta `_company_logo`
	if ( empty( $logo_url ) ) {
		$logo_url = get_post_meta( $job_id, '_company_logo', true );
	}

	// 3. User meta `_company_logo`
	if ( empty( $logo_url ) ) {
		$user_logo_att = get_user_meta( get_current_user_id(), '_company_logo', true );
		if ( ! empty( $user_logo_att ) && is_numeric( $user_logo_att ) ) {
			$logo_url = wp_get_attachment_image_url( $user_logo_att, 'full' );
		}
	}

	// 4. Any attachment with parent $job_id
	if ( empty( $logo_url ) ) {
		$attachments = get_posts( array(
			'post_parent' => $job_id,
			'post_type'   => 'attachment',
			'numberposts' => 1,
			'orderby'     => 'ID',
			'order'       => 'DESC',
		) );
		if ( ! empty( $attachments ) ) {
			$logo_url = wp_get_attachment_url( $attachments[0]->ID );
			set_post_thumbnail( $job_id, $attachments[0]->ID );
			update_post_meta( $job_id, '_company_logo', $logo_url );
		}
	}

	// 5. Look for recently uploaded file in company_logo upload directory (within last 3 hours)
	if ( empty( $logo_url ) ) {
		$upload_dir = wp_upload_dir();
		$logo_folder = trailingslashit( $upload_dir['basedir'] ) . 'job-manager-uploads/company_logo/2026/10/';
		if ( is_dir( $logo_folder ) ) {
			$files = glob( $logo_folder . '*' );
			if ( ! empty( $files ) ) {
				$orig_files = array_filter( $files, function( $f ) {
					return ! preg_match( '/-\d+x\d+\.\w+$/', $f );
				} );
				if ( ! empty( $orig_files ) ) {
					usort( $orig_files, function( $a, $b ) {
						return filemtime( $b ) - filemtime( $a );
					} );
					$latest_file = reset( $orig_files );
					if ( ( time() - filemtime( $latest_file ) ) < 10800 ) { // within 3 hours
						$rel = 'job-manager-uploads/company_logo/2026/10/' . basename( $latest_file );
						$logo_url = trailingslashit( $upload_dir['baseurl'] ) . $rel;
						
						// Automatically attach to job
						include_once ABSPATH . 'wp-admin/includes/image.php';
						include_once ABSPATH . 'wp-admin/includes/media.php';
						$wp_filetype = wp_check_filetype( basename( $latest_file ), null );
						$attachment = array(
							'post_mime_type' => $wp_filetype['type'],
							'post_title'     => get_the_title( $job_id ) ?: basename( $latest_file ),
							'post_content'   => '',
							'post_status'    => 'inherit',
							'post_parent'    => $job_id,
							'guid'           => $logo_url,
						);
						$att_id = wp_insert_attachment( $attachment, $latest_file, $job_id );
						if ( ! is_wp_error( $att_id ) ) {
							wp_update_attachment_metadata( $att_id, wp_generate_attachment_metadata( $att_id, $latest_file ) );
							set_post_thumbnail( $job_id, $att_id );
							update_post_meta( $job_id, '_company_logo', $logo_url );
						}
					}
				}
			}
		}
	}

	// 6. Default fallback
	if ( empty( $logo_url ) ) {
		$upload_dir = wp_upload_dir();
		$logo_url = $upload_dir['baseurl'] . '/2026/10/company-default.svg';
	}

	if ( function_exists( 'jobscout_normalize_media_host' ) ) {
		$logo_url = jobscout_normalize_media_host( $logo_url );
	}

	return $logo_url;
}

/**
 * Ensure company logo uploaded during frontend submission/edit is ALWAYS saved and attached.
 * Fixes issue where uploads from localhost or relative paths fail create_attachment in WPJM core.
 */
add_action( 'job_manager_update_job_data', 'jobscout_ensure_company_logo_saved', 10, 2 );
function jobscout_ensure_company_logo_saved( $job_id, $values ) {
	$raw_logo = '';
	if ( ! empty( $values['company']['company_logo'] ) ) {
		$raw_logo = $values['company']['company_logo'];
	} elseif ( isset( $_POST['current_company_logo'] ) ) {
		$raw_logo = sanitize_text_field( wp_unslash( $_POST['current_company_logo'] ) );
	} elseif ( isset( $_POST['company_logo'] ) ) {
		$raw_logo = sanitize_text_field( wp_unslash( $_POST['company_logo'] ) );
	}

	if ( empty( $raw_logo ) ) {
		return;
	}

	// 1. If it's already an attachment ID:
	if ( is_numeric( $raw_logo ) && absint( $raw_logo ) > 0 ) {
		$attachment_id = absint( $raw_logo );
		set_post_thumbnail( $job_id, $attachment_id );
		$url = wp_get_attachment_image_url( $attachment_id, 'full' );
		if ( $url ) {
			update_post_meta( $job_id, '_company_logo', $url );
		}
		return;
	}

	// 2. If it's a URL or file path:
	$upload_dir = wp_upload_dir();
	$relative_path = '';

	if ( preg_match( '#/wp-content/uploads/(.+)$#i', $raw_logo, $matches ) ) {
		$relative_path = $matches[1];
	} elseif ( false !== strpos( $raw_logo, 'job-manager-uploads' ) ) {
		$parts = explode( 'job-manager-uploads', $raw_logo );
		$relative_path = 'job-manager-uploads' . end( $parts );
	}

	if ( ! empty( $relative_path ) ) {
		$file_path = trailingslashit( $upload_dir['basedir'] ) . $relative_path;
		if ( file_exists( $file_path ) ) {
			$existing_att = get_posts( array(
				'post_type'   => 'attachment',
				'meta_key'    => '_wp_attached_file',
				'meta_value'  => $relative_path,
				'fields'      => 'ids',
				'numberposts' => 1,
			) );

			if ( ! empty( $existing_att ) ) {
				$att_id = reset( $existing_att );
			} else {
				include_once ABSPATH . 'wp-admin/includes/image.php';
				include_once ABSPATH . 'wp-admin/includes/media.php';
				$wp_filetype = wp_check_filetype( basename( $file_path ), null );
				$attachment = array(
					'post_mime_type' => $wp_filetype['type'],
					'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $file_path ) ),
					'post_content'   => '',
					'post_status'    => 'inherit',
					'post_parent'    => $job_id,
					'guid'           => trailingslashit( $upload_dir['baseurl'] ) . $relative_path,
				);
				$att_id = wp_insert_attachment( $attachment, $file_path, $job_id );
				if ( ! is_wp_error( $att_id ) ) {
					$att_data = wp_generate_attachment_metadata( $att_id, $file_path );
					wp_update_attachment_metadata( $att_id, $att_data );
				}
			}

			if ( ! empty( $att_id ) && ! is_wp_error( $att_id ) ) {
				set_post_thumbnail( $job_id, $att_id );
				$full_url = wp_get_attachment_image_url( $att_id, 'full' );
				update_post_meta( $job_id, '_company_logo', $full_url ?: ( trailingslashit( $upload_dir['baseurl'] ) . $relative_path ) );
			}
		} else {
			update_post_meta( $job_id, '_company_logo', esc_url_raw( $raw_logo ) );
		}
	} else {
		update_post_meta( $job_id, '_company_logo', esc_url_raw( $raw_logo ) );
	}
}

/**
 * Automatically adapt siteurl & home to current request host (localhost or wordpress.local)
 * Prevents 301 redirect loop and DNS errors when team members open http://localhost
 */
add_filter( 'pre_option_siteurl', 'jobscout_dynamic_site_host_url', 1 );
add_filter( 'pre_option_home', 'jobscout_dynamic_site_host_url', 1 );
function jobscout_dynamic_site_host_url( $url ) {
	if ( ! empty( $_SERVER['HTTP_HOST'] ) ) {
		$proto = is_ssl() ? 'https://' : 'http://';
		return $proto . $_SERVER['HTTP_HOST'];
	}
	return $url;
}

/**
 * Automatically normalize media URLs to match current visitor HTTP_HOST.
 * Guarantees 100% visibility whether team members access via localhost, wordpress.local, or IP.
 */
add_filter( 'wp_get_attachment_url', 'jobscout_normalize_media_host', 99 );
add_filter( 'the_company_logo', 'jobscout_normalize_media_host', 99 );
add_filter( 'wp_get_attachment_image_src', 'jobscout_normalize_media_image_src', 99 );
function jobscout_normalize_media_host( $url ) {
	if ( empty( $url ) || ! is_string( $url ) ) {
		return $url;
	}
	$current_host = isset( $_SERVER['HTTP_HOST'] ) ? $_SERVER['HTTP_HOST'] : '';
	if ( ! empty( $current_host ) ) {
		$url = preg_replace( '#^https?://[^/]+#i', ( is_ssl() ? 'https://' : 'http://' ) . $current_host, $url );
	}
	return $url;
}
function jobscout_normalize_media_image_src( $image ) {
	if ( is_array( $image ) && ! empty( $image[0] ) ) {
		$image[0] = jobscout_normalize_media_host( $image[0] );
	}
	return $image;
}

/**
 * Custom CSS for Fixed Logo Box, Anti-distortion, Crisp Typography and Responsive Design
 */
add_action( 'wp_enqueue_scripts', 'jobscout_wpjm_custom_styles', 99 );
function jobscout_wpjm_custom_styles() {
	$custom_css = "
	/* Ẩn dòng thông tin phụ trùng lặp từ theme cha */
	.post-type-archive-job_listing .site-content > header.page-header,
	.page-template-page-jobs .site-content > header.page-header,
	.page-id-986 .site-content > header.page-header,
	.tax-job_listing_category .site-content > header.page-header .showing-result,
	.tax-job_listing_type .site-content > header.page-header .showing-result {
		display: none !important;
	}

	/* Loại bỏ sidebar làm lệch 75%, canh giữa 100% trang web cân bằng hoàn hảo */
	.archive.post-type-archive-job_listing #primary,
	.page-template-page-jobs #primary,
	.career-content-area,
	#primary.career-content-area,
	.rightsidebar .career-content-area,
	.leftsidebar .career-content-area,
	.rightsidebar.post-type-archive-job_listing #primary,
	.leftsidebar.post-type-archive-job_listing #primary,
	.tax-job_listing_category #primary,
	.tax-job_listing_type #primary {
		float: none !important;
		width: 100% !important;
		max-width: 100% !important;
		padding-left: 0 !important;
		padding-right: 0 !important;
		margin: 0 auto !important;
		box-sizing: border-box !important;
	}

	.archive.post-type-archive-job_listing #secondary,
	.page-template-page-jobs #secondary,
	.career-content-area ~ #secondary,
	.tax-job_listing_category #secondary,
	.tax-job_listing_type #secondary {
		display: none !important;
	}

	#job-posting-section,
	.career-jobs-section {
		width: 100% !important;
		background: #f5f5f7 !important;
		padding: 40px 0 60px 0 !important;
		box-sizing: border-box !important;
		display: flex !important;
		justify-content: center !important;
	}

	/* Cho phép trang Việc làm bung toàn màn hình (tràn viền) */
	.post-type-archive-job_listing .site-content,
	.page-template-page-jobs .site-content,
	.page-jobs .site-content,
	.tax-job_listing_category .site-content,
	.tax-job_listing_type .site-content {
		margin-top: 0 !important;
		margin-bottom: 0 !important;
		padding: 0 !important;
		max-width: 100% !important;
		width: 100% !important;
		overflow-x: hidden !important;
	}

	.post-type-archive-job_listing .site-content > .container,
	.page-template-page-jobs .site-content > .container,
	.page-jobs .site-content > .container,
	.tax-job_listing_category .site-content > .container,
	.tax-job_listing_type .site-content > .container {
		max-width: 100% !important;
		width: 100% !important;
		margin: 0 !important;
		padding: 0 !important;
	}

	.post-type-archive-job_listing #primary,
	.page-template-page-jobs #primary,
	.page-jobs #primary,
	.career-content-area {
		width: 100% !important;
		max-width: 100% !important;
		margin: 0 !important;
		padding: 0 !important;
		float: none !important;
		overflow: visible !important;
	}

	#job-posting-section .container,
	.career-jobs-section .container,
	.career-content-area > main > .container,
	.career-content-area .site-main > .container,
	.site-main > .container {
		max-width: 1200px !important;
		width: 100% !important;
		margin-left: auto !important;
		margin-right: auto !important;
		padding-left: 20px !important;
		padding-right: 20px !important;
		box-sizing: border-box !important;
		float: none !important;
	}

	/* ==========================================================================
	   1. HERO BANNER: CAREER WITH US (100% MATCH TO 4-all job 2.png)
	   ========================================================================== */
	.career-hero-banner {
		position: relative !important;
		width: 100% !important;
		max-width: 100% !important;
		margin: 0 0 35px 0 !important;
		min-height: 290px !important;
		background: url('http://wordpress.local/wp-content/uploads/2026/10/career-banner.jpg') center center / cover no-repeat !important;
		display: flex !important;
		align-items: center !important;
		justify-content: center !important;
		overflow: hidden !important;
	}

	.career-hero-overlay {
		position: absolute !important;
		inset: 0 !important;
		background: rgba(0, 0, 0, 0.38) !important;
	}

	.career-hero-content {
		position: relative !important;
		z-index: 2 !important;
		text-align: center !important;
	}

	.career-hero-title {
		color: #ffffff !important;
		font-size: 34px !important;
		font-weight: 700 !important;
		letter-spacing: 3px !important;
		text-transform: uppercase !important;
		margin: 0 !important;
		text-shadow: 0 2px 10px rgba(0, 0, 0, 0.4) !important;
	}

	/* ==========================================================================
	   2. ALL JOBS TOP BAR (HEADING + SEARCH + CATEGORY + SORT DROPDOWN)
	   ========================================================================== */
	.all-jobs-top-bar {
		display: flex !important;
		align-items: center !important;
		justify-content: space-between !important;
		margin: 24px 0 24px 0 !important;
		padding-bottom: 16px !important;
		border-bottom: 1px solid #f1f5f9 !important;
		flex-wrap: wrap !important;
		gap: 16px !important;
	}

	.all-jobs-heading {
		font-size: 24px !important;
		font-weight: 700 !important;
		color: #111827 !important;
		text-transform: uppercase !important;
		letter-spacing: 0.5px !important;
		margin: 0 !important;
	}

	.all-jobs-filters-right {
		display: flex !important;
		align-items: center !important;
		gap: 12px !important;
		flex-wrap: wrap !important;
	}

	/* Dropdown Tất cả ngành nghề + Nút kính lúp màu xanh khớp 100% ảnh 1 người dùng gửi */
	.user-category-filter-box {
		display: inline-flex !important;
		align-items: center !important;
		background: #ffffff !important;
		border: 1px solid #cbd5e1 !important;
		border-radius: 4px !important;
		overflow: hidden !important;
		height: 42px !important;
		box-sizing: border-box !important;
	}

	.user-category-filter-box select.user-category-select {
		height: 100% !important;
		border: none !important;
		outline: none !important;
		font-size: 14.5px !important;
		color: #334155 !important;
		padding: 0 34px 0 16px !important;
		background: #ffffff url('data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 20 20\' fill=\'%2364748b\'><path fill-rule=\'evenodd\' d=\'M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z\' clip-rule=\'evenodd\'/></svg>') no-repeat right 12px center / 16px !important;
		appearance: none !important;
		-webkit-appearance: none !important;
		cursor: pointer !important;
		min-width: 175px !important;
		box-shadow: none !important;
	}

	.user-category-filter-box select.user-category-select:focus {
		outline: none !important;
		box-shadow: none !important;
	}

	.user-category-filter-box button.user-category-btn {
		width: 44px !important;
		height: 100% !important;
		min-width: 44px !important;
		background: #27c96d !important;
		border: none !important;
		display: inline-flex !important;
		align-items: center !important;
		justify-content: center !important;
		cursor: pointer !important;
		padding: 0 !important;
		margin: 0 !important;
		transition: background 0.2s ease !important;
	}

	.user-category-filter-box button.user-category-btn:hover {
		background: #21b360 !important;
	}

	.user-category-filter-box button.user-category-btn svg {
		width: 16px !important;
		height: 16px !important;
		fill: #ffffff !important;
		display: block !important;
	}

	/* Sort [ Latest Jobs ▾ ] khớp 100% hình 2 */
	.all-jobs-sort-wrap select.all-jobs-sort-select {
		height: 42px !important;
		padding: 0 34px 0 14px !important;
		border: 1px solid #cbd5e1 !important;
		border-radius: 4px !important;
		font-size: 14px !important;
		color: #334155 !important;
		background: #ffffff url('data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 20 20\' fill=\'%2364748b\'><path fill-rule=\'evenodd\' d=\'M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z\' clip-rule=\'evenodd\'/></svg>') no-repeat right 12px center / 16px !important;
		appearance: none !important;
		-webkit-appearance: none !important;
		cursor: pointer !important;
		outline: none !important;
		min-width: 135px !important;
	}

	.all-jobs-sort-wrap select.all-jobs-sort-select:focus {
		border-color: #27c96d !important;
	}

	/* Ẩn form lọc cồng kềnh mặc định để giao diện giống 100% 4-all job 2.png */
	.career-jobs-wrapper form.job_filters,
	.job-listings-archive-wrapper form.job_filters {
		display: none !important;
	}

	/* Hero Banner CSS được quản lý trực tiếp và tối ưu tại sections/banner.php */

	/* ==========================================================================
	   4. LƯỚI VIỆC LÀM 2 CỘT (2-COLUMN GRID - CANH GIỮA TRANG, KHÔNG MẤT NỘI DUNG, ZOOM KHÔNG LỖI)
	   ========================================================================== */
	#job-posting-section,
	.career-jobs-section,
	.career-content-area {
		width: 100% !important;
		box-sizing: border-box !important;
		overflow: hidden !important;
	}

	#job-posting-section .container,
	.career-content-area .container,
	.career-jobs-section .container {
		max-width: 1200px !important;
		width: 100% !important;
		margin-left: auto !important;
		margin-right: auto !important;
		padding-left: 20px !important;
		padding-right: 20px !important;
		box-sizing: border-box !important;
	}

	.career-jobs-wrapper,
	.job-listings-archive-wrapper {
		width: 100% !important;
		max-width: 100% !important;
		margin-left: auto !important;
		margin-right: auto !important;
		box-sizing: border-box !important;
	}

	.career-jobs-wrapper div.job_listings div.job_listings,
	.career-jobs-wrapper div.job_listings > div:not(.search_jobs):not(.showing_jobs):not(.job_filters),
	.job-listings-archive-wrapper div.job_listings div.job_listings,
	.job-listings-archive-wrapper div.job_listings > div:not(.search_jobs):not(.showing_jobs):not(.job_filters),
	.career-jobs-wrapper ul.job_listings,
	.job-listings-archive-wrapper ul.job_listings,
	.site-main div.job_listings ul.job_listings,
	div.job_listings ul.job_listings,
	div.job_listings > div.job_listings {
		display: grid !important;
		grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
		gap: 24px !important;
		list-style: none !important;
		padding: 0 !important;
		margin: 24px 0 32px 0 !important;
		width: 100% !important;
		box-sizing: border-box !important;
	}

	@media (max-width: 960px) {
		.career-jobs-wrapper div.job_listings div.job_listings,
		.career-jobs-wrapper div.job_listings > div:not(.search_jobs):not(.showing_jobs):not(.job_filters),
		.job-listings-archive-wrapper div.job_listings div.job_listings,
		.job-listings-archive-wrapper div.job_listings > div:not(.search_jobs):not(.showing_jobs):not(.job_filters),
		.career-jobs-wrapper ul.job_listings,
		.job-listings-archive-wrapper ul.job_listings,
		.site-main div.job_listings ul.job_listings,
		div.job_listings ul.job_listings,
		div.job_listings > div.job_listings {
			grid-template-columns: 1fr !important;
		}
	}

	/* Thẻ bài đăng việc làm: Đảm bảo không bị tràn và canh đều */
	article.job_listing,
	article.career-job-card,
	/* Thẻ bài đăng việc làm: Hiệu ứng hover chuyển màu vào sống động, chuyên nghiệp */
	article.job_listing,
	article.career-job-card,
	li.job_listing article {
		width: 100% !important;
		max-width: 100% !important;
		min-width: 0 !important;
		box-sizing: border-box !important;
		background: #ffffff !important;
		border: 1.5px solid #e2e8f0 !important;
		border-radius: 8px !important;
		padding: 24px !important;
		box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04) !important;
		display: flex !important;
		flex-direction: column !important;
		justify-content: space-between !important;
		transition: all 0.28s cubic-bezier(0.4, 0, 0.2, 1) !important;
		margin: 0 !important;
		position: relative !important;
		min-height: 240px !important;
		overflow: hidden !important;
	}

	/* Hover vào việc làm: Chuyển màu nền nhẹ, viền cam nổi bật, nhấc bổng 4px */
	article.career-job-card:hover,
	article.job_listing:hover,
	li.job_listing article:hover {
		background: #fffbf9 !important;
		border-color: #ea580c !important;
		box-shadow: 0 12px 28px rgba(234, 88, 12, 0.12), 0 4px 10px rgba(0, 0, 0, 0.04) !important;
		transform: translateY(-4px) !important;
	}

	article.career-job-card:hover figure.company-logo-box,
	article.job_listing:hover figure.company-logo-box {
		border-color: #fdba74 !important;
		box-shadow: 0 4px 12px rgba(234, 88, 12, 0.15) !important;
	}

	/* Phần trên của Card: Logo + Thông tin tiêu đề */
	.job-card-top {
		display: flex !important;
		align-items: flex-start !important;
		gap: 16px !important;
		width: 100% !important;
		min-width: 0 !important;
		box-sizing: border-box !important;
	}

	/* Khung Logo vuông chuẩn 82x82px: Lấp đầy tối đa, hiển thị trọn vẹn 100% */
	figure.company-logo-box,
	figure.company-logo {
		width: 82px !important;
		height: 82px !important;
		min-width: 82px !important;
		max-width: 82px !important;
		flex: 0 0 82px !important;
		background: #ffffff !important;
		border: 1px solid #e5e7eb !important;
		border-radius: 8px !important;
		margin: 0 !important;
		padding: 0 !important;
		display: flex !important;
		align-items: center !important;
		justify-content: center !important;
		box-sizing: border-box !important;
		overflow: hidden !important;
		transition: all 0.25s ease !important;
	}

	figure.company-logo-box a,
	figure.company-logo a {
		width: 100% !important;
		height: 100% !important;
		display: flex !important;
		align-items: center !important;
		justify-content: center !important;
		margin: 0 !important;
		padding: 0 !important;
		overflow: hidden !important;
		border-radius: inherit !important;
	}

	/* HIỂN THỊ TOÀN BỘ HÌNH ẢNH TRONG KHUNG 82x82px (THẤY TRỌN VẸN CẢ HÌNH VÀ CHỮ, KHÔNG CẮT XÉN, KHÔNG BIẾN DẠNG) */
	figure.company-logo-box img,
	figure.company-logo img,
	.company_logo,
	.company_logo.full-frame-image,
	.career-job-card .company_logo {
		max-width: 95% !important;
		max-height: 95% !important;
		width: auto !important;
		height: auto !important;
		object-fit: contain !important;
		object-position: center !important;
		display: block !important;
		margin: auto !important;
		padding: 0 !important;
		border: none !important;
	}

	/* Cột thông tin bên phải Logo */
	.job-card-info {
		flex: 1 1 auto !important;
		min-width: 0 !important;
		width: calc(100% - 98px) !important;
		box-sizing: border-box !important;
		overflow: hidden !important;
	}

	.job-card-title,
	.job-card-title a {
		font-size: 1.05em !important;
		font-weight: 700 !important;
		color: #1e293b !important;
		text-transform: uppercase !important;
		letter-spacing: 0.3px !important;
		line-height: 1.35 !important;
		margin: 0 0 5px 0 !important;
		text-decoration: none !important;
		display: block !important;
		transition: color 0.25s ease !important;
		word-break: break-word !important;
		overflow-wrap: break-word !important;
	}

	.job-card-title a:hover,
	article.career-job-card:hover .job-card-title a,
	article.job_listing:hover .job-card-title a {
		color: #ea580c !important;
	}

	.job-card-created {
		font-size: 0.82em !important;
		color: #94a3b8 !important;
		margin-bottom: 8px !important;
		font-weight: 500 !important;
	}

	/* Khung Tag Pill: [ Fulltime | Category Name | Ho Chi Minh City ] (Khớp 100% hình 2) */
	.job-card-pill {
		display: inline-block !important;
		background: #f1f5f9 !important;
		border: 1px solid #e2e8f0 !important;
		border-radius: 4px !important;
		padding: 5px 12px !important;
		font-size: 0.82em !important;
		color: #475569 !important;
		font-weight: 500 !important;
		max-width: 100% !important;
		white-space: normal !important;
		word-break: break-word !important;
		line-height: 1.4 !important;
		box-sizing: border-box !important;
		vertical-align: middle !important;
	}

	/* Phần dưới của Card: Danh sách 3 gạch đầu dòng (Khớp 100% hình 2) */
	.job-card-bottom {
		margin-top: 14px !important;
		padding-top: 0 !important;
		border-top: none !important;
	}

	.job-card-bullets {
		margin: 0 !important;
		padding-left: 18px !important;
		list-style: disc !important;
	}

	.job-card-bullets li {
		font-size: 0.86em !important;
		color: #475569 !important;
		line-height: 1.6 !important;
		margin-bottom: 3px !important;
	}

	/* ==========================================================================
	   5. NÚT PHÂN TRANG (LOAD MORE JOBS - OUTLINE CAM 100% MATCH 4-all job 2.png)
	   ========================================================================== */
	.career-load-more-wrap {
		text-align: center !important;
		margin: 36px 0 45px 0 !important;
		width: 100% !important;
		clear: both !important;
	}

	.job_listings .load_more_jobs,
	a.load_more_jobs,
	.job-load-more-btn,
	.career-load-more-btn {
		display: inline-block !important;
		background: transparent !important;
		border: 1.5px solid #ea580c !important;
		color: #ea580c !important;
		padding: 12px 42px !important;
		font-weight: 700 !important;
		text-transform: uppercase !important;
		font-size: 13.5px !important;
		letter-spacing: 0.8px !important;
		border-radius: 4px !important;
		cursor: pointer !important;
		transition: all 0.25s ease !important;
		text-align: center !important;
		text-decoration: none !important;
		margin: 25px auto 35px auto !important;
	}

	.job_listings .load_more_jobs:hover,
	a.load_more_jobs:hover,
	.job-load-more-btn:hover,
	.career-load-more-btn:hover {
		background: #ea580c !important;
		color: #ffffff !important;
		box-shadow: 0 4px 14px rgba(234, 88, 12, 0.3) !important;
	}

	/* Ẩn widget footer cũ trên trang tuyển dụng để match 100% 4-all job 2.png */
	.page-template-page-jobs footer.site-footer .footer-t,
	.post-type-archive-job_listing footer.site-footer .footer-t,
	.page-id-925 footer.site-footer .footer-t,
	.page-id-1010 footer.site-footer .footer-t {
		display: none !important;
	}

	/* ==========================================================================
	   6. NEWSLETTER BAR (CAM TRÀN VIỀN 100% KHÔNG KHOẢNG TRẮNG)
	   ========================================================================== */
	.career-newsletter-bar {
		background: #ea580c !important;
		width: 100% !important;
		max-width: 100% !important;
		margin: 50px 0 0 0 !important;
		padding: 42px 0 !important;
		box-sizing: border-box !important;
		display: block !important;
	}

	.career-newsletter-bar .container {
		max-width: 1200px !important;
		width: 100% !important;
		margin: 0 auto !important;
		padding: 0 20px !important;
		box-sizing: border-box !important;
	}

	.career-newsletter-bar .newsletter-inner {
		max-width: 1140px !important;
		margin: 0 auto !important;
		padding: 0 !important;
		display: flex !important;
		align-items: center !important;
		justify-content: space-between !important;
		gap: 30px !important;
		flex-wrap: wrap !important;
	}

	.career-newsletter-bar .newsletter-title {
		color: #ffffff !important;
		font-size: 22px !important;
		font-weight: 700 !important;
		line-height: 1.25 !important;
	}

	.career-newsletter-bar .newsletter-form-wrap {
		display: flex !important;
		align-items: center !important;
		gap: 14px !important;
		flex: 1 1 500px !important;
		max-width: 580px !important;
	}

	.career-newsletter-bar .newsletter-input {
		flex: 1 !important;
		height: 46px !important;
		border: none !important;
		border-radius: 4px !important;
		padding: 0 16px !important;
		background: #ffffff !important;
		font-size: 14px !important;
		color: #1e293b !important;
		outline: none !important;
	}

	.career-newsletter-bar .newsletter-submit-btn {
		height: 46px !important;
		background: #c2410c !important;
		border: 1px solid #c2410c !important;
		color: #ffffff !important;
		font-weight: 700 !important;
		font-size: 14px !important;
		text-transform: uppercase !important;
		border-radius: 4px !important;
		padding: 0 28px !important;
		cursor: pointer !important;
		transition: background 0.2s ease !important;
	}

	.career-newsletter-bar .newsletter-submit-btn:hover {
		background: #9a3412 !important;
	}

	/* ==========================================================================
	   7. FOOTER BAR VÀ MẠNG XÃ HỘI (TRÀN VIỀN 100%)
	   ========================================================================== */
	.career-footer-bar {
		background: #ffffff !important;
		width: 100% !important;
		max-width: 100% !important;
		margin: 0 !important;
		padding: 38px 0 24px 0 !important;
		text-align: center !important;
		border-top: 1px solid #f1f5f9 !important;
		box-sizing: border-box !important;
		display: block !important;
	}

	.career-footer-bar .container {
		max-width: 1200px !important;
		width: 100% !important;
		margin: 0 auto !important;
		padding: 0 20px !important;
		box-sizing: border-box !important;
	}

	.career-footer-nav {
		display: flex !important;
		justify-content: center !important;
		align-items: center !important;
		gap: 28px !important;
		margin-bottom: 22px !important;
		flex-wrap: wrap !important;
	}

	.career-footer-nav a {
		font-size: 13.5px !important;
		font-weight: 700 !important;
		color: #1e293b !important;
		text-decoration: none !important;
		text-transform: uppercase !important;
		letter-spacing: 0.5px !important;
		transition: color 0.2s ease !important;
	}

	.career-footer-nav a:hover {
		color: #ea580c !important;
	}

	.career-footer-social {
		display: flex !important;
		justify-content: center !important;
		align-items: center !important;
		gap: 14px !important;
		margin-bottom: 35px !important;
	}

	.career-footer-social .social-circle {
		width: 36px !important;
		height: 36px !important;
		border-radius: 50% !important;
		display: inline-flex !important;
		align-items: center !important;
		justify-content: center !important;
		color: #ffffff !important;
		text-decoration: none !important;
		font-size: 15px !important;
		transition: transform 0.2s ease !important;
	}

	.career-footer-social .social-circle:hover {
		transform: scale(1.1) !important;
	}

	.career-footer-social .social-circle.fb { background: #1877f2 !important; }
	.career-footer-social .social-circle.gg { background: #ea4335 !important; }
	.career-footer-social .social-circle.line { background: #06c755 !important; }
	.career-footer-social .social-circle.tw { background: #1da1f2 !important; }

	/* Nút ĐĂNG TIN TUYỂN DỤNG trên header: nằm đều, không xuống hàng, chữ liền mạch */
	.site-header .menu-wrap .btn-wrap,
	.header-main .menu-wrap .btn-wrap {
		width: auto !important;
		min-width: fit-content !important;
		flex-shrink: 0 !important;
	}

	.site-header .menu-wrap .btn-wrap a.btn,
	.site-header .btn-wrap a,
	.site-header a.btn-primary,
	.header-b a.btn {
		background: #ea580c !important;
		border-color: #ea580c !important;
		color: #ffffff !important;
		border-radius: 4px !important;
		font-weight: 700 !important;
		font-size: 14px !important;
		white-space: nowrap !important;
		padding: 10px 22px !important;
		display: inline-flex !important;
		align-items: center !important;
		justify-content: center !important;
		line-height: 1.4 !important;
		text-decoration: none !important;
		box-sizing: border-box !important;
	}

	/* Menu Header: Top level navigation */
	.main-navigation > ul > li > a,
	.main-navigation > div > ul > li > a,
	.main-navigation ul.nav-menu > li > a {
		border-bottom: 2px solid transparent !important;
		transition: all 0.25s ease !important;
		position: relative !important;
		color: #1e293b !important;
	}

	.main-navigation > ul > li.current-menu-item > a,
	.main-navigation > ul > li.current_page_item > a,
	.main-navigation > div > ul > li.current-menu-item > a,
	.main-navigation > div > ul > li.current_page_item > a,
	.main-navigation ul.nav-menu > li.current-menu-item > a,
	.main-navigation ul.nav-menu > li.current_page_item > a {
		color: #ea580c !important;
		border-bottom: 2px solid #ea580c !important;
	}

	.main-navigation > ul > li:hover > a,
	.main-navigation > div > ul > li:hover > a,
	.main-navigation ul.nav-menu > li:hover > a {
		color: #ea580c !important;
	}

	/* SUBMENU DROPDOWN: THIẾT KẾ HIỆN ĐẠI, HOVER PHÂN TÁCH RÕ RÀNG 100% (Sửa lỗi hover vào cái này không thấy cái kia) */
	.main-navigation ul.sub-menu,
	.main-navigation ul ul {
		background: #ffffff !important;
		border: 1px solid #e2e8f0 !important;
		border-radius: 10px !important;
		box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
		padding: 6px !important;
		min-width: 230px !important;
		overflow: hidden !important;
	}

	.main-navigation ul.sub-menu li,
	.main-navigation ul ul li {
		margin: 2px 0 !important;
		padding: 0 !important;
		display: block !important;
	}

	/* Submenu link mặc định: chữ xám đậm sắc nét, nền trong suốt, không viền gạch trái */
	.main-navigation ul.sub-menu li a,
	.main-navigation ul ul li a {
		border: none !important;
		border-bottom: none !important;
		border-left: none !important;
		color: #334155 !important;
		padding: 10px 16px !important;
		font-size: 14.5px !important;
		font-weight: 500 !important;
		display: block !important;
		border-radius: 6px !important;
		transition: all 0.2s ease !important;
		background: transparent !important;
		text-decoration: none !important;
	}

	/* Khi hover vào mục bất kỳ: Nền CAM ĐẬM, chữ TRẮNG TUYỆT ĐỐI nổi bật 100%, không bị chìm hay lẫn với mục khác */
	.main-navigation ul.sub-menu li a:hover,
	.main-navigation ul.sub-menu li:hover > a,
	.main-navigation ul ul li a:hover,
	.main-navigation ul ul li:hover > a {
		background: #ea580c !important;
		color: #ffffff !important;
		font-weight: 600 !important;
		border: none !important;
		border-left: none !important;
		border-radius: 6px !important;
		box-shadow: 0 2px 8px rgba(234, 88, 12, 0.28) !important;
		transform: none !important;
		padding-left: 16px !important;
	}

	/* Mục đang Active (khi chưa hover): Nền cam nhẹ, chữ cam - phân biệt rõ với mục thường và mục hover */
	.main-navigation ul.sub-menu li.current-menu-item > a,
	.main-navigation ul.sub-menu li.current_page_item > a {
		background: #fff7ed !important;
		color: #ea580c !important;
		font-weight: 600 !important;
		border-radius: 6px !important;
		border: none !important;
		border-left: none !important;
		border-bottom: none !important;
	}

	/* Khi hover chính vào mục Active: chuyển sang nền Cam đậm + chữ Trắng nổi bật */
	.main-navigation ul.sub-menu li.current-menu-item > a:hover,
	.main-navigation ul.sub-menu li.current_page_item > a:hover {
		background: #ea580c !important;
		color: #ffffff !important;
	}

	article.job_listing .job-title-wrap .entry-title,
	.site-main .entry-content div.job_listings div.job_listings article .entry-title {
		font-size: 1.15em !important;
		font-weight: 700 !important;
		line-height: 1.4 !important;
		margin: 0 0 6px !important;
		color: #1e293b !important;
	}

	article.job_listing .job-title-wrap .entry-title a {
		color: #1e293b !important;
		text-decoration: none !important;
		transition: color 0.2s ease !important;
		display: inline !important;
	}

	article.job_listing .job-title-wrap .entry-title a:hover {
		color: #2ace5e !important;
	}

	article.job_listing .job-title-wrap .company-name,
	.site-main .entry-content div.job_listings div.job_listings article .company-name {
		font-size: 0.94em !important;
		font-weight: 600 !important;
		color: #334155 !important;
		margin: 0 0 8px !important;
		display: flex !important;
		align-items: center !important;
		gap: 6px !important;
		line-height: 1.35 !important;
	}

	article.job_listing .job-title-wrap .company-name i {
		color: #64748b;
	}

	article.job_listing .job-title-wrap .entry-meta,
	.site-main .entry-content div.job_listings div.job_listings article .entry-meta {
		display: flex !important;
		flex-wrap: wrap !important;
		align-items: center !important;
		gap: 8px 14px !important;
		font-size: 0.88em !important;
		color: #64748b !important;
		line-height: 1.5 !important;
	}

	article.job_listing .job-title-wrap .entry-meta .salary-amt {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		color: #059669;
		font-weight: 600;
		background: #ecfdf5;
		padding: 3px 10px;
		border-radius: 4px;
	}

	article.job_listing .job-title-wrap .entry-meta .salary-amt .currency svg {
		width: 13px;
		height: 13px;
		fill: #059669;
	}

	article.job_listing .job-title-wrap .entry-meta .company-address {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		color: #475569;
	}

	article.job_listing .job-title-wrap .entry-meta .company-address i {
		color: #94a3b8;
	}

	article.job_listing .job-title-wrap .entry-meta .job-category {
		display: inline-flex;
		align-items: center;
		gap: 5px;
	}

	article.job_listing .job-title-wrap .entry-meta .job-category i {
		color: #2ace5e;
	}

	article.job_listing .job-title-wrap .entry-meta .job-category a {
		color: #334155;
		font-weight: 500;
		text-decoration: none;
	}

	article.job_listing .job-title-wrap .entry-meta .job-category a:hover {
		color: #2ace5e;
	}

	article.job_listing .job-title-wrap .entry-meta .job-deadline {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		color: #dc2626;
		background: #fef2f2;
		padding: 3px 9px;
		border-radius: 4px;
		font-weight: 600;
	}

	article.job_listing .job-title-wrap .entry-meta .job-deadline i {
		color: #ef4444;
	}

	article.job_listing .job-title-wrap .entry-meta .job-posted-date {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		color: #94a3b8;
	}

	article.job_listing .job-title-wrap .entry-meta .job-posted-date i {
		color: #cbd5e1;
	}

	article.job_listing .job-title-wrap .entry-meta li.job-type {
		list-style: none;
		display: inline-block;
		padding: 3px 10px;
		border-radius: 20px;
		font-weight: 600;
		font-size: 0.85em;
		background: #f1f5f9;
		color: #475569;
	}

	article.job_listing .job-title-wrap .entry-meta li.job-type.toan-thoi-gian {
		background: #eff6ff;
		color: #2563eb;
	}

	article.job_listing .job-title-wrap .entry-meta li.job-type.ban-thoi-gian {
		background: #fefce8;
		color: #ca8a04;
	}

	article.job_listing .job-title-wrap .entry-meta li.job-type.tu-do,
	article.job_listing .job-title-wrap .entry-meta li.job-type.tu-do-freelance {
		background: #f5f3ff;
		color: #7c3aed;
	}

	article.job_listing .featured-label {
		position: absolute;
		top: 14px;
		right: 14px;
		background: linear-gradient(135deg, #f59e0b, #d97706);
		color: #ffffff;
		font-size: 0.75em;
		font-weight: 700;
		text-transform: uppercase;
		padding: 3px 8px;
		border-radius: 4px;
		letter-spacing: 0.5px;
	}

	/* ==========================================================================
	   3. BỘ LỌC TÌM KIẾM - FORM GỌN GÀNG, HIỆN ĐẠI
	   ========================================================================== */
	.job-listings-archive-wrapper {
		margin-top: 15px;
	}

	form.job_filters {
		background: #ffffff;
		border: 1px solid #e2e8f0;
		border-radius: 12px;
		padding: 24px;
		box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
		margin-bottom: 25px;
	}

	form.job_filters .search_jobs {
		display: flex;
		flex-wrap: wrap;
		gap: 16px;
		align-items: flex-end;
	}

	form.job_filters .search_jobs > div {
		flex: 1 1 200px;
		min-width: 170px;
	}

	form.job_filters .search_jobs > div label {
		display: block;
		margin-bottom: 6px;
		font-weight: 600;
		font-size: 0.88em;
		color: #334155;
	}

	form.job_filters .search_jobs input[type='text'],
	form.job_filters .search_jobs select {
		width: 100%;
		height: 44px;
		border: 1px solid #cbd5e1;
		border-radius: 6px;
		padding: 0 12px;
		font-size: 0.9em;
		color: #1e293b;
		background: #ffffff;
		box-sizing: border-box;
		transition: border-color 0.2s ease, box-shadow 0.2s ease;
	}

	form.job_filters .search_jobs input[type='text']:focus,
	form.job_filters .search_jobs select:focus {
		border-color: #2ace5e;
		box-shadow: 0 0 0 3px rgba(42, 206, 94, 0.15);
		outline: none;
	}

	form.job_filters .search_jobs .search_submit {
		flex: 0 0 130px;
	}

	form.job_filters .search_jobs .search_submit input[type='submit'] {
		width: 100%;
		height: 44px;
		background: #2ace5e;
		color: #ffffff;
		font-weight: 700;
		font-size: 0.92em;
		border: none;
		border-radius: 6px;
		cursor: pointer;
		transition: background 0.2s ease, transform 0.15s ease;
	}

	form.job_filters .search_jobs .search_submit input[type='submit']:hover {
		background: #24b552;
		transform: translateY(-1px);
	}

	form.job_filters .search_remote_position {
		display: flex;
		align-items: center;
		gap: 6px;
		padding-top: 10px;
		flex: 1 1 100%;
	}

	form.job_filters .search_remote_position input[type='checkbox'] {
		margin: 0;
		accent-color: #2ace5e;
	}

	form.job_filters ul.job_types {
		display: flex;
		flex-wrap: wrap;
		gap: 12px;
		list-style: none;
		padding: 14px 0 0;
		margin: 16px 0 0;
		border-top: 1px dashed #e2e8f0;
		width: 100%;
	}

	form.job_filters ul.job_types li {
		margin: 0;
	}

	form.job_filters ul.job_types label {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		cursor: pointer;
		font-size: 0.88em;
		color: #475569;
		padding: 5px 12px;
		border-radius: 20px;
		background: #f8fafc;
		border: 1px solid #e2e8f0;
		transition: all 0.2s ease;
		user-select: none;
	}

	form.job_filters ul.job_types label:hover {
		border-color: #2ace5e;
		background: #f0fdf4;
	}

	form.job_filters ul.job_types input[type='checkbox'] {
		margin: 0;
		accent-color: #2ace5e;
	}

	div.showing_jobs {
		font-size: 0.92em;
		color: #64748b;
		margin: 0 0 16px;
		font-weight: 500;
		display: flex;
		align-items: center;
		justify-content: space-between;
	}

	div.showing_jobs a.reset {
		color: #ef4444;
		text-decoration: underline;
		font-weight: 600;
		cursor: pointer;
	}

	/* ==========================================================================
	   4. PHÂN TRANG (PAGINATION)
	   ========================================================================== */
	.job-manager-pagination {
		margin: 35px 0;
		text-align: center;
	}

	.job-manager-pagination ul {
		display: inline-flex;
		flex-wrap: wrap;
		list-style: none;
		padding: 0;
		margin: 0;
		gap: 8px;
		justify-content: center;
		align-items: center;
	}

	.job-manager-pagination ul li {
		display: inline-block;
		margin: 0;
	}

	.job-manager-pagination ul li a,
	.job-manager-pagination ul li span.current,
	.job-manager-pagination ul li span.gap {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		min-width: 40px;
		height: 40px;
		padding: 0 12px;
		text-align: center;
		border: 1px solid #e2e8f0;
		border-radius: 6px;
		font-size: 0.9em;
		font-weight: 600;
		color: #4a5568;
		background-color: #ffffff;
		transition: all 0.2s ease-in-out;
		text-decoration: none;
	}

	.job-manager-pagination ul li a:hover {
		background-color: #2ace5e;
		border-color: #2ace5e;
		color: #ffffff;
	}

	.job-manager-pagination ul li span.current {
		background-color: #2ace5e;
		border-color: #2ace5e;
		color: #ffffff;
	}

	.job-manager-pagination ul li span.gap {
		border-color: transparent;
		background: transparent;
		color: #a0aec0;
	}

	/* ==========================================================================
	   5. TRANG CHI TIẾT VIỆC LÀM (SINGLE JOB) - LOGO VÀ META CỐ ĐỊNH
	   ========================================================================== */
	.single_job_listing .company {
		display: flex !important;
		align-items: center !important;
		gap: 20px !important;
		padding: 22px !important;
		background: #f8fafc !important;
		border: 1px solid #e2e8f0 !important;
		border-radius: 12px !important;
		margin-bottom: 25px !important;
	}

	.single_job_listing .company img.company_logo {
		width: 68px !important;
		height: 68px !important;
		min-width: 68px !important;
		max-width: 68px !important;
		flex: 0 0 68px !important;
		aspect-ratio: 1 / 1 !important;
		object-fit: cover !important;       /* Lấp đầy 100% khung không để khoảng trắng */
		object-position: center !important;
		background: #ffffff !important;
		padding: 0 !important;
		border: 1px solid #e9ecef !important;
		border-radius: 8px !important;
		box-sizing: border-box !important;
		overflow: hidden !important;
		transform: none !important;
	}

	.single_job_listing .company .company_header {
		flex: 1 1 auto !important;
		min-width: 0 !important;
	}

	.single_job_listing .company .company_header strong {
		font-size: 1.25em !important;
		color: #1e293b !important;
		display: block !important;
		margin-bottom: 4px !important;
	}

	.single_job_listing .company .company_header a.website {
		font-size: 0.9em !important;
		color: #2ace5e !important;
		text-decoration: none !important;
		font-weight: 500 !important;
		margin-right: 12px !important;
	}

	.single_job_listing .company .company_header a.website:hover {
		text-decoration: underline !important;
	}

	.single_job_listing ul.job-listing-meta.meta {
		display: flex !important;
		flex-wrap: wrap !important;
		gap: 12px !important;
		list-style: none !important;
		padding: 0 !important;
		margin: 20px 0 !important;
	}

	.single_job_listing ul.job-listing-meta.meta li {
		display: inline-flex !important;
		align-items: center !important;
		gap: 6px !important;
		padding: 6px 14px !important;
		background: #f1f5f9 !important;
		border-radius: 20px !important;
		font-size: 0.9em !important;
		color: #475569 !important;
		font-weight: 500 !important;
	}

	.single_job_listing ul.job-listing-meta.meta li i {
		color: #2ace5e !important;
	}

	.single_job_listing ul.job-listing-meta.meta li.salary {
		background: #ecfdf5 !important;
		color: #047857 !important;
		font-weight: 600 !important;
	}

	.single_job_listing ul.job-listing-meta.meta li.job-deadline {
		background: #fef2f2 !important;
		color: #b91c1c !important;
		font-weight: 600 !important;
	}

	.single_job_listing ul.job-listing-meta.meta li.job-deadline i {
		color: #dc2626 !important;
	}

	.single_job_listing ul.job-listing-meta.meta li.position-filled,
	.single_job_listing ul.job-listing-meta.meta li.listing-expired {
		background: #fee2e2 !important;
		color: #991b1b !important;
	}

	.single_job_listing .job_application.application {
		margin: 35px 0 20px !important;
		text-align: left !important;
	}

	.single_job_listing .job_application.application .application_button {
		background: #2ace5e !important;
		color: #ffffff !important;
		border: none !important;
		padding: 13px 32px !important;
		border-radius: 6px !important;
		font-weight: 700 !important;
		font-size: 1.05em !important;
		cursor: pointer !important;
		transition: background 0.2s ease, transform 0.15s ease !important;
		box-shadow: 0 4px 12px rgba(42, 206, 94, 0.25) !important;
	}

	.single_job_listing .job_application.application .application_button:hover {
		background: #24b552 !important;
		transform: translateY(-2px) !important;
	}

	.single_job_listing .job_application.application .application_details {
		background: #f0fdf4 !important;
		border: 1px solid #bbf7d0 !important;
		border-radius: 10px !important;
		padding: 20px 24px !important;
		margin-top: 18px !important;
		color: #166534 !important;
	}

	.single_job_listing .job_application.application .application_details a {
		color: #15803d !important;
		font-weight: 700 !important;
		text-decoration: underline !important;
	}

	.single_job_listing .job_application.application p.job-application-prompt {
		font-size: 1.05em !important;
		line-height: 1.6 !important;
		margin: 0 !important;
	}

	.single_job_listing .job_application.application p.job-application-prompt i {
		margin-right: 6px !important;
		color: #2ace5e !important;
	}

	/* Ẩn trường lương trùng lặp từ plugin wpjm-extra-fields */
	li.wpjmef-field-salary {
		display: none !important;
	}

	/* ==========================================================================
	   6. TƯƠNG THÍCH THIẾT BỊ DI ĐỘNG (MOBILE RESPONSIVE)
	   ========================================================================== */
	@media (max-width: 767px) {
		article.job_listing,
		.site-main article.job_listing,
		article.type-job_listing,
		.site-main .entry-content div.job_listings div.job_listings article {
			flex-direction: column !important;
			align-items: stretch !important;
			padding: 0 !important;
		}
		article.job_listing figure.company-logo,
		article.job_listing figure.post-thumbnail,
		.site-main article.job_listing figure.company-logo {
			width: 100% !important;
			max-width: 100% !important;
			min-width: 100% !important;
			flex: 0 0 auto !important;
			height: 210px !important;
			min-height: 210px !important;
			margin: 0 !important;
			border-radius: 8px 8px 0 0 !important;
		}
		article.job_listing .job-title-wrap,
		article.job_listing .content-wrap,
		.site-main article.job_listing .job-title-wrap {
			width: 100% !important;
			padding: 18px 20px !important;
		}
		article.job_listing .job-title-wrap .entry-title {
			font-size: 1.08em !important;
		}
		form.job_filters {
			padding: 16px !important;
		}
		form.job_filters .search_jobs > div {
			flex: 1 1 100% !important;
		}
		form.job_filters .search_jobs .search_submit {
			flex: 1 1 100% !important;
		}
		.single_job_listing .company {
			flex-direction: column !important;
			text-align: center !important;
		}
	}

	/* Chỉ hiển thị thông báo không tìm thấy việc làm 1 lần, full width, căn giữa đẹp mắt */
	li.no_job_listings_found,
	p.no_job_listings_found {
		grid-column: 1 / -1 !important;
		width: 100% !important;
		box-sizing: border-box !important;
		text-align: center !important;
		padding: 50px 20px !important;
		margin: 20px auto !important;
		background: #f8fafc !important;
		border: 1.5px dashed #cbd5e1 !important;
		border-radius: 8px !important;
		color: #64748b !important;
		font-size: 15.5px !important;
		font-weight: 500 !important;
		list-style: none !important;
	}

	/* ==========================================================================
	   GIAO DIỆN QUẢN LÝ TIN TUYỂN DỤNG & ĐĂNG TIN (CRUD FRONTEND)
	   ========================================================================== */
	/* Ẩn hoàn toàn Sidebar thừa ở trang Quản lý tin & Đăng tin, bung full-width 1240px để thấy trọn vẹn cụm Chỉnh sửa */
	.page-id-9 #primary,
	.page-id-8 #primary,
	body.page-id-9 #primary,
	body.page-id-8 #primary {
		width: 100% !important;
		max-width: 100% !important;
		float: none !important;
		margin: 0 auto !important;
	}

	.page-id-9 #secondary,
	.page-id-8 #secondary,
	body.page-id-9 #secondary,
	body.page-id-8 #secondary {
		display: none !important;
	}

	.page-id-9 .site-content > .container,
	.page-id-8 .site-content > .container {
		max-width: 1240px !important;
		width: 100% !important;
	}

	#job-manager-job-dashboard {
		margin: 30px auto 50px auto !important;
		max-width: 1240px !important;
		padding: 0 15px !important;
	}

	.job-manager-jobs {
		width: 100% !important;
		border: 1px solid #e2e8f0 !important;
		border-radius: 8px !important;
		overflow: hidden !important;
		background: #ffffff !important;
		box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04) !important;
	}

	.job-manager-jobs table.job-manager-jobs,
	.job-manager-jobs .jm-dashboard-header {
		background: #f8fafc !important;
		font-weight: 600 !important;
		color: #1e293b !important;
		border-bottom: 2px solid #e2e8f0 !important;
	}

	.job-dashboard-action-edit,
	.job-dashboard-action-delete,
	.job-dashboard-action-mark_filled,
	.job-dashboard-action-relist {
		display: inline-block !important;
		padding: 5px 12px !important;
		border-radius: 4px !important;
		font-size: 12.5px !important;
		font-weight: 600 !important;
		text-decoration: none !important;
		margin: 2px 4px 2px 0 !important;
		transition: all 0.2s ease !important;
	}

	.job-dashboard-action-edit {
		background: #3b82f6 !important;
		color: #ffffff !important;
	}
	.job-dashboard-action-edit:hover {
		background: #2563eb !important;
		color: #ffffff !important;
	}

	.job-dashboard-action-delete {
		background: #ef4444 !important;
		color: #ffffff !important;
	}
	.job-dashboard-action-delete:hover {
		background: #dc2626 !important;
		color: #ffffff !important;
	}

	.job-dashboard-action-mark_filled {
		background: #e2e8f0 !important;
		color: #475569 !important;
	}
	.job-dashboard-action-mark_filled:hover {
		background: #cbd5e1 !important;
	}

	/* Nút thêm việc làm mới */
	.jm-dashboard__actions a,
	.job-dashboard-add-btn {
		background: #27c96d !important;
		color: #ffffff !important;
		padding: 10px 20px !important;
		border-radius: 6px !important;
		font-weight: 600 !important;
		display: inline-flex !important;
		align-items: center !important;
		gap: 6px !important;
		text-decoration: none !important;
	}
	.jm-dashboard__actions a:hover,
	.job-dashboard-add-btn:hover {
		background: #21b360 !important;
		color: #ffffff !important;
	}

	/* Form Đăng tin tuyển dụng */
	form.job-manager-form {
		max-width: 900px !important;
		margin: 30px auto !important;
		background: #ffffff !important;
		border: 1px solid #e2e8f0 !important;
		border-radius: 8px !important;
		padding: 35px 40px !important;
		box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
	}

	form.job-manager-form fieldset {
		border: none !important;
		padding: 0 !important;
		margin-bottom: 20px !important;
	}

	form.job-manager-form fieldset label {
		font-weight: 600 !important;
		color: #1e293b !important;
		font-size: 14px !important;
		margin-bottom: 6px !important;
		display: block !important;
	}

	form.job-manager-form input.input-text,
	form.job-manager-form select,
	form.job-manager-form textarea {
		width: 100% !important;
		border: 1px solid #cbd5e1 !important;
		border-radius: 6px !important;
		padding: 10px 14px !important;
		font-size: 14px !important;
		color: #334155 !important;
		box-sizing: border-box !important;
		outline: none !important;
	}

	form.job-manager-form input.input-text:focus,
	form.job-manager-form select:focus,
	form.job-manager-form textarea:focus {
		border-color: #27c96d !important;
		box-shadow: 0 0 0 3px rgba(39, 201, 109, 0.15) !important;
	}

	/* PHAN TACH RO RANG NUT XEM TRUOC VA LUU NHAP (Hinh 1) */
	.job-submit-buttons-wrap,
	#submit-job-form p:last-child,
	.job-manager-form p:last-child {
		display: flex !important;
		flex-wrap: wrap !important;
		align-items: center !important;
		gap: 16px !important;
		margin-top: 30px !important;
		padding-top: 20px !important;
		border-top: 1px solid #f1f5f9 !important;
	}

	#submit-job-form input[type='submit'],
	.job-manager-form input[type='submit'],
	.job-manager-form button[type='submit'] {
		margin: 0 16px 10px 0 !important;
		border-radius: 6px !important;
		padding: 12px 28px !important;
		font-size: 15px !important;
		font-weight: 700 !important;
		cursor: pointer !important;
		transition: all 0.25s ease !important;
		min-height: 48px !important;
		box-sizing: border-box !important;
		display: inline-flex !important;
		align-items: center !important;
		justify-content: center !important;
	}

	/* Nút Xem trước (Primary) */
	#submit-job-form input[name='submit_job'],
	.job-manager-form input[name='submit_job'],
	.btn-submit-job {
		background: #27c96d !important;
		border: 1.5px solid #27c96d !important;
		color: #ffffff !important;
		box-shadow: 0 4px 12px rgba(39, 201, 109, 0.25) !important;
	}
	#submit-job-form input[name='submit_job']:hover,
	.job-manager-form input[name='submit_job']:hover,
	.btn-submit-job:hover {
		background: #21b360 !important;
		border-color: #21b360 !important;
		transform: translateY(-2px) !important;
		box-shadow: 0 6px 16px rgba(39, 201, 109, 0.35) !important;
	}

	/* Nút Lưu nháp (Secondary - phân biệt độc lập hoàn toàn, không dính liền) */
	#submit-job-form input[name='save_draft'],
	.job-manager-form input[name='save_draft'],
	.job-manager-form .button.save_draft,
	.btn-save-draft {
		background: #ffffff !important;
		border: 1.5px solid #0ea5e9 !important;
		color: #0ea5e9 !important;
		box-shadow: 0 2px 8px rgba(14, 165, 233, 0.15) !important;
	}
	#submit-job-form input[name='save_draft']:hover,
	.job-manager-form input[name='save_draft']:hover,
	.job-manager-form .button.save_draft:hover,
	.btn-save-draft:hover {
		background: #0ea5e9 !important;
		color: #ffffff !important;
		border-color: #0ea5e9 !important;
		transform: translateY(-2px) !important;
		box-shadow: 0 6px 16px rgba(14, 165, 233, 0.3) !important;
	}

	/* THÔNG BÁO LỖI VÀ THÀNH CÔNG ĐẸP MẮT, TRỰC QUAN */
	.job-manager-error,
	.job-manager-form .job-manager-error {
		background: #fef2f2 !important;
		border: 1.5px solid #fecaca !important;
		border-left: 5px solid #ef4444 !important;
		border-radius: 10px !important;
		padding: 16px 22px !important;
		margin: 20px 0 !important;
		color: #991b1b !important;
		font-size: 15px !important;
		font-weight: 600 !important;
		line-height: 1.6 !important;
		box-shadow: 0 4px 14px rgba(239, 68, 68, 0.08) !important;
		display: flex !important;
		align-items: center !important;
		gap: 12px !important;
		list-style: none !important;
	}
	.job-manager-error::before {
		content: \"\\f06a\" !important;
		font-family: \"Font Awesome 5 Free\" !important;
		font-weight: 900 !important;
		font-size: 20px !important;
		color: #ef4444 !important;
		flex-shrink: 0 !important;
	}

	.jm-notice,
	.jm-notice:not(.color-error),
	.jm-notice.color-success,
	.alignwide > .jm-notice,
	.alignwide .jm-notice,
	#job-manager-job-dashboard .jm-notice,
	.job-manager-message,
	.job-manager-form .job-manager-message {
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
	.jm-notice:not(.color-error) [data-icon=check-circle] {
		color: #10b981 !important;
		background-color: #10b981 !important;
		fill: #10b981 !important;
		stroke: #10b981 !important;
		width: 22px !important;
		height: 22px !important;
		min-width: 22px !important;
		flex-shrink: 0 !important;
	}

	.job-manager-message::before {
		content: \"\\f058\" !important;
		font-family: \"Font Awesome 5 Free\" !important;
		font-weight: 900 !important;
		font-size: 20px !important;
		color: #10b981 !important;
		flex-shrink: 0 !important;
	}

	/* Field validation highlights */
	.field.has-error input,
	.field.has-error textarea,
	.field.has-error select {
		border-color: #ef4444 !important;
		background: #fff5f5 !important;
		box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15) !important;
	}
	";
	wp_add_inline_style( 'jobscout', $custom_css );
	return $custom_css;
}

/**
 * Filter WPJM notices to ensure color-success is added to non-error notices
 */
add_filter( 'wpjm_notice', 'jobscout_ensure_notice_success_class', 10, 2 );
function jobscout_ensure_notice_success_class( $notice_html, $template_options = array() ) {
	if ( ! empty( $template_options['classes'] ) && in_array( 'color-error', $template_options['classes'], true ) ) {
		return $notice_html;
	}
	if ( strpos( $notice_html, 'color-error' ) === false ) {
		if ( strpos( $notice_html, 'color-success' ) === false ) {
			$notice_html = preg_replace( '/class=(["\'])jm-notice/', 'class=$1jm-notice color-success', $notice_html, 1 );
		}
	}
	return $notice_html;
}

/**
 * Print custom job card CSS directly in wp_head (priority 999) to guarantee immediate application
 */
add_action( 'wp_head', 'jobscout_wpjm_custom_head_styles', 999 );
function jobscout_wpjm_custom_head_styles() {
	echo "<style type=\"text/css\" id=\"jobscout-custom-jobcard-styles\">\n";
	echo jobscout_wpjm_custom_styles();
	echo "\n</style>\n";
}

/**
 * Modern styling for wp-admin/edit.php?post_type=job_listing:
 * 1. Shows FULL company logo (object-fit: contain, no 50% circle clipping)
 * 2. Positions logo cleanly beside job title & company
 * 3. Modern, professional colors for table rows, badges, and status
 */
add_action( 'admin_head', 'jobscout_admin_job_listing_custom_styles' );
function jobscout_admin_job_listing_custom_styles() {
	global $pagenow, $post_type;

	$is_job_listing_screen = ( 'edit.php' === $pagenow && 'job_listing' === $post_type )
		|| ( isset( $_GET['post_type'] ) && 'job_listing' === $_GET['post_type'] );

	if ( ! $is_job_listing_screen ) {
		return;
	}
	?>
	<style type="text/css">
	/* ==========================================================================
	   GIAO DIỆN QUẢN TRỊ VIỆC LÀM WP-ADMIN (edit.php?post_type=job_listing)
	   ========================================================================== */

	/* 1. CỘT VỊ TRÍ TUYỂN DỤNG & LOGO CÔNG TY */
	.widefat tbody th.column-job_position,
	.widefat tbody td.column-job_position {
		vertical-align: middle !important;
	}

	.widefat tbody th.column-job_position .job_position,
	.widefat td.column-job_position .job_position {
		display: grid !important;
		grid-template-columns: 50px 1fr !important;
		grid-template-rows: auto auto auto !important;
		grid-template-areas: 
			"logo title"
			"logo company"
			"logo actions" !important;
		column-gap: 12px !important;
		row-gap: 2px !important;
		align-items: center !important;
		padding-right: 0 !important;
		position: static !important;
		min-height: 52px !important;
	}

	/* Logo công ty: Thấy toàn bộ ảnh, KHÔNG bị cắt tròn 50%, viền sạch bo góc 8px */
	.widefat tbody th.column-job_position img,
	.widefat td.column-job_position img,
	.widefat tbody th.column-job_position img.company_logo,
	.widefat td.column-job_position img.company_logo {
		grid-area: logo !important;
		position: static !important;
		width: 48px !important;
		height: 48px !important;
		min-width: 48px !important;
		max-width: 48px !important;
		aspect-ratio: 1 / 1 !important;
		border-radius: 8px !important;               /* Bo góc nhẹ hiện đại, KHÔNG bị cắt tròn 50% */
		border: 1px solid #e2e8f0 !important;
		background: #ffffff !important;
		padding: 3px !important;
		box-sizing: border-box !important;
		box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05) !important;
		object-fit: contain !important;               /* THẤY TOÀN BỘ ẢNH 100%, KHÔNG BỊ CẮT XÉN */
		object-position: center !important;
		display: block !important;
		margin: 0 !important;
	}

	/* Tiêu đề công việc */
	.widefat tbody td.column-job_position .job_position a.job_title {
		grid-area: title !important;
		font-size: 13.5px !important;
		font-weight: 600 !important;
		color: #1e293b !important;
		text-decoration: none !important;
		line-height: 1.35 !important;
		margin: 0 !important;
	}

	.widefat tbody td.column-job_position .job_position a.job_title:hover {
		color: #2563eb !important;
	}

	/* Tên công ty */
	.widefat tbody td.column-job_position .job_position .company {
		grid-area: company !important;
		font-size: 12px !important;
		color: #64748b !important;
		margin: 0 !important;
	}

	.widefat tbody td.column-job_position .job_position .company a {
		color: #475569 !important;
		text-decoration: none !important;
	}

	.widefat tbody td.column-job_position .job_position .company a:hover {
		color: #1e293b !important;
		text-decoration: underline !important;
	}

	/* Các link thao tác hàng (Chỉnh sửa, Xem, Xóa) */
	.widefat td.column-job_position .row-actions {
		grid-area: actions !important;
		margin-top: 3px !important;
	}

	/* 2. MÀU SẮC BẢNG QUẢN TRỊ HIỆN ĐẠI */
	.post-type-job_listing .wp-list-table {
		border: 1px solid #e2e8f0 !important;
		border-radius: 8px !important;
		overflow: hidden !important;
		box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04) !important;
	}

	.post-type-job_listing .wp-list-table thead th,
	.post-type-job_listing .wp-list-table tfoot th {
		background: #f8fafc !important;
		color: #334155 !important;
		font-weight: 600 !important;
		border-bottom: 2px solid #e2e8f0 !important;
		padding: 10px 12px !important;
		vertical-align: middle !important;
	}

	.post-type-job_listing .wp-list-table tbody tr {
		transition: background-color 0.15s ease !important;
	}

	.post-type-job_listing .wp-list-table tbody tr:hover {
		background-color: #f1f5f9 !important;
	}

	/* Badge loại hình công việc */
	.post-type-job_listing .column-job_listing_type .job-type {
		display: inline-block !important;
		padding: 3px 10px !important;
		border-radius: 12px !important;
		font-size: 11px !important;
		font-weight: 600 !important;
		background: #e0f2fe !important;
		color: #0369a1 !important;
		border: 1px solid #bae6fd !important;
	}

	.post-type-job_listing .column-job_listing_type .job-type.toan-thoi-gian {
		background: #dcfce7 !important;
		color: #15803d !important;
		border-color: #bbf7d0 !important;
	}

	.post-type-job_listing .column-job_listing_type .job-type.ban-thoi-gian {
		background: #ffedd5 !important;
		color: #c2410c !important;
		border-color: #fed7aa !important;
	}

	.post-type-job_listing .column-job_listing_type .job-type.tu-do,
	.post-type-job_listing .column-job_listing_type .job-type.freelance {
		background: #f3e8ff !important;
		color: #7e22ce !important;
		border-color: #e9d5ff !important;
	}

	/* Trạng thái tin đăng */
	.post-type-job_listing .column-job_status,
	.post-type-job_listing .column-job_posted,
	.post-type-job_listing .column-job_expires,
	.post-type-job_listing .column-job_location,
	.post-type-job_listing .column-job_listing_category {
		vertical-align: middle !important;
		font-size: 12.5px !important;
		color: #475569 !important;
	}

	.post-type-job_listing .column-featured_job,
	.post-type-job_listing .column-filled {
		vertical-align: middle !important;
		text-align: center !important;
	}
	</style>
	<?php
}

/**
 * Dynamic Ajax filter & sort controller for 4-all job 2.png top bar
 */
add_action( 'wp_footer', 'jobscout_career_top_bar_ajax_scripts', 99 );
function jobscout_career_top_bar_ajax_scripts() {
	?>
	<script type="text/javascript">
	(function($) {
		$(document).ready(function() {
			function getContainer() {
				return $('.career-jobs-wrapper .job_listings, .job-listings-archive-wrapper .job_listings, .top-job-section .job_listings');
			}

			function filterByCategory(cat) {
				cat = cat || '';
				var $wrapper = $('.career-jobs-wrapper, .job-listings-archive-wrapper');
				if (!$wrapper.length) {
					window.location.href = '<?php echo esc_url( home_url( '/jobs/' ) ); ?>' + (cat ? '?search_category=' + encodeURIComponent(cat) : '');
					return;
				}

				$wrapper.css({ 'opacity': '0.35', 'transition': 'opacity 0.2s ease' });

				var ajaxData = {
					per_page: 12
				};
				if (cat) {
					ajaxData['search_categories'] = [cat];
				}

				$.ajax({
					url: '<?php echo esc_url( home_url( '/?jm-ajax=get_listings' ) ); ?>',
					type: 'POST',
					data: ajaxData,
					dataType: 'json',
					success: function(response) {
						$wrapper.css('opacity', '1');
						if (response && response.html) {
							var $target = $wrapper.find('.job_listings').first();
							if (!$target.length) $target = $wrapper;
							$target.html('<ul class="job_listings">' + response.html + '</ul>');

							var $noJobs = $target.find('li.no_job_listings_found, p.no_job_listings_found');
							if ($noJobs.length > 1) {
								$noJobs.not(':first').remove();
							}

							if (!response.found_jobs || response.max_num_pages <= 1) {
								$('.career-load-more-wrap').hide();
							} else {
								$('.career-load-more-wrap').show();
							}
						} else {
							$wrapper.find('.job_listings').first().html('<ul class="job_listings"><li class="no_job_listings_found">Không tìm thấy việc làm nào phù hợp với tìm kiếm của bạn.</li></ul>');
							$('.career-load-more-wrap').hide();
						}

						if (window.history && window.history.pushState) {
							var newUrl = '<?php echo esc_url( home_url( '/jobs/' ) ); ?>' + (cat ? '?search_category=' + encodeURIComponent(cat) : '');
							window.history.pushState({ category: cat }, '', newUrl);
						}
					},
					error: function() {
						$wrapper.css('opacity', '1');
						window.location.href = '<?php echo esc_url( home_url( '/jobs/' ) ); ?>' + (cat ? '?search_category=' + encodeURIComponent(cat) : '');
					}
				});
			}

			// Khi người dùng bấm vào nút kính lúp [ 🔍 ] - CHỈ TÌM KIẾM KHI BẤM NÚT
			$(document).on('click', '.user-category-btn', function(e) {
				e.preventDefault();
				var cat = $(this).closest('.user-category-filter-box').find('.user-category-select').val();
				filterByCategory(cat);
			});

			// Form submit trên homepage hoặc nhấn Enter
			$(document).on('submit', '.user-category-filter-form', function(e) {
				e.preventDefault();
				var cat = $(this).find('.user-category-select').val();
				filterByCategory(cat);
			});

			// Nút XEM THÊM VIỆC LÀM (LOAD MORE)
			var currentLoadedPage = 1;
			$(document).on('click', '#home-load-more-trigger, #career-load-more-trigger, #archive-load-more-trigger', function(e) {
				e.preventDefault();
				var $btn = $(this);
				var cat = $('.user-category-select').first().val() || '';
				currentLoadedPage++;

				$btn.text('ĐANG TẢI...');

				var ajaxData = {
					page: currentLoadedPage,
					per_page: 12
				};
				if (cat) {
					ajaxData['search_categories'] = [cat];
				}

				$.ajax({
					url: '<?php echo esc_url( home_url( '/?jm-ajax=get_listings' ) ); ?>',
					type: 'POST',
					data: ajaxData,
					dataType: 'json',
					success: function(response) {
						$btn.text('XEM THÊM VIỆC LÀM');
						if (response && response.html) {
							$('.career-jobs-wrapper ul.job_listings, .job-listings-archive-wrapper ul.job_listings').append(response.html);
							if (currentLoadedPage >= response.max_num_pages) {
								$('.career-load-more-wrap').hide();
							}
						} else {
							$('.career-load-more-wrap').hide();
						}
					},
					error: function() {
						$btn.text('XEM THÊM VIỆC LÀM');
						$('.career-load-more-wrap').hide();
					}
				});
			});
		});
	})(jQuery);
	</script>
	<?php
}

/**
 * Hỗ trợ Quản trị viên (Administrator) toàn quyền Thêm, Sửa, Xóa trên Frontend Dashboard
 */
add_filter( 'job_manager_user_can_edit_job', 'jobscout_admin_can_edit_all_jobs', 10, 2 );
function jobscout_admin_can_edit_all_jobs( $can_edit, $job_id ) {
	if ( current_user_can( 'administrator' ) || current_user_can( 'manage_options' ) ) {
		return true;
	}
	return $can_edit;
}

add_filter( 'job_manager_get_dashboard_jobs_args', 'jobscout_admin_dashboard_show_all_jobs' );
function jobscout_admin_dashboard_show_all_jobs( $args ) {
	if ( current_user_can( 'administrator' ) || current_user_can( 'manage_options' ) ) {
		unset( $args['author'] );
	}
	return $args;
}

/**
 * Đứng ở trang nào thì chỉ kích hoạt (active) duy nhất mục menu của trang đó
 */
add_filter( 'nav_menu_css_class', 'jobscout_nav_menu_active_classes', 10, 2 );
function jobscout_nav_menu_active_classes( $classes, $item ) {
	$home_url_clean = rtrim( home_url(), '/' );
	$is_home = is_front_page() || is_home();
	$is_post_job = is_page( 'post-a-job' ) || ( isset( $_SERVER['REQUEST_URI'] ) && strpos( $_SERVER['REQUEST_URI'], '/post-a-job/' ) !== false );
	$is_dashboard = is_page( 'job-dashboard' ) || ( isset( $_SERVER['REQUEST_URI'] ) && strpos( $_SERVER['REQUEST_URI'], '/job-dashboard/' ) !== false );
	$is_jobs_section = is_post_type_archive( 'job_listing' )
		|| is_page( 'jobs' )
		|| is_tax( 'job_listing_category' )
		|| is_tax( 'job_listing_type' );

	if ( $is_home ) {
		// Ở trang chủ: Chỉ Trang chủ nhận active
		if ( rtrim( $item->url, '/' ) === $home_url_clean ) {
			$classes[] = 'current-menu-item';
			$classes[] = 'current_page_item';
		} else {
			$classes = array_diff( $classes, array( 'current-menu-item', 'current_page_item', 'current-menu-ancestor', 'current_page_parent', 'current_page_ancestor' ) );
		}
	} elseif ( $is_post_job ) {
		// Ở trang Đăng tin: Mục Đăng tin nhận active, Trang chủ bị xóa
		if ( strpos( $item->url, '/post-a-job/' ) !== false ) {
			$classes[] = 'current-menu-item';
			$classes[] = 'current_page_item';
		} elseif ( strpos( $item->url, '/jobs/' ) !== false ) {
			$classes[] = 'current-menu-ancestor';
		}
		if ( rtrim( $item->url, '/' ) === $home_url_clean ) {
			$classes = array_diff( $classes, array( 'current-menu-item', 'current_page_item', 'current-menu-ancestor', 'current_page_parent', 'current_page_ancestor' ) );
		}
	} elseif ( $is_dashboard ) {
		// Ở trang Quản lý tin: Mục Quản lý tin nhận active, Trang chủ bị xóa
		if ( strpos( $item->url, '/job-dashboard/' ) !== false ) {
			$classes[] = 'current-menu-item';
			$classes[] = 'current_page_item';
		} elseif ( strpos( $item->url, '/jobs/' ) !== false ) {
			$classes[] = 'current-menu-ancestor';
		}
		if ( rtrim( $item->url, '/' ) === $home_url_clean ) {
			$classes = array_diff( $classes, array( 'current-menu-item', 'current_page_item', 'current-menu-ancestor', 'current_page_parent', 'current_page_ancestor' ) );
		}
	} elseif ( $is_jobs_section ) {
		// Ở trang danh sách Việc làm: Chỉ mục Việc làm nhận active
		if ( strpos( $item->url, '/jobs/' ) !== false && strpos( $item->url, '/post-a-job/' ) === false && strpos( $item->url, '/job-dashboard/' ) === false ) {
			$classes[] = 'current-menu-item';
			$classes[] = 'current_page_item';
		} else {
			$classes = array_diff( $classes, array( 'current-menu-item', 'current_page_item' ) );
		}
		if ( rtrim( $item->url, '/' ) === $home_url_clean ) {
			$classes = array_diff( $classes, array( 'current-menu-item', 'current_page_item', 'current-menu-ancestor', 'current_page_parent', 'current_page_ancestor' ) );
		}
	} else {
		// Ở các trang khác (Liên hệ, Blog...): Việc làm không nhận active
		if ( strpos( $item->url, '/jobs/' ) !== false || strpos( $item->url, '/post-a-job/' ) !== false || strpos( $item->url, '/job-dashboard/' ) !== false ) {
			$classes = array_diff( $classes, array( 'current-menu-item', 'current_page_item', 'current-menu-ancestor', 'current_page_parent', 'current_page_ancestor' ) );
		}
	}

	return array_unique( $classes );
}

/**
 * Bản dịch tiếng Việt chuẩn cho toàn bộ form đăng tin tuyển dụng
 */
add_filter( 'submit_job_form_fields', 'jobscout_vietnamese_job_submission_fields' );
function jobscout_vietnamese_job_submission_fields( $fields ) {
	if ( isset( $fields['job']['job_title'] ) ) {
		$fields['job']['job_title']['label'] = 'Chức danh việc làm';
		$fields['job']['job_title']['placeholder'] = 'Ví dụ: Lập trình viên Fullstack, Kế toán trưởng...';
	}
	if ( isset( $fields['job']['job_location'] ) ) {
		$fields['job']['job_location']['label'] = 'Địa điểm làm việc';
		$fields['job']['job_location']['placeholder'] = 'Ví dụ: TP. Hồ Chí Minh, Hà Nội, Đà Nẵng...';
	}
	if ( isset( $fields['job']['job_type'] ) ) {
		$fields['job']['job_type']['label'] = 'Loại hình công việc';
		$fields['job']['job_type']['description'] = 'Chọn hình thức làm việc phù hợp';
	}
	if ( isset( $fields['job']['job_category'] ) ) {
		$fields['job']['job_category']['label'] = 'Danh mục ngành nghề';
		$fields['job']['job_category']['description'] = 'Chọn danh mục ngành nghề chuyên môn';
	}
	if ( isset( $fields['job']['job_description'] ) ) {
		$fields['job']['job_description']['label'] = 'Mô tả chi tiết công việc';
		$fields['job']['job_description']['placeholder'] = 'Mô tả chi tiết trách nhiệm, yêu cầu kỹ năng và quyền lợi đãi ngộ...';
	}
	if ( isset( $fields['job']['application'] ) ) {
		$fields['job']['application']['label'] = 'Email hoặc Đường dẫn ứng tuyển';
		$fields['job']['application']['placeholder'] = 'Ví dụ: hr@congty.com hoặc https://congty.com/ung-tuyen';
		$fields['job']['application']['description'] = 'Địa chỉ email hoặc liên kết website để ứng viên nộp hồ sơ';
	}
	if ( isset( $fields['job']['job_salary'] ) ) {
		$fields['job']['job_salary']['label'] = 'Mức lương';
		$fields['job']['job_salary']['placeholder'] = 'Ví dụ: 15.000.000 VNĐ - 25.000.000 VNĐ hoặc Thỏa thuận';
	}
	if ( isset( $fields['job']['remote_position'] ) ) {
		$fields['job']['remote_position']['label'] = 'Việc làm từ xa (Remote)';
	}
	if ( isset( $fields['job']['job_remote_position'] ) ) {
		$fields['job']['job_remote_position']['label'] = 'Việc làm từ xa (Remote)';
	}
	if ( isset( $fields['job']['job_important_info'] ) ) {
		$fields['job']['job_important_info']['label'] = 'Thông tin quan trọng';
		$fields['job']['job_important_info']['placeholder'] = 'Nhập các thông tin bổ sung quan trọng nếu có...';
	}

	if ( isset( $fields['company']['company_name'] ) ) {
		$fields['company']['company_name']['label'] = 'Tên công ty / Doanh nghiệp';
		$fields['company']['company_name']['placeholder'] = 'Nhập tên công ty hoặc doanh nghiệp...';
	}
	if ( isset( $fields['company']['company_website'] ) ) {
		$fields['company']['company_website']['label'] = 'Website công ty';
		$fields['company']['company_website']['placeholder'] = 'https://...';
	}
	if ( isset( $fields['company']['company_tagline'] ) ) {
		$fields['company']['company_tagline']['label'] = 'Khẩu hiệu / Giới thiệu ngắn';
		$fields['company']['company_tagline']['placeholder'] = 'Ví dụ: Nơi khởi nguồn đam mê sáng tạo...';
	}
	if ( isset( $fields['company']['company_video'] ) ) {
		$fields['company']['company_video']['label'] = 'Video giới thiệu';
		$fields['company']['company_video']['placeholder'] = 'Đường dẫn video YouTube hoặc Vimeo...';
	}
	if ( isset( $fields['company']['company_twitter'] ) ) {
		$fields['company']['company_twitter']['label'] = 'Mạng xã hội';
		$fields['company']['company_twitter']['placeholder'] = 'https://facebook.com/congty...';
	}
	if ( isset( $fields['company']['company_logo'] ) ) {
		$fields['company']['company_logo']['label'] = 'Logo công ty';
		$fields['company']['company_logo']['description'] = 'Hỗ trợ tải lên mọi định dạng ảnh (JPG, PNG, WEBP, GIF, SVG...). Tự động tối ưu theo tỷ lệ thực tế.';
		$fields['company']['company_logo']['max_size'] = 100 * 1024 * 1024;
	}

	return $fields;
}

add_filter( 'submit_job_step_preview_submit_text', function() {
	return 'Xác nhận & Đăng tin tuyển dụng';
} );

/**
 * Cho phép tải lên logo công ty với mọi định dạng và kích thước không giới hạn 2MB
 */
add_filter( 'job_manager_company_logo_max_size', function() {
	return 100 * 1024 * 1024; // 100MB
} );

add_filter( 'upload_size_limit', function( $size ) {
	return max( $size, 100 * 1024 * 1024 );
} );

add_filter( 'job_manager_company_logo_allowed_mime_types', function( $mimes ) {
	return array(
		'jpg|jpeg|jpe' => 'image/jpeg',
		'jpg'          => 'image/jpeg',
		'jpeg'         => 'image/jpeg',
		'jpe'          => 'image/jpeg',
		'png'          => 'image/png',
		'webp'         => 'image/webp',
		'gif'          => 'image/gif',
		'svg|svgz'     => 'image/svg+xml',
		'svg'          => 'image/svg+xml',
		'svgz'         => 'image/svg+xml',
		'avif'         => 'image/avif',
		'bmp'          => 'image/bmp',
		'ico'          => 'image/x-icon',
	);
} );

add_filter( 'job_manager_upload_file_pre_upload', function( $file, $args, $allowed_mime_types ) {
	if ( ! empty( $file['type'] ) ) {
		if ( 'image/pjpeg' === $file['type'] ) {
			$file['type'] = 'image/jpeg';
		} elseif ( 'image/x-png' === $file['type'] ) {
			$file['type'] = 'image/png';
		}
	}
	if ( empty( $file['type'] ) || 'application/octet-stream' === $file['type'] ) {
		$ext = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );
		$map = array(
			'jpg'  => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			'png'  => 'image/png',
			'webp' => 'image/webp',
			'gif'  => 'image/gif',
			'svg'  => 'image/svg+xml',
			'avif' => 'image/avif',
			'bmp'  => 'image/bmp',
			'ico'  => 'image/x-icon',
		);
		if ( isset( $map[ $ext ] ) ) {
			$file['type'] = $map[ $ext ];
		}
	}
	return $file;
}, 10, 3 );

add_filter( 'upload_mimes', function( $mimes ) {
	$mimes['svg']  = 'image/svg+xml';
	$mimes['svgz'] = 'image/svg+xml';
	$mimes['webp'] = 'image/webp';
	$mimes['avif'] = 'image/avif';
	$mimes['bmp']  = 'image/bmp';
	$mimes['ico']  = 'image/x-icon';
	return $mimes;
} );

add_filter( 'wp_check_filetype_and_ext', function( $data, $file, $filename, $mimes ) {
	$ext = strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) );
	if ( in_array( $ext, array( 'svg', 'svgz' ), true ) ) {
		$data['ext']  = $ext;
		$data['type'] = 'image/svg+xml';
	} elseif ( in_array( $ext, array( 'webp' ), true ) ) {
		$data['ext']  = 'webp';
		$data['type'] = 'image/webp';
	} elseif ( in_array( $ext, array( 'avif' ), true ) ) {
		$data['ext']  = 'avif';
		$data['type'] = 'image/avif';
	}
	return $data;
}, 10, 4 );

/**
 * Định dạng ngày tháng chuẩn Việt Nam d/m/Y cho trang quản lý Dashboard
 */
add_filter( 'job_manager_get_dashboard_date_format', function() {
	return 'd/m/Y';
} );

/**
 * Bản dịch trạng thái tin tuyển dụng (Job status) chuẩn tiếng Việt
 */
add_filter( 'the_job_status', function( $status, $post ) {
	$st = strtolower( trim( $status ) );
	if ( 'active' === $st || 'publish' === $st ) {
		return 'Đang hiển thị';
	} elseif ( 'filled' === $st ) {
		return 'Đã tuyển đủ';
	} elseif ( 'expired' === $st ) {
		return 'Đã hết hạn';
	} elseif ( 'pending' === $st || 'pending approval' === $st || 'pending_payment' === $st ) {
		return 'Chờ duyệt';
	} elseif ( 'draft' === $st ) {
		return 'Bản nháp';
	}
	return $status;
}, 20, 2 );

add_filter( 'job_listing_post_statuses', function( $statuses ) {
	return array(
		'draft'           => 'Bản nháp',
		'expired'         => 'Đã hết hạn',
		'preview'         => 'Xem trước',
		'pending'         => 'Chờ duyệt',
		'pending_payment' => 'Chờ thanh toán',
		'publish'         => 'Đang hiển thị',
		'future'          => 'Đã lên lịch',
	);
} );

/**
 * Nút lưu thay đổi và thông báo khi chỉnh sửa tin tuyển dụng
 */
add_filter( 'update_job_form_submit_button_text', function() {
	return 'Lưu thay đổi';
} );

add_filter( 'job_manager_update_job_listings_message', function( $message, $job_id ) {
	return 'Các thay đổi của tin tuyển dụng đã được lưu thành công. <a href="' . esc_url( get_permalink( $job_id ) ) . '" style="color: #2563eb; font-weight: 600; text-decoration: underline;">Xem tin tuyển dụng &rarr;</a>';
}, 20, 2 );

/**
 * Tự động đảm bảo thư mục upload của WP Job Manager luôn tồn tại và có quyền ghi 777 (Sửa triệt để lỗi Unable to create directory)
 */
add_filter( 'job_manager_upload_dir', function( $dir, $field_key ) {
	$uploads_basedir = defined( 'WP_CONTENT_DIR' ) ? WP_CONTENT_DIR . '/uploads' : ABSPATH . 'wp-content/uploads';
	$full_path = $uploads_basedir . '/' . $dir;
	if ( ! file_exists( $full_path ) ) {
		wp_mkdir_p( $full_path );
		@chmod( $full_path, 0777 );
	}
	$dated_path = $full_path . '/' . date( 'Y/m' );
	if ( ! file_exists( $dated_path ) ) {
		wp_mkdir_p( $dated_path );
		@chmod( $dated_path, 0777 );
	}
	return $dir;
}, 10, 2 );

/**
 * 100% Vietnamese Localization Engine for WP Job Manager & Theme.
 * Translates all system messages, validation errors, notices, and UI labels.
 */
add_filter( 'gettext', 'jobscout_wpjm_vietnamese_gettext', 20, 3 );
function jobscout_wpjm_vietnamese_gettext( $translation, $text, $domain ) {
	if ( 'wp-job-manager' !== $domain && 'jobscout' !== $domain && 'default' !== $domain ) {
		return $translation;
	}

	static $dict = null;
	if ( null === $dict ) {
		$dict = array(
			// Validation & Errors
			'%s is a required field' => 'Vui lòng nhập thông tin cho mục bắt buộc: %s.',
			'%s is invalid' => '%s không hợp lệ. Vui lòng kiểm tra lại.',
			'Please enter a valid application email address' => 'Vui lòng nhập địa chỉ email nhận hồ sơ ứng tuyển hợp lệ (ví dụ: tuyendung@congty.com).',
			'Please enter a valid application URL' => 'Vui lòng nhập đường dẫn URL nhận hồ sơ ứng tuyển hợp lệ (ví dụ: https://congty.com/careers).',
			'Please enter a valid application email address or URL' => 'Vui lòng nhập địa chỉ email hoặc đường dẫn URL nhận hồ sơ ứng tuyển hợp lệ.',
			'The company logo exceeds the maximum allowed file size of %s.' => 'Logo công ty vượt quá dung lượng tối đa cho phép là %s. Vui lòng chọn tệp nhỏ hơn.',
			'"%1$s" (filetype %2$s) needs to be one of the following file types: %3$s' => '"%1$s" (định dạng %2$s) cần thuộc một trong các định dạng cho phép: %3$s.',
			'Uploaded files need to be one of the following file types: %s' => 'Tệp tải lên cần thuộc một trong các định dạng cho phép: %s.',
			'You are only allowed to upload a maximum of %d files.' => 'Bạn chỉ được phép tải lên tối đa %d tệp tin.',
			'Invalid image path.' => 'Đường dẫn hình ảnh không hợp lệ.',
			'Invalid attachment provided.' => 'Tệp đính kèm không hợp lệ. Vui lòng thử tải lên lại.',
			'The saved file for "%s" is no longer available to use. Please upload it again, or remove it, and resubmit.' => 'Tệp đã lưu cho mục "%s" không còn khả dụng. Vui lòng tải lên lại hoặc xóa và gửi lại.',
			'Invalid email address.' => 'Địa chỉ email không hợp lệ.',
			'Your email address isn&#8217;t correct.' => 'Địa chỉ email của bạn không đúng.',
			'This email is already registered, please choose another one.' => 'Địa chỉ email này đã được sử dụng. Vui lòng chọn email khác hoặc đăng nhập.',
			'Please enter a username.' => 'Vui lòng nhập tên tài khoản đăng nhập.',
			'Please enter your email address.' => 'Vui lòng nhập địa chỉ email của bạn.',
			'Please enter a password.' => 'Vui lòng nhập mật khẩu tài khoản.',
			'Passwords must match.' => 'Mật khẩu xác nhận không khớp. Vui lòng nhập lại.',
			'Passwords must be at least 8 characters long.' => 'Mật khẩu phải có độ dài tối thiểu 8 ký tự.',
			'Password is not valid.' => 'Mật khẩu không hợp lệ.',
			'Invalid Password: %s' => 'Mật khẩu không hợp lệ: %s',
			'You must be signed in to post a new listing.' => 'Bạn cần đăng nhập tài khoản để có thể đăng tin tuyển dụng mới.',
			'You have reached the listing limit for your account and cannot publish this listing.' => 'Tài khoản của bạn đã đạt giới hạn số lượng tin đăng và không thể xuất bản thêm tin này.',
			'Invalid file type. Accepted types:' => 'Định dạng tệp không hợp lệ. Các định dạng được chấp nhận:',
			'Exceeded upload limit' => 'Đã vượt quá số lượng tệp được phép tải lên.',
			'This listing has expired.' => 'Tin tuyển dụng này đã hết hạn nộp hồ sơ.',
			'This field is required.' => 'Mục này là bắt buộc, vui lòng không để trống.',
			'Terms and Conditions is a required field' => 'Bạn cần đồng ý với Điều khoản & Quy định để tiếp tục.',
			'I accept the %s.' => 'Tôi đồng ý với %s.',
			'Terms and Conditions' => 'Điều khoản & Quy định',
			'Maximum file size: %s.' => 'Dung lượng tệp tối đa: %s.',
			'Square format recommended (1:1 ratio).' => 'Khuyến khích hình vuông tỷ lệ 1:1 hoặc hình ảnh rõ nét.',
			'Job listing submitted successfully. Your listing is now live.' => 'Đăng tin tuyển dụng thành công! Tin của bạn đã được xuất bản và đang hiển thị công khai.',
			'Job listing submitted successfully. Your listing will be visible once approved.' => 'Đăng tin tuyển dụng thành công! Tin của bạn đã được tiếp nhận và sẽ hiển thị công khai ngay sau khi được ban quản trị phê duyệt.',
			'View Listing' => 'Xem tin tuyển dụng',
			'Submit Listing' => 'Xác nhận & Đăng tin tuyển dụng',
			'Edit listing' => 'Quay lại chỉnh sửa',
			'Preview' => 'Xem trước tin',
			'remove' => 'Xóa file',
			'Draft was saved. Job listing drafts can be resumed from the <a href="%s">job dashboard</a>.' => 'Bản nháp đã được lưu thành công. Bạn có thể tiếp tục chỉnh sửa bất kỳ lúc nào tại <a href="%s">Trang quản lý tin</a>.',

			// Field Names & Placeholders
			'Job Title' => 'Chức danh việc làm',
			'Job category' => 'Danh mục ngành nghề',
			'Job type' => 'Loại hình công việc',
			'Location' => 'Địa điểm làm việc',
			'Logo' => 'Logo công ty',
			'Application email/URL' => 'Email hoặc Đường dẫn ứng tuyển',
			'Description' => 'Mô tả chi tiết công việc',
			'Company name' => 'Tên công ty / Doanh nghiệp',
			'Website' => 'Website công ty',
			'Tagline' => 'Khẩu hiệu / Giới thiệu ngắn',
			'Video' => 'Video giới thiệu',
			'Choose a category&hellip;' => 'Chọn ngành nghề&hellip;',
			'Choose job type&hellip;' => 'Chọn loại hình công việc&hellip;',
			'Full Time' => 'Toàn thời gian',
			'Part Time' => 'Bán thời gian',
			'Contractor' => 'Hợp đồng',
			'Temporary' => 'Tạm thời',
			'Intern' => 'Thực tập sinh',
			'Volunteer' => 'Tình nguyện',
			'Per Diem' => 'Theo ngày / Thời vụ',
			'Any Category' => 'Tất cả ngành nghề',
			'Leave this blank if the location is not important' => 'Để trống nếu địa điểm làm việc không bắt buộc',
			'Select if this is a remote position.' => 'Đánh dấu nếu đây là công việc làm việc từ xa (Remote).',
			'Enter an email address or website URL' => 'Nhập email (ví dụ: hr@congty.com) hoặc đường dẫn website',
			'Enter the name of the company' => 'Nhập tên công ty hoặc doanh nghiệp',
			'Briefly describe your company' => 'Mô tả ngắn gọn về công ty của bạn',
			'Save changes' => 'Lưu thay đổi',
			'Save Draft' => 'Lưu bản nháp',
			'Load previous listings' => 'Tải tin đã đăng trước đó',
		);
	}

	if ( isset( $dict[ $text ] ) ) {
		return $dict[ $text ];
	}

	// Dynamic pattern translations
	if ( preg_match( '#^(.+)\s+is a required field\.?$#i', $text, $m ) ) {
		return sprintf( 'Vui lòng nhập thông tin cho mục bắt buộc: %s.', $m[1] );
	}
	if ( preg_match( '#^(.+)\s+is invalid\.?$#i', $text, $m ) ) {
		return sprintf( '%s không hợp lệ. Vui lòng kiểm tra lại.', $m[1] );
	}

	return $translation;
}

/**
/**
 * Đặt các trường bắt buộc và nhãn hiển thị tiếng Việt chuẩn hóa.
 * Bắt buộc nhập Logo / Hình ảnh công ty khi đăng tin theo yêu cầu.
 */
add_filter( 'submit_job_form_fields', 'jobscout_customize_submit_job_fields', 20 );
function jobscout_customize_submit_job_fields( $fields ) {
	// 1. Bắt buộc nhập Logo / Hình ảnh công ty
	if ( isset( $fields['company']['company_logo'] ) ) {
		$fields['company']['company_logo']['required']    = true;
		$fields['company']['company_logo']['label']       = 'Logo / Hình ảnh công ty';
		$fields['company']['company_logo']['description'] = 'Bắt buộc tải lên Logo hoặc Hình ảnh đại diện công ty. Hỗ trợ JPG, PNG, WEBP, GIF, SVG.';
	}
	// 2. Chức danh việc làm
	if ( isset( $fields['job']['job_title'] ) ) {
		$fields['job']['job_title']['placeholder'] = 'Ví dụ: Lập trình viên Fullstack, Kế toán trưởng, Quản lý Marketing...';
	}
	// 3. Địa điểm làm việc
	if ( isset( $fields['job']['job_location'] ) ) {
		$fields['job']['job_location']['required']    = true;
		$fields['job']['job_location']['placeholder'] = 'Ví dụ: Hà Nội, TP. Hồ Chí Minh, Đà Nẵng, Toàn quốc...';
	}
	// 4. Danh mục ngành nghề
	if ( isset( $fields['job']['job_category'] ) ) {
		$fields['job']['job_category']['required'] = true;
	}
	// 5. Tên công ty
	if ( isset( $fields['company']['company_name'] ) ) {
		$fields['company']['company_name']['placeholder'] = 'Ví dụ: VinFast Automotive, Viettel Group, FPT Software...';
	}

	return $fields;
}

/**
 * Strict Pre-Submission Validation in 100% Vietnamese.
 * Validates all required inputs, formats, and provides immediate clear Vietnamese messages.
 */
add_filter( 'submit_job_form_validate_fields', 'jobscout_validate_job_submission', 10, 3 );
function jobscout_validate_job_submission( $is_valid, $fields, $values ) {
	// 1. Chức danh việc làm
	if ( empty( trim( $values['job']['job_title'] ?? '' ) ) ) {
		return new WP_Error( 'validation-error', 'Vui lòng nhập Chức danh việc làm (ví dụ: Lập trình viên Fullstack, Kế toán trưởng...).' );
	}

	// 2. Loại hình công việc
	if ( empty( $values['job']['job_type'] ?? '' ) ) {
		return new WP_Error( 'validation-error', 'Vui lòng chọn Loại hình công việc phù hợp (Toàn thời gian, Bán thời gian, Thực tập sinh...).' );
	}

	// 3. Ngành nghề / Danh mục việc làm (nếu hệ thống bật danh mục)
	if ( get_option( 'job_manager_enable_categories' ) && empty( $values['job']['job_category'] ?? '' ) ) {
		return new WP_Error( 'validation-error', 'Vui lòng chọn Ngành nghề / Danh mục công việc phù hợp.' );
	}

	// 4. Địa điểm làm việc
	if ( empty( trim( $values['job']['job_location'] ?? '' ) ) ) {
		return new WP_Error( 'validation-error', 'Vui lòng nhập Địa điểm làm việc (ví dụ: Hà Nội, TP. Hồ Chí Minh, Toàn quốc...).' );
	}

	// 5. Mô tả công việc
	if ( empty( trim( strip_tags( $values['job']['job_description'] ?? '' ) ) ) ) {
		return new WP_Error( 'validation-error', 'Vui lòng nhập đầy đủ nội dung Mô tả chi tiết công việc và yêu cầu tuyển dụng.' );
	}

	// 6. Phương thức nhận hồ sơ ứng tuyển
	$app = trim( $values['job']['application'] ?? '' );
	if ( empty( $app ) ) {
		return new WP_Error( 'validation-error', 'Vui lòng nhập Email hoặc Đường dẫn URL nhận hồ sơ ứng tuyển.' );
	}
	if ( strpos( $app, '@' ) !== false ) {
		if ( ! is_email( $app ) ) {
			return new WP_Error( 'validation-error', 'Địa chỉ email nhận hồ sơ ứng tuyển "' . esc_html( $app ) . '" không đúng định dạng. Vui lòng kiểm tra lại (ví dụ: tuyendung@congty.com).' );
		}
	} else {
		if ( ! filter_var( $app, FILTER_VALIDATE_URL ) ) {
			return new WP_Error( 'validation-error', 'Đường dẫn website nhận hồ sơ ứng tuyển không hợp lệ. Vui lòng nhập đầy đủ tiền tố http:// hoặc https:// (ví dụ: https://congty.com/careers).' );
		}
	}

	// 7. Tên công ty / Doanh nghiệp
	if ( empty( trim( $values['company']['company_name'] ?? '' ) ) ) {
		return new WP_Error( 'validation-error', 'Vui lòng nhập Tên công ty / Doanh nghiệp tuyển dụng.' );
	}

	// 8. BẮT BUỘC NHẬP HÌNH ẢNH / LOGO CÔNG TY (Theo yêu cầu)
	$has_logo = ! empty( $values['company']['company_logo'] );
	if ( ! $has_logo && ! empty( $_FILES['company_logo']['name'] ) ) {
		$has_logo = true;
	}
	if ( ! $has_logo && ! empty( $_POST['current_company_logo'] ) ) {
		$has_logo = true;
	}
	if ( ! $has_logo && ! empty( $_POST['job_id'] ) ) {
		$existing_id = absint( $_POST['job_id'] );
		if ( has_post_thumbnail( $existing_id ) || ! empty( get_post_meta( $existing_id, '_company_logo', true ) ) ) {
			$has_logo = true;
		}
	}
	if ( ! $has_logo ) {
		return new WP_Error( 'validation-error', 'Vui lòng tải lên Logo hoặc Hình ảnh đại diện công ty (bắt buộc khi đăng tin tuyển dụng).' );
	}

	// 9. Hạn nộp hồ sơ (nếu có nhập)
	if ( ! empty( $values['job']['job_expires'] ) ) {
		$exp_ts = strtotime( $values['job']['job_expires'] );
		if ( false === $exp_ts || $exp_ts < strtotime( 'today' ) ) {
			return new WP_Error( 'validation-error', 'Hạn nộp hồ sơ không hợp lệ hoặc đã qua. Vui lòng chọn một ngày trong tương lai.' );
		}
	}

	return $is_valid;
}

/**
 * Đảm bảo quá trình bấm "Xác nhận & Đăng tin tuyển dụng" luôn nhận diện trường continue,
 * không bao giờ bị rớt dữ liệu dù ở bất kỳ trình duyệt nào.
 */
add_action( 'init', 'jobscout_fix_preview_submission_payload', 1 );
function jobscout_fix_preview_submission_payload() {
	if ( isset( $_POST['job_manager_form'] ) && 'submit-job' === $_POST['job_manager_form'] && ! empty( $_POST['job_id'] ) ) {
		// Nếu người dùng không bấm nút quay lại chỉnh sửa (edit_job)
		if ( empty( $_POST['edit_job'] ) ) {
			// Nếu đang ở bước xem trước (step 1)
			if ( isset( $_POST['step'] ) && ( '1' === (string) $_POST['step'] || 1 === (int) $_POST['step'] ) ) {
				if ( ! isset( $_POST['continue'] ) || '' === trim( (string) $_POST['continue'] ) ) {
					$_POST['continue'] = 'Xác nhận & Đăng tin tuyển dụng';
				}
			}
		}
	}
}

/**
 * XÓA VÀ LÀM MỚI CACHE TOÀN DIỆN KHI THÊM, SỬA, XÓA HOẶC ĐỔI TRẠNG THÁI TIN TUYỂN DỤNG.
 * Đảm bảo dữ liệu sau thao tác luôn được cập nhật tức thì, không còn dữ liệu cũ tồn đọng.
 */
function jobscout_clear_all_job_caches( $job_id ) {
	if ( empty( $job_id ) ) {
		return;
	}
	clean_post_cache( $job_id );
	clean_object_term_cache( $job_id, 'job_listing' );
	clean_object_term_cache( $job_id, 'job_listing_category' );
	clean_object_term_cache( $job_id, 'job_listing_type' );
	wp_cache_delete( $job_id, 'posts' );
	wp_cache_delete( $job_id, 'post_meta' );

	if ( class_exists( 'WP_Job_Manager_Cache_Helper' ) ) {
		WP_Job_Manager_Cache_Helper::get_transient_version( 'get_job_listings', true );
		WP_Job_Manager_Cache_Helper::get_transient_version( 'jm_get_job_listing_category', true );
		WP_Job_Manager_Cache_Helper::get_transient_version( 'jm_get_job_listing_type', true );
	}
}

add_action( 'save_post_job_listing', 'jobscout_clear_all_job_caches', 20 );
add_action( 'before_delete_post', 'jobscout_clear_all_job_caches', 20 );
add_action( 'trash_job_listing', 'jobscout_clear_all_job_caches', 20 );
add_action( 'untrash_job_listing', 'jobscout_clear_all_job_caches', 20 );
add_action( 'job_manager_my_job_do_action', function( $action, $job_id ) {
	jobscout_clear_all_job_caches( $job_id );
}, 20, 2 );

