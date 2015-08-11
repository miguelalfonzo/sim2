<?php

namespace Users;

use \Eloquent;

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
    public function getFullName(){
        $name = $this->nombres .' '. $this->apellidos;
        $name =ucwords(strtolower($name));
        return $name;
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

    // idkc : SOLO RM
    public function rmSup()
    {
        return $this->belongsTo( '\Users\Personal' , 'referencia_id' , 'bago_id' )->where('tipo', '=', 'S');
    }

    // idkc : SOLO SUPERVISOR
    public function reps()
    {
        return $this->hasMany('Users\Personal','referencia_id', 'bago_id')->where('tipo', '=', 'RM');
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
}