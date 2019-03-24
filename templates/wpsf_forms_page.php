<?php
/**
 * List of forms
 */
?>

<div class="wrap">
    <h2><?php _e( 'Forms', 'wpsf' ); ?></h2>
    <br/>

    <div class="wpsf-notices"></div>

    <div id="center-panel" style="width: 100%; margin: 15px auto;">
        <div class="widefat">
            <div class="wpsf-form">
                <form id="wpsfFormsForm" method="post" action="javascript:;">
                    <table class="wpsf-table">
                        <tr>
                            <th>&nbsp;</th>
                            <th><?php _e( 'Form name', 'wpsf' ) ?></th>
                            <th><?php _e( 'Actions', 'wpsf' ) ?></th>
                        </tr>
                        <?php
                        if ( !empty($forms) ) :
                            foreach ($forms as $form) :
                        ?>
                            <tr data-id="<?= $form['id'] ?>" class="<?php if ( !$form['active'] ) { echo ' wpsf-deactivate'; } ?>">
                                <td>
                                    <input type="checkbox" name="wpsf_form_id[]" value="<?= $form['id'] ?>">
                                </td>
                                <td>
                                    <a href="/wp-admin/options-general.php?page=wp-space-form&form_id=<?= $form['id'] ?>" class="wpsf-table-link"><?= $form['name'] ?></a>
                                </td>
                                <td>
                                    <?php if ( $form['active'] ) : ?>
                                        <a href="#" class="wpsf-btn wpsf-table-link-deactivate">&#9679;</a>
                                    <?php else: ?>
                                        <a href="#" class="wpsf-btn wpsf-table-link-activate">&#9675;</a>
                                    <?php endif; ?>
                                    <a href="#" class="wpsf-btn wpsf-table-link-delete">&times;</a>
                                </td>
                            </tr>
                        <?php endforeach;
                        else: ?>
                            <tr>
                                <td colspan="3">
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
                        <button type="button" class="wpsf-btn button button-primary"><?php _e( 'Save', 'wpsf' ); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>