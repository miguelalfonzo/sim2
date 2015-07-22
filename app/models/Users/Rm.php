<?php

namespace Users;

use \Eloquent;
use \Auth;

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

    protected static function order()
    {
        if ( Auth::user()->type == SUP )
            return Rm::where( 'idsup' , Auth::user()->sup->idsup )->orderBy( 'nombres' , 'asc' )->get();
        else
            return Rm::orderBy( 'nombres' , 'asc' )->get();
    }

    protected function getSup( $idUser )
    {
        return Rm::where( 'iduser' , $idUser )->first()->rmSup;
    }
 
    protected function getFullNameAttribute()
    {
        return substr( $this->attributes[ 'nombres' ] , 0 , 1 ).'. '.$this->attributes[ 'apellidos' ];
    }

    public function rmSup()
    {
        return $this->belongsTo( 'Users\Sup' , 'idsup' , 'idsup' );
    }

    public function user()
    {
        return $this->hasOne( 'User' , 'id' , 'iduser' );
    }

    protected function bagoVisitador()
    {
        return $this->hasOne( 'Users\Visitador' , 'visvisitador' , 'idrm' );
    }

}