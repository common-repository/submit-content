<?php

/**
 * Exit if accessed directly!
 */
if ( ! defined( 'ABSPATH' ) && ! defined( 'WP_UNINSTALL_PLUGIN ') ) {
	die( 'why though?' );
}

global $wpdb;
$wpbtsc_options = 'submitcontent_options';
$table_name = $wpdb->prefix . 'submitcontent';

// delete plugin options
delete_option( $wpbtsc_options );
// drop plugin custom database table
$wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );