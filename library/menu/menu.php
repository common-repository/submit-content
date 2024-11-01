<?php
/**
 * Exit if accessed directly!
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'why though?' );
}


function wpbtsc_register_menu(){

    add_menu_page(
        __( 'Submit Content Settings', 'submit-content' ),
        __( 'Submit Content', 'submit-content' ),
        'manage_options',
        'submitcontent',
        'wpbtsc_settings_page',
        'dashicons-admin-generic'
    );

    add_submenu_page(
        'submitcontent',
        __( 'Submit Content Form Settings', 'submit-content' ),
        __( 'Form Settings', 'submit-content' ),
        'manage_options',
        'sc-form-settings',
        'wpbtsc_form_settings_page'
    );

    add_submenu_page(
        'submitcontent',
        __( 'Manage Shortcodes', 'submit-content' ),
        __( 'Shortcodes', 'submit-content' ),
        'manage_options',
        'sc-shortcodes',
        'wpbtsc_shortcodes_page'
    );
    
}


/**
 * Settings page callback
 */

function wpbtsc_settings_page(){

    // exit if user can not manage options!
    if( ! current_user_can( 'manage_options' ) ) exit;

    echo '<h1>'. esc_html( get_admin_page_title() ) .'</h1>';

    if( isset( $_GET['settings-updated'] ) ){
        add_settings_error( 
            'submitcontent',
            'submitcontent',
            __( 'options updated', 'submit-content' ),
            'success' 
        );
    }
    settings_errors( 'submitcontent' );

    ?>
        <form action="options.php" method="post">
            <?php
                settings_fields( 'submitcontent_options' );
                do_settings_sections( 'submitcontent' );
                submit_button( __( 'Save Settings', 'submit-content' ) );
            ?>
        </form>
    <?php
}

function wpbtsc_form_settings_page(){

    // exit if user can not manage options!
    if( ! current_user_can( 'manage_options' ) ) exit;

    echo '<h1>'. esc_html( get_admin_page_title() ) .'</h1>';

    $sc_options = get_option( 'submitcontent_options' );
    $wpbtsc_saveas = $sc_options['wpbtsc_saveas'];
    $supported_taxonomies = get_object_taxonomies( $wpbtsc_saveas, 'object' );
    $categories = [];
    $tags = [];
    foreach( $supported_taxonomies as $taxonomy ){
        if( $taxonomy->hierarchical ){
            $category_arr = [
                'slug' => $taxonomy->name,
                'name' => $taxonomy->label
            ];
            array_push( $categories, $category_arr );
        } elseif( ! $taxonomy->hierarchical ) {
            // skipping post_format types for post_type = 'post'
            if( $taxonomy->name == 'post_format' ) continue;
            $tag_arr = [
                'slug' => $taxonomy->name,
                'name' => $taxonomy->label 
            ];
            array_push( $tags, $tag_arr );
        }
    }
    $post_types = get_post_types( [ 'public' => true, '_builtin' => false ], 'names', 'and' );

    ?>
        <form action="" method="post" id="wpbt-sc-generator">
            <table class="form-table" role="presentation">
                <tbody>
                    <?php
                        // generate security field!
                        wpbtsc_generate_input_field( 'hidden', 'wpbt_sc_nonce', '', wp_create_nonce( 'wpbtsc' ) );
                        wpbtsc_generate_input_field( 'select', 'save_content_as', __( 'Save content as', 'submit-content' ), $wpbtsc_saveas );
                        wpbtsc_generate_input_field( 'checkbox', 'add_form_heading', __( 'Form heading', 'submit-content' ), 1 );
                        wpbtsc_generate_input_field( 'text', 'add_form_heading_text', __( 'Heading text', 'submit-content' ), '' );
                        wpbtsc_generate_input_field( 'checkbox', 'add_form_description', __( 'Form description', 'submit-content' ), 1 );
                        wpbtsc_generate_input_field( 'textarea', 'add_form_description_text', __( 'Description text', 'submit-content' ), '' );

                        if( post_type_supports( $wpbtsc_saveas, 'title' ) ){
                            wpbtsc_generate_input_field( 'checkbox', 'add_post_title', __( 'Post title', 'submit-content' ), 1 );
                        } else {
                            wpbtsc_generate_input_field( 'notice', 'notice', __( 'Post title', 'submit-content' ), __( 'not supported for selected post type', 'submit-content' ) );
                        }

                        if( post_type_supports( $wpbtsc_saveas, 'editor' ) ){
                            wpbtsc_generate_input_field( 'checkbox', 'add_post_content', __( 'Post content', 'submit-content' ), 1 );
                        } else {
                            wpbtsc_generate_input_field( 'notice', 'notice', __( 'Post content', 'submit-content' ), __( 'not supported for selected post type', 'submit-content' ) );
                        }

                        if( post_type_supports( $wpbtsc_saveas, 'thumbnail' ) ){
                            wpbtsc_generate_input_field( 'checkbox', 'add_post_featured_image', __( 'Featured image', 'submit-content' ), 1 );
                        } else {
                            wpbtsc_generate_input_field( 'notice', 'notice', __( 'Featured image', 'submit-content' ), __( 'not supported for selected post type', 'submit-content' ) );
                        }

                        if( !empty( $categories ) ){
                            foreach( $categories as $category ){
                                $name = $category['name'];
                                $slug = $category['slug'];
                                $content = sprintf( '%s %s %s',__( 'Allow users to select', 'submit-content' ), $wpbtsc_saveas, $name );
                                wpbtsc_generate_input_field( 'checkbox', $name, $content, $slug,  [ 'type' => 'category' ] );
                            }
                        }

                        if( !empty( $tags ) ){
                            foreach( $tags as $tag ){
                                $name = $tag['name'];
                                $slug = $tag['slug'];
                                $content = sprintf( '%s %s %s', __( 'Allow users to select', 'submit-content' ), $wpbtsc_saveas, $name );
                                wpbtsc_generate_input_field( 'checkbox', $name, $content, $slug,  [ 'type' => 'tag' ] );
                            }
                        }

                    ?>
                </tbody>
            </table>

            <p class="submit">
                <input class="button button-primary" type="submit" name="wpbt_sc_shortcode" value="<?php esc_attr_e( 'Generate Shortcode', 'submit-content' ); ?>">
            </p>
        </form>
    <?php
}

function wpbtsc_shortcodes_page(){
    global $wpdb;
    // exit if user can not manage options!
    if( ! current_user_can( 'manage_options' ) ) exit;

    echo '<h1>'. esc_html( get_admin_page_title() ) .'</h1>';

    /**
     * Querying the database for displaying shortcodes.
     */

    $table_name = $wpdb->prefix . 'submitcontent';
    $shortcodes = $wpdb->get_results( "SELECT id, shortcode_name, options FROM $table_name" );
    
    ?>
        <table class="sc-table">
            <thead>
                <tr>
                    <th><?php _e( 'S.N.', 'submit-content' ); ?></th>
                    <th><?php _e( 'Shortcode', 'submit-content' ); ?></th>
                    <th><?php _e( 'Options', 'submit-content' ); ?></th>
                    <th><?php _e( 'Action', 'submit-content' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if( ! empty( $shortcodes ) ):
                        $count = 1;
                        foreach( $shortcodes as $shortcode ){
                            $options = maybe_unserialize( $shortcode->options );
                            ?>
                                <tr id="<?php echo esc_attr( $shortcode->id ); ?>">
                                    <td class="sc-sn"><?php echo esc_html( $count ); ?></td>
                                    <td class="wpbtsc-copy"><?php echo esc_html( $shortcode->shortcode_name ); ?></td>
                                    <td><?php wpbtsc_generate_options( $options ); ?></td>
                                    <td>
                                        <a 
                                            nonceKey="<?php echo wp_create_nonce( 'wpbt_delete_sc' ); ?>"
                                            class="wpbt-delete-sc button button-primary"
                                            href="#" scid="<?php echo esc_attr( $shortcode->id ); ?>"
                                        >
                                            <?php esc_html_e( 'Delete', 'submit-content' ); ?>
                                        </a>
                                    </td>
                                </tr> 
                            <?php
                            $count++;
                        }
                    else:
                        printf( "<tr class='no-shortcodes'>
                                    <td colspan='4'>
                                        <p>%s</p>
                                        <p>%s: <a href='%s'>%s</a></p>
                                    </td>
                                </tr>",
                                __( 'you haven\'t created any shortcodes yet!', 'submit-content' ),
                                __( 'to create shortcodes, visit', 'submit-content' ),
                                menu_page_url( 'sc-form-settings', false ),
                                __( 'create shortcodes', 'submit-content' )
                            );
                    endif;
                ?>
            </tbody>
        </table>
    <?php
}