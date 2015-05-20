<?php

namespace Dmkt;
use \Eloquent;

class SolicitudProduct extends Eloquent
{
    protected $table = 'SOLICITUD_PRODUCTO';
    protected $primaryKey = 'id';

    public function lastId()
    {
        $lastId = SolicitudProduct::orderBy('id', 'DESC')->first();
        if( is_null( $lastId ) )
            return 0;
        else
            return $lastId->id;
    }

    public function marca()
    {
        return $this->hasOne( 'Dmkt\Marca' , 'id' , 'idfamilia' );
    }
}