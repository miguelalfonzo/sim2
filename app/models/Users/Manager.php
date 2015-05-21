<?php

namespace Users;

use \Eloquent;

class Manager extends Eloquent
{

    protected $table = 'OUTDVP.GERENTES';
    protected $primaryKey = 'id';

    protected function getFullNameAttribute()
    {
        $name = explode( ' ' , trim( $this->attributes['descripcion'] ) );
        if ( count( $name ) == 2 )
            return ucwords( strtolower( substr( $name[ 0 ] , 0 , 1 ) . '. ' . $name[1] ) );
        else
            return ucwords( strtolower( $name[ 0 ] ) );
    }

    public function lastId()
    {
        $lastId = Manager::orderBy('id', 'DESC')->first();
        if( is_null( $lastId ) )
            return 0;
        else
            return $lastId->id;
    }

    public function solicituds()
    {
        return $this->hasMany( 'Dmkt\SolicitudGer' , 'id_gerprod' , 'id' );
    }
}