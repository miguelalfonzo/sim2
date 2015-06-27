<?php

namespace Users;

use \Eloquent;

class Person extends Eloquent
{

    protected $table = 'OUTDVP_PERSONAS';
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