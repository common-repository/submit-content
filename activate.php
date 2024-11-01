<?php
/**
 * Exit if accessed directly!
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( 'why though?' );
}

function wpbtsc_compatibility_check_and_install_defaults(){
    global $wp_version;

    /**
     * Check WordPress version for compatibality
     */
    if ( version_compare( $wp_version, '4.9', '<' ) ) {
        wp_die( 'This plugin requires WordPress version 4.9 or higher.' );
    }

    /**
     * install default options
     */
    $email_template = sprintf(
        "%s,\n\n\n{user_name} %s: {post_title}.\n%s: {post_edit_url}\n\n\n%s,\n{site_name}",
        __( 'Dear Admin', 'submit-content', 'submit-content' ),
        __( 'has submitted a post titled', 'submit-content' ),
        __( 'Please moderate the post at', 'submit-content' ),
        __( 'Regards', 'submit-content' )
    );
    $defaults = [
        'wpbtsc_saveas' => 'post',
        'wpbtsc_default_status' => 'draft',
        'wpbtsc_posttitle_length' => 5,
        'wpbtsc_content_length' => 15,
        'wpbtsc_max_image_size' => 2,
        'wpbtsc_send_admin_email' => '1',
        'wpbtsc_requires_login' => '1',
        'wpbtsc_recaptcha_sitekey' => '',
        'wpbtsc_recaptcha_secretkey' => '',
        'wpbtsc_email_override' => '',
        'wpbtsc_email_template' => $email_template,
    ];
    /**
     * prevent automatic update of options during deactivation and re-activation!
     */
    if( ! get_option( 'submitcontent_options' ) ){
        add_option( 'submitcontent_options', $defaults );
    }

}

/**
 * create database table
 */

function wpbtsc_create_dbtable(){
    global $wpdb;

    $table_name = $wpdb->prefix . 'submitcontent';
    $charset_coallate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        shortcode_name varchar(255) NOT NULL,
        options text NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_coallate";

    // require WordPress dbDelta() function
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}