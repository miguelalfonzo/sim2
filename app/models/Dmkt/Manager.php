<?php


namespace Dmkt;
use \Eloquent;
class Manager extends Eloquent{

    protected $table = 'OUTDVP.GERENTES';
    protected $primaryKey = 'id';

    protected function getFullNameAttribute()
    {
        $name = explode( ' ' , trim( $this->attributes['descripcion'] ) );
        if ( count($name) == 2 )
            return substr( $name[0] , 0 , 1 ).'. '.$name[1];
        else
            return $name[0];
    }

    public function solicituds()
    {
        return $this->hasMany('Dmkt\SolicitudGer','idgerprod','id');
    }
    
    function searchId()
    {
        $lastId = Manager::orderBy('id', 'DESC')->first();
        if($lastId == null)
            return 0;
        else
            return $lastId->id;
    }

}