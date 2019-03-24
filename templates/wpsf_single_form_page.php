<?php
/**
 * Show single form setting
 */
?>

<div class="wrap">
    <h2><?php _e( 'Form fields', 'wpsf' ); ?></h2>
    <br/>

    <div class="wpsf-notices"></div>

    <div id="center-panel" style="width: 100%; margin: 15px auto;">
        <div class="widefat">
            <div class="wpsf-form">
                <form id="wpsfFormFieldsForm" method="post" action="javascript:;">
                    <?php
                    if ( !empty($form) ) :
                    ?>
                        <div class="wpsf-group">
                            <label for="wpsf_name"><?php _e( 'Name', 'wpsf' ); ?></label>
                            <input type="text" id="wpsf_name" name="wpsf_name" class="wpsf-form-input" value="<?= $form['name'] ?>">
                        </div>
                        <div class="wpsf-group">
                            <label for="wpsf_from_email"><?php _e( 'From email', 'wpsf' ); ?></label>
                            <input type="email" id="wpsf_from_email" name="wpsf_from_email" class="wpsf-form-input email" value="<?= !empty($form['from_email'])?$form['from_email']:$this->settings['from_email'] ?>">
                        </div>
                        <div class="wpsf-group">
                            <label for="wpsf_to_email"><?php _e( 'To email', 'wpsf' ); ?></label>
                            <input type="email" id="wpsf_to_email" name="wpsf_to_email" class="wpsf-form-input email" value="<?= $form['to_email'] ?>">
                        </div>
                        <div class="wpsf-group">
                            <label for="wpsf_subject"><?php _e( 'Subject', 'wpsf' ); ?></label>
                            <input type="text" id="wpsf_subject" name="wpsf_subject" class="wpsf-form-input" value="<?= $form['subject'] ?>">
                        </div>
                        <div class="wpsf-group">
                            <label for="wpsf_message_template"><?php _e( 'Message template', 'wpsf' ); ?></label>
                            <?php
                            wp_editor( $form['message_template'], 'wpsf_message_template', array(
                                'wpautop'       => 0,
                                'media_buttons' => 0,
                                'textarea_name' => 'wpsf_message_template',
                                'textarea_rows' => 20,
                                'tabindex'      => null,
                                'editor_css'    => '',
                                'editor_class'  => '',
                                'teeny'         => 0,
                                'dfw'           => 0,
                                'tinymce'       => 0,
                                'quicktags'     => 0,
                                'drag_drop_upload' => false
                            ) );
                            ?>
                        </div>
                        <div class="wpsf-group">
                            <label for="wpsf_admin_message_template"><?php _e( 'Admin message template', 'wpsf' ); ?></label>
                            <?php
                            wp_editor( $form['admin_message_template'], 'wpsf_admin_message_template', array(
                                'wpautop'       => 0,
                                'media_buttons' => 0,
                                'textarea_name' => 'wpsf_admin_message_template',
                                'textarea_rows' => 20,
                                'tabindex'      => null,
                                'editor_css'    => '',
                                'editor_class'  => '',
                                'teeny'         => 0,
                                'dfw'           => 0,
                                'tinymce'       => 0,
                                'quicktags'     => 0,
                                'drag_drop_upload' => false
                            ) );
                            ?>
                        </div>
                        <div class="wpsf-group">
                            <div id="wpsfFormFieldsSorting" class="wpsf-form-fields">
                                <?php
                                if ( !empty($form_fields) ) :
                                    foreach ( $form_fields as $form_field ) : ?>
                                    <div class="wpsf-form-field">

                                    </div>
                                <?php endforeach;
                                else: ?>
                                <p><?php _e( 'You need to add any fields to the form', 'wpsf' ) ?></p>
                                <?php
                                endif;
                                ?>
                            </div>
                            <a href="#" class="wpsf-btn wpsf-form-add-field"><?php _e( 'Add field', 'wpsf' ) ?></a>
                        </div>
                        <div class="wpsf-group">
                            <input type="hidden" name="wpsf_id" value="<?= $form['id'] ?>">
                            <input type="hidden" name="action" value="wpsf_update_single_form">
                            <button type="button" class="wpsf-btn button button-primary"><?php _e( 'Save', 'wpsf' ); ?></button>
                        </div>
                    <?php
                    endif;
                    ?>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="wpsfAddFieldModal" class="wpsf-modal">
    <div class="wpsf-modal-inner">
        <div class="wpsf-modal-body">
            <h3><?php _e( 'Add field to this form', 'wpsf' ) ?></h3>
            <form id="wpsfAddFieldForm" method="post" action="javascript:;">
                <div class="wpsf-group">
                    <label for="wpsf_name"><?php _e( 'Field name* (only latin, without spaces, for multiple use [])', 'wpsf' ); ?></label>
                    <input type="text" id="wpsf_name" name="wpsf_name" class="wpsf-form-input required">
                </div>
                <div class="wpsf-group">
                    <label for="wpsf_label"><?php _e( 'Field label', 'wpsf' ); ?></label>
                    <input type="text" id="wpsf_label" name="wpsf_label" class="wpsf-form-input">
                </div>
                <div class="wpsf-group">
                    <label for="wpsf_type"><?php _e( 'Field type*', 'wpsf' ); ?></label>
                    <select id="wpsf_type" name="wpsf_type" class="wpsf-form-select required">
                        <option value="text" selected><?php _e( 'Text one string', 'wpsf' ); ?></option>
                        <option value="email"><?php _e( 'Email', 'wpsf' ); ?></option>
                        <option value="checkbox"><?php _e( 'Checkbox', 'wpsf' ); ?></option>
                        <option value="radio"><?php _e( 'Radio', 'wpsf' ); ?></option>
                        <option value="textarea"><?php _e( 'Text area', 'wpsf' ); ?></option>
                    </select>
                </div>
                <div class="wpsf-group">
                    <label for="wpsf_required"><?php _e( 'Field is mandatory?', 'wpsf' ); ?></label>
                    <input type="checkbox" id="wpsf_required" name="wpsf_required" class="wpsf-form-checkbox" value="1" checked>
                </div>
                <div class="wpsf-group">
                    <input type="hidden" name="wpsf_form_id" value="<?= !empty($form)?$form['id']:'' ?>">
                    <input type="hidden" name="action" value="wpsf_add_form_field">
                    <button type="button" class="wpsf-btn button button-primary"><?php _e( 'Add', 'wpsf' ); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>