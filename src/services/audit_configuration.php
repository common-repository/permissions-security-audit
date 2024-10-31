<?php

/**
 * Class Audit_Configuration | src/services/audit_configuration.php
 * 
 * @author Innocow
 * @copyright 2020 Innocow
 */

namespace Innocow\Security_Audit\Services;

class Audit_Configuration {

    /**
     * Tests the wordpress installation for any commonly used usernames.
     * 
     * @return mixed Array of account usernames if they exist. False if none.
     */
    public function audit_wp_admin_accounts() {

        if ( ! function_exists( "get_user_by" ) ) {

            throw new \RuntimeException( 
                "The WordPress function: get_user_by() is not available." 
            );

        }

        $common_admin_usernames_array = [
            "root",
            "admin",
            "administrator",
            "wp-admin",
            "wp-administrator",
            "test",
            "user",
            "guest",
            "wordpress"
        ];

        $admin_users_array = [];

        foreach( $common_admin_usernames_array as $test_username ) {

            $WP_User = get_user_by( "login", $test_username );

            if ( ! $WP_User ) {

                continue;

            }

            if ( in_array( "administrator", $WP_User->roles ) ) {

                $admin_users_array[] = $test_username;

            }

        }

        return $admin_users_array;

    }

    /**
     * Tests the wordpress installation if it is using the default table prefix.
     * 
     * @return boolean True if the prefix is using the default, false if not.
     */
    public function audit_wp_db_table_prefixes() {

        global $wpdb;

        if ( ! $wpdb ) {

            throw new \RuntimeException( "" );

        }

        if ( $wpdb->prefix === "wp_" ) {

            return true;

        }

        return false;

    }

    /**
     * Tests the wordpress installation if certain problematic routes are available.
     *
     * @return mixed Array of available routes. False if none.
     */
    public function audit_wp_public_rest_api() {

        $problematic_public_rest_routes_array = [
            "/wp/v2/users",
            "/wp/v2/users/(?P<id>[\d]+)",
        ];
        
        $available_rest_routes_array = [];

        $WP_REST_Server = rest_get_server();
        $routes_array = $WP_REST_Server->get_routes( "wp/v2" );

        foreach( $problematic_public_rest_routes_array as $label ) {

            if ( $routes_array[$label] ) { 

                $available_rest_routes_array[] = $label; 

            }

        }

        if ( ! empty( $available_rest_routes_array ) ) {

            return $available_rest_routes_array;

        }

        return false;

    }

    /**
     * Tests the wordpress installation if the file editor is enabled in the admin panel.
     * 
     * @return boolean True if enabled, false if not.
     */
    public function audit_wp_are_files_editable() {

        if ( defined( "DISALLOW_FILE_EDIT" ) ) {

            if ( DISALLOW_FILE_EDIT === true ) {

                return false;

            }

        }

        return true;

    }

    /**
     * Returns how many days have passed since the wp-config.php file has been modified.
     * 
     * @param $path_override (Optional) System path to wp-config.php.
     * @param $format (Optional) The date() format to return the value in.
     * 
     * @return int Days since wp-config.php has been modified.
     */
    public function audit_wp_get_wpconfig_time_last_modified( $path_override=null, $format=null ) {

        if ( ! is_null ( $path_override ) ) {

            $wpconfig_fullpath = $path_override;

        } else {

            $wpconfig_fullpath = ABSPATH . "/wp-config.php";

        }

        if ( ! file_exists( $wpconfig_fullpath ) ) {

            throw new \RuntimeException( "File wp-config.php not found: $wpconfig_fullpath" );

        }

        $DateTimeNow = new \DateTime( "now", new \DateTimeZone( "UTC" ) );
        $DateTimeFile = new \DateTime( "@" . filemtime( $wpconfig_fullpath ), new \DateTimeZone( "UTC" ) );

        if ( ! is_null( $format ) ) {

            return $DateTimeNow->diff( $DateTimeFile )->format( $format );

        }

        return $DateTimeNow->diff( $DateTimeFile )->format( "%a" );

    }

    /**
     * Tests the wordpress installation if the wordpress auto update is enabled.
     */
    public function audit_wp_is_core_autoupdate_on() {

        if ( ! defined( "WP_AUTO_UPDATE_CORE" ) ) {

            return false;

        }

        return (boolean) WP_AUTO_UPDATE_CORE;

    }

    /**
     * Tests the PHP configuration if certain functions are enabled.
     * 
     * @return mixed Array of sensitive functions. False if none.
     */
    public function audit_sys_sensitive_functions() {

        $sensitive_functions_array = [
            "shell_exec",
            "exec",
            "passthru",
            "system",
            "popen",
            "proc_open",            
            "highlight_file",
            "highlight_string",
            "show_source",
        ];

        $available_sensitive_functions_array = [];

        foreach( $sensitive_functions_array as $function ) {

            if ( function_exists( $function ) ) {

                $available_sensitive_functions_array[] = $function;

            }

        }

        if ( ! empty( $available_sensitive_functions_array ) ) {

            return $available_sensitive_functions_array;

        }

        return false;

    }

}
