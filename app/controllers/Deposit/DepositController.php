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
    
    // IDKC: CHANGE STATUS => DEPOSITADO
    public function depositSolicitudeTes()
    {
        try
        {
            DB::beginTransaction();
            $deposit = Input::all();
            $estado = 0;
            $titulo = '';
        
            $solicitude   = Solicitude::where('token',$deposit['token'])->firstOrFail();
            $row_deposit  = Deposit::find($solicitude->iddeposito);
            $bank         = Account::find($deposit['idcuenta']);
            $tc           = ChangeRate::getTc();
            $detalle      = $solicitude->detalle;
            $fondo        = $detalle->fondo; 
            $montos       = json_decode($detalle->detalle);
            $monto        = $montos->monto_aprobado;
            if ( !$detalle->idretencion == null )
            {
                $total = ($monto - $montos->monto_retencion);
                $depo = ($monto - $montos->monto_retencion);
            }
            else
            {
                $total = $monto;
                $depo = $monto;
            }

            if ( $detalle->idmoneda == $fondo->idtipomoneda )
            {
                if ( $total > $fondo->saldo )
                {
                    return array( 
                    status => warning , 
                    description => "El Saldo del Fondo: '".$fondo->nombre." ".$fondo->typeMoney->simbolo." ".$fondo->saldo. " es insuficiente para completar la operación" 
                    );
                }
            }
            elseif ( $detalle->idmoneda != $fondo->idtipomoneda ) 
            {
                if ( $detalle->idmoneda == 2 )
                {
                    $total = $total*$tc->compra ;
                    if ( $total > $fondo->saldo )
                    {
                        return array( 
                        status => warning , 
                        description => "El Saldo del Fondo: '".$fondo->nombre." ".$fondo->typeMoney->simbolo." ".$fondo->saldo. " es insuficiente para completar la operación" 
                        );
                    }
                }
                else
                {
                    $total = $total/$tc->venta;
                    if ( $total > $fondo->saldo )
                    {
                        return array( 
                        status => warning , 
                        description => "El Saldo del Fondo: '".$fondo->nombre." ".$fondo->typeMoney->simbolo." ".$fondo->saldo. " es insuficiente para completar la operación"
                        ); 
                    }
                }
            }
            
            /*elseif ($deposit['type_deposit'] == FONDO )
            {
                $fondo       = FondoInstitucional::where('token',$deposit['token'])->firstOrFail();
                $row_deposit = Deposit::where('iddeposito',$fondo->iddeposito)->get();
                $estado      = $fondo->estado;
                $titulo      = $fondo->institucion;
                $idSol       = $fondo->idfondo;
                $monto       = $fondo->monto;
                $total       = $monto;
                $idfondo     = $solicitude->idcuenta;
            }
            */

            /*else
            {
                DB::rollback();
                return array(status => warning , description => 'No se encontro los registros de la solicitud');
            }*/
            if(count($row_deposit)>0)
            {
                DB::rollback();
                return array(status => warning , description => 'El deposito ya ha sido registrado');
            }
            else
            {
                if ($bank->idtipomoneda == 1 && $detalle->idmoneda == 2 )
                    $depo = $depo*$tc->compra;
                elseif ($bank->idtipomoneda == 2 && $detalle->idmoneda == 1 )
                    $depo = $depo/$tc->venta;
                $newDeposit                     = new Deposit;
                $newDeposit->iddeposito         = $newDeposit->lastId()+1;
                $newDeposit->num_transferencia  = $deposit['op_number'];
                $newDeposit->idcuenta           = $deposit['idcuenta'];
                $newDeposit->total              = $depo;
                if($newDeposit->save())
                {   
                    $solicitude->idestado     = DEPOSITADO;
                    if ($solicitude->save())
                    {
                        $detalle->iddeposito = $newDeposit->iddeposito;
                        if ($detalle->save())
                        {
                            $fondo->saldo = $fondo->saldo - $total;
                            if ( $fondo->save() )
                            {
                                $rpta = $this->setStatus( DEPOSITO_HABILITADO, DEPOSITADO, Auth::user()->id, USER_CONTABILIDAD, $solicitude->id );
                                if ( $rpta[status] == ok )
                                {
                                    DB::commit();
                                    return $this->setRpta();
                                }
                                else
                                    DB::rollback();
                            }
                        }
                    }
                }      
            }
            $rpta = array(status => warning , description => 'No se pudo procesar el deposito');
        }
        catch (Exception $e) 
        {
            DB::rollback();
            $rpta = $this->internalException($e,__FUNCTION__);
        }
        DB::rollback();
        return $rpta;
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