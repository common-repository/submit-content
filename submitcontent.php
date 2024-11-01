<?php
/**
 * Plugin Name: Submit Content
 * Author: Bharat Thapa
 * Author URI: https://bharatt.com.np
 * Contributors: bharatthapa
 * Description: Submit posts and custom pots from anywhere on your website.
 * Tags: user post, frontend post, public post, submit custom post, submit post, visitor post, submit content
 * Version: 1.1
 * Stable tag: 1.1
 * Text Domain: submit-content
 * Domain Path: /languages
 * Requires at least: 4.9
 * Tested up to: 6.2.2
 * Requires PHP: 5.2.4
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * 
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation. You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License 
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */


/**
 * Exit if accessed directly!
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'why though?' );
}

/**
 * Plugin setup
 * constant definitions:
 * - Version
 * - Plugin path
 * - Plugin URL
 */

define( 'SUBMIT_CONTENT_VERSION', '1.1' );
define( 'SUBMIT_CONTENT_DIRECTORY', plugin_dir_path( __FILE__ ) );
define( 'SUBMIT_CONTENT_DIRECTORY_URL', plugin_dir_url( __FILE__ ) );

/**
 * Plugin includes
 */

require_once( SUBMIT_CONTENT_DIRECTORY . 'activate.php' );
require_once( SUBMIT_CONTENT_DIRECTORY . 'deactivate.php' );
require_once( SUBMIT_CONTENT_DIRECTORY . 'library/utility_functions.php' );
require_once( SUBMIT_CONTENT_DIRECTORY . 'library/menu/menu.php' );
require_once( SUBMIT_CONTENT_DIRECTORY . 'library/menu/settings.php' );
require_once( SUBMIT_CONTENT_DIRECTORY . 'library/menu/settings-field-callbacks.php' );
require_once( SUBMIT_CONTENT_DIRECTORY . 'admin_assets/enqueue.php' );
require_once( SUBMIT_CONTENT_DIRECTORY . 'public_assets/enqueue.php' );
require_once( SUBMIT_CONTENT_DIRECTORY . 'library/ajax.php' );
require_once( SUBMIT_CONTENT_DIRECTORY . 'library/shortcode.php' );

/**
 * Activation and Deactivation hooks
 */
register_activation_hook( __FILE__, 'wpbtsc_compatibility_check_and_install_defaults' );
register_activation_hook( __FILE__, 'wpbtsc_create_dbtable' );
register_deactivation_hook( __FILE__, 'wpbtsc_deactivate' );

/**
 * Plugin hooks
 */

// plugin textdomain
add_action( 'init', 'wpbtsc_load_plugin_textdomain' );

// administrative menu & settings page hooks
add_action( 'admin_menu', 'wpbtsc_register_menu' );
add_action( 'admin_init', 'wpbtsc_register_settings' );
// scripts hooks
add_action( 'admin_enqueue_scripts', 'wpbtsc_admin_scripts' );
add_action( 'wp_enqueue_scripts', 'wpbtsc_public_scripts' );
// ajax hooks
add_action( 'wp_ajax_sc_generate_shortcode', 'wpbtsc_generate_shortcode_callback' );
add_action( 'wp_ajax_sc_delete_shortcode', 'wpbtsc_delete_shortcode_callback' );
add_action( 'wp_ajax_wpbtsc_form_submission', 'wpbtsc_form_submission' );
add_action( 'wp_ajax_nopriv_wpbtsc_form_submission', 'wpbtsc_form_submission' );

// shortcode registration
add_shortcode( 'submitcontent', 'wpbtsc_shortcode' );