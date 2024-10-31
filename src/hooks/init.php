<?php

/**
 * Class Init | src/hooks/Init.php
 * 
 * @author Innocow
 * @copyright 2020 Innocow
 */

namespace Innocow\Security_Audit\Hooks;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Init {

    /**
     * Hook for the admin page content.
     */
    public static function hook_init_load_translations() {

        load_plugin_textdomain(
            "icwpsa",
            false,
            ICWPSA_PLUGIN_SLUG . "/lang/"
        ); 

    }

}
