<?php

/**
 * Class Audit_Entity | src/services/audit_entity.php
 * 
 * @author Innocow
 * @copyright 2020 Innocow
 */

namespace Innocow\Security_Audit\Services;

abstract class Audit_Entity {
    
    /**
     * The installation home path.
     * 
     * @var string
     **/
    protected $install_home_path = null;

    /**
     * The type of entity to audit.
     * 
     * @var string
     */
    protected $type = null;

    /**
     * Validity list for $type.
     * 
     * @var array
     */
    protected $type_array = [ "f", "d" ];

    //
    //
    // Accessors
    //
    //

    /**
    * Gets the value of install_home_path.
    *
    * @return mixed
    */
    public function get_install_home_path() {

        return $this->install_home_path;
    
    }

    /**
    * Sets the value of install_home_path.
    *
    * @param mixed $install_home_path the install home path
    *
    * @return self
    */
    public function set_install_home_path( string $install_home_path ) {

        if ( ! is_dir( $install_home_path ) ) {

            throw new \InvalidArgumentException( "Invalid installation path; $install_home_path." );

        }

        $this->install_home_path = $install_home_path;

    }

    /**
    * Gets the value of type.
    *
    * @return string
    */
    public function get_type() {

        return $this->type;
    
    }
 
    /**
    * Sets the value of type.
    *
    * @param string $type The type of entity to audit for.
    *
    * @return string
    */
    public function set_type( $type ) {

        if ( ! in_array( $type, $this->get_type_array() ) ) {

            throw new \InvalidArgumentException( "Invalid type: $type." );

        }

        $this->type = $type;

    }


    /**
    * Gets the list of valid types as an array.
    *
    * @return array Array of valid entity types.
    */
    protected function get_type_array() {

        return $this->type_array;
    
    }
 
    /**
    * Sets the list of valid types as an array.
    *
    * @param array $type_array Array of valid entity types.
    */
    protected function set_type_array( array $type_array ) {

        $this->type_array = $type_array;

    }

    //
    //
    // Methods
    //
    //

    abstract function audit();

    /**
     * Convert a permission string from an integer to octal (Unix values.)
     */
    protected function integer_to_octal_basic( int $integer ) {

        return substr( sprintf( "%o", $integer ), -3 );

    }

}
