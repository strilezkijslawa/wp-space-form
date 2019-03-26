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
            $this->namespace = 'wpsf/v1'; // api namespace for wp, http://site.com/wp-json/{$namespace}/{$resource}
            $this->_action_nonce = 'wpsf';
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
            $setData = $request->get_params();

            $form_id = $setData['wpsf_form_id'];
            unset($setData['wpsf_form_id']);

            if ( !$form_id ) {
                return $this->renderErrorAnswer( [ 'text' => __( 'Form is undefined. Please reload page, and try again', 'wpsf' ) ] );
            }

            $WPSF_Model = WPSF_Model::get_instance();
            $form = $WPSF_Model->get_wpsf_form( $form_id );
            if ( empty($form) ) {
                return $this->renderErrorAnswer( [ 'text' => __( 'Form is deactivated or deleted. Please reload page', 'wpsf' ) ] );
            }

            $form_fields = $WPSF_Model->get_wpsf_form_fields( $form_id );
            if ( empty($form_fields) ) {
                return $this->renderErrorAnswer( [ 'text' => __( 'Form fields is deactivated or deleted. Please reload page', 'wpsf' ) ] );
            }

            $settings = $WPSF_Model->get_wpsf_settings();

            $from_email = !empty($form['from_email']) ? $form['from_email'] : $settings['from_email'];

            $form_data = "";
            foreach ( $form_fields as $field ) {
                if ( !$field['active'] || !$field['send_to_admin'] ) { continue; }
                $field_name = strpos($field['name'], '[') !== false ? preg_replace('/[]/', '', $field['name']) : $field['name'];
                $field_name = 'wpsf_' . $field_name;
                if ( !isset($setData[$field_name]) ) { continue; }
                $field_data = !is_array($setData[$field_name]) ? [$setData[$field_name]] : $setData[$field_name];
                $value = is_array($field_data) ? implode(", ", array_map( 'sanitize_text_field', $field_data ) ) : sanitize_text_field( $field_data );
                $form_data .= "\n{$field['label']}: {$value}\n";
            }

            $replace_pattern = [
                "{form_id}" => $form_id,
                "{from_email}" => $from_email,
                "{form_data}" => $form_data
            ];

            $template = !empty($form['admin_message_template']) ? $form['admin_message_template'] : '{form_data}';
            $template = strpos( $template, "{form_data}") === false ? $template . "\n{form_data}" : $template;
            $message = strtr( $template, $replace_pattern );

            $subject = !empty($form['subject']) ? $form['subject'] : __( 'New form data filled on site ', 'wpsf' ) . " {$_SERVER['HTTP_HOST']}";

            global $wpdb;
            $inputData = [
                'from_email' => $from_email,
                'to_email' => $form['to_email'],
                'subject' => $subject,
                'message_template' => $message,
                'sended' => 0
            ];
            $inserted = $wpdb->insert( $WPSF_Model->wpsf_letters_table, $inputData );

            if ( $inserted !== false && $settings['send_letters'] ) {
                $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
                $replace_pattern['{letter_url}'] = "{$protocol}{$_SERVER['HTTP_HOST']}/wp-admin/admin.php?page=wpsf-sent-letters&letter_id={$wpdb->insert_id}";
                $message = strtr( $template, $replace_pattern );

                $sent = mail($form['to_email'], $subject, $message);
                if ($sent) {
                    $wpdb->update( $WPSF_Model->wpsf_letters_table, [ 'sended' => 1, 'message_template' => $message ], array( 'id' => $wpdb->insert_id ) );
                }
            }

            return $this->renderSuccessAnswer( [ 'text' => __( 'Form success sent', 'wpsf' ), 'form_data' => $setData, 'message_data' => $form_data ] );
        }

        /**
         * Return json success answer
         * @param array $data
         * @return string
         */
        public function renderSuccessAnswer( $data = [] )
        {
            ob_start();
            wp_send_json_success( $data );

            return ob_get_clean();
        }

        /**
         * Return json error answer
         * @param array $data
         * @return string
         */
        public function renderErrorAnswer( $data = [] )
        {
            ob_start();
            wp_send_json_error( $data );

            return ob_get_clean();
        }
    }

    $WPSF_Routes = WPSF_Routes::get_instance();
}