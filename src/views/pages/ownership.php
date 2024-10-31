<?php

/**
 * Class Ownership | src/views/pages/ownership.php
 * 
 * @author Innocow
 * @copyright 2020 Innocow
 */

namespace Innocow\Security_Audit\Views\Pages;

use Innocow\Security_Audit\Views\Copy;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Ownership {

    public static function javascript( $use_echo=false ) {

        $txt_scanning = __( "Scanning", "icwpsa" );
        $txt_auditcompleted = __( "Audit Completed", "icwpsa" );
        $txt_jumptotop = __( "Jump to top", "icwpsa" );        
        $txt_header_noresults = __( "No items found not matching audit criteria.", "icwpsa" );
        $txt_header_results = __("Found \${n} item(s) not matching audit criteria.", "icwpsa" );
        $txt_passed = __( "Passed", "icwpsa" );
        $txt_warning = __( "Warning", "icwpsa" );        

        $rest_urlroot =  esc_url_raw( rest_url() );
        $rest_nonce = wp_create_nonce( 'wp_rest' );

        $js = <<< JAVASCRIPT

try {

    document.addEventListener( "DOMContentLoaded", function( event ) {

        fetch(
            icWPSecurityAuditAdmin.getRESTUrl(
                "$rest_urlroot",
                "innocow-wp-security-audit/v1/server/process/details"
            ), {
                credentials: "include",
                headers: {
                  "content-type": "application/json",
                  "X-WP-Nonce": "$rest_nonce"
                }
            }
        )
        .then( response => response.json() )
        .then( function( responseJSON ) {

            if ( ! responseJSON.hasOwnProperty( "userid" ) 
                 && ! responseJSON.hasOwnProperty( "groupid" ) ) {
                
                return;   
            
            }

            uid = responseJSON["userid"];
            gid = responseJSON["groupid"];

            ( document.getElementById( "text-uid" ) ).innerHTML = `<b>\${uid}</b>`;
            ( document.getElementById( "text-gid" ) ).innerHTML = `<b>\${gid}</b>`;
            ( document.getElementById( "input-uid" ) ).value = uid;
            ( document.getElementById( "input-gid" ) ).value = gid;

            ( document.getElementById( "preamble-id-discovery" ) ).setAttribute(
                "style",
                "display:block"
            );

        } );

    } );

} catch( e ) {

    console.log( e );

}

try {

    document.getElementById( "form-ffo" ).addEventListener( 
        "submit", 
        function( submitEvent ) {

            submitEvent.preventDefault();

            let formDataQueryString = new URLSearchParams( new FormData( this ) );
            let resultContainer = document.getElementById( "result" );
            let resultHeader = document.getElementById( "result-header" );
            let resultContent = document.getElementById( "result-content" );
            let resultList = document.createElement( "ol" );

            resultContainer.setAttribute( "style", "display:block" );
            resultHeader.innerText = "$txt_scanning ...";
            resultContent.innerHTML = null;

            fetch( 
                icWPSecurityAuditAdmin.getRESTUrl(
                    "$rest_urlroot",
                    "innocow-wp-security-audit/v1/entities/ownership/",
                    formDataQueryString.toString()
                ), {
                    credentials: "include",
                    headers: {
                      "content-type": "application/json",
                      "X-WP-Nonce": "$rest_nonce"
                    }
                }
            )
            .then( function( response ) {

                if ( ! response.ok ) {

                    response.json().then( function( errorJSON ) {

                        resultHeader.innerText = `\${errorJSON.code.toUpperCase()}: \${errorJSON.message}`;
                        
                    } );
                    
                    return;

                }

                return response.json();

            } )
            .then( function( responseJSON ) {

                resultHeader.innerText = "$txt_auditcompleted";

                let n = Object.keys( responseJSON ).length;
                let title;
                let statusContent;
                let statusClasses;

                if ( n === 0 ) {

                    statusContent = "$txt_passed";
                    statusClasses = [ "result-mini-status", "label-passed" ];
                    title = `$txt_header_noresults`;

                } else {

                    statusContent = "$txt_warning";
                    statusClasses = [ "result-mini-status", "label-warning" ];
                    title = `$txt_header_results`;

                    Object.keys( responseJSON ).forEach( function( key ) {

                        let criteria = document.createElement( "span" );
                        criteria.classList.add( "list-column-1" );
                        criteria.classList.add( "column-criteria" );
                        criteria.classList.add( "text-emphasis" );
                        criteria.innerHTML = `Uid:\${responseJSON[key].uid}, `;
                        criteria.innerHTML += `Gid:\${responseJSON[key].gid}, `;

                        let filename = document.createElement( "span" );
                        filename.classList.add( "list-column-2" );
                        filename.classList.add( "column-filename" );
                        filename.innerHTML = `\${responseJSON[key].path}/`;
                        filename.innerHTML += `\${responseJSON[key].name}`;
                        if ( responseJSON[key].type === "d" ) { filename.innerHTML += "/"; }

                        let li = document.createElement( "li" );
                        li.appendChild( criteria );
                        li.appendChild( filename );
                        resultList.appendChild( li );

                    } );

                    if ( responseJSON.length > 20 ) {

                        let a = document.createElement( "a" );
                        a.href = "#pagetop";
                        a.innerText = "$txt_jumptotop";
                        resultList.appendChild( a );

                    }

                }

                resultContent.appendChild( 
                    icWPSecurityAuditAdmin.getFormattedResultMini(
                        {
                            content: title,
                            classes: [ "result-mini-title" ]
                        },
                        {
                            content: statusContent,
                            classes: statusClasses
                        },
                        {
                            content: "",
                            classes: [ "result-mini-desc" ]
                        },
                        resultList,
                        "ffownership"
                    )
                );                        

            } );

        } 
    );

} catch ( e ) {

    console.log ( e );

}

JAVASCRIPT;

        if ( ! $use_echo ) {

            return $js;

        }

        echo $js;

    }

    public static function html( array $page_elements, $use_echo=false ) {

        if ( isset( $page_elements["nav"] ) ) {

            $html_nav = $page_elements["nav"];

        }

        if ( isset( $page_elements["title"] ) ) {

            $html_title = $page_elements["title"];

        }

        $js = self::javascript();

        $Copy = new Copy();
        $txt_subtitle = __( "File & Folder Ownership", "icwpsa" );
        $txt_audit = __( "Audit", "icwpsa" );
        $preamble = $Copy->get( "ffownership_preamble" );
        $id_discovery = $Copy->get( "ffownership_id_discovery" );

        $html = <<< HTML

        <div class="wrap">

        $html_title

        <hr class="wp-header-end">

        $html_nav

        <h2>$txt_subtitle</h2>

        $preamble

        <p id="preamble-id-discovery" style="display:none">
            $id_discovery
        </p>

        <a name="pagetop"></a>
        <form method="GET" name="form-ffo" id="form-ffo">

            <div class="formelementcontainer">

                <span class="formelement">
                    Uid:
                    <input name="uid" id="input-uid" type="text" size="5" placeholder="Uid">
                </span>
                <span class="formelement">
                    Gid:
                    <input name="gid" id="input-gid" type="text" size="5" placeholder="Gid">
                </span>
                <span class="formelement">
                    <input type="submit" id="submit-permissions" class="button action" value="$txt_audit">
                </span>

            </div>
            
        </form>

        <div class="postbox" id="result" style="display:none;">
        <div class="inside">
            <h3 id="result-header"></h3>
            <div id="result-content"></div>
        </div>
        </div>

        </div>

        <script>$js</script>

HTML;

        if ( ! $use_echo ) {
        
            return $html;

        } 

        echo $html;

    }    


}