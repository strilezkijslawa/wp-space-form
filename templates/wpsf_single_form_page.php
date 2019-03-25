<?php
/**
 * Show single form setting
 */
?>

<div class="wrap wpsf-wrap">
    <a href="/wp-admin/admin.php?page=wp-space-form"><?php _e( 'Back to list', 'wpsf' ); ?></a>
    <br>
    <h2><?php _e( 'Form fields', 'wpsf' ); ?><?= !empty($this->data['form'])?' "'.$this->data['form']['name'].'"':'' ?></h2>
    <br/>

    <div id="center-panel" style="width: 100%; margin: 15px auto;">
        <div class="widefat">
            <div class="wpsf-form">
                <form id="wpsfFormFieldsForm" method="post" action="javascript:;">
                    <?php
                    if ( !empty($this->data['form']) ) :
                    ?>
                        <div class="wpsf-group">
                            <label for="wpsf_name"><?php _e( 'Name', 'wpsf' ); ?></label>
                            <input type="text" id="wpsf_name" name="wpsf_name" class="wpsf-form-input" value="<?= $this->data['form']['name'] ?>">
                        </div>
                        <div class="wpsf-group">
                            <label for="wpsf_from_email"><?php _e( 'From email', 'wpsf' ); ?></label>
                            <input type="email" id="wpsf_from_email" name="wpsf_from_email" class="wpsf-form-input email" value="<?= !empty($this->data['form']['from_email'])?$this->data['form']['from_email']:$this->data['settings']['from_email'] ?>">
                        </div>
                        <div class="wpsf-group">
                            <label for="wpsf_to_email"><?php _e( 'To email', 'wpsf' ); ?></label>
                            <input type="email" id="wpsf_to_email" name="wpsf_to_email" class="wpsf-form-input email" value="<?= $this->data['form']['to_email'] ?>">
                        </div>
                        <div class="wpsf-group">
                            <label for="wpsf_subject"><?php _e( 'Subject', 'wpsf' ); ?></label>
                            <input type="text" id="wpsf_subject" name="wpsf_subject" class="wpsf-form-input" value="<?= $this->data['form']['subject'] ?>">
                        </div>
                        <div class="wpsf-group">
                            <label for="wpsf_message_template"><?php _e( 'Message template', 'wpsf' ); ?></label>
                            <?php
                            wp_editor( $this->data['form']['message_template'], 'wpsf_message_template', array(
                                'wpautop'       => 0,
                                'media_buttons' => 0,
                                'textarea_name' => 'wpsf_message_template',
                                'textarea_rows' => 15,
                                'tabindex'      => null,
                                'editor_css'    => '',
                                'editor_class'  => 'wpsf-form-text',
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
                            wp_editor( $this->data['form']['admin_message_template'], 'wpsf_admin_message_template', array(
                                'wpautop'       => 0,
                                'media_buttons' => 0,
                                'textarea_name' => 'wpsf_admin_message_template',
                                'textarea_rows' => 15,
                                'tabindex'      => null,
                                'editor_css'    => '',
                                'editor_class'  => 'wpsf-form-text',
                                'teeny'         => 0,
                                'dfw'           => 0,
                                'tinymce'       => 0,
                                'quicktags'     => 0,
                                'drag_drop_upload' => false
                            ) );
                            ?>
                        </div>
                        <div class="wpsf-group">
                            <label><?php _e( 'Form fields', 'wpsf' ); ?></label>
                            <div id="wpsfFormFieldsSorting" class="wpsf-form-fields">
                                <?php
                                if ( !empty($this->data['form_fields']) ) :
                                    foreach ( $this->data['form_fields'] as $form_field ) : ?>
                                    <div class="wpsf-form-field ui-state-default<?php if ( !$form_field['active'] ) { echo ' wpsf-deactivate'; } ?>" data-id="<?= $form_field['id'] ?>" data-sorting="<?= $form_field['sorting'] ?>">
                                        <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                                        <div class="wpsf-inline">
                                            <div class="wpsf-form-field-sorting"><?= $form_field['sorting'] ?></div>
                                            <div class="wpsf-form-field-label"><?= $form_field['label'] ?></div>
                                            <div class="wpsf-form-field-name"><?= $form_field['name'] ?></div>
                                        </div>
                                        <div class="wpsf-form-field-actions wpsf-right">
                                            <a href="#" class="wpsf-btn wpsf-btn-edit-field"><?php _e( 'Edit field', 'wpsf' ); ?></a>
                                            <?php if ( $form_field['active'] ) : ?>
                                                <a href="#" class="wpsf-btn wpsf-btn-deactivate-field" data-against="<?php _e( 'Activate', 'wpsf' ); ?>"><?php _e( 'Deactivate', 'wpsf' ); ?></a>
                                            <?php else: ?>
                                                <a href="#" class="wpsf-btn wpsf-btn-activate-field" data-against="<?php _e( 'Deactivate', 'wpsf' ); ?>"><?php _e( 'Activate', 'wpsf' ); ?></a>
                                            <?php endif; ?>
                                            <a href="#" class="wpsf-btn wpsf-btn-delete-field"><?php _e( 'Delete', 'wpsf' ); ?></a>
                                        </div>
                                    </div>
                                <?php endforeach;
                                else: ?>
                                <p><?php _e( 'You need to add any fields to the form', 'wpsf' ) ?></p>
                                <?php
                                endif;
                                ?>
                            </div>
                            <div class="wpsf-center">
                                <a href="#" class="wpsf-btn wpsf-form-add-field button"><?php _e( 'Add field', 'wpsf' ) ?></a>
                            </div>
                        </div>
                        <div class="wpsf-group">
                            <input type="hidden" name="wpsf_id" value="<?= $this->data['form']['id'] ?>">
                            <input type="hidden" name="action" value="wpsf_update_single_form">
                            <button type="submit" class="wpsf-btn button button-primary"><?php _e( 'Save', 'wpsf' ); ?></button>
                        </div>
                    <?php
                    endif;
                    ?>
                </form>

                <div class="wpsf-notices"></div>
            </div>
        </div>
    </div>

    <div id="wpsfAddFieldModal" class="wpsf-modal">
        <div class="wpsf-modal-inner">
            <a href="#" class="wpsf-btn wpsf-btn-close">&times;</a>
            <div class="wpsf-modal-body">
                <h3><?php _e( 'Add field to this form', 'wpsf' ) ?></h3>
                <form id="wpsfAddFieldForm" method="post" action="javascript:;">
                    <div class="wpsf-group">
                        <label for="wpsf_field_name"><?php _e( 'Field name', 'wpsf' ); ?>*</label>
                        <input type="text" autocomplete="off" id="wpsf_field_name" name="wpsf_name" class="wpsf-form-input required" title="<?php _e( 'Only latin, without spaces, for multiple use []', 'wpsf' ); ?>">
                        <div class="wpsf-error-message"><?php _e( 'Error! Field can not be empty!', 'wpsf' ); ?></div>
                    </div>
                    <div class="wpsf-group">
                        <label for="wpsf_label"><?php _e( 'Field label', 'wpsf' ); ?></label>
                        <input type="text" autocomplete="off" id="wpsf_label" name="wpsf_label" class="wpsf-form-input">
                    </div>
                    <div class="wpsf-group wpsf-group-inline">
                        <label for="wpsf_type"><?php _e( 'Field type', 'wpsf' ); ?>*</label>
                        <select id="wpsf_type" name="wpsf_type" class="wpsf-form-select required">
                            <option value="text" selected><?php _e( 'Text one string', 'wpsf' ); ?></option>
                            <option value="email"><?php _e( 'Email', 'wpsf' ); ?></option>
                            <option value="checkbox"><?php _e( 'Checkbox', 'wpsf' ); ?></option>
                            <option value="radio"><?php _e( 'Radio', 'wpsf' ); ?></option>
                            <option value="textarea"><?php _e( 'Text area', 'wpsf' ); ?></option>
                        </select>
                        <div class="wpsf-error-message"><?php _e( 'Error! You need select field type!', 'wpsf' ); ?></div>
                    </div>
                    <div class="wpsf-group wpsf-group-inline">
                        <label for="wpsf_required"><?php _e( 'Field is mandatory?', 'wpsf' ); ?></label>
                        <input type="checkbox" id="wpsf_required" name="wpsf_required" class="wpsf-form-checkbox" value="1">
                    </div>
                    <div class="wpsf-group wpsf-group-inline">
                        <label for="wpsf_send_to_admin"><?php _e( 'Send to admin?', 'wpsf' ); ?></label>
                        <input type="checkbox" id="wpsf_send_to_admin" name="wpsf_send_to_admin" class="wpsf-form-checkbox" value="1" checked title="<?php _e( 'If not checked, field value not send to admin', 'wpsf' ); ?>">
                    </div>
                    <div class="wpsf-group wpsf-group-inline">
                        <label for="wpsf_send_to_user"><?php _e( 'Send to user?', 'wpsf' ); ?></label>
                        <input type="checkbox" id="wpsf_send_to_user" name="wpsf_send_to_user" class="wpsf-form-checkbox" value="1" checked title="<?php _e( 'If not checked, field value not send to user', 'wpsf' ); ?>">
                    </div>
                    <div class="wpsf-group">
                        <input type="hidden" id="wpsf_form_id" name="wpsf_form_id" value="<?= !empty($this->data['form'])?$this->data['form']['id']:'' ?>">
                        <input type="hidden" name="action" value="wpsf_add_form_field">
                        <button type="submit" class="wpsf-btn button button-primary"><?php _e( 'Add field', 'wpsf' ); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="wpsfEditFieldModal" class="wpsf-modal">
        <div class="wpsf-modal-inner">
            <a href="#" class="wpsf-btn wpsf-btn-close">&times;</a>
            <div class="wpsf-modal-body">
                <h3><?php _e( 'Edit field', 'wpsf' ) ?></h3>
                <form id="wpsfEditFieldForm" method="post" action="javascript:;">
                    <div class="wpsf-group">
                        <label for="wpsf_edit_field_name"><?php _e( 'Field name', 'wpsf' ); ?>*</label>
                        <input type="text" autocomplete="off" id="wpsf_edit_field_name" name="wpsf_name" class="wpsf-form-input required" title="<?php _e( 'Only latin, without spaces, for multiple use []', 'wpsf' ); ?>">
                        <div class="wpsf-error-message"><?php _e( 'Error! Field can not be empty!', 'wpsf' ); ?></div>
                    </div>
                    <div class="wpsf-group">
                        <label for="wpsf_edit_label"><?php _e( 'Field label', 'wpsf' ); ?></label>
                        <input type="text" autocomplete="off" id="wpsf_edit_label" name="wpsf_label" class="wpsf-form-input">
                    </div>
                    <div class="wpsf-group wpsf-group-inline">
                        <label for="wpsf_edit_type"><?php _e( 'Field type', 'wpsf' ); ?>*</label>
                        <select id="wpsf_edit_type" name="wpsf_type" class="wpsf-form-select required">
                            <option value="text" selected><?php _e( 'Text one string', 'wpsf' ); ?></option>
                            <option value="email"><?php _e( 'Email', 'wpsf' ); ?></option>
                            <option value="checkbox"><?php _e( 'Checkbox', 'wpsf' ); ?></option>
                            <option value="radio"><?php _e( 'Radio', 'wpsf' ); ?></option>
                            <option value="textarea"><?php _e( 'Text area', 'wpsf' ); ?></option>
                        </select>
                        <div class="wpsf-error-message"><?php _e( 'Error! You need select field type!', 'wpsf' ); ?></div>
                    </div>
                    <div class="wpsf-group wpsf-group-inline">
                        <label for="wpsf_edit_required"><?php _e( 'Field is mandatory?', 'wpsf' ); ?></label>
                        <input type="checkbox" id="wpsf_edit_required" name="wpsf_required" class="wpsf-form-checkbox" value="1">
                    </div>
                    <div class="wpsf-group wpsf-group-inline">
                        <label for="wpsf_edit_send_to_admin"><?php _e( 'Send to admin?', 'wpsf' ); ?></label>
                        <input type="checkbox" id="wpsf_edit_send_to_admin" name="wpsf_send_to_admin" class="wpsf-form-checkbox" value="1" checked title="<?php _e( 'If not checked, field value not send to admin', 'wpsf' ); ?>">
                    </div>
                    <div class="wpsf-group wpsf-group-inline">
                        <label for="wpsf_edit_send_to_user"><?php _e( 'Send to user?', 'wpsf' ); ?></label>
                        <input type="checkbox" id="wpsf_edit_send_to_user" name="wpsf_send_to_user" class="wpsf-form-checkbox" value="1" checked title="<?php _e( 'If not checked, field value not send to user', 'wpsf' ); ?>">
                    </div>
                    <div class="wpsf-group">
                        <input type="hidden" id="wpsf_edit_field_id" name="wpsf_field_id">
                        <input type="hidden" id="wpsf_edit_form_id" name="wpsf_form_id" value="<?= !empty($this->data['form'])?$this->data['form']['id']:'' ?>">
                        <input type="hidden" name="action" value="wpsf_update_form_field">
                        <button type="submit" class="wpsf-btn button button-primary"><?php _e( 'Update field', 'wpsf' ); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>