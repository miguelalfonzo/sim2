<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 31/07/14
 * Time: 04:35 PM
 */


namespace Dmkt;
use \Common\State;
use \Common\SubTypeActivity;
use \Common\TypeActivity;
use \BaseController;
use Symfony\Component\Finder\Comparator\DateComparator;
use \View;
use \DB;
use \Input;
use \Redirect;
use \Illuminate\Database\Query\Builder;

class SolicitudeController extends BaseController{


    function objectToArray($object)
    {
        $array=array();
        foreach($object as $member=>$data)
        {
            $array[$member]=$data;
        }
        return $array;
    }

    public function test(){

        $var = SubTypeActivity::find(30);
        echo json_encode($var->type);
    }

    public function show_rm(){

       $states = State::all();

        return View::make('Dmkt.Rm.show_rm')->with('states',$states);

    }

    public function getclients(){

        $clients = Client::take(30)->get(array('clcodigo','clnombre'));

        return json_encode($clients);
    }

    public function newSolicitude(){

        $families = Marca::all();

        $subtypeactivities = SubTypeActivity::all();
        $typesolicituds = TypeSolicitude::all();
        $data = [

            'families' => $families,
            'subtypeactivities' => $subtypeactivities,
            'typesolicituds'=> $typesolicituds
        ];

        return View::make('Dmkt.Rm.register_solicitude',$data);

    }

    public function registerSolicitude(){


        $inputs = Input::all();
        $solicitude = new Solicitude;
        $date = $inputs['delivery_date'];
        list($d,$m,$y) = explode('/',$date);
        $d=mktime(11, 14, 54, $m, $d, $y);
        $date =  date("Y/m/d", $d);
        $solicitude->idsolicitud = $solicitude->searchId() + 1 ;
        $solicitude->descripcion = $inputs['description'] ;
        $solicitude->titulo = $inputs['titulo'];
        $solicitude->monto = $inputs['monto'];
        $solicitude->estado = 2;
        $solicitude->fecha_entrega = $date;

        $solicitude->idsubtipoactividad = $inputs['sub_type_activity'];
        $solicitude->tipo_moneda = $inputs['money'];
        $solicitude->save();

        $clients = $inputs['clients'];
        foreach($clients as $client){
            $cod = explode(' ',$client);
            $solicitude_clients = new SolicitudeClient;

            $solicitude_clients->idsolicitud_clientes = $solicitude_clients->searchId()+1;
            $solicitude_clients->idsolicitud = $solicitude->searchId();
            $solicitude_clients->idcliente = $cod[0];
            $solicitude_clients->save();
        }
        $families = $inputs['families'];
        foreach($families as $family){

            $solicitude_families = new SolicitudeFamily;
            $solicitude_families->idsolicitud_familia = $solicitude_families->searchId()+1;
            $solicitude_families->idsolicitud = $solicitude->searchId();
            $solicitude_families->idfamilia = $family;
            $solicitude_families->save();
        }

        return Redirect::to('show_rm');
    }

    public function listSolicitude($id){

        if($id == 0){
            $solicituds = Solicitude::all();
        }else{
            $solicituds = Solicitude::where('estado','=',$id)->get();
        }


        $view = View::make('Dmkt.Rm.view_solicituds_rm')->with('solicituds',$solicituds);

        return $view;
    }
    public function viewSolicitude($id){

        $solicitude = Solicitude::find($id);
        $clients = DB::table('DMKT_RG_SOLICITUD_CLIENTES')->where('idsolicitud', $id)->lists('idcliente');
        $clients = Client::whereIn('clcodigo',$clients)->get(array('clcodigo','clnombre'));
        $families = DB::table('DMKT_RG_SOLICITUD_FAMILIA')->where('idsolicitud', $id)->lists('idfamilia');
        $families= Marca::whereIn('id',$families)->get(array('id','descripcion'));

        $data = [

            'solicitude' => $solicitude,
            'clients' => $clients,
            'families' => $families
        ];
        //echo json_encode($data);

        return View::make('Dmkt.Rm.view_solicitude',$data);
    }
    public function editSolicitude($id){

        $families = Marca::all();
        $solicitude = Solicitude::find($id);
        $clients = DB::table('DMKT_RG_SOLICITUD_CLIENTES')->where('idsolicitud', $id)->lists('idcliente');
        $clients = Client::whereIn('clcodigo',$clients)->get(array('clcodigo','clnombre'));
        $families2 = DB::table('DMKT_RG_SOLICITUD_FAMILIA')->where('idsolicitud', $id)->lists('idfamilia');
        $families2= Marca::whereIn('id',$families2)->get(array('id','descripcion'));

        $typesolicituds = TypeSolicitude::all();

        $subtypeactivities= SubTypeActivity::all();
        //echo json_encode($solicitude->subtype);
        //echo json_encode($subtypeactivities);


        $data = [

            'solicitude' => $solicitude,
            'clients' => $clients,
            'families' => $families,
            'families2' => $families2,
            'typesolicituds' => $typesolicituds,
            'subtypeactivities' => $subtypeactivities
        ];
        //echo json_encode($data);

        return View::make('Dmkt.Rm.register_solicitude',$data);
    }
    public function formEditSolicitude(){

        $inputs = Input::all();
        $date = $inputs['delivery_date'];
        list($d,$m,$y) = explode('/',$date);
        $d=mktime(11, 14, 54, $m, $d, $y);
        $date =  date("Y/m/d", $d);
        $id = $inputs['idsolicitude'];
        $solicitude = Solicitude::where('idsolicitud',$id);
        //$solicitude->idsolicitud = (int) $id ;
        $solicitude->descripcion = $inputs['description'] ;
        $solicitude->titulo = $inputs['titulo'];
        $solicitude->monto= $inputs['monto'];
        $solicitude->estado = 2;
        $solicitude->fecha_entrega = $date;

        $solicitude->idsubtipoactividad = $inputs['sub_type_activity'];
        $solicitude->tipo_moneda = $inputs['money'];
        $data = $this->objectToArray($solicitude);
        $solicitude->update($data);

        SolicitudeClient::where('idsolicitud','=' , $id)->delete();
        SolicitudeFamily::where('idsolicitud','=' , $id)->delete();

        $clients = $inputs['clients'];
        //var_dump($clients);die;
        foreach($clients as $client){
            $cod = explode(' ',$client);
            $solicitude_clients = new SolicitudeClient;
            $solicitude_clients->idsolicitud_clientes = $solicitude_clients->searchId() + 1;
            $solicitude_clients->idsolicitud = $id;
            $solicitude_clients->idcliente = $cod[0];
            $solicitude_clients->save();

        }
        $families = $inputs['families'];
        foreach($families as $family){

            $solicitude_families = new SolicitudeFamily;
            $solicitude_families->idsolicitud_familia = $solicitude_families->searchId() + 1;
            $solicitude_families->idsolicitud = $id;
            $solicitude_families->idfamilia = $family;
            $solicitude_families->save();
        }

        return Redirect::to('show_rm');

    }
    public function subtypeactivity($id){

        $subtypeactivities = SubTypeActivity::where('idtipoactividad',$id)->get();
        return json_encode($subtypeactivities);

    }

    /** Supervisor */

    public function show_sup(){

        $states = State::all();
        return View::make('Dmkt.Sup.show_sup')->with('states',$states);

    }
    public function viewSolicitudeSup($id){

        $solicitude = Solicitude::find($id);
        $clients = DB::table('DMKT_RG_SOLICITUD_CLIENTES')->where('idsolicitud', $id)->lists('idcliente');
        $clients = Client::whereIn('clcodigo',$clients)->get(array('clcodigo','clnombre'));
        $families = DB::table('DMKT_RG_SOLICITUD_FAMILIA')->where('idsolicitud', $id)->lists('idfamilia');
        $families= Marca::whereIn('id',$families)->get(array('id','descripcion'));

        $data = [

            'solicitude' => $solicitude,
            'clients' => $clients,
            'families' => $families
        ];
        //echo json_encode($data);

        return View::make('Dmkt.Sup.view_solicitude_sup',$data);

    }
    public function denySolicitude(){

        $inputs = Input::all();
        $id = $inputs['idsolicitude'];
        $solicitude = Solicitude::where('idsolicitud',$id);
        $solicitude->idsolicitud = (int) $id ;
        $solicitude->observacion = $inputs['observacion'];
        $solicitude->estado = 3;
        $data = $this->objectToArray($solicitude);
        $solicitude->update($data);
        return Redirect::to('show_sup');

    }
    public function aceptedSolicitude(){

        $inputs = Input::all();
        $idSol =  $inputs['idsolicitude'];
        $solicitude = Solicitude::where('idsolicitud',$idSol) ;
        $solicitude->estado = 8;
        $solicitude->monto = $inputs['monto'];
        $data = $this->objectToArray($solicitude);
        $solicitude->update($data);
        return Redirect::to('show_sup');

    }
    public function listSolicitudeSup($id){

        if($id == 0){
            $solicituds = Solicitude::all();
        }else{
            $solicituds = Solicitude::where('estado','=',$id)->get();
        }

        $view = View::make('Dmkt.Sup.view_solicituds_sup')->with('solicituds',$solicituds);
        return $view;
    }

}