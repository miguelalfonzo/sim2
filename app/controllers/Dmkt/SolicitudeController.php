<?php

namespace Dmkt;

use \Auth;
use \BaseController;
use \Common\Fondo;
use \Common\State;
use \Common\TypePayment;
use \DB;
use \Exception;
use \Expense\Entry;
use \Expense\Expense;
use \Expense\ExpenseItem;
use \Expense\ProofType;
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
use \Expense\ChangeRate;
use \Common\TypeMoney;
use \Dmkt\Solicitud\Periodo;
use \Expense\ExpenseType;
use \Expense\MarkProofAccounts;

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
    /** ----------------------------------  Representante Medico ---------------------------------- */
    public function showUser()
    {
        if (Session::has('state'))
            $state = Session::pull('state');
        else
        {
            if (Auth::user()->type == GER_COM || Auth::user()->type == CONT)
                $state = R_APROBADO ;
            else if ( Auth::user()->type == REP_MED || Auth::user()->type == SUP || Auth::user()->type == GER_PROD )
                $state = R_PENDIENTE;
            elseif ( Auth::user()->type == TESORERIA )
                $state = R_REVISADO ;
            elseif ( Auth::user()->type == ASIS_GER )
                $state = R_APROBADO ;
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
        if ( Auth::user()->type == TESORERIA )
        {
            $data['tc'] = ChangeRate::getTc();    
            $data['banks'] = Account::banks();
        }
        if ( Auth::user()->type == ASIS_GER )
        {
            $data['fondos']  = Fondo::where('idusertype', ASIS_GER )->get();                
            $data['etiquetas'] = Label::orderBy('id','asc')->get();
        }
        return View::make('template.User.show',$data);   
    }

    public function newSolicitude()
    {
        $typesolicituds = SolicitudReason::all();
        $etiquetas = Label::orderBy('id','asc')->get();
        $typePayments = TypePayment::all();
        $typesMoney = TypeMoney::all();
        $families = Marca::orderBy('descripcion', 'Asc')->get();
        $data = array(
            'typesolicituds' => $typesolicituds,
            'etiquetas'      => $etiquetas,
            'typePayments'   => $typePayments,
            'typesMoney'     => $typesMoney,
            'families'       => $families
        );
        return View::make('Dmkt.Register.solicitude', $data);
    }

    public function editSolicitude($token)
    {
        $solicitude = Solicitude::where('token', $token)->firstOrFail();
        $detalle = json_decode($solicitude->detalle->detalle);
        $typesolicituds = SolicitudReason::all();
        $etiquetas = Label::order();
        $typePayments = TypePayment::all();
        $typeMoney = TypeMoney::all();
        $families = Marca::orderBy('descripcion', 'ASC')->get();
        $data = array(
            'solicitude'       => $solicitude,
            'detalle'          => $detalle,
            'typesolicituds'   => $typesolicituds,
            'etiquetas'        => $etiquetas,
            'typePayments'     => $typePayments,
            'typesMoney'       => $typeMoney,
            'families'         => $families
        );
        return View::make('Dmkt.Register.solicitude', $data);
    }

    public function viewSolicitude($token)
    {
        try
        {
            $solicitud = Solicitude::where('token', $token)->first();
            if ( count( $solicitud) == 0 )
                return $this->warningException( __FUNCTION__ ,'No se encontro la Solicitud con Codigo: '.$token );
            else
            {
                if ( $solicitud->idtiposolicitud != SOL_REP )
                    return $this->warningException( __FUNCTION__ , 'La solicitud con Id: '.$solicitud->id.' no es de Representantes');
                else
                {
                    $detalle = json_decode($solicitud->detalle->detalle);
                    $data = array(
                        'solicitud' => $solicitud,
                        'detalle'   => $detalle
                    );
                    if ( Auth::user()->type == SUP && $solicitud->idestado == PENDIENTE )
                    {
                        $solicitud->status = BLOCKED;
                        if ( !$solicitud->save() )
                            return $this->warningException( __FUNCTION__ , 'No se puede procesar la solicitud para el supervisor');
                        else
                        {
                            $data['fondos'] = Fondo::supFondos();
                            $data['solicitud']->status = 1;
                        }
                    }
                    elseif ( Auth::user()->type == GER_PROD && $solicitud->idestado == DERIVADO )
                    {
                        $data['solicitud']->status = 1;
                        $data['fondos'] = Fondo::gerProdFondos();
                    }
                    elseif ( Auth::user()->type == TESORERIA && $solicitud->idestado == DEPOSITO_HABILITADO )
                    {
                        $data['banks'] = Account::banks();
                        if ( is_null($solicitud->detalle->idretencion) )
                            $data['deposito'] = $detalle->monto_aprobado;
                        else
                            if ( $solicitud->detalle->typeRetention->account->idtipomoneda == $solicitud->detalle->idmoneda )
                                $data['deposito'] = $detalle->monto_aprobado - $detalle->monto_retencion ;
                            else
                            {
                                $tc = ChangeRate::getTc();
                                if ( $solicitud->detalle->idmoneda == SOLES )
                                    $data['deposito'] = $detalle->monto_aprobado - ( $detalle->monto_retencion * $tc->compra );    
                                elseif ( $solicitud->detalle->idmoneda == DOLARES )
                                    $data['deposito'] = $detalle->monto_aprobado - ( $detalle->monto_retencion / $tc->venta );   
                            }
                    }
                    elseif ( Auth::user()->type == CONT )
                    {
                        if ( $solicitud->idestado == APROBADO )
                            $data['typeRetention'] = TypeRetention::all();
                        elseif ( count( $solicitud->registerHist ) == 1 )
                            $data = array_merge( $data , $this->expenseData( $solicitud , $detalle->monto_aprobado ) );
                        elseif ( $solicitud->idestado == DEPOSITADO )
                        {
                            $data['date'] = $this->getDay();
                            $data['lv'] = $this->textLv( $solicitud );
                        }
                        elseif ( count( $solicitud->registerHist ) == 1 )
                            $data = array_merge( $data , $this->expenseData( $solicitud , $detalle->monto_aprobado ) );
                    }
                    elseif ( in_array( Auth::user()->type , array( REP_MED , ASIS_GER ) ) && count( $solicitud->advanceSeatHist ) == 1 )
                        $data = array_merge( $data , $this->expenseData( $solicitud , $detalle->monto_aprobado ) );
                    return View::make('Dmkt.Solicitud.view', $data);
                }
            }
        }
        catch ( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    private function expenseData( $solicitud , $monto_aprobado )
    {
        $data = array(
            'typeProof'  => ProofType::orderBy('id','asc')->get(),
            'typeExpense' => ExpenseType::order(),
            'date'         => $this->getDay()
        );
        $gastos = $solicitud->expenses;
        if ( count( $gastos ) > 0 )
        {
            $data['expense'] = $gastos;
            $balance = $gastos->sum('monto');
            $data['balance'] = $monto_aprobado - $balance;
        }
        return $data;
    }

    private function validateInputSolRep( $inputs , $type )
    {
        try
        {
            if ( empty( $inputs['type_solicitude'] ) )
                return $this->warningException( __FUNCTION__ , 'El Id del motivo de la solicitud se encuentra vacio');
            else
            {       
                $rules = array(
                    'titulo'        => 'required|min:1',
                    'etiqueta'      => 'required|numeric|in:'.implode( ',' , Label::lists( 'id' ) ),
                    'pago'          => 'required|numeric|in:'.implode( ',' , TypePayment::lists( 'id' ) ),
                    'monto'         => 'required|numeric|min:1',
                    'moneda'        => 'required|numeric|in:'.implode( ',' , TypeMoney::lists( 'id') ),
                    'fecha'         => 'required|date_format:"d/m/Y"',
                    'familias'      => 'required|array',
                    'clientes'      => 'required|array|min:1',
                );
                if( !in_array( $inputs['type_solicitude'] , SolicitudReason::lists('id') ) )
                    return $this->warningException( __FUNCTION__ , 'El Motivo de la solicitud con Id: '.$inputs['type_solicitude'].' no se encuentra registrado');
                else
                {
                    if ( $inputs['type_solicitude'] ==  REASON_REGALO )
                    {
                        $rules['amount_fac'] = 'required|numeric';
                        if ( $type == 1 )
                            $rules['file']       = 'required|max:700|mimes:jpeg,gif';
                    }
                    $validator = Validator::make($inputs, $rules);
                    if ( $validator->fails() ) 
                        return $this->warningException( __FUNCTION__ , substr($this->msgValidator($validator), 0 , -1 ) );
                    else
                        return $this->setRpta();
                }   
            }
        }
        catch ( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        } 
    }

    private function unsetPago( $jDetalle , $typePayment , $ruc , $account )
    {
        try
        {
            if ($typePayment == 1) 
            {
                unset($jDetalle->numruc);
                unset($jDetalle->numcuenta);
            } 
            else if ($typePayment == 2) 
            {
                $jDetalle->numruc = $ruc;
                unset($jDetalle->numcuenta);
            } 
            else if ($typePayment == 3) 
            {
                $jDetalle->numcuenta = $account;
                unset($jDetalle->numruc);
            }
            return $this->setRpta( $jDetalle );
        }
        catch ( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__);
        }
    }

    private function unsetImage( $oldType , $jDetalle , $typeSolicitude , $image , $factura)
    {
        try
        {
            if ( ( $typeSolicitude == 1 || $typeSolicitude == 3 ) && $oldType == 2 ) 
            {
                $path = public_path('img/reembolso/' . $jDetalle->image);
                @unlink($path);
                unset($jDetalle->monto_factura);
                unset($jDetalle->image);
            }
            elseif ( $typeSolicitude == 2 )
            {
                if ( $oldType == 2 )
                {
                    if ( is_object( $image ) )
                    {
                        $path = public_path('img/reembolso/' . $jDetalle->image);
                        @unlink($path);
                        $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                        $path = public_path('img/reembolso/' . $filename);
                        Image::make($image->getRealPath())->resize(800, 600)->save($path);
                        $jDetalle->image = $filename;
                    }
                    $jDetalle->monto_factura = round( $factura , 2 , PHP_ROUND_HALF_DOWN );  
                }
                else
                {
                    if ( !is_object( $image ) )
                        return $this->warningException(__FUNCTION__,'No ha ingresado la imagen de la Factura');
                    else
                    {
                        $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                        $path = public_path('img/reembolso/' . $filename);
                        Image::make($image->getRealPath())->resize(800, 600)->save($path);
                        $jDetalle->image = $filename;
                        $jDetalle->monto_factura = round( $factura , 2 , PHP_ROUND_HALF_DOWN );  
                    }       
                }
            }
            return $this->setRpta($jDetalle);
        }
        catch ( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__);
        }
    }

    private function setClients( $idSolicitud , $clients , $tables )
    {
        try
        {
            $clients = explode(',',$clients[0]);
            $tables = explode(',',$tables[0]);    
            if ( count($clients) != count($tables) )
                return $this->warningException(__FUNCTION__,'El numero de clientes ingresado no contiene el mismo numero de fuentes');
            else
            { 
                SolicitudeClient::where('idsolicitud' , $idSolicitud )->delete();
                for ($i=0;$i< count($clients);$i++) 
                {        
                    $client = new SolicitudeClient;
                    $client->id = $client->searchId() + 1;
                    $client->idsolicitud = $idSolicitud;
                    $client->idcliente = $clients[$i];
                    $client->from_table = $tables[$i];
                    if ( !$client->save() )
                        return $this->warningException(__FUNCTION__,'No se pudo procesar a los clientes de la solicitud');
                }
                return $this->setRpta();
            }
        }
        catch ( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }                    
    }

    private function setFamilies( $idSolicitud , $families )
    {
        try
        {
            SolicitudeFamily::where( 'idsolicitud' , $idSolicitud )->delete();
            $idGerentes = array_unique( Marca::whereIn( 'id' , $families )->lists('gerente_id') );
            if ( count( $idGerentes ) > 1 )
                return $this->warningException( __FUNCTION__ , 'No se puede registrar familias que pertenezcan a mas de un G. Producto ( Ids: '. implode( $idGerentes , ',').' )' );
            else
            {
                foreach ($families as $idFamily) 
                {
                    $family              = new SolicitudeFamily;
                    $family->id          = $family->searchId() + 1;
                    $family->idsolicitud = $idSolicitud;
                    $family->idfamilia   = $idFamily;
                    if( !$family->save() )
                        return $this->warningException( __FUNCTION__ , 'No se pudo procesar los productos de la solicitud' );
                }
                return $this->setRpta();
            }
        }
        catch ( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    public function registerSolicitude()
    {
        try
        {
            DB::beginTransaction();
            $inputs = Input::all();
            $image = Input::file('file');
            $middleRpta = $this->validateInputSolRep( $inputs , 1 );
            if ( $middleRpta[status] == ok )
            {
                $solicitud = new Solicitude;
                $solicitud->id                       = $solicitud->searchId() + 1;
                $solicitud->titulo                   = $inputs['titulo'];
                $solicitud->descripcion              = $inputs['descripcion'];
                $solicitud->idestado                 = PENDIENTE;
                $solicitud->idetiqueta               = $inputs['etiqueta'];
                $solicitud->idtiposolicitud          = SOL_REP;
                $solicitud->token                    = sha1( md5( uniqid( $solicitud->id , true ) ) );
                $solicitud->status                   = ACTIVE;
                
                $solicitud_detalle                   = new SolicitudeDetalle;
                $solicitud_detalle->id               = $solicitud_detalle->searchId() + 1;
                $solicitud->iddetalle                = $solicitud_detalle->id;

                if ( !$solicitud->save() )
                    return $this->warningException( __FUNCTION__ , 'No se pudo registrar la solicitud');
                else 
                {
                    $detalle    = array(
                        'monto_solicitado'  => round( $inputs['monto'] , 2 , PHP_ROUND_HALF_DOWN ),
                        'fecha_entrega'     => $inputs['fecha']
                    );                       
                    if ($inputs['type_solicitude']   == REASON_REGALO )
                    {
                        $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                        $path                    = public_path( IMAGE_PATH . $filename);
                        Image::make($image->getRealPath())->resize(WIDTH,HEIGHT)->save($path);
                        $detalle['image']        = $filename;
                        $detalle['monto_factura']    = round( $inputs['amount_fac'] , 2 , PHP_ROUND_HALF_DOWN );
                    }
                    
                    if ($inputs['pago']      == PAGO_CHEQUE )
                        $detalle['num_ruc']        = $inputs['ruc'];
                    elseif ($inputs['pago']  == PAGO_DEPOSITO )
                        $detalle['num_cuenta']     = $inputs['number_account'];
                    
                    $solicitud_detalle->detalle      = json_encode($detalle);
                    $solicitud_detalle->idmoneda     = $inputs['moneda'];
                    $solicitud_detalle->idmotivo     = $inputs['type_solicitude'];
                    $solicitud_detalle->idpago       = $inputs['pago'];
                    if ( !$solicitud_detalle->save() )
                        return $this->warningException( __FUNCTION__ , 'No se pudo registrar los detalles de la solicitud');
                    else
                    {
                        $middleRpta = $this->setClients( $solicitud->id , $inputs['clientes'] , $inputs['tables'] );
                        if ( $middleRpta[status] == ok )
                        {
                            $middleRpta = $this->setFamilies( $solicitud->id , $inputs['familias'] );
                            if ( $middleRpta[status] == ok )
                            {
                                $user = Auth::user();
                                $toUserId = $this->toUser( $user );
                                $middleRpta = $this->setStatus( 0 , PENDIENTE, $user->id, $toUserId, $solicitud->id);
                                if ($middleRpta[status] == ok)
                                {
                                    DB::commit();
                                    return $this->setRpta( Auth::user()->type );
                                }
                            }
                        }
                    }
                }
            }
            DB::rollback();
            return $middleRpta;
        }
        catch (Exception $e)
        {
            DB::rollback();
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    private function toUser( $user )
    {
        if($user->type == REP_MED )
        {
            $sup = Sup::where( 'idsup' , $user->rm->idsup )->first();
            return $sup->iduser;
        }
        elseif($user->type == SUP )
            return $user->id;
        else
            return 0;
    }

    public function formEditSolicitude()
    {
        try
        {
            DB::beginTransaction();
            $inputs = Input::all();
            $middleRpta = $this->validateInputSolRep( $inputs , 2 );
            if ( $middleRpta[status] == ok )
            {     
                $solicitud                 =  Solicitude::find( $inputs['idsolicitude'] );
                $solicitud->titulo         =  $inputs['titulo'];
                $solicitud->descripcion    =  $inputs['descripcion'];
                $solicitud->idetiqueta     =  $inputs['etiqueta'];
                if ( !$solicitud->save() )
                    return array( status => warning , description => 'No se pudo procesar la solicitud');
                else
                {
                    $typeSol = $inputs['type_solicitude'];

                    $detalle  = $solicitud->detalle;
                    $jDetalle = json_decode( $detalle->detalle );
                    $jDetalle->monto_solicitado  =  round( $inputs['monto'] , 2 , PHP_ROUND_HALF_DOWN );
                    $jDetalle->fecha_entrega     =  $inputs['fecha'];  
                
                    $middleRpta = $this->unsetImage( $detalle->idmotivo , $jDetalle , $typeSol , Input::file('file') , $inputs['amount_fac'] );    
                    if ( $middleRpta[status] == ok )
                    {
                        $middleRpta = $this->unsetPago( $middleRpta[data] , $inputs['pago'] , $inputs['ruc'] , $inputs['number_account'] );
                        if ( $middleRpta[status] == ok )
                        {
                            $detalle->detalle       =  json_encode( $middleRpta[data] );
                            $detalle->idmotivo      =  $typeSol;
                            $detalle->idpago        =  $inputs['pago'];
                            $detalle->idmoneda      =  $inputs['moneda'];
                            if (!$detalle->save() )
                                return array( status => warning , description => 'No se pudo procesar los detalles de la solicitud' );
                            else
                            {
                                $middleRpta = $this->setClients( $solicitud->id , $inputs['clientes'] , $inputs['tables'] );
                                if ( $middleRpta[status] == ok )
                                {
                                    $middleRpta = $this->setFamilies( $solicitud->id , $inputs['familias'] );
                                    if ( $middleRpta[status] == ok )
                                    {
                                        $user = Auth::user();
                                        $toUserId = $this->toUser( $user );
                                        $middleRpta = $this->setStatus( 0 , PENDIENTE, $user->id, $toUserId, $inputs['idsolicitude'] );
                                        if ( $middleRpta[status] == ok)
                                        {     
                                            DB::commit();
                                            return $middleRpta;
                                        }
                                    }
                                }   
                            }
                        }
                    }
                }
            }
            DB::rollback();
            return $middleRpta;
        }
        catch(Exception $e)
        {
            DB::rollback();
            return $this->internalException($e,__FUNCTION__);
        }
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
                    $data = array( 'solicituds' => $middleRpta[data] );
                    if ( Auth::user()->type == TESORERIA )
                        $data['tc'] = ChangeRate::getTc();
                    $view = View::make('template.List.solicituds')->with( $data )->render();
                    $rpta = $this->setRpta($view);
                }
                else
                    return array ( status => warning , description => $middleRpta[description] );
        }
        catch (Exception $e)
        {
            $rpta = $this->internalException($e,__FUNCTION__);
        }
        return $rpta;
    }

    


    private function textLv( $solicitude )
    {
        return $this->textAccepted( $solicitude ).' - '.$solicitude->titulo.' - '.$this->textClients( $solicitude );
    }

    private function textAccepted( $solicitude )
    {
        if ( $solicitude->acceptHist->user->type == SUP )
            return $solicitude->acceptHist->user->sup->nombres.' '.$solicitude->acceptHist->user->sup->apellidos;
        elseif ( $solicitude->acceptHist->user->type == GER_PROD )
            return $solicitude->acceptHist->user->gerProd->descripcion;
        else
            return 'No encontrado';
    }

    private function textClients( $solicitude )
    {
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
        return implode(',',$clientes);
    }

    // IDKC: CHANGE STATUS => CANCELADO
    public function cancelSolicitude()
    {
        try
        {
            DB::beginTransaction();
            $inputs = Input::all();
            $rules = array(
                'idsolicitud'        => 'required|numeric|min:1',
                'observacion'        => 'required|string|min:1'
            );
            $validator = Validator::make($inputs, $rules);
            if ($validator->fails()) 
                return $this->warningException( __FUNCTION__ , substr($this->msgValidator($validator),0,-1) );
            else
            {
                $solicitud               = Solicitude::find( $inputs['idsolicitud'] );
                if ( count( $solicitud ) == 0 )
                    return $this->warningException( __FUNCTION__ , 'No se encontro la solicitud: '.$inputs['idsolicitud'] );
                else
                {
                    $periodo = $solicitud->detalle->periodo;
                    if ( $solicitud->idtiposolicitud == SOL_INST )
                    {
                        if ( $periodo->status == BLOCKED )
                            return $this->warningException( __FUNCTION__ , 'No se puede eliminar las solicitudes del periodo: '.$periodo->periodo );
                        if ( count ( Solicitude::solInst( $periodo->periodo ) ) == 1 )
                            Periodo::inhabilitar( $periodo->periodo ); 
                    }
                    elseif ( $solicitud->idtiposolicitud == SOL_REP )
                    {
                        if ( $solicitud->idestado != PENDIENTE )
                            return $this->warningException( __FUNCTION__ , 'No se puede cancelar las solicitudes aceptadas');        
                    } 
                    $oldIdestado             = $solicitud->idestado;
                    $solicitud->idestado     = CANCELADO;
                    $solicitud->observacion  = $inputs['observacion'];
                    if ( !$solicitud->save() )
                        return $this->warningException( __FUNCTION__ , 'No se pudo Cancelar la Solicitud' );
                    else
                    {
                        $user_id                  = Auth::user()->id;
                        $rpta = $this->setStatus($oldIdestado, CANCELADO, $user_id, $user_id, $solicitud->id);
                        if ( $rpta[status] == ok)
                        {
                            DB::commit();
                            $rpta['Type'] = $solicitud->idtiposolicitud;
                            if ( $rpta['Type'] == SOL_REP )
                                Session::put('state',R_NO_AUTORIZADO);
                        }
                        else
                            DB::rollback();
                        return $rpta;
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

    // IDKC: CHANGE STATUS => RECHAZADO
    public function denySolicitude()
    {
        try
        {
            DB::beginTransaction();
            $inputs = Input::all();
            $rules = array( 'observacion' => 'required|min:1' );
            $validator = Validator::make($inputs, $rules);
            if ( $validator->fails() ) 
                return array( status => warning , description => substr( $this->msgValidator($validator) , 0 , -1 ) );
            else
            {    
                $solicitude = Solicitude::find( $inputs['idsolicitude'] );
                $oldIdestado = $solicitude->idestado;
                $solicitude->observacion = $inputs['observacion'];
                $solicitude->idestado = RECHAZADO;
                $solicitude->status = ACTIVE;
                $solicitude->save();
                $rpta = $this->setStatus( $oldIdestado, RECHAZADO, Auth::user()->id,  $solicitude->created_by, $solicitude->id );
                if ( $rpta[status] == ok )
                {
                    Session::put('state',R_NO_AUTORIZADO);
                    DB::commit();
                }
                else
                    DB::rollback();
            }
        }
        catch (Exception $e)
        {
            DB::rollback();
            $rpta = $this->internalException($e,__FUNCTION__);
        }
        return $rpta;
    }

    private function verifySum( $aSum , $total )
    {
        if ( trim( $total ) == "" )
            return array( status => warning , description => "Ingresar el monto (Vacío)" );
        elseif ( !is_numeric( $total ) )
            return array( status => warning , description => "El monto especificado debe ser númerico" );
        elseif ( $total == 0 )
            return array( status => warning , description => "El monto especificado no debe ser igual a 0" );
        elseif ( $total < 0 )
            return array( status => warning , description => "El monto especificado no debe ser negativo" );
        elseif ( array_sum( $aSum ) > $total )
            return array( status => warning , description => "El monto total de las familias es mayor al monto asignado" );
        elseif ( array_sum( $aSum ) < $total )
            return array( status => warning , description => "El monto total de las familias es mayor al monto asignado" );
        else
            return array( status => ok , description => "Montos Iguales" );
    }

    private function finalStatus($idestado, $estado , $to_user , $id , $state)
    {
        try
        {
            $rpta = $this->setStatus($idestado, $estado , Auth::user()->id , $to_user , $id );
            if ( $rpta[status] == ok )
                Session::put('state', $state);
            return $rpta;
        }
        catch ( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }

    }

    public function acceptedSolicitude()
    {
        try
        {
            DB::beginTransaction();
            $inputs     = Input::all();   
            $solicitude = Solicitude::find( $inputs['idsolicitude'] );
            $oldidestado = $solicitude->idestado;    
            $middleRpta = $this->verifySum( $inputs['amount_assigned'] , $inputs['monto'] );
            if ( $middleRpta[status] == ok )
            {
                $fondo      = Fondo::find($inputs['idfondo']);
                if ( $fondo->idusertype != SUP && Auth::user()->type == SUP )
                    return $this->warningException( __FUNCTION__ , 'El Supervisor no puede aceptar la solicitud con el Fondo: '.$fondo->id );
                elseif ( $fondo->idusertype != GER_PROD && Auth::user()->type == GER_PROD )
                    return $this->warningException( __FUNCTION__ , 'El G. Producto no puede aceptar la solicitud con el Fondo: '.$fondo->id );
                else 
                {
                    $solicitude->idestado = ACEPTADO;
                    $solicitude->status   = ACTIVE;
                    $solicitude->iduserasigned = $inputs['idresponsable'];
                    $solicitude->observacion   = $inputs['observacion'];
                    if ( !$solicitude->save() )
                        return $this->warningException( __FUNCTION__ , 'No se pudo procesar la solicitud' );
                    else
                    {
                        $sol_detalle = $solicitude->detalle;
                        $sol_detalle->idfondo   = $inputs['idfondo'];
                        $detalle = json_decode($solicitude->detalle->detalle);
                        $detalle->monto_aceptado = round( $inputs['monto'] , 2 , PHP_ROUND_HALF_DOWN );
                        $sol_detalle->detalle = json_encode($detalle);
                        if ( !$sol_detalle->save() )
                            return $this->warningException( __FUNCTION__ , 'No se pudo procesar el detalle de la solicitud' ); 
                        else
                        {
                            for ($i= 0 ; $i < count($inputs['idfamily']) ; $i++ ) 
                            {
                                $family = SolicitudeFamily::find( $inputs['idfamily'][$i] );
                                $family->monto_asignado = round( $inputs['amount_assigned'][$i] , 2 , PHP_ROUND_HALF_DOWN ) ;
                                if ( !$family->save() )
                                    return $this->warningException( __FUNCTION__ , 'No se pudo procesar los montos de las familias' );
                            }
                            $middleRpta = $this->finalStatus( $oldidestado , ACEPTADO , USER_GERENTE_COMERCIAL , $solicitude->id , R_APROBADO );
                            if ( $middleRpta[status] == ok )
                            {
                                DB::commit();
                                return $this->setRpta();
                            }
                        }
                    }
                }
            }
            DB::rollback();
            return $middleRpta;
        }
        catch (Exception $e)
        {
            DB::rollback();
            return $this->internalException($e,__FUNCTION__);
        }
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
                return array ( status => warning , description => substr($this->msgValidator($validator),0,-1) );
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
            $oldidestado = $solicitude->idestado;
            $idSol = $solicitude->id;        

            $solicitude->idestado = APROBADO;
            if ( isset($inputs['observacion']) )
                $solicitude->observacion = $inputs['observacion'];
            $solicitude->status = 1;

            if ($solicitude->save())
            {
                $sol_detalle = SolicitudeDetalle::find($solicitude->iddetalle);
                $detalle = json_decode($sol_detalle->detalle);
                $detalle->monto_aprobado = $detalle->monto_aceptado;    
                if (isset($inputs['monto']))
                    $detalle->monto_aprobado = round( $inputs['monto'] , 2 , PHP_ROUND_HALF_DOWN );
                $sol_detalle->detalle = json_encode($detalle);
                $sol_detalle->save();
                if (isset($inputs['amount_assigned']))
                {
                    $amount_assigned = $inputs['amount_assigned'];
                    $families = SolicitudeFamily::where('idsolicitud', $idSol)->get();
                    $i = 0;
                    foreach ($families as $fam) 
                    {
                        $family = SolicitudeFamily::where('id', $fam->id);
                        $family->monto_asignado = round( $amount_assigned[$i] , 2 , PHP_ROUND_HALF_DOWN );
                        $data = $this->objectToArray($family);
                        $family->update($data);
                        $i++;
                    }
                }
            }
            $rpta = $this->setStatus( $oldidestado , DEPOSITO_HABILITADO , Auth::user()->id , USER_CONTABILIDAD , $idSol );
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

    /*public function disBlockSolicitudeGerCom($token)
    {
        //Desbloquenado La solicitud al presionar el boton Cancelar
        $solicitude = Solicitude::where('token', $token)->firstOrFail();
        $solicitude->status = 1 ;
        $solicitude->save();
        return Redirect::to('show_user');
    }*/

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

    public function getCuentaContHandler()
    {
        $dataInputs  = Input::all();    
        $accountFondo = Account::where( 'num_cuenta' , $dataInputs['cuentaMkt'] )->first();
        $rpta = MarkProofAccounts::listExpenses( $accountFondo->id );
        return $rpta;

        /*foreach ( $mDCs as $mDC )
            $accountExpense[] = $mDC->getExpenseAccount;
        $accountFondo->tExpense = $accountExpense;
        $accountFondo->accountGasto = $accountExpense;

        return $this->getCuentaCont($dataInputs['cuentaMkt']);*/
    }

    public function getCuentaCont($cuentaMkt){
        $result = array();
        if(!empty($cuentaMkt))
        {
            $accountElement = Account::getExpenseAccount( $cuentaMkt );
            $account        = count($accountElement) == 0 ? array() : json_decode($accountElement->toJson());
            if(count($account) > 0)
                $result['account'] =  $account;
            else{
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

    public function generateSeatExpenseData( $solicitud , $fondo, $iduser )
    {    
        $result   = array();
        $seatList = array();
        $AadvanceSeat = $solicitud->baseEntry;
        if ( is_null( $AadvanceSeat ) )
            return $this->warningException( __FUNCTION__ , 'No se encontro registros del asiento de anticipo D/C: "D" para la solicitud');
        else
        {
            Log::error('ASIENTO GASTO');
        $cuentaMkt      = $solicitud->baseEntry->num_cuenta;
        $idcuentaMkt = $solicitud->baseEntry->account->id;

        $accountResult  = $this->getCuentaCont($cuentaMkt);
        $account_number = '';
        $marcaNumber    = '';
        if(!isset($accountResult['error']))
        {
            $account_number = $accountResult['account'][0]->num_cuenta;
            $idaccount_number = $accountResult['account'][0]->id;
            $marcaNumber = MarkProofAccounts::getMarks( $idcuentaMkt , $idaccount_number );
            $marcaNumber = $marcaNumber[0]->codigo; 
        }
        else
            $result['error'][] = $accountResult['error'];

        $userElement = $solicitud->asignedTo;

        $tipo_responsable = $userElement->tipo_responsable;
        $username= '';

        $userType       = $userElement->type;
        if($userType == REP_MED )
            $username = $userElement->rm->full_name;
        elseif ( $userType == SUP )
            $username = $userElement->sup->full_name;
        elseif ( $userType == ASIS_GER )
            $username = $userElement->person->full_name;
        
        $tempId=1;
        foreach($solicitud->documentList as $expense)
        {
            $comprobante             = $this->getTypeDoc($expense->idcomprobante);
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
                if (count($comprobante->marcaArray) == 2 && (boolean) $comprobante->igv == true) 
                {
                    if ( $expense->igv > 0 )
                        $marca = $marcaNumber == '' ? '' : $marcaNumber.$comprobante->marcaArray[1];
                    else
                        $marca = $marcaNumber == '' ? '' : $marcaNumber.$comprobante->marcaArray[0]; 
                }else{
                    $marca = $marcaNumber == '' ? '' : $marcaNumber.$comprobante->marcaArray[0];
                }
            }

            $seatListTemp = array();
            $fecha_origen =  date("d/m/Y", strtotime($expense->fecha_movimiento));
            // COMPROBANTES CON IGV
            if( (boolean) $comprobante->igv === true)
            {
                $itemLength = count($expense->itemList)-1;
                $total_neto = 0;
                foreach ($expense->itemList as $itemKey => $itemElement) 
                {
                    $description_seat_item           = strtoupper($username .' '. $itemElement->cantidad .' '.$itemElement->descripcion);
                    $description_seat_igv            = strtoupper($expense->razon);
                    $description_seat_repair_base    = strtoupper($username .' '.$expense->descripcion .'-REP '. substr($comprobante->descripcion,0,1).'/' .$expense->num_prefijo .'-'. $expense->num_serie);
                    $description_seat_repair_deposit = strtoupper('REPARO IGV MKT '. substr($comprobante->descripcion,0,1).'/' .$expense->num_prefijo .'-'. $expense->num_serie .' '.$expense->razon);
                    
                    // ASIENTO ITEM
                    $seat = $this->createSeatElement($tempId++, $cuentaMkt, $solicitud->id, '', $account_number, $comprobante->cta_sunat, $fecha_origen, ASIENTO_GASTO_IVA_BASE, ASIENTO_GASTO_COD_PROV_IGV, $expense->razon, ASIENTO_GASTO_COD_IGV, $expense->ruc, $expense->num_prefijo, $expense->num_serie, ASIENTO_GASTO_BASE, $itemElement->importe, $marca, $description_seat_item, $tipo_responsable, '');
                    
                    $total_neto += $itemElement->importe;
                    array_push($seatListTemp, $seat);
                    if($itemLength == $itemKey)
                    {
                        // ASIENTO IGV
                          $seat = $this->createSeatElement($tempId++, $cuentaMkt, '', $solicitud->id, $account_number, $comprobante->cta_sunat, $fecha_origen, ASIENTO_GASTO_IVA_IGV, ASIENTO_GASTO_COD_PROV_IGV, $expense->razon, ASIENTO_GASTO_COD_IGV, $expense->ruc, $expense->num_prefijo, $expense->num_serie, ASIENTO_GASTO_BASE, $expense->igv, $marca, $description_seat_igv, $tipo_responsable, 'IGV');
                        array_push($seatListTemp, $seat);
                             // ASIENTO IMPUESTO SERVICIO
                        if(!($expense->imp_serv == null || $expense->imp_serv == 0 || $expense->imp_serv == '')){
                            $porcentaje = $total_neto/$expense->imp_serv;
                            $description_seat_tax_service    = strtoupper('SERVICIO '. $porcentaje .'% '. $expense->descripcion);
                            $seat = $this->createSeatElement($tempId++, $cuentaMkt, $solicitud->id, '', $account_number, '', $fecha_origen, '', '', '', '', '', '', '', ASIENTO_GASTO_BASE, $expense->imp_serv, $marca, $description_seat_tax_service, '', 'SER');       
                            array_push($seatListTemp, $seat);
                        }
                        // ASIENTO REPARO
                        if($expense->reparo == '1')
                        {   
                                $seat = $this->createSeatElement($tempId++, $cuentaMkt, $solicitud->id, '', CUENTA_REPARO_COMPRAS, '', $fecha_origen, '', '', '', '', '', '', '', ASIENTO_GASTO_BASE, $expense->igv, $marca, $description_seat_repair_base, '', 'REP');
                            array_push($seatListTemp, $seat);
                                 $seat = $this->createSeatElement($tempId++, $cuentaMkt, $solicitud->id, '', CUENTA_REPARO_GOBIERNO, '', $fecha_origen, '', '', '', '', '', '', '', ASIENTO_GASTO_DEPOSITO, $expense->igv, $marca, $description_seat_repair_deposit, '', 'REP');
                            array_push($seatListTemp, $seat);
                        }
                    }
                } 
            // TODOS LOS OTROS DOCUMENTOS
            }
            else
            {
                // ASIENTO DOCUMENT - NO ITEM
                    $description_seat_other_doc = strtoupper($username.' '. $expense->descripcion);

                    /*if ( $solicitud->detalle->idmoneda == DOLARES )
                    {
                        $nTc = ChangeRate::getDateTc( $expense->fecha_movimiento );
                        $expense->monto = $expense->monto * $nTc->venta;
                    }
                    */$seat = $this->createSeatElement($tempId++, $cuentaMkt, $solicitud->id, '', $account_number, $comprobante->cta_sunat, $fecha_origen, ASIENTO_GASTO_IVA_BASE, ASIENTO_GASTO_COD_PROV, $expense->razon, ASIENTO_GASTO_COD, $expense->ruc, $expense->num_prefijo, $expense->num_serie, ASIENTO_GASTO_BASE, $expense->monto, $marca, $description_seat_other_doc, $tipo_responsable, '');
                    array_push($seatListTemp, $seat);
            }
            $seatList = array_merge($seatList, $seatListTemp);
        }
        // CONTRAPARTE ASIENTO DE ANTICIPO

        $description_seat_back = strtoupper($username .' '. $solicitud->titulo);
        //Log::error('ASIENTO DE GASTO DEL ANTICIPO');
        $seat = $this->createSeatElement($tempId++, $cuentaMkt , $solicitud->id, '', $cuentaMkt , '', $fecha_origen, '', '', '', '', '', '', '', ASIENTO_GASTO_DEPOSITO , $AadvanceSeat->importe, '', $description_seat_back, '', 'CAN');        
        array_push($seatList, $seat);
        $result['seatList'] = $seatList;
        return $result;
        }
    }

    public function viewGenerateSeatExpense($token)
    {
        $solicitud = Solicitude::where('token', $token)->first();

        $expenses = $solicitud->expenses; 
            $clientes   = array();
            $nom = '';
            foreach($solicitud->clients as $client)
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
        //}
        $typeProof  = ProofType::all();
        $date       = $this->getDay();
        $expenseItem  = array();
        foreach ( $expenses as $expense ) 
        {
            $expenseItems      = $expense->items;
            $expense->itemList = $expenseItems;
            $expense->count    = count( $expenseItems );
        }
        $solicitud->documentList = $expenses;
        $resultSeats             = $this->generateSeatExpenseData($solicitud, null, null);

        Log::error(json_encode($resultSeats));

        $seatList                = $resultSeats['seatList'];

        $solicitude = Solicitude::where('token', $token)->first();
        Log::error( json_encode ( $solicitude) );

        $data = array(
            'solicitude'  => $solicitude,
            'expenseItem' => $expenses,
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
        
        return View::make('Dmkt.Cont.SeatExpense', $data);
        

        //print_r($data);
        /*if( $solicitud->idtiposolicitud == SOL_REP ){
            return View::make('Dmkt.Cont.SeatExpense', $data);
        }else{
            return View::make('Dmkt.Cont.SeatExpenseFondo', $data);
        }*/
    }

    // IDKC: CHANGE STATUS => GENERADO
    public function saveSeatExpense()
    {
        try
        {
            DB::beginTransaction();
            $result = array();
            $dataInputs  = Input::all();
            
            foreach ($dataInputs['seatList'] as $key => $seatItem) 
            {
                /*list($day, $month, $year) = explode('/', $seatItem['fec_origen']);
                $dateTemp = mktime(11, 14, 54, $month, $day, $year);
                $fec_origen = date("Y/m/d", $dateTemp);
                */
                $seat = new Entry;
                $seat->id           = $seat->searchId() + 1;
                $seat->num_cuenta   = $seatItem['numero_cuenta'];
                $seat->cc           = $seatItem['codigo_sunat'];
                $seat->fec_origen   = $seatItem['fec_origen']; // $fec_origen;
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
                $seat->idsolicitud  = $seatItem['solicitudId'];
                $seat->tipo_asiento = ASIENTO_GASTO_TIPO;
                if ( !$seat->save() )
                    return $this->warningException( __FUNCTION__ , 'No se pudo registrar el asiento N°: '.$key);
            }        
            $solicitud = Solicitude::find( $dataInputs['idsolicitud'] );
            $oldIdEstado = $solicitud->idestado;
            $solicitud->idestado = GENERADO;
            $userId = Auth::user()->id;

            if ( !$solicitud->save() )
                return $this->warningException( $e , __FUNCTION__ );
            else
            {
                $middleRpta = $this->setStatus( $oldIdEstado, GENERADO , $userId, $userId , $solicitud->id );
                if ( $middleRpta[status] == ok )
                {
                    DB::commit();
                    Session::put('state' , R_FINALIZADO );
                }
            }
            DB::rollback();
            return $middleRpta;
        }   
        catch (Exception $e)
        {
            DB::rollback();
            return $this->internalException( $e , __FUNCTION__ );        
        }
    }

  

    public function viewSeatSolicitude($token)
    {
        $solicitude = Solicitude::where('token', $token)->firstOrFail();
        $data = [
            'solicitude' => $solicitude
        ];
        return View::make('Dmkt.Cont.view_seat_solicitude', $data);
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
                DB::beginTransaction();    
                $solicitude = Solicitude::find( $inputs['idsolicitude'] );
                $oldidestado = $solicitude->idestado;
                $solicitude->idestado = GASTO_HABILITADO;
                if ( !$solicitude->save() )
                    return $this->warningException( __FUNCTION__ , 'No se pudo procesar la solicitud' );
                else
                {
                    for( $i=0 ; $i < count( $inputs['number_account'] ) ; $i++ )
                    {
                        $tbEntry = new Entry;
                        $tbEntry->id = $tbEntry->searchId()+1;
                        $tbEntry->num_cuenta = $inputs['number_account'][$i];
                        $tbEntry->fec_origen = $solicitude->detalle->deposit->updated_at ;
                        $tbEntry->d_c = $inputs['dc'][$i];
                        $tbEntry->importe = $inputs['total'][$i];
                        $tbEntry->leyenda = trim($inputs['leyenda']);
                        $tbEntry->idsolicitud = $inputs['idsolicitude'];
                        $tbEntry->tipo_asiento = TIPO_ASIENTO_ANTICIPO;
                        if ( !$tbEntry->save() )
                            return $this->warningException( __FUNCTION__ , 'No se pudo procesar el asiento N°: '.( $i+1 ) );
                    }
                    $middleRpta = $this->setStatus( $oldidestado, GASTO_HABILITADO, Auth::user()->id, $solicitude->iduserasigned,$solicitude->id );         
                    if ($middleRpta[status] == ok)
                    {
                        Session::put( 'state' , R_GASTO );
                        DB::commit();
                    }
                    else
                        DB::rollback();
                    return $middleRpta;
                }
            }
        }
        catch (Exception $e)
        {
            DB::rollback();
            return $this->internalException($e,__FUNCTION__);          
        }
        return $middleRpta;
    }

    

    // IDKC: CHANGE STATUS => DEPOSITO HABILITADO
  /*  public function enableDeposit()
    {
        try
        {
            DB::beginTransaction();
            $inputs = Input::all();
            $solicitude = Solicitude::find( $inputs['idsolicitude'] );
            if ( count( $solicitude ) == 0 )
                return $this->warningException( __FUNCTION__ , 'No se encontro la solicitud con Id: '.$inputs['idsolicitude']);
            else
            {
                if ( $solicitude->idestado != APROBADO )
                    return $this->warningException( __FUNCTION__ , 'No se puede procesar una solicitud que no ha sido Aprobada');
                else
                {
                    $oldIdestado = $solicitude->idestado;
                    $solicitude->idestado = DEPOSITO_HABILITADO;
                    if ( !$solicitude->save())
                        return $this->warningException( __FUNCTION__ , 'Cancelado - No se pudo procesar la Solicitud' );
                    else
                    {
                        $val_ret = $inputs['monto_retencion'];
                        if ( !empty( trim( $val_ret ) ) )
                        {
                            if ( !is_numeric( $val_ret ) )
                                return $this->warningException( __FUNCTION__ , 'La retencion debe ser un valor Númerico' );
                            elseif ( $val_ret <= 0 )    
                                return $this->warningException( __FUNCTION__ , 'La retencion debe ser mayor que 0' );
                            elseif ( empty( trim( $inputs['idretencion'] ) ) )
                                return $this->warningException( __FUNCTION__ , 'No se encontro el tipo de retencion a registrar' );
                            else 
                            {
                                $sol_detalle = $solicitude->detalle;
                                $sol_detalle->idretencion = $inputs['idretencion'];
                                $jDetalle = json_decode($sol_detalle->detalle);
                                $jDetalle->monto_retencion = round( $val_ret , 2 ,PHP_ROUND_HALF_DOWN );
                                $sol_detalle->detalle = json_encode($jDetalle);
                                if ( !$sol_detalle->save() )
                                    return $this->warningException( __FUNCTION__ , 'Cancelado - No se pudo registrar la retencion' );
                            }
                        }
                        $middleRpta = $this->setStatus( $oldIdestado, DEPOSITO_HABILITADO, Auth::user()->id, USER_TESORERIA , $solicitude->id);
                        if ($middleRpta[status] == ok)
                        {
                            Session::put('state',R_REVISADO);
                            DB::commit();
                            return $middleRpta;
                        }
                    }
                }
            }
            DB::rollback();
            return $middleRpta;
        }
        catch (Exception $e)
        {
            DB::rollback();
            return $this->internalException($e,__FUNCTION__);
        }
    }*/

    public function findResponsables()
    {
        try
        {
            $inputs = Input::all();   
            $responsables = array();
            $solicitud = Solicitude::find($inputs['idsolicitude']); 
            if ( $solicitud->idestado == PENDIENTE || $solicitud->idestado == DERIVADO ) 
            {
                $asistentes = User::where( 'type' , ASIS_GER )->get();
                foreach ( $asistentes as $asistente )
                    array_push( $responsables , $asistente->person );
                if( $solicitud->createdBy->type == REP_MED )
                    array_push( $responsables , $solicitud->createdBy->rm );
                elseif( $solicitud->createdBy->type == SUP )
                {
                    $rms = $solicitud->createdBy->sup->reps;
                    foreach ( $rms as $rm )
                        $responsables[] = $rm;
                }
                return $this->setRpta( $responsables );
            }
            else
                return $this->warningException( __FUNCTION__ , 'No se puede buscar los responsable de la solicitud con Id: '.$solicitud->id. ' debido a que no se encuentra PENDIENTE');
        }
        catch (Exception $e)
        {
            return $this->internalException($e,__FUNCTION__);
        }
    }

    public function findGerProd()
    {
        try
        {
            $inputs = Input::all();
            $familias = SolicitudeFamily::where( 'idsolicitud' , $inputs['idsolicitud'] )->lists('idfamilia');
            $marcas = Marca::whereIn('id' , $familias )->lists('gerente_id');
            $idGerente = array_unique( $marcas );
            return $this->setRpta( Manager::find( $idGerente ) );
        }
        catch( exception $e)
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    public function deriveSolRep()
    {
        try
        {
            $inputs = Input::all();
            $solicitud = Solicitude::find( $inputs['idsolicitud'] );
            $oldIdEstado = $solicitud->idestado;
            $solicitud->idestado = DERIVADO ;
            if ( !$solicitud->save() )
                return $this->warningException( __FUNCTION__ , 'No se pudo derivar la solicitud');
            else
            {
                $sol_ger = new SolicitudeGer;
                $sol_ger->id = $sol_ger->searchId() + 1 ;
                $sol_ger->idsolicitud = $solicitud->id;
                $sol_ger->idgerprod = $inputs['gerente'];
                $sol_ger->status    = 1 ;
                if ( !$sol_ger->save() )
                    return $this->warningException( __FUNCTION__ , 'No se pudo derivar al Ger. Prod');
                else
                {
                    $middleRpta = $this->setStatus($oldIdEstado, DERIVADO , Auth::user()->id , $inputs['gerente'] , $inputs['idsolicitud'] );
                    if ( $middleRpta[status] == ok )
                    {
                        DB::commit();
                        return $middleRpta;
                    }
                }
            }
            DB::rollback();
            return $middleRpta;
        }
        catch( exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    // IDKC: CHANGE STATUS => GENERADO
    public function generateSeatExpense()
    {
        try
        {
            DB::beginTransaction();
            $inputs = Input::all();
            //$oldOolicitude      = Solicitude::where('idsolicitude', $inputs['idsolicitude'])->first();
            //$oldStatus          = $oldOolicitude->estado;
            //$idSol              = $oldOolicitude->idsolicitud;

            $solicitud = Solicitude::find($inputs['idsolicitude']);
            $oldIdEstado = $solicitud->id;
            $solicitud->idestado = GENERADO;
            if( !$solicitud->save() )
                return $this->warningException( $e , __FUNCTION__ );
            else
            {
                $middleRpta = $this->setStatus( $oldIdestado , GENERADO , Auth::user()->id , USER_CONTABILIDAD, $solicitud->id );    
                if ( $middleRpta[status] == ok )
                    DB::commit();
                else
                    DB::rollback();
                return $middleRpta;
            }
        }
        catch (Exception $e)
        {
            DB::rollback();
            return $this->internalException($e,__FUNCTION__);
        }
    } 
}

    /*public function getResponsables()
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
                elseif ($fondo->idusertype == GER_PROD )
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
            return $this->setRpta(array( 'UserType' => Auth::user()->type , 'Type' => $fondo->idusertype , 'Responsables' => array_unique( $resp ) ) );
        }
        catch (Exception $e)
        {
            return $this->internalException($e,__FUNCTION__);
        }
    }*/