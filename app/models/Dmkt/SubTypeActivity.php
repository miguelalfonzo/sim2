<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 15/08/14
 * Time: 09:37 AM
 */
namespace Dmkt;
use \Eloquent;
use \Dmkt\TypeActivity;

class SubTypeActivity extends Eloquent{


    protected $table = 'DMKT_RG_SUB_TIPO_ACTIVIDAD';
    protected $primaryKey = 'IDSUBTIPOACTIVIDAD';

    public function type(){
        return $this->belongsTo('Dmkt\TypeActivity','idtipoactividad','idtipoactividad');
    }
}