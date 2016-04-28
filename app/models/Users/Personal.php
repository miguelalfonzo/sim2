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

    protected function getResponsible()
    {
        $user = Auth::user();
        $personals = Personal::orderBy( 'nombres' , 'ASC' , 'apellidos' , 'ASC' );
        if( $user->type === REP_MED )
        {
            $personals->where( 'user_id' , $user->id );
        }
        elseif( $user->type === SUP )
        {
            $personals->where( 'user_id' , $user->id )->orWhere( 'referencia_id' , $user->sup->bago_id );
        }
        elseif( in_array( $user->type , [ GER_PROD , GER_PROM , GER_COM , GER_GER ] ) )
        {
            $personals->whereHas( 'user' , function( $query )
            {
                $query->whereIn( 'type' , [ REP_MED , SUP ] );
            });
        }
        return $personals->get();
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
    protected function rmSup()
    {
        return $this->belongsTo( 'Users\Personal' , 'referencia_id' , 'bago_id' )->whereHas( 'user' , function( $query )
        {
            $query->where( 'type' , SUP );
        });
    }

    public function getAccount()
    {
        if( $this->tipo === 'RM' || $this->tipo === 'RI' )
        {
            $visitador = $this->bagoVisitador;
            $account = $visitador->cuenta;
            if( ! is_null( $account ) )
            {
                return $account->cuenta;
            }
        }
        elseif( $this->tipo === SUP )
        {
            $supervisor = $this->bagoSupervisor;
            $account = $supervisor->cuenta;
            if( ! is_null( $account ) )
            {
                return $account->cuenta;
            }
        }
        return null;   
    }

    public function userSup()
    {
        if( $this->tipo === 'RM' || $this->tipo === 'RI' )
        {
            return $this->rmSup->user_id;
        }
        elseif( $this->tipo === SUP )
        {
            return $this->user_id;
        }
        else
        {
            return null;
        }
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

    public function bagoSupervisor()
    {
        return $this->hasOne( 'Users\Supervisor' , 'supsupervisor' , 'bago_id' );
    }

    public function user()
    {
        return $this->belongsTo( 'User' , 'user_id' );
    }
}