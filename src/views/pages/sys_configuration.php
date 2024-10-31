<?php

/**
 * Class Sys_Configuration | src/views/pages/sys_configuration.php
 * 
 * @author Innocow
 * @copyright 2020 Innocow
 */

namespace Innocow\Security_Audit\Views\Pages;

use Innocow\Security_Audit\Views\Copy;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Sys_Configuration {

    public static function javascript( $use_echo=false ) {

        $txt_scanning = __( "Scanning", "icwpsa" );
        $txt_auditcompleted = __( "Audit Completed", "icwpsa" );
        $txt_pending = __( "Pending", "icwpsa" );
        $txt_passed = __( "Passed", "icwpsa" );
        $txt_warning = __( "Warning", "icwpsa" );
        $txt_no_results = __( "Audit passed: no issues found.", "icwpsa" );        

        $Copy = new Copy();
        $sensfx_title = __( "Enabled Sensitive Functions in PHP", "icwpsa" );
        $sensfx_warning_preamble = $Copy->get( "sensfx_warning_preamble" );
        $derror_title = __( "Error Display Status in PHP", "icwpsa" );
        $derror_warning_preamble = $Copy->get( "derror_warning_preamble" );
        $httpctx_title = __( "HTTPS State", "icwpsa" );
        $httpctx_warning_preamble = $Copy->get( "httpctx_warning_preamble" );
        $indexf_title = __( "Indexable Folders", "icwpsa" );
        $indexf_warning_preamble = $Copy->get( "indexf_warning_preamble" );

        $rest_urlroot =  esc_url_raw( rest_url() );
        $rest_nonce = wp_create_nonce( 'wp_rest' );

        $js = <<< JAVASCRIPT

try {

    document.getElementById( "form-conf" ).addEventListener( 
        "submit", 
        function( submitEvent ) {

            submitEvent.preventDefault();

            let formDataQueryString = new URLSearchParams( new FormData( this ) );
            let resultContainer = document.getElementById( "result" );
            let resultContent = document.getElementById( "result-content" );
            let resultHeader = document.getElementById( "result-header" );
            let txtAuditPassed = "<p>$txt_no_results</p>";

            resultContainer.setAttribute( "style", "display:block" );
            resultHeader.innerText = "$txt_scanning ...";
            resultContent.innerHTML = "";

            fetch( 
                icWPSecurityAuditAdmin.getRESTUrl(
                    "$rest_urlroot",
                    "innocow-wp-security-audit/v1/configuration/sys"
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
            .then( function( responseJSON ) { console.log( responseJSON );

                resultHeader.innerHTML = "$txt_auditcompleted";

                if ( responseJSON.hasOwnProperty( "available_sensitive_functions" ) ) {

                    let list = document.createElement( "ol" );
                    let title = "$sensfx_title";
                    let statusContent;
                    let statusClasses;
                    let descContent;

                    if ( responseJSON.available_sensitive_functions.length > 0 ) {

                        statusContent = "$txt_warning";
                        statusClasses = [ "result-mini-status", "label-warning" ];
                        descContent = "$sensfx_warning_preamble";

                        Object.keys( responseJSON.available_sensitive_functions ).forEach(
                            function( k ) {
                                let li = document.createElement( "li" );
                                li.innerText = responseJSON.available_sensitive_functions[k];
                                list.appendChild( li );
                            } 
                        );

                    } else {

                        statusContent = "$txt_passed";
                        statusClasses = [ "result-mini-status", "label-passed" ];
                        descContent = txtAuditPassed;

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
                                content: descContent,
                                classes: [ "result-mini-desc" ]
                            },
                            list
                        )
                    );

                } /** END Sensitive functions **/

                if ( responseJSON.hasOwnProperty( "do_check_httpscontext" ) ) {

                    let title = "$httpctx_title";
                    let statusContent;
                    let statusClasses;
                    let descContent;

                    if ( document.location.protocol !== "https:" ) {

                        statusContent = "$txt_warning";
                        statusClasses = [ "result-mini-status", "label-warning" ];
                        descContent = "$httpctx_warning_preamble";

                    } else {

                        statusContent = "$txt_passed";
                        statusClasses = [ "result-mini-status", "label-passed" ];
                        descContent = txtAuditPassed;

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
                                content: descContent,
                                classes: [ "result-mini-desc" ]
                            }
                        )
                    );

                } /** END HTTPS state **/

                if ( responseJSON.hasOwnProperty( "do_check_indexfolders" ) ) {

                    let list = document.createElement( "ol" );
                    let title = "$indexf_title";
                    let statusContent = "$txt_pending";
                    let statusClasses = [ "result-mini-status", "label-pending" ];

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
                                content: "<p>$txt_scanning <span id='progress'></span></p>",
                                classes: [ "result-mini-desc" ]
                            },
                            list
                        )
                    );                    

                    fetch(
                        icWPSecurityAuditAdmin.getRESTUrl(
                            "$rest_urlroot",
                            "innocow-wp-security-audit/v1/entities/folders"
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

                        let idPrefix = icWPSecurityAuditAdmin.getIdPrefixFromTitle( title );       
                        let resultMiniTitle = document.getElementById( `\${idPrefix}-title` );
                        let resultMiniStatus = document.getElementById( `\${idPrefix}-status` );
                        let resultMiniDesc = document.getElementById( `\${idPrefix}-desc` );
                        let indexableCount = 0;

                        responseJSON.forEach( function( c, i ) {

                            fetch( c.url_path )
                            .then( function( r ) {

                                indexableCount++;

                                let p = document.getElementById( "progress" );
                                if ( p.innerHTML.length <= 6 ) {

                                    p.innerHTML += ".";

                                } else {

                                    p.innerHTML = ".";

                                }
                                
                                r.text().then( function( t ) {

                                    if ( t.includes( "<title>Index of " ) && r.status === 200 ) {

                                        let a = document.createElement( "a" );
                                        a.setAttribute( "target", "_blank" );
                                        a.href = c.url_path;
                                        a.innerHTML = c.url_path;
                                        
                                        let li = document.createElement( "li" );
                                        li.appendChild( a );

                                        list.appendChild( li );

                                    }

                                    if ( indexableCount === responseJSON.length ) {

                                        if ( list.getElementsByTagName("li").length > 0 ) {

                                            resultMiniStatus.classList.remove( 
                                                "label-pending" 
                                            );
                                            resultMiniStatus.classList.add( 
                                                "label-warning" 
                                            );
                                            resultMiniStatus.innerHTML = "$txt_warning";
                                            resultMiniDesc.innerHTML = "$indexf_warning_preamble";

                                        } else {

                                            resultMiniStatus.classList.remove( 
                                                "label-pending" 
                                            );
                                            resultMiniStatus.classList.add( 
                                                "label-passed" 
                                            );
                                            resultMiniStatus.innerHTML = "$txt_passed";
                                            resultMiniDesc.innerHTML = txtAuditPassed;

                                        }

                                    }
                                    
                                } );

                                return r;

                            } )
                        } );

                    } );

                } /** END Indexable Folders **/

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

        $txt_subtitle = __( "System Configuration", "icwpsa" );
        $txt_audit = __( "Audit", "icwpsa" );

        $Copy = new Copy();
        $preamble = $Copy->get( "sysconfiguration_preamble" );

        $html = <<< HTML

        <div class="wrap">

        $html_title   

        <hr class="wp-header-end">

        $html_nav

        <h2>$txt_subtitle</h2>

        $preamble

        <form method="GET" name="form-conf" id="form-conf">
            
            <div class="formelementcontainer">

                <span class="formelement">
                    <input type="submit" id="submit-permissions" class="button action" value="$txt_audit">
                </span>

            </div>

        </form>

        <div class="postbox" id="result" style="display:none">
        <div class="inside">
            <h3 id="result-header"></h3>
            <div id="result-content"></div>
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