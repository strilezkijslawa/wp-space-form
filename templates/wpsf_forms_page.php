<?php
/**
 * List of forms
 */
?>

<div class="wrap wpsf-wrap">
    <h2><?php _e( 'Forms', 'wpsf' ); ?> <a href="#" class="wpsf-btn wpsf-btn-add-form button button-primary"><?php _e( 'Add form', 'wpsf' ); ?></a></h2>
    <br/>

    <div id="center-panel" style="width: 100%; margin: 15px auto;">
        <div class="widefat">
            <div class="wpsf-form">
                <form id="wpsfFormsForm" method="post" action="javascript:;">
                    <table class="wpsf-table">
                        <tr>
                            <th>&nbsp;</th>
                            <th><?php _e( 'Form name', 'wpsf' ) ?></th>
                            <th><?php _e( 'Shortcode', 'wpsf' ) ?></th>
                            <th><?php _e( 'Actions', 'wpsf' ) ?></th>
                        </tr>
                        <?php
                        if ( !empty($this->data['forms']) ) :
                            foreach ($this->data['forms'] as $form) :
                        ?>
                            <tr data-id="<?= $form['id'] ?>" class="<?php if ( !$form['active'] ) { echo 'wpsf-deactivate'; } ?>">
                                <td>
                                    <input type="checkbox" name="wpsf_form_id[]" value="<?= $form['id'] ?>">
                                </td>
                                <td>
                                    <a href="/wp-admin/admin.php?page=wp-space-form&form_id=<?= $form['id'] ?>" class="wpsf-table-link"><?= $form['name'] ?></a>
                                </td>
                                <td>
                                    <code>[wpsf_space_form form_id="<?= $form['id'] ?>"]</code>
                                </td>
                                <td>
                                    <?php if ( $form['active'] ) : ?>
                                        <a href="#" class="wpsf-btn wpsf-table-link-deactivate" title="<?php _e( 'Deactivate this form', 'wpsf' ) ?>">&#9679;</a>
                                    <?php else: ?>
                                        <a href="#" class="wpsf-btn wpsf-table-link-activate" title="<?php _e( 'Activate this form', 'wpsf' ) ?>">&#9675;</a>
                                    <?php endif; ?>
                                    <a href="#" class="wpsf-btn wpsf-table-link-delete" title="<?php _e( 'Delete this form', 'wpsf' ) ?>" data-delete-msg="<?php _e( 'You want to delete this form?', 'wpsf' ) ?>">&times;</a>
                                </td>
                            </tr>
                        <?php endforeach;
                        else: ?>
                            <tr>
                                <td colspan="4" style="text-align:center;font-weight:700">
                                    <?php _e( 'Forms not found', 'wpsf' ) ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </table>
                    <div class="wpsf-group">
                        <label for="wpsf_forms_action"><?php _e( 'Action', 'wpsf' ); ?></label>
                        <select id="wpsf_forms_action" name="action" class="wpsf-form-select">
                            <option>-Select action-</option>
                            <option value="wpsf_delete_forms"><?php _e( 'Delete', 'wpsf' ); ?></option>
                            <option value="wpsf_activate_forms"><?php _e( 'Activate', 'wpsf' ); ?></option>
                            <option value="wpsf_deactivate_forms"><?php _e( 'Deactivate', 'wpsf' ); ?></option>
                        </select>
                    </div>
                    <div class="wpsf-group">
                        <button type="submit" class="wpsf-btn button button-primary"><?php _e( 'Save', 'wpsf' ); ?></button>
                    </div>
                </form>

                <div class="wpsf-notices"></div>
            </div>
        </div>
    </div>

    <div id="wpsfAddFormModal" class="wpsf-modal">
        <div class="wpsf-modal-inner">
            <a href="#" class="wpsf-btn wpsf-btn-close">&times;</a>
            <div class="wpsf-modal-body">
                <h3><?php _e( 'Add new form', 'wpsf' ) ?></h3>
                <form id="wpsfAddFormForm" method="post" action="javascript:;">
                    <div class="wpsf-group">
                        <label for="wpsf_name"><?php _e( 'Name', 'wpsf' ); ?>*</label>
                        <input type="text" id="wpsf_name" name="wpsf_name" class="wpsf-form-input required">
                        <div class="wpsf-error-message"><?php _e( 'Error! Field can not be empty!', 'wpsf' ); ?></div>
                    </div>
                    <div class="wpsf-group">
                        <label for="wpsf_from_email"><?php _e( 'From email', 'wpsf' ); ?>*</label>
                        <input type="email" id="wpsf_from_email" name="wpsf_from_email" class="wpsf-form-input email required" value="<?= $this->data['settings']['from_email'] ?>">
                        <div class="wpsf-error-message"><?php _e( 'Error! Email can not be empty and must be valid!', 'wpsf' ); ?></div>
                    </div>
                    <div class="wpsf-group">
                        <label for="wpsf_to_email"><?php _e( 'To email', 'wpsf' ); ?></label>
                        <input type="email" id="wpsf_to_email" name="wpsf_to_email" class="wpsf-form-input email">
                    </div>
                    <div class="wpsf-group">
                        <label for="wpsf_subject"><?php _e( 'Subject', 'wpsf' ); ?>*</label>
                        <input type="text" id="wpsf_subject" name="wpsf_subject" class="wpsf-form-input required">
                        <div class="wpsf-error-message"><?php _e( 'Error! Field can not be empty!', 'wpsf' ); ?></div>
                    </div>
                    <div class="wpsf-group">
                        <label for="wpsf_admin_message_template"><?php _e( 'Admin message template', 'wpsf' ); ?></label>
                        <?php
                        wp_editor( $this->data['settings']['global_letter_template'], 'wpsf_admin_message_template', array(
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
                        <input type="hidden" name="action" value="wpsf_add_single_form">
                        <button type="submit" class="wpsf-btn button button-primary"><?php _e( 'Add form', 'wpsf' ); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>