function showMessage( form, msg ) {
    form.parent().find('.wpsf-notices').html( msg ).fadeIn(400);
    setTimeout(function(){
        form.parent().find('.wpsf-notices').fadeOut(400);
    }, 3000);
}

jQuery('.wpsf-send-form').on('submit',function (e) {
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
        jQuery.post(form.data('action'), data, function (response) {

            let msg = '';
            if (response.success) {
                msg = '<div class="notice notice-success is-dismissible">' + response.data.text + '</div>';
                form.find('input[type="text"],input[type="email"],textarea').val('');
                form.find('input[type="radio"],input[type="checkbox"]').removeAttr('checked');
            } else {
                msg = '<div class="notice notice-error is-dismissible">' + response.data.text + '</div>';
            }

            showMessage( form, msg );
        });
    }
});