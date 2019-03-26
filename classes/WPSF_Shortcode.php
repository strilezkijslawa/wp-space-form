<?php
/**
 * Class for create shortcode functional
 */

if ( !class_exists('WPSF_Shortcode') ) {
    class WPSF_Shortcode
    {
        /**
         * The unique instance of the plugin.
         *
         * @var instance
         */
        private static $instance;

        /**
         * Gets an instance of our plugin.
         */
        public static function get_instance() {

            if ( null === self::$instance ) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        public function wpsf_space_form_shortcode( $atts )
        {
            $atts = shortcode_atts( array(
                'form_id' => false
            ), $atts );

            if ( !$atts['form_id'] ) {
                return '';
            }

            $WPCF_Model = WPSF_Model::get_instance();
            $form = $WPCF_Model->get_wpsf_form( $atts['form_id'] );
            if ( empty($form) || !$form['active'] ) {
                return '';
            }

            $form_fields = $WPCF_Model->get_wpsf_form_fields( $atts['form_id'] );
            if ( empty($form_fields) ) {
                return '';
            }

            $WPCF_View = WPSF_View::get_instance();
            $WPCF_View->setSettings( $WPCF_Model->get_wpsf_settings() );
            $WPCF_View->setForm( $form );
            $WPCF_View->setFormFields( $form_fields );

            ob_start();
            $WPCF_View->wpsf_show_single_form();

            return ob_get_clean();
        }
    }
}