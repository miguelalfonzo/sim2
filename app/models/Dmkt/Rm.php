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
    protected $primaryKey = 'IDRM';

    function searchId(){

        $lastId = RM::orderBy('idrm', 'DESC')->first();
        if($lastId == null){
            return 0;
        }else{
            return $lastId->idrm;
        }

    }

    public function user(){
        return $this->hasOne('User','id','iduser');
    }


}