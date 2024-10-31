<?php

/**
 * Class WP_Configuration | src/views/pages/wp_configuration.php
 * 
 * @author Innocow
 * @copyright 2020 Innocow
 */

namespace Innocow\Security_Audit\Views\Pages;

use Innocow\Security_Audit\Views\Copy;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class WP_Configuration {

    public static function javascript( $use_echo=false ) {

        $txt_scanning = __( "Scanning", "icwpsa" );
        $txt_header_completed = __( "Audit Completed", "icwpsa" );        
        $txt_passed = __( "Passed", "icwpsa" );
        $txt_warning = __( "Warning", "icwpsa" );
        $txt_no_results = __( "Audit passed: no issues found.", "icwpsa" );

        $Copy = new Copy();
        $restendp_title = __( "Problematic REST Routes", "icwpsa" );
        $restendp_warning_preamble = $Copy->get( "restendp_warning_preamble" );
        $admusers_title = __( "Common Admin Usernames", "icwpsa" );
        $admusers_warning_preamble = $Copy->get( "admusers_warning_preamble" );
        $tablepfx_title = __( "Default Table Prefix", "icwpsa" );
        $tablepfx_warning_preable = $Copy->get( "tablepfx_warning_preable" );
        $fileedit_title = __( "File Editor Access", "icwpsa" );
        $fileedit_warning_preamble = $Copy->get( "fileedit_warning_preamble" );
        $author1_title = __( "Author=1 Page Check", "icwpsa" );
        $author1_warning_preamble = $Copy->get( "author1_warning_preamble" );
        $wplogin_title = __( "Default Login URL", "icwpsa" );
        $wplogin_warning_preamble = $Copy->get( "wplogin_warning_preamble" );
        $autoupd_title = __( "Core Auto Update", "icwpsa" );
        $autoupd_warning_preamble = $Copy->get( "autoupd_warning_preamble" );

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
                    "innocow-wp-security-audit/v1/configuration/wp"
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

                resultHeader.innerHTML = "$txt_header_completed";

                if ( responseJSON.hasOwnProperty( "public_rest_routes" ) ) {

                    let title = "$restendp_title";
                    let idPrefix = icWPSecurityAuditAdmin.getIdPrefixFromTitle( title );
                    let list = document.createElement( "ol" );
                    let statusContent;
                    let statusClasses;
                    let descContent;

                    if ( responseJSON.public_rest_routes.length > 0 ) {

                        statusContent = "$txt_warning";
                        statusClasses = [ "result-mini-status", "label-warning" ];
                        descContent = "$restendp_warning_preamble";

                    } else {

                        statusContent = "$txt_passed";
                        statusClasses = [ "result-mini-status", "label-passed" ];
                        descContent = txtAuditPassed;

                    }

                    Object.keys( responseJSON.public_rest_routes ).forEach(
                        function( k ) {

                            let routeURI = responseJSON.public_rest_routes[k];
                            let a = document.createElement( "a" );
                            let li = document.createElement( "li" );
                            a.href = icWPSecurityAuditAdmin.getRESTUrl(
                                "$rest_urlroot",
                                routeURI
                            );
                            a.innerHTML = routeURI;
                            a.setAttribute( "target", "_blank" );
                            li.appendChild( a );
                            list.appendChild( li );
                        } 
                    );

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


                } /** END Problematic REST Routes **/
                
                if ( responseJSON.hasOwnProperty( "common_admin_accounts" ) ) {

                    let title = "$admusers_title";
                    let list = document.createElement( "ol" );
                    let statusContent;
                    let statusClasses;
                    let descContent;

                    if ( responseJSON.common_admin_accounts.length > 0 ) {

                        statusContent = "$txt_warning";
                        statusClasses = [ "result-mini-status", "label-warning" ];

                        
                        Object.keys( responseJSON.common_admin_accounts ).forEach(
                            function( k ) {
                                let li = document.createElement( "li" );
                                li.innerText = responseJSON.common_admin_accounts[k];
                                list.appendChild( li );
                            } 
                        );

                        descContent = "$admusers_warning_preamble";

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

                } /** END Admin accounts **/

                if ( responseJSON.hasOwnProperty( "is_table_prefix_default" ) ) {

                    let title = "$tablepfx_title";
                    let statusContent;
                    let statusClasses;
                    let descContent;

                    if ( responseJSON.is_table_prefix_default ) {

                        statusContent = "$txt_warning";
                        statusClasses = [ "result-mini-status", "label-warning" ];
                        descContent = "$tablepfx_warning_preable";

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
                
                } /** END default table prefixes **/

                if ( responseJSON.hasOwnProperty( "are_files_editable" ) ) {

                    let title = "$fileedit_title";
                    let statusContent;
                    let statusClasses;
                    let descContent;

                    if ( responseJSON.are_files_editable ) {

                        statusContent = "$txt_warning";
                        statusClasses = [ "result-mini-status", "label-warning" ];
                        descContent = "$fileedit_warning_preamble";

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

                } /** END default table prefixes **/

                /** Author ID  **/

                if ( responseJSON.hasOwnProperty( "do_check_author1url" ) ) {

                    fetch( "/?author=1" )
                    .then( function( response ) {

                        let title = "$author1_title";
                        let statusContent;
                        let statusClasses;
                        let descContent;

                        if ( response.status === 200 ) {

                            statusContent = "$txt_warning";
                            statusClasses = [ "result-mini-status", "label-warning" ];
                            descContent = "$author1_warning_preamble";

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

                    } );

                } /** END author ID **/

                if ( responseJSON.hasOwnProperty( "do_check_wploginphp" ) ) {

                    fetch( "/wp-login.php" )
                    .then( function( response ) {

                        let title = "$wplogin_title";
                        let statusContent;
                        let statusClasses;
                        let descContent;

                        if ( response.status !== 200 ) {

                            statusContent = "$txt_passed";
                            statusClasses = [ "result-mini-status", "label-passed" ];
                            descContent = txtAuditPassed;

                        } else {

                            statusContent = "$txt_warning";
                            statusClasses = [ "result-mini-status", "label-warning" ];
                            descContent = "";
                            descContent = "$wplogin_warning_preamble";

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

                    } );

                } /** END wploginphp **/

                if ( responseJSON.hasOwnProperty( "is_core_autoupdate_on" ) ) {

                        let title = "$autoupd_title";
                        let statusContent;
                        let statusClasses;
                        let descContent;

                        if ( responseJSON.is_core_autoupdate_on ) {

                            statusContent = "$txt_passed";
                            statusClasses = [ "result-mini-status", "label-passed" ];
                            descContent = txtAuditPassed;

                        } else {

                            statusContent = "$txt_warning";
                            statusClasses = [ "result-mini-status", "label-warning" ];
                            descContent = "";
                            descContent = "$autoupd_warning_preamble";

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

                } /** END core autoupdate **/

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

        $txt_subtitle = __( "WP Configuration", "icwpsa" );
        $txt_audit = __( "Audit", "icwpsa" );

        $Copy = new Copy();
        $preamble = $Copy->get( "wpconfiguration_preamble" );

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
            <div id="result-content">
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