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
use \Dmkt\Account;
use \Expense\ChangeRate;
use \Expense\PlanCta;
use \Validator;
use \System\FondoHistory;

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
        $monto = $detalle->monto_actual;
        $msg = 'El Saldo del Fondo: '.$fondo->nombre.' '.$fondo->typeMoney->simbolo.' '.$fondo->saldo.' es insuficiente para completar la operación';
        if ( $detalle->id_moneda == $fondo->id_moneda )
            if ( $monto > $fondo->saldo )
                return $this->warningException( $msg , __FUNCTION__ , __LINE__ , __FILE__ );
            else
                return $this->setRpta($monto);
        else
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

    private function validateInpustDeposit( $inputs )
    {
        $rules = array( 'token'       => 'required|exists:solicitud,token,id_estado,' . DEPOSITO_HABILITADO ,
                        'num_cuenta'  => 'required|numeric|exists:b3o.plancta,ctactaextern',
                        'op_number'   => 'required|min:1' );
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
                
                $middleRpta = $this->validateBalance( $detalle , $tc );
                if ( $middleRpta[status] == ok )
                { 
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
                        $newDeposit->total              = round( $middleRpta[data]['monto'] , 2 , PHP_ROUND_HALF_DOWN );
                        $newDeposit->save();
                        $detalle->id_deposito = $newDeposit->id;
                        $detalle->detalle = json_encode( $middleRpta[data]['jDetalle'] );
                        $detalle->save();
                        $solicitud->id_estado = DEPOSITADO;
                        $solicitud->save();

                        $middleRpta = $this->fondoDecrease( $detalle->fondo , $detalle );
                        if ( $middleRpta[status] == ok )
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
            DB::rollback();
            return $middleRpta;
        }
        catch( Exception $e ) 
        {
            DB::rollback();
            return $this->internalException( $e , __FUNCTION__ );
        }
    }

    private function fondoDecrease( $fondo , $detalle )
    {
        $saldo_inicial = $fondo->saldo;
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
        $fondo->save();
        $fondoHistory = new FondoHistory;
        $fondoHistory->id = $fondoHistory->nextId();
        $fondoHistory->saldo_inicial = $saldo_inicial;
        $fondoHistory->saldo_final = $fondo->saldo;
        $fondoHistory->id_solicitud = $detalle->id;
        $fondoHistory->id_fondo = $fondo->id;
        $fondoHistory->save();
        \Log::error( json_encode( $fondoHistory ) );
        return $this->setRpta();
    }
    
}