<?php

namespace Dmkt;

use \Log;
use \Auth;
use \BaseController;
use Common\TypeUser;
use DateInterval;
use DatePeriod;
use \Fondo\Fondo;
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
use System\SolicitudHistory;
use System\TiempoEstimadoFlujo;
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
use \Carbon\Carbon;
use \Fondo\FondoMkt;
use \Fondo\FondoInstitucional;
use \Seat\MigrateSeatController;
use \VisitZone\Zone;

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
        if ( Session::has( 'state' ) )
            $state = Session::get( 'state' );
        else 
        {
            if ( Auth::user()->type == CONT )
                $state = R_APROBADO;
            elseif ( Auth::user()->type == TESORERIA )
                $state = R_REVISADO;
            else if ( ! is_null( Auth::user()->simApp ) )
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
        
        if (Auth::user()->type == TESORERIA):
            $data['tc']    = ChangeRate::getTc();
            $data['banks'] = Account::banks();
        elseif (Auth::user()->type == ASIS_GER):
            $data['activities'] = Activity::order();
        elseif (Auth::user()->type == CONT):
            $data['proofTypes'] = ProofType::order();
            $data['regimenes']  = Regimen::all();
        endif;
        
        if ( Session::has( 'id_solicitud' ) ) 
        {
            $solicitud = Solicitud::find(Session::pull('id_solicitud'));
            $solicitud->status = ACTIVE;
            
            $solicitud->save();
        }
        return View::make('template.User.show', $data);
    }

    public function newSolicitude()
    {
        include(app_path() . '/models/Query/QueryProducts.php');
        $data = array(
            'reasons'     => SolicitudType::where( 'code' , '<>' , 'F' )->orderBy( 'id' )->get(),
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

    public function editSolicitud($token)
    {
        include(app_path() . '/models/Query/QueryProducts.php');
        $data = array( 
           'solicitud'   => Solicitud::where('token', $token)->firstOrFail(),
           'reasons'     => SolicitudType::where( 'code' , '<>' , 'F' )->orderBy( 'id' )->get(),
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
        $rules = array( 'solicitud_id' => 'required|numeric|min:1|exists:solicitud,id' );
        $validator = Validator::make( $inputs , $rules );
        if ( $validator->fails() )
        {
            return $this->warningException(substr($this->msgValidator($validator), 0, -1), __FUNCTION__, __LINE__, __FILE__);
        }
        else
        {
            $rules = array( 
                'producto' => 'required|numeric|min:1|');
            $validator = Validator::make( $inputs , $rules );
            if ( $validator->fails() )
            {
                return $this->warningException( substr($this->msgValidator($validator), 0, -1), __FUNCTION__, __LINE__, __FILE__);
            }
            return $this->setRpta();
        }
    }

    public function addFamilyFundSolicitud()
    {
        try
        {
            $inputs =   Input::all();
            $middleRpta = $this->validateApprobationFamily( $inputs );
            if ( $middleRpta[ status ] === ok )
            {
                
                //$middleRpta = $this->setProducts( $inputs[ 'solicitud_id' ] , array( $inputs[ 'producto' ] ) );
                //DB::beginTransaction();
                //$solicitudProduct = SolicitudProduct::where( 'id_solicitud' , $inputs[ 'solicitud_id' ] )
                //                    ->where( 'id_producto' , $inputs[ 'producto' ] )->first();
                
                $productoId=  $inputs['producto'];
                $solicitudId =  $inputs['solicitud_id'];
                $solicitudProduct = SolicitudProduct::where('id_solicitud', $solicitudId)->first();
                $solicitud = Solicitud::where('id', $solicitudId)->first();
                $politicType = $solicitud->investment->approvalInstance->approvalPolicyOrder( $solicitud->histories->count() )->tipo_usuario;
                $fondo_product =  $solicitudProduct->getSubFondo( $politicType , $solicitud, $productoId );
                return $this->setRpta(  array( 'Cond' => true , 'Fondo_product' => $fondo_product  ) );   
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
            $solicitud     = Solicitud::where('token', $token)->first();
            $politicStatus = FALSE;
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
                if ( in_array( $politicType , array( Auth::user()->type , Auth::user()->tempType() ) )
                    && ( array_intersect( array( Auth::user()->id, Auth::user()->tempId() ), $solicitud->managerEdit( $politicType )->lists( 'id_gerprod' ) ) ) ) 
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
                    $data['lv'] = $this->textLv($solicitud);
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
            'fecha'         => 'required|date_format:"d/m/Y"|after:' . date("Y-m-d"),
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
        if ($validator->fails())
            return $this->warningException(substr($this->msgValidator($validator), 0, -1), __FUNCTION__, __LINE__, __FILE__);

        $validator->sometimes('ruc', 'required|numeric|digits:11', function ($input) {
            return $input->pago == PAGO_CHEQUE;
        });
        if ($validator->fails())
            return $this->warningException(substr($this->msgValidator($validator), 0, -1), __FUNCTION__, __LINE__, __FILE__);
        return $this->setRpta();
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
                if ($solClient->id_tipo_cliente == MEDICO)
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
        if (Session::has('maxRepeatIdsGerProd'))
            $maxRepeatIdsGerProd = Session::pull('maxRepeatIdsGerProd');
        foreach ($idsGerProd as $idGerProd) {
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
        return $this->setRpta();
    }

    private function unsetRelations($solicitud)
    {
        $detalle = $solicitud->detalle;
        $solicitud->products()->delete();
        $solicitud->clients()->delete();
        $solicitud->gerente()->delete();
        $detalle->delete();
    }

    public function registerSolicitud()
    {
        try 
        {
            DB::beginTransaction();
            $inputs     = Input::all();
            $middleRpta = $this->validateInputSolRep($inputs);
            if ( $middleRpta[ status ] == ok ) 
            {
                if ( isset( $inputs[ 'idsolicitud' ] ) )
                {
                    $solicitud = Solicitud::find($inputs['idsolicitud']);
                    $detalle   = $solicitud->detalle;
                    $this->unsetRelations($solicitud);
                } 
                else 
                {
                    $solicitud     = new Solicitud;
                    $solicitud->id = $solicitud->lastId() + 1;
                }

                $detalle               = new SolicitudDetalle;
                $detalle->id           = $detalle->lastId() + 1;
                $solicitud->id_detalle = $detalle->id;
                $solicitud->token      = sha1(md5(uniqid($solicitud->id, true)));
                $this->setSolicitud( $solicitud, $inputs );
                $solicitud->save();

                $jDetalle         = new stdClass();
                $this->setJsonDetalle( $jDetalle, $inputs );
                $detalle->detalle = json_encode( $jDetalle );
                $this->setDetalle( $detalle, $inputs );
                $detalle->save();

                $middleRpta = $this->setClients( $solicitud->id, $inputs['clientes'], $inputs['tipos_cliente'] );
                if ( $middleRpta[status] == ok ):
                    $middleRpta = $this->setProducts($solicitud->id, $inputs['productos']);
                    if ( $middleRpta[status] == ok ):
                        if ( ! isset( $inputs[ 'responsable' ] ) )
                            $inputs[ 'responsable' ] = 0;
                        $middleRpta = $this->toUser( $solicitud->investment->approvalInstance , $inputs['productos'] , 1 , $inputs['responsable'] );
                        if ( $middleRpta[status] == ok ):
                            $middleRpta = $this->setGerProd( $middleRpta[ data ][ 'iduser' ] , $solicitud->id, $middleRpta[ data ][ 'tipousuario' ]);
                            if ( $middleRpta[status] == ok ):
                                $middleRpta = $this->setStatus(0, PENDIENTE, Auth::user()->id, $middleRpta[data], $solicitud->id);
                                if ( $middleRpta[status] == ok ):
                                    Session::put('state', R_PENDIENTE);
                                    DB::commit();
                                    return $middleRpta;
                                endif;
                            endif;
                        endif;
                    endif;
                endif;
            }
            DB::rollback();
            return $middleRpta;
        } catch (Oci8Exception $e) {
            DB::rollback();
            return $this->internalException($e, __FUNCTION__, DB);
        } catch (Exception $e) {
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

    private function setSolicitud($solicitud, $inputs)
    {
        $solicitud->titulo = $inputs['titulo'];
        $solicitud->id_actividad = $inputs['actividad'];
        $solicitud->id_inversion = $inputs['inversion'];
        $solicitud->descripcion = $inputs['descripcion'];
        $solicitud->id_estado = PENDIENTE;

        $solicitud->idtiposolicitud = $inputs['motivo'];

        $solicitud->status = ACTIVE;
        if ( in_array( Auth::user()->type, array( SUP , GER_PROD , ASIS_GER ) ) )
            $solicitud->id_user_assign = $inputs['responsable'];
        elseif ( Auth::user()->type == REP_MED )
            $solicitud->id_user_assign = Auth::user()->id;
    }

    private function setDetalle($detalle, $inputs)
    {
        $detalle->id_moneda = $inputs['moneda'];
        $detalle->id_pago = $inputs['pago'];
    }

    private function textLv($solicitud)
    {
        return substr( $solicitud->id . ' ' . $solicitud->assignedTo->personal->seat_name . ' ' . strtoupper( $solicitud->investment->accountFund->nombre ) , 0 , 50 );
    }

    private function textAccepted($solicitud)
    {
        if ( in_array( $solicitud->idtiposolicitud , array( SOL_REP , REEMBOLSO ) ) )
            return $solicitud->approvedHistory->user->personal->full_name;
        else if ($solicitud->idtiposolicitud == SOL_INST)
            return $solicitud->createdBy->personal->full_name;
    }

    private function textClients($solicitud)
    {
        $clientes = array();
        foreach ($solicitud->clients as $client)
            $clientes[] = $client->{$client->clientType->relacion}->full_name;
        return implode(',', $clientes);
    }

    public function cancelSolicitud()
    {
        try 
        {
            DB::beginTransaction();
            $inputs = Input::all();
            $rules = array(
                'idsolicitud' => 'required|numeric|min:1|exists:solicitud,id', 
                'observacion' => 'required|string|min:10' );
            
            $validator = Validator::make( $inputs, $rules);
            if ( $validator->fails() )
                return $this->warningException(substr($this->msgValidator($validator), 0, -1), __FUNCTION__, __LINE__, __FILE__);

            $solicitud = Solicitud::find( $inputs[ 'idsolicitud' ] );
            if ( $solicitud->idtiposolicitud == SOL_INST )
            {
                $periodo = $solicitud->detalle->periodo;
                if (  Auth::user()->id != $solicitud->created_by )
                    return $this->warningException( 'No puede cancelar la solicitud si no ha sido registrada con su usuario' , __FUNCTION__ ,  __LINE__ , __FILE__ );
                if ( $periodo->status == BLOCKED )
                    return $this->warningException( 'No se puede eliminar las solicitudes del periodo: ' . $periodo->aniomes , __FUNCTION__, __LINE__, __FILE__ );
                if ( count( Solicitud::solInst( $periodo->aniomes ) ) == 1 )
                    Periodo::inhabilitar( $periodo->aniomes );
            }
            elseif ( in_array( $solicitud->idtiposolicitud , array( SOL_REP , REEMBOLSO ) ) )
            {
                if ( ! in_array( $solicitud->id_estado , State::getCancelStates() ) )
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
                return $this->warningException( 'Tipo de Solicitud: ' . $solicitud->idtiposolicitud . ' no registrado', __FUNCTION__, __LINE__, __FILE__);
            }

            $oldIdEstado = $solicitud->id_estado;
            if ( $oldIdEstado == PENDIENTE && Auth::user()->id == $solicitud->created_by ):
                $solicitud->id_estado = CANCELADO;
            elseif( $oldIdEstado != PENDIENTE || Auth::user()->id != $solicitud->created_by ):
                $solicitud->id_estado = RECHAZADO;
                $this->renovateBalance( $solicitud );
            else:
                return $this->warningException( 'No puede inhabilitar la solicitud , debido a que ha sido modificada ' . $solicitud->state->nombre , __FUNCTION__ , __LINE__ , __FILE__ );
            endif;

            $solicitud->observacion = $inputs['observacion'];
            $solicitud->status      = 1;
            $solicitud->save();

            $rpta = $this->setStatus($oldIdEstado, $solicitud->id_estado, Auth::user()->id, $solicitud->created_by, $solicitud->id );
            if ($rpta[status] === ok)
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
        } catch (Oci8Exception $e) {
            DB::rollback();
            return $this->internalException($e, __FUNCTION__, DB);
        } catch (Exception $e) {
            DB::rollback();
            return $this->internalException($e, __FUNCTION__);
        }
    }

    public function renovateBalance( $solicitud )
    {
        $fondoMktController = new FondoMkt;
        $solicitudProducts = $solicitud->products;
        $ids_fondo_mkt = array();
        foreach ( $solicitudProducts as $solicitudProduct )
            $ids_fondo_mkt[] = array(
                'old'         => $solicitudProduct->id_fondo_marketing,
                'oldUserType' => $solicitudProduct->id_tipo_fondo_marketing,
                'oldMonto'    => $solicitudProduct->monto_asignado);
        $fondoMktController->discountBalance( $ids_fondo_mkt , $solicitud->detalle->id_moneda , ChangeRate::getTc() , $solicitud->id );
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
                $idsUser = array( Personal::getSup( $responsable )->user_id );
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
                        $rpta = $this->acceptedSolicitudTransaction( $solicitud->id , $inputs );
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
        catch (Exception $e) 
        {
            return $this->internalException($e, __FUNCTION__);
        }
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
            $messages[ 'fondo_producto.required_if' ] = 'El campo :attribute es obligatorio. Si va a realizar una validacion seleccione el boton Derivar.' ;
        }
        else
        {
            $messages[ 'fondo_producto.required_if' ] = 'El campo :attribute es obligatorio.' ;
            
        }

        if ( in_array( Auth::user()->type , array( GER_COM , GER_GER ) ) )
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
            return $this->warningException( substr( $this->msgValidator( $validator ) , 0 , -1 ) , __FUNCTION__ , __LINE__ , __FILE__ );
        else
            return $this->setRpta();
    }

    private function acceptedSolicitudTransaction( $idSolicitud , $inputs )
    {
        DB::beginTransaction();
        $middleRpta = $this->validateInputAcceptSolRep( $inputs );
        if ( $middleRpta[ status ] === ok ) 
        {
            $solicitud  = Solicitud::find( $idSolicitud );
            $middleRpta = $this->verifyPolicy( $solicitud , $inputs[ 'monto' ] );
            if ( $middleRpta[ status ] == ok )
            {
                $oldIdEstado          = $solicitud->id_estado;
                if( $inputs[ 'derivacion'] && Auth::user()->type === SUP )
                {
                    $solicitud->id_estado = DERIVADO;
                }
                else
                {
                    $solicitud->id_estado = $middleRpta[data];
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

                if ( $solicitud->id_estado != APROBADO ) 
                {
                    $middleRpta = $this->toUser( $solicitud->investment->approvalInstance , SolicitudProduct::getSolProducts( $inputs['producto'] ), $solicitud->histories->count() + 1 );
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
                else
                {
                    $toUser = USER_CONTABILIDAD;
                }

                $middleRpta = $this->setStatus($oldIdEstado, $solicitud->id_estado, Auth::user()->id, $toUser, $solicitud->id);
                if ( $middleRpta[status] == ok ) 
                {
                    Session::put( 'state' , $solicitud->state->rangeState->id );
                    DB::commit();
                    return $middleRpta;
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
            return $this->acceptedSolicitudTransaction($inputs['idsolicitud'], $inputs);
        } 
        catch (Exception $e) 
        {
            return $this->internalException( $e, __FUNCTION__ );
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
                return $this->warningException( substr( $this->msgValidator( $validator ) , 0 , -1 ) , __FUNCTION__ , __LINE__ , __FILE__ );
            }

            $solicitud = Solicitud::find( $inputs[ 'idsolicitud' ] );
            return $this->checkSolicitudTransaction( $solicitud->token );
        }
        catch( Eception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    private function checkSolicitudTransaction( $solicitudToken )
    {
        DB::beginTransaction();
        $rules = array( 'token' => 'required|min:1|size:40|exists:'.TB_SOLICITUD.',token' );
        $inputs = array( 'token' => $solicitudToken );
        $validator = Validator::make( $inputs , $rules );
        if ( $validator->fails() )
        {
            return $this->warningException( substr( $this->msgValidator( $validator ) , 0 , -1 ) , __FUNCTION__ , __LINE__ , __FILE__ );
        }

        $solicitud = Solicitud::where( 'token' , $solicitudToken )->first();
        
        if ( is_null( $solicitud ) )
        {
            return $this->warningException( 'Cancelado - No se encontro la solicitud con Id: ' . $inputs[ 'idsolicitud' ] , __FUNCTION__, __LINE__, __FILE__ );
        }
        if ( $solicitud->id_estado != APROBADO )
            return $this->warningException( 'Cancelado - La solicitud no ha sido Aprobada o ya se ha procesado' , __FUNCTION__ , __LINE__ , __FILE__ );

        $oldIdEstado = $solicitud->id_estado;
        if ( $solicitud->idtiposolicitud == REEMBOLSO )
        {
            $solicitud->id_estado = GASTO_HABILITADO;
            $toUser               = $solicitud->id_user_assign;
            $state                = R_GASTO;
        }
        else
        {
            $solicitud->id_estado = DEPOSITO_HABILITADO;
            $toUser               = USER_TESORERIA;
            $state                = R_REVISADO;
        }
        $solicitud->save();

        $middleRpta = $this->setStatus( $oldIdEstado , $solicitud->id_estado , Auth::user()->id , $toUser, $solicitud->id );
        if ( $middleRpta[ status ] == ok ) 
        {
            Session::put( 'state' , $state );
            DB::commit();
            return $middleRpta;
        }
        DB::rollback();
        return $middleRpta;
    }

    /** ---------------------------------------------  Contabilidad -------------------------------------------------*/

    public function getTypeDoc($id)
    {
        return json_decode(ProofType::find($id)->toJson());
    }

    public function createSeatElement($cuentaMkt, $solicitudId, $account_number, $cod_snt, $fecha_origen, $iva, $cod_prov, $nom_prov, $cod, $ruc, $prefijo, $numero, $dc, $monto, $marca, $descripcion, $tipo_responsable, $type)
    {
        return array('cuentaMkt' => $cuentaMkt,
            'solicitudId' => intval($solicitudId),
            'numero_cuenta' => $account_number,
            'codigo_sunat' => $cod_snt,
            'fec_origen' => $fecha_origen,
            'iva' => $iva,
            'cod_prov' => $cod_prov,
            'nombre_proveedor' => $nom_prov,
            'cod' => $cod,
            'ruc' => $ruc,
            'prefijo' => $prefijo,
            'cbte_proveedor' => $numero,
            'dc' => $dc,
            'importe' => $monto,
            'leyenda' => $marca,
            'leyenda_variable' => $descripcion,
            'tipo_responsable' => $tipo_responsable,
            'type' => $type);
    }

    public function getCuentaContHandler()
    {
        $dataInputs = Input::all();
        $accountFondo = Account::where('num_cuenta', $dataInputs['cuentaMkt'])->first();
        return MarkProofAccounts::listData($accountFondo->num_cuenta);
    }

    private function searchFundAccount($solicitud)
    {
        $fondo = $solicitud->investment->accountFund;
        if ( is_null( $fondo ) )
        {
            return $this->warningException('No se encontro el Fondo asignado a la solicitud', __FUNCTION__, __LINE__, __FILE__);
        }
        else
        {
            return $this->setRpta($fondo);
        }
    }

    public function generateSeatExpenseData($solicitud)
    {
        $result = array();
        $seatList = array();
        $detalle = $solicitud->detalle;
        $middleRpta = $this->searchFundAccount($solicitud);
        if ($middleRpta[status] == ok) 
        {
            $fondo = $middleRpta[data];
            $cuentaExpense = '';
            $marcaNumber = '';
            $cuentaMkt = '';
            if ( ! is_null( $fondo ) ) 
            {
                $cuentaMkt = $fondo->num_cuenta;

                $cuentaExpense = Account::getExpenseAccount( $cuentaMkt );

                if ( ! is_null( $cuentaExpense[0]->num_cuenta ) ) 
                {
                    $cuentaExpense = $cuentaExpense[0]->num_cuenta;
                    $marcaNumber = MarkProofAccounts::getMarks( $cuentaMkt , $cuentaExpense );
                    $marcaNumber = $marcaNumber[0]->marca_codigo;
                } 
                else
                    $result['error'][] = $accountResult['error'];
            }
            $userElement = $solicitud->assignedTo;
            $tipo_responsable = $userElement->tipo_responsable;
            $username = '';

            $userType = $userElement->type;
            $username = $userElement->personal->full_name;
            
            if ($solicitud->documentList->count() == 0) 
            {
                $result['seatList'] = array();
                return $result;
            }
            else 
            {
                $tempId = 1;
                $total_percepciones = 0;

                foreach ($solicitud->documentList as $expense) 
                {
                    $tasaCompra = $this->getExpenseChangeRate( $solicitud , $expense->updated_at );

                    $comprobante = $this->getTypeDoc($expense->idcomprobante);
                    $desc = substr($comprobante->descripcion, 0, 1) . '/' . $expense->num_prefijo . '-' . $expense->num_serie . ' ' . $expense->razon;
                    $description_detraccion_reembolso = 'VARIOS ' . $desc;
                    $comprobante->marcaArray = explode(',', $comprobante->marca);
                    $marca = '';
                 
                    if ($marcaNumber == '') 
                    {
                        $errorTemp = array( 'error' => ERROR_NOT_FOUND_MARCA,
                                            'msg' => MESSAGE_NOT_FOUND_MARCA );
                        if ( ! isset( $result['error'] ) || ! in_array( $errorTemp , $result['error'] ) )
                            $result['error'][] = $errorTemp;
                    } 
                    else
                        if (count($comprobante->marcaArray) == 2 && (boolean)$comprobante->igv == true)
                            if ( $expense->igv > 0 )
                                $marca = $marcaNumber == '' ? '' : $marcaNumber . $comprobante->marcaArray[1];
                            else
                                $marca = $marcaNumber == '' ? '' : $marcaNumber . $comprobante->marcaArray[0];
                        else
                            $marca = $marcaNumber == '' ? '' : $marcaNumber . $comprobante->marcaArray[0];

                    $fecha_origen = date( 'd/m/Y' , strtotime( $expense->fecha_movimiento ) );
                    // COMPROBANTES CON IGV
                    if ( ( boolean ) $comprobante->igv === true ) 
                    {
                        $itemLength = count( $expense->itemList ) - 1;
                        $total_neto = 0;
                        foreach ( $expense->itemList as $itemKey => $itemElement )
                        {
                            $description_seat_item = strtoupper($username . ' ' . $itemElement->cantidad . ' ' . $itemElement->descripcion);
                            $description_seat_igv = strtoupper($expense->razon);
                            $description_seat_repair_base = strtoupper($username . ' ' . $expense->descripcion . '-REP ' . $desc);
                            $description_seat_repair_deposit = strtoupper('REPARO IGV MKT ' . $desc);
                            $description_seat_retencion_base = strtoupper('ENTREGAS A RENDIR CTA A TERCER ' . $desc);
                            $description_seat_retencion_deposit = strtoupper('RETENCION ' . $desc);
                            $description_seat_detraccion_deposit = strtoupper('DETRACCION ' . $desc);

                            // ASIENTO ITEM
                            $seatList[] = $this->createSeatElement($cuentaMkt, $solicitud->id, $cuentaExpense, $comprobante->cta_sunat, $fecha_origen,
                                ASIENTO_GASTO_IVA_BASE, ASIENTO_GASTO_COD_PROV_IGV, $expense->razon, ASIENTO_GASTO_COD_IGV,
                                $expense->ruc, $expense->num_prefijo, $expense->num_serie, ASIENTO_GASTO_BASE, round( $itemElement->importe * $tasaCompra , 2 , PHP_ROUND_HALF_DOWN ) ,
                                $marca, $description_seat_item, $tipo_responsable, '');

                            $total_neto += $itemElement->importe;
                        }

                        //ASIENTO DE IGV
                        if ( $expense->igv != 0 )
                        {
                            $seatList[] = $this->createSeatElement($cuentaMkt, $solicitud->id, CUENTA_REPARO_GOBIERNO, $comprobante->cta_sunat, $fecha_origen, 
                                ASIENTO_GASTO_IVA_IGV, ASIENTO_GASTO_COD_PROV_IGV, $expense->razon, ASIENTO_GASTO_COD_IGV, $expense->ruc, $expense->num_prefijo, 
                                $expense->num_serie, ASIENTO_GASTO_BASE, round( $expense->igv * $tasaCompra , 2 , PHP_ROUND_HALF_DOWN ) , '' , $description_seat_igv, $tipo_responsable, 'IGV');
                        }

                        //ASIENTO IMPUESTO SERVICIO
                        if ( ! ( $expense->imp_serv == null || $expense->imp_serv == 0 || $expense->imp_serv == '') )
                        {
                            $porcentaje = $total_neto / $expense->imp_serv;


                            $description_seat_tax_service = strtoupper('SERVICIO ' . $porcentaje . '% ' . $expense->descripcion);
                            $seatList[] = $this->createSeatElement($cuentaMkt, $solicitud->id, $cuentaExpense, '', $fecha_origen , 
                                '', '', '', '', '', '', '', ASIENTO_GASTO_BASE, round( $expense->imp_serv * $tasaCompra , 2 , PHP_ROUND_HALF_DOWN ) , 
                                $marca, $description_seat_tax_service, '', 'SER');
                        }

                        //ASIENTO REPARO
                        if ( $expense->reparo == 1 ) 
                        {
                            $seatList[] = $this->createSeatElement($cuentaMkt, $solicitud->id, CUENTA_REPARO_COMPRAS, '', $fecha_origen , '' , '' , '' , '' , '' , '' , '' , 
                                ASIENTO_GASTO_BASE, round( $expense->igv  * $tasaCompra , 2 , PHP_ROUND_HALF_DOWN ) , $marca, $description_seat_repair_base, '', 'REP');
                            $seatList[] = $this->createSeatElement($cuentaMkt, $solicitud->id, CUENTA_REPARO_GOBIERNO, '', $fecha_origen, '', '', '', '', '', '', '', 
                                ASIENTO_GASTO_DEPOSITO, round( $expense->igv  * $tasaCompra , 2 , PHP_ROUND_HALF_DOWN ) , '' , $description_seat_repair_deposit, '', 'REP');
                        }

                        //ASIENTO RETENCION
                        if ($expense->idtipotributo == REGIMEN_RETENCION )
                        {
                            $seatList[] = $this->createSeatElement($cuentaMkt, $solicitud->id, CUENTA_RETENCION_DEBE, '', $fecha_origen, '', '', '', '', '', '', '', ASIENTO_GASTO_BASE, 
                                $expense->monto_tributo  * $tasaCompra , '' , $description_seat_retencion_base, '', 'RET');
                            $seatList[] = $this->createSeatElement($cuentaMkt, $solicitud->id, CUENTA_RETENCION_HABER, '', $fecha_origen, '', '', '', '', '', '', '', 
                                ASIENTO_GASTO_DEPOSITO, round( $expense->monto_tributo  * $tasaCompra , 2 , PHP_ROUND_HALF_DOWN ) , '' , $description_seat_retencion_deposit, '', 'RET');
                        }

                        //ASIENTO DETRACCION
                        if ($expense->idtipotributo == REGIMEN_DETRACCION )
                        {
                            $total_percepciones += $expense->monto_tributo;
                            $seatList[] = $this->createSeatElement( $cuentaMkt, $solicitud->id, CUENTA_DETRACCION_HABER, '', $fecha_origen, '', '', '', '', '', '', '', 
                                ASIENTO_GASTO_DEPOSITO, round( $expense->monto_tributo * $tasaCompra , 2 , PHP_ROUND_HALF_DOWN ) , '' , $description_seat_detraccion_deposit, '', 'DET');
                        }
                    }
                    else //TODOS LOS OTROS DOCUMENTOS
                    {
                        $description_seat_renta4ta_deposit = strtoupper('RENTA 4TA CATEGORIA ' . $desc);

                        //ASIENTO DOCUMENTO OTROS - UN SOLO ASIENTO POR TODOS LOS ITEMS QUE TENGA
                        $description_seat_other_doc = strtoupper( $username .' '. $expense->razon );
                        if ( $expense->idcomprobante == DOC_NO_SUSTENTABLE )
                        {
                            $seatList[] = $this->createSeatElement($cuentaMkt , $solicitud->id , $cuentaExpense , $comprobante->cta_sunat, $fecha_origen, '' , 
                                ASIENTO_GASTO_COD_PROV, $expense->razon, ASIENTO_GASTO_COD, $expense->ruc, $expense->num_prefijo, $expense->num_serie, ASIENTO_GASTO_BASE, 
                                round( $expense->monto  * $tasaCompra , 2 , PHP_ROUND_HALF_DOWN ) , $marca, $description_seat_other_doc, $tipo_responsable, ''); 
                        }
                        else if ( $expense->idcomprobante == DOC_RECIBO_HONORARIO  )
                        {
                            $descripcion_rh = $description_seat_other_doc . ' ' . 'RH/'.$expense->num_prefijo . '-' . $expense->num_serie;
                            if ( $solicitud->id_inversion == 17 ) //Inversion Micromarketing y tipo de documento recibo x honorario
                            {
                                $cuentaExpenseDinamic = 6329200;
                            }
                            else
                            {
                                $cuentaExpenseDinamic = $cuentaExpense;
                            }

                            $seatList[] = $this->createSeatElement( $cuentaMkt , $solicitud->id , $cuentaExpenseDinamic , '' , $fecha_origen , '' , ASIENTO_GASTO_COD_PROV , '' , ASIENTO_GASTO_COD , '' , '' , '' , ASIENTO_GASTO_BASE, 
                                round( $expense->monto  * $tasaCompra , 2 , PHP_ROUND_HALF_DOWN ) , $marca, $descripcion_rh , $tipo_responsable, ''); 
                            
                        }
                        else
                        {
                            $seatList[] = $this->createSeatElement($cuentaMkt , $solicitud->id , $cuentaExpense , $comprobante->cta_sunat, $fecha_origen, ASIENTO_GASTO_IVA_BASE, 
                                ASIENTO_GASTO_COD_PROV, $expense->razon, ASIENTO_GASTO_COD, $expense->ruc, $expense->num_prefijo, $expense->num_serie, ASIENTO_GASTO_BASE, 
                                round( $expense->monto  * $tasaCompra , 2 , PHP_ROUND_HALF_DOWN ) , $marca, $description_seat_other_doc, $tipo_responsable, ''); 
                        }

                        //ASIENTO IMPUESTO A LA RENTA
                        if ( $expense->idtipotributo == REGIMEN_RETENCION && $expense->idcomprobante == DOC_RECIBO_HONORARIO ) 
                        {
                            $total_percepciones += $expense->monto_tributo;
                            $seatList[] = $this->createSeatElement($cuentaMkt, $solicitud->id, CUENTA_RENTA_4TA_HABER, '', $fecha_origen, '', '', '', '', '', '', '', 
                            ASIENTO_GASTO_DEPOSITO, round( $expense->monto_tributo  * $tasaCompra , 2 , PHP_ROUND_HALF_DOWN ) , '' , $description_seat_renta4ta_deposit, '', 'RENTA');
                        }
                    }
                }

                foreach( $solicitud->devolutions()->where( 'id_tipo_devolucion' , DEVOLUCION_INMEDIATA )->get() as $devolution )
                {
                    $tasaCompra = $this->getExpenseChangeRate( $solicitud , $devolution->updated_at );
                    $seatList[] = $this->createSeatElement( $cuentaMkt , $solicitud->id , CUENTA_SOLES , '' , date( 'd/m/Y' , strtotime( $devolution->updated_at ) ) , '' , '' , '' ,
                        '' , '' , '' , '' , ASIENTO_GASTO_BASE , $devolution->monto  * $tasaCompra , '' , 
                        'DEVOLUCION ' . $devolution->type->descripcion . ' - ' . $devolution->numero_operacion . ' - ' . strtoupper( $solicitud->assignedTo->personal->full_name ) , ' ' , 'DEVOLUCION' );
                }

                // CONTRAPARTE ASIENTO DE ANTICIPO
                $tasaCompra = $this->getExpenseChangeRate( $solicitud , Carbon::now() );

                $description_seat_back = strtoupper($username . ' ' . $solicitud->titulo);
                if( $solicitud->idtiposolicitud == REEMBOLSO )
                {
                    $seatList[] = $this->createSeatElement( $cuentaMkt , $solicitud->id , CUENTA_HABER_REEMBOLSO , '' , Carbon::now()->format( 'd/m/Y' ) , '', '', '', '', '', '', '', ASIENTO_GASTO_DEPOSITO,
                    round( ( $solicitud->detalle->monto_aprobado - $total_percepciones )  * $tasaCompra , 2 , PHP_ROUND_HALF_DOWN ) , '', $description_seat_back, '', 'CAN');
                }
                else
                {
                    if ( $solicitud->id_inversion == 36 && $detalle->id_moneda == SOLES )
                    {
                        $cuentaMkt = 1893000;
                    }
                    elseif( $solicitud->id_inversion == 36 && $detalle->id_moneda == DOLARES )
                    {
                        $cuentaMkt = 1894000;
                    }
                    $seatList[] = $this->createSeatElement( $cuentaMkt, $solicitud->id, $cuentaMkt, '', Carbon::now()->format( 'd/m/Y' ) , '', '', '', '', '', '', '', ASIENTO_GASTO_DEPOSITO,
                    round( ( $solicitud->detalle->monto_aprobado - $total_percepciones )  * $tasaCompra , 2 , PHP_ROUND_HALF_DOWN ) , '', $description_seat_back, '', 'CAN');
                }

                $result['seatList'] = $seatList;
                return $result;
            }
        }
        return $middleRpta;
    }

    public function viewGenerateSeatExpense($token)
    {
        $solicitud = Solicitud::where('token', $token)->first();
        $expenses = $solicitud->expenses;
        $clientes = array();

        foreach ( $solicitud->clients as $client ) 
        {
            if ($client->from_table == TB_DOCTOR) 
            {
                $doctors = $client->doctors;
                $nom = $doctors->pefnombres . ' ' . $doctors->pefpaterno . ' ' . $doctors->pefmaterno;
            } 
            else if ($client->from_table == TB_FARMACIA)
            {
                $nom = $client->institutes->pejrazon;
            }
            else
            {
                $nom = 'No encontrado';
            }
            $clientes[] = $nom;
        }
        $clientes = implode(',', $clientes);
        $typeProof = ProofType::all();
        $date = $this->getDay();
        $expenseItem = array();

        foreach ($expenses as $expense) {
            $expenseItems = $expense->items;
            $expense->itemList = $expenseItems;
            $expense->count = count($expenseItems);
        }

        $solicitud->documentList = $expenses;
        $resultSeats = $this->generateSeatExpenseData($solicitud);

        $seatList = $resultSeats['seatList'];

        $data = array(
            'solicitud' => $solicitud,
            'expenseItem' => $expenses,
            'clientes' => $clientes,
            'typeProof' => $typeProof,
            'seats' => json_decode(json_encode($seatList))
        );

        if (isset($resultSeats['error'])) {
            $tempArray = array('error' => $resultSeats['error']);
            $data = array_merge($data, $tempArray);
        }
        Session::put('state', R_GASTO);
        return View::make('Dmkt.Cont.expense_seat', $data);
    }

    // IDKC: CHANGE STATUS => GENERADO
    public function saveSeatExpense()
    {
        try 
        {
            DB::beginTransaction();
            $dataInputs = Input::all();
            $seats = array();

            $solicitud = Solicitud::find( $dataInputs[ 'idsolicitud' ] );
            if ( ( in_array( $solicitud->idtiposolicitud , array( SOL_REP , SOL_INST ) ) && $solicitud->id_estado == GENERADO ) || 
                ( $solicitud->idtiposolicitud == REEMBOLSO  && $solicitud->id_estado == DEPOSITO_HABILITADO ) )
            {
                DB::rollback();
                return $this->warningException( 'La solicitud ya ha sido procesada' , __FUNCTION__ , __LINE__ , __FILE__ );
            }

            if ( isset( $dataInputs[ 'seatList' ] ) )
            {
                foreach ($dataInputs['seatList'] as $key => $seatItem) 
                {
                    $seat = new Entry;
                    $seat->id = $seat->lastId() + 1;
                    $seat->num_cuenta = $seatItem['numero_cuenta'];
                    $seat->cc = $seatItem['codigo_sunat'];
                    $fecha_seat_origen = Carbon::createFromFormat('d/m/Y', $seatItem['fec_origen']);
                    $seat->fec_origen = $fecha_seat_origen->toDateString();
                    $seat->iva = $seatItem['iva'];
                    $seat->cod_pro = $seatItem['cod_prov'];
                    $seat->nom_prov = $seatItem['nombre_proveedor'];
                    $seat->cod = $seatItem['cod'];
                    $seat->ruc = $seatItem['ruc'];
                    $seat->prefijo = $seatItem['prefijo'];
                    $seat->cbte_prov = $seatItem['cbte_proveedor'];
                    $seat->d_c = $seatItem['dc'];
                    $seat->importe = $seatItem['importe'];
                    $seat->leyenda_fj = $seatItem['leyenda'];
                    $seat->leyenda = $seatItem['leyenda_variable'];
                    $seat->tipo_resp = $seatItem['tipo_responsable'];
                    $seat->id_solicitud = $seatItem['solicitudId'];
                    $seat->tipo_asiento = ASIENTO_GASTO_TIPO;
                    $seat->save();

                    if( isset( $seats[ $seatItem[ 'solicitudId' ] ][ ASIENTO_GASTO_TIPO ] ) )
                    {
                        $seats[ $seatItem[ 'solicitudId' ] ][ ASIENTO_GASTO_TIPO ][] = $seat;   
                    }
                    else
                    {
                        $seats[ $seatItem[ 'solicitudId' ] ][ ASIENTO_GASTO_TIPO ] = array();
                        $seats[ $seatItem[ 'solicitudId' ] ][ ASIENTO_GASTO_TIPO ][] = $seat;              
                    }
                }
            }

            $oldIdEstado = $solicitud->id_estado;
            if ($solicitud->idtiposolicitud == REEMBOLSO)
                $solicitud->id_estado = DEPOSITO_HABILITADO;
            else
                $solicitud->id_estado = GENERADO;
            $user = Auth::user();
            $solicitud->save();

            if ($solicitud->idtiposolicitud == REEMBOLSO)
                $middleRpta = $this->setStatus($oldIdEstado, DEPOSITO_HABILITADO, $user->id, USER_TESORERIA, $solicitud->id);
            else
                $middleRpta = $this->setStatus($oldIdEstado, GENERADO, $user->id, $user->id, $solicitud->id);

            if ($middleRpta[status] == ok) 
            {
                if ($solicitud->idtiposolicitud == REEMBOLSO )
                {
                    Session::put('state', R_REVISADO);
                }
                else
                {
                    Session::put('state', R_FINALIZADO);
                }
                $this->generateBagoSeat( $seats );
                DB::commit();
                return $middleRpta;
            }
            DB::rollback();
            return $middleRpta;
        } catch (Exception $e) {
            DB::rollback();
            return $this->internalException($e, __FUNCTION__);
        }
    }

    public function viewSeatExpense($token)
    {
        $solicitud = Solicitud::where('token', $token)->firstOrFail();
        $expense = Expense::where('idsolicitud', $solicitud->id_solicitud)->get();
        $data = array(
            'solicitude' => $solicitud,
            'expense' => $expense
        );
        return View::make('Dmkt.Cont.register_seat_expense', $data);
    }

    // IDKC: CHANGE STATUS => GASTO HABILITADO

    private function validateInputAdvanceSeat($inputs)
    {
            $rules = array(
            'idsolicitud'    => 'required|integer|min:1|exists:solicitud,id',
            'number_account' => 'required|array|size:2|each:numeric|each:digits,7|each:exists,cuenta,num_cuenta',
            'dc'             => 'required|array|size:2|each:string|each:size,1|each:in,D,C',
            'total'          => 'required|array|size:2|each:numeric|each:min,1',
            'leyenda'        => 'required|array|size:2|each:required|each:string|each:min,1');
        $validator = Validator::make( $inputs , $rules );
        if ($validator->fails())
            return $this->warningException(substr($this->msgValidator($validator), 0, -1), __FUNCTION__, __LINE__, __FILE__);
        else
            return $this->setRpta();
    }

    public function generateSeatSolicitude()
    {
        try 
        {
            $middleRpta = array();
            $inputs = Input::all();
            $middleRpta = $this->validateInputAdvanceSeat( $inputs );
            if ( $middleRpta[status] == ok ) 
            {
                DB::beginTransaction();
                $solicitud = Solicitud::find( $inputs['idsolicitud'] );
                if ( $solicitud->id_estado != DEPOSITADO )
                {
                    return $this->warningException( 'Ya se realizo la Operación' , __FUNCTION__ , __LINE__ , __FILE__ );
                }

                $oldIdEstado = $solicitud->id_estado;

                if( $solicitud->idtiposolicitud == REEMBOLSO )
                {
                    $solicitud->id_estado = GENERADO;
                }
                elseif( in_array( $solicitud->idtiposolicitud , array( SOL_REP , SOL_INST ) ) )
                {
                    $solicitud->id_estado = GASTO_HABILITADO;    
                }
                $solicitud->save();

                $seats = array();
                $seats[ $solicitud->id ][ TIPO_ASIENTO_ANTICIPO ] = array();

                for ( $i = 0 ; $i < count( $inputs[ 'number_account' ] ) ; $i++ ) 
                {
                    $tbEntry               = new Entry;
                    $tbEntry->id           = $tbEntry->lastId() + 1;
                    $tbEntry->num_cuenta   = $inputs[ 'number_account' ][ $i ];
                    $tbEntry->fec_origen   = Carbon::createFromFormat( 'd/m/Y' , $solicitud->detalle->deposit->updated_at );
                    $tbEntry->d_c          = $inputs[ 'dc' ][ $i ];
                    $tbEntry->importe      = $inputs[ 'total' ][ $i ];
                    $tbEntry->leyenda      = trim( $inputs[ 'leyenda' ][ $i ] );
                    $tbEntry->id_solicitud = $inputs[ 'idsolicitud' ];
                    $tbEntry->tipo_asiento = TIPO_ASIENTO_ANTICIPO;
                    $tbEntry->save();
                    $seats[ $solicitud->id ][ TIPO_ASIENTO_ANTICIPO ][] = $tbEntry;
                }

                if( $solicitud->idtiposolicitud == REEMBOLSO )
                {
                    $toUser = Auth::user()->id;    
                }
                elseif( in_array( $solicitud->idtiposolicitud , array( SOL_REP , SOL_INST ) ) )
                {
                    $toUser = $solicitud->id_user_assign;
                }

                $middleRpta = $this->setStatus( $oldIdEstado , $solicitud->id_estado , Auth::user()->id , $toUser , $solicitud->id );
                if ( $middleRpta[ status ] === ok ) 
                {
                    if( $solicitud->idtiposolicitud == REEMBOLSO )
                    {
                        Session::put( 'state' , R_FINALIZADO );
                    }
                    elseif( in_array( $solicitud->idtiposolicitud , array( SOL_REP , SOL_INST ) ) )
                    {
                        Session::put( 'state' , R_GASTO );
                    }
                    $this->generateBagoSeat( $seats );
                    DB::commit();
                    return $middleRpta;
                }
                DB::rollback();
            }
            return $middleRpta;
        }
        catch ( Exception $e )
        {
            DB::rollback();
            return $this->internalException($e, __FUNCTION__);
        }
    }

    private function generateBagoSeat( $seats )
    {
            $migrateSeatController = new MigrateSeatController;
            $data = $migrateSeatController->transactionGenerateSeat( $seats );
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

    public function getTimeLine($id)
    {
        $solicitud = Solicitud::find($id);
        //$solicitud_history = $solicitud->histories;
        $solicitud_history = SolicitudHistory::where('id_solicitud', '=', $id)
            ->orderby('ID', 'ASC')
            ->get();
        $time_flow_event = TiempoEstimadoFlujo::all();
        $previus_date = null;
        $orden_history = 0;
        $duration_limit = 5;
        $duration_limit_max = 10;
        foreach ($solicitud_history as $history) {
            foreach ($time_flow_event as $time_flow){
                if ($time_flow->status_id == $history->status_from && $time_flow->to_user_type == $history->user_from){
                    $history->estimed_time = $time_flow->hours;
                    break;
                }
            }
        }
        foreach ($solicitud_history as $history) {
            if ($previus_date) {
                $date_a = $history->created_at;
                $date_b = $previus_date;

                $interval = date_diff($date_a, $date_b);
//                dd($interval);
                $days = $interval->days;
//                $period = new DatePeriod($date_a, new DateInterval('P1D'), $date_b);
//                foreach($period as $dt) {
//                    $curr = $dt->format('D');
//                    // substract if Saturday or Sunday
//                    if ($curr == 'Sat' || $curr == 'Sun') {
//                        $days--;
//                    }
//                }
//
//

                $history->duration = $interval->h < 1? $interval->format('%i M') :$interval->format('%h H');
                if ($interval->h <= $history->estimed_time){
                    $history->duration_color = 'success';
                    $history->hand = 'glyphicon-thumbs-up';
                }
//                elseif ($interval->h <= $duration_limit_max)
//                    $history->duration_color = 'warning';
                else{
                    $history->duration_color = 'danger';
                    $history->hand = 'glyphicon-thumbs-down';
                }
            }
            $previus_date = $history->created_at;
            $history->orden = $orden_history;
            $orden_history++;

        }
        $tasa = $this->getExchangeRate( $solicitud );

        $flujo1 = $solicitud->investment->approvalInstance->approvalPolicies()
            ->orderBy( 'orden' , 'ASC' )->get();
        $flujo = array();

        if( is_null( $solicitud->approvedHistory ) )
        {
            foreach($flujo1 as $fl)
            {
                if($fl->desde == null)
                    $flujo[] = $fl;
                elseif( $fl->desde < ( $solicitud->detalle->monto_actual * $tasa ) || ( $solicitud->id_estado == DERIVADO && $fl->tipo_usuario == GER_PROD ) )
                    $flujo[] = $fl;
            }
        }
        else
        {
            foreach( $solicitud->histories()->whereIn( 'status_to' , array( DERIVADO , ACEPTADO , APROBADO ) )->orderBy( 'created_at' , 'id' )->get() as $approvalFlow )
            {
                $approvalFlow->tipo_usuario = $approvalFlow->user_to;
                $flujo[] = $approvalFlow;
            }
        }


        $type_user = TypeUser::all();
        foreach( $flujo as $fl ) 
        {
            foreach( $type_user as $type ) 
            {
                if( $fl->tipo_usuario == $type->codigo ) 
                {
                    $fl->nombre_usuario = $type->descripcion;
                    break;
                }
            }
        }

        $status_flow = null;
        foreach( $flujo as $fl ) 
        {
            if(isset($status_flow))
            {
                $fl->status = 2;
            }
            else
            {
                $status_flow = 1;
                $fl->status = 1;
            }

            foreach( $time_flow_event as $time_flow )
            {
                if ($time_flow->status_id == $fl->status && $time_flow->to_user_type == $fl->tipo_usuario)
                {
                    $fl->estimed_time = $time_flow->hours;
                    break;
                }
            }
        }
//      dd($flujo);
        $linehard = unserialize(TIMELINEHARD);
        $linecese = unserialize(TIMELINECESE);
        //$motivo = $solicitud->detalle->id_motivo;
        $motivo = $solicitud->idtiposolicitud;

        $line_static = array();
        foreach ( $linehard as $line ) 
        {
            $cond = false;
            $condFin = false;
            foreach ($line as $key => $value) 
            {
                if( $solicitud->state->id_estado == R_NO_AUTORIZADO )
                {
                    break;
                }

                if( $key == 'status_id' && $value == GASTO_HABILITADO )
                {
                    $line[ 'info' ] = is_null( $solicitud->id_user_assign ) ? $line[ 'info' ] : strtoupper( $solicitud->assignedTo->personal->full_name );
                }

                if ( $key == 'cond' ) 
                {
                    $cond = true;
                }

                if ( $key == 'cond_add_motivo' ) 
                {
                    if ( $motivo == $value )
                    {
                        $cond = true;
                    }
                    else
                    {
                        $cond = false;
                    }
                }
                
                if ( $key == 'cond_sub_motivo' )
                {
                    if ( $motivo == $value )
                    {
                        $cond = false;
                    }
                    else
                    {
                        $cond = true;
                    }
                }
                
                if( $key == 'cond_cese' )
                {
                    if( $value && $solicitud->id_estado == 30 )
                    {
                        array_push( $line_static , $linecese[ 1 ] );
                        $condFin = true;
                    }
                }
            }
            if( $condFin )
            {
                break;
            }
            elseif ($cond)
            {
                array_push($line_static, $line);
            }
        }

        $devolutionHistory = $this->getDevolutionTimeLine( $solicitud );

        return  View::make('template.Modals.timeLine2')
                ->with( array(
                    'solicitud'         => $solicitud, 
                    'solicitud_history' => $solicitud_history, 
                    'flujo'             => $flujo, 
                    'line_static'       => $line_static, 
                    'time_flow_event'   => $time_flow_event,
                    'devolutions'       => $devolutionHistory )
                )->render();
    }

    private function getDevolutionTimeLine( $solicitud )
    {
        return $solicitud->devolutions()->where( 'id_tipo_devolucion' , DEVOLUCION_INMEDIATA )
               ->orderBy( 'created_at' , 'ASC' )->orderBy( 'id' , 'ASC' )->get();
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

    private function getExpenseChangeRate( $solicitud , $date )
    {        
        if( $solicitud->detalle->id_moneda == SOLES )
        {
            $tasaCompra = 1;
        }
        elseif( $solicitud->detalle->id_moneda == DOLARES )
        {
            $tc = ChangeRate::getDayTc( $date );
            if ( is_null( $tc ) )
            {
                $tasaCompra = ChangeRate::getTc()->venta;
            }
            else
            {
                $tasaCompra = $tc->venta;
            }
        }
        return $tasaCompra;
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
}
