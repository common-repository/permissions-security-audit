class icWPSecurityAuditAdmin {

    static getIdPrefixFromTitle( title ) {

        var idPrefix = title;
        idPrefix = idPrefix.replace( /\s+/g, "" ).toLowerCase();
        idPrefix = idPrefix.replace( /\/|\:/g, "" ).toLowerCase();        

        return idPrefix;

    }

    static getFormattedResultMini( titleObject={}, statusObject={}, descObject={}, listObject, idPrefix ) {

        if ( ! titleObject.hasOwnProperty( "content") && ! titleObject.hasOwnProperty( "classes" ) ) {

            throw new Error( "Parameter titleObject requires 'content' and 'classes' properties." );

        }

        if ( ! Array.isArray( titleObject.classes ) ) {

            throw new Error( "Parameter titleObject.content must be of type Array." );

        }

        if ( ! statusObject.hasOwnProperty( "content") && ! statusObject.hasOwnProperty( "classes" ) ) {

            throw new Error( "Parameter statusObject requires 'content' and 'classes' properties." );

        }

        if ( ! Array.isArray( statusObject.classes ) ) {

            throw new Error( "Parameter statusObject.content must be of type Array." );

        }
        
        if ( ! descObject.hasOwnProperty( "content") && ! descObject.hasOwnProperty( "classes" ) ) {

            throw new Error( "Parameter descObject requires 'content' and 'classes' properties." );

        }

        if ( ! Array.isArray( descObject.classes ) ) {

            throw new Error( "Parameter descObject.content must be of type Array." );

        }

        if ( idPrefix === undefined ) {
        
            var idPrefix = this.getIdPrefixFromTitle( titleObject.content );

        }

        var result;
        result = document.createElement( "div" );
        result.id = idPrefix;
        result.classList.add( "result-mini" );

        var header;
        var headerTitle;
        var headerStatus;
        var description;
        var descriptionTxt;
        var descriptionContent

        headerStatus = document.createElement( "span" );
        headerStatus.id = `${idPrefix}-status`;
        headerStatus.classList.add( ...statusObject.classes );
        headerStatus.innerHTML = statusObject.content;        
        result.appendChild( headerStatus );
        
        headerTitle = document.createElement( "span" );
        headerTitle.id = `${idPrefix}-title`;
        headerTitle.classList.add( ...titleObject.classes );
        headerTitle.innerHTML = titleObject.content;        
        result.appendChild( headerTitle );

        description = document.createElement( "span" );
        description.id = `${idPrefix}-desc`;
        description.classList.add( ...descObject.classes );
        description.innerHTML = descObject.content;
        result.appendChild( description );

        if ( listObject instanceof HTMLUListElement || listObject instanceof HTMLOListElement ) {

            listObject.id = `${idPrefix}-list`;
            result.appendChild( listObject );

        }

        return result;

    }

    static getRESTUrl( wpRESTRoot, namespace, parameters ) {

        if ( wpRESTRoot === undefined ) { 

            throw new Error( "Undefined parameter: wpRESTRoot" ); 

        }
        if ( namespace === undefined ) { 

            throw new Error( "Undefined parameter: namespace" ); 

        }

        if ( wpRESTRoot.substr( -1 ) === "/" && namespace.charAt( 0 ) === "/" ) {

            namespace = namespace.substr( 1 );
            
        }

        var url = wpRESTRoot + namespace;

        if ( parameters !== undefined ) {

            if ( wpRESTRoot.includes( "/wp-json/" ) ) {

                url += "?" + parameters;

            }

            if ( wpRESTRoot.includes( "/index.php?rest_route=/") ) {

                url += "&" + parameters;

            }

        }

        return url;

    }

    static getPermissionLettersByClass( permission_class, permission_octal_string, hide_hyphens=false ) {

        if ( ! ["u", "g", "o" ].includes( permission_class ) ) {

            throw new Error( "Invalid parameter: permission_class, must be 'u', 'g', 'o'" );

        }

        if ( permission_octal_string === undefined ) { 

            throw new Error( "Undefined parameter: permission_octal_string" ); 

        }

        if ( permission_octal_string.length !== 3 ) {

            throw new Error( "Invalid parameter: permission_octal_string" );

        }

        switch( permission_class ) {

            default:
            case "u":
                var permission_int = parseInt( permission_octal_string[0] );
                break;

            case "g":
                var permission_int = parseInt( permission_octal_string[1] );
                break;


            case "o":
                var permission_int = parseInt( permission_octal_string[2] );
                break;

        }

        switch( permission_int ) {

            case 0: var return_value =  "---"; break;
            case 1: var return_value =  "--x"; break;
            case 2: var return_value =  "-w-"; break;
            case 3: var return_value =  "-wx"; break;
            case 4: var return_value =  "r--"; break;
            case 5: var return_value =  "r-x"; break;
            case 6: var return_value =  "rw-"; break;
            case 7: var return_value =  "rwx"; break;

        }

        if ( hide_hyphens ) {

            return return_value.replace( /-+/g, "" );

        }

        return return_value;

    }

    static getHrefTag( link, use_new_window=true ) {

        var a = document.createElement( "a" );
        a.href = link;
        a.innerText = link;

        if ( use_new_window ) {
        
            a.setAttribute( "target", "_blank" );

        }

        return a.outerHTML;

    }

    static setCurrentNavLink() {

        switch( window.location.search ) {

            case "?page=icwpsa":
                var liClass = "nav-about";
                break;

            case "?page=icwpsa&load=permission":
                var liClass = "nav-permission";
                break;

            case "?page=icwpsa&load=ownership":
                var liClass = "nav-ownership";
                break;

            case "?page=icwpsa&load=wp_configuration":
                var liClass = "nav-wp-configuration";
                break;

            case "?page=icwpsa&load=sys_configuration":
                var liClass = "nav-sys-configuration";
                break;

        }

        if ( document.querySelector( `.${liClass} a` ) ) {
        
            var a = document.querySelector( `.${liClass} a` );
            a.setAttribute( "class", "current" );
            a.setAttribute( "aria-current", "page" );

        }

    }

}

document.addEventListener( "DOMContentLoaded", function( event ) {

    icWPSecurityAuditAdmin.setCurrentNavLink();

} );