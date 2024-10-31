<?php

/**
 * Class Security_Audit | src/security_audit.php
 * 
 * @author Innocow
 * @copyright 2020 Innocow
 */

namespace Innocow\Security_Audit;

class Security_Audit {

    //
    //
    // Properties
    //
    //

    /*
     * Plugin Version.
     *
     * @var string
     */ 
    protected $version = "1.2";

     /*
      * Process unique instance of the ICWPSA class (Singleton model.)
      *
      * @var ICWPSA
      */ 
    protected static $instance = null;

    /**
     * Initialise class to object.
     */
    public function __construct() {
        
        $this->define_constants();
        $this->init_hooks();

    }

    // 
    // 
    // Accessors
    // 
    // 

    /**
     * Gets an already initialised session instance of this class.
     * 
     * Note the static methods required with Singleton patterns.
     *
     * @return ICWPSA The session instance of the object.
     */
    public static function get_instance() {

        // If this class"s reference is null.
        if ( is_null( self::$instance ) ) {

            self::$instance = new self();

        }

        return self::$instance;

    }

    //
    //
    // Methods
    //
    //

    /**
     * Define a constant and ensure it does not exist.
     *
     * @param string $name The name of the constant.
     * @param mixed $value The value of the constant.
     *
     * @throws RuntimeException if the constant has already been defined.
     */
    private function define( $name, $value ) {

        if ( defined( $name ) ) {

            throw new RuntimeException( "The variable $name has already been defined as a constant." );

        }

        define( $name, $value );

    }

    /**
     * Define ICWPSA constants.
     */
    private function define_constants() {

        $this->define( "ICWPSA_VERSION", $this->version );
        $this->define( "ICWPSA_PLUGIN_SLUG", $this->get_plugin_slug() );

    }

    /**
     * Returns the plugins slug.
     * 
     */
    private function get_plugin_slug() {

        $path = plugin_basename( __DIR__ );
        $folders = explode( "/", $path );

        return $folders[0];

    }

    /**
     * Hook into Wordpress functionality.
     */
    private function init_hooks() {

        if ( is_admin() ) {

            add_action( 
                "admin_menu", 
                array( __NAMESPACE__ . "\\Hooks\\Admin", "hook_admin_menu" )
            );

            add_action( 
                "admin_enqueue_scripts", 
                array( __NAMESPACE__ . "\\Hooks\\Admin", "hook_admin_enqueue_scripts" )
            );

            add_action(
                "init",
                array( __NAMESPACE__ . "\\Hooks\\Init", "hook_init_load_translations" ),
                100
            );

        }

        add_action( 
            "rest_api_init",
            array( __NAMESPACE__ . "\\Hooks\\Rest_Routes", "entities_permissions" )
        );

        add_action( 
            "rest_api_init",
            array( __NAMESPACE__ . "\\Hooks\\Rest_Routes", "entities_ownership" )
        );

        add_action( 
            "rest_api_init",
            array( __NAMESPACE__ . "\\Hooks\\Rest_Routes", "entities_folders" )
        );


        add_action( 
            "rest_api_init",
            array( __NAMESPACE__ . "\\Hooks\\Rest_Routes", "server_process_details" )
        );

        add_action( 
            "rest_api_init",
            array( __NAMESPACE__ . "\\Hooks\\Rest_Routes", "configuration_wp" ),
            10
        );

        add_action( 
            "rest_api_init",
            array( __NAMESPACE__ . "\\Hooks\\Rest_Routes", "configuration_sys" ),
            10
        );

    }

}