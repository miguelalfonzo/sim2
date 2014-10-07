<?php


namespace Dmkt;
use \Eloquent;
class Manager extends Eloquent{

    protected $table = 'GERENTES';
    protected $primaryKey = 'ID';

    public function solicituds(){

        return $this->hasMany('Dmkt\SolicitudeGer','idgerprod','id');
    }
    function searchId(){

        $lastId = Manager::orderBy('id', 'DESC')->first();
        if($lastId == null){
            return 0;
        }else{
            return $lastId->id;
        }

    }

}