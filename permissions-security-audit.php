<?php

/**
 * Plugin Name: Permissions & Security Audit
 * Plugin URI: https://innocow.com
 * Description: A collection of tests to check the installations permissions as well as common Wordpress and hosting security risks. The plugin can be found in 'Tools' menu section. Developed by Innocow.
 * Version: 1.2
 * Requires PHP: 5.4
 * Author: Andrew Stewart
 * Author URI: http://innocow.com/
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: icwpsa
 * Domain Path: /lang
 **/

namespace Innocow\Security_Audit;

if ( ! defined( "ABSPATH" ) ) { exit; }

/**
 * Modifies to autoloader to work within the namespace.
 */
function plugin_modify_autoloader() {

    spl_autoload_register( function( $fully_qualified_class ) {

        $namespace = "Innocow\\Security_Audit";
        $path      = "src";

        if ( strpos( $fully_qualified_class, $namespace ) === false ) {

            return;

        }

        $class = str_replace( $namespace, '', $fully_qualified_class );
        $working_directory = realpath( __DIR__ . "/{$path}" );
        $class_lc = strtolower( $class );

        $include_file = $working_directory . DIRECTORY_SEPARATOR . str_replace( '\\', DIRECTORY_SEPARATOR, $class_lc ) . '.php';

        if ( ! file_exists( $include_file ) ) {

            throw new \Exception( "Cannot find file: $include_file" );

        }

        include( $include_file );

    } );

}

/**
 * Determines whether the request is a REST request.
 * 
 * As of the time of this version, wordpress can't definitely give the
 * status of the context of the request. To minimize plugin loading when
 * it shouldn't, this custom function must be used.
 */
function icwpsa_is_rest_context() {

    if ( strpos( $_SERVER["REQUEST_URI"], "/wp-json/" ) !== false ) {

        return true;

    }

    if ( strpos( $_SERVER["REQUEST_URI"], "/index.php?rest_route=" ) !== false ) {

        return true;

    }

    return false;

}

/**
 * Returns the main instance of class to prevent the need to use globals.
 * This is "borrowed" from the WC code.
 *
 * @return Security_Audit
 */
function icwpsa_get_instance() {

    return Security_Audit::get_instance();

}

if ( is_admin() || icwpsa_is_rest_context() ) {

    plugin_modify_autoloader();
    $Security_Audit = icwpsa_get_instance();

    // Global for backwards compatibility.
    $GLOBALS["ICWPSA"] = icwpsa_get_instance();

}