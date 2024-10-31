<?php

/**
 * Class About | src/views/pages/about.php
 * 
 * @author Innocow
 * @copyright 2020 Innocow
 */

namespace Innocow\Security_Audit\Views\Pages;

use Innocow\Security_Audit\Views\Copy;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class About {

    public static function js( $use_echo=false ) {

        $js = <<< JAVASCRIPT

        document.addEventListener( "click", eventClick => {

            if ( eventClick.srcElement.id === "btn-tip" ) {

                let a = document.createElement( "a" );
                a.href = "https://innocow.com/tipjar";
                a.target = "_blank";

                a.click();

            }

        } );

JAVASCRIPT;

        if ( $use_echo ) {

            echo $js;

        }

        return $js;

    }

    public static function html( array $page_elements, $use_echo=false ) {

        if ( isset( $page_elements["nav"] ) ) {

            $html_nav = $page_elements["nav"];

        }

        if ( isset( $page_elements["title"] ) ) {

            $html_title = $page_elements["title"];

        }

        $txt_title = __( "About", "icwpsa" );
        $txt_tips = __( "Best Practices and Advice", "icwpsa" );
        $txt_links_title = __( "Helpful Links", "icwpsa" );
        $txt_furtherhelp = __( "Further Help", "icwpsa" );
        $txt_sendtip = __( "☕ Is this plugin useful? Send us a tip and buy us a coffee(s)!", "icwpsa" );

        $Copy = new Copy();
        $preamble = $Copy->get( "about_preamble" );
        $bestpractices = $Copy->get( "about_advice" );
        $furtherhelp = $Copy->get( "about_furtherhelp" );

        $js = self::js();

        $html = <<< HTML

        <div class="wrap">

        $html_title

        <hr class="wp-header-end">

        $html_nav

        <p><button id='btn-tip' class='button button-primary'>
            $txt_sendtip
        </button></p>

        <h2>$txt_title</h2>

        $preamble

        <h2>$txt_tips</h2>

        $bestpractices

        <h2>$txt_links_title</h2>
        
        <ol>
            <li><a href="https://wordpress.org/support/article/hardening-wordpress/" target="_blank">
                Official Wordpress Hardening Guide
            </a></li>
            <li><a href="https://www.digitalocean.com/community/tutorials/an-introduction-to-linux-permissions" target="_blank">
                An Introduction to Linux Permissions 
            </a></li>
        </ol>

        <h3>$txt_furtherhelp</h3>

        $furtherhelp

        <script>$js</script>

HTML;

        if ( ! $use_echo ) {
        
            return $html;

        } 

        echo $html;

    }    

}