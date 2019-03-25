<?php
/**
 * Class for create routes
 */

if ( !class_exists( 'WPSF_Routes' ) ) {
    class WPSF_Routes
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
        public static function get_instance()
        {

            if (null === self::$instance) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        public function __construct()
        {
            $this->namespace = 'wp-space-form/v1'; // api namespace for wp, http://site.com/wp-json/{$namespace}/{$resource}
            $this->_action_nonce = 'wp-space-form';
        }

        /**
         * Create wpsf routes
         */
        public function wpsf_register_routes()
        {
            // register send form route
            register_rest_route($this->namespace, "/send", array(
                array(
                    'methods' => WP_REST_Server::CREATABLE, // POST
                    'callback' => array($this, 'wpsf_send_form')
                )
            ));
        }

        /**
         * @param $request
         * @return string
         */
        public function wpsf_send_form( $request )
        {
            $output = [];

            $setData = $request->get_params();


            return json_encode( $output );
        }
    }

    $WPSF_Routes = WPSF_Routes::get_instance();
}