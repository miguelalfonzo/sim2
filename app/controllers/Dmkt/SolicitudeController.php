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



        return View::make('Dmkt.show');

    }

    public function getclients(){

        $clients = Client::take(30)->get(array('clcodigo','clnombre'));

        return json_encode($clients);
    }

    public function newSolicitude(){

        $productos = DB::table('vta.equiv_unif')
            ->where('tipo', '=', 'B')
            ->lists('codprod_bag');


        $products = DB::table('vta.foprte')
            ->select('foalias', 'fodescripcion')
            ->where('fotipo', '=', 1)
            ->where('foestado', '=', 1)
            ->whereNotIn('foalias', $productos)
            ->orderBy('fodescripcion', 'asc')
            ->get();


        return View::make('Dmkt.registrar_solicitud')->with('products',$products);

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

        return Redirect::to('show');
    }

    public function listSolicitude($idstate){

        $solicitudes = Solicitude::where('estado_idestado','=',$idstate)->get();

        $view = View::make('Dmkt.view_solicituds')->with('solicituds',$solicitudes);

        return $view;
    }
    public function viewSolicitude(){

        return View::make('Dmkt.view_solicitude');
    }




}