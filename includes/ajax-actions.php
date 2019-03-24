<?php
/**
 * Ajax admin actions.
 */

if ( ! defined( 'ABSPATH' ) ) {
    die();
}

add_action( 'wp_ajax_wpsf_update_settings', 'wpsf_update_settings' );
add_action( 'wp_ajax_wpsf_delete_form', 'wpsf_delete_form' );
add_action( 'wp_ajax_wpsf_delete_forms', 'wpsf_delete_forms' );
add_action( 'wp_ajax_wpsf_activate_forms', 'wpsf_activate_forms' );
add_action( 'wp_ajax_wpsf_deactivate_forms', 'wpsf_deactivate_forms' );

/**
 * Function to accept ajax call for updating settings
 *
 * @since 1.0
 */
if ( ! function_exists( 'wpsf_update_settings' ) ) {
    function wpsf_update_settings() {
        global $wpdb;

        $inputData = array(
            'send_letters' => (bool)$_POST['wpsf_send_letters'],
            'send_letters_to_user' => (bool)$_POST['wpsf_send_letters_to_user'],
            'from_email' => trim($_POST['wpsf_from_email']),
            'global_letter_template' => $_POST['wpsf_global_letter_template'],
            'message_position' => $_POST['wpsf_message_position'],
            'success_message_color' => $_POST['wpsf_success_message_color'],
            'error_message_color' => $_POST['wpsf_error_message_color'],
            'styles' => $_POST['wpsf_styles'],
        );

        if ( class_exists('WPSF_Model') ) {
            $WPSF_Model = WPSF_Model::get_instance();
            $wpdb->update( $WPSF_Model->wpsf_settings_table, $inputData, array( 'id' => 1 ) );

            wp_send_json_success( array( 'text' => __( 'Success updated', 'wpsf' ) ) );
        }
        else {
            wp_send_json_error( array( 'text' => __( 'Updated is failed', 'wpsf' ) ) );
        }

        wp_die();
    }
}

if ( !function_exists( 'wpsf_delete_form' ) ) {
    function wpsf_delete_form()
    {
        global $wpdb;

        $deleted = [
            'id' => $_POST['wpsf_form_id']
        ];

        if ( class_exists('WPSF_Model') ) {
            $WPSF_Model = WPSF_Model::get_instance();
            $form_deleted = $wpdb->delete( $WPSF_Model->wpsf_forms_table, $deleted );
            if ( $form_deleted !== false ) {
                $form_fields_deleted = $wpdb->delete( $WPSF_Model->wpsf_form_fields_table, [ 'form_id' => $deleted['id'] ] );

                wp_send_json_success( array( 'text' => __( 'Success deleted form', 'wpsf' ) ) );
            } else {
                wp_send_json_error( array( 'text' => __( 'Delete form is failed', 'wpsf' ) ) );
            }
        }
        else {
            wp_send_json_error( array( 'text' => __( 'Delete is failed. Class WPSF_Model not exist', 'wpsf' ) ) );
        }

        wp_die();
    }
}

if ( !function_exists( 'wpsf_delete_forms' ) ) {
    function wpsf_delete_forms()
    {
        global $wpdb;

        $wpsf_form_id = $_POST['wpsf_form_id'];
        !is_array($wpsf_form_id) && $wpsf_form_id = [ $wpsf_form_id ];

        if ( class_exists('WPSF_Model') ) {
            $WPSF_Model = WPSF_Model::get_instance();
            $wpsf_form_id = implode( ',', array_map( 'absint', $wpsf_form_id ) );
            $wpdb->query( $wpdb->prepare( "DELETE FROM $WPSF_Model->wpsf_forms_table WHERE id IN($wpsf_form_id)" ) );
            $wpdb->query( $wpdb->prepare( "DELETE FROM $WPSF_Model->wpsf_form_fields_table WHERE form_id IN($wpsf_form_id)" ) );

            wp_send_json_success( array( 'text' => __( 'Success deleted forms', 'wpsf' ) ) );
        }
        else {
            wp_send_json_error( array( 'text' => __( 'Delete is failed. Class WPSF_Model not exist', 'wpsf' ) ) );
        }

        wp_die();
    }
}

if ( !function_exists( 'wpsf_activate_forms' ) ) {
    function wpsf_activate_forms()
    {
        global $wpdb;

        $wpsf_form_id = $_POST['wpsf_form_id'];
        !is_array($wpsf_form_id) && $wpsf_form_id = [ $wpsf_form_id ];

        if ( class_exists('WPSF_Model') ) {
            $WPSF_Model = WPSF_Model::get_instance();
            $wpsf_form_id = implode( ',', array_map( 'absint', $wpsf_form_id ) );
            $wpdb->query( $wpdb->prepare( "UPDATE $WPSF_Model->wpsf_forms_table SET active = 1 WHERE id IN($wpsf_form_id)" ) );

            wp_send_json_success( array( 'text' => __( 'Success activate forms', 'wpsf' ) ) );
        }
        else {
            wp_send_json_error( array( 'text' => __( 'Activate is failed. Class WPSF_Model not exist', 'wpsf' ) ) );
        }

        wp_die();
    }
}

if ( !function_exists( 'wpsf_deactivate_forms' ) ) {
    function wpsf_deactivate_forms()
    {
        global $wpdb;

        $wpsf_form_id = $_POST['wpsf_form_id'];
        !is_array($wpsf_form_id) && $wpsf_form_id = [ $wpsf_form_id ];

        if ( class_exists('WPSF_Model') ) {
            $WPSF_Model = WPSF_Model::get_instance();
            $wpsf_form_id = implode( ',', array_map( 'absint', $wpsf_form_id ) );
            $wpdb->query( $wpdb->prepare( "UPDATE $WPSF_Model->wpsf_forms_table SET active = 0 WHERE id IN($wpsf_form_id)" ) );

            wp_send_json_success( array( 'text' => __( 'Success deactivate forms', 'wpsf' ) ) );
        }
        else {
            wp_send_json_error( array( 'text' => __( 'Deactivate is failed. Class WPSF_Model not exist', 'wpsf' ) ) );
        }

        wp_die();
    }
}