<?php


add_action('wp_ajax_converxion_record_visit', 'handle_visits');
add_action('wp_ajax_nopriv_converxion_record_visit', 'handle_visits');
function handle_visits() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'converxion';
    $page_id = $_POST['page_id'];

    if (empty($page_id)) {
        wp_send_json_error();
        die;
    }

    $page_record = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE page_id = %d", $page_id));

    if ($page_record) {
        // Update existing record
        $wpdb->update(
            $table_name,
            array('visits' => $page_record->visits + 1),
            array('page_id' => $page_id)
        );
    } else {
        // Insert new record
        $wpdb->insert(
            $table_name,
            array(
                'page_id' => $page_id,
                'visits' => 1,
                'conversions' => 0,
                'unique_conversions' => 0
            )
        );
    }

    wp_send_json_success();
    die;
}