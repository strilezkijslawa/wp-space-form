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

        }
    }
}