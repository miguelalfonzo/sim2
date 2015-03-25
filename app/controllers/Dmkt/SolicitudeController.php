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
use \Common\StateRange;
use \Dmkt\Label;

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
    public function show_user()
    {
        if (Session::has('state'))
            $state = Session::pull('state');
        else
        {
            if (Auth::user()->type == GER_COM || Auth::user()->type == CONT)
                $state = R_APROBADO;
            else if ( Auth::user()->type == REP_MED || Auth::user()->type == SUP || Auth::user()->type == GER_PROD )
                $state = R_PENDIENTE;
            elseif ( Auth::user()->type == TESORERIA )
                $state = R_REVISADO;
            elseif ( Auth::user()->type == ASIS_GER )
                $state = R_TODOS;
        }
        $mWarning = array();
        if (Session::has('warnings'))
        {
            $warnings = Session::pull('warnings');
            $mWarning[status] = ok ;
            if (!is_null($warnings))
                foreach ($warnings as $key => $warning)
                     $mWarning[data] = $warning[0].' ';
            $mWarning[data] = substr($mWarning[data],0,-1);
        }
        $data = array(
            'state'  => $state,
            'states' => StateRange::order(),
            'warnings' => $mWarning
        );
        return View::make('template.show_user',$data);   
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
        $label = Label::all();
        $data = array(
            'etiquetas'       => $label,
            'families'       => $families,
            'fondos'         => $fondos,
            'typesolicituds' => $typesolicituds,
            'typePayments'   => $typePayments
        );
        return View::make('Dmkt.Rm.register_solicitude', $data);
    }

    // IDKC: CHANGE STATUS => PENDIENTE
    public function registerSolicitude()
    {
        try
        {
            DB::beginTransaction();
            $inputs = Input::all();
            $image = Input::file('file');
            foreach ($inputs['clients'] as $client)
            {
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
                $solicitude->idsolicitud       = $aux_idsol;
                $solicitude->idetiqueta        = $inputs['etiqueta'];
                $solicitude->descripcion       = $inputs['description'];
                $solicitude->titulo            = $inputs['titulo'];
                $solicitude->monto             = $inputs['monto'];
                $solicitude->iduser            = Auth::user()->id;
                $solicitude->monto_factura     = $inputs['amount_fac'];
                $solicitude->fecha_entrega     = $date;
                $solicitude->idtiposolicitud   = $inputs['type_solicitude'];
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
                    $clients = explode(',',$inputs['clients'][0]);
                    $tables = explode(',',$inputs['tables'][0]);
                    for ($i=0;$i< count($clients);$i++) 
                    {
                        $solicitude_clients = new SolicitudeClient;
                        $solicitude_clients->idsolicitud_clientes = $solicitude_clients->searchId() + 1;
                        $solicitude_clients->idsolicitud = $solicitude->searchId();
                        $solicitude_clients->idcliente = $clients[$i];
                        $solicitude_clients->from_table = $tables[$i];
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
                    $userRm     = User::where('id', Auth::user()->id)->first();
                    $toUserId;
                    if($userRm->type == 'R')
                    {
                        $sup    = Sup::where('idsup', $userRm->rm->idsup)->first();
                        $toUserId = $sup->iduser;
                    }
                    elseif($userRm->type == 'S')
                    {
                        $toUserId    = Auth::user()->id;
                    }
                    $rpta = $this->setStatus($solicitude->titulo .' - '. $solicitude->descripcion, '', PENDIENTE, Auth::user()->id, $toUserId, $aux_idsol);
                    if ($rpta[status] == ok)
                    {
                        $rpta = $this->setRpta($typeUser);
                        DB::commit();
                    }
                }
            }
        }
        catch (Exception $e)
        {
            DB::rollback();
            $rpta = $this->internalException($e,__FUNCTION__);
        }
        return $rpta;
    }

    public function formEditSolicitude()
    {
        try
        {
            DB::beginTransaction();
            $inputs = Input::all();
            $date = $inputs['delivery_date'];
            list($d, $m, $y) = explode('/', $date);
            $d = mktime(11, 14, 54, $m, $d, $y);
            $date = date("Y/m/d", $d);
            $id = $inputs['idsolicitude'];
            $sol = Solicitude::find($id);
            $solicitude = Solicitude::where('idsolicitud', $id);
            $image = Input::file('file');
            
            if (isset($image)) 
            {
                $path = public_path('img/reembolso/' . $sol->image);
                @unlink($path);
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                $path = public_path('img/reembolso/' . $filename);
                Image::make($image->getRealPath())->resize(800, 600)->save($path);
                $solicitude->image = $filename;
            }
            $solicitude->idetiqueta        = $inputs['etiqueta'];
            $solicitude->descripcion    =  $inputs['description'];
            $solicitude->titulo         =  $inputs['titulo'];
            $solicitude->monto          =  $inputs['monto'];
            $solicitude->estado         =  PENDIENTE;
            $solicitude->fecha_entrega  =  $date;
            $solicitude->monto_factura  =  $inputs['amount_fac'];
            $solicitude->tipo_moneda    =  $inputs['money'];
            $typeSolicitude             =  $inputs['type_solicitude'];

            if ($sol->idtiposolicitud == 2 && ($typeSolicitude == 1 || $typeSolicitude == 3)) 
            {
                $path = public_path('img/reembolso/' . $sol->image);
                @unlink($path);
                $solicitude->monto_factura = null;
            }
            $solicitude->idtiposolicitud = $typeSolicitude;
            $typePayment = $inputs['type_payment'];
            if ($typePayment == 1) 
            {
                $solicitude->numruc = null;
                $solicitude->numcuenta = null;
            } 
            else if ($typePayment == 2) 
            {
                $solicitude->numruc = $inputs['ruc'];
                $solicitude->numcuenta = null;
            } 
            else if ($typePayment == 3) 
            {
                $solicitude->numcuenta = $inputs['number_account'];
                $solicitude->numruc = null;
            }

            $solicitude->idtipopago = $inputs['type_payment'];
            
            if(isset($inputs['sub_type_activity']))
            {
                $fondo =$inputs['sub_type_activity'];
                $solicitude->idfondo = $fondo;
            }
            $data = $this->objectToArray($solicitude);
            $solicitude->update($data);
            SolicitudeClient::where('idsolicitud', '=', $id)->delete();
            SolicitudeFamily::where('idsolicitud', '=', $id)->delete();
            $clients = $inputs['clients'];
            $tables = $inputs['tables'];
            $clients = explode(',',$inputs['clients'][0]);
            $tables = explode(',',$inputs['tables'][0]);
             
            for ($i=0;$i< count($clients);$i++) 
            {        
                $solicitude_clients = new SolicitudeClient;
                $solicitude_clients->idsolicitud_clientes = $solicitude_clients->searchId() + 1;
                $solicitude_clients->idsolicitud = $id;
                $solicitude_clients->idcliente = $clients[$i];
                $solicitude_clients->from_table = $tables[$i];
                $solicitude_clients->save();
            }
            $families = $inputs['families'];
            foreach ($families as $family) 
            {
                $solicitude_families = new SolicitudeFamily;
                $solicitude_families->idsolicitud_familia = $solicitude_families->searchId() + 1;
                $solicitude_families->idsolicitud = $id;
                $solicitude_families->idfamilia = $family;
                $solicitude_families->save();
            }
            $typeUser = Auth::user()->type;
            $iduser_to = 0;
            if ($typeUser == REP_MED)
            {
                $toUserId = Rm::where('iduser',Auth::user()->id)->first();
                $toUserId = $toUserId->rmSup->iduser;
            }
            elseif ($typeUser == SUP)
            {
                $toUserId = Auth::user()->id;
            }

            $rpta = $this->setStatus($inputs['titulo'].' - '. $inputs['description'], '', PENDIENTE, Auth::user()->id, $toUserId, $inputs['idsolicitude']);
            if ($rpta[status] = ok)
            {         
                $rpta = $this->setRpta($typeUser);
                DB::commit();
            }
        }
        catch(Exception $e)
        {
            DB::rollback();
            $rpta = $this->internalException($e,__FUNCTION__);
        }
        return $rpta;
    }

    public function searchSolicituds()
    {
        try
        {
            $inputs = Input::all();
            $today = getdate();
            $m = $today['mday'] . '-' . $today['mon'] . '-' . $today['year'];      
            if (Input::has('idstate'))
                $estado = $inputs['idstate'];
            else
                $estado = R_TODOS;
            if (Input::has('date_start'))
                $start = $inputs['date_start'];
            else
                $start = date('01-m-Y', strtotime($m));
            if (Input::has('date_end'))
                $end = $inputs['date_end'];
            else
                $end = date('t-m-Y', strtotime($m));
            $rpta = $this->userType();
            if ($rpta[status] == ok)
                $rpta = $this->searchTransaction($estado,$rpta[data],$start,$end);
                if ($rpta[status] == ok)
                    $view = View::make('template.solicituds')->with($rpta[data])->render();
                    $rpta = $this->setRpta($view);
        }
        catch (Exception $e)
        {
            $rpta = $this->internalException($e,__FUNCTION__);
        }
        return $rpta;
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
        $etiqueta = Label::all();
        $fondos = Fondo::all();
        $data = array(
            'etiquetas'        => $etiqueta,
            'solicitude'       => $solicitude,
            'clients'          => $clients,
            'families'         => $families,
            'families2'        => $families2,
            'typesolicituds'   => $typesolicituds,
            'fondos'           => $fondos,
            'typePayments'     => $typePayments
        );
        return View::make('Dmkt.Rm.register_solicitude', $data);
    }

    // IDKC: CHANGE STATUS => CANCELADO
    public function cancelSolicitude()
    {
        try
        {
            DB::beginTransaction();
            $inputs             = Input::all();
            $id                 = $inputs['idsolicitude'];
            $solicitude         = Solicitude::where('idsolicitud', $id);
            $oldOolicitude      = Solicitude::where('idsolicitud', $id)->first();
            $oldStatus          = $oldOolicitude->estado;
            $solicitude->estado = CANCELADO;
            $solicitude->observacion = $inputs['observacion'];
            $data               = $this->objectToArray($solicitude);
            $solicitude->update($data);
            $rpta = $this->setStatus($oldOolicitude->titulo .' - '. $oldOolicitude->descripcion, $oldStatus, CANCELADO, Auth::user()->id, $oldOolicitude->iduser, $id);
            if ( $rpta[status] == ok)
            {
                DB::commit();
            }
        }
        catch (Exception $e)
        {
            DB::rollback();
            $rpta = $this->internalException($e,__FUNCTION__);
        }
        Session::put('state',R_NO_AUTORIZADO);
        return $rpta;
    }

    public function subtypeactivity($id)
    {
        $subtypeactivities = Fondo::where('idtipoactividad', $id)->get();
        return json_encode($subtypeactivities);
    }

    public function viewSolicitudeSup($token)
    {
        $sol = Solicitude::where('token', $token)->firstOrFail();
        if ($sol->user->type == REP_MED && $sol->estado == PENDIENTE)
            Solicitude::where('token', $token)->update(array('blocked' => 1));
        $solicitude = Solicitude::where('token', $token)->firstOrFail();
        $fondos = Fondo::all();
        $gerentes = array();
        foreach($solicitude->families as $v)
        {
            $manager_temp = $v->marca->manager;
            if (!in_array($manager_temp,$gerentes))
                $gerentes[] = $manager_temp;    
        }

        $data = array(
            'solicitude' => $solicitude,
            'fondos' => $fondos,
            'gerentes' => $gerentes
        );
        $responsables = $this->findResponsables($solicitude,$token);
        if (isset($responsables[data]))
            $data['responsables'] = $responsables[data];
        return View::make('Dmkt.Sup.view_solicitude_sup', $data);
    }

    public function viewSolicitudeGerProd($token)
    {

        $solicitude = Solicitude::where('token', $token)->firstOrFail();
        $userid = Auth::user()->gerprod->id;
        $block = false;
        if($solicitude->estado == DERIVADO)
            Solicitude::where('token', $token)->update(array('blocked' => 1));
        $solicitudeBlocked = SolicitudeGer::where('idsolicitud', $solicitude->idsolicitud)->where('idgerprod', $userid)->firstOrFail(); //vemos si la solicitud esta blokeada
       
        if ($solicitudeBlocked->blocked == 0) 
        {
            SolicitudeGer::where('idsolicitud', $solicitude->idsolicitud) // blockeamos la solicitud para que el otro gerente no lo pueda editar
            ->where('idgerprod', '<>', $userid)
                ->update(array('blocked' => 1));
        } 
        else 
            $block = true;
        $fondos = Fondo::all();
        $data = array(
            'solicitude' => $solicitude,
            'block' => $block,
            'fondos' => $fondos
        );
        $responsables = $this->findResponsables($solicitude,$token);
        Log::error($responsables);
        if (isset($responsables[data]))
            $data['responsables'] = $responsables[data];
        return View::make('Dmkt.GerProd.view_solicitude_gerprod', $data);
    }

    public function viewSolicitudeGerCom($token)
    {
        $solicitude = Solicitude::where('token', $token)->first();
        $sol = Solicitude::where('token', $token);
        $sol->blocked = 1;
        $data = $this->objectToArray($sol);
        $sol->update($data);
        $info = array();
        if ($solicitude->estado == APROBADO && $solicitude->asiento == 1)
        {
            $resp = array();
            $asistentes = User::where('type','AG')->get();
            foreach ($asistentes as $asistente)
                array_push($resp, $asistente->person);
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
            $info = array('solicitude' => $solicitude);
        $info[status] = ok;
        return View::make('Dmkt.GerCom.view_solicitude_gercom',$info);
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

            if ($typeUser == SUP)
                return SUP;
        }
    }

    // IDKC: CHANGE STATUS => RECHAZADO
    public function denySolicitude()
    {
        try
        {
            DB::beginTransaction();
            $user = Auth::user();
            $inputs = Input::all();
            $rules = array( 'observacion' => 'required|min:1' );
            $validator = Validator::make($inputs, $rules);
            if ($validator->fails()) 
            {
                $messages = $validator->errors()->getMessages();
                return Redirect::to('show_user')->with('warnings',$messages);
            }
            else
            {
                $id = $inputs['idsolicitude'];
                $oldOolicitude      = Solicitude::where('idsolicitud', $id)->first();
                $oldStatus          = $oldOolicitude->estado;
                $solicitude = Solicitude::where('idsolicitud', $id);
                $solicitude->idsolicitud = (int)$id;
                $solicitude->observacion = $inputs['observacion'];
                $solicitude->estado = RECHAZADO;
                $solicitude->blocked = 0;
                $data = $this->objectToArray($solicitude);
                $solicitude->update($data);
                $rpta = $this->setStatus($oldOolicitude->titulo .' - '. $oldOolicitude->descripcion, $oldStatus, RECHAZADO, $user->id,  $oldOolicitude->iduser, $id);
                if ( $rpta[status] == ok )
                    DB::commit();
            }
        }
        catch (Exception $e)
        {
            DB::rollback();
            $rpta = $this->internalException($e,__FUNCTION__);
        }
        if ($rpta[status] == ok)
            return Redirect::to('show_user')->with('state',R_NO_AUTORIZADO);
    }

    // IDKC: CHANGE STATUS => RECHAZADO
    /*public function denySolicitudeGerCom()
    {
        try
        {
            DB::beginTransaction();   
            $inputs = Input::all();
            $id = $inputs['idsolicitude'];
            $oldOolicitude      = Solicitude::where('idsolicitud', $id)->first();
            $oldStatus          = $oldOolicitude->estado;
            $idSol              = $oldOolicitude->idsolicitud;
            $solicitude = Solicitude::where('idsolicitud', $id);
            $solicitude->idsolicitud = (int)$id;
            $solicitude->idaproved = Auth::user()->id;
            $solicitude->observacion = $inputs['observacion'];
            $solicitude->estado = RECHAZADO;
            $solicitude->blocked = 0;
            $data = $this->objectToArray($solicitude);
            $solicitude->update($data);
            $rpta = $this->setStatus($oldOolicitude->titulo .' - '. $oldOolicitude->descripcion, $oldStatus, RECHAZADO, Auth::user()->id, $oldOolicitude->iduser, $idSol);
            if ( $rpta[status] == ok )
            {
                DB::commit();
            }
        }
        catch (Exception $e)
        {
            DB::rollback();
            $rpta = $this->internalException($e,__FUNCTION__);
        }
        if ($rpta[status] == ok)
            return Redirect::to('show_user')->with('state', R_NO_AUTORIZADO);
        else
            return $rpta;
    }*/


    // IDKC: CHANGE STATUS => ACEPTADO
    

   public function redirectAcceptedSolicitude()
   {
       return Redirect::to('show_user')->with('state', R_APROBADO);
   }

    // IDKC: CHANGE STATUS => PENDIENTE DERIVADO
    public function derivedSolicitude($token,$derive=0)
    {   
        try
        {
            DB::beginTransaction();
            $oldOolicitude      = Solicitude::where('token', $token)->first();
            $oldStatus          = $oldOolicitude->estado;
            $idSol              = $oldOolicitude->idsolicitud;

            Solicitude::where('token', $token)->update(array('derived' => 1 , 'blocked' => 0 , 'estado' => DERIVADO));
            $solicitude = Solicitude::where('token', $token)->firstOrFail();
            $id = $solicitude->idsolicitud;
            $sol = Solicitude::find($id);
            $gerentes = array();
            foreach ($sol->families as $v) 
            {
                $manager_temp = $v->marca->manager;
                if (!in_array($manager_temp,$gerentes))
                {
                    $gerentes[] = $manager_temp;
                    $solGer = new SolicitudeGer;
                    $solGer->idsolicitud_gerente = $solGer->searchId() + 1;
                    $solGer->idsolicitud = $id;
                    $solGer->idgerprod = $v->marca->manager->id;
                    $solGer->save();
                }
                $rpta = $this->setStatus($oldOolicitude->titulo .' - '. $oldOolicitude->descripcion, $oldStatus, DERIVADO, Auth::user()->id, $v->marca->manager->iduser, $idSol);
                if ( $rpta[status] == ok )
                {
                    DB::commit();
                }
            }
        }
        catch (Exception $e)
        {
            DB::rollback();
            $rpta = $this->internalException($e,__FUNCTION__);
        }
        return Redirect::to('show_user');
    }

    public function disBlockSolicitudeSup($token)
    {
        $solicitude = Solicitude::where('token', $token)->firstOrFail();
        Solicitude::where('idsolicitud', $solicitude->idsolicitud) // desbloqueamos la solicitud para que el rm lo pueda editar
        ->update(array('blocked' => 0));
        return Redirect::to('show_user');
    }

    public function disBlockSolicitudeGerProd($token)
    {
        //Desbloquenado La solicitud al presionar el boton Cancelar
        $solicitude = Solicitude::where('token', $token)->firstOrFail();
        $solicitude->blocked = 0 ;
        $solicitude->save();
        SolicitudeGer::where('idsolicitud', $solicitude->idsolicitud) // desblockeamos la solicitud para que el otro gerente no lo pueda editar
        ->update(array('blocked' => 0));
        return Redirect::to('show_user');
    }

    public function acceptedSolicitude()
    {
        try
        {
            DB::beginTransaction();
            $inputs = Input::all();      
            $idSol = $inputs['idsolicitude'];
            $oldOolicitude      = Solicitude::where('idsolicitud', $idSol)->first();
            $oldStatus          = $oldOolicitude->estado;
            $solicitude = Solicitude::where('idsolicitud', $idSol);
            $solicitude->estado = ACEPTADO;
            $solicitude->idresponse = $inputs['responsable'];
            $solicitude->idaproved = Auth::user()->id;
            $solicitude->monto = $inputs['monto'];
            $solicitude->idfondo = $inputs['sub_type_activity'];
            $solicitude->observacion = $inputs['observacion'];
            $solicitude->blocked = 0;
            $data = $this->objectToArray($solicitude);
            $solicitude->update($data);
            $rpta = $this->setStatus($oldOolicitude->titulo .' - '. $oldOolicitude->descripcion, $oldStatus, ACEPTADO, Auth::user()->id, USER_GERENTE_COMERCIAL, $idSol);
            if ( $rpta[status] == ok )
            {
                $amount_assigned = $inputs['amount_assigned'];
                $families = SolicitudeFamily::where('idsolicitud', $idSol)->get();
                $i = 0;
                foreach ($families as $fam) 
                {
                    $family = SolicitudeFamily::where('idsolicitud_familia', $fam->idsolicitud_familia);
                    $family->monto_asignado = $amount_assigned[$i];
                    $data = $this->objectToArray($family);
                    $family->update($data);
                    $i++;
                }
                $rpta = $this->setRpta(R_APROBADO);
                DB::commit();
            }
            else
                DB::rollback();
        }
        catch (Exception $e)
        {
            DB::rollback();
            $rpta = $this->internalException($e,__FUNCTION__);
        }
        return $rpta;
    }

    /*public function redirectAcceptedSolicitudeGerProd(){
        return Redirect::to('show_user')->with('state', ACEPTADO);
    }*/

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
            $middleRpta = $this->internalException($e,__FUNCTION__);
        }
        return $middleRpta;
    } 

    public function massApprovedSolicitudes()
    {
        try
        {
            $inputs = Input::all();
            $rules = array(
                'sols' => 'required'
            );
            $validator = Validator::make($inputs, $rules);
            if ($validator->fails()) 
                $rpta = $validator->messages();
            else
            {
                $status = array( ok => array() , error => array() );
                foreach($inputs['sols'] as $solicitude)
                {
                    $rpta = $this->approvedTransaction($solicitude);
                    if ( $rpta[status] != ok )
                        $status[error][] = $solicitude['token'];
                    else
                        $status[ok][] = $solicitude['token'];
                }
                if (empty($status[error]))
                    $rpta = array( status => ok , description => $status);
                else if (empty($status[ok]))
                    $rpta = array( status => danger , description => $status);
                else
                    $rpta = array( status => warning , description => $status);
            }
        }
        catch (Exception $e)
        {
            $rpta = $this->internalException($e,__FUNCTION__);
        }
        return $rpta;
    }

    // IDKC: CHANGE STATUS => APROBADA
    public function approvedSolicitude()
    {
        try
        {
            $inputs = Input::all();
            $rules = array(
                'token'             => 'required',
                'monto'             => 'required|numeric',
                'amount_assigned'   => 'required'
            );
            $validator = Validator::make($inputs, $rules);
            if ($validator->fails()) 
                $rpta = $validator->messages();
            else
                $rpta = $this->approvedTransaction($inputs);
        }
        catch(Exception $e)
        {
            $rpta = $this->internalException($e,__FUNCTION__);
        }  
        return $rpta;        
    }

    private function approvedTransaction($inputs)
    {
        try
        {
            DB::beginTransaction();
            $token = $inputs['token'];
            $oldOolicitude      = Solicitude::where('token', $token)->first();
            $oldStatus          = $oldOolicitude->estado;
            $sol = Solicitude::where('token', $token)->first();
            $idSol = $sol->idsolicitud;
            $solicitude = Solicitude::where('token', $token);
            $solicitude->estado = APROBADO;
            $solicitude->blocked = 0;
            if (isset($inputs['monto']))
            {
                $solicitude->monto = $inputs['monto'];
            }
            $data = $this->objectToArray($solicitude);
            $solicitude->update($data);
            if (isset($inputs['amount_assigned']))
            {
                $amount_assigned = $inputs['amount_assigned'];
                $families = SolicitudeFamily::where('idsolicitud', $idSol)->get();
                $i = 0;
                foreach ($families as $fam) 
                {
                    $family = SolicitudeFamily::where('idsolicitud_familia', $fam->idsolicitud_familia);
                    $family->monto_asignado = $amount_assigned[$i];
                    $data = $this->objectToArray($family);
                    $family->update($data);
                    $i++;
                }
            }
            $fondo_aux = Fondo::where('idfondo', $sol->idfondo)->first();
            $saldo = $fondo_aux->saldo;
            $fondo = Fondo::where('idfondo', $sol->idfondo);
            $fondo->saldo = $saldo - $sol->monto;
            $data = $this->objectToArray($fondo);
            $fondo->update($data);
            $rpta = $this->setStatus($oldOolicitude->titulo .' - '. $oldOolicitude->descripcion, $oldStatus, APROBADO, Auth::user()->id, USER_CONTABILIDAD, $idSol);
            if ( $rpta[status] == ok )
                DB::commit();
            else
                DB::rollback();
        }
        catch (Exception $e)
        {
            DB::rollback();
            $rpta = $this->internalException($e,__FUNCTION__);
        }
        return $rpta;
    }

    public function disBlockSolicitudeGerCom($token)
    {
        //Desbloquenado La solicitud al presionar el boton Cancelar
        $solicitude = Solicitude::where('token', $token)->firstOrFail();
        $solicitude->blocked = 0 ;
        $solicitude->save();
        return Redirect::to('show_user');
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
        Log::error(json_encode($fondo->idfondo  ));
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
            $seat = $this->createSeatElement($tempId++, $cuentaMkt, $solicitude->idsolicitud, '', CUENTA_CONTRA_PARTE, '', $fecha_origen, '', '', '', '', '', '', '', ASIENTO_GASTO_DEPOSITO, $advanceSeat->importe, '', $description_seat_back, '', 'CAN');
        }else{
            $description_seat_back = strtoupper($username .' '. $solicitude->institucion);
            $seat = $this->createSeatElement($tempId++, $cuentaMkt, '', $solicitude->idfondo, CUENTA_CONTRA_PARTE, '', $fecha_origen, '', '', '', '', '', '', '', ASIENTO_GASTO_DEPOSITO, $advanceSeat->importe, '', $description_seat_back, '', 'CAN');
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
            $nom = '';
            foreach($solicitude->clients as $client)
            {
                if ($client->from_table == TB_DOCTOR)
                {
                    $doctors = $client->doctors;
                    $nom = $doctors->pefnombres.' '.$doctors->pefpaterno.' '.$doctors->pefmaterno;            
                }
                elseif ($client->from_table == TB_INSTITUTE)
                    $nom = $client->institutes->pejrazon;
                else
                    $nom = 'No encontrado';
                array_push($clientes,$nom);
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

    // IDKC: CHANGE STATUS => GENERADO
    public function saveSeatExpense(){
        try
        {
            DB::beginTransaction();
            $result = array();
            $dataInputs  = Input::all();
            $solicitudeId;
            $fondoId;
            foreach ($dataInputs['seatList'] as $key => $seatItem) 
            {
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
                if(isset($seatItem['solicitudId']))
                {
                    $solicitudeId       = $seatItem['solicitudId'];
                    $seat->idsolicitud  = $solicitudeId;
                }
                else
                {
                    $fondoId        = $seatItem['fondoId'];
                    $seat->idfondo  = $fondoId;
                }
                $seat->tipo_asiento = ASIENTO_GASTO_TIPO;
                $seat->save();
            }        
            if($solicitudeId != null)
            {
                $oldOolicitude      = Solicitude::where('idsolicitud', $solicitudeId)->first();
                $oldStatus          = $oldOolicitude->estado;
                $idSol              = $oldOolicitude->idsolicitud;

                Solicitude::where('idsolicitud', $solicitudeId )->update(array('estado' => ESTADO_GENERADO));

                $rpta = $this->setStatus($oldOolicitude->titulo .' - '. $oldOolicitude->descripcion, $oldStatus, ESTADO_GENERADO, Auth::user()->id, $oldOolicitude->iduser, $idSol);
                if ( $rpta[status] == ok )
                {
                    DB::commit();
                    $result['msg'] = 'Asientos Registrados';
                }
            }
            else if($fondoId != null)
            {
                FondoInstitucional::where('idfondo', $fondoId)->update(array('asiento' => ASIENTO_FONDO_GASTO));
                DB::commit();
                $result['msg'] = 'Asientos Registrados';
            }
            else
            {
                DB::rollback();
                $result['error'] = 'ERROR';
                $result['msg']   = 'No se pudo registrar asientos';
            }
        }
        catch (Exception $e)
        {
            DB::rollback();
            $result['error'] = 'ERROR';
            $result['msg']   = 'No se pudo registrar asientos';
            $temp = $this->internalException($e,__FUNCTION__);        
        }
        return json_encode($result);
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
        $date = $this->getDay();
        $expense = Expense::where('idsolicitud',$solicitude->idsolicitud)->get();
        $clientes = array();
        $nom = '';
        foreach($solicitude->clients as $client)
        {
            if ($client->from_table == TB_DOCTOR)
            {
                $doctors = $client->doctors;
                $nom = $doctors->pefnombres.' '.$doctors->pefpaterno.' '.$doctors->pefmaterno;            
            }
            elseif ($client->from_table == TB_INSTITUTE)
                $nom = $client->institutes->pejrazon;
            else
                $nom = 'No encontrado';
            array_push($clientes,$nom);
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
            'type'       => SOLIC,
            'solicitude' => $solicitude,
            'expense'    => $expense,
            'date'       => $date,
            'clientes'   => $clientes,
            'bancos'     => $banco
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

    // IDKC: CHANGE STATUS => GASTO HABILITADO
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

                $oldOolicitude      = Solicitude::where('idsolicitud', $inputs['idsolicitude'])->first();
                $oldStatus          = $oldOolicitude->estado;
                $idSol              = $oldOolicitude->idsolicitud;

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
                Solicitude::where('idsolicitud', $inputs['idsolicitude'])->update( array('asiento' => SOLICITUD_ASIENTO , 'estado' => GASTO_HABILITADO) );
                $middleRpta = $this->setStatus($oldOolicitude->titulo .' - '. $oldOolicitude->descripcion, $oldStatus, GASTO_HABILITADO, Auth::user()->id, $oldOolicitude->iduser, $idSol);         
                if ($middleRpta[status] == ok)
                {
                    Session::put('state',R_REVISADO);
                    $middleRpta[status] = 1;
                    DB::commit();
                }
            }
        }
        catch (Exception $e)
        {
            DB::rollback();
            $middleRpta = $this->internalException($e,__FUNCTION__);          
        }
        return json_encode($middleRpta);
    }

    // IDKC: CHANGE STATUS => GENERADO
    public function generateSeatExpense()
    {
        try
        {
            DB::beginTransaction();
            $inputs = Input::all();
            $oldOolicitude      = Solicitude::where('idsolicitude', $inputs['idsolicitude'])->first();
            $oldStatus          = $oldOolicitude->estado;
            $idSol              = $oldOolicitude->idsolicitud;

            $solicitude = Solicitude::find($inputs['idsolicitude']);
            $solicitude->estado = GENERADO;
            if($solicitude->update())
            {
                $rpta = $this->setStatus($oldOolicitude->titulo .' - '. $oldOolicitude->descripcion, $oldStatus, DEPOSITADO, Auth::user()->id, USER_CONTABILIDAD, $idSol);    
                if ( $rpta[status] == ok )
                {
                    DB::commit();
                }
            }
            else
            {
                DB::rollback();
                $rpta = array(status => warning , description => 'No se pudo registrar la información');
            }
        }
        catch (Exception $e)
        {
            DB::rollback();
            $rpta = $this->internalException($e,__FUNCTION__);
        }
        return $rpta;
    }

    // IDKC: CHANGE STATUS => DEPOSITO HABILITADO
    public function enableDeposit()
    {
        try
        {
            DB::beginTransaction();
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
            $solicitude->estado = DEPOSITO_HABILITADO;
            $solicitude->retencion = $val_ret;
            $oldOolicitude      = Solicitude::where('idsolicitud', $id)->first();
            $oldStatus          = $oldOolicitude->estado;
            $idSol              = $oldOolicitude->idsolicitud;
            $userAproved          = $oldOolicitude->idaproved;
            if($val_ret != null)
            {
                $solicitude->idtiporetencion = $idtyperetention;    
            }
            $solicitude->asiento = ENABLE_DEPOSIT;
            $data = $this->objectToArray($solicitude);
            if($solicitude->update($data))
            {
                $user_cod = User::where('id',$userAproved)->select('id')->first();
                $rpta = $this->setStatus($oldOolicitude->titulo .' - '. $oldOolicitude->descripcion, $oldStatus, DEPOSITO_HABILITADO, Auth::user()->id, $user_cod->id, $idSol);
                if ($rpta[status] == ok)
                {
                    Session::put('state',R_REVISADO);
                    DB::commit();
                }
            }
            else
            {
                DB::rollback();
                $rpta = array(status => warning , description => 'No se pudo registrar la información');
            }
        }
        catch (Exception $e)
        {
            DB::rollback();
            $rpta = $this->internalException($e,__FUNCTION__);
        }
        return $rpta;
    }

    /** ---------------------------  Asistente de  Gerencia  ---------------------------- **/

    public function listSolicitudeAGer()
    {
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
            if ( in_array($solicitude->estado,array(PENDIENTE,DERIVADO)) && is_null($solicitude->idresponse) )
            {
                $responsables = array();
                $asistentes = User::where('type',ASIS_GER)->get();
                foreach ($asistentes as $asistente)
                    array_push($responsables, $asistente->person);
                if($solicitude->user->type == REP_MED)
                    array_push( $responsables, Solicitude::where('token', $token)->select('iduser')->first()->rm );
                elseif($solicitude->user->type == SUP)
                {
                    $rms = Solicitude::where('token', $token)->first()->sup->Reps;
                    foreach ( $rms as $rm )
                         array_push( $responsables, $rm );
                }
                $rpta[status] = ok;
                $rpta[data] = $responsables;
            }
        }
        catch (Exception $e)
        {
            $rpta = $this->internalException($e,__FUNCTION__);
        }
        return $rpta;
    }

    // IDKC: CHANGE STATUS => RESPONSABLE HABILITADO
    public function asignarResponsableSolicitud()
    {
        try
        {
            DB::beginTransaction();
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
                $oldOolicitude      = Solicitude::where('token', $inputs['token'])->first();
                $oldStatus          = $oldOolicitude->estado;
                $idSol              = $oldOolicitude->idsolicitud;
                Solicitude::where('token',$inputs['token'])->update( array('idresponse' => $inputs['responsable']) );
                $middleRpta = $this->setStatus($oldOolicitude->titulo .' - '. $oldOolicitude->descripcion, $oldStatus, RESPONSABLE_HABILITADO, Auth::user()->id, USER_TESORERIA, $idSol);
                if ( $middleRpta[status] == ok )
                {
                    Session::put('state',R_REVISADO);
                    DB::commit();
                }
            }
        }
        catch (Exception $e)
        {
            DB::rollback();
            $middleRpta = $this->internalException($e,__FUNCTION__);
        }
        return $middleRpta;
    }
}

 
// IDKC: CHANGE STATUS ACEPTADO => APROBADO
    /*public function acceptedSolicitudeGerProd()
    {
        try
        {   
            DB::beginTransaction();
            $inputs = Input::all();
            $idSol = $inputs['idsolicitude'];
            $oldOolicitude      = Solicitude::where('idsolicitud', $idSol)->first();
            $oldStatus          = $oldOolicitude->estado;
            $solicitude = Solicitude::where('idsolicitud', $idSol);
            $solicitude->estado = ACEPTADO;
            $solicitude->idresponse = $inputs['responsable'];
            $solicitude->idaproved = Auth::user()->id;
            $solicitude->monto = $inputs['monto'];
            $solicitude->idfondo = $inputs['sub_type_activity'];
            $solicitude->observacion = $inputs['observacion'];
            $solicitude->blocked = 0;
            $data = $this->objectToArray($solicitude);
            $solicitude->update($data);
            $rpta = $this->setStatus($oldOolicitude->titulo .' - '. $oldOolicitude->descripcion, $oldStatus, ACEPTADO, Auth::user()->id, USER_GERENTE_COMERCIAL, $idSol);
            if ( $rpta[status] == ok )
            {
                $amount_assigned = $inputs['amount_assigned'];
                $families = SolicitudeFamily::where('idsolicitud', $idSol)->get();
                $i = 0;
                foreach ($families as $fam) 
                {
                    $family = SolicitudeFamily::where('idsolicitud_familia', $fam->idsolicitud_familia);
                    $family->monto_asignado = $amount_assigned[$i];
                    $data = $this->objectToArray($family);
                    $family->update($data);
                    $i++;
                }
                $this->setRpta(R_APROBADO);
                DB::commit();
            }   
        }
        catch (Exception $e)
        {
            DB::rollback();
            $rpta = $this->internalException($e,__FUNCTION__);
        }
        return $rpta;
    }*/

    /*public function show_cont()
    {
        $state = Session::pull('state');
        $states = StateRange::order();
        $data = [
            'states' => $states,
            'state' => $state
        ];
        return View::make('Dmkt.Cont.show_cont', $data);
    }*/
    /*public function listSolicitudeGerCom($id)
    {


        if ($id == 0) {
            $solicituds = Solicitude::all();
        } else {
            $solicituds = Solicitude::where('estado', '=', $id)->get();
        }

        $view = View::make('Dmkt.GerCom.view_solicituds_gercom')->with('solicituds', $solicituds);
        return $view;

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
                    $solicituds = Solicitude::where('estado', $estado)->where('idaproved', Auth::user()->id)
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
                    $solicituds = Solicitude::where('estado', $estado)->where('idaproved', Auth::user()->id)
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

    }*/

    /*public function searchSolicitudsSup()
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
        if ($user->type == 'S') 
        {
            $reps = $user->Sup->Reps;
            $users_ids = array();
            foreach ($reps as $rm)
                $users_ids[] = $rm->iduser;
            $users_ids[] = $user->id;
            
            $solicituds = $this->searchTransaction($estado,$idUser,$start,$end);

            $view = View::make('Dmkt.Sup.view_solicituds_sup')->with($solicituds);
            return $view;
        }
    }*/

    /*public function listSolicitudeSup($id)
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
    }*/


    /*public function searchSolicitudsGerProd()
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
            foreach ($solicituds as $sol)
                $solicitud_ids[] = $sol->idsolicitud; // jalo los ids de las solicitudes pertenecientes al gerente de producto
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
    }*/

    /*public function listSolicitudeGerProd($id)
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
    }*/

    // IDKC: CHANGE STATUS => CANCELADO
    /*public function cancelSolicitudeSup()
    {
        try
        {
            DB::beginTransaction();
            $inputs = Input::all();
            $id = $inputs['idsolicitude'];
            $solicitude = Solicitude::where('idsolicitud', $id);
            $oldOolicitude      = Solicitude::where('idsolicitud', $id)->first();
            $oldStatus          = $oldOolicitude->estado;
            $solicitude->estado = CANCELADO;
            $data               = $this->objectToArray($solicitude);
            $solicitude->update($data);
            $rpta = $this->setStatus($oldOolicitude->titulo .' - '. $oldOolicitude->descripcion, $oldStatus, CANCELADO, Auth::user()->id, $oldOolicitude->iduser, $id);
            if ( $rpta[status] == ok)
            {
                DB::commit();
            }
        }
        catch (Exception $e)
        {
            DB::rollback();
            $rpta = $this->internalException($e,__FUNCTION__);
        }
        return $this->listSolicitudeSup(NO_AUTORIZADO);
    }*/

       /*public function listSolicitude($estado)
    {
        $today = getdate();
        $m = $today['mday'] . '-' . $today['mon'] . '-' . $today['year'];
        $end = date('t-m-Y', strtotime($m));
        $start = date('01-m-Y', strtotime($m));
        $user = Auth::user();
        if ($user->type == 'S') 
        {
            $reps = $user->Sup->Reps;
            $users_ids = array();
            foreach ($reps as $rm)
                $users_ids[] = $rm->iduser;
            $users_ids[] = $user->id;
        }
        else if ($user->type == 'P') 
        {
            $solicitud_ids = [];
            $solicituds = $user->GerProd->solicituds;
            foreach ($solicituds as $sol)
                $solicitud_ids[] = $sol->idsolicitud;
            $users_ids = $solicitud_ids;
        }
        else
        {
            $users_ids = array($user->id);
        }
        $solicituds = $this->searchTransaction($estado,$users_ids,$start,$end);
        $view = View::make('template.solicituds')->with($solicituds);
        return $view;
    }*/

     /*// IDKC: CHANGE STATUS => RECHAZADO
    public function denySolicitudeGerProd()
    {
        try
        {
            DB::beginTransaction();
            $inputs = Input::all();
            $id = $inputs['idsolicitude'];
            $oldOolicitude      = Solicitude::where('idsolicitud', $id)->first();
            $oldStatus          = $oldOolicitude->estado;
            //$idSol              = $oldOolicitude->idsolicitud;
            $solicitude = Solicitude::where('idsolicitud', $id);
            $solicitude->idsolicitud = (int)$id;
            $solicitude->idaproved = Auth::user()->id;
            $solicitude->observacion = $inputs['observacion'];
            $solicitude->blocked = 0;
            $solicitude->estado = RECHAZADO;
            $data = $this->objectToArray($solicitude);
            $solicitude->update($data);
            $rpta = $this->setStatus($oldOolicitude->titulo .' - '. $oldOolicitude->descripcion, $oldStatus, RECHAZADO, Auth::user()->id, $oldOolicitude->iduser, $idSol);
            if ( $rpta[status] == ok )
            {
                DB::commit();
            }
        }
        catch (Exception $e)
        {
            DB::rollback();
            $rpta = $this->internalException($e,__FUNCTION__);
        }
        return Redirect::to('show_gerprod')->with('state', RECHAZADO);
    }*/

    /*public function searchSolicitudeCont()
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

    }*/

    /*public function redirectApprovedSolicitude()
    {
        return Redirect::to('show_gercom')->with('state',APROBADO);
    }*/

    /*public function searchSolicitudeTes()
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
            if ($estado != 0) {

                $solicituds = Solicitude::whereNotNull('idresponse')
                    ->where('estado',$estado)
                    ->where('asiento',ENABLE_DEPOSIT)
                    ->whereRaw("created_at between to_date('$start' ,'DD-MM-YY') and to_date('$end' ,'DD-MM-YY')+1")
                    ->get();

            } else {
                $solicituds = Solicitude::whereNotNull('idresponse')
                    ->where('estado', $estado)
                    ->where('asiento',ENABLE_DEPOSIT)
                    ->whereRaw("created_at between to_date('$start' ,'DD-MM-YY') and to_date('$end' ,'DD-MM-YY')+1")
                    ->get();
            }


        } else {
            if ($estado != 0) {
                $solicituds = Solicitude::whereNotNull('idresponse')
                    ->where('estado', $estado)
                    ->where('asiento',ENABLE_DEPOSIT)
                    ->whereRaw("created_at between to_date('$firstday' ,'DD-MM-YY') and to_date('$lastday' ,'DD-MM-YY')+1")
                    ->get();
            } else {
                $solicituds = Solicitude::whereNotNull('idresponse')
                    ->where('estado', $estado)
                    ->where('asiento',ENABLE_DEPOSIT)
                    ->whereRaw("created_at between to_date('$firstday' ,'DD-MM-YY') and to_date('$lastday' ,'DD-MM-YY')+1")
                    ->get();
            }
        }

        $view = View::make('Treasury.view_solicituds_tes')->with('solicituds', $solicituds);
        return $view;
    }*/

    /*public function listSolicitudeCont($id)
    {
        if ($id == 0) {
            $solicituds = Solicitude::all();
        } else {
            $solicituds = Solicitude::where('estado', '=', $id)->get();
        }

        $view = View::make('Dmkt.Cont.view_solicituds_cont')->with('solicituds', $solicituds);
        return $view;
    }*/

    /*public function show_rm()
    {
        $data = array(
            'state' => Session::pull('state'),
            'states' => StateRange::order()
            );
        return View::make('template.show_rm',$data);
    }*/
    /** ---------------------------------------------  Gerente Comercial  -------------------------------------------------*/

    /*public function show_gercom()
    {
        $state = Session::pull('state');
        $estado = Session::pull('Estado');
        $states = StateRange::order();
        $data = array(
            'state' => $state,
            'states' => $states,
            'estado' => $estado
        );
        return View::make('Dmkt.GerCom.show_gercom', $data);
    }*/
    /** -----------------------------------------------  Supervisor  -------------------------------------------------------- */
    /*public function show_sup()
    {

        $state = Session::pull('state');
        $states = StateRange::order();
        $data = array(
            'states' => $states,
            'state' => $state
        );
        return View::make('template.show_sup', $data);
    }*/
    /** --------------------------------------------- Gerente de  Producto ----------------------------------------------- */

    /*public function show_gerprod()
    {
        $state = Session::pull('state');
        $states = StateRange::order();
        $data = array(
            'states' => $states,
            'state' => $state
        );
        return View::make('Dmkt.GerProd.show_gerprod', $data);
    }*/