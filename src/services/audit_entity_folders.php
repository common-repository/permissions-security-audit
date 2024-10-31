<?php

/**
 * Class Audit_Entity_Ownership | src/services/audit_entity_ownership.php
 * 
 * @author Innocow
 * @copyright 2020 Innocow
 */

namespace Innocow\Security_Audit\Services;

class Audit_Entity_Folders extends Audit_Entity {

    /**
     * The uid to test entities against.
     * 
     * @var int
     */
    protected $uid = null;

    /**
     * The gid to test entities against.
     * 
     * @var int
     */
    protected $gid = null;

    //
    //
    // Constructor
    //
    //

    public function __construct( string $install_home_path ) {

        $this->set_install_home_path( $install_home_path );

    }

    //
    //
    // Methods
    //
    //

    /**
     * Use Iterator classes to audit folder against test critera.
     * 
     * @return Generator
     */
    protected function iterate_and_filter( \RecursiveIteratorIterator $RecursiveIteratorIterator ) {

        foreach( $RecursiveIteratorIterator as $SplFileInfo ) {

            if ( ! $SplFileInfo->isDir() ) {
                
                continue;

            }            

            // strlen() - 1 to keep the trailing "/"
            $url_path = strstr( $SplFileInfo->getPathName(), $this->get_install_home_path() );
            $url_path = substr( $url_path, strlen( $this->get_install_home_path() ) - 1 );

            yield [
                "sys_path" => $SplFileInfo->getPathName(),
                "url_path" => $url_path
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

        $GeneratorCollection = $this->iterate_and_filter( $RecursiveIteratorIterator );

        $entities_array = [];

        foreach( $GeneratorCollection as $a ) {

            $entities_array[] = $a;

        };

        return $entities_array;

    }

}

    