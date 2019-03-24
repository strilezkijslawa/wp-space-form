<?php
/**
 * View class
 */

if ( !class_exists( 'WPSF_View' ) ) {
    class WPSF_View
    {
        public $data = [];

        public function setSettings( $settings )
        {
            $this->data['settings'] = $settings;
        }

        public function setForm( $form = [] )
        {
            $this->data['form'] = $form;
        }

        public function setFormFields( $form_fields = [] )
        {
            $this->data['form_fields'] = $form_fields;
        }

        public function setForms( $forms = [] )
        {
            $this->data['forms'] = $forms;
        }

        public function setPositions( $positions = [] )
        {
            $this->data['positions'] = $positions;
        }

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
        }

        public function wpsf_show_forms_page()
        {
            if ( isset($_GET['form_id']) ) {
                require_once WPSF_TEMPLATES_DIR . 'wpsf_single_form_page.php';
            } else {
                require_once WPSF_TEMPLATES_DIR . 'wpsf_forms_page.php';
            }
        }

        /**
         * WPSF admin page template
         */
        public function wpsf_show_settings_page()
        {
            require_once WPSF_TEMPLATES_DIR . 'wpsf_settings_page.php';
        }
    }
}