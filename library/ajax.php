<?php

/**
 * Exit if accessed directly!
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'why though?' );
}

/**
 * AJAX handler to generate and save shortcode
 */

function wpbtsc_generate_shortcode_callback(){
    global $wpdb;

    if( isset( $_POST['options'] ) ){
        $result = wpbtsc_validate_admin_form( $_POST['options'] );
    } else {
        $result = [];
        $result['errors'] = __( 'no options available!', 'submit-content' );
    }

    // handle errors!
    if( ! empty( $result['errors'] ) ){
        $response = [
            'data' => $result['errors'],
            'type' => 'error'
        ];
        wp_send_json( $response );
    }

    // handle save
    $table_name = $wpdb->prefix . 'submitcontent';
    $shortcode_options = '';
    $id = '';

    if( ! is_serialized( $result['data'] ) ){
        $shortcode_options = maybe_serialize( $result['data'] );
    }

    $duplicate = wpbtsc_check_duplicate_shortcode( $shortcode_options );
    if( $duplicate ){
        $response = [
            'data' => $shortcode_name,
            'type' => 'warning'
        ];
        wp_send_json( $response );
    }
    $shortcode_name = '[submitcontent id="'. $id .'"]';
    $result = $wpdb->query( 
                    $wpdb->prepare(
                        "INSERT INTO $table_name (shortcode_name, options) VALUES (%s, %s)",
                        $shortcode_name,
                        $shortcode_options
                    )
                );

    if( $result ){
        $shortcode_name = '[submitcontent id="'. $wpdb->insert_id .'"]';
        $update = $wpdb->query( 
                        $wpdb->prepare(
                            "UPDATE $table_name SET shortcode_name=%s WHERE id=%d",
                            $shortcode_name,
                            $wpdb->insert_id
                        )
                    );

        if( $update ){
            $response = [
                'data' => $shortcode_name,
                'type' => 'success'
            ];
            wp_send_json( $response );
        }
    } else {
        $response = [
            'data' => $result,
            'type' => 'error'
        ];
        wp_send_json( $response );
    }
}

/**
 * AJAX handler to delete shortcode
 */

function wpbtsc_delete_shortcode_callback(){

    global $wpdb;

    if( ! wp_verify_nonce( $_POST['securityKey'], 'wpbt_delete_sc' ) ){
        $response = [
            'type' => 'error',
            'data' => __( 'invalid nonce!', 'submit-content' )
        ];
        wp_send_json( $response );
    } 
    
    // delete the record!

    $table_name = $wpdb->prefix . 'submitcontent';
    $row_id = ( $_POST['id'] ) ? intval( $_POST['id'] ) : '';

    if( ! $row_id ){
        $response = [ 'type' => 'error', 'data' => __( 'empty shortcode id', 'submit-content' ) ];
        wp_send_json( $response );
    }

    $result = $wpdb->delete(
        $table_name,
        [ 'id' => $row_id ],
        [ '%d' ]
    );
    if( $result ){
        $tableEmptyMessage = sprintf( "<tr class='no-shortcodes'>
                                            <td colspan='4'>
                                                <p>%s</p>
                                                <p>%s: <a href='%s'>%s</a></p>
                                            </td>
                                       </tr>",
                                        __( 'you haven\'t created any shortcodes yet!', 'submit-content' ),
                                        __( 'to create shortcodes, visit', 'submit-content' ),
                                        admin_url( 'admin.php?page=sc-form-settings' ),
                                        __( 'create shortcodes', 'submit-content' )
                            );
        $response = [
            'type' => 'success',
            'data' => [
                'rowid' => $row_id,
                'message' => __( 'shortcode deleted successfully', 'submit-content' ),
                'tableEmpty' => $tableEmptyMessage
            ]
        ];
        wp_send_json( $response );
    }
    
}

/**
 * AJAX handler to process user form submission
 */

function wpbtsc_form_submission(){
    if( isset( $_POST ) || isset( $_FILES['wpbtsc_featured_img'] ) ){
        $result = wpbtsc_validate_public_form( $_POST, $_FILES['wpbtsc_featured_img'] );
    } else {
        $result = [];
        $result['errors'] = __( 'no data available!', 'submit-content' );
    }
    // handle errors!
    $form_id = sanitize_text_field( $_POST['form_id'] );
    if( ! empty( $result['errors'] ) ){
        $response = [
            'data' => $result['errors'],
            'type' => 'error',
            'form_id' => $form_id,
        ];
        wp_send_json( $response );
    }
    
    $post_array = wpbtsc_create_posts_array( $result['data'] );
    $post_id = wp_insert_post( $post_array, true );

    if( ! is_wp_error( $post_id ) ){
        $success_message = __( 'content submitted successfully!', 'submit-content' );
        if( is_null( $result['data']['featured_image'] ) ){
            // form doesn't have featured image field case!
            $response = [
                'data' => $success_message,
                'type' => 'success',
                'form_id' => $form_id,
            ];
            $wpbtsc_options = get_option( 'submitcontent_options' );
            if( $wpbtsc_options['wpbtsc_send_admin_email'] ){
                wpbtsc_send_email( $post_id, $post_array['post_title'] );
            }

        } else {
            // These files need to be included as dependencies when on the front end.
            require_once( ABSPATH . 'wp-admin/includes/image.php' );
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
            require_once( ABSPATH . 'wp-admin/includes/media.php' );

            $attachment_id = media_handle_upload( 'wpbtsc_featured_img', $post_id );

            if( ! is_wp_error( $attachment_id ) ){
                $featured_img_set = set_post_thumbnail( $post_id, $attachment_id );
                if( $featured_img_set ){
                    $response = [
                        'data' => $success_message,
                        'type' => 'success',
                        'form_id' => $form_id,
                    ];
                    $wpbtsc_options = get_option( 'submitcontent_options' );
                    if( $wpbtsc_options['wpbtsc_send_admin_email'] ){
                        wpbtsc_send_email( $post_id, $post_array['post_title'] );
                    }
                } else {
                    $response = [
                        'data' => __( 'featured image is required', 'submit-content' ),
                        'type' => 'error',
                        'form_id' => $form_id,
                    ];
                }
            } else {
                $response = [
                    'data' => $attachment_id->get_error_message(),
                    'type' => 'error',
                    'form_id' => $form_id,
                ];
            }
        }
    } else {
        $response = [
            'data' => $post_id->get_error_message(),
            'type' => 'error',
            'form_id' => $form_id,
        ];
    }
    wp_send_json( $response );
}