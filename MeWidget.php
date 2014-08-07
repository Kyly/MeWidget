<?php
/*
Plugin Name: Me Widget
Plugin URI: https://github.com/Kyly/MeWidget
Description: Show info about me!
Version: 1.0
Author: Kyly Vass
License: GPLv2
*/

/*
Copyright (C) 2014  Kyly G. Vass

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details (http://www.gnu.org/licenses/).

*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

define( 'ME_WIDGET_VERSION', '1.0.0' );
define( 'ME_WIDGET__MINIMUM_WP_VERSION', '3.0' );
define( 'ME_WIDGET__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'ME_WIDGET__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once( ME_WIDGET__PLUGIN_DIR . 'class.MeWidget.php' );
require_once( ME_WIDGET__PLUGIN_DIR . 'mewidget_functions.php');

add_action( 'admin_enqueue_scripts', 'load_me_widget_admin_styles', 99 );
add_action('widgets_init', create_function('', 'return register_widget("MeWidget");'));
