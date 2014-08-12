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

        $solicitude = new Solicitude;
        $solicitude->idsolicitud = 1;
        $solicitude->descripcion = 'jdl sadjlsldjs dsada';
        $solicitude->titulo = 'solicitud1';
        $solicitude->presupuesto = 50;
        $solicitude->estado_idestado = 1;
        $solicitude->save();

    }


    public function viewSolicitude(){

        return View::make('Dmkt.view_solicitude');
    }




}