<?php
/**
 * Base functionality for workin with db
 */

if ( !defined( 'ABSPATH' ) ) {
    die();
}

if ( ! class_exists('WPSF_Model') ) {

    class WPSF_Model
    {
        /**
         * The unique instance of the plugin.
         *
         * @var instance
         */
        private static $instance;

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
         * WPSF data var
         * @var array settings
         */
        public $settings = [];


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

        /**
         * Constructor.
         */
        function __construct()
        {
            $this->_wpsf_create_db_table();

            add_action('plugins_loaded', array($this, 'wpsf_load_textdomain'));
            add_action('admin_menu', array($this, 'wpsf_admin_generate_menu'));
            $this->wpsf_enqueue_scripts();

            $WPSF_Shortcode = WPSF_Shortcode::get_instance();
            add_shortcode('wpsf_space_form', array($WPSF_Shortcode, 'wpsf_space_form_shortcode'));

            $this->get_wpsf_settings();

            /* Css Asynchronous Loading */
            add_action('wp_head', array($this, 'wpsf_load_css_async'), 7);

        }

        /**
         * Create WPSF DB tables
         */
        private function _wpsf_create_db_table()
        {
            global $wpdb;
            $table = $this->wpsf_settings_table = $wpdb->prefix . 'wpsf_settings';
            $table2 = $this->wpsf_forms_table = $wpdb->prefix . 'wpsf_forms';
            $table3 = $this->wpsf_form_fields_table = $wpdb->prefix . 'wpsf_form_fields';
            $table4 = $this->wpsf_letters_table = $wpdb->prefix . 'wpsf_sended_letters';

            $charset_collate = '';
            if (!empty($wpdb->charset)) {
                $charset_collate = " DEFAULT CHARACTER SET $wpdb->charset";
            }

            /**
             * create table for plugin settings
             */
            if ($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
                $sql_tbl_wpsf = "
                   CREATE TABLE `" . $table . "` (
                        id int(1) NOT NULL AUTO_INCREMENT,
                        send_letters tinyint(1) NOT NULL DEFAULT '1',
                        send_letters_to_user tinyint(1) NOT NULL DEFAULT '1',
                        from_email varchar(200) NOT NULL DEFAULT 'wpsf@" . str_replace('www.', '', $_SERVER['HTTP_HOST']) . "',
                        message_position enum(" . implode(', ', $this->wpsf_message_positions ) . ") NOT NULL DEFAULT 'bottom',
                        global_letter_template text NOT NULL DEFAULT '',
                        success_message_color varchar(50) NOT NULL DEFAULT '28a745',
                        error_message_color varchar(50) NOT NULL DEFAULT 'dc3545',
                        styles text NOT NULL DEFAULT '',
                        PRIMARY KEY  (`id`)
                   ) " . $charset_collate . " ;";

                require_once ABSPATH . 'wp-admin/includes/upgrade.php';
                dbDelta($sql_tbl_wpsf);

                $wpdb->insert($table, array('send_letters' => 1));
            }

            /**
             * create table for forms
             */
            if ($wpdb->get_var("SHOW TABLES LIKE '$table2'") != $table2) {
                $sql_tbl_wpsf = "
                   CREATE TABLE `" . $table2 . "` (
                        id int(11) NOT NULL AUTO_INCREMENT,
                        name varchar(255) NOT NOT NULL DEFAULT '',
                        from_email varchar(255) NOT NULL DEFAULT '',
                        to_email varchar(255) NOT NULL DEFAULT '',
                        subject varchar(255) NOT NULL DEFAULT '',
                        message_template text NOT NULL DEFAULT '',
                        admin_message_template text NOT NULL DEFAULT '',
                        active tinyint(1) NOT NULL DEFAULT '1',
                        PRIMARY KEY  (`id`)
                        ) $charset_collate ;";

                require_once ABSPATH . 'wp-admin/includes/upgrade.php';
                dbDelta($sql_tbl_wpsf);
            }

            /**
             * create table for form fields
             */
            if ($wpdb->get_var("SHOW TABLES LIKE '$table3'") != $table3) {
                $sql_tbl_wpsf = "
                   CREATE TABLE `" . $table3 . "` (
                        id int(11) NOT NULL AUTO_INCREMENT,
                        form_id int(11) NOT NULL,
                        name varchar(100) NOT NULL,
                        label varchar(255) NOT NULL DEFAULT '',
                        type varchar(50) NOT NULL DEFAULT 'text',
                        required tinyint(1) NOT NULL DEFAULT '0',
                        sorting int(11) NOT NULL DEFAULT '99',
                        active tinyint(1) NOT NULL DEFAULT '1',
                        PRIMARY KEY  (`id`)
                        ) $charset_collate ;";

                require_once ABSPATH . 'wp-admin/includes/upgrade.php';
                dbDelta($sql_tbl_wpsf);
            }

            /**
             * create table for sended letters
             */
            if ($wpdb->get_var("SHOW TABLES LIKE '$table4'") != $table4) {
                $sql_tbl_wpsf = "
                   CREATE TABLE `" . $table4 . "` (
                        id int(11) NOT NULL AUTO_INCREMENT,
                        from_email varchar(255) NOT NULL,
                        to_email varchar(255) NOT NULL,
                        subject varchar(255) NOT NULL,
                        message_template text NULL,
                        admin_message_template text NULL,
                        sended tinyint(1) NOT NULL DEFAULT '1',
                        PRIMARY KEY  (`id`)
                        ) $charset_collate ;";

                require_once ABSPATH . 'wp-admin/includes/upgrade.php';
                dbDelta($sql_tbl_wpsf);
            }
        }

        /**
         * Create admin menu page
         *
         * @since 1.0
         */
        function wpsf_admin_generate_menu()
        {
            add_submenu_page('options-general.php', WPSF_NAME, WPSF_NAME, 'manage_options', WPSF_SLUG, array($this, 'wpsf_admin_page'));
            add_submenu_page('options-general.php?page=wp-space-form', __( 'Settings', 'wpsf' ), __( 'Settings', 'wpsf' ), 'manage_options', 'wpsf-settings', array($this, 'wpsf_setting_page'));
        }

        /**
         * WPSF admin page
         */
        public function wpsf_admin_page()
        {
            $this->wpsf_show_forms_page();
        }

        public function wpsf_show_forms_page()
        {
            if ( isset($_GET['form_id']) ) {
                $form = $this->get_wpsf_form( $_GET['form_id'] );
                $form_fields = $this->get_wpsf_form_fields( $_GET['form_id'] );
                require_once WPSF_TEMPLATES_DIR . 'wpsf_single_form_page.php';
            } else {
                $forms = $this->get_wpsf_forms();
                require_once WPSF_TEMPLATES_DIR . 'wpsf_forms_page.php';
            }
        }

        /**
         * WPSF settings page logic
         */
        public function wpsf_setting_page()
        {
            $this->wpsf_show_settings_page();
        }

        /**
         * WPSF admin page template
         */
        private function wpsf_show_settings_page()
        {
            require_once WPSF_TEMPLATES_DIR . 'wpsf_settings_page.php';
        }

        /**
         * Load plugin text domain.
         *
         * @since 1.0
         */
        public function wpsf_load_textdomain()
        {

            // Traditional WordPress plugin locale filter.
            $locale = apply_filters('plugin_locale', get_locale(), 'wpsf');

            // Setup paths to current locale file.
            $mofile_global = trailingslashit(WP_LANG_DIR) . 'plugins/wp-space-form/' . $locale . '.mo';
            $mofile_local = trailingslashit(WPSF_BASE_DIR) . 'languages/' . $locale . '.mo';

            if (file_exists($mofile_global)) {
                // Look in global /wp-content/languages/plugins/wp-space-rate/ folder.
                return load_textdomain('wpsf', $mofile_global);
            } elseif (file_exists($mofile_local)) {
                // Look in local /wp-content/plugins/wp-space-rate/languages/ folder.
                return load_textdomain('wpsf', $mofile_local);
            }

            // Nothing found.
            return false;
        }

        /**
         * Enqueue scripts and styles on frontend
         *
         * @since 1.0
         */
        public function wpsf_enqueue_scripts()
        {

            if (is_admin()) {
                add_action('admin_enqueue_scripts', array($this, 'wpsf_enqueue_admin_scripts'), 10);
            } else {
                add_action('wp_print_scripts', array($this, 'wpsf_enqueue_front_scripts'), 10);

            }
        }

        public function wpsf_enqueue_admin_scripts()
        {
            wp_enqueue_style('jquery-ui-style', WPSF_BASE_URL . 'assets/admin/js/jquery-ui-1.12.1/jquery-ui.min.css');
            wp_enqueue_style('wpsf-global-style', WPSF_BASE_URL . 'assets/global/css/wpsf_global.css');
            wp_enqueue_style('wpsf-style', WPSF_BASE_URL . 'assets/admin/css/wpsf.css');
            wp_enqueue_script('jquery-ui-script', WPSF_BASE_URL . 'assets/admin/js/jquery-ui-1.12.1/jquery-ui.min.js', array('jquery'), null, true);
            wp_enqueue_script('jscolor-script', WPSF_BASE_URL . 'assets/admin/js/jscolor.js', array('jquery'), null, true);
            wp_enqueue_script('wpsf-global-script', WPSF_BASE_URL . 'assets/global/js/wpsf_global.js', array('jquery'), null, true);
            wp_enqueue_script('wpsf-script', WPSF_BASE_URL . 'assets/admin/js/wpsf.js', array('jquery'), null, true);
        }

        public function wpsf_enqueue_front_scripts()
        {
            wp_enqueue_style('wpsf-global-style', WPSF_BASE_URL . 'assets/global/css/wpsf_global.css');
            wp_enqueue_style('wpsf-front-style', WPSF_BASE_URL . 'assets/front/css/wpsf.css');
            wp_enqueue_script('wpsf-global-script', WPSF_BASE_URL . 'assets/global/js/wpsf_global.js', array('jquery'), null, true);
            wp_enqueue_script('wpsf-front-script', WPSF_BASE_URL . 'assets/front/js/wpsf.js', array('jquery'), null, true);
        }

        /**
         * Custom styles in head
         */
        public function wpsf_load_css_async()
        {

            $settings = $this->settings;
            $scripts = "";
            $scripts .= "<style>{$settings['styles']}</style>";

            echo $scripts;
        }

        /**
         * Get WPSF settings from db
         * @return array
         */
        public function get_wpsf_settings()
        {
            global $wpdb;

            $settings = $wpdb->get_results("SELECT * FROM `" . $this->wpsf_settings_table . "` LIMIT 1", ARRAY_A);
            $this->settings = $settings[0];

            return $this->settings;
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

    }

    $WPSF_Model = WPSF_Model::get_instance();
}