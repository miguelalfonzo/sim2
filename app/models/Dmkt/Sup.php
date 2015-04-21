<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 08/09/14
 * Time: 11:42 AM
 */
namespace Dmkt;
use \Eloquent;
class Sup extends Eloquent{


    protected $table = 'DMKT_RG_SUPERVISOR';
    protected $primaryKey = 'idsup';

    protected function getFullNameAttribute()
    {
        return substr( $this->attributes['nombres'] , 0 , 1 ).'. '.$this->attributes['apellidos'];
    }

    function searchId()
    {
        $lastId = Sup::orderBy('idsup', 'DESC')->first();
        if($lastId == null)
            return 0;
        else
            return $lastId->idsup;

    }

    public function Reps(){
        return $this->hasMany('Dmkt\Rm','idsup','idsup');
    }
}