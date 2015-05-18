<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 06/10/14
 * Time: 04:16 PM
 */

namespace Common;
use \Eloquent;
class Person extends Eloquent{

    protected $table = 'OUTDVP.PERSONAS';
    protected $primaryKey = 'idpersona';

    protected function getFullNameAttribute()
    {
        return substr( $this->attributes[ 'nombres' ] , 0 , 1 ) . '. ' . $this->attributes[ 'apellidos' ];
    }

    function lastId()
    {
        $lastId = Person::orderBy( 'idpersona' , 'DESC' )->first();
        if ( is_null( $lastId ) )
            return 0;
        else
            return $lastId->idpersona;
    }
}