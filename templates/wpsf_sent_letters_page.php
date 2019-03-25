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
                <?= print_r( $this->data['letters'], true ); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>