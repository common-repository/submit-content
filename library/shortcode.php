<?php

/**
 * Exit if accessed directly!
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'why though?' );
}


/**
 * [submitcontent] callback
 * @param int $id Shortcode id
 * @return String $html outputs shortcode (form)
 */

function wpbtsc_shortcode( $atts ){

    global $wpdb;

    $table_name = $wpdb->prefix . 'submitcontent';
    $defaults = [ 'id' => '' ];
    $atts = shortcode_atts( $defaults, $atts );
    $output = '';
    
    ob_start();
    
    if( ! $atts['id'] ) return sprintf( '<p>%s</p>', __( 'invalid shortcode', 'submit-content' ) );

    $options = $wpdb->get_row( 
                        $wpdb->prepare( 
                            "SELECT options FROM $table_name WHERE id = %d",
                            $atts['id']
                        )
                    );
    if( ! $options ) return sprintf( '<p>%s</p>', __( 'invalid shortcode id provided', 'submit-content' ) );
    $options = maybe_unserialize( $options->options );
    
    wpbtsc_output_form( $options, $atts['id'] );

    return $output = ob_get_clean();
}