<?php

/**
 * Class Loader | src/views/loader.php
 * 
 * @author Innocow
 * @copyright 2020 Innocow
 */

namespace Innocow\Security_Audit\Views;

use Innocow\Security_Audit\Views\Pages\About;
use Innocow\Security_Audit\Views\Pages\Permission;
use Innocow\Security_Audit\Views\Pages\Ownership;
use Innocow\Security_Audit\Views\Pages\WP_Configuration;
use Innocow\Security_Audit\Views\Pages\Sys_Configuration;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Loader {

    public static function html_title() {

        $title = __( "Permissions & Security Audit by Innocow", "icwpsa" );

        $html_title = <<< HTML

        <h1 class="wp-heading-inline">
            $title
        </h1>

HTML;

        return $html_title;

    }    

    public static function html_nav() {

        $nav_title_about = __( "About", "icwpsa" );
        $nav_title_ffpermissions = __( "File & Folder Permissions", "icwpsa" );
        $nav_title_ffownership = __( "File & Folder Ownership", "icwpsa" );
        $nav_title_wp_configuration = __( "WP Configuration", "icwpsa" );
        $nav_title_sys_configuration = __( "System Configuration", "icwpsa" );

        $plugin_url = menu_page_url( "icwpsa", false );

        $html_nav = <<< HTML

        <ul class="subsubsub" style="float:none">
            <li class="nav-about">
                <a href="$plugin_url">
                    $nav_title_about
                </a> |
            </li>
            <li class="nav-permission">
                <a href="$plugin_url&load=permission">
                    $nav_title_ffpermissions
                </a> |
            </li>
            <li class="nav-ownership">
                <a href="$plugin_url&load=ownership">
                    $nav_title_ffownership
                </a> |
            </li>
            <li class="nav-wp-configuration">
                <a href="$plugin_url&load=wp_configuration">
                    $nav_title_wp_configuration
                </a> |
            </li>
            <li class="nav-sys-configuration">
                <a href="$plugin_url&load=sys_configuration">
                    $nav_title_sys_configuration
                </a>
            </li>            
        </ul>

HTML;

        return $html_nav;

    }

    public static function load() {

        $load = isset( $_GET["load"] ) ? $_GET["load"] : "about";

        switch( $load ) {

            case "about":
            default:
                echo About::html( [
                    "nav" => self::html_nav(),
                    "title" => self::html_title()
                ] );
                break;

            case "permission":
                echo Permission::html( [
                    "nav" => self::html_nav(),
                    "title" => self::html_title()
                ] );
                break;

            case "ownership":
                echo Ownership::html( [ 
                    "nav" => self::html_nav(),
                    "title" => self::html_title()
                ] );
                break;

            case "wp_configuration":
                echo WP_Configuration::html( [ 
                    "nav" => self::html_nav(),
                    "title" => self::html_title()
                ] );
                break;

            case "sys_configuration":
                echo Sys_Configuration::html( [ 
                    "nav" => self::html_nav(),
                    "title" => self::html_title()
                ] );
                break;

        }

    }

}