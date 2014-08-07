<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 31/07/14
 * Time: 04:35 PM
 */

class SolicitudeController extends BaseController{

    public function show(){

        return View::make('RM.show');

    }

    public function newSolicitude(){

        return View::make('RM.registrar_solicitud');
    }


}