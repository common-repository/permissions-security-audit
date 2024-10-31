<?php

/**
 * Class Admin | src/hooks/Admin.php
 * 
 * @author Innocow
 * @copyright 2020 Innocow
 */

namespace Innocow\Security_Audit\Hooks;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Admin {

    /**
     * Hook for the admin page content.
     */
    public static function hook_admin_menu() {

        add_submenu_page( 
            "tools.php",
            __( "Permissions & Security Audit by Innocow", "icwpsa" ),
            __( "Permissions & Security Audit", "icwpsa" ),
            "administrator",
            "icwpsa",
            array( "Innocow\\Security_Audit\\Views\\Loader", "load" ) 
        );

    }

    /**
     * Hook for the admin page scriptst.
     */
    public static function hook_admin_enqueue_scripts() {
        
        wp_enqueue_style( "site-health" );

        wp_enqueue_style( 
            'icwpsa-admin-css', 
            plugins_url( '/views/css/admin.css', __DIR__ ),
            null,
            "1.0"
        );

        wp_enqueue_script( 
            'icwpsa-admin-js', 
            plugins_url( '/views/js/icwpsecurityauditadmin.js', __DIR__ ),
            null,
            "1.0",
            true // put in footer.
        );

    }

}
