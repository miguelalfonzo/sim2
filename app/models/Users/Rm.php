<?php

namespace Users;

use \Eloquent;

class Rm extends Eloquent
{

    protected $table = 'OUTDVP.DMKT_RG_RM';
    protected $primaryKey = 'idrm';

    function lastId()
    {
        $lastId = Rm::orderBy( 'idrm' , 'DESC' )->first();
        if( is_null( $lastId ) )
            return 0;
        else
            return $lastId->idrm;
    }

    protected function getSup( $idUser )
    {
        return Rm::where( 'iduser' , $idUser )->first()->rmSup;
    }
 
    protected function getFullNameAttribute()
    {
        return substr( $this->attributes[ 'nombres' ] , 0 , 1 ).'. '.$this->attributes[ 'apellidos' ];
    }

    function rmSup()
    {
        return $this->belongsTo( 'Users\Sup' , 'idsup' , 'idsup' );
    }

    function user(){
        return $this->hasOne( 'User' , 'id' , 'iduser' );
    }

}