<?php
/**
 * Script to fix all Vietnamese menu items, pages, posts, and terms corrupted by CP437 mojibake.
 */
require_once __DIR__ . '/wp-load.php';

global $wpdb;

// 1. Explicit Post ID to Title Mapping
$post_title_fixes = array(
    // Main Pages & Menu Items
    987  => 'Trang chủ',
    1072 => 'Trang chủ',
    986  => 'Việc làm',
    993  => 'Việc làm',
    1073 => 'Việc làm',
    1074 => 'Việc làm',
    8    => 'Đăng tin tuyển dụng',
    991  => 'Đăng tin tuyển dụng',
    1070 => 'Đăng tin tuyển dụng',
    1079 => 'Đăng tin tuyển dụng',
    9    => 'Quản lý tin tuyển dụng',
    992  => 'Quản lý tin tuyển dụng',
    1071 => 'Quản lý tin tuyển dụng',
    1080 => 'Quản lý tin tuyển dụng',
    984  => 'Liên hệ',
    133  => 'Liên hệ',
    1076 => 'Liên hệ',
    1009 => 'Tin tức & Bài viết',
    1012 => 'Tin tức & Bài viết',
    1077 => 'Tin tức & Bài viết',
    1078 => 'Tin tức & Bài viết',
    985  => 'Hồ sơ năng lực',
    1075 => 'Hồ sơ năng lực',
    3    => 'Chính sách bảo mật',
    954  => 'Chính sách bảo mật',
    1035 => 'Chính sách bảo mật',
    1088 => 'Chính sách bảo mật',
    1089 => 'Chính sách bảo mật',
    1090 => 'Chính sách bảo mật',
    1030 => 'Cửa hàng',
    917  => 'Cửa hàng',
    1081 => 'Cửa hàng',
    1031 => 'Giỏ hàng',
    1082 => 'Giỏ hàng',
    1032 => 'Thanh toán',
    1083 => 'Thanh toán',
    1033 => 'Tài khoản của tôi',
    1084 => 'Tài khoản của tôi',
    1034 => 'Đăng nhập & Đăng ký',
    1085 => 'Đăng nhập & Đăng ký',
    140  => 'Trang chiều rộng đầy đủ',
    1006 => 'Toàn chiều rộng',
    1087 => 'Trang chiều rộng đầy đủ',
    149  => 'Hướng dẫn giao diện',
    1005 => 'Hướng dẫn',
    1086 => 'Hướng dẫn giao diện',
    1004 => 'Sản phẩm',
    2    => 'Trang mẫu',
    983  => 'Trang mẫu',
    988  => 'Trang mẫu',
    1091 => 'Trang mẫu',
    1092 => 'Trang mẫu',
    1093 => 'Trang mẫu',
    1069 => 'Danh sách việc làm',
    1    => 'Chào mừng bạn đến với hệ thống tuyển dụng CMS!',
    1094 => 'Chào mừng bạn đến với hệ thống tuyển dụng CMS!',
    100  => 'Hướng dẫn tạo CV và nộp hồ sơ xin việc hiệu quả',
    1095 => 'Hướng dẫn tạo CV và nộp hồ sơ xin việc hiệu quả',
    103  => 'Bí quyết phỏng vấn chinh phục nhà tuyển dụng',
    1096 => 'Bí quyết phỏng vấn chinh phục nhà tuyển dụng',
    116  => 'Bí quyết viết CV và phỏng vấn tìm việc thành công',
    1019 => 'Bí quyết viết CV và phỏng vấn tìm việc thành công',
    1036 => 'Bí quyết viết CV và phỏng vấn tìm việc thành công',
    1097 => 'Bí quyết viết CV và phỏng vấn tìm việc thành công',
    1098 => 'Bí quyết viết CV và phỏng vấn tìm việc thành công',
    1099 => 'Bí quyết viết CV và phỏng vấn tìm việc thành công',
    1109 => 'Shopee Việt Nam Logo',
    1110 => 'Ví MoMo Logo',
    1161 => 'Nhân viên',
    1166 => 'Nhân viên',
    1167 => 'Nhân viên',
    1172 => 'Trưởng phòng',
    1173 => 'Trưởng phòng',
    1174 => 'Bảo vệ',
    1175 => 'Bảo vệ'
);

foreach ( $post_title_fixes as $pid => $title ) {
    $wpdb->update(
        $wpdb->posts,
        array( 'post_title' => $title ),
        array( 'ID' => $pid )
    );
}

// 2. Term Name Fixes
$term_name_fixes = array(
    1  => 'Chưa phân loại',
    4  => 'Bán thời gian',
    5  => 'Tạm thời',
    6  => 'Tự do (Freelance)',
    7  => 'Thực tập',
    8  => 'Lễ hội',
    9  => 'Khách sạn',
    10 => 'Nhà hàng',
    11 => 'Spa & Làm đẹp',
    12 => 'Du lịch',
    30 => 'Công nghệ'
);

foreach ( $term_name_fixes as $tid => $name ) {
    $wpdb->update(
        $wpdb->terms,
        array( 'name' => $name ),
        array( 'term_id' => $tid )
    );
}

// 3. Clean any remaining general post_title mojibake string replacements
$replacements = array(
    'Trang chß╗º' => 'Trang chủ',
    'Viß╗çc l├ám'  => 'Việc làm',
    'Li├¬n hß╗ç'  => 'Liên hệ',
    'Tin tß╗⌐c'   => 'Tin tức',
    'B├ái viß║┐t' => 'Bài viết',
    '─É─âng tin tuyß╗ân dß╗Ñng' => 'Đăng tin tuyển dụng',
    'Quß║ún l├╜ tin tuyß╗ân dß╗Ñng' => 'Quản lý tin tuyển dụng',
    'Quß║ún l├╜' => 'Quản lý',
    'tuyß╗ân dß╗Ñng' => 'tuyển dụng',
    'Ch├¡nh s├ích bß║úo mß║¡t' => 'Chính sách bảo mật',
    'Hß╗ô s╞í n─âng lß╗▒c' => 'Hồ sơ năng lực',
    'Cß╗¡a h├áng' => 'Cửa hàng',
    'Giß╗Å h├áng' => 'Giỏ hàng',
    'Thanh to├ín' => 'Thanh toán',
    'T├ái khoß║ún' => 'Tài khoản',
    '─É─âng nhß║¡p' => 'Đăng nhập',
    '─É─âng k├╜' => 'Đăng ký',
    'Sß║ún phß║⌐m' => 'Sản phẩm',
    'H╞░ß╗¢ng dß║½n' => 'Hướng dẫn'
);

foreach ( $replacements as $bad => $good ) {
    $wpdb->query( $wpdb->prepare(
        "UPDATE {$wpdb->posts} SET post_title = REPLACE(post_title, %s, %s) WHERE post_title LIKE %s",
        $bad, $good, '%' . $wpdb->esc_like( $bad ) . '%'
    ) );
    $wpdb->query( $wpdb->prepare(
        "UPDATE {$wpdb->posts} SET post_content = REPLACE(post_content, %s, %s) WHERE post_content LIKE %s",
        $bad, $good, '%' . $wpdb->esc_like( $bad ) . '%'
    ) );
}

// Clear all WordPress caches
wp_cache_flush();

echo "SUCCESS: Navigation menu, pages, posts, and terms fixed with clean Vietnamese!\n";
