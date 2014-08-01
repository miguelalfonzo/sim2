<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 31/07/14
 * Time: 04:35 PM
 */

class SolicitudController extends BaseController{

    public function show(){

        return View::make('RM.show');

    }

    public function newRequest(){

        return View::make('RM.registrar_solicitud');
    }


}