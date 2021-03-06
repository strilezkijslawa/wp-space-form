jQuery( function() {
    jQuery( document ).tooltip();

    jQuery('.wpsf-form-select').selectmenu();

    if ( typeof jQuery('#wpsfFormFieldsSorting') !== 'undefined' ) {
        jQuery('#wpsfFormFieldsSorting').sortable({
            placeholder: "ui-state-highlight",
            update: function( event, ui ) {
                let data = {
                    'action': 'wpsf_update_field_sorting',
                    'wpsf_field_id': [],
                    'wpsf_field_sorting': []
                };
                jQuery('#wpsfFormFieldsSorting').find('.wpsf-form-field').each(function(i){
                    let item = jQuery(this);
                    let position = i + 1;
                    item.data('sorting', position);
                    item.find('.wpsf-form-field-sorting').html( position );
                    data.wpsf_field_id.push( item.data('id') );
                    data.wpsf_field_sorting.push( position );
                });

                jQuery.post(ajaxObject.url, data, function(response) {
                    let msg = '';
                    if (response.success) {
                        msg = '<div class="notice notice-success is-dismissible">' + response.data.text + '</div>';
                    } else {
                        msg = '<div class="notice notice-error is-dismissible">' + response.data.text + '</div>';
                    }

                    showMessage( msg );
                });
            }
        });
        jQuery('#wpsfFormFieldsSorting').disableSelection();
    }
});

function showMessage( msg ) {
    jQuery('.wpsf-notices').html( msg ).fadeIn(400);
    setTimeout(function(){
        jQuery('.wpsf-notices').fadeOut(400);
    }, 3000);
}

function showModal( modal_id ) {
    jQuery('body').css('overflow','hidden');
    jQuery(modal_id).addClass( 'open' );
}

function closeModal( modal_id ) {
    jQuery('body').css('overflow','');
    jQuery(modal_id).removeClass( 'open' );
    jQuery(modal_id).find('.wpsf-form-input').val('');
}

jQuery('#wpsfAdminForm').on('submit',function (e) {
    e.preventDefault();

    let form = jQuery(this);
    let data = form.serialize();
    jQuery.post(ajaxObject.url, data, function(response) {
        let msg = '';
        if (response.success) {
            msg = '<div class="notice notice-success is-dismissible">' + response.data.text + '</div>';
        } else {
            msg = '<div class="notice notice-error is-dismissible">' + response.data.text + '</div>';
        }

        showMessage( msg );
    });
});

jQuery('#wpsfAddFormForm').on('submit',function (e) {
    e.preventDefault();

    let error = false;
    let form = jQuery(this);

    form.find('.has-error').removeClass('has-error');
    form.find('.required').each(function(){
        let item = jQuery(this);
        if ( item.val() == '' ) {
            item.parent().addClass('has-error');
            error = true;
        }
        if ( item.hasClass('email') ) {
            if(!checkEmail( item.val() )){
                item.parent().addClass('has-error');
                error = true;
            }
        }
    });

    if ( !error ) {
        let data = form.serialize();
        jQuery.post(ajaxObject.url, data, function (response) {
            let msg = '';
            if (response.success) {
                msg = '<div class="notice notice-success is-dismissible">' + response.data.text + '</div>';
            } else {
                msg = '<div class="notice notice-error is-dismissible">' + response.data.text + '</div>';
            }

            if (typeof response.data.form !== 'undefined') {
                let form_tr = '<tr>' +
                    '<td><input type="checkbox" name="wpsf_form_id[]" value="' + response.data.form.id + '"></td>' +
                    '<td><a href="/wp-admin/admin.php?page=wp-space-form&form_id=' + response.data.form.id + '" class="wpsf-table-link">' + response.data.form.name + '</a></td>' +
                    '<td><code>[wpsf_space_form form_id="' + response.data.form.id + '"]</code></td>' +
                    '<td><a href="#" class="wpsf-btn wpsf-table-link-deactivate">&#9679;</a><a href="#" class="wpsf-btn wpsf-table-link-delete">&times;</a></td>' +
                    '</tr>';

                jQuery('.wpsf-table').append( form_tr );
            }

            form.closest('.wpsf-modal').find('.wpsf-btn-close').click();

            showMessage(msg);
        });
    }
});

jQuery('#wpsfFormFieldsForm').on('submit',function (e) {
    e.preventDefault();

    let error = false;
    let form = jQuery(this);

    form.find('.has-error').removeClass('has-error');
    form.find('.required').each(function(){
        let item = jQuery(this);
        if ( item.val() == '' ) {
            item.parent().addClass('has-error');
            error = true;
        }
        if ( item.hasClass('email') ) {
            if(!checkEmail( item.val() )){
                item.parent().addClass('has-error');
                error = true;
            }
        }
    });

    if ( !error ) {
        let data = form.serialize();
        jQuery.post(ajaxObject.url, data, function (response) {
            let msg = '';
            if (response.success) {
                msg = '<div class="notice notice-success is-dismissible">' + response.data.text + '</div>';
            } else {
                msg = '<div class="notice notice-error is-dismissible">' + response.data.text + '</div>';
            }

            showMessage(msg);
        });
    }
});

jQuery('#wpsfAddFieldForm').on('submit',function (e) {
    e.preventDefault();

    let error = false;
    let form = jQuery(this);

    form.find('.has-error').removeClass('has-error');
    form.find('.required').each(function(){
        let item = jQuery(this);
        if ( item.val() == '' ) {
            item.parent().addClass('has-error');
            error = true;
        }
    });

    if ( !error ) {
        let data = form.serialize();
        jQuery.post(ajaxObject.url, data, function (response) {
            let msg = '';
            if (response.success) {
                msg = '<div class="notice notice-success is-dismissible">' + response.data.text + '</div>';
            } else {
                msg = '<div class="notice notice-error is-dismissible">' + response.data.text + '</div>';
            }

            if (typeof response.data.field !== 'undefined') {
                let field_block = '<div class="wpsf-form-field ui-state-default" data-id="' + response.data.field.id + '" data-sorting="' + response.data.field.sorting + '">' +
                    '<span class="ui-icon ui-icon-arrowthick-2-n-s"></span>' +
                    '<div class="wpsf-inline">' +
                    '<div class="wpsf-form-field-sorting">' + response.data.field.sorting + '</div>' +
                    '<div class="wpsf-form-field-label">' + response.data.field.label + '</div>' +
                    '<div class="wpsf-form-field-name">' + response.data.field.name + '</div>' +
                    '<div class="wpsf-form-field-name">[' + response.data.field.type + ']</div>' +
                    '<div class="wpsf-form-field-name">Required: ' + response.data.field.required + '</div>' +
                    '<div class="wpsf-form-field-name">To admin: ' + response.data.field.send_to_admin + '</div>' +
                    '</div>' +
                    '<div class="wpsf-form-field-actions wpsf-right">' +
                    '<a href="#" class="wpsf-btn wpsf-btn-edit-field">Edit</a>' +
                    '<a href="#" class="wpsf-btn wpsf-btn-delete-field">Delete</a>' +
                    '</div>' +
                    '</div>';

                if ( jQuery('#wpsfFormFieldsSorting').find('.wpsf-form-field').length == 0 ) {
                    jQuery('#wpsfFormFieldsSorting').html( field_block );
                } else {
                    jQuery('#wpsfFormFieldsSorting').append( field_block );
                }

                jQuery('#wpsfFormFieldsSorting').sortable( "refresh" );
            }

            form.closest('.wpsf-modal').find('.wpsf-btn-close').click();

            showMessage(msg);
        });
    }
});

jQuery(document).on('click','.wpsf-btn-add-form',function(){
    showModal( '#wpsfAddFormModal' );

    return false;
});

jQuery(document).on('click','.wpsf-btn-close',function(){
    let modal = jQuery( this ).closest( '.wpsf-modal' );

    closeModal( '#' + modal.attr('id') );

    return false;
});

jQuery(document).on('click','.wpsf-table-link-activate',function(){
    let link = jQuery(this);
    let data = {
        'action': 'wpsf_activate_forms',
        'wpsf_form_id': link.closest('tr').data('id')
    };
    jQuery.post(ajaxObject.url, data, function(response) {
        let msg = '';
        if (response.success) {
            msg = '<div class="notice notice-success is-dismissible">' + response.data.text + '</div>';
            link.closest('tr').removeClass('wpsf-deactivate');
            link.parent().prepend('<a href="#" class="wpsf-btn wpsf-table-link-deactivate">&#9679;</a>');
            link.remove();
        } else {
            msg = '<div class="notice notice-error is-dismissible">' + response.data.text + '</div>';
        }

        showMessage( msg );
    });

    return false;
});

jQuery(document).on('click','.wpsf-table-link-deactivate',function(){
    let link = jQuery(this);
    let data = {
        'action': 'wpsf_deactivate_forms',
        'wpsf_form_id': link.closest('tr').data('id')
    };
    jQuery.post(ajaxObject.url, data, function(response) {
        let msg = '';
        if (response.success) {
            msg = '<div class="notice notice-success is-dismissible">' + response.data.text + '</div>';
            link.closest('tr').addClass('wpsf-deactivate');
            link.parent().prepend('<a href="#" class="wpsf-btn wpsf-table-link-activate">&#9675;</a>');
            link.remove();
        } else {
            msg = '<div class="notice notice-error is-dismissible">' + response.data.text + '</div>';
        }

        showMessage( msg );
    });

    return false;
});

jQuery(document).on('click','.wpsf-table-link-delete',function(){
    let link = jQuery(this);
    var r = confirm(link.data('delete-msg'));
    if (r === true) {
        let data = {
            'action': 'wpsf_delete_forms',
            'wpsf_form_id': link.closest('tr').data('id')
        };
        jQuery.post(ajaxObject.url, data, function (response) {
            let msg = '';
            if (response.success) {
                msg = '<div class="notice notice-success is-dismissible">' + response.data.text + '</div>';
                link.closest('tr').fadeOut(400);
                setTimeout(function () {
                    link.closest('tr').remove();
                }, 1000);
            } else {
                msg = '<div class="notice notice-error is-dismissible">' + response.data.text + '</div>';
            }

            showMessage(msg);
        });
    }

    return false;
});

jQuery(document).on('click','.wpsf-form-add-field',function(){
    showModal( '#wpsfAddFieldModal' );

    return false;
});

jQuery(document).on('click','.wpsf-btn-edit-field',function(){
    let data = {
        'action': 'wpsf_get_field_data',
        'wpsf_field_id': jQuery(this).closest('.wpsf-form-field').data('id')
    };

    jQuery.post(ajaxObject.url, data, function (response) {
        if (response.success) {
            let modal = jQuery('#wpsfEditFieldModal');
            modal.find('#wpsf_edit_field_id').val( response.data.field.id );
            modal.find('#wpsf_edit_field_name').val( response.data.field.name );
            modal.find('#wpsf_edit_label').val( response.data.field.label );
            modal.find('#wpsf_edit_type option[value="' + response.data.field.type + '"]').prop('selected',true);
            modal.find('.wpsf-form-select').selectmenu('refresh');
            if ( response.data.field.required == 1 ) {
                modal.find('#wpsf_edit_required').prop('checked',true);
            } else {
                modal.find('#wpsf_edit_required').removeAttr('checked');
            }
            if ( response.data.field.send_to_admin == 1 ) {
                modal.find('#wpsf_edit_send_to_admin').prop('checked',true);
            } else {
                modal.find('#wpsf_edit_send_to_admin').removeAttr('checked');
            }
            modal.find('#wpsf_edit_form_id').val( response.data.field.form_id );

            showModal( '#wpsfEditFieldModal' );
        } else {
            msg = '<div class="notice notice-error is-dismissible">' + response.data.text + '</div>';
            showMessage(msg);
        }
    });

    return false;
});

jQuery('#wpsfEditFieldForm').on('submit',function (e) {
    e.preventDefault();

    let error = false;
    let form = jQuery(this);

    form.find('.has-error').removeClass('has-error');
    form.find('.required').each(function(){
        let item = jQuery(this);
        if ( item.val() == '' ) {
            item.parent().addClass('has-error');
            error = true;
        }
    });

    if ( !error ) {
        let data = form.serialize();
        jQuery.post(ajaxObject.url, data, function (response) {
            let msg = '';
            if (response.success) {
                msg = '<div class="notice notice-success is-dismissible">' + response.data.text + '</div>';
            } else {
                msg = '<div class="notice notice-error is-dismissible">' + response.data.text + '</div>';
            }

            if (typeof response.data.field !== 'undefined') {
                let field_block = '<div class="wpsf-form-field ui-state-default" data-id="' + response.data.field.id + '" data-sorting="' + response.data.field.sorting + '">' +
                    '<span class="ui-icon ui-icon-arrowthick-2-n-s"></span>' +
                    '<div class="wpsf-inline">' +
                    '<div class="wpsf-form-field-sorting">' + response.data.field.sorting + '</div>' +
                    '<div class="wpsf-form-field-label">' + response.data.field.label + '</div>' +
                    '<div class="wpsf-form-field-name">' + response.data.field.name + '</div>' +
                    '<div class="wpsf-form-field-name">[' + response.data.field.type + ']</div>' +
                    '<div class="wpsf-form-field-name">Required: ' + response.data.field.required + '</div>' +
                    '<div class="wpsf-form-field-name">To admin: ' + response.data.field.send_to_admin + '</div>' +
                    '</div>' +
                    '<div class="wpsf-form-field-actions wpsf-right">' +
                    '<a href="#" class="wpsf-btn wpsf-btn-edit-field">Edit</a>' +
                    '<a href="#" class="wpsf-btn wpsf-btn-delete-field">Delete</a>' +
                    '</div>' +
                    '</div>';

                jQuery('.wpsf-form-field[data-id="' + response.data.field.id + '"]').after( field_block );
                jQuery('.wpsf-form-field[data-id="' + response.data.field.id + '"]').eq(0).remove();

                jQuery('#wpsfFormFieldsSorting').sortable( "refresh" );
            }

            form.closest('.wpsf-modal').find('.wpsf-btn-close').click();

            showMessage(msg);
        });
    }
});

jQuery(document).on('click','.wpsf-btn-activate-field',function(){
    let link = jQuery(this);
    let data = {
        'action': 'wpsf_activate_fields',
        'wpsf_field_id': link.closest('.wpsf-form-field').data('id')
    };
    jQuery.post(ajaxObject.url, data, function(response) {
        let msg = '';
        if (response.success) {
            msg = '<div class="notice notice-success is-dismissible">' + response.data.text + '</div>';
            link.closest('.wpsf-form-field').removeClass('wpsf-deactivate');
            let against = link.data('against');
            link.after('<a href="#" class="wpsf-btn wpsf-btn-deactivate-field">' + against + '</a>');
            link.remove();
        } else {
            msg = '<div class="notice notice-error is-dismissible">' + response.data.text + '</div>';
        }

        showMessage( msg );
    });

    return false;
});

jQuery(document).on('click','.wpsf-btn-deactivate-field',function(){
    let link = jQuery(this);
    let data = {
        'action': 'wpsf_deactivate_fields',
        'wpsf_field_id': link.closest('.wpsf-form-field').data('id')
    };
    jQuery.post(ajaxObject.url, data, function(response) {
        let msg = '';
        if (response.success) {
            msg = '<div class="notice notice-success is-dismissible">' + response.data.text + '</div>';
            link.closest('.wpsf-form-field').addClass('wpsf-deactivate');
            let against = link.data('against');
            link.after('<a href="#" class="wpsf-btn wpsf-btn-activate-field">' + against + '</a>');
            link.remove();
        } else {
            msg = '<div class="notice notice-error is-dismissible">' + response.data.text + '</div>';
        }

        showMessage( msg );
    });

    return false;
});

jQuery(document).on('click','.wpsf-btn-delete-field',function(){
    let link = jQuery(this);
    let data = {
        'action': 'wpsf_delete_fields',
        'wpsf_field_id': link.closest('.wpsf-form-field').data('id')
    };
    jQuery.post(ajaxObject.url, data, function(response) {
        let msg = '';
        if (response.success) {
            msg = '<div class="notice notice-success is-dismissible">' + response.data.text + '</div>';
            link.closest('.wpsf-form-field').fadeOut(400);
            setTimeout(function(){
                link.closest('.wpsf-form-field').remove();
            },1000);
        } else {
            msg = '<div class="notice notice-error is-dismissible">' + response.data.text + '</div>';
        }

        showMessage( msg );
    });

    return false;
});

jQuery('#wpsfFormsForm').on('submit',function (e) {
    e.preventDefault();

    let form = jQuery(this);
    let data = form.serialize();
    jQuery.post(ajaxObject.url, data, function (response) {
        let msg = '';
        if (response.success) {
            msg = '<div class="notice notice-success is-dismissible">' + response.data.text + '</div>';
        } else {
            msg = '<div class="notice notice-error is-dismissible">' + response.data.text + '</div>';
        }

        showMessage(msg);

        window.location.reload();
    });
});

jQuery(document).on('click','.wpsf-table-link-letter-delete',function(){
    let link = jQuery(this);
    var r = confirm(link.data('delete-msg'));
    if (r === true) {
        let data = {
            'action': 'wpsf_delete_letters',
            'wpsf_letter_id': link.closest('.wpsf-letter-row').data('id')
        };
        jQuery.post(ajaxObject.url, data, function (response) {
            let msg = '';
            if (response.success) {
                msg = '<div class="notice notice-success is-dismissible">' + response.data.text + '</div>';
                if (!link.hasClass('wpsf-single-letter')) {
                    link.closest('.wpsf-letter-row').fadeOut(400);
                    setTimeout(function () {
                        link.closest('.wpsf-letter-row').remove();
                    }, 1000);
                }
            } else {
                msg = '<div class="notice notice-error is-dismissible">' + response.data.text + '</div>';
            }

            showMessage(msg);

            if (link.hasClass('wpsf-single-letter')) {
                window.location.href = '/wp-admin/admin.php?page=wpsf-sent-letters';
            }
        });
    }

    return false;
});

jQuery('#wpsfLettersForm').on('submit',function (e) {
    e.preventDefault();

    let form = jQuery(this);
    let data = form.serialize();
    jQuery.post(ajaxObject.url, data, function (response) {
        let msg = '';
        if (response.success) {
            msg = '<div class="notice notice-success is-dismissible">' + response.data.text + '</div>';
        } else {
            msg = '<div class="notice notice-error is-dismissible">' + response.data.text + '</div>';
        }

        showMessage(msg);

        window.location.reload();
    });
});