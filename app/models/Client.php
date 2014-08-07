<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 07/08/14
 * Time: 02:18 PM
 */

class Client extends Eloquent{

    protected $fillable = array('clcodigo', 'clnombre');
    protected $table = 'VTA.CLIENTES';
    protected $primaryKey = 'CL_CODIGO';


}

