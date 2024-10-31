<?php

/**
 * Class Rest | src/controllers/rest.php
 * 
 * @author Innocow
 * @copyright 2020 Innocow
 */

namespace Innocow\Security_Audit\Controllers;

use Innocow\Security_Audit\Services\Audit_Entity_Permissions;
use Innocow\Security_Audit\Services\Audit_Entity_Ownership;
use Innocow\Security_Audit\Services\Audit_Entity_Folders;
use Innocow\Security_Audit\Services\Audit_Configuration;

class Rest {

    /**
     * Validates the incoming permission parameter.
     * 
     * @return boolean
     */
    protected static function is_permission_valid( string $form_value ) {

        $valid_permissions_array = array( "r", "rw" );

        return in_array( $form_value, $valid_permissions_array );

    }

    /**
     * Validates the incoming type parameter.
     * 
     * @return boolean
     */
    protected static function is_type_valid( string $type ) {

        $valid_types_array = array( "f", "d", "a" );

        return in_array( $type, $valid_types_array );

    }

    /**
     * Controller for URL: /entities/permission.
     * 
     * @return array
     */
    public static function entities_permissions( \WP_REST_Request $Request ) {

        try {

            $query_array = $Request->get_query_params();

            if ( ! isset( $query_array["type"] ) ) {

                throw new \InvalidArgumentException( "Parameter 'type' missing." );

            }

            if ( ! self::is_type_valid( $query_array["type"] ) ) {

                throw new \InvalidArgumentException( "Parameter 'type' invalid." );

            }

            if ( ! isset( $query_array["owner"] ) ) {

                throw new \InvalidArgumentException( "Parameter 'owner' missing." );

            }

            if ( ! self::is_permission_valid( $query_array["owner"] ) ) {

                throw new \InvalidArgumentException( "Parameter 'owner' invalid." );

            }

            if ( ! isset( $query_array["group"] ) ) {

                throw new \InvalidArgumentException( "Parameter 'group' missing." );

            }

            if ( ! self::is_permission_valid( $query_array["group"] ) ) {

                throw new \InvalidArgumentException( "Parameter 'group' invalid." );

            }

            if ( ! isset( $query_array["other"] ) ) {

                throw new \InvalidArgumentException( "Parameter 'other' missing." );

            }

            if ( ! self::is_permission_valid( $query_array["other"] ) ) {

                throw new \InvalidArgumentException( "Parameter 'other' invalid." );

            }        

            $AEP = new Audit_Entity_Permissions( ABSPATH );

            if ( $query_array["owner"] === "rw" ) {

                $AEP->is_preferred_writeable_by_user( true );

            } else {

                $AEP->is_preferred_writeable_by_user( false );

            }

            if ( $query_array["group"] === "rw" ) {

                $AEP->is_preferred_writeable_by_group( true );

            } else {

                $AEP->is_preferred_writeable_by_group( false );

            }

            if ( $query_array["other"] === "rw" ) {

                $AEP->is_preferred_writeable_by_other( true );

            } else {

                $AEP->is_preferred_writeable_by_other( false );

            }

            switch( $query_array["type"] ) {

                default:
                case "a":
                    $AEP->set_type( "f" );
                    $return_array_files = $AEP->audit();
                    $AEP->set_type( "d" );
                    $return_array_folders = $AEP->audit();
                    $return_array = array_merge(
                        $return_array_files,
                        $return_array_folders
                    );
                    break;

                case "f":
                    $AEP->set_type( "f" );
                    $return_array = $AEP->audit();
                    break;

                case "d":
                    $AEP->set_type( "d" );
                    $return_array = $AEP->audit();
                    break;

            }

            return $return_array;

        } catch ( \Exception $e ) {

            error_log( $e->getMessage() . " " . $e->getTraceAsString() );
            return new \WP_Error( "error", "Uncaught exception in controller", [ 'status' => 500 ] );

        }

    }

    /**
     * Controller for URL: /entities/ownership.
     * 
     * @return array
     */
    public static function entities_ownership( \WP_REST_Request $Request ) {

        try {

            $query_array = $Request->get_query_params();

            if ( ! isset( $query_array["uid"] ) 
                 || is_null( $query_array["uid"] )
                 || $query_array["uid"] === "" ) {

                throw new \InvalidArgumentException( "Parameter 'uid' missing." );

            }

            if ( ! isset( $query_array["gid"] )
                 || is_null( $query_array["gid"] )
                 || $query_array["gid"] === "" ) {

                throw new \InvalidArgumentException( "Parameter 'gid' missing." );
                
            }

            $AEO = new Audit_Entity_Ownership( ABSPATH );

            $uid_int = (int) $query_array["uid"];
            $AEO->set_uid( $uid_int );

            $gid_int = (int) $query_array["gid"];
            $AEO->set_gid( $gid_int );

            $return_array = $AEO->audit();

            return $return_array;

        } catch ( \InvalidArgumentException $e ) {

            return new \WP_Error( "alert", $e->getMessage(), [ 'status' => 400 ] );

        } catch ( \Exception $e ) {

            error_log( $e->getMessage() . " " . $e->getTraceAsString() );
            return new \WP_Error( "error", "Uncaught exception in controller", [ 'status' => 500 ] );

        }
 
    }

    /**
     * Controller for URL: /entities/folders.
     * 
     * @return array
     */
    public static function entities_folders( \WP_REST_Request $Request ) {

        try {

            $AEF = new Audit_Entity_Folders( ABSPATH );
            $AEF->set_type( "d" );

            $return_array = $AEF->audit();

            return $return_array;

        } catch ( \InvalidArgumentException $e ) {

            return new \WP_Error( "alert", $e->getMessage(), [ 'status' => 400 ] );

        } catch ( \Exception $e ) {

            error_log( $e->getMessage() . " " . $e->getTraceAsString() );
            return new \WP_Error( "error", "Uncaught exception in controller", [ 'status' => 500 ] );

        }
 
    }

    /**
     * Controller for URL: /server/process/details
     * 
     * @return array
     */
    public static function server_process_details( \WP_REST_Request $Request ) {

        try {

            $temp_file_name = tempnam( sys_get_temp_dir(), "icwpsa_" );

            $process_uid = fileowner( $temp_file_name );
            $process_gid = filegroup( $temp_file_name );

            unlink( $temp_file_name );

            $return_array = [ 
                "userid" => $process_uid,
                "groupid" => $process_gid
            ];

            return $return_array;

        } catch ( \Exception $e ) {

            error_log( $e->getMessage() . " " . $e->getTraceAsString() );
            return new \WP_Error( "error", "Uncaught exception in controller", [ 'status' => 500 ] );

        }

    }

    /**
     * Controller for URL: /server/configuration/wp
     * 
     * @return array
     */
    public static function configuration_wp( \WP_REST_Request $Request ) {

        try {

            $AC = new Audit_Configuration();

            /**
             * For futureproofing turning off/on individual tests.
             */
            $do_check_author1url = true;
            $do_check_wploginphp = true;

            $return_array = [
                "public_rest_routes" => $AC->audit_wp_public_rest_api(),
                "common_admin_accounts" => $AC->audit_wp_admin_accounts(),
                "wpconfig_changed_days_ago" => $AC->audit_wp_get_wpconfig_time_last_modified(),
                "is_table_prefix_default" => $AC->audit_wp_db_table_prefixes(),
                "is_core_autoupdate_on" => $AC->audit_wp_is_core_autoupdate_on(),
                "are_files_editable" => $AC->audit_wp_are_files_editable(),
                "do_check_author1url" => $do_check_author1url,
                "do_check_wploginphp" => $do_check_wploginphp,
            ];

            return $return_array;

        } catch ( \Exception $e ) {

            error_log( $e->getMessage() . " " . $e->getTraceAsString() );
            return new \WP_Error( "error", "Uncaught exception in controller", [ 'status' => 500 ] );

        }

    }

    /**
     * Controller for URL: /server/configuration/sys
     * 
     * @return array
     */
    public static function configuration_sys( \WP_REST_Request $Request ) {

        try {

            $AC = new Audit_Configuration();

            /**
             * For futureproofing turning off/on individual tests.
             */
            $do_check_httpscontext = true;
            $do_check_indexfolders = true;

            $return_array = [
                "do_check_httpscontext" => $do_check_httpscontext,
                "do_check_indexfolders" => $do_check_indexfolders,
                "available_sensitive_functions" => $AC->audit_sys_sensitive_functions()
            ];

            return $return_array;

        } catch ( \Exception $e ) {

            error_log( $e->getMessage() . " " . $e->getTraceAsString() );
            return new \WP_Error( "error", "Uncaught exception in controller", [ 'status' => 500 ] );

        }

    }

}