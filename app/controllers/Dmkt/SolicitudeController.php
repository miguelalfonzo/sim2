<?php
/**
 * Created by PhpStorm.
 * User: Developer
 * Date: 31/07/14
 * Time: 04:35 PM
 */


namespace Dmkt;

use \Common\State;
use \Common\Fondo;
use \Common\TypePayment;
use \BaseController;
use \View;
use \DB;
use \Input;
use \Redirect;
use \Demo;
use \Mail;
use \Image;
use \Hash;
use \User;
use \Auth;
use \Session;
use \Validator;
use \Excel;
use \Response;
use \Illuminate\Database\Query\Builder;
use \Expense\Expense;

class SolicitudeController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->beforeFilter('active-user');


    }

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
        Excel::load('test.xlsx', function($reader) {

            $results = $reader->get();

            // ->all() is a wrapper for ->get() and will work the same
            $results = $reader->all();
            echo $results;

        });



       // Demo::display();


    }

    /** ----------------------------------  Representante Medico ---------------------------------- */

    public function show_rm()
    {
        $states = State::orderBy('idestado', 'ASC')->get();
        return View::make('Dmkt.Rm.show_rm')->with('states', $states);
    }

    public function getclients()
    {

        $clients = Client::take(1030)->get(array('clcodigo', 'clnombre'));
        return Response::json($clients);
        //return json_encode($clients);
    }

    public function newSolicitude()
    {

        $families = Marca::orderBy('descripcion', 'Asc')->get();
        $typePayments = TypePayment::all();
        $fondos = Fondo::all();
        $typesolicituds = TypeSolicitude::all();
        $data = [
            'families' => $families,
            'fondos' => $fondos,
            'typesolicituds' => $typesolicituds,
            'typePayments' => $typePayments
        ];

        return View::make('Dmkt.Rm.register_solicitude', $data);

    }

    public function registerSolicitude()
    {

        $inputs = Input::all();
        $image = Input::file('file');

        $rules = array(
            'titulo'         => 'required|alpha_num',
            'description'             => 'alpha_num',
            'monto'             => 'required|numeric',

        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {

            // get the error messages from the validator
            $messages = $validator->messages();

            // redirect our user back to the form with the errors from the validator
            //return Redirect::to('show_rm')->with('errors', $messages);
            return $messages;
        }else{

            $solicitude = new Solicitude;
            $typeUser = Auth::user()->type;
            if (isset($image)) {

                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                //$filename = $image->getClientOriginalName();
                $path = public_path('img/reembolso/' . $filename);
                Image::make($image->getRealPath())->resize(800, 600)->save($path);
                $solicitude->image = $filename;
            }


            $date = $inputs['delivery_date'];
            list($d, $m, $y) = explode('/', $date);
            $d = mktime(11, 14, 54, $m, $d, $y);
            $date = date("Y/m/d", $d);
            $aux_idsol = $solicitude->searchId() + 1;
            $solicitude->idsolicitud = $aux_idsol;
            $solicitude->descripcion = $inputs['description'];
            $solicitude->titulo = $inputs['titulo'];
            $solicitude->monto = $inputs['monto'];

            $solicitude->iduser = Auth::user()->id;
            $solicitude->monto_factura = $inputs['amount_fac'];
            $solicitude->fecha_entrega = $date;
            $solicitude->idtiposolicitud = $inputs['type_solicitude'];
            $token = sha1(md5(uniqid($solicitude->idsolicitud, true)));
            $solicitude->token =  $token;

            if (isset($inputs['sub_type_activity'])) {
                $fondo = $inputs['sub_type_activity'];
                $solicitude->idfondo = $inputs['sub_type_activity'];
            }

            $solicitude->tipo_moneda = $inputs['money'];
            if ($inputs['type_payment'] == 2) {
                $solicitude->numruc = $inputs['ruc'];
            } else if ($inputs['type_payment'] == 3) {
                $solicitude->numcuenta = $inputs['number_account'];
            }
            $solicitude->idtipopago = $inputs['type_payment'];
            $solicitude->estado = PENDIENTE;


            if ($solicitude->save()) {
                $data = array(

                    'name' => $inputs['titulo'],
                    'description' => $inputs['description'],
                    'monto' => $inputs['monto'],
                    'money' => $inputs['money']

                );

                /*
                Mail::send('emails.template', $data, function ($message) {
                    $message->to('cesarhm1687@gmail.com');
                    $message->subject('Nueva Solicitud');

                });*/
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

                if(isset($fondo) && $fondo == 31 ) // si es de tipo actividad "otros" lo deriva a los gerentes
                    $this->derivedSolicitude($token,1);

                return $typeUser;

            }
        }


    }

    public function listSolicitude($state)
    {

        $today = getdate();
        $m = $today['mday'] . '-' . $today['mon'] . '-' . $today['year'];
        $lastday = date('t-m-Y', strtotime($m));
        $firstday = date('01-m-Y', strtotime($m));

        if (Auth::user()->type == 'R') {
            if ($state== 0) {

                $solicituds = Solicitude::where('iduser', Auth::user()->Rm->iduser)
                    ->whereRaw("created_at between to_date('$firstday' ,'DD-MM-YY') and to_date('$lastday' ,'DD-MM-YY')+1")
                    ->get();

            } else {
                $solicituds = Solicitude::where('iduser', Auth::user()->Rm->iduser)
                    ->where('estado', $state)
                    ->whereRaw("created_at between to_date('$firstday' ,'DD-MM-YY') and to_date('$lastday' ,'DD-MM-YY')+1")
                    ->get();

            }

            $view = View::make('Dmkt.Rm.view_solicituds_rm')->with('solicituds', $solicituds);

            return $view;
        }
    }

    public function viewSolicitude($token)
    {
        $solicitude = Solicitude::where('token', $token)->firstOrFail();
        $managers = Manager::all();
        $typePayments = TypePayment::all();
        $data = [
            'solicitude' => $solicitude,
            'managers' => $managers,
            'typePayments' => $typePayments
        ];

        return View::make('Dmkt.Rm.view_solicitude', $data);
    }

    public function editSolicitude($token)
    {

        $families = Marca::orderBy('descripcion', 'Asc')->get();
        $solicitude = Solicitude::where('token', $token)->firstOrFail();
        $id = $solicitude->idsolicitud;
        $clients = DB::table('DMKT_RG_SOLICITUD_CLIENTES')->where('idsolicitud', $id)->lists('idcliente');
        $clients = Client::whereIn('clcodigo', $clients)->get(array('clcodigo', 'clnombre'));
        $families2 = DB::table('DMKT_RG_SOLICITUD_FAMILIA')->where('idsolicitud', $id)->lists('idfamilia');
        $families2 = Marca::whereIn('id', $families2)->get(array('id', 'descripcion'));

        $typesolicituds = TypeSolicitude::all();
        $typePayments = TypePayment::all();
        $fondos = Fondo::all();

        $data = [

            'solicitude' => $solicitude,
            'clients' => $clients,
            'families' => $families,
            'families2' => $families2,
            'typesolicituds' => $typesolicituds,
            'fondos' => $fondos,
            'typePayments' => $typePayments
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
        $sol = Solicitude::find($id);
        $solicitude = Solicitude::where('idsolicitud', $id);
        $image = Input::file('file');

        if (isset($image)) {

            $path = public_path('img/reembolso/' . $sol->image);
            @unlink($path);
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();
            //$filename = $image->getClientOriginalName();
            $path = public_path('img/reembolso/' . $filename);
            Image::make($image->getRealPath())->resize(800, 600)->save($path);
            $solicitude->image = $filename;
        }

        //$solicitude->idsolicitud = (int) $id ;
        $solicitude->descripcion = $inputs['description'];
        $solicitude->titulo = $inputs['titulo'];
        $solicitude->monto = $inputs['monto'];
        $solicitude->estado = PENDIENTE;
        $solicitude->fecha_entrega = $date;
        $solicitude->monto_factura = $inputs['amount_fac'];

        $solicitude->tipo_moneda = $inputs['money'];
        $typeSolicitude = $inputs['type_solicitude'];
        if ($sol->idtiposolicitud == 2 && ($typeSolicitude == 1 || $typeSolicitude == 3)) {
            $path = public_path('img/reembolso/' . $sol->image);
            @unlink($path);
            $solicitude->monto_factura = null;
        }

        $solicitude->idtiposolicitud = $typeSolicitude;

        $typePayment = $inputs['type_payment'];
        if ($typePayment == 1) {
            $solicitude->numruc = null;
            $solicitude->numcuenta = null;
        } else if ($typePayment == 2) {
            $solicitude->numruc = $inputs['ruc'];
            $solicitude->numcuenta = null;
        } else if ($typePayment == 3) {
            $solicitude->numcuenta = $inputs['number_account'];
            $solicitude->numruc = null;
        }
        $solicitude->idtipopago = $inputs['type_payment'];

        if(isset($inputs['sub_type_activity'])){
            $fondo =$inputs['sub_type_activity'];
            $solicitude->idfondo = $fondo;
        }

        $data = $this->objectToArray($solicitude);
        $solicitude->update($data);

        SolicitudeClient::where('idsolicitud', '=', $id)->delete();
        SolicitudeFamily::where('idsolicitud', '=', $id)->delete();

       /* if($activity == 1){

        }
        else if($activity == 31){

        }*/

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

        $typeUser = Auth::user()->type;
        if ($typeUser == 'R')
            return 'R';
        if ($typeUser == 'S')
            return 'S';

    }

    public function cancelSolicitude()
    {

        $inputs = Input::all();
        $id = $inputs['idsolicitude'];
        $solicitude = Solicitude::where('idsolicitud', $id);
        $solicitude->estado = CANCELADO;
        $data = $this->objectToArray($solicitude);
        $solicitude->update($data);

        return $this->listSolicitude(PENDIENTE);

    }

    public function subtypeactivity($id)
    {

        $subtypeactivities = Fondo::where('idtipoactividad', $id)->get();
        return json_encode($subtypeactivities);

    }

    public function searchSolicituds()
    {

        $inputs = Input::all();
        $estado = $inputs['idstate'];
        $start = $inputs['date_start'];
        $end = $inputs['date_end'];
        $today = getdate();
        $m = $today['mday'] . '-' . $today['mon'] . '-' . $today['year'];
        $lastday = date('t-m-Y', strtotime($m));
        $firstday = date('01-m-Y', strtotime($m));
        $idUser = Auth::user()->id;
        if ($start != null && $end != null) {
            if ($estado != 10) {

                $solicituds = Solicitude::select('*')
                    ->where('estado', $estado)
                    ->where('iduser', $idUser)
                    ->whereRaw("created_at between to_date('$start' ,'DD-MM-YY') and to_date('$end' ,'DD-MM-YY')+1")
                    ->get();

            } else {

                $solicituds = Solicitude::select('*')
                    ->where('iduser', $idUser)
                    ->whereRaw("created_at between to_date('$start' ,'DD-MM-YY') and to_date('$end' ,'DD-MM-YY')+1")
                    ->get();
            }

        } else {
            if ($estado != 10) {

                $solicituds = Solicitude::select('*')
                    ->where('estado', $estado)
                    ->where('iduser', $idUser)
                    ->whereRaw("created_at between to_date('$firstday' ,'DD-MM-YY') and to_date('$lastday' ,'DD-MM-YY')+1")
                    ->get();
            } else {

                $solicituds = Solicitude::select('*')
                    ->where('iduser', $idUser)
                    ->whereRaw("created_at between to_date('$firstday' ,'DD-MM-YY') and to_date('$lastday' ,'DD-MM-YY')+1")
                    ->get();
            }
        }


        $view = View::make('Dmkt.Rm.view_solicituds_rm')->with('solicituds', $solicituds);
        return $view;
    }

    /** -----------------------------------------------  Supervisor  -------------------------------------------------------- */

    public function show_sup()
    {

        $state = Session::get('state');
        $states = State::orderBy('idestado', 'ASC')->get();
        $data = [

            'states' => $states,
            'state' => $state
        ];
        return View::make('Dmkt.Sup.show_sup', $data);

    }

    public function viewSolicitudeSup($token)
    {
        $sol = Solicitude :: where('token', $token)->firstOrFail();

        if ($sol->user->type == 'R' && $sol->estado == PENDIENTE)
            Solicitude::where('token', $token)->update(array('blocked' => 1));

        $solicitude = Solicitude::where('token', $token)->firstOrFail();
        $typePayments = TypePayment::all();
        $managers = Manager::all();
        $fondos = Fondo::all();
        $data = [
            'solicitude' => $solicitude,
            'managers' => $managers,
            'typePayments' => $typePayments,
            'fondos' => $fondos
        ];
        return View::make('Dmkt.Sup.view_solicitude_sup', $data);
    }

    public function registerSolicitudeGerProd()
    {
        $inputs = Input::all();
        $image = Input::file('file');
        $solicitude = new Solicitude;
        if (isset($image)) {

            $filename = uniqid() . '.' . $image->getClientOriginalExtension();
            //$filename = $image->getClientOriginalName();
            $path = public_path('img/reembolso/' . $filename);
            Image::make($image->getRealPath())->resize(800, 600)->save($path);
            $solicitude->image = $filename;
        }
        $date = $inputs['delivery_date'];
        list($d, $m, $y) = explode('/', $date);
        $d = mktime(11, 14, 54, $m, $d, $y);
        $date = date("Y/m/d", $d);
        $solicitude->idsolicitud = $solicitude->searchId() + 1;
        $solicitude->descripcion = $inputs['description'];
        $solicitude->titulo = $inputs['titulo'];
        $solicitude->monto = $inputs['monto'];
        $solicitude->estado = PENDIENTE;
        $solicitude->iduser = Auth::user()->id;
        $solicitude->monto_factura = $inputs['amount_fac'];
        $solicitude->fecha_entrega = $date;
        $solicitude->idtiposolicitud = $inputs['type_solicitude'];
        $solicitude->token = sha1(md5(uniqid($solicitude->idsolicitud, true)));
        $solicitude->idfondo = $inputs['sub_type_activity'];
        $solicitude->tipo_moneda = $inputs['money'];
        if ($solicitude->save()) {
            $data = array(

                'name' => $inputs['titulo'],
                'description' => $inputs['description'],
                'monto' => $inputs['monto'],
                'money' => $inputs['money']

            );

            /*
            Mail::send('emails.template', $data, function ($message) {
                $message->to('cesarhm1687@gmail.com');
                $message->subject('Nueva Solicitud');

            });*/
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
            $typeUser = Auth::user()->type;

            if ($typeUser == 'S')
                return 'S';
        }

    }

    public function denySolicitude()
    {

        $inputs = Input::all();
        $id = $inputs['idsolicitude'];
        $solicitude = Solicitude::where('idsolicitud', $id);
        $solicitude->idsolicitud = (int)$id;
        $solicitude->observacion = $inputs['observacion'];
        $solicitude->estado = RECHAZADO;
        $solicitude->blocked = 0;
        $data = $this->objectToArray($solicitude);
        $solicitude->update($data);
        return Redirect::to('show_sup')->with('state', RECHAZADO);

    }

    public function acceptedSolicitude()
    {

        $inputs = Input::all();
        $idSol = $inputs['idsolicitude'];
        $solicitude = Solicitude::where('idsolicitud', $idSol);
        $solicitude->estado = ACEPTADO;
        $solicitude->idaproved = Auth::user()->id;
        $solicitude->monto = $inputs['monto'];
        $solicitude->idfondo = $inputs['sub_type_activity'];
        $solicitude->observacion = $inputs['observacion'];
        $solicitude->blocked = 0;
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

        return Redirect::to('show_sup')->with('state', ACEPTADO);

    }

    public function derivedSolicitude($token,$derive=0)
    {

        Solicitude::where('token', $token)->update(array('derived' => 1 ,'idfondo' => 31));
        $solicitude = Solicitude::where('token', $token)->firstOrFail();

        $id = $solicitude->idsolicitud;
        $sol = Solicitude::find($id);
        foreach ($sol->families as $v) {
            $solGer = new SolicitudeGer;
            $solGer->idsolicitud_gerente = $solGer->searchId() + 1;
            $solGer->idsolicitud = $id;
            $solGer->idgerprod = $v->marca->manager->id;
            $solGer->save();
        }

         if($derive == 0)return Redirect::to('show_sup');
    }

    public function listSolicitudeSup($id)
    {

        $today = getdate();
        $m = $today['mday'] . '-' . $today['mon'] . '-' . $today['year'];
        $lastday = date('t-m-Y', strtotime($m));
        $firstday = date('01-m-Y', strtotime($m));

        if (Auth::user()->type == 'S') {
            $reps = Auth::user()->Sup->Reps;
            $users_ids = [];
            foreach ($reps as $rm)
                $users_ids[] = $rm->iduser;
            $users_ids[] = Auth::user()->id;
            if ($id == 10) {

                $solicituds = Solicitude::whereIn('iduser', $users_ids)
                    ->whereRaw("created_at between to_date('$firstday' ,'DD-MM-YY') and to_date('$lastday' ,'DD-MM-YY')+1")
                    ->get();
            } else {

                $solicituds = Solicitude::whereIn('iduser', $users_ids)
                    ->where('estado', $id)
                    ->whereRaw("created_at between to_date('$firstday' ,'DD-MM-YY') and to_date('$lastday' ,'DD-MM-YY')+1")
                    ->get();
            }


            $view = View::make('Dmkt.Sup.view_solicituds_sup')->with('solicituds', $solicituds);
            return $view;
        }

    }

    public function searchSolicitudsSup()
    {

        $inputs = Input::all();
        $estado = $inputs['idstate'];
        $start = $inputs['date_start'];
        $end = $inputs['date_end'];
        $today = getdate();
        $m = $today['mday'] . '-' . $today['mon'] . '-' . $today['year'];
        $lastday = date('t-m-Y', strtotime($m));
        $firstday = date('01-m-Y', strtotime($m));

        $user = Auth::user();
        if ($user->type == 'S') {
            $reps = $user->Sup->Reps;
            $users_ids = [];
            foreach ($reps as $rm)
                $users_ids[] = $rm->iduser;
            $users_ids[] = $user->id;
            if ($start != null && $end != null) {
                if ($estado != 10) {
                    $solicituds = Solicitude::whereIn('iduser', $users_ids)
                        ->where('estado', $estado)
                        ->whereRaw("created_at between to_date('$start' ,'DD-MM-YY') and to_date('$end' ,'DD-MM-YY')+1")
                        ->get();

                } else {
                    $solicituds = Solicitude::whereIn('iduser', $users_ids)
                        ->whereRaw("created_at between to_date('$start' ,'DD-MM-YY') and to_date('$end' ,'DD-MM-YY')+1")
                        ->get();
                }


            } else {
                if ($estado != 10) {
                    $solicituds = Solicitude::whereIn('iduser', $users_ids)
                        ->where('estado', $estado)
                        ->whereRaw("created_at between to_date('$firstday' ,'DD-MM-YY') and to_date('$lastday' ,'DD-MM-YY')+1")
                        ->get();
                } else {
                    $solicituds = Solicitude::whereIn('iduser', $users_ids)
                        ->whereRaw("created_at between to_date('$firstday' ,'DD-MM-YY') and to_date('$lastday' ,'DD-MM-YY')+1")
                        ->get();
                }
            }

            $view = View::make('Dmkt.Sup.view_solicituds_sup')->with('solicituds', $solicituds);
            return $view;
        }
    }

    public function cancelSolicitudeSup()
    {

        $inputs = Input::all();
        $id = $inputs['idsolicitude'];
        $solicitude = Solicitude::where('idsolicitud', $id);
        $solicitude->estado = CANCELADO;
        $data = $this->objectToArray($solicitude);
        $solicitude->update($data);
        return $this->listSolicitude(PENDIENTE);

    }

    public function disBlockSolicitudeSup($token)
    {

        $solicitude = Solicitude::where('token', $token)->firstOrFail();
        Solicitude::where('idsolicitud', $solicitude->idsolicitud) // desbloqueamos la solicitud para que el rm lo pueda editar
            ->update(array('blocked' => 0));
        return Redirect::to('show_sup');

    }


    /** --------------------------------------------- Gerente de  Producto ----------------------------------------------- */

    public function show_gerprod()
    {
        $state = Session::get('state');
        $states = State::orderBy('idestado', 'ASC')->get();
        $data = [

            'states' => $states,
            'state' => $state
        ];
        return View::make('Dmkt.GerProd.show_gerprod', $data);
    }

    public function listSolicitudeGerProd($id)
    {

        $today = getdate();
        $m = $today['mday'] . '-' . $today['mon'] . '-' . $today['year'];
        $lastday = date('t-m-Y', strtotime($m));
        $firstday = date('01-m-Y', strtotime($m));
        $user = Auth::user();
        if ($user->type == 'P') {
            $solicitud_ids = [];
            $solicituds = $user->GerProd->solicituds;
            foreach ($solicituds as $sol) {
                $solicitud_ids[] = $sol->idsolicitud;
            }

            if(count($solicitud_ids)){ // si no hay solicitudes
                if ($id == 0) {
                    $solicituds = Solicitude::whereIn('idsolicitud', $solicitud_ids)
                        ->whereRaw("created_at between to_date('$firstday' ,'DD-MM-YY') and to_date('$lastday' ,'DD-MM-YY')+1")
                        ->get();
                } else {
                    $solicituds = Solicitude::whereIn('idsolicitud', $solicitud_ids)
                        ->where('estado', $id)
                        ->whereRaw("created_at between to_date('$firstday' ,'DD-MM-YY') and to_date('$lastday' ,'DD-MM-YY')+1")
                        ->get();
                }
            }

            $view = View::make('Dmkt.GerProd.view_solicituds_gerprod')->with('solicituds', $solicituds);
            return $view;
        }
    }

    public function viewSolicitudeGerProd($token)
    {

        $block = false;
        $userid = Auth::user()->gerprod->id;
        $solicitude = Solicitude::where('token', $token)->firstOrFail();
        if($solicitude->estado == PENDIENTE)
        Solicitude::where('token', $token)->update(array('blocked' => 1));

        $solicitudeBlocked = SolicitudeGer::where('idsolicitud', $solicitude->idsolicitud)->where('idgerprod', $userid)->firstOrFail(); //vemos si la solicitud esta blokeada
        //echo json_encode($solicitudeBlocked);die;
        if ($solicitudeBlocked->blocked == 0) {
            SolicitudeGer::where('idsolicitud', $solicitude->idsolicitud) // blockeamos la solicitud para que el otro gerente no lo pueda editar
                ->where('idgerprod', '<>', $userid)
                ->update(array('blocked' => 1));
        } else {
            $block = true;
        }
        $fondos = Fondo::all();
        $typePayments = TypePayment::all();
        $data = [
            'solicitude' => $solicitude,
            'block' => $block,
            'typePayments' => $typePayments,
            'fondos' => $fondos

        ];
        return View::make('Dmkt.GerProd.view_solicitude_gerprod', $data);
    }

    public function disBlockSolicitudeGerProd($token)
    {

        $solicitude = Solicitude::where('token', $token)->firstOrFail();
        SolicitudeGer::where('idsolicitud', $solicitude->idsolicitud) // desblockeamos la solicitud para que el otro gerente no lo pueda editar
            ->update(array('blocked' => 0));
        return Redirect::to('show_gerprod');

    }

    public function acceptedSolicitudeGerProd()
    {

        $inputs = Input::all();
        $idSol = $inputs['idsolicitude'];
        $solicitude = Solicitude::where('idsolicitud', $idSol);
        $solicitude->estado = ACEPTADO;
        $solicitude->idaproved = Auth::user()->id;
        $solicitude->monto = $inputs['monto'];
        $solicitude->observacion = $inputs['observacion'];
        $solicitude->idfondo = $inputs['sub_type_activity'];
        $solicitude->blocked = 0;
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
        return Redirect::to('show_gerprod')->with('state', ACEPTADO);

    }

    public function searchSolicitudsGerProd()
    {

        $inputs = Input::all();
        $estado = $inputs['idstate'];
        $start = $inputs['date_start'];
        $end = $inputs['date_end'];
        $today = getdate();
        $m = $today['mday'] . '-' . $today['mon'] . '-' . $today['year'];
        $lastday = date('t-m-Y', strtotime($m));
        $firstday = date('01-m-Y', strtotime($m));
        $user = Auth::user();

        if ($user->type == 'P') {

            $solicitud_ids = [];
            $solicituds = $user->GerProd->solicituds;
            foreach ($solicituds as $sol) {
                $solicitud_ids[] = $sol->idsolicitud; // jalo los ids de las solicitudes pertenecientes al gerente de producto
            }
            $solicitud_ids[] = 0; // el cero va para que tenga al menos con que comparar, para que no salga error.
            if ($start != null && $end != null) {
                if ($estado != 10) {
                    $solicituds = Solicitude::whereIn('idsolicitud', $solicitud_ids)
                        ->where('estado', $estado)
                        ->whereRaw("created_at between to_date('$start' ,'DD-MM-YY') and to_date('$end' ,'DD-MM-YY')+1")
                        ->get();

                } else {
                    $solicituds = Solicitude::whereIn('idsolicitud', $solicitud_ids)
                        ->whereRaw("created_at between to_date('$start' ,'DD-MM-YY') and to_date('$end' ,'DD-MM-YY')+1")
                        ->get();
                }


            } else {
                if ($estado != 10) {
                    $solicituds = Solicitude::whereIn('idsolicitud', $solicitud_ids)
                        ->where('estado', $estado)
                        ->whereRaw("created_at between to_date('$firstday' ,'DD-MM-YY') and to_date('$lastday' ,'DD-MM-YY')+1")
                        ->get();
                } else {
                    $solicituds = Solicitude::whereIn('idsolicitud', $solicitud_ids)
                        ->whereRaw("created_at between to_date('$firstday' ,'DD-MM-YY') and to_date('$lastday' ,'DD-MM-YY')+1")
                        ->get();
                }
            }

            $view = View::make('Dmkt.GerProd.view_solicituds_gerprod')->with('solicituds', $solicituds);
            return $view;
        }
    }

    public function denySolicitudeGerProd()
    {

        $inputs = Input::all();
        $id = $inputs['idsolicitude'];
        $solicitude = Solicitude::where('idsolicitud', $id);
        $solicitude->idsolicitud = (int)$id;
        $solicitude->idaproved = Auth::user()->id;
        $solicitude->observacion = $inputs['observacion'];
        $solicitude->blocked = 0;
        $solicitude->estado = RECHAZADO;
        $data = $this->objectToArray($solicitude);
        $solicitude->update($data);
        return Redirect::to('show_gerprod')->with('state', RECHAZADO);

    }


    /** ---------------------------------------------  Gerente Comercial  -------------------------------------------------*/

    public function show_gercom()
    {
        $state = Session::get('state');
        $states = State::orderBy('idestado', 'ASC')->get();
        $data = [
          'state' => $state,
          'states' => $states  
        ];
        return View::make('Dmkt.GerCom.show_gercom', $data);
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


        $solicitude = Solicitude::where('token', $token)->first();
        $sol = Solicitude::where('token', $token);
        $sol->blocked = 1;
        $data = $this->objectToArray($sol);
        $sol->update($data);

        return View::make('Dmkt.GerCom.view_solicitude_gercom')->with('solicitude', $solicitude);
    }

    public function approvedSolicitude()
    {

        $inputs = Input::all();
        $token = $inputs['token'];
        $sol = Solicitude::where('token', $token)->first();
        $idSol = $sol->idsolicitud;
        $solicitude = Solicitude::where('token', $token);
        $solicitude->estado = APROBADO;
        $solicitude->blocked = 0;
        $solicitude->monto = $inputs['monto'];
        $data = $this->objectToArray($solicitude);
        $solicitude->update($data);
        //SolicitudeFamily::where('idsolicitud', $idSol)->delete();
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

        return Redirect::to('show_gercom')->with('state',APROBADO);
    }

    public function denySolicitudeGerCom()
    {

        $inputs = Input::all();
        $id = $inputs['idsolicitude'];
        $solicitude = Solicitude::where('idsolicitud', $id);
        $solicitude->idsolicitud = (int)$id;
        $solicitude->idaproved = Auth::user()->id;
        $solicitude->observacion = $inputs['observacion'];
        $solicitude->estado = RECHAZADO;
        $solicitude->blocked = 0;
        $data = $this->objectToArray($solicitude);
        $solicitude->update($data);
        return Redirect::to('show_gercom')->with('state', RECHAZADO);

    }

    public function searchSolicitudsGerCom()
    {

        $inputs = Input::all();
        $estado = $inputs['idstate'];
        $start = $inputs['date_start'];
        $end = $inputs['date_end'];
        $today = getdate();
        $m = $today['mday'] . '-' . $today['mon'] . '-' . $today['year'];
        $lastday = date('t-m-Y', strtotime($m));
        $firstday = date('01-m-Y', strtotime($m));
        $user = Auth::user();


        if ($start != null && $end != null) {
            if ($estado != 10) {
                if ($estado == RECHAZADO) {
                    $solicituds = Solicitude::where('estado', $estado)->where('idaproved', 16)
                        ->whereRaw("created_at between to_date('$start' ,'DD-MM-YY') and to_date('$end' ,'DD-MM-YY')+1")
                        ->get();
                } else {
                    $solicituds = Solicitude::where('estado', $estado)
                        ->whereRaw("created_at between to_date('$start' ,'DD-MM-YY') and to_date('$end' ,'DD-MM-YY')+1")
                        ->get();
                }


            } else {

                $solicituds = Solicitude::where('estado', $estado)
                    ->whereRaw("created_at between to_date('$start' ,'DD-MM-YY') and to_date('$end' ,'DD-MM-YY')+1")
                    ->get();
            }


        } else {
            if ($estado != 10) {
                if ($estado == RECHAZADO) {
                    $solicituds = Solicitude::where('estado', $estado)->where('idaproved', 16) // id gerente comercial
                        ->whereRaw("created_at between to_date('$firstday' ,'DD-MM-YY') and to_date('$lastday' ,'DD-MM-YY')+1")
                        ->get();
                } else {
                    $solicituds = Solicitude::where('estado', $estado)
                        ->whereRaw("created_at between to_date('$firstday' ,'DD-MM-YY') and to_date('$lastday' ,'DD-MM-YY')+1")
                        ->get();
                }

            } else {
                $solicituds = Solicitude::where('estado', $estado)
                    ->whereRaw("created_at between to_date('$firstday' ,'DD-MM-YY') and to_date('$lastday' ,'DD-MM-YY')+1")
                    ->get();
            }
        }

        $view = View::make('Dmkt.GerCom.view_solicituds_gercom')->with('solicituds', $solicituds);
        return $view;

    }

    /** ---------------------------------------------  Contabilidad -------------------------------------------------*/


    public function show_cont()
    {
        $state = Session::get('state');
        $states = State::orderBy('idestado', 'ASC')->get();
        $data = [
            'states' => $states,
            'state' => $state
        ];
        return View::make('Dmkt.Cont.show_cont', $data);
    }

    public function listSolicitudeCont($id)
    {
        if ($id == 0) {
            $solicituds = Solicitude::all();
        } else {
            $solicituds = Solicitude::where('estado', '=', $id)->get();
        }

        $view = View::make('Dmkt.Cont.view_solicituds_cont')->with('solicituds', $solicituds);
        return $view;
    }

    public function searchSolicitudeCont()
    {

        $inputs = Input::all();
        $estado = $inputs['idstate'];
        $start = $inputs['date_start'];
        $end = $inputs['date_end'];
        $today = getdate();
        $m = $today['mday'] . '-' . $today['mon'] . '-' . $today['year'];
        $lastday = date('t-m-Y', strtotime($m));
        $firstday = date('01-m-Y', strtotime($m));
        $user = Auth::user();


        if ($start != null && $end != null) {
            if ($estado != 10) {
                $solicituds = Solicitude::where('estado', $estado)
                    ->whereRaw("created_at between to_date('$start' ,'DD-MM-YY') and to_date('$end' ,'DD-MM-YY')+1")
                    ->get();

            } else {
                $solicituds = Solicitude::where('estado', $estado)
                    ->whereRaw("created_at between to_date('$start' ,'DD-MM-YY') and to_date('$end' ,'DD-MM-YY')+1")
                    ->get();
            }


        } else {
            if ($estado != 10) {
                $solicituds = Solicitude::where('estado', $estado)
                    ->whereRaw("created_at between to_date('$firstday' ,'DD-MM-YY') and to_date('$lastday' ,'DD-MM-YY')+1")
                    ->get();
            } else {
                $solicituds = Solicitude::where('estado', $estado)
                    ->whereRaw("created_at between to_date('$firstday' ,'DD-MM-YY') and to_date('$lastday' ,'DD-MM-YY')+1")
                    ->get();
            }
        }

        $view = View::make('Dmkt.Cont.view_solicituds_cont')->with('solicituds', $solicituds);
        return $view;

    }

    public function viewSolicitudeCont($token)
    {
        $solicitude = Solicitude::where('token', $token)->firstOrFail();

        return View::make('Dmkt.Cont.view_solicitude_cont')->with('solicitude', $solicitude);
    }

    public function viewSeatSolicitude($token)
    {
        $solicitude = Solicitude::where('token', $token)->firstOrFail();
        $data = [
            'solicitude' => $solicitude
        ];
        return View::make('Dmkt.Cont.register_seat', $data);
    }

    public function viewSeatExpense($token)
    {
        $solicitude = Solicitude::where('token', $token)->firstOrFail();
        $expense = Expense::where('idsolicitud',$solicitude->idsolicitud)->get();
        $data = [
            'solicitude' => $solicitude,
            'expense' => $expense
        ];
        return View::make('Dmkt.Cont.register_seat_expense', $data);
    }

    public function generateSeatSolicitude()
    {
        $inputs     = Input::all();
        $solicitude = Solicitude::where('idsolicitud', $inputs['idsolicitude']);
        $solicitude->asiento = 2;
        $data = $this->objectToArray($solicitude);
        if($solicitude->update($data))
        {
            return 1;
        }
        return 0;
    }

    public function generateSeatExpense()
    {
        $inputs = Input::all();
        $solicitude = Solicitude::find($inputs['idsolicitude']);
        $solicitude->estado = 7;
        if($solicitude->update())
        {
            return 1;
        }
        return 0;
    }

    public function enableDeposit()
    {
        $inputs = Input::all();

        $val_ret = null;
        if($inputs['ret0'])
        {
            $val_ret = intval($inputs['ret0']);
        }
        if($inputs['ret1'])
        {
            $val_ret = intval($inputs['ret1']);
        }
        if($inputs['ret2'])
        {
            $val_ret = intval($inputs['ret2']);
        }
        
        $id = $inputs['idsolicitude'];
        $solicitude = Solicitude::where('idsolicitud', $id);
        $solicitude->retencion = $val_ret;
        $solicitude->asiento = 1;
        $data = $this->objectToArray($solicitude);

        if($solicitude->update($data))
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }
}