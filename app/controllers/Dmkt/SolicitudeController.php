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
        return View::make('template.User.show',$data);   
    }

    public function newSolicitude()
    {
        $data = array( 'reasons'     => Reason::all() ,
                       'activities'  => Activity::order(),
                       'payments'    => TypePayment::all(),
                       'currencies'  => TypeMoney::all(),
                       'families'    => Marca::orderBy('descripcion', 'ASC')->get(),
                       'investments' => InvestmentType::order() );

        return View::make('Dmkt.Register.solicitud', $data);
    }    

    public function editSolicitude($token)
    {
        $data = array( 'solicitud'   => Solicitud::where('token', $token)->firstOrFail(),
                       'reasons'     => Reason::all(),
                       'activities'  => Activity::order(),
                       'payments'    => TypePayment::all(),
                       'currencies'  => TypeMoney::all(),
                       'families'    => Marca::orderBy('descripcion', 'ASC')->get(),
                       'investments' => InvestmentType::order() );

        $data[ 'detalle' ] = $data['solicitud']->detalle ;
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
            else
            {
                $detalle = $solicitud->detalle;
                $politicType = $solicitud->aprovalPolicy( $solicitud->histories->count() )->tipo_usuario;
                $data = array( 'solicitud' => $solicitud , 'detalle' => $detalle );
                if ( in_array( $solicitud->id_estado , array( PENDIENTE , DERIVADO , ACEPTADO ) )
                    && in_array( $politicType , array( Auth::user()->type , Auth::user()->tempType() ) )
                    && ( array_intersect ( array( Auth::user()->id , Auth::user()->tempId() ) , $solicitud->managerEdit( $politicType )->lists( 'id_gerprod' ) ) ) )
                {
                    $politicStatus = TRUE;
                    $typeUser = $solicitud->aprovalPolicy( $solicitud->histories->count() )->tipo_usuario;
                    $solicitud->status = BLOCKED;
                    $solicitud->save();
                    $data[ 'fondos' ] = Fondo::getFunds( $typeUser );
                    if ( ! is_null( $solicitud->detalle->fondo ) )
                    {
                        $data[ 'fondos' ]->push( $solicitud->detalle->fondo );
                        $data['fondos'] = $data[ 'fondos' ]->unique();
                    }
                    $data['solicitud']->status = 1;
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
                elseif ( ! is_null( $solicitud->expenseHistory ) && $user->id == $solicitud->id_user_assign )
                {
                    $data = array_merge( $data , $this->expenseData( $solicitud , $detalle->monto_aprobado ) );
                    $data['igv'] = Table::getIGV();
                }
                Session::put( 'state' , $data[ 'solicitud' ]->state->id_estado );
                $data[ 'politicStatus' ] = $politicStatus;
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
        $data = array( 'typeProof'   => ProofType::orderBy('id','asc')->get(),
                       'typeExpense' => ExpenseType::order(),
                       'date'        => $this->getDay() );
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
        $rules = array( 'idsolicitud'   => 'integer|min:1|exists:solicitud,id' ,
                        'motivo'        => 'required|integer|min:1|exists:motivo,id' ,
                        'inversion'     => 'required|integer|min:1|exists:tipo_inversion,id' ,
                        'actividad'     => 'required|integer|min:1|exists:tipo_actividad,id' ,
                        'titulo'        => 'required|string|min:1|max:20',
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
        
        $activity = Activity::find( $inputs[ 'actividad' ] );
        
        $validator->sometimes( 'factura' , 'required|image|max:900' , function( $input ) use( $activity )
        {
            return ( ( trim( $input->idsolicitud ) === '' && $activity->imagen == 1 ) || ( trim( $input->idsolicitud ) !== '' &&  $activity->imagen == 1 && $input->factura ) );
        });

        $validator->sometimes( 'monto_factura' , 'required|numeric|min:1' , function( $input ) use( $activity )
        {
            return $activity->imagen == 1;
        });

        $validator->sometimes( 'ruc' , 'required|numeric|digits:11' , function( $input )
        {
            return $input->pago == PAGO_CHEQUE;
        });
        if ( $validator->fails() ) 
            return $this->warningException( substr( $this->msgValidator( $validator ) , 0 , -1 ) , __FUNCTION__ , __LINE__ , __FILE__ );
        return $this->setRpta( $activity );
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

    private function setProductsAmount( $solProductIds , $amount )
    {
        foreach ( $solProductIds as $key => $solProductId ) 
        {
            $solProduct = SolicitudProduct::find( $solProductId );
            $solProduct->monto_asignado = round( $amount[ $key ] , 2 , PHP_ROUND_HALF_DOWN ) ;
            $solProduct->save();
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

    private function setInvoice( $jDetalle , $inputs )
    {
        $actividad = Activity::find( $inputs[ 'actividad' ] );
        if ( $actividad->imagen == 1 )
            $this->setImage( $jDetalle , $inputs[ 'factura' ] , $inputs[ 'monto_factura'] );
    }

    private function setImage( &$jDetalle , $image , $monto )
    {
        if ( is_object( $image ) )
        {
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();
            $path = public_path( 'img/reembolso/' . $filename);
            Image::make( $image->getRealPath() )->resize( WIDTH , HEIGHT )->save( $path );
            $jDetalle->image = $filename;
        }
        $jDetalle->monto_factura = round( $monto , 2 , PHP_ROUND_HALF_DOWN );
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
                    $actividad = $middleRpta[ data ];
                    if ( ( $actividad->imagen == 1 && $solicitud->activity->imagen == 1 ) )
                        if ( is_object( $inputs[ 'factura'] ) ) 
                            $imagePath = public_path( 'img/reembolso/' . $detalle->image );
                        else
                            $jImage = $detalle->image;
                    else if ( $actividad->imagen == 1 )
                        $newImage = 1;        
                    else if ( $solicitud->activity->imagen == 1 )
                        $imagePath = public_path( 'img/reembolso/' . $detalle->image );
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
                if ( isset( $jImage) )
                    $jDetalle->image = $jImage;
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
                                    if ( isset( $imagePath ) )
                                        @unlink( $imagePath );
                                    return $middleRpta;
                                }
                            }
                        }
                    }
                }
                if ( ( $solicitud->activity->imagen == 1 && ! isset( $jImage ) ) || $newImage === 1 )
                    @unlink( public_path( 'img/reembolso/' . $detalle->image ) );
            }
            DB::rollback();
            return $middleRpta;
        }
        catch ( \yajra\Pdo\Oci8\Exceptions\Oci8Exception $e )
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
        $this->setInvoice( $jDetalle , $inputs );                    
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

    public function searchDmkt()
    {
        try
        {
            $inputs = Input::all();
            $today = getdate();
            $m = $today['mday'] . '-' . $today['mon'] . '-' . $today['year'];      
            if ( Input::has('idstate'))
                $estado = $inputs['idstate'];
            else
                $estado = R_TODOS;
            if ( Input::has('date_start'))
                $start = $inputs['date_start'];
            else
                $start = date('01-m-Y', strtotime($m));
            if (Input::has('date_end'))
                $end = $inputs['date_end'];
            else
                $end = date('t-m-Y', strtotime($m));
            $rpta = $this->userType();
            if ( $rpta[status] == ok )
                $middleRpta = $this->searchSolicituds( $estado , $rpta[data] , $start , $end );
                if ( $middleRpta[status] == ok )
                {
                    $data = array( 'solicituds' => $middleRpta[data] );
                    if ( Auth::user()->type == TESORERIA )
                        $data['tc'] = ChangeRate::getTc();
                    return $this->setRpta( View::make('template.List.solicituds')->with( $data )->render() );
                }
                return $middleRpta;
        }
        catch (Exception $e)
        {
            return $this->internalException($e,__FUNCTION__);
        }
    }

    private function textLv( $solicitud )
    {
        return $this->textAccepted( $solicitud ).' - '.$solicitud->titulo.' - '.$this->textClients( $solicitud );
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
            else
            {
                $solicitud = Solicitud::find( $inputs['idsolicitud'] );
                if ( $solicitud->idtiposolicitud == SOL_INST )
                {
                    $periodo = $solicitud->detalle->periodo;
                    if ( $periodo->status == BLOCKED )
                        return $this->warningException( 'No se puede eliminar las solicitudes del periodo: '.$periodo->periodo , __FUNCTION__ , __LINE__ , __FILE__ );
                    if ( count ( Solicitud::solInst( $periodo->periodo ) ) == 1 )
                        Periodo::inhabilitar( $periodo->periodo ); 
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

                Session::put( 'usertype' , $solicitud->createdBy->type );
                $rpta = $this->setStatus( $oldIdEstado , $solicitud->id_estado , Auth::user()->id , $solicitud->created_by , $solicitud->id );
                if ( $rpta[status] === ok )
                {
                    DB::commit();
                    $rpta['Type'] = $solicitud->idtiposolicitud;
                    if ( $solicitud->id_estado == RECHAZADO )
                        $rpta['Type'] = 3;
                    Session::put( 'state' , R_NO_AUTORIZADO );
                }
                else
                    DB::rollback();
                return $rpta;
            }
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
            return $this->setRpta( DERIVADO );
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
        $userType = $aprovalPolicy->tipo_usuario;
        Session::put( 'usertype' , $userType );
        if ( is_null( $aprovalPolicy ) )
            return $this->warningException( 'La inversion no  tiene politica de aprobacion' , __FUNCTION__ , __LINE__ , __FILE__ );
        else
        {   
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
                Session::put( 'maxRepeatIdsGerProd'  , Manager::whereIn( 'id' , array_keys( $repeatIds , $maxNumberRepeat )->lists( 'iduser' ) ) );
                $notRegisterGerProdName = Manager::getGerProdNotRegisteredName( $uniqueIdsGerProd );
                if ( count( $notRegisterGerProdName ) === 0 )
                    $idsUser = Manager::whereIn( 'id' , $uniqueIdsGerProd )->lists( 'iduser' );
                else
                    return $this->warningException( 'Los siguientes Gerentes no estan registrados en el sistema: ' . implode( ' , ' , $notRegisterGerProdName ) , __FUNCTION__ , __LINE__ , __FILE__ );
            }
            else
            {
                $user = User::getUserType( $userType );
                if ( count( $user ) === 0 )
                    return $this->warningException( 'No se encuentra el usuario: ' . $userType , __FUNCTION__ , __LINE__ , __FILE__ );
                else                
                    $idsUser = $user;
            }
            return $this->setRpta( array( 'iduser' => $idsUser , 'tipousuario' => $userType ) );
        }
    }

    public function massApprovedSolicitudes()
    {
        try
        {
            $inputs = Input::all();
            $rules = array( 'sols' => 'required' );
            if ( Validator::make($inputs, $rules)->fails() ) 
                $rpta = $validator->messages();
            else
            {
                $status = array( ok => array() , error => array() );
                $message = '';
                foreach($inputs['sols'] as $solicitud )
                {
                    $solicitud = Solicitud::where( 'token' , $solicitud )->first();
                    $inputs = array( 'monto'         => $solicitud->detalle->monto_actual,
                                     'idresponsable' => 0 ,
                                     'idfamily'      => $solicitud->orderProducts()->lists( 'id' ),
                                     'observacion'   => $solicitud->anotacion );

                    $solProducts = $solicitud->orderProducts();
                    if ( $solicitud->id_estado != DERIVADO )
                        $inputs[ 'amount_assigned' ] = $solProducts->lists( 'monto_asignado' );
                    else
                        $inputs[ 'amount_assigned' ] = array_fill( 0 , count( $solProducts->get() ) , $inputs[ 'monto' ] / count( $solProducts->get() ) );
                    $rpta = $this->acceptedSolicitudeTransaction( $solicitud->id ,  $inputs );
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
        return $this->setRpta();
    }


    private function acceptedSolicitudeTransaction( $idSolicitud , $inputs )
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
                    
                    $middleRpta = $this->setProductsAmount( $inputs[ 'producto' ] , $inputs[ 'monto_producto' ] );
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
                else
                {
                    $toUser = USER_CONTABILIDAD;
                    Session::put( 'usertype' , CONT );
                }
                $middleRpta = $this->setStatus( $oldIdEstado , $solicitud->id_estado , Auth::user()->id , $toUser , $solicitud->id );
                if ( $middleRpta[ status ] == ok )
                {
                    Session::put( 'state' , $solicitud->state->rangeState->id );
                    DB::commit();
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
            return $this->acceptedSolicitudeTransaction( $inputs[ 'idsolicitud' ] , $inputs );
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

    // IDKC: CHANGE STATUS => GENERADO
    public function generateSeatExpense()
    {
        try
        {
            DB::beginTransaction();
            $inputs = Input::all();
            $solicitud = Solicitud::find($inputs[ 'idsolicitud' ]);
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