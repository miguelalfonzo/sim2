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

    protected function getSup( $user_id )
    {
        $persona = Personal::where( 'user_id' , $user_id )->first();
        return $persona->rmSup;
    }

    public function rmSup()
    {
        return $this->belongsTo( '\Users\Personal' , 'referencia_id' , 'bago_id' );
    }

    // public function reps()
    // {
    //     return $this->hasMany('Users\Rm','idsup','idsup');
    // }
}