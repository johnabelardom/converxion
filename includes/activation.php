<?php

global $wpdb;
$table_name = $wpdb->prefix . 'converxion';

// SQL to create your table
$sql = "CREATE TABLE $table_name (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    page_id mediumint(9) NOT NULL,
    visits bigint(20) NOT NULL,
    conversions bigint(20) NOT NULL,
    unique_conversions bigint(20) NOT NULL,
    PRIMARY KEY  (id)
);";

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($sql);

// Create 'Control' and 'Experiment A' pages
if (! get_page_by_path($GLOBALS['converxion']['control'], OBJECT, 'page')) {
    wp_insert_post([
        'post_title'    => $GLOBALS['converxion']['control'],
        'post_status'   => 'publish',
        'post_type'     => 'page',
        'post_content'  => '<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="http://google.com" target="_blank" rel="noreferrer noopener">Conversion</a></div>
<!-- /wp:button -->'
    ]);
}

// Create 'Experiment A' page
if (! get_page_by_path($GLOBALS['converxion']['experiment'], OBJECT, 'page')) {
    wp_insert_post([
        'post_title'    => $GLOBALS['converxion']['experiment'],
        'post_status'   => 'publish',
        'post_type'     => 'page',
        'post_content'  => '<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="http://google.com" target="_blank" rel="noreferrer noopener">Conversion</a></div>
<!-- /wp:button -->'
    ]);
}