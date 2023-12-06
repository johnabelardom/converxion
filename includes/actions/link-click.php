<?php


// AJAX Handler for Conversion Tracking
add_action('wp_ajax_converxion_record_conversion', 'handle_conversion');
add_action('wp_ajax_nopriv_converxion_record_conversion', 'handle_conversion');
function handle_conversion() {
    global $wpdb;

    // Get the page ID from the AJAX request
    $page_id = isset($_POST['page_id']) ? intval($_POST['page_id']) : 0;

    // Validate the page ID
    if ($page_id <= 0) {
        wp_send_json_error('Invalid Page ID');
        exit;
    }

    $table_name = $wpdb->prefix . 'converxion';

    // Check if the page record exists
    $page_record = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE page_id = %d", $page_id));

    error_log(gettype($_POST['is_unique']));

    if ($page_record) {
        // Update existing record
        $wpdb->update(
            $table_name,
            array(
                'conversions' => $page_record->conversions + 1,
                'unique_conversions' => $_POST['is_unique'] == 'true' ? $page_record->unique_conversions +1 : $page_record->unique_conversions,
            ),
            array('page_id' => $page_id),
            array('%d'),
            array('%d')
        );
    } else {
        // Insert new record
        $wpdb->insert(
            $table_name,
            array(
                'page_id' => $page_id,
                'visits' => 1,
                'conversions' => 1,
                'unique_conversions' => 1
            ),
            array('%d', '%d', '%d', '%d')
        );
    }

    wp_send_json_success('Conversion recorded');
    exit;
}

