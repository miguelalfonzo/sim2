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
use \Users\Manager;
use \Users\Sup;
use \Users\Rm;
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
        if (Session::has('state'))
            $state = Session::get('state');
        else {
            if (Auth::user()->type == CONT)
                $state = R_APROBADO;
            else if (in_array(Auth::user()->type, array(REP_MED, SUP, GER_PROD, GER_PROM, GER_COM, ASIS_GER)))
                $state = R_PENDIENTE;
            elseif (Auth::user()->type == TESORERIA)
                $state = R_REVISADO;
        }
        $mWarning = array();
        if (Session::has('warnings')) {
            $warnings = Session::pull('warnings');
            $mWarning[status] = ok;
            if (!is_null($warnings))
                foreach ($warnings as $key => $warning)
                    $mWarning[data] = $warning[0] . ' ';
            $mWarning[data] = substr($mWarning[data], 0, -1);
        }
        $data = array('state' => $state, 'states' => StateRange::order(), 'warnings' => $mWarning);
        if (Auth::user()->type == TESORERIA) {
            $data['tc']    = ChangeRate::getTc();
            $data['banks'] = Account::banks();
        } elseif (Auth::user()->type == ASIS_GER) {
            $data['activities'] = Activity::order();
        } elseif (Auth::user()->type == CONT) {
            $data['proofTypes'] = ProofType::order();
            $data['regimenes']  = Regimen::all();
        }

        if (Session::has('id_solicitud')) {
            $solicitud = Solicitud::find(Session::pull('id_solicitud'));
            $solicitud->status = ACTIVE;
            $solicitud->save();
        }

        $alert = new AlertController;
        $data['alert'] = $alert->alertConsole();
        return View::make('template.User.show', $data);
    }

    public function newSolicitude()
    {
        include(app_path() . '/models/Query/QueryProducts.php');
        $data = array('reasons' => Reason::all(),
            'activities' => Activity::order(),
            'payments' => TypePayment::all(),
            'currencies' => TypeMoney::all(),
            'families' => $qryProducts->get(),
            'investments' => InvestmentType::orderMkt());
        if (in_array(Auth::user()->type, array(SUP, GER_PROD)))
            $data['reps'] = Rm::order();
        return View::make('Dmkt.Register.solicitud', $data);
    }

    public function editSolicitud($token)
    {
        include(app_path() . '/models/Query/QueryProducts.php');
            $data = array( 'solicitud'   => Solicitud::where('token', $token)->firstOrFail(),
                           'reasons'     => Reason::all(),
                           'activities'  => Activity::order(),
                           'payments'    => TypePayment::all(),
                           'currencies'  => TypeMoney::all(),
                           'families'    => $qryProducts->get(),
                           'investments' => InvestmentType::orderMkt(),
                           'edit'        => true);
        $data['detalle'] = $data['solicitud']->detalle;
        if (in_array(Auth::user()->type, array(SUP, GER_PROD)))
            $data['reps'] = Rm::order();
        return View::make('Dmkt.Register.solicitud', $data);
    }

    public function viewSolicitude($token)
    {
        try {
            $solicitud = Solicitud::where('token', $token)->first();
            $politicStatus = FALSE;
            $user = Auth::user();
            if (is_null($solicitud))
                return $this->warningException('No se encontro la Solicitud con Token: ' . $token, __FUNCTION__, __LINE__, __FILE__);

            $detalle = $solicitud->detalle;
            $data = array('solicitud' => $solicitud, 'detalle' => $detalle);

            if ($solicitud->idtiposolicitud != SOL_INST && in_array($solicitud->id_estado, array(PENDIENTE, DERIVADO, ACEPTADO))) {
                $politicType = $solicitud->investment->approvalInstance->approvalPolicyOrder( $solicitud->histories->count() )->tipo_usuario;
                if ( in_array( $politicType, array( Auth::user()->type , Auth::user()->tempType() ) )
                    && (array_intersect( array( Auth::user()->id, Auth::user()->tempId() ), $solicitud->managerEdit($politicType)->lists('id_gerprod')))
                ) {
                    $politicStatus = TRUE;
                    $data['tipo_usuario'] = $politicType;
                    $solicitud->status = BLOCKED;
                    Session::put('id_solicitud', $solicitud->id);
                    $solicitud->save();
                    $data['solicitud']->status = 1;
                }
            } elseif (Auth::user()->type == TESORERIA && $solicitud->id_estado == DEPOSITO_HABILITADO) {
                $data['banks'] = Account::banks();
                $data['deposito'] = $detalle->monto_aprobado;
            } elseif (Auth::user()->type == CONT) {
                $data['date'] = $this->getDay();
                if ($solicitud->id_estado == DEPOSITADO)
                    $data['lv'] = $this->textLv($solicitud);
                elseif (!is_null($solicitud->registerHistory)) {
                    $data = array_merge($data, $this->expenseData($solicitud, $detalle->monto_actual));
                    $data['igv'] = Table::getIGV();
                    $data['regimenes'] = Regimen::all();
                }
            } elseif (!is_null($solicitud->expenseHistory) && $user->id == $solicitud->id_user_assign) {
                $data = array_merge($data, $this->expenseData($solicitud, $detalle->monto_actual));
                $data['igv'] = Table::getIGV();
                $data['date'] = $this->getExpenseDate($solicitud);
            }
            Session::put('state', $data['solicitud']->state->id_estado);
            $data['politicStatus'] = $politicStatus;
            $alert = new AlertController;
            if (is_null($data['solicitud']->registerHistory) && !in_array($data['solicitud']->id_estado, array(CANCELADO, RECHAZADO)))
                $data['alert'] = $alert->compareTime($data['solicitud'], 'diffInMonths');

            $event = Event::where('solicitud_id', '=', $solicitud->id)->get();
            if ($event->count() != 0)
                $data['event'] = $event[0];
            return View::make('Dmkt.Solicitud.view', $data);
        } catch (Exception $e) {
            return $this->internalException($e, __FUNCTION__);
        }
    }

    private function expenseData($solicitud, $monto_aprobado)
    {
        $data = array('typeProof' => ProofType::orderBy('id', 'asc')->get(),
            'typeExpense' => ExpenseType::order(),
            'date' => $this->getExpenseDate($solicitud));
        $gastos = $solicitud->expenses;
        if (count($gastos) > 0) {
            $data['expenses'] = $gastos;
            $balance = $gastos->sum('monto');
            $data['balance'] = $monto_aprobado - $balance;
        }
        return $data;
    }

    private function validateInputSolRep($inputs)
    {

        $rules = array(
            'idsolicitud'   => 'integer|min:1|exists:'.TB_SOLICITUD.',id',
            'motivo'        => 'required|integer|min:1|exists:'.TB_MOTIVO.',id',
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
            'descripcion'   => 'string|max:200'
        );

        if (in_array(Auth::user()->type, array(SUP, GER_PROD)))
            $rules['responsable'] = 'required|numeric|min:1|exists:outdvp.dmkt_rg_rm,iduser';

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

    private function setPago(&$jDetalle, $paymentType, $ruc)
    {
        if ($paymentType == PAGO_CHEQUE)
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
        foreach ($solProductIds as $key => $solProductId) 
        {
            $solProduct = SolicitudProduct::find($solProductId);

            $old_id_fondo_mkt = $solProduct->id_fondo_marketing;
            $old_cod_user_type = $solProduct->id_tipo_fondo_marketing;
            $old_ammount = $solProduct->monto_asignado;

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
        $fondoMktController->discountBalance($ids_fondo_mkt, $moneda, $tc, $detalle->solicitud->id, $userTypeforDiscount);
        return $fondoMktController->validateBalance($userTypes, $fondos);
    }

    private function renovateBalance($solicitud)
    {
        $fondoMktController = new FondoMkt;
        $solicitudProducts = $solicitud->products;
        $ids_fondo_mkt = array();
        foreach ($solicitudProducts as $solicitudProduct) {
            $ids_fondo_mkt[] = array('old' => $solicitudProduct->id_fondo_marketing,
                'oldUserType' => $solicitudProduct->id_tipo_fondo_marketing,
                'oldMonto' => $solicitudProduct->monto_asignado);
        }
        $fondoMktController->discountBalance($ids_fondo_mkt, $solicitud->detalle->id_moneda, ChangeRate::getTc(), $solicitud->id);
    }

    private function setProducts($idSolicitud, $idsProducto)
    {
        foreach ($idsProducto as $idProducto) {
            $solProduct = new SolicitudProduct;
            $solProduct->id = $solProduct->lastId() + 1;
            $solProduct->id_solicitud = $idSolicitud;
            $solProduct->id_producto = $idProducto;
            if (!$solProduct->save())
                return $this->warningException(__FUNCTION__, 'No se pudo procesar los productos de la solicitud');
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
        try {
            DB::beginTransaction();
            $inputs     = Input::all();
            $middleRpta = $this->validateInputSolRep($inputs);
            if ($middleRpta[status] == ok) {
                if (isset($inputs['idsolicitud'])) {
                    $solicitud = Solicitud::find($inputs['idsolicitud']);
                    $detalle   = $solicitud->detalle;
                    $this->unsetRelations($solicitud);
                } else {
                    $solicitud = new Solicitud;
                    $solicitud->id = $solicitud->lastId() + 1;
                }

                $detalle               = new SolicitudDetalle;
                $detalle->id           = $detalle->lastId() + 1;
                $solicitud->id_detalle = $detalle->id;
                $solicitud->token      = sha1(md5(uniqid($solicitud->id, true)));
                $this->setSolicitud($solicitud, $inputs);
                // dd($inputs);
                $solicitud->save();

                $jDetalle              = new stdClass();
                $this->setJsonDetalle($jDetalle, $inputs);
                $detalle->detalle      = json_encode($jDetalle);
                $this->setDetalle($detalle, $inputs);
                $detalle->save();

                $middleRpta = $this->setClients($solicitud->id, $inputs['clientes'], $inputs['tipos_cliente']);
                if ($middleRpta[status] == ok) {
                    $middleRpta = $this->setProducts($solicitud->id, $inputs['productos']);
                    if ($middleRpta[status] == ok) {
                        if ( ! isset( $inputs[ 'responsable' ] ) )
                            $inputs[ 'responsable' ] = 0;
                        $middleRpta = $this->toUser( $solicitud->investment->approvalInstance , $inputs['productos'] , 1 , $inputs['responsable'] );
                        if ( $middleRpta[status] == ok ):
                            $middleRpta = $this->setGerProd($middleRpta[data]['iduser'], $solicitud->id, $middleRpta[data]['tipousuario']);
                            if ($middleRpta[status] == ok):
                                $middleRpta = $this->setStatus(0, PENDIENTE, Auth::user()->id, $middleRpta[data], $solicitud->id);
                                if ($middleRpta[status] == ok):
                                    Session::put('state', R_PENDIENTE);
                                    DB::commit();
                                    return $middleRpta;
                                endif;
                            endif;
                        endif;
                    }
                }
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
        if (in_array(Auth::user()->type, array(SUP, GER_PROD)))
            $solicitud->id_user_assign = $inputs['responsable'];
        elseif (Auth::user()->type == REP_MED)
            $solicitud->id_user_assign = Auth::user()->id;
    }

    private function setDetalle($detalle, $inputs)
    {
        $detalle->id_moneda = $inputs['moneda'];
        $detalle->id_pago = $inputs['pago'];
    }

    private function textLv($solicitud)
    {
        if ($solicitud->idtiposolicitud == SOL_REP)
            return $this->textAccepted($solicitud) . ' - ' . $solicitud->titulo . ' - ' . $this->textClients($solicitud);
        else
            return $this->textAccepted($solicitud) . ' - ' . $solicitud->titulo;
    }

    private function textAccepted($solicitud)
    {
        if ($solicitud->idtiposolicitud == SOL_REP)
            // if ($solicitud->approvedHistory->user->type == SUP)
            //     return $solicitud->approvedHistory->user->sup->full_name;
            // elseif ($solicitud->approvedHistory->user->type == GER_PROD)
            //     return $solicitud->approvedHistory->user->gerProd->full_name;
            // elseif ($solicitud->approvedHistory->user->type == REP_MED)
            //     return $solicitud->approvedHistory->user->rm->full_name;
            // elseif (!is_null($solicitud->approvedHistory->user->simApp))
            //     return $solicitud->approvedHistory->user->person->full_name;
            // else
            //     return 'No Registrado';
            return $solicitud->approvedHistory->user->personal->getFullName();
        else if ($solicitud->idtiposolicitud == SOL_INST)
            // return $solicitud->createdBy->person->full_name;
            return $solicitud->createdBy->personal->getFullName();
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
        try {
            DB::beginTransaction();
            $inputs = Input::all();
            $rules = array('idsolicitud' => 'required|numeric|min:1|exists:solicitud,id', 'observacion' => 'required|string|min:10');
            $validator = Validator::make($inputs, $rules);

            if ($validator->fails())
                return $this->warningException(substr($this->msgValidator($validator), 0, -1), __FUNCTION__, __LINE__, __FILE__);

            $solicitud = Solicitud::find($inputs['idsolicitud']);
            if ($solicitud->idtiposolicitud == SOL_INST) {
                $periodo = $solicitud->detalle->periodo;
                if ($periodo->status == BLOCKED)
                    return $this->warningException('No se puede eliminar las solicitudes del periodo: ' . $periodo->aniomes, __FUNCTION__, __LINE__, __FILE__);
                if (count(Solicitud::solInst($periodo->aniomes)) == 1)
                    Periodo::inhabilitar($periodo->aniomes);
            } elseif ($solicitud->idtiposolicitud == SOL_REP) {
                if (!in_array($solicitud->id_estado, State::getCancelStates()))
                    return $this->warningException('No se puede cancelar las solicitudes en esta etapa: ' . $solicitud->state->nombre, __FUNCTION__, __LINE__, __FILE__);
            } else
                return $this->warningException('Tipo de Solicitud: ' . $solicitud->idtiposolicitud . ' no registrado', __FUNCTION__, __LINE__, __FILE__);

            $oldIdEstado = $solicitud->id_estado;
            if (Auth::user()->id == $solicitud->created_by) {
                $solicitud->id_estado = CANCELADO;
                if ($oldIdEstado != PENDIENTE)
                    $this->renovateBalance($solicitud);
            } else {
                $solicitud->id_estado = RECHAZADO;
                if ($oldIdEstado != PENDIENTE)
                    $this->renovateBalance($solicitud);
            }
            $solicitud->observacion = $inputs['observacion'];
            $solicitud->status = 1;
            $solicitud->save();

            $rpta = $this->setStatus($oldIdEstado, $solicitud->id_estado, Auth::user()->id, $solicitud->created_by, $solicitud->id);
            if ($rpta[status] === ok) {
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

    private function verifyPolicy( $solicitud , $monto )
    {
        $type = array(Auth::user()->type, Auth::user()->tempType());
        $approvalPolicy = $solicitud->investment->approvalInstance->approvalPolicyTypesOrder( $type , $solicitud->histories->count() );
        if ( is_null( $approvalPolicy ) )
            return $this->warningException( 'No se encontro la politica de aprobacion para la inversion: ' . $solicitud->id_inversion . ' y su rol: ' . Auth::user()->type, __FUNCTION__, __LINE__, __FILE__);
        
        if ( is_null( $approvalPolicy->desde ) && is_null( $approvalPolicy->hasta ) ):
            return $this->setRpta( ACEPTADO ); //$this->setRpta( DERIVADO );
        else:
            if ( $solicitud->detalle->id_moneda == DOLARES )
                $monto = $monto * ChangeRate::getTc()->compra;
            if ( $monto > $approvalPolicy->hasta )
                return $this->setRpta( ACEPTADO );
            elseif ($monto < $approvalPolicy->desde )
                return $this->warningException( 'Por Politica solo puede aceptar para este Tipo de Inversion montos mayores a: ' . $approvalPolicy->desde , __FUNCTION__ , __LINE__ , __FILE__ );
            else
                return $this->setRpta( APROBADO );
        endif;
    }

    private function toUser( $approvalInstance , $idsProducto, $order, $responsable = NULL)
    {
        $approvalPolicy = $approvalInstance->approvalPolicyOrder( $order );
        
        if ( is_null( $approvalPolicy ) )
            return $this->warningException( 'La inversion no cuenta con una politica de aprobacion para esta etapa del flujo (' . $order . ')' , __FUNCTION__, __LINE__, __FILE__);
        
        $userType = $approvalPolicy->tipo_usuario;
        $msg = '';
        
        if ( ! is_null( $approvalPolicy->desde ) || ! is_null( $approvalPolicy->hasta ) )
            $msg .= ' para la siguiente etapa del flujo , comuniquese con Informatica. El rol aprueba montos ' . ( is_null( $approvalPolicy->desde ) ? '' : 'mayores a S/.' . $approvalPolicy->desde . ' ') . ( is_null( $approvalPolicy->hasta ) ? '' : 'hasta S/.' . $approvalPolicy->hasta );
        if ( $userType == SUP ): 
            if ( Auth::user()->type === REP_MED ){
                $temp = Personal::getSup( Auth::user()->id );
                $idsUser = array( $temp->user_id ); // idkc : ES CORRECTO ESTE TIPO DE PARSE? SI ES UN MODELO XQ NO USAR ->toArray() ?
            }
            else if ( Auth::user()->type === SUP )
                $idsUser = array( Auth::user()->id );
            else if ( Auth::user()->type === GER_PROD )
                $idsUser = array( Personal::getSup( $responsable )->user_id );
            else
                return $this->warningException( 'El rol ' . Auth::user()->type . ' no tiene permisos para crear o derivar al supervisor' , __FUNCTION__ , __LINE__ , __FILE__ );
        elseif ( $userType == GER_PROD ):
            $idsGerProd = Marca::whereIn( 'id' , $idsProducto )->lists( 'gerente_id' );
            $uniqueIdsGerProd = array_unique( $idsGerProd );
            $repeatIds = array_count_values( $idsGerProd );
            $maxNumberRepeat = max( $repeatIds );
            Session::put( 'maxRepeatIdsGerProd' , Manager::whereIn( 'id' , array_keys( $repeatIds , $maxNumberRepeat ) )->lists( 'iduser' ) );
            $notRegisterGerProdName = Manager::getGerProdNotRegisteredName( $uniqueIdsGerProd );
        
            if ( count( $notRegisterGerProdName ) === 0 )
                $idsUser = Manager::whereIn( 'id' , $uniqueIdsGerProd )->lists( 'iduser' );
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
        try {
            $inputs = Input::all();
            $rules = array('solicitudes' => 'required');
            $validator = Validator::make($inputs, $rules);
            if ($validator->fails())
                return $this->warningException($this->msg2Validator($validator), __FUNCTION__, __LINE__, __FILE__);
            else {
                $status = array(ok => array(), error => array());
                $message = '';
                foreach ($inputs['solicitudes'] as $solicitud) {
                    $solicitud = Solicitud::where('token', $solicitud)->first();
                    $solicitudProducts = $solicitud->orderProducts;
                    $fondo = array();

                    foreach ($solicitudProducts as $solicitudProduct)
                        $fondo[] = $solicitudProduct->id_fondo_marketing . ',' . $solicitudProduct->id_tipo_fondo_marketing;

                    $inputs = array('idsolicitud' => $solicitud->id,
                        'monto' => $solicitud->detalle->monto_actual,
                        'producto' => $solicitud->orderProducts()->lists('id'),
                        'anotacion' => $solicitud->anotacion,
                        'fondo_producto' => $fondo);

                    $solProducts = $solicitud->orderProducts();
                    if ($solicitud->id_estado == DERIVADO)
                        $inputs['monto_producto'] = array_fill(0, count($solProducts->get()), $inputs['monto'] / count($solProducts->get()));
                    else
                        $inputs['monto_producto'] = $solProducts->lists('monto_asignado');
                    $rpta = $this->acceptedSolicitudTransaction($solicitud->id, $inputs);
                    if ($rpta[status] != ok) {
                        $status[error][] = $solicitud['token'];
                        $message .= $message . 'No se pudo procesar la Solicitud N°: ' . $solicitud->id . ': ' . $rpta[description] . '. ';
                    } else
                        $status[ok][] = $solicitud['token'];
                }
                if (empty($status[error]))
                    return array(status => ok, 'token' => $status, description => 'Se aprobaron las solicitudes seleccionadas');
                else if (empty($status[ok]))
                    return array(status => danger, 'token' => $status, description => substr($message, 0, -1));
                else
                    return array(status => warning, 'token' => $status, description => substr($message, 0, -1));
            }
        } catch (Exception $e) {
            return $this->internalException($e, __FUNCTION__);
        }
    }

    private function validateInputAcceptSolRep($inputs)
    {
        $rules = array(
                    'idsolicitud'    => 'required|integer|min:1|exists:'.TB_SOLICITUD.',id',
                    'monto'          => 'required|numeric|min:1',
                    'anotacion'      => 'sometimes|string|min:1',
                    'producto'       => 'required|array|min:1|each:integer|each:min,1|each:exists,'.TB_SOLICITUD_PRODUCTO.',id',
                    'monto_producto' => 'required|array|min:1|each:numeric|each:min,1|sumequal:monto',
                    'fondo_producto' => 'required|array|each:string|each:min,1'
                );
        $validator = Validator::make($inputs, $rules);
        if ($validator->fails())
            return $this->warningException(substr($this->msgValidator($validator), 0, -1), __FUNCTION__, __LINE__, __FILE__);
        else
            return $this->setRpta();
    }

    private function acceptedSolicitudTransaction($idSolicitud, $inputs)
    {
        DB::beginTransaction();
        $middleRpta = $this->validateInputAcceptSolRep($inputs);
        if ($middleRpta[status] === ok) {
            $solicitud  = Solicitud::find($idSolicitud);
            $middleRpta = $this->verifyPolicy($solicitud, $inputs['monto']);
            if ($middleRpta[status] == ok) {
                $oldIdEstado          = $solicitud->id_estado;
                $solicitud->id_estado = $middleRpta[data];
                $solicitud->status    = ACTIVE;
                if (isset($inputs['anotacion']))
                    $solicitud->anotacion = $inputs['anotacion'];
                $solicitud->save();

                $solDetalle = $solicitud->detalle;
                $detalle    = json_decode($solDetalle->detalle);                
                $monto      = round($inputs['monto'], 2, PHP_ROUND_HALF_DOWN);

                if ($solicitud->id_estado == DERIVADO)
                    $detalle->monto_derivado = $monto;
                if ($solicitud->id_estado == ACEPTADO)
                    $detalle->monto_aceptado = $monto;
                else if ($solicitud->id_estado == APROBADO) ;
                $detalle->monto_aprobado = $monto;

                $middleRpta = $this->setProductsAmount($inputs['producto'], $inputs['monto_producto'], $inputs['fondo_producto'], $solDetalle);

                if ($middleRpta[status] != ok):
                    DB::rollback();
                    return $middleRpta;
                endif;

                $solDetalle->detalle = json_encode($detalle);
                $solDetalle->save();

                if ($solicitud->id_estado != APROBADO) {
                    $middleRpta = $this->toUser( $solicitud->investment->approvalInstance , SolicitudProduct::getSolProducts( $inputs['producto'] ), $solicitud->histories->count() + 1 );
                    if ($middleRpta[status] != ok)
                        return $middleRpta;
                    else {
                        $middleRpta = $this->setGerProd($middleRpta[data]['iduser'], $solicitud->id, $middleRpta[data]['tipousuario']);
                        if ($middleRpta[status] == ok)
                            $toUser = $middleRpta[data];
                        else
                            return $middleRpta;
                    }
                } else
                    $toUser = USER_CONTABILIDAD;

                $middleRpta = $this->setStatus($oldIdEstado, $solicitud->id_estado, Auth::user()->id, $toUser, $solicitud->id);
                if ($middleRpta[status] == ok) {
                    Session::put('state', $solicitud->state->rangeState->id);
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
            return $this->internalException($e, __FUNCTION__);
        }
    }

    public function checkSolicitud()
    {
        try {
            DB::beginTransaction();
            $inputs = Input::all();
            $solicitud = Solicitud::find($inputs['idsolicitud']);
            if (is_null($solicitud))
                return $this->warningException('Cancelado - No se encontro la solicitud con Id: ' . $inputs['idsolicitud'], __FUNCTION__, __LINE__, __FILE__);

            if ($solicitud->id_estado != APROBADO)
                return $this->warningException('Cancelado - No se puede procesar una solicitud que no ha sido Aprobada', __FUNCTION__, __LINE__, __FILE__);

            $oldIdEstado = $solicitud->id_estado;
            if ($solicitud->idtiposolicitud == REEMBOLSO) {
                $solicitud->id_estado = GASTO_HABILITADO;
                $toUser = $solicitud->id_user_assign;
                $state = R_GASTO;
            } else {
                $solicitud->id_estado = DEPOSITO_HABILITADO;
                $toUser = USER_TESORERIA;
                $state = R_REVISADO;
            }
            $solicitud->save();

            $middleRpta = $this->setStatus($oldIdEstado, $solicitud->id_estado, Auth::user()->id, $toUser, $solicitud->id);
            if ($middleRpta[status] == ok) {
                Session::put('state', $state);
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

    public function getCuentaCont($cuentaMkt)
    {
        $result = array();
        if (!empty($cuentaMkt)) {
            $accountElement = Account::getExpenseAccount($cuentaMkt);
            $account = count($accountElement) == 0 ? array() : json_decode($accountElement->toJson());
            if (count($account) > 0)
                $result['account'] = $account;
            else {
                $errorTemp = array(
                    'error' => ERROR_NOT_FOUND_MATCH_ACCOUNT_MKT_CNT,
                    'msg' => MESSAGE_NOT_FOUND_MATCH_ACCOUNT_MKT_CNT
                );
                if (!isset($result['error']) || !in_array($errorTemp, $result['error']))
                    $result['error'][] = $errorTemp;
            }

        } else {
            $result['error'] = ERROR_INVALID_ACCOUNT_MKT;
            $result['msg'] = MSG_INVALID_ACCOUNT_MKT;
        }
        return $result;
    }

    private function searchFundAccount($solicitud)
    {
        $fondo = $solicitud->investment->accountFund;
            if (is_null($fondo))
                return $this->warningException('No se encontro el Fondo asignado a la solicitud', __FUNCTION__, __LINE__, __FILE__);
            else
                return $this->setRpta($fondo);
    }

    public function generateSeatExpenseData($solicitud)
    {
        $result = array();
        $seatList = array();

        $middleRpta = $this->searchFundAccount($solicitud);
        if ($middleRpta[status] == ok) {
            $fondo = $middleRpta[data];
            $cuentaExpense = '';
            $marcaNumber = '';
            $cuentaMkt = '';
            if (!is_null($fondo)) {
                $cuentaMkt = $fondo->num_cuenta;

                $cuentaExpense = Account::getExpenseAccount($cuentaMkt);

                if (!is_null($cuentaExpense[0]->num_cuenta)) {
                    $cuentaExpense = $cuentaExpense[0]->num_cuenta;
                    $marcaNumber = MarkProofAccounts::getMarks($cuentaMkt, $cuentaExpense);
                    $marcaNumber = $marcaNumber[0]->marca_codigo;
                } else
                    $result['error'][] = $accountResult['error'];
            }
            $userElement = $solicitud->asignedTo;
            $tipo_responsable = $userElement->tipo_responsable;
            $username = '';

            $userType = $userElement->type;
            if ($userType == REP_MED)
                $username = $userElement->rm->full_name;
            elseif ($userType == SUP)
                $username = $userElement->sup->full_name;
            elseif ($userType == ASIS_GER)
                $username = $userElement->person->full_name;

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
                                $expense->ruc, $expense->num_prefijo, $expense->num_serie, ASIENTO_GASTO_BASE, $itemElement->importe,
                                $marca, $description_seat_item, $tipo_responsable, '');

                            $total_neto += $itemElement->importe;
                        }

                        //ASIENTO DE IGV
                        if ($expense->igv != 0)
                            $seatList[] = $this->createSeatElement($cuentaMkt, $solicitud->id, $cuentaExpense, $comprobante->cta_sunat, $fecha_origen, ASIENTO_GASTO_IVA_IGV, ASIENTO_GASTO_COD_PROV_IGV, $expense->razon, ASIENTO_GASTO_COD_IGV, $expense->ruc, $expense->num_prefijo, $expense->num_serie, ASIENTO_GASTO_BASE, $expense->igv, $marca, $description_seat_igv, $tipo_responsable, 'IGV');

                        //ASIENTO IMPUESTO SERVICIO
                        if ( ! ( $expense->imp_serv == null || $expense->imp_serv == 0 || $expense->imp_serv == '') )
                        {
                            $porcentaje = $total_neto / $expense->imp_serv;


                            $description_seat_tax_service = strtoupper('SERVICIO ' . $porcentaje . '% ' . $expense->descripcion);
                            $seatList[] = $this->createSeatElement($cuentaMkt, $solicitud->id, $cuentaExpense, '', $fecha_origen, '', '', '', '', '', '', '', ASIENTO_GASTO_BASE, $expense->imp_serv, $marca, $description_seat_tax_service, '', 'SER');


                        }
                        //ASIENTO REPARO
                        if ($expense->reparo == 1) 
                        {
                            $seatList[] = $this->createSeatElement($cuentaMkt, $solicitud->id, CUENTA_REPARO_COMPRAS, '', $fecha_origen, '', '', '', '', '', '', '', ASIENTO_GASTO_BASE, $expense->igv, $marca, $description_seat_repair_base, '', 'REP');
                            $seatList[] = $this->createSeatElement($cuentaMkt, $solicitud->id, CUENTA_REPARO_GOBIERNO, '', $fecha_origen, '', '', '', '', '', '', '', ASIENTO_GASTO_DEPOSITO, $expense->igv, $marca, $description_seat_repair_deposit, '', 'REP');
                        }

                        //ASIENTO RETENCION
                        if ($expense->idtipotributo == 1)
                        {
                            $seatList[] = $this->createSeatElement($cuentaMkt, $solicitud->id, CUENTA_RETENCION_DEBE, '', $fecha_origen, '', '', '', '', '', '', '', ASIENTO_GASTO_BASE, $expense->monto_tributo, $marca, $description_seat_retencion_base, '', 'RET');
                            $seatList[] = $this->createSeatElement($cuentaMkt, $solicitud->id, CUENTA_RETENCION_HABER, '', $fecha_origen, '', '', '', '', '', '', '', ASIENTO_GASTO_DEPOSITO, $expense->monto_tributo, $marca, $description_seat_retencion_deposit, '', 'RET');
                        }

                        //ASIENTO DETRACCION
                        if ($expense->idtipotributo == 2 )
                        {
                            $total_percepciones += $expense->monto_tributo;
                            $seatList[] = $this->createSeatElement($cuentaMkt, $solicitud->id, CUENTA_DETRACCION_HABER, '', $fecha_origen, '', '', '', '', '', '', '', ASIENTO_GASTO_DEPOSITO, $expense->monto_tributo, $marca, $description_seat_detraccion_deposit, '', 'DET');
                        }

                        //ASIENTO DETRACCION REEMBOLSO
                        if ($expense->idtipotributo == 2 && $solicitud->idtiposolicitud == REEMBOLSO) 
                        {
                            $total_percepciones += ($expense->monto - $expense->monto_tributo);
                            $seatList[] = $this->createSeatElement($cuentaMkt, $solicitud->id, CUENTA_DETRACCION_REEMBOLSO, '', $fecha_origen, '', '', '', '', '', '', '', ASIENTO_GASTO_BASE, $expense->monto - $expense->monto_tributo, $marca, $description_detraccion_reembolso, '', 'DET');
                        }
                    }
                    else //TODOS LOS OTROS DOCUMENTOS
                    {
                        $description_seat_renta4ta_deposit = strtoupper('RENTA 4TA CATEGORIA ' . $desc);

                        //ASIENTO DOCUMENT - NO ITEM
                        $description_seat_other_doc = strtoupper( $username .' '. $expense->descripcion );
                        $seatList[] = $this->createSeatElement($cuentaMkt , $solicitud->id , $cuentaExpense , $comprobante->cta_sunat, $fecha_origen, ASIENTO_GASTO_IVA_BASE, ASIENTO_GASTO_COD_PROV, $expense->razon, ASIENTO_GASTO_COD, $expense->ruc, $expense->num_prefijo, $expense->num_serie, ASIENTO_GASTO_BASE, $expense->monto, $marca, $description_seat_other_doc, $tipo_responsable, ''); 
                    
                        //ASIENTO IMPUESTO A LA RENTA
                        if ($expense->idtipotributo == 1) 
                        {
                            $total_percepciones += $expense->monto_tributo;
                            $seatList[] = $this->createSeatElement($cuentaMkt, $solicitud->id, CUENTA_RENTA_4TA_HABER, '', $fecha_origen, '', '', '', '', '', '', '', ASIENTO_GASTO_DEPOSITO, $expense->monto_tributo, $marca, $description_seat_renta4ta_deposit, '', 'RENTA');
                        }

                        //ASIENTO IMPUESTO A LA RENTA REEMBOLSO
                        if ($expense->idtipotributo == 1 && $solicitud->idtiposolicitud == REEMBOLSO ) 
                        {
                            $total_percepciones += ($expense->monto - $expense->monto_tributo);
                            $seatList[] = $this->createSeatElement($cuentaMkt, $solicitud->id, CUENTA_DETRACCION_REEMBOLSO, '', $fecha_origen, '', '', '',
                                '', '', '', '', ASIENTO_GASTO_DEPOSITO, $expense->monto - $expense->monto_tributo, $marca,
                                $description_seat_renta4ta_deposit, '', 'RENTA');
                        }
                    }
                }

                // CONTRAPARTE ASIENTO DE ANTICIPO
                $description_seat_back = strtoupper($username . ' ' . $solicitud->titulo);
                $seatList[] = $this->createSeatElement($cuentaMkt, $solicitud->id, $cuentaMkt, '', $fecha_origen, '', '', '', '', '', '', '', ASIENTO_GASTO_DEPOSITO, json_decode($solicitud->detalle->detalle)->monto_aprobado - $total_percepciones, '', $description_seat_back, '', 'CAN');
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

        foreach ($solicitud->clients as $client) {
            if ($client->from_table == TB_DOCTOR) {
                $doctors = $client->doctors;
                $nom = $doctors->pefnombres . ' ' . $doctors->pefpaterno . ' ' . $doctors->pefmaterno;
            } elseif ($client->from_table == TB_FARMACIA)
                $nom = $client->institutes->pejrazon;
            else
                $nom = 'No encontrado';
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
        try {
            DB::beginTransaction();
            $dataInputs = Input::all();
            if (isset($dataInputs['seatList']))
                foreach ($dataInputs['seatList'] as $key => $seatItem) {
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
                }

            $solicitud = Solicitud::find($dataInputs['idsolicitud']);
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

            if ($middleRpta[status] == ok) {
                DB::commit();
                if ($solicitud->idtiposolicitud == REEMBOLSO)
                    Session::put('state', R_REVISADO);
                else
                    Session::put('state', R_FINALIZADO);
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
        $rules = array('idsolicitud' => 'required|integer|min:1|exists:solicitud,id',
            'number_account' => 'required|array|size:2|each:numeric|each:digits,7|each:exists,cuenta,num_cuenta',
            'dc' => 'required|array|size:2|each:string|each:size,1|each:in,D,C',
            'total' => 'required|array|size:2|each:numeric|each:min,1',
            'leyenda' => 'required|string|min:1');
        $validator = Validator::make($inputs, $rules);
        if ($validator->fails())
            return $this->warningException(substr($this->msgValidator($validator), 0, -1), __FUNCTION__, __LINE__, __FILE__);
        else
            return $this->setRpta();
    }

    public function generateSeatSolicitude()
    {
        try {
            $middleRpta = array();
            $inputs = Input::all();
            $middleRpta = $this->validateInputAdvanceSeat($inputs);
            if ($middleRpta[status] == ok) {
                DB::beginTransaction();
                $solicitud = Solicitud::find($inputs['idsolicitud']);
                $oldIdEstado = $solicitud->id_estado;
                $solicitud->id_estado = GASTO_HABILITADO;
                $solicitud->save();

                for ($i = 0; $i < count($inputs['number_account']); $i++) {
                    $tbEntry = new Entry;
                    $tbEntry->id = $tbEntry->lastId() + 1;
                    $tbEntry->num_cuenta = $inputs['number_account'][$i];
                    $tbEntry->fec_origen = Carbon::createFromFormat('d/m/Y', $solicitud->detalle->deposit->updated_at);
                    $tbEntry->d_c = $inputs['dc'][$i];
                    $tbEntry->importe = $inputs['total'][$i];
                    $tbEntry->leyenda = trim($inputs['leyenda']);
                    $tbEntry->id_solicitud = $inputs['idsolicitud'];
                    $tbEntry->tipo_asiento = TIPO_ASIENTO_ANTICIPO;
                    $tbEntry->save();
                }
                $middleRpta = $this->setStatus($oldIdEstado, GASTO_HABILITADO, Auth::user()->id, $solicitud->id_user_assign, $solicitud->id);
                if ($middleRpta[status] === ok) {
                    Session::put('state', R_GASTO);
                    DB::commit();
                    return $middleRpta;
                }
                DB::rollback();
            }
            return $middleRpta;
        } catch (Exception $e) {
            DB::rollback();
            return $this->internalException($e, __FUNCTION__);
        }
    }

    public function findResponsables()
    {
        try {
            $inputs = Input::all();
            $responsables = array();
            $solicitud = Solicitud::find($inputs['idsolicitud']);
            if (is_null($solicitud->id_user_assign)) {
                if ($solicitud->id_estado == PENDIENTE || $solicitud->id_estado == DERIVADO) {
                    if ($solicitud->createdBy->type == REP_MED)
                        array_push($responsables, $solicitud->createdBy->rm);
                    else {
                        if (Auth::user()->type == SUP)
                            $responsables = array_merge($responsables, Auth::user()->sup->reps->toArray());
                        else
                            $responsables = array_merge($responsables, Rm::all()->toArray());
                    }
                    return $this->setRpta($responsables);
                } else
                    return $this->warningException(__FUNCTION__, 'No se puede buscar los responsable de la solicitud con Id: ' . $solicitud->id . ' debido a que no se encuentra PENDIENTE');
            } else
                return $this->setRpta('');
        } catch (Exception $e) {
            return $this->internalException($e, __FUNCTION__);
        }
    }

    public function findDocument()
    {
        $data = array('proofTypes' => ProofType::order(), 'regimenes' => Regimen::all());
        return View::make('Dmkt.Cont.documents_menu')->with($data);
    }

    public function showSolicitudeInstitution()
    {
        if (in_array(Auth::user()->type, array(ASIS_GER)))
            $state = R_PENDIENTE;
        $mWarning = array();
        if (Session::has('warnings')) {
            $warnings = Session::pull('warnings');
            $mWarning[status] = ok;
            if (!is_null($warnings))
                foreach ($warnings as $key => $warning)
                    $mWarning[data] = $warning[0] . ' ';
            $mWarning[data] = substr($mWarning[data], 0, -1);
        }
        $data = array('state' => $state, 'states' => StateRange::order(), 'warnings' => $mWarning);
        if (Auth::user()->type == ASIS_GER) 
        {
            $data[ 'investments' ] = InvestmentType::orderInst();
            $data[ 'subFondos' ]  = FondoInstitucional::getSubFondo();
        }
        if (Session::has('id_solicitud')) {
            $solicitud = Solicitud::find(Session::pull('id_solicitud'));
            $solicitud->status = ACTIVE;
            $solicitud->save();
        }
        $alert = new AlertController;
        $data['alert'] = $alert->alertConsole();
        return View::make('template.User.institucion', $data);
    }

    public function getTimeLine($id)
    {

        $solicitud = Solicitud::find($id);
//        $solicitud_history = $solicitud->histories;
        $solicitud_history = SolicitudHistory::where('id_solicitud', '=', $id)
            ->orderby('ID', 'ASC')
            ->get();
//        dd((array)$solicitud_history);
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


        /*$flujo = DB::table('INVERSION_POLITICA_APROBACION')
            ->join('POLITICA_APROBACION', function ($join) use ($solicitud) {
                $join->on('INVERSION_POLITICA_APROBACION.ID_POLITICA_APROBACION', '=', 'POLITICA_APROBACION.ID')
                    ->where('INVERSION_POLITICA_APROBACION.ID_INVERSION', '=', $solicitud->id_inversion);
            })
            ->where('POLITICA_APROBACION.DESDE', '<', $solicitud->detalle->monto_actual)
            ->orwhere('POLITICA_APROBACION.DESDE', '=', null)
            ->orderBy('ORDEN', 'ASC')
            ->get();*/

        $flujo1 = $solicitud->investment->approvalInstance->approvalPolicies()
            ->orderBy( 'orden' , 'ASC' )->get();
        $flujo = array();
        foreach($flujo1 as $fl){
            if($fl->desde == null)
                $flujo[] = $fl;
            elseif($fl->desde < $solicitud->detalle->monto_actual)
                $flujo[] = $fl;
        }
        $type_user = TypeUser::all();
        foreach ($flujo as $fl) {

            foreach ($type_user as $type) {
                if ($fl->tipo_usuario == $type->codigo) {
                    $fl->nombre_usuario = $type->descripcion;
                    break;
                }
            }

        }
        $status_flow = null;
        foreach ($flujo as $fl) {
            if(isset($status_flow))
            {
                $fl->status = 2;
            }else{
                $status_flow = 1;
                $fl->status = 1;
            }

            foreach ($time_flow_event as $time_flow){
                if ($time_flow->status_id == $fl->status && $time_flow->to_user_type == $fl->tipo_usuario){
                    $fl->estimed_time = $time_flow->hours;
                    break;
                }
            }
        }
//      dd($flujo);
        $linehard = unserialize(TIMELINEHARD);
        //$motivo = $solicitud->detalle->id_motivo;
        $motivo = $solicitud->idtiposolicitud;

        $line_static = array();
        foreach ($linehard as $line) {
            $cond = false;
            foreach ($line as $key => $value) {
                if ($key == 'cond_sol_type' && $solicitud->idtiposolicitud == $value) {
                    $cond = true;
                    break;
                } elseif ($key == 'cond_sol_motivo' && $motivo == $value) {
                    $cond = true;
                    break;
                } elseif ($key == 'cond') {
                    $cond = true;
                }
            }
            if ($cond)
                array_push($line_static, $line);
        }


        return View::make('template.Modals.timeLine2')->with(array('solicitud' => $solicitud, 'solicitud_history' =>
            $solicitud_history, 'flujo' => $flujo, 'line_static' => $line_static , 'time_flow_event' => $time_flow_event))->render();
    }

    public function album()
    {
        return View::make('Event.show');
    }

    public function getEventList()
    {
        // dd(Input::all());
        $start          = Input::get("date_start");
        $end            = Input::get("date_end");
        $data           = array();
        $data['events'] = Event::whereRaw("created_at between to_date('$start','DD-MM-YY') and to_date('$end','DD-MM-YY')+1")->get();
        return View::make('Event.album', $data);
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

    public function viewTestUploadImgSave()
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
}