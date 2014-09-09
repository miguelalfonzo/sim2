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

    public function user(){
        return $this->hasOne('User','id','iduser');
    }


}