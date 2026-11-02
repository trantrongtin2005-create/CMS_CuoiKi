<?php
/**
 * Module Cấu trúc STAR & Social Share Helper
 * File: inc/star-framework.php
 *
 * Cung cấp hệ thống Shortcode và Helper cho mô hình STAR:
 * S - Situation: Bối cảnh / Tình huống thực tế
 * T - Task: Nhiệm vụ / Thách thức đặt ra
 * A - Action: Hành động / Giải pháp triển khai
 * R - Result: Kết quả đo lường đạt được
 *
 * @package JobScout
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 1. Hàm sinh mã SVG Icon chất lượng cao cho từng thành tố STAR
 */
function jobscout_star_get_icon( $type = 'situation' ) {
	switch ( $type ) {
		case 'situation':
		case 's':
			// Icon Radar / Scope / Target đại diện cho Bối cảnh / Tình huống
			return '<svg class="star-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
				<circle cx="12" cy="12" r="10"></circle>
				<circle cx="12" cy="12" r="6"></circle>
				<circle cx="12" cy="12" r="2"></circle>
				<line x1="12" y1="2" x2="12" y2="4"></line>
				<line x1="12" y1="20" x2="12" y2="22"></line>
				<line x1="2" y1="12" x2="4" y2="12"></line>
				<line x1="20" y1="12" x2="22" y2="12"></line>
			</svg>';

		case 'task':
		case 't':
			// Icon Flag / Milestone đại diện cho Nhiệm vụ / Thách thức
			return '<svg class="star-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
				<path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path>
				<line x1="4" y1="22" x2="4" y2="15"></line>
			</svg>';

		case 'action':
		case 'a':
			// Icon Rocket / Zap đại diện cho Hành động / Giải pháp triển khai
			return '<svg class="star-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
				<path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"></path>
				<path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"></path>
				<path d="M9 12H4s.55-3.03 2-4.5c1.62-1.63 5-2.5 5-2.5"></path>
				<path d="M15 12v5s3.03-.55 4.5-2c1.63-1.62 2.5-5 2.5-5"></path>
			</svg>';

		case 'result':
		case 'r':
			// Icon Trophy / Trending Up đại diện cho Kết quả đo lường đạt được
			return '<svg class="star-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
				<path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path>
				<path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path>
				<path d="M4 22h16"></path>
				<path d="M10 14.66V17c0 .55-.45 1-1 1H7v4h10v-4h-2c-.55 0-1-.45-1-1v-2.34"></path>
				<path d="M18 4H6v7a6 6 0 0 0 12 0V4z"></path>
			</svg>';

		default:
			return '';
	}
}

/**
 * 2. Lấy thông tin cấu hình mặc định của từng thành tố STAR
 */
function jobscout_star_get_meta( $type = 'situation' ) {
	$data = array(
		'situation' => array(
			'letter'     => 'S',
			'label_en'   => 'Situation',
			'label_vi'   => 'Bối cảnh / Tình huống thực tế',
			'step'       => 'Step 01',
			'class'      => 'star-situation',
			'color_code' => '#2563EB',
		),
		'task' => array(
			'letter'     => 'T',
			'label_en'   => 'Task',
			'label_vi'   => 'Nhiệm vụ / Thách thức đặt ra',
			'step'       => 'Step 02',
			'class'      => 'star-task',
			'color_code' => '#D97706',
		),
		'action' => array(
			'letter'     => 'A',
			'label_en'   => 'Action',
			'label_vi'   => 'Hành động / Giải pháp triển khai',
			'step'       => 'Step 03',
			'class'      => 'star-action',
			'color_code' => '#7C3AED',
		),
		'result' => array(
			'letter'     => 'R',
			'label_en'   => 'Result',
			'label_vi'   => 'Kết quả đo lường đạt được',
			'step'       => 'Step 04',
			'class'      => 'star-result',
			'color_code' => '#059669',
		),
	);

	$type = strtolower( trim( $type ) );
	if ( 's' === $type ) $type = 'situation';
	if ( 't' === $type ) $type = 'task';
	if ( 'a' === $type ) $type = 'action';
	if ( 'r' === $type ) $type = 'result';

	return isset( $data[ $type ] ) ? $data[ $type ] : $data['situation'];
}

/**
 * 3. Render HTML cho một mục thành tố STAR
 */
function jobscout_render_star_item_html( $type, $content, $custom_title = '', $custom_badge = '' ) {
	$meta = jobscout_star_get_meta( $type );
	$icon = jobscout_star_get_icon( $type );

	$title = ! empty( $custom_title ) ? $custom_title : $meta['label_vi'];
	$badge_letter = ! empty( $custom_badge ) ? $custom_badge : $meta['letter'];

	ob_start();
	?>
	<div class="star-item <?php echo esc_attr( $meta['class'] ); ?>">
		<div class="star-badge-wrap">
			<div class="star-badge" aria-label="<?php echo esc_attr( $meta['label_en'] ); ?>">
				<span class="star-letter"><?php echo esc_html( $badge_letter ); ?></span>
			</div>
			<div class="star-line-connector" aria-hidden="true"></div>
		</div>
		<div class="star-content-wrap">
			<div class="star-header">
				<div class="star-meta-info">
					<span class="star-step-pill"><?php echo esc_html( $meta['step'] ); ?></span>
					<span class="star-en-label"><?php echo esc_html( $meta['label_en'] ); ?></span>
				</div>
				<h4 class="star-title">
					<span class="star-header-icon" aria-hidden="true"><?php echo $icon; ?></span>
					<span class="star-title-text"><?php echo esc_html( $title ); ?></span>
				</h4>
			</div>
			<div class="star-body">
				<?php echo do_shortcode( wpautop( trim( $content ) ) ); ?>
			</div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * 4. Đăng ký Shortcode [star_box]
 *
 * Cú pháp:
 * [star_box title="Phân tích tình huống theo cấu trúc STAR" layout="timeline|grid"]
 *   [star_situation title="..."]Nội dung Bối cảnh[/star_situation]
 *   [star_task title="..."]Nội dung Nhiệm vụ[/star_task]
 *   [star_action title="..."]Nội dung Hành động[/star_action]
 *   [star_result title="..."]Nội dung Kết quả[/star_result]
 * [/star_box]
 */
function jobscout_star_box_shortcode( $atts, $content = null ) {
	$atts = shortcode_atts( array(
		'title'    => '',
		'subtitle' => '',
		'layout'   => 'timeline', // 'timeline' hoặc 'grid'
		'class'    => '',
	), $atts, 'star_box' );

	$layout_class = ( 'grid' === $atts['layout'] ) ? 'star-layout-grid' : 'star-layout-timeline';
	$extra_class  = ! empty( $atts['class'] ) ? ' ' . esc_attr( $atts['class'] ) : '';

	ob_start();
	?>
	<div class="star-box <?php echo esc_attr( $layout_class . $extra_class ); ?>">
		<?php if ( ! empty( $atts['title'] ) || ! empty( $atts['subtitle'] ) ) : ?>
			<div class="star-box-header">
				<?php if ( ! empty( $atts['title'] ) ) : ?>
					<h3 class="star-box-main-title">
						<span class="star-box-badge-tag">STAR MODEL</span>
						<?php echo esc_html( $atts['title'] ); ?>
					</h3>
				<?php endif; ?>
				<?php if ( ! empty( $atts['subtitle'] ) ) : ?>
					<p class="star-box-subtitle"><?php echo esc_html( $atts['subtitle'] ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="star-items-list">
			<?php echo do_shortcode( $content ); ?>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'star_box', 'jobscout_star_box_shortcode' );

/**
 * 5. Đăng ký Shortcode cho từng thành tố S, T, A, R
 */
function jobscout_star_situation_shortcode( $atts, $content = null ) {
	$atts = shortcode_atts( array(
		'title' => '',
		'badge' => 'S',
	), $atts, 'star_situation' );

	return jobscout_render_star_item_html( 'situation', $content, $atts['title'], $atts['badge'] );
}
add_shortcode( 'star_situation', 'jobscout_star_situation_shortcode' );

function jobscout_star_task_shortcode( $atts, $content = null ) {
	$atts = shortcode_atts( array(
		'title' => '',
		'badge' => 'T',
	), $atts, 'star_task' );

	return jobscout_render_star_item_html( 'task', $content, $atts['title'], $atts['badge'] );
}
add_shortcode( 'star_task', 'jobscout_star_task_shortcode' );

function jobscout_star_action_shortcode( $atts, $content = null ) {
	$atts = shortcode_atts( array(
		'title' => '',
		'badge' => 'A',
	), $atts, 'star_action' );

	return jobscout_render_star_item_html( 'action', $content, $atts['title'], $atts['badge'] );
}
add_shortcode( 'star_action', 'jobscout_star_action_shortcode' );

function jobscout_star_result_shortcode( $atts, $content = null ) {
	$atts = shortcode_atts( array(
		'title' => '',
		'badge' => 'R',
	), $atts, 'star_result' );

	return jobscout_render_star_item_html( 'result', $content, $atts['title'], $atts['badge'] );
}
add_shortcode( 'star_result', 'jobscout_star_result_shortcode' );

// Shortcode tổng quát [star_item type="..."]
function jobscout_star_item_shortcode( $atts, $content = null ) {
	$atts = shortcode_atts( array(
		'type'  => 'situation',
		'title' => '',
		'badge' => '',
	), $atts, 'star_item' );

	return jobscout_render_star_item_html( $atts['type'], $content, $atts['title'], $atts['badge'] );
}
add_shortcode( 'star_item', 'jobscout_star_item_shortcode' );

/**
 * 6. Helper sinh đường dẫn chia sẻ mạng xã hội chuẩn
 */
function jobscout_get_social_share_data( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}
	$url   = get_permalink( $post_id );
	$title = get_the_title( $post_id );

	$encoded_url   = rawurlencode( $url );
	$encoded_title = rawurlencode( $title );

	return array(
		'url'       => $url,
		'title'     => $title,
		'facebook'  => 'https://www.facebook.com/sharer/sharer.php?u=' . $encoded_url,
		'twitter'   => 'https://twitter.com/intent/tweet?url=' . $encoded_url . '&text=' . $encoded_title,
		'linkedin'  => 'https://www.linkedin.com/sharing/share-offsite/?url=' . $encoded_url,
	);
}
