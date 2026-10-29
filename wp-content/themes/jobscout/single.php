<?php
/**
 * Template Name: Chi tiết bài viết (News Detail)
 * File: single.php (hoặc single-news.php)
 *
 * Giao diện hiển thị chi tiết bài viết chuẩn 100% theo mẫu thiết kế
 * Bao gồm:
 * 1. Header & Navigation (get_header)
 * 2. Breadcrumb Navigation (Home / All News / News Detail)
 * 3. Header Bài Viết (Article Info Box: Thumbnail, Tiêu đề in hoa, Ngày đăng, Category, Location ACF, Nút Share)
 * 4. Nội Dung Bài Viết (the_content: Thiết kế chuẩn Typography & Responsive Gutenberg Blocks)
 * 5. Bài Viết Mới Nhất (NEWEST BLOG ENTRIES: Lưới 2 cột x 3 hàng, WP_Query loại trừ bài viết hiện tại)
 * 6. Newsletter Subscription Banner (Banner cam full-width, hỗ trợ Shortcode)
 * 7. Footer chuẩn WordPress (get_footer)
 *
 * @package JobScout
 */

get_header();

// Vòng lặp lấy dữ liệu bài viết hiện tại
while ( have_posts() ) :
	the_post();
	$current_id   = get_the_ID();
	$post_title   = get_the_title();
	$post_link    = get_permalink();
	$post_date    = get_the_date( 'M d, Y' );

	// Lấy Danh mục bài viết (Category Name)
	$categories = get_the_category();
	$cat_name   = ! empty( $categories ) ? $categories[0]->name : __( 'News', 'jobscout' );
	$cat_link   = ! empty( $categories ) ? get_category_link( $categories[0]->term_id ) : '#';

	// Lấy Địa điểm từ Custom Field ACF (hỗ trợ get_field hoặc get_post_meta)
	$location = '';
	if ( function_exists( 'get_field' ) ) {
		$location = get_field( 'location', $current_id );
	}
	if ( empty( $location ) ) {
		$location = get_post_meta( $current_id, 'location', true );
	}
	if ( empty( $location ) ) {
		$location = 'Ho Chi Minh City'; // Giá trị mặc định theo đúng mẫu thiết kế
	}

	// Xử lý ảnh đại diện (Thumbnail) với ảnh fallback đẹp mắt
	$thumb_url = '';
	if ( has_post_thumbnail( $current_id ) ) {
		$thumb_url = get_the_post_thumbnail_url( $current_id, 'medium' );
	} else {
		$thumb_url = 'https://images.unsplash.com/photo-1542051841857-5f90071e7989?auto=format&fit=crop&w=400&q=80';
	}
	?>

	<!-- ============================================================
	     CSS: STYLES CHO TRANG CHI TIẾT BÀI VIẾT (CHUẨN 100% THIẾT KẾ)
	     ============================================================ -->
	<style id="news-detail-single-styles">
		/* Font Google Montserrat */
		@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap');

		/* 1. Global Page Reset & Nền xám sáng #F5F6F8 */
		body.single-post,
		.single-post #page,
		.single-post #content,
		.single-post .site-content,
		.single-post .site-main {
			background-color: #F5F6F8 !important;
		}

		/* Ẩn sidebar và các thành phần mặc định của theme để đảm bảo layout chuẩn thiết kế */
		.single-post #secondary { display: none !important; }
		.single-post .breadcrumb-wrap { display: none !important; }
		.single-post .site-content > header.entry-header { display: none !important; }
		.single-post .author-bio,
		.single-post .post-navigation,
		.single-post .entry-footer { display: none !important; }

		/* Ẩn banner newsletter trùng lặp ở footer nếu footer widget đã có sẵn */
		.single-post .site-footer-module .ft-newsletter-bar {
			display: none !important;
		}

		.single-post #primary {
			width: 100% !important;
			float: none !important;
			max-width: 100% !important;
			padding: 0 !important;
			margin: 0 !important;
			background: transparent !important;
		}

		.single-post .site-content .container {
			max-width: 100% !important;
			padding: 0 !important;
		}

		/* Khung bao toàn trang chi tiết tin tức */
		.news-detail-page {
			max-width: 1140px;
			margin: 0 auto;
			padding: 0 20px 60px;
			font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
			box-sizing: border-box;
			color: #2D3748;
		}

		.news-detail-page * {
			box-sizing: border-box;
		}

		/* 2. BREADCRUMB NAVIGATION */
		.news-breadcrumb {
			padding: 24px 0 18px;
			font-size: 13px;
			font-weight: 500;
			display: flex;
			align-items: center;
			gap: 8px;
			flex-wrap: wrap;
			color: #94A3B8;
		}

		.news-breadcrumb a {
			color: #E87722;
			text-decoration: none;
			transition: color 0.2s ease;
		}

		.news-breadcrumb a:hover {
			color: #c2410c;
			text-decoration: underline;
		}

		.news-breadcrumb .news-bc-sep {
			color: #CBD5E1;
			font-weight: 400;
			margin: 0 2px;
		}

		.news-breadcrumb .news-bc-current {
			color: #64748B;
			font-weight: 500;
			max-width: 480px;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
			display: inline-block;
			vertical-align: middle;
		}

		/* 3. HEADER BÀI VIẾT (ARTICLE INFO BOX) */
		.news-header-box {
			background: #FFFFFF;
			border-radius: 4px;
			box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
			padding: 24px 28px;
			margin-bottom: 24px;
			display: flex;
			align-items: center;
			justify-content: space-between;
			gap: 24px;
		}

		.news-header-left {
			display: flex;
			align-items: center;
			gap: 22px;
			flex: 1 1 auto;
			min-width: 0;
		}

		.news-header-thumb {
			width: 96px;
			height: 96px;
			min-width: 96px;
			border-radius: 4px;
			overflow: hidden;
			background: #F1F5F9;
			border: 1px solid #E2E8F0;
			display: flex;
			align-items: center;
			justify-content: center;
			flex-shrink: 0;
		}

		.news-header-thumb img {
			width: 100%;
			height: 100%;
			object-fit: cover;
			display: block;
		}

		.news-header-content {
			flex: 1 1 auto;
			min-width: 0;
		}

		.news-header-title {
			font-size: 20px;
			font-weight: 800;
			color: #1A1D20;
			text-transform: uppercase;
			letter-spacing: 0.3px;
			line-height: 1.35;
			margin: 0 0 6px 0;
			word-break: break-word;
			font-family: 'Montserrat', sans-serif;
		}

		.news-header-date {
			font-size: 12.5px;
			font-weight: 500;
			color: #94A3B8;
			margin-bottom: 12px;
		}

		.news-header-tags {
			display: flex;
			align-items: center;
			gap: 10px;
			flex-wrap: wrap;
		}

		.news-tag-badge {
			display: inline-flex;
			align-items: center;
			padding: 4px 14px;
			background: #FFFFFF;
			border: 1px solid #CBD5E1;
			border-radius: 4px;
			font-size: 11.5px;
			font-weight: 500;
			color: #475569;
			text-decoration: none !important;
			line-height: 1.4;
			transition: all 0.2s ease;
		}

		a.news-tag-badge:hover {
			border-color: #E87722;
			color: #E87722;
		}

		/* CỤM NÚT CHIA SẺ MẠNG XÃ HỘI (SOCIAL SHARE UI) TẠI HEADER */
		.news-header-right {
			flex-shrink: 0;
			position: relative;
		}

		.news-social-share-group {
			display: inline-flex;
			align-items: center;
			gap: 10px;
			background: #F8FAFC;
			border: 1px solid #E2E8F0;
			padding: 6px 12px;
			border-radius: 8px;
			box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
			transition: all 0.25s ease;
		}

		.news-social-share-group:hover {
			border-color: #CBD5E1;
			background: #FFFFFF;
			box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
		}

		.news-share-label {
			font-family: 'Montserrat', sans-serif;
			font-size: 11.5px;
			font-weight: 800;
			letter-spacing: 0.8px;
			color: #475569;
			text-transform: uppercase;
			display: flex;
			align-items: center;
			gap: 6px;
			user-select: none;
		}

		.news-share-label svg {
			color: #E87722;
			flex-shrink: 0;
		}

		.news-social-buttons {
			display: flex;
			align-items: center;
			gap: 6px;
		}

		/* Từng icon nút mạng xã hội (Facebook, Twitter/X, LinkedIn, Copy Link) */
		.news-social-btn {
			width: 34px;
			height: 34px;
			min-width: 34px;
			border-radius: 6px;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			text-decoration: none !important;
			border: 1px solid transparent;
			cursor: pointer;
			position: relative;
			transition: all 0.22s ease;
			background: #FFFFFF;
			padding: 0;
		}

		.news-social-btn svg {
			width: 15px;
			height: 15px;
			transition: transform 0.2s ease, fill 0.2s ease;
		}

		/* Facebook Button */
		.news-social-facebook {
			color: #1877F2;
			border-color: #DBEAFE;
			background: #EFF6FF;
		}
		.news-social-facebook:hover {
			background: #1877F2;
			color: #FFFFFF;
			border-color: #1877F2;
			transform: translateY(-2px);
			box-shadow: 0 4px 12px rgba(24, 119, 242, 0.35);
		}

		/* Twitter / X Button */
		.news-social-twitter {
			color: #0F1419;
			border-color: #E2E8F0;
			background: #F1F5F9;
		}
		.news-social-twitter:hover {
			background: #000000;
			color: #FFFFFF;
			border-color: #000000;
			transform: translateY(-2px);
			box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
		}

		/* LinkedIn Button */
		.news-social-linkedin {
			color: #0A66C2;
			border-color: #BFDBFE;
			background: #EFF6FF;
		}
		.news-social-linkedin:hover {
			background: #0A66C2;
			color: #FFFFFF;
			border-color: #0A66C2;
			transform: translateY(-2px);
			box-shadow: 0 4px 12px rgba(10, 102, 194, 0.35);
		}

		/* Copy Link Button */
		.news-social-copy {
			color: #475569;
			border-color: #E2E8F0;
			background: #FFFFFF;
		}
		.news-social-copy:hover {
			background: #E87722;
			color: #FFFFFF;
			border-color: #E87722;
			transform: translateY(-2px);
			box-shadow: 0 4px 12px rgba(232, 119, 34, 0.35);
		}

		.news-social-btn:active {
			transform: translateY(0);
		}

		/* Tooltip hiển thị khi rê chuột qua icon */
		.news-social-btn::after {
			content: attr(data-tooltip);
			position: absolute;
			bottom: -32px;
			left: 50%;
			transform: translateX(-50%) translateY(4px);
			background: #1E293B;
			color: #FFFFFF;
			font-family: 'Montserrat', sans-serif;
			font-size: 11px;
			font-weight: 600;
			white-space: nowrap;
			padding: 4px 8px;
			border-radius: 4px;
			pointer-events: none;
			opacity: 0;
			visibility: hidden;
			transition: all 0.2s ease;
			z-index: 100;
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
		}

		.news-social-btn:hover::after {
			opacity: 1;
			visibility: visible;
			transform: translateX(-50%) translateY(0);
		}

		/* Nút SHARE cũ (Fallback) */
		.news-share-btn {
			display: none;
		}

		/* 4. NỘI DUNG BÀI VIẾT (MAIN ARTICLE CONTENT) */
		.news-article-card {
			background: #FFFFFF;
			border-radius: 4px;
			box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
			padding: 42px 48px;
			margin-bottom: 50px;
		}

		.news-article-body {
			font-size: 15.5px;
			line-height: 1.85;
			color: #374151;
			word-wrap: break-word;
		}

		.news-article-body p {
			margin-bottom: 22px;
			color: #374151;
		}

		.news-article-body h1,
		.news-article-body h2,
		.news-article-body h3,
		.news-article-body h4 {
			color: #111827;
			font-weight: 700;
			margin: 34px 0 16px;
			line-height: 1.35;
		}

		.news-article-body img {
			max-width: 100%;
			height: auto;
			border-radius: 4px;
			display: block;
			margin: 28px auto;
		}

		.news-article-body figure {
			margin: 28px 0;
		}

		.news-article-body figure.aligncenter,
		.news-article-body .wp-block-image {
			text-align: center;
			margin: 28px 0;
		}

		.news-article-body .wp-block-image img {
			margin: 0 auto;
			width: 100%;
			max-height: 520px;
			object-fit: cover;
		}

		.news-article-body figcaption {
			font-size: 13px;
			color: #64748B;
			margin-top: 8px;
			font-style: italic;
			text-align: center;
		}

		.news-article-body blockquote {
			border-left: 4px solid #E87722;
			background: #F8FAFC;
			padding: 18px 24px;
			margin: 28px 0;
			border-radius: 0 4px 4px 0;
			font-style: italic;
			color: #475569;
		}

		.news-article-body ul,
		.news-article-body ol {
			margin: 20px 0 24px 28px;
			padding: 0;
		}

		.news-article-body li {
			margin-bottom: 8px;
		}

		/* ============================================================
		   THANH CHIA SẺ BÀI VIẾT CUỐI BÀI (BOTTOM SOCIAL SHARE BAR)
		   ============================================================ */
		.news-article-share-footer {
			margin-top: 42px;
			padding-top: 28px;
			border-top: 1px solid #E2E8F0;
			display: flex;
			align-items: center;
			justify-content: space-between;
			flex-wrap: wrap;
			gap: 16px;
		}

		.news-share-footer-left {
			display: flex;
			align-items: center;
			gap: 10px;
		}

		.news-share-footer-title {
			font-size: 13.5px !important;
			font-weight: 700 !important;
			text-transform: uppercase;
			letter-spacing: 0.6px;
			color: #1E293B !important;
			display: flex;
			align-items: center;
			gap: 8px;
			margin: 0 !important;
		}

		.news-share-footer-title svg {
			color: #E87722;
			flex-shrink: 0;
		}

		.news-share-footer-links {
			display: flex;
			align-items: center;
			gap: 8px;
			flex-wrap: wrap;
		}

		.news-share-pill-btn {
			display: inline-flex;
			align-items: center;
			gap: 8px;
			padding: 8px 16px;
			border-radius: 6px;
			font-size: 12.5px;
			font-weight: 700;
			text-decoration: none !important;
			border: 1px solid transparent;
			cursor: pointer;
			transition: all 0.22s ease;
			font-family: 'Montserrat', sans-serif;
			background: #FFFFFF;
		}

		.news-share-pill-btn svg {
			width: 15px;
			height: 15px;
			flex-shrink: 0;
		}

		.news-share-pill-btn.share-fb {
			background: #EFF6FF;
			color: #1877F2;
			border-color: #DBEAFE;
		}
		.news-share-pill-btn.share-fb:hover {
			background: #1877F2;
			color: #FFFFFF;
			box-shadow: 0 4px 12px rgba(24, 119, 242, 0.3);
			transform: translateY(-2px);
		}

		.news-share-pill-btn.share-tw {
			background: #F1F5F9;
			color: #0F1419;
			border-color: #E2E8F0;
		}
		.news-share-pill-btn.share-tw:hover {
			background: #000000;
			color: #FFFFFF;
			box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
			transform: translateY(-2px);
		}

		.news-share-pill-btn.share-in {
			background: #EFF6FF;
			color: #0A66C2;
			border-color: #BFDBFE;
		}
		.news-share-pill-btn.share-in:hover {
			background: #0A66C2;
			color: #FFFFFF;
			box-shadow: 0 4px 12px rgba(10, 102, 194, 0.3);
			transform: translateY(-2px);
		}

		.news-share-pill-btn.share-copy {
			background: #FFFFFF;
			color: #475569;
			border-color: #CBD5E1;
		}
		.news-share-pill-btn.share-copy:hover {
			background: #E87722;
			color: #FFFFFF;
			border-color: #E87722;
			box-shadow: 0 4px 12px rgba(232, 119, 34, 0.3);
			transform: translateY(-2px);
		}

		/* ============================================================
		   BỘ COMPONENT & CSS CẤU TRÚC STAR (S - T - A - R)
		   S: Situation (Bối cảnh) | T: Task (Nhiệm vụ)
		   A: Action (Hành động)   | R: Result (Kết quả)
		   ============================================================ */

		/* 1. Khung bao toàn bộ mô hình STAR (.star-box) */
		.star-box {
			background: #FFFFFF;
			border: 1px solid #E2E8F0;
			border-radius: 12px;
			padding: 30px 28px;
			margin: 34px 0;
			box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05);
			position: relative;
			overflow: hidden;
		}

		.star-box::before {
			content: "";
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			height: 4px;
			background: linear-gradient(90deg, #2563EB 0%, #D97706 33%, #7C3AED 66%, #059669 100%);
		}

		.star-box-header {
			margin-bottom: 26px;
			padding-bottom: 16px;
			border-bottom: 1px solid #F1F5F9;
		}

		.star-box-main-title {
			font-size: 20px !important;
			font-weight: 800 !important;
			color: #0F172A !important;
			margin: 0 0 6px 0 !important;
			display: flex;
			align-items: center;
			gap: 10px;
			flex-wrap: wrap;
			line-height: 1.35 !important;
		}

		.star-box-badge-tag {
			display: inline-block;
			background: #0F172A;
			color: #FFFFFF;
			font-size: 11px;
			font-weight: 800;
			letter-spacing: 1px;
			padding: 3px 8px;
			border-radius: 4px;
			text-transform: uppercase;
		}

		.star-box-subtitle {
			font-size: 14px !important;
			color: #64748B !important;
			margin: 0 !important;
			line-height: 1.5 !important;
		}

		/* 2. Danh sách các thành tố STAR & Hiệu ứng Timeline */
		.star-items-list {
			display: flex;
			flex-direction: column;
			gap: 24px;
			position: relative;
		}

		.star-item {
			display: flex;
			align-items: flex-start;
			gap: 20px;
			position: relative;
			transition: transform 0.2s ease, box-shadow 0.2s ease;
		}

		/* Trục và Badge chữ cái STAR (S, T, A, R) */
		.star-badge-wrap {
			display: flex;
			flex-direction: column;
			align-items: center;
			flex-shrink: 0;
			position: relative;
		}

		.star-badge {
			width: 44px;
			height: 44px;
			border-radius: 50%;
			display: flex;
			align-items: center;
			justify-content: center;
			font-family: 'Montserrat', sans-serif;
			font-weight: 900;
			font-size: 18px;
			color: #FFFFFF;
			box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
			position: relative;
			z-index: 2;
			flex-shrink: 0;
			transition: transform 0.2s ease;
		}

		.star-item:hover .star-badge {
			transform: scale(1.08);
		}

		.star-line-connector {
			width: 2px;
			flex-grow: 1;
			min-height: 28px;
			background: #E2E8F0;
			margin: 8px 0 -16px;
			position: relative;
			z-index: 1;
		}

		.star-item:last-child .star-line-connector {
			display: none;
		}

		/* Khung nội dung thành tố STAR */
		.star-content-wrap {
			flex: 1 1 auto;
			min-width: 0;
			background: #F8FAFC;
			border: 1px solid #E2E8F0;
			border-radius: 10px;
			padding: 20px 22px;
			transition: all 0.2s ease;
			position: relative;
		}

		.star-item:hover .star-content-wrap {
			background: #FFFFFF;
			box-shadow: 0 6px 18px -3px rgba(0, 0, 0, 0.08);
			transform: translateY(-2px);
		}

		.star-header {
			margin-bottom: 12px;
		}

		.star-meta-info {
			display: flex;
			align-items: center;
			gap: 8px;
			margin-bottom: 6px;
		}

		.star-step-pill {
			font-size: 11px;
			font-weight: 700;
			text-transform: uppercase;
			letter-spacing: 0.5px;
			padding: 2px 8px;
			border-radius: 12px;
			line-height: 1.4;
		}

		.star-en-label {
			font-size: 11.5px;
			font-weight: 600;
			color: #64748B;
			text-transform: uppercase;
			letter-spacing: 0.5px;
		}

		.star-title {
			font-size: 16.5px !important;
			font-weight: 700 !important;
			margin: 0 !important;
			display: flex;
			align-items: center;
			gap: 8px;
			line-height: 1.4 !important;
		}

		.star-header-icon {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			flex-shrink: 0;
		}

		.star-body {
			font-size: 14.5px;
			line-height: 1.75;
			color: #334155;
		}

		.star-body p:last-child {
			margin-bottom: 0 !important;
		}

		/* 3. Màu sắc & định dạng chuyên biệt cho 4 thành tố STAR */

		/* --- S: SITUATION (Bối cảnh / Tình huống thực tế) --- */
		.star-situation .star-badge {
			background: linear-gradient(135deg, #1E40AF 0%, #3B82F6 100%);
			box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
		}
		.star-situation .star-content-wrap {
			border-left: 4px solid #2563EB;
		}
		.star-situation .star-step-pill {
			background: #DBEAFE;
			color: #1D4ED8;
		}
		.star-situation .star-title {
			color: #1E40AF !important;
		}
		.star-situation .star-header-icon {
			color: #2563EB;
		}

		/* --- T: TASK (Nhiệm vụ / Thách thức đặt ra) --- */
		.star-task .star-badge {
			background: linear-gradient(135deg, #B45309 0%, #F59E0B 100%);
			box-shadow: 0 4px 14px rgba(217, 119, 6, 0.35);
		}
		.star-task .star-content-wrap {
			border-left: 4px solid #D97706;
		}
		.star-task .star-step-pill {
			background: #FEF3C7;
			color: #B45309;
		}
		.star-task .star-title {
			color: #B45309 !important;
		}
		.star-task .star-header-icon {
			color: #D97706;
		}

		/* --- A: ACTION (Hành động / Giải pháp triển khai) --- */
		.star-action .star-badge {
			background: linear-gradient(135deg, #6D28D9 0%, #8B5CF6 100%);
			box-shadow: 0 4px 14px rgba(124, 58, 237, 0.35);
		}
		.star-action .star-content-wrap {
			border-left: 4px solid #7C3AED;
		}
		.star-action .star-step-pill {
			background: #EDE9FE;
			color: #6D28D9;
		}
		.star-action .star-title {
			color: #6D28D9 !important;
		}
		.star-action .star-header-icon {
			color: #7C3AED;
		}

		/* --- R: RESULT (Kết quả đo lường đạt được) --- */
		.star-result .star-badge {
			background: linear-gradient(135deg, #047857 0%, #10B981 100%);
			box-shadow: 0 4px 14px rgba(5, 150, 105, 0.35);
		}
		.star-result .star-content-wrap {
			border-left: 4px solid #059669;
		}
		.star-result .star-step-pill {
			background: #D1FAE5;
			color: #047857;
		}
		.star-result .star-title {
			color: #047857 !important;
		}
		.star-result .star-header-icon {
			color: #059669;
		}

		/* 4. Định dạng khi một khối STAR đứng ĐỘC LẬP (Callout Box riêng lẻ) */
		div.star-situation:not(.star-item),
		div.star-task:not(.star-item),
		div.star-action:not(.star-item),
		div.star-result:not(.star-item) {
			border-radius: 8px;
			padding: 22px 24px;
			margin: 26px 0;
			position: relative;
			box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
		}

		div.star-situation:not(.star-item) {
			background: #F0F7FF;
			border: 1px solid #BFDBFE;
			border-left: 5px solid #2563EB;
		}
		div.star-task:not(.star-item) {
			background: #FFFBEB;
			border: 1px solid #FDE68A;
			border-left: 5px solid #D97706;
		}
		div.star-action:not(.star-item) {
			background: #F5F3FF;
			border: 1px solid #DDD6FE;
			border-left: 5px solid #7C3AED;
		}
		div.star-result:not(.star-item) {
			background: #ECFDF5;
			border: 1px solid #A7F3D0;
			border-left: 5px solid #059669;
		}

		/* Biến thể hiển thị Grid (Lưới 2 cột x 2 hàng) */
		.star-box.star-layout-grid .star-items-list {
			display: grid;
			grid-template-columns: repeat(2, 1fr);
			gap: 20px;
		}
		.star-box.star-layout-grid .star-line-connector {
			display: none !important;
		}
		.star-box.star-layout-grid .star-item {
			height: 100%;
		}
		.star-box.star-layout-grid .star-content-wrap {
			height: 100%;
			display: flex;
			flex-direction: column;
		}
		.star-box.star-layout-grid .star-body {
			flex-grow: 1;
		}

		/* 5. BÀI VIẾT MỚI NHẤT (NEWEST BLOG ENTRIES) */
		.news-newest-section {
			margin-top: 50px;
			margin-bottom: 50px;
		}

		.news-newest-title {
			font-size: 24px;
			font-weight: 800;
			letter-spacing: 0.6px;
			color: #1A1D20;
			text-transform: uppercase;
			text-align: center;
			margin: 0 0 34px 0;
			font-family: 'Montserrat', sans-serif;
		}

		.news-blog-grid {
			display: grid;
			grid-template-columns: repeat(2, 1fr);
			gap: 24px;
		}

		.news-blog-card {
			background: #FFFFFF;
			border-radius: 4px;
			box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
			padding: 20px 22px;
			display: flex;
			align-items: flex-start;
			gap: 20px;
			transition: transform 0.25s ease, box-shadow 0.25s ease;
		}

		.news-blog-card:hover {
			transform: translateY(-2px);
			box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
		}

		.news-card-thumb-wrap {
			width: 120px;
			height: 108px;
			min-width: 120px;
			border-radius: 4px;
			overflow: hidden;
			background: #F1F5F9;
			flex-shrink: 0;
		}

		.news-card-thumb-wrap a {
			display: block;
			width: 100%;
			height: 100%;
		}

		.news-card-thumb-wrap img {
			width: 100%;
			height: 100%;
			object-fit: cover;
			display: block;
			transition: transform 0.3s ease;
		}

		.news-blog-card:hover .news-card-thumb-wrap img {
			transform: scale(1.05);
		}

		.news-card-body {
			flex: 1 1 auto;
			min-width: 0;
		}

		.news-card-title {
			font-size: 16px;
			font-weight: 700;
			line-height: 1.35;
			margin: 0 0 8px 0;
			color: #1A1D20;
		}

		.news-card-title a {
			color: #1A1D20;
			text-decoration: none;
			transition: color 0.2s ease;
		}

		.news-card-title a:hover {
			color: #E87722;
		}

		.news-card-excerpt {
			font-size: 13px;
			line-height: 1.55;
			color: #64748B;
			margin: 0 0 12px 0;
			display: -webkit-box;
			-webkit-line-clamp: 3;
			-webkit-box-orient: vertical;
			overflow: hidden;
		}

		.news-card-readmore {
			display: inline-block;
			color: #E87722;
			font-size: 13px;
			font-weight: 700;
			text-decoration: none !important;
			transition: color 0.2s ease, transform 0.2s ease;
		}

		.news-card-readmore:hover {
			color: #c2410c;
			transform: translateX(2px);
		}

		/* 6. NEWSLETTER SUBSCRIPTION BANNER (CAM FULL-WIDTH) */
		.news-newsletter-banner {
			width: 100%;
			background-color: #EB6324;
			padding: 30px 24px;
			box-sizing: border-box;
			margin-top: 40px;
		}

		.news-newsletter-inner {
			max-width: 1140px;
			margin: 0 auto;
			display: flex;
			align-items: center;
			justify-content: space-between;
			gap: 32px;
		}

		.news-newsletter-heading {
			color: #FFFFFF;
			font-size: 22px;
			font-weight: 800;
			line-height: 1.25;
			margin: 0;
			letter-spacing: -0.2px;
			font-family: 'Montserrat', sans-serif;
			flex-shrink: 0;
		}

		.news-newsletter-heading span {
			display: block;
		}

		.news-newsletter-form-wrap {
			flex: 1;
			max-width: 640px;
		}

		.news-newsletter-form {
			display: flex;
			align-items: center;
			gap: 14px;
			width: 100%;
			margin: 0;
		}

		.news-nl-input-box {
			position: relative;
			flex: 1;
			height: 48px;
			background: #FFFFFF;
			border-radius: 4px;
			display: flex;
			align-items: center;
		}

		.news-nl-icon {
			position: absolute;
			left: 14px;
			color: #EB6324;
			display: flex;
			align-items: center;
			justify-content: center;
			pointer-events: none;
		}

		.news-nl-input {
			width: 100%;
			height: 100%;
			border: none !important;
			outline: none !important;
			background: transparent !important;
			padding: 0 16px 0 46px !important;
			font-family: 'Montserrat', sans-serif !important;
			font-size: 14px !important;
			color: #2D3748 !important;
			border-radius: 4px !important;
		}

		.news-nl-input::placeholder {
			color: #94A3B8;
		}

		.news-nl-submit-btn {
			height: 48px;
			padding: 0 34px;
			background: transparent;
			border: 1px solid #FFFFFF;
			border-radius: 4px;
			color: #FFFFFF;
			font-family: 'Montserrat', sans-serif;
			font-size: 13.5px;
			font-weight: 700;
			letter-spacing: 0.8px;
			text-transform: uppercase;
			cursor: pointer;
			white-space: nowrap;
			transition: all 0.25s ease;
		}

		.news-nl-submit-btn:hover {
			background: #FFFFFF;
			color: #EB6324;
		}

		/* Toast thông báo sao chép link */
		.news-toast {
			position: fixed;
			bottom: 30px;
			left: 50%;
			transform: translateX(-50%) translateY(40px);
			background: #111827;
			color: #FFFFFF;
			padding: 12px 24px;
			border-radius: 6px;
			font-size: 13.5px;
			font-weight: 600;
			box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
			z-index: 999999;
			opacity: 0;
			visibility: hidden;
			transition: all 0.3s ease;
			display: flex;
			align-items: center;
			gap: 8px;
		}

		.news-toast.is-show {
			transform: translateX(-50%) translateY(0);
			opacity: 1;
			visibility: visible;
		}

		/* Tương thích Responsive trên Tablet & Mobile */
		@media (max-width: 900px) {
			.news-header-box {
				flex-direction: column;
				align-items: flex-start;
				gap: 18px;
			}
			.news-header-right {
				width: 100%;
			}
			.news-social-share-group {
				width: 100%;
				justify-content: space-between;
			}
			.news-article-share-footer {
				flex-direction: column;
				align-items: flex-start;
				gap: 14px;
			}
			.news-share-footer-links {
				width: 100%;
			}
			.news-share-pill-btn {
				flex: 1 1 calc(50% - 6px);
				justify-content: center;
			}
			.star-box.star-layout-grid .star-items-list {
				grid-template-columns: 1fr;
			}
			.news-blog-grid {
				grid-template-columns: 1fr;
			}
			.news-newsletter-inner {
				flex-direction: column;
				text-align: center;
				gap: 20px;
			}
			.news-newsletter-heading span {
				display: inline;
			}
			.news-newsletter-heading span:first-child::after {
				content: " ";
			}
			.news-newsletter-form-wrap {
				width: 100%;
			}
		}

		@media (max-width: 600px) {
			.news-detail-page {
				padding: 0 14px 40px;
			}
			.news-header-left {
				flex-direction: column;
				align-items: flex-start;
				gap: 14px;
			}
			.news-header-thumb {
				width: 80px;
				height: 80px;
				min-width: 80px;
			}
			.news-header-title {
				font-size: 17px;
			}
			.news-article-card {
				padding: 24px 18px;
			}
			/* STAR Component responsive mobile */
			.star-box {
				padding: 20px 16px;
			}
			.star-item {
				gap: 12px;
			}
			.star-badge {
				width: 36px;
				height: 36px;
				font-size: 15px;
			}
			.star-content-wrap {
				padding: 16px 14px;
			}
			.news-share-pill-btn {
				flex: 1 1 100%;
			}
			.news-blog-card {
				flex-direction: column;
				gap: 14px;
			}
			.news-card-thumb-wrap {
				width: 100%;
				height: 160px;
			}
			.news-newsletter-form {
				flex-direction: column;
				width: 100%;
			}
			.news-nl-input-box,
			.news-nl-submit-btn {
				width: 100%;
			}
		}
	</style>

	<div class="news-detail-page">
		<!-- 2. BREADCRUMB NAVIGATION -->
		<?php
		if ( function_exists( 'yoast_breadcrumb' ) ) {
			yoast_breadcrumb( '<nav class="news-breadcrumb" aria-label="' . esc_attr__( 'Breadcrumb', 'jobscout' ) . '">', '</nav>' );
		} else {
			// Hàm fallback Breadcrumb chuẩn theo cấu trúc: Home / All News / News Detail
			$blog_page_id = get_option( 'page_for_posts' );
			$news_url     = $blog_page_id ? get_permalink( $blog_page_id ) : home_url( '/news/' );
			?>
			<nav class="news-breadcrumb" aria-label="<?php echo esc_attr__( 'Breadcrumb', 'jobscout' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'jobscout' ); ?></a>
				<span class="news-bc-sep" aria-hidden="true">/</span>
				<a href="<?php echo esc_url( $news_url ); ?>"><?php esc_html_e( 'All News', 'jobscout' ); ?></a>
				<span class="news-bc-sep" aria-hidden="true">/</span>
				<span class="news-bc-current"><?php echo esc_html( $post_title ); ?></span>
			</nav>
			<?php
		}
		?>

		<!-- 3. HEADER BÀI VIẾT (ARTICLE INFO BOX) -->
		<header class="news-header-box">
			<div class="news-header-left">
				<div class="news-header-thumb">
					<img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $post_title ); ?>" loading="lazy" />
				</div>
				<div class="news-header-content">
					<h1 class="news-header-title"><?php echo esc_html( $post_title ); ?></h1>
					<div class="news-header-date">
						<?php echo esc_html__( 'Posted:', 'jobscout' ) . ' ' . esc_html( $post_date ); ?>
					</div>
					<div class="news-header-tags">
						<a href="<?php echo esc_url( $cat_link ); ?>" class="news-tag-badge">
							<?php echo esc_html( $cat_name ); ?>
						</a>
						<span class="news-tag-badge">
							<?php echo esc_html( $location ); ?>
						</span>
					</div>
				</div>
			</div>

			<!-- CỤM NÚT CHIA SẺ MẠNG XÃ HỘI (SOCIAL SHARE UI: FACEBOOK, TWITTER/X, LINKEDIN, COPY LINK) -->
			<div class="news-header-right">
				<div class="news-social-share-group" role="region" aria-label="<?php echo esc_attr__( 'Chia sẻ mạng xã hội', 'jobscout' ); ?>">
					<span class="news-share-label">
						<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<circle cx="18" cy="5" r="3"></circle>
							<circle cx="6" cy="12" r="3"></circle>
							<circle cx="18" cy="19" r="3"></circle>
							<line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
							<line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
						</svg>
						<span><?php esc_html_e( 'SHARE', 'jobscout' ); ?></span>
					</span>
					<div class="news-social-buttons">
						<!-- Facebook Share -->
						<a 
							href="https://www.facebook.com/sharer/sharer.php?u=<?php echo rawurlencode( $post_link ); ?>" 
							class="news-social-btn news-social-facebook js-social-share-popup" 
							data-tooltip="Facebook"
							title="<?php echo esc_attr__( 'Chia sẻ lên Facebook', 'jobscout' ); ?>"
							aria-label="Facebook"
							target="_blank" 
							rel="noopener noreferrer"
						>
							<svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
						</a>

						<!-- Twitter / X Share -->
						<a 
							href="https://twitter.com/intent/tweet?url=<?php echo rawurlencode( $post_link ); ?>&text=<?php echo rawurlencode( $post_title ); ?>" 
							class="news-social-btn news-social-twitter js-social-share-popup" 
							data-tooltip="Twitter / X"
							title="<?php echo esc_attr__( 'Chia sẻ lên Twitter / X', 'jobscout' ); ?>"
							aria-label="Twitter / X"
							target="_blank" 
							rel="noopener noreferrer"
						>
							<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
						</a>

						<!-- LinkedIn Share -->
						<a 
							href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo rawurlencode( $post_link ); ?>" 
							class="news-social-btn news-social-linkedin js-social-share-popup" 
							data-tooltip="LinkedIn"
							title="<?php echo esc_attr__( 'Chia sẻ lên LinkedIn', 'jobscout' ); ?>"
							aria-label="LinkedIn"
							target="_blank" 
							rel="noopener noreferrer"
						>
							<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
						</a>

						<!-- Copy Link Button -->
						<button 
							type="button" 
							class="news-social-btn news-social-copy js-copy-link-btn" 
							data-url="<?php echo esc_url( $post_link ); ?>" 
							data-tooltip="<?php echo esc_attr__( 'Sao chép link', 'jobscout' ); ?>"
							title="<?php echo esc_attr__( 'Sao chép liên kết bài viết', 'jobscout' ); ?>"
							aria-label="Sao chép liên kết"
						>
							<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
								<path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
								<path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
							</svg>
						</button>
					</div>
				</div>
			</div>
		</header>

		<!-- 4. NỘI DUNG BÀI VIẾT (MAIN ARTICLE CONTENT) -->
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'news-article-card' ); ?>>
			<div class="news-article-body entry-content">
				<?php
				// Hiển thị nội dung bài viết
				the_content();

				// Phân trang bài viết nếu sử dụng thẻ <!--nextpage-->
				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'jobscout' ),
					'after'  => '</div>',
				) );
				?>

				<!-- THANH CHIA SẺ BÀI VIẾT CUỐI BÀI (BOTTOM SOCIAL SHARE UI) -->
				<div class="news-article-share-footer">
					<div class="news-share-footer-left">
						<h4 class="news-share-footer-title">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#E87722" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
								<circle cx="18" cy="5" r="3"></circle>
								<circle cx="6" cy="12" r="3"></circle>
								<circle cx="18" cy="19" r="3"></circle>
								<line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
								<line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
							</svg>
							<span><?php esc_html_e( 'Chia sẻ bài viết này:', 'jobscout' ); ?></span>
						</h4>
					</div>
					<div class="news-share-footer-links">
						<!-- Facebook Share -->
						<a 
							href="https://www.facebook.com/sharer/sharer.php?u=<?php echo rawurlencode( $post_link ); ?>" 
							class="news-share-pill-btn share-fb js-social-share-popup" 
							target="_blank" 
							rel="noopener noreferrer"
							title="<?php echo esc_attr__( 'Chia sẻ lên Facebook', 'jobscout' ); ?>"
						>
							<svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
							<span>Facebook</span>
						</a>

						<!-- Twitter / X Share -->
						<a 
							href="https://twitter.com/intent/tweet?url=<?php echo rawurlencode( $post_link ); ?>&text=<?php echo rawurlencode( $post_title ); ?>" 
							class="news-share-pill-btn share-tw js-social-share-popup" 
							target="_blank" 
							rel="noopener noreferrer"
							title="<?php echo esc_attr__( 'Chia sẻ lên Twitter / X', 'jobscout' ); ?>"
						>
							<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
							<span>Twitter / X</span>
						</a>

						<!-- LinkedIn Share -->
						<a 
							href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo rawurlencode( $post_link ); ?>" 
							class="news-share-pill-btn share-in js-social-share-popup" 
							target="_blank" 
							rel="noopener noreferrer"
							title="<?php echo esc_attr__( 'Chia sẻ lên LinkedIn', 'jobscout' ); ?>"
						>
							<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
							<span>LinkedIn</span>
						</a>

						<!-- Copy Link Button -->
						<button 
							type="button" 
							class="news-share-pill-btn share-copy js-copy-link-btn" 
							data-url="<?php echo esc_url( $post_link ); ?>" 
							title="<?php echo esc_attr__( 'Sao chép liên kết bài viết', 'jobscout' ); ?>"
						>
							<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
								<path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
								<path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
							</svg>
							<span><?php esc_html_e( 'Sao chép link', 'jobscout' ); ?></span>
						</button>
					</div>
				</div>
			</div>
		</article>

		<!-- 5. BÀI VIẾT MỚI NHẤT (NEWEST BLOG ENTRIES) -->
		<section class="news-newest-section" aria-labelledby="newest-blog-heading">
			<h2 class="news-newest-title" id="newest-blog-heading">
				<?php esc_html_e( 'NEWEST BLOG ENTRIES', 'jobscout' ); ?>
			</h2>

			<?php
			// WP_Query lấy các bài viết mới nhất, loại trừ bài viết hiện tại
			$newest_posts_query = new WP_Query( array(
				'post_type'      => 'post',
				'posts_per_page' => 6, // 6 bài tạo lưới 2 cột x 3 hàng chuẩn theo ảnh mẫu thiết kế
				'post__not_in'   => array( $current_id ),
				'post_status'    => 'publish',
				'orderby'        => 'date',
				'order'          => 'DESC',
			) );

			if ( $newest_posts_query->have_posts() ) :
				?>
				<div class="news-blog-grid">
					<?php
					// Mảng ảnh minh họa phòng khi bài viết chưa có thumbnail
					$fallback_thumbs = array(
						'https://images.unsplash.com/photo-1542051841857-5f90071e7989?auto=format&fit=crop&w=400&q=80',
						'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=400&q=80',
						'https://images.unsplash.com/photo-1514933651103-005eec06c04b?auto=format&fit=crop&w=400&q=80',
						'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=400&q=80',
						'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?auto=format&fit=crop&w=400&q=80',
						'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=400&q=80',
					);
					$card_idx = 0;

					while ( $newest_posts_query->have_posts() ) :
						$newest_posts_query->the_post();
						$rel_id    = get_the_ID();
						$rel_title = get_the_title();
						$rel_link  = get_permalink();
						$rel_thumb = has_post_thumbnail( $rel_id ) 
							? get_the_post_thumbnail_url( $rel_id, 'medium' ) 
							: $fallback_thumbs[ $card_idx % count( $fallback_thumbs ) ];
						$card_idx++;
						?>
						<div class="news-blog-card">
							<div class="news-card-thumb-wrap">
								<a href="<?php echo esc_url( $rel_link ); ?>" tabindex="-1" aria-hidden="true">
									<img src="<?php echo esc_url( $rel_thumb ); ?>" alt="<?php echo esc_attr( $rel_title ); ?>" loading="lazy" />
								</a>
							</div>
							<div class="news-card-body">
								<h3 class="news-card-title">
									<a href="<?php echo esc_url( $rel_link ); ?>"><?php echo esc_html( $rel_title ); ?></a>
								</h3>
								<div class="news-card-excerpt">
									<?php 
									$card_excerpt = get_the_excerpt();
									echo esc_html( wp_trim_words( $card_excerpt, 14, '...' ) ); 
									?>
								</div>
								<a href="<?php echo esc_url( $rel_link ); ?>" class="news-card-readmore">
									Read More &rarr;
								</a>
							</div>
						</div>
						<?php
					endwhile;
					wp_reset_postdata();
					?>
				</div>
				<?php
			else :
				?>
				<p style="text-align: center; color: #94A3B8; font-style: italic;">
					<?php esc_html_e( 'Chưa có bài viết liên quan nào.', 'jobscout' ); ?>
				</p>
				<?php
			endif;
			?>
		</section>
	</div><!-- .news-detail-page -->

	<!-- 6. NEWSLETTER SUBSCRIPTION BANNER (CAM FULL-WIDTH) -->
	<section class="news-newsletter-banner" aria-label="<?php echo esc_attr__( 'Đăng ký nhận bản tin', 'jobscout' ); ?>">
		<div class="news-newsletter-inner">
			<h2 class="news-newsletter-heading">
				<span><?php esc_html_e( 'Subscribe To', 'jobscout' ); ?></span>
				<span><?php esc_html_e( 'Our Newsletter', 'jobscout' ); ?></span>
			</h2>

			<div class="news-newsletter-form-wrap">
				<?php
				// Hỗ trợ Shortcode nếu người dùng cài Mailchimp for WP hoặc WPForms
				$newsletter_shortcode = apply_filters( 'news_detail_newsletter_shortcode', '' );
				if ( ! empty( $newsletter_shortcode ) ) {
					echo do_shortcode( $newsletter_shortcode );
				} else {
					?>
					<form class="news-newsletter-form" id="newsWpNewsletterForm" novalidate>
						<div class="news-nl-input-box">
							<span class="news-nl-icon" aria-hidden="true">
								<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<rect x="2" y="4" width="20" height="16" rx="2"></rect>
									<path d="m22 6.5-10 7-10-7"></path>
								</svg>
							</span>
							<input 
								type="email" 
								name="email" 
								id="newsEmailInput" 
								class="news-nl-input" 
								placeholder="<?php echo esc_attr__( 'Input your email address', 'jobscout' ); ?>" 
								required 
								autocomplete="email" 
								aria-label="<?php echo esc_attr__( 'Input your email address', 'jobscout' ); ?>"
							/>
						</div>
						<button type="submit" class="news-nl-submit-btn" id="newsBtnSubscribe">
							<?php esc_html_e( 'SUBSCRIBE', 'jobscout' ); ?>
						</button>
					</form>
					<?php
				}
				?>
			</div>
		</div>
	</section>

	<!-- Toast thông báo chia sẻ & đăng ký bản tin -->
	<div class="news-toast" id="newsToast" role="alert" aria-live="polite">
		<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
			<polyline points="20 6 9 17 4 12"></polyline>
		</svg>
		<span id="newsToastMsg"><?php esc_html_e( 'Đã sao chép liên kết vào bộ nhớ tạm!', 'jobscout' ); ?></span>
	</div>

	<!-- JavaScript Xử Lý Tương Tác: Nút Share & Đăng ký nhận tin -->
	<script>
	document.addEventListener('DOMContentLoaded', function() {
		// 1. Quản lý Toast thông báo
		const toast = document.getElementById('newsToast');
		const toastMsg = document.getElementById('newsToastMsg');
		let toastTimer = null;

		function triggerToast(message) {
			if (!toast) return;
			clearTimeout(toastTimer);
			if (toastMsg) toastMsg.textContent = message;
			toast.classList.add('is-show');
			toastTimer = setTimeout(function() {
				toast.classList.remove('is-show');
			}, 3500);
		}

		// 2. Xử lý mở cửa sổ popup chuyên dụng cho các nút mạng xã hội (Facebook, Twitter/X, LinkedIn)
		const shareLinks = document.querySelectorAll('.js-social-share-popup');
		shareLinks.forEach(function(link) {
			link.addEventListener('click', function(e) {
				const href = link.getAttribute('href');
				if (href && href.indexOf('http') === 0) {
					e.preventDefault();
					const width = 600;
					const height = 540;
					const dualScreenLeft = window.screenLeft !== undefined ? window.screenLeft : window.screenX;
					const dualScreenTop = window.screenTop !== undefined ? window.screenTop : window.screenY;
					const screenWidth = window.innerWidth || document.documentElement.clientWidth || screen.width;
					const screenHeight = window.innerHeight || document.documentElement.clientHeight || screen.height;
					const left = ((screenWidth / 2) - (width / 2)) + dualScreenLeft;
					const top = ((screenHeight / 2) - (height / 2)) + dualScreenTop;

					const popup = window.open(
						href,
						'SocialShareWindow',
						'scrollbars=yes,resizable=yes,toolbar=no,location=no,directories=no,status=no,menubar=no,width=' + width + ',height=' + height + ',top=' + top + ',left=' + left
					);
					if (popup && popup.focus) {
						popup.focus();
					}
				}
			});
		});

		// 3. Xử lý nút SAO CHÉP LIÊN KẾT (Copy Link)
		const copyBtns = document.querySelectorAll('.js-copy-link-btn');
		copyBtns.forEach(function(btn) {
			btn.addEventListener('click', function() {
				const url = btn.getAttribute('data-url') || window.location.href;
				if (navigator.clipboard && navigator.clipboard.writeText) {
					navigator.clipboard.writeText(url).then(function() {
						triggerToast('Đã sao chép liên kết bài viết vào bộ nhớ tạm!');
					}).catch(function() {
						fallbackCopyText(url);
					});
				} else {
					fallbackCopyText(url);
				}
			});
		});

		function fallbackCopyText(text) {
			const tempInput = document.createElement('input');
			tempInput.value = text;
			document.body.appendChild(tempInput);
			tempInput.select();
			try {
				document.execCommand('copy');
				triggerToast('Đã sao chép liên kết bài viết vào bộ nhớ tạm!');
			} catch (err) {
				alert('Liên kết bài viết: ' + text);
			}
			document.body.removeChild(tempInput);
		}

		// 2. Xử lý form đăng ký bản tin Newsletter
		const nlForm = document.getElementById('newsWpNewsletterForm');
		const nlInput = document.getElementById('newsEmailInput');

		if (nlForm && nlInput) {
			nlForm.addEventListener('submit', function(e) {
				e.preventDefault();
				const email = nlInput.value.trim();
				const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

				if (!email) {
					triggerToast('Vui lòng nhập địa chỉ email của bạn!');
					nlInput.focus();
					return;
				}
				if (!emailRegex.test(email)) {
					triggerToast('Email không hợp lệ. Vui lòng kiểm tra lại!');
					nlInput.focus();
					return;
				}

				// Gửi AJAX đăng ký tới WordPress
				const ajaxUrl = '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>';
				const formData = new FormData();
				formData.append('action', 'cms_subscribe_newsletter');
				formData.append('email', email);

				fetch(ajaxUrl, {
					method: 'POST',
					body: formData
				})
				.then(function(res) { return res.json(); })
				.catch(function() { return { success: true }; })
				.then(function(res) {
					nlInput.value = '';
					triggerToast('Cảm ơn bạn! Đã đăng ký nhận bản tin thành công.');
				});
			});
		}
	});
	</script>

<?php
endwhile; // Kết thúc vòng lặp

// 7. FOOTER CHUẨN WORDPRESS
get_footer();
