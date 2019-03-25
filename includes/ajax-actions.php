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

add_action( 'wp_ajax_wpsf_add_single_form', 'wpsf_add_single_form' );
add_action( 'wp_ajax_wpsf_update_single_form', 'wpsf_update_single_form' );

add_action( 'wp_ajax_wpsf_add_form_field', 'wpsf_add_form_field' );
add_action( 'wp_ajax_wpsf_update_form_field', 'wpsf_update_form_field' );
add_action( 'wp_ajax_wpsf_update_field_sorting', 'wpsf_update_field_sorting' );
add_action( 'wp_ajax_wpsf_activate_fields', 'wpsf_activate_fields' );
add_action( 'wp_ajax_wpsf_deactivate_fields', 'wpsf_deactivate_fields' );
add_action( 'wp_ajax_wpsf_delete_fields', 'wpsf_delete_fields' );
add_action( 'wp_ajax_wpsf_get_field_data', 'wpsf_get_field_data' );

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
            'global_letter_template' => stripslashes( trim( $_POST['wpsf_global_letter_template'] ) ),
            'message_position' => $_POST['wpsf_message_position'],
            'success_message_color' => $_POST['wpsf_success_message_color'],
            'error_message_color' => $_POST['wpsf_error_message_color'],
            'styles' => stripslashes( trim( $_POST['wpsf_styles'] ) ),
        );

        if ( class_exists('WPSF_Model') ) {
            $WPSF_Model = WPSF_Model::get_instance();
            $wpdb->update( $WPSF_Model->wpsf_settings_table, $inputData, array( 'id' => 1 ) );

            wp_send_json_success( array( 'text' => __( 'Success settings updated', 'wpsf' ) ) );
        }
        else {
            wp_send_json_error( array( 'text' => __( 'Update settings is failed', 'wpsf' ) ) );
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
            wp_send_json_error( array( 'text' => __( 'Delete is failed. Class WPSF_Controller not exist', 'wpsf' ) ) );
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
            wp_send_json_error( array( 'text' => __( 'Delete is failed. Class WPSF_Controller not exist', 'wpsf' ) ) );
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
            wp_send_json_error( array( 'text' => __( 'Activate is failed. Class WPSF_Controller not exist', 'wpsf' ) ) );
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
            wp_send_json_error( array( 'text' => __( 'Deactivate is failed. Class WPSF_Controller not exist', 'wpsf' ) ) );
        }

        wp_die();
    }
}

if ( !function_exists( 'wpsf_add_single_form' ) ) {
    function wpsf_add_single_form()
    {
        global $wpdb;

        $inputData = array(
            'name' => sanitize_text_field( $_POST['wpsf_name'] ),
            'from_email' => sanitize_text_field( $_POST['wpsf_from_email'] ),
            'to_email' => sanitize_text_field( $_POST['wpsf_to_email'] ),
            'subject' => sanitize_text_field( $_POST['wpsf_subject'] ),
            'message_template' => stripslashes( trim( $_POST['wpsf_message_template'] ) ),
            'admin_message_template' => stripslashes( trim( $_POST['wpsf_admin_message_template'] ) )
        );

        if ( class_exists('WPSF_Model') ) {
            $WPSF_Model = WPSF_Model::get_instance();
            $inserted = $wpdb->insert( $WPSF_Model->wpsf_forms_table, $inputData );

            if ( $inserted !== false ) {
                wp_send_json_success(array('text' => __('Success create form', 'wpsf'), 'form' => $WPSF_Model->get_wpsf_form( $wpdb->insert_id )));
            } else {
                wp_send_json_error( array( 'text' => __( 'Create form is failed', 'wpsf' ) ) );
            }
        }
        else {
            wp_send_json_error( array( 'text' => __( 'Create form is failed. WPSF_Model not exist', 'wpsf' ) ) );
        }

        wp_die();
    }
}

if ( !function_exists( 'wpsf_update_single_form' ) ) {
    function wpsf_update_single_form()
    {
        global $wpdb;

        if ( class_exists('WPSF_Model') ) {
            $WPSF_Model = WPSF_Model::get_instance();
            $inputData = array(
                'name' => sanitize_text_field( $_POST['wpsf_name'] ),
                'from_email' => sanitize_text_field( $_POST['wpsf_from_email'] ),
                'to_email' => sanitize_text_field( $_POST['wpsf_to_email'] ),
                'subject' => sanitize_text_field( $_POST['wpsf_subject'] ),
                'message_template' => stripslashes( trim( $_POST['wpsf_message_template'] ) ),
                'admin_message_template' => stripslashes( trim( $_POST['wpsf_admin_message_template'] ) )
            );
            $updated = $wpdb->update( $WPSF_Model->wpsf_forms_table, $inputData, array( 'id' => (int) $_POST['wpsf_id'] ) );

            if ( $updated !== false ) {
                wp_send_json_success(array( 'text' => __('Success updated', 'wpsf'), 'form' => $WPSF_Model->get_wpsf_form( (int) $_POST['wpsf_id'] ) ));
            } else {
                wp_send_json_error( array( 'text' => __( 'Updated is failed', 'wpsf' ) ) );
            }
        }
        else {
            wp_send_json_error( array( 'text' => __( 'Update is failed. WPSF_Model not exist', 'wpsf' ) ) );
        }

        wp_die();
    }
}

if ( !function_exists( 'wpsf_add_form_field' ) ) {
    function wpsf_add_form_field()
    {
        global $wpdb;

        if ( class_exists('WPSF_Model') ) {
            $WPSF_Model = WPSF_Model::get_instance();
            $name = sanitize_text_field( $_POST['wpsf_name'] );
            $name = $WPSF_Model->sanitize_name_with_translit( $name );
            $inputData = array(
                'name' => $name,
                'label' => sanitize_text_field( $_POST['wpsf_label'] ),
                'type' => $_POST['wpsf_type'],
                'required' => (bool) $_POST['wpsf_required'],
                'send_to_admin' => (bool) $_POST['wpsf_send_to_admin'],
                'send_to_user' => (bool) $_POST['wpsf_send_to_user'],
                'form_id' => (int) $_POST['wpsf_form_id']
            );
            $inserted = $wpdb->insert( $WPSF_Model->wpsf_form_fields_table, $inputData );

            if ( $inserted !== false ) {
                wp_send_json_success(array('text' => __('Success create form', 'wpsf'), 'field' => $WPSF_Model->get_wpsf_form_single_field( $wpdb->insert_id )));
            } else {
                wp_send_json_error( array( 'text' => __( 'Create form is failed', 'wpsf' ) ) );
            }
        }
        else {
            wp_send_json_error( array( 'text' => __( 'Create form is failed. WPSF_Model not exist', 'wpsf' ) ) );
        }

        wp_die();
    }
}

if ( !function_exists( 'wpsf_update_form_field' ) ) {
    function wpsf_update_form_field()
    {
        global $wpdb;

        if ( class_exists('WPSF_Model') ) {
            $WPSF_Model = WPSF_Model::get_instance();
            $name = sanitize_text_field( $_POST['wpsf_name'] );
            $name = $WPSF_Model->sanitize_name_with_translit( $name );
            $inputData = array(
                'name' => $name,
                'label' => sanitize_text_field( $_POST['wpsf_label'] ),
                'type' => $_POST['wpsf_type'],
                'required' => (bool) $_POST['wpsf_required'],
                'send_to_admin' => (bool) $_POST['wpsf_send_to_admin'],
                'send_to_user' => (bool) $_POST['wpsf_send_to_user'],
                'form_id' => (int) $_POST['wpsf_form_id']
            );

            if ( empty($_POST['wpsf_field_id']) ) {
                wp_send_json_error(array('text' => __('Updated is failed', 'wpsf')));
            } else {
                $updated = $wpdb->update($WPSF_Model->wpsf_form_fields_table, $inputData, array('id' => (int)$_POST['wpsf_field_id']));

                if ($updated !== false) {
                    wp_send_json_success(array('text' => __('Success updated', 'wpsf'), 'field' => $WPSF_Model->get_wpsf_form_single_field((int)$_POST['wpsf_field_id'])));
                } else {
                    wp_send_json_error(array('text' => __('Updated is failed', 'wpsf')));
                }
            }
        }
        else {
            wp_send_json_error( array( 'text' => __( 'Update is failed. WPSF_Model not exist', 'wpsf' ) ) );
        }

        wp_die();
    }
}

if ( !function_exists( 'wpsf_get_field_data' ) ) {
    function wpsf_get_field_data()
    {
        global $wpdb;
        if ( class_exists('WPSF_Model') ) {
            $WPSF_Model = WPSF_Model::get_instance();
            $field = $wpdb->get_row( "SELECT * FROM $WPSF_Model->wpsf_form_fields_table WHERE id = " . (int) $_POST['wpsf_field_id'], ARRAY_A );

            if ( $field !== null ) {
                wp_send_json_success(array( 'text' => __('Success select', 'wpsf'), 'field' => $field ));
            } else {
                wp_send_json_error( array( 'text' => __( 'Select is failed', 'wpsf' ) ) );
            }
        }
        else {
            wp_send_json_error( array( 'text' => __( 'Select is failed. WPSF_Model not exist', 'wpsf' ) ) );
        }

        wp_die();
    }
}

if ( ! function_exists( 'wpsf_update_field_sorting' ) ) {
    function wpsf_update_field_sorting() {
        global $wpdb;

        $wpsf_field_id = $_POST['wpsf_field_id'];
        !is_array($wpsf_field_id) && $wpsf_field_id = [ $wpsf_field_id ];

        $wpsf_field_sorting = $_POST['wpsf_field_sorting'];
        !is_array($wpsf_field_sorting) && $wpsf_field_sorting = [ $wpsf_field_sorting ];

        if ( class_exists('WPSF_Model') ) {
            $WPSF_Model = WPSF_Model::get_instance();
            foreach ($wpsf_field_id as $key => $field_id) {
                $inputData = [
                    'sorting' => $wpsf_field_sorting[$key]
                ];
                $wpdb->update( $WPSF_Model->wpsf_form_fields_table, $inputData, array( 'id' => (int) $field_id ) );
            }

            wp_send_json_success(array( 'text' => __('Sorting updated', 'wpsf') ));
        }
        else {
            wp_send_json_error( array( 'text' => __( 'Update is failed. Class WPSF_Model not exist', 'wpsf' ) ) );
        }

        wp_die();
    }
}

if ( !function_exists( 'wpsf_activate_fields' ) ) {
    function wpsf_activate_fields()
    {
        global $wpdb;

        $wpsf_field_id = $_POST['wpsf_field_id'];
        !is_array($wpsf_field_id) && $wpsf_field_id = [ $wpsf_field_id ];

        if ( class_exists('WPSF_Model') ) {
            $WPSF_Model = WPSF_Model::get_instance();
            $wpsf_field_id = implode( ',', array_map( 'absint', $wpsf_field_id ) );
            $wpdb->query( $wpdb->prepare( "UPDATE $WPSF_Model->wpsf_form_fields_table SET active = 1 WHERE id IN($wpsf_field_id)" ) );

            wp_send_json_success( array( 'text' => __( 'Success activate fields', 'wpsf' ) ) );
        }
        else {
            wp_send_json_error( array( 'text' => __( 'Activate is failed. Class WPSF_Model not exist', 'wpsf' ) ) );
        }

        wp_die();
    }
}

if ( !function_exists( 'wpsf_deactivate_fields' ) ) {
    function wpsf_deactivate_fields()
    {
        global $wpdb;

        $wpsf_field_id = $_POST['wpsf_field_id'];
        !is_array($wpsf_field_id) && $wpsf_field_id = [ $wpsf_field_id ];

        if ( class_exists('WPSF_Model') ) {
            $WPSF_Model = WPSF_Model::get_instance();
            $wpsf_field_id = implode( ',', array_map( 'absint', $wpsf_field_id ) );
            $wpdb->query( $wpdb->prepare( "UPDATE $WPSF_Model->wpsf_form_fields_table SET active = 0 WHERE id IN($wpsf_field_id)" ) );

            wp_send_json_success( array( 'text' => __( 'Success deactivate fields', 'wpsf' ) ) );
        }
        else {
            wp_send_json_error( array( 'text' => __( 'Deactivate is failed. Class WPSF_Model not exist', 'wpsf' ) ) );
        }

        wp_die();
    }
}

if ( !function_exists( 'wpsf_delete_fields' ) ) {
    function wpsf_delete_fields()
    {
        global $wpdb;

        $wpsf_field_id = $_POST['wpsf_field_id'];
        !is_array($wpsf_field_id) && $wpsf_field_id = [ $wpsf_field_id ];

        if ( class_exists('WPSF_Model') ) {
            $WPSF_Model = WPSF_Model::get_instance();
            $wpsf_field_id = implode( ',', array_map( 'absint', $wpsf_field_id ) );
            $wpdb->query( $wpdb->prepare( "DELETE FROM $WPSF_Model->wpsf_form_fields_table WHERE id IN($wpsf_field_id)" ) );

            wp_send_json_success( array( 'text' => __( 'Success deleted fields', 'wpsf' ) ) );
        }
        else {
            wp_send_json_error( array( 'text' => __( 'Delete is failed. Class WPSF_Model not exist', 'wpsf' ) ) );
        }

        wp_die();
    }
}