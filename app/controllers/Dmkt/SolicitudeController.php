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
use \Mail;
use \Image;
use \Illuminate\Database\Query\Builder;

class SolicitudeController extends BaseController
{


    function objectToArray($object)
    {
        $array = array();
        foreach ($object as $member => $data) {
            $array[$member] = $data;
        }
        return $array;
    }

    function decrypt($string, $key)
    {
        $result = '';
        $string = base64_decode($string);
        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) - ord($keychar));
            $result .= $char;
        }
        return $result;
    }

    public function test()
    {

        $token = md5(sha1(uniqid(2, true)));
        var_dump($token);
    }

    public function show_rm()
    {

        $states = State::all();

        return View::make('Dmkt.Rm.show_rm')->with('states', $states);

    }

    public function getclients()
    {

        $clients = Client::take(30)->get(array('clcodigo', 'clnombre'));

        return json_encode($clients);
    }

    public function newSolicitude()
    {

        $families = Marca::all();

        $subtypeactivities = SubTypeActivity::all();
        $typesolicituds = TypeSolicitude::all();
        $data = [

            'families' => $families,
            'subtypeactivities' => $subtypeactivities,
            'typesolicituds' => $typesolicituds
        ];

        return View::make('Dmkt.Rm.register_solicitude', $data);

    }

    public function registerSolicitude()
    {


        $inputs = Input::all();
        $image = Input::file('file');
        if (isset($image)) {

            $filename = uniqid() . '.' . $image->getClientOriginalExtension();
            //$filename = $image->getClientOriginalName();
            $path = public_path('img/reembolso/' . $filename);
            Image::make($image->getRealPath())->resize(800, 600)->save($path);

        }


        $solicitude = new Solicitude;
        $date = $inputs['delivery_date'];
        list($d, $m, $y) = explode('/', $date);
        $d = mktime(11, 14, 54, $m, $d, $y);
        $date = date("Y/m/d", $d);
        $solicitude->idsolicitud = $solicitude->searchId() + 1;
        $solicitude->descripcion = $inputs['description'];
        $solicitude->titulo = $inputs['titulo'];
        $solicitude->monto = $inputs['monto'];
        $solicitude->estado = 2;
        $solicitude->fecha_entrega = $date;
        $solicitude->idtiposolicitud = $inputs['type_solicitude'];
        $solicitude->token = sha1(md5(uniqid($solicitude->idsolicitud, true)));
        $solicitude->idsubtipoactividad = $inputs['sub_type_activity'];
        $solicitude->tipo_moneda = $inputs['money'];
        if ($solicitude->save()) {
            $data = array(

                'name' => $inputs['titulo'],
                'description' => $inputs['description'],
                'monto' => $inputs['monto'],
                'money' => $inputs['money']

            );


            Mail::send('emails.template', $data, function ($message) {
                $message->to('cesarhm1687@gmail.com');
                $message->subject('Nueva Solicitud');

            });
            $clients = $inputs['clients'];
            foreach ($clients as $client) {
                $cod = explode(' ', $client);
                $solicitude_clients = new SolicitudeClient;

                $solicitude_clients->idsolicitud_clientes = $solicitude_clients->searchId() + 1;
                $solicitude_clients->idsolicitud = $solicitude->searchId();
                $solicitude_clients->idcliente = $cod[0];
                $solicitude_clients->save();
            }
            $families = $inputs['families'];
            foreach ($families as $family) {

                $solicitude_families = new SolicitudeFamily;
                $solicitude_families->idsolicitud_familia = $solicitude_families->searchId() + 1;
                $solicitude_families->idsolicitud = $solicitude->searchId();
                $solicitude_families->idfamilia = $family;
                $solicitude_families->save();
            }
            echo true;
        }

    }

    public function listSolicitude($id)
    {

        if ($id == 0) {
            $solicituds = Solicitude::all();
        } else {
            $solicituds = Solicitude::where('estado', '=', $id)->get();
        }


        $view = View::make('Dmkt.Rm.view_solicituds_rm')->with('solicituds', $solicituds);

        return $view;
    }

    public function viewSolicitude($token)
    {


        $solicitude = Solicitude::where('token', $token)->firstOrFail();
        $managers = Manager::all();
        $data = [

            'solicitude' => $solicitude,
            'managers' => $managers
        ];

        return View::make('Dmkt.Rm.view_solicitude', $data);


    }

    public function editSolicitude($token)
    {

        $families = Marca::all();
        $solicitude = Solicitude::where('token', $token)->firstOrFail();
        $id = $solicitude->idsolicitud;
        $clients = DB::table('DMKT_RG_SOLICITUD_CLIENTES')->where('idsolicitud', $id)->lists('idcliente');
        $clients = Client::whereIn('clcodigo', $clients)->get(array('clcodigo', 'clnombre'));
        $families2 = DB::table('DMKT_RG_SOLICITUD_FAMILIA')->where('idsolicitud', $id)->lists('idfamilia');
        $families2 = Marca::whereIn('id', $families2)->get(array('id', 'descripcion'));

        $typesolicituds = TypeSolicitude::all();

        $subtypeactivities = SubTypeActivity::all();

        $data = [

            'solicitude' => $solicitude,
            'clients' => $clients,
            'families' => $families,
            'families2' => $families2,
            'typesolicituds' => $typesolicituds,
            'subtypeactivities' => $subtypeactivities
        ];
        //echo json_encode($data);

        return View::make('Dmkt.Rm.register_solicitude', $data);
    }

    public function formEditSolicitude()
    {

        $inputs = Input::all();
        $date = $inputs['delivery_date'];
        list($d, $m, $y) = explode('/', $date);
        $d = mktime(11, 14, 54, $m, $d, $y);
        $date = date("Y/m/d", $d);
        $id = $inputs['idsolicitude'];
        $solicitude = Solicitude::where('idsolicitud', $id);
        //$solicitude->idsolicitud = (int) $id ;
        $solicitude->descripcion = $inputs['description'];
        $solicitude->titulo = $inputs['titulo'];
        $solicitude->monto = $inputs['monto'];
        $solicitude->estado = 2;
        $solicitude->fecha_entrega = $date;

        $solicitude->idsubtipoactividad = $inputs['sub_type_activity'];
        $solicitude->tipo_moneda = $inputs['money'];
        $data = $this->objectToArray($solicitude);
        $solicitude->update($data);
        //$solicitude->save();
        SolicitudeClient::where('idsolicitud', '=', $id)->delete();
        SolicitudeFamily::where('idsolicitud', '=', $id)->delete();

        $clients = $inputs['clients'];
        //var_dump($clients);die;
        foreach ($clients as $client) {
            $cod = explode(' ', $client);
            $solicitude_clients = new SolicitudeClient;
            $solicitude_clients->idsolicitud_clientes = $solicitude_clients->searchId() + 1;
            $solicitude_clients->idsolicitud = $id;
            $solicitude_clients->idcliente = $cod[0];
            $solicitude_clients->save();

        }
        $families = $inputs['families'];
        foreach ($families as $family) {

            $solicitude_families = new SolicitudeFamily;
            $solicitude_families->idsolicitud_familia = $solicitude_families->searchId() + 1;
            $solicitude_families->idsolicitud = $id;
            $solicitude_families->idfamilia = $family;
            $solicitude_families->save();
        }

        return Redirect::to('show_rm');

    }

    public function cancelSolicitude()
    {

        $inputs = Input::all();
        $id = $inputs['idsolicitude'];
        $solicitude = Solicitude::where('idsolicitud', $id);
        $solicitude->estado = 1;
        $data = $this->objectToArray($solicitude);
        $solicitude->update($data);

        return $this->listSolicitude(2);

    }

    public function subtypeactivity($id)
    {

        $subtypeactivities = SubTypeActivity::where('idtipoactividad', $id)->get();
        return json_encode($subtypeactivities);

    }

    public function searchSolicituds()
    {

        $inputs = Input::all();
        $estado = $inputs['idstate'];
        $start = $inputs['date_start'];
        $end = $inputs['date_end'];
        $today = getdate();
        $m = $today['mday'].'-'.$today['mon'].'-'.$today['year'];
        $lastday = date('t-m-Y', strtotime($m));
        $firstday = date('01-m-Y', strtotime($m));

        if($start!=null && $end!=null){
            if($estado !=0){
                $solicituds = Solicitude::select('*')
                    ->where('estado', $estado)
                    ->whereRaw("created_at between to_date('$start' ,'DD-MM-YY') and to_date('$end' ,'DD-MM-YY')+1")
                    ->get();

            }else{
                $solicituds = Solicitude::select('*')
                    ->whereRaw("created_at between to_date('$start' ,'DD-MM-YY') and to_date('$end' ,'DD-MM-YY')+1")
                    ->get();
            }


        }else{
            if($estado!=0){
                $solicituds = Solicitude::select('*')
                    ->where('estado', $estado)
                    ->whereRaw("created_at between to_date('$firstday' ,'DD-MM-YY') and to_date('$lastday' ,'DD-MM-YY')+1")
                    ->get();
            }else{
                $solicituds = Solicitude::select('*')
                    ->whereRaw("created_at between to_date('$firstday' ,'DD-MM-YY') and to_date('$lastday' ,'DD-MM-YY')+1")
                    ->get();
            }
        }


        $view = View::make('Dmkt.Rm.view_solicituds_rm')->with('solicituds', $solicituds);
        return $view;
    }

    /** Supervisor */

    public function show_sup()
    {

        $states = State::all();
        return View::make('Dmkt.Sup.show_sup')->with('states', $states);

    }

    public function viewSolicitudeSup($token)
    {


        $solicitude = Solicitude::where('token', $token)->firstOrFail();

        //$clients = DB::table('DMKT_RG_SOLICITUD_CLIENTES')->where('idsolicitud', $id)->lists('idcliente');
        //$clients = Client::whereIn('clcodigo',$clients)->get(array('clcodigo','clnombre'));
        //$families = DB::table('DMKT_RG_SOLICITUD_FAMILIA')->where('idsolicitud', $id)->lists('idfamilia');
        //$families= Marca::whereIn('id',$families)->get(array('id','descripcion'));
        $managers = Manager::all();
        $data = [

            'solicitude' => $solicitude,
            'managers' => $managers
        ];
        return View::make('Dmkt.Sup.view_solicitude_sup', $data);
    }

    public function denySolicitude()
    {

        $inputs = Input::all();
        $id = $inputs['idsolicitude'];
        $solicitude = Solicitude::where('idsolicitud', $id);
        $solicitude->idsolicitud = (int)$id;
        $solicitude->observacion = $inputs['observacion'];
        $solicitude->estado = 3;
        $data = $this->objectToArray($solicitude);
        $solicitude->update($data);
        return Redirect::to('show_sup');

    }

    public function acceptedSolicitude()
    {

        $inputs = Input::all();
        $amount_assigned = $inputs['amount_assigned'];
        $idSol = $inputs['idsolicitude'];
        $solicitude = Solicitude::where('idsolicitud', $idSol);
        $solicitude->estado = 8;
        $solicitude->monto = $inputs['monto'];
        $data = $this->objectToArray($solicitude);
        $solicitude->update($data);

        $amount_assigned = $inputs['amount_assigned'];
        $families = SolicitudeFamily::where('idsolicitud', $idSol)->get();
        $i = 0;
        foreach ($families as $fam) {
            $family = SolicitudeFamily::where('idsolicitud_familia', $fam->idsolicitud_familia);
            $family->monto_asignado = $amount_assigned[$i];
            $data = $this->objectToArray($family);
            $family->update($data);
            $i++;
        }
        //echo json_encode($families);die;
        return Redirect::to('show_sup');

    }

    public function listSolicitudeSup($id)
    {

        if ($id == 0) {
            $solicituds = Solicitude::all();
        } else {
            $solicituds = Solicitude::where('estado', '=', $id)->get();
        }

        $view = View::make('Dmkt.Sup.view_solicituds_sup')->with('solicituds', $solicituds);
        return $view;
    }

    public function searchSolicitudsSup()
    {

        $inputs = Input::all();
        $estado = $inputs['idstate'];
        $start = $inputs['date_start'];
        $end = $inputs['date_end'];
        $today = getdate();
        $m = $today['mday'].'-'.$today['mon'].'-'.$today['year'];
        $lastday = date('t-m-Y', strtotime($m));
        $firstday = date('01-m-Y', strtotime($m));

        if($start!=null && $end!=null){
            if($estado !=0){
                $solicituds = Solicitude::select('*')
                    ->where('estado', $estado)
                    ->whereRaw("created_at between to_date('$start' ,'DD-MM-YY') and to_date('$end' ,'DD-MM-YY')+1")
                    ->get();

            }else{
                $solicituds = Solicitude::select('*')
                    ->whereRaw("created_at between to_date('$start' ,'DD-MM-YY') and to_date('$end' ,'DD-MM-YY')+1")
                    ->get();
            }


        }else{
            if($estado!=0){
                $solicituds = Solicitude::select('*')
                    ->where('estado', $estado)
                    ->whereRaw("created_at between to_date('$firstday' ,'DD-MM-YY') and to_date('$lastday' ,'DD-MM-YY')+1")
                    ->get();
            }else{
                $solicituds = Solicitude::select('*')
                    ->whereRaw("created_at between to_date('$firstday' ,'DD-MM-YY') and to_date('$lastday' ,'DD-MM-YY')+1")
                    ->get();
            }
        }

        $view = View::make('Dmkt.Sup.view_solicituds_sup')->with('solicituds', $solicituds);
        return $view;
    }

    /** Gerente Comercial */

    public function show_gercom()
    {

        $states = State::all();
        return View::make('Dmkt.GerCom.show_gercom')->with('states', $states);
    }

    public function listSolicitudeGerCom($id)
    {

        if ($id == 0) {
            $solicituds = Solicitude::all();
        } else {
            $solicituds = Solicitude::where('estado', '=', $id)->get();
        }

        $view = View::make('Dmkt.GerCom.view_solicituds_gercom')->with('solicituds', $solicituds);
        return $view;
    }

    public function viewSolicitudeGerCom($token)
    {


        $solicitude = Solicitude::where('token', $token)->firstOrFail();
        return View::make('Dmkt.GerCom.view_solicitude_gercom')->with('solicitude', $solicitude);
    }

    public function approvedSolicitude($token)
    {

        $solicitude = Solicitude::where('token', $token);
        $solicitude->estado = 4;
        $data = $this->objectToArray($solicitude);
        $solicitude->update($data);
        return Redirect::to('show_gercom');
    }

}