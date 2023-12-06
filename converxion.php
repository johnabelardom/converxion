<?php
/*
Plugin Name: Converxion
Description: Basic split testing and conversion tracking plugin.
Version: 1.0
Author: John Abelardo Manangan II
Author URI: https://github.com/johnabelardom
*/

if (!defined('ABSPATH')) exit; // Exit if accessed directly

$GLOBALS['converxion'] = [
    'control' => "Control - Manangan",
    'experiment' => "Experiment A - Manangan"
];

// Activation Hook: Create Pages and Set up Database
register_activation_hook(__FILE__, 'converxion_activate');
function converxion_activate() {
    include 'includes/activation.php';
}

// Enqueue JavaScript for Conversion Tracking
add_action('wp_enqueue_scripts', 'converxion_enqueue_scripts');
function converxion_enqueue_scripts() {
    global $wp_query;

    wp_enqueue_script('converxion', plugin_dir_url(__FILE__) . 'assets/public/converxion.js', array('jquery'), null, true);
    wp_localize_script('converxion', 'converxionParams', array(
        'postID' => $wp_query->post->ID
    ));
}


// Traffic Distribution
add_action('template_redirect', 'converxion_redirect');
function converxion_redirect() {
    $is_control_page = is_page($GLOBALS['converxion']['control']);
    $is_experiment_page = is_page($GLOBALS['converxion']['experiment']);

    // Redirect logic for the Control page
    if ($is_control_page && rand(0, 1) === 1) {
        wp_redirect(home_url('/' . sanitize_title($GLOBALS['converxion']['experiment'])));
        exit;
    }
}

add_action('admin_menu', 'register_dashboard_menu_page');
function register_dashboard_menu_page(){
    add_menu_page(
        __('Converxion Reports', 'converxion'),
        'Converxion Reports',
        'manage_options',
        'converxion-reports',
        'converxion_reports_page',
        'dashicons-chart-area',
        6
    );
}

function converxion_reports_page(){
    require 'includes/Converxion_List_Table.php';
    $converxionListTable = new Converxion_List_Table();
    $converxionListTable->prepare_items();

    echo '<div class="wrap"><h2>Converxion Reports</h2>';
    $converxionListTable->display();
    echo '</div>';
}


require 'includes/actions/link-click.php';
require 'includes/actions/page-visit.php';