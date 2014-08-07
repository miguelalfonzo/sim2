<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 31/07/14
 * Time: 04:35 PM
 */

namespace Dmkt;
use \BaseController;
use \View;
class SolicitudeController extends BaseController{

    public function show(){

        $clients = Client::take(30)->get(array('clcodigo','clnombre'));;
        echo json_encode($clients);

        die;
        return View::make('Dmkt.show');

    }

    public function newSolicitude(){

        return View::make('Dmkt.registrar_solicitud');

    }
    public function test(){

        return View::make('Dmkt.test');
    }



}