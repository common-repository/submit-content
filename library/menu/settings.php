<?php
/**
 * Exit if accessed directly!
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'why though?' );
}

function wpbtsc_register_settings(){
    register_setting(
        'submitcontent_options',
        'submitcontent_options',
        'wpbtsc_validate'
    );

    // general section
    add_settings_section(
        'wpbt_submitcontent_general_section',
        __( 'General settings', 'submit-content' ),
        null,
        'submitcontent'
    );

    // securit section
    add_settings_section(
        'wpbt_submitcontent_security_section',
        __( 'Form security settings', 'submit-content' ),
        'wpbtsc_security_section_callback',
        'submitcontent'
    );

    // email section
    add_settings_section(
        'wpbtsc_email_section',
        __( 'Email settings', 'submit-content' ),
        null,
        'submitcontent'
    );

    // general section fields
    add_settings_field(
        'wpbtsc_saveas',
        __( 'Save content as: ', 'submit-content' ),
        'wpbtsc_saveas_callback', 
        'submitcontent',
        'wpbt_submitcontent_general_section',
        [
            'id' =>  'wpbtsc_saveas',
            'label_for' =>  'wpbtsc_saveas',
        ]
    );

    add_settings_field(
        'wpbtsc_default_status',
        __( 'Default status of the content', 'submit-content' ),
        'wbptsc_default_status_callback',
        'submitcontent',
        'wpbt_submitcontent_general_section',
        [
            'id' =>  'wpbtsc_default_status',
            'label_for' =>  'wpbtsc_default_status',
        ]
    );

    add_settings_field(
        'wpbtsc_send_admin_email',
        __( 'Send email to admin whenever content is submitted', 'submit-content' ),
        'wpbtsc_email_callback',
        'submitcontent',
        'wpbt_submitcontent_general_section',
        [
            'id' => 'wpbtsc_send_admin_email',
            'label_for' =>  'wpbtsc_send_admin_email',
        ]
    );

    add_settings_field(
        'wpbtsc_requires_login',
        __( 'Only loggedin users can submit', 'submit-content' ),
        'wpbtsc_requires_login_callback',
        'submitcontent',
        'wpbt_submitcontent_general_section',
        [
            'id' => 'wpbtsc_requires_login',
            'label_for' =>  'wpbtsc_requires_login',
        ]
    );

    add_settings_field(
        'wpbtsc_posttitle_length',
        __( 'Minimum post title length', 'submit-content' ),
        'wpbtsc_posttitle_length_callback',
        'submitcontent',
        'wpbt_submitcontent_general_section',
        [
            'id' => 'wpbtsc_posttitle_length',
            'label_for' =>  'wpbtsc_posttitle_length'
        ]
    );

    add_settings_field(
        'wpbtsc_content_length',
        __( 'Minimum post content length', 'submit-content' ),
        'wpbtsc_content_length_callback',
        'submitcontent',
        'wpbt_submitcontent_general_section',
        [
            'id' => 'wpbtsc_content_length',
            'label_for' =>  'wpbtsc_content_length'
        ]
    );

    add_settings_field(
        'wpbtsc_max_image_size',
        __( 'Maximum allowed image size (in Mb)', 'submit-content' ),
        'wpbtsc_max_image_size_callback',
        'submitcontent',
        'wpbt_submitcontent_general_section',
        [
            'id' => 'wpbtsc_max_image_size',
            'label_for' =>  'wpbtsc_max_image_size'
        ]
    );

    // security fields
    add_settings_field(
        'wpbtsc_recaptcha_sitekey',
        __( 'Enter reCAPTCHA v3 site key', 'submit-content' ),
        'wpbtsc_sitekey_callback',
        'submitcontent',
        'wpbt_submitcontent_security_section',
        [
            'id' => 'wpbtsc_recaptcha_sitekey',
            'label_for' =>  'wpbtsc_recaptcha_sitekey'
        ]
    );

    add_settings_field(
        'wpbtsc_recaptcha_secretkey',
        __( 'Enter reCAPTCHA v3 secret key', 'submit-content' ),
        'wpbtsc_secretkey_callback',
        'submitcontent',
        'wpbt_submitcontent_security_section',
        [
            'id' => 'wpbtsc_recaptcha_secretkey',
            'label_for' =>  'wpbtsc_recaptcha_secretkey',
        ]
    );

    // email section fields
    add_settings_field(
        'wpbtsc_email_override',
        __( 'Send email to (default is admin email)', 'submit-content' ),
        'wpbtsc_email_override_callback',
        'submitcontent',
        'wpbtsc_email_section',
        [
            'id' => 'wpbtsc_email_override',
            'label_for' =>  'wpbtsc_email_override',
        ]
    );

    add_settings_field(
        'wpbtsc_email_template',
        __( 'Email template', 'submit-content' ),
        'wpbtsc_email_template_callback',
        'submitcontent',
        'wpbtsc_email_section',
        [
            'id' => 'wpbtsc_email_template',
            'label_for' =>  'wpbtsc_email_template',
        ]
    );
}

/**
 * validation callback
 */

function wpbtsc_validate( $input ){
    // get default options
    $wpbtsc_options = get_option( 'submitcontent_options' );

    if( ! $input['wpbtsc_saveas'] ){
        // set default option
        $input['wpbtsc_saveas'] = $wpbtsc_options['wpbtsc_saveas'];
    }

    if( ! $input['wpbtsc_default_status'] ){
        // set default option
        $input['wpbtsc_default_status'] = $wpbtsc_options['wpbtsc_default_status'];
    }

    // sanitize post title length field
    $filter_options = [
        'options' => [
            'default' => $wpbtsc_options['wpbtsc_posttitle_length'],
            'min_range' => 1
        ]
    ];
    $input['wpbtsc_posttitle_length'] = filter_var( $input['wpbtsc_posttitle_length'], FILTER_VALIDATE_INT, $filter_options );

    // sanitize content length field
    unset( $filter_options );
    $filter_options = [
        'options' => [
            'default' => $wpbtsc_options['wpbtsc_content_length'],
            'min_range' => 1
        ]
    ];
    $input['wpbtsc_content_length'] = filter_var( $input['wpbtsc_content_length'], FILTER_VALIDATE_INT, $filter_options );

    // sanitize image size field 
    unset( $filter_options );
    $filter_options = [
        'options' => [
            'default' => $wpbtsc_options['wpbtsc_max_image_size'],
            'min_range' => 0
        ]
    ];
    $input['wpbtsc_max_image_size'] = filter_var( $input['wpbtsc_max_image_size'], FILTER_VALIDATE_FLOAT, $filter_options );

    // reCAPTCHA keys
    if( ! $input['wpbtsc_recaptcha_sitekey'] ){
        $input['wpbtsc_recaptcha_sitekey'] = '';
    } else {
        $input['wpbtsc_recaptcha_sitekey'] = sanitize_text_field( $input['wpbtsc_recaptcha_sitekey'] );
    }

    if( ! $input['wpbtsc_recaptcha_secretkey'] ){
        $input['wpbtsc_recaptcha_secretkey'] = '';
    } else {
        $input['wpbtsc_recaptcha_secretkey'] = sanitize_text_field( $input['wpbtsc_recaptcha_secretkey'] );
    }

    // email fields
    if( ! $input['wpbtsc_email_override'] ){
        $input['wpbtsc_email_override'] = '';
    } else {
        $input['wpbtsc_email_override'] = sanitize_email( $input['wpbtsc_email_override'] );
    }

    if( ! $input['wpbtsc_email_template'] ){
        $input['wpbtsc_email_template'] = '';
    } else {
        $input['wpbtsc_email_template'] = sanitize_textarea_field( $input['wpbtsc_email_template'] );
    }

    if( ! $input['wpbtsc_send_admin_email'] ){
        $input['wpbtsc_send_admin_email'] = '0';
    }

    if( ! $input['wpbtsc_requires_login'] ){
        $input['wpbtsc_requires_login'] = '0';
    }

    return $input;
}