<?php

/**
 * Exit if accessed directly!
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'why though?' );
}

/**
 * Enqueue scripts in the frontend
 */

function wpbtsc_public_scripts(){

    // style
    wp_register_style( 'wpbtsc-public-style', SUBMIT_CONTENT_DIRECTORY_URL . 'public_assets/css/public-styles.css' );
    wp_enqueue_style( 'wpbtsc-public-style' );
    
    // script and variables
    $jsObject = [
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'error_heading' => sprintf( esc_html__( '<h4>%s!</h4>', 'submit-content' ), 'please fix following errors' )
    ];
    $wpbtsc_options = get_option( 'submitcontent_options' );

    wp_register_script( 'wpbtsc-public-script', SUBMIT_CONTENT_DIRECTORY_URL . 'public_assets/js/public-scripts.js', [ 'jquery' ], '', true );

    // a global variable and script enqueu if reCAPTCHA service is enabled!
    if( $wpbtsc_options['wpbtsc_recaptcha_sitekey'] && $wpbtsc_options['wpbtsc_recaptcha_secretkey'] ){

        $url = 'https://www.google.com/recaptcha/api.js?render=' . esc_attr( $wpbtsc_options['wpbtsc_recaptcha_sitekey'] );
        $jsObject['site_key'] = esc_attr( $wpbtsc_options['wpbtsc_recaptcha_sitekey'] );

        wp_register_script( 'wpbtsc-recaptcha', esc_url( $url ), [], '', true );
        wp_enqueue_script( 'wpbtsc-recaptcha' );

    }
    
    wp_localize_script(
        'wpbtsc-public-script',
        'scJSOBJ',
        $jsObject
    );

    
    wp_enqueue_script( 'wpbtsc-public-script' );

}