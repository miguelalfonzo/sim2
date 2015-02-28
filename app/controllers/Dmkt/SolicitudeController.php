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
                $this->setStatus($solicitude->titulo .' - '. $solicitude->descripcion, '', PENDIENTE, Auth::user()->id, Auth::user()->id, $aux_idsol);
                $data = array(
                    'name' => $inputs['titulo'],
                    'description' => $inputs['description'],
                    'monto' => $inputs['monto'],
                    'money' => $inputs['money']
                );
                
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

        $inputs             = Input::all();
        $id                 = $inputs['idsolicitude'];
        $solicitude         = Solicitude::where('idsolicitud', $id);
        $oldOolicitude      = Solicitude::where('idsolicitud', $id)->first();
        $oldStatus          = $oldOolicitude->estado;
        $solicitude->estado = CANCELADO;
        $data               = $this->objectToArray($solicitude);
        $solicitude->update($data);

        $this->setStatus($oldOolicitude->titulo .' - '. $oldOolicitude->descripcion, $oldStatus, CANCELADO, Auth::user()->id, $oldOolicitude->iduser, $id);
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

        $data = array(
            'solicitude' => $solicitude,
            'managers' => $managers,
            'typePayments' => $typePayments,
            'fondos' => $fondos,
            'gerentes' => $gerentes
        );
        $responsables = $this->findResponsables($solicitude,$token);
        if (isset($responsables['Data']))
        {
            $data['responsables'] = $responsables['Data'];
        }
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
        
        $oldOolicitude      = Solicitude::where('idsolicitud', $id)->first();
        $oldStatus          = $oldOolicitude->estado;
        $idSol              = $oldOolicitude->idsolicitud;

        $solicitude = Solicitude::where('idsolicitud', $id);
        $solicitude->idsolicitud = (int)$id;
        $solicitude->observacion = $inputs['observacion'];
        $solicitude->estado = RECHAZADO;
        $solicitude->blocked = 0;
        $data = $this->objectToArray($solicitude);
        $solicitude->update($data);

        $this->setStatus($oldOolicitude->titulo .' - '. $oldOolicitude->descripcion, $oldStatus, RECHAZADO, Auth::user()->id,  $oldOolicitude->iduser, $idSol);
        return Redirect::to('show_sup')->with('state', RECHAZADO);

    }

    public function acceptedSolicitude()
    {

        $inputs = Input::all();
            
        $idSol = $inputs['idsolicitude'];
        $oldOolicitude      = Solicitude::where('idsolicitud', $idSol)->first();
        $oldStatus          = $oldOolicitude->estado;

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

        $user = User::where('id', Auth::user()->id)->first();
        $this->setStatus($oldOolicitude->titulo .' - '. $oldOolicitude->descripcion, $oldStatus, ACEPTADO, Auth::user()->id, Auth::user()->id, $idSol);
        return 'ok';
    }
   public function redirectAcceptedSolicitude(){
       return Redirect::to('show_sup')->with('state', ACEPTADO);
   }

    public function derivedSolicitude($token,$derive=0)
    {   
        $oldOolicitude      = Solicitude::where('token', $token)->first();
        $oldStatus          = $oldOolicitude->estado;
        $idSol              = $oldOolicitude->idsolicitud;

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
            $this->setStatus($oldOolicitude->titulo .' - '. $oldOolicitude->descripcion, $oldStatus, DERIVADO, Auth::user()->id, $v->marca->manager->iduser, $idSol);
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
        
        $oldOolicitude      = Solicitude::where('idsolicitud', $id)->first();
        $oldStatus          = $oldOolicitude->estado;
        $idSol              = $oldOolicitude->idsolicitud;

        $solicitude = Solicitude::where('idsolicitud', $id);
        $solicitude->estado = CANCELADO;
        $data = $this->objectToArray($solicitude);
        $solicitude->update($data);

        $this->setStatus($oldOolicitude->titulo .' - '. $oldOolicitude->descripcion, $oldStatus, CANCELADO, Auth::user()->id, $oldOolicitude->iduser, $idSol);
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
       
        if ($solicitudeBlocked->blocked == 0) {
            SolicitudeGer::where('idsolicitud', $solicitude->idsolicitud) // blockeamos la solicitud para que el otro gerente no lo pueda editar
            ->where('idgerprod', '<>', $userid)
                ->update(array('blocked' => 1));
        } 
        else 
        {
            $block = true;
        }
        $fondos = Fondo::all();
        $typePayments = TypePayment::all();
        $data = array(
            'solicitude' => $solicitude,
            'block' => $block,
            'typePayments' => $typePayments,
            'fondos' => $fondos
        );
        $responsables = $this->findResponsables($solicitude,$token);
        if (isset($responsables['Data']))
        {
            $data['responsables'] = $responsables['Data'];
        }
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

    //idkc2015 cambio de estado -> de aceptado a aprobado
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

    /**
     * idkc2015 - cambio de estado a Cancelado
     */
    public function denySolicitudeGerProd()
    {

        $inputs = Input::all();
        $id = $inputs['idsolicitude'];

        $oldOolicitude      = Solicitude::where('idsolicitud', $id)->first();
        $oldStatus          = $oldOolicitude->estado;
        $idSol              = $oldOolicitude->idsolicitud;

        $solicitude = Solicitude::where('idsolicitud', $id);
        $solicitude->idsolicitud = (int)$id;
        $solicitude->idaproved = Auth::user()->id;
        $solicitude->observacion = $inputs['observacion'];
        $solicitude->blocked = 0;
        $solicitude->estado = RECHAZADO;
        $data = $this->objectToArray($solicitude);
        $solicitude->update($data);

        $this->setStatus($oldOolicitude->titulo .' - '. $oldOolicitude->descripcion, $oldStatus, RECHAZADO, Auth::user()->id, $oldOolicitude->iduser, $idSol);
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

    /**
     * idkc2015 - solicitud aprobada
     */
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
        $fondo_aux = Fondo::where('idfondo', $sol->idfondo)->first();
        $saldo = $fondo_aux->saldo;
        $fondo = Fondo::where('idfondo', $sol->idfondo);
        $fondo->saldo = $saldo - $sol->monto;
        $data = $this->objectToArray($fondo);
        $fondo->update($data);

       return 'ok';
    }
    public function redirectApprovedSolicitude(){
        return Redirect::to('show_gercom')->with('state',APROBADO);
    }
    /**
     * idkc2015 - cambio de estado a Cancelado
     */
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
    public function createSeatElement($tempId, $cuentaMkt, $solicitudId, $fondoId, $account_number, $cod_snt, $fecha_origen, $iva, $cod_prov, $nom_prov, $cod, $ruc, $serie, $numero, $dc, $monto, $marca, $descripcion, $tipo_responsable, $type){
        $seat = array(
            'tempId'            => $tempId,             // Temporal
            'cuentaMkt'         => $cuentaMkt,          // TEMPORAL
            'solicitudId'       => intval($solicitudId),
            'fondoId'           => intval($fondoId),
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

    public function getCuentaContHandler(){
        $dataInputs  = Input::all();
        return $this->getCuentaCont($dataInputs['cuentaMkt']);
    }

    public function getCuentaCont($cuentaMkt){
        $result = array();
        if(!empty($cuentaMkt))
        {
            $accountElement = Fondo::where('cuenta_mkt', $cuentaMkt)->get();
            $account        = count($accountElement) == 0 ? array() : json_decode($accountElement->toJson());

            if(count($account) > 0){
                $result['account'] =  $account;
            }else{
                $errorTemp = array(
                    'error' => ERROR_NOT_FOUND_MATCH_ACCOUNT_MKT_CNT,
                    'msg'   => MESSAGE_NOT_FOUND_MATCH_ACCOUNT_MKT_CNT
                );
                if(!isset($result['error']) || !in_array($errorTemp, $result['error']))
                    $result['error'][] = $errorTemp;
            }

        }else{
            $result['error'] = ERROR_INVALID_ACCOUNT_MKT;
            $result['msg'] = MSG_INVALID_ACCOUNT_MKT;
        }
        return $result;
    }

    public function generateSeatExpenseData($solicitude, $fondo, $iduser){
        
        $result   = array();
        $seatList = array();

        if($solicitude){
            $advanceSeat = json_decode(Entry::where('idsolicitud', $solicitude->idsolicitud)->where('d_c', ASIENTO_GASTO_BASE)->first()->toJson());
        }else{
            $advanceSeat = json_decode(Entry::where('idfondo', $fondo->idfondo)->where('d_c', ASIENTO_GASTO_BASE)->first()->toJson());
        }

        $cuentaMkt      = $advanceSeat->num_cuenta;
        $accountResult  = $this->getCuentaCont($cuentaMkt);
        $account_number = '';
        $marcaNumber    = '';
        if(!isset($accountResult['error'])){
            $account_number = $accountResult['account'][0]->cuenta_cont;
            $marcaNumber    = $accountResult['account'][0]->marca;

        }else{
            $result['error'][] = $accountResult['error'];
        }

        if($solicitude)
        {
            $userElement = User::where('id', $solicitude->idresponse)->first();
        }else{
            $userElement = User::where('id', $iduser)->first();   
        }

        $tipo_responsable = $userElement->tipo_responsable;
        $username= '';

        $userType       = $userElement->type;
        if($userType == 'R'){
            $username .= strtoupper(substr($userElement->rm->nombres, 0, 1) .' ');
            $username .= strtoupper(explode(' ', $userElement->rm->apellidos)[0]);
        }
        elseif($userType == 'S')
        {
            $username .= strtoupper(substr($userElement->sup->nombres, 0, 1) .' ');
            $username .= strtoupper(explode(' ', $userElement->sup->apellidos)[0]);
        }
        elseif($userType == 'P'){
            $tempNameArray = explode(' ', $userElement->gerProd->descripcion);
            $username .= strtoupper(substr($tempNameArray[0], 0, 1) .' ');
            $username .= strtoupper($tempNameArray[1]);
        }else{
            $username .= strtoupper(substr($userElement->person->nombres, 0, 1) .' ');
            $username .= strtoupper(explode(' ', $userElement->person->apellidos)[0]);
        }
        
        $tempId=1;

        if($solicitude){
            $solicitude = $solicitude;
        }else{
            $solicitude = $fondo;
        }

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

                    $description_seat_item           = strtoupper($username .' '. $itemElement->cantidad .' '.$itemElement->descripcion);
                    $description_seat_igv            = strtoupper($documentElement->razon);
                    $description_seat_repair_base    = strtoupper($username .' '.$documentElement->descripcion .'-REP '. substr($comprobante->descripcion,0,1).'/' .$documentElement->num_prefijo .'-'. $documentElement->num_serie);
                    $description_seat_repair_deposit = strtoupper('REPARO IGV MKT '. substr($comprobante->descripcion,0,1).'/' .$documentElement->num_prefijo .'-'. $documentElement->num_serie .' '.$documentElement->razon);
                    
                    // ASIENTO ITEM
                    if(!$fondo){
                        $seat = $this->createSeatElement($tempId++, $cuentaMkt, $solicitude->idsolicitud, '', $account_number, $comprobante->cta_sunat, $fecha_origen, ASIENTO_GASTO_IVA_BASE, ASIENTO_GASTO_COD_PROV_IGV, $documentElement->razon, ASIENTO_GASTO_COD_IGV, $documentElement->ruc, $documentElement->num_prefijo, $documentElement->num_serie, ASIENTO_GASTO_BASE, $itemElement->importe, $marca, $description_seat_item, $tipo_responsable, '');
                    }else{
                        $seat = $this->createSeatElement($tempId++, $cuentaMkt, '', $solicitude->idfondo, $account_number, $comprobante->cta_sunat, $fecha_origen, ASIENTO_GASTO_IVA_BASE, ASIENTO_GASTO_COD_PROV_IGV, $documentElement->razon, ASIENTO_GASTO_COD_IGV, $documentElement->ruc, $documentElement->num_prefijo, $documentElement->num_serie, ASIENTO_GASTO_BASE, $itemElement->importe, $marca, $description_seat_item, $tipo_responsable, '');
                    }
                    
                    $total_neto += $itemElement->importe;
                    array_push($seatListTemp, $seat);
                    if($itemLength == $itemKey){

                        // ASIENTO IGV
                        if(!$fondo){
                            $seat = $this->createSeatElement($tempId++, $cuentaMkt, $solicitude->idsolicitud, '', $account_number, $comprobante->cta_sunat, $fecha_origen, ASIENTO_GASTO_IVA_IGV, ASIENTO_GASTO_COD_PROV_IGV, $documentElement->razon, ASIENTO_GASTO_COD_IGV, $documentElement->ruc, $documentElement->num_prefijo, $documentElement->num_serie, ASIENTO_GASTO_BASE, $documentElement->igv, $marca, $description_seat_igv, $tipo_responsable, 'IGV');
                        }else{
                            $seat = $this->createSeatElement($tempId++, $cuentaMkt, '', $solicitude->idfondo, $account_number, $comprobante->cta_sunat, $fecha_origen, ASIENTO_GASTO_IVA_IGV, ASIENTO_GASTO_COD_PROV_IGV, $documentElement->razon, ASIENTO_GASTO_COD_IGV, $documentElement->ruc, $documentElement->num_prefijo, $documentElement->num_serie, ASIENTO_GASTO_BASE, $documentElement->igv, $marca, $description_seat_igv, $tipo_responsable, 'IGV');
                        }
                        
                        array_push($seatListTemp, $seat);

                        // ASIENTO IMPUESTO SERVICIO
                        if(!($documentElement->imp_serv == null || $documentElement->imp_serv == 0 || $documentElement->imp_serv == '')){
                            $porcentaje = $total_neto/$documentElement->imp_serv;
                            $description_seat_tax_service    = strtoupper('SERVICIO '. $porcentaje .'% '. $documentElement->descripcion);
                            if(!$fondo){
                                $seat = $this->createSeatElement($tempId++, $cuentaMkt, $solicitude->idsolicitud, '', $account_number, '', $fecha_origen, '', '', '', '', '', '', '', ASIENTO_GASTO_BASE, $documentElement->imp_serv, $marca, $description_seat_tax_service, '', 'SER');
                            }else{
                                $seat = $this->createSeatElement($tempId++, $cuentaMkt, '', $solicitude->idfondo, $account_number, '', $fecha_origen, '', '', '', '', '', '', '', ASIENTO_GASTO_BASE, $documentElement->imp_serv, $marca, $description_seat_tax_service, '', 'SER');
                            }
                            
                            array_push($seatListTemp, $seat);
                        }

                        // ASIENTO REPARO
                        if($documentElement->reparo == '1'){
                            if(!$fondo){
                                $seat = $this->createSeatElement($tempId++, $cuentaMkt, $solicitude->idsolicitud, '', CUENTA_REPARO_COMPRAS, '', $fecha_origen, '', '', '', '', '', '', '', ASIENTO_GASTO_BASE, $documentElement->igv, $marca, $description_seat_repair_base, '', 'REP');
                            }else{
                                $seat = $this->createSeatElement($tempId++, $cuentaMkt, '', $solicitude->idfondo, CUENTA_REPARO_COMPRAS, '', $fecha_origen, '', '', '', '', '', '', '', ASIENTO_GASTO_BASE, $documentElement->igv, $marca, $description_seat_repair_base, '', 'REP');
                            }
                            
                            array_push($seatListTemp, $seat);

                            if(!$fondo){
                                $seat = $this->createSeatElement($tempId++, $cuentaMkt, $solicitude->idsolicitud, '', CUENTA_REPARO_GOBIERNO, '', $fecha_origen, '', '', '', '', '', '', '', ASIENTO_GASTO_DEPOSITO, $documentElement->igv, $marca, $description_seat_repair_deposit, '', 'REP');
                            }else{
                                $seat = $this->createSeatElement($tempId++, $cuentaMkt, '', $solicitude->idfondo, CUENTA_REPARO_GOBIERNO, '', $fecha_origen, '', '', '', '', '', '', '', ASIENTO_GASTO_DEPOSITO, $documentElement->igv, $marca, $description_seat_repair_deposit, '', 'REP');
                            }
                            array_push($seatListTemp, $seat);
                        }
                    }

                } 
            // TODOS LOS OTROS DOCUMENTOS
            }else{
                // ASIENTO DOCUMENT - NO ITEM
                if(!$fondo){
                    $description_seat_other_doc = strtoupper($username.' '. $documentElement->descripcion);
                    $seat = $this->createSeatElement($tempId++, $cuentaMkt, $solicitude->idsolicitud, '', $account_number, $comprobante->cta_sunat, $fecha_origen, ASIENTO_GASTO_IVA_BASE, ASIENTO_GASTO_COD_PROV, $documentElement->razon, ASIENTO_GASTO_COD, $documentElement->ruc, $documentElement->num_prefijo, $documentElement->num_serie, ASIENTO_GASTO_BASE, $documentElement->monto, $marca, $description_seat_other_doc, $tipo_responsable, '');
                    array_push($seatListTemp, $seat);
                }else{
                    $description_seat_other_doc = strtoupper($username.' '. $documentElement->descripcion);
                    $seat = $this->createSeatElement($tempId++, $cuentaMkt, '', $solicitude->idfondo, $account_number, $comprobante->cta_sunat, $fecha_origen, ASIENTO_GASTO_IVA_BASE, ASIENTO_GASTO_COD_PROV, $documentElement->razon, ASIENTO_GASTO_COD, $documentElement->ruc, $documentElement->num_prefijo, $documentElement->num_serie, ASIENTO_GASTO_BASE, $documentElement->monto, $marca, $description_seat_other_doc, $tipo_responsable, '');
                    array_push($seatListTemp, $seat);
                }
            }
            $seatList = array_merge($seatList, $seatListTemp);
        }
        
        // CONTRAPARTE ASIENTO DE ANTICIPO
        if(!$fondo){
            $description_seat_back = strtoupper($username .' '. $solicitude->titulo);
            $seat = $this->createSeatElement($tempId++, $cuentaMkt, $solicitude->idsolicitud, '', $account_number, '', $fecha_origen, '', '', '', '', '', '', '', ASIENTO_GASTO_DEPOSITO, $advanceSeat->importe, '', $description_seat_back, '', 'CAN');
        }else{
            $description_seat_back = strtoupper($username .' '. $solicitude->institucion);
            $seat = $this->createSeatElement($tempId++, $cuentaMkt, '', $solicitude->idfondo, $account_number, '', $fecha_origen, '', '', '', '', '', '', '', ASIENTO_GASTO_DEPOSITO, $advanceSeat->importe, '', $description_seat_back, '', 'CAN');
        }
        
        array_push($seatList, $seat);

        $result['seatList'] = $seatList;
        return $result;
    }

    public function viewGenerateSeatExpense($token){
        $solicitude = Solicitude::where('token', $token)->first();
        $type = EXPENSE_SOLICITUDE;
        
        if(count($solicitude) == 0)
        {
            $fondo   = FondoInstitucional::where('token', $token)->first();
            $type    = EXPENSE_FONDO;
            $expense = Expense::where('idfondo',$fondo->idfondo)->where('tipo', EXPENSE_FONDO)->get();
        }
        if($type == EXPENSE_SOLICITUDE){
            $expense = Expense::where('idsolicitud',$solicitude->idsolicitud)->where('tipo', EXPENSE_SOLICITUDE)->get();
            $clientes   = array();
            foreach($solicitude->clients as $client)
            {
                array_push($clientes,$client->client->clnombre);
            }
            $clientes = implode(',',$clientes);
        }
        
        $typeProof  = ProofType::all();
        $date       = $this->getDay();
        $documentList = json_decode($expense->toJson());

        $expenseItem  = array();
        foreach ($documentList as $documentListKey => $documentElement) {
            $itemList                  = ExpenseItem::where('idgasto','=',intval($documentElement->idgasto))->get();
            $itemList                  = json_decode($itemList->toJson());
            $documentElement->itemList = $itemList;
            $documentElement->count    = count($itemList);
        }

        if($type == EXPENSE_SOLICITUDE){
            $solicitud               = json_decode($solicitude->toJson());
            $solicitud->documentList = $documentList;
            $resultSeats             = $this->generateSeatExpenseData($solicitud, null, null);
            $seatList                = $resultSeats['seatList'];

            $data = array(
                'solicitude'  => $solicitude,
                'expenseItem' => $documentList,
                'date'        => $date,
                'clientes'    => $clientes,
                'typeProof'   => $typeProof,
                'seats'       => json_decode(json_encode($seatList))
            );
        }
        else{
            $iduser              = $fondo->iduser($fondo->idrm);
            $fondoaux            = $fondo;
            $fondo               = json_decode($fondo->toJson());
            $fondo->documentList = $documentList;
            $resultSeats         = $this->generateSeatExpenseData(null, $fondo, $iduser);
            $seatList            = $resultSeats['seatList'];

            $data = array(
                'fondo'       => $fondoaux,
                'expenseItem' => $documentList,
                'date'        => $date,
                'typeProof'   => $typeProof,
                'seats'       => json_decode(json_encode($seatList))
            );
        }

        if(isset($resultSeats['error'])){
            $tempArray          = array();
            $tempArray['error'] = $resultSeats['error'];
            $data = array_merge($data, $tempArray);
        }
        //print_r($data);
        if(!isset($fondo)){
            return View::make('Dmkt.Cont.SeatExpense', $data);
        }else{
            return View::make('Dmkt.Cont.SeatExpenseFondo', $data);
        }
        
    }

    /**
     * idkc2015 - cambio de estado a generado
     */
    public function saveSeatExpense(){
        try
        {
            $result = array();
            $dataInputs  = Input::all();
            $solicitudeId = null;
            $fondoId = null;
            DB::beginTransaction();
            foreach ($dataInputs['seatList'] as $key => $seatItem) {
                
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
                if(isset($seatItem['solicitudeId'])){
                    $solicitudeId       = $seatItem['solicitudId'];
                    $seat->idsolicitud  = $solicitudeId;
                }else{
                    $fondoId        = $seatItem['fondoId'];
                    $seat->idfondo  = $fondoId;
                }
                $seat->tipo_asiento = ASIENTO_GASTO_TIPO;
                //$seat->idfondo      = null;
                $seat->save();
            }
            if($solicitudeId != null){
                Solicitude::where('idsolicitud', $solicitudeId )->update(array('estado' => ESTADO_GENERADO));
                DB::commit();
                $result['msg'] = 'Asientos Registrados';
            }else if($fondoId != null){
                FondoInstitucional::where('idfondo', $fondoId)->update(array('asiento' => ASIENTO_FONDO_GASTO));
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

    /**
     * idkc2015 - gasto habilitado
     */
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

    /**
     * idkc2015 - cambio a generado
     */
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

    /**
     * idkc2015 cambio de estado - de aprobado a deposito habilitado
     */
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


    private function findResponsables($solicitude,$token)
    {
        try
        {
            $rpta = array();
            if ($solicitude->estado == APROBADO && $solicitude->asiento == 1 && Auth::user()->id == $solicitude->idaproved && !isset($solicitude->idresponse))
            {
                $responsables = array();
                $asistentes = User::where('type','AG')->get();
                foreach ($asistentes as $asistente)
                {
                    array_push($responsables, $asistente->person);
                }
                if($solicitude->user->type == 'R')
                {
                    array_push( $responsables, Solicitude::where('token', $token)->select('iduser')->first()->rm );
                }
                elseif($solicitude->user->type == 'S')
                {
                    $rms = Solicitude::where('token', $token)->select('idaproved')->first()->aprovedSup->Reps;
                    Log::error(json_encode($rms));
                    foreach ( $rms as $rm )
                    {
                         array_push( $responsables, $rm );
                    }
                }
                $rpta['Data'] = $responsables;
            }
        }
        catch (Exception $e)
        {
            $rpta = $this->internalException($e);
        }
        return $rpta;
    }

    /**
     * idkc2015 - habilitar responsable - responsable habilitado
     */
    public function asignarResponsableSolicitud()
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

}