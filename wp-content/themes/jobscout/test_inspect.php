<?php
require_once dirname( __DIR__, 3 ) . '/wp-load.php';
$pages = get_posts(['post_type' => 'page', 'numberposts' => -1, 'post_status' => 'any']);
foreach ($pages as $p) {
    echo $p->ID . " | " . $p->post_title . " | " . $p->post_name . " | template: " . get_post_meta($p->ID, '_wp_page_template', true) . PHP_EOL;
}

