'use strict';

/**
 * application setup
 */

let submitContentApp = {
    data: {},
    init: function(){
        submitContentApp.data = {
            form: jQuery( '#wpbt-sc-generator' ),
            button: jQuery( '#wpbt-sc-generator' ).find( 'input[type="submit"]' ),
            action: 'sc_generate_shortcode',
            ajaxURL: scJSOBJ.ajax_url,
            options: {
                save_content_as: jQuery( '#save_content_as option:selected' ).val()
            },
        };
        submitContentApp.handleSecurity( jQuery( submitContentApp.data.form ).find( 'input#wpbt_sc_nonce' ) );
        jQuery( submitContentApp.data.form ).find( ':input' ).change( submitContentApp.handleInput );
        submitContentApp.data.button.click( submitContentApp.handleSubmit );
    },
    handleSecurity: function( element ){
        let input = jQuery( element );
        let inputKey = input.attr( 'name' );
        let value = input.val();
        submitContentApp.updateOptions( inputKey, value );
    },
    handleInput: function(){ 
        // 'this' refers to the input field
        let inputType = this.type || this.tagName.toLowerCase();
        let inputKey = jQuery( this ).attr( 'name' );
        let label = jQuery( this ).attr( 'label' );
        let id = jQuery( this ).attr( 'id' );
        let value = '';
        let taxonomies = {};
        switch( inputType ){
            case 'checkbox':
                if( jQuery( this ).prop( 'checked' ) == true){
                    value = jQuery( this ).val();
                } else {
                    value = 0;
                }
                if( ( inputKey === 'category' ) || ( inputKey === 'tag' ) ) {
                    taxonomies = {
                        slug: id,
                        name: label
                    };
                    submitContentApp.updateOptions( inputKey, '', false, taxonomies );
                } else {
                    submitContentApp.updateOptions( inputKey, value );
                }
                jQuery( 'tr.' + inputKey + '_text' ).toggle();
                break;
            case 'select-one':
            case 'select':
                value = jQuery( this ).val();
                submitContentApp.updateOptions( inputKey, value, false );
                break;
            case 'text':
                value = jQuery( this ).val();
                submitContentApp.updateOptions( inputKey, value, false );
                break;
            case 'textarea':
                value = jQuery( this ).val();
                submitContentApp.updateOptions( inputKey, value, false );
                break;
        }
    },
    updateOptions: function( key, value, remove = true, taxonomies = null ){
        if( key in submitContentApp.data.options ){
            // check if taxonomies are provieded!
            if( taxonomies ){
                // check if the taxonomy property is empty!
                if( submitContentApp.data.options[key].length == 0 ){
                    submitContentApp.data.options[key].push( taxonomies );
                } else {
                    // update/delete based on current value!
                    for( let p in submitContentApp.data.options[key] ){
                        if( typeof submitContentApp.data.options[key][p] !== 'undefined' ){
                            if( 
                                ( submitContentApp.data.options[key][p].slug == taxonomies.slug )
                                &&
                                ( submitContentApp.data.options[key][p].name == taxonomies.name )
                            ){
                                // filter the taxonomy if its already exists
                                let filtered = submitContentApp.data.options[key].filter( function( element ){
                                    return ( element.name != taxonomies.name ) && ( element.slug != taxonomies.slug );
                                });
                                submitContentApp.data.options[key] = filtered;
                            } else {
                                // add the taxonomy if its new to the array.
                                submitContentApp.data.options[key].push( taxonomies );
                            }
                        }
                    }
                }
            } else if( remove || ! value ){
                delete submitContentApp.data.options[key];
            } else {
                submitContentApp.data.options[key] = value;
            }
        } else {
            if( taxonomies ){
                submitContentApp.data.options[key] = [taxonomies];
            } else {
                submitContentApp.data.options[key] = value;
            }
        }
    },
    handleSubmit: function( event ){
        event.preventDefault();
        jQuery.ajax({
            type: 'post',
            url: submitContentApp.data.ajaxURL,
            data: {
                action: submitContentApp.data.action,
                options: submitContentApp.data.options
            },
            beforeSend: submitContentApp.beforeSend,
            success: submitContentApp.success,
            complete: submitContentApp.complete
        });

    },
    beforeSend: function( xhr, settings ){
        jQuery( '.notice' ).remove();
        jQuery( submitContentApp.data.button ).prop( 'disabled', true );
    },
    createResponseElement: function( response, message = null ){
        let div = '';
        let divStart = '<div class="notice notice-' + response.type + ' settings-error is-dismissible">';
        let text = '';
        let divClose = '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
        if( message ) {
            text = '<p><strong>'+ message +'</strong</p>';
            div = divStart + text + divClose;
        }
        if( typeof response.data === 'object' && response.data !== null ){
            for( let error in response.data ){
                div += divStart + '<p><strong>' + response.data[error] + '</strong></p>' + divClose;
            }
        }
        jQuery( div ).insertBefore( submitContentApp.data.form );
    },
    success: function( response ){
        if( response.type == 'warning' ){
            submitContentApp.createResponseElement( response, scJSOBJ.duplicateText );
        }
        if( response.type == 'success' ){
            submitContentApp.createResponseElement( response, scJSOBJ.updateText );
        }
        if( response.type == 'error' ){
            submitContentApp.createResponseElement( response );
        }
    },
    complete: function(){
        jQuery( submitContentApp.data.button ).removeAttr( 'disabled' );
        jQuery( 'html, body' ).animate({
            scrollTop: 0
        }, 750 );
        jQuery( 'button.notice-dismiss' ).click( submitContentApp.removeResponseElement );
    },
    removeResponseElement: function(){
        jQuery( this ).closest( '.is-dismissible' ).remove();
    }
};

let deleteShortcode = {
    init: function(){
        jQuery( '.wpbt-delete-sc' ).click( deleteShortcode.handleDelete );
    },
    handleDelete: function( event ){
        event.preventDefault();
        let shortcodeId = jQuery( this ).attr( 'scid' );
        let nonceKey = jQuery(this).attr( 'nonceKey' );
        jQuery.ajax({
            type: 'post',
            url: scJSOBJ.ajax_url,
            data: {
                id: shortcodeId,
                securityKey: nonceKey,
                action: 'sc_delete_shortcode'
            },
            success: deleteShortcode.success
        });
    },
    success: function( response ){
        let shortcodeId = response.data.rowid;
        let message = response.data.message;
        let container = '';
        container += '<td colspan="4">';
        container += '<p>';
        container += message;
        container += '</p>';
        container += '</td>';
        let rowToDelete = jQuery( 'tr#' + shortcodeId );
        
        jQuery( rowToDelete ).html( container );
        jQuery( rowToDelete ).fadeIn( function(){
            jQuery( this ).remove();
            
            let currentSC = '';
            let totalRows = jQuery( '.sc-table tbody tr' ).length;
            let shortcode_numbers = jQuery( '.sc-table td.sc-sn' );
            for( let i = 0; i < totalRows; i++ ){
                currentSC = jQuery( shortcode_numbers )[i];
                jQuery(currentSC).text( i + 1 );
            }
            if( ! totalRows ){
                let emptyMessage = response.data.tableEmpty;
                jQuery( '.sc-table tbody' ).append( emptyMessage );
            }
        });

    }
};

/**
 * kick start application!
 */
jQuery( document ).ready( submitContentApp.init );
jQuery( document ).ready( deleteShortcode.init );
