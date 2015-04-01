<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 08/09/14
 * Time: 11:41 AM
 */
namespace Dmkt;
use \Eloquent;

class Rm extends Eloquent{

    protected $table = 'DMKT_RG_RM';
    protected $primaryKey = 'idrm';

    function searchId()
    {
        $lastId = Rm::orderBy('idrm', 'DESC')->first();
        if($lastId == null)
            return 0;
        else
            return $lastId->idrm;
    }

    function rmSup()
    {
        return $this->belongsTo('Dmkt\Sup','idsup','idsup');
    }

    function user(){
        return $this->hasOne('User','id','iduser');
    }

}