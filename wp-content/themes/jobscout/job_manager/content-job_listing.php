<?php
/**
 * Job listing in the loop - 100% matched to 4-all job 2.png
 *
 * @package JobScout
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $post;
$job_id       = get_the_ID();
$job_salary   = get_post_meta( $job_id, '_job_salary', true );
$job_featured = get_post_meta( $job_id, '_featured', true );
$company_name = get_post_meta( $job_id, '_company_name', true );
$job_expires  = get_post_meta( $job_id, '_job_expires', true );
$categories   = get_the_terms( $job_id, 'job_listing_category' );
$types        = wpjm_get_the_job_types( $job_id );

// 1. Get real enterprise logo from database (Ưu tiên ảnh đại diện mới được cập nhật)
if ( has_post_thumbnail( $job_id ) ) {
    $logo_url = get_the_post_thumbnail_url( $job_id, 'full' );
} else {
    $logo_url = get_post_meta( $job_id, '_company_logo', true );
}
if ( empty( $logo_url ) ) {
    $logo_url = 'http://wordpress.local/wp-content/uploads/2026/10/company-default.svg';
}

// 2. Format Created Date: "Ngày đăng: 14/10/2026"
$created_date = 'Ngày đăng: ' . get_the_date( 'd/m/Y' );

// 3. Pill meta: [ Toàn thời gian | Ngành nghề | TP. Hồ Chí Minh ]
$pill_items = array();
if ( ! empty( $types ) ) {
    $first_type = reset( $types );
    $pill_items[] = $first_type->name;
} else {
    $pill_items[] = 'Toàn thời gian';
}

if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
    $first_cat = reset( $categories );
    $pill_items[] = $first_cat->name;
} else {
    $pill_items[] = 'Tất cả ngành nghề';
}

$location = get_the_job_location();
if ( ! empty( $location ) ) {
    $clean_loc = trim( strip_tags( $location ) );
    if ( preg_match( '/(TP\.?\s*Hồ Chí Minh|Hồ Chí Minh|Hà Nội|Đà Nẵng|Cần Thơ|Hải Phòng)/ui', $clean_loc, $loc_matches ) ) {
        $clean_loc = $loc_matches[1];
    } elseif ( strlen( $clean_loc ) > 20 ) {
        $parts = explode( ',', $clean_loc );
        $clean_loc = trim( end( $parts ) );
    }
    $pill_items[] = $clean_loc;
} else {
    $pill_items[] = 'TP. Hồ Chí Minh';
}
$pill_text = implode( ' | ', $pill_items );

// 4. Extract 3 clean bullet points from job description
$bullets = array();
$raw_content = get_the_content();
if ( ! empty( $raw_content ) ) {
    if ( preg_match_all( '/<li[^>]*>(.*?)<\/li>/is', $raw_content, $matches ) ) {
        foreach ( $matches[1] as $m ) {
            $cleaned = trim( strip_tags( $m ) );
            if ( ! empty( $cleaned ) ) {
                $bullets[] = $cleaned;
                if ( count( $bullets ) >= 3 ) break;
            }
        }
    }
    if ( count( $bullets ) < 3 ) {
        $clean_text = trim( strip_tags( $raw_content ) );
        $sentences = preg_split( '/(?<=[.!?\n])\s+/u', $clean_text, -1, PREG_SPLIT_NO_EMPTY );
        foreach ( $sentences as $s ) {
            $s = trim( $s );
            if ( strlen( $s ) > 15 && ! in_array( $s, $bullets, true ) ) {
                $bullets[] = $s;
                if ( count( $bullets ) >= 3 ) break;
            }
        }
    }
}

$default_bullets = array(
    'Đảm bảo chất lượng công việc và hoàn thành xuất sắc các mục tiêu chuyên môn được giao.',
    'Môi trường làm việc năng động, chuyên nghiệp, lộ trình thăng tiến và chế độ đãi ngộ hấp dẫn.',
    'Chế độ bảo hiểm toàn diện, thưởng hiệu quả kinh doanh và các khóa đào tạo nâng cao kỹ năng.'
);
while ( count( $bullets ) < 3 ) {
    $bullets[] = array_shift( $default_bullets );
}

if ( function_exists( 'jobscout_normalize_media_host' ) ) {
    $logo_url = jobscout_normalize_media_host( $logo_url );
}
?>
<article <?php job_listing_class( 'career-job-card' ); ?> data-longitude="<?php echo esc_attr( $post->geolocation_lat ); ?>" data-latitude="<?php echo esc_attr( $post->geolocation_long ); ?>">

    <!-- TOP SECTION: LOGO + HEADER INFO -->
    <div class="job-card-top">
        <figure class="company-logo-box">
            <a href="<?php the_job_permalink(); ?>" title="<?php echo esc_attr( $company_name ?: get_the_title() ); ?>">
                <img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( $company_name ?: get_the_title() ); ?>" class="company_logo full-frame-image" />
            </a>
        </figure>

        <div class="job-card-info">
            <h3 class="job-card-title">
                <a href="<?php the_job_permalink(); ?>"><?php wpjm_the_job_title(); ?></a>
            </h3>

            <div class="job-card-created">
                <?php echo esc_html( $created_date ); ?>
            </div>

            <div class="job-card-pill">
                <?php echo esc_html( $pill_text ); ?>
            </div>
        </div>
    </div>

    <!-- BOTTOM SECTION: 3 BULLET POINTS -->
    <div class="job-card-bottom">
        <ul class="job-card-bullets">
            <?php foreach ( array_slice( $bullets, 0, 3 ) as $bullet ) : ?>
                <li><?php echo esc_html( $bullet ); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

</article>
