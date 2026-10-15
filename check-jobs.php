<?php
$cats = get_terms( array( 'taxonomy' => 'job_listing_category', 'hide_empty' => false ) );
foreach ( $cats as $c ) {
    $jobs = get_posts( array(
        'post_type'      => 'job_listing',
        'posts_per_page' => -1,
        'tax_query'      => array( array(
            'taxonomy' => 'job_listing_category',
            'field'    => 'term_id',
            'terms'    => $c->term_id,
        ) )
    ) );
    echo $c->name . ' (' . $c->slug . '): ' . count( $jobs ) . " jobs\n";
    foreach ( $jobs as $j ) {
        echo '  - [' . $j->ID . '] ' . $j->post_title . "\n";
    }
}
