<?php
/**
 * Sent letters
 */
?>

<div class="wrap wpsf-wrap">
    <h2><?php _e( 'Sent letters', 'wpsf' ); ?></h2>
    <br/>

    <div class="wpsf-notices"></div>

    <div id="center-panel" style="width: 100%; margin: 15px auto;">
        <div class="widefat">
            <div class="wpsf-form">
                <?php if ( !empty($this->data['letters']) ) : ?>
                    <form id="wpsfLettersForm" method="post" action="javascript:;">
                        <table class="wpsf-table">
                            <tr>
                                <th>&nbsp;</th>
                                <th><?php _e( 'Subject', 'wpsf' ) ?></th>
                                <th><?php _e( 'Send to', 'wpsf' ) ?></th>
                                <th><?php _e( 'Actions', 'wpsf' ) ?></th>
                            </tr>
                            <?php foreach ($this->data['letters'] as $letter) : ?>
                                <tr class="wpsf-letter-row" data-id="<?= $letter['id'] ?>">
                                    <td>
                                        <input type="checkbox" name="wpsf_letter_id[]" value="<?= $letter['id'] ?>">
                                    </td>
                                    <td>
                                        <a href="/wp-admin/admin.php?page=wpsf-sent-letters&letter_id=<?= $letter['id'] ?>" class="wpsf-table-link"><?= $letter['subject'] ?></a>
                                    </td>
                                    <td>
                                        <?= $letter['to_email'] ?>
                                    </td>
                                    <td>
                                        <a href="#" class="wpsf-btn wpsf-table-link-letter-delete" title="<?php _e( 'Delete this letter', 'wpsf' ) ?>" data-delete-msg="<?php _e( 'You want to delete this letter?', 'wpsf' ) ?>">&times;</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                        <div class="wpsf-group">
                            <label for="wpsf_forms_action"><?php _e( 'Action', 'wpsf' ); ?></label>
                            <select id="wpsf_forms_action" name="action" class="wpsf-form-select">
                                <option>-Select action-</option>
                                <option value="wpsf_delete_letters"><?php _e( 'Delete', 'wpsf' ); ?></option>
                            </select>
                        </div>
                        <div class="wpsf-group">
                            <button type="submit" class="wpsf-btn button button-primary"><?php _e( 'Save', 'wpsf' ); ?></button>
                        </div>
                    </form>

                    <div class="wpsf-notices"></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>