<?php

/**
 * Class Audit_Entity_Permissions | src/services/audit_entity_permissions.php
 * 
 * @author Innocow
 * @copyright 2020 Innocow
 */

namespace Innocow\Security_Audit\Services;

class Audit_Entity_Permissions extends Audit_Entity {

    /**
     * Whether to audit for entities not writeable by the user.
     * 
     * @var boolean
     **/
    protected $is_preferred_writeable_by_user = null;

    /**
     * Whether to audit for entities not writeable by the group.
     * 
     * @var boolean
     **/
    protected $is_preferred_writeable_by_group = null;
    
    /**
     * Whether to audit for entities not writeable by others.
     * 
     * @var boolean
     */
    protected $is_preferred_writeable_by_other = null;

    //
    //
    // Constructor
    //
    //

    public function __construct( $install_home_path ) {

        $this->set_install_home_path( $install_home_path );

        $this->set_type( "d" );        
        $this->is_preferred_writeable_by_user( true );
        $this->is_preferred_writeable_by_group( false );
        $this->is_preferred_writeable_by_other( false );

    }

    //
    //
    // Accessors
    //
    //

    /**
     * Whether the audit tests for "user" writeable entities.
     * 
     * @param $is_writeable boolean Whether to test for "user" writeability.
     * 
     * @return boolean
     */
    public function is_preferred_writeable_by_user( $is_writeable=null ) {

        if ( is_null( $is_writeable ) ) {

            return $this->is_preferred_writeable_by_user;

        }

        $this->is_preferred_writeable_by_user = (boolean) $is_writeable;

    }

    /**
     * Whether the audit tests for "group" writeable entities.
     * 
     * @param $is_writeable boolean Whether to test for "group" writeability.
     * 
     * @return boolean
     */
    public function is_preferred_writeable_by_group( $is_writeable=null ) {

        if ( is_null( $is_writeable ) ) {

            return $this->is_preferred_writeable_by_group;

        }

        $this->is_preferred_writeable_by_group = (boolean) $is_writeable;
        
    }

    /**
     * Whether the audit tests for "other" writeable entities.
     * 
     * @param $is_writeable boolean Whether to test for "other" writeability.
     * 
     * @return boolean
     */
    public function is_preferred_writeable_by_other( $is_writeable=null ) {

        if ( is_null( $is_writeable ) ) {

            return $this->is_preferred_writeable_by_other;

        }

        $this->is_preferred_writeable_by_other = (boolean) $is_writeable;
        
    }


    //
    //
    // Methods
    //
    //

    /**
     * Get the permission octal based on the audit test criteria.
     * 
     * @return string
     */
    protected function get_permission_as_octal_basic() {

        if ( $this->is_preferred_writeable_by_user() ) {

            $u_p = 6;


        } else {

            $u_p = 4;

        }

        if ( $this->is_preferred_writeable_by_group() ) {

            $g_p = 6;

        } else {

            $g_p = 4;

        }

        if ( $this->is_preferred_writeable_by_other() ) {

            $o_p = 6;

        } else {

            $o_p = 4;

        }

        if ( $this->get_type() === "d" ) {
            $u_p++;
            $g_p++;
            $o_p++;
        }

        return sprintf( "%s%s%s", $u_p, $g_p, $o_p );

    }

    /**
     * Use Iterator classes to audit files against test critera.
     * 
     * @return Generator
     */
    protected function iterate_and_filter_files( \RecursiveIteratorIterator $RecursiveIteratorIterator ) {

        foreach( $RecursiveIteratorIterator as $SplFileInfo ) {

            if ( $SplFileInfo->isDir() ) {
                
                continue;

            }

            $entity_permission = $this->integer_to_octal_basic( $SplFileInfo->getPerms() );
            if ( $entity_permission === $this->get_permission_as_octal_basic() ) {
                
                continue;

            }

            yield [
                "name" => $SplFileInfo->getBasename(),
                "path" => $SplFileInfo->getPath(),
                "type" => substr( $SplFileInfo->getType(), 0, 1 ),
                "perms" => $entity_permission,
                "uid" => $SplFileInfo->getOwner(),
                "gid" => $SplFileInfo->getGroup()
            ];

        }

    }

    /**
     * Use Iterator classes to audit folder against test critera.
     * 
     * @return Generator
     */
    protected function iterate_and_filter_folders( \RecursiveIteratorIterator $RecursiveIteratorIterator ) {

        foreach( $RecursiveIteratorIterator as $SplFileInfo ) {

            if ( ! $SplFileInfo->isDir() ) {
                
                continue;

            }

            $entity_permission = $this->integer_to_octal_basic( $SplFileInfo->getPerms() );
            if ( $entity_permission === $this->get_permission_as_octal_basic() ) {
                
                continue;

            }

            yield [
                "name" => $SplFileInfo->getBasename(),
                "path" => $SplFileInfo->getPath(),
                "type" => substr( $SplFileInfo->getType(), 0, 1 ),
                "perms" => $entity_permission,
                "uid" => $SplFileInfo->getOwner(),
                "gid" => $SplFileInfo->getGroup()
            ];

        }

    }    

    /**
     * Run the audit test and return results as a multidimensional array of files and 
     * information. If an empty array is returned, then the audit found no files that 
     * did not match.
     * 
     * @return array
     */
    public function audit() {

        $RecursiveDirectoryIterator = new \RecursiveDirectoryIterator( 
            $this->get_install_home_path(), 
            \RecursiveDirectoryIterator::SKIP_DOTS
        );

        $RecursiveIteratorIterator = new \RecursiveIteratorIterator( 
            $RecursiveDirectoryIterator, 
            \RecursiveIteratorIterator::SELF_FIRST 
        );

        switch( $this->get_type() ) {

            default:
            case "d":
                $GeneratorCollection = $this->iterate_and_filter_folders( $RecursiveIteratorIterator );
                break;

            case "f":
                $GeneratorCollection = $this->iterate_and_filter_files( $RecursiveIteratorIterator );            
                break;

        }

        $entities_array = [];

        foreach( $GeneratorCollection as $a ) {
            $entities_array[] = $a;
        };

        return $entities_array;

    }

}