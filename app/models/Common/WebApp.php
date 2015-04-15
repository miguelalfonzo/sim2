<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 04/11/2014
 * Time: 04:03 PM
 * Modelo para relacionar los usuarios con sus aplicaciones
 */
namespace Common;
use \Eloquent;

class WebApp extends Eloquent {

    protected $table = 'APPS';
    protected $primaryKey = 'idapp';



}