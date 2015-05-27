<?php

namespace Deposit;

use \BaseController;
use \View;
use \Session;
use \Auth;
use \User;
use \Common\State;
use \Common\Deposit;
use \Dmkt\Solicitud;
use \Input;
use \Redirect;
use \DB;
use \Exception;
use \Common\StateRange;
use \Log;
use \Dmkt\Account;
use \Expense\ChangeRate;
use \Expense\PlanCta;
use \Validator;

class DepositController extends BaseController{

    private function objectToArray($object)
    {
        $array = array();
        foreach ($object as $member => $data) {
            $array[$member] = $data;
        }
        return $array;
    }

    private function validateBalance( $detalle , $tc )
    {
        $fondo = $detalle->fondo;
        $monto = $detalle->monto_aprobado;
        $msg = 'El Saldo del Fondo: '.$fondo->nombre.' '.$fondo->typeMoney->simbolo.' '.$fondo->saldo.' es insuficiente para completar la operación';
        if ( $detalle->id_moneda == $fondo->idtipomoneda )
        {
            if ( $monto > $fondo->saldo )
                return $this->warningException( $msg , __FUNCTION__ , __LINE__ , __FILE__ );
            else
                return $this->setRpta($monto);
        }
        else 
        {
            if ( $detalle->id_moneda == DOLARES )
                if ( ( $monto * $tc->compra ) > $fondo->saldo )
                    return $this->warningException( $msg , __FUNCTION__ , __LINE__ , __FILE__ );
                else
                    return $this->setRpta( $monto * $tc->compra);
            elseif ( $detalle->id_moneda == SOLES )
                if ( ( $monto / $tc->venta ) > $fondo->saldo )
                    return $this->warningException( $msg , __FUNCTION__ , __LINE__ , __FILE__ );
                else
                    return $this->setRpta( $monto / $tc->venta );
            else
                return $this->warningException( 'Tipo de Moneda no registrada: ' . $detalle->id_moneda , __FUNCTION__ , __LINE__ , __FILE__ );
        }
    }

    private function validateInpustDeposit( $inputs )
    {
        try
        {
            $rules = array(
                'token'       => 'required',
                'num_cuenta'  => 'required|numeric|digits_between:6,8',
                'op_number'   => 'required|min:3'
            );
            if ( Validator::make( $inputs , $rules )->fails() ) 
                return $this->warningException( substr( $this->msgValidator($validator), 0 , -1 ) , __FUNCTION__ , __LINE__ , __FILE__ );
            else
                return $this->setRpta();
        }
        catch ( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    private function verifyMoneyType( $solIdMoneda , $bankIdMoneda , $monto , $tc , $jDetalle )
    {
        try
        {
            $jDetalle->tcc = $tc->compra;
            $jDetalle->tcv = $tc->venta;    
            if ( $solIdMoneda != $bankIdMoneda )
            {
                if ( $solIdMoneda == SOLES)
                    $monto = $monto / $tc->venta;
                elseif ( $solIdMoneda == DOLARES )
                    $monto = $monto * $tc->compra;
                else
                    return $this->warningException( __FUNCTION__ , 'Tipo de Moneda no Registrada con Id: '.$solIdMoneda );
                return $this->setRpta( array( 'monto' => $monto , 'jDetalle' => $jDetalle ) );
            }
            else
                return $this->setRpta( array( 'monto' => $monto , 'jDetalle' => $jDetalle ) );
        }
        catch( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
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
        try
        {
            $jDetalle = json_decode( $detalle->detalle );
            return $this->verifyMoneyType( $detalle->id_moneda , $bank->idtipomoneda , $jDetalle->monto_aprobado , $tc , $jDetalle );
        }                      
        catch ( Exception $e )
        {
            return $this->internalException( $e , __FUNCTION__ );
        }
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
                if ( is_null( $solicitud ) )
                    return $this->warningException( 'Cancelado - No se encontro la solicitud (Cod:'.$inputs['token'].')' , __FUNCTION__ , __LINE__ , __FILE__ );
                else
                {
                    if ( $solicitud->id_estado != DEPOSITO_HABILITADO )
                        return $this->warningException( 'Cancelado - No se puede depositar la solicitud en esta etapa del flujo: '.$solicitud->id_estado , __FUNCTION__ , __LINE__ , __FILE__ );
                    else
                    {
                        $oldIdestado  = $solicitud->id_estado;
                        $detalle      = $solicitud->detalle;
                        $tc           = ChangeRate::getTc();
                        if ( ! is_null( $detalle->id_deposito )  )
                            return $this->warningException( 'Cancelado - El deposito ya ha sido registrado' , __FUNCTION__ , __LINE__ , __FILE__ );
                        else
                        {
                            $middleRpta = $this->validateBalance( $detalle , $tc );
                            if ( $middleRpta[status] == ok )
                            {
                                $fondo = $detalle->fondo;
                                $fondo->saldo = $fondo->saldo - round( $middleRpta[data] , 2 , PHP_ROUND_HALF_DOWN );
                                if ( !$fondo->save() )
                                    return $this->warningException( 'Cancelado - No se pudo procesar el saldo del fondo' , __FUNCTION__ , __LINE__ , __FILE__ );
                                else
                                { 
                                    $bagoAccount = PlanCta::find( $inputs['num_cuenta'] );
                                    if ( is_null( $bagoAccount ) )
                                        return $this->warningException( 'Cancelado - No se encontro la Cuenta del Banco con Id: '.$inputs['idcuenta'] , __FUNCTION__ , __LINE__ , __FILE__ );
                                    else
                                    {
                                        if ( $bagoAccount->account->idtipocuenta != BANCO )
                                            return $this->warningException( 'Cancelado - La cuenta N°: '.$inputs['num_cuenta'].' no ha sido registrada en el Sistema como Cuenta de Banco' , __FUNCTION__ , __LINE__ , __FILE__ );
                                        else
                                        {
                                            $middleRpta = $this->getBankAmount( $detalle , $bagoAccount->account , $tc );
                                            if ( $middleRpta[status] == ok )
                                            {    
                                                $newDeposit                     = new Deposit;
                                                $newDeposit->id                 = $newDeposit->lastId() + 1;
                                                $newDeposit->num_transferencia  = $inputs['op_number'];
                                                $newDeposit->num_cuenta         = $inputs['num_cuenta'];
                                                $newDeposit->total              = round( $middleRpta[data]['monto'] , 2 , PHP_ROUND_HALF_DOWN );
                                                if( !$newDeposit->save() )
                                                    return $this->warningException( 'Cancelado - No se pudo procesar el deposito' , __FUNCTION__ , __LINE__ , __FILE__ );
                                                else
                                                {
                                                    $detalle->id_deposito = $newDeposit->id;
                                                    $detalle->detalle = json_encode( $middleRpta[data]['jDetalle'] );
                                                    if ( !$detalle->save() )
                                                        return $this->warningException( 'Cancelado - No se pudo procesar el detalle de la solicitud' , __FUNCTION__ , __LINE__ , __FILE__ );
                                                    else
                                                    {
                                                        $solicitud->id_estado = DEPOSITADO;
                                                        if ( $solicitud->save() )
                                                        {
                                                            $middleRpta = $this->setStatus( $oldIdestado, DEPOSITADO , Auth::user()->id , USER_CONTABILIDAD , $solicitud->id );
                                                            if ( $middleRpta[status] == ok )
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
                            }
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
    
}