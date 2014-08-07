<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 31/07/14
 * Time: 04:35 PM
 */

class SolicitudeController extends BaseController{

    public function show(){

        $results = DB::select('select * from datos');
        var_dump($results);
        die;
        return View::make('RM.show');

    }

    public function newSolicitude(){

        return View::make('RM.registrar_solicitud');
    }



}