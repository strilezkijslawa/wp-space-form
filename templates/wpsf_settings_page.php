<?php
/**
 * WPSF settings page
 */

?>

<div class="wrap">
    <h2><?php _e( 'Settings for', 'wpsf' ); ?> <?=WPSF_NAME?></h2>
    <br/>

    <div class="wpsf-notices"></div>

    <div id="center-panel" style="width: 100%; margin: 15px auto;">
        <div class="widefat">
            <div class="wpsf-form">
                <form id="wpsfAdminForm" method="POST" action="javascript:;">
                    <div class="wpsf-group">
                        <label for="wpsf_send_letters"><?php _e( 'Send letter to admin?', 'wpsf' ); ?></label>
                        <input type="checkbox" id="wpsf_send_letters" name="wpsf_send_letters" class="wpsf-form-check" value="1" <?php if ( $this->settings['send_letters'] ) { echo 'checked'; } ?>>
                    </div>
                    <div class="wpsf-group">
                        <label for="wpsf_send_letters_to_user"><?php _e( 'Send letter to user?', 'wpsf' ); ?></label>
                        <input type="checkbox" id="wpsf_send_letters_to_user" name="wpsf_send_letters_to_user" class="wpsf-form-check" value="1" <?php if ( $this->settings['send_letters_to_user'] ) { echo 'checked'; } ?>>
                    </div>
                    <div class="wpsf-group">
                        <label for="wpsf_from_email"><?php _e( 'From email', 'wpsf' ); ?></label>
                        <input type="email" id="wpsf_from_email" name="wpsf_from_email" class="wpsf-form-input" value="<?=$this->settings['from_email']?>">
                    </div>
                    <div class="wpsf-group">
                        <label for="wpsf_message_position"><?php _e( 'Success/error message position', 'wpsf' ); ?></label>
                        <select id="wpsf_message_position" name="wpsf_message_position" class="wpsf-form-select">
                            <?php
                            foreach ( $this->wpsf_message_positions as $position ) :
                                $selectedAttr = $this->settings['message_position'] == $position ? ' selected' : ''; ?>
                                <option value="<?=$position?>"<?=$selectedAttr?>><?php _e( ucfirst($position), 'wpsf' ); ?></option>
                            <?php
                            endforeach;
                            ?>
                        </select>
                    </div>
                    <div class="wpsf-group">
                        <label for="wpsf_success_message_color"><?php _e( 'Success color', 'wpsf' ); ?></label>
                        <input type="text" id="wpsf_success_message_color" name="wpsf_success_message_color" class="wpsf-form-input wpsf-color jscolor" value="<?=$this->settings['success_message_color']?>">
                    </div>
                    <div class="wpsf-group">
                        <label for="wpsf_error_message_color"><?php _e( 'Error color', 'wpsf' ); ?></label>
                        <input type="text" id="wpsf_error_message_color" name="wpsf_error_message_color" class="wpsf-form-input wpsf-color jscolor" value="<?=$this->settings['error_message_color']?>">
                    </div>
                    <?php if ( !empty( $this->wpsf_letter_templates ) ) : ?>
                        <div class="wpsf-group">
                            <label for="wpsf_global_letter_template"><?php _e( 'Admin letter template', 'wpsf' ); ?></label>
                            <?php
                            wp_editor( $this->settings['global_letter_template'], 'wpsf_global_letter_template', array(
                                'wpautop'       => 0,
                                'media_buttons' => 0,
                                'textarea_name' => 'wpsf_global_letter_template',
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
                    <?php endif; ?>
                    <div class="wpsf-group">
                        <label for="wpsf_styles"><?php _e( 'Custom styles', 'wpsf' ); ?></label>
                        <?php
                        wp_editor( $this->settings['styles'], 'wpsf_styles', array(
                            'wpautop'       => 0,
                            'media_buttons' => 0,
                            'textarea_name' => 'wpsf_styles',
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
                        <input type="hidden" name="action" value="wpsf_update_settings">
                        <button type="button" class="wpsf-btn button button-primary"><?php _e( 'Save', 'wpsf' ); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>