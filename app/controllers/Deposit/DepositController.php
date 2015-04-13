<?php

namespace Deposit;

use \BaseController;
use \Dmkt\FondoInstitucional;
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
        if ( $detalle->idmoneda == $fondo->idtipomoneda )
        {
            if ( $monto > $fondo->saldo )
                return array( status => warning , description => "El Saldo del Fondo: '".$fondo->nombre." ".$fondo->typeMoney->simbolo." ".$fondo->saldo. " es insuficiente para completar la operación" );
            else
                return $this->setRpta($monto);
        }
        elseif ( $detalle->idmoneda != $fondo->idtipomoneda ) 
        {
            if ( $detalle->idmoneda == DOLARES )
                if ( ( $monto*$tc->compra ) > $fondo->saldo )
                    return array( status => warning , description => "El Saldo del Fondo: '".$fondo->nombre." ".$fondo->typeMoney->simbolo." ".$fondo->saldo. " es insuficiente para completar la operación" );
                else
                    return $this->setRpta($monto*$tc->compra);
            elseif ( $detalle->idmoneda == SOLES)
                if ( ( $monto/$tc->venta ) > $fondo->saldo )
                    return array( status => warning , description => "El Saldo del Fondo: '".$fondo->nombre." ".$fondo->typeMoney->simbolo." ".$fondo->saldo. " es insuficiente para completar la operación" ); 
                else
                    return $this->setRpta( $monto/$tc->venta);
            else
                return array( status => warning , description => "Tipo de Moneda no registrada: ".$detalle->typeMoney->descripcion );
        }
        return array( status => warning , description => "No se pudo procesar el fondo de la solicitud");
    }

    // IDKC: CHANGE STATUS => DEPOSITADO
    public function depositSolicitudeTes()
    {
        try
        {
            DB::beginTransaction();
            
            $deposit      = Input::all();
            Log::error($deposit);
            $solicitude   = Solicitude::where('token',$deposit['token'])->firstOrFail();
            $detalle      = $solicitude->detalle;
            $tc           = ChangeRate::getTc();
            
            $middleRpta = $this->validateBalance( $detalle , $tc );
            if ( $middleRpta[status] == ok )
            {
                $fondo = $detalle->fondo;
                $fondo->saldo = $fondo->saldo - $middleRpta[data];
                $fondo->save(); 
                $row_deposit  = Deposit::find($solicitude->iddeposito);      
                if( count($row_deposit) > 0 )
                    return array(status => warning , description => 'El deposito ya ha sido registrado');
                else
                {
                    $bank = Account::find($deposit['idcuenta']);
                    $jDetalle = json_decode( $detalle->detalle );
                    if ( is_null( $detalle->idretencion ) )
                        $monto_deposito = $jDetalle->monto_aprobado;
                    else
                        $monto_deposito = ( $jDetalle->monto_aprobado - $jDetalle->monto_retencion );

                    if ( $detalle->idmoneda != $bank->idtipomoneda )
                    {
                        if ( $detalle->idmoneda == SOLES)
                            $monto_deposito = $monto_deposito/$tc->venta;
                        elseif ( $detalle->idmoneda == DOLARES )
                            $monto_deposito = $monto_deposito*$tc->compra;
                        $jDetalle->tcc = $tc->compra;
                        $jDetalle->tcv = $tc->venta;
                    }

                    $newDeposit                     = new Deposit;
                    $newDeposit->iddeposito         = $newDeposit->lastId() + 1;
                    $newDeposit->num_transferencia  = $deposit['op_number'];
                    $newDeposit->idcuenta           = $deposit['idcuenta'];
                    $newDeposit->total              = $monto_deposito;
                    if($newDeposit->save())
                    {   
                        $solicitude->idestado     = DEPOSITADO;
                        if ($solicitude->save())
                        {
                            $detalle->iddeposito = $newDeposit->iddeposito;
                            $detalle->detalle = json_encode( $jDetalle );
                            if ($detalle->save())
                            {
                                $rpta = $this->setStatus( DEPOSITO_HABILITADO, DEPOSITADO, Auth::user()->id, USER_CONTABILIDAD, $solicitude->id );
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

    /*public function show_tes()
    {
        $state = Session::get('state');
        $states = StateRange::order();
        $data = array(
            'states' => $states,
            'state' => $state
        );
        return View::make('Treasury.show_tes',$data);
    }*/    
    /*public function listSolicitudeTes($id)
    {
        if ($id == 0) {
            $solicituds = Solicitude::all();
        } else {

            $solicituds = Solicitude::whereNotNull('idresponse')->where('estado', '=', APROBADO)->where('asiento','=',ENABLE_DEPOSIT)->get();
        }
        $view = View::make('Treasury.view_solicituds_tes')->with('solicituds', $solicituds);
        return $view;
    }*/
}