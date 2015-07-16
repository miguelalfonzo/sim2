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
use \Users\Sup;
use \Users\Rm;
use \Client\ClientType;
use \yajra\Pdo\Oci8\Exceptions\Oci8Exception;
use \System\FondoHistory;
use \Alert\AlertController;
use \Event\Event;
use \FotoEventos;
use \Common\FileStorage;
use \Response;
use \Maintenance\Fondos;
use \Maintenance\FondosSupervisor;
use \Carbon\Carbon;

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
            $state = Session::get('state');
        else
        {
            if ( Auth::user()->type == CONT )
                $state = R_APROBADO ;
            else if ( in_array( Auth::user()->type , array( REP_MED , SUP , GER_PROD , GER_PROM , GER_COM , ASIS_GER ) ) )
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
        $data = array( 'state'  => $state , 'states' => StateRange::order() , 'warnings' => $mWarning );
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
        if ( Session::has( 'id_solicitud') )
        {
            $solicitud = Solicitud::find( Session::pull( 'id_solicitud' ) );
            $solicitud->status = ACTIVE ;
            $solicitud->save();
        }
        $alert = new AlertController;
        $data[ 'alert' ] = $alert->alertConsole();
        return View::make('template.User.show',$data);   
    }

    public function newSolicitude()
    {
        $alert = new AlertController;
        $data = array( 'reasons'     => Reason::all() ,
                       'activities'  => Activity::order(),
                       'payments'    => TypePayment::all(),
                       'currencies'  => TypeMoney::all(),
                       'families'    => Marca::orderBy('descripcion', 'ASC')->get(),
                       'investments' => InvestmentType::order() ,
                       'alert'       => $alert->expenseAlert() ); 
        return View::make('Dmkt.Register.solicitud', $data);
    }    

    public function editSolicitude($token)
    {
        $alert = new AlertController;
        $data = array( 'solicitud'   => Solicitud::where('token', $token)->firstOrFail(),
                       'reasons'     => Reason::all(),
                       'activities'  => Activity::order(),
                       'payments'    => TypePayment::all(),
                       'currencies'  => TypeMoney::all(),
                       'families'    => Marca::orderBy('descripcion', 'ASC')->get(),
                       'investments' => InvestmentType::order() ,
                       'alert'       => $alert->expenseAlert(),
                       'edit'        => true );
        $data[ 'detalle' ] = $data['solicitud']->detalle;
        return View::make('Dmkt.Register.solicitud', $data);
    }

    public function viewSolicitude($token)
    {
        try
        {
            $solicitud = Solicitud::where('token', $token)->first();
            $politicStatus = FALSE;
            $user = Auth::user();
            if ( is_null( $solicitud ) )
                return $this->warningException( 'No se encontro la Solicitud con Token: ' . $token , __FUNCTION__ , __LINE__ , __FILE__ );
            
            $detalle = $solicitud->detalle;
            $data = array( 'solicitud' => $solicitud , 'detalle' => $detalle );
        
            if ( $solicitud->idtiposolicitud != SOL_INST && in_array( $solicitud->id_estado , array( PENDIENTE , DERIVADO , ACEPTADO ) ) )
            {
                $politicType = $solicitud->aprovalPolicy( $solicitud->histories->count() )->tipo_usuario;
                if ( in_array( $politicType , array( Auth::user()->type , Auth::user()->tempType() ) )
                && ( array_intersect ( array( Auth::user()->id , Auth::user()->tempId() ) , $solicitud->managerEdit( $politicType )->lists( 'id_gerprod' ) ) ) )
                {
                    $politicStatus = TRUE;
                    $data['tipo_usuario'] = $politicType;
                    $solicitud->status = BLOCKED;
                    Session::put( 'id_solicitud' , $solicitud->id );
                    $solicitud->save();
                    $fondos = Fondo::getFunds( $politicType );
                    if ( ! $fondos->isEmpty() )    
                        $data[ 'fondos' ] = $fondos;
                    if ( isset( $data['fondos'] ) && ! is_null( $solicitud->detalle->id_fondo ) )
                    {
                        $data[ 'fondos' ]->push( $solicitud->detalle->fondo );
                        $data['fondos'] = $data[ 'fondos' ]->unique();
                    }
                    $data['solicitud']->status = 1;
                }
            }
            elseif ( Auth::user()->type == TESORERIA && $solicitud->id_estado == DEPOSITO_HABILITADO )
            {
                $data['banks'] = Account::banks();
                $data['deposito'] = $detalle->monto_aprobado;
            }
            elseif ( Auth::user()->type == CONT )
            {
                $data['date'] = $this->getDay();
                if ( $solicitud->id_estado == DEPOSITADO )
                    $data['lv'] = $this->textLv( $solicitud );
                elseif ( ! is_null( $solicitud->registerHistory ) )
                {
                    $data = array_merge( $data , $this->expenseData( $solicitud , $detalle->monto_actual ) );
                    $data['igv'] = Table::getIGV();
                    $data['regimenes'] = Regimen::all();
                }
            }
            elseif ( ! is_null( $solicitud->expenseHistory ) && $user->id == $solicitud->id_user_assign )
            {
                $data = array_merge( $data , $this->expenseData( $solicitud , $detalle->monto_actual ) );
                $data['igv'] = Table::getIGV();
                $data['date'] = $this->getExpenseDate( $solicitud );
            }
            Session::put( 'state' , $data[ 'solicitud' ]->state->id_estado );
            $data[ 'politicStatus' ] = $politicStatus;
            $alert = new AlertController;
            if ( is_null( $data[ 'solicitud' ]->registerHistory ) && !in_array( $data['solicitud']->id_estado , array( CANCELADO , RECHAZADO ) ) ) 
                $data[ 'alert' ] = $alert->compareTime( $data[ 'solicitud'] , 'diffInMonths' );

            $event = Event::where('solicitud_id', '=', $solicitud->id)->get();
            if( $event->count() != 0 )
                $data['event'] = $event[0];
            return View::make( 'Dmkt.Solicitud.view' , $data );
        }
        catch ( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    private function expenseData( $solicitud , $monto_aprobado )
    {
        $data = array( 'typeProof'   => ProofType::orderBy('id','asc')->get(),
                       'typeExpense' => ExpenseType::order(),
                       'date'        => $this->getExpenseDate( $solicitud ) );
        $gastos = $solicitud->expenses;
        if ( count( $gastos ) > 0 )
        {
            $data['expenses'] = $gastos;
            $balance = $gastos->sum('monto');
            $data['balance'] = $monto_aprobado - $balance;
        }
        return $data;
    }

    private function validateInputSolRep( $inputs )
    {
        $rules = array( 'idsolicitud'   => 'integer|min:1|exists:solicitud,id' ,
                        'motivo'        => 'required|integer|min:1|exists:motivo,id' ,
                        'inversion'     => 'required|integer|min:1|exists:tipo_inversion,id' ,
                        'actividad'     => 'required|integer|min:1|exists:tipo_actividad,id' ,
                        'titulo'        => 'required|string|min:1|max:50',
                        'moneda'        => 'required|integer|min:1|exists:tipo_moneda,id',
                        'monto'         => 'required|numeric|min:1',
                        'pago'          => 'required|integer|min:1|exists:tipo_pago,id',
                        'fecha'         => 'required|date_format:"d/m/Y"|after:'.date("Y-m-d"),
                        'productos'     => 'required|array|min:1|each:integer|each:min,1|each:exists,outdvp.marcas,id',
                        'clientes'      => 'required|array|min:1|each:integer|each:min,1',
                        'tipos_cliente' => 'required|array|min:1|each:integer|each:min,1|each:exists,tipo_cliente,id',
                        'descripcion'   => 'string|max:200' );
        $validator = Validator::make( $inputs, $rules );
        if ( $validator->fails() ) 
            return $this->warningException( substr( $this->msgValidator( $validator ) , 0 , -1 ) , __FUNCTION__ , __LINE__ , __FILE__ );
        
        $validator->sometimes( 'ruc' , 'required|numeric|digits:11' , function( $input )
        {
            return $input->pago == PAGO_CHEQUE;
        });
        if ( $validator->fails() ) 
            return $this->warningException( substr( $this->msgValidator( $validator ) , 0 , -1 ) , __FUNCTION__ , __LINE__ , __FILE__ );
        return $this->setRpta();
    }

    private function setPago( &$jDetalle , $paymentType , $ruc )
    {
        if ( $paymentType == PAGO_CHEQUE ) 
            $jDetalle->num_ruc = $ruc;
    }

    private function setClients( $idSolicitud , $clients , $types )
    {
        if ( count( $clients ) != count( $types ) )
            return $this->warningException( 'Hay un error con los tipos de clientes de la solicitud' , __FUNCTION__ , __LINE__ , __FILE__ );
        else
        { 
            $clientType = ClientType::find( $types[ 0 ] );
            $med = 0 ;
            foreach ( $clients as $key => $client ) 
            { 
                $solClient = new SolicitudClient;
                $solClient->id = $solClient->lastId() + 1;
                $solClient->id_solicitud = $idSolicitud;
                $solClient->id_cliente = $client;
                $solClient->id_tipo_cliente = $types[$key];
                $solClient->save();
                if ( $solClient->id_tipo_cliente == MEDICO )
                    $med ++;
            }
            if ( $clientType->num_medico > $med )
                return $this->warningException( 'Se necesita ingresar al menos '. $clientType->num_medico.' medicos' , __FUNCTION__ , __LINE__ , __FILE__ );
            else
                return $this->setRpta();
        }    
    }

    private function setGerProd( $idsGerProd , $idSolicitud , $usersType )
    {
        if ( Session::has( 'maxRepeatIdsGerProd') )
            $maxRepeatIdsGerProd = Session::pull( 'maxRepeatIdsGerProd' );
        foreach ( $idsGerProd as $idGerProd )
        {
            $solGer               = new SolicitudGer;
            $solGer->id           = $solGer->lastId() + 1 ;
            $solGer->id_solicitud = $idSolicitud;
            $solGer->id_gerprod   = $idGerProd;
            $solGer->tipo_usuario = $usersType;
            $solGer->status       = 1 ;
            if ( $usersType == GER_PROD )
                if ( in_array( $idGerProd , $maxRepeatIdsGerProd ) )
                    $solGer->permiso = 1;
                else
                    $solGer->permiso = 2;
            else
                $solGer->permiso = 1;
            if ( ! $solGer->save() )
                return $this->warningException( 'No se pudo derivar al Ger. Prod N°: ' . $idGerProd , __FUNCTION__ , __LINE__ , __FILE__ );
        }
        return $this->setRpta( $idsGerProd );
    }

    private function setProductsAmount( $solProductIds , $amount , $fondo , $user_id , $detalle )
    {
        $fondo_total = array();
        $fondos = array();
        if ( $detalle->id_moneda == DOLARES )
            $tc = ChangeRate::getTc();
        foreach ( $solProductIds as $key => $solProductId ) 
        {
            $solProduct = SolicitudProduct::find( $solProductId );
            $solProduct->monto_asignado = round( $amount[ $key ] , 2 , PHP_ROUND_HALF_DOWN ) ;
            if ( $fondo != 0 )
            {
                $fData = explode( ',' , $fondo[ $key ] );
                $user = \Auth::user();
                if ( $user->type == SUP || $user->type == GER_PROD )
                {
                    $user = \Auth::user();
                    if( isset( $fData[2] ) )
                    {
                        $user = \User::find( $fData[2] );
                        $user_id = $user->id; 
                    }
                }
                else
                {
                    $user_id = $solProduct->id_fondo_user;
                    $user = \User::find( $user_id );
                }
                if ( $user->type == SUP )
                    $subFondo = FondosSupervisor::where( 'subcategoria_id' , $fData[ 0 ] )->where( 'marca_id' , $fData[ 1 ] )->where( 'supervisor_id' , $user_id )->first();
                else
                    $subFondo = Fondos::where( 'fondos_subcategoria_id' , $fData[ 0 ] )->where( 'marca_id' , $fData[ 1 ] )->first();

                $fondos[ $fData[ 0 ] ][ $fData[ 1 ] ] = $subFondo; 

                if ( $detalle->id_moneda == DOLARES )
                    $monto_soles = round( $solProduct->monto_asignado * $tc->compra , 2 , PHP_ROUND_HALF_DOWN );
                else
                    $monto_soles = $solProduct->monto_asignado;

                if ( isset( $fondo_total[ $fData[ 0 ] ][ $fData[ 1 ] ] ) )
                    $fondo_total[ $fData[ 0 ] ][ $fData[ 1 ] ] += $monto_soles;
                else
                    $fondo_total[ $fData[ 0 ] ][ $fData[ 1 ] ] = $monto_soles;
                $solProduct->id_fondo = $fData[ 0 ];
                $solProduct->id_fondo_producto = $fData[ 1 ];
                $solProduct->id_fondo_user = ( isset( $fData[ 2 ] ) ) ? $fData[ 2 ] : $user_id ;
            }
            $solProduct->save();
        }

        if ( $fondo != 0 )
        {
            $fondoCategoria = array_unique( array_keys( $fondos ) );
            if ( count( $fondoCategoria ) != 1 )
                return $this->warningException( 'No es posible seleccionar Fondos de Diferentes Categorias por Solicitud' , __FUNCTION__ , __LINE__ , __FILE__ );
            foreach( $fondos as $key1 => $fondos_categoria )
                foreach( $fondos_categoria as $key2 => $fondo_producto )
                {
                    $marca = Marca::find( $key2 );
                    if ( $fondo_producto->saldo < $fondo_total[ $key1 ][ $key2 ] )
                        return $this->warningException( 'El Fondo asignado ' . $fondo_producto->subCategoria->descripcion . ' | ' . $marca->descripcion . ' solo cuenta con S/.' . $fondo_producto->saldo . ' el cual no es suficiente para completar el registro , se requiere un monto de S/.' . $fondo_total[ $key1 ][ $key2 ] . ' en total ' , __FUNCTION__ , __LINE__ , __FILE__ );       
                }
        }

        return $this->setRpta();
    }

    private function setProducts( $idSolicitud , $idsProducto )
    {
        foreach ( $idsProducto as $idProducto ) 
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

    private function unsetRelations( $solicitud )
    {
        $detalle = $solicitud->detalle;
        $solicitud->products()->delete();
        $solicitud->clients()->delete();
        $solicitud->gerente()->delete();
        $detalle->delete();
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
                if ( isset( $inputs[ 'idsolicitud' ] ) )
                {
                    $solicitud = Solicitud::find( $inputs[ 'idsolicitud' ] );
                    $detalle   = $solicitud->detalle;
                    $this->unsetRelations( $solicitud );   
                }
                else
                {
                    $solicitud = new Solicitud;
                    $solicitud->id = $solicitud->lastId() + 1;
                }
                $detalle               = new SolicitudDetalle;
                $detalle->id           = $detalle->lastId() + 1;
                $solicitud->id_detalle = $detalle->id;
                $solicitud->token      = sha1( md5( uniqid( $solicitud->id , true ) ) );   
                $this->setSolicitud( $solicitud , $inputs );
                $solicitud->save();
                $jDetalle = new stdClass();
                $this->setJsonDetalle( $jDetalle , $inputs );
                $detalle->detalle      = json_encode( $jDetalle );
                $this->setDetalle( $detalle , $inputs );
                $detalle->save();
                $middleRpta = $this->setClients( $solicitud->id , $inputs['clientes'] , $inputs['tipos_cliente'] );
                if ( $middleRpta[status] == ok )
                {
                    $middleRpta = $this->setProducts( $solicitud->id , $inputs['productos'] );
                    if ( $middleRpta[status] == ok )
                    {
                        $middleRpta = $this->toUser( $inputs['inversion'] , $inputs['productos'] , 1 );
                        if ( $middleRpta[ status ] == ok )
                        {
                            $middleRpta = $this->setGerProd( $middleRpta[ data ][ 'iduser' ] , $solicitud->id , $middleRpta[ data ][ 'tipousuario'] );
                            if ( $middleRpta[ status ] == ok )
                            {
                                $middleRpta = $this->setStatus( 0 , PENDIENTE, Auth::user()->id , $middleRpta[ data ] , $solicitud->id );
                                if ( $middleRpta[status] == ok )
                                {
                                    Session::put( 'state' , R_PENDIENTE );
                                    DB::commit();
                                    return $middleRpta;
                                }
                            }
                        }
                    }
                }
            }
            DB::rollback();
            return $middleRpta;
        }
        catch ( Oci8Exception $e )
        {
            DB::rollback();
            return $this->internalException( $e , __FUNCTION__ , DB );
        }
        catch ( Exception $e)
        {
            DB::rollback();
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    private function setJsonDetalle( &$jDetalle , $inputs )  
    {
        $jDetalle->monto_solicitado  =  round( $inputs['monto'] , 2 , PHP_ROUND_HALF_DOWN );
        $jDetalle->fecha_entrega     =  $inputs['fecha'];            
        $this->setPago( $jDetalle , $inputs['pago'] , $inputs['ruc'] );    
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

    private function textLv( $solicitud )
    {
        if ( $solicitud->idtiposolicitud == SOL_REP ) 
            return $this->textAccepted( $solicitud ).' - '.$solicitud->titulo.' - '.$this->textClients( $solicitud );
        else
            return $this->textAccepted( $solicitud ) . ' - ' . $solicitud->titulo;
    }

    private function textAccepted( $solicitud )
    {
        if ( $solicitud->idtiposolicitud == SOL_REP )
            if ( $solicitud->approvedHistory->user->type == SUP )
                return $solicitud->approvedHistory->user->sup->full_name;
            elseif ( $solicitud->approvedHistory->user->type == GER_PROD )
                return $solicitud->approvedHistory->user->gerProd->full_name;
            elseif ( $solicitud->approvedHistory->user->type == REP_MED )
                return $solicitud->approvedHistory->user->rm->full_name;
            elseif ( ! is_null( $solicitud->approvedHistory->user->simApp ) )
                return $solicitud->approvedHistory->user->person->full_name;
            else
                return 'No Registrado';
        else if ( $solicitud->idtiposolicitud == SOL_INST )
            return $solicitud->createdBy->person->full_name;
    }

    private function textClients( $solicitud )
    {
        $clientes = array();
        foreach( $solicitud->clients as $client )
            $clientes[] = $client->{$client->clientType->relacion}->full_name ;     
        return implode(',',$clientes);
    }

    public function cancelSolicitud()
    {
        try
        {
            DB::beginTransaction();
            $inputs = Input::all();
            $rules = array( 'idsolicitud' => 'required|numeric|min:1|exists:solicitud,id' , 'observacion' => 'required|string|min:10' );
            $validator = Validator::make($inputs, $rules);
            
            if ( $validator->fails() ) 
                return $this->warningException( substr($this->msgValidator($validator) , 0 , -1 ) , __FUNCTION__ , __LINE__ , __FILE__ );
            
            $solicitud = Solicitud::find( $inputs['idsolicitud'] );
            if ( $solicitud->idtiposolicitud == SOL_INST )
            {
                $periodo = $solicitud->detalle->periodo;
                if ( $periodo->status == BLOCKED )
                    return $this->warningException( 'No se puede eliminar las solicitudes del periodo: '.$periodo->aniomes , __FUNCTION__ , __LINE__ , __FILE__ );
                if ( count ( Solicitud::solInst( $periodo->aniomes ) ) == 1 )
                    Periodo::inhabilitar( $periodo->aniomes ); 
            }
            elseif ( $solicitud->idtiposolicitud == SOL_REP )
            {
                if ( ! in_array( $solicitud->id_estado , State::getCancelStates() ) )
                    return $this->warningException( 'No se puede cancelar las solicitudes en esta etapa: '.$solicitud->state->nombre , __FUNCTION__ , __LINE__ , __FILE__ );        
            }
            else
                return $this->warningException( 'Tipo de Solicitud: '.$solicitud->idtiposolicitud.' no registrado' , __FUNCTION__ , __LINE__ , __FILE__ );
            
            $oldIdEstado = $solicitud->id_estado;
            if ( Auth::user()->id == $solicitud->created_by )
                $solicitud->id_estado = CANCELADO;
            else
                $solicitud->id_estado = RECHAZADO;
            $solicitud->observacion = $inputs['observacion'];
            $solicitud->status   = 1;
            $solicitud->save();

            $rpta = $this->setStatus( $oldIdEstado , $solicitud->id_estado , Auth::user()->id , $solicitud->created_by , $solicitud->id );
            if ( $rpta[status] === ok )
            {
                DB::commit();
                $rpta['Type'] = $solicitud->idtiposolicitud;
                if ( $solicitud->id_estado == RECHAZADO )
                    $rpta['Type'] = 3;
                Session::put( 'state' , R_NO_AUTORIZADO );
                return $rpta;
            }
            DB::rollback();
            return $rpta;
        }
        catch ( Oci8Exception $e )
        {
            DB::rollback();
            return $this->internalException( $e , __FUNCTION__ , DB );
        }
        catch (Exception $e)
        {
            DB::rollback();
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    private function verifyPolicy( $solicitud , $monto )
    {
        $type = array( Auth::user()->type , Auth::user()->tempType() );
        $aprovalPolicy = AprovalPolicy::getUserInvestmentPolicy( $solicitud->id_inversion , $type , $solicitud->histories->count() );
        if ( is_null( $aprovalPolicy ) )
            return $this->warningException( 'No se encontro la politica de aprobacion para la inversion: '. $solicitud->id_inversion . ' y su rol: ' . Auth::user()->type , __FUNCTION__ , __LINE__ , __FILE__ );    
        if ( is_null( $aprovalPolicy->desde ) && is_null( $aprovalPolicy->hasta ) )
            return $this->setRpta( ACEPTADO ); // return $this->setRpta( DERIVADO );
        else
        {
            if ( $solicitud->detalle->id_moneda == DOLARES )
                $monto = $monto * ChangeRate::getTc()->compra;
            if ( $monto > $aprovalPolicy->hasta )
            {
                return $this->setRpta( ACEPTADO );
                return $this->warningException( 'Por Politica solo puede aceptar para este Tipo de Inversion montos menores a: '. $aprovalPolicy->hasta , __FUNCTION__ , __LINE__ , __FILE__ );
            }
            else if ( $monto < $aprovalPolicy->desde )
                return $this->warningException( 'Por Politica solo puede aceptar para este Tipo de Inversion montos mayores a: '. $aprovalPolicy->desde , __FUNCTION__ , __LINE__ , __FILE__ );
            else
                return $this->setRpta( APROBADO );
        }
    }

    private function toUser( $investmentType , $idsProducto , $order )
    {
        $aprovalPolicy = AprovalPolicy::getToUser( $investmentType , $order );
        if ( is_null( $aprovalPolicy ) )
            return $this->warningException( 'La inversion no  tiene politica de aprobacion' , __FUNCTION__ , __LINE__ , __FILE__ );
        $userType = $aprovalPolicy->tipo_usuario;
        $msg= '';
        if ( ! is_null( $aprovalPolicy->desde ) || ! is_null( $aprovalPolicy->hasta ) )
            $msg .= ' para montos ' . ( is_null( $aprovalPolicy->desde ) ? '' : 'desde S/.' . $aprovalPolicy->desde . ' ' ) . ( is_null( $aprovalPolicy->hasta ) ? '' : 'hasta S/.' . $aprovalPolicy->hasta ) ; 
        if ( $userType == SUP )
        {
            if ( Auth::user()->type === REP_MED )
                $idsUser = array( Rm::getSup( Auth::user()->id )->iduser );
            else if ( Auth::user()->type === SUP )
                $idsUser = array( Auth::user()->id );
            else
                $idsUser = Sup::all()->lists( 'iduser');
        }
        else if ( $userType == GER_PROD )
        {
            $idsGerProd = Marca::whereIn( 'id' , $idsProducto )->lists( 'gerente_id' );
            $uniqueIdsGerProd = array_unique( $idsGerProd );
            $repeatIds = array_count_values( $idsGerProd );
            $maxNumberRepeat = max( $repeatIds );
            Session::put( 'maxRepeatIdsGerProd'  , Manager::whereIn( 'id' , array_keys( $repeatIds , $maxNumberRepeat ) )->lists( 'iduser' ) );
            $notRegisterGerProdName = Manager::getGerProdNotRegisteredName( $uniqueIdsGerProd );
            if ( count( $notRegisterGerProdName ) === 0 )
                $idsUser = Manager::whereIn( 'id' , $uniqueIdsGerProd )->lists( 'iduser' );
            else
                return $this->warningException( 'Los siguientes Gerentes de Producto no estan registrados en el sistema: ' . implode( ' , ' , $notRegisterGerProdName ) , __FUNCTION__ , __LINE__ , __FILE__ );
        }
        else
        {
            $user = User::getUserType( $userType );
            if ( count( $user ) === 0 )
                return $this->warningException( 'Se requiere el usuario ' . $aprovalPolicy->userType->descripcion . $msg , __FUNCTION__ , __LINE__ , __FILE__ );
            else                
                $idsUser = $user;
        }
        return $this->setRpta( array( 'iduser' => $idsUser , 'tipousuario' => $userType ) );
    }

    public function massApprovedSolicitudes()
    {
        try
        {
            $inputs = Input::all();
            $rules = array( 'solicitudes' => 'required' );
            $validator = Validator::make( $inputs , $rules );
            if ( $validator->fails() ) 
                return $this->warningException( $this->msg2Validator( $validator ) , __FUNCTION__ , __LINE__ , __FILE__ );
            else
            {
                $status = array( ok => array() , error => array() );
                $message = '';
                foreach($inputs['solicitudes'] as $solicitud )
                {
                    $solicitud = Solicitud::where( 'token' , $solicitud )->first();
                    $inputs = array( 'idsolicitud' => $solicitud->id ,
                                     'monto'       => $solicitud->detalle->monto_actual ,
                                     'producto'    => $solicitud->orderProducts()->lists( 'id' ),
                                     'anotacion'   => $solicitud->anotacion );

                    $solProducts = $solicitud->orderProducts();
                    if ( $solicitud->id_estado == DERIVADO )
                        $inputs[ 'monto_producto' ] = array_fill( 0 , count( $solProducts->get() ) , $inputs[ 'monto' ] / count( $solProducts->get() ) );
                    else
                        $inputs[ 'monto_producto' ] = $solProducts->lists( 'monto_asignado' );
                    $rpta = $this->acceptedSolicitudTransaction( $solicitud->id ,  $inputs );
                    if ( $rpta[status] != ok )
                    {
                        $status[error][] = $solicitud['token'];
                        $message .= $message . 'No se pudo procesar la Solicitud N°: ' . $solicitud->id . ': ' .$rpta[ description ] . '. ';
                    }
                    else
                        $status[ok][] = $solicitud['token'];
                }
                if ( empty( $status[error] ) )
                    return array( status => ok , 'token' => $status , description => 'Se aprobaron las solicitudes seleccionadas' );
                else if ( empty( $status[ ok ] ) )
                    return array( status => danger , 'token' => $status , description => substr( $message , 0 , -1 ) );
                else
                    return array( status => warning , 'token' => $status , description => substr( $message , 0 , -1 ) );
            }
        }
        catch ( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    private function validateInputAcceptSolRep( $inputs )
    {
        $rules = array( 'idsolicitud'    => 'required|integer|min:1|exists:solicitud,id' ,
                        'monto'          => 'required|numeric|min:1' ,
                        'idfondo'        => 'sometimes|integer|min:1|exists:fondo,id' ,
                        'anotacion'      => 'sometimes|string|min:5' ,
                        'producto'       => 'required|array|min:1|each:integer|each:min,1|each:exists,solicitud_producto,id',
                        'monto_producto' => 'required|array|min:1|each:numeric|each:min,1|sumequal:monto',
                        'idresponsable'  => 'sometimes|integer|min:1|exists:outdvp.users,id' );
        $validator = Validator::make( $inputs, $rules );
        if ( $validator->fails() ) 
            return $this->warningException( substr( $this->msgValidator( $validator ) , 0 , -1 ) , __FUNCTION__ , __LINE__ , __FILE__ );
        else
            return $this->setRpta();
    }

    private function validateFondo( $idFondo , $monto , $tipoMoneda )
    {
        $fondo = Fondo::find( $idFondo );
        if ( $fondo->id_moneda == $tipoMoneda )
        {
            if ( $monto > $fondo->saldo )
                return $this->warningException( 'El monto asignado supera el saldo del fondo' , __FUNCTION__ , __LINE__ , __FILE__ );
        }
        else
        {
            $tc = ChangeRate::getTc();
            if ( $tipoMoneda == DOLARES )
                if ( ( $monto * $tc->compra ) > $fondo->saldo )
                        return $this->warningException( 'El monto asignado supera el saldo del fondo' , __FUNCTION__ , __LINE__ , __FILE__ );
            else if ( $tipoMoneda == SOLES )
                if ( ( $monto / $tc->venta ) > $fondo->saldo )
                    return $this->warningException( 'El monto asignado supera el saldo del fondo' , __FUNCTION__ , __LINE__ , __FILE__ );
            else
                return $this->warningException( 'No existes tipo de cambio para la moneda: '.$tipoMoneda , __FUNCTION__ , __LINE__ , __FILE__ );
        }
        return $this->setRpta();
    }


    private function acceptedSolicitudTransaction( $idSolicitud , $inputs )
    {
        DB::beginTransaction();
        $middleRpta = $this->validateInputAcceptSolRep( $inputs );
        if ( $middleRpta[ status] === ok )
        {
            $solicitud   = Solicitud::find( $idSolicitud );
            $middleRpta = $this->verifyPolicy( $solicitud , $inputs[ 'monto' ] );
            if ( $middleRpta[ status ] == ok )
            {
                $oldIdEstado = $solicitud->id_estado;    
                $solicitud->id_estado      = $middleRpta[ data ];
                $solicitud->status         = ACTIVE;
                if ( isset( $inputs['idresponsable'] ) )
                    $solicitud->id_user_assign = $inputs['idresponsable'];
                $solicitud->anotacion      = $inputs['anotacion'];
                $solicitud->save();
                    
                if ( $solicitud->id_estado != DERIVADO )
                {
                    $solDetalle = $solicitud->detalle;
                    
                    if ( isset( $inputs[ 'idfondo'] ) )
                    {
                        if ( in_array( Auth::user()->type , array( SUP , GER_PROD ) ) && $inputs['idfondo'] == 0 )
                            return $this->warningException( 'No se ha especificado el Fondo' , __FUNCTION__ , __LINE__ , __FILE__ ); 
                        $middleRpta = $this->validateFondo( $inputs[ 'idfondo'] , $inputs[ 'monto' ] , $solDetalle->id_moneda );
                        if ( $middleRpta[ status ] != ok )
                            return $middleRpta;
                    }
                    else
                        if ( in_array( Auth::user()->type , array( SUP , GER_PROD ) ) )
                            return $this->warningException( 'No se ha especificado el Fondo' , __FUNCTION__ , __LINE__ , __FILE__ ); 

                    if ( isset( $inputs[ 'idfondo' ] ) )
                        $solDetalle->id_fondo = $inputs['idfondo'];
                    $detalle = json_decode( $solDetalle->detalle );                            
                    $monto = round( $inputs[ 'monto' ] , 2 , PHP_ROUND_HALF_DOWN );
                    if ( $solicitud->id_estado == ACEPTADO )
                        $detalle->monto_aceptado = $monto;
                    else if ( $solicitud->id_estado == APROBADO );
                        $detalle->monto_aprobado = $monto;
                    $solDetalle->detalle = json_encode( $detalle );
                    $solDetalle->save();
                    if ( Auth::user()->type == SUP )
                        if ( $solicitud->createdBy->type == REP_MED )
                            $user = \User::find( $solicitud->createdBy->rm->rmSup->iduser );
                        elseif ( $solicitud->createdBy->type == SUP )
                            $user = \User::find( $solicitud->created_by );
                        else
                            $user = Auth::user();
                    else
                        $user = Auth::user();

                    if ( ! isset( $inputs[ 'fondo-producto' ] ) )
                        $inputs[ 'fondo-producto'] = 0;
                    $middleRpta = $this->setProductsAmount( $inputs[ 'producto' ] , $inputs[ 'monto_producto' ] , $inputs[ 'fondo-producto' ] , $user->id , $solDetalle );
                    
                    if ( $middleRpta[ status ] != ok )
                        return $middleRpta;
                }
                if ( $solicitud->id_estado != APROBADO )
                {
                    $middleRpta = $this->toUser( $solicitud->id_inversion , SolicitudProduct::getSolProducts( $inputs[ 'producto' ] ) , $solicitud->histories->count() + 1 );
                    if ( $middleRpta[ status ] != ok )
                        return $middleRpta;
                    else
                    {
                        $middleRpta = $this->setGerProd( $middleRpta[ data ][ 'iduser' ] , $solicitud->id , $middleRpta[ data ][ 'tipousuario'] );
                        if ( $middleRpta[ status ] == ok )
                            $toUser = $middleRpta[ data ];
                        else
                            return $middleRpta;
                    }
                }
                else if( is_null( $solDetalle->id_fondo ) )
                    return $this->warningException( 'Debe asignar el fondo para aprobar la solicitud' , __FUNCTION__ , __LINE__ , __FILE__ );
                else
                    $toUser = USER_CONTABILIDAD;
                
                $middleRpta = $this->setStatus( $oldIdEstado , $solicitud->id_estado , Auth::user()->id , $toUser , $solicitud->id );
                if ( $middleRpta[ status ] == ok )
                {
                    Session::put( 'state' , $solicitud->state->rangeState->id );
                    DB::commit();
                    //return array( status => warning , description => 'prueba');
                    return $middleRpta ;
                }
            }
        }
        DB::rollback();
        return $middleRpta;
    }

    public function acceptedSolicitude()
    {
        try
        {
            $inputs = Input::all();   
            return $this->acceptedSolicitudTransaction( $inputs[ 'idsolicitud' ] , $inputs );
        }
        catch( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    public function checkSolicitud()
    {
        try
        {
            DB::beginTransaction();
            $inputs = Input::all();
            $solicitud = Solicitud::find( $inputs['idsolicitud'] );
            if ( is_null( $solicitud ) )
                return $this->warningException( 'Cancelado - No se encontro la solicitud con Id: '.$inputs[ 'idsolicitud' ] , __FUNCTION__ , __LINE__ , __FILE__ );
            
            if ( $solicitud->id_estado != APROBADO )
                return $this->warningException( 'Cancelado - No se puede procesar una solicitud que no ha sido Aprobada' , __FUNCTION__ , __LINE__ , __FILE__ );
            
            $oldIdEstado = $solicitud->id_estado;
            if ( $solicitud->detalle->id_motivo == REEMBOLSO )
            {
                $solicitud->id_estado = GASTO_HABILITADO;
                $toUser = $solicitud->id_user_assign;
                $state = R_GASTO;
            }
            else
            {
                $solicitud->id_estado = DEPOSITO_HABILITADO;
                $toUser = USER_TESORERIA;
                $state = R_REVISADO;
            }
            $solicitud->save();
            
            $middleRpta = $this->setStatus( $oldIdEstado , $solicitud->id_estado , Auth::user()->id , $toUser , $solicitud->id );
            if ( $middleRpta[status] == ok )
            {
                Session::put( 'state' , $state );
                DB::commit();
                return $middleRpta;
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

    /** ---------------------------------------------  Contabilidad -------------------------------------------------*/

    public function getTypeDoc( $id )
    {
        return json_decode(ProofType::find($id)->toJson());
    }

    public function createSeatElement( $cuentaMkt , $solicitudId , $account_number , $cod_snt , $fecha_origen , $iva , $cod_prov , $nom_prov, $cod, $ruc, $prefijo, $numero, $dc, $monto, $marca, $descripcion, $tipo_responsable, $type )
    {   
        return array(  'cuentaMkt'         => $cuentaMkt ,
                       'solicitudId'       => intval($solicitudId),
                       'numero_cuenta'     => $account_number,
                       'codigo_sunat'      => $cod_snt,
                       'fec_origen'        => $fecha_origen,
                       'iva'               => $iva,
                       'cod_prov'          => $cod_prov,
                       'nombre_proveedor'  => $nom_prov,
                       'cod'               => $cod,
                       'ruc'               => $ruc,
                       'prefijo'           => $prefijo,
                       'cbte_proveedor'    => $numero,
                       'dc'                => $dc,
                       'importe'           => $monto,
                       'leyenda'           => $marca,
                       'leyenda_variable'  => $descripcion,
                       'tipo_responsable'  => $tipo_responsable,
                       'type'              => $type );
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
            $total_percepciones = 0;

            foreach( $solicitud->documentList as $expense )
            {
                        
                $comprobante             =  $this->getTypeDoc( $expense->idcomprobante );
                $desc = substr( $comprobante->descripcion , 0 , 1 ) . '/' . $expense->num_prefijo . '-' . $expense->num_serie .' '. $expense->razon ;
                $description_detraccion_reembolso = 'VARIOS ' . $desc;
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
                        $description_seat_item               = strtoupper( $username .' '. $itemElement->cantidad .' '. $itemElement->descripcion );
                        $description_seat_igv                = strtoupper( $expense->razon );
                        $description_seat_repair_base        = strtoupper( $username .' '. $expense->descripcion . '-REP ' . $desc );
                        $description_seat_repair_deposit     = strtoupper( 'REPARO IGV MKT '. $desc );
                        $description_seat_retencion_base     = strtoupper( 'ENTREGAS A RENDIR CTA A TERCER ' . $desc );
                        $description_seat_retencion_deposit  = strtoupper( 'RETENCION ' . $desc );
                        $description_seat_detraccion_deposit = strtoupper( 'DETRACCION ' . $desc );
                        
                        // ASIENTO ITEM
                        $seatList[] = $this->createSeatElement( $cuentaMkt , $solicitud->id , $cuentaExpense , $comprobante->cta_sunat, $fecha_origen , 
                                                                ASIENTO_GASTO_IVA_BASE, ASIENTO_GASTO_COD_PROV_IGV, $expense->razon, ASIENTO_GASTO_COD_IGV , 
                                                                $expense->ruc, $expense->num_prefijo, $expense->num_serie, ASIENTO_GASTO_BASE , $itemElement->importe , 
                                                                $marca , $description_seat_item , $tipo_responsable , '' );
                        $total_neto += $itemElement->importe;
                    }
                    //ASIENTO DE IGV
                    if ( $expense->igv != 0 )
                        $seatList[] = $this->createSeatElement( $cuentaMkt , $solicitud->id , $cuentaExpense , $comprobante->cta_sunat , $fecha_origen , ASIENTO_GASTO_IVA_IGV , ASIENTO_GASTO_COD_PROV_IGV , $expense->razon , ASIENTO_GASTO_COD_IGV , $expense->ruc , $expense->num_prefijo , $expense->num_serie , ASIENTO_GASTO_BASE , $expense->igv , $marca , $description_seat_igv , $tipo_responsable , 'IGV' );
                    
                    //ASIENTO IMPUESTO SERVICIO
                    if( !( $expense->imp_serv == null || $expense->imp_serv == 0 || $expense->imp_serv == '' ) )
                    {
                        $porcentaje = $total_neto / $expense->imp_serv;
                        $description_seat_tax_service    = strtoupper('SERVICIO '. $porcentaje .'% '. $expense->descripcion);
                        $seatList[] = $this->createSeatElement( $cuentaMkt , $solicitud->id, '', $cuentaExpense , '', $fecha_origen, '', '', '', '', '', '', '', ASIENTO_GASTO_BASE, $expense->imp_serv, $marca, $description_seat_tax_service, '', 'SER');       
                    }
                    //ASIENTO REPARO
                    if ( $expense->reparo == 1 )
                    {   
                        $seatList[] = $this->createSeatElement( $cuentaMkt , $solicitud->id , CUENTA_REPARO_COMPRAS, '', $fecha_origen, '', '', '', '', '', '', '', ASIENTO_GASTO_BASE, $expense->igv, $marca, $description_seat_repair_base, '', 'REP');
                        $seatList[] = $this->createSeatElement( $cuentaMkt , $solicitud->id , CUENTA_REPARO_GOBIERNO, '', $fecha_origen, '', '', '', '', '', '', '', ASIENTO_GASTO_DEPOSITO, $expense->igv, $marca, $description_seat_repair_deposit, '', 'REP');
                    }

                    //ASIENTO RETENCION
                    if ( $expense->idtipotributo == 1 )
                    {
                        $seatList[] = $this->createSeatElement( $cuentaMkt , $solicitud->id , CUENTA_RETENCION_DEBE , '', $fecha_origen, '', '', '', '', '', '', '', ASIENTO_GASTO_BASE, $expense->monto_tributo , $marca, $description_seat_retencion_base , '' , 'RET' );
                        $seatList[] = $this->createSeatElement( $cuentaMkt , $solicitud->id , CUENTA_RETENCION_HABER , '', $fecha_origen, '', '', '', '', '', '', '', ASIENTO_GASTO_DEPOSITO, $expense->monto_tributo , $marca, $description_seat_retencion_deposit , '' , 'RET');    
                    }

                    //ASIENTO DETRACCION
                    if ( $expense->idtipotributo == 2 )
                    {
                        $total_percepciones += $expense->monto_tributo;
                        $seatList[] = $this->createSeatElement( $cuentaMkt , $solicitud->id , CUENTA_DETRACCION_HABER , '', $fecha_origen, '', '', '', '', '', '', '', ASIENTO_GASTO_DEPOSITO, $expense->monto_tributo , $marca, $description_seat_detraccion_deposit , '' , 'DET');    
                    }

                    //ASIENTO DETRACCION REEMBOLSO
                    if ( $expense->idtipotributo == 2 && $solicitud->id_motivo == REEMBOLSO )
                    {
                        $total_percepciones += ( $expense->monto - $expense->monto_tributo );
                        $seatList[] = $this->createSeatElement( $cuentaMkt , $solicitud->id , CUENTA_DETRACCION_REEMBOLSO , '', $fecha_origen, '', '', '', '', '', '', '', ASIENTO_GASTO_BASE , $expense->monto - $expense->monto_tributo , $marca, $description_detraccion_reembolso , '' , 'DET');                                    
                    }
                }
                else //TODOS LOS OTROS DOCUMENTOS
                {
                    $description_seat_renta4ta_deposit   = strtoupper( 'RENTA 4TA CATEGORIA ' . $desc );
            
                    //ASIENTO DOCUMENT - NO ITEM
                    $description_seat_other_doc = strtoupper( $username.' '. $expense->descripcion );
                    $seatList[] = $this->createSeatElement($cuentaMkt , $solicitud->id , $cuentaExpense , $comprobante->cta_sunat, $fecha_origen, ASIENTO_GASTO_IVA_BASE, ASIENTO_GASTO_COD_PROV, $expense->razon, ASIENTO_GASTO_COD, $expense->ruc, $expense->num_prefijo, $expense->num_serie, ASIENTO_GASTO_BASE, $expense->monto, $marca, $description_seat_other_doc, $tipo_responsable, ''); 
                
                    //ASIENTO IMPUESTO A LA RENTA
                    if ( $expense->idtipotributo == 1 )
                    {
                        $total_percepciones += $expense->monto_tributo;
                        $seatList[] = $this->createSeatElement( $cuentaMkt , $solicitud->id , CUENTA_RENTA_4TA_HABER , '', $fecha_origen, '', '', '', '', '', '', '', ASIENTO_GASTO_DEPOSITO, $expense->monto_tributo , $marca, $description_seat_renta4ta_deposit , '' , 'RENTA');        
                    }

                    //ASIENTO IMPUESTO A LA RENTA REEMBOLSO
                    if ( $expense->idtipotributo == 1 && $solicitud->id_motivo == REEMBOLSO )
                    {
                        $total_percepciones += ( $expense->monto - $expense->monto_tributo );
                        $seatList[] = $this->createSeatElement( $cuentaMkt , $solicitud->id , CUENTA_DETRACCION_REEMBOLSO , '' , $fecha_origen , '' , '' , '' , 
                                                                '', '', '', '' , ASIENTO_GASTO_DEPOSITO , $expense->monto - $expense->monto_tributo , $marca , 
                                                                $description_seat_renta4ta_deposit , '' , 'RENTA' );        
                    }    

                }
            }

            // CONTRAPARTE ASIENTO DE ANTICIPO
            $description_seat_back = strtoupper( $username .' '. $solicitud->titulo );
            $seatList[]  = $this->createSeatElement( $cuentaMkt , $solicitud->id , $cuentaMkt , '', $fecha_origen, '', '', '', '', '', '', '', ASIENTO_GASTO_DEPOSITO , json_decode( $solicitud->detalle->detalle )->monto_aprobado - $total_percepciones , '', $description_seat_back, '', 'CAN');        
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
            'clientes'    => $clientes,
            'typeProof'   => $typeProof,
            'seats'       => json_decode( json_encode( $seatList ) )
        );

        if( isset( $resultSeats['error'] ) )
        {
            $tempArray  = array( 'error' => $resultSeats['error'] );
            $data       = array_merge( $data , $tempArray );
        }    
        return View::make( 'Dmkt.Cont.expense_seat' , $data );
    }

    // IDKC: CHANGE STATUS => GENERADO
    public function saveSeatExpense()
    {
        try
        {
            DB::beginTransaction();
            $dataInputs  = Input::all();
            if ( isset( $dataInputs[ 'seatList' ] ) )
                foreach ( $dataInputs['seatList'] as $key => $seatItem ) 
                {
                    $seat = new Entry;
                    $seat->id           = $seat->lastId() + 1;
                    $seat->num_cuenta   = $seatItem['numero_cuenta'];
                    $seat->cc           = $seatItem['codigo_sunat'];
                    \Log::error( $seatItem[ 'fec_origen'] );
                    $fecha_seat_origen = Carbon::createFromFormat( 'd/m/Y' , $seatItem['fec_origen'] );
                    $seat->fec_origen   = $fecha_seat_origen->toDateString();
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
                    $seat->id_solicitud = $seatItem['solicitudId'];
                    $seat->tipo_asiento = ASIENTO_GASTO_TIPO;
                    \Log::error( json_encode( $seat ) );
                    $seat->save();
                }

            $solicitud = Solicitud::find( $dataInputs['idsolicitud'] );
            $oldIdEstado = $solicitud->id_estado;
            if ( $solicitud->detalle->id_motivo == REEMBOLSO )
                $solicitud->id_estado = DEPOSITO_HABILITADO;
            else
                $solicitud->id_estado = GENERADO;
            $user = Auth::user();
            $solicitud->save();

            /*$detalle = $solicitud->detalle;
            $fondo = $detalle->fondo;
            if ( $fondo->id_moneda = $detalle->id_moneda )
                $fondo->saldo -= $detalle->monto_actual;
            else
            {
                $tc = ChangeRate::getTc();
                if ( $detalle->id_moneda == SOLES )
                    $fondo->saldo -= ( $detalle->monto_actual / $tc->venta );
                elseif ( $detalle->id_moneda == DOLARES )
                    $fondo->saldo -= ( $detalle->monto_actual * $tc->compra );
                else
                    return $this->warningException( 'No existe el registro de la Moneda #: ' . $detalle->id_moneda , __FUNCTION__ , __LINE__ , __FILE__ );
            }
            if ( $fondo->saldo <= 0 )
                return $this->warningException( 'El fondo '. $fondo->nombre . 'solo cuenta con ' . $fondo->saldo . ' el cual es insuficiente para registrar la operacion' , __FUNCTION__ , __LINE__ , __FILE__ );
            $fondo->save();*/
            
            if ( $solicitud->detalle->id_motivo == REEMBOLSO )
                $middleRpta = $this->setStatus( $oldIdEstado, DEPOSITO_HABILITADO , $user->id , USER_TESORERIA , $solicitud->id );    
            else    
                $middleRpta = $this->setStatus( $oldIdEstado, GENERADO , $user->id , $user->id , $solicitud->id );
            
            if ( $middleRpta[status] == ok )
            {
                DB::commit();
                if ( $solicitud->detalle->id_motivo == REEMBOLSO )
                    Session::put( 'state' , R_REVISADO );
                else
                    Session::put( 'state' , R_FINALIZADO );
                return $middleRpta;
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

    public function viewSeatExpense($token)
    {
        $solicitud = Solicitud::where('token', $token)->firstOrFail();
        $expense = Expense::where('idsolicitud',$solicitud->id_solicitud)->get();
        $data = array(
            'solicitude' => $solicitud,
            'expense' => $expense
        );
        return View::make('Dmkt.Cont.register_seat_expense', $data);
    }

    // IDKC: CHANGE STATUS => GASTO HABILITADO

    private function validateInputAdvanceSeat( $inputs )
    {
        $rules = array( 'idsolicitud'    => 'required|integer|min:1|exists:solicitud,id' ,
                        'number_account' => 'required|array|size:2|each:numeric|each:digits,7|each:exists,cuenta,num_cuenta' ,
                        'dc'             => 'required|array|size:2|each:string|each:size,1|each:in,D,C' ,
                        'total'          => 'required|array|size:2|each:numeric|each:min,1' ,
                        'leyenda'        => 'required|string|min:1' );
        $validator = Validator::make( $inputs, $rules );
        if ( $validator->fails() ) 
            return $this->warningException( substr( $this->msgValidator( $validator ) , 0 , -1 ) , __FUNCTION__ , __LINE__ , __FILE__ );
        else
            return $this->setRpta();
    }

    public function generateSeatSolicitude()
    {   
        try
        {
            $middleRpta = array();
            $inputs  = Input::all();
            $middleRpta = $this->validateInputAdvanceSeat( $inputs );
            if ($middleRpta[status] == ok)
            {
                DB::beginTransaction();    
                $solicitud = Solicitud::find( $inputs['idsolicitud'] );
                $oldIdEstado = $solicitud->id_estado;
                $solicitud->id_estado = GASTO_HABILITADO;
                $solicitud->save();
                
                for( $i=0 ; $i < count( $inputs['number_account'] ) ; $i++ )
                {
                        $tbEntry = new Entry;
                        $tbEntry->id = $tbEntry->lastId()+1;
                        $tbEntry->num_cuenta = $inputs['number_account'][$i];
                        $tbEntry->fec_origen = date( 'Y-m-d' , strtotime( (string)$solicitud->detalle->deposit->updated_at ) );
                        $tbEntry->d_c = $inputs['dc'][$i];
                        $tbEntry->importe = $inputs['total'][$i];
                        $tbEntry->leyenda = trim($inputs['leyenda']);
                        $tbEntry->id_solicitud = $inputs['idsolicitud'];
                        $tbEntry->tipo_asiento = TIPO_ASIENTO_ANTICIPO;
                        $tbEntry->save();
                }
                $middleRpta = $this->setStatus( $oldIdEstado, GASTO_HABILITADO, Auth::user()->id, $solicitud->id_user_assign , $solicitud->id );         
                if ($middleRpta[status] === ok )
                {
                    Session::put( 'state' , R_GASTO );
                    DB::commit();
                    return $middleRpta;
                }
                DB::rollback();
            }
            return $middleRpta;
        }
        catch (Exception $e)
        {
            DB::rollback();
            return $this->internalException( $e , __FUNCTION__ );          
        }
    }

    public function findResponsables()
    {
        try
        {
            $inputs = Input::all();   
            $responsables = array();
            $solicitud = Solicitud::find( $inputs[ 'idsolicitud' ]); 
            if ( is_null( $solicitud->id_user_assign ) )
            {
                if ( $solicitud->id_estado == PENDIENTE || $solicitud->id_estado == DERIVADO ) 
                {
                    $asistentes = User::getAsisGer();
                    foreach ( $asistentes as $asistente )
                        array_push( $responsables , $asistente->person );
                    if( $solicitud->createdBy->type == REP_MED )
                        array_push( $responsables , $solicitud->createdBy->rm );
                    else
                    {
                        if ( Auth::user()->type == SUP )
                            $responsables = array_merge( $responsables , Auth::user()->sup->reps->toArray() );
                        else
                            $responsables = array_merge( $responsables , Rm::all()->toArray() );
                    }
                    return $this->setRpta( $responsables );
                }
                else
                    return $this->warningException( __FUNCTION__ , 'No se puede buscar los responsable de la solicitud con Id: '.$solicitud->id. ' debido a que no se encuentra PENDIENTE');
            }
            else 
                return $this->setRpta( '' );    
        }
        catch (Exception $e)
        {
            return $this->internalException($e,__FUNCTION__);
        }
    }

    public function findDocument()
    {
        $data = array( 'proofTypes' => ProofType::order() , 'regimenes' => Regimen::all() );
        return View::make('Dmkt.Cont.documents_menu')->with($data);
    }

    public function showSolicitudeInstitution(){
        if ( in_array( Auth::user()->type , array(ASIS_GER ) ) )
            $state = R_PENDIENTE;
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
        $data = array( 'state'  => $state , 'states' => StateRange::order() , 'warnings' => $mWarning );
        if ( Auth::user()->type == ASIS_GER )
        {
            $data['fondos']  = Fondo::asisGerFondos();                
            $data['activities'] = Activity::order();
        }
        if ( Session::has( 'id_solicitud') )
        {
            $solicitud = Solicitud::find( Session::pull( 'id_solicitud' ) );
            $solicitud->status = ACTIVE ;
            $solicitud->save();
        }
        $alert = new AlertController;
        $data[ 'alert' ] = $alert->alertConsole();
        return View::make('template.User.institucion', $data);
    }

    public function getTimeLine( $id )
    {
        $solicitud = Solicitud::find( $id );
        return View::make( 'template.Modals.timeLine')->with( array( 'solicitud' => $solicitud ) )->render();
    }
    public function album(){
        return View::make('Event.show');
    }
    public function getEventList(){
        // dd(Input::all());
        $start = Input::get("date_start");
        $end = Input::get("date_end");
        $data =array();
        $data['events'] = Event::whereRaw("created_at between to_date('$start','DD-MM-YY') and to_date('$end','DD-MM-YY')+1")->get();
        return View::make('Event.album',$data);
    }

    public function photos(){
        $event_id = Input::get('event_id');
        $photos = FotoEventos::where('event_id', $event_id)->get();
        $view = View::make('Event.carousel', compact('photos'))->render();
        return $view;
    }

    public function createEventHandler()
    {
        try
        {
            $result = array();
            $input  = Input::all();
            $rules  = array( 'name' => 'required|unique:event',
                             'description'  => 'required',            
                             'event_date'   => 'required',
                             'solicitud_id' => 'required' );
            $validator = Validator::make(Input::all(), $rules);
            if ($validator->fails())
            {
                $result['status']   = 'error';
                $result['message'] = DATOS_INVALIDOS;
                $result['detail']   = $validator->messages();
            }
            else
            {        
                $newEvent = new Event();
                $newId    = $newEvent->searchId() + 1;
                $input    = array("id" => $newId) + $input;
                $input['place'] = is_null($input['place']) ? null : $input['place'];
                if($newEvent->create($input))
                {
                    $result['status']   = 'ok';
                    $result['message'] = CREADO_SATISFACTORIAMENTE;
                    $result['id'] = $newId;
                }
                else
                {
                    $result['status']   = 'error';
                    $result['message'] = DB_NOT_INSERT;
                }        
            }
            return $result;
        }
        catch(Exception $e)
        {
            return $e;
        }
    }

    public function viewTestUploadImgSave()
    {

        $fileList = Input::file('image');
        $event_id = Input::get('event_id');
        if ( count($fileList) == 0 )
            return Response::json(array( 'success'   => false , 'errors'    => 'No se pudo Cargar Archivo' ));
        else 
        {
            $resultFileList = array();    
            foreach ($fileList as $fileKey => $fileItem) 
            {    
                $destinationPath    = FILESTORAGE_DIR;
                $fileName           = pathinfo($fileItem->getClientOriginalName(), PATHINFO_FILENAME);
                $fileExt            = pathinfo($fileItem->getClientOriginalName(), PATHINFO_EXTENSION);
                $fileNameMD5        = md5(uniqid(rand(), true));
                $fileStorage                = new FileStorage;
                $fileStorage->id            = $fileNameMD5;
                $fileStorage->name          = pathinfo($fileItem->getClientOriginalName(), PATHINFO_FILENAME);
                $fileStorage->extension     = $fileExt;
                $fileStorage->directory     = $destinationPath;
                $fileStorage->app           = APP_ID;
                $fileStorage->event_id      = $event_id;
                $fileStorage->save();
                $fileItem->move($destinationPath, $fileNameMD5.'.'.$fileExt);
                $resultFileList[] = array( 'id' => $fileNameMD5 , 'name' => asset($destinationPath.$fileNameMD5 . '.' . $fileExt ) );
            }
            return Response::json(array( 'success' => true , 'fileList' => $resultFileList ));
        }
    }
}