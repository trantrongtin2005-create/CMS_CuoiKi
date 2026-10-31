<?php
/**
 * Module xử lý khối Danh sách Việc làm Tương tác (Interactive Job Listings)
 * Hỗ trợ tìm kiếm, lọc theo địa điểm, ngành nghề, hình thức làm việc, mức lương thời gian thực
 * Tương thích hoàn toàn với Trình soạn thảo Gutenberg Block Editor dưới dạng shortcode [jobs]
 *
 * @package JobScout
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Lấy toàn bộ dữ liệu công việc đã xuất bản để xử lý tìm kiếm thời gian thực phía máy khách
 *
 * @return array
 */
function jobscout_get_jobs_dataset() {
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

			// Tách giá trị số từ mức lương để sắp xếp và lọc chính xác
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

			// Xác định ảnh logo của công ty (nếu không có thì dùng logo mặc định)
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

			// Hình thức làm việc
			$type_label = 'Toàn thời gian';
			$type_slug  = 'toan-thoi-gian';
			if ( ! empty( $types ) ) {
				$first_t    = reset( $types );
				$type_label = $first_t->name;
				$type_slug  = $first_t->slug;
			}

			// Ngành nghề / Danh mục
			$cat_label = 'Công nghệ thông tin';
			$cat_slug  = 'cong-nghe-thong-tin';
			if ( ! empty( $cats ) && ! is_wp_error( $cats ) ) {
				$first_c   = reset( $cats );
				$cat_label = $first_c->name;
				$cat_slug  = $first_c->slug;
			}

			// Địa điểm làm việc
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

			// Tách 3 ý mô tả công việc (gạch đầu dòng)
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
				'Chịu trách nhiệm quản lý, điều hành và tối ưu hóa hiệu quả hoạt động bộ phận.',
				'Chế độ đãi ngộ hấp dẫn, thưởng hiệu suất và lộ trình thăng tiến rõ ràng.',
				'Phụ cấp ngoại ngữ, đào tạo nâng cao chuyên môn và bảo hiểm toàn diện.',
			);
			while ( count( $bullets ) < 3 ) {
				$bullets[] = array_shift( $def_bullets );
			}

			// Định dạng ngày theo tiếng Việt và đảm bảo năm luôn là 2026
			$raw_date_time = get_post_time( 'U', true, $jid );
			$job_date_vn   = date_i18n( 'd/m/Y', $raw_date_time );
			if ( false === strpos( $job_date_vn, '2026' ) ) {
				$job_date_vn = date_i18n( 'd/m', $raw_date_time ) . '/2026';
			}

			$jobs_dataset[] = array(
				'id'             => $jid,
				'title'          => html_entity_decode( $title, ENT_QUOTES, 'UTF-8' ),
				'permalink'      => get_permalink( $jid ),
				'company'        => html_entity_decode( $company ?: $title, ENT_QUOTES, 'UTF-8' ),
				'logo'           => $logo,
				'date_formatted' => $job_date_vn,
				'date_timestamp' => $raw_date_time,
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

	return $jobs_dataset;
}

/**
 * Tạo mã HTML và JavaScript cho khối Tìm kiếm & Danh sách việc làm tương tác
 *
 * @param array $args
 * @return string
 */
function jobscout_render_interactive_jobs_markup( $args = array() ) {
	$defaults = array(
		'section_title'  => '',
		'load_more_text' => 'XEM THÊM VIỆC LÀM',
		'jobs_per_page'  => 6,
		'wrapper_class'  => '',
		'show_heading'   => true,
	);
	$args = wp_parse_args( $args, $defaults );

	$jobs_dataset   = jobscout_get_jobs_dataset();
	$all_categories = get_terms( array( 'taxonomy' => 'job_listing_category', 'hide_empty' => false ) );

	$init_keyword  = isset( $_GET['search_keywords'] ) ? sanitize_text_field( wp_unslash( $_GET['search_keywords'] ) ) : '';
	$init_location = isset( $_GET['search_location'] ) ? sanitize_text_field( wp_unslash( $_GET['search_location'] ) ) : '';
	$init_category = isset( $_GET['search_category'] ) ? sanitize_text_field( wp_unslash( $_GET['search_category'] ) ) : '';

	$section_title = $args['section_title'];
	if ( empty( $section_title ) ) {
		$section_title = get_theme_mod( 'jobs_page_section_title', 'TẤT CẢ VIỆC LÀM' );
	}
	$load_more_text = $args['load_more_text'];
	if ( empty( $load_more_text ) ) {
		$load_more_text = get_theme_mod( 'jobs_page_load_more_text', 'XEM THÊM VIỆC LÀM' );
	}
	$jobs_per_page = absint( $args['jobs_per_page'] );
	if ( $jobs_per_page < 1 ) {
		$jobs_per_page = 6;
	}

	$current_post_content = get_post_field( 'post_content', get_the_ID() );
	$has_gutenberg_heading = ( false !== strpos( $current_post_content, 'jobs-section-heading' ) || false !== strpos( $current_post_content, 'TẤT CẢ VIỆC LÀM' ) );

	$show_heading = $args['show_heading'];
	if ( $has_gutenberg_heading ) {
		$show_heading = false;
	}

	ob_start();
	?>
	<div class="aj-page-container aj-interactive-jobs-module <?php echo esc_attr( $args['wrapper_class'] ); ?>">

		<!-- THẺ TÌM KIẾM & BỘ LỌC NHANH -->
		<div class="aj-search-filter-card">
			<div class="aj-search-row">
				<!-- 1. Ô nhập từ khóa tìm kiếm -->
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
						placeholder="<?php echo esc_attr( get_theme_mod( 'jobs_page_search_placeholder', 'Tìm kiếm theo vị trí, công ty, kỹ năng...' ) ); ?>" 
						value="<?php echo esc_attr( $init_keyword ); ?>" 
						autocomplete="off" 
						aria-label="Từ khóa tìm kiếm"
					/>
					<button type="button" class="aj-input-clear" id="aj-keyword-clear" title="Xóa từ khóa" style="<?php echo empty( $init_keyword ) ? 'display: none;' : ''; ?>">&times;</button>
				</div>

				<!-- 2. Chọn địa điểm -->
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

				<!-- 3. Chọn ngành nghề -->
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

				<!-- 4. Nút bấm tìm kiếm -->
				<button type="button" id="aj-search-btn" class="aj-search-btn"><?php echo esc_html( get_theme_mod( 'jobs_page_search_btn_text', 'TÌM KIẾM' ) ); ?></button>
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

			<!-- Các thẻ bộ lọc đang kích hoạt: [Hà Nội ✕], [Dưới 15 triệu ✕] -->
			<div class="aj-active-filters-bar" id="aj-active-filters-bar" style="display: none;">
				<span class="aj-af-label">Đang lọc:</span>
				<div class="aj-af-tags" id="aj-af-tags"></div>
				<button type="button" class="aj-af-clear-all" id="aj-af-clear-all">Xóa tất cả</button>
			</div>
		</div>

		<!-- THANH TRÊN: TIÊU ĐỀ + Bộ đếm kết quả + Menu sắp xếp -->
		<div class="aj-top-bar">
			<div class="aj-top-left">
				<?php if ( $show_heading ) : ?>
					<h2 class="aj-heading" id="aj-section-title"><?php echo esc_html( $section_title ); ?></h2>
				<?php endif; ?>
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

		<!-- LƯỚI DANH SÁCH VIỆC LÀM 2 CỘT -->
		<div class="aj-grid" id="aj-grid"></div>

		<!-- NÚT "XEM THÊM VIỆC LÀM" -->
		<div class="aj-load-more-wrap" id="aj-load-more-wrap">
			<button type="button" class="aj-load-more-btn" id="aj-load-more-btn"><?php echo esc_html( $load_more_text ); ?></button>
		</div>

		<!-- THÔNG BÁO KHI KHÔNG CÓ KẾT QUẢ PHÙ HỢP -->
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

	<script>
	(function() {
		var rawJobs = <?php echo wp_json_encode( $jobs_dataset ); ?>;
		var currentKeyword  = <?php echo wp_json_encode( $init_keyword ); ?>;
		var currentLocation = <?php echo wp_json_encode( $init_location ); ?>;
		var currentCategory = <?php echo wp_json_encode( $init_category ); ?>;
		var currentType     = 'all';
		var currentSalary   = 'all';
		var currentSort     = 'latest';
		var itemsPerPage    = <?php echo (int) $jobs_per_page; ?>;
		var visibleCount    = itemsPerPage;

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

		function getFilteredJobs() {
			return rawJobs.filter(function(job) {
				if (currentKeyword) {
					var kw = currentKeyword.toLowerCase().trim();
					if (job.search_text.indexOf(kw) === -1) {
						return false;
					}
				}
				if (currentLocation) {
					if (job.loc_code !== currentLocation) {
						return false;
					}
				}
				if (currentCategory) {
					if (job.cat_slug !== currentCategory) {
						return false;
					}
				}
				if (currentType !== 'all') {
					if (currentType === 'remote' || currentType === 'lam-tu-xa') {
						if (job.type_slug !== 'remote' && job.type_slug !== 'lam-tu-xa') {
							return false;
						}
					} else if (job.type_slug !== currentType) {
						return false;
					}
				}
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

		function render() {
			if (!gridEl) return;
			var filtered = getFilteredJobs();
			var sorted = sortJobs(filtered.slice());
			var totalMatches = sorted.length;

			renderActiveFilters();

			if (totalMatches === 0) {
				gridEl.style.display = 'none';
				gridEl.innerHTML = '';
				if (loadMoreWrapEl) loadMoreWrapEl.style.display = 'none';
				if (emptyEl) emptyEl.style.display = 'block';
				if (countBadgeEl) countBadgeEl.textContent = '(Tìm thấy 0 việc làm phù hợp)';
				return;
			}

			if (emptyEl) emptyEl.style.display = 'none';
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

				html += '<a href="' + escapeHtml(job.permalink) + '" class="aj-job-card">' +
					'<div class="aj-job-card-top">' +
						'<div class="aj-job-card-logo">' +
							'<img src="' + escapeHtml(job.logo) + '" alt="' + escapeHtml(job.company) + '" loading="lazy" />' +
						'</div>' +
						'<div class="aj-job-card-info">' +
							'<h3 class="aj-job-card-name">' + escapeHtml(job.title) + '</h3>' +
							'<div class="aj-job-card-date">Ngày đăng: ' + escapeHtml(job.date_formatted) + '</div>' +
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

			var showingCount = Math.min(visibleCount, totalMatches);
			if (countBadgeEl) {
				countBadgeEl.textContent = '(Hiển thị ' + showingCount + ' / ' + totalMatches + ' công việc)';
			}

			if (loadMoreWrapEl) {
				if (visibleCount >= totalMatches) {
					loadMoreWrapEl.style.display = 'none';
				} else {
					loadMoreWrapEl.style.display = 'block';
				}
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

		if (searchBtn) {
			searchBtn.addEventListener('click', function() {
				currentKeyword  = keywordInput ? keywordInput.value : '';
				currentLocation = locationSelect ? locationSelect.value : '';
				currentCategory = categorySelect ? categorySelect.value : '';
				onFilterChange();
			});
		}

		if (sortSelect) {
			sortSelect.addEventListener('change', function() {
				currentSort = sortSelect.value;
				render();
			});
		}

		var typePills = document.querySelectorAll('#qf-type-group .aj-qf-pill');
		typePills.forEach(function(pill) {
			pill.addEventListener('click', function() {
				typePills.forEach(function(p) { p.classList.remove('active'); });
				pill.classList.add('active');
				currentType = pill.getAttribute('data-type');
				onFilterChange();
			});
		});

		var salPills = document.querySelectorAll('#qf-salary-group .aj-qf-pill');
		salPills.forEach(function(pill) {
			pill.addEventListener('click', function() {
				salPills.forEach(function(p) { p.classList.remove('active'); });
				pill.classList.add('active');
				currentSalary = pill.getAttribute('data-salary');
				onFilterChange();
			});
		});

		if (afTagsEl) {
			afTagsEl.addEventListener('click', function(e) {
				var removeBtn = e.target.closest('.aj-af-tag-remove');
				if (removeBtn) {
					var key = removeBtn.getAttribute('data-filter-key');
					removeFilterByKey(key);
				}
			});
		}

		if (clearAllBtn) {
			clearAllBtn.addEventListener('click', function() {
				resetAllFilters();
			});
		}

		if (resetBtn) {
			resetBtn.addEventListener('click', function() {
				resetAllFilters();
			});
		}

		if (loadMoreBtn) {
			loadMoreBtn.addEventListener('click', function(e) {
				e.preventDefault();
				visibleCount += 6;
				render();
			});
		}

		render();
	})();
	</script>
	<?php
	return ob_get_clean();
}

/**
 * Xử lý shortcode [jobs] và [jobscout_jobs]
 *
 * @param array $atts
 * @return string
 */
function jobscout_render_interactive_jobs_shortcode( $atts = array() ) {
	$atts = shortcode_atts( array(
		'per_page'      => 6,
		'title'         => '',
		'show_heading'  => 'false',
	), $atts, 'jobs' );

	return jobscout_render_interactive_jobs_markup( array(
		'jobs_per_page' => (int) $atts['per_page'],
		'section_title' => $atts['title'],
		'show_heading'  => filter_var( $atts['show_heading'], FILTER_VALIDATE_BOOLEAN ),
	) );
}

/**
 * Ghi đè shortcode [jobs] và đăng ký [jobscout_jobs]
 */
function jobscout_setup_jobs_shortcode() {
	remove_shortcode( 'jobs' );
	add_shortcode( 'jobs', 'jobscout_render_interactive_jobs_shortcode' );
	add_shortcode( 'jobscout_jobs', 'jobscout_render_interactive_jobs_shortcode' );
}
add_action( 'init', 'jobscout_setup_jobs_shortcode', 20 );
