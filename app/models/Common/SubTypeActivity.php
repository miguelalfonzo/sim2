<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 15/08/14
 * Time: 09:37 AM
 */
namespace Common;
use \Eloquent;
use \Common\TypeActivity;

class SubTypeActivity extends Eloquent{


    protected $table = 'DMKT_RG_SUB_TIPO_ACTIVIDAD';
    protected $primaryKey = 'IDSUBTIPOACTIVIDAD';

    public function type(){
        return $this->belongsTo('Common\TypeActivity','idtipoactividad','idtipoactividad');
    }
}