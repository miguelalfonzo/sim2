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
    protected $primaryKey = 'IDSUP';

    public function Reps(){

        return $this->hasMany('Dmkt\Rm','idsup','idsup');
    }
}