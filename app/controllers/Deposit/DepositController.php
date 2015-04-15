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

    // IDKC: CHANGE STATUS => DEPOSITADO
    public function depositSolicitudeTes()
    {
        try
        {
            DB::beginTransaction();
            $deposit      = Input::all();
            $solicitude   = Solicitude::where('token',$deposit['token'])->firstOrFail();
            $oldIdestado  = $solicitude->idestado;
            $detalle      = $solicitude->detalle;
            $tc           = ChangeRate::getTc();
            
            $middleRpta = $this->validateBalance( $detalle , $tc );
            if ( $middleRpta[status] == ok )
            {
                $fondo = $detalle->fondo;
                $fondo->saldo = $fondo->saldo - $middleRpta[data];
                if ( !$fondo->save() )
                    return $this->warningException( __FUNCTION__ , 'Cancelado - No se pudo procesar el saldo del fondo' );
                else
                { 
                    $row_deposit  = Deposit::find($solicitude->iddeposito);      
                    if( count($row_deposit) > 0 )
                        return $this->warningException( __FUNCTION__ , 'El deposito ya ha sido registrado');
                    else
                    {
                        $bank = Account::find($deposit['idcuenta']);
                        $jDetalle = json_decode( $detalle->detalle );
                        if ( is_null( $detalle->idretencion ) )
                            $monto_deposito = $jDetalle->monto_aprobado;
                        else
                            if ( $detalle->typeRetention->account->idtipomoneda == $detalle->idmoneda )
                                $monto_deposito = ( $jDetalle->monto_aprobado - $jDetalle->monto_retencion );
                            else
                                if ( $detalle->idmoneda == SOLES )
                                    $monto_deposito = ( $jDetalle->monto_aprobado / $tc->venta ) - $jDetalle->monto_retencion ;
                                elseif ( $detalle->idmoneda == DOLARES )
                                    $monto_deposito = ( $jDetalle->monto_aprobado * $tc->compra ) - $jDetalle->monto_retencion ;
                        if ( $detalle->idmoneda != $bank->idtipomoneda )
                        {
                            if ( $detalle->idmoneda == SOLES)
                                $monto_deposito = $monto_deposito / $tc->venta;
                            elseif ( $detalle->idmoneda == DOLARES )
                                $monto_deposito = $monto_deposito * $tc->compra;
                            $jDetalle->tcc = $tc->compra;
                            $jDetalle->tcv = $tc->venta;
                        }

                        $newDeposit                     = new Deposit;
                        $newDeposit->id                 = $newDeposit->lastId() + 1;
                        $newDeposit->num_transferencia  = $deposit['op_number'];
                        $newDeposit->idcuenta           = $deposit['idcuenta'];
                        $newDeposit->total              = $monto_deposito;
                        if( !$newDeposit->save() )
                            return $this->warningException( __FUNCTION__ , 'Cancelado - No se pudo procesar el deposito' );
                        else
                        {
                            $solicitude->idestado     = DEPOSITADO;
                            if ($solicitude->save())
                            {
                                $detalle->iddeposito = $newDeposit->id;
                                $detalle->detalle = json_encode( $jDetalle );
                                if ( !$detalle->save() )
                                    return $this->warningException( __FUNCTION__ , 'Cancelado - No se pudo procesar el detalle de la solicitud');
                                else
                                {
                                    $rpta = $this->setStatus( $oldIdestado, DEPOSITADO, Auth::user()->id, USER_CONTABILIDAD, $solicitude->id );
                                    if ( $rpta[status] == ok )
                                        DB::commit();
                                    else
                                        DB::rollback();
                                    return $this->setRpta();
                                }
                            }
                        }
                    }      
                }
            }
        }
        catch (Exception $e) 
        {
            $middleRpta = $this->internalException($e,__FUNCTION__);
        }
        DB::rollback();
        return $middleRpta;
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