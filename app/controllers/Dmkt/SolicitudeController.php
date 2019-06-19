<?php

namespace Dmkt;

use \Log;
use \Auth;
use \BaseController;
use \Fondo\Fondo;
use \Common\State;
use \Common\TypePayment;
use \DB;
use \Exception;
use \Expense\ProofType;
use \Image;
use \Input;
use \Session;
use System\SolicitudHistory;
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
use \Policy\ApprovalPolicy;
use \Users\Personal;
use \Client\ClientType;
use \yajra\Pdo\Oci8\Exceptions\Oci8Exception;
use \Alert\AlertController;
use \Event\Event;
use \FotoEventos;
use \Common\FileStorage;
use \Response;
use \Fondo\FondoMkt;
use \Fondo\FondoInstitucional;
use \VisitZone\Zone;
use \Seat\Generate;
use \Redirect;
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
        if( Session::has( 'state' ) )
        {
            $state = Session::get( 'state' );
        }
        else 
        {
            if ( Auth::user()->type == CONT )
                $state = R_APROBADO;
            elseif ( Auth::user()->type == TESORERIA )
                $state = R_REVISADO;
            else
                $state = R_PENDIENTE;
        }

        $mWarning = array();
        if ( Session::has( 'warnings' ) )
        {
            $warnings = Session::pull('warnings');
            $mWarning[status] = ok;
            if ( ! is_null( $warnings ) )
            {
                foreach ( $warnings as $key => $warning )
                {
                    $mWarning[data] = $warning[0] . ' ';
                }
            }
            $mWarning[data] = substr($mWarning[data], 0, -1);
        }
        
        $data = array( 'state' => $state, 'states' => StateRange::order(), 'warnings' => $mWarning );
        
        $user = Auth::user();
        if( $user->type == TESORERIA )
        {
            $data[ 'tc']          = ChangeRate::getTc();
            $data[ 'banks']       = Account::banks();
            $data[ 'depositIds' ] = Solicitud::getDepositSolicituds( Carbon::now()->year );
        }
        elseif ( $user->type == ASIS_GER )
        {
            $data[ 'activities'] = Activity::order();
        }
        elseif ( $user->type == CONT)
        {
            $data[ 'proofTypes']      = ProofType::order();
            $data[ 'regimenes']       = Regimen::all();
            $data[ 'revisionSolicituds' ]   = Solicitud::getRevisionSolicituds();
            $data[ 'depositSeatSolicituds' ] = Solicitud::getDepositSeatSolicituds();
            $data[ 'regularizationSeatSolicituds' ]   = Solicitud::getRegularizationSeatSolicituds();
        }
        
        if ( Session::has( 'id_solicitud' ) ) 
        {
            $solicitud = Solicitud::find(Session::pull('id_solicitud'));
            $solicitud->status = ACTIVE;
            
            $solicitud->save();
        }

        if( Session::has( 'start_date' ) && Session::has( 'end_date' ) )
        {
            $date = [];
            $date[ 'startDate' ] = Session::get( 'start_date' );
            $date[ 'endDate' ] = Session::get( 'end_date' );
            $data[ 'date' ] = $date;
        }

        if( $user->type == 'E' )
        {
            return Redirect::to( 'view-sup-rep' );
        }
        else
        {
            return View::make('template.User.showNew', $data);
        }
    }

    public function newSolicitude()
    {

        include(app_path() . '/models/Query/QueryProducts.php');

        $data = array(
            'reasons'     => SolicitudType::getNormalTypes(),
            'activities'  => Activity::order(),
            'payments'    => TypePayment::all(),
            'currencies'  => TypeMoney::all(),
            'families'    => $qryProducts->get(),
            'investments' => InvestmentType::orderMkt());

        if ( Auth::user()->type != REP_MED )
        {
            $data[ 'reps' ] = Personal::getResponsible();
        }


        return View::make('Dmkt.Register.solicitud', $data);
    }

    // public function newSolicitude()
    // {

    //     include(app_path() . '/models/Query/QueryProducts.php');
        
    //     $data = array(
    //         'reasons'     => SolicitudType::getNormalTypes(),
    //         'activities'  => Activity::order(),
    //         'payments'    => TypePayment::all(),
    //         'currencies'  => TypeMoney::all(),
    //         'families'    => $qryProducts->get(),
    //         'investments' => InvestmentType::orderMkt());

    //     if ( Auth::user()->type != REP_MED )
    //     {
    //         $data[ 'reps' ] = Personal::getResponsible();
    //     }


    //     return View::make('Dmkt.Register.solicitud', $data);
    // }

    public function editSolicitud($token)
    {
        include(app_path() . '/models/Query/QueryProducts.php');
        $data = array( 
           'solicitud'   => Solicitud::where('token', $token)->firstOrFail(),
           'reasons'     => SolicitudType::getNormalTypes(),
           'activities'  => Activity::order(),
           'payments'    => TypePayment::all(),
           'currencies'  => TypeMoney::all(),
           'families'    => $qryProducts->get(),
           'investments' => InvestmentType::orderMkt(),
           'edit'        => true );
        $data[ 'detalle' ] = $data['solicitud']->detalle;
        if ( in_array(Auth::user()->type, array( SUP , GER_PROD , ASIS_GER ) ) )
            $data[ 'reps' ] = Personal::getResponsible();
        return View::make('Dmkt.Register.solicitud', $data);
    }

    private function validateApprobationFamily( $inputs )
    {
        $rules = 
            [ 
                'solicitud_id' => 'required|numeric|min:1|exists:solicitud,id' ,
                'producto' => 'required|numeric|min:1'
                
            ];

        $validator = Validator::make( $inputs , $rules );

        if( $validator->fails() )
        {
            return $this->warningException( $this->msgValidator( $validator ) , __FUNCTION__ , __LINE__ , __FILE__ );
        }
        return $this->setRpta();
    }

    public function addFamilyFundSolicitud()
    {
        try
        {
            $inputs =   Input::all();
            $middleRpta = $this->validateApprobationFamily( $inputs );
            if( $middleRpta[ status ] == ok )
            {
                $productoId                           =  $inputs['producto'];
                $solicitudId                          =  $inputs['solicitud_id'];
                $solicitud                            = Solicitud::find( $solicitudId );
                $politicType                          = $solicitud->investment->approvalInstance->approvalPolicyOrder( $solicitud->histories->count() )->tipo_usuario;
                //$solicitudProduct                     = SolicitudProduct::where( 'id_solicitud', $solicitudId )->first();
                $solicitudProduct                     = new SolicitudProduct;
                $fondo_product                        = $solicitudProduct->getSubFondo( $politicType , $solicitud , $productoId );
                return $this->setRpta( $fondo_product );   
            }
            return $middleRpta;
        }
        catch( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    public function viewSolicitude($token)
    {
        try
        {
            $solicitud     = Solicitud::where( 'token' , $token )->first();
            if( is_null( $solicitud ) )
            {
                return $this->warningException( 'La solicitud no existe' , __FUNCTION__ , __LINE__ , __FILE__ );
            }

            $politicStatus = FALSE;
            if( $solicitud->idtiposolicitud == REEMBOLSO )
            {
                $regularizationStatus = $this->setRpta();
            }
            else
            {
                $regularizationStatus = $this->validateRegularization( $solicitud->id_user_assign );
            }

            $user          = Auth::user();
            if ( is_null( $solicitud ) )
            {
                return $this->warningException('No se encontro la Solicitud con Token: ' . $token, __FUNCTION__, __LINE__, __FILE__);
            }
            $detalle = $solicitud->detalle;
            include( app_path() . '/models/Query/QueryProducts.php' );
            $data = array( 
                'solicitud' => $solicitud , 
                'detalle' => $detalle );

            if ( $solicitud->idtiposolicitud != SOL_INST && in_array( $solicitud->id_estado, array( PENDIENTE , DERIVADO , ACEPTADO ) ) ) 
            {
                $politicType = $solicitud->investment->approvalInstance->approvalPolicyOrder( $solicitud->histories->count() )->tipo_usuario;
                if( in_array( $politicType , array( Auth::user()->type , Auth::user()->tempType() ) ) && 
                    ( array_intersect( array( Auth::user()->id, Auth::user()->tempId() ), $solicitud->managerEdit( $politicType )->lists( 'id_gerprod' ) ) ) ) 
                {

                    $politicStatus = TRUE;
                    $data[ 'payments' ] = TypePayment::all();
                    $data[ 'families' ] = $qryProducts->get();
                    $data[ 'reps' ] = Personal::getResponsible();    
                    $data[ 'tipo_usuario' ] = $politicType;
                    $solicitud->status = BLOCKED;
                    Session::put( 'id_solicitud' , $solicitud->id );
                    $solicitud->save();
                    $data[ 'solicitud' ]->status = 1;
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
                if ($solicitud->id_estado == DEPOSITADO )
                {
                    $entryController   = new Generate;
                    $data[ 'entries' ] = $entryController->generateDepositEntryData( $solicitud );
                }
                elseif ( ! is_null( $solicitud->toDeliveredHistory ) )
                {
                    $this->setExpenseData( $solicitud , $detalle , $data );
                }
            } 
            elseif ( ! is_null( $solicitud->expenseHistory ) && $user->id == $solicitud->id_user_assign ) 
            {
                $this->setExpenseData( $solicitud , $detalle , $data );
                $event = Event::where( 'solicitud_id', $solicitud->id )->get();
                if ( $event->count() !== 0 )
                {
                    $data['event'] = $event[ 0 ];
                }
            }
            Session::put( 'state' , $data[ 'solicitud' ]->state->id_estado );
            $data[ 'politicStatus' ] = $politicStatus;
            $data[ 'regularizationStatus' ] = $regularizationStatus;
            return View::make( 'Dmkt.Solicitud.view' , $data );
        } 
        catch (Exception $e) 
        {
            return $this->internalException( $e, __FUNCTION__ );
        }
    }

    private function setExpenseData( $solicitud , $detalle , &$data )
    {
        $data                = array_merge( $data , $this->expenseData( $solicitud, $detalle->monto_actual ) );
        $data[ 'igv' ]       = Table::getIGV();
        $data[ 'regimenes' ] = Regimen::all();   
    }

    private function expenseData( $solicitud , $monto_aprobado )
    {
        $data = array('typeProof' => ProofType::orderBy('id', 'asc')->get(),
            'typeExpense' => ExpenseType::order(),
            'date' => $this->getExpenseDate( $solicitud ) );
        $gastos = $solicitud->expenses;
        if ( count( $gastos ) > 0 ) 
        {
            $data['expenses'] = $gastos;
            $balance = $gastos->sum('monto');
            $data['balance'] = round( ( $monto_aprobado - $balance ) , 2 );
        }
        return $data;
    }

    private function validateInputSolRep($inputs)
    {

        $rules = array(
            'idsolicitud'   => 'integer|min:1|exists:'.TB_SOLICITUD.',id',
            'motivo'        => 'required|integer|min:1|exists:'.TB_SOLICITUD_TIPO.',id',
            'inversion'     => 'required|integer|min:1|exists:'.TB_TIPO_INVERSION.',id',
            'actividad'     => 'required|integer|min:1|exists:'.TB_TIPO_ACTIVIDAD.',id',
            'titulo'        => 'required|string|min:1|max:50',
            'moneda'        => 'required|integer|min:1|exists:'.TB_TIPO_MONEDA.',id',
            'monto'         => 'required|numeric|min:1',
            'pago'          => 'required|integer|min:1|exists:'.TB_TIPO_PAGO.',id',
            'fecha'         => 'required|date_format:"d/m/Y"|after:' . date( 'Y-m-d' ) ,
            'productos'     => 'required|array|min:1|each:integer|each:min,1|each:exists,'.TB_MARCAS_BAGO.',id',
            'clientes'      => 'required|array|min:1|each:integer|each:min,1',
            'tipos_cliente' => 'required|array|min:1|each:integer|each:min,1|each:exists,tipo_cliente,id',
            'descripcion'   => 'string|max:500'
        );

        if ( in_array( Auth::user()->type , array( SUP, GER_PROD ) ) )
        {
            $rules[ 'responsable' ] = 'required|numeric|min:1|exists:' . TB_USUARIOS . ',id' ;
        }

        $validator = Validator::make($inputs, $rules);
        
        if( $validator->fails() )
        {
            return $this->warningException( $this->msgValidator( $validator ) , __FUNCTION__ , __LINE__ , __FILE__ );
        }
        else
        {
            return $this->setRpta();
        }
        /*$validator->sometimes('ruc', 'required|numeric|digits:11', function ($input) {
            return $input->pago == PAGO_CHEQUE;
        });
        if ($validator->fails())
            return $this->warningException($this->msgValidator( $validator ), __FUNCTION__, __LINE__, __FILE__);
        return $this->setRpta();*/
    }

    private function setPago( &$jDetalle , $paymentType , $ruc)
    {
        if ( $paymentType == PAGO_CHEQUE )
            $jDetalle->num_ruc = $ruc;
    }

    private function setClients($idSolicitud, $clients, $types)
    {
        if (count($clients) != count($types))
            return $this->warningException('Hay un error con los tipos de clientes de la solicitud', __FUNCTION__, __LINE__, __FILE__);
        else {
            $clientType = ClientType::find($types[0]);
            $med = 0;
            foreach ($clients as $key => $client) {
                $solClient = new SolicitudClient;
                $solClient->id = $solClient->lastId() + 1;
                $solClient->id_solicitud = $idSolicitud;
                $solClient->id_cliente = $client;
                $solClient->id_tipo_cliente = $types[$key];
                $solClient->save();
                if ($solClient->id_tipo_cliente == CLT_MED )
                    $med++;
            }
            if ($clientType->num_medico > $med)
                return $this->warningException('Se necesita ingresar al menos ' . $clientType->num_medico . ' medicos', __FUNCTION__, __LINE__, __FILE__);
            else
                return $this->setRpta();
        }
    }

    private function setGerProd($idsGerProd, $idSolicitud, $usersType)
    {
        if ( Session::has( 'maxRepeatIdsGerProd' ) )
        {
            $maxRepeatIdsGerProd = Session::pull('maxRepeatIdsGerProd');
        }
        foreach( $idsGerProd as $idGerProd )
        {
            $solGer = new SolicitudGer;
            $solGer->id = $solGer->lastId() + 1;
            $solGer->id_solicitud = $idSolicitud;
            $solGer->id_gerprod = $idGerProd;
            $solGer->tipo_usuario = $usersType;
            $solGer->status = 1;
            if ($usersType == GER_PROD)
                if (in_array($idGerProd, $maxRepeatIdsGerProd))
                    $solGer->permiso = 1;
                else
                    $solGer->permiso = 2;
            else
                $solGer->permiso = 1;
            if (!$solGer->save())
                return $this->warningException('No se pudo derivar al Ger. Prod N°: ' . $idGerProd, __FUNCTION__, __LINE__, __FILE__);
        }
        return $this->setRpta($idsGerProd);
    }

    private function setProductsAmount( $solProductIds, $amount, $fondo, $detalle )
    {
        $fondoMktController = new FondoMkt;
        $fondos             = array();
        $tc                 = ChangeRate::getTc();
        $moneda             = $detalle->id_moneda;
        $userTypes          = array();
        $ids_fondo_mkt      = array();
        foreach ( $solProductIds as $key => $solProductId ) 
        {
            $solProduct = SolicitudProduct::find( $solProductId );
            $old_id_fondo_mkt  = $solProduct->id_fondo_marketing;
            $old_cod_user_type = $solProduct->id_tipo_fondo_marketing;
            $old_ammount       = $solProduct->monto_asignado;

            $solProduct->monto_asignado = $amount[$key];      
            $middleRpta                 = $fondoMktController->setFondo($fondo[$key], $solProduct, $detalle, $tc, $userTypes, $fondos);

            if ($middleRpta[status] != ok)
                return $middleRpta;

            $solProduct->save();
            $userTypeforDiscount = $solProduct->id_tipo_fondo_marketing;
            $ids_fondo_mkt[] = array(
                'old'         => $old_id_fondo_mkt,
                'oldUserType' => $old_cod_user_type,
                'oldMonto'    => $old_ammount,
                'new'         => $solProduct->id_fondo_marketing,
                'newMonto'    => $solProduct->monto_asignado );
        }
        $fondoMktController->discountBalance( $ids_fondo_mkt , $moneda, $tc, $detalle->solicitud->id, $userTypeforDiscount);
        return $fondoMktController->validateBalance( $userTypes, $fondos );
    }    

    private function setProducts( $idSolicitud, $idsProducto )
    {
        $productController = new ProductController;
        foreach ( $idsProducto as $idProducto ) 
        {
            $data = array( 
                'id_solicitud' => $idSolicitud ,
                'id_producto'  => $idProducto );
            $productController->newSolicitudProduct( $data );
        }
        return $this->setRpta();//
    }

    private function unsetRelations($solicitud)
    {
        //$detalle = $solicitud->detalle;
        $solicitud->products()->delete();
        $solicitud->clients()->delete();
        $solicitud->gerente()->delete();
        //$detalle->delete();
        $solicitud->detalle()->delete();
    }

    public function registerSolicitud()
    {
        try 
        {
            $inputs     = Input::all();
            $middleRpta = $this->validateInputSolRep($inputs);
            if ( $middleRpta[ status ] === ok ) 
            {
                if( isset( $inputs[ 'responsable' ] ) )
                {
                    $responsible = $inputs[ 'responsable' ];
                }
                else
                {
                    $responsible = Auth::user()->id;
                }
                $middleRpta = $this->validateMicroMkt( $inputs[ 'inversion' ] , $inputs[ 'tipos_cliente' ] , $inputs[ 'clientes' ] , $responsible );
                if( $middleRpta[ status ] === ok )
                {
                    $middleRpta = $this->newSolicitudTransaction( $inputs );
                }
            }
            return $middleRpta;
        } 
        catch( Exception $e ) 
        {
            return $this->internalException($e, __FUNCTION__);
        }
    }

    private function validateMicroMkt( $inversion , array $clientsType , array $clientsCode , $responsible )
    {
        if( $inversion == INV_MICROMKT )
        {
            $uniqueClientsType = array_unique( $clientsType );
            if( count( $uniqueClientsType ) === 1 && $uniqueClientsType[ 0 ] == CLT_MED )
            {
                $responsibleRegister = User::find( $responsible );
                if( $responsibleRegister->type === REP_MED )
                {
                    $msg = '';
                    foreach( $clientsCode as $clientCode )
                    {
                        //$cmp = \Client\Doctor::find( $clientCode )->pefnrodoc1;
                        $validateMsg = DB::select( 'SELECT VERIFICAR_MICROMKT_FN( :medico_id , :responsable_id ) msg from DUAL' , [ 'medico_id' => $clientCode , 'responsable_id' => $responsible ] );
                        if( $validateMsg[ 0 ]->msg !== 'Ok' )
                        {
                            $msg .= $validateMsg[ 0 ]->msg . '<br>';
                        }
                    }
                    if( $msg === '' )
                    {
                        return $this->setRpta();
                    }
                    else
                    {
                        return $this->warningException( $msg , __FUNCTION__ , __LINE__ , __FILE__ );
                    }
                }
                else
                {
                    return $this->warningException( 'Solo puede asignarse a un representante una solicitud de Micromarketing' , __FUNCTION__ , __LINE__ , __FILE__ );
                }
            }
            else
            {
                return $this->warningException( 'Solo puede ingresar medicos para crear una solicitud de Micromarketing' , __FUNCTION__ , __LINE__ , __FILE__ );
            }
        }
        else
        {
            return $this->setRpta();
        }
    }

    private function newSolicitudTransaction( $inputs )
    {
        try
        {
            DB::beginTransaction();

            if ( isset( $inputs[ 'idsolicitud' ] ) )
            {
                $solicitud = Solicitud::find( $inputs[ 'idsolicitud' ] );
                $solicitudId = $solicitud->id;
                //$detalle   = $solicitud->detalle;
                $this->unsetRelations( $solicitud );

            } 
            else 
            {
                $solicitud     = new Solicitud;
                $solicitudId = $solicitud->nextId();
            }

            if( Auth::user()->type === REP_MED )
            {
                $responsible = Auth::user()->id;
            }
            else
            {
                $responsible = $inputs[ 'responsable' ];
            }
    
            $detalle               = new SolicitudDetalle;
            $detalle->id           = $detalle->nextId();
            
            $solicitud->insert( $solicitudId , $detalle->id , $inputs[ 'titulo' ] , $inputs[ 'actividad' ] , $inputs[ 'inversion' ] , $inputs[ 'descripcion' ] , $inputs[ 'motivo' ] , $responsible );
  
            //$this->setSolicitud( $solicitud, $inputs );
            
            $jDetalle         = new stdClass();
            $this->setJsonDetalle( $jDetalle, $inputs );
            $detalle->detalle = json_encode( $jDetalle );
            $this->setDetalle( $detalle, $inputs );
            $detalle->save();

            $middleRpta = $this->setClients( $solicitud->id , $inputs[ 'clientes' ] , $inputs[ 'tipos_cliente' ] );
            if ( $middleRpta[ status ] === ok )
            {
                $middleRpta = $this->setProducts( $solicitud->id , $inputs[ 'productos' ] );
                if ( $middleRpta[ status ] === ok )
                {
                    if ( ! isset( $inputs[ 'responsable' ] ) )
                    {
                        $inputs[ 'responsable' ] = 0;
                    }

                    $middleRpta = $this->toUser( $solicitud->investment->approvalInstance , $inputs['productos'] , 1 , $inputs['responsable'] );
                    if ( $middleRpta[status] === ok )
                    {
                        $middleRpta = $this->setGerProd( $middleRpta[ data ][ 'iduser' ] , $solicitud->id, $middleRpta[ data ][ 'tipousuario' ]);
                        if ( $middleRpta[status] === ok )
                        {
                            $middleRpta = $this->setStatus(0, PENDIENTE, Auth::user()->id, $middleRpta[data], $solicitud->id);
                            if ( $middleRpta[status] === ok )
                            {
                                Session::put('state', R_PENDIENTE);
                                DB::commit();
                            }
                        }
                    }
                }
            }
            return $middleRpta;
        }
        catch( Oci8Exception $e )
        {
            DB::rollback();
            return $this->internalException( $e, __FUNCTION__ , DB );
        } 
        catch( Exception $e )
        {
            DB::rollback();
            return $this->internalException($e, __FUNCTION__);
        }
    }

    private function setJsonDetalle(&$jDetalle, $inputs)
    {
        $jDetalle->monto_solicitado = round($inputs['monto'], 2, PHP_ROUND_HALF_DOWN);
        $jDetalle->fecha_entrega = $inputs['fecha'];
        $this->setPago($jDetalle, $inputs['pago'], $inputs['ruc']);
    }

    private function setDetalle($detalle, $inputs)
    {
        $detalle->id_moneda = $inputs['moneda'];
        $detalle->id_pago = $inputs['pago'];
    }

    public function cancelSolicitud()
    {
        try 
        {
            $inputs = Input::all();
            $rules  = 
            [
                'idsolicitud' => 'required|numeric|min:1|exists:solicitud,id', 
                'observacion' => 'required|string|min:10' 
            ];

            $validator = Validator::make( $inputs, $rules);
            if ( $validator->fails() )
            {
                return $this->warningException($this->msgValidator( $validator ), __FUNCTION__, __LINE__, __FILE__);
            }

            $solicitud = Solicitud::find( $inputs[ 'idsolicitud' ] );
            
            if( $solicitud->idtiposolicitud == SOL_INST )
            {
                if( Auth::user()->type !== CONT )
                {
                    $periodo = $solicitud->detalle->periodo;
                    if (  Auth::user()->id != $solicitud->created_by )
                        return $this->warningException( 'No puede cancelar la solicitud si no ha sido registrada con su usuario' , __FUNCTION__ ,  __LINE__ , __FILE__ );
                    if ( $periodo->status == BLOCKED )
                        return $this->warningException( 'No se puede eliminar las solicitudes del periodo: ' . $periodo->aniomes , __FUNCTION__, __LINE__, __FILE__ );
                    if ( count( Solicitud::solInst( $periodo->aniomes ) ) == 1 )
                        Periodo::inhabilitar( $periodo->aniomes );
                }
            }
            elseif( in_array( $solicitud->idtiposolicitud , [ SOL_REP , REEMBOLSO ] ) )
            {
                if( ! in_array( $solicitud->id_estado , State::getCancelStates() ) )
                {
                    if( ! ( $solicitud->idtiposolicitud == REEMBOLSO && $solicitud->id_estado == GASTO_HABILITADO ) )
                    {
                        return $this->warningException( 'No se puede cancelar las solicitudes en esta etapa: ' . $solicitud->state->nombre , __FUNCTION__, __LINE__, __FILE__ );
                    }
                }
                if( Auth::user()->type != CONT )
                {
                    $politicType = $solicitud->investment->approvalInstance->approvalPolicyOrder( $solicitud->histories->count() )->tipo_usuario;
                    if (  Auth::user()->id != $solicitud->created_by )
                    {
                        if ( ! in_array( $politicType , array( Auth::user()->type , Auth::user()->tempType() ) ) )
                            return $this->warningException( 'No puede rechazar la solicitud , verifique el estado de la solicitud se esperaba al usuario ( ' . $politicType . ' )' , __FUNCTION__ , __LINE__ , __FILE__ );
                        if ( ! array_intersect( array( Auth::user()->id, Auth::user()->tempId() ) , $solicitud->managerEdit( $politicType )->lists('id_gerprod') ) )
                            return $this->warningException( 'No puede rechazar la solicitud , su usuario no ha sido asociado a la solicitud' , __FUNCTION__ , __LINE__ , __FILE__ );
                    }
                }
            }
            else
            {
                return $this->warningException( 'Tipo de Solicitud: ' . $solicitud->idtiposolicitud . ' no registrado' , __FUNCTION__, __LINE__, __FILE__ );
            }

            if( is_null( $solicitud->detalle->deposito ) )
            {
                $middleRpta = $this->renovateBalance( $solicitud );        
                if( $middleRpta[ status ] !== ok )
                {
                    DB::rollback();
                    return $middleRpta;
                }
            }
            else
            {
                return $this->warningException( 'No se puede rechazar solicitudes que cuentan con un deposito. Solicitud #' . $solicitud->id , __FUNCTION__ , __LINE__ , __FILE__ );
            }

            $oldIdEstado = $solicitud->id_estado;
            switch( $oldIdEstado )
            {
                case PENDIENTE:
                    $solicitud->id_estado = CANCELADO;
                    break;
                default:
                    $solicitud->id_estado = RECHAZADO;
                    break;
            }

            $solicitud->observacion = $inputs['observacion'];
            $solicitud->status      = 1;

            DB::beginTransaction();
            $solicitud->save();

            $rpta = $this->setStatus($oldIdEstado, $solicitud->id_estado, Auth::user()->id, $solicitud->created_by, $solicitud->id );
            if( $rpta[status] === ok )
            {
                DB::commit();
                $rpta['Type'] = $solicitud->idtiposolicitud;
                if ($solicitud->id_estado == RECHAZADO)
                    $rpta['Type'] = 3;
                Session::put('state', R_NO_AUTORIZADO);
                return $rpta;
            }
            DB::rollback();
            return $rpta;
        } 
        catch( Oci8Exception $e ) 
        {
            DB::rollback();
            return $this->internalException( $e , __FUNCTION__ , DB );
        } 
        catch( Exception $e ) 
        {
            DB::rollback();
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    private function setDiscountFundData( $solicitudProducts )
    {
        $ids_fondo_mkt = [];
        foreach ( $solicitudProducts as $solicitudProduct )
        {
            $ids_fondo_mkt[] = 
            [
                'old'         => $solicitudProduct->id_fondo_marketing,
                'oldUserType' => $solicitudProduct->id_tipo_fondo_marketing,
                'oldMonto'    => $solicitudProduct->monto_asignado
            ];
        }
        return $ids_fondo_mkt;
    }

    public function renovateBalance( $solicitud )
    {
        if( $solicitud->id_estado != PENDIENTE )
        {
            switch( $solicitud->idtiposolicitud )
            {
                case SOL_INST:
                    $ids_fondo_mkt      = [];
                    $ids_fondo_mkt[] =
                    [
                        'old'         => $solicitud->detalle->id_fondo,
                        'oldUserType' => FONDO_SUBCATEGORIA_INSTITUCION,
                        'oldMonto'    => $solicitud->detalle->monto_aprobado
                    ];
                    break;
                case SOL_REP:
                    $solicitudProducts = $solicitud->products;
                    $ids_fondo_mkt     = $this->setDiscountFundData( $solicitudProducts ); 
                    break;
                case REEMBOLSO:
                    $solicitudProducts = $solicitud->products;
                    $ids_fondo_mkt     = $this->setDiscountFundData( $solicitudProducts );
                    break;
                default:
                    return $this->warningException( 'No se pudo identificar el tipo de solicitud. Solicitud #' . $solicitud->id , __FUNCTION__ , __LINE__ , __FILE__ );        
            }
         
            $fondoMktController = new FondoMkt;
            return $fondoMktController->discountBalance( $ids_fondo_mkt , $solicitud->detalle->id_moneda , ChangeRate::getTc() , $solicitud->id );
        }
        else
        {
            return $this->setRpta();
        }
    }

    private function verifyPolicy( $solicitud , $monto )
    {
        $type    = array( Auth::user()->type , Auth::user()->tempType() );
        $approvalPolicy = $solicitud->investment->approvalInstance->approvalPolicyTypesOrder( $type , $solicitud->histories->count() );
        if ( is_null( $approvalPolicy ) )
            return $this->warningException( 'No se encontro la politica de aprobacion para la inversion: ' . $solicitud->id_inversion . ' y su rol: ' . Auth::user()->type, __FUNCTION__, __LINE__, __FILE__);
        
        if ( is_null( $approvalPolicy->desde ) && is_null( $approvalPolicy->hasta ) ):
            return $this->setRpta( ACEPTADO );
        else:
            if ( $solicitud->detalle->id_moneda == DOLARES )
                $monto = $monto * ChangeRate::getTc()->venta;
            if ( $monto > $approvalPolicy->hasta && ! is_null( $approvalPolicy->hasta ) )
                return $this->setRpta( ACEPTADO );
            elseif ( $monto <= $approvalPolicy->desde )
                return $this->warningException( 'Por Politica solo puede aceptar para este Tipo de Inversion montos mayores a: ' . $approvalPolicy->desde , __FUNCTION__ , __LINE__ , __FILE__ );
            else
                return $this->setRpta( APROBADO );
        endif;
    }

    private function toUser( $approvalInstance , $idsProducto, $order, $responsable = NULL )
    {
        $approvalPolicy = $approvalInstance->approvalPolicyOrder( $order );
        
        if ( is_null( $approvalPolicy ) )
            return $this->warningException( 'La inversion no cuenta con una politica de aprobacion para esta etapa del flujo (' . $order . ')' , __FUNCTION__, __LINE__, __FILE__);
        
        $userType = $approvalPolicy->tipo_usuario;
        $msg = '';
        
        if ( ! is_null( $approvalPolicy->desde ) || ! is_null( $approvalPolicy->hasta ) )
            $msg .= ' para la siguiente etapa del flujo , comuniquese con Informatica. El rol aprueba montos ' . ( is_null( $approvalPolicy->desde ) ? '' : 'mayores a S/.' . $approvalPolicy->desde . ' ') . ( is_null( $approvalPolicy->hasta ) ? '' : 'hasta S/.' . $approvalPolicy->hasta );
        if ( $userType == SUP ): 
            if ( Auth::user()->type === REP_MED )
            {
                $temp = Personal::getSup( Auth::user()->id );
                $idsUser = array( $temp->user_id ); // idkc : ES CORRECTO ESTE TIPO DE PARSE? SI ES UN MODELO XQ NO USAR ->toArray() ?
            }
            else if ( Auth::user()->type === SUP )
            {
                $idsUser = array( Auth::user()->id );
            }
            else if ( in_array( Auth::user()->type , array( GER_PROD , ASIS_GER ) ) )
            {
                $responsibleUserRegister = User::find( $responsable );
                
                if( $responsibleUserRegister->type == REP_MED )
                {
                    $idsUser = array( Personal::getSup( $responsable )->user_id );            
                }
                elseif( $responsibleUserRegister->type == SUP )
                {
                    $idsUser = array( $responsable );
                }
                else
                {
                    return $this->warningException( 'Solo se puede asignar como responsables de la solicitud a los Representantes o Supervisores' , __FUNCTION__ , __LINE__ , __FILE__ );
                }
            }
            else
            {
                return $this->warningException( 'El rol ' . Auth::user()->type . ' no tiene permisos para crear o derivar al supervisor' , __FUNCTION__ , __LINE__ , __FILE__ );
            }
        elseif ( $userType == GER_PROD ):
            $idsGerProd = Marca::whereIn( 'id' , $idsProducto )->lists( 'gerente_id' );
            $uniqueIdsGerProd = array_unique( $idsGerProd );
            
            $repeatIds = array_count_values( $idsGerProd );
            $maxNumberRepeat = max( $repeatIds );
            Session::put( 'maxRepeatIdsGerProd' , Personal::getGerProd( array_keys( $repeatIds , $maxNumberRepeat ) )->lists( 'user_id' ) );
            $notRegisterGerProdName = Personal::getGerProdNotRegisteredName( $uniqueIdsGerProd );
        
            if ( count( $notRegisterGerProdName ) === 0 )
                $idsUser = Personal::whereIn( 'bago_id' , $uniqueIdsGerProd )->where( 'tipo' , GER_PROD )->lists( 'user_id' );
            else
                return $this->warningException( 'Los siguientes Gerentes de Producto no estan registrados en el sistema: ' . implode( ' , ' , $notRegisterGerProdName ) , __FUNCTION__ , __LINE__ , __FILE__ );
        else:
            $user = User::getUserType( $userType );
            if ( count( $user ) === 0 )
                return $this->warningException( 'No existe el usuario con el Rol de ' . $approvalPolicy->userType->descripcion . $msg , __FUNCTION__ , __LINE__ , __FILE__ );
            else
                $idsUser = $user;
        endif;
            
        return $this->setRpta( array( 'iduser' => $idsUser , 'tipousuario' => $userType ) );
    }

    private function validateInputAcceptSolRep( $inputs )
    {
        $size  = count( $inputs[ 'producto' ] ); 
        $rules = array(
            'idsolicitud'            => 'required|integer|min:1|exists:'.TB_SOLICITUD.',id',
            'monto'                  => 'required|numeric|min:1',
            'anotacion'              => 'sometimes|string|min:1',
            'derivacion'             => 'required|numeric|boolean' ,
            'modificacion_productos' => 'required|numeric|boolean' ,
            'modificacion_clientes'  => 'required|numeric|boolean' );
        $messages = array();
        if ( Auth::user()->type === SUP )
        {
            $messages[ 'fondo_producto.required' ] = 'El campo :attribute es obligatorio. Si va a realizar una validacion seleccione el boton Derivar.' ;
        }
        else
        {
            $messages[ 'fondo_producto.required' ] = 'El campo :attribute es obligatorio.' ;
            
        }

        if ( in_array( Auth::user()->type , array( SUP , GER_PROD , GER_PROM , GER_COM , GER_GER ) ) )
        {
            $rules[ 'pago' ] = 'required|integer|min:1|exists:'.TB_TIPO_PAGO.',id';    
        }
        
        $messages[ 'fondo_producto.string' ] = 'El campo :attribute es obligatorio';
        $validator = Validator::make( $inputs , $rules , $messages );
        
        $validator->sometimes( 'monto_producto' , 'required|array|min:1|each:required|each:numeric|each:min,0|sumequal:monto', function ( $input ) 
        {
            return $input->derivacion == 0;
        });
        $validator->sometimes( 'fondo_producto' , 'required|array|size:'.$size.'|each:required|each:string|each:min,3', function ( $input ) 
        {
            return $input->derivacion == 0;
        });
        $validator->sometimes( 'producto' , 'required|array|min:1|each:integer|each:min,1|each:exists,'.TB_SOLICITUD_PRODUCTO.',id' , function ( $input ) 
        {
            return $input->modificacion_productos == 0;
        });


        $validator->sometimes( 'clientes' , 'required|array|min:1|each:integer|each:min,1' , function ( $input ) 
        {
            return $input->modificacion_clientes == 1;
        });

        $validator->sometimes( 'ruc' , 'required|numeric|digits:11'  , function ( $input ) 
        {
            return $input->pago == PAGO_CHEQUE;
        });
        $validator->sometimes( 'fecha' , 'required|string'  , function ( $input ) 
        {
            return isset($input->fecha);
        });

        $validator->sometimes( 'responsable' , 'required|numeric|exists:'.TB_PERSONAL.',user_id'  , function ( $input ) 
        {
            return isset($input->responsable);
        });
     
        if ( $validator->fails() )
        {
            return $this->warningException( $this->msgValidator( $validator ) , __FUNCTION__ , __LINE__ , __FILE__ );
        }
        else
        {
            if( $inputs[ 'derivacion' ] != 1 )
            {
                if( array_unique( $inputs[ 'producto' ] ) != $inputs[ 'producto' ] )
                {
                    return $this->warningException( 'Ha ingresado al menos una familia repetida' , __FUNCTION__ , __LINE__ , __FILE__ );
                }
            }
            return $this->setRpta();       
        }    
    }

    public function acceptedSolicitude()
    {
        try 
        {
            $inputs = Input::all();
            return $this->acceptedSolicitudOperation( $inputs[ 'idsolicitud' ] , $inputs );
        } 
        catch (Exception $e) 
        {
            return $this->internalException( $e, __FUNCTION__ );
        }
    }

    public function acceptedSolicitudOperation( $solicitudId , $inputs )
    {
        $middleRpta = $this->validateInputAcceptSolRep( $inputs );
        if( $middleRpta[ status ] === ok )
        {
            $solicitud = Solicitud::find( $solicitudId );
            $middleRpta = $this->verifyPolicy( $solicitud , $inputs[ 'monto' ] );
            if( $middleRpta[ status ] === ok )
            {
                $state = $middleRpta[ data ];
                if( $solicitud->idtiposolicitud == REEMBOLSO )
                {
                    $middleRpta = $this->acceptedSolicitudTransaction( $solicitudId , $state , $inputs );
                }
                else
                {
                    $middleRpta = $this->validateRegularization( $solicitud->id_user_assign );
                    if( $middleRpta[ status ] === ok )
                    {
                        $middleRpta = $this->acceptedSolicitudTransaction( $solicitudId , $state , $inputs );
                    }
                }
            }
        }
        return $middleRpta;
    }

    private function acceptedSolicitudTransaction( $solicitudId , $state , $inputs )
    {
        DB::beginTransaction();
        $solicitud = Solicitud::find( $solicitudId );        
        $oldIdEstado = $solicitud->id_estado;
        if( $inputs[ 'derivacion'] && Auth::user()->type === SUP )
        {
            $solicitud->id_estado = DERIVADO;
        }
        else
        {
            $solicitud->id_estado = $state;
        }
        $solicitud->status    = ACTIVE;
        
        if ( isset( $inputs[ 'anotacion' ] ) )
        {
            $solicitud->anotacion = $inputs[ 'anotacion' ];
        }

        if( isset( $inputs[ 'responsable' ] ) )
        {
            $solicitud->id_user_assign = $inputs['responsable'];
        }
        
        $solicitud->save();

        if( $solicitud->id_estado != DERIVADO )
        {
            $solDetalle = $solicitud->detalle;
            $detalle    = json_decode( $solDetalle->detalle );                
            $monto      = round( $inputs[ 'monto' ] , 2 , PHP_ROUND_HALF_DOWN );

            if ( $solicitud->id_estado == ACEPTADO )
            {
                $detalle->monto_aceptado = $monto;
            }
            else if ( $solicitud->id_estado == APROBADO ) ;
            {
                $detalle->monto_aprobado = $monto;
            }

            if( isset( $inputs[ 'pago' ] ) )
            {
                $solDetalle->id_pago = $inputs[ 'pago' ];
            }
            if( isset( $inputs[ 'ruc' ] ) )
            {
                $detalle->num_ruc = $inputs[ 'ruc' ];
            }

            if( isset( $inputs[ 'fecha' ] ) )
            {
                $detalle->fecha_entrega = $inputs[ 'fecha' ];
            }

            //VALIDAR SI SE MODIFICARAN LOS CLIENTES
            if ( $inputs[ 'modificacion_clientes' ] == 1 )
            {
                $solicitud->clients()->delete();
                $middleRpta = $this->setClients( $solicitud->id, $inputs['clientes'], $inputs['tipos_cliente'] );
            }
            //VALIDAR SI SE MODIFICARAN LOS PRODUCTOS
            if ( $inputs[ 'modificacion_productos' ] == 1 )
            {
                $productController = new ProductController;
                $middleRpta        = $productController->unsetSolicitudProducts( $solicitud->id , $inputs[ 'producto' ] );
                if ( $middleRpta[ status ] !== ok )
                {
                    DB::rollback();
                    return $middleRpta;
                }
                $inputs[ 'producto' ] = $middleRpta[ data ];
            }
            
            $middleRpta = $this->setProductsAmount( $inputs[ 'producto' ] , $inputs[ 'monto_producto' ] , $inputs[ 'fondo_producto' ] , $solDetalle );
            
            if ( $middleRpta[ status ] != ok )
            {
                DB::rollback();
                return $middleRpta;
            }

            $solDetalle->detalle = json_encode( $detalle );
            $solDetalle->save();
        }

        if( $solicitud->id_estado == APROBADO ) 
        {
            $toUser = USER_CONTABILIDAD;
        } 
        else
        {
            $familiesId = $solicitud->products->lists( 'id_producto' );
            
            $middleRpta = $this->toUser( $solicitud->investment->approvalInstance , $familiesId , $solicitud->histories->count() + 1 );
            if ( $middleRpta[ status] != ok )
            {
                return $middleRpta;
            }
            else 
            {
                $middleRpta = $this->setGerProd( $middleRpta[data]['iduser'] , $solicitud->id , $middleRpta[ data ][ 'tipousuario' ] );
                if ( $middleRpta[status] == ok )
                {
                    $toUser = $middleRpta[ data ];
                }
                else
                {
                    return $middleRpta;
                }
            }
        }

        $middleRpta = $this->setStatus( $oldIdEstado, $solicitud->id_estado , Auth::user()->id , $toUser , $solicitud->id );
        if ( $middleRpta[status] == ok ) 
        {
            Session::put( 'state' , $solicitud->state->rangeState->id );
            DB::commit();
            return $middleRpta;
        }
        return $middleRpta;
    }

    /** ---------------------------------------------  Contabilidad -------------------------------------------------*/

    public function getCuentaContHandler()
    {
        $dataInputs = Input::all();
        $accountFondo = Account::where('num_cuenta', $dataInputs['cuentaMkt'])->first();
        return MarkProofAccounts::listData($accountFondo->num_cuenta);
    }

    public function findDocument()
    {
        $data = array('proofTypes' => ProofType::order(), 'regimenes' => Regimen::all());
        return View::make('Dmkt.Cont.documents_menu')->with($data);
    }

    public function showSolicitudeInstitution()
    {
        if ( in_array( Auth::user()->type, array( ASIS_GER ) ) )
            $state = R_PENDIENTE;
        $mWarning = array();
        if ( Session::has( 'warnings' ) ) 
        {
            $warnings = Session::pull( 'warnings' );
            $mWarning[ status ] = ok;
            if ( ! is_null( $warnings ) )
                foreach ( $warnings as $key => $warning )
                    $mWarning[ data ] = $warning[ 0 ] . ' ';
            $mWarning[ data ] = substr( $mWarning[ data ] , 0 , -1 );
        }
        
        $data = array( 'state' => $state , 'states' => StateRange::order() , 'warnings' => $mWarning );
        
        if ( Auth::user()->type == ASIS_GER ) 
        {
            $data[ 'investments' ] = InvestmentType::orderInst();
            $data[ 'subFondos' ]   = FondoInstitucional::getSubFondo();
        }
        return View::make('template.User.institucion', $data);
    }

    public function album()
    {
        $data = array(
            'reps'  => Personal::getResponsible() ,
            'zones' => Zone::orderBy( 'N3GDESCRIPCION' , 'asc' )->get() );
        return View::make( 'Event.show' , $data );
    }

    public function getEventList()
    {
        try
        {
            $start = Input::get("date_start");
            $end   = Input::get("date_end");
            $user  = Input::get( 'usuario' );
            $zona  = Input::get( 'zona' );
            
            if ( $user == 0 )
            {
                $authUser = Auth::user();
                
                $userIds = $authUser->getResponsibleIds();
            }
            else
            {
                $userIds = [ $user ];
            }

            $data[ 'events' ] =   Event::whereRaw( "created_at between to_date( '$start' , 'DD-MM-YY' ) and to_date( '$end' , 'DD-MM-YY' ) +1" );
            $data[ 'events' ]->whereHas( 'solicitud' , function( $query ) use( $userIds , $zona )
            {
                $query->whereIn( 'id_user_assign' , $userIds );
                if ( $zona != 0 )
                {   
                    $query->whereHas( 'personalTo' , function( $query ) use( $zona )
                    {
                        $query->where( function( $query ) use( $zona )
                        {
                            $query->where( function( $query ) use( $zona )
                            {
                                $query->whereIn( 'tipo' , [ 'RM' , 'RI' , 'RF' ] )->whereHas( 'bagoVisitador' , function( $query ) use( $zona )
                                {
                                    $query->where( 'visnivel3geog' , $zona );
                                });
                            })->orWhere( function( $query ) use( $zona )
                            {
                                $query->whereIn( 'tipo' , [ SUP ] )->whereHas( 'bagoSupervisor' , function( $query ) use( $zona )
                                {
                                    $query->where( 'supnivel3geog' , $zona );
                                });
                            });
                        });
                    });  
                }
            });
                        
            $data[ 'events' ] = $data[ 'events' ]->get();
            return View::make('Event.album', $data);
        }
        catch( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    public function photos()
    {
        $result          = array();
        $event_id        = Input::get('event_id');
        $photos          = FotoEventos::where('event_id', $event_id)->get();
        $photo           = $photos->first();
        $result['title'] = $photo->event->name;
        $result['view']  = View::make('Event.carousel', compact('photos'))->render();
        return $result;
    }

    public function createEventHandler()
    {
        try {
            $result = array();
            $input = Input::all();
            $rules = array('name' => 'required|unique:event',
                'description' => 'required',
                'event_date' => 'required',
                'solicitud_id' => 'required');
            $validator = Validator::make(Input::all(), $rules);
            if ($validator->fails()) {
                $result['status'] = 'error';
                $result['message'] = DATOS_INVALIDOS;
                $result['detail'] = $validator->messages();
            } else {
                $newEvent = new Event();
                $newId = $newEvent->searchId() + 1;
                $input = array("id" => $newId) + $input;
                $input['place'] = is_null($input['place']) ? null : $input['place'];
                if ($newEvent->create($input)) {
                    $result['status'] = 'ok';
                    $result['message'] = CREADO_SATISFACTORIAMENTE;
                    $result['id'] = $newId;
                } else {
                    $result['status'] = 'error';
                    $result['message'] = DB_NOT_INSERT;
                }
            }
            return $result;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function uploadImgSave()
    {

        $fileList = Input::file('image');
        $event_id = Input::get('event_id');
        if (count($fileList) == 0)
            return Response::json(array('success' => false, 'errors' => 'No se pudo Cargar Archivo'));
        else {
            $resultFileList = array();
            foreach ($fileList as $fileKey => $fileItem) {
                $destinationPath = FILESTORAGE_DIR;
                $fileName = pathinfo($fileItem->getClientOriginalName(), PATHINFO_FILENAME);
                $fileExt = pathinfo($fileItem->getClientOriginalName(), PATHINFO_EXTENSION);
                $fileNameMD5 = md5(uniqid(rand(), true));
                $fileStorage = new FotoEventos;
                $fileStorage->id = $fileNameMD5;
                $fileStorage->name = pathinfo($fileItem->getClientOriginalName(), PATHINFO_FILENAME);
                $fileStorage->extension = $fileExt;
                $fileStorage->directory = $destinationPath;
                $fileStorage->app = APP_ID;
                $fileStorage->event_id = $event_id;
                $fileStorage->save();
                $fileItem->move($destinationPath, $fileNameMD5 . '.' . $fileExt);
                $resultFileList[] = array('id' => $fileNameMD5, 'name' => asset($destinationPath . $fileNameMD5 . '.' . $fileExt));
            }
            return Response::json(array('success' => true, 'fileList' => $resultFileList));
        }
    }

    public function getInvestmentsActivities()
    {
        try
        {
            $investments = array( 'investments' => InvestmentType::orderMkt() );
            $activities  = array( 'activities'  => Activity::order() );
            $vInvestment = View::make( 'Dmkt.Register.Detail.investments' , $investments )->render();
            $vActivity   = View::make( 'Dmkt.Register.Detail.activities' , $activities )->render();
            $data = array(
                'Investments' => $vInvestment ,
                'Activities'   => $vActivity );
            return $this->setRpta( $data );
        }
        catch( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    public function solicitudsToDeposit()
    {
        try
        {
            $data = array( 'solicituds' => Solicitud::getDepositSolicituds( Carbon::now()->year ) );
            return View::make( 'Dmkt.Cont.SolicitudsToDeposit.show' , $data );
        }
        catch( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    public function massApprovedSolicitudes()
    {
        try 
        {
            $inputs = Input::all();
            $rules = array( 'solicitudes' => 'required|array|min:1' );
            $validator = Validator::make( $inputs , $rules);
            if ( $validator->fails() )
            {
                return $this->warningException($this->msg2Validator($validator), __FUNCTION__, __LINE__, __FILE__);
            }
            else 
            {
                $status = array( ok => array() , error => array() );
                $message = '';
                foreach ( $inputs[ 'solicitudes' ] as $solicitud ) 
                {
                    $solicitud = Solicitud::where( 'token' , $solicitud[ 'token' ] )->first();
                    
                    if ( Auth::user()->type == GER_COM )
                    {
                        $solicitudProducts = $solicitud->orderProducts;
                        $fondo = array();

                        foreach ( $solicitudProducts as $solicitudProduct )
                            $fondo[] = $solicitudProduct->id_fondo_marketing . ',' . $solicitudProduct->id_tipo_fondo_marketing;

                        $inputs = array(
                            'idsolicitud'            => $solicitud->id,
                            'monto'                  => $solicitud->detalle->monto_actual ,
                            'producto'               => $solicitud->orderProducts()->lists('id') ,
                            'anotacion'              => $solicitud->anotacion ,
                            'fondo_producto'         => $fondo ,
                            'derivacion'             => 0 ,
                            'pago'                   => $solicitud->detalle->id_pago , 
                            'ruc'                    => $solicitud->detalle->num_ruc ,
                            'modificacion_productos' => 0 ,
                            'modificacion_clientes'  => 0 ); 

                        $solProducts = $solicitud->orderProducts();
                        if ($solicitud->id_estado == DERIVADO)
                            $inputs['monto_producto'] = array_fill(0, count($solProducts->get()), $inputs['monto'] / count($solProducts->get()));
                        else
                            $inputs['monto_producto'] = $solProducts->lists('monto_asignado');
                        $rpta = $this->acceptedSolicitudOperation( $solicitud->id , $inputs );
                    }
                    elseif( Auth::user()->type == CONT )
                    {
                        $rpta = $this->checkSolicitudTransaction( $solicitud[ 'token' ] );
                    }
                    if ($rpta[status] != ok) {
                        $status[error][] = $solicitud['token'];
                        $message .= $message . 'No se pudo procesar la Solicitud N°: ' . $solicitud->id . ': ' . $rpta[description] . '. ';
                    } else
                        $status[ok][] = $solicitud['token'];
                }
                if ( empty( $status[ error ] ) )
                    return array( status => ok , 'token' => $status , description => 'Se aprobaron las solicitudes seleccionadas' );
                else if ( empty( $status[ ok ] ) )
                    return array( status => danger , 'token' => $status , description => substr( $message , 0, -1 ) );
                else
                    return array( status => warning , 'token' => $status , description => substr( $message , 0, -1 ) );
            }
        } 
        catch( Exception $e ) 
        {
            return $this->internalException($e, __FUNCTION__);
        }
    }

    public function checkSolicitud()
    {
        try
        {
            $inputs = Input::all();
            $rules  = array( 'idsolicitud' => 'required|min:1|exists:'.TB_SOLICITUD.',id' );
            $validator = Validator::make( $inputs , $rules );
            if ( $validator->fails() )
            {
                return $this->warningException( $this->msgValidator( $validator ) , __FUNCTION__ , __LINE__ , __FILE__ );
            }

            $solicitud = Solicitud::find( $inputs[ 'idsolicitud' ] );
            return $this->checkSolicitudOperation( $solicitud->token );
        }
        catch( Eception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    private function checkSolicitudOperation( $solicitudToken )
    {
        try
        {
            $rules  = array( 'token' => 'required|min:1|exists:'.TB_SOLICITUD.',token' );
            $inputs = array( 'token' => $solicitudToken );
            $validator = Validator::make( $inputs , $rules );
            if ( $validator->fails() )
            {
                return $this->warningException( $this->msgValidator( $validator ) , __FUNCTION__ , __LINE__ , __FILE__ );
            }

            $solicitud = Solicitud::findByToken( $solicitudToken );

            if ( $solicitud->id_estado != APROBADO )
            {
                return $this->warningException( 'Cancelado - La solicitud no ha sido Aprobada o ya se ha procesado' , __FUNCTION__ , __LINE__ , __FILE__ );
            }
            
            return $this->checkSolicitudTransaction( $solicitud );
        }
        catch( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    private function checkSolicitudTransaction( $solicitud )
    {
        DB::beginTransaction();
        $oldIdEstado = $solicitud->id_estado;
        if( $solicitud->idtiposolicitud == REEMBOLSO )
        {
            $solicitud->id_estado = GASTO_HABILITADO;
            $toUser               = $solicitud->id_user_assign;
        }
        else
        {
            $solicitud->id_estado = DEPOSITO_HABILITADO;
            $toUser               = USER_TESORERIA;
        }
        $solicitud->save();

        $middleRpta = $this->setStatus( $oldIdEstado , $solicitud->id_estado , Auth::user()->id , $toUser, $solicitud->id );
        if ( $middleRpta[ status ] == ok ) 
        {
            Session::put( 'state' , APROBADO );
            DB::commit();
        }
        DB::rollback();
        return $middleRpta;
    }

    public function massiveSolicitudsRevision()
    {
        try
        {
            $validStates = [ APROBADO , DEPOSITADO , REGISTRADO ];
            $inputs = Input::all();
            $tokens = array_pluck( $inputs[ 'data' ] , 'token' );
            $solicituds = Solicitud::findByTokens( $tokens );
            $states = array_unique( $solicituds->lists( 'id_estado' ) );
            $intersectArrays = array_intersect( $validStates , $states );
            if( count( $intersectArrays ) == 0 )
            {
                return $this->warningException( 'Al menos una solicitud seleccionada no esta en la etapa para realizar la aprobacion masiva' , __FUNCTION__ , __LINE__ , __FILE__ );
            }
            elseif( count( $intersectArrays ) > 1 )
            {
                return $this->warningException( 'No se puede procesar masivamente solicitudes de diferentes estados' , __FUNCTION__ , __LINE__ , __FILE__ );
            }
            elseif( count( $intersectArrays ) == 1 )
            {
                $uniqueState = array_pop( $intersectArrays );
                if( $uniqueState == APROBADO )
                {
                    $middleRpta = $this->massiveSolicitudsCheck( $inputs[ 'data' ] );
                    Session::put( 'revisiones' , $middleRpta );
                    $location = 'revision-export';
                }
                elseif( $uniqueState == DEPOSITADO )
                {
                    $middleRpta = $this->massiveSolicitudsAdvanceSeat( $inputs[ 'data' ] );
                    Session::put( 'asientos_anticipo' , $middleRpta );
                    $location = 'advance-entry-export';
                }
                elseif( $uniqueState == REGISTRADO )
                {
                    $middleRpta = $this->massiveSolicitudsRegularizationSeat( $inputs[ 'data' ] );
                    Session::put( 'asientos_regularizacion' , $middleRpta );
                    $location = 'regularization-entry-export';
                }
                else
                {
                    return $this->warningException( 'No se pudo procesar el estado actual de las solicitudes. #' . $uniqueState , __FUNCTION__ , __LINE__ , __FILE__ );
                }

                $status = array_unique( array_pluck( $middleRpta , status ) );
            
                
                if( count( $status ) === 1 && $status[ 0 ] === ok )
                {
                    $rpta = $this->setRpta( $middleRpta , 'Registro realizado correctamente' );
                    
                }
                elseif( in_array( ok , $status , 1 ) )
                {
                    $rpta = $this->setRpta( $middleRpta , 'Registro realizado parcialmente' );
                }
                else
                {
                    $rpta = $this->warningException( 'No se pudo realizar el registro. Existen las siguientes observaciones' , __FUNCTION__ , __LINE__ , __FILE__ );
                    $rpta[ data ] = $middleRpta;
                }
                $rpta[ 'location' ] = $location;
                return $rpta;
            }
            else
            {
                return $this->warningException( 'No se pudo validar los estados de la solicitud. #' . count( $intersectArrays ) , __FUNCTION__ , __LINE__ , __FILE__ );
            }
        }
        catch( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    private function massiveSolicitudsCheck( $solicituds )
    {
        $responses = [];
        foreach ( $solicituds as $solicitud ) 
        {
            $middleRpta = $this->checkSolicitudOperation( $solicitud[ 'token' ] );
            $responses[ $solicitud[ 'id' ] ] = $middleRpta;
        }
        return $responses;
    }

    private function massiveSolicitudsAdvanceSeat( $solicituds )
    {
        $responses = [];
        foreach ( $solicituds as $solicitud ) 
        {
            $seatController = new Generate;
            $middleRpta = $seatController->advanceEntryOperation( $solicitud[ 'token' ] );
            $responses[ $solicitud[ 'id' ] ] = $middleRpta;
        }
        return $responses;
    }

    private function massiveSolicitudsRegularizationSeat( $solicituds )
    {
        $responses = [];
        foreach ( $solicituds as $solicitud ) 
        {
            $seatController = new Generate;
            $middleRpta = $seatController->regularizationEntryOperation( $solicitud[ 'token' ] );
            $responses[ $solicitud[ 'id' ] ] = $middleRpta;
        }
        return $responses;
    }

    private function validateRegularization( $user_id )
    {
        $response = DB::select( 'SELECT VERIFICAR_REGULARIZACION_FN( :user_id ) rpta from dual' , [ 'user_id' => $user_id ] )[ 0 ];
        if( $response->rpta === ok )
        {
            return $this->setRpta();
        }
        else
        {
            $rpta = explode( '|' , $response->rpta  );
            if( $rpta[ 0 ] === warning )
            {
                return $this->warningException( $rpta[ 1 ] , __FUNCTION__ , __LINE__ , __FILE__ );
            }
            else
            {
                return $this->warningException( $rpta[ 1 ] , __FUNCTION__ , __LINE__ , __FILE__ , 1 );
            }
        }
    }

}
