<?php
/**
 * Single form template on front
 */
?>

<div class="wpsf-form">
    <?php
    if ( !empty($this->data['form']) ) : ?>
    <form id="wpsfSendForm_<?= $this->data['form']['id'] ?>" class="wpsf-send-form" method="post" action="javascript:;" data-action="/wp-json/wpsf/v1/send">
        <h3 class="wpsf-title wpsf-center"><?= $this->data['form']['name'] ?></h3>
        <input type="hidden" id="wpsf_form_id" name="wpsf_form_id" value="<?= $this->data['form']['id'] ?>">
        <?php if ( $this->data['settings']['message_position'] == 'top' ) :?>
        <div class="wpsf-notices"></div>
        <?php endif;

        if ( !empty($this->data['form_fields']) ) :
            foreach ( $this->data['form_fields'] as $form_field ) :
                if ( !$form_field['active'] ) { continue; }
                switch ($form_field['type']) {
                    case 'email':
                        $wpsf_group_class = '';
                        $pattern = '<input id="%s" type="email" name="wpsf_%s" class="wpsf-form-input email %s">';
                        break;
                    case 'checkbox':
                        $wpsf_group_class = ' wpsf-inline';
                        $pattern = '<input id="%s" type="checkbox" name="wpsf_%s" class="wpsf-form-checkbox %s">';
                        break;
                    case 'radio':
                        $wpsf_group_class = ' wpsf-inline';
                        $pattern = '<input id="%s" type="radio" name="wpsf_%s" class="wpsf-form-checkbox %s">';
                        break;
                    case 'textarea':
                        $wpsf_group_class = '';
                        $pattern = '<textarea id="%s" name="wpsf_%s" class="wpsf-form-text %s"></textarea>';
                        break;
                    default:
                        $wpsf_group_class = '';
                        $pattern = '<input id="%s" type="text" name="wpsf_%s" class="wpsf-form-input %s">';
                }
                $required = $form_field['required'] ? 'required' : '';
                ?>
                <div class="wpsf-group<?= $wpsf_group_class ?>">
                    <label for="<?= $form_field['name'] ?>"><?= $form_field['label'] ?></label>
                    <?= wp_sprintf( $pattern, $form_field['name'], $form_field['name'], $required ); ?>
                    <?php if ( $form_field['required'] && $form_field['type'] == 'email' ) : ?>
                        <div class="wpsf-error-message"><?php _e( 'Email is empty or not valid!', 'wpsf' ); ?></div>
                    <?php elseif ( $form_field['required'] ) : ?>
                        <div class="wpsf-error-message"><?php _e( 'Field is mandatory!', 'wpsf' ); ?></div>
                    <?php endif; ?>
                </div>
            <?php endforeach;
        endif;
        ?>
        <div class="wpsf-group wpsf-center">
            <button type="submit" class="wpsf-btn wpsf-btn-send"><?php _e( 'Send', 'wpsf' ); ?></button>
        </div>
    </form>

    <?php if ( $this->data['settings']['message_position'] == 'bottom' ) :?>
        <div class="wpsf-notices"></div>
    <?php endif;
    endif;
    ?>
</div>