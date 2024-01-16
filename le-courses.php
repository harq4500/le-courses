<?php
/**
 * @package le-courses
 */
/*
Plugin Name: LE Courses
Plugin URI:  
Description: 
Version: 1.0
Author: Anton Balyan
Author URI: 
License: GPLv2 or later
Text Domain: le-courses
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

define( 'LE_VERSION', '1.0' );
define( 'LE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'LE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );


require_once( LE_PLUGIN_DIR . '/includes/functions.php' );
require_once( LE_PLUGIN_DIR . '/classes/db.class.php' );
require_once( LE_PLUGIN_DIR . '/classes/counter.class.php' );
require_once( LE_PLUGIN_DIR . '/classes/custom-post.class.php' );
require_once( LE_PLUGIN_DIR . '/classes/import.class.php' );
require_once( LE_PLUGIN_DIR . '/classes/le-courses.class.php' );
require_once( LE_PLUGIN_DIR . '/classes/shortcodes.class.php' );

register_activation_hook( __FILE__, array( 'LE_Courses', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'LE_Courses', 'plugin_deactivation' ) );

add_action( 'init', array( 'LE_Courses', 'init' ) );
