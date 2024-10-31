<?php

/**
 * Class Audit_Entity_Ownership | src/services/audit_entity_ownership.php
 * 
 * @author Innocow
 * @copyright 2020 Innocow
 */

namespace Innocow\Security_Audit\Services;

class Audit_Entity_Ownership extends Audit_Entity {

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

    /**
     * The miniumum value for a system id.
     * 
     * @var int
     */
    private $id_min = 0;

    /**
     * The maximum value for a system id.
     * 
     * @var int
     */
    private $id_max = 65535;

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
    // Accessors
    //
    //
 
    /**
    * Gets the target user id.
    *
    * @return int
    */
    public function get_uid() {

        if ( is_null( $this->uid ) ) {

            throw new \RuntimeException( "Property \$uid cannot be null." );

        }

        return $this->uid;
    
    }
 
    /**
    * Sets the target user id.
    *
    * @param mixed $uid The target user id.
    */
    public function set_uid( int $uid ) {

        if ( $uid < $this->id_min || $uid > $this->id_max ) { 

            throw new \InvalidArgumentException( "Invalid uid." );

        }

        $this->uid = $uid;

    }
 
    /**
    * Gets the target group id.
    *
    * @return int
    */
    public function get_gid() {

        if ( is_null( $this->gid ) ) {

            throw new \RuntimeException( "Property \$gid cannot be null." );
            
        }

        return $this->gid;
    
    }
 
    /**
    * Sets the target group id.
    *
    * @param mixed $gid The group id.
    */
    public function set_gid( int $gid ) {

        if ( $gid < $this->id_min || $gid > $this->id_max ) { 

            throw new \InvalidArgumentException( "Invalid uid." );

        }

        $this->gid = $gid;

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

            if ( $SplFileInfo->getOwner() === $this->get_uid() 
                 && $SplFileInfo->getGroup() === $this->get_gid() ) {
                continue;
            }

            if ( $SplFileInfo->isDir() ) { 

                $type = "d";

            } else {

                $type = "f";

            }

            yield [
                "name" => $SplFileInfo->getFilename(),
                "path" => $SplFileInfo->getPath(),
                "type" => $type,
                "perms" => $this->integer_to_octal_basic( $SplFileInfo->getPerms() ),
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

        $GeneratorCollection = $this->iterate_and_filter( $RecursiveIteratorIterator );

        $entities_array = [];

        foreach( $GeneratorCollection as $a ) {

            $entities_array[] = $a;

        };

        return $entities_array;

    }

}

    