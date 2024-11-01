<?php
/**
 * Exit if accessed directly!
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'why though?' );
}


/**
 * General section setting field callbacks!
 */

function wpbtsc_saveas_callback( $args ){

    $options = get_option( 'submitcontent_options' );
    $value = ( $options[$args['id']] ) ? esc_attr( $options[$args['id']] ) : '';
    $post_types = get_post_types( 
        [
            'public' => true,
            '_builtin' => false
        ],
        'names',
        'and'
    );

    ?>
        <select name="submitcontent_options[<?php echo esc_attr( $args['id'] ); ?>]" id="<?php echo esc_attr( $args['id'] ); ?>">
            <option value="post" <?php echo selected( $value, 'post' ); ?>><?php esc_html_e( 'post', 'submit-content' ) ?></option>
            <?php
                foreach( $post_types as $post_type ){
                    ?>
                        <option value="<?php echo esc_attr( $post_type ); ?>" <?php echo selected( $value, $post_type ); ?>><?php echo esc_html( $post_type ); ?></option>
                    <?php
                }
            ?>
        </select>
    <?php
}

function wbptsc_default_status_callback( $args ){
    $options = get_option( 'submitcontent_options' );
    $value = ( $options[$args['id']] ) ? esc_attr( $options[$args['id']] ) : '';
    $statuses = [
        'draft' => __( 'Draft', 'submit-content' ),
        'pending' => __( 'Pending', 'submit-content' ),
        'publish' => __( 'Publish', 'submit-content' ),
    ];
    ?>
        <select name="submitcontent_options[<?php echo esc_attr( $args['id'] ); ?>]" id="<?php echo esc_attr( $args['id'] ); ?>">
            <?php
                foreach( $statuses as $key => $name ){
                    ?>
                        <option value="<?php echo esc_attr( $key ); ?>" <?php echo selected( $value, $key ); ?>><?php echo esc_html( $name ) ?></option>
                    <?php
                }
            ?>
        </select>
    <?php
}

function wpbtsc_email_callback( $args ){
    $options = get_option( 'submitcontent_options' );
    $value = ( $options[$args['id']] ) ? esc_attr( $options[$args['id']] ) : '';
    ?>
        <input id="<?php echo esc_attr( $args['id'] ); ?>" type="checkbox" name="submitcontent_options[<?php echo esc_attr( $args['id'] ); ?>]" value="1" <?php echo checked( $value, 1 ); ?> />
    <?php
}

function wpbtsc_requires_login_callback( $args ){
    $options = get_option( 'submitcontent_options' );
    $value = ( $options[$args['id']] ) ? esc_attr( $options[$args['id']] ) : '';
    ?>
        <input id="<?php echo esc_attr( $args['id'] ); ?>" type="checkbox" name="submitcontent_options[<?php echo esc_attr( $args['id'] ); ?>]" value="1" <?php echo checked( $value, 1 ); ?> />
    <?php
}

function wpbtsc_posttitle_length_callback( $args ){
    $options = get_option( 'submitcontent_options' );
    $value = ( $options[$args['id']] ) ? esc_attr( $options[$args['id']] ) : '';
    ?>
        <input id="<?php echo esc_attr( $args['id'] ); ?>" type="number" name="submitcontent_options[<?php echo esc_attr( $args['id'] ); ?>]" value="<?php echo trim( intval( $value ) ); ?>">
    <?php
}

function wpbtsc_content_length_callback( $args ){
    $options = get_option( 'submitcontent_options' );
    $value = ( $options[$args['id']] ) ? esc_attr( $options[$args['id']] ) : '';
    ?>
        <input id="<?php echo esc_attr( $args['id'] ); ?>" type="number" name="submitcontent_options[<?php echo esc_attr( $args['id'] ); ?>]" value="<?php echo trim( intval( $value ) ); ?>">
    <?php
}

function wpbtsc_max_image_size_callback( $args ){
    $options = get_option( 'submitcontent_options' );
    $value = ( $options[$args['id']] ) ? esc_attr( $options[$args['id']] ) : '';
    ?>
        <input id="<?php echo esc_attr( $args['id'] ); ?>" type="text" name="submitcontent_options[<?php echo esc_attr( $args['id'] ); ?>]" value="<?php echo trim( $value ); ?>">
    <?php
}

/**
 * Security section callback
 */

function wpbtsc_security_section_callback(){
    printf(
        '<p><strong>%s: %s</strong></p>',
        __( 'Note', 'submit-content' ),
        __( 'To implement Google\'s v3 reCAPTCHA service, enter both site key and secret key', 'submit-content' ),
    );
}

/**
 * Security section setting field callbacks
 */

function wpbtsc_sitekey_callback( $args ){
    $options = get_option( 'submitcontent_options' );
    $value = ( $options[$args['id']] ) ? esc_attr( $options[$args['id']] ) : '';
    ?>
        <input id="<?php echo esc_attr( $args['id'] ); ?>" type="text" name="submitcontent_options[<?php echo esc_attr( $args['id'] ); ?>]" value="<?php echo trim( $value ); ?>">
    <?php
}

function wpbtsc_secretkey_callback( $args ){
    $options = get_option( 'submitcontent_options' );
    $value = ( $options[$args['id']] ) ? esc_attr( $options[$args['id']] ) : '';
    ?>
        <input id="<?php echo esc_attr( $args['id'] ); ?>" type="text" name="submitcontent_options[<?php echo esc_attr( $args['id'] ); ?>]" value="<?php echo trim( $value ); ?>">
    <?php
}

/**
 * Email section settings field callbacks
 */

function wpbtsc_email_override_callback( $args ){
    $options = get_option( 'submitcontent_options' );
    $value = ( $options[$args['id']] ) ? esc_attr( $options[$args['id']] ) : '';
    ?>
        <input id="<?php echo esc_attr( $args['id'] ); ?>" type="text" name="submitcontent_options[<?php echo esc_attr( $args['id'] ); ?>]" value="<?php echo trim( $value ); ?>">
    <?php
}

function wpbtsc_email_template_callback( $args ){
    $options = get_option( 'submitcontent_options' );
    $value = ( $options[$args['id']] ) ? esc_attr( $options[$args['id']] ) : '';
    ?>
        <textarea id="<?php echo esc_attr( $args['id'] ); ?>" name="submitcontent_options[<?php echo esc_attr( $args['id'] ); ?>]" id="" cols="50" rows="12"><?php echo esc_textarea( $value ); ?></textarea>
        <p><?php _e( 'Available tags:', 'submit-content' ); ?></p>
        <span>{user_name}, {post_title}, {post_edit_url}, {site_name}</span>
        <p><strong><?php _e( 'Note: Leaving email template empty will disable email.', 'submit-content' ); ?></strong></p>
    <?php
}
