<?php
/**
 * Common helper functions
 */

/**
 * Check IP address by $_SERVER
 *
 * @return string
 */
if ( !function_exists('wpsf_get_ip') ) {
    function wpsf_get_ip()
    {
        $keys = array('HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'REMOTE_ADDR', 'HTTP_X_REAL_IP');
        foreach ($keys as $key) {
            $ip = trim(strtok(filter_input(INPUT_SERVER, $key), ','));
            if (is_valid_ip($ip)) {
                return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
            }
        }

        return NULL;
    }
}

/**
 * Validate IP address
 * @param IP address in format 1.2.3.4
 * @return bool : true - if ip real, else false
 */
if ( !function_exists('is_valid_ip') ) {
    function is_valid_ip($ip)
    {
        return (bool)filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
    }
}