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
use \DB;
use \Input;
use \Redirect;

class SolicitudeController extends BaseController{


    public function test(){


        echo 'algo';
        // return View::make('Dmkt.test');
    }

    public function show(){

       $states = State::all();

        return View::make('Dmkt.show')->with('states',$states);

    }

    public function getclients(){

        $clients = Client::take(30)->get(array('clcodigo','clnombre'));

        return json_encode($clients);
    }

    public function newSolicitude(){

        $families = Marca::all();
        return View::make('Dmkt.registrar_solicitud')->with('families',$families);

    }

    public function registerSolicitude(){


        $inputs = Input::all();
        $solicitude = new Solicitude;

        $solicitude->idsolicitud = $solicitude->searchId() + 1 ;
        $solicitude->descripcion = $inputs['description'] ;
        $solicitude->titulo = $inputs['titulo'];
        $solicitude->presupuesto = $inputs['estimate'];
        $solicitude->estado_idestado = 1;
        $solicitude->tipo_solicitud = $inputs['type_solicitude'];
        $solicitude->tipo_actividad = $inputs['type_activity'];
        $solicitude->save();

        $clients = $inputs['clients'];
        foreach($clients as $client){
            $cod = explode(' ',$client);
            $solicitude_clients = new SolicitudeClient;
            $solicitude_clients->idsolicitud_clientes = 1;
            $solicitude_clients->idsolicitud = $solicitude->searchId();
            $solicitude_clients->idcliente = $cod[0];
            $solicitude_clients->save();
        }
        $families = $inputs['families'];
        foreach($families as $family){

            $solicitude_families = new SolicitudeFamily;
            $solicitude_families->idsolicitud_familia = 1;
            $solicitude_families->idsolicitud = $solicitude->searchId();
            $solicitude_families->idfamilia = $family;
            $solicitude_families->save();
        }

        return Redirect::to('show');
    }

    public function listSolicitude($idstate){

        if($idstate == 0){

            $solicituds = Solicitude::all();
        }else{
            $solicituds = Solicitude::where('estado_idestado','=',$idstate)->get();
        }


        $view = View::make('Dmkt.view_solicituds')->with('solicituds',$solicituds);

        return $view;
    }
    public function viewSolicitude(){

        return View::make('Dmkt.view_solicitude');
    }




}