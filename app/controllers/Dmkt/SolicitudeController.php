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
use \Expense\ExpenseType;
use \Expense\MarkProofAccounts;
use \Expense\Table;
use \Expense\Regimen;
use \stdClass;
use \Policy\AprovalPolicy;
use \Users\Manager;

class SolicitudeController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->beforeFilter('active-user');
    }

    /** ----------------------------------  Representante Medico ---------------------------------- */

    public function showUser()
    {
        if ( Session::has('state') )
            $state = Session::pull('state');
        else
        {
            if ( in_array( Auth::user()->type , array( GER_COM , CONT , ASIS_GER ) ) )
                $state = R_APROBADO ;
            else if ( in_array( Auth::user()->type , array( REP_MED , SUP , GER_PROD , GER_PROM ) ) )
                $state = R_PENDIENTE;
            elseif ( Auth::user()->type == TESORERIA )
                $state = R_REVISADO ;
        }
        $mWarning = array();
        if ( Session::has('warnings') )
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
        elseif ( Auth::user()->type == ASIS_GER )
        {
            $data['fondos']  = Fondo::asisGerFondos();                
            $data['activities'] = Activity::order();
        }
        elseif ( Auth::user()->type == CONT )
        {
            $data['proofTypes'] = ProofType::order();
            $data['regimenes'] = Regimen::all();      
        }
        return View::make('template.User.show',$data);   
    }

    public function newSolicitude()
    {
        $data = array( 
            'reasons'     => Reason::all() ,
            'activities'  => Activity::order(),
            'payments'    => TypePayment::all(),
            'currencies'  => TypeMoney::all(),
            'families'    => Marca::orderBy('descripcion', 'ASC')->get(),
            'investments' => InvestmentType::order()
        );
        return View::make('Dmkt.Register.solicitud', $data);
    }    

    public function editSolicitude($token)
    {
        $data = array(
            'solicitud'   => Solicitud::where('token', $token)->firstOrFail(),
            'reasons'     => Reason::all(),
            'activities'  => Activity::order(),
            'payments'    => TypePayment::all(),
            'currencies'  => TypeMoney::all(),
            'families'    => Marca::orderBy('descripcion', 'ASC')->get(),
            'investments' => InvestmentType::order()    
        );
        $data['detalle'] = json_decode( $data['solicitud']->detalle->detalle );
        return View::make('Dmkt.Register.solicitud', $data);
    }

    public function viewSolicitude($token)
    {
        try
        {
            $solicitud = Solicitud::where('token', $token)->first();
            if ( count( $solicitud) == 0 )
                return $this->warningException( __FUNCTION__ , 'No se encontro la Solicitud con Codigo: '.$token );
            else
            {
                $detalle = json_decode($solicitud->detalle->detalle);
                $data = array(
                    'solicitud' => $solicitud,
                    'detalle'   => $detalle
                );
                if ( Auth::user()->type == SUP && $solicitud->id_estado == PENDIENTE )
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
                elseif ( Auth::user()->type == GER_PROD && $solicitud->id_estado == DERIVADO )
                {
                    $data['solicitud']->status = 1;
                    $data['fondos'] = Fondo::gerProdFondos();
                }
                elseif ( Auth::user()->type == TESORERIA && $solicitud->id_estado == DEPOSITO_HABILITADO )
                {
                    $data['banks'] = Account::banks();
                    $data['deposito'] = $detalle->monto_aprobado;
                }
                elseif ( Auth::user()->type == CONT )
                {
                    if ( $solicitud->id_estado == DEPOSITADO )
                    {
                        $data['date'] = $this->getDay();
                        $data['lv'] = $this->textLv( $solicitud );
                    }
                    elseif ( count( $solicitud->registerHist ) == 1 )
                    {
                        $data = array_merge( $data , $this->expenseData( $solicitud , $detalle->monto_aprobado ) );
                        $data['igv'] = Table::getIGV();
                        $data['regimenes'] = Regimen::all();
                    }
                }
                elseif ( in_array( Auth::user()->type , array( REP_MED , ASIS_GER ) ) && ( $solicitud->advanceSeatHist->count() == 1 ) )
                {
                    $data = array_merge( $data , $this->expenseData( $solicitud , $detalle->monto_aprobado ) );
                    $data['igv'] = Table::getIGV();
                }
                return View::make('Dmkt.Solicitud.view', $data);
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
            'typeProof'    => ProofType::orderBy('id','asc')->get(),
            'typeExpense'  => ExpenseType::order(),
            //'date'         => array( 'toDay' => $solicitud->created_at , 'lastDay' => json_decode( $solicitud->detalle->detalle )->monto_aprobado )
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

    private function validateInputSolRep( $inputs )
    {
        try
        {          
            $rules = array(
                'motivo'        => 'required|numeric|min:1|in:'.implode( ',' , Reason::lists('id') ),
                'inversion'     => 'required|numeric|min:1|in:'.implode( ',' , InvestmentType::lists('id') ),
                'actividad'     => 'required|numeric|in:'.implode( ',' , Activity::lists( 'id' ) ),
                'titulo'        => 'required|min:1',
                'moneda'        => 'required|numeric|in:'.implode( ',' , TypeMoney::lists( 'id') ),
                'monto'         => 'required|numeric|min:1',
                'pago'          => 'required|numeric|in:'.implode( ',' , TypePayment::lists( 'id' ) ),
                'fecha'         => 'required|date_format:"d/m/Y"',
                'productos'     => 'required|array|min:1',
                'clientes'      => 'required|array|min:1'
            );
            $validator = Validator::make( $inputs, $rules );
            if ( $validator->fails() ) 
                return $this->warningException( __FUNCTION__ , substr( $this->msgValidator($validator) , 0 , -1 ) );
            else
            {
                $actividad = Activity::find( $inputs[ 'actividad' ] );
                $rules = array();
                if ( $actividad->imagen == 1 )
                {
                    $rules[ 'monto_factura' ] = 'required|numeric|min:1';
                    $rules[ 'factura' ]       = 'required|max:700|mimes:jpeg,gif'; 
                }
                if ( $inputs[ 'pago'] == PAGO_CHEQUE )
                    $rules['ruc'] = 'required|numeric|digits:11';
                if ( count( $rules ) == 0 )
                    return $this->setRpta();
                else
                {
                    $validator = Validator::make($inputs, $rules);
                    if ( $validator->fails() ) 
                        return $this->warningException( __FUNCTION__ , substr( $this->msgValidator($validator) , 0 , -1 ) );
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

    private function setPago( &$jDetalle , $paymentType , $ruc )
    {
        if ( $paymentType != PAGO_CHEQUE ) 
            unset( $jDetalle->num_ruc );
        else if ( $paymentType == PAGO_CHEQUE ) 
            $jDetalle->num_ruc = $ruc;
    }

    private function setClients( $idSolicitud , $clients , $types )
    {
        try
        {
            if ( count( $clients ) != count( $types ) )
                return $this->warningException( 'Hay un error con los tipos de clientes de la solicitud' , __FUNCTION__ , __LINE__ , __DIR__ );
            else
            { 
                SolicitudClient::where('id_solicitud' , $idSolicitud )->delete();
                for ( $i = 0 ; $i < count( $clients ) ; $i++ ) 
                {        
                    $solClient = new SolicitudClient;
                    $solClient->id = $solClient->lastId() + 1;
                    $solClient->id_solicitud = $idSolicitud;
                    $solClient->id_cliente = $clients[$i];
                    $solClient->id_tipo_cliente = $types[$i];
                    if ( ! $solClient->save() )
                        return $this->warningException( 'No se pudo procesar a los clientes de la solicitud' , __FUNCTION__ , __LINE__ , __DIR__ );
                }
                return $this->setRpta();
            }
        }
        catch ( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }                    
    }

    private function setGerProd( $idsGerProd , $idsolicitud )
    {
        try
        {
            SolicitudGer::deleteSolicitud( $idSolicitud );
            foreach ( $idsGerProd as $idGerProd )
            {
                $solGer = new SolicitudeGer;
                $solGer->id = $sol_ger->lastId() + 1 ;
                $solGer->id_solicitud = $solicitud->id;
                $solGer->id_gerprod = $idGerProd;
                $sol_ger->status    = 1 ;
                if ( ! $sol_ger->save() )
                    return $this->warningException( 'No se pudo derivar al Ger. Prod N°: ' . $idGerProd , __FUNCTION__ , __LINE__ , __FILE__ );
            }
            return $this->setRpta();
        }
        catch ( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        } 
    }

    private function setProducts( $idSolicitud , $idsProducto )
    {
        try
        {
            SolicitudProduct::where( 'id_solicitud' , $idSolicitud )->delete();
            foreach ($idsProducto as $idProducto) 
            {
                $solProduct               = new SolicitudProduct;
                $solProduct->id           = $solProduct->lastId() + 1;
                $solProduct->id_solicitud = $idSolicitud;
                $solProduct->id_producto  = $idProducto;
                if( ! $solProduct->save() )
                    return $this->warningException( __FUNCTION__ , 'No se pudo procesar los productos de la solicitud' );
            }
            return $this->setRpta();
        }
        catch ( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    private function setInvoice( $type , $oldActivity , $jDetalle , $inputs )
    {
        try
        {
            $actividad = Activity::find( $inputs[ 'actividad' ] );

            if ( $type === 'REGISTRAR' )
                if ( $actividad->imagen == 1 )
                    $this->setImage( $jDetalle , $inputs[ 'factura' ] , $inputs[ 'monto_factura'] );
            elseif ( $type === 'ACTUALIZAR' )
            {
                if ( $oldActivity->imagen == 1 && $actividad->imagen != 1 ) 
                {
                    @unlink( public_path( 'img/reembolso/' . $jDetalle->image ) );
                    unset( $jDetalle->monto_factura );
                    unset( $jDetalle->image );
                }
                elseif ( $actividad->imagen == 1 )
                {
                    if ( $solicitud->activity->imagen == 1 )
                        @unlink( public_path( 'img/reembolso/' . $jDetalle->image ) );
                    $this->setImage( $jDetalle , $inputs[ 'factura' ] , $inputs[ 'monto_factura']  ); 
                }
            }
            return $this->setRpta( $jDetalle );
        }
        catch ( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__);
        }
    }

    private function setImage( &$jDetalle , $image , $monto )
    {
        $filename = uniqid() . '.' . $image->getClientOriginalExtension();
        $path = public_path( 'img/reembolso/' . $filename);
        Image::make( $image->getRealPath() )->resize( WIDTH , HEIGHT )->save( $path );
        $jDetalle->image = $filename;
        $jDetalle->monto_factura = round( $monto , 2 , PHP_ROUND_HALF_DOWN );
    }

    public function registerSolicitude()
    {
        try
        {
            DB::beginTransaction();
            $inputs = Input::all();
            $middleRpta = $this->validateInputSolRep( $inputs );
            if ( $middleRpta[status] == ok )
            {
                $solicitud             = new Solicitud;
                $solicitud->id         = $solicitud->lastId() + 1;
                $detalle               = new SolicitudDetalle;
                $detalle->id           = $detalle->lastId() + 1;
                $solicitud->id_detalle  = $detalle->id;
                $solicitud->token      = sha1( md5( uniqid( $solicitud->id , true ) ) );   
                $this->setSolicitud( $solicitud , $inputs );

                if ( ! $solicitud->save() )
                    return $this->warningException( 'No se pudo registrar la solicitud' , __FUNCTION__ , __LINE__ , __FILE__ );
                else 
                {
                    Session::put( 'id_solicitud' , $solicitud->id );
                    $jDetalle = new stdClass();
                    $this->setJsonDetalle( $jDetalle , $solicitud->activity , $inputs , 'REGISTRAR' );
                    $detalle->detalle      = json_encode( $jDetalle );
                    Log::error( json_encode( $detalle->detalle));
                    $this->setDetalle( $detalle , $inputs );
                    if ( ! $detalle->save() )
                        return $this->warningException( __FUNCTION__ , 'No se pudo registrar los detalles de la solicitud');
                    else
                    {
                        $middleRpta = $this->setClients( $solicitud->id , $inputs['clientes'] , $inputs['tipos_cliente'] );
                        if ( $middleRpta[status] == ok )
                        {
                            $middleRpta = $this->setProducts( $solicitud->id , $inputs['productos'] );
                            if ( $middleRpta[status] == ok )
                            {
                                $middleRpta = $this->toUser( $inputs['inversion'] , $inputs['productos'] , $solicitud->id , 1 );
                                Log::error( json_encode($middleRpta ));
                                if ( $middleRpta[ status ] == ok )
                                {
                                    $middleRpta = $this->setStatus( 0 , PENDIENTE, Auth::user()->id , $middleRpta[ data ] , Session::pull( 'id_solicitud' ) );
                                    //dd( $middleRpta );
                                    if ( $middleRpta[status] == ok )
                                        DB::commit();
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

    private function toUser( $investmentType , $idsProducto , $idSolicitud , $order )
    {
        try
        {
            $aprovalPolicy = AprovalPolicy::getToUser( $investmentType , $order );
            Log::error( $idsProducto );
            $userType = $aprovalPolicy->tipo_usuario;
            Session::put( 'usertype' , $userType );
            Log::error( $userType );
            if ( is_null( $aprovalPolicy ) )
                return $this->warningException( 'La inversion no  tiene politica de aprobacion' , __FUNCTION__ , __LINE__ , __FILE__ );
            else
            {   
                Log::error( 'check');     
                if ( $userType == SUP )
                {
                    if ( Auth::user()->type == REP_MED )
                        return $this->setRpta( Sup::getSup()->iduser );
                    else if ( Auth::user()->type == SUP )
                        return $this->setRpta( Auth::user()->id );
                }
                else if ( $userType == GER_PROD )
                {
                    $idsGerProd = array_unique( Marca::whereIn( 'id' , $idsProducto )->lists( 'gerente_id' ) );
                    $idsUser = Manager::whereIn( 'id' , $idsGerProd )->lists( 'iduser' );
                    $users = User::whereIn( 'id' , $idsUser );
                    if ( $users->count() != count( $idsGerProd) )
                        return $this->warningException( 'Uno o mas de los siguientes Gerentes de Producto no estan registrados en el sistema: ' . implode( ' , ' , $idsGerProd) , __FUNCTION__ , __LINE__ , __FILE__ );
                    else
                    {
                        $middleRpta = $this->setGerProd( $idsUser , Session::get( 'id_solicitud') );
                        return $this->setRpta( $idsUser );
                    }
                }
                else
                {
                    Log::error( 'user');
                    $user = User::getUserType( $userType );
                    if ( count( $user ) === 0 )
                        return $this->warningException( 'No se encuentra el usuario: ' . $userType , __FUNCTION__ , __LINE__ , __DIR__ );
                    else                
                        return $this->setRpta( $user->id );
                }
            }
        }
        catch ( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    private function setSolicitud( $solicitud , $inputs )
    {
        $solicitud->titulo          = $inputs['titulo'];
        $solicitud->id_actividad    = $inputs['actividad'];
        $solicitud->id_inversion    = $inputs['inversion'];
        $solicitud->descripcion     = $inputs['descripcion'];
        $solicitud->id_estado       = PENDIENTE;
        $solicitud->idtiposolicitud = SOL_REP;
        $solicitud->status          = ACTIVE;
    }

    private function setDetalle( $detalle , $inputs )
    {
        $detalle->id_moneda     = $inputs['moneda'];
        $detalle->id_motivo     = $inputs['motivo'];
        $detalle->id_pago       = $inputs['pago'];
    }                  

    private function setJsonDetalle( &$jDetalle , $oldActivity , $inputs , $type )  
    {
        $jDetalle->monto_solicitado  =  round( $inputs['monto'] , 2 , PHP_ROUND_HALF_DOWN );
        $jDetalle->fecha_entrega     =  $inputs['fecha']; 
        Log::error( json_encode($jDetalle));
                    
        $this->setInvoice( $type , $oldActivity , $jDetalle , $inputs );    
        Log::error( json_encode($jDetalle));
                    
        $this->setPago( $jDetalle , $inputs['pago'] , $inputs['ruc'] );            
        Log::error( json_encode($jDetalle));
                    
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
                $solicitud =  Solicitud::find( $inputs['idsolicitud'] );
                $actividad = $solicitud->actividad;
                $this->setSolicitud( $solicitud , $inputs );
                if ( !$solicitud->save() )
                    return array( status => warning , description => 'No se pudo procesar la solicitud');
                else
                {
                    $detalle  = $solicitud->detalle;
                    $jDetalle = json_decode( $detalle->detalle );
                    Log::error( json_encode($jDetalle));
                    $this->setJsonDetalle( $jDetalle , $actividad , $inputs , 'ACTUALIZAR' );
                    Log::error( json_encode($jDetalle));
                    $detalle->detalle       =  json_encode( $jDetalle );
                    $this->setDetalle( $detalle , $inputs );
                    if (!$detalle->save() )
                        return array( status => warning , description => 'No se pudo procesar los detalles de la solicitud' );
                    else
                    {
                        $middleRpta = $this->setClients( $solicitud->id , $inputs['clientes'] , $inputs['tipos_cliente'] );
                        if ( $middleRpta[status] == ok )
                        {
                            $middleRpta = $this->setFamilies( $solicitud->id , $inputs['productos'] );
                            if ( $middleRpta[status] == ok )
                            {
                                $user = Auth::user();
                                $toUserId = $this->toUser( $user );
                                $middleRpta = $this->setStatus( 0 , PENDIENTE, $user->id, $toUserId, $inputs['idsolicitud'] );
                                if ( $middleRpta[status] == ok)
                                    DB::commit();
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
                $middleRpta = $this->searchSolicituds( $estado , $rpta[data] , $start , $end );
                if ( $middleRpta[status] == ok )
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
        if ( $solicitude->idtiposolicitud == SOL_REP )
            if ( $solicitude->acceptHist->user->type == SUP )
                return $solicitude->acceptHist->user->sup->nombres.' '.$solicitude->acceptHist->user->sup->apellidos;
            elseif ( $solicitude->acceptHist->user->type == GER_PROD )
                return $solicitude->acceptHist->user->gerProd->descripcion;
            else
                return 'No encontrado';
        else if ( $solicitude->idtiposolicitud == SOL_INST )
            return $solicitude->createdBy->person->full_name;
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
                'observacion'        => 'required|string|min:10'
            );
            $validator = Validator::make($inputs, $rules);
            if ($validator->fails()) 
                return $this->warningException( substr($this->msgValidator($validator) , 0 , -1 ) , __FUNCTION__ , __LINE__ , __FILE__ );
            else
            {
                $solicitud               = Solicitud::find( $inputs['idsolicitud'] );
                if ( count( $solicitud ) == 0 )
                    return $this->warningException( __FUNCTION__ , 'No se encontro la solicitud: '.$inputs['idsolicitud'] );
                else
                {
                    if ( $solicitud->idtiposolicitud == SOL_INST )
                    {
                        $periodo = $solicitud->detalle->periodo;
                        if ( $periodo->status == BLOCKED )
                            return $this->warningException( __FUNCTION__ , 'No se puede eliminar las solicitudes del periodo: '.$periodo->periodo );
                        if ( count ( Solicitud::solInst( $periodo->periodo ) ) == 1 )
                            Periodo::inhabilitar( $periodo->periodo ); 
                    }
                    elseif ( $solicitud->idtiposolicitud == SOL_REP )
                    {
                        if ( $solicitud->id_estado != PENDIENTE )
                            return $this->warningException( __FUNCTION__ , 'No se puede cancelar las solicitudes aceptadas');        
                    } 
                    $oldIdestado             = $solicitud->id_estado;
                    $solicitud->id_estado     = CANCELADO;
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
                $solicitude = Solicitud::find( $inputs['idsolicitude'] );
                $oldIdestado = $solicitude->id_estado;
                $solicitude->observacion = $inputs['observacion'];
                $solicitude->id_estado = RECHAZADO;
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
            $solicitude = Solicitud::find( $inputs['idsolicitude'] );
            $oldidestado = $solicitude->id_estado;    
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
                    $solicitude->id_estado = ACEPTADO;
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
                                $family = SolicitudProduct::find( $inputs['idfamily'][$i] );
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
            $solicitude = Solicitud::where('token', $token)->firstOrFail();
            $oldidestado = $solicitude->id_estado;
            $idSol = $solicitude->id;        
            $solicitude->id_estado = APROBADO;
            if ( isset($inputs['observacion']) )
                $solicitude->observacion = $inputs['observacion'];
            $solicitude->status = 1;

            if ($solicitude->save())
            {
                $sol_detalle = SolicitudDetalle::find($solicitude->iddetalle);
                $detalle = json_decode($sol_detalle->detalle);
                $detalle->monto_aprobado = $detalle->monto_aceptado;    
                if (isset($inputs['monto']))
                    $detalle->monto_aprobado = round( $inputs['monto'] , 2 , PHP_ROUND_HALF_DOWN );
                $sol_detalle->detalle = json_encode($detalle);
                $sol_detalle->save();
                if (isset($inputs['amount_assigned']))
                {
                    $amount_assigned = $inputs['amount_assigned'];
                    $families = SolicitudProduct::where('idsolicitud', $idSol)->get();
                    $i = 0;
                    foreach ($families as $fam) 
                    {
                        $family = SolicitudProduct::where('id', $fam->id);
                        $family->monto_asignado = round( $amount_assigned[$i] , 2 , PHP_ROUND_HALF_DOWN );
                        $family->save();
                        $i++;
                    }
                }
            }
            $rpta = $this->setStatus( $oldidestado , APROBADO , Auth::user()->id , USER_CONTABILIDAD , $idSol );
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

    public function checkSolicitud()
    {
        try
        {
            DB::beginTransaction();
            $inputs = Input::all();
            $solicitud = Solicitud::find( $inputs['idsolicitude'] );
            if ( is_null( $solicitud ) )
                return $this->warningException( __FUNCTION__ , 'Cancelado - No se encontro la solicitud con Id: '.$inputs['idsolicitude']);
            else
            {
                if ( $solicitud->id_estado != APROBADO )
                    return $this->warningException( __FUNCTION__ , 'Cancelado - No se puede procesar una solicitud que no ha sido Aprobada');
                else
                {
                    $oldIdestado = $solicitud->id_estado;
                    if ( $solicitud->detalle->idmotivo == REEMBOLSO )
                    {
                        $solicitud->id_estado = GASTO_HABILITADO;
                        $toUser = $solicitud->iduserasigned;
                    }
                    else
                    {
                        $solicitud->id_estado = DEPOSITO_HABILITADO;
                        $toUser = USER_TESORERIA;
                    }
                    if ( !$solicitud->save() )
                        return $this->warningException( __FUNCTION__ , 'Cancelado - No se pudo procesar la Solicitud' );
                    else
                    {
                        $middleRpta = $this->setStatus( $oldIdestado , $solicitud->id_estado , Auth::user()->id , $toUser , $solicitud->id );
                        if ( $middleRpta[status] == ok )
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
    }

    /** ---------------------------------------------  Contabilidad -------------------------------------------------*/

    public function getTypeDoc( $id )
    {
        return json_decode(ProofType::find($id)->toJson());
    }

    public function createSeatElement($tempId, $cuentaMkt, $solicitudId, $fondoId, $account_number, $cod_snt, $fecha_origen, $iva, $cod_prov, $nom_prov, $cod, $ruc, $serie, $numero, $dc, $monto, $marca, $descripcion, $tipo_responsable, $type){
        $seat = array(
            //'tempId'            => $tempId,             // Temporal
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
        $dataInputs   = Input::all();    
        $accountFondo = Account::where( 'num_cuenta' , $dataInputs[ 'cuentaMkt' ] )->first();
        return MarkProofAccounts::listData( $accountFondo->num_cuenta );
    }

    public function getCuentaCont( $cuentaMkt )
    {
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

    public function generateSeatExpenseData( $solicitud )
    {    
        $result   = array();
        $seatList = array();
        //AadvanceSeat = $solicitud->baseEntry;
        $fondo = $solicitud->detalle->fondo;

        $cuentaExpense = '';
        $marcaNumber    = '';
        $cuentaMkt      = '';
        if ( !is_null( $fondo ) )
        {
            $cuentaMkt      = $fondo->num_cuenta;
            
            $cuentaExpense  = Account::getExpenseAccount( $cuentaMkt );
            
            if( !is_null( $cuentaExpense[0]->num_cuenta ) )
            {
                $cuentaExpense   = $cuentaExpense[0]->num_cuenta;
                $marcaNumber     = MarkProofAccounts::getMarks( $cuentaMkt , $cuentaExpense );
                $marcaNumber     = $marcaNumber[0]->marca_codigo; 
            }
            else
                $result['error'][] = $accountResult['error'];
        }

        $userElement = $solicitud->asignedTo;

        $tipo_responsable = $userElement->tipo_responsable;
        $username = '';

        $userType       = $userElement->type;
        if($userType == REP_MED )
            $username = $userElement->rm->full_name;
        elseif ( $userType == SUP )
            $username = $userElement->sup->full_name;
        elseif ( $userType == ASIS_GER )
            $username = $userElement->person->full_name;
        
        if ( $solicitud->documentList->count() == 0 )
        {
            $result['seatList'] = array();
            return $result;
        }
        else
        {
            $tempId=1;
    
            foreach( $solicitud->documentList as $expense )
            {
                $comprobante             =  $this->getTypeDoc( $expense->idcomprobante );
                $comprobante->marcaArray =  explode( ',' , $comprobante->marca);
                $marca = '';
                if( $marcaNumber == '' )
                {
                    $errorTemp = array(
                        'error' => ERROR_NOT_FOUND_MARCA,
                        'msg'   => MESSAGE_NOT_FOUND_MARCA
                    );
                    if( !isset( $result['error'] ) || !in_array( $errorTemp, $result['error'] ) )
                        $result['error'][] = $errorTemp;
                }
                else
                    if ( count( $comprobante->marcaArray ) == 2 && (boolean) $comprobante->igv == true ) 
                        if ( $expense->igv > 0 )
                            $marca = $marcaNumber == '' ? '' : $marcaNumber . $comprobante->marcaArray[1];
                        else
                            $marca = $marcaNumber == '' ? '' : $marcaNumber . $comprobante->marcaArray[0]; 
                    else
                        $marca = $marcaNumber == '' ? '' : $marcaNumber . $comprobante->marcaArray[0];

                $fecha_origen =  date( 'd/m/Y' , strtotime( $expense->fecha_movimiento ) );
                // COMPROBANTES CON IGV
                if ( (boolean) $comprobante->igv === true )
                {
                    $itemLength = count( $expense->itemList ) - 1;
                    $total_neto = 0;
                    foreach ( $expense->itemList as $itemKey => $itemElement ) 
                    {
                        $description_seat_item           = strtoupper( $username .' '. $itemElement->cantidad .' '. $itemElement->descripcion );
                        $description_seat_igv            = strtoupper( $expense->razon );
                        $description_seat_repair_base    = strtoupper( $username .' '. $expense->descripcion . '-REP ' . substr( $comprobante->descripcion , 0 , 1). '/' .$expense->num_prefijo .'-'. $expense->num_serie );
                        $description_seat_repair_deposit = strtoupper( 'REPARO IGV MKT '. substr( $comprobante->descripcion , 0 , 1 ) . '/' .$expense->num_prefijo . '-' . $expense->num_serie .' '. $expense->razon );
                        
                        // ASIENTO ITEM
                        $seatList[] = $this->createSeatElement( $tempId++ , $cuentaMkt, $solicitud->id, '', $cuentaExpense , $comprobante->cta_sunat, $fecha_origen, ASIENTO_GASTO_IVA_BASE, ASIENTO_GASTO_COD_PROV_IGV, $expense->razon, ASIENTO_GASTO_COD_IGV, $expense->ruc, $expense->num_prefijo, $expense->num_serie, ASIENTO_GASTO_BASE, $itemElement->importe, $marca , $description_seat_item , $tipo_responsable , '' );
                        $total_neto += $itemElement->importe;
                    }
                    $seatList[] = $this->createSeatElement($tempId++, $cuentaMkt, '', $solicitud->id, $cuentaExpense , $comprobante->cta_sunat, $fecha_origen, ASIENTO_GASTO_IVA_IGV, ASIENTO_GASTO_COD_PROV_IGV, $expense->razon, ASIENTO_GASTO_COD_IGV, $expense->ruc, $expense->num_prefijo, $expense->num_serie, ASIENTO_GASTO_BASE, $expense->igv, $marca, $description_seat_igv, $tipo_responsable, 'IGV');
                    
                    //ASIENTO IMPUESTO SERVICIO
                    if( !( $expense->imp_serv == null || $expense->imp_serv == 0 || $expense->imp_serv == '' ) )
                    {
                        $porcentaje = $total_neto / $expense->imp_serv;
                        $description_seat_tax_service    = strtoupper('SERVICIO '. $porcentaje .'% '. $expense->descripcion);
                        $seatList[] = $this->createSeatElement($tempId++, $cuentaMkt, $solicitud->id, '', $cuentaExpense , '', $fecha_origen, '', '', '', '', '', '', '', ASIENTO_GASTO_BASE, $expense->imp_serv, $marca, $description_seat_tax_service, '', 'SER');       
                    }
                    //ASIENTO REPARO
                    if( $expense->reparo == 1 )
                    {   
                        $seatList[] = $this->createSeatElement( $tempId++, $cuentaMkt, $solicitud->id, '' , CUENTA_REPARO_COMPRAS, '', $fecha_origen, '', '', '', '', '', '', '', ASIENTO_GASTO_BASE, $expense->igv, $marca, $description_seat_repair_base, '', 'REP');
                        $seatList[] = $this->createSeatElement( $tempId++, $cuentaMkt, $solicitud->id, '' , CUENTA_REPARO_GOBIERNO, '', $fecha_origen, '', '', '', '', '', '', '', ASIENTO_GASTO_DEPOSITO, $expense->igv, $marca, $description_seat_repair_deposit, '', 'REP');
                    }
                }
                else //TODOS LOS OTROS DOCUMENTOS
                {
                    //ASIENTO DOCUMENT - NO ITEM
                    $description_seat_other_doc = strtoupper( $username.' '. $expense->descripcion );
                    $seatList[] = $this->createSeatElement($tempId++, $cuentaMkt, $solicitud->id, '', $cuentaExpense , $comprobante->cta_sunat, $fecha_origen, ASIENTO_GASTO_IVA_BASE, ASIENTO_GASTO_COD_PROV, $expense->razon, ASIENTO_GASTO_COD, $expense->ruc, $expense->num_prefijo, $expense->num_serie, ASIENTO_GASTO_BASE, $expense->monto, $marca, $description_seat_other_doc, $tipo_responsable, ''); 
                }
                //$seatList = array_merge($seatList, $seatListTemp);
            }  
            // CONTRAPARTE ASIENTO DE ANTICIPO
            $description_seat_back = strtoupper( $username .' '. $solicitud->titulo );
            $seatList[]  = $this->createSeatElement( $tempId++, $cuentaMkt , $solicitud->id, '', $cuentaMkt , '', $fecha_origen, '', '', '', '', '', '', '', ASIENTO_GASTO_DEPOSITO , json_decode( $solicitud->detalle->detalle )->monto_aprobado , '', $description_seat_back, '', 'CAN');        
            $result['seatList'] = $seatList;
            return $result;
        }
    }

    public function viewGenerateSeatExpense( $token )
    {
        $solicitud  = Solicitud::where( 'token' , $token)->first();
        $expenses   = $solicitud->expenses; 
        $clientes   = array();
        
        foreach( $solicitud->clients as $client )
        {
            if ( $client->from_table == TB_DOCTOR )
            {
                $doctors = $client->doctors;
                $nom = $doctors->pefnombres.' '.$doctors->pefpaterno.' '.$doctors->pefmaterno;            
            }
            elseif ($client->from_table == TB_INSTITUTE)
                $nom = $client->institutes->pejrazon;
            else
                $nom = 'No encontrado';
            $clientes[] = $nom;
        }
        $clientes     = implode( ',' , $clientes );
        $typeProof    = ProofType::all();
        $date         = $this->getDay();
        $expenseItem  = array();

        foreach ( $expenses as $expense ) 
        {
            $expenseItems      = $expense->items;
            $expense->itemList = $expenseItems;
            $expense->count    = count( $expenseItems );
        }

        $solicitud->documentList = $expenses;
        $resultSeats             = $this->generateSeatExpenseData( $solicitud );

        $seatList = $resultSeats['seatList'];

        $data = array(
            'solicitud'   => $solicitud,
            'expenseItem' => $expenses,
            'date'        => $date,
            'clientes'    => $clientes,
            'typeProof'   => $typeProof,
            'seats'       => json_decode( json_encode( $seatList ) )
        );

        if( isset( $resultSeats['error'] ) )
        {
            $tempArray  = array( 'error' => $resultSeats['error'] );
            $data       = array_merge( $data , $tempArray );
        }    
        return View::make('Dmkt.Cont.SeatExpense', $data);
    }

    // IDKC: CHANGE STATUS => GENERADO
    public function saveSeatExpense()
    {
        try
        {
            DB::beginTransaction();
            $result = array();
            $dataInputs  = Input::all();
            foreach ( $dataInputs['seatList'] as $key => $seatItem ) 
            {
                $seat = new Entry;
                $seat->id           = $seat->searchId() + 1;
                $seat->num_cuenta   = $seatItem['numero_cuenta'];
                $seat->cc           = $seatItem['codigo_sunat'];
                $seat->fec_origen   = date( 'Y-m-d' , strtotime( $seatItem[ 'fec_origen' ] ) );
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
                $seat->id_solicitud  = $seatItem['solicitudId'];
                $seat->tipo_asiento = ASIENTO_GASTO_TIPO;
                if ( !$seat->save() )
                    return $this->warningException( __FUNCTION__ , 'No se pudo registrar el asiento N°: '.$key);
            }        
            $solicitud = Solicitud::find( $dataInputs['idsolicitud'] );
            $oldIdEstado = $solicitud->id_estado;
            $solicitud->id_estado = GENERADO;
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

  

    /*public function viewSeatSolicitude($token)
    {
        $solicitude = Solicitud::where('token', $token)->firstOrFail();
        $data = [
            'solicitude' => $solicitude
        ];
        return View::make('Dmkt.Cont.view_seat_solicitude', $data);
    }*/


    public function viewSeatExpense($token)
    {
        $solicitude = Solicitud::where('token', $token)->firstOrFail();
        $expense = Expense::where('idsolicitud',$solicitude->id_solicitud)->get();
        $data = array(
            'solicitude' => $solicitude,
            'expense' => $expense
        );
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
                $solicitude = Solicitud::find( $inputs['idsolicitude'] );
                $oldidestado = $solicitude->id_estado;
                $solicitude->id_estado = GASTO_HABILITADO;
                if ( !$solicitude->save() )
                    return $this->warningException( __FUNCTION__ , 'No se pudo procesar la solicitud' );
                else
                {
                    for( $i=0 ; $i < count( $inputs['number_account'] ) ; $i++ )
                    {
                        $tbEntry = new Entry;
                        $tbEntry->id = $tbEntry->searchId()+1;
                        $tbEntry->num_cuenta = $inputs['number_account'][$i];
                        $tbEntry->fec_origen = date( 'Y-m-d' , strtotime( (string)$solicitude->detalle->deposit->updated_at ) );
                        $tbEntry->d_c = $inputs['dc'][$i];
                        $tbEntry->importe = $inputs['total'][$i];
                        $tbEntry->leyenda = trim($inputs['leyenda']);
                        $tbEntry->id_solicitud = $inputs['idsolicitude'];
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

    public function findResponsables()
    {
        try
        {
            $inputs = Input::all();   
            $responsables = array();
            $solicitud = Solicitud::find($inputs['idsolicitude']); 
            if ( $solicitud->id_estado == PENDIENTE || $solicitud->id_estado == DERIVADO ) 
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
            $familias = SolicitudProduct::where( 'idsolicitud' , $inputs['idsolicitud'] )->lists('idfamilia');
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
            $solicitud = Solicitud::find( $inputs['idsolicitud'] );
            $oldIdEstado = $solicitud->id_estado;
            $solicitud->id_estado = DERIVADO ;
            if ( !$solicitud->save() )
                return $this->warningException( __FUNCTION__ , 'No se pudo derivar la solicitud');
            else
            {
                $middleRpta = $this->setGerProd( $inputs[ 'gerente'] );
                if ( $middleRpta[ status ] == ok )
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
            $solicitud = Solicitud::find($inputs['idsolicitude']);
            $oldIdEstado = $solicitud->id;
            $solicitud->id_estado = GENERADO;
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