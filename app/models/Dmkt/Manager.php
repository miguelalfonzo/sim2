<?php


namespace Dmkt;
use \Eloquent;
class Manager extends Eloquent{

    protected $table = 'GERENTES';
    protected $primaryKey = 'ID';

    public function solicituds(){

        return $this->hasMany('Dmkt\SolicitudeGer','idgerprod','id');
    }


}