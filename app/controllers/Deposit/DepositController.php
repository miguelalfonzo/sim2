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
            if ($deposit['type_deposit'] == SOLIC )
            {
                $solicitude   = Solicitude::where('token',$deposit['token'])->firstOrFail();
                $row_deposit  = Deposit::where('iddeposito',$solicitude->iddeposito)->get();
                $estado       = $solicitude->estado;       
                $titulo       = $solicitude->titulo.' '.$solicitude->descripcion;
                $idSol        = $solicitude->idsolicitud;
                $monto        = $solicitude->monto;
                $idfondo      = $solicitude->idfondo;
                if (!$solicitude->retencion == null)
                    $total = ($monto - $solicitude->retencion);
                else
                    $total = $monto;
            }
            elseif ($deposit['type_deposit'] == FONDO )
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
            else
            {
                DB::rollback();
                return array(status => warning , description => 'No se encontro los registros de la solicitud');
            }
            if(count($row_deposit)>0)
            {
                DB::rollback();
                return array(status => warning , description => 'El deposito ya ha sido registrado');
            }
            else
            {
                $newDeposit                     = new Deposit;
                $id                             = $newDeposit->lastId()+1;
                $newDeposit->iddeposito         = $id;
                $newDeposit->num_transferencia  = $deposit['op_number'];
                $newDeposit->total              = $total
                if($newDeposit->save())
                {
                    if ($deposit['type_deposit'] == SOLIC )
                    {
                        $solicitudeUpd             = Solicitude::where('token',$deposit['token']);
                        $solicitudeUpd->estado     = DEPOSITADO;
                        $solicitudeUpd->iddeposito = $id;
                        $solicitudeUpd->update($this->objectToArray($solicitudeUpd));
                    }
                    else if ($deposit['type_deposit'] == FONDO )
                    {
                        $fondoInst                = FondoInstitucional::where('token',$deposit['token']);
                        $fondoInst->estado        = DEPOSITADO;
                        $fondoInst->iddeposito    = $id;
                        $fondoInst->update($this->objectToArray($fondoInst));
                    }
                    $fondoUpd          = Fondo::where('idfondo', $idfondo);
                    $fondoUpd->saldo   = $fondoUpd->saldo - $monto; 
                    if ($fondoUpd->saldo < 0)
                    {
                        DB::rollback();
                        return array( status => warning , description => 'Saldo Insuficiente en el Fondo: '.$fondoUpd->nombre_mkt);
                    }
                    else
                        $fondoUpd->update($this->objectToArray($fondoUpd));        
                    $rpta = $this->setStatus($titulo , $estado, DEPOSITADO, Auth::user()->id, USER_CONTABILIDAD, $idSol, $deposit['type_deposit']);
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
        catch (Exception $e) 
        {
            DB::rollback();
            $rpta = $this->internalException($e,__FUNCTION__);
        }
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