<?php
/**
 * Blog Section (NEWEST BLOG ENTRIES MODULE)
 * Thiết kế theo mẫu:
 * - Tiêu đề căn giữa: "NEWEST BLOG ENTRIES".
 * - Lưới 2 cột x 2 hàng = 4 thẻ bài viết mới nhất (chỉ lấy bài có ảnh đại diện).
 * - Mỗi thẻ nền trắng: ảnh vuông bên trái, bên phải là tiêu đề, đoạn tóm tắt ngắn, link "Read More" màu cam.
 *
 * Tiêu đề / mô tả / nút xem tất cả chỉnh trong: Giao diện → Tùy biến → Front Page Settings → Blog Section.
 *
 * @package JobScout
 */

$blog_heading   = get_theme_mod( 'blog_section_title', 'BÀI VIẾT MỚI NHẤT' );
$sub_title      = get_theme_mod( 'blog_section_subtitle', '' );
$blog           = get_option( 'page_for_posts' );
$label          = get_theme_mod( 'blog_view_all', 'Xem tất cả bài viết' );
$ed_blog        = get_theme_mod( 'ed_blog_section', get_theme_mod( 'ed_blog', true ) );
$read_more_text = get_theme_mod( 'blog_read_more_text', 'Xem thêm' );
$source         = get_theme_mod( 'blog_posts_source', 'auto' );
$qry            = null;

// 1. Chế độ thủ công: Lấy theo 4 ID bài viết do người dùng chọn trong Customizer
if ( 'manual' === $source ) {
    $manual_ids = array();
    for ( $i = 1; $i <= 4; $i++ ) {
        $pid = absint( get_theme_mod( "blog_post_id_{$i}", 0 ) );
        if ( $pid > 0 && 'publish' === get_post_status( $pid ) ) {
            $manual_ids[] = $pid;
        }
    }

    if ( ! empty( $manual_ids ) ) {
        $args = array(
            'post_type'           => 'post',
            'post__in'            => $manual_ids,
            'orderby'             => 'post__in',
            'posts_per_page'      => count( $manual_ids ),
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true,
        );
        $qry = new WP_Query( $args );
    }
}

// 2. Chế độ tự động: Ưu tiên các bài viết đánh dấu Nổi bật (_featured_home_blog = 1) và có ảnh
if ( empty( $qry ) || ! $qry->have_posts() ) {
    $featured_posts = get_posts( array(
        'post_type'      => 'post',
        'post_status'    => array( 'publish' ),
        'posts_per_page' => 4,
        'meta_key'       => '_thumbnail_id',
        'meta_query'     => array(
            array(
                'key'     => '_featured_home_blog',
                'value'   => '1',
                'compare' => '=',
            ),
        ),
    ) );

    $chosen_ids = ! empty( $featured_posts ) ? wp_list_pluck( $featured_posts, 'ID' ) : array();

    if ( count( $chosen_ids ) < 4 ) {
        $needed = 4 - count( $chosen_ids );
        $recent_posts = get_posts( array(
            'post_type'      => 'post',
            'post_status'    => array( 'publish' ),
            'posts_per_page' => $needed,
            'post__not_in'   => $chosen_ids,
            'meta_key'       => '_thumbnail_id',
            'orderby'        => 'date',
            'order'          => 'DESC',
        ) );
        if ( ! empty( $recent_posts ) ) {
            $chosen_ids = array_merge( $chosen_ids, wp_list_pluck( $recent_posts, 'ID' ) );
        }
    }

    if ( ! empty( $chosen_ids ) ) {
        $qry = new WP_Query( array(
            'post_type'      => 'post',
            'post__in'       => $chosen_ids,
            'orderby'        => 'post__in',
            'posts_per_page' => count( $chosen_ids ),
            'post_status'    => array( 'publish' ),
        ) );
    }

    // Fallback nếu không có post nào có thumbnail
    if ( empty( $qry ) || ! $qry->have_posts() ) {
        $args = array(
            'post_type'           => 'post',
            'post_status'         => 'publish',
            'posts_per_page'      => 4,
            'ignore_sticky_posts' => true,
            'orderby'             => 'date',
            'order'               => 'DESC',
        );
        $qry = new WP_Query( $args );
    }
}

if ( $ed_blog && ( $blog_heading || $sub_title || ( $qry && $qry->have_posts() ) ) ) { ?>

<!-- ========================================================
     CSS CHO MODULE NEWEST BLOG ENTRIES
     ======================================================== -->
<style id="newest-blog-module-styles">
  #blog-section.newest-blog-module-section {
    width: 100% !important;
    background-color: #F2F2F2 !important;
    padding: 50px 0 60px !important;
    font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
    box-sizing: border-box !important;
  }

  .newest-blog-module-section * {
    box-sizing: border-box !important;
  }

  .newest-blog-module-section .newest-blog-container {
    max-width: 960px !important;
    margin: 0 auto !important;
    padding: 0 24px !important;
  }

  .newest-blog-module-section .newest-blog-heading-wrap {
    text-align: center !important;
    margin: 0 0 34px 0 !important;
  }

  .newest-blog-module-section .newest-blog-heading {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 30px !important;
    font-weight: 700 !important;
    color: #1E2022 !important;
    text-transform: uppercase !important;
    text-align: center !important;
    letter-spacing: 0.4px !important;
    line-height: 1.2 !important;
    margin: 0 !important;
    display: inline-block !important;
    position: relative !important;
  }

  /* Customizer shortcut pencil buttons */
  .newest-blog-module-section .customize-partial-edit-shortcut {
    z-index: 99999 !important;
  }
  .newest-blog-module-section .customize-partial-edit-shortcut button {
    z-index: 99999 !important;
    pointer-events: auto !important;
  }
  .newest-blog-module-section .newest-blog-heading .customize-partial-edit-shortcut button {
    left: -38px !important;
    top: 0 !important;
  }
  .newest-blog-module-section .newest-blog-desc {
    text-align: center !important;
    color: #64748B !important;
    font-size: 14px !important;
    margin: -20px auto 34px !important;
    max-width: 640px !important;
    position: relative !important;
  }
  .newest-blog-module-section .newest-blog-desc .customize-partial-edit-shortcut button {
    left: -38px !important;
    top: 0 !important;
  }
  .newest-blog-module-section .newest-blog-btn {
    position: relative !important;
  }
  .newest-blog-module-section .newest-blog-btn .customize-partial-edit-shortcut button {
    left: auto !important;
    right: -12px !important;
    top: -12px !important;
  }

  .newest-blog-module-section .newest-blog-desc p {
    margin: 0 !important;
  }

  /* Lưới 2 cột */
  .newest-blog-module-section .newest-blog-grid {
    display: grid !important;
    grid-template-columns: repeat(2, 1fr) !important;
    column-gap: 22px !important;
    row-gap: 42px !important;
  }

  /* Thẻ bài viết */
  .newest-blog-module-section .newest-blog-card {
    display: flex !important;
    align-items: center !important;
    gap: 20px !important;
    background: #FFFFFF !important;
    padding: 14px 18px 14px 14px !important;
    border-radius: 2px !important;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04) !important;
    transition: box-shadow 0.25s ease, transform 0.25s ease !important;
    margin: 0 !important;
  }

  .newest-blog-module-section .newest-blog-card:hover {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.09) !important;
    transform: translateY(-2px) !important;
  }

  /* Ảnh vuông bên trái */
  .newest-blog-module-section .newest-blog-thumb {
    flex: 0 0 150px !important;
    width: 150px !important;
    height: 150px !important;
    overflow: hidden !important;
    display: block !important;
    margin: 0 !important;
  }

  .newest-blog-module-section .newest-blog-thumb img,
  .newest-blog-module-section .newest-blog-thumb svg {
    width: 100% !important;
    height: 100% !important;
    object-fit: cover !important;
    display: block !important;
    transition: transform 0.4s ease !important;
  }

  .newest-blog-module-section .newest-blog-card:hover .newest-blog-thumb img {
    transform: scale(1.05) !important;
  }

  .newest-blog-module-section .newest-blog-body {
    flex: 1 1 auto !important;
    min-width: 0 !important;
    text-align: left !important;
  }

  .newest-blog-module-section .newest-blog-title {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 15px !important;
    font-weight: 700 !important;
    line-height: 1.5 !important;
    color: #1E2022 !important;
    margin: 0 0 10px 0 !important;
    display: -webkit-box !important;
    -webkit-line-clamp: 3 !important;
    -webkit-box-orient: vertical !important;
    overflow: hidden !important;
  }

  .newest-blog-module-section .newest-blog-title a {
    color: inherit !important;
    text-decoration: none !important;
    transition: color 0.2s ease !important;
  }

  .newest-blog-module-section .newest-blog-title a:hover {
    color: #EB6324 !important;
  }

  .newest-blog-module-section .newest-blog-excerpt {
    font-size: 12.5px !important;
    line-height: 1.6 !important;
    color: #333B44 !important;
    margin: 0 0 10px 0 !important;
  }

  .newest-blog-module-section .newest-blog-readmore {
    font-size: 12.5px !important;
    font-weight: 600 !important;
    color: #EB6324 !important;
    text-decoration: none !important;
    transition: color 0.2s ease, letter-spacing 0.2s ease !important;
  }

  .newest-blog-module-section .newest-blog-readmore:hover {
    color: #C94F15 !important;
    letter-spacing: 0.3px !important;
  }

  /* Nút xem tất cả (chỉ hiện khi có nhập chữ trong Tùy biến) */
  .newest-blog-module-section .newest-blog-footer {
    text-align: center !important;
    margin-top: 42px !important;
  }

  .newest-blog-module-section .newest-blog-btn {
    display: inline-block !important;
    background: #FFFFFF !important;
    border: 1px solid #EB6324 !important;
    color: #EB6324 !important;
    font-size: 13px !important;
    font-weight: 700 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.6px !important;
    padding: 12px 34px !important;
    border-radius: 2px !important;
    text-decoration: none !important;
    transition: all 0.25s ease !important;
  }

  .newest-blog-module-section .newest-blog-btn:hover {
    background: #EB6324 !important;
    color: #FFFFFF !important;
  }

  /* Responsive */
  @media (max-width: 860px) {
    .newest-blog-module-section .newest-blog-grid {
      grid-template-columns: 1fr !important;
      row-gap: 18px !important;
    }
    .newest-blog-module-section .newest-blog-heading {
      font-size: 26px !important;
      margin-bottom: 26px !important;
    }
  }

  @media (max-width: 480px) {
    .newest-blog-module-section .newest-blog-card {
      flex-direction: column !important;
      align-items: flex-start !important;
      padding: 14px !important;
    }
    .newest-blog-module-section .newest-blog-thumb {
      flex-basis: auto !important;
      width: 100% !important;
      height: 190px !important;
    }
  }
</style>

<!-- ========================================================
     HTML MARKUP CHO MODULE NEWEST BLOG ENTRIES
     ======================================================== -->
<section id="blog-section" class="article-section newest-blog-module-section">
  <div class="newest-blog-container">
    <?php
      if ( $blog_heading ) echo '<div class="newest-blog-heading-wrap"><h2 class="newest-blog-heading">' . esc_html( $blog_heading ) . '</h2></div>';
      if ( $sub_title ) echo '<div class="newest-blog-desc">' . wpautop( wp_kses_post( $sub_title ) ) . '</div>';
    ?>

    <?php if ( $qry->have_posts() ) { ?>
      <div class="newest-blog-grid">
        <?php
        while ( $qry->have_posts() ) {
            $qry->the_post(); ?>
            <article class="newest-blog-card">
              <a href="<?php the_permalink(); ?>" class="newest-blog-thumb" aria-hidden="true" tabindex="-1">
                <?php
                  if ( has_post_thumbnail() ) {
                      the_post_thumbnail( 'medium', array( 'itemprop' => 'image', 'loading' => 'lazy' ) );
                  } else {
                      jobscout_fallback_svg_image( 'thumbnail' );
                  }
                ?>
              </a>
              <div class="newest-blog-body">
                <h3 class="newest-blog-title">
                  <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h3>
                <p class="newest-blog-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18, '...' ) ); ?></p>
                <a href="<?php the_permalink(); ?>" class="newest-blog-readmore"><?php echo esc_html( $read_more_text ); ?></a>
              </div>
            </article>
            <?php
        }
        wp_reset_postdata();
        ?>
      </div><!-- .newest-blog-grid -->

      <?php if ( $label ) { 
        $blog_target_url = ! empty( $blog ) ? get_permalink( $blog ) : home_url( '/blog/' );
      ?>
        <div class="newest-blog-footer">
          <a href="<?php echo esc_url( $blog_target_url ); ?>" class="newest-blog-btn"><?php echo esc_html( $label ); ?></a>
        </div>
      <?php } ?>
    <?php } ?>
  </div>
</section>
<?php
}
