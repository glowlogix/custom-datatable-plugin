<?php
/*
Plugin Name: Glowlogix Datatables
Description: A simple WordPress plugin template
Version: 1.0
Author: GlowLogix
Author URI: https://www.glowlogix.com/
License: GPL2
*/
/*
Copyright 2018    (email : masood@glowlogix.com)

*/
if ( !defined('DATATABLE_BASE_URL') )
    define('DATATABLE_BASE_URL', plugin_dir_path( __FILE__ ));

require DATATABLE_BASE_URL . 'includes/activation.php';
require DATATABLE_BASE_URL . 'includes/shortcode.php';
//require DATATABLE_BASE_URL . 'css/form.css';
register_activation_hook(__FILE__,"create_agri_table");
register_deactivation_hook( __FILE__, 'my_plugin_remove_tables' );
?>
<?php

add_action('admin_print_styles', 'custom_css');
function custom_css() {
    wp_enqueue_style('agri-style',plugin_dir_url( __FILE__ ) . 'css/form1.css',false);
}
?>
