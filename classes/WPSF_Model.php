<?php
/**
 * Project: WPSpaceFormProject
 * Created by: spaceweb.com.ua
 * Date: 3/25/2019
 * Time: 12:05 AM
 */

if ( !class_exists( 'WPSF_Model' ) ) {
    class WPSF_Model
    {
        /*
         * @var wpsf_settings_table
         */
        public $wpsf_settings_table;

        /*
         * @var wpsf_articles_table
         */
        public $wpsf_forms_table;

        /**
         * @var string $wpsf_letters_table
         */
        public $wpsf_letters_table;

        /**
         * @var string $wpsf_form_fields_table
         */
        public $wpsf_form_fields_table;

        /**
         * @var array
         */
        public $wpsf_message_positions = [
            'top', 'bottom', 'center'
        ];

        /**
         * The unique instance of the plugin.
         *
         * @var instance
         */
        private static $instance;

        /**
         * Gets an instance of our plugin.
         */
        public static function get_instance()
        {

            if (null === self::$instance) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        public function __construct()
        {
            global $wpdb;
            $this->wpsf_settings_table = $wpdb->prefix . 'wpsf_settings';
            $this->wpsf_forms_table = $wpdb->prefix . 'wpsf_forms';
            $this->wpsf_form_fields_table = $wpdb->prefix . 'wpsf_form_fields';
            $this->wpsf_letters_table = $wpdb->prefix . 'wpsf_sended_letters';
        }

        /**
         * Select wpsf forms from db
         * @param bool $form_id
         * @return array
         */
        public function get_wpsf_form( $form_id = false )
        {
            global $wpdb;

            if ( $form_id ) {
                $forms = $wpdb->get_results("SELECT * FROM `" . $this->wpsf_forms_table . "` WHERE `id` = '{$form_id}' LIMIT 1", ARRAY_A);
                if ( empty($forms) ) {
                    return [];
                } else {
                    return $forms[0];
                }
            }

            $forms = $wpdb->get_results("SELECT * FROM `" . $this->wpsf_forms_table . "`", ARRAY_A);
            return $forms;
        }

        /**
         * Select wpsf form fields from db
         * @param bool $form_id
         * @return array
         */
        public function get_wpsf_form_fields( $form_id = false )
        {
            if ( !$form_id ) {
                return [];
            }

            global $wpdb;

            $form_fields = $wpdb->get_results("SELECT * FROM `" . $this->wpsf_form_fields_table . "` WHERE `form_id` = '{$form_id}' ORDER BY sorting ASC", ARRAY_A);
            return $form_fields;
        }

        public function get_wpsf_form_single_field( $field_id = false )
        {
            if ( !$field_id ) {
                return [];
            }

            global $wpdb;

            $form_fields = $wpdb->get_results("SELECT * FROM `" . $this->wpsf_form_fields_table . "` WHERE `id` = '{$field_id}' LIMIT 1", ARRAY_A);
            if ( !empty($form_fields) ) {
                return $form_fields[0];
            } else {
                return [];
            }
        }
    }
}