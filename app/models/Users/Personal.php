<?php

namespace Users;

use \Eloquent;
use \Auth;

class Personal extends Eloquent
{

    protected $table = TB_PERSONAL;
    protected $primaryKey = 'id';

    // protected function getFullNameAttribute()
    // {
    //     return substr( $this->attributes['nombres'] , 0 , 1 ).'. '.$this->attributes['apellidos'];
    // }

    protected function lastId()
    {
        $lastId = Personal::orderBy('id', 'DESC')->first();
        if( is_null( $lastId ) )
            return 0;
        else
            return $lastId->id;
    }
    public function getFullName()
    {
        $name = $this->nombres .' '. $this->apellidos;
        $name =ucwords(strtolower($name));
        return $name;
    }

    protected function getSeatNameAttribute()
    {
        return strtoupper( substr( $this->nombres , 0 , 2 ) . ' ' . $this->apellidos );
    }

    public function getFullNameAttribute()
    {
        return ucwords( strtolower( $this->nombres . ' ' . $this->apellidos ) );
    }

    // idkc : RETORNA MODELO DE SUPERVISOR
    protected function getSup( $user_id )
    {
        $persona = Personal::where( 'user_id' , $user_id )->first();
        return $persona->rmSup;
    }

    // mamv : RETORNA MODELO DE REPRESENTANTE MEDICO por ID BAGO
    protected function getRM( $bago_id )
    {
        return Personal::where( 'bago_id' , $bago_id )->whereHas( 'user' , function( $query )
        {
            $query->where( 'type' , REP_MED );
        })->first();
    }

    protected function getRms()
    {
        $rms = Personal::wherehas( 'user' , function( $query )
        {
            $query->where( 'type' , REP_MED );
        })->orderBy( 'nombres' , 'ASC' );
        
        if ( Auth::user()->type == SUP )
        {
            $rms->where( 'referencia_id' , Auth::user()->sup->bago_id );
        }
        elseif ( Auth::user()->type == REP_MED )
        {
            $rms->where( 'user_id' , Auth::user()->id );
        }
        
        return $rms->get();
    }

    public function employees()
    {
        return $this->hasMany( 'Users\Personal' , 'referencia_id' , 'bago_id' );
    }

    // mamv : RETORNA MODELO DE REPRESENTANTE SUPERVISOR por ID BAGO
    protected function getSupvervisor( $bago_id )
    {
        $persona = Personal::where( 'bago_id' , $bago_id)->where( 'tipo' , SUP )->first();
        return $persona;
    }

    // idkc : SOLO RM
    public function rmSup()
    {
        return $this->belongsTo( '\Users\Personal' , 'referencia_id' , 'bago_id' )->whereHas( 'user' , function( $query )
        {
            $query->where( 'type' , SUP );
        });
    }

    // idkc : SOLO SUPERVISOR
    public function reps()
    {
        return $this->hasMany( 'Users\Personal' , 'referencia_id' , 'bago_id' )->whereHas( 'user' , function( $query )
        {
            $query->where( 'type' , REP_MED );
        });
    }


    public function getType()
    {
        return $this->hasOne('Users\PersonalType', 'id', 'tipo_personal_id');

    }
    // idkc : SOLO GERENTE DE PRODUCTO
    public function solicituds()
    {
        return $this->hasMany('Dmkt\SolicitudGer' , 'id_gerprod' , 'bago_id');
    }

    protected static function getGerProd( $bagoIds )
    {
        return Personal::whereIn( 'bago_id' , $bagoIds )->where( 'tipo' , GER_PROD )->get();
    }

    protected static function getGerProdNotRegisteredName( $uniqueIdsGerProd )
    {
        return Personal::whereIn( 'bago_id' , $uniqueIdsGerProd )->whereNull( 'user_id' )->get()->lists( 'full_name' );
    }

    public function bagoVisitador()
    {
        return $this->hasOne( 'Users\Visitador' , 'visvisitador' , 'bago_id' );
    }

    public function user()
    {
        return $this->belongsTo( 'User' , 'user_id' );
    }
}