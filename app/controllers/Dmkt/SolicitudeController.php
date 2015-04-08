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
use \Dmkt\SolicitudeDetalle;

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
    public function showUser()
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
                    return array( status => warning , description =>"Ingrese todos los campos de Cliente" );
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
                    return array( status => warning , description => "Ingrese un archivo de imagen" );
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
                return array( status => warning , description => "Tipo de Solicitud no Existente");
            }
            $validator = Validator::make($inputs, $rules);
            if ($validator->fails()) 
            {
                $messages = $validator->messages();
                return $messages;
            }
            else
            {
                $date       = $inputs['delivery_date'];
                list($d, $m, $y) = explode('/', $date);
                $d          = mktime(11, 14, 54, $m, $d, $y);
                $date       = date("Y/m/d", $d);
                $typeUser   = Auth::user()->type;
                
                $detalle    = array();
 
                $solicitud = new Solicitude;
            
                $id_sol                              = $solicitud->searchId() + 1;
                $solicitud->id                       = $id_sol;
                $solicitud->titulo                   = $inputs['titulo'];
                $solicitud->descripcion              = $inputs['description'];
                $solicitud->idestado                 = PENDIENTE;
                $solicitud->idetiqueta               = $inputs['etiqueta'];
                $solicitud->idtiposolicitud          = SOL_REP;
                $solicitud->token                    = sha1( md5( uniqid( $solicitud->id , true ) ) );
                $solicitud->status                   = ACTIVE;
                
                $solicitud_detalle                   = new SolicitudeDetalle;
                $solicitud_detalle->id               = $solicitud_detalle->searchId() + 1;

                $solicitud->iddetalle                = $solicitud_detalle->id;

                if ($solicitud->save()) 
                {
                    $detalle['monto_solicitado']     = $inputs['monto'];
                    $detalle['fecha_entrega']        = $date;
                    //$detalle['id_motivo_solicitud']  = $inputs['type_solicitude'];
                    //$detalle['id_tipo_moneda']       = $inputs['money'];
                    //$detalle['id_tipo_pago']         = $inputs['type_payment'];
                    
                    if ($inputs['type_solicitude']   == 2)
                    {
                        if (isset($image)) 
                        {
                            $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                            $path                    = public_path( IMAGE_PATH . $filename);
                            Image::make($image->getRealPath())->resize(WIDTH,HEIGHT)->save($path);
                            $detalle['image']        = $filename;
                        }
                        else
                            return array ( status => warning , description => 'Imagen no ingresada o no valida');
                        $detalle['monto_factura']    = $inputs['amount_fac'];
                    }
                    elseif ( ! in_array($inputs['type_solicitude'] , array(1,3) ))
                        return array ( status => warning , description => 'Tipo de Solicitud ( Cod: '.$inputs['type_solicitude'].' ) no registrado');
                    if ($inputs['type_payment']      == 2)
                        $detalle['num_ruc']           = $inputs['ruc'];
                    elseif ($inputs['type_payment']  == 3)
                        $detalle['num_cuenta']        = $inputs['number_account'];
                    elseif ($inputs['type_payment']  != 1)
                        return array( status => warning , description => 'No se pudo procesar el tipo de pago ( Cod: '.$inputs['type_payment']. ' ) de la solicitud');
                    $detalle = json_encode($detalle); 

                    $solicitud_detalle->detalle      = $detalle;
                    $solicitud_detalle->idmoneda     = $inputs['money'];
                    $solicitud_detalle->idmotivo     = $inputs['type_solicitude'];
                    $solicitud_detalle->idpago       = $inputs['type_payment'];
                    if ($solicitud_detalle->save())
                    {
                
                        $clients = explode(',',$inputs['clients'][0]);
                        $tables = explode(',',$inputs['tables'][0]);
                        for ($i=0;$i< count($clients);$i++) 
                        {
                            $solicitude_clients                = new SolicitudeClient;
                            $solicitude_clients->id            = $solicitude_clients->searchId() + 1;
                            $solicitude_clients->idsolicitud   = $solicitud->id;
                            $solicitude_clients->idcliente     = $clients[$i];
                            $solicitude_clients->from_table    = $tables[$i];
                            $solicitude_clients->save();
                        }
                        $families = $inputs['families'];
                        foreach ($families as $family) 
                        {
                            $solicitude_families               = new SolicitudeFamily;
                            $solicitude_families->id           = $solicitude_families->searchId() + 1;
                            $solicitude_families->idsolicitud  = $solicitud->id;
                            $solicitude_families->idfamilia    = $family;
                            $solicitude_families->save();
                        }
                        $userRm     = User::where('id', Auth::user()->id)->first();
                        $toUserId;
                        if($userRm->type == REP_MED)
                        {
                            $sup    = Sup::where('idsup', $userRm->rm->idsup)->first();
                            $toUserId = $sup->iduser;
                        }
                        elseif($userRm->type == SUP)
                        {
                            $toUserId    = Auth::user()->id;
                        }
                        $rpta = $this->setStatus(0, PENDIENTE, Auth::user()->id, $toUserId, $id_sol);
                        if ($rpta[status] == ok)
                        {
                            $rpta = $this->setRpta($typeUser);
                            DB::commit();
                        }
                        else
                            DB::rollback();
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
            $sol          = Solicitude::find($id);
            
            $sol_detalle  = SolicitudeDetalle::find($sol->iddetalle);
            
            $solicitude   = Solicitude::find($id);
            $image        = Input::file('file');
            $detalle      = json_decode($sol_detalle->detalle);
            
            $solicitude->titulo         =  $inputs['titulo'];
            $solicitude->descripcion    =  $inputs['description'];
            $solicitude->idetiqueta     =  $inputs['etiqueta'];
            
            $detalle->monto_solicitado  =  $inputs['monto'];
            $detalle->fecha_entrega     =  $date;
            
            
            $typePayment    = $inputs['type_payment'];
            $typeSolicitude = $inputs['type_solicitude'];

            if ( $sol->detalle->idmotivo == 2 && ( $typeSolicitude == 1 || $typeSolicitude == 3 ) ) 
            {
                $path = public_path('img/reembolso/' . $sol->image);
                @unlink($path);
                unset($detalle->monto_factura);
                unset($detalle->image);
            }
            elseif ( $typeSolicitude == 2 )
            {
                if (isset($image))
                {
                    $path = public_path('img/reembolso/' . $sol->image);
                    @unlink($path);
                    $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                    $path = public_path('img/reembolso/' . $filename);
                    Image::make($image->getRealPath())->resize(800, 600)->save($path);
                    $detalle->image = $filename;
                }
                $detalle->monto_factura = $inputs['amount_fac'];
            }
            if ($typePayment == 1) 
            {
                unset($detalle->numruc);
                unset($detalle->numcuenta);
            } 
            else if ($typePayment == 2) 
            {
                $detalle->numruc = $inputs['ruc'];
                unset($detalle->numcuenta);
            } 
            else if ($typePayment == 3) 
            {
                $detalle->numcuenta = $inputs['number_account'];
                unset($detalle->numruc);
            }
            $sol_detalle->detalle = json_encode($detalle);
            
            $solicitude->save();
            /*Log::error($data);
            $solicitude->update($data);
            *//*$data = $this->objectToArray($sol_detalle);
            Log::error($data);
            $sol_detalle->update($data);
            */
            $sol_detalle->idmotivo      =  $inputs['type_solicitude'];
            $sol_detalle->idpago        =  $inputs['type_payment'];
            $sol_detalle->idmoneda      = $inputs['money'];
            
            $detail    = SolicitudeDetalle::find($sol->iddetalle);
            $detail->idmoneda = $inputs['money'];
            $detail->idpago  = $inputs['type_payment'];
            $detail->idmotivo = $inputs['type_solicitude'];
            $detail->detalle = json_encode($detalle);
            $detail->save();
            

            SolicitudeClient::where('idsolicitud', '=', $id)->delete();
            SolicitudeFamily::where('idsolicitud', '=', $id)->delete();
            $clients = $inputs['clients'];
            $tables = $inputs['tables'];
            $clients = explode(',',$inputs['clients'][0]);
            $tables = explode(',',$inputs['tables'][0]);
             
            for ($i=0;$i< count($clients);$i++) 
            {        
                $solicitude_clients = new SolicitudeClient;
                $solicitude_clients->id = $solicitude_clients->searchId() + 1;
                $solicitude_clients->idsolicitud = $id;
                $solicitude_clients->idcliente = $clients[$i];
                $solicitude_clients->from_table = $tables[$i];
                $solicitude_clients->save();
            }
            $families = $inputs['families'];
            foreach ($families as $family) 
            {
                $solicitude_families = new SolicitudeFamily;
                $solicitude_families->id = $solicitude_families->searchId() + 1;
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

            $rpta = $this->setStatus(0, PENDIENTE, Auth::user()->id, $toUserId, $inputs['idsolicitude']);
            if ($rpta[status] = ok)
            {         
                $rpta = $this->setRpta($typeUser);
                DB::commit();
            }
            else
                DB::rollback();
        }
        catch(Exception $e)
        {
            DB::rollback();
            $rpta = $this->internalException($e,__FUNCTION__);
        }
        return $rpta;
    }

    public function searchDmkt()
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
                $middleRpta = $this->searchSolicituds($estado,$rpta[data],$start,$end);
                if ($middleRpta[status] == ok)
                {
                    $data = $middleRpta[data];
                    Log::error(json_encode($data));
                    $view = View::make('template.solicituds')->with( array( 'solicituds' => $data ) )->render();
                    $rpta = $this->setRpta($view);

                    $detalle = SolicitudeDetalle::first();
                    Log::error($detalle->prueba());
                }
                else
                    return array ( status => warning , description => $middleRpta[description]);
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
        $id = $solicitude->id;
        $clients = SolicitudeClient::where('idsolicitud', $id)->lists('idcliente');

        $clients = Client::whereIn('clcodigo', $clients)->get(array('clcodigo', 'clnombre'));
        $families2 = SolicitudeFamily::where('idsolicitud', $id)->lists('idfamilia');
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
            
            $inputs                   = Input::all();
            $rules = array(
                    'idsolicitude'        => 'required|numeric|min:1',
                    'observacion'         => 'required|string|min:1'
            );
            $validator = Validator::make($inputs, $rules);
            if ($validator->fails()) 
            {
                $messages = $validator->messages()->all();
                Log::error(gettype($messages));
                Log::error($messages);
                $desc = '';
                //Log::error($messages->all());
                foreach ($messages as $msg)
                {
                    //Log::error($key);
                    Log::error($msg);
                    $desc .= $msg.'-';
                }
                return array ( status => warning , description => substr($desc,0,-1) );
            }

            $id                       = $inputs['idsolicitude'];
            Log::error($id);
            $solicitude               = Solicitude::find($id);
            $solicitude->idestado     = CANCELADO;
            $solicitude->observacion  = $inputs['observacion'];
            $solicitude->save();
            
            $status                   = $solicitude->idestado;
            $user_id                  = Auth::user()->id;
            $rpta = $this->setStatus($status, CANCELADO, $user_id, $user_id, $id);
            if ( $rpta[status] == ok)
                DB::commit();
            else
                DB::rollback();
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
        if ($sol->createdBy->type == REP_MED && $sol->idestado == PENDIENTE)
            Solicitude::where('token', $token)->update( array( 'status' => BLOCKED ) );
        $solicitude = Solicitude::where('token', $token)->firstOrFail();
        $detalle = json_decode($sol->detalle->detalle);
        $fondos = Fondo::supFondos();
        $gerentes = array();
        foreach($solicitude->families as $v)
        {
            $manager_temp = $v->marca->manager;
            if (!in_array($manager_temp,$gerentes))
                $gerentes[] = $manager_temp;    
        }

        $data = array(
            'solicitude' => $solicitude,
            'detalle'    => $detalle,
            'fondos'     => $fondos,
            'gerentes'   => $gerentes
        );
        $responsables = $this->findResponsables($solicitude,$token);
        if (isset($responsables[data]))
            $data['responsables'] = $responsables[data];
        return View::make('Dmkt.Sup.view_solicitude_sup', $data);
    }

    public function viewSolicitudeGerProd($token)
    {

        $solicitude = Solicitude::where('token', $token)->firstOrFail();
        $userid = Auth::user()->id;
        $block = false;
        if($solicitude->idestado == DERIVADO)
        {
            SolicitudeGer::where('idsolicitud', $solicitude->id) // blockeamos la solicitud para que el otro gerente no lo pueda editar
            ->update(array('status' => 1));
        }
        $fondos = Fondo::GerProdFondos();
        $data = array(
            'solicitude' => $solicitude,
            'fondos' => $fondos
        );
        $responsables = $this->findResponsables($solicitude,$token);
        if (isset($responsables[data]))
            $data['responsables'] = $responsables[data];
        return View::make('Dmkt.GerProd.view_solicitude_gerprod', $data);
    }

    public function viewSolicitudeGerCom($token)
    {
        $solicitude = Solicitude::where('token', $token)->first();
        $sol = Solicitude::where('token', $token);
        $sol->status = 2;
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
                $solicitude->idestado = RECHAZADO;
                $solicitude->status = 2;
                $data = $this->objectToArray($solicitude);
                $solicitude->update($data);
                $rpta = $this->setStatus($oldOolicitude->titulo .' - '. $oldOolicitude->descripcion, $oldStatus, RECHAZADO, $user->id,  $oldOolicitude->iduser, $id,SOLIC);
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

   /*public function redirectAcceptedSolicitude()
   {
       return Redirect::to('show_user')->with('state', R_APROBADO);
   }*/

    // IDKC: CHANGE STATUS => PENDIENTE DERIVADO
    /*public function derivedSolicitude($token,$derive=0)
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
                $rpta = $this->setStatus($oldOolicitude->titulo .' - '. $oldOolicitude->descripcion, $oldStatus, DERIVADO, Auth::user()->id, $v->marca->manager->iduser, $idSol,SOLIC);
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
    }*/

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
        $solicitude->status = 1 ;
        $solicitude->save();
        SolicitudeGer::where('idsolicitud', $solicitude->id) // desblockeamos la solicitud para que el otro gerente no lo pueda editar
        ->update(array('status' => 1));
        return Redirect::to('show_user');
    }

    public function acceptedSolicitude()
    {
        try
        {
            DB::beginTransaction();
            $inputs = Input::all();   
            Log::error($inputs);   
            $rpta = array();
            $idSol = $inputs['idsolicitude'];
            $solicitude = Solicitude::find($idSol);
            $fondo      = Fondo::find($inputs['idfondo']);

            if ($fondo->idusertype == SUP)
            {
                $solicitude->idestado = ACEPTADO;
                $solicitude->iduserasigned = $inputs['idresponsable'];
                if ($solicitude->save())
                {
                    $sol_detalle = SolicitudeDetalle::find($solicitude->iddetalle);
                    $sol_detalle->idfondo   = $inputs['idfondo'];

                    $detalle = json_decode($solicitude->detalle->detalle);
                    $detalle->monto_aceptado = $inputs['monto'];

                    $sol_detalle->detalle = json_encode($detalle);

                    if ($sol_detalle->save())
                    {
                        $amount_assigned = $inputs['amount_assigned'];
                        $families = SolicitudeFamily::where('idsolicitud', $idSol)->get();
                        $i = 0;
                        foreach ($families as $family) 
                        {
                            $fam = SolicitudeFamily::where('id', $family->id);
                            $fam->monto_asignado = $amount_assigned[$i];
                            $data = $this->objectToArray($fam);
                            $fam->update($data);
                            $i++;
                        }
                        $rpta = $this->setStatus($solicitude->idestado, ACEPTADO, Auth::user()->id, USER_GERENTE_COMERCIAL, $idSol);
                        if ( $rpta[status] == ok )
                        {
                            DB::commit();
                            Session::pull('state', R_APROBADO);
                            return $rpta;
                        }
                    }
                }
            }
            elseif ($fondo->idusertype == GER_PROD )
            {
                if (Auth::user()->type == SUP )
                {
                    $solicitude->idestado = DERIVADO;
                    if ($solicitude->save())
                    {
                        $solicitude_gerente = new SolicitudeGer;
                        $solicitude_gerente->id = $solicitude_gerente->searchId() + 1;
                        $solicitude_gerente->idsolicitud = $solicitude->id;
                        $solicitude_gerente->idgerprod = $inputs['idresponsable'];
                        $solicitude_gerente->status = 1;

                        if ($solicitude_gerente->save())
                        {
                            $sol_detalle = SolicitudeDetalle::find($solicitude->iddetalle);
                            $sol_detalle->idfondo   = $inputs['idfondo'];
                            if ($sol_detalle->save())
                            {
                                $rpta = $this->setStatus($solicitude->idestado, DERIVADO, Auth::user()->id, $inputs['idresponsable'], $idSol);
                                if ( $rpta[status] == ok )
                                {
                                    DB::commit();
                                    Session::pull('state', R_PENDIENTE);
                                    return $rpta;
                                }
                            }
                        }
                    }
                }
                elseif (Auth::user()->type == GER_PROD) 
                {
                    Log::error('fggggggggggggggg');
                    $solicitude->idestado = ACEPTADO;
                    $solicitude->iduserasigned = $inputs['idresponsable'];
            
                    if ($solicitude->save())
                    {
                        $sol_detalle = SolicitudeDetalle::find($solicitude->iddetalle);
                        $sol_detalle->idfondo   = $inputs['idfondo'];

                        $detalle = json_decode($solicitude->detalle->detalle);
                        $detalle->monto_aceptado = $inputs['monto'];

                        $sol_detalle->detalle = json_encode($detalle);

                        if ($sol_detalle->save())
                        {
                            $amount_assigned = $inputs['amount_assigned'];
                            $families = SolicitudeFamily::where('idsolicitud', $idSol)->get();
                            $i = 0;
                            foreach ($families as $family) 
                            {
                                $fam = SolicitudeFamily::where('id', $family->id);
                                $fam->monto_asignado = $amount_assigned[$i];
                                $data = $this->objectToArray($fam);
                                $fam->update($data);
                                $i++;
                            }
                            $rpta = $this->setStatus($solicitude->idestado, ACEPTADO, Auth::user()->id, USER_GERENTE_COMERCIAL, $idSol);
                            if ( $rpta[status] == ok )
                            {
                                DB::commit();
                                Session::pull('state', R_APROBADO);
                                return $rpta;
                            }
                        }
                    }
                }  
            }
            elseif ($fondo->idusertype == ASIS_GER)
            {
                $solicitude->idestado = ACEPTADO;
                $solicitude->iduserasigned = $inputs['idresponsable'];
                if ($solicitude->save())
                {
                    $sol_detalle = SolicitudeDetalle::find($solicitude->iddetalle);
                    $sol_detalle->idfondo   = $inputs['idfondo'];
                    if ($sol_detalle->save())
                    {
                       $rpta = $this->setStatus($solicitude->idestado, ACEPTADO, Auth::user()->id, USER_GERENTE_COMERCIAL, $idSol);
                       if ( $rpta[status] == ok )
                       {
                            DB::commit();
                            Session::pull('state', R_APROBADO);
                            return $rpta;
                       }
                    }
                }
            }
        }
        catch (Exception $e)
        {
            DB::rollback();
            $rpta = $this->internalException($e,__FUNCTION__);
        }
        DB::rollback();
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
            Log::error($inputs);
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
                'monto'             => 'required|numeric|min:1',
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
            $rpta = array ( status => warning , description => 'Error Desconocido' );
            $token = $inputs['token'];
            $solicitude = Solicitude::where('token', $token)->firstOrFail();
            
            $idSol = $solicitude->id;        

            $solicitude->idestado = APROBADO;
            $solicitude->status = 1;
            if ($solicitude->save())
            {
                if (isset($inputs['monto']))
                {
                    $sol_detalle = SolicitudeDetalle::find($solicitude->iddetalle);
                    $detalle = json_decode($sol_detalle->detalle);
                    $detalle->monto_aprobado = $inputs['monto'];
                    $sol_detalle->detalle = json_encode($detalle);
                    $sol_detalle->save();
                }
                if (isset($inputs['amount_assigned']))
                {
                    $amount_assigned = $inputs['amount_assigned'];
                    $families = SolicitudeFamily::where('idsolicitud', $idSol)->get();
                    $i = 0;
                    foreach ($families as $fam) 
                    {
                        $family = SolicitudeFamily::where('id', $fam->id);
                        $family->monto_asignado = $amount_assigned[$i];
                        $data = $this->objectToArray($family);
                        $family->update($data);
                        $i++;
                    }
                }
            }
            $rpta = $this->setStatus($solicitude->idestado, APROBADO, Auth::user()->id, USER_CONTABILIDAD, $idSol);
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
        $solicitude->status = 1 ;
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
        if($solicitude)
            $advanceSeat = json_decode(Entry::where('idsolicitud', $solicitude->idsolicitud)->where('d_c', ASIENTO_GASTO_BASE)->first()->toJson());
        else
            $advanceSeat = json_decode(Entry::where('idfondo', $fondo->idfondo)->where('d_c', ASIENTO_GASTO_BASE)->first()->toJson());

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
        if($userType == REP_MED){
            $username .= strtoupper(substr($userElement->rm->nombres, 0, 1) .' ');
            $username .= strtoupper(explode(' ', $userElement->rm->apellidos)[0]);
        }
        elseif($userType == SUP)
        {
            $username .= strtoupper(substr($userElement->sup->nombres, 0, 1) .' ');
            $username .= strtoupper(explode(' ', $userElement->sup->apellidos)[0]);
        }
        elseif($userType == GER_PROD){
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

                $rpta = $this->setStatus($oldOolicitude->titulo .' - '. $oldOolicitude->descripcion, $oldStatus, ESTADO_GENERADO, Auth::user()->id, $oldOolicitude->iduser, $idSol,SOLIC);
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
        try
        {
            $solicitude = Solicitude::where('token', $token)->firstOrFail();
            $date = $this->getDay();
            //$expense = Expense::where('idsolicitud',$solicitude->idsolicitud)->get();
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
                $banco = Account::where('alias',BANCOS)->where('num_cuenta',CUENTA_SOLES)->get();
            }
            elseif ($solicitude->tipo_moneda == DOLARES) {
                $banco = Account::where('alias',BANCOS)->where('num_cuenta',CUENTA_DOLARES)->get();
            }
            if (count($banco) == 1)
            {
                $data = array(
                    'type'       => SOLIC,
                    'solicitude' => $solicitude,
                    //'expense'    => $expense,
                    'date'       => $date,
                    'clientes'   => $clientes,
                    'bancos'     => $banco
                );
                return View::make('Dmkt.Cont.register_advance_seat', $data);
            }
            else
                return array ( status => warning , description => 'Existen varias cuentas de banco en '.$solicitude->tipo_moneda);
        }
        catch (Exception $e)
        {
            $rpta = $this->internalException($e,__FUNCTION__);
            return $rpta;
        }
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
                $middleRpta = $this->setStatus($oldOolicitude->titulo .' - '. $oldOolicitude->descripcion, $oldStatus, GASTO_HABILITADO, Auth::user()->id, $oldOolicitude->iduser, $idSol,SOLIC);         
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
                $rpta = $this->setStatus($oldOolicitude->titulo .' - '. $oldOolicitude->descripcion, $oldStatus, DEPOSITADO, Auth::user()->id, USER_CONTABILIDAD, $idSol,SOLIC);    
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
                $rpta = $this->setStatus($oldOolicitude->titulo .' - '. $oldOolicitude->descripcion, $oldStatus, DEPOSITO_HABILITADO, Auth::user()->id, $user_cod->id, $idSol,SOLIC);
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
            $responsables = array();
            if ( $solicitude->idestado == PENDIENTE ) 
            {
                $asistentes = User::where('type',ASIS_GER)->get();
                foreach ($asistentes as $asistente)
                    array_push($responsables, $asistente->person);
                if($solicitude->createdBy->type == REP_MED)
                    array_push( $responsables, Solicitude::where('token', $token)->select('created_by')->first()->rm );
                elseif($solicitude->createdBy->type == SUP)
                {
                    $rms = Solicitude::where('token', $token)->first()->sup->Reps;
                    foreach ( $rms as $rm )
                         array_push( $responsables, $rm );
                }
                $rpta[status] = ok;
                $rpta[data] = $responsables;
            }
            elseif ( $solicitude->idestado == DERIVADO )
            {
                $responsables = array();
            }
        }
        catch (Exception $e)
        {
            $rpta = $this->internalException($e,__FUNCTION__);
        }
        return $rpta;
    }

    // IDKC: CHANGE STATUS => RESPONSABLE HABILITADO

    public function getResponsables()
    {
        try
        {
            $inputs = Input::all();
            $solicitude = Solicitude::find($inputs['idsolicitude']);
            $fondo = Fondo::find($inputs['idfondo']);
            $responsables = array();
            if ( Auth::user()->type == SUP )
            {
                if ($fondo->idusertype == SUP)
                {
                    if ( $solicitude->createdBy->type == REP_MED )
                        $resp[] = $solicitude->createdBy->rm;
                    elseif ( $solicitude->createdBy->type == SUP )
                        foreach ( $solicitude->createdBy->sup->Reps as $rep )
                            $resp[] = $rep;
                    else
                        return array( status => warning , description => 'Rol del Solicitante no definido: '.$solicitude->createdBy->type);
                }
                elseif ($fondo->idusertype == GER_PROD)
                {
                    foreach ( $solicitude->families as $family )
                    {
                        if ( !is_null($family->marca->manager) )
                            if (is_null($family->marca->manager->iduser))
                                return array( status => warning , description => 'El Gerente no se encuentra registrado en el sistema' );
                            else
                                $resp[] = $family->marca->manager;
                        else
                            return array( status => warning , description => 'No se encontro el encargado (Gerente) de la Marca: '.$family->marca->descripcion );
                    }   
                }
                elseif ($fondo->idusertype == ASIS_GER)
                {
                    $asis = User::where('type', ASIS_GER)->get();
                    foreach ($asis as $asi)
                        $resp[] = $asi->person;
                }
                else
                    return array( status => warning , description => 'Tipo de Fondo no registrado: '.$fondo->idusertype );
            }
            elseif (Auth::user()->type == GER_PROD)
            {
                if ($fondo->idusertype == GER_PROD)
                {
                    if ( $solicitude->createdBy->type == REP_MED )
                        $resp[] = $solicitude->createdBy->rm;
                    elseif ( $solicitude->createdBy->type == SUP )
                        foreach ( $solicitude->createdBy->sup->Reps as $rep )
                            $resp[] = $rep;
                    else
                        return array( status => warning , description => 'Rol del Solicitante no definido: '.$solicitude->createdBy->type);
                }
                elseif ($fondo->idusertype == ASIS_GER)
                {
                    $asis = User::where('type', ASIS_GER)->get();
                    foreach ($asis as $asi)
                        $resp[] = $asi->person;
                }
                else
                    return array( status => warning , description => 'Tipo de Fondo no registrado: '.$fondo->idusertype );    
            }
            return $this->setRpta(array( 'UserType' => Auth::user()->type , 'Type' => $fondo->idusertype , 'Responsables' => $resp ));
        }
        catch (Exception $e)
        {
            return $this->internalException($e,__FUNCTION__);
        }
    }

    /*public function asignar2ResponsableSolicitud()
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
                $oldOolicitude      = Solicitude::where('token', $inputs['token'])->first();
                $oldStatus          = $oldOolicitude->estado;
                $idSol              = $oldOolicitude->idsolicitud;
                Solicitude::where('token',$inputs['token'])->update( array('idresponse' => $inputs['responsable']) );
                $middleRpta = $this->setStatus($oldOolicitude->titulo .' - '. $oldOolicitude->descripcion, $oldStatus, RESPONSABLE_HABILITADO, Auth::user()->id, USER_TESORERIA, $idSol,SOLIC);
                if ( $middleRpta[status] == ok )
                {
                    Session::put('state',R_REVISADO);
                    DB::commit();
                }
                else
                    DB::rollback();
            }
        }
        catch (Exception $e)
        {
            DB::rollback();
            $middleRpta = $this->internalException($e,__FUNCTION__);
        }
        return $middleRpta;
    }*/
}