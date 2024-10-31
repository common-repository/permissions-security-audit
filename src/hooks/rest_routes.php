<?php

/**
 * Class Rest_Routes | src/hooks/rest_routes.php
 * 
 * @author Innocow
 * @copyright 2020 Innocow
 */

namespace Innocow\Security_Audit\Hooks;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Rest_Routes {

    /**
     * The namespace portion of the REST URL.
     * 
     * @var string
     */
    static protected $url_namespace = "innocow-wp-security-audit";

    /**
     * The version of the REST URL structure.
     * 
     * @var string
     */
    static protected $url_version = "1";

    /**
     * Get the formatted namespace portion of the REST URL.
     * 
     * @return string
     */
    protected static function get_full_namespace() {

        return sprintf( "%s/v%s", self::$url_namespace, self::$url_version );

    }

    /**
     * Checks Wordpress capabilities and roles for REST authorisation.
     * 
     * @return boolean
     */
    protected static function is_user_permissible() {

       if ( current_user_can( 'administrator' ) ) {

            return true;

        }

        return false;

    }

    /**
     * Hook for REST URL: index.php?rest_route=/innocow-wp-security-audit/v1/entities/permissions
     */
    public static function entities_permissions() {

        register_rest_route(
            self::get_full_namespace(), 
            "entities/permissions",
            array(
                "method" => "GET",
                "callback" => array( 
                    "Innocow\\Security_Audit\\Controllers\\Rest", 
                    "entities_permissions"
                ),
                "permission_callback" => function() { return self::is_user_permissible(); }
            )
        );

    }


    /**
     * Hook for REST URL: index.php?rest_route=/innocow-wp-security-audit/v1/entities/ownership
     */
    public static function entities_ownership() {

        register_rest_route( 
            self::get_full_namespace(), 
            "entities/ownership",
            array(
                "method" => "GET",
                "callback" => array( 
                    "Innocow\\Security_Audit\\Controllers\\Rest", 
                    "entities_ownership"
                ),
                "permission_callback" => function() { return self::is_user_permissible(); }
            )
        );

    }

    /**
     * Hook for REST URL: index.php?rest_route=/innocow-wp-security-audit/v1/entities/folders
     */
    public static function entities_folders() {

        register_rest_route( 
            self::get_full_namespace(), 
            "entities/folders",
            array(
                "method" => "GET",
                "callback" => array( 
                    "Innocow\\Security_Audit\\Controllers\\Rest", 
                    "entities_folders"
                ),
                "permission_callback" => function() { return self::is_user_permissible(); }
            )
        );

    }

    /**
     * Hook for REST URL: index.php?rest_route=/innocow-wp-security-audit/v1/server/process/details
     */
    public static function server_process_details() {

        register_rest_route( 
            self::get_full_namespace(), 
            "server/process/details",
            array(
                "method" => "GET",
                "callback" => array( 
                    "Innocow\\Security_Audit\\Controllers\\Rest", 
                    "server_process_details"
                ),
                "permission_callback" => function() { return self::is_user_permissible(); }
            )
        );

    }

    /**
     * Hook for REST URL: index.php?rest_route=/innocow-wp-security-audit/v1/configuration/wp
     */
    public static function configuration_wp() {

        register_rest_route( 
            self::get_full_namespace(), 
            "configuration/wp",
            array(
                "method" => "GET",
                "callback" => array( 
                    "Innocow\\Security_Audit\\Controllers\\Rest", 
                    "configuration_wp"
                ),
                "permission_callback" => function() { return self::is_user_permissible(); }
            )
        );

    }

    /**
     * Hook for REST URL: index.php?rest_route=/innocow-wp-security-audit/v1/configuration/sys
     */
    public static function configuration_sys() {

        register_rest_route( 
            self::get_full_namespace(), 
            "configuration/sys",
            array(
                "method" => "GET",
                "callback" => array( 
                    "Innocow\\Security_Audit\\Controllers\\Rest", 
                    "configuration_sys"
                ),
                "permission_callback" => function() { return self::is_user_permissible(); }
            )
        );

    }    

}