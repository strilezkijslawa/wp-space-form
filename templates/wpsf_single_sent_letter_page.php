<?php
/**
 * Single letter page
 */
?>

<div class="wrap wpsf-wrap">
    <a href="/wp-admin/admin.php?page=wpsf-sent-letters" class="wpsf-single-letter-back"><?php _e( 'Back to list', 'wpsf' ); ?></a>
    <br>
    <h2><?php _e( 'Letter', 'wpsf' ); ?><?= !empty($this->data['letter']) ? ' "' . $this->data['letter']['subject'] . '"' : '' ?></h2>
    <br/>

    <div class="wpsf-notices"></div>

    <div id="center-panel" style="width: 100%; margin: 15px auto;">
        <div class="widefat">
            <div class="wpsf-form">
                <?php if ( !empty($this->data['letter']) ) : ?>
                    <div class="wpsf-group wpsf-inline">
                        <div class="wpsf-name"><?php _e( 'Subject', 'wpsf' ); ?></div>
                        <div class="wpsf-value"><?= $this->data['letter']['subject'] ?></div>
                    </div>
                    <div class="wpsf-group wpsf-inline">
                        <div class="wpsf-name"><?php _e( 'From email', 'wpsf' ); ?></div>
                        <div class="wpsf-value"><?= $this->data['letter']['from_email'] ?></div>
                    </div>
                    <div class="wpsf-group wpsf-inline">
                        <div class="wpsf-name"><?php _e( 'To email', 'wpsf' ); ?></div>
                        <div class="wpsf-value"><?= $this->data['letter']['to_email'] ?></div>
                    </div>
                    <div class="wpsf-group">
                        <div class="wpsf-name"><?php _e( 'Message', 'wpsf' ); ?></div>
                        <div class="wpsf-value">
                            <pre><?= $this->data['letter']['message_template'] ?></pre>
                        </div>
                    </div>
                    <div class="wpsf-group wpsf-inline">
                        <div class="wpsf-name"><?php _e( 'Is sent?', 'wpsf' ); ?></div>
                        <div class="wpsf-value"><?= $this->data['letter']['sended'] ? __( 'Yes', 'wpsf' ) : __( 'No', 'wpsf' ) ?></div>
                    </div>
                    <div class="wpsf-group wpsf-letter-row" data-id="<?= $this->data['letter']['id'] ?>">
                        <a href="#" class="wpsf-table-link-letter-delete wpsf-single-letter" data-delete-msg="<?php _e( 'You want to delete this letter?', 'wpsf' ) ?>"><?php _e( 'Delete this letter', 'wpsf' ) ?></a>
                    </div>

                    <div class="wpsf-notices"></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>