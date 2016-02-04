<?php
/**
 *
 * @package   CGC_Exercises
 * @author    Nick Haskins <nick@cgcookie.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Your Name or Company Name
 *
 * Plugin Name:       CGC Exercise and Submissions
 * Plugin URI:        http://cgcookie.com
 * Description:       Creates an exercise and submission system.
 * Version:           5.4
 * GitHub Plugin URI: https://github.com/cgcookie/cgc-exercises
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Set some constants
define('CGC_EXERCISES_VERSION', '5.4');
define('CGC_EXERCISES_DIR', plugin_dir_path( __FILE__ ));
define('CGC_EXERCISES_URL', plugins_url( '', __FILE__ ));
/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/
require_once( plugin_dir_path( __FILE__ ) . 'public/class-cgc-exercises.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 *
 */
register_activation_hook( __FILE__, array( 'CGC_Exercises', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'CGC_Exercises', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'CGC_Exercises', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 *
 * If you want to include Ajax within the dashboard, change the following
 * conditional to:
 *
 * if ( is_admin() ) {
 *   ...
 * }
 *
 * The code below is intended to to give the lightest footprint possible.
 */
if ( is_admin() ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-cgc-exercises-admin.php' );
	add_action( 'plugins_loaded', array( 'CGC_Exercises_Admin', 'get_instance' ) );

}
