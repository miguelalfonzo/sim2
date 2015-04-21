<?php

namespace Deposit;

use \BaseController;
use \View;
use \Session;
use \Auth;
use \User;
use \Common\State;
use \Common\Deposit;
use \Dmkt\Solicitude;
use \Input;
use \Redirect;
use \DB;
use \Exception;
use \Common\StateRange;
use \Log;
use \Dmkt\Account;
use \Expense\ChangeRate;
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

    public function viewSolicitudeTes($token)
    {
        $solicitude = Solicitude::where('token', $token)->firstOrFail();
        return View::make('Treasury.view_solicitude_tes')->with('solicitude', $solicitude);
    }

    public function viewFondoTes($token)
    {
        $solicitude = FondoInstitucional::where('token', $token)->firstOrFail();
        return View::make('Treasury.view_solicitude_tes')->with('solicitude', $solicitude);        
    }
    

    private function validateBalance( $detalle , $tc )
    {
        $fondo = $detalle->fondo;
        $monto = json_decode($detalle->detalle)->monto_aprobado;
        $msg = 'El Saldo del Fondo: '.$fondo->nombre.' '.$fondo->typeMoney->simbolo.' '.$fondo->saldo.' es insuficiente para completar la operación';
        if ( $detalle->idmoneda == $fondo->idtipomoneda )
        {
            if ( $monto > $fondo->saldo )
                return $this->warningException( __FUNCTION__ , $msg );
            else
                return $this->setRpta($monto);
        }
        elseif ( $detalle->idmoneda != $fondo->idtipomoneda ) 
        {
            if ( $detalle->idmoneda == DOLARES )
                if ( ( $monto*$tc->compra ) > $fondo->saldo )
                    return $this->warningException( __FUNCTION__ , $msg );
                else
                    return $this->setRpta($monto*$tc->compra);
            elseif ( $detalle->idmoneda == SOLES)
                if ( ( $monto/$tc->venta ) > $fondo->saldo )
                    return $this->warningException( __FUNCTION__ , $msg );
                else
                    return $this->setRpta( $monto/$tc->venta);
            else
                return $this->warningException( __FUNCTION__ , 'Tipo de Moneda no registrada: '.$detalle->typeMoney->descripcion );
        }
        return $this->warningException( __FUNCTION__ , 'No se pudo procesar el fondo de la solicitud');
    }

    private function validateInpustDeposit( $inputs )
    {
        try
        {
            $rules = array(
                'token'       => 'required',
                'idcuenta'    => 'required|numeric|min:1',
                'op_number'   => 'required|min:3'
            );
            $validator = Validator::make($inputs, $rules);
            if ($validator->fails()) 
                return $this->warningException( __FUNCTION__ , substr($this->msgValidator($validator), 0 , -1 ) );
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
            if ( $solIdMoneda != $bankIdMoneda )
            {
                if ( $solIdMoneda == SOLES)
                    $monto = $monto / $tc->venta;
                elseif ( $solIdMoneda == DOLARES )
                    $monto = $monto * $tc->compra;
                else
                    return $this->warningException( __FUNCTION__ , 'Tipo de Moneda no Registrada con Id: '.$solIdMoneda );
                $jDetalle->tcc = $tc->compra;
                $jDetalle->tcv = $tc->venta;
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
            if ( is_null( $detalle->idretencion ) )
                return $this->verifyMoneyType( $detalle->idmoneda , $bank->idtipomoneda , $jDetalle->monto_aprobado , $tc , $jDetalle );
            else
                if ( $detalle->typeRetention->account->idtipomoneda == $detalle->idmoneda )
                    return $this->verifyMoneyType( $detalle->idmoneda , $bank->idtipomoneda , ( $jDetalle->monto_aprobado - $jDetalle->monto_retencion ) , $tc , $jDetalle );
                else
                    if ( $detalle->idmoneda == SOLES )
                        return $this->verifyMoneyType( $detalle->idmoneda , $bank->idtipomoneda , $this->amountRate( $jDetalle , $tc , 1 ) , $tc , $jDetalle );
                    elseif ( $detalle->idmoneda == DOLARES )
                        return $this->verifyMoneyType( $detalle->idmoneda , $bank->idtipomoneda , $this->amountRate( $jDetalle , $tc , 2 ) , $tc , $jDetalle );
                    else
                        return $this->warningException( __FUNCTION , 'La moneda (Id: '.$detalle->idmoneda.') no cuenta con un registro de Tipo de Cambio en el sistema' );      
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
                $solicitud   = Solicitude::where('token',$inputs['token'])->first();
                if ( count( $solicitud) == 0 )
                    return $this->warningException( __FUNCTION__ , 'Cancelado - No se encontro la solicitud (Cod:'.$inputs['token'].')');
                else
                {
                    if ( $solicitud->idestado != DEPOSITO_HABILITADO )
                        return $this->warningException( __FUNCTION__ , 'Cancelado - No se puede depositar la solicitud en esta etapa del flujo: '.$solicitud->idestado );
                    else
                    {
                        $oldIdestado  = $solicitud->idestado;
                        $detalle      = $solicitud->detalle;
                        $tc           = ChangeRate::getTc();
                        if ( !is_null( $detalle->iddeposito )  )
                            return $this->warningException( __FUNCTION__ , 'Cancelado - El deposito ya ha sido registrado');
                        else
                        {
                            $middleRpta = $this->validateBalance( $detalle , $tc );
                            if ( $middleRpta[status] == ok )
                            {
                                $fondo = $detalle->fondo;
                                $fondo->saldo = $fondo->saldo - round( $middleRpta[data] , 2 , PHP_ROUND_HALF_DOWN );
                                if ( !$fondo->save() )
                                    return $this->warningException( __FUNCTION__ , 'Cancelado - No se pudo procesar el saldo del fondo' );
                                else
                                { 
                                    $account = Account::find( $inputs['idcuenta'] );
                                    if ( count( $account ) == 0 )
                                        return $this->warningException( __FUNCTION__ , 'Cancelado - No se encontro la Cuenta del Banco con Id: '.$inputs['idcuenta'] );
                                    else
                                        if ( $account->idtipocuenta != BANCO )
                                            return $this->warningException( __FUNCTION__ , 'Cancelado - La cuenta de Id: '.$account->id.' no es de Bancos');
                                        else
                                        {
                                            $middleRpta = $this->getBankAmount( $detalle , $account , $tc );
                                            if ( $middleRpta[status] == ok )
                                            {    
                                                $newDeposit                     = new Deposit;
                                                $newDeposit->id                 = $newDeposit->lastId() + 1;
                                                $newDeposit->num_transferencia  = $inputs['op_number'];
                                                $newDeposit->idcuenta           = $inputs['idcuenta'];
                                                $newDeposit->total              = round( $middleRpta[data]['monto'] , 2 , PHP_ROUND_HALF_DOWN );
                                                if( !$newDeposit->save() )
                                                    return $this->warningException( __FUNCTION__ , 'Cancelado - No se pudo procesar el deposito' );
                                                else
                                                {
                                                    $detalle->iddeposito = $newDeposit->id;
                                                    $detalle->detalle = json_encode( $middleRpta[data]['jDetalle'] );
                                                    if ( !$detalle->save() )
                                                        return $this->warningException( __FUNCTION__ , 'Cancelado - No se pudo procesar el detalle de la solicitud');
                                                    else
                                                    {
                                                        $solicitud->idestado     = DEPOSITADO;
                                                        if ($solicitud->save() )
                                                        {
                                                            $middleRpta = $this->setStatus( $oldIdestado, DEPOSITADO, Auth::user()->id, USER_CONTABILIDAD, $solicitud->id );
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
            DB::rollback();
            return $middleRpta;
        }
        catch (Exception $e) 
        {
            DB::rollback();
            return $this->internalException($e,__FUNCTION__);
        }
    }    

    public function getFondos($mes){
        $mes = explode('-', $mes);
        $periodo = $mes[1].str_pad($mes[0], 2, '0', STR_PAD_LEFT);
        $fondos = FondoInstitucional::where('terminado', TERMINADO)->where('periodo', $periodo)->get();
        $estado = 1;
        foreach ($fondos as $fondo) {
            if($fondo->depositado == PDTE_DEPOSITO)
            {
                $estado = PDTE_DEPOSITO;
            }
        }
        $view = View::make('Treasury.list_fondos')->with('fondos',$fondos)->with('estado', $estado);
        return $view;

    }

}