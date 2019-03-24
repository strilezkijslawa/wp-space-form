<?php
/**
 * Base loader of all plugin
 */

// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

if ( ! class_exists('WPSF_Loader') ) {

    class WPSF_Loader
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

        /**
         * Constructor.
         */
        private function __construct() {

            // minimum requirement for PHP version.
            $php = '5.3';

            // If current version is less than minimum requirement, display admin notice.
            if ( version_compare( PHP_VERSION, $php, '<' ) ) {

                add_action( 'admin_notices', array( $this, 'php_version_notice' ) );
                return;
            }

            $this->define_constants();
            $this->load_files();
            add_filter( 'all_plugins', __CLASS__ . '::plugins_page' );
        }

        /**
         * Shows an admin notice for outdated php version.
         */
        function php_version_notice() {

            $message = __( 'Your server seems to be running outdated, unsupported and vulnerable version of PHP. You are advised to contact your host provider and upgrade to PHP version 5.3 or greater.', 'wpsf' );

            $this->render_admin_notice( $message, 'warning' );
        }

        /**
         * Function Name: render_admin_notice.
         * Function Description: Renders an admin notice.
         *
         * @param string $message string parameter.
         * @param string $type string parameter.
         */
        private function render_admin_notice( $message, $type = 'update' ) {

            if ( ! is_admin() ) {
                return;
            } elseif ( ! is_user_logged_in() ) {
                return;
            } elseif ( ! current_user_can( 'update_core' ) ) {
                return;
            }

            echo '<div class="' . $type . '">';
            echo '<p>' . $message . '</p>';
            echo '</div>';
        }

        /**
         * Define constants.
         *
         * @since 1.0
         * @return void
         */
        private function define_constants() {

            $file = dirname( dirname( __FILE__ ) );

            define( 'WPSF_DIR_NAME', plugin_basename( $file ) );
            define( 'WPSF_BASE_FILE', trailingslashit( $file ) . WPSF_DIR_NAME . '.php' );
            define( 'WPSF_BASE_DIR', plugin_dir_path( WPSF_BASE_FILE ) );
            define( 'WPSF_TEMPLATES_DIR', WPSF_BASE_DIR . 'templates/' );
            define( 'WPSF_BASE_URL', plugins_url( '/', WPSF_BASE_FILE ) );
            define( 'WPSF_NAME', 'WP SpaceForm' );
            define( 'WPSF_SLUG', 'wp-space-form' );
            define( 'WPSF_AUTHOR_NAME', 'strilezkijslawa' );
            define( 'WPSF_AUTHOR_URL', 'https://freelancehunt.com/freelancer/strilezkijslawa.html' );
            define( 'WPSF_DESCRIPTION', WPSF_NAME . ' is best plugin for create any form for your website.' );

            $plugin_name = get_option( 'wpsf_branding_plugin_name' );
            $_name       = ( '' == $plugin_name ) ? WPSF_NAME : $plugin_name;

            define( 'WPSF_BRANDING_NAME', $_name );
        }

        /**
         * Branding addon on the plugins page.
         *
         * @since 1.0
         * @param array $plugins An array data for each plugin.
         * @return array
         */
        static public function plugins_page( $plugins ) {

            $branding = WPSF_Loader::get_branding();
            $basename = plugin_basename( WPSF_BASE_DIR . 'wp-space-rate.php' );

            if ( isset( $plugins[ $basename ] ) && is_array( $branding ) ) {

                $plugin_name = ( array_key_exists( 'name', $branding ) ) ? $branding['name'] : WPSF_NAME;
                $plugin_desc = ( array_key_exists( 'description', $branding ) ) ? $branding['description'] : WPSF_DESCRIPTION;
                $author_name = ( array_key_exists( 'author', $branding ) ) ? $branding['author'] : WPSF_AUTHOR_NAME;
                $author_url  = ( array_key_exists( 'author_url', $branding ) ) ? $branding['author_url'] : WPSF_AUTHOR_URL;

                $_name = $plugin_name;

                if ( '' != $plugin_name ) {
                    $plugins[ $basename ]['Name']  = $plugin_name;
                    $plugins[ $basename ]['Title'] = $plugin_name;
                }

                if ( '' != $plugin_desc ) {
                    $plugins[ $basename ]['Description'] = $plugin_desc;
                }

                if ( '' != $author_name ) {
                    $plugins[ $basename ]['Author']     = $author_name;
                    $plugins[ $basename ]['AuthorName'] = $author_name;
                }

                if ( '' != $author_url ) {
                    $plugins[ $basename ]['AuthorURI'] = $author_url;
                    $plugins[ $basename ]['PluginURI'] = $author_url;
                }
            }

            return $plugins;
        }

        /**
         * Loads classes and includes.
         *
         * @since 1.0
         * @return void
         */
        static private function load_files() {
            /* Classes */
            $wpsf_is_admin = is_admin();
            require_once WPSF_BASE_DIR . 'includes/common-helper-functions.php';
            require_once WPSF_BASE_DIR . 'includes/ajax-actions.php';

            if ( $wpsf_is_admin ) {
                require_once WPSF_BASE_DIR . 'includes/admin-helper-functions.php';
            }

            require_once WPSF_BASE_DIR . 'classes/WPSF_Shortcode.php';
            require_once WPSF_BASE_DIR . 'classes/WPSF_Model.php';
            require_once WPSF_BASE_DIR . 'classes/WPSF_View.php';
            require_once WPSF_BASE_DIR . 'classes/WPSF_Controller.php';
            require_once WPSF_BASE_DIR . 'classes/WPSF_Routes.php';

        }

        /**
         * Returns Branding details for the plugin.
         *
         * @since 1.0
         * @return array
         */
        static public function get_branding() {

            $branding['name']              = get_option( 'wpsf_branding_plugin_name' );
            $branding['description']       = get_option( 'wpsf_branding_plugin_desc' );
            $branding['author']            = get_option( 'wpsf_branding_plugin_author_name' );
            $branding['author_url']        = esc_url( get_option( 'wpsf_branding_plugin_author_url' ) );

            return $branding;
        }
    }

    $WPSF_Loader = WPSF_Loader::get_instance();
} else {

    add_action( 'admin_notices', 'wpsf_admin_notices' );
    add_action( 'network_admin_notices', 'wpsf_admin_notices' );

    /**
     * Function Name: admin_notices.
     * Function Description: admin notices.
     */
    function wpsf_admin_notices() {

        $url = admin_url( 'plugins.php' );

        echo '<div class="notice notice-error"><p>';
        /* translators: %s URL */
        echo sprintf( __( "You currently have two versions of <strong>WP Space Form</strong> active on this site. Please <a href='%s'>deactivate one</a> before continuing.", 'wpsf' ), $url );
        echo '</p></div>';

    }

}