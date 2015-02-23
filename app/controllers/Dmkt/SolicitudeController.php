<?php

namespace Dmkt;

use \Auth;
use \BaseController;
use \Common\Fondo;
use \Common\State;
use \Common\TypePayment;
use \DB;
use \Demo;
use \Excel;
use \Exception;
use \Expense\Entry;
use \Expense\Expense;
use \Expense\ExpenseItem;
use \Expense\ProofType;
use \Hash;
use \Illuminate\Database\Query\Builder;
use \Image;
use \Input;
use \Log;
use \Mail;
use \Redirect;
use \Response;
use \Session;
use \URL;
use \User;
use \Validator;
use \View;

class SolicitudeController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->beforeFilter('active-user');
    }

    private function getDay(){
        $currentDate = getdate();
        $toDay = $currentDate['mday']."/".str_pad($currentDate['mon'],2,'0',STR_PAD_LEFT)."/".$currentDate['year'];
        $lastDay = '06/'.str_pad(($currentDate['mon']+1),2,'0',STR_PAD_LEFT).'/'.$currentDate['year'];
        $date = ['toDay'=>$toDay,'lastDay'=> $lastDay];
        return $date;
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
        Log::error(json_encode($image));
        Log::error(json_encode(filesize($image)));
        Log::error(json_encode($inputs));
        foreach ($inputs['clients'] as $client)
        {
            Log::error($client);
            if (empty($client))
            {
                return "Ingrese todos los campos de Cliente";
            }
        }
        if ($inputs['type_solicitude'] == 1 || $inputs['type_solicitude'] == 3)
        {
            $rules = array(
            'titulo'        => 'required',
            'monto'         => 'required|numeric',
            'delivery_date' => 'required|date_format:"d/m/Y"',
            'clients'       => 'required'
            );
        }
        else if($inputs['type_solicitude'] == 2)
        {
            if (filesize($image) == FALSE)
            {
                return "Ingrese un archivo de imagen";
            }
            $rules = array(
            'titulo'        => 'required',
            'monto'         => 'required|numeric',
            'amount_fac'     => 'required|numeric',
            'delivery_date' => 'required|date_format:"d/m/Y"',
            'clients'       => 'required'
            );   
        }
        else
        {
            return "Tipo de Solicitud no Existente";
        }
        $validator = Validator::make($inputs, $rules);
        if ($validator->fails()) 
        {
            $messages = $validator->messages();
            return $messages;
        }
        else
        {
            $solicitude = new Solicitude;
            $typeUser = Auth::user()->type;
            if (isset($image)) 
            {
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                $path = public_path( IMAGE_PATH . $filename);
                Image::make($image->getRealPath())->resize(WIDTH,HEIGHT)->save($path);
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
            if (isset($inputs['sub_type_activity'])) 
            {
                $fondo = $inputs['sub_type_activity'];
                $solicitude->idfondo = $inputs['sub_type_activity'];
            }
            $solicitude->tipo_moneda = $inputs['money'];
            if ($inputs['type_payment'] == 2) 
            {
                $solicitude->numruc = $inputs['ruc'];
            } 
            else if ($inputs['type_payment'] == 3) 
            {
                $solicitude->numcuenta = $inputs['number_account'];
            }
            $solicitude->idtipopago = $inputs['type_payment'];
            $solicitude->estado = PENDIENTE;
            if ($solicitude->save()) 
            {
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
                foreach ($clients as $client) 
                {
                    $cod = explode(' ', $client);
                    $solicitude_clients = new SolicitudeClient;
                    $solicitude_clients->idsolicitud_clientes = $solicitude_clients->searchId() + 1;
                    $solicitude_clients->idsolicitud = $solicitude->searchId();
                    $solicitude_clients->idcliente = $cod[0];
                    $solicitude_clients->save();
                }
                $families = $inputs['families'];
                foreach ($families as $family) 
                {
                    $solicitude_families = new SolicitudeFamily;
                    $solicitude_families->idsolicitud_familia = $solicitude_families->searchId() + 1;
                    $solicitude_families->idsolicitud = $solicitude->searchId();
                    $solicitude_families->idfamilia = $family;
                    $solicitude_families->save();
                }
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

                $rSolicituds = Solicitude::select('*')
                    ->where('estado', $estado)
                    ->where('iduser', '<>' , $idUser)
                    ->where('idresponse',Auth::user()->id)
                    ->whereRaw("created_at between to_date('$start' ,'DD-MM-YY') and to_date('$end' ,'DD-MM-YY')+1")
                    ->get();
            } else {
                $solicituds = Solicitude::select('*')
                    ->where('iduser', $idUser)
                    ->whereRaw("created_at between to_date('$start' ,'DD-MM-YY') and to_date('$end' ,'DD-MM-YY')+1")
                    ->get();

                $rSolicituds = Solicitude::select('*')
                    ->where('iduser', '<>' , $idUser)
                    ->where('idresponse',Auth::user()->id)
                    ->whereRaw("created_at between to_date('$start' ,'DD-MM-YY') and to_date('$end' ,'DD-MM-YY')+1")
                    ->get();
            }
        } 
        else 
        {
            if ($estado != 10) 
            {
                $solicituds = Solicitude::select('*')
                    ->where('estado', $estado)
                    ->where('iduser', $idUser)
                    ->whereRaw("created_at between to_date('$firstday' ,'DD-MM-YY') and to_date('$lastday' ,'DD-MM-YY')+1")
                    ->get();

                $rSolicituds = Solicitude::select('*')
                    ->where('estado', $estado)
                    ->where('iduser', '<>' ,$idUser)
                    ->where('idresponse',Auth::user()->id)
                    ->whereRaw("created_at between to_date('$firstday' ,'DD-MM-YY') and to_date('$lastday' ,'DD-MM-YY')+1")
                    ->get();
            } else {

                $solicituds = Solicitude::select('*')
                    ->where('iduser', $idUser)
                    ->whereRaw("created_at between to_date('$firstday' ,'DD-MM-YY') and to_date('$lastday' ,'DD-MM-YY')+1")
                    ->get();

                $rSolicituds = Solicitude::select('*')
                    ->where('iduser', '<>' ,$idUser)
                    ->where('idresponse',Auth::user()->id)
                    ->whereRaw("created_at between to_date('$firstday' ,'DD-MM-YY') and to_date('$lastday' ,'DD-MM-YY')+1")
                    ->get();
            }
        }
        $view = View::make('Dmkt.Rm.view_solicituds_rm')->with(array('solicituds' => $solicituds,'rSolicitudes' => $rSolicituds));
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
        $sol = Solicitude::where('token', $token)->firstOrFail();

        if ($sol->user->type == 'R' && $sol->estado == PENDIENTE)
        {
            Solicitude::where('token', $token)->update(array('blocked' => 1));
        }

        $solicitude = Solicitude::where('token', $token)->firstOrFail();
        $typePayments = TypePayment::all();
        $managers = Manager::all();
        $fondos = Fondo::all();
        foreach($solicitude->families as $v)
        {
            $gerentes[] = $v->marca->manager;
        }
        $data = [
            'solicitude' => $solicitude,
            'managers' => $managers,
            'typePayments' => $typePayments,
            'fondos' => $fondos,
            'gerentes' => $gerentes
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
            return 'ok';


    }
   public function redirectAcceptedSolicitude(){
       return Redirect::to('show_sup')->with('state', ACEPTADO);
   }

    public function derivedSolicitude($token,$derive=0)
    {   
        Solicitude::where('token', $token)->update(array('derived' => 1 , 'blocked' => 0));
        $solicitude = Solicitude::where('token', $token)->firstOrFail();
        $id = $solicitude->idsolicitud;
        $sol = Solicitude::find($id);
        foreach ($sol->families as $v) 
        {
            $solGer = new SolicitudeGer;
            $solGer->idsolicitud_gerente = $solGer->searchId() + 1;
            $solGer->idsolicitud = $id;
            $solGer->idgerprod = $v->marca->manager->id;
            $solGer->save();

        }
        return Redirect::to('show_sup');
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
        return $this->listSolicitudeSup(PENDIENTE);

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
        //echo json_encode($data); die;
        return View::make('Dmkt.GerProd.view_solicitude_gerprod', $data);
    }

    public function disBlockSolicitudeGerProd($token)
    {
        //Desbloquenado La solicitud al presionar el boton Cancelar
        $solicitude = Solicitude::where('token', $token)->firstOrFail();
        $solicitude->blocked = 0 ;
        $solicitude->save();
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
        return 'ok';

    }
    public function redirectAcceptedSolicitudeGerProd(){
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
        $estado = Session::get('Estado');
        $states = State::orderBy('idestado', 'ASC')->get();
        $data = array(
            'state' => $state,
            'states' => $states,
            'estado' => $estado
        );
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

    public function gercomAsignarResponsable()
    {
        try
        {
            $middleRpta = array();
            $inputs = Input::all();
            $rules = array('responsable' => 'required|integer|min:1' );   
            $validator = Validator::make($inputs, $rules);
            if ($validator->fails()) 
            {
                $middleRpta = $validator->messages();
            }
            else
            {
                Solicitude::where('token',$inputs['token'])->update( array('idresponse' => $inputs['responsable']) );
                $middleRpta = array( status => ok , description => 'Se asigno la solicitud correctamente');
            }
        }
        catch (Exception $e)
        {
            $middleRpta = $this->internalException($e);
        }
        return $middleRpta;
    }

    public function viewSolicitudeGerCom($token)
    {
        $solicitude = Solicitude::where('token', $token)->first();
        $sol = Solicitude::where('token', $token);
        $sol->blocked = 1;
        $data = $this->objectToArray($sol);
        $sol->update($data);
        $info = array();
        $info[status] = ok;
        if ($solicitude->estado == APROBADO && $solicitude->asiento == 1)
        {
            $resp = array();
            $asistentes = User::where('type','AG')->get();
            foreach ($asistentes as $asistente)
            {
                array_push($resp, $asistente->person);
            }
            if(isset($solicitude->user->type))
            {
                if($solicitude->user->type == 'R')
                {
                    array_push( $resp, Solicitude::where('token', $token)->select('iduser')->first()->rm );
                    
                }
                elseif($solicitude->user->type == 'S')
                {
                    array_push( $resp, Solicitude::where('token', $token)->select('iduser')->first()->sup );
                }
                else
                {
                    $info[status] = warning;
                    $info[description] = 'No se encontro a un Representante Medico o Supervisor';                
                }
            }
            else
            {
                $info[status] = warning;
                $info[description] = 'No se encontro al Tipo de Solicitante';  
            }
            $info['solicitude'] = $solicitude;
            $info['responsables'] = $resp;
        }
        else
        {
            $info = array('solicitude' => $solicitude);
        }
        return View::make('Dmkt.GerCom.view_solicitude_gercom',$info);
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

       return 'ok';
    }
    public function redirectApprovedSolicitude(){
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

     public function disBlockSolicitudeGerCom($token)
    {
        //Desbloquenado La solicitud al presionar el boton Cancelar
        $solicitude = Solicitude::where('token', $token)->firstOrFail();
        $solicitude->blocked = 0 ;
        $solicitude->save();

        return Redirect::to('show_gercom');

    }

    /** ---------------------------------------------  Contabilidad -------------------------------------------------*/

    public function getTypeDoc($id){
        return json_decode(ProofType::find($id)->toJson());
    }
    public function createSeatElement($tempId, $solicitudId, $account_number, $cod_snt, $fecha_origen, $iva, $cod_prov, $nom_prov, $cod, $ruc, $serie, $numero, $dc, $monto, $marca, $descripcion, $tipo_responsable, $type){
        $seat = array(
            'tempId'            => $tempId,      // Temporal
            'solicitudId'       => intval($solicitudId),
            'numero_cuenta'     => $account_number,
            'codigo_sunat'      => $cod_snt,
            'fec_origen'        => $fecha_origen,
            'iva'               => $iva,
            'cod_prov'          => $cod_prov,
            'nombre_proveedor'  => $nom_prov,
            'cod'               => $cod,
            'ruc'               => $ruc,
            'prefijo'           => $serie,
            'cbte_proveedor'    => $numero,
            'dc'                => $dc,
            'importe'           => $monto,
            'leyenda'           => $marca,
            'leyenda_variable'  => $descripcion,
            'tipo_responsable'  => $tipo_responsable,
            'type'              => $type
        );
        return $seat;
    }
    public function generateSeatExpenseData($solicitude){
        
        $result         = array();
        $seatList       = array();
        $advanceSeat    = json_decode(Entry::where('idsolicitud', $solicitude->idsolicitud)->where('d_c', ASIENTO_GASTO_BASE)->first()->toJson());
        $accountElement = Fondo::where('cuenta_mkt', $advanceSeat->num_cuenta)->first();
        $account        = count($accountElement) == 0 ? array() : json_decode($accountElement->toJson());
        //var_dump($solicitude);
        $userElement    = User::where('id', $solicitude->idresponse)->first();
        //var_dump($userElement);
        //$user           = count($userElement) == 0 ? array() : json_decode($userElement->toJson());

        $account_number = '';
        $marcaNumber    = '';

        if(count($account) > 0){
            $account_number = $account->cuenta_cont;
            $marcaNumber    = $account->marca;
        }else{
            $errorTemp = array(
                'error' => ERROR_NOT_FOUND_MATCH_ACCOUNT_MKT_CNT,
                'msg'   => MESSAGE_NOT_FOUND_MATCH_ACCOUNT_MKT_CNT
            );
            if(!isset($result['error']) || !in_array($errorTemp, $result['error']))
                $result['error'][] = $errorTemp;
        }
        $tempId=1;
        foreach($solicitude->documentList as $documentKey => $documentElement){
            
            $comprobante             = $this->getTypeDoc($documentElement->idcomprobante);
            $comprobante->marcaArray =  explode(",", $comprobante->marca);

            $marca = '';
            if($marcaNumber == ''){
                $errorTemp = array(
                    'error' => ERROR_NOT_FOUND_MARCA,
                    'msg'   => MESSAGE_NOT_FOUND_MARCA
                );
                if(!isset($result['error']) || !in_array($errorTemp, $result['error']))
                    $result['error'][] = $errorTemp;
            }else{
                if (count($comprobante->marcaArray) == 2 && (boolean) $comprobante->igv == true) {
                    $marca = $marcaNumber == '' ? '' : $marcaNumber.$comprobante->marcaArray[1];
                }else{
                    $marca = $marcaNumber == '' ? '' : $marcaNumber.$comprobante->marcaArray[0];
                }
            }
            $seatListTemp = array();
            $fecha_origen =  date("d/m/Y", strtotime($documentElement->fecha_movimiento));
            // COMPROBANTES CON IGV
            if((boolean) $comprobante->igv === true){
                $itemLength = count($documentElement->itemList)-1;
                $total_neto = 0;
                foreach ($documentElement->itemList as $itemKey => $itemElement) {
                    $seat = $this->createSeatElement($tempId++, $solicitude->idsolicitud, $account_number, $comprobante->cta_sunat, $fecha_origen, ASIENTO_GASTO_IVA_BASE, ASIENTO_GASTO_COD_PROV_IGV, $documentElement->razon, ASIENTO_GASTO_COD_IGV, $documentElement->ruc, $documentElement->num_prefijo, $documentElement->num_serie, ASIENTO_GASTO_BASE, $itemElement->importe, $marca, 'M TEMPLE '.$itemElement->cantidad .' '.$itemElement->descripcion, 1, '');
                    $total_neto += $itemElement->importe;
                    array_push($seatListTemp, $seat);
                    if($itemLength == $itemKey){
                        $seat = $this->createSeatElement($tempId++, $solicitude->idsolicitud, $account_number, $comprobante->cta_sunat, $fecha_origen, ASIENTO_GASTO_IVA_IGV, ASIENTO_GASTO_COD_PROV_IGV, $documentElement->razon, ASIENTO_GASTO_COD_IGV, $documentElement->ruc, $documentElement->num_prefijo, $documentElement->num_serie, ASIENTO_GASTO_BASE, $documentElement->igv, $marca, $documentElement->razon, 1, 'IGV');

                        array_push($seatListTemp, $seat);
                        if(!($documentElement->imp_serv == null || $documentElement->imp_serv == 0 || $documentElement->imp_serv == '')){
                            $porcentaje = $total_neto/$documentElement->imp_serv;
                            $seat = $this->createSeatElement($tempId++, $solicitude->idsolicitud, $account_number, '', $fecha_origen, '', '', '', '', '', '', '', ASIENTO_GASTO_BASE, $documentElement->imp_serv, $marca, 'SERVICIO '. $porcentaje .'% '. $documentElement->descripcion, '', 'SER');

                            array_push($seatListTemp, $seat);
                        }

                        //FACTURA TIENE REPARO
                        if($documentElement->reparo == '1'){
                            $seat = $this->createSeatElement($tempId++, $solicitude->idsolicitud, CUENTA_REPARO_COMPRAS, '', $fecha_origen, '', '', '', '', '', '', '', ASIENTO_GASTO_BASE, $documentElement->imp_serv, $marca, $documentElement->descripcion .'-REP '. substr($comprobante->descripcion,0,1).'/' .$documentElement->num_prefijo .'-'. $documentElement->num_serie, '', 'REP');
                            array_push($seatListTemp, $seat);

                            $seat = $this->createSeatElement($tempId++, $solicitude->idsolicitud, CUENTA_REPARO_GOBIERNO, '', $fecha_origen, '', '', '', '', '', '', '', ASIENTO_GASTO_DEPOSITO, $documentElement->imp_serv, $marca, 'REPARO IGV MKT '. substr($comprobante->descripcion,0,1).'/' .$documentElement->num_prefijo .'-'. $documentElement->num_serie .' '.$documentElement->razon, '', 'REP');
                            array_push($seatListTemp, $seat);
                        }
                    }

                } 
            // TODOS LOS OTROS DOCUMENTOS
            }else{
                $seat = $this->createSeatElement($tempId++, $solicitude->idsolicitud, $account_number, $comprobante->cta_sunat, $fecha_origen, ASIENTO_GASTO_IVA_BASE, ASIENTO_GASTO_COD_PROV, $documentElement->razon, ASIENTO_GASTO_COD, $documentElement->ruc, $documentElement->num_prefijo, $documentElement->num_serie, ASIENTO_GASTO_BASE, $documentElement->monto, $marca, 'M TEMPLE '. $documentElement->descripcion, 1, '');
                array_push($seatListTemp, $seat);
            }
            $seatList = array_merge($seatList, $seatListTemp);
        }
        $result['seatList'] = $seatList;
        return $result;
    }
    public function viewGenerateSeatExpense($token){
        $solicitude = Solicitude::where('token', $token)->firstOrFail();
        $expense    = Expense::where('idsolicitud',$solicitude->idsolicitud)->get();
        $typeProof  = ProofType::all();
        $clientes   = array();
        $date       = $this->getDay();
        foreach($solicitude->clients as $client)
        {
            array_push($clientes,$client->client->clnombre);
        }
        $clientes     = implode(',',$clientes);
        $documentList = json_decode($expense->toJson());
        $expenseItem  = array();
        foreach ($documentList as $documentListKey => $documentElement) {
            $itemList                  = ExpenseItem::where('idgasto','=',intval($documentElement->idgasto))->get();
            $itemList                  = json_decode($itemList->toJson());
            $documentElement->itemList = $itemList;
            $documentElement->count    = count($itemList);
        }
        $solicitud               = json_decode($solicitude->toJson());
        $solicitud->documentList = $documentList;
        $resultSeats = $this->generateSeatExpenseData($solicitud);
        $seatList = $resultSeats['seatList'];

        $data = array(
            'solicitude'  => $solicitude,
            'expenseItem' => $documentList,
            'date'        => $date,
            'clientes'    => $clientes,
            'typeProof'   => $typeProof,
            'seats'       => json_decode(json_encode($seatList))
        );

        if(isset($resultSeats['error'])){
            $tempArray          = array();
            $tempArray['error'] = $resultSeats['error'];
            $data = array_merge($data, $tempArray);
        }
        //print_r($data);
        return View::make('Dmkt.Cont.SeatExpense', $data);
    }

    public function saveSeatExpense(){
        try
        {
            $result = array();
            $dataInputs  = Input::all();
            $solicitudeId = null;
            DB::beginTransaction();
            foreach ($dataInputs['seatList'] as $key => $seatItem) {
                $solicitudeId       = $seatItem['solicitudId'];
                list($day, $month, $year) = explode('/', $seatItem['fec_origen']);
                $dateTemp = mktime(11, 14, 54, $month, $day, $year);
                $fec_origen = date("Y/m/d", $dateTemp);
                $seat = new Entry;
                $seat->idasiento    = $seat->searchId()+1;
                $seat->num_cuenta   = $seatItem['numero_cuenta'];
                $seat->cc           = $seatItem['codigo_sunat'];
                $seat->fec_origen   = $fec_origen;
                $seat->iva          = $seatItem['iva'];
                $seat->cod_pro      = $seatItem['cod_prov'];
                $seat->nom_prov     = $seatItem['nombre_proveedor'];
                $seat->cod          = $seatItem['cod'];
                $seat->ruc          = $seatItem['ruc'];
                $seat->prefijo      = $seatItem['prefijo'];
                $seat->cbte_prov    = $seatItem['cbte_proveedor'];
                $seat->d_c          = $seatItem['dc'];
                $seat->importe      = $seatItem['importe'];
                $seat->leyenda_fj   = $seatItem['leyenda'];
                $seat->leyenda      = $seatItem['leyenda_variable'];
                $seat->tipo_resp    = $seatItem['tipo_responsable'];
                $seat->idsolicitud  = $solicitudeId;
                $seat->tipo_asiento = ASIENTO_GASTO_TIPO;
                //$seat->idfondo      = null;
               // dd($seat);
                $seat->save();
            }
            if($solicitudeId != null){
                Solicitude::where('idsolicitud', $solicitudeId )->update(array('estado' => ESTADO_GENERADO));
                DB::commit();
                $result['msg'] = 'Asientos Registrados';
            }else{
                $result['error'] = 'ERROR';
                $result['msg']   = 'No se pudo registrar asientos';
                //$result
            }
        }
        catch (Exception $e)
        {
            $result['error'] = 'ERROR';
            $result['msg']   = 'No se pudo registrar asientos';
            $temp = $this->internalException($e);
            DB::rollback();
        }
        return json_encode($result);
    }

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

            } 
            else 
            {
                $solicituds = Solicitude::where('estado', $estado)
                    ->whereRaw("created_at between to_date('$start' ,'DD-MM-YY') and to_date('$end' ,'DD-MM-YY')+1")
                    ->get();
            }
        } 
        else 
        {
            if ($estado != 10) {
                $solicituds = Solicitude::where('estado', $estado)
                    ->whereRaw("created_at between to_date('$firstday' ,'DD-MM-YY') and to_date('$lastday' ,'DD-MM-YY')+1")
                    ->get();
            } 
            else 
            {
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
        $typeRetention = TypeRetention::all();
        $data = [
            'solicitude' => $solicitude,
            'typeRetention' => $typeRetention
        ];

        return View::make('Dmkt.Cont.view_solicitude_cont', $data);
    }

    public function viewSeatSolicitude($token)
    {
        $solicitude = Solicitude::where('token', $token)->firstOrFail();
        $data = [
            'solicitude' => $solicitude
        ];
        return View::make('Dmkt.Cont.view_seat_solicitude', $data);
    }

    public function viewGenerateSeatSolicitude($token)
    {
        $solicitude = Solicitude::where('token', $token)->firstOrFail();
        $expense = Expense::where('idsolicitud',$solicitude->idsolicitud)->get();
        $date = $this->getDay();
        $clientes = array();
        foreach($solicitude->clients as $client)
        {
            array_push($clientes,$client->client->clnombre);
        }
        $clientes = implode(',',$clientes);
        if ($solicitude->tipo_moneda == SOLES)
        {
            $banco = Account::where('alias',BANCOS)->where('num_cuenta',CUENTA_SOLES)->first();
        }
        elseif ($solicitude->tipo_moneda == DOLARES) {
            $banco = Account::where('alias',BANCOS)->where('num_cuenta',CUENTA_DOLARES)->first();
        }
        $data = array(
            'solicitude' => $solicitude,
            'expense' => $expense,
            'date' => $date,
            'clientes' => $clientes,
            'bancos'   => $banco
        );
        return View::make('Dmkt.Cont.register_seat_solicitude', $data);
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
        try
        {
            $middleRpta = array();
            $inputs  = Input::all();
            $middleRpta = $this->validateInput($inputs,'number_account,dc,total,leyenda,idsolicitude');
            if ($middleRpta[status] == ok)
            {
                $fec_origin = Solicitude::where('idsolicitud',$inputs['idsolicitude'])->select('created_at')->get();
                DB::beginTransaction();
                for($i=0;$i<count($inputs['number_account']);$i++)
                {
                    $tbEntry = new Entry;
                    $id = $tbEntry->searchId()+1;
                    $tbEntry->idasiento = $id;
                    $tbEntry->num_cuenta = $inputs['number_account'][$i];
                    $tbEntry->fec_origen = $fec_origin[0]->created_at;
                    $tbEntry->d_c = $inputs['dc'][$i];
                    $tbEntry->importe = $inputs['total'][$i];
                    $tbEntry->leyenda = trim($inputs['leyenda']);
                    $tbEntry->idsolicitud = $inputs['idsolicitude'];
                    $tbEntry->tipo_asiento = TIPO_ASIENTO_ANTICIPO;
                    $tbEntry->updated_at = null;
                    $tbEntry->save();
                }
                Solicitude::where('idsolicitud', $inputs['idsolicitude'])->update(array('asiento' => SOLICITUD_ASIENTO));                    
                DB::commit();
                $middleRpta[status] = 1;
            }
        }
        catch (Exception $e)
        {
            $middleRpta = $this->internalException($e);
            DB::rollback();
        }
        return json_encode($middleRpta);
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
            $idtyperetention = 1;
        }
        if($inputs['ret1'])
        {
            $val_ret = intval($inputs['ret1']);
            $idtyperetention = 2;
        }
        if($inputs['ret2'])
        {
            $val_ret = intval($inputs['ret2']);
            $idtyperetention = 3;
        }

        $id = $inputs['idsolicitude'];
        $solicitude = Solicitude::where('idsolicitud', $id);
        $solicitude->retencion = $val_ret;
        if($val_ret != null)
        {
            $solicitude->idtiporetencion = $idtyperetention;    
        }
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

    /** ---------------------------  Asistente de  Gerencia  ---------------------------- **/

    public function listSolicitudeAGer(){

        //$solicituds = Solicitude::where('estado',DEPOSITADO)->where('idtipopago',2)->where('asiento',2)->get();
        $solicituds = Solicitude::where( 'idresponse', Auth::user()->id )->get();
        return View::make('Dmkt.AsisGer.list_solicitudes')->with('solicituds',$solicituds);
    }
    public function viewSolicitudeAGer($token)
    {
        $solicitude = Solicitude::where('token', $token)->firstOrFail();
        return View::make('Dmkt.AsisGer.view_solicitude_ager')->with('solicitude', $solicitude);
    }
}