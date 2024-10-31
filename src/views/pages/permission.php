<?php

/**
 * Class Permission | src/views/pages/permission.php
 * 
 * @author Innocow
 * @copyright 2020 Innocow
 */

namespace Innocow\Security_Audit\Views\Pages;

use Innocow\Security_Audit\Views\Copy;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Permission {

    public static function javascript( $use_echo=false ) {

        $txt_scanning = __( "Scanning", "icwpsa" );
        $txt_auditcompleted = __( "Audit Completed", "icwpsa" );
        $txt_jumptotop = __( "Jump to top", "icwpsa" );
        $txt_header_noresults = __( "No items found not matching audit criteria.", "icwpsa" );
        $txt_header_results = __("Found \${n} item(s) not matching audit criteria.", "icwpsa" );
        $txt_passed = __( "Passed", "icwpsa" );
        $txt_warning = __( "Warning", "icwpsa" );
        $txt_user = __( "User", "icwpsa" );
        $txt_group = __( "Group", "icwpsa" );
        $txt_other = __( "Other", "icwpsa" );

        $rest_urlroot =  esc_url_raw( rest_url() );
        $rest_nonce = wp_create_nonce( 'wp_rest' );
        
        $js = <<< JAVASCRIPT

try {

    document.getElementById( "form-ffp" ).addEventListener( 
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
                    "innocow-wp-security-audit/v1/entities/permissions/",
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
                let statusContent;
                let statusClasses;
                let title;

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
                        criteria.innerHTML = "$txt_user:";
                        criteria.innerHTML += icWPSecurityAuditAdmin.getPermissionLettersByClass( 
                            "u", 
                            responseJSON[key].perms
                        );
                        criteria.innerHTML += ", $txt_group:";
                        criteria.innerHTML += icWPSecurityAuditAdmin.getPermissionLettersByClass( 
                            "g", 
                            responseJSON[key].perms 
                        );
                        criteria.innerHTML += ", $txt_other:";
                        criteria.innerHTML += icWPSecurityAuditAdmin.getPermissionLettersByClass( 
                            "o", 
                            responseJSON[key].perms 
                        );

                        let filename = document.createElement( "span" );
                        filename.classList.add( "list-column-2" );
                        filename.classList.add( "column-filename" );
                        filename.innerHTML = ` \${responseJSON[key].path}/`;
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
                        "ffpermissions"
                    )
                );

            } );


    } );

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
        $txt_subtitle = __( "File & Folder Permissions", "icwpsa" );
        $txt_bothff = __( "Both Files & Folders", "icwpsa" );
        $txt_files = __( "Files", "icwpsa" );
        $txt_folders = __( "Folders", "icwpsa" );
        $txt_user = __( "User", "icwpsa" );
        $txt_group = __( "Group", "icwpsa" );
        $txt_other = __( "Other", "icwpsa" );
        $txt_readwrite_perm = __( "read/write (rw)", "icwpsa" );
        $txt_read_perm = __( "read (r)", "icwpsa" );
        $txt_audit = __( "Audit", "icwpsa" );
        $preamble = $Copy->get( "ffpermissions_preamble" );        

        $html = <<< HTML
        
        <div class="wrap">

        $html_title   

        <hr class="wp-header-end">

        $html_nav

        <h2>$txt_subtitle</h2>

        <span class="preamble">
            $preamble
        <span>

        <a name="pagetop"></a>
        <form method="GET" name="form-ffp" id="form-ffp">

            <div class="formelementcontainer">

                <span class="formelement">
                    <select name="type" id="select-permissions-type">
                        <option value="a">$txt_bothff</option>
                        <option value="f">$txt_files</option>
                        <option value="d">$txt_folders</option>
                    </select>
                </span>

                <span class="formelement">
                    <select name="owner" id="select-permissions-owner">
                        <option value="rw">$txt_user: $txt_readwrite_perm</option>
                        <option value="r">$txt_user: $txt_read_perm</option>
                    </select>
                </span>

                <span class="formelement">
                    <select name="group" id="select-permissions-group">
                        <option value="rw">$txt_group: $txt_readwrite_perm</option>
                        <option value="r">$txt_group: $txt_read_perm</option>
                    </select>
                </span>

                <span class="formelement">
                    <select name="other" id="select-permissions-other">
                        <option value="rw">$txt_other: $txt_readwrite_perm</option>
                        <option value="r">$txt_other: $txt_read_perm</option>
                    </select>
                </span>

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

        </div>

        <script>$js</script>

HTML;

        if ( ! $use_echo ) {
        
            return $html;

        } 

        echo $html;

    }    


}