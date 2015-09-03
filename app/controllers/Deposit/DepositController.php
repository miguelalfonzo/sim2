<?php

namespace Deposit;

use \BaseController;
use \View;
use \Session;
use \Auth;
use \Common\Deposit;
use \Dmkt\Solicitud;
use \Input;
use \DB;
use \Exception;
use \Expense\ChangeRate;
use \Expense\PlanCta;
use \Validator;
use \Fondo\FondoMkt;
use \User;
use \Devolution\DevolutionController;
use \Carbon\Carbon;

class DepositController extends BaseController
{

    private function objectToArray($object)
    {
        $array = array();
        foreach ( $object as $member => $data )
            $array[$member] = $data;
        return $array;
    }

    private function validateInpustDeposit( $inputs )
    {
        $rules = array( 'token'       => 'required|exists:solicitud,token,id_estado,' . DEPOSITO_HABILITADO ,
                        'num_cuenta'  => 'required|numeric|exists:'.TB_PLAN_CUENTA.',ctactaextern',
                        'op_number'   => 'required|string|min:1' );
        $validator = Validator::make( $inputs , $rules );
        if ( $validator->fails() ) 
            return $this->warningException( substr( $this->msgValidator( $validator ), 0 , -1 ) , __FUNCTION__ , __LINE__ , __FILE__ );
        else
            return $this->setRpta();
    }

    private function verifyMoneyType( $solIdMoneda , $bankIdMoneda , $monto , $tc , $jDetalle )
    {
        $jDetalle->tcc = $tc->compra;
        $jDetalle->tcv = $tc->venta;    
        if ( $solIdMoneda != $bankIdMoneda )
        {
            if ( $solIdMoneda == SOLES )
                $monto = $monto / $tc->venta;
            elseif ( $solIdMoneda == DOLARES )
                $monto = $monto * $tc->compra;
            else
                return $this->warningException( 'Tipo de Moneda no Registrada con Id: '.$solIdMoneda , __FUNCTION__ , __LINE__ , __FILE__ );
            return $this->setRpta( array( 'monto' => $monto , 'jDetalle' => $jDetalle ) );
        }
        else
            return $this->setRpta( array( 'monto' => $monto , 'jDetalle' => $jDetalle ) );
    }

    private function amountRate( $jDetalle , $tc , $type )
    {
        if ( $type == 1 )
            return ( ( ( $jDetalle->monto_aprobado / $tc->venta ) - $jDetalle->monto_retencion ) * $tc->venta );
        elseif ( $type == 2 )
            return ( ( ( $jDetalle->monto_aprobado * $tc->compra ) - $jDetalle->monto_retencion ) / $tc->compra );
    }

    private function getBankAmount( $detalle , $bank , $tc )
    {
        $jDetalle = json_decode( $detalle->detalle );
        return $this->verifyMoneyType( $detalle->id_moneda , $bank->idtipomoneda , $jDetalle->monto_aprobado , $tc , $jDetalle );
    }

    // IDKC: CHANGE STATUS => DEPOSITADO
    public function depositSolicitudeTes()
    {
        try
        {
            DB::beginTransaction();
            $inputs      = Input::all();
            $middleRpta  = $this->validateInpustDeposit( $inputs );
            if ( $middleRpta[status] == ok )
            {
                $solicitud   = Solicitud::where( 'token' , $inputs['token'] )->first();
                
                $oldIdestado  = $solicitud->id_estado;
                $detalle      = $solicitud->detalle;
                $tc           = ChangeRate::getTc();
                
                if ( ! is_null( $detalle->id_deposito )  )
                    return $this->warningException( 'Cancelado - El deposito ya ha sido registrado' , __FUNCTION__ , __LINE__ , __FILE__ );
            
                $bagoAccount = PlanCta::find( $inputs['num_cuenta'] );
                if ( $bagoAccount->account->idtipocuenta != BANCO )
                    return $this->warningException( 'Cancelado - La cuenta N°: '.$inputs['num_cuenta'].' no ha sido registrada en el Sistema como Cuenta de Banco' , __FUNCTION__ , __LINE__ , __FILE__ );
                        
                $middleRpta = $this->getBankAmount( $detalle , $bagoAccount->account , $tc );
                if ( $middleRpta[status] == ok )
                {    
                    $newDeposit                     = new Deposit;
                    $newDeposit->id                 = $newDeposit->lastId() + 1;
                    $newDeposit->num_transferencia  = $inputs['op_number'];
                    $newDeposit->num_cuenta         = $inputs['num_cuenta'];
                    $newDeposit->total              = $middleRpta[data]['monto'];
                    $newDeposit->save();

                    $detalle->id_deposito = $newDeposit->id;
                    $detalle->detalle     = json_encode( $middleRpta[data]['jDetalle'] );
                    $detalle->save();

                    if ( $solicitud->idtiposolicitud == REEMBOLSO )
                        $solicitud->id_estado = GENERADO;
                    else
                        $solicitud->id_estado = DEPOSITADO;
                    $solicitud->save();

                    $middleRpta = $this->discountFondoBalance( $solicitud );
                    
                    if ( $middleRpta[ status ] == ok )
                    {
                        if ( $solicitud->idtiposolicitud == REEMBOLSO )
                            $middleRpta = $this->setStatus( $oldIdestado, GENERADO , Auth::user()->id , USER_CONTABILIDAD , $solicitud->id );
                        else
                            $middleRpta = $this->setStatus( $oldIdestado, DEPOSITADO , Auth::user()->id , USER_CONTABILIDAD , $solicitud->id );
                
                        if ( $middleRpta[status] == ok )
                        {
                            if ( $solicitud->idtiposolicitud == REEMBOLSO )
                                Session::put( 'state' , R_FINALIZADO );
                            else
                                Session::put( 'state' , R_REVISADO );
                            DB::commit();
                            return $middleRpta;
                        }
                    }
                }
            }
            DB::rollback();
            return $middleRpta;
        }
        catch( Exception $e ) 
        {
            DB::rollback();
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    private function discountFondoBalance( $solicitud )
    {
        $fondoMktController = new FondoMkt;
        $fondoDataHistories = array();
        $fondosData         = array();
        
        $tasaCompra           = $this->getExchangeRate( $solicitud );
        $tasaCompraAprobacion = $this->getApprovalExchangeRate( $solicitud );
        $msg                = ' el cual no es suficiente para completar el deposito , se requiere un saldo de S/.';
        
        if ( in_array( $solicitud->idtiposolicitud , array( SOL_REP , REEMBOLSO ) ) )
        {
            $products = $solicitud->products;
            $fondo_type = $products[ 0 ]->id_tipo_fondo_marketing;
            foreach( $products as $solicitudProduct )
            {
                $fondo            = $solicitudProduct->thisSubFondo;
                $oldSaldo         = $fondo->saldo;
                $oldRetencion     = $fondo->retencion;
                $fondo->saldo     -= $solicitudProduct->monto_asignado * $tasaCompra;
                $fondo->retencion -= $solicitudProduct->monto_asignado * $tasaCompraAprobacion;
                if ( isset( $fondoData[ $fondo->id ] ) )
                    $fondosData[ $fondo->id ] += $solicitudProduct->monto_asignado;
                else
                    $fondosData[ $fondo->id ] = $solicitudProduct->monto_asignado;
                $data = array(
                   'idFondo'      => $fondo->id , 
                   'idFondoTipo'  => $fondo_type ,
                   'oldSaldo'     => $oldSaldo ,
                   'oldRetencion' => $oldRetencion , 
                   'newSaldo'     => $fondo->saldo , 
                   'newRetencion' => $fondo->retencion ,
                   'reason'       => FONDO_DEPOSITO );
                $fondoDataHistories[] = $data;
                $fondoMktController->setPeriodHistoryData( $fondo->subcategoria_id , $data );
                $fondo->save();
            }       
            $middleRpta = $fondoMktController->validateFondoSaldo( $fondosData , $fondo_type , $msg );
            if ( $middleRpta[ status] != ok )
                return $middleRpta;
        }
        elseif ( $solicitud->idtiposolicitud == SOL_INST )
        {    
            $detalle          = $solicitud->detalle;

            $fondo            = $detalle->thisSubFondo;
            $oldSaldo         = $fondo->saldo;
            $oldRetencion     = $fondo->retencion;
            $fondo->saldo     -= $detalle->monto_aprobado * $tasaCompra;
            $fondo->retencion -= $detalle->monto_aprobado * $tasaCompraAprobacion;

            if ( $fondo->saldo < 0 )
                return $this->warningException( 'El Fondo ' . $this->fondoName( $fondo ) . ' solo cuenta con S/.' . ( $fondo->saldo + $fondoMonto ) . 
                                                $msg . $fondoMonto . ' en total' , __FUNCTION__ , __FILE__ , __LINE__ );
            $data = array(
                'idFondo'      => $fondo->id , 
                'idFondoTipo'  => INVERSION_INSTITUCIONAL ,
                'oldSaldo'     => $oldSaldo , 
                'oldRetencion' => $oldRetencion , 
                'newSaldo'     => $fondo->saldo , 
                'newRetencion' => $fondo->retencion , 
                'reason'       => FONDO_DEPOSITO );
            $fondoDataHistories[] = $data;
            $fondoMktController->setPeriodHistoryData( $fondo->subcategoria_id , $data );
            $fondo->save();
        }
        $fondoMktController->setFondoMktHistories( $fondoDataHistories , $solicitud->id );  
        return $this->setRpta();
    }

    public function modalExtorno()
    {
        $inputs = Input::all();
        $solicitud = Solicitud::where( 'token' , $inputs[ 'token' ] )->first();
        if ( is_null ( $solicitud ) )
            return $this->warningException( 'No se encontro la informacion de la solicitud' , __FUNCTION__ , __FILE__ , __LINE__ );
        else
            return $this->setRpta( array( 'View' => View::make( 'template.Modals.extorno' , array( 'solicitud' => $solicitud ) )->render() ) );
    }

    public function confirmExtorno()
    {
        $inputs    = Input::all();
        $rules     = array( 'numero_operacion' => 'required|min:1' );
        $validator = Validator::make( $inputs , $rules );
        
        if ( $validator->fails() )
            return $this->warningException( substr( $this->msgValidator( $validator ), 0 , -1 ) , __FUNCTION__ , __LINE__ , __FILE__ );
        
        $solicitud = Solicitud::where( 'token' , $inputs[ 'token' ] )->first();
        
        if ( is_null( $solicitud ) )
            return $this->warningException( 'No se encontro la informacion de la solicitud' , __FUNCTION__ , __FILE__ , __LINE__ );
        elseif( $solicitud->id_estado != DEPOSITADO )
            return $this->warningException( 'La solicitud ya ha sido validada por contabilidad' , __FUNCTION__ , __FILE__ , __LINE__ );
        else
        {
            $deposito = $solicitud->detalle->deposit;
            $deposito->num_transferencia = $inputs[ 'numero_operacion' ];
            $deposito->save();
            return $this->setRpta();
        }
    }

    public function modalLiquidation()
    {
        $inputs = Input::all();
        $solicitud = Solicitud::where( 'token' , $inputs[ 'token' ] )->first();
        if ( is_null ( $solicitud ) )
            return $this->warningException( 'No se encontro la informacion de la solicitud' , __FUNCTION__ , __FILE__ , __LINE__ );
        else
            return $this->setRpta( array( 'View' => View::make( 'template.Modals.liquidation' , array( 'solicitud' => $solicitud ) )->render() ) );
    }

    public function confirmLiquidation()
    {
        try
        {
            $inputs    = Input::all();      
            $solicitud = Solicitud::where( 'token' , $inputs[ 'token' ] )->first();
            $oldIdestado = $solicitud->id_estado;

            if ( is_null( $solicitud ) )
            {
                return $this->warningException( 'No se encontro la informacion de la solicitud' , __FUNCTION__ , __FILE__ , __LINE__ );
            }
            elseif( ! in_array( $solicitud->id_estado, [DEPOSITADO, GASTO_HABILITADO] ) ) 
            {
                return $this->warningException( 'No se puede Cancelar por Liquidacion' , __FUNCTION__ , __FILE__ , __LINE__ );
            }
            else
            {
                DB::beginTransaction(); 
                
                //REGISTRO DE LA DEVOLUCION
                $devolucion = new DevolutionController;
                $periodo = Carbon::createFromFormat( 'm-Y' , $inputs[ 'periodo' ] )->format( 'Ym' );
                $devolucion->setDevolucion( $solicitud->id , $periodo , $solicitud->detalle->monto_aprobado , DEVOLUCION_CONFIRMADA , DEVOLUCION_LIQUIDACION );

                //RETORNO DE SALDO AL FONDO
                $fondo = new FondoMkt;
                $fondo->refund( $solicitud , $solicitud->detalle->monto_aprobado , 8 );
                
                //ACTUALIZACION DEL ESTADO DE LA SOLICITUD
                $solicitud->id_estado = 30;
                $solicitud->save();

                if ( $solicitud->idtiposolicitud != SOL_INST )
                {
                    $middleRpta = $this->setStatus( $oldIdestado , 30 , Auth::user()->id, $solicitud->approvedHistory->created_by , $solicitud->id );
                }
                else
                {
                    $middleRpta = $this->setStatus( $oldIdestado , 30 , Auth::user()->id, $solicitud->created_by , $solicitud->id );    
                }

                if ( $middleRpta[ status ] == ok )
                {
                    Session::put( 'state' , R_FINALIZADO );
                    DB::commit();
                    return $this->setRpta();
                }
                else
                {
                    DB::rollback();
                    return $middleRpta;
                }
            }

        }
        catch( Exception $e )
        {
            DB::rollback();
            return $this->internalException($e,__FUNCTION__);
        }
    }
}