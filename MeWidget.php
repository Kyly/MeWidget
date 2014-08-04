<?php
/*
Plugin Name: Me Widget
Plugin URI: http://kylyv.com
Description: Show info about me!
Version: 1.0
Author: Kyly Vass
License: none
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

define( 'ME_WIDGET_VERSION', '0.0.1' );
define( 'ME_WIDGET__MINIMUM_WP_VERSION', '3.0' );
define( 'ME_WIDGET__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'ME_WIDGET__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

register_activation_hook( __FILE__, array( 'MeWidget', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'MeWidget', 'plugin_deactivation' ) );

require_once( ME_WIDGET__PLUGIN_DIR . 'class.MeWidget.php' );
require_once( ME_WIDGET__PLUGIN_DIR . 'mewidget_functions.php');

add_action( 'admin_enqueue_scripts', 'load_font_awesome_admin_style', 99 );
add_action('widgets_init', create_function('', 'return register_widget("MeWidget");'));
