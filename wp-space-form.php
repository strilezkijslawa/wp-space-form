<?php
/*
 * Plugin Name: WP SpaceForm
 * Plugin URI:
 * Description: Create any form for your website
 * Version: 1.0.0
 * Author: strilezkijslawa
 * Author URI: https://freelancehunt.com/freelancer/strilezkijslawa.html
 * Text Domain: wpsf
*/

add_action( 'plugins_loaded', 'wpsf_load_space_form', 1 );

// Activation.
register_activation_hook( __FILE__, 'activation' );

if ( ! function_exists( 'wpsf_load_space_form' ) ) {

    /**
     * Function to load packages
     *
     * @since 1.0
     */
    function wpsf_load_space_form() {
        require_once 'classes/WPSF_Loader.php';

    }
}

/**
 * Function for activation hook
 *
 * @since 1.0
 */
function activation() {

    update_option( 'wpsf_demo', true );

    global $wp_version;
    $wp  = '3.5';
    $php = '5.3';
    if ( version_compare( PHP_VERSION, $php, '<' ) ) {
        $flag = 'PHP';
    } elseif ( version_compare( $wp_version, $wp, '<' ) ) {
        $flag = 'WordPress';
    } else {
        return;
    }
    $version = 'PHP' == $flag ? $php : $wp;
    deactivate_plugins( WPSF_DIR_NAME );
    wp_die(
        '<p><strong>' . WPSF_NAME . ' </strong> requires <strong>' . $flag . '</strong> version <strong>' . $version . '</strong> or greater. Please contact your host.</p>', 'Plugin Activation Error', array(
            'response'  => 200,
            'back_link' => true,
        )
    );
}