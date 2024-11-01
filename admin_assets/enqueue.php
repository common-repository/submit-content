<?php

/**
 * Exit if accessed directly!
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'why though?' );
}

/**
 * Enqueue scripts in the WordPress admin on plugin settings pages!
 *
 * @param String $hook current page slug.
 * @return Null 
 */

function wpbtsc_admin_scripts( $hook ){
    if( 
        ( 'toplevel_page_submitcontent' == $hook )
        ||
        ( 'submit-content_page_sc-form-settings' == $hook )
        ||
        ( 'submit-content_page_sc-shortcodes' == $hook )
    ){
        // style
        wp_register_style( 'wpbtsc-admin-style', SUBMIT_CONTENT_DIRECTORY_URL . 'admin_assets/css/admin-styles.css' );
        wp_enqueue_style( 'wpbtsc-admin-style' );
        // script
        wp_register_script( 'wpbtsc-admin-script', SUBMIT_CONTENT_DIRECTORY_URL . 'admin_assets/js/admin-scripts.js', [ 'jquery' ], '', true );
        wp_localize_script(
            'wpbtsc-admin-script',
            'scJSOBJ',
            [
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'updateText' => __( 'shortcode created successfully', 'submit-content' ),
                'duplicateText' => __( 'similar shortcode already exists', 'submit-content' )
            ]
        );
        wp_enqueue_script( 'wpbtsc-admin-script' );
    }

}